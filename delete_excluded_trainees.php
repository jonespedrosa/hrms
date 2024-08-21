<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Check if empno is provided
if(isset($_POST['empno'])) {
    // Sanitize the empno to prevent SQL injection
    $empno = mysqli_real_escape_string($HRconnect, $_POST['empno']);

    // Your SQL DELETE query to delete entry based on empno
    $query = "DELETE FROM lnd_excluded_trainees WHERE empno = '$empno'";

    if(mysqli_query($HRconnect, $query)) {
        // If deletion successful, return success response
        echo json_encode(array('success' => true));
    } else {
        // If deletion fails, return error response
        echo json_encode(array('error' => 'Failed to delete entry: ' . mysqli_error($HRconnect)));
    }
} else {
    // If empno is not provided, return error response
    echo json_encode(array('error' => 'Empno not provided'));
}

// Close the database connection
mysqli_close($HRconnect);

