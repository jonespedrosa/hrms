<?php
if (isset($_POST['pattern_id']) && isset($_POST['empno'])) {
    // Database connection
    $HRconnect = new mysqli("localhost", "root", "", "hrms");
    if ($HRconnect->connect_error) {
        die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $HRconnect->connect_error]));
    }

    // Prepare the SQL statement
    $empno = $HRconnect->real_escape_string($_POST['empno']);
    $pattern_id = $HRconnect->real_escape_string($_POST['pattern_id']);

    $sql = "UPDATE user_info SET pattern_id = '$pattern_id' WHERE empno = '$empno'";

    if ($HRconnect->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $HRconnect->error]);
    }

    $HRconnect->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
