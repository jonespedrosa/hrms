<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Check if schedule_id is set in the URL
if (isset($_GET['datefrom'])) {
    // Sanitize the schedule_id to prevent SQL injection
    $datefrom = mysqli_real_escape_string($HRconnect, $_GET['datefrom']);
} else {
    // If schedule_id is not set, return an error message
    echo json_encode(array('error' => 'schedule_id parameter is missing in the URL'));
    exit();
}

// Check if schedule_id is set in the URL
if (isset($_GET['schedule_id'])) {
    // Sanitize the schedule_id to prevent SQL injection
    $scheduleiD = mysqli_real_escape_string($HRconnect, $_GET['schedule_id']);
} else {
    // If schedule_id is not set, return an error message
    echo json_encode(array('error' => 'schedule_id parameter is missing in the URL'));
    exit();
}

// SQL query to select data from lnd_training_batch table with a specific id
$query = "SELECT id_main, id, day, trainees_empno, datefrom, location, starttime, endtime, status FROM `lnd_training_batch` WHERE id = '$scheduleiD'";

// Execute the query
$result = mysqli_query($HRconnect, $query);

// Check if the query was successful
if ($result) {
    // Initialize an array to hold the data
    $data = array();

    // Fetch all rows
    while ($row = mysqli_fetch_assoc($result)) {
        // Decode the trainees_empno JSON array
        $trainees_empno_array = json_decode($row['trainees_empno'], true);

        // Ensure the trainees_empno is in the correct format for JavaScript
        if (is_array($trainees_empno_array)) {
            $row['trainees_empno'] = json_encode($trainees_empno_array);
        } else {
            $row['trainees_empno'] = json_encode(array());
        }

        // Add the row to the data array
        $data[] = $row;
    }

    // Extract trainees_empno values
    $trainees_empno_values = array();
    foreach ($data as $row) {
        $trainees_empno_values = array_merge($trainees_empno_values, json_decode($row['trainees_empno']));
    }
    $trainees_empno_values = array_unique($trainees_empno_values);

    // Formulate the second query with the extracted trainees_empno values
    $query2 = "SELECT 
                u.userid, 
                u.empno, 
                u.name, 
                u.branch, 
                s.datefromto, 
                s.schedfrom, 
                s.schedto, 
                s.break, 
                s.M_timein, 
                s.M_timeout, 
                s.A_timein, 
                s.A_timeout, 
                l.id_main, 
                l.id, 
                l.datefrom, 
                l.location, 
                a.isAbsent AS empno_absent,
                a.datefrom AS empno_dateAbsent,
                e.IsExclude AS empno_excluded, 
                e.datefrom AS empno_dateExclude
            FROM 
                `user_info` u
            JOIN 
                `sched_time` s ON u.empno = s.empno
            JOIN 
                `lnd_training_batch` l ON s.empno = s.empno
            LEFT JOIN 
                `lnd_absent_trainees` a ON s.empno = a.empno AND s.datefromto = a.datefrom
            LEFT JOIN 
                `lnd_excluded_trainees` e ON s.empno = e.empno AND s.datefromto = e.datefrom
            WHERE 
                s.empno IN (" . implode(",", $trainees_empno_values) . ")
                AND s.datefromto = '$datefrom'  
                AND l.id = '$scheduleiD' 
                AND e.IsExclude IS NULL
            GROUP BY 
                u.empno
            ORDER BY 
                u.empno ASC;";

    // Execute the second query
    $result2 = mysqli_query($HRconnect, $query2);

    // Check if the second query was successful
    if ($result2) {
        // Initialize an array to hold the data from the second query
        $data2 = array();
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $data2[] = $row2;
        }

        // Formulate the third query with the extracted trainees_empno values
        $query3 = "SELECT empno, cafename, type, logdate FROM logs WHERE empno IN (" . implode(",", $trainees_empno_values) . ") AND logdate = '$datefrom' ORDER BY type ASC";

        // Execute the third query
        $result3 = mysqli_query($HRconnect, $query3);

        // Check if the third query was successful
        if ($result3) {
            // Initialize an array to hold the data from the third query
            $data3 = array();
            while ($row3 = mysqli_fetch_assoc($result3)) {
                $data3[] = $row3;
            }

            // Combine data from all three queries into a single array
            $response = array(
                'training_batch' => $data,
                'employee_schedule' => $data2,
                'employee_with_location' => $data3
            );

            // Output the combined data in JSON format
            echo json_encode($response);
        } else {
            // If the third query fails, return an error message
            echo json_encode(array('error' => 'Query 3 failed: ' . mysqli_error($HRconnect)));
        }
    } else {
        // If the second query fails, return an error message
        echo json_encode(array('error' => 'Query 2 failed: ' . mysqli_error($HRconnect)));
    }
} else {
    // If the first query fails, return an error message
    echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
}

