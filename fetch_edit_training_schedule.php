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

    // Prepare the SQL statement to fetch data based on batch_number and schedule_id from lnd_training_schedule
    $stmt_schedule = $HRconnect->prepare("SELECT id, datefrom, dateto, batch_number, status FROM lnd_training_schedule WHERE batch_number = ? AND id = ?");
    $stmt_schedule->bind_param("si", $batch_number, $schedule_id);

    // Execute the prepared statement for lnd_training_schedule
    $stmt_schedule->execute();

    // Get the result for lnd_training_schedule
    $result_schedule = $stmt_schedule->get_result();

    if ($result_schedule) {
        $data_schedule = array();

        // Fetch each row of the result as an associative array for lnd_training_schedule
        while ($row_schedule = $result_schedule->fetch_assoc()) {
            $data_schedule[] = $row_schedule;
        }

        // Return the fetched data as a JSON response
        echo json_encode(array('data' => $data_schedule));
    } else {
        echo json_encode(array('error' => 'Failed to fetch schedule data from database'));
    }

    // Close the statement for lnd_training_schedule
    $stmt_schedule->close();
} else {
    echo json_encode(array('error' => 'Invalid request.'));
}

// Close the database connection
mysqli_close($HRconnect);





// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('error' => 'Failed to connect to database'));
//     exit();
// }

// // Check if batch_number and schedule_id are set in the URL
// if (isset($_GET['batch_number']) && isset($_GET['schedule_id'])) {
//     $batch_number = $_GET['batch_number'];
//     $schedule_id = $_GET['schedule_id'];

//     // Prepare the SQL statement to fetch data based on batch_number and schedule_id from lnd_training_schedule
//     $stmt_schedule = $HRconnect->prepare("SELECT id, datefrom, dateto, batch_number, status FROM lnd_training_schedule WHERE batch_number = ? AND id = ?");
//     $stmt_schedule->bind_param("si", $batch_number, $schedule_id);

//     // Execute the prepared statement for lnd_training_schedule
//     $stmt_schedule->execute();

//     // Get the result for lnd_training_schedule
//     $result_schedule = $stmt_schedule->get_result();

//     if ($result_schedule) {
//         $data_schedule = array();

//         // Fetch each row of the result as an associative array for lnd_training_schedule
//         while ($row_schedule = $result_schedule->fetch_assoc()) {
//             $data_schedule[] = $row_schedule;
//         }

//         // Prepare the SQL statement to fetch additional data from lnd_training_batch
//         $stmt_batch = $HRconnect->prepare("SELECT id_main, id, day, datefrom, location, name_facilitator, starttime, endtime, status FROM lnd_training_batch WHERE id = ?");
//         $stmt_batch->bind_param("i", $batch_number);

//         // Execute the prepared statement for lnd_training_batch
//         $stmt_batch->execute();

//         // Get the result for lnd_training_batch
//         $result_batch = $stmt_batch->get_result();

//         if ($result_batch) {
//             $data_batch = array();

//             // Fetch each row of the result as an associative array for lnd_training_batch
//             while ($row_batch = $result_batch->fetch_assoc()) {
//                 $data_batch[] = $row_batch;
//             }

//             // Combine both sets of data into a single response
//             $response = array(
//                 'schedule_data' => $data_schedule,
//                 'batch_data' => $data_batch
//             );

//             // Return the fetched data as a JSON response
//             echo json_encode(array('data' => $response));
//         } else {
//             echo json_encode(array('error' => 'Failed to fetch batch data from database'));
//         }

//         // Close the statement for lnd_training_batch
//         $stmt_batch->close();
//     } else {
//         echo json_encode(array('error' => 'Failed to fetch schedule data from database'));
//     }

//     // Close the statement for lnd_training_schedule
//     $stmt_schedule->close();
// } else {
//     echo json_encode(array('error' => 'Invalid request.'));
// }

// // Close the database connection
// mysqli_close($HRconnect);
