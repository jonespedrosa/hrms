<?php
    session_start();
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    if(isset($_GET['breakdown'])){
        if($_GET['breakdown'] == 'getCutoff'){
            echo getCutOff($HRconnect, $_SESSION["empno"]); 
        }else if($_GET['breakdown'] == 'overtime'){
            echo getOvertimeBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'obp'){
            echo getOBPbreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'wdo'){
            echo getWDObreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'leave'){
            echo getLeaveBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'sched'){
            echo getSchedBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'concern'){
            echo getConcernBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'late'){
            echo getLateBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'overbreak'){
            echo getOverbreakBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
        else if($_GET['breakdown'] == 'undertime'){
            echo getUndertimeBreakdown($_GET["datefrom"], $_GET["dateto"], $HRconnect);
        }
    } 

    function getCutOff($HRconnect, $empno){ 
        $select_cut_off = "SELECT DISTINCT datefrom, dateto FROM `hrms`.`sched_info` WHERE status = 'Pending' AND empno = '$empno' ORDER BY datefrom ASC LIMIT 1";
        $query = $HRconnect->query($select_cut_off);
        $cut_off = $query->fetch_array(MYSQLI_ASSOC);

        return json_encode($cut_off);
    }

    function getOvertimeBreakdown($datefrom, $dateto, $HRconnect){

        $select_ot_breakdown = "SELECT us.areatype, ui.userid, ui.branch, SUM(ot.othours) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.overunder ot ON ot.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND ot.otdatefrom BETWEEN '$datefrom' AND '$dateto' AND ot.otstatus = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getOBPbreakdown($datefrom, $dateto, $HRconnect){

        $select_obp_breakdown = "SELECT us.areatype, ui.userid, ui.branch, count(obp.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.obp ON obp.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND obp.datefromto BETWEEN '$datefrom' AND '$dateto' AND obp.status = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC;";
        $query = $HRconnect->query($select_obp_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getWDObreakdown($datefrom, $dateto, $HRconnect){

        $select_wdo_breakdown = "SELECT us.areatype, ui.userid, ui.branch, count(wdo.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.working_dayoff wdo ON wdo.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND wdo.datefrom BETWEEN '$datefrom' AND '$dateto' AND wdo.wdostatus = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_wdo_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    
    function getLeaveBreakdown($datefrom, $dateto, $HRconnect){

        $select_leave_breakdown = "SELECT us.areatype, ui.userid, ui.branch, count(vl.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.vlform vl ON vl.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND vl.vldatefrom BETWEEN '$datefrom' AND '$dateto' AND vl.vlstatus = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_leave_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getSchedBreakdown($datefrom, $dateto, $HRconnect){

        $select_sched_breakdown = "SELECT us.areatype, ui.userid, ui.branch, count(cs.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.change_schedule cs ON cs.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND cs.datefrom BETWEEN '$datefrom' AND '$dateto' AND cs.cs_status = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_sched_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getConcernBreakdown($datefrom, $dateto, $HRconnect){

        $select_concern_breakdown = "SELECT us.areatype, ui.userid, ui.branch, count(dc.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.dtr_concerns dc ON dc.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                LEFT JOIN db.user us ON us.userid = ui.userid
                                WHERE ui.userid in (
                                    SELECT DISTINCT(userid) FROM hrms.user_info
                                ) AND dc.filing_date BETWEEN '$datefrom' AND '$dateto' AND dc.status = 'approved'
                                GROUP BY ui.userid ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_concern_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getLateBreakdown($datefrom, $dateto, $HRconnect){ 
        $late_breakdown_branch = array();
        $employee_sched = getSchedTime($datefrom, $dateto, $HRconnect);
        // var_dump($employee_sched);
        foreach($employee_sched as $userid => $data){
            $late = 0;
            $branch = "";
            $areatype = "";
            foreach($data as $row) { 
                $branch = $row["branch"];
                $areatype = $row["areatype"];
                $sched_from_str = strtotime($row["schedfrom"]);
                $timein_str = strtotime($row["M_timein"]);
                $late += max($timein_str - $sched_from_str, 0);
            }
            $branch_late = array(
                "areatype" => $areatype,
                "userid" => $userid,
                "branch" => $branch,
                "total_hours" => $late / 60
            );
            array_push($late_breakdown_branch, $branch_late);
            // push
        }
        return json_encode($late_breakdown_branch);
    }

    function getOverbreakBreakdown($datefrom, $dateto, $HRconnect){ 
        $overbreak_breakdown_branch = array();
        $employee_sched = getSchedTime($datefrom, $dateto, $HRconnect);
        // var_dump($employee_sched);
        foreach($employee_sched as $userid => $data){
            $overbreak = 0;
            $branch = "";
            $areatype = "";
            foreach($data as $row) { 
                $branch = $row["branch"];
                $areatype = $row["areatype"];
                $breakout_str = strtotime("+".intval($row["break"])." hours", strtotime($row["M_timeout"]));
                $breakin_str = strtotime($row["A_timein"]);
                $overbreak += max($breakin_str - $breakout_str, 0);
            }
            $branch_overbreak = array(
                "areatype" => $areatype,
                "userid" => $userid,
                "branch" => $branch,
                "total_hours" => $overbreak / 60
            );
            array_push($overbreak_breakdown_branch, $branch_overbreak);
            // push
        }
        return json_encode($overbreak_breakdown_branch);
    }

    function getUndertimeBreakdown($datefrom, $dateto, $HRconnect){ 
        $undertime_breakdown_branch = array();
        $employee_sched = getSchedTime($datefrom, $dateto, $HRconnect);
        // var_dump($employee_sched);
        foreach($employee_sched as $userid => $data){
            $undertime = 0;
            $branch = "";
            $areatype = "";
            foreach($data as $row) { 
                $branch = $row["branch"];
                $areatype = $row["areatype"];
                $timeout_str = strtotime($row["A_timeout"]);
                $schedto_str = strtotime($row["schedto"]);
                $undertime += max($schedto_str - $timeout_str, 0);
            }
            $branch_undertime = array(
                "areatype" => $areatype,
                "userid" => $userid,
                "branch" => $branch,
                "total_hours" => $undertime / 60
            );
            array_push($undertime_breakdown_branch, $branch_undertime);
            // push
        }
        return json_encode($undertime_breakdown_branch);
    }

    function getSchedTime($datefrom, $dateto, $HRconnect){
        $test_user = 9999;
        $test_user2 = 9998;
        $select_schedule = "SELECT us.areatype, ui.userid, ui.branch, st.empno, st.schedfrom, st.schedto, st.M_timein, st.M_timeout, st.A_timein, st.A_timeout, st.break
                            FROM hrms.sched_time st
                            LEFT JOIN hrms.user_info ui ON ui.empno = st.empno
                            LEFT JOIN db.user us ON us.userid = ui.userid
                            WHERE datefromto BETWEEN ? AND ?
                            AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND ui.empno NOT IN (?, ?) ORDER BY ui.branch";
        $stmt = $HRconnect->prepare($select_schedule); 
        $stmt->bind_param("ssii", $datefrom, $dateto, $test_user, $test_user2);
        $stmt->execute();
        $stmt->bind_result(
            $areatype,
            $userid,
            $branch,
            $empno,
            $schedfrom,
            $schedto,
            $M_timein,
            $M_timeout,
            $A_timein,
            $A_timeout, 
            $break,
        );

        $result = array();
        while ($stmt->fetch()) {
            $result[$userid][] = array(
                'areatype' => $areatype,
                'empno' => $empno, 
                'branch' => $branch, 
                'schedfrom' => $schedfrom,
                'schedto' => $schedto,
                'M_timein' => $M_timein,
                'M_timeout' => $M_timeout,
                'A_timein' => $A_timein,
                'A_timeout' => $A_timeout,
                'break' => $break,
            );
        }

        return $result;
    }
