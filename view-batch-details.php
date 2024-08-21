<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$sql = "SELECT userid, mothercafe, branch, userlevel, name, empno FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$userlevel = $row['userlevel'];
$empno = $row['empno'];

$sql1 = "SELECT areatype, username FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->

</head>

<style>
    .badge-success {
        background-color: #1CC88A !important;
        color: #fff;
        padding: 0.25em 0.5em;
        border-radius: 1.5em;
        font-weight: bold !important;
        /* font-size: 0.9em; */
        text-transform: uppercase;
        cursor: pointer;
        position: relative;
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
    }

    .badge-success:before,
    .badge-success:after {
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

    .badge-success:after {
        right: 10px;
        left: auto;
        transform: skew(8deg) rotate(3deg);
    }

    .badge-success:hover {
        background-color: #20E19E !important;
    }

    /* Flexbox wrapping */
    #branchBadge {
        display: flex;
        flex-wrap: wrap;
    }

    /* Add margin-top to wrapped items */
    /* #branchBadge>.badge {
        margin-top: 0.5em;
    } */

    #branchBadge>.badge:first-child {
        margin-top: 0;
        /* No margin-top for the first item */
    }

    /* Media query for small screens */
    @media (max-width: 600px) {
        #branchORdeparmentDetails {
            flex-direction: column;
        }

        .badge-success {
            margin: 0.5em 0;
            /* Add margin for better spacing in column layout */
        }
    }

    /* .badge-success {
        background-color: #1CC88A !important;
        color: #fff;
        padding: 0.25em 0.5em;
        border-radius: 1.5em;
        font-weight: bold !important;
        text-transform: uppercase;
    }

    .badge-success {
        position: relative;
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
    }

    .badge-success :before,
    .badge-success :after {
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

    .badge-success :after {
        right: 10px;
        left: auto;
        -webkit-transform: skew(8deg) rotate(3deg);
        -moz-transform: skew(8deg) rotate(3deg);
        -ms-transform: skew(8deg) rotate(3deg);
        -o-transform: skew(8deg) rotate(3deg);
        transform: skew(8deg) rotate(3deg);
    } */

    .course-container {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .course-title {
        font-weight: bold;
    }

    .topic-container {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 5px;
        margin: 5px 0;
    }

    .topic-title {
        font-weight: bold;
    }


    .text-center {
        text-align: center;
    }

    #displayTableTrainingSched td {
        text-align: center;
    }

    .hidden-column {
        display: none;
    }

    .my-actions-class .swal2-actions {
        display: flex;
        justify-content: space-between;
    }

    .my-actions-class .swal2-cancel {
        margin-right: 10px;
        /* Adjust as needed */
        justify-self: flex-start;
        /* Add this line */
    }

    tr.disabled {
        background-color: #f2f2f2;
        /* Set the background color to a light gray */
        pointer-events: none;
        /* Disable pointer events on the row */
        cursor: not-allowed;
        /* Set the cursor style to not-allowed */
    }

    tr.disabled input[type="time"] {
        background-color: #f2f2f2;
        /* Set the background color of time inputs to match the row background */
        color: #777;
        /* Set the text color of time inputs to a darker shade */
        cursor: not-allowed;
        /* Set the cursor style to not-allowed */
    }

    .course-container {
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 10px;
        /* box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); */
    }

    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    textarea[readonly] {
        background-color: #ffffff !important;
        /* Set background color to white */
        cursor: default;
        /* Set cursor to default */
    }

    .course-name {
        font-weight: bold;
        margin-top: 10px;
    }

    .course-description {
        margin-left: 10px;
    }

    .topic-container {
        margin-bottom: 2px;
        padding: 8px;
        border-radius: 10px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        /* Adjust the shadow color and size as needed */
    }

    .topic-name {
        font-weight: bold;
        margin-top: 5px;
    }

    .topic-description {
        margin-left: 10px;
        margin-bottom: 5px;
    }

    .border {
        border: 1px solid #ccc;
    }

    .rounded {
        border-radius: 10px;
    }

    .course-content h5 {
        font-weight: bold;
    }

    .course-content,
    .topic-description {
        display: none;
    }

    .fa-circle-chevron-down {
        margin-left: 10px;
    }

    .topic-name-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        display: flex;
        justify-content: space-between;
        /* Adjust as needed */
    }

    /* Center text horizontally and vertically in specific columns */
    .dataTable tbody td:nth-child(1),
    .dataTable tbody td:nth-child(2),
    .dataTable tbody td:nth-child(3),
    .dataTable tbody td:nth-child(4),
    .dataTable tbody td:nth-child(9),
    .dataTable tbody td:nth-child(10) {
        text-align: center;
        /* Horizontal center */
        vertical-align: middle;
        /* Vertical center */
    }
