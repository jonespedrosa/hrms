<?php
// meal-allowance-remove-query.php
session_start();

if (isset($_POST['empno'])) {
    $hostname = "localhost";
    $username = "root";
    $password = ""; // Replace with your database password
    $database = "hrms"; // Replace with your database name

    $HRconnect = new mysqli($hostname, $username, $password, $database);

    if ($HRconnect->connect_error) {
        die("Connection failed: " . $HRconnect->connect_error);
    }

    $query = "DELETE FROM meal_allowance_list WHERE empno = ?";
    $stmt = $HRconnect->prepare($query);
    $stmt->bind_param("s", $_POST['empno']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error deleting record']);
    }

    $stmt->close();
    $HRconnect->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Employee number not provided']);
}
