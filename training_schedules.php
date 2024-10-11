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
        .text-center {
            text-align: center;
        }

        #displayTableTrainingSched td {
            text-align: center;
        }


        /* Define a CSS class to set cursor to "not allowed" */
        .cursor-not-allowed {
            cursor: not-allowed;
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
                <h4 class="mb-0 mr-3 font-weight-bold">Training Schedules</h4>
            </div>
        </div>
        <!-- Datatable -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 row m-0">
                <!-- + New Courses  -->
                <div class="col-sm-6">
                    <!-- <button type class="btn btn-primary d-flex align-items-center" data-toggle="modal" data-target="#filterModal">
                        <span class="mr-3">Filter</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                        </svg>
                    </button> -->
                </div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <a href="createtrainingschedules.php" class="btn btn-primary font-weight-bold">
                        <i class="mr-1 fas fa-calendar"></i> + Add New Schedules
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="displayTableTrainingSched" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>BATCH NO.</th>
                                <th>COURSE TITLE</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>NO. OF TRAINEES</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal EditCourse or ViewCourses-->
            <div class="modal fade" id="viewCoursesEdit" tabindex="-1" role="dialog" aria-labelledby="viewCoursesEditTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <!-- parents of content -->
                    <div class="modal-content custom-modal-content" style="background-color: white; width: 120%;">
                        <!-- <div class="modal-content"> -->
                        <div class="modal-header">
                            <h5 class="modal-title" id="courseTitle"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5 class="" id="courseDescription"></h5>
                            <h6 class="mt-5" id="courseContentTopic">Course Content:</h6>
                            <h6 class="" id="countTopics"></h6>
                            <div class="border p-2 rounded-sm">
                                <p>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="editCourseButton">Edit</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
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
    function onRenderTrainingSchedules() {
        $.ajax({
            url: 'fetch_training_schedule.php',
            method: 'GET',
            success: function(response) {
                // console.log(response);
                let dataTable = $('#displayTableTrainingSched').DataTable({
                    data: JSON.parse(response),
                    "columnDefs": [{
                            targets: '_all',
                            className: 'text-center'
                        } // Center-align all columns
                    ],
                    "autoWidth": true,
                    columns: [{
                            data: "batch_number",
                            title: "BATCH NO.",
                            className: "batch_number"
                        },
                        {
                            data: "course_name",
                            title: "COURSE TITLE",
                            className: "course_name"
                        },
                        {
                            data: "datefrom",
                            title: "START DATE",
                            className: "datefrom"
                        },
                        {
                            data: "dateto",
                            title: "END DATE",
                            className: "dateto"
                        },
                        {
                            data: "number_of_trainees",
                            title: "NO. OF TRAINEES",
                            className: "number_of_trainees"
                        },
                        {
                            data: 'status',
                            title: 'STATUS', // Column title
                            className: 'text-center', // Center align text
                            render: function(data, type, row) {
                                if (data === 'Active') {
                                    return `<span class="badge badge-success">${data}</span>`;
                                } else if (data === 'Inactive') {
                                    return `<span class="badge badge-danger">${data}</span>`;
                                } else {
                                    return `<span class="badge badge-secondary">${data}</span>`; // Default styling
                                }
                            }
                        },
                        {
                            data: "id",
                            title: "ACTION",
                            className: "action_btns",
                            render: function(data, type, row) {
                                // console.log(row); // Log the entire row object
                                // Check if the status is Completed or completed
                                if (row.status.toLowerCase() === 'completed') {
                                    // If status is completed, disable the calendar button
                                    return `
                                    <div class="text-center">
                                        <a href="#" class="mr-2" onclick="openPages('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="View Batch Details?">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                            </svg>
                                        </a>
                                        <a href="edittrainingschedules.php?batch_number=${row.batch_number}&schedule_id=${row.id}" class="mr-2" data-toggle="tooltip" title="Edit Batch Date?">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="mr-2" data-toggle="tooltip" title="Completed Batch" class="disabled cursor-not-allowed" disabled>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6c757d" class="bi bi-calendar-check-fill disabled-icon" viewBox="0 0 16 16">
                                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                                            </svg>
                                        </a>
                                    </div>`;
                                } else {
                                    // If status is not completed, render both buttons
                                    return `
                                    <div class="text-center">
                                        <a href="#" class="mr-2" onclick="openPages('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="View Batch Details?">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                            </svg>
                                        </a>
                                        <a href="edittrainingschedules.php?batch_number=${row.batch_number}&schedule_id=${row.id}" class="mr-2" data-toggle="tooltip" title="Edit Batch Date?">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                            </svg>
                                        </a>
                                        <a href="#" class="mr-2" onclick="openCalendar('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="Complete Batch?">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check-fill" viewBox="0 0 16 16">
                                                <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                                            </svg>
                                        </a>
                                    </div>`;
                                }
                            }
                        }
                        // {
                        //     data: "id",
                        //     title: "ACTION",
                        //     className: "action_btns",
                        //     render: function(data, type, row) {
                        //         // console.log(row); // Log the entire row object
                        //         // Check if the status is Completed or completed
                        //         if (row.status.toLowerCase() === 'completed') {
                        //             // If status is completed, disable the calendar button
                        //             return `
                        //             <div class="text-center">
                        //                 <a href="#" class="mr-4" onclick="openPages('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="View Batch Details?">
                        //                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        //                         <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        //                         <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        //                     </svg>
                        //                 </a>
                        //                 <a href="#" data-toggle="tooltip" title="Complete Batch?" class="disabled cursor-not-allowed" disabled>
                        //                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#6c757d" class="bi bi-calendar-check-fill disabled-icon" viewBox="0 0 16 16">
                        //                     <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                        //                     </svg>
                        //                 </a>
                        //             </div>`;
                        //         } else {
                        //             // If status is not completed, render both buttons
                        //             return `
                        //             <div class="text-center">
                        //                 <a href="#" class="mr-4" onclick="openPages('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="View Batch Details?">
                        //                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                        //                         <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        //                         <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                        //                     </svg>
                        //                 </a>
                        //                 <a href="#" onclick="openCalendar('${row.batch_number}', '${row.id}')" data-toggle="tooltip" title="Complete Batch?">
                        //                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check-fill" viewBox="0 0 16 16">
                        //                         <path d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
                        //                     </svg>
                        //                 </a>
                        //             </div>`;
                        //         }
                        //     }
                        // },
                    ],
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Call the function to fetch data and render the DataTable
    onRenderTrainingSchedules();

    function openPages(batchNumber, scheduleId) {
        // alert("Batch Number:", batchNumber);
        // alert("Schedule ID:", scheduleId);
        // var scheduleId = 2;
        var viewBatchURL = 'view-batch-schedules.php?id=' + batchNumber + '&schedule_id=' + scheduleId;
        var addTrainingURL = 'add-training-details.php?id=' + batchNumber;
        window.location.href = viewBatchURL;
    }

    function openCalendar(batchNumber, scheduleId) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
            },
            buttonsStyling: false,
            showCloseButton: true, // Show close button (x) in the top-right corner
        });

        swalWithBootstrapButtons.fire({
            title: "Complete <strong>Batch " + batchNumber + "</strong> training schedule?",
            html: `<p>By confirming, you acknowledge that the training for this batch will be marked as completed.</p>`,
            icon: "info",
            confirmButtonText: "Confirm",

        }).then((result) => {
            if (result.isConfirmed) {
                // Send AJAX request to update status
                $.ajax({
                    url: 'update_batch_number_status.php', // Change this to your server endpoint
                    type: 'POST',
                    data: {
                        batch_number: batchNumber,
                        schedule_id: scheduleId,
                        status: 'completed'
                    }, // Data to be sent to the server
                    success: function(response) {
                        // Handle success
                        console.log("Status updated successfully");
                        // Optionally, you can reload the page or update UI here
                        // Reload the page
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                        console.error("Error updating status:", error);
                    }
                });
            }
        });
    }
</script>

</html>