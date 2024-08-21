<?php
// Increase maximum execution time
ini_set('max_execution_time', 300); // 300 seconds = 5 minutes

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}
// Get the value of 'id' from the URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';
$scheduleId = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : '';
$datefrom = isset($_GET['datefrom']) ? $_GET['datefrom'] : '';

// Sanitize input to prevent SQL injection
$id = mysqli_real_escape_string($HRconnect, $id);
$scheduleId = mysqli_real_escape_string($HRconnect, $scheduleId);
$datefrom = mysqli_real_escape_string($HRconnect, $datefrom);

// Echo the parameters to check their values
// echo "id: " . $id . "<br>";
// echo "scheduleId: " . $scheduleId . "<br>";
// echo "datefrom: " . $datefrom . "<br>";

// Prepare the query for fetching batch numbers
$queryBatchNumber = "SELECT 
                    x.*,
                    y.batch_number,
                    b.starttime,
                    b.endtime,
                    b.datefrom
                    FROM (
                    SELECT 
                        t.schedule_id,
                        REPLACE(REPLACE(REPLACE(t.course_id, '[', ''), ']', ''), '\"', '') AS course_id,
                        t.course_name_topics,
                        t.course_name_courses,
                        e.enrolled_emp_data
                    FROM (
                        SELECT 
                            s.id AS schedule_id,
                            REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') AS course_id,
                            t.name AS course_name_topics,
                            c.name AS course_name_courses
                        FROM 
                            lnd_training_batch b
                        JOIN 
                            lnd_training_schedule s ON b.id = s.id
                        LEFT JOIN 
                            lnd_course_topics t ON REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') = t.course_id
                        LEFT JOIN 
                            lnd_training_courses c ON REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') = c.id
                    ) AS t
                    JOIN 
                        lnd_enrolled_employees e ON t.schedule_id = e.training_sched_id
                    ) AS x
                    JOIN
                    lnd_training_schedule y ON x.schedule_id = y.id
                    JOIN
                    lnd_training_batch b ON y.id = b.id
                    WHERE 
                        y.batch_number = '$id' AND b.datefrom = '$datefrom' AND schedule_id = '$scheduleId' LIMIT 1";

                        
// Execute the query for fetching batch numbers
$connectionQueryBatch = $HRconnect->query($queryBatchNumber);

// Check if the query executed successfully
if ($connectionQueryBatch) {
    $empDataArray = array(); // Initialize an array to store employee data

    // Fetch all rows as an associative array
    while ($queryBatch = $connectionQueryBatch->fetch_assoc()) {
        // Decode the enrolled_emp_data JSON string
        $enrolled_emp_data = json_decode($queryBatch['enrolled_emp_data'], true);

        // Iterate through each entry in enrolled_emp_data
        foreach ($enrolled_emp_data as $empno => $emp_data) {

            // Fetch additional information for the current empno from user_info table
            // $additionalInfoQuery = "SELECT ui.userid, ui.empno, ui.name, ui.mothercafe, ui.branch, ui.department, et.isExclude, et.datefrom AS empno_dateExclude
            //                         FROM user_info ui
            //                         LEFT JOIN lnd_excluded_trainees et ON ui.empno = et.empno
            //                         WHERE ui.empno = '$empno'";

            $additionalInfoQuery = "SELECT ui.userid, ui.empno, ui.name, ui.mothercafe, ui.branch, ui.department, et.isExclude, et.datefrom AS empno_dateExclude,     
                                    TIME_FORMAT(st.schedfrom, '%H:%i') AS schedfrom, 
                                    TIME_FORMAT(st.schedto, '%H:%i') AS schedto
                                    FROM user_info ui
                                    LEFT JOIN lnd_excluded_trainees et ON ui.empno = et.empno
                                    LEFT JOIN sched_time st ON ui.empno = st.empno AND st.datefromto = '$datefrom' 
                                    WHERE ui.empno = '$empno'  
                                    ORDER BY st.schedfrom ASC;";

            $additionalInfoResult = mysqli_query($HRconnect, $additionalInfoQuery);

            // Check if query executed successfully
            if ($additionalInfoResult) {
                // Fetch the additional information
                $additionalInfo = mysqli_fetch_assoc($additionalInfoResult);

                // Merge the additional information with the emp_data
                $emp_data = array_merge($emp_data, $additionalInfo);

                // Add starttime and endtime to emp_data
                $emp_data['starttime'] = $queryBatch['starttime'];
                $emp_data['endtime'] = $queryBatch['endtime'];
                $emp_data['datefrom'] = $queryBatch['datefrom'];

                // Add the emp_data to the empDataArray
                $empDataArray[] = $emp_data;
            }

            // Free the additionalInfoResult
            mysqli_free_result($additionalInfoResult);
        }
    }

    // Close the connectionQueryBatch
    $connectionQueryBatch->close();

    // Create an array to hold the ID and emp data
    $response = array(
        'id' => $id,
        'empData' => $empDataArray
    );

    // Encode the response as JSON and echo it
    echo json_encode($response);
} else {
    // If query execution fails, return an error message
    echo json_encode(array('error' => 'Failed to execute query for batch numbers'));
}

// Close the database connection
mysqli_close($HRconnect);
