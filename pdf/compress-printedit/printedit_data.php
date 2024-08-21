<?php
$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Hooks
$empid = $_SESSION["emp_sched"]["empno"];
$cutfrom = $_SESSION["emp_sched"]["datefrom"];
$cutto = $_SESSION["emp_sched"]["dateto"];
$arr_remarks = array(
    "AB" => "AB",
    "RD" => "RD",
    "NWD" => "NWD",
    "LWP" => "LWP",
    "ML" => "ML",
    "PL" => "PL",
    "SPL" => "SPL",
    "BL" => "BL",
    "WDL" => "WDL",
    "NS" => "NS",
);

// Sched Info ID Validation and Redirection --------------------------------------//
$select_si_id = "SELECT id FROM `hrms`.`sched_info` WHERE empno = ? AND datefrom = ? AND dateto = ?";
$stmt = $HRconnect->prepare($select_si_id);
$stmt->bind_param("iss", $empid, $cutfrom, $cutto);
$stmt->execute();
$employee_si_id = $stmt->get_result()->fetch_array();
$stmt->close();

$isCompressed = "SELECT sched_type FROM `hrms`.`sched_time` WHERE empno = ? AND datefromto BETWEEN ? AND ? AND sched_type != ?";
$stmt = $HRconnect->prepare($isCompressed);
$schedType = "cmp_sched";
$stmt->bind_param("isss", $empid, $cutfrom, $cutto, $schedType);
$stmt->execute();
$resultIsCompressed = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (count($resultIsCompressed) > 0) {
    header("location:../printedit.php?id=" . $employee_si_id["id"] . "");
}
// ------------------------------------------------------------------------------//

// Employee Details -------------------------------------------------------------//
$select_details = "SELECT name, branch, department,userid, position FROM `hrms`.`user_info` WHERE empno = ?";
$stmt = $HRconnect->prepare($select_details);
$stmt->bind_param("i", $empid);
$stmt->execute();
$employee_details = $stmt->get_result()->fetch_array();
$stmt->close();

$name = $employee_details["name"];
$branch = $employee_details["branch"];
$department = $employee_details["department"];
$position = $employee_details["position"];
//-------------------------------------------------------------------------------//

// Employee Schedule ------------------------------------------------------------//
$select_schedule = "SELECT datefromto, schedfrom, schedto, break, M_timein, M_timeout, A_timein, A_timeout, timein, breakout, breakin, timeout, remarks, work_hours
                        FROM `hrms`.`sched_time` WHERE empno = ? AND datefromto BETWEEN ? AND ?";
$stmt = $HRconnect->prepare($select_schedule);
$stmt->bind_param("iss", $empid, $cutfrom, $cutto);
$stmt->execute();
$employee_schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
// ------------------------------------------------------------------------------//


