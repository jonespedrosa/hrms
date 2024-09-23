<?php

header('Content-Type: application/json');

// Connect to the database
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get 'id' and 'empno' from the query parameters (from URL)
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$empno = isset($_GET['empno']) ? intval($_GET['empno']) : null;

// Check if 'id' and 'empno' are valid
if (!$id || !$empno) {
    echo json_encode(['success' => false, 'message' => 'Invalid ID or Employee Number']);
    exit;
}

// Update the status to "Cancelled"
$updateQuery = "UPDATE hear_you_out SET status = 'Cancelled' WHERE id = ? AND empno = ?";
$stmt = mysqli_prepare($HRconnect, $updateQuery);
mysqli_stmt_bind_param($stmt, 'ii', $id, $empno);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Hear You Out successfully cancelled']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel Hear You Out']);
}

mysqli_stmt_close($stmt);
mysqli_close($HRconnect);
