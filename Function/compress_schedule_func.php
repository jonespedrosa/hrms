<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
if (isset($_GET['sched'])) {
    if ($_GET['sched'] == 'isCompressed') {
        echo isCompressed($_GET["empno"], ($_GET["datefrom"]), ($_GET["dateto"]), $HRconnect);
    } else if ($_GET['sched'] == 'schedTime') {
        echo getSchedTime($_GET["empno"], ($_GET["datefrom"]), ($_GET["dateto"]), $HRconnect);
    } else if ($_GET['sched'] == 'saveTime') {
        echo saveTime($_GET["empno"], ($_GET["datefrom"]), ($_GET["dateto"]), $_GET["breaks"], $_GET["response"], $HRconnect);
    } else if ($_GET['sched'] == 'uncompress') {
        echo uncompressTime($_POST["empno"], ($_POST["datefrom"]), ($_POST["dateto"]), $_POST["breaks"], $HRconnect);
    } else if ($_GET['sched'] == 'getBreaks') {
        echo getBreaks($_GET["empno"], ($_GET["datefrom"]), ($_GET["dateto"]), $HRconnect);
    } else if ($_GET['sched'] == 'timeinputs') {
        echo timeInputsSched($_GET["empno"], ($_GET["datefrom"]), ($_GET["dateto"]), $HRconnect);
    }
}

function isCompressed($empno, $datefrom, $dateto, $HRconnect)
{
    $isCompressed = false;

    $select_sched = "SELECT empno FROM hrms.sched_time WHERE empno = ? AND datefromto BETWEEN ? AND ? AND sched_type = ?";
    $stmt = $HRconnect->prepare($select_sched);
    $cmp = "cmp_sched";
    $stmt->bind_param("isss", $empno, $datefrom, $dateto, $cmp);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_sched = $result->fetch_all(MYSQLI_ASSOC);

    if ($result->num_rows > 0) {
        $isCompressed = true;
    }
    $response = array(
        "isCompressed" => $isCompressed,
    );
    return json_encode($response);
}

