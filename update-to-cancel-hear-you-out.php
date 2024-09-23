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

// Set the time zone to the Philippines time zone
date_default_timezone_set('Asia/Manila');
$updated_at = date('Y-m-d H:i:s');

// Update the status to "Cancelled" and set the updated_at timestamp
$updateQuery = "UPDATE hear_you_out SET status = 'Cancelled', updated_at = ? WHERE id = ? AND empno = ?";
$stmt = mysqli_prepare($HRconnect, $updateQuery);

// Bind the updated_at timestamp along with id and empno
mysqli_stmt_bind_param($stmt, 'sii', $updated_at, $id, $empno);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Hear You Out successfully cancelled']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel Hear You Out']);
}

mysqli_stmt_close($stmt);
mysqli_close($HRconnect);
