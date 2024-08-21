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
$courseId = mysqli_real_escape_string($HRconnect, $_GET['courseId']);
$scheduleId = mysqli_real_escape_string($HRconnect, $_GET['scheduleId']);

// Sanitize the input to prevent SQL injection
$empNoId = mysqli_real_escape_string($HRconnect, $empNoId);

// Execute the query to fetch employee details
$query_employee = "SELECT lnd_training_batch.id_main, lnd_training_batch.id, lnd_training_batch.day, lnd_training_batch.trainees_empno, lnd_training_batch.datefrom, lnd_training_batch.location, lnd_training_batch.starttime, lnd_training_batch.endtime, lnd_training_batch.no_of_courses, lnd_training_batch.no_of_topics, lnd_training_batch.status, 
lnd_training_schedule.dateto, lnd_training_schedule.batch_number, JSON_UNQUOTE(JSON_EXTRACT(lnd_training_schedule.course_id, '$[0]')) AS course_id, lnd_training_schedule.status 
FROM lnd_training_batch 
INNER JOIN lnd_training_schedule ON lnd_training_batch.id = lnd_training_schedule.id AND lnd_training_batch.datefrom = lnd_training_schedule.datefrom 
WHERE JSON_CONTAINS(lnd_training_batch.trainees_empno, '\"$empNoId\"');";

$result_employee = mysqli_query($HRconnect, $query_employee);

// Fetch the results
$employee_data = array();
if (!$result_employee) {
    $error = mysqli_error($HRconnect);
} else {
    while ($row_employee = mysqli_fetch_assoc($result_employee)) {
        // Parse the JSON string for 'no_of_topics' if present
        if (isset($row_employee['no_of_topics'])) {
            $row_employee['no_of_topics'] = json_decode($row_employee['no_of_topics'], true);
        }
        $employee_data[] = $row_employee;
    }
}

// Fetch the value of CourseName from the URL
$courseName = $_GET['CourseName'];

// Sanitize the input to prevent SQL injection
$courseName = mysqli_real_escape_string($HRconnect, $courseName);
// Extract course_id from the employee data
// $courseId = $employee_data[0]['course_id'];

// Execute the query to fetch employee details from user_info and lnd_enrolled_dept tables
$query_fetch_details = "SELECT DISTINCT ui.userid, ui.branch, le.course_id, tc.name, tc.description
                    FROM user_info ui
                    JOIN lnd_enrolled_dept le ON ui.userid = le.userid
                    JOIN lnd_training_courses tc ON le.course_id = tc.id
                    WHERE le.course_id IN (
                        SELECT DISTINCT JSON_UNQUOTE(JSON_EXTRACT(lnd_training_schedule.course_id, '$[0]'))
                        FROM lnd_training_batch 
                        INNER JOIN lnd_training_schedule ON lnd_training_batch.id = lnd_training_schedule.id AND lnd_training_batch.datefrom = lnd_training_schedule.datefrom 
                        WHERE JSON_CONTAINS(lnd_training_batch.trainees_empno, '\"$empNoId\"')
                    ) AND le.course_id = '$courseId';";

$result_details = mysqli_query($HRconnect, $query_fetch_details);

// Fetch the results
$details_data = array();
if (!$result_details) {
    $error = isset($error) ? $error . ', ' . mysqli_error($HRconnect) : mysqli_error($HRconnect);
} else {
    while ($row_details = mysqli_fetch_assoc($result_details)) {
        $details_data[] = $row_details;
    }
}

// Fetch the value of $id from the URL
$id = $employee_data[0]['id'];

// Sanitize the input to prevent SQL injection
$id = mysqli_real_escape_string($HRconnect, $id);

// Execute the query to fetch the required data
// $query = "SELECT 
//             lnd_training_batch.id_main, 
//             lnd_training_batch.id, 
//             lnd_training_batch.day, 
//             lnd_training_batch.trainees_empno,
//             lnd_training_batch.datefrom, 
//             lnd_training_batch.location,
//             lnd_training_batch.starttime,
//             lnd_training_batch.endtime,
//             lnd_training_batch.no_of_courses,
//             lnd_training_batch.no_of_topics,
//             lnd_training_batch.status
//             FROM 
//             lnd_training_batch
//             LEFT JOIN 
//             lnd_absent_trainees 
//             ON 
//             lnd_training_batch.id = lnd_absent_trainees.schedule_id 
//             AND 
//             lnd_training_batch.datefrom = lnd_absent_trainees.datefrom
//             WHERE 
//             lnd_training_batch.id = '$scheduleId'  
//             ORDER BY `lnd_training_batch`.`datefrom` ASC;";

// Execute the query to fetch the required data
$query = "SELECT id_main, id, day, trainees_empno, datefrom, location, starttime, endtime, no_of_courses, no_of_topics, status FROM `lnd_training_batch` where `id` = '$scheduleId';";

$result = mysqli_query($HRconnect, $query);

// Fetch the results
$batch_data = array();
if (!$result) {
    $error = mysqli_error($HRconnect);
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $batch_data[] = $row;
    }
}

// Fetch the value of $id from the URL
$id = $batch_data[0]['id']; // Using $batch_data instead of $employee_data

// Sanitize the input to prevent SQL injection
$id = mysqli_real_escape_string($HRconnect, $id);

// Fetch datefrom values from batch_data array
$datefrom_values = array();
foreach ($batch_data as $data) { // Loop through batch_data instead of per_day_data
    $datefrom_values[] = "'" . mysqli_real_escape_string($HRconnect, $data['datefrom']) . "'";
}

$datefrom_list = implode(',', $datefrom_values);

// Execute the query to fetch the required data
$query = "SELECT 
            st.userid, 
            st.empno, 
            st.datefromto, 
            st.schedfrom, 
            st.schedto, 
            st.M_timein, 
            st.M_timeout, 
            st.A_timein, 
            st.A_timeout,
            lat.schedule_id,
            lat.isAbsent,
            lat.reason,
            lat.attachment
        FROM 
            `sched_time` st
        LEFT JOIN 
            `lnd_absent_trainees` lat 
        ON 
            st.empno = lat.empno AND st.datefromto = lat.datefrom
        WHERE 
            st.empno IN ($empNoId) AND 
            st.datefromto IN ($datefrom_list);";

// $query = "SELECT userid, empno, datefromto, schedfrom, schedto, M_timein, M_timeout, A_timein, A_timeout FROM `sched_time` WHERE `empno` IN ($empNoId) AND `datefromto` IN ($datefrom_list)";

$result = mysqli_query($HRconnect, $query);

// Fetch the results
$timeinputs_data = array();
if (!$result) {
    $error = mysqli_error($HRconnect);
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $timeinputs_data[] = $row;
    }
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


// Close the database connection
mysqli_close($HRconnect);

// Output the results in JSON format
if (isset($error)) {
    echo json_encode(array('error' => 'Query failed: ' . $error));
} else {
    $combined_data = array(
        'employee_data' => $employee_data,
        'details_data' => $details_data,
        'per_day_data' => $batch_data,
        'timeinputs_data' => $timeinputs_data,
        'user_info' => $user_info
    );
    echo json_encode($combined_data);
}
