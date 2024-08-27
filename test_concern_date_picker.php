<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();

if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
}

// Ensure $empno is defined
if (isset($_GET['empno'])) {
    $empno = $_GET['empno'];
} else {
    die("Error: Employee number is not provided in the URL.");
}

// Extract the concern date from URL
$concernDate = isset($_GET['date']) ? $_GET['date'] : '';

// NEW QUERY TO GET TIME INPUTS
$sqlTimeInputs = "SELECT empno, datefromto, schedfrom, schedto, break, M_timein, M_timeout, A_timein, A_timeout, timein4, timeout4
                FROM sched_time
                WHERE empno = ? AND datefromto = ?";
$stmtTimeInputs = $HRconnect->prepare($sqlTimeInputs);
$stmtTimeInputs->bind_param("ss", $empno, $concernDate);
$stmtTimeInputs->execute();
$resultTimeInputs = $stmtTimeInputs->get_result();
$timeInputs = $resultTimeInputs->fetch_array(MYSQLI_ASSOC);

// Accessing specific fields
$M_timein = $timeInputs['M_timein'];
$M_breakout = $timeInputs['M_timeout'];
$A_breakin = $timeInputs['A_timein'];
$A_timeout = $timeInputs['A_timeout'];

// Embed PHP variables into JavaScript
echo "<script>
    const M_timein = '$M_timein';
    const M_breakout = '$M_breakout';
    const A_breakin = '$A_breakin';
    const A_timeout = '$A_timeout';
</script>";

// Close the prepared statement and connection
$stmtTimeInputs->close();
$HRconnect->close();
