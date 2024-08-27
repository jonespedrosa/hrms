<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Get JSON data from POST request
$responseData = $_POST['response'];

// Decode the JSON data
$data = json_decode($responseData, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array('error' => 'Invalid JSON data'));
    exit();
}

// Extract values from decoded JSON
$empno = $data['employee_details']['empno'];
$concern_date = $data['employee_details']['concern_date'];
$type_concern = $data['employee_details']['type_concern'];
$place_of_incident = $data['employee_details']['place_of_incident'];

// Handle file upload
$attachmentFileName = '';
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
    $targetDir = "hyo_attachments/";
    $fileExtension = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
    $uniqueFileName = md5(time() . $_FILES["attachment"]["name"]) . '.' . $fileExtension; // Generate unique file name
    $targetFile = $targetDir . $uniqueFileName;

    // Check if directory is writable
    if (!is_writable($targetDir)) {
        echo json_encode(array('error' => 'Directory is not writable'));
        exit();
    }

    // Attempt to move the uploaded file
    if (!move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFile)) {
        echo json_encode(array('error' => 'Failed to upload file'));
        exit();
    }

    $attachmentFileName = $uniqueFileName; // Update the filename to the unique name
} elseif (isset($_FILES['attachment']) && $_FILES['attachment']['error'] != 0) {
    // Check for upload errors
    echo json_encode(array('error' => 'File upload error: ' . $_FILES['attachment']['error']));
    exit();
}

// Prepare an SQL statement with placeholders
$sql = "INSERT INTO hear_you_out (responses, empno, date_submitted, type_concern, attachment) VALUES (?, ?, ?, ?, ?)";

if ($stmt = mysqli_prepare($HRconnect, $sql)) {
    // Bind the parameters
    mysqli_stmt_bind_param($stmt, 'sssss', $responseData, $empno, $concern_date, $type_concern, $attachmentFileName);

    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Return success response
        echo json_encode(array('success' => 'Data inserted successfully'));
    } else {
        echo json_encode(array('error' => 'Failed to insert data into database: ' . mysqli_stmt_error($stmt)));
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array('error' => 'Failed to prepare statement: ' . mysqli_error($HRconnect)));
}

mysqli_close($HRconnect);





// Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
//     exit();
// }

// // Get JSON data from POST request
// $responseData = $_POST['response'];

// // Decode the JSON data
// $data = json_decode($responseData, true);
// if (json_last_error() !== JSON_ERROR_NONE) {
//     echo json_encode(array('error' => 'Invalid JSON data'));
//     exit();
// }

// // Extract values from decoded JSON
// $empno = $data['employee_details']['empno'];
// $concern_date = $data['employee_details']['concern_date'];
// $place_of_incident = $data['employee_details']['place_of_incident'];

// // Handle file upload
// $attachmentFileName = '';
// if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
//     $targetDir = "hyo_attachment/";
//     $fileExtension = pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION);
//     $uniqueFileName = md5(time() . $_FILES["attachment"]["name"]) . '.' . $fileExtension; // Generate unique file name
//     $targetFile = $targetDir . $uniqueFileName;

//     // Check if directory is writable
//     if (!is_writable($targetDir)) {
//         echo json_encode(array('error' => 'Directory is not writable'));
//         exit();
//     }

//     // Attempt to move the uploaded file
//     if (!move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFile)) {
//         echo json_encode(array('error' => 'Failed to upload file'));
//         exit();
//     }

//     $attachmentFileName = $uniqueFileName; // Update the filename to the unique name
// } elseif (isset($_FILES['attachment']) && $_FILES['attachment']['error'] != 0) {
//     // Check for upload errors
//     echo json_encode(array('error' => 'File upload error: ' . $_FILES['attachment']['error']));
//     exit();
// }

// // Prepare an SQL statement with placeholders
// $sql = "INSERT INTO hear_you_out (responses, empno, submitted, date_submitted, attachment) VALUES (?, ?, ?, ?, ?)";

// if ($stmt = mysqli_prepare($HRconnect, $sql)) {
//     // Bind the parameters
//     $submitted = 1; // Set the submitted value to 1
//     mysqli_stmt_bind_param($stmt, 'ssiss', $responseData, $empno, $submitted, $concern_date, $attachmentFileName);

//     // Execute the prepared statement
//     if (mysqli_stmt_execute($stmt)) {
//         // Return success response
//         echo json_encode(array('success' => 'Data inserted successfully'));
//     } else {
//         echo json_encode(array('error' => 'Failed to insert data into database: ' . mysqli_stmt_error($stmt)));
//     }

//     mysqli_stmt_close($stmt);
// } else {
//     echo json_encode(array('error' => 'Failed to prepare statement: ' . mysqli_error($HRconnect)));
// }

// mysqli_close($HRconnect);


