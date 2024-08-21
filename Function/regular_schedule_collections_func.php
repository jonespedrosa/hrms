<?php
function getTimeInputs($empno, $cutfrom, $cutto, $HRconnect)
{
    $employee_schedule = getSchedule($empno, $cutfrom, $cutto, $HRconnect);

    $time_inputs_arr = array();
    $computations_arr = array();

    $workdays = 0;
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

    foreach ($employee_schedule as $key => $value) {
        $sched_arr = array(
            "datefromto" => $value["datefromto"],
            "sched" => checkSchedule($value["schedfrom"], $value["schedto"], $value["break"]),
            "break" => $value["break"],
            "M_timein" => checkTimein($value["M_timein"], $value["schedfrom"], $value["m_in_status"]),
            "A_timeout" => checkTimeOut($value["A_timeout"], $value["schedto"], $value["a_o_status"]),
            "M_timeout" => checkBreakOut($value["M_timeout"], $value["m_o_status"]),
            "A_timein" => checkBreakIn($value["M_timeout"], $value["A_timein"], $value["break"], $value["a_in_status"]),
            "othours" => /*checkOT($value["otstatus"], $value["othours"], $value["ottype"])*/ checkOT($value["empno"], $value["datefromto"], $HRconnect),
            "broken_othours" => /*checkBrokenOT($value["otstatus"], $value["othours"], $value["ottype"])*/ checkBrokenOT($value["empno"], $value["datefromto"], $HRconnect),
            "gen_timein" => ($value["timein4"] == "") ? " " : date("H:i", strtotime($value["timein4"])),
            "gen_timeout" => ($value["timeout4"] == "") ? " " : date("H:i", strtotime($value["timeout4"])),
            "remarks" => checkRemarksPerFile($value["wdostatus"], $value["vlstatus"], $value["obp_status"], $value["cs_status"], $value["empno"], $value["datefromto"], $value['remarks'], $HRconnect)
        );

        // var_dump($value);
        array_push($time_inputs_arr, $sched_arr);

        $isComplete = isComplete($value["M_timein"], $value["M_timeout"], $value["A_timein"], $value["A_timeout"], $value["schedfrom"], $value["schedto"], $value["break"], $value["m_in_status"], $value["m_o_status"], $value["a_in_status"], $value["a_o_status"]);
        $workdays += workdaysComputation(
            $isComplete
        );

        $late += lateCalculator(
            $isComplete,
            $value["schedfrom"],
            $value["M_timein"],
            $value["break"],
            $value["M_timeout"],
            $value["A_timein"]
        );
        // broken sched 
        $undertime += utCalculator(
            $isComplete,
            $value["schedto"],
            $value["A_timeout"],
            // $value["othours"],
            // $value["ottype"],
            // $value["otstatus"],
            $value["timein4"],
            $value["timeout4"]
        );

        $leave += LeaveCalculator($value["vlstatus"], $value["vlhours"]);

        $workloadData = workLoadCalcu(
            $isComplete,
            $value["M_timein"],
            $value["A_timeout"],
            $value["break"],
            $value["wdostatus"],
            $value["working_hours"],
            // $value["othours"],
            // $value["ottype"],
            // $value["otstatus"],
            $value["schedfrom"],
            $value["schedto"],
            $value["type"],
            $value["holiday_worked"],
            $value["sched_type"],
            $value["work_hours"],
            $value["timein4"],
            // $value["isNWD"]
            $value["empno"],
            $value["datefromto"]
        );

        $ordinary_ot += $workloadData["ordinary_ot"];
        $ordinary_nd += $workloadData["ordinary_nd"];
        $ordinary_ndot += $workloadData["ordinary_ndot"];
        $special_ot += $workloadData["special_ot"];
        $special_nd += $workloadData["special_nd"];
        $special_ndot += $workloadData["special_ndot"];
        $legal_ot += $workloadData["legal_ot"];
        $legal_nd += $workloadData["legal_nd"];
        $legal_ndot += $workloadData["legal_ndot"];
        $special_hrs += $workloadData["special_hours"];
        $legal_hrs += $workloadData["legal_hours"];
        $working_off += $workloadData["wdo_hours"];
    }

    $sched_computation_arr = array(
        "workdays" => $workdays / 8,
        "late" => floor($late / 60),
        "undertime" => floor($undertime / 60),
        "leave" => $leave,
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

    array_push($computations_arr, $sched_computation_arr);
    $print_schedule_arr = array(
        "time_inputs" => $time_inputs_arr,
        "computation" => checkBackTrack($empno, $cutfrom, $cutto, $HRconnect) ? getGenerated($empno, $cutfrom, $cutto, $HRconnect) : $sched_computation_arr
    );

    return $print_schedule_arr;
}

function checkBackTrack($empno, $cutfrom, $cutto, $HRconnect)
{
    $is_generated = "SELECT COUNT(empno) AS is_generated FROM generated WHERE empno = ? AND datefrom = ? AND dateto = ?";
    $stmt = $HRconnect->prepare($is_generated);
    $stmt->bind_param("iss", $empno, $cutfrom, $cutto);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // return false;
    return $result["is_generated"] > 0;
}

function getGenerated($empno, $cutfrom, $cutto, $HRconnect)
{
    $is_generated = "SELECT * FROM generated WHERE empno = ? AND datefrom = ? AND dateto = ? LIMIT 1";
    $stmt = $HRconnect->prepare($is_generated);
    $stmt->bind_param("iss", $empno, $cutfrom, $cutto);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $sched_computation_arr = array(
        "workdays" => $result["dayswork"],
        "late" => $result["lateover"],
        "undertime" => $result["undertime"],
        "leave" => $result["vleave"],
        'ordinary_nd' => $result["nightdiff"],
        'ordinary_ot' => $result["regularot"],
        'ordinary_ndot' => $result["nightdiffot"],
        'special_hrs' => $result["specialday"],
        'special_nd' => $result["specialdaynd"],
        'special_ot' => $result["specialdayot"],
        'special_ndot' => $result["specialdayndot"],
        'legal_hrs' => $result["legalday"],
        'legal_nd' => $result["legaldaynd"],
        'legal_ot' => $result["legaldayot"],
        'legal_ndot' => $result["legaldayndot"],
        'working_off' => $result["workdayoiff"]
    );

    return $sched_computation_arr;
}
function getSchedule($empno, $cutfrom, $cutto, $HRconnect)
{
    $select_schedule = "SELECT st.empno, st.datefromto, st.schedfrom, st.schedto, st.break, st.M_timein, st.M_timeout, st.A_timein, st.A_timeout, st.timein, 
            st.breakout, st.breakin, st.timeout, st.remarks, st.timein4, st.timeout4, st.work_hours, st.sched_type, st.m_in_status, st.m_o_status, st.a_in_status, st.a_o_status,
            /*ot.othours, ot.otstatus, ot.ottype, ot.isNWD,*/ wdo.wdostatus, wdo.working_hours,
            vl.vlstatus, vl.vlhours, obp.status AS obp_status, cs.cs_status,
            ho.type, ho.prior1, ho.prior2, ho.prior3,
            ui.name, ui.branch, ui.eligible_work_premium, (
                    SELECT COUNT(*) AS hasInputs FROM `hrms`.`sched_time` sta
                    LEFT JOIN `hrms`.`vlform` vla ON sta.empno = vla.empno 
                    WHERE sta.empno = st.empno AND sta.datefromto in (ho.prior1, ho.prior2, ho.prior3) AND (
                        (sta.M_timein != '' AND sta.M_timeout != '' AND sta.A_timein != '' AND sta.A_timeout != '') OR 
                        (vla.vlstatus = 'approved' AND vla.vldatefrom in (ho.prior1, ho.prior2, ho.prior3))
                    )
                    -- SELECT COUNT(*) as hasInputs FROM `hrms`.`sched_time` sta LEFT JOIN `hrms`.`vlform` vl ON vl.empno = sta.empno 
                    -- WHERE sta.empno = st.empno AND sta.datefromto in (ho.prior1, ho.prior2, ho.prior3) AND 
                    -- ((sta.M_timein != '' AND sta.M_timeout != '' AND sta.A_timein != '' AND sta.A_timeout != '') OR vl.vlstatus = 'approved');
                ) AS holiday_worked
            FROM `hrms`.`sched_time` st
            -- LEFT JOIN `hrms`.`overunder` ot ON ot.otdatefrom = st.datefromto AND st.empno = ot.empno AND ot.otstatus != 'canceled'
            LEFT JOIN `hrms`.`working_dayoff` wdo ON wdo.datefrom = st.datefromto AND st.empno = wdo.empno AND wdo.wdostatus != 'cancelled'
            LEFT JOIN `hrms`.`vlform` vl ON vl.vldatefrom = st.datefromto AND vl.empno = st.empno AND vl.vlstatus != 'canceled'
            -- LEFT JOIN `hrms`.`dtr_concerns` con ON con.ConcernDate = st.datefromto AND con.empno = st.empno AND con.status != 'Cancelled' AND con.status != 'Disapproved' AND con.concern != 'Remove Time Inputs'
            LEFT JOIN `hrms`.`obp` obp ON obp.datefromto = st.datefromto AND obp.empno = st.empno AND obp.status != 'Canceled' 
            LEFT JOIN `hrms`.`change_schedule` cs ON cs.datefrom = st.datefromto AND cs.empno = st.empno AND cs.cs_status != 'cancelled' 
            LEFT JOIN `hrms`.`holiday` ho ON ho.holiday_day = st.datefromto  
            LEFT JOIN `hrms`.`user_info` ui ON ui.empno = st.empno 
            WHERE st.empno = ? AND st.datefromto BETWEEN ? AND ? ORDER BY st.datefromto ASC";
    $stmt = $HRconnect->prepare($select_schedule);
    $status = "active";
    $stmt->bind_param("iss", $empno, $cutfrom, $cutto);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $result;
}

function checkSchedule($schedfrom, $schedto, $break)
{
    $work_hours = 8;
    $break = intval($break);

    $schedule_computation = ((strtotime($schedto) - strtotime($schedfrom)) / 3600) - $break;

    $flag = ($schedule_computation == $work_hours) ? "" : "text-danger font-weight-bold";
    $check_schedule_arr = array(
        "flag" => $flag,
        "schedfrom" => date("H:i", strtotime($schedfrom)),
        "schedto" => date("H:i", strtotime($schedto)),
    );

    return $check_schedule_arr;
}

function checkTimein($timein, $schedfrom, $status)
{
    $timein_computation = strtotime($timein) > strtotime($schedfrom);

    $flag = ($timein_computation == false) ? " " : "text-danger font-weight-bold";
    $result = '';
    if (strcasecmp('pending', $status) == 0) {
        $result = 'Pending';
    } else if ($timein == "") {
        $result = " ";
    } else {
        $result = date("H:i", strtotime($timein));
    }
    $check_timein_arr = array(
        "flag" => $result == 'Pending' ? '' : $flag,
        "timein" => $result

        // "timein" => ($timein == "") ? " " : date("H:i", strtotime($timein)),
    );

    return $check_timein_arr;
}

function checkTimeOut($timeout, $schedto, $status)
{
    $timeout_computation = strtotime($timeout) < strtotime($schedto);

    $flag = ($timeout_computation == false) ? "" : "text-danger font-weight-bold";
    $result = '';
    if (strcasecmp('pending', $status) == 0) {
        $result = 'Pending';
    } else if ($timeout == "") {
        $result = " ";
    } else {
        $result = date("H:i", strtotime($timeout));
    }
    $check_timeout_arr = array(
        "flag" => $result == 'Pending' ? '' : $flag,
        "timeout" => $result
    );

    return $check_timeout_arr;
}

function checkBreakOut($breakout, $status)
{
    $result = "";
    if (strcasecmp('pending', $status) == 0) {
        $result = 'Pending';
    } else if ($breakout == "") {
        $result = " ";
    } else if (strcasecmp($breakout, "No Break") == 0) {
        $result = $breakout;
    } else {
        $result = date("H:i", strtotime($breakout));
    }
    return $result;

}

function checkBreakIn($breakout, $breakin, $break, $status)
{
    $flag = false;

    if ($breakout != "" || !strcasecmp($breakout, "No Break")) {
        $breaktime = strtotime("+ " . intval($break) . " hours", strtotime($breakout));
        $breaktime_computation = $breaktime < strtotime($breakin);
        $flag = ($breaktime_computation == false) ? "" : "text-danger font-weight-bold";
    }

    $result = "";
    if (strcasecmp('pending', $status) == 0) {
        $result = 'Pending';
    } else if ($breakin == "") {
        $result = " ";
    } else if (strcasecmp($breakin, "No Break") == 0) {
        $result = $breakin;
    } else {
        $result = date("H:i", strtotime($breakin));
    }
    $check_breakout_arr = array(
        "flag" => $result == 'Pending' ? '' : $flag,
        "breakin" => $result

    );

    return $check_breakout_arr;
}

// function checkOT($otstatus, $othours, $ottype)
// {
//     $ot = "";
//     if ($ottype == 0) {
//         if ($otstatus == "pending") {
//             $ot = "Pending";
//         } else if ($otstatus == "pending2") {
//             $ot = "Partially Approved";
//         } else if ($otstatus == "approved") {
//             $ot = $othours;
//         }
//     }
//     return $ot;
// }
function checkOT($empno, $datefromto, $HRconnect)
{
    $ot = "";
    $sql_regular_ot = "SELECT ottype, otstatus, othours FROM `hrms`.`overunder` WHERE empno = $empno AND otdatefrom = '$datefromto' AND ottype = '0' AND otstatus != 'canceled'";
    $query = $HRconnect->query($sql_regular_ot);
    $row = $query->fetch_assoc();

    if (!is_null($row)) {
        if ($row["otstatus"] == "pending") {
            $ot = "Pending";
        } else if ($row["otstatus"] == "pending2") {
            $ot = "Partially Approved";
        } else if ($row["otstatus"] == "approved") {
            $ot = $row["othours"];
        }
    }
    return $ot;
}

function checkBrokenOT($empno, $datefromto, $HRconnect)
{
    $ot = "";
    $sql_regular_ot = "SELECT ottype, otstatus, othours FROM `hrms`.`overunder` WHERE empno = $empno AND otdatefrom = '$datefromto' AND ottype != '0'  AND otstatus != 'canceled'";
    $query = $HRconnect->query($sql_regular_ot);
    $row = $query->fetch_assoc();

    if (!is_null($row)) {
        if ($row["otstatus"] == "pending") {
            $ot = "Pending";
        } else if ($row["otstatus"] == "pending2") {
            $ot = "Partially Approved";
        } else if ($row["otstatus"] == "approved") {
            $ot = $row["othours"];
        }
    }
    return $ot;
}
// function checkBrokenOT($otstatus, $othours, $ottype)
// {
//     $ot = "";
//     if ($ottype != 0) {
//         if ($otstatus == "pending") {
//             $ot = "Pending";
//         } else if ($otstatus == "pending2") {
//             $ot = "Partially Approved";
//         } else if ($otstatus == "approved") {
//             $ot = $othours;
//         }
//     }
//     return $ot;
// }

function checkRemarksPerFile($wdo_status, $vl_status, $obp_status, $cs_status, $empno, $datefromto, $remarks, $HRconnect)
{
    $wdo_array = getStatusArray($wdo_status, 'WDO');
    $vl_array = getStatusArray($vl_status, 'WL');
    $concern_array = getConcernArray($empno, $datefromto, $HRconnect);
    $obp_array = getStatusArray($obp_status, 'OBP');
    $cs_array = getStatusArray($cs_status, 'CS');
    $remark_regular = $remarks;

    $check_remarks_arr = array(
        "wdo" => $wdo_array,
        "vl" => $vl_array,
        "concern" => $concern_array,
        "obp" => $obp_array,
        "cs" => $cs_array,
        "remarks" => $remark_regular
    );

    return $check_remarks_arr;
}

function getStatusArray($status, $type)
{
    $flag = 'd-none';

    $remarks = ' ';

    if (strcasecmp($status, 'pending') == 0) {
        $flag = 'text-danger text-uppercase font-italic';
        $remarks = "Pending $type";
    } elseif (strcasecmp($status, 'approved') == 0) {
        $flag = 'text-success text-uppercase font-italic';
        $remarks = "Approved $type";
    } elseif (strcasecmp($status, 'pending2') == 0) {
        $flag = 'text-danger text-uppercase font-italic';
        $remarks = "Partially Approved $type";
    }

    return [
        'flag' => $flag,
        'remarks' => $remarks,
    ];
}

function getConcernArray($empno, $datefromto, $HRconnect)
{
    // var_dump($HRconnect);
    $flag = 'd-none';

    $remarks = ' ';

    $sql_concern = "SELECT status FROM `hrms`.`dtr_concerns` WHERE empno = $empno AND ConcernDate = '$datefromto'";
    $query = $HRconnect->query($sql_concern);


    $remarks_array = array();
    $flag_array = array();
    while ($row = $query->fetch_assoc()) {
        // var_dump($row);
        if (strcasecmp($row["status"], 'disapproved') !== 0) {
            if (strcasecmp($row["status"], 'pending') == 0) {
                $flag = 'text-danger text-uppercase font-italic';
                $remarks = "Pending Concern";
                array_push($remarks_array, $remarks);
                array_push($flag_array, $flag);
            } elseif (strcasecmp($row["status"], 'approved') == 0) {
                $flag = 'text-success text-uppercase font-italic';
                $remarks = "Approved Concern";
                array_push($remarks_array, $remarks);
                array_push($flag_array, $flag);
            } elseif (strcasecmp($row["status"], 'pending2') == 0) {
                $flag = 'text-danger text-uppercase font-italic';
                $remarks = "Partially Approved Concern";
                array_push($remarks_array, $remarks);
                array_push($flag_array, $flag);
            }
        }
    }
    return [
        'flag' => $flag_array,
        'remarks' => $remarks_array,
    ];

}
// -------------------- FOOTER FUNCTIONS ----------------------- //

function isComplete($timein, $breakout, $breakin, $timeout, $schedfrom, $schedto, $break, $timein_status, $breakout_status, $breakin_status, $timeout_status)
{
    $sched_range = strtotime("+" . 8 + intval($break) . " hours", strtotime($schedfrom));
    if (
        (strcasecmp('pending', $timein_status) != 0 && strcasecmp('pending', $breakout_status) != 0 && strcasecmp('pending', $breakin_status) != 0 && strcasecmp('pending', $timeout_status) != 0) &&
        (
            ($timein != "" && $breakout != "" && $breakin != "" && $timeout != "") &&
            $sched_range == strtotime($schedto))
    ) {
        return true;
    }
    return false;
}

function workdaysComputation($isComplete)
{
    return $isComplete ? 8 : 0;
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

function utCalculator($isComplete, $schedto, $timeout, /*$ot_hours, $ot_type, $ot_status,*/ $timein_broken, $timeout_broken)
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

function workLoadCalcu($isComplete, $timein, $timeout, $break, $wdostatus, $working_hours, /*$ot_hours, $ot_type, $ot_status, */ $schedfrom, $schedto, $holiday_type, $holiday_worked, $sched_type, $scheduled_work_hours, $timein_broken, /*$isNWD*/ $empno, $datefromto)
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
        if (strcmp(strtolower($wdostatus), "approved") == 0) {
            $wdo_hours += intval($working_hours);
        }
        $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
        $sql_ot = "SELECT isNWD FROM `hrms`.`overunder` WHERE empno = $empno AND otdatefrom = '$datefromto'";
        $query = $HRconnect->query($sql_ot);
        $row = $query->fetch_assoc();
        $isNWD = 0;

        if (isset($row["isNWD"])) {
            $isNWD = $row["isNWD"];
        }

        if (($isNWD == 1 && $scheduled_work_hours == "NWD") || ($isNWD == 0 && $scheduled_work_hours != "NWD")) {
            if ($holiday_type == 1) {
                $special_hours += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["normal_hrs"];
                $special_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];
                $special_ot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["normal_ot"];
                $special_ndot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["nd_overtime"];
            } else if ($holiday_type == 0 && $holiday_worked > 0) {
                $legal_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];
                $legal_ot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["normal_ot"];
                $legal_ndot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["nd_overtime"];
            } else {
                $ordinary_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $nd_worked_hours, $nd_break, $holiday_type)["night_diff"];
                $ordinary_ot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["normal_ot"];
                $ordinary_ndot += OTcalculator(strtotime($schedto), /*intval($ot_hours),*/ strtotime($schedfrom), $scheduled_work_hours, /*$ot_status,*/ strtotime($timeout), /*$ot_type,*/ $timein_broken, $empno, $datefromto)["nd_overtime"];
            }
        }
    }

    if ($holiday_worked > 0 || ($holiday_type === 0 && $isComplete)) {
        $legal_hours += nightDiffCalcu(strtotime($schedfrom), strtotime($timeout), strtotime($timein), $worked_hours * 60, $break, $holiday_type)["normal_hrs"];
        // var_dump($legal_hours);
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
    // var_dump($work_hours);
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
            } else if (($sched_range > /*$timein*/ $schedfrom) && ($sched_range <= $timeout)) {
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
    // var_dump(date("Y-m-d H:i", $timeout));

    return $hours_container;
}
function OTcalculator($schedto, /*$ot_hours,*/ $schedfrom, $work_sched, /*$ot_status,*/ $timeout, /*$ot_type,*/ $timein_broken, $empno, $datefromto)
{
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    $overtime = 0;
    $nd_overtime = 0;
    $night_start = strtotime(date("Y-m-d", $schedfrom) . " " . "23:00");
    $night_end = strtotime(date("Y-m-d", strtotime("+1 day", $schedfrom)) . " " . "06:00");

    $ot_status = "";
    $ot_hours = 0;
    $ot_type = "";

    $sql_regular_ot = "SELECT ottype, otstatus, othours, otdatefrom, isNWD FROM `hrms`.`overunder` WHERE empno = $empno AND otdatefrom = '$datefromto'";
    $query = $HRconnect->query($sql_regular_ot);
    // var_dump($sql_regular_ot);
    while ($row = $query->fetch_assoc()) {
        // var_dump($datefromto);
        $ot_hours += $row['othours'];
        if ($row['otstatus'] == "approved") {
            if ($row['ottype'] == 0 || $row['ottype'] == "") {
                if ($work_sched == "NWD") {
                    for ($counter = 1; $counter <= $row['ot_hours']; $counter++) {
                        $sched_range = strtotime("+" . $counter . "hours", $schedfrom);
                        if (($sched_range >= $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                            $nd_overtime++;
                        } else {
                            $overtime++;
                        }
                    }
                } else {
                    for ($counter = 1; $counter <= $row['othours']; $counter++) {
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
                    for ($counter = 1; $counter <= $row['othours']; $counter++) {
                        $sched_range = strtotime("+" . $counter . "hours", strtotime($timein_broken));
                        if (($sched_range >= $night_start && $sched_range <= $night_end) && $sched_range <= $timeout) {
                            $nd_overtime++;
                        } else {
                            $overtime++;
                        }
                    }
                } else {
                    for ($counter = 1; $counter <= $row['othours']; $counter++) {
                        $sched_range = strtotime("+" . $counter . "hours", strtotime($timein_broken));
                        if ($sched_range >= $night_start && $sched_range <= $night_end) {
                            $nd_overtime++;
                        } else {
                            // var_dump(date("Y-m-d H:i",$sched_range));
                            $overtime++;
                        }
                    }
                }
            }
        }
    }
    ;


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