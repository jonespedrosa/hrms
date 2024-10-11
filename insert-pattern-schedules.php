<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve data from the AJAX request
$userid = mysqli_real_escape_string($HRconnect, $_POST['userid']);  // Retrieve actual user ID
$scheduleName = mysqli_real_escape_string($HRconnect, $_POST['scheduleName']);
$scheduleType = mysqli_real_escape_string($HRconnect, $_POST['scheduleType']);
$noBreak = (int)$_POST['noBreak'];
$timeSchedule = mysqli_real_escape_string($HRconnect, $_POST['timeSchedule']);

// Insert data into the pattern_schedule table
$sql = "INSERT INTO pattern_schedule (userid, sched_name_pattern, sched_type, no_break, time_schedule)
        VALUES ('$userid', '$scheduleName', '$scheduleType', '$noBreak', '$timeSchedule')";

if (mysqli_query($HRconnect, $sql)) {
    echo "Record inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($HRconnect);
}

mysqli_close($HRconnect);
