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
$status = $data['status'];

// Function to insert concern into the database
function insertConcern($HRconnect, $params)
{
    $sql = "INSERT INTO dtr_concerns
            (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $HRconnect->prepare($sql)) {
        $stmt->bind_param("sssssssssssssssssss", ...$params);
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Failed to execute statement'];
        }
        $stmt->close();
    } else {
        return ['success' => false, 'message' => 'Failed to prepare statement'];
    }
}

// Check if the concern already exists for the same empno and ConcernDate
$checkSql = "SELECT COUNT(*) AS concern_count FROM dtr_concerns WHERE empno = ? AND ConcernDate = ? AND concern = ?";
$stmt = $HRconnect->prepare($checkSql);
$stmt->bind_param("sss", $empno, $concernDate, $concern);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['concern_count'] > 0) {
    // Duplicate entry found
    echo json_encode(['success' => false, 'message' => 'You have already filed the same concern on this date.']);
} else {
    // Prepare the parameters for insertion based on the type of concern
    if ($concern === "Failure/Forgot to time in or time out" || $concern === "Failure/Forgot to break in or break out" || $concern === "Wrong filing of OBP" || $concern === "Not following break out and break in interval" || $concern === "Failure/Forgot to click half day") {
        $params = [
            $filling_date,
            $empno,
            $name,
            $userlevel,
            $branch,
            $userid,
            $area,
            $concernDate,
            $concern,
            $errortype,
            $data['actualIN'],
            $data['actualbOUT'],
            $data['actualBIN'],
            $data['actualOUT'],
            $data['proposedTimeIn'],
            $data['proposedBreakOut'],
            $data['proposedBreakIn'],
            $data['proposedTimeOut'],
            $status
        ];
    } else if ($concern === "Failure/Forgot to click broken schedule") {
        $params = [
            $filling_date,
            $empno,
            $name,
            $userlevel,
            $branch,
            $userid,
            $area,
            $concernDate,
            $concern,
            $errortype,
            $data['capturedBrokenSchedIn'],
            "No Break", // Directly insert "No Break"
            "No Break", // Directly insert "No Break"
            $data['capturedBrokenSchedOut'],
            $data['proposedBrokenSchedIn'],
            "No Break", // Directly insert "No Break"
            "No Break", // Directly insert "No Break"
            $data['proposedBrokenSchedOut'],
            $status
        ];
    } else if ($concern === "Wrong filing of overtime") {
        // Different SQL and binding for overtime concern
        $sql = "INSERT INTO dtr_concerns
                (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, othours, reason, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssssss",
                $filling_date,
                $empno,
                $name,
                $userlevel,
                $branch,
                $userid,
                $area,
                $concernDate,
                $concern,
                $errortype,
                $data['othours'],
                $data['concern_reason'],
                $status
            );
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        }
        exit;
    } else if ($concern === "Wrong filing of leave") {
        // Different SQL and binding for overtime concern
        $sql = "INSERT INTO dtr_concerns
                (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, vltype, reason, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssssss",
                $filling_date,
                $empno,
                $name,
                $userlevel,
                $branch,
                $userid,
                $area,
                $concernDate,
                $concern,
                $errortype,
                $data['vltype'],
                $data['concern_reason'],
                $status
            );
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        }
        exit;
    } else if ($concern === "Remove time inputs") {
        $sql = "INSERT INTO dtr_concerns
                (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, vltype, reason, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssssss",
                $filling_date,
                $empno,
                $name,
                $userlevel,
                $branch,
                $userid,
                $area,
                $concernDate,
                $concern,
                $errortype,
                $data['removeTimeinputs'],
                $data['concern_reason'],
                $status
            );
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute statement',
                    'error' => $stmt->error // Detailed SQL error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare statement',
                'error' => $HRconnect->error // Detailed MySQL connection error
            ]);
        }
        exit;
    } else if ($concern === "Time inputs did not sync") {

        $sql = "INSERT INTO dtr_concerns
            (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssssssssssss",
                $filling_date,
                $empno,
                $name,
                $userlevel,
                $branch,
                $userid,
                $area,
                $concernDate,
                $concern,
                $errortype,
                $data['actualIN'],
                $data['actualbOUT'],
                $data['actualBIN'],
                $data['actualOUT'],
                $data['proposedTimeIn'],
                $data['proposedBreakOut'],
                $data['proposedBreakIn'],
                $data['proposedTimeOut'],
                $status
            );
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute statement',
                    'error' => $stmt->error // Detailed SQL error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare statement',
                'error' => $HRconnect->error // Detailed MySQL connection error
            ]);
        }
        exit;
    }

    // Insert the concern using the insertConcern function
    $response = insertConcern($HRconnect, $params);
    echo json_encode($response);
}

