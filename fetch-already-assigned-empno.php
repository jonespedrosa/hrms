<?php
$HRconnect = new mysqli("localhost", "root", "", "hrms");

if ($HRconnect->connect_error) {
    die("Connection failed: " . $HRconnect->connect_error);
}

// Get the pattern_id from the AJAX request
$patternId = $_POST['pattern_id'];

$sqlPattern = "SELECT assigned_empno_schedule FROM pattern_schedule WHERE pattern_id = ?";
$stmt = $HRconnect->prepare($sqlPattern);
$stmt->bind_param("i", $patternId);
$stmt->execute();
$result = $stmt->get_result();

$response = [];

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $assignedEmpnoSchedule = json_decode($row['assigned_empno_schedule'], true);
    if ($assignedEmpnoSchedule) {
        $response = $assignedEmpnoSchedule;
    }
}

// Close the database connection
$stmt->close();
$HRconnect->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