// Close the database connection
mysqli_close($HRconnect);





















// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     // If connection fails, return an error message
//     echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
//     exit();
// }

// // Check if schedule_id is set in the URL
// if (isset($_GET['datefrom'])) {
//     // Sanitize the schedule_id to prevent SQL injection
//     $datefrom = mysqli_real_escape_string($HRconnect, $_GET['datefrom']);
// } else {
//     // If schedule_id is not set, return an error message
//     echo json_encode(array('error' => 'schedule_id parameter is missing in the URL'));
//     exit();
// }


// // Check if schedule_id is set in the URL
// if (isset($_GET['schedule_id'])) {
//     // Sanitize the schedule_id to prevent SQL injection
//     $scheduleiD = mysqli_real_escape_string($HRconnect, $_GET['schedule_id']);
// } else {
//     // If schedule_id is not set, return an error message
//     echo json_encode(array('error' => 'schedule_id parameter is missing in the URL'));
//     exit();
// }

// // SQL query to select data from lnd_training_batch table with a specific id
// $query = "SELECT id_main, id, day, trainees_empno, datefrom, location, starttime, endtime, status FROM `lnd_training_batch` WHERE id = '$scheduleiD'";

// // Execute the query
// $result = mysqli_query($HRconnect, $query);

// // Check if the query was successful
// if ($result) {
//     // Initialize an array to hold the data
//     $data = array();

//     // Fetch all rows
//     while ($row = mysqli_fetch_assoc($result)) {
//         // Decode the trainees_empno JSON array
//         $trainees_empno_array = json_decode($row['trainees_empno'], true);

//         // Ensure the trainees_empno is in the correct format for JavaScript
//         if (is_array($trainees_empno_array)) {
//             $row['trainees_empno'] = json_encode($trainees_empno_array);
//         } else {
//             $row['trainees_empno'] = json_encode(array());
//         }

//         // Add the row to the data array
//         $data[] = $row;
//     }

//     // Extract trainees_empno values
//     $trainees_empno_values = array();
//     foreach ($data as $row) {
//         $trainees_empno_values = array_merge($trainees_empno_values, json_decode($row['trainees_empno']));
//     }
//     $trainees_empno_values = array_unique($trainees_empno_values);

//     // Formulate the second query with the extracted trainees_empno values
//     $query2 = "SELECT 
//                 u.userid, 
//                 u.empno, 
//                 u.name, 
//                 u.branch, 
//                 s.datefromto, 
//                 s.schedfrom, 
//                 s.schedto, 
//                 s.break, 
//                 s.M_timein, 
//                 s.M_timeout, 
//                 s.A_timein, 
//                 s.A_timeout, 
//                 l.id_main, 
//                 l.id, 
//                 l.datefrom, 
//                 l.location, 
//                 a.isAbsent AS empno_absent,
//                 a.datefrom AS empno_dateAbsent,
//                 e.IsExclude AS empno_excluded, 
//                 e.datefrom AS empno_dateExclude
//             FROM 
//                 `user_info` u
//             JOIN 
//                 `sched_time` s ON u.empno = s.empno
//             JOIN 
//                 `lnd_training_batch` l ON s.empno = s.empno
//             LEFT JOIN 
//                 `lnd_absent_trainees` a ON s.empno = a.empno AND s.datefromto = a.datefrom
//             LEFT JOIN 
//                 `lnd_excluded_trainees` e ON s.empno = e.empno AND s.datefromto = e.datefrom
//             WHERE 
//                 s.empno IN (" . implode(",", $trainees_empno_values) . ")
//                 AND s.datefromto = '$datefrom'  
//                 AND l.id = '$scheduleiD' 
//                 AND e.IsExclude IS NULL
//             GROUP BY 
//                 u.empno
//             ORDER BY 
//                 u.empno ASC;";

