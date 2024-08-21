<?php
session_start();

// // Check if user is logged in
// if (!isset($_SESSION['user_validate'])) {
//     // Redirect to login page
//     header("Location: index.php?m=2"); // m=2 to indicate access failure
//     exit();
// }

// $connect = mysqli_connect("localhost", "root", "", "db");
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
// $empno = $_GET['empno'] ?? null;

// if ($empno !== $_SESSION['user_validate']) {
//     // Redirect to login page
//     header("Location: index.php?m=2"); // m=2 to indicate access failure
//     exit();
// }
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <style type="text/css">
        html {
            background-color: #F1F0F0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Apply Poppins font to body */
            background-color: #EFEEEE;
        }

        .badge-branch {
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

        .badge-branch:before,
        .badge-branch:after {
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

        .badge-branch:after {
            right: 10px;
            left: auto;
            -webkit-transform: skew(8deg) rotate(3deg);
            -moz-transform: skew(8deg) rotate(3deg);
            -ms-transform: skew(8deg) rotate(3deg);
            -o-transform: skew(8deg) rotate(3deg);
            transform: skew(8deg) rotate(3deg);
        }

        .badge-branch:hover {
            background-color: #20E19E !important;
            /* Change this to the desired hover color */
        }

        .rounded-more {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Add a subtle box shadow */
        }

        #seeMoreBtn {
            display: none;
            /* Hide the label initially */
            margin-top: 10px;
            margin-left: 5px;

            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }

        #seeMoreBtn:hover {
            color: #0056b3;
        }

        .badge-branch {
            background-color: #1CC88A;
            font-size: 16px;
            /* Adjust the font size as needed */
            /* or any other color you prefer */
        }

        .large-font {
            font-size: 15px;
            /* Adjust the font size as needed */
        }

        .time-inputs-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            position: relative;
            padding: 1rem;
        }

        .bg-gray {
            background-color: #F1F1F2;
            padding: 10px;
        }

        .gray-background {
            background-color: #EAECF4;
        }

        #parent-div {
            background-color: #FFFFFF;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .table {
            display: table;
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px !important;
        }

        .table-header,
        .table-row {
            display: table-row;

        }

        .table-header {
            background-color: #F1F1F2;
            /* Gray background color */
        }

        .table-cell {
            display: table-cell;
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
            font-size: 16px;
            color: #777777;
            /* Rounded borders */
        }

        .bold {
            font-weight: bold;
            /* Bold text */
        }

        .late {
            color: red;
            font-weight: bold;
        }

        .reason-button {
            cursor: pointer;
            display: inline-block;
            /* Ensure the link occupies the entire space of the div */
            width: 100%;
            height: 100%;
        }

        .reason-button:hover {
            text-decoration: underline;
            /* Optional: Add underline on hover */
        }

        .reason-button.disabled {
            cursor: not-allowed;
        }

        .reason-button.enabled i {
            color: blue;
            /* Change the color of the icon to blue */
        }

        textarea[readonly] {
            background-color: #ffffff !important;
            /* Set background color to white */
            cursor: default;
            /* Set cursor to default */
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

        #detailsSched {
            display: flex;
            justify-content: space-between;
            /* Adjust spacing as needed */
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

            #LabelText h4 {
                font-size: 20px;
                /* Adjust this value as needed */
            }

            #courseNameLabel {
                font-size: 20px;

            }

            #courseDescription {
                font-size: 12px;

            }

            #detailsSched {
                flex-direction: column;
            }

            .location,
            .timeSchedule,
            .datefrom {
                font-size: 14px;
                /* Adjust this value as needed */
            }

            #seperator {
                display: none;
                /* Hide the separator */
            }

            /* Table Header */
            #timeinputsText {
                font-size: 10px;
                /* Adjust font size as needed */
            }

            .table-header .table-cell {
                font-size: 10px;
                /* Adjust font size for table header cells */
            }

            /* Table Body */
            .table-cell {
                font-size: 10px;
                /* Adjust font size for table cells */
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="images/logoo.png" height="40" alt="">
        <h4><i class="text-light ml-3 mr-2 py-3" style="font-family:Times New Roman, cursive;font-size:100%;">Mary Grace Café</i></h4>
    </div>
    <div id="parent-div" class="pt-3 pr-3 pl-3 pb-3 mt-4 mr-3 ml-3 rounded">
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
        <div id="LabelText" class="d-flex">
            <a id="training-courses-link" href="#">
                <h4 class="mb-0 mr-3" id="myTrainingCourseTitle" style="font-weight: bold;">My Training Courses</h4>
            </a>
            <a href="">
                <h4 id="slash" class="mr-3">/</h4>
            </a>
            <a href="">
                <h4 class="mb-0 mr-3" style="font-weight: bold;" id="courseName"></h4>
            </a>
        </div>
        <hr class="mb-3 mt-2">
        <div id="MyTrainingDetails">

            <h3 id="courseNameLabel" class="mb-2 mt-4" style="font-weight:bold; color: #098CFD;"></h3>
            <h5 id="courseDescription" class="mb-0 mt-0" style="font-style: italic;"></h5>

            <div id="branchDiv">
                <h4 id="branchName"></h4>
                <div id="branchList"></div>
                <label id="seeMoreBtn" onclick="showMoreBranches()">See more</label>
            </div>

            <h5 class="ml-2 mb-1" style="font-weight: bold;">Course Topics and Schedule:</h5>
            <div id="container"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="reasonModals" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-flex flex-column align-items-center">
                    <h3 class="modal-Reason mt-3" id="reasonModalLong">Reason for Late/Absence</h3>
                </div>
                <div class="modal-body">
                    <!-- Textarea with minimum height -->
                    <textarea id="reasonTextarea" class="form-control min-height-10"></textarea>

                    <!-- Choose File input -->
                    <div class="form-group mt-3">
                        <label for="fileInput">Attach File:</label>
                        <input type="file" class="form-control-file" id="fileInput" accept="image/png, image/jpeg">
                        <!-- Limiting to PNG and JPEG images -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitReasonBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- My Modal Already Attached -->
    <div class="modal fade" id="reasonModalAlreadyAttached" tabindex="-1" role="dialog" aria-labelledby="modalAlreadyAttachedTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-flex flex-column align-items-center">
                    <h3 class="modal-Reason mt-3" id="reasonModalAlreadyAttached">My Reason for Late/Absence Attachment</h3>
                </div>
                <div class="modal-body">
                    <!-- Textarea with minimum height -->
                    <textarea id="reasonTextareaAlreadyAttached" class="form-control min-height-10"></textarea>
                    <!-- Filename link container -->
                    <div class="d-flex mt-2 align-items-center">
                        <p class="mr-2 mb-0" style="font-weight: bold;">My Attachment: </p>
                        <div id="filenameLinkMyAttachment" class="mb-0"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Function to show more branches
    function showMoreBranches() {
        $('#branchList h4').show();
        $('#seeMoreBtn').hide(); // Optionally hide the button after showing all branches
    }

    // Function to get URL parameter by name
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    // Get the value of EmpNoId parameter from the URL
    var empNoId = getParameterByName('empno');

    // Set the href attribute of the training courses link
    var trainingCoursesLink = document.getElementById('training-courses-link');
    trainingCoursesLink.href = 'my-training-courses.php?empno=' + empNoId;

    // Get the value of CourseName parameter from the URL
    var courseName = getParameterByName('CourseName');
    var course_id = getParameterByName('course_id');
    var schedule_id = getParameterByName('schedule_id');

    // Display the CourseName value in the HTML element
    document.getElementById('courseName').textContent = courseName;

    // console.log(empNoId);
    // Send AJAX request to fetch_empno_course.php
    $.ajax({
        url: 'fetch_empno_details.php',
        type: 'GET',
        data: {
            EmpNoId: empNoId,
            CourseName: courseName,
            courseId: course_id,
            scheduleId: schedule_id
        },
        dataType: 'json',
        success: function(response) {
            // Handle the response
            // console.log("Employee details: ", response);

            // Check if there's an error in the response
            if (response.hasOwnProperty('error')) {
                console.error(response.error);
                return;
            }

            var userInfo = response.user_info[0]; // Assuming user_info is an array with one object

            // Populate the HTML elements with the data in all capital letters
            document.getElementById('employeeId').innerText = userInfo.empno.toUpperCase();
            document.getElementById('employeeName').innerText = userInfo.name.toUpperCase();
            document.getElementById('employeeBranch').innerText = userInfo.branch.toUpperCase();
            document.getElementById('employeePosition').innerText = userInfo.position.toUpperCase();

            // Extract name and description from the first item in details_data
            var name = response.details_data[0].name;
            var description = response.details_data[0].description;

            // Update HTML elements with the extracted values
            $('#courseNameLabel').text(name);
            // console.log(name);
            $('#courseDescription').text(description);
            // console.log(description);

            // Extract branch values from details_data
            var details = response.details_data;
            var branchValues = details.map(function(detail) {
                return detail.branch;
            });

            // Remove duplicate branch values
            var uniqueBranches = branchValues.filter(function(value, index, self) {
                return self.indexOf(value) === index;
            });
            // Remove duplicate branch values
            var uniqueBranches = branchValues.filter(function(value, index, self) {
                return self.indexOf(value) === index;
            });

            // Generate HTML dynamically for each unique branch
            var branchHtml = '';
            uniqueBranches.forEach(function(branch, index) {
                // Apply a CSS class for styling the badge background color
                if (index < 5) {
                    branchHtml += '<h4 class="m-1 pr-4 pl-4 pt-2 pb-2 badge badge-branch">' + branch + '</h4>';
                } else {
                    branchHtml += '<h4 class="m-1 pr-4 pl-4 pt-2 pb-2 badge badge-branch" style="display:none;">' + branch + '</h4>';
                }
            });

            // Replace the content of the branchList with the generated HTML for branch details
            $('#branchList').html(branchHtml);

            // Show the "SEE MORE" button if there are more than 5 branches
            if (uniqueBranches.length > 5) {
                $('#seeMoreBtn').show();
            } else {
                $('#seeMoreBtn').hide();
            }

            // Get the container element
            const container = $('#container');

            // Loop through each day data
            response.per_day_data.forEach(function(dayData, index) {
                // Get the formatted date
                const formattedDate = new Date(dayData.datefrom).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Create a div to hold the modified content
                const modifiedContent = $('<div class="parentsTable pr-4 pl-4 pt-2 border rounded-more mb-5"></div>');

                // Modify and append the content
                modifiedContent.html(parentsTableHTML);
                modifiedContent.find('.datefrom').html('<i class="fas fa-calendar-alt"></i> ' + formattedDate);
                modifiedContent.find('.location').html('<i class="fas fa-map-marker-alt"></i> ' + (dayData.location || '<span class="badge badge-secondary">NO SET</span>'));
                modifiedContent.find('.timeSchedule').html('<i class="fas fa-clock"></i> ' + (dayData.starttime || '<span class="badge badge-secondary">NO SET</span>') + ' - ' + (dayData.endtime || '<span class="badge badge-secondary">NO SET</span>'));

                // Extract the time inputs data for the current day
                const timeInputs = response.timeinputs_data[index];

                // Format and update the HTML elements with time inputs
                const timeIn = formatTime(timeInputs.M_timein) || '--:--';
                // console.log(timeInputs);
                // console.log(timeInputs.M_timein);

                const breakOut = timeInputs.M_timeout === 'No Break' ? 'No Break' : (formatTime(timeInputs.M_timeout) || '--:--');
                const breakIn = timeInputs.A_timein === 'No Break' ? 'No Break' : (formatTime(timeInputs.A_timein) || '--:--');
                const timeOut = formatTime(timeInputs.A_timeout) || '--:--';

                // Function to parse time string into Date object
                function parseTime(timeString) {
                    const [hours, minutes] = timeString.split(':').map(Number);
                    const date = new Date();
                    date.setHours(hours);
                    date.setMinutes(minutes);
                    return date;
                }

                let totalLate = 0;

                // Calculate total late based on M_timein
                const startTime = parseTime(dayData.starttime);
                const mTimeIn = parseTime(timeIn);
                const lateMinutes = (mTimeIn - startTime) / (1000 * 60); // Difference in minutes

                // Check if late
                if (lateMinutes > 0) {
                    totalLate += lateMinutes;
                }

                // Calculate break time between M_timeout and A_timein
                const mTimeOut = parseTime(breakOut);
                const aTimeIn = parseTime(breakIn);
                const breakTime = (aTimeIn - mTimeOut) / (1000 * 60); // Break time in minutes

                // Check if break time exceeds threshold
                if (breakTime > 60) {
                    totalLate += (breakTime - 60); // Add excess break time to total late
                }

                // Update the HTML elements with time inputs
                modifiedContent.find('#timeIn').text(timeIn);
                modifiedContent.find('#breakOut').text(breakOut);
                modifiedContent.find('#breakIn').text(breakIn);
                modifiedContent.find('#timeOut').text(timeOut);
                modifiedContent.find('#lateMinutes').text(totalLate);

                // Update lateMinutes text
                const lateMinutesElement = modifiedContent.find('#lateMinutes');
                if (totalLate > 0) {
                    lateMinutesElement.text(totalLate);
                } else {
                    lateMinutesElement.text('--:--');
                }

                // Add or remove class based on totalLate value
                if (totalLate > 0) {
                    lateMinutesElement.addClass('late');
                } else {
                    lateMinutesElement.removeClass('late');
                }

                // Log values
                // console.log("value of dayData.Starttime: " + dayData.starttime);
                // console.log("value of M_timein: " + timeIn);
                // console.log("value of M_timeout: " + breakOut);
                // console.log("value of A_timein: " + breakIn);
                // console.log("value of A_timeout: " + timeOut);
                // console.log("Total Late: " + totalLate + " minutes");
                // console.log("<br>");

                // Check if all time-related values are '--:--'
                if ((timeIn === '--:--' && breakOut === '--:--' && breakIn === '--:--' && timeOut === '--:--') && timeInputs.isAbsent !== "1") {
                    // Hide the "Present" and "Absent" badges
                    modifiedContent.find('.present').hide();
                    modifiedContent.find('.absent').hide();
                    // console.log("Check if all time-related values are '--:--' and isAbsent is not equal to 1");
                }

                // Check if dayData.location is empty or null and at least one time-related value is not '--:--'
                else if ((!dayData.location || dayData.location.trim() === '') && (timeIn !== '--:--' || breakOut !== '--:--' || breakIn !== '--:--' || timeOut !== '--:--')) {
                    // Hide the "Present" and "Absent" badges
                    modifiedContent.find('.present').hide();
                    modifiedContent.find('.absent').hide();
                    // console.log("Check if dayData.location is empty or null and at least one time-related value is not '--:--'");
                }

                // Check if at least one time-related value is present and isAbsent is not equal to 1
                else if ((timeIn !== '--:--' || breakOut !== '--:--' || breakIn !== '--:--' || timeOut !== '--:--') && timeInputs.isAbsent !== "1") {
                    // If at least one value is present and isAbsent is not equal to 1, hide the "Absent" badge
                    modifiedContent.find('.absent').hide();

                    // console.log("Check if at least one time-related value is present and isAbsent is not equal to 1");
                    // console.log(formattedDate);
                    // console.log(dayData.starttime);
                    // console.log(dayData.location)


                } else if (timeInputs.isAbsent === "1") {
                    modifiedContent.find('.present').hide();
                    var reasonButton = modifiedContent.find('.reason-button');
                    reasonButton.removeClass('disabled');
                    reasonButton.off('click').on('click', function() {
                        // console.log("Reason button clicked");

                        // Check if 'dayData.reason' is empty
                        if (timeInputs.reason !== "") {
                            // Reason already exists, show the modal for already attached reason
                            $('#reasonModalAlreadyAttached').modal('show');
                            // console.log("reason value 1: " + timeInputs.reason);
                            // console.log("attachment value 1: " + timeInputs.attachment);

                            // Set values to modal fields
                            $('#reasonTextareaAlreadyAttached').val(timeInputs.reason);

                            // Display the filename link
                            if (timeInputs.attachment) {
                                var filenameLink = "<a href='lnd_attachment/" + timeInputs.attachment + "' target='_blank'>" + timeInputs.attachment + "</a>";
                                $('#filenameLinkMyAttachment').html(filenameLink);
                            } else {
                                $('#filenameLinkMyAttachment').html('');
                            }
                            // Make the textarea readonly
                            $('#reasonTextareaAlreadyAttached').prop('readonly', true);

                        } else {
                            // Reason is empty, show the modal for providing a new reason
                            $('#reasonModals').modal('show');
                            // console.log("reason value 2: " + timeInputs.reason);
                            // console.log("attachment value 2: " + timeInputs.attachment);
                        }

                        // Reset textarea and choose file input when modal is shown
                        $('#reasonModals').off('show.bs.modal').on('show.bs.modal', function() {
                            $('#reasonTextarea').val('');
                            $('#fileInput').val('');
                        });

                        // Autofocus on the textarea when modal is shown
                        $('#reasonModals').off('shown.bs.modal').on('shown.bs.modal', function() {
                            $('#reasonTextarea').focus();
                        });


                        // ---------- WITH REASON AND ATTACHMENT REQUIRED! DO NOT DELETE
                        // $('#submitReasonBtn').off('click').on('click', function() {
                        //     var reason = $('#reasonTextarea').val();
                        //     var fileInput = $('#fileInput')[0].files[0];
                        //     if (reason.trim() === '' || !fileInput) {
                        //         alert("Please provide a reason and attach a file before submitting.");
                        //         return;
                        //     }

                        //     if (confirm("Are you sure you want to submit this reason?")) {
                        //         var urlParams = new URLSearchParams(window.location.search);
                        //         var empNoId = urlParams.get('empno');
                        //         var datefrom = dayData.datefrom;
                        //         var formData = new FormData();
                        //         formData.append('reason', reason);
                        //         formData.append('fileInput', fileInput);
                        //         formData.append('EmpNoId', empNoId);
                        //         formData.append('datefrom', datefrom);

                        //         $.ajax({
                        //             url: 'update_reasonattachment.php',
                        //             type: 'POST',
                        //             data: formData,
                        //             contentType: false,
                        //             processData: false,
                        //             success: function(response) {
                        //                 $('.confirmation-message').text("Reason submitted successfully!");
                        //                 console.log(response);
                        //                 $('#reasonModals').modal('hide');
                        //                 // Reload the page after successful submission
                        //                 location.reload();
                        //             },
                        //             error: function(xhr, status, error) {
                        //                 $('.confirmation-message').text("Error occurred while submitting reason!");
                        //                 console.error(xhr.responseText);
                        //             }
                        //         });
                        //     } else {
                        //         console.log("Reason submission canceled.");
                        //     }
                        // });


                        $('#submitReasonBtn').off('click').on('click', function() {
                            var reason = $('#reasonTextarea').val();
                            var fileInput = $('#fileInput')[0].files[0];

                            if (reason.trim() === '') {
                                alert("Please provide a reason before submitting.");
                                return;
                            }

                            if (confirm("Are you sure you want to submit this reason?")) {
                                var urlParams = new URLSearchParams(window.location.search);
                                var empNoId = urlParams.get('empno');
                                var datefrom = dayData.datefrom;
                                var formData = new FormData();
                                formData.append('reason', reason);

                                if (fileInput) {
                                    formData.append('fileInput', fileInput);
                                }

                                formData.append('EmpNoId', empNoId);
                                formData.append('datefrom', datefrom);

                                $.ajax({
                                    url: 'update_reasonattachment.php',
                                    type: 'POST',
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        $('.confirmation-message').text("Reason submitted successfully!");
                                        console.log(response);
                                        $('#reasonModals').modal('hide');
                                        // Reload the page after successful submission
                                        location.reload();
                                    },
                                    error: function(xhr, status, error) {
                                        $('.confirmation-message').text("Error occurred while submitting reason!");
                                        console.error(xhr.responseText);
                                    }
                                });
                            } else {
                                console.log("Reason submission canceled.");
                            }
                        });

                    });
                }

                // Append the modified content to the container
                container.append(modifiedContent);

                // Check if topicsName is available for the current day
                if (dayData.hasOwnProperty('no_of_topics')) {
                    try {
                        const topics = JSON.parse(dayData.no_of_topics);
                        const topicsDiv = $('<div class="d-flex"></div>');
                        topics.forEach(function(topic) {
                            // Create the main h4 element for the topic name
                            const topicNameElement = $('<h4 id="topicsText" class="mr-3 mb-0 mt-2 rounded border border-gray" style="font-weight: bold; color: #098CFD; padding: 10px; box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">' + topic.topicsName + '</h4>');

                            // Append both elements to the topicsDiv
                            topicsDiv.append(topicNameElement);
                        });

                        // Insert the topicsDiv after the #topicsName element
                        modifiedContent.find('#topicsName').after(topicsDiv);

                    } catch (error) {
                        // console.error('Error parsing JSON:', error);
                    }
                }

            });
            // Function to format time to HH:MM format in 24-hour (military) time
            function formatTime(time) {
                if (!time) return ''; // Return empty string if time is not provided
                return new Date(time).toLocaleTimeString('en-US', {
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(xhr.responseText);
        }
    });

    const parentsTableHTML = `
    <div class="d-flex">
        <div id="detailsSched">
            <h5 class="location mb-0 mr-3">
                <i class="fas fa-map-marker-alt"></i> Room 1
            </h5>
            <h5 class="timeSchedule mb-0 mr-3">
                <i class="fas fa-clock"></i> 07:00 - 16:00
            </h5>
            <h5 class="datefrom mb-0 mr-3">
                <i class="fas fa-calendar-alt"></i> January 2, 2024
            </h5>
        </div>
        <h5 id="seperator" class="mr-3" style="font-weight:;">│
        </h5>
        <h5 class="present mr-3" style="font-weight:;">
            <span style="font-weight; bold; background-color: #26C808; color: white; padding: 3px 10px; border-radius: 20px;">Present
            </span>
        </h5>
        <h5 class="absent mr-3" style="font-weight:;">
            <span style="font-weight; bold; background-color: #EF5132; color: white; padding: 3px 10px; border-radius: 20px;">Absent
            </span>
        </h5>
    </div>
        <hr class="mb-2 mt-1">
    <div class="d-flex">
        <h4 id="topicsName" class="" style="font-weight: bold;"></h4>
        <h4 id="topicDescription" class="" style="font-weight: bold;"></h4>
    </div>

    <div class="table">
        <!-- Table Header -->
        <h5 id="timeinputsText" class="mb-1 mt-3" style="">Time Inputs:</h5>
        <div class="table-header bg-gray">
            <div class="table-cell bold">TIME IN</div>
            <div class="table-cell bold">BREAK OUT</div>
            <div class="table-cell bold">BREAK IN</div>
            <div class="table-cell bold">TIME OUT</div>
            <div class="table-cell bold">LATE</div>
            <div class="table-cell bold">ACTION</div>
        </div>
        <!-- Table Body -->
        <div class="table-row">
            <div id="timeIn" class="table-cell">--:--</div>
            <div id="breakOut" class="table-cell">--:--</div>
            <div id="breakIn" class="table-cell">--:--</div>
            <div id="timeOut" class="table-cell">--:--</div>
            <div id="lateMinutes" class="table-cell">--:--</div>
            <div class="table-cell">
            <i class="reason-button disabled far fa-file"></i>
            </div>
        </div>
    </div>`;

    $(document).ready(function() {
        // Set the height of the textarea to at least 10% of the viewport height
        var viewportHeight = $(window).height();
        var minTextareaHeight = viewportHeight * 0.3; // 10% of viewport height
        $('#reasonTextarea').css('min-height', minTextareaHeight + 'px');
    });

    $(document).ready(function() {
        // Set the height of the textarea to at least 10% of the viewport height
        var viewportHeight = $(window).height();
        var minTextareaHeight = viewportHeight * 0.3; // 10% of viewport height
        $('#reasonTextareaAlreadyAttached').css('min-height', minTextareaHeight + 'px');
    });
</script>

</html>