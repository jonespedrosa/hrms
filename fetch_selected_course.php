<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Get parameters from query string
$schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;
$day = isset($_GET['day']) ? $_GET['day'] : null;

// Define the query
$query = "SELECT id_main, id AS schedule_id, day, datefrom, location, name_facilitator, starttime, endtime, no_of_courses, no_of_topics, status
    FROM `lnd_training_batch`
    WHERE `day` = {$day} AND id = {$schedule_id};";

// Execute the query
$result = mysqli_query($HRconnect, $query);

if ($result) {
    $data = mysqli_fetch_assoc($result);
    
    // Decode the JSON string to an associative array
    $topics = json_decode($data['no_of_topics'], true);
    
    // Extract course_ids from the topics array
    $course_ids = array_column($topics, 'course_id');
    
    // Generate comma-separated string of course_ids
    $course_ids_str = implode(',', $course_ids);
    
    // Query to fetch course details based on extracted course_ids
    $course_query = "SELECT id as course_id, name as name_course, description as description_course 
                    FROM `lnd_training_courses` 
                    WHERE id IN ({$course_ids_str});";
    
    // Execute the course query
    $course_result = mysqli_query($HRconnect, $course_query);
    
    if ($course_result) {
        // Fetch course details
        $courses = mysqli_fetch_all($course_result, MYSQLI_ASSOC);
        
        // Add course details to data array
        $data['courses'] = $courses;
        
        // Encode the final data array to JSON and output
        echo json_encode($data);
    } else {
        echo json_encode(array('error' => 'Failed to execute course query'));
    }
} else {
    echo json_encode(array('error' => 'Failed to execute query'));
}

// Close the database connection
mysqli_close($HRconnect);








