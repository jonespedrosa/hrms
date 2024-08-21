<?php

session_start(); // Start the session if not already started

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Assuming $selectedEmployees is an array of objects received from your AJAX request
$selectedEmployees = $_POST['selectedEmployees']; // Adjust this based on how data is sent
$schedule_id = mysqli_real_escape_string($HRconnect, $_POST['schedule_id']);
$day = mysqli_real_escape_string($HRconnect, $_POST['day']); // Assuming day is passed from AJAX
$course_id = mysqli_real_escape_string($HRconnect, $_POST['course_id']); // Assuming course_id is passed from AJAX

// Update lnd_enrolled_employees table
$query_enrolled = "SELECT enrolled_emp_data FROM lnd_enrolled_employees WHERE training_sched_id = '$schedule_id'";
$result_enrolled = mysqli_query($HRconnect, $query_enrolled);

if (!$result_enrolled) {
    echo json_encode(array('error' => 'Failed to fetch enrolled employees data'));
    mysqli_close($HRconnect);
    exit();
}

// Extract existing enrolled_emp_data from the result
$row_enrolled = mysqli_fetch_assoc($result_enrolled);
$existingData_enrolled = json_decode($row_enrolled['enrolled_emp_data'], true); // Decode JSON to associative array

// Append new selected employees to the existing data for lnd_enrolled_employees
foreach ($selectedEmployees as $employee) {
    $empno = $employee['empno'];
    $userid = $employee['userid'];
    
    // Check if empno already exists to avoid duplicates (optional)
    if (!isset($existingData_enrolled[$empno])) {
        $existingData_enrolled[$empno] = array(
            'empno' => $empno,
            'isAbsent' => '',
            'name' => '',
            'department' => '',
            'userid' => $userid,
            'late' => '',
            'reason' => '',
            'attachment' => ''
        );
    }
}

// Convert the updated data back to JSON format for lnd_enrolled_employees
$updatedData_enrolled = json_encode($existingData_enrolled);

// Update query for lnd_enrolled_employees
$updateQuery_enrolled = "UPDATE lnd_enrolled_employees SET enrolled_emp_data = '$updatedData_enrolled' WHERE training_sched_id = '$schedule_id'";

if (!mysqli_query($HRconnect, $updateQuery_enrolled)) {
    echo json_encode(array('error' => 'Error updating enrolled employees'));
    mysqli_close($HRconnect);
    exit();
}

// Update lnd_training_batch table
$query_training = "SELECT trainees_empno FROM lnd_training_batch WHERE id = '$schedule_id' AND day = '$day'";
$result_training = mysqli_query($HRconnect, $query_training);

if (!$result_training) {
    echo json_encode(array('error' => 'Failed to fetch trainees_empno data'));
    mysqli_close($HRconnect);
    exit();
}

// Extract existing trainees_empno from the result for lnd_training_batch
$row_training = mysqli_fetch_assoc($result_training);
$existingData_training = json_decode($row_training['trainees_empno'], true); // Decode JSON to associative array

// Merge new selected employees with the existing data for lnd_training_batch (avoid duplicates)
foreach ($selectedEmployees as $employee) {
    $empno = $employee['empno'];
    
    if (!in_array($empno, $existingData_training)) {
        $existingData_training[] = $empno;
    }
}

// Convert the updated data back to JSON format for lnd_training_batch
$updatedData_training = json_encode($existingData_training);

// Update query for lnd_training_batch
$updateQuery_training = "UPDATE lnd_training_batch SET trainees_empno = '$updatedData_training' WHERE id = '$schedule_id' AND day = '$day'";

if (!mysqli_query($HRconnect, $updateQuery_training)) {
    echo json_encode(array('error' => 'Error updating trainees'));
    mysqli_close($HRconnect);
    exit();
}

// Now you can use $_SESSION['empno'] in your insert query
$session = $_SESSION['empno'];

// Insert into lnd_enrolled_dept table
foreach ($selectedEmployees as $employee) {
    $userid = $employee['userid']; // Assuming userid is present in selectedEmployees array
    // $course_id is already retrieved from the POST data above

    $insertQuery_enrolled_dept = "INSERT INTO lnd_enrolled_dept (userid, course_id, created_by_id) VALUES ('$userid', '$course_id', '$session')";

    if (!mysqli_query($HRconnect, $insertQuery_enrolled_dept)) {
        echo json_encode(array('error' => 'Error inserting enrolled department data'));
        mysqli_close($HRconnect);
        exit();
    }
}

// Success message
echo json_encode(array('success' => 'Enrolled employees, trainees, and enrolled departments updated successfully'));

// Close the connection
mysqli_close($HRconnect);
