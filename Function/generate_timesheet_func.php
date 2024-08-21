<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
if (isset($_GET['generate'])) {
    if ($_GET['generate'] == 'timesheet') {
        echo generateTimesheet($_GET["cutfrom"], $_GET["cutto"], $_GET["dept"], $_GET["isRegen"], $HRconnect);
    } else if ($_GET['generate'] == 'checkExisting') {
        // echo print_r($_GET);
        echo isExistingTimesheet($_POST["cutfrom"], $_POST["cutto"], $_POST["employees"], $HRconnect);
    } else if ($_GET['generate'] == 'save') {
        // var_dump($_POST["employee_timesheet"]);
        echo saveTimesheet($_POST["cutfrom"], $_POST["cutto"], $_POST["isExisting"], $_POST["employee_timesheet"], $HRconnect);
    }
}

function generateTimesheet($cutfrom, $cutto, $dept, $isRegen, $HRconnect)
{
    if (count(json_decode(isRegenerate($cutfrom, $cutto, $dept, $HRconnect))) > 0 && $isRegen == "false") {
        // var_dump(json_encode(isRegenerate($cutfrom, $cutto, $dept, $HRconnect)));
        return isRegenerate($cutfrom, $cutto, $dept, $HRconnect);
    } else {
        $select_schedule = "SELECT 	
            st.empno, st.datefromto, st.schedfrom, st.schedto, st.break, st.M_timein, st.M_timeout, st.A_timein, st.A_timeout, st.timein, 
            st.breakout, st.breakin, st.timeout, st.remarks, st.timein4, st.timeout4, st.work_hours, st.sched_type, st.m_in_status, st.m_o_status, st.a_in_status, st.a_o_status,
            ot.othours, ot.otstatus, ot.ottype, ot.isNWD, wdo.wdostatus, wdo.working_hours,
            vl.vlstatus, vl.vlhours, ho.type, ho.prior1, ho.prior2, ho.prior3,
            ui.name, ui.branch, ui.eligible_work_premium, (
                SELECT COUNT(*) AS hasInputs FROM `hrms`.`sched_time` sta
                    LEFT JOIN `hrms`.`vlform` vla ON sta.empno = vla.empno 
                    WHERE sta.empno = st.empno AND sta.datefromto in (ho.prior1, ho.prior2, ho.prior3) AND (
                        (sta.M_timein != '' AND sta.M_timeout != '' AND sta.A_timein != '' AND sta.A_timeout != '') OR 
                        (vla.vlstatus = 'approved' AND vla.vldatefrom in (ho.prior1, ho.prior2, ho.prior3)) OR (st.remarks = 'NWD')
                    )
                ) AS holiday_worked
            FROM 
                `hrms`.`sched_time` st
            LEFT JOIN 
                `hrms`.`overunder` ot ON ot.otdatefrom = st.datefromto AND st.empno = ot.empno AND ot.otstatus != 'canceled'
            LEFT JOIN 
                `hrms`.`working_dayoff` wdo ON wdo.datefrom = st.datefromto AND st.empno = wdo.empno AND wdo.wdostatus != 'cancelled'
            LEFT JOIN 
                `hrms`.`vlform` vl ON vl.vldatefrom = st.datefromto AND vl.empno = st.empno AND vl.vlstatus != 'canceled'
            LEFT JOIN 
                `hrms`.`holiday` ho ON ho.holiday_day = st.datefromto  
            LEFT JOIN 
                `hrms`.`user_info` ui ON ui.empno = st.empno 
            WHERE 
                st.empno in (SELECT empno FROM `hrms`.`user_info` WHERE userid = ?) AND (ui.status = ? OR ui.status = ?)
                AND st.datefromto BETWEEN ? AND ?
            GROUP BY 
                st.empno, st.datefromto
            ORDER BY 
                st.empno ASC";
        $stmt = $HRconnect->prepare($select_schedule);
        $status = "active";
        $resigned = 'resigned';
        $stmt->bind_param("issss", $dept, $status, $resigned, $cutfrom, $cutto);
        $stmt->execute();
        $stmt->bind_result(
            $empno,
            $datefromto,
            $schedfrom,
            $schedto,
            $break,
            $M_timein,
            $M_timeout,
            $A_timein,
            $A_timeout,
            $timein,
            $breakout,
            $breakin,
            $timeout,
            $remarks,
            $timein4,
            $timeout4,
            $work_hours,
            $sched_type,
            $m_in_status,
            $m_o_status,
            $a_in_status,
            $a_o_status,
            $othours,
            $otstatus,
            $ottype,
            $isNWD,
            $wdostatus,
            $working_hours,
            $vlstatus,
            $vlhours,
            $type,
            $prior1,
            $prior2,
            $prior3,
            $name,
            $branch,
            $eligible_work_premiums,
            $holiday_worked
        );

        $result = array();
        while ($stmt->fetch()) {
            $result[$empno][] = array(
                'datefromto' => $datefromto,
                'schedfrom' => $schedfrom,
                'schedto' => $schedto,
                'break' => $break,
                'M_timein' => $M_timein,
                'M_timeout' => $M_timeout,
                'A_timein' => $A_timein,
                'A_timeout' => $A_timeout,
                'timein' => $timein,
                'breakout' => $breakout,
                'breakin' => $breakin,
                'timeout' => $timeout,
                'remarks' => $remarks,
                'timein4' => $timein4,
                'timeout4' => $timeout4,
                'work_hours' => $work_hours,
                'sched_type' => $sched_type,
                'm_in_status' => $m_in_status,
                'm_o_status' => $m_o_status,
                'a_in_status' => $a_in_status,
                'a_o_status' => $a_o_status,
                'othours' => $othours,
                'otstatus' => $otstatus,
                'ottype' => $ottype,
                'isNWD' => $isNWD,
                'wdostatus' => $wdostatus,
                'working_hours' => $working_hours,
                'vlstatus' => $vlstatus,
                'vlhours' => $vlhours,
                'type' => $type,
                'prior1' => $prior1,
                'prior2' => $prior2,
                'prior3' => $prior3,
                'name' => $name,
                'branch' => $branch,
                'eligible_work_premium' => $eligible_work_premiums,
                'holiday_worked' => $holiday_worked,
            );
        }

        // $employee_schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return json_encode(calculation($result));
    }
}
function calculation($employee_schedule)
{
    $all_dept_arr = array();
    foreach ($employee_schedule as $empno => $data) {
        $total_work_hours = 0;
        $late = 0;
        $undertime = 0;
        $leave = 0;
        $ordinary_ot = 0;
        $ordinary_nd = 0;
        $ordinary_ndot = 0;
        $special_hrs = 0;
        $special_ot = 0;
        $special_nd = 0;
        $special_ndot = 0;
        $legal_hrs = 0;
        $legal_ot = 0;
        $legal_nd = 0;
        $legal_ndot = 0;
        $working_off = 0;
        $name = "";
        $id = "";
        $branch = "";
        $is_leave = array();
        $emp_arr = array();
        foreach ($data as $row) {
            // var_dump( $row);
            #region hoooks
            $name = $row["name"];
            $branch = $row["branch"];
            $eligible_work_premiums = $row["eligible_work_premium"];
            $datefromto = $row["datefromto"];
            $schedfrom = $row["schedfrom"];
            $schedto = $row["schedto"];
            $M_timein = $row["M_timein"];
            $M_timeout = $row["M_timeout"];
            $A_timein = $row["A_timein"];
            $A_timeout = $row["A_timeout"];
            $timein = ($row["M_timein"] == "") ? "" : date("Y-m-d H:i", strtotime($row["M_timein"]));

            // FOR BREAKS
            $M_timeout_result;
            $A_timein_result;
            if (stripos($row["M_timeout"], "No Break") !== false) {
                $M_timeout_result = "No Break";
            } else {
                $M_timeout_result = date("H:i", strtotime($row["M_timeout"]));
            }
            if (stripos($row["A_timein"], "No Break") !== false) {
                $A_timein_result = "No Break";
            } else {
                $A_timein_result = date("H:i", strtotime($row["A_timein"]));
            }
            $breakout = ($row["M_timeout"] == "") ? "" : $M_timeout_result;
            $breakin = ($row["A_timein"] == "") ? "" : $A_timein_result;
            $timeout = ($row["A_timeout"] == "") ? "" : date("Y-m-d H:i", strtotime($row["A_timeout"]));
            $break = $row["break"];

            // broken sched
            $timein_broken = ($row["timein4"] == "") ? "" : date("Y-m-d H:i", strtotime($row["timein4"]));
            $timeout_broken = ($row["timeout4"] == "") ? "" : date("Y-m-d H:i", strtotime($row["timeout4"]));

            // holiday
            $holiday_type = $row["type"];
            $holiday_worked = $row["holiday_worked"];

            // workloads 
            $wdostatus = $row["wdostatus"];
            $working_hours = $row["working_hours"];
            $ot_hours = $row["othours"];
            $ot_type = $row["ottype"];
            $ot_status = $row["otstatus"];
            $isNWD = $row["isNWD"];
            $scheduled_work_hours = $row["work_hours"];
            $sched_type = $row["sched_type"];
            $vl_status = $row["vlstatus"];
            $vl_hours = $row["vlhours"];
            #endregion

            $timein_status = $row["m_in_status"];
            $breakout_status = $row["m_o_status"];
            $breakin_status = $row["a_in_status"];
            $timeout_status = $row["a_o_status"];
            $isComplete = isComplete($timein, $timeout, $breakin, $breakout, $schedfrom, $schedto, $scheduled_work_hours, $sched_type, $break, $timein_status, $breakout_status, $breakin_status, $timeout_status);
            array_push($is_leave, $vl_status);
            if ($scheduled_work_hours != "NWD") {
                $total_work_hours += workDaysCalculator($isComplete, $scheduled_work_hours, $sched_type);
                $late += lateCalculator($isComplete, $schedfrom, $M_timein, $break, $M_timeout, $A_timein);
                $undertime += utCalculator($isComplete, $schedto, $A_timeout, $ot_hours, $ot_type, $ot_status, $timein_broken, $timeout_broken);
            }
            $leave += LeaveCalculator($vl_status, $vl_hours);

            $workload_calcu = workLoadCalcu($isComplete, $M_timein, $A_timeout, $break, $wdostatus, $working_hours, $ot_hours, $ot_type, $ot_status, $schedfrom, $schedto, $holiday_type, $holiday_worked, $sched_type, $scheduled_work_hours, $timein_broken, $isNWD, $empno, $datefromto);
            // Is Eligible for work premiums
            if ($eligible_work_premiums == 1) {
                $ordinary_ot += $workload_calcu["ordinary_ot"];

                $ordinary_nd += $workload_calcu["ordinary_nd"];

                $ordinary_ndot += $workload_calcu["ordinary_ndot"];

                $special_ot += $workload_calcu["special_ot"];

                $special_nd += $workload_calcu["special_nd"];

                $special_ndot += $workload_calcu["special_ndot"];

                $legal_ot += $workload_calcu["legal_ot"];

                $legal_nd += $workload_calcu["legal_nd"];

                $legal_ndot += $workload_calcu["legal_ndot"];
            }

            $special_hrs += $workload_calcu["special_hours"];

            $legal_hrs += $workload_calcu["legal_hours"];

            $working_off += $workload_calcu["wdo_hours"];
        }
        #region array push
        $emp_arr = array(
            'empno' => $empno,
            'name' => $name,
            'branch' => $branch,
            'total_work_hours' => ($total_work_hours / 8),
            'late' => floor($late / 60),
            'undertime' => floor($undertime / 60),
            'leave' => $leave,
            'ordinary_nd' => floor($ordinary_nd / 60),
            'ordinary_ot' => $ordinary_ot,
            'ordinary_ndot' => $ordinary_ndot,
            'special_hrs' => floor($special_hrs / 60),
            'special_nd' => floor($special_nd / 60),
            'special_ot' => $special_ot,
            'special_ndot' => $special_ndot,
            'legal_hrs' => floor($legal_hrs / 60),
            'legal_nd' => floor($legal_nd / 60),
            'legal_ot' => $legal_ot,
            'legal_ndot' => $legal_ndot,
            'working_off' => $working_off
        );

        if ((!($total_work_hours / 8) <= 0) || in_array("approved", $is_leave)) {
            array_push($all_dept_arr, $emp_arr);
        }
        // array_push($all_dept_arr, $emp_arr);
        #endregion
    }
    return $all_dept_arr;
}
function isComplete($timein, $timeout, $breakin, $breakout, $schedfrom, $schedto, $scheduled_work_hours, $sched_type, $break, $timein_status, $breakout_status, $breakin_status, $timeout_status)
{
    $worked_hours = 0;
    if ($sched_type == "cmp_sched") {
        if (intval($scheduled_work_hours) == 8 || intval($scheduled_work_hours) == 10) {
            $worked_hours = intval($scheduled_work_hours);
        } else if ($scheduled_work_hours == "NWD" || $scheduled_work_hours == "RD") {
            $worked_hours = 8;
        } else {
            $worked_hours = 0;
        }
    } else {
        $worked_hours = 8;
    }
    $sched_range = strtotime("+" . $worked_hours + $break . " hours", strtotime($schedfrom));
    if (
        (strcasecmp('pending', $timein_status) != 0 && strcasecmp('pending', $breakout_status) != 0 && strcasecmp('pending', $breakin_status) != 0 && strcasecmp('pending', $timeout_status) != 0) &&
        (($timein != "" && $breakout != "" && $breakin != "" && $timeout != "") && $sched_range == strtotime($schedto))
    ) {
        return true;
    }
    return false;
}
function workDaysCalculator($isComplete, $scheduled_work_hours, $sched_type)
{
    if ($isComplete) {
        if ($sched_type == "cmp_sched") {
            if (intval($scheduled_work_hours) == 8 || intval($scheduled_work_hours) == 10) {
                return intval($scheduled_work_hours);
            } else if ( /*$scheduled_work_hours == "NWD" ||*/ $scheduled_work_hours == "RD") {
                return 8;
            } else {
                return 0;
            }
        } else {
            return 8;
        }
    }
}
function lateCalculator($isComplete, $schedfrom, $timein, $break, $breakout, $breakin)
{
    $late = 0;
    $timein_str = strtotime($timein);
    $sched_from_str = strtotime($schedfrom);
    $breakout_str = strtotime("+" . intval($break) . " hours", strtotime($breakout));
    $breakin_str = strtotime($breakin);
    if ($isComplete) {
        $late += max($timein_str - $sched_from_str, 0);
        // if($break > 0 && (strcmp(strtolower($breakout), "no break") !== 0 && strcmp(strtolower($breakin), "no break") !== 0)){
        if ($break != 0) {
            $late += max($breakin_str - $breakout_str, 0);
        }
        // }
    }
    return $late;
}
function utCalculator($isComplete, $schedto, $timeout, $ot_hours, $ot_type, $ot_status, $timein_broken, $timeout_broken)
{
    $ut = 0;
    $timeout_str = strtotime($timeout);
    $schedto_str = strtotime($schedto);
    $broken_minutes = 0;
    if ($isComplete) {
        $ut = max($schedto_str - $timeout_str, 0);
    }

    if ($timein_broken != "" && $timeout_broken != "") {
        $timein_b = strtotime($timein_broken);
        $timeout_b = strtotime($timeout_broken);

        $broken_minutes = max($timeout_b - $timein_b, 0);
        $ut -= $broken_minutes;
    }

    // if ($ot_type != 0 && $ot_status == "approved") {
    //     $ut -= intval($ot_hours) * 60;
    // }

    if ($ut <= 0) {
        $ut = 0;
    }
    return $ut;
}
function LeaveCalculator($vl_status, $vl_hours)
{
    $vl = 0;
    if (strcmp(strtolower($vl_status), "approved") == 0) {
        $vl += $vl_hours;
    }
    return $vl;
}
function workLoadCalcu($isComplete, $timein, $timeout, $break, $wdostatus, $working_hours, $ot_hours, $ot_type, $ot_status, $schedfrom, $schedto, $holiday_type, $holiday_worked, $sched_type, $scheduled_work_hours, $timein_broken, $isNWD, $empno, $datefromto)
{
    $wdo_hours = 0;
    $special_hours = 0;
    $legal_hours = 0;
    $wdo_nd = 0;
    $special_nd = 0;
    $legal_nd = 0;
    $ordinary_nd = 0;
    $wdo_ot = 0;
    $special_ot = 0;
    $legal_ot = 0;
    $ordinary_ot = 0;
    $wdo_ndot = 0;
    $special_ndot = 0;
    $legal_ndot = 0;
    $ordinary_ndot = 0;

    $worked_hours = 0;
    $nd_worked_hours = 0;
    if ($sched_type == "cmp_sched") {
        if (intval($scheduled_work_hours) == 8 || intval($scheduled_work_hours) == 10) {
            $worked_hours = intval($scheduled_work_hours);
            $nd_worked_hours = intval($scheduled_work_hours);
        } else if ($scheduled_work_hours == "NWD" || $scheduled_work_hours == "RD") {
            $worked_hours = 8;
            $nd_worked_hours = 8;
        } else {
            $worked_hours = 0;
            $nd_worked_hours = 0;
        }
    } else {
        $worked_hours = 8;
        $nd_worked_hours = 8;
    }
    $nd_worked_hours *= 60;
    $nd_break = $break * 60;

    if ($isComplete) {
        // var_dump($schedfrom);
        if (strcmp(strtolower($wdostatus), "approved") == 0) {
            $wdo_hours += intval($working_hours);
            // $wdo_hours += intval($ot_hours); 
            // if($holiday_type == 1){
            //     $special_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];
            //     $special_ndot += OTcalculator(strtotime($schedto), intval($ot_hours), strtotime($schedfrom), $scheduled_work_hours, $ot_status, strtotime($timeout), $ot_type, $timein_broken, $timein_broken)["nd_overtime"];
            // }else if($holiday_type == 0 && $holiday_worked > 0){
            //     $legal_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];
            //     $legal_ndot += OTcalculator(strtotime($schedto), intval($ot_hours), strtotime($schedfrom), $scheduled_work_hours, $ot_status, strtotime($timeout), $ot_type, $timein_broken)["nd_overtime"];
            // }else{
            //     $ordinary_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"]; 
            //     $ordinary_ndot += OTcalculator(strtotime($schedto), intval($ot_hours), strtotime($schedfrom), $scheduled_work_hours, $ot_status, strtotime($timeout), $ot_type, $timein_broken)["nd_overtime"];
            // }
        }
        $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
        $otdatefrom = date('Y-m-d', strtotime($datefromto));
        $check_multiple_ot = "SELECT othours, ottype, otstatus, otdatefrom FROM `hrms`.`overunder` WHERE empno = $empno AND otdatefrom = '$otdatefrom' AND otstatus = 'approved'";
        $query_multiple_ot = $HRconnect->query($check_multiple_ot);
        $result_multiple_ot = $query_multiple_ot->fetch_all(MYSQLI_ASSOC);
        if (($isNWD == 1 && $scheduled_work_hours == "NWD") || ($isNWD == 0 && $scheduled_work_hours != "NWD")) {
            if ($holiday_type == 1) {
                $special_hours += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["normal_hrs"];
                $special_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];


                foreach ($result_multiple_ot as $multiple_ot) {
                    $special_ot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["normal_ot"];
                    $special_ndot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["nd_overtime"];
                }

            } else if ($holiday_type === 0 && $holiday_worked > 0) {
                $legal_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];

                foreach ($result_multiple_ot as $multiple_ot) {
                    $legal_ot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["normal_ot"];
                    $legal_ndot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["nd_overtime"];
                }

            } else {
                $ordinary_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];

                foreach ($result_multiple_ot as $multiple_ot) {
                    // var_dump($multiple_ot);
                    $ordinary_ot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["normal_ot"];
                    $ordinary_ndot += OTcalculator(strtotime($schedto), intval($multiple_ot['othours']), strtotime($datefromto), $scheduled_work_hours, $multiple_ot['otstatus'], strtotime($timeout), $multiple_ot['ottype'], $timein_broken)["nd_overtime"];
                }

            }
        }
    }

    if ($holiday_worked > 0 || ($holiday_type === 0 && $isComplete)) {
        $legal_hours += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $worked_hours * 60, $break, $holiday_type)["normal_hrs"];
    }

    $workload_container = array(
        "wdo_hours" => $wdo_hours,
        "special_hours" => $special_hours,
        "legal_hours" => $legal_hours,
        "special_nd" => $special_nd,
        "legal_nd" => $legal_nd,
        "ordinary_nd" => $ordinary_nd,
        "special_ot" => $special_ot,
        "legal_ot" => $legal_ot,
        "ordinary_ot" => $ordinary_ot,
        "special_ndot" => $special_ndot,
        "legal_ndot" => $legal_ndot,
        "ordinary_ndot" => $ordinary_ndot
    );
    return $workload_container;
}
function nightDiffCalcu($schedfrom, $timeout, $timein, $work_hours, $break, $holiday_type)
{
    $normal_hrs = 0;
    $night_diff = 0;
    if (date("H:i", $schedfrom) < "06:00") {
        $night_start = strtotime(date("Y-m-d", strtotime("-1 day", $schedfrom)) . " " . "22:00");
        $night_end = strtotime(date("Y-m-d", $schedfrom) . " " . "06:00");
    } else {
        $night_start = strtotime(date("Y-m-d", $schedfrom) . " " . "22:00");
        $night_end = strtotime(date("Y-m-d", strtotime("+1 day", $schedfrom)) . " " . "06:00");
    }
    for ($counter = 1; $counter <= $work_hours + $break; $counter++) {
        $sched_range = strtotime("+" . $counter . " minutes", $schedfrom);
        if ($holiday_type == 1) {
            if (($sched_range > $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                $night_diff++;
            } else if ($sched_range > /*$timein*/ $schedfrom && $sched_range <= $timeout) {
                $normal_hrs++;
            }
        } else {
            if (($sched_range > $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                $night_diff++;
            } else {
                $normal_hrs++;
            }
        }
    }
    $hours_container = array(
        "normal_hrs" => ($normal_hrs + $night_diff) - $break,
        "night_diff" => $night_diff
    );
    return $hours_container;
}
function OTcalculator($schedto, $ot_hours, $schedfrom, $work_sched, $ot_status, $timeout, $ot_type, $timein_broken)
{
    $overtime = 0;
    $nd_overtime = 0;
    $night_start = strtotime(date("Y-m-d", $schedfrom) . " " . "23:00");
    $night_end = strtotime(date("Y-m-d", strtotime("+1 day", $schedfrom)) . " " . "06:00");
    if ($ot_status == "approved") {
        if ($ot_type == 0 || $ot_type == "") {
            if ($work_sched == "NWD") {
                for ($counter = 1; $counter <= $ot_hours; $counter++) {
                    $sched_range = strtotime("+" . $counter . "hours", $schedfrom);
                    if (($sched_range >= $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                        $nd_overtime++;
                    } else {
                        $overtime++;
                    }
                }
            } else {
                for ($counter = 1; $counter <= $ot_hours; $counter++) {
                    $sched_range = strtotime("+" . $counter . "hours", $schedto);
                    if ($sched_range >= $night_start && $sched_range <= $night_end) {
                        $nd_overtime++;
                    } else {
                        $overtime++;
                    }
                }
            }
        } else {
            if ($work_sched == "NWD") {
                for ($counter = 1; $counter <= $ot_hours; $counter++) {
                    $sched_range = strtotime("+" . $counter . "hours", strtotime($timein_broken));
                    if (($sched_range >= $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                        $nd_overtime++;
                    } else {
                        $overtime++;
                    }
                }
            } else {
                for ($counter = 1; $counter <= $ot_hours; $counter++) {
                    $sched_range = strtotime("+" . $counter . "hours", strtotime($timein_broken));
                    if ($sched_range >= $night_start && $sched_range <= $night_end) {
                        $nd_overtime++;
                    } else {
                        // var_dump(date("Y-m-d H:i", $sched_range));
                        $overtime++;
                    }
                }
            }
        }
    }
    if ($ot_hours <= 0) {
        $overtime = 0;
        $nd_overtime = 0;
    }
    $overtime_container = array(
        "normal_ot" => $overtime + $nd_overtime,
        "nd_overtime" => $nd_overtime
    );
    return $overtime_container;
}
function saveTimesheet($cutfrom, $cutto, $isExisting, $employee_timesheet, $HRconnect)
{

    if (!($employee_timesheet == "" || $employee_timesheet == null)) {
        try {
            foreach (json_decode($employee_timesheet) as $data) {
                $daysWork = floatval($data->dayswork);
                $lateOver = floatval($data->late);
                $undertime = floatval($data->undertime);
                $vLeave = floatval($data->leave);
                $nightDiff = floatval($data->ordinary_nd);
                $regularOT = floatval($data->ordinary_ot);
                $nightDiffOT = floatval($data->ordinary_ndot);
                $legalDay = floatval($data->legal_hrs);
                $legalDayND = floatval($data->legal_nd);
                $legalDayOT = floatval($data->legal_ot);
                $legalDayNDOT = floatval($data->legal_ndot);
                $specialDay = floatval($data->special_hrs);
                $specialDayND = floatval($data->special_nd);
                $specialDayOT = floatval($data->special_ot);
                $specialDayNDOT = floatval($data->special_ndot);
                $workdayOiff = floatval($data->working_off);
                $empno = intval($data->empno);
                $cutfrom = $data->cutfrom;
                $cutto = $data->cutto;

                $is_existing = "SELECT COUNT(*) AS existing FROM `hrms`.`generated` WHERE empno = ? AND datefrom = ? AND dateto = ?";
                $stmt = $HRconnect->prepare($is_existing);
                $stmt->bind_param("iss", $empno, $cutfrom, $cutto);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();

                if ($result["existing"] > 0) {
                    $update_timesheet = "UPDATE `hrms`.`generated` SET dayswork = ?, lateover = ?, undertime = ?, vleave = ?, nightdiff = ?, regularot = ?, nightdiffot = ?, legalday = ?, legaldaynd = ?, legaldayot = ?, legaldayndot = ?, specialday = ?, specialdaynd = ?, specialdayot = ?, specialdayndot = ?, workdayoiff = ? WHERE empno = ? AND datefrom = ? AND dateto = ?";
                    $stmt = $HRconnect->prepare($update_timesheet);
                    $stmt->bind_param(
                        "ddddddddddddddddiss",
                        $daysWork,
                        $lateOver,
                        $undertime,
                        $vLeave,
                        $nightDiff,
                        $regularOT,
                        $nightDiffOT,
                        $legalDay,
                        $legalDayND,
                        $legalDayOT,
                        $legalDayNDOT,
                        $specialDay,
                        $specialDayND,
                        $specialDayOT,
                        $specialDayNDOT,
                        $workdayOiff,
                        $empno,
                        $cutfrom,
                        $cutto
                    );
                    $stmt->execute();
                } else {
                    $insert_timesheet = "INSERT INTO `hrms`.`generated` (empno, datefrom, dateto, dayswork, lateover, undertime, vleave, nightdiff, regularot, nightdiffot, legalday, legaldaynd, legaldayot, legaldayndot, specialday, specialdaynd, specialdayot, specialdayndot, workdayoiff)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                    $stmt = $HRconnect->prepare($insert_timesheet);
                    $stmt->bind_param(
                        "issdddddddddddddddd",
                        $empno,
                        $cutfrom,
                        $cutto,
                        $daysWork,
                        $lateOver,
                        $undertime,
                        $vLeave,
                        $nightDiff,
                        $regularOT,
                        $nightDiffOT,
                        $legalDay,
                        $legalDayND,
                        $legalDayOT,
                        $legalDayNDOT,
                        $specialDay,
                        $specialDayND,
                        $specialDayOT,
                        $specialDayNDOT,
                        $workdayOiff
                    );
                    $stmt->execute();
                }
            }
            $response = array(
                "status" => true
            );
            return json_encode($response);
        } catch (Exception $e) {
            $response = array(
                "status" => false
            );
            return json_encode($response);
        }
    }
    return false;
}
function isExistingTimesheet($cutfrom, $cutto, $employees, $HRconnect)
{
    $response = "";
    if (!($employees == "" || $employees == null)) {
        try {
            foreach (json_decode($employees, true) as $data) {
                $is_existing = "SELECT COUNT(*) AS existing FROM `hrms`.`generated` WHERE empno = ? AND datefrom = ? AND dateto = ?";
                $stmt = $HRconnect->prepare($is_existing);
                $stmt->bind_param("iss", $data[0], $cutfrom, $cutto);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                if ($result["existing"] > 0) {
                    $response = array(
                        "status" => true
                    );
                } else {
                    $response = array(
                        "status" => false
                    );
                    return json_encode($response);
                }
            }
            $response = array(
                "status" => true
            );
            return json_encode($response);
        } catch (Exception $e) {
            $response = array(
                "status" => false
            );
            return json_encode($response);
        }
    }
    $response = array(
        "status" => false
    );
    return json_encode($response);
}
function isRegenerate($cutfrom, $cutto, $dept, $HRconnect)
{
    $select_timesheet = "SELECT ui.empno, ui.name, ui.branch, 
        gd.dayswork AS total_work_hours, gd.lateover AS late, gd.undertime AS undertime, gd.vleave AS `leave`,
        gd.nightdiff AS ordinary_nd, gd.regularot AS ordinary_ot, gd.nightdiffot AS ordinary_ndot,
        gd.specialday AS special_hrs, gd.specialdaynd AS special_nd, gd.specialdayot AS special_ot, gd.specialdayndot AS special_ndot,
        gd.legalday AS legal_hrs, gd.legaldaynd AS legal_nd, gd.legaldayot AS legal_ot, gd.legaldayndot AS legal_ndot,
        gd.workdayoiff AS working_off
        FROM `hrms`.`generated` gd
        LEFT JOIN `hrms`.`user_info` ui ON ui.empno = gd.empno
        WHERE gd.empno IN (SELECT empno FROM hrms.user_info WHERE userid = ?) AND datefrom = ? AND dateto = ?";
    $stmt = $HRconnect->prepare($select_timesheet);
    $stmt->bind_param("iss", $dept, $cutfrom, $cutto);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return json_encode($result);
}
