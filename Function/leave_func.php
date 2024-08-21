<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
require ("global_timestamp.php");
if (isset($_GET['sched'])) {
    if ($_GET['sched'] == 'workHours') {
        echo getWorkHours($_GET["empno"], ($_GET["datefrom"]), $HRconnect);
    } else if ($_GET['sched'] == 'leave') {
        echo getLeave($_GET["empno"], $HRconnect);
    } else if ($_GET['sched'] == 'postLeave') {
        echo postLeave($_POST["leave_details"], $_POST["emp_details"], $timestamp, $HRconnect);
    } else if ($_GET['sched'] == 'isExist') {
        echo isExist($_GET["empno"], $_GET["selected_dates"], $HRconnect);
    } else if ($_GET['sched'] == 'approval') {
        echo approvalLeave($_POST["empno"], $_POST["reason"], $_POST["datefrom"], $_SESSION, $timestamp, $HRconnect);
    } else if ($_GET['sched'] == 'decline') {
        echo declineLeave($_POST["empno"], $_POST["reason"], $_POST["datefrom"], $_SESSION, $timestamp, $HRconnect);
    } else if ($_GET['sched'] == 'redirection') {
        echo redirection($_SESSION);
    }

}

function getWorkHours($emp_id, $datefrom, $HRconnect)
{
    if (strlen($datefrom) <= 0) {
        return json_encode("null");
    }
    $work_hours_st = "SELECT work_hours, schedfrom, schedto, datefromto, break, sched_type FROM `hrms`.`sched_time` WHERE empno = $emp_id AND datefromto IN ($datefrom) ORDER BY datefromto ASC";
    $query_work_hours = $HRconnect->query($work_hours_st);
    $result_arr = $query_work_hours->fetch_all(MYSQLI_ASSOC);

    $leave_schedule = array();
    foreach ($result_arr as $details => $result) {
        $hours = 0;
        if ($result["work_hours"] == "" || $result["work_hours"] == null || $result["work_hours"] == "NWD" || $result["work_hours"] == "RD") {
            $hours = 8;
        } else {
            if ($result["work_hours"] == "AB" || $result["work_hours"] == "LWP") {
                $hours = ((strtotime($result["schedto"]) - strtotime($result["schedfrom"])) / 3600) - $result["break"];
            } else {
                $hours = $result["work_hours"];
            }
        }

        $whole_day_credit = 0;
        $half_day_credit = 0;
        if ($hours == 10) {
            $whole_day_credit = 1.25;
        } else {
            $whole_day_credit = 1;
        }

        if ($hours == 10) {
            $half_day_credit = 0.63;
        } else {
            $half_day_credit = 0.5;
        }

        $work_arr = array(
            "date" => $result["datefromto"],
            "whole" => $whole_day_credit,
            "half" => $half_day_credit
        );

        array_push($leave_schedule, $work_arr);
    }


    $response = array(
        "leave_sched" => $leave_schedule,
    );
    return json_encode($response);
}

