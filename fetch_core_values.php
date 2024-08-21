<?php

// Establish database connection (comment out or modify as needed for testing)
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    error_log("Database connection failed: " . mysqli_connect_error());
    echo json_encode(array('error' => 'Database connection failed'));
    exit();
}

// Fetch data from the database
$query = "
    SELECT core_values.id, core_values.empno, user_info.name AS response_name, core_values.responses 
    FROM core_values
    INNER JOIN user_info ON core_values.empno = user_info.empno
";

$result = mysqli_query($HRconnect, $query);

// Check for query errors
if (!$result) {
    error_log("Query failed: " . mysqli_error($HRconnect));
    echo json_encode(array('error' => 'Query failed'));
    mysqli_close($HRconnect);
    exit();
}

// Fetch data and process each row
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $responses = json_decode($row['responses'], true);

    // Prepare the output data
    $data[] = array(
        'id' => $row['id'], // Include the id in the output
        'response_name' => $row['response_name'],
        'evaluated_name' => $responses['evaluated']['name'] ?? '',
        'evaluated_position' => $responses['evaluated']['position'] ?? '',
        'evaluated_idnumber' => $responses['evaluated']['idnumber'] ?? '',
        'responses' => $row['responses'] // Include the raw responses JSON
    );
}

mysqli_free_result($result);
mysqli_close($HRconnect);

// Output JSON data
echo json_encode($data);





// // Establish database connection (comment out or modify as needed for testing)
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// // Check connection
// if (mysqli_connect_errno()) {
//     error_log("Database connection failed: " . mysqli_connect_error());
//     echo json_encode(array('error' => 'Database connection failed'));
//     exit();
// }

// // Fetch data from the database
// $query = "
//     SELECT core_values.id, core_values.empno, user_info.name AS response_name, core_values.responses 
//     FROM core_values
//     INNER JOIN user_info ON core_values.empno = user_info.empno
// ";

// $result = mysqli_query($HRconnect, $query);

// // Check for query errors
// if (!$result) {
//     error_log("Query failed: " . mysqli_error($HRconnect));
//     echo json_encode(array('error' => 'Query failed'));
//     mysqli_close($HRconnect);
//     exit();
// }

// // Fetch data and process each row
// $data = array();
// while ($row = mysqli_fetch_assoc($result)) {
//     $responses = json_decode($row['responses'], true);

//     // Prepare the output data
//     $data[] = array(
//         'id' => $row['id'], // Include the id in the output
//         'response_name' => $row['response_name'],
//         'evaluated_name' => $responses['evaluated']['name'] ?? '',
//         'evaluated_position' => $responses['evaluated']['position'] ?? '',
//     );
// }

// mysqli_free_result($result);
// mysqli_close($HRconnect);

// // Output JSON data
// echo json_encode($data);



