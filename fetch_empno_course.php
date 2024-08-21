<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Fetch the value of EmpNoId from the URL
$empNoId = $_GET['EmpNoId'];

// Sanitize the input to prevent SQL injection
$empNoId = mysqli_real_escape_string($HRconnect, $empNoId);

// Execute the query to retrieve training batch details
$query_training = "SELECT 
            b.trainees_empno,
            b.datefrom, 
            b.starttime, 
            b.endtime, 
            b.location, 
            b.no_of_courses,
            JSON_LENGTH(JSON_KEYS(b.no_of_courses)) AS no_of_courses_count,
            JSON_LENGTH(b.no_of_topics) AS no_of_topics_count, 
            b.no_of_topics AS list_of_topics,
            b.status,
            s.batch_number,
            s.id AS schedule_id,
            REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') AS course_id
            FROM 
            lnd_training_batch b
            JOIN 
            lnd_training_schedule s ON b.id = s.id
            WHERE JSON_CONTAINS(b.trainees_empno, '\"$empNoId\"', '$');";

$result_training = mysqli_query($HRconnect, $query_training);

if (!$result_training) {
    echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
    exit();
}

// Fetch the training results
$training_data = array();
while ($row = mysqli_fetch_assoc($result_training)) {
    $training_data[] = $row;
}

// Execute the query to retrieve absent trainees details
$query_absent = "SELECT id_absent, empno, datefrom, schedule_id, course_id, isAbsent, reason FROM `lnd_absent_trainees` WHERE empno = '$empNoId'";

$result_absent = mysqli_query($HRconnect, $query_absent);

if (!$result_absent) {
    echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
    exit();
}

// Fetch the absent trainees results
$absent_data = array();
while ($row = mysqli_fetch_assoc($result_absent)) {
    $absent_data[] = $row;
}


// Execute the query to get user_info
$query_user_info = "SELECT userid, empno, name, branch, position FROM `user_info` WHERE empno = '$empNoId'";

$result_user_info = mysqli_query($HRconnect, $query_user_info);

if (!$result_user_info) {
    echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
    exit();
}

// Fetch the user information results
$user_info = array();
while ($row = mysqli_fetch_assoc($result_user_info)) {
    $user_info[] = $row;
}

// Combine all sets of data
$output_data = array(
    'training_data' => $training_data,
    'absent_data' => $absent_data,
    'user_info' => $user_info
);

// Output the results in JSON format
echo json_encode($output_data);

// Close the database connection
mysqli_close($HRconnect);














// Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('error' => 'Failed to connect to database'));
//     exit();
// }

// // Fetch the value of EmpNoId from the URL
// $empNoId = $_GET['EmpNoId'];

// // Sanitize the input to prevent SQL injection
// $empNoId = mysqli_real_escape_string($HRconnect, $empNoId);

// // Execute the query
// $query = "SELECT 
//             b.trainees_empno,
//             b.datefrom, 
//             b.starttime, 
//             b.endtime, 
//             b.location, 
//             b.no_of_courses,
//             JSON_LENGTH(JSON_KEYS(b.no_of_courses)) AS no_of_courses_count,
//             JSON_LENGTH(b.no_of_topics) AS no_of_topics_count, 
//             b.no_of_topics AS list_of_topics,
//             b.status,
//             s.batch_number,
//             s.id AS schedule_id,
//             REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') AS course_id
//             FROM 
//             lnd_training_batch b
//             JOIN 
//             lnd_training_schedule s ON b.id = s.id
//             WHERE JSON_CONTAINS(b.trainees_empno, '\"$empNoId\"', '$');";

// $result = mysqli_query($HRconnect, $query);

// if (!$result) {
//     echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
//     exit();
// }



// // Fetch the results
// $data = array();
// while ($row = mysqli_fetch_assoc($result)) {
//     $data[] = $row;
// }

// // Output the results in JSON format
// echo json_encode($data);

// // Close the database connection
// mysqli_close($HRconnect);