function getLeave($emp_id, $HRconnect)
{
    $leave_st = "SELECT vl FROM `hrms`.`user_info` WHERE empno = ?";
    $stmt = $HRconnect->prepare($leave_st);
    $stmt->bind_param("i", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $response = array(
        "remainingLeave" => $result["vl"]
    );
    return json_encode($response);
}

function postLeave($leave_details, $emp_details, $timestamp, $HRconnect)
{
    $empno = intval($emp_details["empno"]);
    $leaveType = $emp_details["leaveTypes"];
    $reason = $emp_details["reason"];
    $vlnumber = $emp_details["vlnumber"];

    for ($ctr = 0; $ctr < count($leave_details); $ctr++) {
        $date_leave = $leave_details[$ctr]["date"];
        $value_leave = floatval($leave_details[$ctr]["value"]);
        $duration_leave = $leave_details[$ctr]["duration"];

        // Check if leave already exists
        $select_leave = "SELECT COUNT(*) AS leave_count FROM `hrms`.`vlform` WHERE empno = ? AND vldatefrom = ? AND vlstatus NOT IN ('canceled', 'approved')";
        $stmt_select = $HRconnect->prepare($select_leave);
        $stmt_select->bind_param("is", $empno, $date_leave);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $row = $result->fetch_assoc();
        $leave_count = intval($row['leave_count']);

        if ($leave_count == 0) {
            // Leave does not exist, insert new leave
            $insert_leave = "INSERT INTO `hrms`.`vlform` (empno, vltype, vlnumber, vldatefrom, vlreason, vlhours, vlduration, timedate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $HRconnect->prepare($insert_leave);
            $stmt_insert->bind_param("issssdss", $empno, $leaveType, $vlnumber, $date_leave, $reason, $value_leave, $duration_leave, $timestamp);
            $stmt_insert->execute();
        }
    }
}

function isExist($empno, $selected_dates, $HRconnect)
{
    if ($selected_dates != "") {
        $imploded_dates = implode("', '", $selected_dates);
        $select_approved_dates = "SELECT vldatefrom FROM `hrms`.`vlform` WHERE empno = $empno AND vldatefrom in ('$imploded_dates') AND vlstatus in ('pending', 'approved')";
        $query = $HRconnect->query($select_approved_dates);
        $result = $query->fetch_all(MYSQLI_ASSOC);
        $response = array(
            "dates" => $result,
            "status" => true
        );

        return json_encode($response);
    } else {
        $response = array(
            "dates" => null,
            "status" => false
        );
        return json_encode($response);
    }
}

function approvalLeave($empno, $reason, $datefrom, $session_arr, $timestamp, $HRconnect)
{
    $check_remaining_leave = "SELECT ui.vl, vl.vlhours, vl.vlduration FROM `hrms`.`vlform` vl
        LEFT JOIN `hrms`.`user_info` ui ON ui.empno = vl.empno
        WHERE vl.empno = ? and vl.vldatefrom = ?";
    $stmt = $HRconnect->prepare($check_remaining_leave);
    $stmt->bind_param("is", $empno, $datefrom);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $approver_details = "SELECT name FROM `hrms`.`user_info` WHERE empno = ?";
    $stmt = $HRconnect->prepare($approver_details);
    $stmt->bind_param("i", $session_arr["empno"]);
    $stmt->execute();
    $details = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $remaining_leave = $result["vl"];
    $vl_hours_filed = $result["vlhours"];
    $vl_duration_filed = $result["vlduration"];
    $approver_name = $details["name"];


    $leave_credits = $remaining_leave;
    $leave_difference = floatval($remaining_leave) - floatval($vl_hours_filed);

    if ($vl_duration_filed == "Whole Day" && ($leave_credits < 1 && $leave_credits > 0)) {
        $leave_difference = 0;
        $vl_duration_filed = "Filed Remaining Leaves";
        $vl_hours_filed = $leave_credits;
    } else if ($vl_duration_filed == "Half Day" && ($leave_credits < 0.5 && $leave_credits > 0)) {
        $leave_difference = 0;
        $vl_duration_filed = "Filed Remaining Leaves";
        $vl_hours_filed = $leave_credits;
    }

    $is_already_approved = "SELECT vlstatus FROM `hrms`.`vlform` WHERE empno = ? and vldatefrom = ?";
    $stmt = $HRconnect->prepare($is_already_approved);
    $stmt->bind_param("is", $empno, $datefrom);
    $stmt->execute();
    $result_already_approved = $stmt->get_result()->fetch_assoc();
    $stmt->close();


    $status = false;
    if ($result_already_approved["vlstatus"] == "approved") {
        $response = array(
            "status" => $status,
            "remaining_leave" => number_format($leave_credits, 2),
            "is_approved" => true
        );
        return json_encode($response);
    }

    if ($leave_difference >= 0) {
        $vlstatus = "approved";
        $status = true;
        $update_leave_credit = "UPDATE `hrms`.`user_info` SET vl = ? WHERE empno = ?";
        $stmt = $HRconnect->prepare($update_leave_credit);
        $stmt->bind_param("di", $leave_difference, $empno);
        $stmt->execute();
        $stmt->close();

        $update_leave_status = "UPDATE `hrms`.`vlform` SET vlstatus = ?, approver = ?, apptimedate = ?, vlduration = ?, vlhours = ? WHERE empno = ? AND vldatefrom = ?";
        $stmt = $HRconnect->prepare($update_leave_status);
        $stmt->bind_param("ssssdis", $vlstatus, $approver_name, $timestamp, $vl_duration_filed, $vl_hours_filed, $empno, $datefrom);
        $stmt->execute();
        $stmt->close();
        $leave_credits = $leave_difference;
    }

    $response = array(
        "status" => $status,
        "remaining_leave" => number_format($leave_credits, 2),
        "is_approved" => false
    );
    return json_encode($response);
}

function declineLeave($empno, $reason, $datefrom, $session_arr, $timestamp, $HRconnect)
{
    $approver_details = "SELECT name FROM `hrms`.`user_info` WHERE empno = ?";
    $stmt = $HRconnect->prepare($approver_details);
    $stmt->bind_param("i", $session_arr["empno"]);
    $stmt->execute();
    $details = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $approver_name = $details["name"];

    $status = true;

    $vlstatus = "canceled";
    $update_leave_status = "UPDATE `hrms`.`vlform` SET vlstatus = ?, approver = ?, apptimedate = ? WHERE empno = ? AND vldatefrom = ?";
    $stmt = $HRconnect->prepare($update_leave_status);
    $stmt->bind_param("sssis", $vlstatus, $approver_name, $timestamp, $empno, $datefrom);
    $stmt->execute();
    $stmt->close();
    $response = array(
        "status" => $status,
    );
    return json_encode($response);
}

function redirection($session_arr)
{
    $response = array(
        "session" => $session_arr
    );
    return json_encode($response);
}
?>