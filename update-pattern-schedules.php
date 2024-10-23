<?php

session_start(); // Make sure session is started to access session variables
date_default_timezone_set('Asia/Manila');


if (isset($_POST['pattern_id']) && isset($_POST['assigned_employees'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    $pattern_id = $_POST['pattern_id'];
    $assignedEmployees = json_decode($_POST['assigned_employees'], true);

    // Retrieve empno from session
    $updated_by = $_SESSION['empno'];
    $updated_at = date('Y-m-d H:i:s'); // Current timestamp in Asia/Manila timezone

    // Set assigned_empno_schedule to NULL if no employees are assigned
    $assignedEmployeesJson = empty($assignedEmployees) ? NULL : json_encode($assignedEmployees);

    // Update pattern_schedule with updated_at and updated_by
    $sql = "UPDATE pattern_schedule
            SET assigned_empno_schedule = ?, updated_at = ?, updated_by = ?
            WHERE pattern_id = ?";
    $stmt = $HRconnect->prepare($sql);
    $stmt->bind_param('ssii', $assignedEmployeesJson, $updated_at, $updated_by, $pattern_id);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        $stmt->close();
        $HRconnect->close();
        exit();
    }

    $stmt->close();

    // If employees were assigned, update the user_info table
    if (!empty($assignedEmployees)) {
        $userInfoSql = "UPDATE user_info SET pattern_id = ? WHERE empno = ?";
        $userStmt = $HRconnect->prepare($userInfoSql);

        foreach ($assignedEmployees as $employee) {
            $empno = $employee['empno'];
            $userStmt->bind_param('si', $pattern_id, $empno);
            if (!$userStmt->execute()) {
                echo json_encode(['status' => 'error', 'message' => $userStmt->error]);
                $userStmt->close();
                $HRconnect->close();
                exit();
            }
        }
        $userStmt->close();
    }

    $HRconnect->close();
    echo json_encode(['status' => 'success', 'message' => 'Employees assigned and pattern_id updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}

