<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    error_log("Database connection failed: " . mysqli_connect_error());
    echo json_encode(array('error' => 'Database connection failed'));
    exit();
}

// Get the `id` from POST data
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Prepare and execute the SQL query
$query = "SELECT * FROM `core_values` WHERE `id` = $id";
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
    // Decode the JSON string in the `responses` field
    $row['responses'] = json_decode($row['responses'], true);
    $data[] = $row;
}

mysqli_free_result($result);
mysqli_close($HRconnect);

// Output JSON data
echo json_encode($data);
