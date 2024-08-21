<?php

// THIS QUERY IS ONLY UPDATE OF lnd_training_batch and sched_time when button switch is enabled.

// Increase maximum execution time
ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

session_start();

$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = mysqli_real_escape_string($HRconnect, $_POST['inputLocation']);
    $facilitatorNames = isset($_POST['facilitatorNames']) ? json_decode($_POST['facilitatorNames'], true) : []; // Decode JSON string to associative array or use empty array if not set
    $startTime = mysqli_real_escape_string($HRconnect, $_POST['startTime']);
    $endTime = mysqli_real_escape_string($HRconnect, $_POST['endTime']);
    $day = mysqli_real_escape_string($HRconnect, $_POST['day']);
    $datefrom = mysqli_real_escape_string($HRconnect, $_POST['datefrom']);
    $schedule_id = isset($_GET['id']) ? $_GET['id'] : null;
    $timeScheduleSwitch = isset($_POST['timeScheduleSwitch']) ? $_POST['timeScheduleSwitch'] : false; // Get the switch state


    if ($schedule_id === null) {
        echo json_encode(array('error' => 'ID parameter is missing'));
        exit();
    }

    $enrolled_emp_data = json_encode($_POST['enrolled_emp_data']);
    $checkedValues = $_POST['checkedValues'];
    $courseNamesMap = json_decode($_POST['courseNamesMap'], true);
    $empno = isset($_SESSION['empno']) ? $_SESSION['empno'] : null;

    if ($empno === null) {
        echo json_encode(array('error' => 'User is not logged in'));
        exit();
    }

    // Prepare facilitator names for SQL
    $name_facilitator = [];
    foreach ($facilitatorNames as $facilitator) {
        $name_facilitator[] = [
            'name_facilitator' => $facilitator['name_facilitator']
        ];
    }
    $name_facilitator_json = json_encode($name_facilitator);

    // Update SQL query
    $sql = "UPDATE lnd_training_batch 
        SET location = '$location', 
            starttime = '$startTime', 
            endtime = '$endTime', 
            name_facilitator = '" . mysqli_real_escape_string($HRconnect, $name_facilitator_json) . "', 
            trainees_empno = '$enrolled_emp_data', 
            no_of_courses = '" . mysqli_real_escape_string($HRconnect, json_encode($courseNamesMap)) . "', 
            no_of_topics = '" . mysqli_real_escape_string($HRconnect, json_encode($checkedValues)) . "',
            updated_by_id = '$empno',
            updated_at = NOW()
        WHERE id = '$schedule_id' AND day = '$day'";

    // Only execute the update query if the switch is enabled
    if ($timeScheduleSwitch === 'true') { // Assuming 'true' is the value when the switch is checked
        if (mysqli_query($HRconnect, $sql)) {
            // Decode the enrolled_emp_data JSON string back into an array
            $enrolled_emp_data_array = json_decode($enrolled_emp_data, true);

            // Prepare the datetime format for schedfrom and schedto
            $schedfrom = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $startTime));
            $schedto = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $endTime));

            // Update sched_time table for each empno in enrolled_emp_data
            foreach ($enrolled_emp_data_array as $empno) {
                $sql_sched_time_update = "UPDATE sched_time 
                SET schedfrom = '$schedfrom', 
                    schedto = '$schedto'
                WHERE empno = '$empno' AND datefromto = '$datefrom'";

                if (!mysqli_query($HRconnect, $sql_sched_time_update)) {
                    echo json_encode(array('error' => 'Error updating sched_time for empno ' . $empno . ': ' . mysqli_error($HRconnect)));
                    exit();
                }
            }
            echo json_encode(array('success' => 'Data updated successfully'));
            exit();
        } else {
            echo json_encode(array('error' => 'Error updating lnd_training_batch: ' . mysqli_error($HRconnect)));
            exit();
        }
    } else {
        
        echo json_encode(array('error' => 'Switch is disabled. No updates performed.'));
        exit();
    }
} else {
    echo json_encode(array('error' => 'No data submitted'));
}

