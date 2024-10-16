<?php
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
$patternId = mysqli_real_escape_string($HRconnect, $_POST['patternId']); // Assuming patternId is sent
$assignedEmps = $_POST['assignedEmps']; // Expecting JSON array of empnos

// Convert assigned employees to JSON format
$assignedEmpsJson = json_encode($assignedEmps);

// Check if the pattern already exists in the database
$checkQuery = "SELECT * FROM pattern_schedule WHERE pattern_id = '$patternId'";
$result = mysqli_query($HRconnect, $checkQuery);

if (mysqli_num_rows($result) > 0) {
    // Update logic for existing pattern
    $updateQuery = "
        UPDATE pattern_schedule
        SET
            assigned_empno_schedule = '$assignedEmpsJson',
        WHERE pattern_id = '$patternId'
    ";

    if (mysqli_query($HRconnect, $updateQuery)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($HRconnect);
    }
} else {
    // Insert logic for a new pattern
    $insertQuery = "
        INSERT INTO pattern_schedule (userid, sched_name_pattern, sched_type, no_break, time_schedule, assigned_empno_schedule)
        VALUES ('$userid', '$scheduleName', '$scheduleType', '$noBreak', '$timeSchedule', '$assignedEmpsJson')
    ";

    if (mysqli_query($HRconnect, $insertQuery)) {
        echo "Record inserted successfully";
    } else {
        echo "Error inserting record: " . mysqli_error($HRconnect);
    }
}

mysqli_close($HRconnect);















// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// // Retrieve data from the AJAX request
// $userid = mysqli_real_escape_string($HRconnect, $_POST['userid']);  // Retrieve actual user ID
// $scheduleName = mysqli_real_escape_string($HRconnect, $_POST['scheduleName']);
// $scheduleType = mysqli_real_escape_string($HRconnect, $_POST['scheduleType']);
// $noBreak = (int)$_POST['noBreak'];
// $timeSchedule = mysqli_real_escape_string($HRconnect, $_POST['timeSchedule']);

// // Insert data into the pattern_schedule table
// $sql = "INSERT INTO pattern_schedule (userid, sched_name_pattern, sched_type, no_break, time_schedule)
//         VALUES ('$userid', '$scheduleName', '$scheduleType', '$noBreak', '$timeSchedule')";

// if (mysqli_query($HRconnect, $sql)) {
//     echo "Record inserted successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($HRconnect);
// }

// mysqli_close($HRconnect);
