<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    error_log("Database connection failed: " . mysqli_connect_error());
    echo json_encode(array('error' => 'Database connection failed'));
    exit();
}

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    error_log("Invalid JSON data received: " . file_get_contents('php://input'));
    echo json_encode(array('error' => 'Invalid JSON data'));
    exit();
}

$evaluatedName = mysqli_real_escape_string($HRconnect, $data['evaluated_name']['name']);
$evaluatedPosition = mysqli_real_escape_string($HRconnect, $data['evaluated_name']['position']);
$evaluatedIdNumber = mysqli_real_escape_string($HRconnect, $data['evaluated_name']['idnumber']);

$responses = mysqli_real_escape_string($HRconnect, json_encode($data['responses']));

// Concatenate name and position
$evaluatedNameWithPosition = "$evaluatedName, $evaluatedPosition, $evaluatedIdNumber";

// Assuming you have session handling for empno
// session_start(); 
// $empno = isset($_SESSION['empno']) ? mysqli_real_escape_string($HRconnect, $_SESSION['empno']) : null;

// session_start(); 
// $empno = isset($_SESSION['user_validate']) ? mysqli_real_escape_string($HRconnect, $_SESSION['user_validate']) : null;

session_start();

// First check if 'empno' is set, if not, check 'user_validate'
if (isset($_SESSION['empno'])) {
    $empno = mysqli_real_escape_string($HRconnect, $_SESSION['empno']);
} elseif (isset($_SESSION['user_validate'])) {
    $empno = mysqli_real_escape_string($HRconnect, $_SESSION['user_validate']);
} else {
    $empno = null; // Default to null if neither session variable is set
}


if (!$empno) {
    echo json_encode(array('error' => 'Session expired or empno not set'));
    exit();
}

// Insert data into the database
$query = "INSERT INTO core_values (empno, responses) VALUES ('$empno', '$responses')";
$result = mysqli_query($HRconnect, $query);

if ($result) {
    echo json_encode(array('success' => true));
} else {
    error_log("Database query failed: " . mysqli_error($HRconnect));
    echo json_encode(array('error' => 'Failed to insert data'));
}

// Close the connection
mysqli_close($HRconnect);

