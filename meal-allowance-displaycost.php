<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// SQL query to fetch data
$sql = "SELECT id, food_cost FROM meal_allowance_setting";
$result = mysqli_query($HRconnect, $sql);

// Fetch data as associative array
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Get total number of records (optional, but recommended for correct pagination info)
$totalRecords = mysqli_num_rows($result);

// Close connection
mysqli_close($HRconnect);

// Prepare response
$response = [
    'draw' => 1, // This should match the draw parameter sent by DataTables
    'recordsTotal' => $totalRecords, // Total records available in the dataset
    'recordsFiltered' => $totalRecords, // Total records when filtered (if you have filtering logic)
    'data' => $data // Data rows to be displayed
];

// Return JSON response
echo json_encode($response);