</style>

<body id="page-top" class="sidebar-toggled">
    <?php include("navigation.php"); ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <div class="d-flex">
                    <a href="training_schedules.php">
                        <h4 class="mb-0 mr-3" style="font-weight: bold;">Training Schedule</h4>
                    </a>
                    <h4 class="mr-3">/</h4>
                    <a href="#" id="goBackLink">
                        <h4 class="mb-0 mr-3" id="addTrainingBatch">Batch</h4>
                    </a>
                    <h4 class="mr-3">/</h4>
                    <h4 class="mb-0 mr-3" id="addTrainingDay">Day</h4>
                </div>
                <div class="small">
                    <!-- <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                    .<?php echo date('F d, Y - h:i:s A'); ?> -->
                </div>
            </div>
        </div>
        <form id="viewBatchTrainingDetails" class="bg-white rounded-sm shadow">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Training Day Details</h6>
            </div>
            <div class="form-group p-3">
                <!-- scheduleDate, StartTime, EndTime  -->
                <div id="dateDetails" class="d-flex">
                    <label class="mr-2 font-weight-bold" for="" id="datefrom"><i class="fas fa-calendar-alt mr-1"></i></label>
                    <label class="mr-2 font-weight-bold" for="" id="startTime"><i class="fas fa-clock mr-1"></i></label>
                    <label class="mr-2 font-weight-bold" for="" id="location"><i class="fas fa-map-marker-alt mr-1"></i></label>
                    <label class="mr-2 font-weight-bold" for="" id="nameFacilitator"><i class="fas fa-chalkboard-teacher mr-1"></i></label>
                </div>
                <!-- List of Deparment or Branch  -->
                <div id="branchORdeparmentDetails" class="d-flex">
                    <h5 class="font-weight-bold">
                        <div id="branchBadge" class="d-flex font-weight-bold"></div>
                    </h5>
                </div>
                <!-- dynamically generated courses and topics  -->
                <h5 class="mt-2 font-weight-bold">Course(s)</h5>
                <div id="courseListContainer">
                    <!-- Course names and topics will be dynamically inserted here -->
                </div>
            </div>
        </form>
        <!-- Datatable -->
        <div class="card shadow mt-2">
            <div class="table-responsive">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Employee List</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover text-uppercase" id="displayEmployeeList" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="text-center">EMPLOYEE ID</th>
                                <th class="text-center">EMPLOYEE NAME</th>
                                <th class="text-center">DEPARTMENT</th>
                                <th class="text-center">TIME SCHEDULE</th>
                                <th class="text-center">TIME IN</th>
                                <th class="text-center">BREAK OUT</th>
                                <th class="text-center">BREAK IN</th>
                                <th class="text-center">TIME OUT</th>
                                <th class="text-center">LATE</th>
                                <th class="text-center">ACTION</th>
                                <th class="text-center hidden-column"></th>
                                <th class="text-center hidden-column"></th>
                            </tr>
                        </thead>
                        <tbody id="employeeBatchDetails">
                            <!-- Table body will be populated dynamically using JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="reasonModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center d-flex flex-column align-items-center">
                        <h3 class="modal-Reason mt-3" id="reasonModalLong">Reason for Late/Absence</h3>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <!-- Textarea with minimum height -->
                        <textarea id="reasonTextarea" class="form-control min-height-10"></textarea>
                        <!-- Filename link container -->
                        <div class="d-flex mt-2 align-items-center">
                            <p class="mr-2 mb-0" style="font-weight: bold;">Attachment: </p>
                            <div id="filenameLink" class="mb-0"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Main Content -->
    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright © Mary Grace Foods Inc. 2019.</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <!-- Calendar Restriction-->
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css" />
    <script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>
    <!-- Include jQuery and Bootstrap JS -->
    <!-- Include Swal CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<script>
    // console.log(xhr.responseText);
    window.onload = function() {
        var urlParams = new URLSearchParams(window.location.search);
        var batchNumber = urlParams.get('id');
        var day = urlParams.get('day'); // Get the value of the 'day' parameter
        var courseId = urlParams.get('course_id'); // Get the value of the 'course_id' parameter
        var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter

        if (!batchNumber) {
            batchNumber = ''; // Set a default value, such as an empty string
        }

        var addTrainingBatch = document.getElementById('addTrainingBatch');
        addTrainingBatch.innerHTML = '<a href="view-batch-schedules.php?id=' + batchNumber + '&schedule_id=' + scheduleId + '">Batch ' + batchNumber + '</a>';

        var addTrainingDay = document.getElementById('addTrainingDay');
        addTrainingDay.innerText = 'Day ' + day; // Concatenate the value of 'day'

        // Call fetchSelectedCourses with courseId, scheduleId, day, and dateFrom
        fetchSelectedCourses(courseId, scheduleId, day)

    }

    function fetchSelectedCourses(courseId, scheduleId, day) {
        // Make an AJAX request to your PHP script
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_selected_course.php?schedule_id=' + scheduleId + '&day=' + day + '&course_id=' + courseId, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Parse the JSON response
                    var responseData = JSON.parse(xhr.responseText);
                    // console.log(xhr.responseText);

                    // Update the HTML elements with the fetched data
                    if (!responseData.error) {

                        var formattedDate = formatDate(responseData.datefrom);
                        document.getElementById('datefrom').innerHTML = '<i class="fas fa-calendar-alt mr-1"></i> ' + formattedDate;
                        document.getElementById('startTime').innerHTML = '<i class="fas fa-clock mr-1"></i> ' + responseData.starttime + ' - ' + responseData.endtime;
                        document.getElementById('location').innerHTML = '<i class="fas fa-map-marker-alt mr-1"></i> ' + (responseData.location || 'N/A');
                        // Transform the name_facilitator JSON array to a comma-separated string
                        var facilitatorNames = JSON.parse(responseData.name_facilitator || '[]').map(function(facilitator) {
                            return facilitator.name_facilitator;
                        }).join(', ');
                        document.getElementById('nameFacilitator').innerHTML = '<i class="fas fa-chalkboard-teacher mr-1"></i> ' + (facilitatorNames || 'N/A');


                        // Handle no_of_topics data
                        var courseListContainer = document.getElementById('courseListContainer');
                        courseListContainer.innerHTML = ''; // Clear existing content

                        // Parse the no_of_topics JSON data
                        var topicsData = JSON.parse(responseData.no_of_topics);
                        var courses = {};

                        // Group topics by course
                        topicsData.forEach(function(topic) {
                            if (!courses[topic.courseName]) {
                                courses[topic.courseName] = [];
                            }
                            courses[topic.courseName].push(topic.topicsName);
                        });

                        for (var courseName in courses) {
                            if (courses.hasOwnProperty(courseName)) {

                                var courseDiv = document.createElement('div');
                                courseDiv.className = 'course-container'; // Add CSS class for styling

                                var courseTitle = document.createElement('h5');
                                courseTitle.className = 'course-title';
                                courseTitle.style.cursor = 'pointer'; // Change cursor to pointer for course title
                                courseTitle.style.color = '#098CFD'; // Add style to set the color to blue
                                courseTitle.innerHTML = '<span class="mr-3">' + courseName + '</span><span class="chevron-container"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="18" height="18" viewBox="0 0 512 512" class="chevron" style="vertical-align: middle; margin-top: -6px;"><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg></span>';
                                courseDiv.appendChild(courseTitle);

                                // Add click event listener to course title to toggle visibility
                                courseTitle.addEventListener('click', function() {
                                    // Select sibling elements
                                    var siblingElements = this.parentElement.querySelectorAll('.course-description, .topic-title, .topic-container, .course-divider');
                                    siblingElements.forEach(function(element) {
                                        element.style.display = (element.style.display === 'none' ? 'block' : 'none');
                                    });
                                    // Toggle chevron direction
                                    var chevron = this.querySelector('.chevron');
                                    if (chevron) {
                                        if (siblingElements[0].style.display === 'none') {
                                            chevron.style.transform = 'rotate(0deg)'; // Downward
                                        } else {
                                            chevron.style.transform = 'rotate(180deg)'; // Upward
                                        }
                                    }
                                });

                                // Get the description_course for the current courseName
                                var description = '';
                                responseData.courses.forEach(function(course) {
                                    if (course.name_course === courseName) {
                                        description = course.description_course;
                                    }
                                });

                                // Create a paragraph element for description_course
                                var courseDescription = document.createElement('p');
                                courseDescription.className = 'course-description mb-0';
                                courseDescription.textContent = description;
                                courseDiv.appendChild(courseDescription);

                                // Create an hr element
                                var hr = document.createElement('hr');
                                hr.className = 'course-divider mb-0 mt-0 mr-2 ml-2';
                                courseDiv.appendChild(hr);

                                var topicsTitle = document.createElement('h5');
                                topicsTitle.className = 'topic-title mt-3 ml-2'; // Add mt-5 class for margin-top spacing
                                topicsTitle.textContent = 'Topic(s)';
                                courseDiv.appendChild(topicsTitle);

                                var topicsDiv = document.createElement('div');
                                courses[courseName].forEach(function(topicName) {
                                    var topicItem = document.createElement('div');
                                    topicItem.className = 'topic-container'; // Add CSS class for styling
                                    topicItem.textContent = topicName;
                                    topicItem.style.color = '#098CFD'; // Add style to set the color of topic name to blue
                                    topicItem.style.fontWeight = 'bold'; // Add style to make the text bold
                                    topicItem.style.cursor = 'pointer'; // Change cursor to pointer for topic item
                                    topicsDiv.appendChild(topicItem);
                                });

                                courseDiv.appendChild(topicsDiv);
                                courseListContainer.appendChild(courseDiv);
                            }
                        }
                    } else {
                        console.error('Error:', responseData.error);
                    }
                } else {
                    // Handle errors if any
                    console.error('Error fetching course data:', xhr.statusText);
                }
            }
        };
        xhr.send();
    }

    $(document).ready(function() {

        // Function to safely handle null or invalid times
        function handleTime(time) {
            return time && time !== '--:--' ? time : '--:--';
        }

        // Your AJAX call and DataTables initialization code here...
        var urlParams = new URLSearchParams(window.location.search);
        var datefrom = urlParams.get('datefrom');
        var course_id = urlParams.get('course_id');
        var scheduleId = urlParams.get('schedule_id');
        var day = urlParams.get('day');
        var endtime = urlParams.get('endtime');

        $.ajax({
            url: 'fetch_employee_schedule.php',
            method: 'GET',
            dataType: 'json',
            data: {
                schedule_id: scheduleId,
                datefrom: datefrom
            },
            success: function(data) {
                let tbody = $('#employeeBatchDetails');
                tbody.empty();

                // console.log(data.employee_with_location);
                // console.log(data.employee_schedule);

                let displayedEmpnos = {};
                let empnoToCafename = {
                    type1: {},
                    type2: {},
                    type3: {},
                    type4: {}
                };

                data.employee_with_location.forEach(function(loc) {
                    if (loc.type == 1) {
                        empnoToCafename.type1[loc.empno] = loc.cafename;
                    } else if (loc.type == 2) {
                        empnoToCafename.type2[loc.empno] = loc.cafename;
                    } else if (loc.type == 3) {
                        empnoToCafename.type3[loc.empno] = loc.cafename;
                    } else if (loc.type == 4) {
                        empnoToCafename.type4[loc.empno] = loc.cafename;
                    }
                });

                data.employee_schedule.forEach(function(row) {

                    if (!displayedEmpnos[row.empno]) {
                        let tr = $('<tr>');
                        let isDisabled = row.empno_excluded == 1 && row.empno_dateExclude == datefrom;
                        if (isDisabled) {
                            tr.addClass('disabled');
                            tr.hide();
                        }

                        // let timeInString = handleTime(row.M_timein);
                        // let breakoutString = handleTime(row.M_timeout);
                        // let breakinString = handleTime(row.A_timein);
                        // let timeoutString = handleTime(row.A_timeout);

                        // let timeInCafename = empnoToCafename.type1[row.empno] ? empnoToCafename.type1[row.empno] + ' ' + timeInString : timeInString;
                        // let breakoutCafename = empnoToCafename.type2[row.empno] ? empnoToCafename.type2[row.empno] + ' ' + breakoutString : breakoutString;
                        // let breakinCafename = empnoToCafename.type3[row.empno] ? empnoToCafename.type3[row.empno] + ' ' + breakinString : breakinString;
                        // let timeoutCafename = empnoToCafename.type4[row.empno] ? empnoToCafename.type4[row.empno] + ' ' + timeoutString : timeoutString;

                        let lateness = 0;
                        let schedFrom = new Date(row.schedfrom);
                        let timeIn = new Date(row.M_timein);
                        let M_timeout = new Date(row.M_timeout);
                        let A_timein = new Date(row.A_timein);
                        let A_timeout = new Date(row.A_timeout);
                        let breaktime = parseInt(row.break, 10) * 60;

                        let diff = (A_timein - M_timeout) / (1000 * 60);    
                        if (diff > breaktime) {
                            lateness = (diff - breaktime);
                        }
                        if (lateness < 0) {
                            lateness = 0;
                        }

                        let latenessFromSched = (timeIn - schedFrom) / (1000 * 60);
                        if (latenessFromSched < 0) {
                            latenessFromSched = 0;
                        }

                        row.lateness = lateness + latenessFromSched;

                        tr.append(
                            $('<td>').text(row.empno).addClass('text-center'),
                            $('<td>').text(row.name).addClass('text-center'),
                            $('<td>').text(row.branch).addClass('text-center'),
                            $('<td>').text(formatTime(row.schedfrom) + ' - ' + formatTime(row.schedto)).addClass('text-center'),
                            $('<td>').html((empnoToCafename.type1[row.empno] || '') + '<br>' + formatTime(row.M_timein)).addClass('text-center'),
                            $('<td>').html((empnoToCafename.type2[row.empno] || '') + '<br>' + formatTime(row.M_timeout)).addClass('text-center'),
                            $('<td>').html((empnoToCafename.type3[row.empno] || '') + '<br>' + formatTime(row.A_timein)).addClass('text-center'),
                            $('<td>').html((empnoToCafename.type4[row.empno] || '') + '<br>' + formatTime(row.A_timeout)).addClass('text-center'),
                            $('<td>').text(row.lateness > 0 ? row.lateness.toFixed(0) : '0').addClass('text-center ' + (row.lateness > 0 ? 'text-danger font-weight-bold' : '')),
                            $('<td>').html(getActionButton(row.empno_absent, row.empno_dateAbsent, row.empno, row.name, course_id, row.schedule_id, datefrom, day, isDisabled)).addClass('text-center'),
                            $('<td>').text(row.empno_absent).addClass('text-center hidden-column'),
                            $('<td>').text(row.empno_dateAbsent).addClass('text-center hidden-column')
                        );
                        tbody.append(tr);
                        displayedEmpnos[row.empno] = true;
                        // console.log('<br>')
                        // console.log("Empno:", row.empno);0
                        // console.log(timeInCafename);
                        // console.log(breakoutCafename);
                        // console.log(breakinCafename);
                        // console.log(timeoutCafename);
                        // console.log(row.schedfrom, row.schedto);

                    }
                });

                let branchBadge = $('#branchBadge');
                branchBadge.empty();
                let uniqueBranches = [];

                data.employee_schedule.forEach(function(row) {
                    let branchName = row.branch.toLowerCase();
                    if (!uniqueBranches.includes(branchName)) {
                        branchBadge.append($('<div>').text(row.branch).addClass('badge badge-success mr-1 mt-1'));
                        uniqueBranches.push(branchName);
                    }
                });

                // Initialize the DataTable after data is appended
                $('#displayEmployeeList').DataTable({
                    stateSave: true,
                    columns: [{
                            title: "EMPLOYEE ID",
                            data: "empno"
                        },
                        {
                            title: "EMPLOYEE NAME",
                            data: "name"
                        },
                        {
                            title: "DEPARTMENT",
                            data: "branch"
                        },
                        {
                            title: "TIME SCHEDULE",
                            data: "schedfrom",
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            title: "TIME IN",
                            data: "M_timein",
                            render: function(data, type, row) {
                                return handleTime(data);
                            }
                        },
                        {
                            title: "BREAK OUT",
                            data: "M_timeout",
                            render: function(data, type, row) {
                                return handleTime(data);
                            }
                        },
                        {
                            title: "BREAK IN",
                            data: "A_timein",
                            render: function(data, type, row) {
                                return handleTime(data);
                            }
                        },
                        {
                            title: "TIME OUT",
                            data: "A_timeout",
                            render: function(data, type, row) {
                                return handleTime(data);
                            }
                        },
                        {
                            title: "LATE",
                            data: "lateness",
                            render: function(data, type, row) {
                                let latenessValue = parseFloat(data);
                                if (!isNaN(latenessValue) && latenessValue > 0) {
                                    return latenessValue.toFixed(0);
                                } else {
                                    return '0';
                                }
                            }
                        },
                        {
                            title: "ACTION",
                            render: function(data, type, row) {
                                return getActionButton(row.empno_absent, row.empno_dateAbsent, row.empno, row.name, course_id, row.schedule_id, datefrom, row.empno_excluded == 1 && DATE);
                            }
                        },
                        {
                            title: "",
                            data: "empno_absent",
                            visible: false
                        },
                        {
                            title: "",
                            data: "empno_dateAbsent",
                            visible: false
                        },
                    ],
                    columnDefs: [{
                            targets: [10],
                            visible: false
                        }, // Hide the 11th column
                        {
                            className: "text-center",
                            targets: "_all"
                        }, // Center all columns
                    ],
                    createdRow: function(row, data, dataIndex) {
                        var today = new Date();
                        var formattedToday = today.getFullYear() + '-' +
                            ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
                            ('0' + today.getDate()).slice(-2) + ' ' +
                            ('0' + today.getHours()).slice(-2) + ':' +
                            ('0' + today.getMinutes()).slice(-2);
                        var endOfSchedule = datefrom + ' ' + endtime;

                        // Check if today is past the end of the schedule
                        if (today > new Date(endOfSchedule)) {
                            // Extract text content from cells
                            var $row = $(row);
                            var timeIn = $row.find('td:eq(4)').text().trim(); // Adjust the index based on your table structure
                            var timeout = $row.find('td:eq(5)').text().trim(); // Adjust the index based on your table structure
                            var breakIn = $row.find('td:eq(6)').text().trim(); // Adjust the index based on your table structure
                            var breakOut = $row.find('td:eq(7)').text().trim(); // Adjust the index based on your table structure

                            // Check if all columns contain '--:--'
                            if (timeIn === '--:--' && timeout === '--:--' && breakIn === '--:--' && breakOut === '--:--') {
                                $row.css('background-color', 'pink');
                            }
                        }
                    }
                });

            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });

        function getActionButton(empno_absent, empno_dateAbsent, empno, name, course_id, schedule_id, datefrom, day, isDisabled) {
            let fillColor = isDisabled ? 'gray' : 'red';
            if (empno_absent == 1 && empno_dateAbsent == datefrom) {
                // console.log(datefrom);
                // Return SVG with data attributes
                return '<div class="svg-container" style="display: inline-block; cursor: pointer;" data-empno="' + empno + '" data-name="' + name + '" data-course-id="' + course_id + '" data-schedule-id="' + schedule_id + '" data-datefrom="' + datefrom + '">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#4E73DF" class="bi bi-eye-fill open-reason-modal" viewBox="0 0 16 16">' +
                    '<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>' +
                    '<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>' +
                    '</svg>' +
                    '</div>';
            } else {
                return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18" height="18" style="cursor: pointer;" fill="' + fillColor + '" class="x-icon" onclick="openModal(\'' + empno + '\', \'' + name + '\', \'' + course_id + '\', \'' + schedule_id + '\', \'' + datefrom + '\')"><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c-9.4 9.4-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0z"/></svg>';
            }
        }

    });

    // Function to format date
    function formatDate(dateString) {
        // Create a new Date object from the dateString
        let date = new Date(dateString);

        // Define an array of month names
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        // Define an array of day names
        const dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        // Extract day, month, and day name from the Date object
        let day = date.getDate();
        let month = monthNames[date.getMonth()];
        let dayName = dayNames[date.getDay()];

        // Return formatted date string
        return `${month} ${day}, ${dayName}`;
    }


    // Function to format datetime to time or handle special cases like "NO BREAK"
    function formatTime(dateTime) {
        if (dateTime) {
            // Handle specific case for "No Break"
            if (dateTime === 'No Break') {
                return dateTime;
            }

            // Extract time part from datetime string
            let parts = dateTime.split(' ');
            if (parts.length === 2) {
                let time = parts[1];
                return time.slice(0, 5); // Return time in HH:mm format
            }
        }
        return '--:--'; // Default return value if dateTime is null or does not match format
    }

    // Define swalWithBootstrapButtons globally
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
            actions: "my-actions-class"
        },
        buttonsStyling: false
    });

    function openReasonModal(empno, name, course_id, schedule_id, datefrom, reason, attachment) {

        // Check if the reason or attachment is empty
        if (!reason) {
            Swal.fire({
                icon: "error",
                title: "Attachment Not Found.",
                text: "Please contact the employee to attach the required file.",
                timerProgressBar: true,
                confirmButtonColor: "#17A673" // Change the button color to green
            });
            return; // Prevent the modal from opening
        }

        // Set values to modal fields
        $('#reasonTextarea').val(reason);

        // Display the filename link
        if (attachment) {
            var filenameLink = "<a href='lnd_attachment/" + attachment + "' target='_blank'>" + attachment + "</a>";
            $('#filenameLink').html(filenameLink);
        } else {
            $('#filenameLink').html('');
        }

        // Make the textarea readonly
        $('#reasonTextarea').prop('readonly', true);

        // Open the modal
        $('#reasonModals').modal('show');
    }

    $(document).ready(function() {
        // Add event listener to the document for event delegation
        document.addEventListener('click', function(event) {
            // Check if the clicked element is the SVG or its child
            const svgContainer = event.target.closest('.svg-container');
            if (svgContainer && svgContainer.querySelector('.open-reason-modal')) {
                // Extract data attributes from the SVG container
                const empno = svgContainer.getAttribute('data-empno');
                const name = svgContainer.getAttribute('data-name');
                const course_id = svgContainer.getAttribute('data-course-id');
                const schedule_id = svgContainer.getAttribute('data-schedule-id');
                const datefrom = svgContainer.getAttribute('data-datefrom');

                // Fetch the reason data using AJAX
                $.ajax({
                    url: 'display_reasonabsent.php',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        empno: empno,
                        datefrom: datefrom
                    },
                    success: function(data) {
                        // Log the data to the console for debugging
                        // console.log(data);
                        // Open the modal with reason data
                        openReasonModal(empno, name, course_id, schedule_id, datefrom, data.reason, data.attachment);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching reason data:', error);
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        // Set the height of the textarea to at least 10% of the viewport height
        var viewportHeight = $(window).height();
        var minTextareaHeight = viewportHeight * 0.3; // 10% of viewport height
        $('#reasonTextarea').css('min-height', minTextareaHeight + 'px');
    });

    // Define the openModal function with additional parameters
    function openModal(empno, name, course_id, scheduleId, datefrom, day) {
        var urlParams = new URLSearchParams(window.location.search);
        var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter
        var day = urlParams.get('day'); // Get the value of the 'schedule_id' parameter

        // Use swalWithBootstrapButtons here
        swalWithBootstrapButtons.fire({
            title: "Tag <strong>" + name + "</strong> as absent?",
            html: `<p>By confirming, you acknowledge that the selected trainee will be marked as absent for Day <strong>${day}</strong></p>`,
            icon: "info",
            timerProgressBar: true,
            showCancelButton: false, // Remove the cancel button
            confirmButtonText: "Confirm",
            showCloseButton: true, // Show close button (x) in the top-right corner

        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, make AJAX request
                $.ajax({
                    url: 'insert_absent_trainees.php',
                    type: 'POST',
                    data: {
                        empno: empno,
                        course_id: course_id,
                        schedule_id: scheduleId,
                        datefrom: datefrom
                    },
                    success: function(response) {
                        // Handle success response
                        swalWithBootstrapButtons.fire('Success', 'Data inserted successfully!', 'success');
                        // Show additional success message
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            icon: "success",
                            title: "Successfully tag as absent.",
                        });
                        // Reload the page after a 1-second delay
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        // For example, show an error message
                        try {
                            var response = JSON.parse(xhr.responseText); // Parse the JSON response
                            var errorMessage = response.error; // Get the error message from the response
                            if (errorMessage) {
                                // If there's an error message, display it
                                swalWithBootstrapButtons.fire('Error', errorMessage, 'error');
                            } else {
                                // If there's no specific error message, show a generic error message
                                swalWithBootstrapButtons.fire('Error', 'Failed to insert data: ' + error, 'error');
                            }
                        } catch (e) {
                            // If parsing the JSON fails, show a generic error message
                            swalWithBootstrapButtons.fire('Error', 'Failed to insert data: ' + error, 'error');
                        }
                    }
                });
            }
        });
    }
</script>

</html>