$HRconnect->close();




// header('Content-Type: application/json');

// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
// echo json_encode(['success' => false, 'message' => 'Database connection failed']);
// exit;
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

// // Variables for "Forgot to click broken schedule"
// $capturedBrokenSchedIn = $data['capturedBrokenSchedIn'];
// $capturedNoBreakOut = $data['capturedNoBreakOut'];
// $capturedNoBreakIn = $data['capturedNoBreakIn'];
// $capturedBrokenSchedOut = $data['capturedBrokenSchedOut'];
// $proposedBrokenSchedIn = $data['proposedBrokenSchedIn'];
// $proposedNoBreakOut = $data['proposedNoBreakOut'];
// $proposedNoBreakIn = $data['proposedNoBreakIn'];
// $proposedBrokenSchedOut = $data['proposedBrokenSchedOut'];

// // Check if the concern already exists for the same empno and ConcernDate
// $checkSql = "SELECT COUNT(*) AS concern_count FROM dtr_concerns WHERE empno = ? AND ConcernDate = ? AND concern = ?";
// $stmt = $HRconnect->prepare($checkSql);
// $stmt->bind_param("sss", $empno, $concernDate, $concern);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();

// if ($row['concern_count'] > 0) {
// // Duplicate entry found
// echo json_encode(['success' => false, 'message' => 'You have already filed the same concern on this date.']);
// } else {
// // Insert logic based on selectedConcern
// if ($concern === "Forgot/Wrong to time in or time out" || $concern === "Forgot/Wrong to break in or break out") {
// // Insert for "Forgot/Wrong to time in or time out" and "Forgot/Wrong to break in or break out"
// $sql = "INSERT INTO dtr_concerns (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
// VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// if ($stmt = $HRconnect->prepare($sql)) {
// $stmt->bind_param("sssssssssssssssssss", $filling_date, $empno, $name, $userlevel, $branch, $userid, $area, $concernDate, $concern, $errortype, $actualIN, $actualbOUT, $actualBIN, $actualOUT, $proposedTimeIn, $proposedBreakOut, $proposedBreakIn, $proposedTimeOut, $status);
// if ($stmt->execute()) {
// echo json_encode(['success' => true]);
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
// }
// $stmt->close();
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
// }
// } else if ($concern === "Forgot to click broken schedule") {
// // Insert for "Forgot to click broken schedule"
// $sql = "INSERT INTO dtr_concerns (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
// VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// if ($stmt = $HRconnect->prepare($sql)) {
// $stmt->bind_param("sssssssssssssssssss", $filling_date, $empno, $name, $userlevel, $branch, $userid, $area, $concernDate, $concern, $errortype, $capturedBrokenSchedIn, $capturedNoBreakOut, $capturedNoBreakIn, $capturedBrokenSchedOut, $proposedBrokenSchedIn, $proposedNoBreakOut, $proposedNoBreakIn, $proposedBrokenSchedOut, $status);
// if ($stmt->execute()) {
// echo json_encode(['success' => true]);
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
// }
// $stmt->close();
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
// }
// } else {
// // Handle other cases if needed
// echo json_encode(['success' => false, 'message' => 'Invalid concern type']);
// }
// }

// $HRconnect->close();














// header('Content-Type: application/json');

// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
// echo json_encode(['success' => false, 'message' => 'Database connection failed']);
// exit;
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

// // Check if the concern already exists for the same empno and ConcernDate
// $checkSql = "SELECT COUNT(*) AS concern_count FROM dtr_concerns WHERE empno = ? AND ConcernDate = ? AND concern = ?";
// $stmt = $HRconnect->prepare($checkSql);
// $stmt->bind_param("sss", $empno, $concernDate, $concern);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();

// if ($row['concern_count'] > 0) {
// // Duplicate entry found
// echo json_encode(['success' => false, 'message' => 'You already filed the same concern on this date.']);
// } else {
// // No duplicate, proceed with insert
// $sql = "INSERT INTO dtr_concerns (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status)
// VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// if ($stmt = $HRconnect->prepare($sql)) {
// $stmt->bind_param("sssssssssssssssssss", $filling_date, $empno, $name, $userlevel, $branch, $userid, $area, $concernDate, $concern, $errortype, $actualIN, $actualbOUT, $actualBIN, $actualOUT, $proposedTimeIn, $proposedBreakOut, $proposedBreakIn, $proposedTimeOut, $status);
// if ($stmt->execute()) {
// echo json_encode(['success' => true]);
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to execute statement']);
// }
// $stmt->close();
// } else {
// echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
// }
// }

// $HRconnect->close();