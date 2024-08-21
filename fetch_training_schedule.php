<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Fetch data from the lnd_training_schedule table
$sql = "SELECT lnd_training_schedule.id, 
        lnd_training_schedule.batch_number,
        lnd_training_schedule.datefrom,
        lnd_training_schedule.dateto,
        lnd_training_schedule.course_id,
        lnd_enrolled_employees.enrolled_emp_data,
        lnd_training_schedule.status
        FROM lnd_training_schedule
        JOIN lnd_enrolled_employees ON lnd_training_schedule.id = lnd_enrolled_employees.training_sched_id";

$result = mysqli_query($HRconnect, $sql);

// Check if there are any rows returned
if (mysqli_num_rows($result) > 0) {
    // Initialize an array to store the fetched data
    $data = array();

    // Fetch rows and push them into the data array
    while ($row = mysqli_fetch_assoc($result)) {
        // Parse the enrolled_emp_data JSON to count the number of trainees
        $enrolledData = json_decode($row['enrolled_emp_data'], true);
        $numberOfTrainees = count($enrolledData);

        // Fetch the course name based on the course_id
        $course_id = $row['course_id'];
        $course_sql = "SELECT id, name FROM lnd_training_courses WHERE id = $course_id";
        $course_result = mysqli_query($HRconnect, $course_sql);
        $course_name = "";

        if ($course_result && mysqli_num_rows($course_result) > 0) {
            $course_row = mysqli_fetch_assoc($course_result);
            $course_name = $course_row['name'];
        }

        // Append the course name and number of trainees to the row data
        $row['course_name'] = $course_name;
        $row['number_of_trainees'] = $numberOfTrainees;

        // Add the modified row to the data array
        $data[] = $row;
    }

    // Close the database connection
    mysqli_close($HRconnect);

    // Return the JSON data
    echo json_encode($data);
} else {
    // If no data is found, return an empty array
    echo json_encode(array());
}