function getSchedTime($empno, $datefrom, $dateto, $HRconnect)
{
    $select_sched = "SELECT datefromto, schedfrom, schedto, work_hours, cmp_remarks, remarks FROM hrms.sched_time WHERE empno = ? AND datefromto BETWEEN ? AND ?";
    $stmt = $HRconnect->prepare($select_sched);
    $stmt->bind_param("iss", $empno, $datefrom, $dateto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_sched = $result->fetch_all(MYSQLI_ASSOC);

    $response = array(
        "result" => $row_sched,
    );
    return json_encode($response);
}

function saveTime($empno, $datefrom, $dateto, $breaks, $work_hours, $HRconnect)
{
    // 2 things two update, sched type and sched info
    $sql_select = "SELECT st.datefromto, st.work_hours, st.sched_type, st.schedfrom, st.schedto, ui.eligible_work_premium, ui.allowed_nobreak
        FROM `hrms`.`sched_time`st LEFT JOIN `hrms`.`user_info` ui ON ui.empno = st.empno 
        WHERE ui.empno = ? AND st.datefromto BETWEEN ? AND ?";
    $stmt = $HRconnect->prepare($sql_select);
    $stmt->bind_param("iss", $empno, $datefrom, $dateto);
    $stmt->execute();
    $result = $stmt->get_result();

    $counter = 0;
    $cmp = "cmp_sched";
    $arr_remarks = array(
        "" => "",
        "AB" => "AB",
        "RD" => "RD",
        "NWD" => "NWD",
        "LWP" => "LWP",
        "ML" => "ML",
        "PL" => "PL",
        "SPL" => "SPL",
    );


    while ($row = $result->fetch_assoc()) {

        $no_break = 1;
        if ($row["allowed_nobreak"] == 1) {
            $no_break = 0;
        }
        $numeric_time = intval($work_hours[$counter]) + $no_break /*intval($breaks[$counter])/*intval($row["eligible_work_premium"])*/;
        if (!($numeric_time <= 1)) {
            $initial_time = strtotime('+' . $numeric_time . ' hours', strtotime($row["schedfrom"]));

            $updated_date = date("Y-m-d", strtotime($row["schedto"])) . " " . date("H:i", $initial_time);

            if (strtotime($row["schedfrom"]) > strtotime($updated_date)) {
                // increment 1 day
                $updated_date = date("Y-m-d H:i", strtotime("+1 day", strtotime($updated_date)));
            }
        } else {
            if ($work_hours[$counter] == "AB" || $work_hours[$counter] == "LWP" || $work_hours[$counter] == "ML" || $work_hours[$counter] == "PL" || $work_hours[$counter] == "SPL") {
                $updated_date = $row["schedto"];
            } else {
                $initial_time = strtotime('+' . (8 + $no_break /*intval($breaks[$counter])*/) . ' hours', strtotime($row["schedfrom"]));
                $updated_date = date("Y-m-d", strtotime($row["schedto"])) . " " . date("H:i", $initial_time);
            }
            // var_dump($updated_date);
        }
        if ($row["work_hours"] != $work_hours[$counter]) {
            $work_hours_up = $work_hours[$counter];
            $break_time = $no_break /*$row["eligible_work_premium"]*/;
            $datefromto = $row['datefromto'];
            $update_sql = "UPDATE `hrms`.`sched_time` SET work_hours = ?, sched_type = ?, schedto = ?, break = ?";

            if (array_key_exists($work_hours_up, $arr_remarks)) {
                $update_sql .= ", remarks = ?";
            } else {
                $update_sql .= ", remarks = ?";
            }

            $update_sql .= " WHERE empno = ? AND datefromto = ?";
            $stmt = $HRconnect->prepare($update_sql);

            if (array_key_exists($work_hours_up, $arr_remarks)) {
                $stmt->bind_param("sssisis", $work_hours_up, $cmp, $updated_date, $break_time, $work_hours_up, $empno, $datefromto);
            } else {
                $null = null;
                $stmt->bind_param("sssisis", $work_hours_up, $cmp, $updated_date, $break_time, $null, $empno, $datefromto);
            }

            $stmt->execute();
            $stmt->close();
        }
        $counter++;
    }
    $update_sql = "UPDATE `hrms`.`sched_info` SET sched_type = ? WHERE empno = ? AND datefrom = ? AND dateto = ?";
    $stmt = $HRconnect->prepare($update_sql);
    $stmt->bind_param("siss", $cmp, $empno, $datefrom, $dateto);
    $stmt->execute();
    $stmt->close();

    $response = array(
        "break" => $breaks,
        "text" => "Schedule Compressed",
        "status" => "success"
    );
    return json_encode($response);
}

function uncompressTime($empno, $datefrom, $dateto, $breaks, $HRconnect)
{
    // 2 things two update, sched type and sched info
    $sql_select = "SELECT datefromto, work_hours, sched_type, schedfrom, schedto FROM `hrms`.`sched_time` WHERE empno = ? AND datefromto BETWEEN ? AND ?";
    $stmt = $HRconnect->prepare($sql_select);
    $stmt->bind_param("iss", $empno, $datefrom, $dateto);
    $stmt->execute();
    $result = $stmt->get_result();

    $counter = 0;
    $cmp = null;
    $work_hours = null;

    while ($row = $result->fetch_assoc()) {
        $initial_time = strtotime('+' . (8 + intval($breaks[$counter])) . ' hours', strtotime($row["schedfrom"]));
        $updated_date = date("Y-m-d", strtotime($row["schedto"])) . " " . date("H:i", $initial_time);

        $update_sql = "UPDATE `hrms`.`sched_time` SET work_hours = ?, sched_type = ?, schedto = ?  WHERE empno = ? AND datefromto = ?";
        $stmt = $HRconnect->prepare($update_sql);
        $stmt->bind_param("sssis", $work_hours, $cmp, $updated_date, $empno, $row['datefromto']);
        $stmt->execute();
        $stmt->close();
        $counter++;
    }
    $update_sql = "UPDATE `hrms`.`sched_info` SET sched_type = ? WHERE empno = ? AND datefrom = ? AND dateto = ?";
    $stmt = $HRconnect->prepare($update_sql);
    $stmt->bind_param("siss", $cmp, $empno, $datefrom, $dateto);
    $stmt->execute();
    $stmt->close();
    $response = array(
        "text" => "Schedule Uncompressed",
        "status" => "success"
    );
    return json_encode($response);
}

function getBreaks($empno, $datefrom, $dateto, $HRconnect)
{
    $select_sched = "SELECT break FROM hrms.sched_time WHERE empno = ? AND datefromto BETWEEN ? AND ?";
    $stmt = $HRconnect->prepare($select_sched);
    $stmt->bind_param("iss", $empno, $datefrom, $dateto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_sched = $result->fetch_all(MYSQLI_ASSOC);

    $response = array(
        "result" => $row_sched,
    );
    return json_encode($response);
}

function timeInputsSched($empno, $datefrom, $dateto, $HRconnect)
{
    $select_sched = "SELECT datefromto, schedfrom, schedto, M_timein, M_timeout, A_timein, A_timeout, break, work_hours FROM hrms.sched_time WHERE empno = ? AND datefromto BETWEEN ? AND ?";
    $stmt = $HRconnect->prepare($select_sched);
    $stmt->bind_param("iss", $empno, $datefrom, $dateto);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_sched = $result->fetch_all(MYSQLI_ASSOC);

    $response = array(
        "result" => $row_sched,
    );
    return json_encode($response);
}
