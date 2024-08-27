<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

$empno = $_GET['empno'];
$concernDate = $_GET['date'];

$sqlTimeInputs = "SELECT empno, datefromto, schedfrom, schedto, break, M_timein, M_timeout, A_timein, A_timeout, timein4, timeout4
                FROM sched_time
                WHERE empno = ? AND datefromto = ?";
$stmtTimeInputs = $HRconnect->prepare($sqlTimeInputs);
$stmtTimeInputs->bind_param("ss", $empno, $concernDate);
$stmtTimeInputs->execute();
$resultTimeInputs = $stmtTimeInputs->get_result();
$timeInputs = $resultTimeInputs->fetch_array(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($timeInputs);

$stmtTimeInputs->close();
$HRconnect->close();
