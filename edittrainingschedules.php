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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Add this line in the head section of your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .text-center {
            text-align: center;
        }

        #firstDataTableDisplay td {
            text-align: center;
        }

        #secondaryDataTableDisplay td {
            text-align: center;
        }

        #batchNumber {
            width: 300px;
        }

        .datetimepicker input[type="text"] {
            background-color: white;
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
                <div class="d-flex">
                    <a href="training_schedules.php">
                        <h4 class="mb-0 mr-3" id="LabelTrainingSchedule" style="font-weight: bold;">Training Schedule</h4>
                    </a>
                    <h4 class="mr-3" id="slash">/</h4>
                    <h4 class="mb-0 mr-3" id="addTrainingBatch">Edit Batch Schedule</h4>
                </div>
            </div>
        </div>
        <form id="trainingSchedulesForm" class="bg-white rounded-sm shadow">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Schedule and Batch Details</h6>
                <!-- Button with Font Awesome icon -->
                <button id="updateScheduleBtn" class="btn btn-primary pt-2 text-center">
                    <h6 class="mb-1 font-weight-bold">
                        <i class="mr-1 fas fa-sync-alt"></i> Update Batch Schedule
                    </h6>
                </button>
            </div>
            <div class="form-group p-3">
                <div class="d-flex justify-content-between">
                    <label class="mb-1" for="">Select New Date Schedules:</label>
                </div>
                <!-- Date Picker Start Date and End Date  -->
                <div class="d-flex">
                    <div class="datetimepicker">
                        <input type="text" class="form-control" id="dateStartSched" placeholder="Select Start Date" required>
                    </div>
                    <label for="" class="ml-2 mr-2 mt-2">To:</label>
                    <div class="datetimepicker">
                        <input type="text" class="form-control" id="dateEndSched" placeholder="Select End Date" required>
                    </div>
                </div>
                <!-- Batch Number -->
                <div class="mt-1">
                    <label class="mb-1" for="">Update New Batch Number:</label>
                    <input id="batchNumber" type="number" class="form-control" min="1" max="5" placeholder="0000" required>
                </div>
            </div>
        </form>
        <!-- first Datatable -->
        <div class="card shadow mb-3">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Training Details: Current Number of Days</h6>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="secondaryDataTableDisplay" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>DAY</th>
                                <th>DATE</th>
                                <th>LOCATION</th>
                                <th>FACILITATOR</th>
                                <th>START TIME</th>
                                <th>END TIME</th>
                                <th>NO. OF COURSES</th>
                                <th>NO. OF TOPICS</th>
                                <th>STATUS</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                    </table>
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
    $(document).ready(function() {
        // Function to get query parameters from URL
        function getQueryParam(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Get the batch_number and schedule_id from the URL
        var batch_number = getQueryParam('batch_number');
        var schedule_id = getQueryParam('schedule_id');

        // Initialize the DataTable for lnd_training_batch
        var tableSecondary = $('#secondaryDataTableDisplay').DataTable({
            "ajax": {
                "url": "fetch_editdays_trainings_schedule.php",
                "data": {
                    "batch_number": batch_number,
                    "schedule_id": schedule_id
                },
                "dataSrc": function(json) {
                    return json.data; // Update data source to reflect new structure
                }
            },
            "columns": [{
                    "data": "day",
                    "title": "DAY"
                },
                {
                    "data": "datefrom",
                    "title": "DATE"
                },
                {
                    data: "location",
                    title: "LOCATION",
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) {
                            return '<span class="badge badge-secondary">Not Set</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: "name_facilitator",
                    title: "FACILITATOR",
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) {
                            return '<span class="badge badge-secondary">Not Set</span>';
                        } else {
                            // Parse the JSON array and build the HTML for each facilitator
                            var facilitators = JSON.parse(data);
                            var formattedNames = '';

                            facilitators.forEach(function(facilitator, index) {
                                formattedNames += '<span>' + facilitator.name_facilitator + '</span>';
                                // Add a comma and space if it's not the last item
                                if (index < facilitators.length - 1) {
                                    formattedNames += ', ';
                                }
                            });

                            return formattedNames;
                        }
                    }
                },
                {
                    data: "starttime",
                    title: "START TIME",
                    className: "text-center",
                    render: function(data, type, row) {
                        // Check if starttime is null or empty
                        if (!data) {
                            return '<span class="">--:--</span>';
                        } else {
                            // Assuming starttime is in HH:mm format
                            return data;
                        }
                    }
                },
                {
                    data: "endtime",
                    title: "END TIME",
                    className: "text-center",
                    render: function(data, type, row) {
                        // Check if endtime is null or empty
                        if (!data) {
                            return '<span class="">--:--</span>';
                        } else {
                            // Assuming endtime is in HH:mm format
                            return data;
                        }
                    }
                },
                {
                    data: "no_of_courses",
                    title: "NO. OF COURSES",
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) {
                            return '<span class="badge badge-secondary">Not Set</span>';
                        } else {
                            try {
                                // Parse the JSON string
                                var courses = JSON.parse(data);
                                // Count the number of keys in the parsed object
                                var courseCount = Object.keys(courses).length;
                                // Return the count
                                return courseCount;
                            } catch (e) {
                                // Handle any errors in parsing
                                return '<span class="badge badge-danger">Invalid Data</span>';
                            }
                        }
                    }
                },
                {
                    data: "no_of_topics",
                    title: "NO. OF TOPICS",
                    className: "text-center",
                    render: function(data, type, row) {
                        if (!data) {
                            return '<span class="badge badge-secondary">Not Set</span>';
                        } else {
                            try {
                                // Parse the JSON string
                                var topics = JSON.parse(data);
                                // Filter to count only entries with topicsName
                                var topicCount = topics.filter(topic => topic.topicsName).length;
                                // Return the count
                                return topicCount;
                            } catch (e) {
                                // Handle any errors in parsing
                                return '<span class="badge badge-danger">Invalid Data</span>';
                            }
                        }
                    }
                },
                {
                    "data": "status",
                    "title": "STATUS",
                    "className": "status",
                    "render": function(data, type, row) {
                        if (row.status === 'In-Progress') {
                            return '<span class="badge badge-warning">' + row.status + '</span>';
                        } else if (row.status === 'Completed') {
                            return '<span class="badge badge-success">' + row.status + '</span>';
                        } else {
                            return '<span class="badge badge-warning">' + row.status + '</span>';
                        }
                    }
                },
                {
                    "data": "action",
                    "title": "ACTION",
                    "render": function(data, type, row) {
                        return '<span class="fa fa-trash action-icon" style="cursor:pointer; color: #FF0808;"></span>';
                    }
                }
            ],
            "initComplete": function(settings, json) {
                // Log fetched data to console for verification
                // console.log('Fetched Data of secondaryDataTableDisplay:', json.data);

                // Set form fields based on fetched data
                var data_schedule = json.data[0]; // Assuming fetched data is an array with a single object
                $('#dateStartSched').val(data_schedule.datefrom);
                $('#dateEndSched').val(data_schedule.dateto);
                $('#batchNumber').val(data_schedule.batch_number);

                // Initialize date pickers with fetched data
                var dateStartPicker = flatpickr("#dateStartSched", {
                    enableTime: false,
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    defaultDate: data_schedule.datefrom,
                    onChange: function(selectedDates, dateStr, instance) {
                        dateEndPicker.set('minDate', dateStr);
                        dateEndPicker.setDate(dateStr, true);
                    }
                });

                var dateEndPicker = flatpickr("#dateEndSched", {
                    enableTime: false,
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "F j, Y",
                    defaultDate: data_schedule.dateto
                });

            }
        });

        // Event delegation for click on trash icon to show SweetAlert modal
        $('#secondaryDataTableDisplay').on('click', '.fa-trash', function() {
            var data = tableSecondary.row($(this).parents('tr')).data();
            var id_main = data.id_main;
            var dateToDelete = data.datefrom; // Adjust this according to your data structure

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success"
                },
                buttonsStyling: false,
                showCloseButton: true // Show close button (x) in the top-right corner
            });

            swalWithBootstrapButtons.fire({
                title: `Are you sure you want to delete this date <strong> ${dateToDelete}</strong>?`,
                html: `<p>By confirming, the data will be deleted and it will not be recoverable.</p>`,
                icon: "warning",
                showCancelButton: false, // Remove the cancel button
                confirmButtonText: "Confirm"

            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform delete operation here, e.g., make AJAX request
                    $.ajax({
                        url: 'delete_days_date_trainings.php',
                        method: 'POST',
                        data: {
                            id_main: id_main,
                            dateToDelete: dateToDelete
                        },
                        success: function(response) {
                            // Reload DataTable or update UI as needed
                            tableSecondary.ajax.reload();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The date has been deleted.',
                                icon: 'success',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting date:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete the date.',
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });

        // Update Batch Schedule Button Click Handler (for schedule_data)
        $('#updateScheduleBtn').click(function(e) {
            e.preventDefault();
            var dateStartSched = $('#dateStartSched').val();
            var dateEndSched = $('#dateEndSched').val();
            var batchNumber = $('#batchNumber').val();

            // Check if any of the fields are empty
            if (dateStartSched === '' || dateEndSched === '' || batchNumber === '') {
                Swal.fire({
                    icon: "error",
                    title: "Dates or Batch Number is required",
                    text: "Please make sure that batch number and date schedules are inputted!"
                });
                return; // Exit function, preventing further execution
            }

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success"
                },
                buttonsStyling: false,
                showCloseButton: true // Show close button (x) in the top-right corner
            });

            swalWithBootstrapButtons.fire({
                title: `Are you sure you want to update this <strong>Batch ${batch_number}</strong>?`,
                html: `<p>By confirming, the batch number or the training dates will be updated.</p>`,
                icon: "info",
                confirmButtonText: "Confirm",

            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'update_training_schedule_date.php',
                        method: 'POST',
                        data: {
                            dateStartSched: dateStartSched,
                            dateEndSched: dateEndSched,
                            batchNumber: batchNumber,
                            original_batch_number: batch_number, // Send the original batch number
                            schedule_id: schedule_id
                        },
                        success: function(response) {
                            console.log('Schedule updated successfully.');
                            // Show success message and redirect
                            Swal.fire({
                                title: 'Success!',
                                text: 'Schedule updated successfully.',
                                icon: 'success',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                // Redirect the user to the training_schedules.php page after the Swal.fire message is closed
                                window.location.href = 'training_schedules.php';
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating schedule:', error);
                        }
                    });
                }
            });
        });
    });









    // $(document).ready(function() {
    //     // Function to get query parameters from URL
    //     function getQueryParam(param) {
    //         var urlParams = new URLSearchParams(window.location.search);
    //         return urlParams.get(param);
    //     }

    //     // Get the batch_number and schedule_id from the URL
    //     var batch_number = getQueryParam('batch_number');
    //     var schedule_id = getQueryParam('schedule_id');

    //     // Initialize the DataTable for lnd_training_schedule
    //     var tableSchedule = $('#firstDataTableDisplay').DataTable({
    //         "ajax": {
    //             "url": "fetch_edit_training_schedule.php",
    //             "data": {
    //                 "batch_number": batch_number,
    //                 "schedule_id": schedule_id
    //             },
    //             "dataSrc": function(json) {
    //                 return json.data; // Update data source to reflect new structure
    //             }
    //         },
    //         "columns": [{
    //                 "data": "batch_number",
    //                 "title": "BATCH NO."
    //             },
    //             {
    //                 "data": "datefrom",
    //                 "title": "START DATE"
    //             },
    //             {
    //                 "data": "dateto",
    //                 "title": "END DATE"
    //             },
    //             {
    //                 "data": "status",
    //                 "title": "STATUS",
    //                 "className": "status",
    //                 "render": function(data, type, row) {
    //                     if (row.status === 'In-Progress') {
    //                         return '<span class="badge badge-warning">' + row.status + '</span>';
    //                     } else if (row.status === 'Completed') {
    //                         return '<span class="badge badge-success">' + row.status + '</span>';
    //                     } else {
    //                         return '<span class="badge badge-warning">' + row.status + '</span>';
    //                     }
    //                 }
    //             }
    //         ],
    //         "initComplete": function(settings, json) {
    //             // Log fetched data to console for verification
    //             console.log('Fetched Data of firstDataTableDisplay:', json.data);

    //             // Set form fields based on fetched data
    //             var data_schedule = json.data[0]; // Assuming fetched data is an array with a single object
    //             $('#dateStartSched').val(data_schedule.datefrom);
    //             $('#dateEndSched').val(data_schedule.dateto);
    //             $('#batchNumber').val(data_schedule.batch_number);

    //             // Initialize date pickers with fetched data
    //             var dateStartPicker = flatpickr("#dateStartSched", {
    //                 enableTime: false,
    //                 dateFormat: "Y-m-d",
    //                 altInput: true,
    //                 altFormat: "F j, Y",
    //                 defaultDate: data_schedule.datefrom,
    //                 onChange: function(selectedDates, dateStr, instance) {
    //                     dateEndPicker.set('minDate', dateStr);
    //                     dateEndPicker.setDate(dateStr, true);
    //                 }
    //             });

    //             var dateEndPicker = flatpickr("#dateEndSched", {
    //                 enableTime: false,
    //                 dateFormat: "Y-m-d",
    //                 altInput: true,
    //                 altFormat: "F j, Y",
    //                 defaultDate: data_schedule.dateto
    //             });
    //         }
    //     });

    //     // Get the batch_number and schedule_id from the URL
    //     var batch_number = getQueryParam('batch_number');
    //     var schedule_id = getQueryParam('schedule_id');

    //     // Initialize the DataTable for lnd_training_batch
    //     var tableSecondary = $('#secondaryDataTableDisplay').DataTable({
    //         "ajax": {
    //             "url": "fetch_editdays_trainings_schedule.php",
    //             "data": {
    //                 "batch_number": batch_number,
    //                 "schedule_id": schedule_id
    //             },
    //             "dataSrc": function(json) {
    //                 return json.data; // Update data source to reflect new structure
    //             }
    //         },
    //         "columns": [{
    //                 "data": "day",
    //                 "title": "DAY"
    //             },
    //             {
    //                 "data": "datefrom",
    //                 "title": "DATE"
    //             },
    //             {
    //                 data: "location",
    //                 title: "LOCATION",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     if (!data) {
    //                         return '<span class="badge badge-secondary">Not Set</span>';
    //                     } else {
    //                         return data;
    //                     }
    //                 }
    //             },
    //             {
    //                 data: "name_facilitator",
    //                 title: "FACILITATOR",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     if (!data) {
    //                         return '<span class="badge badge-secondary">Not Set</span>';
    //                     } else {
    //                         // Parse the JSON array and build the HTML for each facilitator
    //                         var facilitators = JSON.parse(data);
    //                         var formattedNames = '';

    //                         facilitators.forEach(function(facilitator, index) {
    //                             formattedNames += '<span>' + facilitator.name_facilitator + '</span>';
    //                             // Add a comma and space if it's not the last item
    //                             if (index < facilitators.length - 1) {
    //                                 formattedNames += ', ';
    //                             }
    //                         });

    //                         return formattedNames;
    //                     }
    //                 }
    //             },
    //             {
    //                 data: "starttime",
    //                 title: "START TIME",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     // Check if starttime is null or empty
    //                     if (!data) {
    //                         return '<span class="">--:--</span>';
    //                     } else {
    //                         // Assuming starttime is in HH:mm format
    //                         return data;
    //                     }
    //                 }
    //             },
    //             {
    //                 data: "endtime",
    //                 title: "END TIME",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     // Check if endtime is null or empty
    //                     if (!data) {
    //                         return '<span class="">--:--</span>';
    //                     } else {
    //                         // Assuming endtime is in HH:mm format
    //                         return data;
    //                     }
    //                 }
    //             },
    //             {
    //                 data: "no_of_courses",
    //                 title: "NO. OF COURSES",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     if (!data) {
    //                         return '<span class="badge badge-secondary">Not Set</span>';
    //                     } else {
    //                         try {
    //                             // Parse the JSON string
    //                             var courses = JSON.parse(data);
    //                             // Count the number of keys in the parsed object
    //                             var courseCount = Object.keys(courses).length;
    //                             // Return the count
    //                             return courseCount;
    //                         } catch (e) {
    //                             // Handle any errors in parsing
    //                             return '<span class="badge badge-danger">Invalid Data</span>';
    //                         }
    //                     }
    //                 }
    //             },
    //             {
    //                 data: "no_of_topics",
    //                 title: "NO. OF TOPICS",
    //                 className: "text-center",
    //                 render: function(data, type, row) {
    //                     if (!data) {
    //                         return '<span class="badge badge-secondary">Not Set</span>';
    //                     } else {
    //                         try {
    //                             // Parse the JSON string
    //                             var topics = JSON.parse(data);
    //                             // Filter to count only entries with topicsName
    //                             var topicCount = topics.filter(topic => topic.topicsName).length;
    //                             // Return the count
    //                             return topicCount;
    //                         } catch (e) {
    //                             // Handle any errors in parsing
    //                             return '<span class="badge badge-danger">Invalid Data</span>';
    //                         }
    //                     }
    //                 }
    //             },
    //             {
    //                 "data": "status",
    //                 "title": "STATUS",
    //                 "className": "status",
    //                 "render": function(data, type, row) {
    //                     if (row.status === 'In-Progress') {
    //                         return '<span class="badge badge-warning">' + row.status + '</span>';
    //                     } else if (row.status === 'Completed') {
    //                         return '<span class="badge badge-success">' + row.status + '</span>';
    //                     } else {
    //                         return '<span class="badge badge-warning">' + row.status + '</span>';
    //                     }
    //                 }
    //             },
    //             {
    //                 "data": "action",
    //                 "title": "ACTION",
    //                 "render": function(data, type, row) {
    //                     return '<span class="fa fa-trash action-icon" style="cursor:pointer; color: #FF0808;"></span>';
    //                 }
    //             }
    //         ],
    //         "initComplete": function(settings, json) {
    //             // Log fetched data to console for verification
    //             console.log('Fetched Data of secondaryDataTableDisplay:', json.data);
    //         }
    //     });

    //     // Event delegation for click on trash icon to show SweetAlert modal
    //     $('#secondaryDataTableDisplay').on('click', '.fa-trash', function() {
    //         var data = tableSecondary.row($(this).parents('tr')).data();
    //         var id_main = data.id_main;
    //         var dateToDelete = data.datefrom; // Adjust this according to your data structure

    //         const swalWithBootstrapButtons = Swal.mixin({
    //             customClass: {
    //                 confirmButton: "btn btn-success"
    //             },
    //             buttonsStyling: false,
    //             showCloseButton: true // Show close button (x) in the top-right corner
    //         });

    //         swalWithBootstrapButtons.fire({
    //             title: `Are you sure you want to delete this date <strong> ${dateToDelete}</strong>?`,
    //             html: `<p>By confirming, the data will be deleted and it will not be recoverable.</p>`,
    //             icon: "warning",
    //             showCancelButton: false, // Remove the cancel button
    //             confirmButtonText: "Confirm"

    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 // Perform delete operation here, e.g., make AJAX request
    //                 $.ajax({
    //                     url: 'delete_days_date_trainings.php',
    //                     method: 'POST',
    //                     data: {
    //                         id_main: id_main,
    //                         dateToDelete: dateToDelete
    //                     },
    //                     success: function(response) {
    //                         // Reload DataTable or update UI as needed
    //                         tableSecondary.ajax.reload();
    //                         Swal.fire({
    //                             title: 'Deleted!',
    //                             text: 'The date has been deleted.',
    //                             icon: 'success',
    //                             timer: 1500,
    //                             timerProgressBar: true,
    //                             showConfirmButton: false
    //                         });
    //                     },
    //                     error: function(xhr, status, error) {
    //                         console.error('Error deleting date:', error);
    //                         Swal.fire({
    //                             title: 'Error!',
    //                             text: 'Failed to delete the date.',
    //                             icon: 'error',
    //                             timer: 1500,
    //                             timerProgressBar: true,
    //                             showConfirmButton: false
    //                         });
    //                     }
    //                 });
    //             }
    //         });
    //     });

    //     // Update Batch Schedule Button Click Handler (for schedule_data)
    //     $('#updateScheduleBtn').click(function(e) {
    //         e.preventDefault();
    //         var dateStartSched = $('#dateStartSched').val();
    //         var dateEndSched = $('#dateEndSched').val();
    //         var batchNumber = $('#batchNumber').val();

    //         // Check if any of the fields are empty
    //         if (dateStartSched === '' || dateEndSched === '' || batchNumber === '') {
    //             Swal.fire({
    //                 icon: "error",
    //                 title: "Dates or Batch Number is required",
    //                 text: "Please make sure that batch number and date schedules are inputted!"
    //             });
    //             return; // Exit function, preventing further execution
    //         }

    //         const swalWithBootstrapButtons = Swal.mixin({
    //             customClass: {
    //                 confirmButton: "btn btn-success"
    //             },
    //             buttonsStyling: false,
    //             showCloseButton: true // Show close button (x) in the top-right corner
    //         });

    //         swalWithBootstrapButtons.fire({
    //             title: `Are you sure you want to update this <strong>Batch ${batch_number}</strong>?`,
    //             html: `<p>By confirming, the batch number or the training dates will be updated.</p>`,
    //             icon: "info",
    //             confirmButtonText: "Confirm",

    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 $.ajax({
    //                     url: 'update_training_schedule_date.php',
    //                     method: 'POST',
    //                     data: {
    //                         dateStartSched: dateStartSched,
    //                         dateEndSched: dateEndSched,
    //                         batchNumber: batchNumber,
    //                         original_batch_number: batch_number, // Send the original batch number
    //                         schedule_id: schedule_id
    //                     },
    //                     success: function(response) {
    //                         console.log('Schedule updated successfully.');
    //                         // Show success message and redirect
    //                         Swal.fire({
    //                             title: 'Success!',
    //                             text: 'Schedule updated successfully.',
    //                             icon: 'success',
    //                             timer: 1500,
    //                             timerProgressBar: true,
    //                             showConfirmButton: false
    //                         }).then(() => {
    //                             // Redirect the user to the training_schedules.php page after the Swal.fire message is closed
    //                             window.location.href = 'training_schedules.php';
    //                         });
    //                     },
    //                     error: function(xhr, status, error) {
    //                         console.error('Error updating schedule:', error);
    //                     }
    //                 });
    //             }
    //         });
    //     });
    // });





















    // Initialize the DataTable
    // $('#firstDataTableDisplay').dataTable({
    //     stateSave: true
    // });

    // Initialize the DataTable
    // $('#secondaryDataTableDisplay').dataTable({
    //     stateSave: true
    // });
</script>

</html>