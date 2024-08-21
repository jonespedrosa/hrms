<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if batch_number, status, and schedule_id are set
    if (isset($_POST['batch_number']) && isset($_POST['status']) && isset($_POST['schedule_id'])) {
    // Sanitize inputs
        $batch_number = mysqli_real_escape_string($HRconnect, $_POST['batch_number']);
        $status = mysqli_real_escape_string($HRconnect, $_POST['status']);
        $schedule_id = mysqli_real_escape_string($HRconnect, $_POST['schedule_id']);

        // Update status in the database for lnd_training_schedule
        $update_query_schedule = "UPDATE lnd_training_schedule SET status = '$status' WHERE id = '$schedule_id'";

        // Update status in the database for lnd_training_batch
        $update_query_batch = "UPDATE lnd_training_batch SET status = '$status' WHERE id = '$schedule_id'";

        // Execute update query for lnd_training_schedule
        $result_schedule = mysqli_query($HRconnect, $update_query_schedule);
        $result_batch = mysqli_query($HRconnect, $update_query_batch);

        if ($result_schedule && $result_batch) {
            // If update is successful, return success message
            echo json_encode(array('success' => 'Status updated successfully'));
        } else {
            // If update fails, return error message
            echo json_encode(array('error' => 'Error updating status'));
        }

        exit();
    } else {
        // If batch_number, status, or schedule_id is not set, return error message
        echo json_encode(array('error' => 'Batch number, status, or schedule_id is missing'));
        exit();
    }
} else {
    // If request method is not POST, return error message
    echo json_encode(array('error' => 'Invalid request method'));
    exit();
}








// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     // If connection fails, return an error message
//     echo json_encode(array('error' => 'Failed to connect to database'));
//     exit();
// }

// // Check if the request method is POST
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     // Check if batch_number and status are set
//     if (isset($_POST['batch_number']) && isset($_POST['status'])) {
//         // Sanitize inputs
//         $batch_number = mysqli_real_escape_string($HRconnect, $_POST['batch_number']);
//         $status = mysqli_real_escape_string($HRconnect, $_POST['status']);

//         // Update status in the database
//         $update_query = "UPDATE lnd_training_schedule SET status = '$status' WHERE batch_number = '$batch_number'";

    
//         if (mysqli_query($HRconnect, $update_query)) {
//             // If update is successful, return success message
//             echo json_encode(array('success' => 'Status updated successfully'));
//             exit();
//         } else {
//             // If update fails, return error message
//             echo json_encode(array('error' => 'Error updating status'));
//             exit();
//         }
//     } else {
//         // If batch_number or status is not set, return error message
//         echo json_encode(array('error' => 'Batch number or status is missing'));
//         exit();
//     }
// } else {
//     // If request method is not POST, return error message
//     echo json_encode(array('error' => 'Invalid request method'));
//     exit();
// }
