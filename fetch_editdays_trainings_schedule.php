<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Check if batch_number and schedule_id are set in the URL
if (isset($_GET['batch_number']) && isset($_GET['schedule_id'])) {
    $batch_number = $_GET['batch_number'];
    $schedule_id = $_GET['schedule_id'];

    // Prepare the SQL query
    // $query = "SELECT id_main, id, day, datefrom, location, name_facilitator, starttime, endtime, no_of_courses, no_of_topics, status 
    //           FROM `lnd_training_batch` 
    //           WHERE id = ?";

    $query = "SELECT b.id_main, b.id, b.day, b.datefrom, s.dateto, s.batch_number, b.location, b.name_facilitator, b.starttime, b.endtime, b.no_of_courses, b.no_of_topics, b.status 
          FROM lnd_training_batch b
          LEFT JOIN lnd_training_schedule s ON b.id = s.id AND b.datefrom = s.datefrom
          WHERE b.id = ?";

    // Prepare statement
    if ($stmt = mysqli_prepare($HRconnect, $query)) {
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "i", $schedule_id);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Initialize an array to store rows
        $rows = array();

        // Fetch all rows and store them in the array
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        // Output the result in JSON format
        echo json_encode(array('data' => $rows));

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(array('error' => 'Failed to prepare the statement'));
    }
} else {
    echo json_encode(array('error' => 'batch_number or schedule_id not set'));
}

// Close the connection
mysqli_close($HRconnect);
