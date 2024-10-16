<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
unset($_SESSION['viewPrintSched']);
unset($_SESSION['emp_sched']);
if (empty($_SESSION['user'])) {
    header('location:login.php');
}

$sqlUserInfo = "SELECT userid, empno, userlevel, name, mothercafe FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$queryUserInfo = $HRconnect->query($sqlUserInfo);
$rowUserInfo = $queryUserInfo->fetch_array();
$userlevel = $rowUserInfo['userlevel'];
$empno = $rowUserInfo['empno'];
$name = $rowUserInfo['name'];
$userid = $rowUserInfo['userid'];

$sqlUser = "SELECT username, areatype FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$queryUser = $ORconnect->query($sqlUser);
$rowUser = $queryUser->fetch_array();
$areatype = $rowUser['areatype'];

// Fetch additional user info by userid
$sqlUserInfoById = "SELECT empno, name, status, is_compressed FROM user_info WHERE userid = '$userid' AND status IN ('active', '')";
$queryUserInfoById = $HRconnect->query($sqlUserInfoById);

// Collect user information from query results
$employees = [];
if ($queryUserInfoById) {
    while ($rowUserWithName = $queryUserInfoById->fetch_array(MYSQLI_ASSOC)) {
        $employees[] = $rowUserWithName;
    }
}

// Encode the employee array as JSON for JavaScript use
echo "<script>var employees = " . json_encode($employees) . ";</script>";

// Output the employees data
// echo "<pre>";
// print_r($employees); // This will show the details of employee data
// echo "</pre>";

// Output the user info data
// echo "<pre>";
// print_r($rowUserInfo); // This will show the details of user info
// echo "</pre>";

// echo "<pre>";
// print_r($rowUser); // This will show the details of the user
// echo "</pre>";

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
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3 mr-4">
                                    <label for="scheduleType" class="form-label" style="font-weight: bold;">Schedule Type:</label>
                                    <select class="form-select schedule-type-select" id="scheduleType"> <!-- New class applied -->
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
                                <label class="form-label mb-0" style="font-weight: bold;">Schedule Details:</label>
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
                    <div class="modal-header d-flex justify-content-between pb-0">
                        <div>
                            <h5 class="modal-title" style="font-weight: bold; color: #2E59D9;" id="assignEmployesModalLabel">Assigning of Schedule Pattern to Employee</h5>
                            <h6 id="schedNamePattern" class="m-0"></h6>
                            <div class="d-flex align-items-center">
                                <h6 id="schedType" class="m-0 me-2 mr-2"></h6>
                                <div class="form-check m-0">
                                    <input type="checkbox" class="form-check-input" id="noBreak" disabled>
                                    <label class="form-check-label" for="noBreak"><strong>No Break</strong></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="container">
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

