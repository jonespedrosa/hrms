<?php

header('Content-Type: application/json');

// Connect to the database
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get 'id' and 'empno' from the query parameters (from URL)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$empno = isset($_GET['empno']) ? intval($_GET['empno']) : null;

// Check if 'id' and 'empno' are valid
if (!$id || !$empno) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID or Employee Number']);
    exit;
}

// Get the data sent via POST (the updated fields)
$typeEmployment = $_POST['typeEmployment'];
$placeIncident = $_POST['placeIncident'];
$nameSuperior = $_POST['nameSuperior'];
$employeeExplanation = $_POST['employeeExplanation'];
$stateYourGoal = $_POST['stateYourGoal'];
$stateRealities = $_POST['stateRealities'];
$stateOptions = $_POST['stateOptions'];
$wayForward = $_POST['wayForward'];

// Check if a file was uploaded
$attachmentFileName = null;

if (isset($_FILES['attachmentImagesEdit']) && $_FILES['attachmentImagesEdit']['error'] === UPLOAD_ERR_OK) {
    // Generate a unique filename
    $tempFile = $_FILES['attachmentImagesEdit']['tmp_name'];
    $originalFileName = $_FILES['attachmentImagesEdit']['name'];
    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $attachmentFileName = md5(uniqid(rand(), true)) . '.' . $extension;

    // Define the target directory
    $targetDirectory = 'hyo_attachments/';
    $targetFilePath = $targetDirectory . $attachmentFileName;

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($tempFile, $targetFilePath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload attachment']);
        exit;
    }
}

// Fetch the existing 'responses' JSON from the database for the given 'id' and 'empno'
$query = "SELECT responses, attachment FROM hear_you_out WHERE id = ? AND empno = ?";
$stmt = mysqli_prepare($HRconnect, $query);
mysqli_stmt_bind_param($stmt, 'ii', $id, $empno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    // Get the current 'responses' and 'attachment' data
    $row = mysqli_fetch_assoc($result);
    $responses = json_decode($row['responses'], true); // Decode JSON to an array
    $currentAttachment = $row['attachment'];

    if ($responses) {
        // Update the specific fields in the 'employee_details' part of the JSON
        $responses['employee_details']['type_of_employment'] = $typeEmployment;
        $responses['employee_details']['place_of_incident'] = $placeIncident;
        $responses['employee_details']['name_superior'] = $nameSuperior;
        $responses['employee_details']['employee_explanation'] = $employeeExplanation;
        $responses['employee_details']['state_your_goal'] = $stateYourGoal;
        $responses['employee_details']['state_your_realities'] = $stateRealities;
        $responses['employee_details']['state_your_option'] = $stateOptions;
        $responses['employee_details']['way_forward'] = $wayForward;

        // Re-encode the modified data back to JSON
        $updatedResponses = json_encode($responses);

        // If a new file was uploaded, update the attachment column
        if ($attachmentFileName) {
            // Optionally, you can delete the old file from the server if needed
            if ($currentAttachment && file_exists($targetDirectory . $currentAttachment)) {
                unlink($targetDirectory . $currentAttachment); // Remove old file
            }
        } else {
            $attachmentFileName = $currentAttachment; // Keep the old filename if no new file uploaded
        }

        // Set the time zone to the Philippines time zone
        date_default_timezone_set('Asia/Manila');
        $updated_at = date('Y-m-d H:i:s');

        // Update the database with the modified JSON, attachment filename, and updated_at timestamp
        $updateQuery = "UPDATE hear_you_out SET responses = ?, attachment = ?, updated_at = ? WHERE id = ? AND empno = ?";
        $updateStmt = mysqli_prepare($HRconnect, $updateQuery);

        // Bind the updated values, including the updated_at timestamp
        mysqli_stmt_bind_param($updateStmt, 'sssii', $updatedResponses, $attachmentFileName, $updated_at, $id, $empno);

        if (mysqli_stmt_execute($updateStmt)) {
            echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update data']);
        }

        mysqli_stmt_close($updateStmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No data found for the given ID and Employee Number']);
}

mysqli_stmt_close($stmt);
mysqli_close($HRconnect);
