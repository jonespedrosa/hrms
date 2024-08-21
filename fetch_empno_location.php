<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

$empno = $_POST['empno'];
$logdate = $_POST['logdate'];

$sql = "SELECT empno, cafename, type, logdate FROM logs WHERE empno = ? AND logdate = ? ORDER BY type ASC";
$stmt = $HRconnect->prepare($sql);
$stmt->bind_param("ss", $empno, $logdate);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$HRconnect->close();