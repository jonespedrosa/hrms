<?php

// Start session if needed
// session_start();

// Database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch active employees
$query = "SELECT userid, empno, name, branch FROM `user_info` WHERE `status` IN ('active', '')";
$result = mysqli_query($HRconnect, $query);

// Initialize an array to store employee data
$employees = array();

// Fetch data from the result set
while ($row = mysqli_fetch_assoc($result)) {
    $employees[] = $row;
}

// Close connection
mysqli_close($HRconnect);

// Output JSON encoded data
echo json_encode($employees);
