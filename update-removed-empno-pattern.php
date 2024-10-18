<?php
if (isset($_POST['pattern_id']) && isset($_POST['empno'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    $empno = $HRconnect->real_escape_string($_POST['empno']);
    $pattern_id = $HRconnect->real_escape_string($_POST['pattern_id']);

    // Fetch the existing JSON data from the `pattern_schedule` table
    $selectSql = "SELECT assigned_empno_schedule FROM pattern_schedule WHERE pattern_id = '$pattern_id'";
    $result = $HRconnect->query($selectSql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $assignedEmployees = json_decode($row['assigned_empno_schedule'], true);

        // Remove the employee with the matching empno
        $updatedEmployees = array_filter($assignedEmployees, function ($employee) use ($empno) {
            return $employee['empno'] !== $empno;
        });

        // Re-encode the updated employee list back to JSON
        $updatedJson = json_encode(array_values($updatedEmployees)); // Reset keys

        // Update the `pattern_schedule` table with the new JSON
        $updateSql = "UPDATE pattern_schedule SET assigned_empno_schedule = ? WHERE pattern_id = ?";
        $stmt = $HRconnect->prepare($updateSql);
        $stmt->bind_param('si', $updatedJson, $pattern_id);

        if ($stmt->execute()) {
            // Also update `user_info` to set `pattern_id` to 0 (or the desired default)
            $userInfoSql = "UPDATE user_info SET pattern_id = 0 WHERE empno = ?";
            $userStmt = $HRconnect->prepare($userInfoSql);
            $userStmt->bind_param('i', $empno);

            if ($userStmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Employee removed and pattern_id updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $userStmt->error]);
            }
            $userStmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pattern not found']);
    }

    $HRconnect->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
