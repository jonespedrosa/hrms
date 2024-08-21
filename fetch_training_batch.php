<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Fetch id and schedule_id parameters from the URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : '';

// Prepare SQL query with placeholders for batch_number and schedule_id
$sql = "SELECT 
        b.day, 
        b.trainees_empno,
        b.datefrom, 
        b.location,
        b.name_facilitator,
        b.starttime, 
        b.endtime, 
        JSON_LENGTH(JSON_KEYS(b.no_of_courses)) AS no_of_courses_count,
        JSON_LENGTH(b.no_of_topics) AS no_of_topics_count, 
        b.no_of_topics AS list_of_topics,
        b.status,
        s.status AS status_batch,
        s.batch_number,
        s.id AS schedule_id,
        REPLACE(REPLACE(REPLACE(s.course_id, '[', ''), ']', ''), '\"', '') AS course_id
        FROM 
        lnd_training_batch b
        JOIN 
        lnd_training_schedule s ON b.id = s.id
        WHERE 
        s.batch_number = ? AND s.id = ?";

// Prepare statement
$stmt = mysqli_prepare($HRconnect, $sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "ss", $id, $schedule_id);

// Execute statement
mysqli_stmt_execute($stmt);

// Get result
$result = mysqli_stmt_get_result($stmt);

// Check if there are any results
if ($result && mysqli_num_rows($result) > 0) {
    // Fetch data rows
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    // Close the result set
    mysqli_free_result($result);
} else {
    // If no data is found or query fails, return an empty array
    $data = array();
}

// Close the statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($HRconnect);

// Return the JSON data
echo json_encode($data);
