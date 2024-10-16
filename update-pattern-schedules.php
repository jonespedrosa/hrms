<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection

$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die("Connection failed: " . $HRconnect->connect_error);
    }

    // Get the data from the AJAX request
    $patternId = $_POST['pattern_id'];
    $assignedEmpnoSchedule = $_POST['assigned_empno_schedule']; // JSON format
    $selectedDate = $_POST['start_selected_date'];

    // Prepare the UPDATE query
    $sql = "UPDATE pattern_schedule
            SET assigned_empno_schedule = ?, start_selected_date = ?
            WHERE pattern_id = ?";

    $stmt = $HRconnect->prepare($sql);
    $stmt->bind_param('ssi', $assignedEmpnoSchedule, $selectedDate, $patternId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $HRconnect->close();
}