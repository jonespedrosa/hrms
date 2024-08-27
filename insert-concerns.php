<?php
header('Content-Type: application/json');

$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$filling_date = date('Y-m-d H:i:s');
$empno = $data['empno'];
$name = $data['name'];
$userlevel = $data['userlevel'];
$branch = $data['branch'];
$userid = $data['userid'];
$area = $data['area'];
$concernDate = $data['concernDate'];
$concern = $data['selectedConcern'];
$errortype = $data['concernType'];
$actualIN = $data['actualIN'];
$actualbOUT = $data['actualbOUT'];
$actualBIN = $data['actualBIN'];
$actualOUT = $data['actualOUT'];
$proposedTimeIn = $data['proposedTimeIn'];
$proposedBreakOut = $data['proposedBreakOut'];
$proposedBreakIn = $data['proposedBreakIn'];
$proposedTimeOut = $data['proposedTimeOut'];
$status = $data['status'];

// Check if the concern already exists for the same empno and ConcernDate
$checkSql = "SELECT COUNT(*) AS concern_count FROM dtr_concerns WHERE empno = ? AND ConcernDate = ? AND concern = ?";
$stmt = $HRconnect->prepare($checkSql);
$stmt->bind_param("sss", $empno, $concernDate, $concern);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['concern_count'] > 0) {
    // Duplicate entry found
    echo json_encode(['success' => false, 'message' => 'You already filed the same concern on this date.']);
} else {
    // No duplicate, proceed with insert
    $sql = "INSERT INTO dtr_concerns (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $HRconnect->prepare($sql)) {
        $stmt->bind_param("sssssssssssssssssss", $filling_date, $empno, $name, $userlevel, $branch, $userid, $area, $concernDate, $concern, $errortype, $actualIN, $actualbOUT, $actualBIN, $actualOUT, $proposedTimeIn, $proposedBreakOut, $proposedBreakIn, $proposedTimeOut, $status);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
    }
}

$HRconnect->close();




// header('Content-Type: application/json');

// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     echo json_encode(['success' => false, 'message' => 'Database connection failed']);
//     exit;
// }

// $data = json_decode(file_get_contents('php://input'), true);

// $filling_date = date('Y-m-d H:i:s');
// $empno = $data['empno'];
// $name = $data['name'];
// $userlevel = $data['userlevel'];
// $branch = $data['branch'];
// $userid = $data['userid'];
// $area = $data['area'];
// $concernDate = $data['concernDate'];
// $concern = $data['selectedConcern'];
// $errortype = $data['concernType'];
// $actualIN = $data['actualIN'];
// $actualbOUT = $data['actualbOUT'];
// $actualBIN = $data['actualBIN'];
// $actualOUT = $data['actualOUT'];
// $proposedTimeIn = $data['proposedTimeIn'];
// $proposedBreakOut = $data['proposedBreakOut'];
// $proposedBreakIn = $data['proposedBreakIn'];
// $proposedTimeOut = $data['proposedTimeOut'];
// $status = $data['status'];

// $sql = "INSERT INTO dtr_concerns (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
//         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// if ($stmt = $HRconnect->prepare($sql)) {
//     $stmt->bind_param("sssssssssssssssssss", $filling_date, $empno, $name, $userlevel, $branch, $userid, $area, $concernDate, $concern, $errortype, $actualIN, $actualbOUT, $actualBIN, $actualOUT, $proposedTimeIn, $proposedBreakOut, $proposedBreakIn, $proposedTimeOut, $status);
//     if ($stmt->execute()) {
//         echo json_encode(['success' => true]);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
//     }
//     $stmt->close();
// } else {
//     echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
// }

// $HRconnect->close();
