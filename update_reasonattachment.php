<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Fetch the value of EmpNoId and dateFrom from the POST data
$empNoId = $_POST['EmpNoId'];
$dateFrom = $_POST['datefrom'];

// Fetch reason from the POST data
$reason = $_POST['reason'];

// Initialize the query variable
$query = "";

// Check if a file is uploaded
if (isset($_FILES['fileInput']) && $_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
    // Get the name of the uploaded file
    $original_filename = $_FILES['fileInput']['name'];
    $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    
    // Generate a unique filename
    $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;

    // Set the destination directory for the attachment
    $destination_dir = "C:/xampp/htdocs/hrms/lnd_attachment/";

    // Move the uploaded file to the destination directory with the unique filename
    $destination_path = $destination_dir . $unique_filename;
    if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $destination_path)) {
        // Update query with the attachment file name
        $query = "UPDATE lnd_absent_trainees 
                  SET reason = '$reason', 
                      attachment = '$unique_filename' 
                  WHERE empno = $empNoId AND datefrom = '$dateFrom'";
    } else {
        echo json_encode(array('error' => 'Error moving uploaded file'));
        exit();
    }
} else {
    // Update query without the attachment file name
    $query = "UPDATE lnd_absent_trainees 
              SET reason = '$reason' 
              WHERE empno = $empNoId AND datefrom = '$dateFrom'";
}

// Execute the query
if (mysqli_query($HRconnect, $query)) {
    echo json_encode(array('success' => 'Record updated successfully'));
} else {
    echo json_encode(array('error' => 'Error updating record: ' . mysqli_error($HRconnect)));
}

// Close connection
mysqli_close($HRconnect);



   // ---------- WITH REASON AND ATTACHMENT REQUIRED! DO NOT DELETE
// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('error' => 'Failed to connect to database'));
//     exit();
// }

// // Fetch the value of EmpNoId and dateFrom from the URL parameters
// $empNoId = $_POST['EmpNoId']; // Note: use 'EmpNoId' instead of 'empno'
// $dateFrom = $_POST['datefrom']; // Note: use 'datefrom' instead of 'dateFrom'

// // Fetch reason from the POST data
// $reason = $_POST['reason'];

// // Check if a file is uploaded
// if ($_FILES['fileInput']['error'] === UPLOAD_ERR_OK) {
//     // Get the name of the uploaded file
//     $original_filename = $_FILES['fileInput']['name'];
//     $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
    
//     // Generate a unique filename
//     $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;

//     // Set the destination directory for the attachment
//     $destination_dir = "C:/xampp/htdocs/hrms/lnd_attachment/";

//     // Move the uploaded file to the destination directory with the unique filename
//     $destination_path = $destination_dir . $unique_filename;
//     if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $destination_path)) {
//         // Update query with the attachment file name
//         $query = "UPDATE lnd_absent_trainees 
//             SET reason = '$reason', 
//                 attachment = '$unique_filename' 
//             WHERE empno = $empNoId AND datefrom = '$dateFrom'";

//         // Execute the query
//         if (mysqli_query($HRconnect, $query)) {
//             echo json_encode(array('success' => 'Record updated successfully'));
//         } else {
//             echo json_encode(array('error' => 'Error updating record: ' . mysqli_error($HRconnect)));
//         }
//     } else {
//         echo json_encode(array('error' => 'Error moving uploaded file'));
//     }
// } else {
//     echo json_encode(array('error' => 'Error uploading file'));
// }

// // Close connection
// mysqli_close($HRconnect);
