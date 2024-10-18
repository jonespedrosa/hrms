<?php
session_start(); // Start the session

// Ensure that empno is available in the session
if (!isset($_SESSION['empno'])) {
    die("User not logged in.");
}

$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from the AJAX request
$userid = mysqli_real_escape_string($HRconnect, $_POST['userid']);
$scheduleName = mysqli_real_escape_string($HRconnect, $_POST['scheduleName']);
$scheduleType = mysqli_real_escape_string($HRconnect, $_POST['scheduleType']);
$noBreak = (int)$_POST['noBreak'];
$timeSchedule = mysqli_real_escape_string($HRconnect, $_POST['timeSchedule']);

// Get empno from session for created_by
$created_by = mysqli_real_escape_string($HRconnect, $_SESSION['empno']);

// Insert logic for a new pattern
$insertQuery = "
    INSERT INTO pattern_schedule (userid, sched_name_pattern, sched_type, no_break, time_schedule, created_by)
    VALUES ('$userid', '$scheduleName', '$scheduleType', '$noBreak', '$timeSchedule', '$created_by')
";

if (mysqli_query($HRconnect, $insertQuery)) {
    echo "Record inserted successfully";
} else {
    echo "Error inserting record: " . mysqli_error($HRconnect);
}

mysqli_close($HRconnect);
