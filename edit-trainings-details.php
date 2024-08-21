<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
unset($_SESSION['viewPrintSched']);
unset($_SESSION['emp_sched']);
if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$userlevel = $row['userlevel'];
$empno = $row['empno'];

$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
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
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add this in the <head> section of your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <style>
        .modal-lg-custom {
            max-width: 80%;
        }

        .green-header {
            background: linear-gradient(to right, #DBE2F8, #EDF0FC);
            color: white;
        }

        .flex-container {
            display: flex;
            flex-direction: column;
            /* Change to column layout */
            align-items: flex-start;
            /* Align items to the start of the container */
            width: 100%;
        }

        .custom-bg-color {
            background-color: #f2f2f2;
        }

        /* Custom class for modal animation */
        .modal-bounce {
            animation: modal-bounce 0.5s;
        }

        .swal2-confirm {
            background-color: #28a745 !important;
            /* Green */
            color: white !important;
            border: none !important;
        }

        .swal2-cancel {
            background-color: #dc3545 !important;
            /* Red */
            color: white !important;
            border: none !important;
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
        }

        tr.disabled svg.icon-pointer {
            pointer-events: auto;
            /* Re-enable pointer events for the SVG elements */
        }

        tr.disabled input[type="time"] {
            background-color: #f2f2f2;
            /* Set the background color of time inputs to match the row background */
            color: #777;
            /* Set the text color of time inputs to a darker shade */
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .time-input {
            width: 150px !important;
            /* Corrected width value */
            text-align: center !important;
        }

        .action-column {
            width: 2px !important;
            /* Adjust the width as needed */
        }

        #trainingDetails th.th-width {
            width: 50px !important;
            /* Adjust this value as needed */
        }

        th.text-center {
            width: 120px !important;
        }

        /* Style for the input fields */
        .text-center {
            text-align: center;
        }

        .rounded {
            border-radius: 5px;
        }

        .border {
            border: 1px solid #ccc;
            /* Set the outline style */
            outline: none;
            /* This removes the outline */
        }

        /* Add focus style */
        .border:focus {
            border-color: #2E59D9;
            box-shadow: 0 0 4px #2E59D9;
        }

        .icon-pointer {
            cursor: pointer;
        }

        @media screen and (max-width: 800px) {
            table {
                border: 0;
            }

            table caption {
                font-size: 1.3em;
            }

            table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table tr {
                border-bottom: 5px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }

            table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }

        /* New media query for screen widths 400px or less */
        @media screen and (max-width: 500px) {

            #LabelTrainingSchedule,
            #addTrainingBatch,
            #addTrainingDay {
                font-size: 0.75rem !important;
                color: red !important;
                /* Adjust the font size as needed */
            }
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">
    <?php
    include("navigation.php");
    include("course/filterModal.php");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <div class="d-flex">
                    <a href="createtrainingschedules.php">
                        <h4 class="mb-0 mr-3" id="LabelTrainingSchedule" style="font-weight: bold;">Training Schedule</h4>
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
        <!-- Datatable -->
        <div class="card shadow mb-3">
            <div class="card-header py-3 row m-0 d-flex justify-content-between">
                <!-- + New Courses  -->
                <div class="col-sm-6 ">
                    <!-- <button> -->
                    <!-- <span class="mr-3"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                        </svg> -->
                    <!-- </button> -->
                </div>
                <button id="addEmployees" class="btn btn-primary pt-2 text-center">
                    <h6 class="mb-1 font-weight-bold">
                        <i class="mr-1 fas fa-user-plus"></i> Add New Trainees
                    </h6>
                </button>
            </div>
            <!-- Datatable -->
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover text-uppercase" id="trainingDetails" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="text-center th-width">EMPLOYEE ID</th>
                            <th class="text-center">EMPLOYEE NAME</th>
                            <th class="text-center">DEPARTMENT</th>
                            <th class="text-center">TIME SCHEDULE</th>
                            <th class="text-center th-width">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <div>
                <a href="#" class="btn btn-primary" style="font-weight: bold;" id="doneBtn">
                    Done
                </a>
            </div>
        </div>


        <!-- Add Select Employees Modal -->
        <div class="modal fade" id="modalAddNewEmployees" tabindex="-1" role="dialog" aria-labelledby="modalAddNewEmployeesTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header green-header">
                        <div class="flex-container">
                            <h5 class="modal-title m-0 font-weight-bold text-primary" id="exampleModalLongTitle">List of Employees</h5>
                            <h6 class="m-0 font-weight-bold text-primary">Total of Selected: <span style="color: red;" id="selectedCount">0</span></h6>
                            <span style="color: red; display: none;" class="mt-2" id="noSelectError">Please select at least one employee before submitting.</span>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-wrapper">
                            <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="selectEmployeesTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="custom-bg-color">
                                        <th>Select</th>
                                        <th>Employee No</th>
                                        <th>Name</th>
                                        <th>Branch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary font-weight-bold" id="submitEmployees">
                            Submit
                        </button>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Add DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <!-- JSZip -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- PDFMake -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <!-- Buttons HTML5 -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <!-- Buttons Print -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!-- SheetJS -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script src="js/ajax-course.js"></script>
</body>

<script>
    window.onload = function() {
        var urlParams = new URLSearchParams(window.location.search);
        var batchNumber = urlParams.get('id');
        var day = urlParams.get('day'); // Get the value of the 'day' parameter
        var datefrom = urlParams.get('datefrom'); // Get the value of the 'day' parameter
        var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter

        var courseId = urlParams.get('course_id'); // Get the value of the 'course_id' parameter
        if (!batchNumber) {
            batchNumber = ''; // Set a default value, such as an empty string
        }

        var addTrainingBatch = document.getElementById('addTrainingBatch');
        // Set the URL without the word "Batch"
        var url = 'view-batch-schedules.php?id=' + batchNumber + '&schedule_id=' + scheduleId;
        // Set the link text to include the word "Batch"
        addTrainingBatch.innerHTML = '<a href="' + url + '">Batch ' + batchNumber + '</a>';

        // Construct the Go Back URL
        var goBackUrl = 'view-batch-schedules.php?id=' + encodeURIComponent(batchNumber) +
            '&day=' + encodeURIComponent(day)

        // Set the href attribute of the Go Back link
        var goBackLink = document.getElementById('goBackLink');
        goBackLink.href = goBackUrl;

        var addTrainingDay = document.getElementById('addTrainingDay');
        addTrainingDay.innerText = 'Day ' + day + ' — Add Training Details'; // Concatenate the value of 'day'

    }

    $(document).ready(function() {
        // Get the value of 'id' from the URL parameter
        var id = getUrlParameter('id');
        var datefrom = getUrlParameter('datefrom');
        var day = getUrlParameter('day');
        var schedule_id = getUrlParameter('schedule_id');


        // Show loading indicator
        var loadingIndicator = Swal.fire({
            title: 'Loading data...',
            timerProgressBar: true,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch data from fetch_display_empno_details.php with 'id' parameter
        $.ajax({
            url: 'fetch_display_empno_details.php',
            type: 'GET',
            data: {
                id: id,
                datefrom: datefrom,
                day: day,
                schedule_id, schedule_id
            },
            dataType: 'text', // Expecting text so we can handle the parsing ourselves
            success: function(responseText) {
                var response;
                try {
                    response = JSON.parse(responseText);
                } catch (e) {
                    console.error('Parsing error:', e);
                    console.error('Response was:', responseText);
                    alert('An error occurred while processing the response from the server.');
                    return;
                }

                // console.log(response);
                // Populate the table with fetched emp data
                var tableBody = $('#tableBody');
                var displayedEmpnos = {}; // Object to track displayed employee numbers
                // Loop through empData array
                response.empData.forEach(function(empData) {
                    var empno = empData.empno;
                    var isExclude = empData.isExclude; // Accessing isExclude variable
                    var dateExclude = empData.empno_dateExclude; // Accessing isExclude variable
                    if (!displayedEmpnos[empno]) {
                        // console.log(empData.empno);
                        var newRow = $('<tr data-datefrom="' + empData.datefrom + '">' +
                            '<td class="text-center">' + empno + '</td>' +
                            '<td class="text-center">' + empData.name + '</td>' +
                            '<td class="text-center">' + empData.branch + '</td>' +
                            '<td class="text-center">' +
                            '<input type="time" name="startTime" class="rounded border time-input" placeholder="00:00" value="' + empData.schedfrom + '"> - ' +
                            '<input type="time" name="endTime" class="rounded border time-input" placeholder="00:00" value="' + empData.schedto + '">' +
                            '</td>' +
                            '<td class="text-center action-column">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20" class="icon-pointer" fill="#FF0000"><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM472 200H616c13.3 0 24 10.7 24 24s-10.7 24-24 24H472c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>' +
                            '</td>' +
                            '</tr>');

                        if (isExclude == 1 && dateExclude == datefrom) {
                            newRow.find('.action-column').html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20" class="icon-pointer exclude-icon" fill="#008000"><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>');

                            newRow.find('.exclude-icon').click(function(event) {
                                event.stopPropagation();
                                EnabledRow(empno, empData.name, day, newRow);
                            });
                        } else {
                            newRow.find('.action-column').click(function(event) {
                                event.stopPropagation();
                                DisabledRow(empno, empData.name, day, newRow);
                            });
                        }

                        if (isExclude == 1 && dateExclude == datefrom) {
                            newRow.addClass('disabled');
                        }

                        tableBody.append(newRow);
                        displayedEmpnos[empno] = true;
                    }

                });

                $('input[name="startTime"], input[name="endTime"]').on('input', validateTime);

                function validateTime(event) {
                    const timePattern = /^([01]\d|2[0-3]):([0-5]\d)$/;
                    const value = event.target.value;
                    if (!timePattern.test(value)) {
                        alert('Please enter a valid time between 00:00 and 23:59');
                        event.target.value = '';
                    }
                }

                loadingIndicator.close();

                // Initialize the DataTable after the table data has been populated, only if it's not already initialized
                if (!$.fn.DataTable.isDataTable('#trainingDetails')) {
                    $('#trainingDetails').DataTable({
                        stateSave: true
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                loadingIndicator.close();
            }
        });

        $('#doneBtn').on('click', function(e) {
            e.preventDefault(); // Prevent the default action

            // Show loading indicator
            var loadingIndicator = Swal.fire({
                title: 'Updating the schedules for each employee ...',
                timerProgressBar: true,
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Reference to the already initialized DataTable
            var table = $('#trainingDetails').DataTable();

            // Gather data from the table
            var updatedSchedules = [];
            table.rows().every(function() {
                var $row = $(this.node());
                if (!$row.hasClass('disabled')) { // Exclude disabled rows
                    var empno = $row.find('td').eq(0).text();
                    var schedfrom = $row.find('input').eq(0).val();
                    var schedto = $row.find('input').eq(1).val();
                    var datefrom = $row.data('datefrom'); // Assuming datefrom is stored as a data attribute

                    updatedSchedules.push({
                        empno: empno,
                        starttime: schedfrom,
                        endtime: schedto,
                        datefrom: datefrom,
                    });
                }
            });

            // Log the data being sent
            // console.log(JSON.stringify({
            //     schedules: updatedSchedules
            // }));

            // Send AJAX request to update the database
            $.ajax({
                url: 'update_training_schedule.php',
                type: 'POST',
                data: JSON.stringify({
                    schedules: updatedSchedules
                }),
                contentType: 'application/json', // Important for sending JSON data
                dataType: 'json',
                success: function(response) {
                    // Hide loading indicator
                    loadingIndicator.close();
                    Swal.fire({
                        title: 'Success!',
                        text: 'Time schedule successfully updated.',
                        icon: 'success',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // var returnViewBatchSchedule = 'view-batch-schedules.php?id=' + getUrlParameter('id');
                        // window.location.href = returnViewBatchSchedule;
                        
                        // Construct the URL with the id and schedule_id parameters
                        var returnViewBatchSchedule = 'view-batch-schedules.php?id=' + id + '&schedule_id=' + schedule_id;

                        // Redirect to the new URL
                        window.location.href = returnViewBatchSchedule;
                    });
                },
                error: function(xhr, status, error) {
                    // Hide loading indicator
                    console.error('AJAX Error Updating Schedule:', status, error);
                    console.error('Response:', xhr.responseText);
                    loadingIndicator.close();
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while updating the schedule. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

    });

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    // Define Toast globally
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    function DisabledRow(empno, name, day, row) {
        var id = getUrlParameter('id');
        var datefrom = getUrlParameter('datefrom');
        var day = getUrlParameter('day');
        var scheduleId = getUrlParameter('schedule_id');

        Swal.fire({
            title: "Exclude <strong>" + name + "</strong> from Day " + day + "?",
            html: `<p>By confirming, you acknowledge that selected trainee will be excluded from the training.</p>`,
            icon: "warning",
            showCancelButton: false,
            showCloseButton: true,
            confirmButtonText: "Confirm",
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm'
            }

        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: 'insert_excluded_trainees.php',
                    data: {
                        empno: empno,
                        id: id,
                        datefrom: datefrom,
                        scheduleId: scheduleId
                    },
                    success: function(response) {
                        row.addClass('disabled');

                        // Check if exclude icon already exists
                        var existingExcludeIcon = row.find('.exclude-icon');
                        if (existingExcludeIcon.length === 0) {
                            row.find('.action-column').html('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20" class="icon-pointer exclude-icon" fill="#008000"><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM504 312V248H440c-13.3 0-24-10.7-24-24s10.7-24 24-24h64V136c0-13.3 10.7-24 24-24s24 10.7 24 24v64h64c13.3 0 24 10.7 24 24s-10.7 24-24 24H552v64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/></svg>');

                            // Attach click event to the new exclude icon
                            row.find('.exclude-icon').click(function(event) {
                                event.stopPropagation();
                                EnabledRow(empno, name, day, row);
                            });
                        }

                        // Trigger the Toast notification for success
                        Toast.fire({
                            icon: "warning",
                            title: "Successfully tagged as excluded"
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
    }

    function EnabledRow(empno, name, day, row) {
        Swal.fire({
            title: "Re-Add <strong>" + name + "</strong> to Day " + day + "?",
            html: `<p>By confirming, you acknowledge that selected trainee will be re-added to the training.</p>`,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            reverseButtons: true,
            customClass: {
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'delete_excluded_trainees.php',
                    type: 'POST',
                    data: {
                        empno: empno,
                        name: name,
                        day: day
                    },
                    success: function(response) {
                        row.removeClass('disabled');

                        var existingActionIcon = row.find('.icon-pointer');
                        existingActionIcon.replaceWith('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="20" height="20" class="icon-pointer" fill="#FF0000"><path d="M96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM472 200H616c13.3 0 24 10.7 24 24s-10.7 24-24 24H472c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/></svg>');

                        row.find('.action-column').click(function(event) {
                            event.stopPropagation();
                            DisabledRow(empno, name, day, row);
                        });

                        // Trigger the Toast with an information icon
                        Toast.fire({
                            icon: 'info', // Information icon
                            title: "Trainees successfully re-added" // Updated message
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
        });
    }

    $(document).ready(function() {

        var urlParams = new URLSearchParams(window.location.search);
        var schedule_id = urlParams.get('schedule_id');
        var day = urlParams.get('day');
        var course_id = urlParams.get('course_id');

        var selectedEmployees = []; // Array to store selected employee data

        // Function to initialize DataTable
        function initializeDataTable() {
            $('#selectEmployeesTable').DataTable({
                ajax: {
                    url: 'fetch_addnewemployees.php',
                    dataSrc: ''
                },
                columns: [{
                        data: null,
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="employee-select" data-empno="' + row.empno + '" data-userid="' + row.userid + '" value="' + row.empno + '">';
                        }
                    },
                    {
                        data: 'empno'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'branch'
                    }
                ],
                stateSave: true,
                drawCallback: function(settings) {
                    // Reapply selection after redraw
                    applySelection();
                }
            });

            // Handle row click to toggle checkbox
            $('#selectEmployeesTable tbody').on('click', 'tr', function(e) {
                // Check if the click event was on the checkbox itself
                if (e.target.type !== 'checkbox') {
                    var checkbox = $(this).find('.employee-select');
                    checkbox.prop('checked', !checkbox.prop('checked')).change();
                }
            });
        }

        // Open modal when 'Add New Employees' button is clicked
        $('#addEmployees').click(function() {
            $('#modalAddNewEmployees').modal('show');
        });

        // Initialize DataTable when modal is shown
        $('#modalAddNewEmployees').on('shown.bs.modal', function() {
            if (!$.fn.DataTable.isDataTable('#selectEmployeesTable')) {
                initializeDataTable();
            } else {
                $('#selectEmployeesTable').DataTable().ajax.reload();
            }
        });

        // Handle checkbox change event to update selected count and track selections
        $(document).on('change', '.employee-select', function() {
            var isChecked = $(this).prop('checked');
            var empno = $(this).data('empno'); // Get the empno value
            var userid = $(this).data('userid'); // Get the userid value

            if (isChecked && selectedEmployees.indexOf(empno) === -1) {
                selectedEmployees.push({
                    empno: empno,
                    userid: userid
                });
            } else if (!isChecked && selectedEmployees.some(e => e.empno === empno)) {
                selectedEmployees = selectedEmployees.filter(function(employee) {
                    return employee.empno !== empno;
                });
            }

            updateSelectedCount();
        });

        // Function to update selected count in the UI
        function updateSelectedCount() {
            var selectedCount = selectedEmployees.length;
            $('#selectedCount').text(selectedCount);
            // console.log('Selected Employees:', selectedEmployees); // Log selectedEmployees array to console
        }

        // Apply selection based on stored selectedEmployees array
        function applySelection() {
            var checkboxes = $('#selectEmployeesTable').find('.employee-select');

            checkboxes.each(function() {
                var empno = $(this).data('empno');
                if (selectedEmployees.some(e => e.empno === empno)) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            });

            updateSelectedCount();
        }

        $('#submitEmployees').click(function() {
            // Check if any checkboxes are selected
            if (selectedEmployees.length === 0) {
                // Display error message
                $('#noSelectError').show();

                // Optionally, you can update the error message text dynamically here if needed
                $('#noSelectError').text('Please select at least one employee before submitting.');

                // Optionally, you can hide the error message after a delay
                setTimeout(function() {
                    $('#noSelectError').hide();
                }, 3000); // Hide after 3 seconds

                return; // Prevent further execution
            }

            // Prepare data to send to the server
            var data = {
                selectedEmployees: selectedEmployees,
                schedule_id: schedule_id, // Assuming schedule_id is defined elsewhere
                day: day, // Assuming day is defined elsewhere
                course_id: course_id // Assuming course_id is defined elsewhere
            };

            // Send AJAX request to update_addnewemployees.php
            $.ajax({
                url: 'update_addnewemployees.php',
                method: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {
                    // Handle success response
                    console.log('Update successful:', response);
                    // Optionally, perform any UI updates or actions upon success

                    // Reload the page after successful update
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Update failed:', error);
                    // Optionally, inform the user of the error or retry logic
                }
            });

            // Close the modal after sending the AJAX request
            $('#modalAddNewEmployees').modal('hide');
        });

        // Update selected count and manage error message display
        function updateSelectedCount() {
            var count = selectedEmployees.length;
            $('#selectedCount').text(count);

            // Hide error message if at least one employee is selected
            if (count > 0) {
                $('#noSelectError').hide();
            }
        }

        // Call updateSelectedCount initially and whenever selectedEmployees changes
        updateSelectedCount();

    });
</script>

</html>