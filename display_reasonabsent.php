<?php

// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Get empno and datefrom from the request
$empno = $_GET['empno'];
$datefrom = $_GET['datefrom'];

// Escape variables for security
$empno = mysqli_real_escape_string($HRconnect, $empno);
$datefrom = mysqli_real_escape_string($HRconnect, $datefrom);

// Query to select reason and attachment column value based on empno and datefrom
$query = "SELECT reason, attachment FROM `lnd_absent_trainees` WHERE empno = '$empno' AND datefrom = '$datefrom'";

// Execute the query
$result = mysqli_query($HRconnect, $query);

// Check if query execution was successful
if ($result) {
    // Fetch the reason and attachment column values
    $row = mysqli_fetch_assoc($result);
    $reason = isset($row['reason']) ? $row['reason'] : ''; // Check if reason exists
    $attachment = isset($row['attachment']) ? $row['attachment'] : ''; // Check if attachment exists

    // Output the reason and attachment as JSON
    echo json_encode(array('reason' => $reason, 'attachment' => $attachment));
} else {
    // If query execution failed
    echo json_encode(array('error' => 'Failed to retrieve reason from the database'));
}

// Close the database connection
mysqli_close($HRconnect);
?>
