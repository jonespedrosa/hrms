<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Sanitize inputs to prevent SQL injection
$scheduleId = isset($_GET['schedule_id']) ? mysqli_real_escape_string($HRconnect, $_GET['schedule_id']) : '';
$day = isset($_GET['day']) ? mysqli_real_escape_string($HRconnect, $_GET['day']) : '';

// Get the course IDs from the GET request
$courses = isset($_GET['course_id']) ? $_GET['course_id'] : '';
// Sanitize input to prevent SQL injection
$courses = mysqli_real_escape_string($HRconnect, $courses);

if (!empty($courses)) {
    // Prepare the query for fetching course topics
    $queryCourseTopic = "SELECT 
                            t.id, 
                            t.name, 
                            t.description, 
                            t.course_id,
                            c.description AS course_description,
                            c.name AS course_name 
                        FROM 
                            `lnd_course_topics` AS t 
                        INNER JOIN 
                            `lnd_training_courses` AS c 
                        ON 
                            t.course_id = c.id 
                        WHERE 
                            t.course_id IN ($courses)";

    // Execute the query for fetching course topics
    $connectionQueryTopic = $HRconnect->query($queryCourseTopic);

    // Check if the query executed successfully
    if ($connectionQueryTopic) {
        // Initialize an empty array to store course details
        $courseArray = [];

        // Fetch all rows as an associative array
        while ($queryTopic = $connectionQueryTopic->fetch_assoc()) {
            $current_course_id = $queryTopic['course_id'];

            // If the course ID is not yet present in $courseArray, initialize its details
            if (!isset($courseArray[$current_course_id])) {
                $courseArray[$current_course_id] = [
                    'course_id' => $queryTopic['course_id'], // Set the course_id property
                    'course_name' => $queryTopic['course_name'],
                    'course_description' => $queryTopic['course_description'], // Include course_description
                    'topics' => [], // Change 'topics_names' to 'topics'
                    'enrollments' => [], // Initialize enrollments array
                    'training_batches' => [], // Initialize training batches array
                ];
            }

            // Details of the current topic
            $detailsArray = [
                'id' => $queryTopic['id'], // Include topic id
                'name' => $queryTopic['name'],
                'description' => $queryTopic['description'],
            ];

            // Add the details to the topics array of the current course
            $courseArray[$current_course_id]['topics'][] = $detailsArray;
        }

        // Prepare the query for fetching training batch details
        $queryTrainingBatch = "SELECT id, day, trainees_empno, datefrom, location, starttime, endtime, no_of_courses, no_of_topics 
                            FROM `lnd_training_batch` 
                            WHERE `id` = '$scheduleId'
                               AND `day` = '$day'"; // Replace 105 with the appropriate condition

        // Execute the query for fetching training batch details
        $connectionQueryTrainingBatch = $HRconnect->query($queryTrainingBatch);

        // Check if the query executed successfully
        if ($connectionQueryTrainingBatch) {
            // Fetch all rows as an associative array
            while ($queryBatch = $connectionQueryTrainingBatch->fetch_assoc()) {
                // Convert trainees_empno to array if it's not already an array
                $trainees_empno = json_decode($queryBatch['trainees_empno'], true);
                if (!is_array($trainees_empno)) {
                    $trainees_empno = array($trainees_empno);
                }

                foreach ($trainees_empno as $empno) {
                    // Assuming the training batch is related to a specific course
                    // Add the batch details to the course array (you might need to adjust the course ID logic)
                    foreach ($courseArray as $courseId => $courseDetails) {
                        $courseArray[$courseId]['training_batches'][] = [
                            'id' => $queryBatch['id'],
                            'day' => $queryBatch['day'],
                            'trainees_empno' => $empno,
                            'datefrom' => $queryBatch['datefrom'],
                            'location' => $queryBatch['location'],
                            'starttime' => $queryBatch['starttime'],
                            'endtime' => $queryBatch['endtime'],
                            'no_of_courses' => $queryBatch['no_of_courses'],
                            'no_of_topics' => $queryBatch['no_of_topics'],
                        ];
                    }
                }
            }

            // Close the result set
            $connectionQueryTrainingBatch->close();
        } else {
            // If query execution for training batch details fails, return an error message
            echo json_encode(array('error' => 'Failed to execute query for training batch details'));
            exit();
        }

        // Output the course array
        echo json_encode($courseArray);
    } else {
        // If query execution fails, return an error message
        echo json_encode(array('error' => 'Failed to execute query for course topics'));
        exit();
    }
} else {
    // If no courses were provided, return an error message
    echo json_encode(array('error' => 'No course IDs provided'));
    exit();
}

// Close the database connection
mysqli_close($HRconnect);


