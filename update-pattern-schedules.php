<?php
if (isset($_POST['pattern_id']) && isset($_POST['assigned_employees'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    $pattern_id = $_POST['pattern_id'];
    $assignedEmployees = $_POST['assigned_employees']; // JSON string

    // Prepare the SQL query to update the assigned_empno_schedule field
    $sql = "UPDATE pattern_schedule
            SET assigned_empno_schedule = ?
            WHERE pattern_id = ?";

    $stmt = $HRconnect->prepare($sql);
    $stmt->bind_param('si', $assignedEmployees, $pattern_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Employees assigned successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $HRconnect->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
