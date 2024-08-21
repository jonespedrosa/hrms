<?php

// Start the session
session_start();

// Ensure you have the session value for the employee number
$created_by_id = $_SESSION['empno'];

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $empno = $_POST['empno'];
    $batch_number = $_POST['id'];
    $datefrom = $_POST['datefrom'];
    $training_sched_id = $_POST['scheduleId'];

    // Check if the record already exists
    $check_sql = "SELECT * FROM lnd_excluded_trainees WHERE empno = ? AND batch_number = ?";
    $check_stmt = mysqli_prepare($HRconnect, $check_sql);
    mysqli_stmt_bind_param($check_stmt, 'ss', $empno, $batch_number);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        // Record already exists, so don't insert again
        echo json_encode(array('error' => 'Trainee already excluded'));
    } else {
        // Prepare the SQL statement for insertion
        $insert_sql = "INSERT INTO lnd_excluded_trainees (empno, training_sched_id, batch_number, datefrom, created_by_id) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($HRconnect, $insert_sql);

        // Bind parameters for insertion
        mysqli_stmt_bind_param($insert_stmt, 'sssss', $empno, $training_sched_id, $batch_number, $datefrom, $created_by_id);

        // Execute insertion statement
        if (mysqli_stmt_execute($insert_stmt)) {
            echo json_encode(array('success' => 'Trainee excluded successfully'));
        } else {
            echo json_encode(array('error' => 'Failed to exclude trainee'));
        }

        // Close insertion statement
        mysqli_stmt_close($insert_stmt);
    }

    // Close SELECT statement
    mysqli_stmt_close($check_stmt);

    // Close connection
    mysqli_close($HRconnect);
}
