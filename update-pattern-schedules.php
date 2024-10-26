<?php
session_start(); // Start session to access session variables
date_default_timezone_set('Asia/Manila'); // Set timezone

// Set script execution time limit to 120 seconds (2 minutes)
// set_time_limit(120);

if (isset($_POST['pattern_id']) && isset($_POST['assigned_employees'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    $pattern_id = $_POST['pattern_id'];
    $assignedEmployees = json_decode($_POST['assigned_employees'], true);
    $startDate = $_POST['start_date'] ?? null;
    $endDate = $_POST['end_date'] ?? null;

    // Retrieve empno from session
    $updated_by = $_SESSION['empno'];
    $updated_at = date('Y-m-d H:i:s'); // Current timestamp

    // Set assigned_empno_schedule to NULL if no employees are assigned
    $assignedEmployeesJson = empty($assignedEmployees) ? null : json_encode($assignedEmployees);

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

    // Update user_info with pattern_id for each assigned employee
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

    // Only run sched_time update if $startDate is not null
    if (!empty($startDate)) {
        foreach ($assignedEmployees as $employee) {
            $empno = $employee['empno'];

            // Retrieve time_schedule from pattern_schedule
            $patternQuery = "SELECT time_schedule FROM pattern_schedule WHERE pattern_id = ?";
            $patternStmt = $HRconnect->prepare($patternQuery);
            $patternStmt->bind_param('i', $pattern_id);
            $patternStmt->execute();
            $patternResult = $patternStmt->get_result()->fetch_assoc();
            $timeSchedule = json_decode($patternResult['time_schedule'], true);
            $patternStmt->close();

            // Prepare the SQL query to select sched_time
            $schedTimeQuery = "
            SELECT st.userid, st.empno, st.datefromto, st.schedfrom, st.schedto, st.remarks
            FROM sched_time st
            LEFT JOIN change_schedule cs
                ON st.empno = cs.empno AND st.datefromto = cs.datefrom AND cs.cs_status = 'approved'
            WHERE st.empno = ?
                AND st.datefromto BETWEEN ? AND ?
                AND cs.datefrom IS NULL
            ORDER BY st.schedfrom ASC";

            $schedStmt = $HRconnect->prepare($schedTimeQuery);
            $schedStmt->bind_param('iss', $empno, $startDate, $endDate);
            $schedStmt->execute();
            $schedResult = $schedStmt->get_result();

            while ($row = $schedResult->fetch_assoc()) {
                $dayOfWeek = strtolower(date('l', strtotime($row['datefromto']))); // e.g., "monday"

                if (isset($timeSchedule[$dayOfWeek])) {
                    $fromTime = $timeSchedule[$dayOfWeek]['from']; // e.g., "06:00"
                    $toTime = $timeSchedule[$dayOfWeek]['to'];     // e.g., "15:00"

                    // Initialize remarks
                    $remarks = $row['remarks'];

                    //  Check if remarks should be set to RD or NWD
                    if ($fromTime === "RD" || $toTime === "RD") {
                        $remarks = "RD";
                        $newSchedFrom = $row['schedfrom'];
                        $newSchedTo = $row['schedto'];
                    } elseif ($fromTime === "NWD" || $toTime === "NWD") {
                        $remarks = "NWD";
                        $newSchedFrom = $row['schedfrom'];
                        $newSchedTo = $row['schedto'];
                    } else {
                        $newSchedFrom = date('Y-m-d', strtotime($row['datefromto'])) . " $fromTime:00";
                        $newSchedTo = date('Y-m-d', strtotime($row['datefromto'])) . " $toTime:00";

                        // Check if fromTime is greater than toTime
                        if (strtotime($fromTime) > strtotime($toTime)) {
                            // Increment newSchedTo by one day
                            $newSchedTo = date('Y-m-d', strtotime($row['datefromto'] . ' +1 day')) . " $toTime:00";
                        }
                    }

                    // Update sched_time with new times and remarks
                    $updateSchedQuery = "UPDATE sched_time
                    SET schedfrom = ?, schedto = ?, remarks = ?
                    WHERE empno = ? AND datefromto = ?";

                    $updateStmt = $HRconnect->prepare($updateSchedQuery);
                    $updateStmt->bind_param('sssis', $newSchedFrom, $newSchedTo, $remarks, $empno, $row['datefromto']);


                    if (!$updateStmt->execute()) {
                        echo json_encode(['status' => 'error', 'message' => $updateStmt->error]);
                        $updateStmt->close();
                        $HRconnect->close();
                        exit();
                    }

                    $updateStmt->close();
                }
            }
            $schedStmt->close();
        }
    }

    // $HRconnect->close();
    echo json_encode(['status' => 'success', 'message' => 'Employees assigned, schedules updated, and remarks set successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
