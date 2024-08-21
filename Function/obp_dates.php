<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    if(isset($_GET['dates'])){
        if($_GET['dates'] == 'getOBP'){
            echo getDates($HRconnect, $_GET['date'], $_GET['empno']);
        }
    }

    function getDates($HRconnect, $date, $empno){
        try{
            $sql_date = "SELECT M_timein, M_timeout, A_timein, A_timeout FROM `hrms`.`sched_time`
                    WHERE empno = ? AND datefromto = ?";
            $query_date = $HRconnect->prepare($sql_date);
            $query_date->bind_param("is", $empno, $date);
            $query_date->execute();
            $result_date = $query_date->get_result()->fetch_array();
            if (empty($result_date)) {
                throw new Exception('No rows found');
            }
            // FOR BREAKS
            $M_timeout_result;
            $A_timein_result;
            if(stripos($result_date["M_timeout"], "No Break") !== false){
                $M_timeout_result = "No Break";
            }else{
                $M_timeout_result = date('H:i', strtotime($result_date['M_timeout']));
            }
            if(stripos($result_date["A_timein"], "No Break") !== false){
                $A_timein_result = "No Break";
            }else{
                $A_timein_result = date('H:i', strtotime($result_date['A_timein']));
            
            }

            $array_date = array(
                "m_timein" => ($result_date['M_timein'])?date('H:i', strtotime($result_date['M_timein'])):null,
                "m_timeout" => ($result_date['M_timeout'])? $M_timeout_result :null,
                "a_timein" => ($result_date['A_timein'])? $A_timein_result :null,
                "a_timeout" => ($result_date['A_timeout'])?date('H:i', strtotime($result_date['A_timeout'])):null
            );
            return json_encode($array_date);
        }catch(Exception $e){
            $array_date = array(
                "m_timein" => null,
                "m_timeout" => null,
                "a_timein" => null,
                "a_timeout" => null
            );
            return json_encode($array_date);
        }
    }
?>