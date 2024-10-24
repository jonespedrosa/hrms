<?php
session_start();  // Start session to access session variables
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Set timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if deletion is allowed
    if (isset($_POST['check_pattern_id'])) {
        $pattern_id = $_POST['check_pattern_id'];

        $checkQuery = "SELECT assigned_empno_schedule FROM pattern_schedule WHERE pattern_id = ?";
        $stmt = $HRconnect->prepare($checkQuery);
        $stmt->bind_param("i", $pattern_id);
        $stmt->execute();
        $stmt->bind_result($assigned_empno_schedule);
        $stmt->fetch();
        $stmt->close();

        $assignedData = json_decode($assigned_empno_schedule, true);

        // Check if it's null, an empty string, or an empty array
        if (is_null($assigned_empno_schedule) ||
            $assigned_empno_schedule === '' ||
            (is_array($assignedData) && count($assignedData) === 0)) {
            echo json_encode(['canDelete' => true]); // Can delete
        } else {
            echo json_encode(['canDelete' => false]); // Cannot delete
        }
        exit();
    }

    // Handle the actual deletion if allowed
    if (isset($_POST['delete_pattern_id'])) {
        $pattern_id = $_POST['delete_pattern_id'];
        $empno = $_SESSION['empno'];  // Retrieve empno from session
        $currentTime = date('Y-m-d H:i:s');  // Get current timestamp in Asia/Manila timezone

        $updateQuery = "
            UPDATE pattern_schedule
            SET status = 'Inactive', updated_by = ?, updated_at = ?
            WHERE pattern_id = ?";
        $stmt = $HRconnect->prepare($updateQuery);
        $stmt->bind_param("isi", $empno, $currentTime, $pattern_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch active pattern schedules
$sqlPatternSchedule = "SELECT pattern_id, userid, sched_name_pattern, sched_type, no_break, time_schedule, status
                    FROM pattern_schedule
                    WHERE status = 'Active'";
$result = $HRconnect->query($sqlPatternSchedule);

$schedules = [];
while ($row = $result->fetch_assoc()) {
    $schedules[] = $row;
}

header('Content-Type: application/json');
echo json_encode($schedules);
