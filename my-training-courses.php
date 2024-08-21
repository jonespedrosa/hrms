<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_validate'])) {
    // Redirect to login page
    header("Location: index.php?m=2"); // m=2 to indicate access failure
    exit();
}

$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
$empno = $_GET['empno'] ?? null;

if ($empno !== $_SESSION['user_validate']) {
    // Redirect to login page
    header("Location: index.php?m=2"); // m=2 to indicate access failure
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../images/logoo.png">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Include SweetAlert2 CSS and JS files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style type="text/css">
        html {
            background-color: #F1F0F0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Apply Poppins font to body */
        }

        .course-container {
            background: linear-gradient(45deg, #DAEDFC, #FFFFFF) !important;
            /* Gradient background */
            /* color: #fff; */
            padding: 0.25em 0.5em;
            border-radius: 1.5em;
            /* font-weight: bold !important; */
            /* font-size: 0.9em; */
            /* text-transform: uppercase; */
            cursor: pointer;
            position: relative;
            -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        }

        .course-container:before,
        .course-container:after {
            content: "";
            position: absolute;
            z-index: -1;
            -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            -moz-box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
            top: 50%;
            bottom: 0;
            left: 10px;
            right: 10px;
            -moz-border-radius: 100px / 10px;
            border-radius: 100px / 10px;
        }

        .course-container:after {
            right: 10px;
            left: auto;
            -webkit-transform: skew(8deg) rotate(3deg);
            -moz-transform: skew(8deg) rotate(3deg);
            -ms-transform: skew(8deg) rotate(3deg);
            -o-transform: skew(8deg) rotate(3deg);
            transform: skew(8deg) rotate(3deg);
        }

        .course-container:hover {
            background: linear-gradient(45deg, #ECF6FE, #88F293) !important;
            /* Gradient background for hover state */
        }

        .cursor-pointer {
            cursor: pointer !important;
        }

        .new-button {
            color: white !important;
        }

        .button-container {
            display: flex;
            margin-bottom: 10px;
            /* Adjust as needed */
        }

        #CoursesDiv {
            display: flex;
            flex-direction: column;
        }

        .custom-width {
            width: 40%;
        }

        .custom-nav {
            background-color: #f0f0f0;
        }

        .custom-progress-bar {
            background-color: #FDBD0F;
        }

        /* .custom-progress-bar {
            background-color: #FDBD0F;
        } */


        /* .status-sections div {
            display: none;
        } */

        .bg-yellow {
            background-color: #FCEAEA;
        }

        .rounded-notification {
            border-radius: 1.5rem;
        }

        .px-2 {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }

        .py-1 {
            padding-top: 0.5rem !important;
            padding-bottom: 0.1rem !important;
        }

        #content-Inprogress {
            display: block;
        }

        .warning-bg {
            background-color: #FCEAEA;
            color: red;
            /* Define your warning background color */
        }

        .default-bg {
            background-color: #818A8A;
            /* Define your default background color */
        }

        .status-button {
            background-color: white;
            color: black;
            border: 1px solid #8B2331;
            padding: 5px 10px;
            outline: none !important;
            /* Remove outline */
            cursor: pointer;
        }

        .status-button.active {
            background-color: maroon;
            color: white;
            font-weight: 600;
        }

        #content-Inprogress {
            display: block;
        }

        .status-button:first-child {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .status-button:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        #parentsTextHeader {
            display: flex;
            justify-content: flex-end;
        }

        .col {
            float: left;
        }

        #leftDiv {
            width: 80%;
        }

        #rightDiv {
            width: 20%;
        }

        #CoursesDiv,
        #upComingSchedule,
        #notificationUser {
            background-color: white !important;
        }

        .swal2-title.custom-title {
            font-size: 3em;
            /* Increase title size */
        }

        .swal2-icon.custom-icon::before {
            font-size: 3em;
            /* Increase icon size */
        }

        /* CSS for the header */
        .header {
            background: linear-gradient(to right, #5B101B, #932634, #932634, #5B101B);
            color: white;
            padding: 10px;
            text-align: center;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        @page {
            size: portrait
        }

        body {
            page-break-before: avoid;
        }

        @media (max-width: 800px) {
            .container {
                display: flex;
                flex-direction: column;
            }

            #leftDiv,
            #rightDiv {
                width: 100%;
            }

            #leftDiv {
                order: 1;
            }

            #rightDiv {
                order: 2;
                margin-top: 5px;
            }

            /* .d-flex {
                flex-direction: column;
            } */

            .d-flex h5 {
                font-size: 11px;
                /* Adjust font size as needed */
            }

            .d-flex span {
                font-size: 11px;
                /* Adjust font size as needed */
            }

            a.text-center h4 {
                font-size: 15px;
                margin-top: 10px !important;
                /* Adjust font size as needed */
            }

            .btn-primary {
                margin-top: 5px !important;
            }

            #LabelText {
                flex-direction: column;
            }


            #employeeDetails {
                flex-direction: column;
            }

            #employeeDetails h5 {
                font-size: 12px;
                /* Adjust the font size as needed */
            }

            #employeeDetails span {
                font-size: 12px;
                /* Adjust the font size of the span elements as needed */
            }

            #buttonFunction {
                margin-top: 1rem;
                /* Add margin to separate buttons from the title */
            }

            #myTrainingCourseTitle {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="images/logoo.png" height="40" alt="">
        <h4><i class="text-light ml-3 mr-2 py-3" style="font-family:Times New Roman, cursive;font-size:100%;">Mary Grace Café</i></h4>
    </div>
    <div class="mt-4">
        <div id="leftDiv" class="col">
            <div id="CoursesDiv" class="border rounded p-3">
                <!-- User Details  -->
                <div id="employeeDetails" class="d-flex justify-content-between border-bottom pb-2">
                    <div>
                        <h5 class="mb-1 mt-1">EMPLOYEE #: <span id="employeeId" style="font-weight: bold; color: #DC3545;"></span></h5>
                        <h5 class="mb-1 mt-1">NAME: <span id="employeeName" style="font-weight: bold;"></span></h5>
                    </div>
                    <div>
                        <h5 class="mb-1 mt-1">BRANCH: <span id="employeeBranch" style="font-weight: bold;"></span></h5>
                        <h5 class="mb-1 mt-1">POSITION: <span id="employeePosition" style="font-weight: bold;"></span></h5>
                    </div>
                </div>
                <div id="LabelText" class="d-flex justify-content-between align-items-center">
                    <!-- Label function  -->
                    <a href="" class="text-center mt-0 mb-0">
                        <h4 class="mr-3 mb-0 mt-0" id="myTrainingCourseTitle" style="font-weight: bold;">My Training Courses</h4>
                    </a>
                    <!-- button function -->
                    <div id="buttonFunction" class="button-container mt-3">
                        <button id="inProgressButton" class="status-button">In-Progress</button>
                        <button id="completedButton" class="status-button">Completed</button>
                    </div>
                </div>
                <hr class="mb-2 mt-2">
                <!-- In-progress section  -->
                <div class="status-sections">
                    <div id="content-Inprogress" class="p-2">
                        <hr>
                    </div>
                    <!-- Completed section  -->
                    <div id="content-Completed" class="p-2">
                        <hr>
                    </div>
                </div>
            </div>
        </div>
        <div id="rightDiv" class="col">
            <div id="upComingSchedule" class="rounded mb-2 p-3">
                <div>
                    <div class="rounded p-3" style="background-color: #F0F0F0;">
                        <h5 id="titleTopics" style="font-weight: bold;"></h5>
                        <div class="d-flex">
                            <h5 class="mb-0">
                                <i class="far fa-calendar icon"></i>
                                <span id="datefromTraining" class="mr-3"></span>
                            </h5>
                            <h5 class="mb-0">
                                <i class="far fa-clock icon"></i>
                                <span id="scheduleTime" class="mr-3"></span>
                            </h5>
                            <h5>
                                <i class="fas fa-map-marker-alt icon"></i>
                                <span id="locationTraining"></span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div id="notificationUser" class="border rounded p-3">
                <div id="notificationHeader" class="d-flex justify-content-between">
                    <a href="">
                        <h4 class="mb-2 mt-0" style="font-weight: bold;">Notification</h4>
                    </a>
                    <span id="countAbsent"></span>
                </div>
                <h5 id="notificationMessage" class="mb-1"></h5>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            document.getElementById("content-Inprogress").style.display = "block";
            document.getElementById("content-Completed").style.display = "none";
            document.getElementById("inProgressButton").classList.add("active");
            document.getElementById("completedButton").classList.remove("active");

            // Extract EmpNoId from the URL
            var urlParams = new URLSearchParams(window.location.search);
            var empNoId = urlParams.get('empno');

            $.ajax({
                url: 'fetch_empno_course.php',
                type: 'GET',
                data: {
                    EmpNoId: empNoId
                },
                dataType: 'json',
                success: function(response) {
                    // Log the response for debugging
                    // console.log("Employee number details: ", response);

                    var userInfo = response.user_info[0]; // Assuming user_info is an array with one object

                    // Populate the HTML elements with the data in all capital letters
                    document.getElementById('employeeId').innerText = userInfo.empno.toUpperCase();
                    document.getElementById('employeeName').innerText = userInfo.name.toUpperCase();
                    document.getElementById('employeeBranch').innerText = userInfo.branch.toUpperCase();
                    document.getElementById('employeePosition').innerText = userInfo.position.toUpperCase();

                    // Access training data from the response object
                    var trainingData = response.training_data;

                    // Count the number of absent entries with an empty or null reason
                    var absentCount = response.absent_data.filter(entry => !entry.reason).length;

                    // Determine the background color based on the count
                    var backgroundColorClass;
                    if (absentCount > 0) {
                        backgroundColorClass = 'warning-bg'; // You can define different classes for different background colors
                    } else {
                        backgroundColorClass = 'default-bg'; // Default background color
                    }

                    // Update the HTML with the dynamic background color and the absent count
                    $('#countAbsent').html('<span class="badge badge-pill ' + backgroundColorClass + '">' + absentCount + ' new message</span>');

                    // Check if there are absent entries with empty or null reasons
                    if (absentCount > 0) {
                        // Empty the notification message container
                        $('#notificationMessage').empty();

                        // Filter the absent entries to include only those with an empty or null reason
                        var filteredAbsentData = response.absent_data.filter(entry => !entry.reason);

                        // Iterate through each filtered absent entry
                        filteredAbsentData.forEach(function(entry, index) {
                            // Create a new div for each absent entry
                            var notificationDiv = $('<div class="absent-notification"></div>');

                            // Display a message for each absent entry
                            var notificationMsg = $('<div><i class="fas fa-info-circle mr-2"></i>You were marked as absent by the instructor.</div>');
                            notificationDiv.append(notificationMsg);

                            // Extract and format the date from the entry
                            var dateFrom = entry.datefrom;
                            var formattedDate = new Date(dateFrom).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            // Create a new div to hold the date and the view button with d-flex justify-content-between
                            var dateViewDiv = $('<div class="d-flex justify-content-between mb-2 mt-2"></div>');

                            // Display the formatted date
                            var dateDiv = $('<div><i>Date Absent: </i><span style="color: #FF0808; font-weight: bold;">' + formattedDate + '</span></div>');
                            dateViewDiv.append(dateDiv);

                            // Add a "View" button with a unique identifier
                            var viewButton = $('<button class="btn btn-primary" data-index="' + index + '">View</button>');
                            dateViewDiv.append(viewButton);

                            // Append the dateViewDiv to the notification div
                            notificationDiv.append(dateViewDiv);

                            // Append the notification div to the notification message container
                            $('#notificationMessage').append(notificationDiv);
                            $('#notificationMessage').append('<hr class="mt-4 mb-4">');
                        });

                        // Add an event listener to handle click on the "View" button
                        $(document).on('click', '.btn-primary', function() {
                            // Get the index of the clicked button
                            var index = $(this).data('index');

                            // Extract the necessary data from the filteredAbsentData based on the index
                            var absentEntry = filteredAbsentData[index];

                            // Find the matching training entry
                            var trainingEntry = response.training_data.find(function(entry) {
                                return entry.course_id === absentEntry.course_id && entry.schedule_id === absentEntry.schedule_id;
                            });

                            // Show loading message
                            Swal.fire({
                                title: 'Now loading ...',
                                // icon: 'info',
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                timer: 1500,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                customClass: {
                                    title: 'custom-title', // Custom class for title
                                    icon: 'custom-icon' // Custom class for icon
                                }


                            }).then(() => {
                                // Your condition check and subsequent actions
                                if (trainingEntry) {
                                    var empno = absentEntry.empno;
                                    var courseName = JSON.parse(trainingEntry.no_of_courses)[absentEntry.course_id].courseName;
                                    var courseId = absentEntry.course_id;
                                    var scheduleId = absentEntry.schedule_id;

                                    // Construct the URL for redirection
                                    var redirectURL = 'view-my-training-details.php?empno=' + empno + '&CourseName=' + encodeURIComponent(courseName) + '&course_id=' + courseId + '&schedule_id=' + scheduleId;
                                    window.location.href = redirectURL;

                                } else {
                                    // Show error message if training entry is not found
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Training entry not found for the absent data.',
                                        icon: 'error',
                                        confirmButtonText: 'OK'
                                    });
                                }
                            });

                        });

                    } else {
                        // Display an italicized "No records available" message if there are no absent entries with empty or null reasons
                        $('#notificationMessage').html('');
                    }

                    // Initialize an object to store data for each batch
                    var batches = {};

                    // Ensure the training data is not empty
                    if (trainingData.length > 0) {
                        trainingData.forEach(function(batch) {
                            // Parse the JSON string from the batch
                            var topics = JSON.parse(batch.list_of_topics);

                            // Initialize the batch if not already done
                            if (!batches.hasOwnProperty(batch.batch_number)) {
                                batches[batch.batch_number] = {
                                    courseName: JSON.parse(batch.no_of_courses)[batch.course_id].courseName,
                                    startDate: new Date(batch.datefrom),
                                    endDate: new Date(batch.datefrom),
                                    totalTopics: 0,
                                    completedTopics: 0,
                                    topics: [],
                                    course_id: batch.course_id, // Store course_id
                                    schedule_id: batch.schedule_id // Store schedule_id
                                };
                            }

                            // Update the start and end dates for the batch
                            var batchData = batches[batch.batch_number];
                            var currentDate = new Date(batch.datefrom);
                            if (currentDate < batchData.startDate) {
                                batchData.startDate = currentDate;
                            }
                            if (currentDate > batchData.endDate) {
                                batchData.endDate = currentDate;
                            }

                            // Increment total topics count and check completion status
                            batchData.totalTopics += parseInt(batch.no_of_topics_count);
                            if (batch.status === "Completed") {
                                batchData.completedTopics += parseInt(batch.no_of_topics_count);
                            }

                            // Add topics to the batch
                            batchData.topics = batchData.topics.concat(topics);
                        });
                    }

                    // Generate HTML dynamically for each batch
                    var inProgressHtml = '';
                    var completedHtml = '';
                    var batchCount = 0;
                    var rowStart = '<div class="row">'; // Start of each row
                    var rowEnd = '</div>'; // End of each row

                    for (var batchNumber in batches) {
                        if (batches.hasOwnProperty(batchNumber)) {
                            var batchData = batches[batchNumber];

                            // Format the start and end dates
                            var options = {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };

                            var startDate = batchData.startDate.toLocaleDateString('en-US', options);
                            var endDate = batchData.endDate.toLocaleDateString('en-US', options);

                            // Calculate the progress percentage and round it to an integer
                            var progressPercentage = Math.round((batchData.completedTopics / batchData.totalTopics) * 100);

                            // Determine the color of the progress bar
                            var progressBarColor = (batchData.completedTopics === batchData.totalTopics) ? 'green' : '#FFBE0F';

                            // Append batch details to HTML
                            var batchHtml = '<div class="course-container col-md-4 mr-3" style="border: 1px solid #ccc; border-radius: 10px; padding: 10px; margin-bottom: 10px; cursor: pointer; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" data-empno="' + empNoId + '" data-course-name="' + batchData.courseName + '" data-course-id="' + batchData.course_id + '" data-schedule-id="' + batchData.schedule_id + '">';
                            batchHtml += '<h4 class="mb-2 mt-1"><strong>' + batchData.courseName + '</strong></h4>';
                            // Add date range as a separate paragraph
                            batchHtml += '<p class="mb-1">' + startDate + ' - ' + endDate + '</p>';
                            // Add progress bar
                            batchHtml += '<div class="progress mb-1">';
                            batchHtml += '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="' + progressPercentage + '" aria-valuemin="0" aria-valuemax="100" style="width:' + progressPercentage + '%; background-color:' + progressBarColor + ';">';
                            batchHtml += progressPercentage + '%'; // Remove .toFixed(2)
                            batchHtml += '</div>';
                            batchHtml += '</div>';
                            // Add total topics count and completed topics count with justify-between
                            batchHtml += '<div style="display: flex; justify-content: space-between;">';
                            batchHtml += '<p>' + batchData.totalTopics + ' Topics</p>';
                            batchHtml += '<p>' + batchData.completedTopics + '/' + batchData.totalTopics + ' Completed</p>';
                            batchHtml += '</div>';
                            batchHtml += '</div>';

                            // Add the HTML to the correct section based on completion status
                            if (batchData.completedTopics === batchData.totalTopics) {
                                completedHtml += batchHtml;
                            } else {
                                inProgressHtml += batchHtml;
                            }

                            batchCount++;
                        }
                    }

                    // Check if there are no records and add the message accordingly
                    if (batchCount === 0) {
                        inProgressHtml = '<em>No records available</em>';
                        completedHtml = '<em>No records available</em>';
                    }

                    // Insert generated HTML into the respective divs
                    $("#content-Inprogress").html(inProgressHtml);
                    $("#content-Completed").html(completedHtml);

                    // Add click event listener to each course container
                    $('.course-container').on('click', function() {
                        var empNoId = $(this).data('empno');
                        var courseName = $(this).data('course-name');
                        var courseId = $(this).data('course-id');
                        var scheduleId = $(this).data('schedule-id');
                        redirectToDetails(empNoId, courseName, courseId, scheduleId);
                    });

                    // Toggle button functionality
                    $('#inProgressButton').on('click', function() {
                        $('#content-Inprogress').show();
                        $('#content-Completed').hide();
                    });

                    $('#completedButton').on('click', function() {
                        $('#content-Inprogress').hide();
                        $('#content-Completed').show();
                    });

                    // Initially show the in-progress section
                    $('#content-Inprogress').show();
                    $('#content-Completed').hide();

                    // Access the training data array within the response object
                    var trainingData = response.training_data;

                    // Filter in-progress schedules from training data
                    var inProgressSchedules = trainingData.filter(function(batch) {
                        return batch.status === "In-Progress";
                    });

                    // Initialize the in-progress schedules HTML with the "Upcoming Training Schedules" text
                    var inProgressHtml = '<div id="upComingSchedule" class="rounded mb-3">' +
                        '<div>' +
                        '<a href="#">' +
                        '<h4 class="mb-2 mt-0" style="font-weight: bold;">Upcoming Training Schedules</h4>' +
                        '</a>' +
                        '</div>' +
                        '</div>';


                    if (inProgressSchedules.length === 0) {
                        inProgressHtml += '<em>No records available</em>';
                    }

                    inProgressSchedules.forEach(function(schedule) {
                        // Extracting the topicsName dynamically from list_of_topics
                        var topicsNames = [];
                        try {
                            var topicsArray = JSON.parse(schedule.list_of_topics);
                            topicsArray.forEach(function(topic) {
                                topicsNames.push(topic.topicsName);
                            });
                        } catch (error) {
                            console.error("Error parsing JSON:", error);
                        }

                        // Format the start and end times
                        var startTime = schedule.starttime.substring(0, 5);
                        var endTime = schedule.endtime.substring(0, 5);

                        // Convert and format the date
                        var date = new Date(schedule.datefrom);
                        var options = {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        };
                        var formattedDate = date.toLocaleDateString('en-US', options);
                        // Generate the list of topics in HTML
                        var topicsListHtml = '<ul style="padding-left: 2.5rem;">';
                        topicsNames.forEach(function(topic) {
                            topicsListHtml += '<li><h4 style="font-weight: bold;" class="mb-1 mt-1">' + topic + '</h4></li>';
                        });
                        topicsListHtml += '</ul>';

                        // Append schedule details to HTML
                        inProgressHtml += '<div class="rounded p-3 mb-3" style="background-color: #F6FBFE; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">' +
                            '<div id="titleTopics" style="font-weight: bold; color: #098CFD;" class="mt-1">' + topicsListHtml + '</div>' +
                            '<h5 class="mb-0">' +
                            '<h4><span id="datefromTraining" class="mr-3" style="font-weight:bold;">➡️ ' + formattedDate + '</span></h4>' +

                            '</h5>' +
                            '<div class="d-flex">' +
                            '<h5 class="mb-0 mt-1">' +
                            '<i class="far fa-clock icon mr-2"></i>' +
                            '<span id="scheduleTime" class="mr-3">' + startTime + ' - ' + endTime + '</span>' +
                            '</h5>' +
                            '<h5 class="mb-0 mt-1">' +
                            '<i class="fas fa-map-marker-alt icon mr-2"></i>' +
                            '<span id="locationTraining">' + schedule.location + '</span>' +
                            '</h5>' +
                            '</div>' +
                            '</div>';
                    });

                    // Insert generated HTML for in-progress schedules into the upComingSchedule div
                    $("#upComingSchedule").html(inProgressHtml);

                    // Add click event listener to each in-progress course container in upComingSchedule
                    $('#upComingSchedule .course-container').on('click', function() {
                        var empNoId = $(this).data('empno');
                        var courseName = $(this).data('course-name');
                        var courseId = $(this).data('course-id');
                        var scheduleId = $(this).data('schedule-id');
                        redirectToDetails(empNoId, courseName, courseId, scheduleId);
                    });

                },

                error: function(xhr, status, error) {
                    // Log error if AJAX request fails
                    console.error("Error fetching data:", error);
                }
            });

        };

        // Redirect function
        function redirectToDetails(empNoId, courseName, courseId, scheduleId) {
            // Show loading message
            Swal.fire({
                title: 'Now loading ...',
                // icon: 'info',
                allowEscapeKey: false,
                allowOutsideClick: false,
                timerProgressBar: true,
                showConfirmButton: false,
                timer: 1500,
                didOpen: () => {
                    Swal.showLoading();
                },
                customClass: {
                    title: 'custom-title', // Custom class for title
                    icon: 'custom-icon' // Custom class for icon
                }
            }).then(() => {
                // Encode the course name to ensure proper URL encoding
                var encodedCourseName = encodeURIComponent(courseName);

                // Construct the URL with parameters
                var url = 'view-my-training-details.php?empno=' + empNoId + '&CourseName=' + encodedCourseName + '&course_id=' + courseId + '&schedule_id=' + scheduleId;

                // Redirect to the new page
                window.location.href = url;
            });
        }

        document.getElementById("inProgressButton").addEventListener("click", function() {
            document.getElementById("content-Inprogress").style.display = "block";
            document.getElementById("content-Completed").style.display = "none";
            document.getElementById("inProgressButton").classList.add("active", "bold-font");
            document.getElementById("completedButton").classList.remove("active");
        });

        document.getElementById("completedButton").addEventListener("click", function() {
            document.getElementById("content-Inprogress").style.display = "none";
            document.getElementById("content-Completed").style.display = "block";
            document.getElementById("completedButton").classList.add("active");
            document.getElementById("inProgressButton").classList.remove("active");
        });
    </script>
</body>

</html>