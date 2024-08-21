<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    // If connection fails, return an error message
    echo json_encode(array('error' => 'Failed to connect to database'));
    exit();
}

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $location = $_POST['courseName'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Get the URL parameters from the current page
    $urlParams = http_build_query($_GET);

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO lnd_training_batch (location, starttime, endtime) VALUES ('$location', '$startTime', '$endTime')";

    // Execute the SQL statement
    if (mysqli_query($HRconnect, $sql)) {
        // Data inserted successfully, perform redirect
        header("Location: trainings-details.php?" . $urlParams); // Redirect to trainings-details.php with parameters
        exit();
    } else {
        // Error occurred while inserting data
        echo json_encode(array('error' => 'Error inserting data: ' . mysqli_error($HRconnect)));
    }
} else {
    // No data submitted
    echo json_encode(array('error' => 'No data submitted'));
}

// Close the database connection
mysqli_close($HRconnect);
?>
