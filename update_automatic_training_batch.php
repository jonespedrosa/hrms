<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to database: " . mysqli_connect_error();
    exit();
}

// Get today's date
$today = date('Y-m-d');

// Update the status to 'Completed' where datefrom is in the past and status is not 'Completed'
$update_sql = "UPDATE lnd_training_batch SET status = 'Completed' WHERE datefrom < ? AND status != 'Completed'";

// Prepare statement
$stmt = mysqli_prepare($HRconnect, $update_sql);

// Bind parameters
mysqli_stmt_bind_param($stmt, "s", $today);

// Execute statement
if (mysqli_stmt_execute($stmt)) {
    echo "Status updated successfully.";
} else {
    echo "Error updating status: " . mysqli_error($HRconnect);
}

// Close the statement
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($HRconnect);