// For Saving Schedule ----------------------------------------------------------//
if (isset($_POST["SubmitButton"])) {
    // Collating Data 
    $schedule_container = array();
    $key_array = array_keys($_POST);
    foreach ($_POST['cutoff-date'] as $counter => $cutoff_date) {
        $per_date_container = array();

        foreach ($key_array as $key) {
            if (isset($_POST[$key][$counter])) {
                $value = $_POST[$key][$counter];
            } else {
                $value = null;
            }

            array_push($per_date_container, $value);
        }
        array_push($schedule_container, $per_date_container);
    }
    $status = false;
    for ($counter = 0; $counter < count($schedule_container); $counter++) {
        $datefromto = DateTime::createFromFormat('m-d-Y', $schedule_container[$counter][0]);
        $converted_d = $datefromto->format("Y-m-d");

        if ($_SESSION["userlevel"] != "master") {
            $break = $schedule_container[$counter][5];
            $remarks = $schedule_container[$counter][6];

            $schedfrom = $converted_d . " " . $schedule_container[$counter][1] . ":" . $schedule_container[$counter][2];
            $schedto = $converted_d . " " . $schedule_container[$counter][3] . ":" . $schedule_container[$counter][4];

            if (strtotime($schedto) < strtotime($schedfrom)) {
                $schedto = date("Y-m-d H:i", strtotime($schedto . " +1 day"));
            }
            // Saving to the database
            $select_sched_time = "SELECT datefromto, schedfrom, schedto, 
                break, M_timein, M_timeout, A_timein, 
                A_timeout, remarks, timein, timeout,
                breakout, breakin FROM `hrms`.`sched_time` WHERE empno = ? AND datefromto = ? AND break = ? AND schedto = ? AND schedfrom = ? AND remarks = ?";
            $stmt_sel = $HRconnect->prepare($select_sched_time);
            $stmt_sel->bind_param("isisss", $empid, $converted_d, $break, $schedto, $schedfrom, $remarks);
            $stmt_sel->execute();
            $stmt_sel->store_result();

            if ($stmt_sel->num_rows > 0) {
                continue;
            }
            $update_sql = "UPDATE `hrms`.`sched_time` SET schedfrom = ?, schedto = ?, break = ?, remarks = ? WHERE empno = ? AND datefromto = ?";
            $stmt = $HRconnect->prepare($update_sql);
            $stmt->bind_param("ssisis", $schedfrom, $schedto, $break, $remarks, $empid, $converted_d);
            $stmt->execute();
            $status = true;
        } else {
            $break = intval($schedule_container[$counter][3]);
            $remarks = $schedule_container[$counter][12];

            // if (trim($schedule_container[$counter][6]) == "No Break") {
            //     $breakout_cls = $schedule_container[$counter][6];
            // } else {
            //     $breakout_cls = $converted_d . " " . $schedule_container[$counter][6];
            // }
            // if (trim($schedule_container[$counter][8]) == "No Break") {
            //     $breakin_cls = $schedule_container[$counter][8];
            // } else {
            //     $breakin_cls = $converted_d . " " . $schedule_container[$counter][8];
            // }
            // if (trim($schedule_container[$counter][7]) == "No Break") {
            //     $breakout_hd = $schedule_container[$counter][7];
            // } else {
            //     $breakout_hd = $converted_d . " " . $schedule_container[$counter][7];
            // }
            // if (trim($schedule_container[$counter][9]) == "No Break") {
            //     $breakin_hd = $schedule_container[$counter][9];
            // } else {
            //     $breakin_hd = $converted_d . " " . $schedule_container[$counter][9];
            // }
            $M_timein = "";
            $M_timeout = "";
            $A_timein = "";
            $A_timeout = "";
            $timein = "";
            $breakout = "";
            $breakin = "";
            $timeout = "";
            // $M_timein = (trim($schedule_container[$counter][4]) == "") ? "" : $converted_d . " " . $schedule_container[$counter][4];
            // $M_timeout = (trim($schedule_container[$counter][6]) == "") ? "" : $breakout_cls;
            // $A_timein = (trim($schedule_container[$counter][8]) == "") ? "" : $breakin_cls;
            // $A_timeout = (trim($schedule_container[$counter][10]) == "") ? "" : $converted_d . " " . $schedule_container[$counter][10];
            // $timein = (trim($schedule_container[$counter][5]) == "") ? "" : $converted_d . " " . $schedule_container[$counter][5];
            // $breakout = (trim($schedule_container[$counter][7]) == "") ? "" : $breakout_hd ;
            // $breakin = (trim($schedule_container[$counter][9]) == "") ? "" : $breakin_hd;
            // $timeout = (trim($schedule_container[$counter][11]) == "") ? "" : $converted_d . " " . $schedule_container[$counter][11];
            $schedfrom = $converted_d . " " . $schedule_container[$counter][1];
            $schedto = $converted_d . " " . $schedule_container[$counter][2];


            if (strtotime($schedto) < strtotime($schedfrom)) {
                $schedto = date("Y-m-d H:i", strtotime($schedto . " +1 day"));
            }

            if (strtotime($A_timeout) < strtotime($M_timein) && trim($schedule_container[$counter][10]) != "") {
                $A_timeout = date("Y-m-d H:i", strtotime($A_timeout . " +1 day"));
            }
            $select_sched_time = "SELECT datefromto, schedfrom, schedto, 
                break, M_timein, M_timeout, A_timein, 
                A_timeout, remarks, timein, timeout,
                breakout, breakin FROM `hrms`.`sched_time` WHERE schedfrom = ? AND schedto = ? AND break = ? AND remarks = ? AND empno = ? AND datefromto = ?";
            $stmt_sel = $HRconnect->prepare($select_sched_time);
            $stmt_sel->bind_param("ssisis", $schedfrom, $schedto, $break, $remarks, $empid, $converted_d);

            $stmt_sel->execute();
            $stmt_sel->store_result();
            if ($stmt_sel->num_rows > 0) {
                continue;
            }

            $update_sql = "UPDATE `hrms`.`sched_time` SET schedfrom = ?, schedto = ?, break = ?, remarks = ? WHERE empno = ? AND datefromto = ?";
            $stmt = $HRconnect->prepare($update_sql);
            $stmt->bind_param("ssisis", $schedfrom, $schedto, $break, $remarks, $empid, $converted_d);
            $stmt->execute();
            $status = true;
        }
    }

    if ($status) {
        echo "<script>alert('Save Successful');</script>";
        header("Refresh:0");
    } else {
        echo "<script>alert('No Changes were made');</script>";
    }
}
// ------------------------------------------------------------------------------//
