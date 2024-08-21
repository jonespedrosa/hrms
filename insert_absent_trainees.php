<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Get the data from the AJAX request
$empno = $_POST['empno'];
$course_id = $_POST['course_id'];
$schedule_id = $_POST['schedule_id'];
$datefrom = $_POST['datefrom'];

// Check if the combination of empno and datefrom already exists
$query_check = "SELECT * FROM lnd_absent_trainees WHERE empno = '$empno' AND datefrom = '$datefrom'";
$result_check = mysqli_query($HRconnect, $query_check);

if (mysqli_num_rows($result_check) > 0) {
    // If the combination already exists, return an error message with appropriate HTTP status code
    http_response_code(400); // Bad Request status code
    echo json_encode(array('error' => "Employee ID# $empno has already been marked as absent."));
    exit; // Ensure to exit after echoing the error message
} else {
    // Insert the data into your database table
    $query_insert = "INSERT INTO lnd_absent_trainees (empno, course_id, schedule_id, datefrom) VALUES ('$empno', '$course_id', '$schedule_id', '$datefrom')";
    if (mysqli_query($HRconnect, $query_insert)) {
        // Return success message
        echo json_encode(array('success' => 'Data inserted successfully!'));
    } else {
        // Return error message
        echo json_encode(array('error' => 'Failed to insert data: ' . mysqli_error($HRconnect)));
    }
}

// Close database connection
mysqli_close($HRconnect);
