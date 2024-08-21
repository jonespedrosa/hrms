<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the raw POST data
$data = file_get_contents("php://input");
$selectedEmployees = json_decode($data, true);

foreach ($selectedEmployees as $employee) {
    $empno = mysqli_real_escape_string($HRconnect, $employee['empno']);
    $name = mysqli_real_escape_string($HRconnect, $employee['name']);
    $branch = mysqli_real_escape_string($HRconnect, $employee['branch']);

    // Check if the employee already exists
    $checkQuery = "SELECT COUNT(*) as count FROM meal_allowance_list WHERE empno = '$empno'";
    var_dump($checkQuery);
    $result = mysqli_query($HRconnect, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] == 0) {
        // If the employee doesn't exist, insert into the table
        $insertQuery = "INSERT INTO meal_allowance_list (empno, name, branch) VALUES ('$empno', '$name', '$branch')";
        mysqli_query($HRconnect, $insertQuery);
    }
}

mysqli_close($HRconnect);
echo json_encode(['status' => 'success']);
