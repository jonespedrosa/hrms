<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Get the value of 'id' from the URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$scheduleId = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;

// Check if 'id' is provided
if ($id === null) {
    echo json_encode(array('error' => 'ID parameter is missing'));
    exit();
}

// Echo the parameters to check their values
// echo "id: " . $id . "<br>";
// echo "scheduleId: " . $scheduleId . "<br>";


// Sanitize input to prevent SQL injection
$id = mysqli_real_escape_string($HRconnect, $id);
$scheduleId = mysqli_real_escape_string($HRconnect, $scheduleId);

// Prepare the query for fetching batch numbers
$queryBatchNumber = "SELECT 
                        t.schedule_id, 
                        REPLACE(REPLACE(REPLACE(t.course_id, '[', ''), ']', ''), '\"', '') AS course_id, 
                        e.enrolled_emp_data 
                    FROM (
                        SELECT 
                            s.id AS schedule_id, 
                            REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') AS course_id 
                        FROM 
                            lnd_training_batch b 
                        JOIN 
                            lnd_training_schedule s ON b.id = s.id 
                    ) AS t 
                    JOIN 
                        lnd_enrolled_employees e ON t.schedule_id = e.training_sched_id 
                    JOIN 
                        lnd_training_schedule y ON t.schedule_id = y.id 
                    WHERE 
                        y.batch_number = '$id' AND t.schedule_id = '$scheduleId'";

// Execute the query for fetching batch numbers
$connectionQueryBatch = $HRconnect->query($queryBatchNumber);

// Check if the query executed successfully
if ($connectionQueryBatch) {
    // Initialize an empty array to store converted data
    $convertedData = [];

    // Fetch all rows as an associative array
    while ($queryBatch = $connectionQueryBatch->fetch_assoc()) {
        // Decode the enrolled_emp_data JSON string
        $enrolledEmpData = json_decode($queryBatch['enrolled_emp_data'], true);

        // Initialize an array to store empno values
        $empnos = [];

        // Loop through each empno in enrolledEmpData and construct an array of objects
        foreach ($enrolledEmpData as $empNo => $empData) {
            $empnos[] = array('empno' => $empNo);
        }

        // Create an object with the required fields and push it to convertedData
        $convertedData[] = [
            'schedule_id' => $queryBatch['schedule_id'],
            'course_id' => $queryBatch['course_id'],
            'enrolled_emp_data' => $empnos
        ];
    }

    // Output the converted data
    echo json_encode($convertedData);
} else {
    // If query execution fails, return an error message
    echo json_encode(array('error' => 'Failed to execute query'));
}

// Close the database connection
mysqli_close($HRconnect);
