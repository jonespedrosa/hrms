<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Check if the course ID is passed via POST request
if (isset($_POST['courseId'])) {
    // Sanitize the input to prevent SQL injection
    $courseId =  implode(',', $_POST['courseId']);
    // Query to fetch employee data for the selected course
    $sql = "SELECT
                tc.id AS course_id,
                tc.name AS course_name,
                ed.userid,
                ui.empno,
                ui.name AS employee_name,
                ui.branch,
                ui.department
            FROM
                lnd_training_courses tc
            INNER JOIN
                lnd_enrolled_dept ed ON tc.id = ed.course_id
            INNER JOIN
                user_info ui ON ed.userid = ui.userid
            WHERE
                ui.status IN ('active', '')
                AND tc.id IN ($courseId)
            ORDER BY
                ui.branch DESC";

    $result = mysqli_query($HRconnect, $sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
}

// Close database connection
mysqli_close($HRconnect);
