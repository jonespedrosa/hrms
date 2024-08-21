<?php
    session_start();
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    $attendance_discrepencies = array("late", "overbreak", "undertime");

    if(isset($_GET["action"])){
        if($_GET["action"] == "getBreakdown" && in_array($_GET["category"], $attendance_discrepencies)){
            echo getBreakdownDiscrepancies($_GET["branch"], $_GET["category"], $_GET["cutfrom"], $_GET["cutto"], $HRconnect);
        }else if($_GET["action"] == "getBreakdown"){
            echo getFiledDocuments($_GET["branch"], $_GET["category"], $_GET["cutfrom"], $_GET["cutto"], $HRconnect);
        }
    }

    function getBreakdownDiscrepancies($branch, $category, $cutfrom, $cutto, $HRconnect){
        $employee_sched = getSchedTime($cutfrom, $cutto, $branch, $HRconnect);
        $breakdown_branch = array();
        // var_dump($employee_sched);
        foreach($employee_sched as $empno => $data){
            $hours = 0;
            $branch = "";
            $areatype = "";
            foreach($data as $row) { 
                $branch = $row["branch"];
                $name = $row["name"];
                
                $sched_from_str = strtotime($row["schedfrom"]);
                $schedto_str = strtotime($row["schedto"]);

                $timein_str = strtotime($row["M_timein"]);
                $timeout_str = strtotime($row["A_timeout"]);

                $breakout_str = strtotime("+".intval($row["break"])." hours", strtotime($row["M_timeout"]));
                $breakin_str = strtotime($row["A_timein"]);

                if($category == "late"){
                    $hours += max($timein_str - $sched_from_str, 0);
                }else if($category == "overbreak"){
                    $hours += max($breakin_str - $breakout_str, 0);
                }else if($category == "undertime"){
                    $hours += max($schedto_str - $timeout_str, 0);
                }
            }
            $branch = array(
                "empno" => $empno, 
                "name" => $name, 
                "branch" => $branch,
                "total_hours" => $hours / 60
            );
            array_push($breakdown_branch, $branch);
            // push
        }
        // var_dump($late_breakdown_branch);
        return json_encode($breakdown_branch);
    }

    function getSchedTime($datefrom, $dateto, $branch, $HRconnect){
        $test_user = 9999;
        $test_user2 = 9998;
        $select_schedule = "SELECT ui.userid, ui.name, ui.branch, st.empno, st.schedfrom, st.schedto, st.M_timein, st.M_timeout, st.A_timein, st.A_timeout, st.break
                            FROM hrms.sched_time st
                            LEFT JOIN hrms.user_info ui ON ui.empno = st.empno
                            WHERE datefromto BETWEEN ? AND ? AND ui.userid = ?
                            AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND ui.empno NOT IN (?, ?) GROUP BY ui.empno, st.schedfrom ";
        $stmt = $HRconnect->prepare($select_schedule); 
        $stmt->bind_param("sssii", $datefrom, $dateto, $branch, $test_user, $test_user2);
        $stmt->execute();
        $stmt->bind_result(
            $userid,
            $name,
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
            $result[$empno][] = array(
                'empno' => $empno, 
                'name' => $name, 
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

    function getFiledDocuments($branch, $category, $cutfrom, $cutto, $HRconnect){
        $query_result;

        if($category == "overtime"){
            $query_result = getOvertimeBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }else if($category == "obp"){
            $query_result = getOBPBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }else if($category == "wdo"){
            $query_result = getWDOBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }else if($category == "leave"){
            $query_result = getLeaveBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }else if($category == "sched"){
            $query_result = getSchedBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }else if($category == "concern"){
            $query_result = getConcernBreakdown($branch, $cutfrom, $cutto, $HRconnect);
        }

        return $query_result;
    }

    function getOvertimeBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_ot_breakdown = "SELECT ui.empno, ui.name, ui.branch, SUM(ot.othours) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.overunder ot ON ot.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND ot.otdatefrom BETWEEN '$cutfrom' AND '$cutto' AND ot.otstatus = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getOBPBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_obp_breakdown = "SELECT ui.empno, ui.name, ui.branch, count(obp.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.obp ON obp.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto' AND obp.status = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_obp_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getWDOBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_ot_breakdown = "SELECT ui.empno, ui.name, ui.branch, count(wdo.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.working_dayoff wdo ON wdo.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND wdo.datefrom BETWEEN '$cutfrom' AND '$cutto' AND wdo.wdostatus = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getSchedBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_ot_breakdown = "SELECT ui.empno, ui.name, ui.branch, count(cs.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.change_schedule cs ON cs.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND cs.datefrom BETWEEN '$cutfrom' AND '$cutto' AND cs_status = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getLeaveBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_ot_breakdown = "SELECT ui.empno, ui.name, ui.branch, count(vl.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.vlform vl ON vl.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND vl.vldatefrom BETWEEN '$cutfrom' AND '$cutto' AND vl.vlstatus = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }

    function getConcernBreakdown($branch, $cutfrom, $cutto, $HRconnect){
        $select_ot_breakdown = "SELECT ui.empno, ui.name, ui.branch, count(dc.empno) AS total_hours FROM hrms.user_info ui
                                LEFT JOIN hrms.dtr_concerns dc ON dc.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                WHERE ui.userid = $branch AND dc.filing_date BETWEEN '$cutfrom' AND '$cutto' AND dc.status = 'approved'
                                GROUP BY ui.empno ORDER BY total_hours DESC";
        $query = $HRconnect->query($select_ot_breakdown);
        $result = $query->fetch_all(MYSQLI_ASSOC);

        return json_encode($result);
    }
?>