<script>
    // Displaying Data and Validation
    $(document).ready(function() {

        // Declare a global variable to store fetched schedules
        var fetchedSchedules = [];

        // Initialize the DataTable
        var table = $('#displaySchedulePattern').DataTable({
            ajax: {
                url: 'fetch-pattern-schedules.php', // Path to your PHP script
                dataSrc: function(json) {
                    // Log the fetched data to the console
                    console.log('Fetched Data:', json);

                    // Store the fetched data in the global variable
                    fetchedSchedules = json;

                    return json; // Return the data for DataTables
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
                        <a href="#" class="text-warning assign-btn" data-id="${data}" data-sched-name="${row.sched_name_pattern}" data-sched-type="${row.sched_type}" data-no-break="${row.no_break}" title="Assign">
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

            // Handle Assign button click to show the secondary modal
            $(document).on('click', '.assign-btn', function(e) {
                e.preventDefault();

                // Get the pattern_id, sched_name_pattern, and sched_type from the clicked button
                var patternId = $(this).data('id');
                var schedNamePattern = $(this).data('sched-name');
                var schedType = $(this).data('sched-type');
                var noBreak = $(this).data('no-break'); // Get no_break value
                $('#hiddenPatternId').val(patternId); // Store it in a hidden input
                console.log(patternId);

                // Display the retrieved sched_name_pattern and sched_type in the modal
                $('#schedNamePattern').html(`<strong>Schedule Pattern Name:</strong> ${schedNamePattern}`);
                $('#schedType').html(`<strong>Schedule Type:</strong> ${schedType}`);
                $('#noBreak').prop('checked', noBreak === 1); // Check the box if no_break is 1

                // Show the Assign Employee modal
                $('#assignEmployee').modal('show');

                // Populate the Unassigned Employees with actual data
                var unassignedContainer = $('#UnassignedEmployees');
                unassignedContainer.empty(); // Clear existing content

                // Populate the employee list with checkboxes
                employees.forEach(function(employee) {
                    // Check the conditions for displaying the employee
                    if ((employee.is_compressed == 0 && schedType === "Regular") ||
                        (employee.is_compressed == 1 && schedType === "CWW")) {

                        var employeeRow = `
                    <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${employee.empno}">
                        <input type="checkbox" class="mr-2 employee-checkbox" value="${employee.empno}" />
                        ${employee.name}
                    </div>`;

                        unassignedContainer.append(employeeRow);

                        // Log the employee data being added
                        console.log("Added Employee:", employee);
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
                assignedContainer.innerHTML += `
                <div data-empno="${empno}" class="assigned-employee">
                    ${employeeName}
                    <button class="btn btn-danger btn-sm remove-btn" onclick="removeEmployee('${empno}', this)">X</button>
                </div>`;

                // Log the empno and employeeName to the console in one line
                console.log(`Employee Added to Assigned: Empno: ${empno}, Employee Name: ${employeeName}`);
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

                // Get all assigned employee divs
                const assignedEmployees = sourceDiv.querySelectorAll('.assigned-employee');

                // Loop through each assigned employee
                assignedEmployees.forEach(empDiv => {
                    const employeeName = empDiv.childNodes[0].textContent.trim(); // Get the name directly
                    const empno = empDiv.getAttribute('data-empno'); // Retrieve the empno

                    // Move the employee back to UnassignedEmployees with a checkbox
                    targetDiv.innerHTML += `
            <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${empno}">
                <input type="checkbox" class="mr-2 employee-checkbox" value="${empno}" />
                ${employeeName}
            </div>`;

                    empDiv.remove(); // Remove from assigned
                    // Log empno and employeeName to the console
                    console.log(`Employee Unselected: Empno: ${empno}, Employee Name: ${employeeName}`);
                });

                console.log("All employees have been unselected from Assigned to Unassigned.");
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

            // Function to remove an employee and move them back to Unassigned
            window.removeEmployee = function(empno, button) {
                const assignedContainer = document.getElementById("assignedEmployees");
                const unassignedContainer = document.getElementById("UnassignedEmployees");

                // Get the employee name to be removed
                const employeeName = button.parentElement.childNodes[0].textContent;

                // Log empno and employeeName to the console
                console.log(`Removing Employee: Empno: ${empno}, Name: ${employeeName}`);

                // Move back to UnassignedEmployees
                unassignedContainer.innerHTML += `
                <div class="draggable" draggable="true" ondragstart="drag(event)" data-empno="${empno}">
                    <input type="checkbox" class="mr-2 employee-checkbox" value="${empno}" />
                    ${employeeName}
                </div>`;

                // Remove from assigned
                button.parentElement.remove();

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
                    unassignedAll(); // This transfers all assigned employees back to Unassigned

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
                    const empnos = [];

                    // Collect employee numbers into an array
                    assignedEmployees.forEach(emp => {
                        empnos.push(emp.getAttribute('data-empno'));
                    });

                    // Check if there are no assigned employees
                    if (empnos.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No employees assigned',
                            text: 'Please select at least one employee before saving.',
                        });
                        return; // Stop further execution if no employees are selected
                    }

                    // Convert the array to JSON format
                    const assignedEmpnoJSON = JSON.stringify(empnos);

                    const patternId = $('#hiddenPatternId').val(); // Retrieve the value
                    console.log("Pattern ID:", patternId);

                    // AJAX request to update the database
                    $.ajax({
                        url: 'update-pattern-schedules.php', // Your backend PHP file
                        type: 'POST',
                        data: {
                            pattern_id: patternId,
                            assigned_empno_schedule: assignedEmpnoJSON,
                        },
                        beforeSend: function() {
                            console.log("Data to be sent:", {
                                pattern_id: patternId,
                                assigned_empno_schedule: assignedEmpnoJSON,
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Schedule has been updated successfully!',
                                timer: 1500, // Auto-close
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while updating the schedule.',
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
                showCloseButton: true, // Shows the X button
                confirmButtonText: 'Submit', // Change confirm button text
                confirmButtonColor: '#d33' // Optional: change color for visibility
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send POST request to update the status to 'Inactive'
                    $.ajax({
                        type: 'POST',
                        url: 'fetch-pattern-schedules.php', // Same script for fetching and updating
                        data: {
                            delete_pattern_id: patternId
                        },
                        success: function(response) {
                            // Optionally refresh the DataTable
                            $('#displaySchedulePattern').DataTable().ajax.reload();

                            // Show success message with timer
                            Swal.fire({
                                title: 'Deactivated!',
                                text: 'The schedule pattern has been deactivated.',
                                icon: 'success',
                                timer: 2000, // Timer in milliseconds (2000ms = 2 seconds)
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                }
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error occurred while deleting:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was an error deactivating the schedule pattern.',
                                icon: 'error',
                                timer: 2000, // Timer in milliseconds
                                timerProgressBar: true,
                                showConfirmButton: false,
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                }
                            });
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

        // Add event listener to the scheduleType dropdown to hide/show NWD based on selected type
        document.getElementById('scheduleType').addEventListener('change', function(e) {
            const scheduleType = e.target.value;

            // If schedule type is "Regular", hide the NWD option
            if (scheduleType === "Regular") {
                populateTimeDropdowns(false); // Hide NWD
            } else {
                populateTimeDropdowns(true); // Show NWD
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

            // Validate input fields
            if (!scheduleName || scheduleType === "Select schedule type") {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter a schedule name and select a schedule type.'
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