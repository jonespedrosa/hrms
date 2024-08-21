<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
$sqlEmpno = "SELECT empno FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sqlEmpno);
$row = $query->fetch_array();
$empnoSession = $row['empno'];

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, send an error response
    echo json_encode(array('status' => 'error', 'message' => 'Failed to connect to database'));
    exit();
}

// Check if the required POST data is received
if (isset($_POST['dateStart'], $_POST['dateEnd'], $_POST['batchNumber'], $_POST['courseId'])) {

    // Sanitize the input to prevent SQL injection (consider using prepared statements)
    $dateStart = mysqli_real_escape_string($HRconnect, $_POST['dateStart']);
    $dateEnd = mysqli_real_escape_string($HRconnect, $_POST['dateEnd']);
    $batchNumber = mysqli_real_escape_string($HRconnect, $_POST['batchNumber']);
    $courseIds = json_encode($_POST['courseId']); // Encode the course IDs array as JSON

    // Perform the database insertion (consider using prepared statements)
    $sql = "INSERT INTO lnd_training_schedule (datefrom, dateto, batch_number, course_id, created_by_id) 
            VALUES ('$dateStart', '$dateEnd', '$batchNumber', '$courseIds', '$empnoSession')";
    if (mysqli_query($HRconnect, $sql)) {
        // Retrieve the last inserted ID
        $lastInsertedId = mysqli_insert_id($HRconnect);

        // Insert dates into lnd_training_batch table
        $dateFrom = new DateTime($dateStart);
        $dateTo = new DateTime($dateEnd);
        // Add 1 day to the end date
        $dateTo->modify('+1 day');
        $interval = new DateInterval('P1D'); // 1 day interval
        $dateRange = new DatePeriod($dateFrom, $interval, $dateTo);

        $dayCount = 1; // Initialize day count

        foreach ($dateRange as $date) {
            // Insert into lnd_training_batch table
            $insertBatchSql = "INSERT INTO lnd_training_batch (day, id, dateFrom) 
                        VALUES ('$dayCount', '$lastInsertedId', '" . $date->format('Y-m-d') . "')";
            mysqli_query($HRconnect, $insertBatchSql);
            $dayCount++; // Increment day count
        }

        // Iterate through selectedEmployees array and construct JSON for each employee
        $employeeData = [];
        foreach ($_POST['selectedEmployees'] as $empno) {
            // Assuming you have additional employee data available (name, department, userid, etc.)
            $employeeJson = [
                'empno' => $empno,
                'isAbsent' => '',
                'name' => '', // Add employee name here
                'department' => '', // Add department here
                'userid' => '', // Add user ID here
                'late' => '',
                'reason' => '', // Add reason here
                'attachment' => '' // Add attachment here
            ];
            // Add employee JSON data to the array
            $employeeData[$empno] = $employeeJson;
        }

        // Encode the employee data array as JSON
        $enrolledEmployeeJson = json_encode($employeeData);

        // Insert into the enrolled employees table
        $enrolledSql = "INSERT INTO lnd_enrolled_employees (training_sched_id, enrolled_emp_data, created_by_id) 
                    VALUES ('$lastInsertedId', '$enrolledEmployeeJson', '$empnoSession')";

        if (mysqli_query($HRconnect, $enrolledSql)) {
            // If insertion is successful, send a success response
            echo json_encode(array('status' => 'success', 'message' => 'Schedule created successfully'));
        } else {
            // If insertion into enrolled employees fails, send an error response
            echo json_encode(array('status' => 'error', 'message' => 'Error creating schedule: ' . mysqli_error($HRconnect)));
        }
    } else {
        // If insertion into training schedule fails, send an error response
        echo json_encode(array('status' => 'error', 'message' => 'Error creating schedule: ' . mysqli_error($HRconnect)));
    }
} else {
    // If required data is not received, send an error response
    echo json_encode(array('status' => 'error', 'message' => 'Missing required data'));
}

// Close database connection
mysqli_close($HRconnect);




























// NOT ALLLOWED DUPLICATE BATCH NUMBER

// <?php
// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// session_start();
// $sqlEmpno = "SELECT empno FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
// $query = $HRconnect->query($sqlEmpno);
// $row = $query->fetch_array();
// $empnoSession = $row['empno'];

// // Check connection
// if (mysqli_connect_errno()) {
//     // If connection fails, send an error response
//     echo json_encode(array('status' => 'error', 'message' => 'Failed to connect to database'));
//     exit();
// }

