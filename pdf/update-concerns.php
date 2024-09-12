<?php
// Database connections and session start
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();

// Redirect to login if not logged in
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Set the time zone to the Philippines time zone
date_default_timezone_set('Asia/Manila');
// Get the current date and time
$dateApproved = date('Y-m-d H:i:s');

// Retrieve POST data from the AJAX request
$empno = $_POST['empno'];
$ConcernDate = $_POST['ConcernDate'];
$newIN = $_POST['newIN'];
$newbOUT = $_POST['newbOUT'];
$newbIN = $_POST['newbIN'];
$newOUT = $_POST['newOUT'];
$dtrconcerns = $_POST['dtrconcerns']; // Retrieve the concern type
$approverRemarks = $_POST['approverRemarks'];

// Ensure the POST data is properly sanitized
$empno = mysqli_real_escape_string($HRconnect, $empno);
$ConcernDate = mysqli_real_escape_string($HRconnect, $ConcernDate);
$newIN = mysqli_real_escape_string($HRconnect, $newIN);
$newbOUT = mysqli_real_escape_string($HRconnect, $newbOUT);
$newbIN = mysqli_real_escape_string($HRconnect, $newbIN);
$newOUT = mysqli_real_escape_string($HRconnect, $newOUT);
$dtrconcerns = mysqli_real_escape_string($HRconnect, $dtrconcerns); // Sanitize the concern type

// Combine ConcernDate and Time Fields for morning (M_timein)
$M_timein = date('Y-m-d H:i', strtotime($ConcernDate . ' ' . $newIN));

// Check if both $newbOUT and $newbIN are "No Break", otherwise convert them to valid date-time formats
if ($newbOUT === 'No Break' && $newbIN === 'No Break') {
    $M_timeout = 'No Break';  // Assign "No Break" to M_timeout
    $A_timein = 'No Break';   // Assign "No Break" to A_timein
} else {
    // If not "No Break", convert to date-time format
    $M_timeout = date('Y-m-d H:i', strtotime($ConcernDate . ' ' . $newbOUT));
    $A_timein = date('Y-m-d H:i', strtotime($ConcernDate . ' ' . $newbIN));
}

// Combine ConcernDate and Time Fields for afternoon (A_timeout)
$A_timeout = date('Y-m-d H:i', strtotime($ConcernDate . ' ' . $newOUT));  // Convert to date-time

// Check the concern type
if ($dtrconcerns === "Failure/Forgot to time in or time out" || $dtrconcerns === "Failure/Forgot to break in or break out"  || $dtrconcerns === "Failure/Forgot to click half day") {
    // Update query for sched_time
    $updateSchedTimeSql = "UPDATE sched_time
        SET M_timein = '$M_timein', M_timeout = '$M_timeout', A_timein = '$A_timein', A_timeout = '$A_timeout'
        WHERE empno = '$empno' AND datefromto = '$ConcernDate'";

    // Update query for dtr_concern
    $updateDtrConcernSql = "UPDATE dtr_concerns
        SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
        WHERE empno = '$empno' AND ConcernDate = '$ConcernDate'";

    // Start a transaction
    mysqli_begin_transaction($HRconnect);

    try {
        // Execute the sched_time update query
        mysqli_query($HRconnect, $updateSchedTimeSql);

        // Execute the dtr_concern update query
        mysqli_query($HRconnect, $updateDtrConcernSql);

        // Commit the transaction
        mysqli_commit($HRconnect);

        echo json_encode(["status" => "success", "message" => "Records updated successfully"]);
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($HRconnect);
        echo json_encode(["status" => "error", "message" => "Failed to update records: " . mysqli_error($HRconnect)]);
    }
} else if ($dtrconcerns === "Failure/Forgot to click broken schedule") {

    // Update query for sched_time
    $updateSchedTimeSql = "UPDATE sched_time
        SET timein4 = '$M_timein', timeout4 = '$A_timeout'
        WHERE empno = '$empno' AND datefromto = '$ConcernDate'";

    // Update query for dtr_concern
    $updateDtrConcernSql = "UPDATE dtr_concerns
        SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
        WHERE empno = '$empno' AND ConcernDate = '$ConcernDate'";

    // Start a transaction
    mysqli_begin_transaction($HRconnect);

    try {
        // Execute the sched_time update query
        mysqli_query($HRconnect, $updateSchedTimeSql);

        // Execute the dtr_concern update query
        mysqli_query($HRconnect, $updateDtrConcernSql);

        // Commit the transaction
        mysqli_commit($HRconnect);

        echo json_encode(["status" => "success", "message" => "Records updated successfully"]);
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($HRconnect);
        echo json_encode(["status" => "error", "message" => "Failed to update records: " . mysqli_error($HRconnect)]);
    }
}
