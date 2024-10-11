<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check if a delete request was made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_pattern_id'])) {
    $pattern_id = $_POST['delete_pattern_id'];

    // Update the status to 'Inactive'
    $updateQuery = "UPDATE pattern_schedule SET status = 'Inactive' WHERE pattern_id = ?";
    $stmt = $HRconnect->prepare($updateQuery);
    $stmt->bind_param("i", $pattern_id); // Assuming pattern_id is an integer
    $stmt->execute();
    $stmt->close();
}

// Fetch pattern schedule data where status is 'Active'
$sqlPatternSchedule = "SELECT pattern_id, sched_name_pattern, sched_type, no_break, time_schedule, status
                    FROM pattern_schedule
                    WHERE status = 'Active'";

$result = $HRconnect->query($sqlPatternSchedule);

$schedules = array();
while ($row = $result->fetch_assoc()) {
    $schedules[] = $row; // Make sure pattern_id is included in each row
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($schedules);







// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Fetch pattern schedule data where status is 'Active'
// $sqlPatternSchedule = "SELECT pattern_id, sched_name_pattern, sched_type, no_break, time_schedule, status
//                     FROM pattern_schedule
//                     WHERE status = 'Active'";

// $result = $HRconnect->query($sqlPatternSchedule);

// $schedules = array();
// while ($row = $result->fetch_assoc()) {
//     $schedules[] = $row; // Make sure pattern_id is included in each row
// }

// // Return the data as JSON
// header('Content-Type: application/json');
// echo json_encode($schedules);
