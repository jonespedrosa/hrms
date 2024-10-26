<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
unset($_SESSION['viewPrintSched']);
unset($_SESSION['emp_sched']);

if (empty($_SESSION['user'])) {
    header('location:login.php');
    exit;
}

// Fetch user information
$sqlUserInfo = "SELECT userid, empno, userlevel, name, mothercafe
                FROM user_info
                WHERE empno = '" . $_SESSION['empno'] . "'";
$queryUserInfo = $HRconnect->query($sqlUserInfo);
$rowUserInfo = $queryUserInfo->fetch_array();
$userlevel = $rowUserInfo['userlevel'];
$empno = $rowUserInfo['empno'];
$name = $rowUserInfo['name'];
$userid = $rowUserInfo['userid'];

// Pass the userid to JavaScript securely
echo "<script>
    var userid = " . json_encode($userid) . ";
    console.log('User ID from PHP:', userid);
</script>";

// Fetch user area type from another table
$sqlUser = "SELECT username, areatype
            FROM user
            WHERE username = '" . $_SESSION['user']['username'] . "'";
$queryUser = $ORconnect->query($sqlUser);
$rowUser = $queryUser->fetch_array();
$areatype = $rowUser['areatype'];

// Fetch additional user info by userid
$sqlUserInfoById = "SELECT empno, name, status, is_compressed, pattern_id
                    FROM user_info
                    WHERE userid = '$userid'
                    AND status IN ('active', '')";
$queryUserInfoById = $HRconnect->query($sqlUserInfoById);

// Collect user information into an array
$employees = [];
if ($queryUserInfoById) {
    while ($rowUserWithName = $queryUserInfoById->fetch_array(MYSQLI_ASSOC)) {
        $employees[] = $rowUserWithName;
    }
}

// Output the employees array as JSON for JavaScript
echo "<script>var employees = " . json_encode($employees) . ";</script>";

// Fetch the date range (datefrom and dateto) for the current user     12
$getCutOffDateRange = "SELECT si.datefrom, si.dateto
                    FROM user_info ui
                    LEFT JOIN sched_info si ON si.empno = ui.empno
                    WHERE si.status = 'Pending' AND ui.empno = $empno";
$queryCutOffRange = $HRconnect->query($getCutOffDateRange);

// Ensure the query was successful and fetch the result
$cutoffrange_dateStart = null;
$cutoffrange_dateEnd = null;

