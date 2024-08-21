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
</head>

<style>
    #timeScheduleSwitch:disabled+.custom-control-label {
        cursor: not-allowed;
        color: #6c757d;
        /* Optional: Change the label color to indicate it's disabled */
    }

    #batchNumber {
        width: 300px;
    }

    #course {
        width: 300px !important;
    }

    .selected-course {
        background-color: #36B9CC;
        padding: 5px 10px;
        border-radius: 0.25rem;
        display: inline-block;
        margin-right: 10px;
        color: white;
        font-weight: bold;
    }

    .close-button {
        color: red;
        cursor: pointer;
        margin-left: 5px;
        font-weight: bold;
    }

    .btn-primary {
        font-weight: bold;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .time-input {
        width: 100px;
    }

    th.text-center {
        width: 120px !important;

    }

    .text-center {
        text-align: center;
    }

    .rounded {
        border-radius: 5px;
    }

    .border {
        border: 1px solid #ccc;
        outline: none;
    }

    .border:focus {
        border-color: #2E59D9;
        box-shadow: 0 0 4px #2E59D9;
    }

    .icon-pointer {
        cursor: pointer;
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
            </div>
        </div>
        <form id="trainingSchedulesForm" class="bg-white rounded-sm shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Course & Topics Details</h6>
            </div>
            <div class="form-group p-3 d-flex flex-column">
                <div class="trainings-box row ml-2">
                    <div class="p-2 container-fluid col-md-6 border rounded">
                        <!-- Main Course Container 1  -->
                        <div id="maincontainer">
                            <!-- Container for course topics -->
                            <div id="courseTopicsContainer">
                            </div>
                            <p id="checkboxError" class="mb-0 mt-0" style="color: red; display: none;">Please select at least one topic.</p>
                        </div>
                    </div>

                    <!-- 2 container  -->
                    <div class="container-fluid col-md-6 justify-content-end">


                        <!-- time schedule -->
                        <h6 class="font-weight-bold mr-2">Input Time Schedule <span style="color: red;">*</span></h6>
                        <div class="d-flex flex-column align-items-start mb-3">
                            <div class="d-flex">
                                <input type="time" id="startTime" name="startTime" class="form-control text-center" placeholder="00:00" required>
                                <h6 class="font-weight-bold mt-2 mr-2 ml-2">To</h6>
                                <input type="time" id="endTime" name="endTime" class="form-control text-center" placeholder="00:00" required>
                            </div>
                            <div id="timeScheduleError" class="ml-2 mb-2 mt-0" style="color: red; display: none;">Time schedule cannot be empty.</div>

                            <!-- Add Switch Button Here -->
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="timeScheduleSwitch" checked>
                                <label class="custom-control-label font-weight-bold" for="timeScheduleSwitch">Enable the switch to edit the time schedule of all trainees</label>
                            </div>
                        </div>


                        <!-- Location  -->
                        <div>
                            <h6 class="font-weight-bold">Input Location <span style="color: red;">*</span></h6>
                            <div class="input-group mb-1">
                                <input type="text" id="inputLocation" name="inputLocation" placeholder="Location" class="form-control" maxlength="200" required>
                            </div>
                            <div id="locationError" style="color: red; display: none;">Location cannot be empty.</div>
                        </div>

                        <!-- Facilitator Name Section -->
                        <div>
                            <div class="d-flex justify-content-between">
                                <h6 class="font-weight-bold mt-3">Facilitator Name <span style="color: red;">*</span></h6>
                                <h6 class="mt-3" id="showInputName" style="color: #4E73E4; cursor: pointer;">
                                    <i class="fa fa-users mr-1"></i> Click here to add new name
                                </h6>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="input-group mb-1 mr-2">
                                    <input type="text" id="inputFacilitatorName1" name="inputFacilitatorName1" placeholder="Name" class="form-control" maxlength="200" required>
                                </div>
                                <div class="input-group mr-2">
                                    <input type="text" id="inputFacilitatorName2" style="display: none;" name="inputFacilitatorName2" placeholder="Name (Optional)" class="form-control" maxlength="200" required>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="inputFacilitatorName3" style="display: none;" name="inputFacilitatorName3" placeholder="Name (Optional)" class="form-control" maxlength="200" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="input-group mb-1 mr-2">
                                    <input type="text" id="inputFacilitatorName4" style="display: none;" name="inputFacilitatorName4" placeholder="Name (Optional)" class="form-control" maxlength="200" required>
                                </div>
                                <div class="input-group mr-2">
                                    <input type="text" id="inputFacilitatorName5" style="display: none;" name="inputFacilitatorName5" placeholder="Name (Optional)" class="form-control" maxlength="200" required>
                                </div>
                                <div class="input-group">
                                    <input type="text" id="inputFacilitatorName6" style="display: none;" name="inputFacilitatorName6" placeholder="Name (Optional)" class="form-control" maxlength="200" required>
                                </div>
                            </div>
                            <div id="facilitatorError" style="color: red; display: none;">Facilitator Name cannot be empty.</div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-between">
            <a href="#" id="goBackButton" class="btn btn-secondary">
                Go Back
            </a>
            <a href="#" id="nextButton" class="btn btn-primary">
                Next
            </a>
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
</body>

<script>
    // Function to handle keypress event on inputLocation field
    document.getElementById('inputLocation').onkeypress = function(event) {
        var keyCode = event.keyCode || event.which;
        var inputChar = String.fromCharCode(keyCode);

        // Check if the entered character is '&'
        if (inputChar === '&') {
            // Prevent the default action (character input)
            event.preventDefault();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        var switchButton = document.getElementById('timeScheduleSwitch');
        switchButton.disabled = true;
    });

    window.onload = function() {
        var urlParams = new URLSearchParams(window.location.search);
        var batchNumber = urlParams.get('id');
        var day = urlParams.get('day'); // Get the value of the 'day' parameter
        var datefrom = urlParams.get('datefrom'); // Get the value of the 'day' parameter
        var courseId = urlParams.get('course_id'); // Get the value of the 'course_id' parameter
        var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter

        if (!batchNumber) {
            batchNumber = ''; // Set a default value, such as an empty string
        }

        // var addTrainingBatch = document.getElementById('addTrainingBatch');
        // addTrainingBatch.innerText = 'Batch ' + batchNumber;

        var addTrainingBatch = document.getElementById('addTrainingBatch');
        // Set the URL without the word "Batch"
        var url = 'view-batch-schedules.php?id=' + batchNumber + '&schedule_id=' + scheduleId;
        // Set the link text to include the word "Batch"
        addTrainingBatch.innerHTML = '<a href="' + url + '">Batch ' + batchNumber + '</a>';

        var addTrainingDay = document.getElementById('addTrainingDay');
        addTrainingDay.innerText = 'Day ' + day + ' Add Training Details'; // Concatenate the value of 'day'

        // Construct the Go Back URL
        var goBackUrl = 'view-batch-schedules.php?id=' + encodeURIComponent(batchNumber) +
            '&day=' + encodeURIComponent(day)
        // Set the href attribute of the Go Back link
        var goBackLink = document.getElementById('goBackLink');
        goBackLink.href = goBackUrl;

        // Set the href attribute of the Go Back button
        var goBackButton = document.getElementById('goBackButton');
        goBackButton.href = goBackUrl;

        // Fetch course data from server based on courseId
        fetchCourseData(courseId);
    }

    var checkedValues = []; // Define this at the top to make it global

    function fetchCourseData(courseId) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // console.log("Course and Topics: " + xhr.responseText);
                    var courses = JSON.parse(xhr.responseText);
                    // console.log('Courses data:', courses); // Log courses data

                    // Clear previous content
                    var courseTopicsContainer = document.getElementById('courseTopicsContainer');
                    courseTopicsContainer.innerHTML = '';

                    // Iterate over each course
                    for (var courseIdKey in courses) {
                        if (courses.hasOwnProperty(courseIdKey)) {
                            var course = courses[courseIdKey]; // Use a different variable name to avoid conflict with the loop variable

                            // Create course container
                            var courseContainer = document.createElement('div');
                            courseContainer.id = 'courseContainer-' + courseIdKey; // Add an id to the course container

                            // Add styles to make the courseContainer scrollable
                            courseContainer.style.maxHeight = '300px'; // Set a fixed height
                            courseContainer.style.overflowY = 'auto'; // Enable vertical scrolling
                            courseContainer.style.padding = '10px'; // Optional: add padding

                            // Course name
                            var courseNameHeader = document.createElement('h5');
                            courseNameHeader.innerHTML = course.course_name;
                            courseContainer.appendChild(courseNameHeader);
                            courseNameHeader.classList.add('font-weight-bold');
                            courseNameHeader.style.backgroundColor = '#EBECF0';
                            courseNameHeader.classList.add('rounded-sm');
                            courseNameHeader.classList.add('p-2');
                            courseNameHeader.style.cursor = 'pointer'; // Add CSS for cursor pointer

                            // Iterate over topics of the course
                            course.topics.forEach(function(topic) { // Change to course.topics
                                // Checkbox for the topic
                                var checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.value = topic.name;
                                checkbox.name = 'courseTopic'; // Set the name attribute if needed
                                checkbox.style.cursor = 'pointer'; // Add CSS for cursor pointer

                                // Label for the topic
                                var label = document.createElement('label');
                                label.textContent = topic.name;
                                label.classList.add('ml-2');
                                label.style.cursor = 'pointer'; // Add CSS for cursor pointer

                                // Create a paragraph to contain checkbox and label
                                var topicContainer = document.createElement('p');
                                topicContainer.appendChild(checkbox);
                                topicContainer.appendChild(label);
                                topicContainer.classList.add('m-0');
                                topicContainer.classList.add('ml-3');

                                // Add event listener to label to check corresponding checkbox
                                label.addEventListener('click', (function(header) {
                                    return function() {
                                        checkbox.checked = !checkbox.checked;
                                        // Trigger checkbox event handler manually
                                        checkbox.dispatchEvent(new Event('click'));
                                    };
                                })(courseNameHeader)); // Pass courseNameHeader to the IIFE

                                checkbox.addEventListener('click', (function(header, topicId, course) {
                                    return function() {
                                        if (checkbox.checked) {
                                            // If checkbox is checked, add its value, id, course name, and course id to the checkedValues array
                                            checkedValues.push({
                                                id: topicId,
                                                topicsName: checkbox.value,
                                                course_id: course.course_id, // Update this line
                                                courseName: header.innerText
                                            });
                                        } else {
                                            // If checkbox is unchecked, remove its value and course name from the checkedValues array
                                            var index = checkedValues.findIndex(function(item) {
                                                return item.topicsName === checkbox.value && item.courseName === header.innerText;
                                            });
                                            if (index !== -1) {
                                                checkedValues.splice(index, 1);
                                            }
                                        }
                                        // Log the checkedValues array
                                        // console.log('Checked values:', checkedValues);
                                        // You can replace the console.log with your code to insert the checkedValues into the database
                                    };
                                })(courseNameHeader, topic.id, course));

                                // Append topic container to course container
                                courseContainer.appendChild(topicContainer);
                            });

                            // Append course container to main container
                            courseTopicsContainer.appendChild(courseContainer);

                            // Add horizontal rule after each course container
                            var hr = document.createElement('hr');
                            hr.classList.add('mb-1', 'mt-0');
                            courseTopicsContainer.appendChild(hr);
                        }
                    }

                } else {
                    console.error('Error fetching course details. Status code: ' + xhr.status);
                }
            }
        };

        xhr.open('GET', 'fetch_display_course.php?course_id=' + courseId, true);
        xhr.send();
    }

    function processEnrolledEmpData(enrolledEmpData, scheduleId) {
        function onNextButtonClick(event) {
            var startTime = document.getElementById('startTime').value;
            var endTime = document.getElementById('endTime').value;
            var location = document.getElementById('inputLocation').value;

            var locationError = document.getElementById('locationError');
            var facilitatorError = document.getElementById('facilitatorError');
            var checkboxError = document.getElementById('checkboxError');
            var timeScheduleError = document.getElementById('timeScheduleError');

            locationError.style.display = 'none';
            facilitatorError.style.display = 'none';
            checkboxError.style.display = 'none';
            timeScheduleError.style.display = 'none';

            if (!location) {
                locationError.style.display = 'block';
                event.preventDefault();
                return;
            }

            var facilitatorNames = {};
            for (var i = 1; i <= 6; i++) {
                var facilitatorName = document.getElementById('inputFacilitatorName' + i).value;
                if (facilitatorName) {
                    facilitatorNames[i - 1] = {
                        name_facilitator: facilitatorName
                    };
                }
            }

            if (Object.keys(facilitatorNames).length === 0) {
                facilitatorError.style.display = 'block';
                event.preventDefault();
                return;
            }

            if (!startTime || !endTime) {
                timeScheduleError.style.display = 'block';
                event.preventDefault();
                return;
            }

            if (checkedValues.length === 0) {
                checkboxError.style.display = 'block';
                event.preventDefault();
                return;
            }

            var urlParams = new URLSearchParams(window.location.search);
            var day = urlParams.get('day');
            var datefrom = urlParams.get('datefrom');

            var formattedTopics = {};
            var courseNamesMap = {};

            checkedValues.forEach(function(item) {
                if (!courseNamesMap[item.course_id]) {
                    courseNamesMap[item.course_id] = {
                        courseName: item.courseName
                    };
                }

                if (!formattedTopics[item.course_id]) {
                    formattedTopics[item.course_id] = [];
                }
                formattedTopics[item.course_id].push({
                    id: item.id,
                    topicsName: item.topicsName
                });
            });

            Object.keys(formattedTopics).forEach(function(course_id) {
                formattedTopics[course_id] = JSON.stringify(formattedTopics[course_id]);
            });

            var timeScheduleSwitch = document.getElementById('timeScheduleSwitch').checked;

            // Convert the boolean value to string 'true' or 'false'
            timeScheduleSwitch = timeScheduleSwitch ? 'true' : 'false';

            var data = {
                startTime: startTime,
                endTime: endTime,
                inputLocation: location,
                facilitatorNames: JSON.stringify(facilitatorNames),
                enrolled_emp_data: enrolledEmpData,
                checkedValues: checkedValues,
                datefrom: datefrom,
                day: day,
                no_of_topics: JSON.stringify(formattedTopics),
                courseNamesMap: JSON.stringify(courseNamesMap),
                timeScheduleSwitch: timeScheduleSwitch // Pass the switch state as string
            };

            // Array of loading messages
            var loadingMessages = [
                'Saving schedules for each employee...',
                'Please wait while we update schedules...',
                'Updating location trainings...',
                'Updating facilitator name...',
                'Updating Courses and Topics trainings...',
                'Updating DTR time schedule employees...',
                'Processing your request...'
            ];

            // Initial index for the loading message
            var messageIndex = 0;

            // Function to update the loading message
            function updateLoadingMessage() {
                Swal.update({
                    title: loadingMessages[messageIndex]
                });
                messageIndex = (messageIndex + 1) % loadingMessages.length; // Move to the next message
            }

            // Show loading indicator
            var loadingIndicator = Swal.fire({
                title: 'Saving schedules for each employee...',
                timerProgressBar: true,
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                    // Start updating the message every 2 seconds
                    setInterval(updateLoadingMessage, 2000); // Adjust the interval as needed
                }
            });

            $.ajax({
                url: 'update_timeschedule_details.php?id=' + scheduleId,
                method: 'POST',
                data: data,
                success: function(response) {
                    // console.log('Data inserted successfully');
                    loadingIndicator.close();
                    window.location.href = trainingsDetailsURL;
                },
                error: function(xhr, status, error) {
                    console.error('Error inserting data:', error);
                    loadingIndicator.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the schedule. Please try again.'
                    });
                }
            });
        }

        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('id');
        var day = urlParams.get('day');
        var datefrom = urlParams.get('datefrom');
        var course_id = urlParams.get('course_id');
        var scheduleId = urlParams.get('schedule_id');

        var trainingsDetailsURL = 'trainings-details.php?id=' + id + '&day=' + day + '&course_id=' + course_id + '&schedule_id=' + scheduleId + '&datefrom=' + datefrom;

        document.getElementById('nextButton').addEventListener('click', onNextButtonClick);

        document.getElementById('nextButton').setAttribute('href', trainingsDetailsURL);
    }

    $(document).ready(function() {
        // Get URL parameters
        var id = new URLSearchParams(window.location.search).get('id');
        var scheduleId = new URLSearchParams(window.location.search).get('schedule_id');

        // Check if id parameter is present
        if (id === null) {
            console.error('ID parameter is missing');
            return;
        }

        // Construct the AJAX URL based on the presence of the schedule_id parameter
        var ajaxUrl = 'fetch_trainings_empno.php?id=' + id;
        if (scheduleId !== null) {
            ajaxUrl += '&schedule_id=' + scheduleId;
        }

        // Perform the AJAX request
        $.ajax({
            url: ajaxUrl,
            method: 'GET',
            success: function(response) {
                // console.log('Response from server:', response);
                var responseData = JSON.parse(response);

                if (responseData.length > 0 && responseData[0].hasOwnProperty('schedule_id')) {
                    var scheduleId = responseData[0].schedule_id;
                    // console.log('Schedule ID:', scheduleId);

                    var enrolledEmpData = responseData[0].enrolled_emp_data.map(function(item) {
                        return item.empno;
                    });

                    processEnrolledEmpData(enrolledEmpData, scheduleId);
                } else {
                    console.error('No schedule_id found in the response');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching training data:', error);
            }
        });
    });

    document.getElementById('showInputName').addEventListener('click', function() {
        const inputs = document.querySelectorAll('[id^="inputFacilitatorName"]');
        inputs.forEach(input => {
            input.style.display = 'block';
        });
    });

    document.getElementById('startTime').addEventListener('input', validateTime);
    document.getElementById('endTime').addEventListener('input', validateTime);

    function validateTime(event) {
        const timePattern = /^([01]\d|2[0-3]):([0-5]\d)$/;
        const value = event.target.value;
        if (!timePattern.test(value)) {
            alert('Please enter a valid time between 00:00 and 23:59');
            event.target.value = ''; // Clear the invalid input
        }
    }
</script>

</html>