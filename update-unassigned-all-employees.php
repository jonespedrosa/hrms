<?php
session_start(); // Start session to access session variables
date_default_timezone_set('Asia/Manila'); // Set timezone

if (isset($_POST['unassigned_employees']) && isset($_POST['pattern_id'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    // Decode the empnos array sent from the AJAX
    $unassignedEmployees = json_decode($_POST['unassigned_employees'], true);
    $patternId = intval($_POST['pattern_id']); // Get pattern_id from POST request

    // Retrieve empno from session
    $updated_by = $_SESSION['empno'];
    $updated_at = date('Y-m-d H:i:s'); // Current timestamp

    // Prepare update query to set pattern_id to NULL (or 0) for unassigned employees
    $userInfoSql = "UPDATE user_info SET pattern_id = NULL WHERE empno = ?";
    $userStmt = $HRconnect->prepare($userInfoSql);

    foreach ($unassignedEmployees as $empno) {
        // Bind only empno for each iteration
        $userStmt->bind_param('i', $empno);
        if (!$userStmt->execute()) {
            echo json_encode(['status' => 'error', 'message' => $userStmt->error]);
            $userStmt->close();
            $HRconnect->close();
            exit();
        }
    }
    $userStmt->close();

    // Clear the assigned_empno_schedule in the pattern_schedule table for the given pattern_id
    $scheduleSql = "UPDATE pattern_schedule
                    SET assigned_empno_schedule = NULL,
                        updated_at = ?,
                        updated_by = ?
                    WHERE pattern_id = ?";

    $scheduleStmt = $HRconnect->prepare($scheduleSql);
    $scheduleStmt->bind_param('ssi', $updated_at, $updated_by, $patternId);

    if ($scheduleStmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Unassigned employees updated and schedule cleared successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $HRconnect->error]);
    }

    // Close the database connection
    $scheduleStmt->close();
    $HRconnect->close();

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