if ($queryCutOffRange && $rowCutOffRange = $queryCutOffRange->fetch_array()) {
    $cutoffrange_dateStart = $rowCutOffRange['datefrom'];
    $cutoffrange_dateEnd = $rowCutOffRange['dateto'];
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>



    <style>
        .draggable-container {
            border: 1px solid #ccc;
            padding: 10px;
            height: 400px;
            width: 425px;
            /* Increase the container width if needed */
            overflow-y: auto;
        }

        .draggable {
            padding: 10px;
            /* Adjust padding if needed */
            margin: 5px;
            background-color: #f0f0f0;
            cursor: move;
            border-radius: 4px;
            transition: background-color 0.2s;
            width: 375px !important;
            /* Increase the width of the draggable boxes */
        }

        .draggable:hover {
            background-color: #e0e0e0;
        }

        .dropzone {
            border: 2px dashed #aaa;
            padding: 10px;
            height: 400px;
            width: 425px;
            overflow-y: auto;
            background-color: #fafafa;
            /* margin-top: 55px; */
        }

        .btn-light {
            background-color: #4E73DF;
            color: #fff;
            border-color: #4E73DF;
        }

        /* Hover effect for light blue button */
        .btn-light:hover {
            background-color: #2E59D9;
            color: #fff;
            border-color: #2E59D9;
        }

        /* Ensure the row content fits well within the modal */
        .modal-body .container .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .assigned-employee {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 5px 0;
            /* Spacing between assigned employees */
        }

        .remove-btn {
            margin-left: 10px;
        }

        .employee-checkbox {
            width: 12px;
            height: 12px;
            cursor: pointer;
            transform: scale(1.5);
            margin-right: 15px !important;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            /* or use 'space-around' or 'space-evenly' based on your design needs */
            align-items: center;
            /* Aligns items vertically */
        }



        /* Highlighted range: green background and dark text */
        .datepicker table tr td.in-range,
        .datepicker table tr td.in-range:hover {
            background-color: #d4edda !important;
            /* Light green */
            color: #155724 !important;
            /* Dark green */
            cursor: pointer;
            /* Normal pointer for valid dates */
        }

        /* Today's date within the range: yellow background */
        .datepicker table tr td.today.in-range {
            background-color: #ffc107 !important;
            /* Yellow */
            color: black !important;
        }

        /* Disabled dates: light gray background and disabled cursor */
        .datepicker table tr td.disabled,
        .datepicker table tr td.disabled:hover {
            background-color: #f0f0f0 !important;
            /* Light gray */
            color: #d6d6d6 !important;
            /* Faint text */
            cursor: not-allowed !important;
            /* Disable pointer */
        }

        #clearDate:focus {
            outline: none;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            /* Add spacing above the table */
        }

        .table th,
        .table td {
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            /* Border for table cells */
        }

        .table th {
            background-color: #f8f9fa;
            /* Light background for header */
            font-weight: bold;
        }

        .info-item {
            margin: 0;
            /* No margin top and bottom */
            /* Optional: Adjust line height */
        }

        .custom-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 10px auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .swal-html-content {
            text-align: center;
        }



        .swal-button-green {
            background-color: #48BF81 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            outline: none !important;
        }

        .swal-button-green:hover {
            background-color: #48BF81 !important;
        }

        .swal2-confirm {
            outline: none !important;
        }

        .time-select {
            border: 2px solid lightgray;
            border-radius: 8px;
            background-color: white;
            transition: border-color 0.3s;
        }

        .time-select:focus {
            border-color: #4A90E2;
            outline: none;
        }

        .schedule-type-select {
            border: 2px solid lightgray;
            border-radius: 8px;
            background-color: white;
            transition: border-color 0.3s;
        }

        .schedule-type-select:focus {
            border-color: #4A90E2;
            outline: none;
        }

        .schedule-name-select {
            border: 2px solid lightgray;
            border-radius: 8px;
            background-color: white;
            transition: border-color 0.3s;
        }

        .schedule-name-select:focus {
            border-color: #4A90E2;
            outline: none;
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
    </style>
</head>

<body id="page-top" class="sidebar-toggled">
    <?php
    include("navigation.php");
    // include("course/filterModal.php");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <h4 class="mb-0 mr-3 font-weight-bold">Schedule Pattern List</h4>
            </div>
        </div>
        <!-- Datatable -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 row m-0">
                <!-- + New Courses  -->
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <a href="javascript:void(0);" class="btn btn-primary font-weight-bold" data-bs-toggle="modal" data-bs-target="#newSchedulePattern">
                        <i class="mr-1 fas fa-plus"></i> New Schedule Pattern
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="displaySchedulePattern" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>SCHEDULE NAME</th>
                                <th>SCHEDULE TYPE</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal for Creation and viewing Pattern Schedules -->
        <div class="modal fade" id="newSchedulePattern" tabindex="-1" aria-labelledby="newSchedulePatternModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="font-weight: bold; color: #2E59D9;" id="newSchedulePatternModalLabel">Creating Schedule Pattern</h5>
                    </div>
                    <div class="modal-body">
                        <form id="schedulePatternForm">
                            <!-- Schedule Name -->
                            <div class="mb-3">
                                <label for="scheduleName" style="font-weight: bold;" class="form-label">Schedule Name:</label>
                                <input type="text" class="form-control schedule-name-select" id="scheduleName" placeholder="Enter schedule pattern name">
                            </div>
                            <!-- Schedule Type -->
                            <div class="d-flex align-items-center">
                                <div class="me-3 mr-4">
                                    <label for="scheduleType" class="form-label" style="font-weight: bold;">Schedule Type:</label>
                                    <select class="form-select schedule-type-select" id="scheduleType">
                                        <option selected>Select schedule type</option>
                                        <option value="Regular">Regular</option>
                                        <option value="CWW">CWW</option>
                                    </select>
                                </div>
                                <!-- Checkbox No Break  -->
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="noBreakCheckbox">
                                    <label class="form-check-label" style="font-weight: bold;" for="noBreakCheckbox">No Break</label>
                                </div>
                            </div>
                            <!-- Schedule Details -->
                            <div class="mb-3">
                                <div class="d-flex flex-column">
                                    <label id="noteLabel" class="form-label mb-1" style="font-weight: bold; color: blue; display: none;">
                                        Notes: <span id="noteText" style="font-weight: bold; color: blue;"></span>
                                    </label>
                                    <label class="form-label mb-0" style="font-weight: bold;">Schedule Details:</label>
                                </div>
                                <hr class="mt-2 mb-2">
                                <div class="row mb-2">
                                    <div style="font-weight: bold;" class="col">Day</div>
                                    <div style="font-weight: bold;" class="col">Sched From</div>
                                    <div style="font-weight: bold;" class="col">Sched To</div>
                                </div>
                                <!-- Monday -->
                                <div class="row mb-2">
                                    <div class="col">Monday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="mondayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="mondayTo"></select>
                                    </div>
                                </div>
                                <!-- Tuesday -->
                                <div class="row mb-2">
                                    <div class="col">Tuesday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="tuesdayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="tuesdayTo"></select>
                                    </div>
                                </div>
                                <!-- Wednesday -->
                                <div class="row mb-2">
                                    <div class="col">Wednesday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="wednesdayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="wednesdayTo"></select>
                                    </div>
                                </div>
                                <!-- Thursday -->
                                <div class="row mb-2">
                                    <div class="col">Thursday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="thursdayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="thursdayTo"></select>
                                    </div>
                                </div>
                                <!-- Friday -->
                                <div class="row mb-2">
                                    <div class="col">Friday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="fridayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="fridayTo"></select>
                                    </div>
                                </div>
                                <!-- Saturday -->
                                <div class="row mb-2">
                                    <div class="col">Saturday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="saturdayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="saturdayTo"></select>
                                    </div>
                                </div>
                                <!-- Sunday -->
                                <div class="row mb-2">
                                    <div class="col">Sunday</div>
                                    <div class="col">
                                        <select class="form-select time-select" id="sundayFrom"></select>
                                    </div>
                                    <div class="col">
                                        <select class="form-select time-select" id="sundayTo"></select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button id="btnClose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="btnSave" type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Assigned Employees -->
        <div class="modal fade" id="assignEmployee" tabindex="-1" aria-labelledby="assignEmployeeModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">

                <div class="modal-content">

                    <h5 class="modal-title pr-3 pl-3 pb-2 pt-2" style="font-weight: bold; color: #2E59D9;" id="assignEmployesModalLabel">
                        Assigning of Schedule Pattern to Employees
                    </h5>
                    <hr style="margin: 0;">

                    <div class="modal-header d-flex justify-content-between pb-0 pt-2">
                        <div>
                            <div class="flex-container"> <!-- New flex container -->
                                <div class="info-container">
                                    <h6 class="info-item" id="schedNamePattern"></h6>
                                    <h6 class="info-item" id="schedType"></h6>
                                    <div class="info-item" id="noBreak"></div>
                                </div>
                                <!-- Time Schedule Container -->
                                <div class="time-schedule-container">
                                    <h6 class="mb-0" style="font-weight: bold;">Time Schedule Pattern:</h6> <!-- Label added here -->
                                    <table class="table time-schedule">
                                        <thead>
                                            <tr>
                                                <th>Monday</th>
                                                <th>Tuesday</th>
                                                <th>Wednesday</th>
                                                <th>Thursday</th>
                                                <th>Friday</th>
                                                <th>Saturday</th>
                                                <th>Sunday</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="time-schedule-row">
                                                <!-- Times will be inserted here -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body pt-1">
                        <div class="container">
                            <!-- Date Picker Container (Right-Aligned) -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div></div>
                                <div class="d-flex flex-column align-items-end">
                                    <label for="startSelectedDate" class="mb-0" style="font-weight: bold;">
                                        Select Date:
                                        <span style="font-weight: normal;"><em>(Optional)</em></span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="startSelectedDate" class="form-control" placeholder="YYYY/MM/DD" readonly style="width: 140px;" />
                                        <button type="button" id="clearDate"
                                            style="border: none; background: none; cursor: pointer; color: #D2435B;
                        font-size: 1.8rem; padding: 0; margin-left: 5px;"
                                            title="Clear Date">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Unassigned Employees with Search and Checkboxes -->
                                <div class="col-md-5">
                                    <h6 style="font-weight: bold;">Unassigned Employees</h6>
                                    <input type="text" id="employeeSearch" class="form-control mb-2" placeholder="Search" onkeyup="filterEmployees()">
                                    <div id="UnassignedEmployees" class="draggable-container"></div>
                                    <button class="btn btn-primary mt-2" style="font-weight: bold;" onclick="assignedAll()">Assign All</button>
                                </div>
                                <!-- Move All Buttons -->
                                <div class="col-auto d-flex flex-column justify-content-center align-items-center">
                                    <button class="btn btn-light" onclick="moveToAssigned()">
                                        <i class="fa-solid fa-chevron-right" style="font-size: 24px; margin-right: 5px;"></i>
                                    </button>
                                </div>
                                <!-- Assigned Employees -->
                                <div class="col-md-5">
                                    <h6 style="font-weight: bold;">Assigned Employees</h6>
                                    <input type="text" id="assignedEmployeeSearch" class="form-control mb-2" placeholder="Search" onkeyup="filterAssignedEmployees()">
                                    <div id="assignedEmployees" class="dropzone" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
                                    <button class="btn btn-secondary mt-2" style="font-weight: bold;" onclick="unassignedAll()">Unassign All</button>
                                </div>
                                <input type="hidden" id="hiddenPatternId" value="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button id="btnCloseAssign" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="btnSaveAssign" type="button" class="btn btn-primary" style="font-weight: bold;">Save</button>
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
</body>

<script>
    // Displaying Data and Validation
    $(document).ready(function() {

        // Check if 'userid' is available
        if (typeof userid === 'undefined') {
            console.error('User ID is not defined!');
            return; // Stop further execution if userid is undefined
        }

        // Declare a global variable to store fetched schedules
        var fetchedSchedules = [];

        // Initialize the DataTable
        var table = $('#displaySchedulePattern').DataTable({
            ajax: {
                url: 'fetch-pattern-schedules.php', // Path to your PHP script
                dataSrc: function(json) {
                    // Store the fetched data in the global variable
                    fetchedSchedules = json;
                    // console.log('Fetched Data:', fetchedSchedules);

                    // Filter schedules by matching userid
                    var matchingSchedules = fetchedSchedules.filter(
                        schedule => schedule.userid === String(userid)
                    );

                    console.log('Matching Schedules:', matchingSchedules);
                    return matchingSchedules; // Return filtered data for DataTables
                }
            },
            columns: [{
                    data: 'sched_name_pattern',
                    title: 'SCHEDULE NAME', // Column title
                    className: 'text-center' // Center align text
                },
                {
                    data: 'sched_type',
                    title: 'SCHEDULE TYPE', // Column title
                    className: 'text-center' // Center align text
                },
                {
                    data: 'status',
                    title: 'STATUS', // Column title
                    className: 'text-center', // Center align text
                    render: function(data, type, row) {
                        if (data === 'Active') {
                            return `<span class="badge badge-success">Active</span>`;
                        } else {
                            return `<span class="badge badge-secondary">Inactive</span>`;
                        }
                    }
                },
                {
                    data: 'pattern_id',
                    title: 'ACTION',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return `
                        <a href="#" class="text-info view-btn" data-id="${data}" title="View">
                            <i class="fas fa-eye" style="margin-right: 10px;"></i>
                        </a>
                        <a href="#" class="text-warning assign-btn" data-id="${data}"
                            data-sched-name="${row.sched_name_pattern}"
                            data-sched-type="${row.sched_type}"
                            data-no-break="${row.no_break}"
                            data-time-schedule='${row.time_schedule}' title="Assign">
                                <i class="fas fa-user-plus" style="margin-right: 10px;"></i>
                        </a>
                        <a href="#" class="text-danger delete-btn" data-id="${data}" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        `;
                    }
                }
            ]
        });

        // Handle Assigned to Unassigned Employees
        $(document).ready(function() {

            var cutoffStart = "<?php echo $cutoffrange_dateStart; ?>";
            var cutoffEnd = "<?php echo $cutoffrange_dateEnd; ?>";

            // Initialize the datepicker with the specified date range
            $('#startSelectedDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startDate: cutoffStart,
                endDate: cutoffEnd,
            }).on('changeDate', function(e) {
                $(this).val(e.format('yyyy-mm-dd')); // Set the selected date in the input
            });

            // Show datepicker on input click
            $('#startSelectedDate').on('click', function() {
                $(this).datepicker('show');
            });

            // Function to highlight the date range
            function highlightRange(start, end) {
                const startDate = new Date(start);
                const endDate = new Date(end);

                $('.datepicker-days td').each(function() {
                    const dateString = $(this).attr('data-date');
                    if (dateString) {
                        const cellDate = new Date(dateString);
                        if (cellDate >= startDate && cellDate <= endDate) {
                            $(this).addClass('in-range');
                        } else {
                            $(this).removeClass('in-range');
                        }
                    }
                });
            }

            // Call the highlight function initially
            highlightRange(cutoffStart, cutoffEnd);

            // Clear date button functionality
            $('#clearDate').on('click', function() {
                $('#startSelectedDate').val(''); // Clear the date input
                $('#startSelectedDate').datepicker('update', null); // Reset the datepicker
            });

            $(document).on('click', '.assign-btn', function(e) {
                e.preventDefault();

                var patternId = $(this).data('id');
                var schedNamePattern = $(this).data('sched-name');
                var schedType = $(this).data('sched-type');
                var noBreak = $(this).data('no-break'); // 1 or 0
                var timeSchedule = $(this).data('time-schedule'); // Get time_schedule

                console.log('Pattern ID:', patternId);
                console.log('Schedule Name Pattern:', schedNamePattern);
                console.log('Schedule Type:', schedType);
                console.log('No Break:', noBreak);
                console.log('Raw Time Schedule:', timeSchedule); // Log the raw string

                $('#hiddenPatternId').val(patternId);

                console.log("Actual Pattern ID:", patternId);

                $.ajax({
                    url: 'fetch-already-assigned-empno.php',
                    method: 'POST',
                    data: {
                        pattern_id: patternId
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log("Already Assigned Employees:", response);

                        // Convert timeSchedule from JSON string to object
                        var scheduleObject;
                        try {
                            // Only parse if it's a string
                            scheduleObject = typeof timeSchedule === "string" ? JSON.parse(timeSchedule) : timeSchedule;
                        } catch (error) {
                            console.error("Failed to parse time_schedule:", error);
                            return; // Stop further execution if parsing fails
                        }

                        // Format the time schedule for display
                        var scheduleRow = document.getElementById('time-schedule-row');
                        var scheduleData = [
                            scheduleObject['monday'],
                            scheduleObject['tuesday'],
                            scheduleObject['wednesday'],
                            scheduleObject['thursday'],
                            scheduleObject['friday'],
                            scheduleObject['saturday'],
                            scheduleObject['sunday']
                        ];

                        // Prepare a row with the times
                        var rowHtml = '';
                        scheduleData.forEach((daySchedule, index) => {
                            if (daySchedule) {
                                var fromTime = daySchedule.from;
                                var toTime = daySchedule.to;

                                // Check for "RD" or "NWD" and display accordingly
                                if (fromTime === "RD" || fromTime === "NWD") {
                                    rowHtml += `<td>${fromTime}</td>`; // Only display "RD" or "NWD"
                                } else {
                                    rowHtml += `<td>${fromTime} - ${toTime}</td>`;
                                }
                            } else {
                                rowHtml += `<td>--</td>`; // Empty cell for days with no schedule
                            }
                        });

                        // Insert the row into the table
                        scheduleRow.innerHTML = rowHtml;

                        // Set modal content
                        $('#schedNamePattern').html(`<strong>Schedule Pattern Name:</strong> ${schedNamePattern}`);
                        $('#schedType').html(`<strong>Schedule Type:</strong> ${schedType}`);
                        $('#noBreak').html(`<strong>No Break Type:</strong> ${noBreak === 1 ? 'Yes' : 'No'}`);
                        // Show the modal

                        $('#assignEmployee').modal('show');
                        // Populate Assigned Employees container
                        var assignedContainer = $('#assignedEmployees');
                        assignedContainer.empty();

                        const assignedEmpnos = new Set(response.map(emp => emp.empno));
                        response.forEach(function(employee) {
                            assignedContainer.append(`
                    <div data-empno="${employee.empno}" class="assigned-employee">
                        ${employee.name}
                        <button class="btn btn-danger btn-sm remove-btn"
                            onclick="removeEmployee('${employee.empno}', this)">X</button>
                    </div>
                `);

                            console.log(`Employee Added to Assigned: Empno: ${employee.empno}, Name: ${employee.name}`);
                        });

                        // Populate Unassigned Employees container
                        var unassignedContainer = $('#UnassignedEmployees');
                        unassignedContainer.empty();

                        employees.forEach(function(employee) {
                            if (!assignedEmpnos.has(employee.empno) && employee.pattern_id === "0" &&
                                ((employee.is_compressed == 0 && schedType === "Regular") ||
                                    (employee.is_compressed == 1 && schedType === "CWW"))
                            ) {
                                unassignedContainer.append(`
                        <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${employee.empno}">
                            <input type="checkbox" class="mr-2 employee-checkbox" value="${employee.empno}" />
                            ${employee.name}
                        </div>
                    `);
                                console.log("Added Employee to Unassigned:", employee);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching assigned employees:", error);
                    }
                });
            });

            // Drag-and-drop functionality
            let draggedElement;

            function allowDrop(event) {
                event.preventDefault();
            }

            function drag(event) {
                draggedElement = event.target;
                event.dataTransfer.setData("text", event.target.textContent);
            }

            function drop(event) {
                event.preventDefault();
                var data = event.dataTransfer.getData("text");
                var empno = draggedElement.getAttribute('data-empno');
                addToAssigned(empno, data);
                draggedElement.remove(); // Remove the dragged element from the original container
            }

            function addToAssigned(empno, employeeName) {
                const assignedContainer = document.getElementById("assignedEmployees");
                if (!assignedContainer.querySelector(`[data-empno="${empno}"]`)) {
                    assignedContainer.innerHTML += `
                    <div data-empno="${empno}" class="assigned-employee">
                        ${employeeName}
                        <button class="btn btn-danger btn-sm remove-btn" onclick="removeEmployee('${empno}', this)">X</button>
                    </div>`;
                    console.log(`Employee Added to Assigned: Empno: ${empno}, Employee Name: ${employeeName}`);
                }
            }

            function assignedAll() {
                const sourceDiv = document.getElementById("UnassignedEmployees");
                const targetDiv = document.getElementById("assignedEmployees");

                // Get all employees from UnassignedEmployees
                const employees = sourceDiv.querySelectorAll('.draggable');

                employees.forEach(employee => {
                    const empno = employee.getAttribute('data-empno');
                    const employeeName = employee.textContent.trim();

                    // Check if the employee is already in the assigned list to avoid duplicates
                    if (!targetDiv.querySelector(`[data-empno="${empno}"]`)) {
                        addToAssigned(empno, employeeName);

                        // Log empno and employeeName to the console
                        console.log(`Employee Added: Empno: ${empno}, Employee Name: ${employeeName}`);
                    }

                    // Remove the employee from unassigned
                    employee.remove();
                });

                console.log("All employees have been transferred from Unassigned to Assigned.");
            }

            // Move selected employees with checkboxes
            function moveToAssigned() {
                const unassignedContainer = document.getElementById("UnassignedEmployees");
                const assignedContainer = document.getElementById("assignedEmployees");
                const selectedCheckboxes = unassignedContainer.querySelectorAll('.employee-checkbox:checked');

                selectedCheckboxes.forEach(checkbox => {
                    const empno = checkbox.value;
                    const employeeName = checkbox.parentElement.textContent.trim();
                    addToAssigned(empno, employeeName);
                    // Log empno and employeeName to the console
                    console.log(`Employee Moved to Assigned: Empno: ${empno}, Employee Name: ${employeeName}`);
                    checkbox.parentElement.remove(); // Remove from unassigned
                });
            }

            function unassignedAll() {
                const sourceDiv = document.getElementById("assignedEmployees");
                const targetDiv = document.getElementById("UnassignedEmployees");
                const patternId = $('#hiddenPatternId').val(); // Get the pattern ID for the AJAX call

                // Get all assigned employee divs
                const assignedEmployees = sourceDiv.querySelectorAll('.assigned-employee');

                // Create an array to store empnos for the AJAX call
                const empnosToUpdate = [];

                // Show SweetAlert2 confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will remove the employee from the schedule immediately, even without clicking the Save button.",
                    icon: 'warning',
                    showCloseButton: true, // Show the "X" button
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Submit',
                    didOpen: () => {
                        // Prevent datepicker interference by hiding or blurring
                        $('#startSelectedDate').datepicker('hide');
                        document.activeElement.blur();
                    },
                    willClose: () => {
                        $('#startSelectedDate').datepicker('hide');
                        document.activeElement.blur();
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Loop through each assigned employee to prepare the update
                        assignedEmployees.forEach(empDiv => {
                            const employeeName = empDiv.childNodes[0].textContent.trim(); // Get the name directly
                            const empno = empDiv.getAttribute('data-empno'); // Retrieve the empno

                            // Move the employee back to UnassignedEmployees with a checkbox
                            targetDiv.innerHTML += `
                    <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${empno}">
                        <input type="checkbox" class="mr-2 employee-checkbox" value="${empno}" />
                        ${employeeName}
                    </div>`;

                            empnosToUpdate.push(empno); // Store empno for AJAX call
                            empDiv.remove(); // Remove from assigned
                        });

                        console.log("All employees have been unselected from Assigned to Unassigned.");

                        // AJAX call to update the pattern_id for all employees being unassigned
                        $.ajax({
                            url: 'update-unassigned-all-employees.php', // Your server-side script to handle the update
                            type: 'POST',
                            data: {
                                pattern_id: patternId, // Send pattern_id to PHP
                                unassigned_employees: JSON.stringify(empnosToUpdate) // Send the array of empnos
                            },
                            success: function(response) {
                                const res = JSON.parse(response);
                                if (res.status === 'success') {
                                    console.log(`Successfully updated pattern_id for all unassigned employees.`);
                                } else {
                                    Swal.fire('Error!', res.message, 'error');
                                    console.error(`Error updating pattern_id: ${res.message}`);
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'An error occurred while processing your request.', 'error');
                                console.error('AJAX error:', error);
                            }
                        });
                    }
                });
            }


            function filterEmployees() {
                const input = document.getElementById("employeeSearch");
                const filter = input.value.toLowerCase();
                const employees = document.querySelectorAll("#UnassignedEmployees .draggable");

                employees.forEach(function(employee) {
                    const employeeName = employee.textContent.toLowerCase();

                    // Log employeeName to the console
                    // console.log(`Filtering Employee: ${employeeName}`);
                    employee.style.display = employeeName.includes(filter) ? "" : "none";
                });
            }

            function filterAssignedEmployees() {
                const input = document.getElementById("assignedEmployeeSearch");
                const filter = input.value.toLowerCase();
                const employees = document.querySelectorAll("#assignedEmployees .assigned-employee");

                employees.forEach(function(employee) {
                    const employeeName = employee.textContent.toLowerCase();
                    employee.style.display = employeeName.includes(filter) ? "" : "none";
                });
            }

            window.removeEmployee = function(empno, button) {
                const assignedContainer = document.getElementById("assignedEmployees");
                const unassignedContainer = document.getElementById("UnassignedEmployees");
                const employeeName = button.parentElement.childNodes[0].textContent;
                const patternId = $('#hiddenPatternId').val();

                // Log empno, employeeName, and patternId to the console
                console.log(`Removing Employee - Empno: ${empno}, Name: ${employeeName}, Pattern ID: ${patternId}`);

                // Show SweetAlert2 confirmation dialog
                Swal.fire({
                    title: `Are you sure you want to remove this employee <strong>${empno}</strong>?`,
                    text: "This will remove the employee from the schedule immediately, even without clicking the Save button.",
                    icon: 'warning',
                    showCloseButton: true, // Show the "X" button
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Submit',
                    didOpen: () => {
                        // Prevent datepicker interference by hiding or blurring
                        $('#startSelectedDate').datepicker('hide');
                        document.activeElement.blur();
                    },
                    willClose: () => {
                        $('#startSelectedDate').datepicker('hide');
                        document.activeElement.blur();
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Move the employee back to UnassignedEmployees
                        unassignedContainer.innerHTML += `
                        <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${empno}">
                            <input type="checkbox" class="mr-2 employee-checkbox" value="${empno}" />
                            ${employeeName}
                        </div>`;

                        // Remove from assigned
                        button.parentElement.remove();

                        // AJAX call to update the database
                        $.ajax({
                            url: 'update-removed-empno-pattern.php',
                            type: 'POST',
                            data: {
                                pattern_id: patternId,
                                empno: empno
                            },
                            success: function(response) {
                                const res = JSON.parse(response);
                                if (res.status === 'success') {
                                    console.log(`Successfully removed Empno: ${empno} from Pattern ID: ${patternId}`);
                                } else {
                                    Swal.fire('Error!', res.message, 'error');
                                    console.error(`Error updating pattern_id: ${res.message}`);
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'An error occurred while processing your request.', 'error');
                                console.error('AJAX error:', error);
                            }
                        });
                    } else {
                        console.log(`Cancelled removal of Empno: ${empno}`);
                    }
                });
            };

            // Ensure functions are accessible in global scope
            window.allowDrop = allowDrop;
            window.drag = drag;
            window.drop = drop;
            window.assignedAll = assignedAll;
            window.unassignedAll = unassignedAll;
            window.moveToAssigned = moveToAssigned;
            window.filterEmployees = filterEmployees;
            window.filterAssignedEmployees = filterAssignedEmployees;

            $(document).ready(function() {
                // Handle modal close/reset functionality
                $('#btnCloseAssign').on('click', function() {
                    // Clear search input fields
                    $('#employeeSearch').val('');
                    $('#assignedEmployeeSearch').val('');

                    // Move all assigned employees back to unassigned
                    // unassignedAll(); // This transfers all assigned employees back to Unassigned

                    // Clear both Unassigned and Assigned employee lists
                    $('#UnassignedEmployees').empty();
                    $('#assignedEmployees').empty();

                    // Clear schedule details (schedNamePattern, schedType, NoBreak checkbox)
                    $('#schedNamePattern').html('');
                    $('#schedType').html('');
                    $('#noBreak').prop('checked', false);
                });
            });

            $(document).ready(function() {
                $('#btnSaveAssign').on('click', function() {
                    const assignedEmployees = document.querySelectorAll('#assignedEmployees .assigned-employee');
                    const assignedData = [];

                    // Collect assigned employee data
                    assignedEmployees.forEach(emp => {
                        const empno = emp.getAttribute('data-empno');
                        let name = emp.textContent.trim().replace(/\s*X\s*$/, '');

                        assignedData.push({
                            empno,
                            name
                        });
                    });

                    const patternId = $('#hiddenPatternId').val();
                    const startSelectedDate = $('#startSelectedDate').val(); // Get selected date
                    const cutoffEndDate = cutoffEnd; // Use the PHP value embedded earlier

                    // Determine the appropriate confirmation message
                    let message = startSelectedDate ?
                        `You selected a date. Do you want to update the schedule starting ${startSelectedDate}?` :
                        'Do you want to continue to save?';

                    // Show the confirmation alert
                    Swal.fire({
                        title: 'Confirmation',
                        text: message,
                        icon: 'question',
                        showCancelButton: false, // Disable the "No" button
                        showCloseButton: true, // Enable the "X" button in the top corner
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        customClass: {
                            confirmButton: 'swal-button-green'
                        },
                        didOpen: () => {
                            $('#startSelectedDate').datepicker('hide'); // Hide datepicker
                            document.activeElement.blur(); // Blur focus to avoid issues
                        },
                        willClose: () => {
                            $('#startSelectedDate').datepicker('hide'); // Hide datepicker again
                            document.activeElement.blur(); // Blur any focused element
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Show loading modal while processing the AJAX request
                            Swal.fire({
                                title: 'Processing...',
                                html: `
                                    <div class="custom-spinner"></div>
                                    <p>Updating each employee's schedule. Please do not refresh or close the window to avoid interrupting the process.</p>
                                `,
                                footer: '<b>This may take a few moments.</b>',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false, // Hide the OK button
                                customClass: {
                                    htmlContainer: 'swal-html-content'
                                }
                            });

                            // If confirmed, proceed with the AJAX request
                            $.ajax({
                                url: 'update-pattern-schedules.php',
                                type: 'POST',
                                data: {
                                    pattern_id: patternId,
                                    assigned_employees: JSON.stringify(assignedData),
                                    start_date: startSelectedDate,
                                    end_date: cutoffEndDate
                                },
                                success: function(response) {
                                    const res = JSON.parse(response);
                                    if (res.status === 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Saved!',
                                            text: 'Employees have been successfully assigned.',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            didOpen: () => {
                                                $('#startSelectedDate').datepicker('hide');
                                                document.activeElement.blur();
                                            }
                                        }).then(() => location.reload());
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: res.message,
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'An error occurred while saving.',
                                    });
                                    console.error('AJAX error:', error);
                                }
                            });
                        }
                    });
                });
            });

        });

        // Handle view function button
        $(document).on('click', '.view-btn', function(e) {
            e.preventDefault();

            // Get the pattern_id from the clicked button
            var patternId = $(this).data('id');

            // Find the selected schedule pattern in the fetchedSchedules array
            var selectedPattern = fetchedSchedules.find(pattern => pattern.pattern_id == patternId);

            if (selectedPattern) {
                // Set modal title with color
                $('#newSchedulePatternModalLabel').text('Viewing Schedule Pattern').css('color', '#2E59D9');

                // Populate schedule name and type
                $('#scheduleName').val(selectedPattern.sched_name_pattern).prop('disabled', true);
                $('#scheduleType').val(selectedPattern.sched_type).prop('disabled', true); // Set schedule type and handle case sensitivity

                // Handle 'No Break' checkbox if applicable
                $('#noBreakCheckbox').prop('checked', selectedPattern.no_break === "1").prop('disabled', true);

                // Populate schedule details (assuming time_schedule is JSON formatted)
                var timeSchedule = JSON.parse(selectedPattern.time_schedule);

                // Populate each day's time
                $('#mondayFrom').val(timeSchedule.monday.from).prop('disabled', true);
                $('#mondayTo').val(timeSchedule.monday.to).prop('disabled', true);
                $('#tuesdayFrom').val(timeSchedule.tuesday.from).prop('disabled', true);
                $('#tuesdayTo').val(timeSchedule.tuesday.to).prop('disabled', true);
                $('#wednesdayFrom').val(timeSchedule.wednesday.from).prop('disabled', true);
                $('#wednesdayTo').val(timeSchedule.wednesday.to).prop('disabled', true);
                $('#thursdayFrom').val(timeSchedule.thursday.from).prop('disabled', true);
                $('#thursdayTo').val(timeSchedule.thursday.to).prop('disabled', true);
                $('#fridayFrom').val(timeSchedule.friday.from).prop('disabled', true);
                $('#fridayTo').val(timeSchedule.friday.to).prop('disabled', true);
                $('#saturdayFrom').val(timeSchedule.saturday.from).prop('disabled', true);
                $('#saturdayTo').val(timeSchedule.saturday.to).prop('disabled', true);
                $('#sundayFrom').val(timeSchedule.sunday.from).prop('disabled', true);
                $('#sundayTo').val(timeSchedule.sunday.to).prop('disabled', true);

                // Hide the Save button
                $('#btnSave').hide();

                // Show the modal
                $('#newSchedulePattern').modal('show');
            } else {
                console.error('Schedule pattern not found!');
            }
        });

        // When the modal is hidden, reset all fields
        $('#newSchedulePattern').on('hidden.bs.modal', function() {
            // Reset all input fields and remove disabled attribute
            $('#scheduleName').val('').prop('disabled', false);
            $('#scheduleType').val('Select schedule type').prop('disabled', false);
            $('#noBreakCheckbox').prop('checked', false).prop('disabled', false);

            // Reset all time inputs and enable them
            $('#mondayFrom, #mondayTo').val('').prop('disabled', false);
            $('#tuesdayFrom, #tuesdayTo').val('').prop('disabled', false);
            $('#wednesdayFrom, #wednesdayTo').val('').prop('disabled', false);
            $('#thursdayFrom, #thursdayTo').val('').prop('disabled', false);
            $('#fridayFrom, #fridayTo').val('').prop('disabled', false);
            $('#saturdayFrom, #saturdayTo').val('').prop('disabled', false);
            $('#sundayFrom, #sundayTo').val('').prop('disabled', false);

            // Show the Save button
            $('#btnSave').show();

            // Reset modal title
            $('#newSchedulePatternModalLabel').text('Create New Schedule Pattern');
        });

        // Handle delete button click
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var patternId = $(this).data('id');

            // Use SweetAlert2 for confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this schedule pattern!',
                icon: 'warning',
                showCloseButton: true,
                confirmButtonText: 'Submit',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send POST request to check if deletion is allowed
                    $.ajax({
                        type: 'POST',
                        url: 'fetch-pattern-schedules.php',
                        data: {
                            check_pattern_id: patternId
                        }, // Check if deletion is allowed
                        success: function(response) {
                            var res = JSON.parse(response); // Parse the response

                            if (res.canDelete) {
                                // Proceed with the deletion if allowed
                                $.ajax({
                                    type: 'POST',
                                    url: 'fetch-pattern-schedules.php',
                                    data: {
                                        delete_pattern_id: patternId
                                    },
                                    success: function() {
                                        $('#displaySchedulePattern').DataTable().ajax.reload();
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: 'The schedule pattern has been deleted.',
                                            icon: 'success',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });
                                    },
                                    error: function() {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'There was an error deleted the schedule pattern.',
                                            icon: 'error',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });
                                    }
                                });
                            } else {
                                // Show warning if deletion is not allowed
                                Swal.fire({
                                    title: 'Cannot Delete!',
                                    text: 'This pattern is already assigned to employees and cannot be deleted.',
                                    icon: 'warning',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        }
                    });
                }
            });
        });

    });

    // Inserting Data and Validation
    document.addEventListener("DOMContentLoaded", function() {

        // Function to reset form fields when "Close" button is clicked
        function resetSelections() {
            // Reset all time-select dropdowns to the default option (empty value)
            document.querySelectorAll('.time-select').forEach(select => {
                select.value = "";
            });

            // Reset the schedule type to default
            document.getElementById('scheduleType').value = "Select schedule type";

            // Uncheck the no break checkbox
            document.getElementById('noBreakCheckbox').checked = false;
        }

        // Add event listener to the Close button
        document.getElementById('btnClose').addEventListener('click', function() {
            resetSelections(); // Reset selections when Close button is clicked
        });

        document.getElementById('scheduleName').addEventListener('input', function(e) {
            // Regex pattern: allow letters, numbers, dashes (-), underscores (_), and spaces
            const validCharacters = /^[a-zA-Z0-9-_ ]*$/;

            // If the entered value doesn't match the allowed characters, remove the invalid characters
            if (!validCharacters.test(e.target.value)) {
                e.target.value = e.target.value.replace(/[^a-zA-Z0-9-_ ]/g, '');
            }
        });

        // Function to generate time options in 30-minute intervals (military time format)
        function generateTimeOptions(showNWD = true) {
            const timeOptions = [];
            let hour, minute;

            // Create the standard time options in military format
            for (let i = 0; i < 48; i++) {
                hour = Math.floor(i / 2);
                minute = (i % 2 === 0) ? "00" : "30";
                let formattedTime = ("0" + hour).slice(-2) + ":" + minute; // Format as HH:mm
                timeOptions.push(`<option value="${formattedTime}">${formattedTime}</option>`);
            }

            // Add RD (Rest Day) option
            timeOptions.push('<option value="RD">RD</option>');

            // Conditionally add NWD (No Working Day) option based on showNWD parameter
            if (showNWD) {
                timeOptions.push('<option value="NWD">NWD</option>');
            }

            return timeOptions.join('');
        }

        // Function to populate time dropdowns, with an option to show or hide NWD
        function populateTimeDropdowns(showNWD = true) {
            const timeSelects = document.querySelectorAll('.time-select');
            const timeOptions = generateTimeOptions(showNWD);

            timeSelects.forEach(select => {
                select.innerHTML = '<option value="" selected>Select time</option>' + timeOptions;
            });
        }

        document.getElementById('scheduleType').addEventListener('change', function(e) {
            const scheduleType = e.target.value;
            const noteLabel = document.getElementById('noteLabel');
            const noteText = document.getElementById('noteText');

            if (scheduleType) {
                // Show the label and set the appropriate text
                noteLabel.style.display = 'block';

                if (scheduleType === "Regular") {
                    noteText.textContent = "You should declare the Rest Day (RD).";
                    populateTimeDropdowns(false); // Hide NWD
                } else if (scheduleType === "CWW") {
                    noteText.textContent = "You should declare the No Work Day (NWD) and Rest Day (RD).";
                    populateTimeDropdowns(true); // Show NWD
                }
            } else {
                // Hide the label if no type is selected
                noteLabel.style.display = 'none';
            }
        });

        // Check if schedule type is selected before allowing time selection
        function checkScheduleType() {
            const scheduleType = document.getElementById('scheduleType').value;
            if (scheduleType === "Select schedule type") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please select a schedule type',
                    text: 'You must select a schedule type before choosing a time and check the checkbox if employee is no break.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }

        // Function to add or subtract hours from a given time (HH:mm format)
        function addHoursToTime(time, hoursToAdd) {
            const [hours, minutes] = time.split(':').map(Number);
            let newHours = hours + hoursToAdd;

            // Adjust for 24-hour wrap around
            if (newHours >= 24) {
                newHours = newHours % 24;
            }

            // Ensure new time is formatted as HH:mm
            const formattedHours = ("0" + newHours).slice(-2);
            const formattedMinutes = ("0" + minutes).slice(-2);

            return `${formattedHours}:${formattedMinutes}`;
        }

        // Automatically adjust the "To" time based on the selected "From" time, schedule type, and no-break status
        function adjustToTime(dayFromSelect, dayToSelect) {
            const fromTime = dayFromSelect.value;
            const scheduleType = document.getElementById('scheduleType').value;
            const noBreak = document.getElementById('noBreakCheckbox').checked;

            let hoursToAdd = 0;

            if (fromTime === "RD") {
                // If the "From" time is RD (Rest Day), set the "To" time to RD as well
                dayToSelect.value = "RD";
                return;
            }

            if (fromTime === "NWD") {
                // If the "From" time is NWD, set the "To" time to NWD as well
                dayToSelect.value = "NWD";
                return;
            }

            if (scheduleType === "CWW") {
                hoursToAdd = noBreak ? 10 : 11;
            }

            if (scheduleType === "Regular") {
                hoursToAdd = noBreak ? 8 : 9;
            }

            if (fromTime !== "") {
                // Add or subtract the hours to/from the "From" time to calculate the "To" time
                const toTime = addHoursToTime(fromTime, hoursToAdd);
                dayToSelect.value = toTime;
            }
        }

        // Add event listeners to all "From" selects to automatically adjust the corresponding "To" time
        document.querySelectorAll('.time-select[id$="From"]').forEach(fromSelect => {
            const day = fromSelect.id.replace('From', ''); // Extract the day from the ID
            const toSelect = document.getElementById(`${day}To`);

            fromSelect.addEventListener('change', function() {
                if (checkScheduleType()) {
                    adjustToTime(fromSelect, toSelect);
                } else {
                    fromSelect.value = ""; // Reset the "From" value if schedule type isn't selected
                }
            });
        });

        // Event listener for noBreakCheckbox changes
        document.getElementById('noBreakCheckbox').addEventListener('change', function() {
            const scheduleType = document.getElementById('scheduleType').value;

            // Recalculate the "To" time when the checkbox is checked/unchecked
            document.querySelectorAll('.time-select[id$="From"]').forEach(fromSelect => {
                const day = fromSelect.id.replace('From', ''); // Extract the day from the ID
                const toSelect = document.getElementById(`${day}To`);

                if (fromSelect.value !== "" && (scheduleType === "Regular" || scheduleType === "CWW")) {
                    adjustToTime(fromSelect, toSelect);
                }
            });
        });

        // Call the function to populate the time dropdowns when the modal is shown
        populateTimeDropdowns();

        document.getElementById('btnSave').addEventListener('click', function() {
            const scheduleName = document.getElementById('scheduleName').value.trim();
            const scheduleType = document.getElementById('scheduleType').value;
            const noBreakChecked = document.getElementById('noBreakCheckbox').checked ? 1 : 0;
            const userid = "<?php echo htmlspecialchars($userid); ?>"; // Adjust according to your server-side variables

            // Get all the time values from the form
            const timeSchedule = {
                monday: {
                    from: document.getElementById('mondayFrom').value,
                    to: document.getElementById('mondayTo').value
                },
                tuesday: {
                    from: document.getElementById('tuesdayFrom').value,
                    to: document.getElementById('tuesdayTo').value
                },
                wednesday: {
                    from: document.getElementById('wednesdayFrom').value,
                    to: document.getElementById('wednesdayTo').value
                },
                thursday: {
                    from: document.getElementById('thursdayFrom').value,
                    to: document.getElementById('thursdayTo').value
                },
                friday: {
                    from: document.getElementById('fridayFrom').value,
                    to: document.getElementById('fridayTo').value
                },
                saturday: {
                    from: document.getElementById('saturdayFrom').value,
                    to: document.getElementById('saturdayTo').value
                },
                sunday: {
                    from: document.getElementById('sundayFrom').value,
                    to: document.getElementById('sundayTo').value
                }
            };

            // Schedule Details is Missing for time fields
            function areAllDaysFilled(schedule) {
                for (const day in schedule) {
                    if (!schedule[day].from || !schedule[day].to) {
                        return false; // If any day is missing a time value
                    }
                }
                return true;
            }

            // Validate input fields
            if (!scheduleName || scheduleType === "Select schedule type") {
                Swal.fire({
                    icon: 'error',
                    title: 'Schedule Details is Missing',
                    text: 'Please enter a schedule name and select a schedule type.'
                });
                return;
            }

            if (!areAllDaysFilled(timeSchedule)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Schedule Details is Missing',
                    text: 'Please fill in all time fields for Monday to Sunday.'
                });
                return;
            }

            // Send data to PHP using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'insert-pattern-schedules.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Success message or redirection logic
                    Swal.fire({
                        icon: 'success',
                        title: 'Schedule Pattern Saved',
                        text: 'Your schedule pattern has been saved successfully!',
                        timer: 1500, // Auto-close
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            confirmButton: 'swal-button-green'
                        }
                    }).then(() => {
                        // Reload the page after the timer ends
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was a problem saving the schedule.'
                    });
                }
            };

            // Prepare the data to send
            const data = `userid=${encodeURIComponent(userid)}&scheduleName=${encodeURIComponent(scheduleName)}&scheduleType=${encodeURIComponent(scheduleType)}&noBreak=${noBreakChecked}&timeSchedule=${encodeURIComponent(JSON.stringify(timeSchedule))}`;

            xhr.send(data);

        });

    });
</script>

</html>