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
$vl = $_POST['vl'];
$vlhours = $_POST['vlhours'];
$vltype = $_POST['vltype'];
$dtrconcerns = $_POST['dtrconcerns']; // Retrieve the concern type
$approverRemarks = $_POST['approverRemarks'];
$action = $_POST['action']; // Retrieve the action type

// Ensure the POST data is properly sanitized
$empno = mysqli_real_escape_string($HRconnect, $empno);
$ConcernDate = mysqli_real_escape_string($HRconnect, $ConcernDate);
$newIN = mysqli_real_escape_string($HRconnect, $newIN);
$newbOUT = mysqli_real_escape_string($HRconnect, $newbOUT);
$newbIN = mysqli_real_escape_string($HRconnect, $newbIN);
$newOUT = mysqli_real_escape_string($HRconnect, $newOUT);
$vl = mysqli_real_escape_string($HRconnect, $vl);
$vlhours = mysqli_real_escape_string($HRconnect, $vlhours);
$vltype = mysqli_real_escape_string($HRconnect, $vltype);
$dtrconcerns = mysqli_real_escape_string($HRconnect, $dtrconcerns); // Sanitize the concern type
$approverRemarks = mysqli_real_escape_string($HRconnect, $approverRemarks); // Sanitize the concern type
$action = mysqli_real_escape_string($HRconnect, $action); // Sanitize the action type

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

// Check the action and perform the corresponding update
if ($action === 'approve') {
    if ($dtrconcerns === "Failure/Forgot to click half day" || $dtrconcerns === "Wrong filing of OBP" || $dtrconcerns === "Not following break out and break in interval" || $dtrconcerns === "Time inputs did not sync" || $dtrconcerns === "Misaligned time inputs" || $dtrconcerns === "Persona error" || $dtrconcerns === "Hardware malfunction" || $dtrconcerns === "Emergency time out" || $dtrconcerns === "Fingerprint problem") {
        // Update query for sched_time
        $updateSchedTimeSql = "UPDATE sched_time
            SET M_timein = '$M_timein', M_timeout = '$M_timeout', A_timein = '$A_timein', A_timeout = '$A_timeout'
            WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Failure/Forgot to time in or time out") {
        // Update query for sched_time but only M_timein and A_timeout
        $updateSchedTimeSql = "UPDATE sched_time
        SET M_timein = '$M_timein', A_timeout = '$A_timeout'
        WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
        SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
        WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Failure/Forgot to break in or break out") {
        // Update query for sched_time but only M_timeout and A_timein
        $updateSchedTimeSql = "UPDATE sched_time
        SET M_timeout = '$M_timeout', A_timein = '$A_timein'
        WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Failure/Forgot to click broken schedule") {
        // Update query for sched_time
        $updateSchedTimeSql = "UPDATE sched_time
        SET timein4 = '$M_timein', timeout4 = '$A_timeout' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Wrong filing of overtime") {
        // Update query for overunder
        $updateSchedTimeSql = "UPDATE overunder
        SET othours = '' WHERE empno = '$empno' AND otdatefrom = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
        SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
        WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Wrong filing of leave") {
        // Update query to add the value of $vlhours to the existing vl value in user_info
        $updateSchedTimeSql = "UPDATE user_info
            SET vl = vl + '$vlhours' WHERE empno = '$empno'";
        // Update query for vlform
        $updateVlFormSql = "UPDATE vlform
        SET vlstatus = 'canceled'
        WHERE empno = '$empno' AND vldatefrom = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Remove time inputs") {
        // Check the value of vltype and set the corresponding update query
        if ($vltype === "Time In") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET M_timein = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "Break Out") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET M_timeout = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "Break In") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET A_timein = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "Time Out") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET A_timeout = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "Broken Sched In") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET timein4 = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "Broken Sched Out") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET timeout4 = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "All Regular Inputs") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET M_timein = '', M_timeout = '', A_timein = '', A_timeout = ''
            WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        } else if ($vltype === "All Broken Sched Inputs") {
            $updateSchedTimeSql = "UPDATE sched_time
            SET timein4 = '', timeout4 = '' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        }
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
                    SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
                    WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Broken Schedule did not sync") {
        // Update query for sched_time
        $updateSchedTimeSql = "UPDATE sched_time
            SET timein4 = '$M_timein', timeout4 = '$A_timeout' WHERE empno = '$empno' AND datefromto = '$ConcernDate'";
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    } else if ($dtrconcerns === "Wrong computation") {
        // Update query for dtr_concern
        $updateDtrConcernSql = "UPDATE dtr_concerns
            SET status = 'Approved', date_approved = '$dateApproved', remarks = '$approverRemarks'
            WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";
    }

    // Execute the update queries
    mysqli_query($HRconnect, $updateSchedTimeSql);
    mysqli_query($HRconnect, $updateVlFormSql); // Execute vlform update
    mysqli_query($HRconnect, $updateDtrConcernSql);
} else if ($action === 'disapprove') {
    // Update query for dtr_concern
    $updateDtrConcernSql = "UPDATE dtr_concerns
        SET status = 'Disapproved', date_approved = '$dateApproved', remarks = '$approverRemarks'
        WHERE empno = '$empno' AND ConcernDate = '$ConcernDate' AND concern = '$dtrconcerns'";

    // Execute the update query
    mysqli_query($HRconnect, $updateDtrConcernSql);
}

// Return a success message
echo json_encode(array('status' => 'success'));
