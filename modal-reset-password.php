<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if empno is sent via POST
if (isset($_POST['empno'])) {
    // Sanitize empno input to prevent SQL injection
    $empno = mysqli_real_escape_string($HRconnect, $_POST['empno']);
    
    // Update query
    $updateQuery = "UPDATE `user_info` SET `secpass` = '1234' WHERE `empno` = '$empno'";
    
    // Execute the update query
    if (mysqli_query($HRconnect, $updateQuery)) {
        echo "Password reset successfully for Employee Number: $empno";
    } else {
        echo "Error updating record: " . mysqli_error($HRconnect);
    }
} else {
    echo "Employee number not provided";
}

// Close database connection
mysqli_close($HRconnect);
?>