mysqli_close($HRconnect);












// // Increase maximum execution time
// ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

// session_start();

// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (mysqli_connect_errno()) {
// echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
// exit();
// }

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
// $location = mysqli_real_escape_string($HRconnect, $_POST['inputLocation']);
// $facilitatorNames = isset($_POST['facilitatorNames']) ? json_decode($_POST['facilitatorNames'], true) : []; // Decode JSON string to associative array or use empty array if not set
// $startTime = mysqli_real_escape_string($HRconnect, $_POST['startTime']);
// $endTime = mysqli_real_escape_string($HRconnect, $_POST['endTime']);
// $day = mysqli_real_escape_string($HRconnect, $_POST['day']);
// $datefrom = mysqli_real_escape_string($HRconnect, $_POST['datefrom']);
// $schedule_id = isset($_GET['id']) ? $_GET['id'] : null;

// if ($schedule_id === null) {
// echo json_encode(array('error' => 'ID parameter is missing'));
// exit();
// }

// $enrolled_emp_data = json_encode($_POST['enrolled_emp_data']);
// $checkedValues = $_POST['checkedValues'];
// $courseNamesMap = json_decode($_POST['courseNamesMap'], true);
// $empno = isset($_SESSION['empno']) ? $_SESSION['empno'] : null;

// if ($empno === null) {
// echo json_encode(array('error' => 'User is not logged in'));
// exit();
// }

// // Prepare facilitator names for SQL
// $name_facilitator = [];
// foreach ($facilitatorNames as $facilitator) {
// $name_facilitator[] = [
// 'name_facilitator' => $facilitator['name_facilitator']
// ];
// }
// $name_facilitator_json = json_encode($name_facilitator);

// // Update SQL query
// $sql = "UPDATE lnd_training_batch
// SET location = '$location',
// starttime = '$startTime',
// endtime = '$endTime',
// name_facilitator = '" . mysqli_real_escape_string($HRconnect, $name_facilitator_json) . "',
// trainees_empno = '$enrolled_emp_data',
// no_of_courses = '" . mysqli_real_escape_string($HRconnect, json_encode($courseNamesMap)) . "',
// no_of_topics = '" . mysqli_real_escape_string($HRconnect, json_encode($checkedValues)) . "',
// updated_by_id = '$empno',
// updated_at = NOW()
// WHERE id = '$schedule_id' AND day = '$day'";


// // This query it will be run when then Switch button is Enabled
// if (mysqli_query($HRconnect, $sql)) {
// // Decode the enrolled_emp_data JSON string back into an array
// $enrolled_emp_data_array = json_decode($enrolled_emp_data, true);

// // Prepare the datetime format for schedfrom and schedto
// $schedfrom = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $startTime));
// $schedto = date('Y-m-d H:i:s', strtotime($datefrom . ' ' . $endTime));

// // Update sched_time table for each empno in enrolled_emp_data
// foreach ($enrolled_emp_data_array as $empno) {
// $sql_sched_time_update = "UPDATE sched_time
// SET schedfrom = '$schedfrom',
// schedto = '$schedto'
// WHERE empno = '$empno' AND datefromto = '$datefrom'";

// if (!mysqli_query($HRconnect, $sql_sched_time_update)) {
// echo json_encode(array('error' => 'Error updating sched_time for empno ' . $empno . ': ' . mysqli_error($HRconnect)));
// exit();
// }
// }
// echo json_encode(array('success' => 'Data updated successfully'));
// exit();
// } else {
// echo json_encode(array('error' => 'Error updating lnd_training_batch: ' . mysqli_error($HRconnect)));
// }
// } else {
// echo json_encode(array('error' => 'No data submitted'));
// }

// mysqli_close($HRconnect);