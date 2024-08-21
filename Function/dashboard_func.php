<?php
    session_start();
    include("global_timestamp.php");
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    if(isset($_GET['dashboard'])){
        if($_GET['dashboard'] == 'renderedHours'){
            echo total_rendered_hours($_GET["cutfrom"], $_GET["cutto"], $HRconnect); 
        }else if($_GET['dashboard'] == 'lateMin'){
            echo total_late_minutes($_GET["cutfrom"], $_GET["cutto"], $HRconnect); 
        }else if($_GET['dashboard'] == 'overbreakMin'){
            echo total_overbreak_minutes($_GET["cutfrom"], $_GET["cutto"], $HRconnect); 
        }else if($_GET['dashboard'] == 'undertimeMin'){
            echo total_undertime_minutes($_GET["cutfrom"], $_GET["cutto"], $HRconnect); 
        }else if($_GET['dashboard'] == 'overtime'){
            echo total_overtime_breakdown($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }else if($_GET['dashboard'] == 'obp'){
            echo total_obp_count($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }else if($_GET['dashboard'] == 'leave'){
            echo total_leave_count($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }else if($_GET['dashboard'] == 'concern'){
            echo total_concern_count($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }else if($_GET['dashboard'] == 'cs'){
            echo total_change_schedule_count($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }else if($_GET['dashboard'] == 'wdo'){
            echo total_working_day_off_count($_GET["cutfrom"], $_GET["cutto"], $_GET["is_refresh"], $timestamp, $HRconnect); 
        }
    } 

    function total_rendered_hours($cutfrom, $cutto, $HRconnect){
        $total_hours = 0;
        if(isset($_SESSION["total_rendered_hours"])){
            $total_hours = $_SESSION["total_rendered_hours"];
            $response = array(
                "isCached" => true,
                "total_rendered_hours" => number_format($total_hours)
            );
            return json_encode($response);
        }else{
            $workhours_arr = array("NWD", "RD", "");

            // sched time count
            $select_schedule = "SELECT break, work_hours
                                    FROM hrms.sched_time
                                    WHERE datefromto BETWEEN '$cutfrom' AND '$cutto'
                                    AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND empno NOT IN ('9999', '9998')";
            $query_schedule = $HRconnect->query($select_schedule); 
    
            while($result_schedule = $query_schedule->fetch_assoc()){
                $breaks = $result_schedule["break"];
                $workhours = $result_schedule["work_hours"];
                
                if(in_array($workhours, $workhours_arr)){
                    $total_hours += 8;
                }else{
                    $total_hours += intval($workhours);
                }
    
                $total_hours -= intval($breaks);
            }
    
            // overtime count 
            $select_overunder = "SELECT SUM(othours) AS othours FROM hrms.overunder 
                                WHERE otdatefrom BETWEEN '$cutfrom' AND '$cutto' AND otstatus = 'approved'";
            $query_overunder = $HRconnect->query($select_overunder); 
            $result_overunder = $query_overunder->fetch_assoc();
            $total_hours += intval($result_overunder["othours"]);
            
    
            $_SESSION["total_rendered_hours"] = $total_hours;
            $response = array(
                "isCached" => false,
                "total_rendered_hours" => number_format($total_hours)
            );
            return json_encode($response);
        } 
    }

    function total_late_minutes($cutfrom, $cutto, $HRconnect){
        $total_mins = 0;
        // sched time count
        $select_schedule = "SELECT schedfrom, M_timein, M_timeout, A_timein, break
            FROM hrms.sched_time
            WHERE datefromto BETWEEN '$cutfrom' AND '$cutto'
            AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND empno NOT IN ('9999', '9998')";
        $query_schedule = $HRconnect->query($select_schedule); 

        while($result_schedule = $query_schedule->fetch_assoc()){
            $timein_str = strtotime($result_schedule["M_timein"]);
            $sched_from_str = strtotime($result_schedule["schedfrom"]);
            $breakout_str = strtotime("+".intval($result_schedule["break"])." hours", strtotime($result_schedule["M_timeout"]));
            $breakin_str = strtotime($result_schedule["A_timein"]);
            $total_mins += max($timein_str - $sched_from_str, 0);
            // $total_mins += max($breakin_str  - $breakout_str, 0);
        }

        $response = array(
            "isCached" => false,
            "total_late_minutes" => round($total_mins / 60, 2)
        );
        return json_encode($response);
        
    }

    function total_overbreak_minutes($cutfrom, $cutto, $HRconnect){
        $total_mins = 0;
        // sched time count
        $select_schedule = "SELECT schedfrom, M_timein, M_timeout, A_timein, break
            FROM hrms.sched_time
            WHERE datefromto BETWEEN '$cutfrom' AND '$cutto'
            AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND empno NOT IN ('9999', '9998')";
        $query_schedule = $HRconnect->query($select_schedule); 

        while($result_schedule = $query_schedule->fetch_assoc()){
            $timein_str = strtotime($result_schedule["M_timein"]);
            $sched_from_str = strtotime($result_schedule["schedfrom"]);
            $breakout_str = strtotime("+".intval($result_schedule["break"])." hours", strtotime($result_schedule["M_timeout"]));
            $breakin_str = strtotime($result_schedule["A_timein"]);
            // $total_mins += max($timein_str - $sched_from_str, 0);
            $total_mins += max($breakin_str  - $breakout_str, 0);
        }

        $response = array(
            "isCached" => false,
            "total_overbreak_minutes" => round($total_mins / 60, 2)
        );
        return json_encode($response);
        
    }

    function total_undertime_minutes($cutfrom, $cutto, $HRconnect){
        $total_mins = 0;
        // sched time count
        $select_schedule = "SELECT schedto, A_timeout
            FROM hrms.sched_time
            WHERE datefromto BETWEEN '$cutfrom' AND '$cutto'
            AND M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '' AND empno NOT IN ('9999', '9998')";
        $query_schedule = $HRconnect->query($select_schedule); 

        while($result_schedule = $query_schedule->fetch_assoc()){
            $timeout_str = strtotime($result_schedule["A_timeout"]);
            $schedto_str = strtotime($result_schedule["schedto"]);
            $total_mins += max($schedto_str - $timeout_str, 0); 
        }

        $response = array(
            "isCached" => false,
            "total_undertime_hours" => round($total_mins / 60, 2)
        );
        return json_encode($response);
    }

    function total_overtime_breakdown($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){ 
        if(isset($_SESSION["overtime_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["overtime_breakdown"]);
        }else{
            $select_ot_breakdown = "SELECT ui.userid, ui.branch, SUM(ot.othours) AS total_overtime_hours FROM hrms.user_info ui
                                    LEFT JOIN hrms.overunder ot ON ot.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND ot.otdatefrom BETWEEN '$cutfrom' AND '$cutto' AND ot.otstatus = 'approved'
                                    GROUP BY ui.userid ORDER BY total_overtime_hours DESC LIMIT 5;";
            $query = $HRconnect->query($select_ot_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC);
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            );  
            $_SESSION["overtime_breakdown"] = $output;
            return json_encode($output);
        } 
    }

    function total_obp_count($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){
        if(isset($_SESSION["obp_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["obp_breakdown"]);
        }else{
            $select_ot_breakdown = "SELECT ui.userid, ui.branch, count(obp.empno) AS total_obp_count FROM hrms.user_info ui
                                    LEFT JOIN hrms.obp ON obp.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto' AND obp.status = 'approved'
                                    GROUP BY ui.userid ORDER BY total_obp_count DESC LIMIT 5;";
            $query = $HRconnect->query($select_ot_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC); 
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            ); 
            
            $_SESSION["obp_breakdown"] = $output;
            return json_encode($output);
        }
    }

    function total_leave_count($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){
        if(isset($_SESSION["leave_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["leave_breakdown"]);
        }else{
            $select_vl_breakdown = "SELECT ui.userid, ui.branch, count(vl.empno) AS total_leave_count FROM hrms.user_info ui
                                    LEFT JOIN hrms.vlform vl ON vl.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND vl.vldatefrom BETWEEN '$cutfrom' AND '$cutto' AND vl.vlstatus = 'approved'
                                    GROUP BY ui.userid ORDER BY total_leave_count DESC LIMIT 5;";
            $query = $HRconnect->query($select_vl_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC);
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            ); 
            
            $_SESSION["leave_breakdown"] = $output; 
            return json_encode($output);
        }
    }

    function total_concern_count($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){
        if(isset($_SESSION["concern_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["concern_breakdown"]);
        }else{
            $select_vl_breakdown = "SELECT ui.userid, ui.branch, count(dc.empno) AS total_concern_count FROM hrms.user_info ui
                                    LEFT JOIN hrms.dtr_concerns dc ON dc.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND dc.filing_date BETWEEN '$cutfrom' AND '$cutto' AND dc.status = 'approved'
                                    GROUP BY ui.userid ORDER BY total_concern_count DESC LIMIT 5;";
            $query = $HRconnect->query($select_vl_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC);
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            ); 
            
            $_SESSION["concern_breakdown"] = $output;
            return json_encode($output);
        }
    }

    function total_change_schedule_count($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){
        if(isset($_SESSION["cs_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["cs_breakdown"]);
        }else{
            $select_vl_breakdown = "SELECT ui.userid, ui.branch, count(cs.empno) AS total_cs_count FROM hrms.user_info ui
                                    LEFT JOIN hrms.change_schedule cs ON cs.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND cs.datefrom BETWEEN '$cutfrom' AND '$cutto' AND cs.cs_status = 'approved'
                                    GROUP BY ui.userid ORDER BY total_cs_count DESC LIMIT 5;";
            $query = $HRconnect->query($select_vl_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC);
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            ); 
            
            $_SESSION["cs_breakdown"] = $output;
            return json_encode($output);
        }
    }

    function total_working_day_off_count($cutfrom, $cutto, $is_refresh, $timestamp, $HRconnect){
        if(isset($_SESSION["wdo_breakdown"]) && $is_refresh == 0){
            return json_encode($_SESSION["wdo_breakdown"]);
        }else{
            $select_vl_breakdown = "SELECT ui.userid, ui.branch, count(wdo.empno) AS total_wdo_count FROM hrms.user_info ui
                                    LEFT JOIN hrms.working_dayoff wdo ON wdo.empno = ui.empno AND ui.empno NOT IN ('9999', '9998')
                                    WHERE ui.userid in (
                                        SELECT DISTINCT(userid) FROM hrms.user_info
                                    ) AND wdo.datefrom BETWEEN '$cutfrom' AND '$cutto' AND wdo.wdostatus = 'approved'
                                    GROUP BY ui.userid ORDER BY total_wdo_count DESC LIMIT 5;";
            $query = $HRconnect->query($select_vl_breakdown);
            $result = $query->fetch_all(MYSQLI_ASSOC);
            $output = array(
                "result" => $result,
                "timestamp" => date("M d, Y g:i A", strtotime($timestamp))

            ); 
            
            $_SESSION["wdo_breakdown"] = $output;
            return json_encode($output);
        }
    }
