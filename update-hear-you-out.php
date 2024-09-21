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

// Fetch the existing 'responses' JSON from the database for the given 'id' and 'empno'
$query = "SELECT responses FROM hear_you_out WHERE id = ? AND empno = ?";
$stmt = mysqli_prepare($HRconnect, $query);
mysqli_stmt_bind_param($stmt, 'ii', $id, $empno);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    // Get the current 'responses' data
    $row = mysqli_fetch_assoc($result);
    $responses = json_decode($row['responses'], true); // Decode JSON to an array

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

        // Update the database with the modified JSON
        $updateQuery = "UPDATE hear_you_out SET responses = ? WHERE id = ? AND empno = ?";
        $updateStmt = mysqli_prepare($HRconnect, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'sii', $updatedResponses, $id, $empno);

        if (mysqli_stmt_execute($updateStmt)) {
            // Successfully updated
            echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
        } else {
            // Update failed
            echo json_encode(['success' => false, 'message' => 'Failed to update data']);
        }

        mysqli_stmt_close($updateStmt);
    } else {
        // Invalid JSON format
        echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    }
} else {
    // No data found for the given 'id' and 'empno'
    echo json_encode(['success' => false, 'message' => 'No data found for the given ID and Employee Number']);
}

mysqli_stmt_close($stmt);
mysqli_close($HRconnect);







// // Handle file upload
// $attachmentFileName = '';
// if (isset($_FILES['attachmentImagesEdit']) && $_FILES['attachmentImagesEdit']['error'] == 0) {
//     $targetDir = "hyo_attachments/";
//     $fileExtension = pathinfo($_FILES["attachmentImagesEdit"]["name"], PATHINFO_EXTENSION);
//     $uniqueFileName = md5(time() . $_FILES["attachmentImagesEdit"]["name"]) . '.' . $fileExtension; // Generate unique file name
//     $targetFile = $targetDir . $uniqueFileName;

//     // Check if directory is writable
//     if (!is_writable($targetDir)) {
//         echo json_encode(array('error' => 'Directory is not writable'));
//         exit();
//     }

//     // Attempt to move the uploaded file
//     if (!move_uploaded_file($_FILES["attachmentImagesEdit"]["tmp_name"], $targetFile)) {
//         echo json_encode(array('error' => 'Failed to upload file'));
//         exit();
//     }

//     $attachmentFileName = $uniqueFileName; // Update the filename to the unique name
// } elseif (isset($_FILES['attachmentImagesEdit']) && $_FILES['attachmentImagesEdit']['error'] != 0) {
//     // Check for upload errors
//     echo json_encode(array('error' => 'File upload error: ' . $_FILES['attachmentImagesEdit']['error']));
//     exit();
// }








// header('Content-Type: application/json');

// // Connect to the database
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     echo json_encode(['success' => false, 'message' => 'Database connection failed']);
//     exit;
// }

// // Get 'id' and 'empno' from the query parameters (from URL)
// $id = isset($_GET['id']) ? intval($_GET['id']) : null;
// $empno = isset($_GET['empno']) ? intval($_GET['empno']) : null;

// // Check if 'id' and 'empno' are valid
// if (!$id || !$empno) {
//     echo json_encode(['success' => false, 'message' => 'Invalid ID or Employee Number']);
//     exit;
// }

// // Get the data sent via POST (the updated fields)
// $typeEmployment = $_POST['typeEmployment'];
// $placeIncident = $_POST['placeIncident'];
// $nameSuperior = $_POST['nameSuperior'];
// $employeeExplanation = $_POST['employeeExplanation'];
// $stateYourGoal = $_POST['stateYourGoal'];
// $stateRealities = $_POST['stateRealities'];
// $stateOptions = $_POST['stateOptions'];
// $wayForward = $_POST['wayForward'];

// // Fetch the existing 'responses' JSON from the database for the given 'id' and 'empno'
// $query = "SELECT responses FROM hear_you_out WHERE id = ? AND empno = ?";
// $stmt = mysqli_prepare($HRconnect, $query);
// mysqli_stmt_bind_param($stmt, 'ii', $id, $empno);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);

// if ($result && mysqli_num_rows($result) > 0) {
//     // Get the current 'responses' data
//     $row = mysqli_fetch_assoc($result);
//     $responses = json_decode($row['responses'], true); // Decode JSON to an array

//     if ($responses) {
//         // Update the specific fields in the 'employee_details' part of the JSON
//         $responses['employee_details']['type_of_employment'] = $typeEmployment;
//         $responses['employee_details']['place_of_incident'] = $placeIncident;
//         $responses['employee_details']['name_superior'] = $nameSuperior;
//         $responses['employee_details']['employee_explanation'] = $employeeExplanation;
//         $responses['employee_details']['state_your_goal'] = $stateYourGoal;
//         $responses['employee_details']['state_your_realities'] = $stateRealities;
//         $responses['employee_details']['state_your_option'] = $stateOptions;
//         $responses['employee_details']['way_forward'] = $wayForward;

//         // Re-encode the modified data back to JSON
//         $updatedResponses = json_encode($responses);

//         // Update the database with the modified JSON
//         $updateQuery = "UPDATE hear_you_out SET responses = ? WHERE id = ? AND empno = ?";
//         $updateStmt = mysqli_prepare($HRconnect, $updateQuery);
//         mysqli_stmt_bind_param($updateStmt, 'sii', $updatedResponses, $id, $empno);

//         if (mysqli_stmt_execute($updateStmt)) {
//             // Successfully updated
//             echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
//         } else {
//             // Update failed
//             echo json_encode(['success' => false, 'message' => 'Failed to update data']);
//         }

//         mysqli_stmt_close($updateStmt);
//     } else {
//         // Invalid JSON format
//         echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
//     }
// } else {
//     // No data found for the given 'id' and 'empno'
//     echo json_encode(['success' => false, 'message' => 'No data found for the given ID and Employee Number']);
// }

// mysqli_stmt_close($stmt);
// mysqli_close($HRconnect);