//     // Execute the second query
//     $result2 = mysqli_query($HRconnect, $query2);

//     // Check if the second query was successful
//     if ($result2) {
//         // Initialize an array to hold the data from the second query
//         $data2 = array();
//         while ($row2 = mysqli_fetch_assoc($result2)) {
//             $data2[] = $row2;
//         }

//         // Combine data from both queries into a single array
//         $response = array(
//             'training_batch' => $data,
//             'employee_schedule' => $data2
//         );

//         // Output the combined data in JSON format
//         echo json_encode($response);
//     } else {
//         // If the second query fails, return an error message
//         echo json_encode(array('error' => 'Query 2 failed: ' . mysqli_error($HRconnect)));
//     }
// } else {
//     // If the first query fails, return an error message
//     echo json_encode(array('error' => 'Query failed: ' . mysqli_error($HRconnect)));
// }

// // Close the database connection
// mysqli_close($HRconnect);






















// Formulate the second query with the extracted trainees_empno values
    // $query2 = "SELECT 
    //         u.userid, 
    //         u.empno, 
    //         u.name, 
    //         u.branch, 
    //         s.datefromto, 
    //         s.schedfrom, 
    //         s.schedto, 
    //         s.break, 
    //         s.M_timein, 
    //         s.M_timeout, 
    //         s.A_timein, 
    //         s.A_timeout, 
    //         l.id_main, 
    //         l.id, 
    //         l.datefrom, 
    //         l.location, 
    //         a.isAbsent AS empno_absent,
    //         a.datefrom AS empno_dateAbsent,
    //         e.IsExclude AS empno_excluded, -- New column from lnd_excluded_trainees
    //         e.datefrom AS empno_dateExclude
    //     FROM 
    //         `user_info` u
    //     JOIN 
    //         `sched_time` s ON u.empno = s.empno
    //     JOIN 
    //         `lnd_training_batch` l ON s.empno = s.empno
    //     LEFT JOIN 
    //         `lnd_absent_trainees` a ON s.empno = a.empno  AND s.datefromto = '$datefrom' 
    //     LEFT JOIN 
    //         `lnd_excluded_trainees` e ON s.empno = e.empno  AND s.datefromto = '$datefrom'  -- Joining lnd_excluded_trainees
    //     WHERE 
    //         s.empno IN (" . implode(",", $trainees_empno_values) . ")
    //         AND s.datefromto = '$datefrom'  
    //         AND l.id = '$scheduleiD' 
    //     GROUP BY 
    //         u.empno
    //     ORDER BY 
    //         u.empno ASC";







    // Formulate the second query with the extracted trainees_empno values
    // $query2 = "SELECT u.userid, u.empno, u.name, u.branch, s.datefromto, s.schedfrom, s.schedto, s.break, s.M_timein, s.M_timeout, s.A_timein, s.A_timeout, l.id_main, l.id, l.datefrom, l.location, a.isAbsent AS empno_absent
    //             FROM `user_info` u
    //             JOIN `sched_time` s ON u.empno = s.empno
    //             JOIN `lnd_training_batch` l ON s.empno = s.empno
    //             LEFT JOIN `lnd_absent_trainees` a ON s.empno = a.empno  AND s.datefromto = '$datefrom'
    //             WHERE s.empno IN (" . implode(",", $trainees_empno_values) . ")
    //             AND s.datefromto = '$datefrom' AND l.id = '$scheduleiD'
    //             GROUP BY u.empno
    //             ORDER BY u.empno ASC";