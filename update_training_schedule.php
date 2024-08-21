<?php
// Increase maximum execution time
ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('success' => false, 'error' => 'Failed to connect to database'));
    exit();
}

// Get the JSON input data
$data = json_decode(file_get_contents('php://input'), true);

// Check if schedules data is provided
if (!isset($data['schedules'])) {
    echo json_encode(array('success' => false, 'error' => 'No schedules data provided'));
    exit();
}

// Prepare the update statement
$updateStmt = $HRconnect->prepare("UPDATE sched_time SET schedfrom = ?, schedto = ? WHERE empno = ? AND datefromto = ?");

$success = true;
$updatedCount = 0;

// Loop through the schedules and update each one
foreach ($data['schedules'] as $schedule) {
    $empno = $schedule['empno'];
    $starttime = $schedule['starttime'];
    $endtime = $schedule['endtime'];
    $datefrom = $schedule['datefrom'];

    // Prepare the datetime format for schedfrom and schedto
    $schedfrom = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $starttime));
    $schedto = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $endtime));

    // Bind parameters and execute the update statement
    $updateStmt->bind_param('ssss', $schedfrom, $schedto, $empno, $datefrom);
    if (!$updateStmt->execute()) {
        // Log the error message or code
        error_log("Update failed for empno $empno: " . $updateStmt->error);
        $success = false;
        break; // Exit the loop if update fails
    } else {
        $updatedCount++;
    }
}

// Close the statement and connection
$updateStmt->close();
mysqli_close($HRconnect);

// Return the result
echo json_encode(array('success' => $success, 'updatedCount' => $updatedCount));










// // Increase maximum execution time
// // ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('success' => false, 'error' => 'Failed to connect to database'));
//     exit();
// }

// // Get the JSON input data
// $data = json_decode(file_get_contents('php://input'), true);

// // Check if schedules data is provided
// if (!isset($data['schedules'])) {
//     echo json_encode(array('success' => false, 'error' => 'No schedules data provided'));
//     exit();
// }

// // Prepare the update statement
// $updateStmt = $HRconnect->prepare("UPDATE sched_time SET schedfrom = ?, schedto = ? WHERE empno = ? AND datefromto = ?");

// $success = true;

// // Loop through the schedules and update each one
// foreach ($data['schedules'] as $schedule) {
//     $empno = $schedule['empno'];
//     $starttime = $schedule['starttime'];
//     $endtime = $schedule['endtime'];
//     $datefrom = $schedule['datefrom'];

//     // Prepare the datetime format for schedfrom and schedto
//     $schedfrom = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $starttime));
//     $schedto = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $endtime));

//     // Bind parameters and execute the update statement
//     $updateStmt->bind_param('ssss', $schedfrom, $schedto, $empno, $datefrom);
//     if (!$updateStmt->execute()) {
//         // Log the error message or code
//        var_dump(("Update failed for empno $empno: " . $updateStmt->error));
//         $success = false;
//         break; // Exit the loop if update fails
//     }
// }

// // Close the statement and connection
// $updateStmt->close();
// mysqli_close($HRconnect);

// // Return the result
// echo json_encode(array('success' => $success));
