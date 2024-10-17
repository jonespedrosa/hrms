<?php
if (isset($_POST['pattern_id']) && isset($_POST['assigned_employees'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    $pattern_id = $_POST['pattern_id'];
    $assignedEmployees = json_decode($_POST['assigned_employees'], true); // Decode JSON string to array

    // Prepare the SQL query to update the pattern_schedule table
    $sql = "UPDATE pattern_schedule SET assigned_empno_schedule = ? WHERE pattern_id = ?";
    $stmt = $HRconnect->prepare($sql);
    $assignedEmployeesJson = json_encode($assignedEmployees); // Convert back to JSON string
    $stmt->bind_param('si', $assignedEmployeesJson, $pattern_id);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        $stmt->close();
        $HRconnect->close();
        exit();
    }

    $stmt->close(); // Close the first statement

    // Now update the pattern_id in the user_info table for each employee
    $userInfoSql = "UPDATE user_info SET pattern_id = ? WHERE empno = ?";
    $userStmt = $HRconnect->prepare($userInfoSql);

    // Loop through each assigned employee and update their pattern_id
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
    $HRconnect->close();

    echo json_encode(['status' => 'success', 'message' => 'Employees assigned and pattern_id updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}












// if (isset($_POST['pattern_id']) && isset($_POST['assigned_employees'])) {
//     // Database connection
//     $HRconnect = new mysqli("localhost", "root", "", "hrms");
//     if ($HRconnect->connect_error) {
//         die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
//     }

//     $pattern_id = $_POST['pattern_id'];
//     $assignedEmployees = $_POST['assigned_employees']; // JSON string

//     // Prepare the SQL query to update the assigned_empno_schedule field
//     $sql = "UPDATE pattern_schedule
//             SET assigned_empno_schedule = ?
//             WHERE pattern_id = ?";

//     $stmt = $HRconnect->prepare($sql);
//     $stmt->bind_param('si', $assignedEmployees, $pattern_id);

//     if ($stmt->execute()) {
//         echo json_encode(['status' => 'success', 'message' => 'Employees assigned successfully']);
//     } else {
//         echo json_encode(['status' => 'error', 'message' => $stmt->error]);
//     }

//     $stmt->close();
//     $HRconnect->close();
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
// }
