<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Check if id_main and dateToDelete are set and not empty
if (isset($_POST['id_main']) && isset($_POST['dateToDelete'])) {
    // Sanitize inputs to prevent SQL injection
    $id_main = mysqli_real_escape_string($HRconnect, $_POST['id_main']);
    $dateToDelete = mysqli_real_escape_string($HRconnect, $_POST['dateToDelete']);

    // Construct the delete query
    $delete_query = "DELETE FROM `lnd_training_batch` WHERE `id_main` = '$id_main' AND `datefrom` = '$dateToDelete'";

    // Execute the delete query
    if (mysqli_query($HRconnect, $delete_query)) {
        echo json_encode(array('success' => 'Date deleted successfully'));

        // Log the action in lnd_action_logs
        session_start();
        if (isset($_SESSION['empno'])) {
            $session_empno = $_SESSION['empno'];
            $action = "Deleted date: $dateToDelete";

            // Insert query for action logs
            $insert_query = "INSERT INTO lnd_action_logs (action, action_by, created_at) VALUES (?, ?, NOW())";
            $stmt = mysqli_prepare($HRconnect, $insert_query);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $action, $session_empno);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(array('error' => 'Failed to log action: ' . mysqli_error($HRconnect)));
            }
        } else {
            echo json_encode(array('error' => 'Session empno not set.'));
        }
    } else {
        echo json_encode(array('error' => 'Failed to delete date: ' . mysqli_error($HRconnect)));
    }
} else {
    echo json_encode(array('error' => 'id_main and dateToDelete parameters are required'));
}

// Close database connection
mysqli_close($HRconnect);












// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     echo json_encode(array('error' => 'Failed to connect to database'));
//     exit();
// }

// // Check if id_main and dateToDelete are set and not empty
// if (isset($_POST['id_main']) && isset($_POST['dateToDelete'])) {
//     // Sanitize inputs to prevent SQL injection
//     $id_main = mysqli_real_escape_string($HRconnect, $_POST['id_main']);
//     $dateToDelete = mysqli_real_escape_string($HRconnect, $_POST['dateToDelete']);

//     // Construct the delete query
//     $delete_query = "DELETE FROM `lnd_training_batch` WHERE `id_main` = '$id_main' AND `datefrom` = '$dateToDelete'";

//     // Execute the query
//     if (mysqli_query($HRconnect, $delete_query)) {
//         echo json_encode(array('success' => 'Date deleted successfully'));
//     } else {
//         echo json_encode(array('error' => 'Failed to delete date: ' . mysqli_error($HRconnect)));
//     }
// } else {
//     echo json_encode(array('error' => 'id_main and dateToDelete parameters are required'));
// }

// // Close database connection
// mysqli_close($HRconnect);
