<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Query to fetch data
$query = "SELECT ui.userid, ui.empno, ui.name, ui.branch 
        FROM user_info ui
        INNER JOIN (
            SELECT DISTINCT userid 
            FROM lnd_enrolled_dept
        ) led ON ui.userid = led.userid
        WHERE ui.status IN ('active', '')  
        ORDER BY ui.empno ASC
        ";

$result = mysqli_query($HRconnect, $query);

// Initialize an array to store the fetched data
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode($data);

// Close the connection
mysqli_close($HRconnect);