// // Check if the required POST data is received
// if (isset($_POST['dateStart'], $_POST['dateEnd'], $_POST['batchNumber'], $_POST['courseId'])) {

//     // Sanitize the input to prevent SQL injection (consider using prepared statements)
//     $dateStart = mysqli_real_escape_string($HRconnect, $_POST['dateStart']);
//     $dateEnd = mysqli_real_escape_string($HRconnect, $_POST['dateEnd']);
//     $batchNumber = mysqli_real_escape_string($HRconnect, $_POST['batchNumber']);
//     $courseIds = json_encode($_POST['courseId']); // Encode the course IDs array as JSON

//     // Check if the batch number already exists in the database
//     $existingBatchCheckQuery = "SELECT COUNT(*) AS count FROM lnd_training_schedule WHERE batch_number = '$batchNumber'";
//     $existingBatchCheckResult = mysqli_query($HRconnect, $existingBatchCheckQuery);
//     $existingBatchCheckRow = mysqli_fetch_assoc($existingBatchCheckResult);
//     $existingBatchCount = $existingBatchCheckRow['count'];

//     if ($existingBatchCount > 0) {
//         // Batch number already exists, send an alert message
//         echo json_encode(array('status' => 'error', 'message' => 'Batch number already exists'));
//     } else {
//         // Perform the database insertion (consider using prepared statements)
//         $sql = "INSERT INTO lnd_training_schedule (datefrom, dateto, batch_number, course_id, created_by_id) 
//         VALUES ('$dateStart', '$dateEnd', '$batchNumber', '$courseIds', '$empnoSession')";
//         if (mysqli_query($HRconnect, $sql)) {
//             // Retrieve the last inserted ID
//             $lastInsertedId = mysqli_insert_id($HRconnect);

//             // Insert dates into lnd_training_batch table
//             $dateFrom = new DateTime($dateStart);
//             $dateTo = new DateTime($dateEnd);
//             // Add 1 day to the end date
//             $dateTo->modify('+1 day');
//             $interval = new DateInterval('P1D'); // 1 day interval
//             $dateRange = new DatePeriod($dateFrom, $interval, $dateTo);

//             $dayCount = 1; // Initialize day count

//             foreach ($dateRange as $date) {
//                 // Insert into lnd_training_batch table
//                 $insertBatchSql = "INSERT INTO lnd_training_batch (day, id, dateFrom) 
//                             VALUES ('$dayCount', '$lastInsertedId', '" . $date->format('Y-m-d') . "')";
//                 mysqli_query($HRconnect, $insertBatchSql);
//                 $dayCount++; // Increment day count
//             }

//             // Iterate through selectedEmployees array and construct JSON for each employee
//             $employeeData = [];
//             foreach ($_POST['selectedEmployees'] as $empno) {
//                 // Assuming you have additional employee data available (name, department, userid, etc.)
//                 $employeeJson = [
//                     'empno' => $empno,
//                     'isAbsent' => '',
//                     'name' => '', // Add employee name here
//                     'department' => '', // Add department here
//                     'userid' => '', // Add user ID here
//                     'late' => '',
//                     'reason' => '', // Add reason here
//                     'attachment' => '' // Add attachment here
//                 ];
//                 // Add employee JSON data to the array
//                 $employeeData[$empno] = $employeeJson;
//             }

//             // Encode the employee data array as JSON
//             $enrolledEmployeeJson = json_encode($employeeData);

//             // Insert into the enrolled employees table
//             $enrolledSql = "INSERT INTO lnd_enrolled_employees (training_sched_id, enrolled_emp_data, created_by_id) 
//                         VALUES ('$lastInsertedId', '$enrolledEmployeeJson', '$empnoSession')";

//             if (mysqli_query($HRconnect, $enrolledSql)) {
//                 // If insertion is successful, send a success response
//                 echo json_encode(array('status' => 'success', 'message' => 'Schedule created successfully'));
//             } else {
//                 // If insertion into enrolled employees fails, send an error response
//                 echo json_encode(array('status' => 'error', 'message' => 'Error creating schedule: ' . mysqli_error($HRconnect)));
//             }
//         } else {
//             // If insertion into training schedule fails, send an error response
//             echo json_encode(array('status' => 'error', 'message' => 'Error creating schedule: ' . mysqli_error($HRconnect)));
//         }
//     }
// } else {
//     // If required data is not received, send an error response
//     echo json_encode(array('status' => 'error', 'message' => 'Missing required data'));
// }

// // Close database connection
// mysqli_close($HRconnect);
