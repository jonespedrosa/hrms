<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

$empno = $_GET['empno'];
$concernDate = $_GET['date'];

$sqlTimeInputs = "
            SELECT
                sched_time.empno,
                sched_time.datefromto,
                sched_time.schedfrom,
                sched_time.schedto,
                sched_time.break,
                sched_time.M_timein,
                sched_time.M_timeout,
                sched_time.A_timein,
                sched_time.A_timeout,
                sched_time.timein4,
                sched_time.timeout4,
                overunder.othours,
                overunder.otreason,
                overunder.otstatus,
                overunder.p_approver,
                overunder.approver,
                vlform.vltype,
                vlform.vldatefrom,
                vlform.vlreason,
                vlform.vlstatus,
                vlform.timedate,
                vlform.approver AS vl_approver,
                obp.status AS obp_status  -- Include the status from the obp table
            FROM
                sched_time
            LEFT JOIN
                overunder
            ON
                sched_time.empno = overunder.empno AND sched_time.datefromto = overunder.otdatefrom
            LEFT JOIN
                vlform
            ON
                sched_time.empno = vlform.empno AND sched_time.datefromto = vlform.vldatefrom
            LEFT JOIN
                obp  -- Join the obp table
            ON
                sched_time.empno = obp.empno AND sched_time.datefromto = obp.datefromto
            WHERE
                sched_time.empno = ? AND sched_time.datefromto = ? ORDER BY `vlform`.`timedate` DESC";

$stmtTimeInputs = $HRconnect->prepare($sqlTimeInputs);
$stmtTimeInputs->bind_param("ss", $empno, $concernDate);
$stmtTimeInputs->execute();
$resultTimeInputs = $stmtTimeInputs->get_result();
$timeInputs = $resultTimeInputs->fetch_array(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($timeInputs);

$stmtTimeInputs->close();
$HRconnect->close();
