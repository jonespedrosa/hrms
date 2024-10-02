<?php

header('Content-Type: application/json');

$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$data = $_POST;
$attachment1 = isset($_FILES['attachment1']) ? $_FILES['attachment1'] : null; // Include attachment1
$attachment2 = isset($_FILES['attachment2']) ? $_FILES['attachment2'] : null; // Include attachment2

$filling_date = date('Y-m-d H:i:s');
$empno = isset($data['empno']) ? $data['empno'] : '';
$name = isset($data['name']) ? $data['name'] : '';
$userlevel = isset($data['userlevel']) ? $data['userlevel'] : '';
$branch = isset($data['branch']) ? $data['branch'] : '';
$userid = isset($data['userid']) ? $data['userid'] : '';
$area = isset($data['area']) ? $data['area'] : '';
$concernDate = isset($data['concernDate']) ? $data['concernDate'] : '';
$concern = isset($data['selectedConcern']) ? $data['selectedConcern'] : '';
$errortype = isset($data['concernType']) ? $data['concernType'] : '';
$status = isset($data['status']) ? $data['status'] : '';

// Check if the concern already exists for the same empno and ConcernDate
$checkSql = "SELECT COUNT(*) AS concern_count FROM dtr_concerns WHERE empno = ? AND ConcernDate = ? AND concern = ? AND status IN('Pending','Approved')";
$stmt = $HRconnect->prepare($checkSql);
$stmt->bind_param("sss", $empno, $concernDate, $concern);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['concern_count'] > 0) {
    // Duplicate entry found
    echo json_encode(['success' => false, 'message' => 'You have already filed the same concern on this date.']);
} else {

    if ($concern === "Time inputs did not sync" || $concern === "Misaligned time inputs") {

        // Handle file upload
        $attachment1Filename = null;
        if ($attachment1 && $attachment1['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'pdf/attachments/';

            $filenameExtension = 'attachments/';
            // Generate a random filename with the original extension
            $fileExtension = pathinfo($attachment1['name'], PATHINFO_EXTENSION);
            $randomFilename = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $randomFilename;

            if (move_uploaded_file($attachment1['tmp_name'], $uploadFile)) {
                $attachment1Filename = $filenameExtension . $randomFilename;  // Use the random filename
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
                exit;
            }
        }

        $sql = "INSERT INTO dtr_concerns
        (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status, attachment1)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "ssssssssssssssssssss",
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
                $status,
                $attachment1Filename
            );
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute statement',
                    'error' => $stmt->error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare statement',
                'error' => $HRconnect->error
            ]);
        }
    } else if ($concern === "Persona error" || $concern === "Hardware malfunction" || $concern === "Emergency time out" || $concern === "Fingerprint problem") {

        // Handle file uploads
        $uploadDir = 'pdf/attachments/';
        $attachment1Filename = null;
        $attachment2Filename = null; // Variable for attachment2

        // Process attachment1 if it exists
        if ($attachment1 && $attachment1['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($attachment1['name'], PATHINFO_EXTENSION);
            $randomFilename = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $randomFilename;

            if (move_uploaded_file($attachment1['tmp_name'], $uploadFile)) {
                $attachment1Filename = 'attachments/' . $randomFilename; // Use the random filename
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload attachment1']);
                exit;
            }
        }

        // Process attachment2 if it exists
        if ($attachment2 && $attachment2['error'] === UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($attachment2['name'], PATHINFO_EXTENSION);
            $randomFilename = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $randomFilename;

            if (move_uploaded_file($attachment2['tmp_name'], $uploadFile)) {
                $attachment2Filename = 'attachments/' . $randomFilename; // Use the random filename for attachment2
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload attachment2']);
                exit;
            }
        }

        // SQL query for insertion
        $sql = "INSERT INTO dtr_concerns
        (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status, attachment1, attachment2)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; // Include attachment2

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "sssssssssssssssssssss",
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
                $status,
                $attachment1Filename,  // Include attachment1 filename
                $attachment2Filename   // Include attachment2 filename
            );

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute statement',
                    'error' => $stmt->error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare statement',
                'error' => $HRconnect->error
            ]);
        }
    } else if ($concern === "Broken Schedule did not sync") {

        // Handle file upload
        $attachment1Filename = null;
        if (isset($attachment1) && $attachment1['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'pdf/attachments/';
            $filenameExtension = 'attachments/';

            // Generate a random filename with the original extension
            $fileExtension = pathinfo($attachment1['name'], PATHINFO_EXTENSION);
            $randomFilename = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $randomFilename;

            if (move_uploaded_file($attachment1['tmp_name'], $uploadFile)) {
                $attachment1Filename = $filenameExtension . $randomFilename;
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
                exit;
            }
        }

        // SQL query for insertion
        $sql = "INSERT INTO dtr_concerns
            (filing_date, empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, actualIN, actualbOUT, actualBIN, actualOUT, newIN, newbOUT, newbIN, newOUT, status, attachment1)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $HRconnect->prepare($sql)) {
            $stmt->bind_param(
                "ssssssssssssssssssss",
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
                $data['actualbOUT'],
                $data['actualBIN'],
                $data['capturedBrokenSchedOut'],
                $data['proposedBrokenSchedIn'],
                $data['newbIN'],
                $data['newbOUT'],
                $data['proposedBrokenSchedOut'],
                $status,
                $attachment1Filename
            );

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute statement',
                    'error' => $stmt->error
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare statement',
                'error' => $HRconnect->error
            ]);
        }
    } else if ($concern === "File broken sched overtime") {

        $check_sql = "SELECT COUNT(*) AS ot_count FROM overunder WHERE empno = ? AND otdatefrom = ? AND ottype = ?";
        if ($check_stmt = $HRconnect->prepare($check_sql)) {
            $ot_type = isset($data['ottype']) ? $data['ottype'] : '';
            $check_stmt->bind_param("sss", $empno, $concernDate, $ot_type);
            $check_stmt->execute();
            $check_stmt->bind_result($ot_count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($ot_count > 0) {
                // You have already submitted the overtime for the broken schedule on this date..
                echo json_encode(['success' => false, 'message' => 'You have already submitted the broken schedule overtime on this date.']);
            } else {
                // Insert the new record
                $sql = "INSERT INTO overunder (empno, otdatefrom, ottype, othours, otreason, otstatus, timedate) VALUES (?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = $HRconnect->prepare($sql)) {
                    $stmt->bind_param(
                        "sssssss",
                        $empno,
                        $concernDate,
                        $data['ottype'],
                        $data['othours'],
                        $data['concern_reason'],
                        $status,
                        $filling_date
                    );
                    if ($stmt->execute()) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to execute statement', 'error' => $stmt->error]);
                    }
                    $stmt->close();
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement', 'error' => $HRconnect->error]);
                }
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare check statement', 'error' => $HRconnect->error]);
        }
    }
}

$HRconnect->close();
