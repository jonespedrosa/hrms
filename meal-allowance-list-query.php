<?php

session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming empno is passed as a parameter
$empno = isset($_GET['empno']) ? mysqli_real_escape_string($HRconnect, $_GET['empno']) : '';

// Query 1: Fetch data from meal_allowance_list
$query1 = "SELECT empno, name, branch, no_of_day, total_allowance, status FROM meal_allowance_list";
if (!empty($empno)) {
    $query1 .= " WHERE empno = '$empno'";
}
$result1 = mysqli_query($HRconnect, $query1);

$data = array();
while ($row = mysqli_fetch_assoc($result1)) {
    $empno = $row['empno']; // Get the empno for each row

    // Query 2: Fetch data from sched_time table for each empno
    $query2 = "SELECT 
                st.userid, 
                st.empno, 
                st.datefromto, 
                st.schedfrom, 
                st.schedto, 
                st.break, 
                st.M_timein, 
                st.M_timeout, 
                st.A_timein, 
                st.A_timeout,
                ou.otdatefrom,
                ou.ottype,
                ou.othours,
                ou.otstatus
            FROM 
                sched_time st
            LEFT JOIN 
                overunder ou 
            ON 
                st.empno = ou.empno 
                AND st.datefromto = ou.otdatefrom
            WHERE 
                st.empno = '$empno' 
                AND st.datefromto BETWEEN '2024-06-24' AND '2024-07-08'";

    $result2 = mysqli_query($HRconnect, $query2);

    $sched_time_data = array();
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $sched_time_data[] = $row2;
    }

    // Add sched_time data to the current row
    $row['sched_time'] = $sched_time_data;

    $data[] = $row;
}

mysqli_close($HRconnect);

// Return the combined result
header('Content-Type: application/json');
echo json_encode($data);




































// session_start();
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// // Assuming empno is passed as a parameter
// $empno = isset($_GET['empno']) ? mysqli_real_escape_string($HRconnect, $_GET['empno']) : '';

// // Query 1: Fetch data from meal_allowance_list
// $query1 = "SELECT empno, name, branch, no_of_day, total_meal, status FROM meal_allowance_list";
// if (!empty($empno)) {
//     $query1 .= " WHERE empno = '$empno'";
// }
// $result1 = mysqli_query($HRconnect, $query1);

// $data = array();
// while ($row = mysqli_fetch_assoc($result1)) {
//     $empno = $row['empno']; // Get the empno for each row


    
//     // Query 2: Fetch data from sched_time table for each empno
//     $query2 = "SELECT userid, empno, datefromto, schedfrom, schedto, break, M_timein, M_timeout, A_timein, A_timeout 
//             FROM sched_time 
//             WHERE empno = '$empno' 
//             AND datefromto BETWEEN '2024-06-24' AND '2024-07-08'";




//     $result2 = mysqli_query($HRconnect, $query2);

//     $sched_time_data = array();
//     while ($row2 = mysqli_fetch_assoc($result2)) {
//         $sched_time_data[] = $row2;
//     }

//     // Add sched_time data to the current row
//     $row['sched_time'] = $sched_time_data;

//     $data[] = $row;
// }

// mysqli_close($HRconnect);

// // Return the combined result
// header('Content-Type: application/json');
// echo json_encode($data);
