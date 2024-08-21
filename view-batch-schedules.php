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
        .dropdown-item.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .custom-swal-button {
            background-color: #007BFF !important;
            /* Green */
            color: white;
        }

        #displayTableTrainingSched td {
            text-align: center;
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
    include("course/filterModal.php");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <div class="d-flex">
                    <a href="training_schedules.php">
                        <h4 class="mb-0 mr-3" style="font-weight: bold;">Training Course</h4>
                    </a>
                    <h4 class="mr-3">/</h4>
                    <h4 class="mb-0 mr-3" style="" id="viewBatchHeader">View Batch</h4>
                </div>
                <!-- <div class="small">
                    <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                    .<?php echo date('F d, Y - h:i:s A'); ?>
                </div> -->
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
                    <a id="completeThisBatch" class="btn btn-primary font-weight-bold">
                        <i class="mr-1 fas fa-check-circle"></i> Complete Batch Trainings
                    </a>
                </div>
            </div>
            <!-- Datatable -->
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover text-uppercase" id="displayTrainingBatch" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="text-center">DAY</th>
                            <th class="text-center">DATE</th>
                            <th class="text-center">LOCATION</th>
                            <th class="text-center">FACILITATOR</th>
                            <th class="text-center">START TIME</th>
                            <th class="text-center">END TIME</th>
                            <th class="text-center">NO. OF COURSES</th>
                            <th class="text-center">NO. OF TOPICS</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">STATUS BATCH</th>
                            <th class="text-center">ACTION</th>
                        </tr>
                    </thead>
                </table>
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
        var id = urlParams.get('id');
        var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter

        if (!id) {
            id = ''; // Set a default value, such as an empty string
        }
        if (!scheduleId) {
            scheduleId = ''; // Set a default value, such as an empty string
        }
        var viewBatchHeader = document.getElementById('viewBatchHeader');
        viewBatchHeader.innerText = 'Batch ' + id;
        // console.log("batch_number: " + id);
        
   // Call the function to render training batches
   onRenderTrainingBatch(id, scheduleId); // Pass both id and scheduleId

        // Call the function to trigger the update script
        updateTrainingBatchStatus();

    };

    function updateTrainingBatchStatus() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'update_automatic_training_batch.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log('Training batch status update: ' + xhr.responseText);
            }
        };
        xhr.send();
    }

    function onRenderTrainingBatch(id, scheduleId) {
        $.ajax({
            url: 'fetch_training_batch.php?id=' + id + '&schedule_id=' + scheduleId, // Pass both id and schedule_id in the URL
            method: 'GET',
            success: function(response) {
                // console.log("date captured " + response);
                let dataTable = $('#displayTrainingBatch').DataTable({
                    data: JSON.parse(response),
                    "columnDefs": [{
                        targets: '_all',
                        className: 'text-center'
                    }], // Center-align all columns
                    "autoWidth": true,
                    columns: [{
                            data: "day",
                            title: "DAY",
                            className: "text-center"
                        },
                        {
                            data: "datefrom",
                            title: "DATE",
                            className: "text-center"
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
                            data: "no_of_courses_count", // change 
                            title: "NO. OF COURSES",
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
                            data: "no_of_topics_count",
                            title: "NO. OF TOPICS",
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
                            data: "status",
                            title: "STATUS",
                            className: "text-center",
                            render: function(data, type, row) {
                                let badgeClass = "";
                                switch (row.status) {
                                    case 'In-Progress':
                                        badgeClass = "badge-warning";
                                        break;
                                    case 'Completed':
                                        badgeClass = "badge-success";
                                        break;
                                    default:
                                        badgeClass = "badge-warning";
                                        break;
                                }
                                return `<span class="badge ${badgeClass}">${row.status}</span>`;
                            }
                        },
                        {
                            data: "status_batch",
                            title: "STATUS BATCH",
                            className: "text-center",
                            visible: false,
                            render: function(data, type, row) {
                                let badgeClass = "";
                                switch (row.status_batch) {
                                    case 'In-Progress':
                                        badgeClass = "badge-warning";
                                        break;
                                    case 'Completed':
                                        badgeClass = "badge-success";
                                        break;
                                    default:
                                        badgeClass = "badge-warning";
                                        break;
                                }
                                return `<span class="badge ${badgeClass}">${row.status_batch}</span>`;
                            }
                        },
                        {
                            data: "id",
                            title: "ACTION",
                            className: "text-center",
                            render: function(data, type, row) {
                                let disableAddButton = row.location ? "disabled" : "";
                                let topicsName = '';
                                let topicIds = ''; // Initialize topicIds variable
                                if (row.list_of_topics) {
                                    let topics = JSON.parse(row.list_of_topics);
                                    if (Array.isArray(topics) && topics.length > 0) {
                                        topicsName = topics.map(topic => topic.topicsName).join(', ');
                                        topicIds = topics.map(topic => topic.id).join(','); // Join topic ids
                                    }
                                }
                                // console.log('topicsName:', topicsName); // Log the value of topicsName
                                // console.log('topicIds:', topicIds); // Log the value of topicIds

                                return `
                                    <div class="text-center d-flex justify-content-around">
                                        <a href="#" data-toggle="tooltip" 
                                            data-location="${row.location}" 
                                            data-datefrom="${row.datefrom}" 
                                            data-starttime="${row.starttime}" 
                                            data-endtime="${row.endtime}" 
                                            onclick="ViewBatchDetails('${row.id}', '${row.day}', '${row.course_id}', '${row.schedule_id}', this)" 
                                            title="View Batch Schedule" 
                                            class="ml-3">
                                            <svg id="batchDetailsIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                            </svg>
                                        </a>
                                        <div class="dropdown mr-3">
                                            <a href="#" class="dropdown-toggle" id="editDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-toggle="tooltip" title="Edit Batch Schedule">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="editDropdown">      
                                                <a class="dropdown-item font-weight-bold addBatchLink ${disableAddButton}" href="#" onclick="openAddTrainingDetails('${row.id}', '${row.day}', '${row.course_id}', '${row.schedule_id}',this)">Add</a>
                                                <hr>
                                                <a class="dropdown-item font-weight-bold editBatchLink" href="#" onclick="openEditTrainingDetails('${row.id}', '${row.day}', '${row.course_id}', '${row.schedule_id}', '${row.starttime}', '${row.endtime}', '${row.location}', '${row.datefrom}', '${topicsName}', '${topicIds}','${encodeURIComponent(row.name_facilitator)}')">Edit</a>
                                            </div>
                                        </div>
                                    </div>`;
                            }
                        },
                    ],
                    "initComplete": function(settings, json) {
                        checkBatchStatus();
                    }

                });

            },

            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function openEditTrainingDetails(id, day, course_id, schedule_id, startTime, endTime, location, datefrom, topicsName, topicIds, facilitatorName) {
        var urlParams = new URLSearchParams(window.location.search);
        var urlId = urlParams.get('id');
        if (!urlId) {
            urlId = '';
        }
        // Construct the URL for the edit-training-details.php page with startTime, endTime, location, and topic_ids parameters
        var editTrainingURL = 'edit-training-details.php?id=' + urlId + '&day=' + day + '&course_id=' + course_id + '&schedule_id=' + schedule_id +
            '&startTime=' + startTime + '&endTime=' + endTime + '&location=' + location + '&datefrom=' + datefrom + '&topicsName=' + encodeURIComponent(topicsName) +
            '&topic_ids=' + encodeURIComponent(topicIds) + '&facilitatorName=' + facilitatorName;

        // console.log('Constructed URL for edit-training-details.php:', editTrainingURL); // Log the constructed URL for the edit page

        // Check if location is empty or "Not Set"
        if (!location || location === "Not Set") {
            Swal.fire({
                icon: "error",
                title: "No data available",
                text: "Location is not set. Please set the location, courses, and topics before editing batch details!",
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'custom-swal-button'
                }
            });
            return; // Stop further execution
        }

        // Check if CTRL key is pressed
        if (event.ctrlKey || event.metaKey) {
            // Open link in new tab
            window.open(editTrainingURL, '_blank');
        } else {
            // Redirect to view-batch-details.php with the constructed URL
            window.location.href = editTrainingURL;
        }
        // Prevent default click action
        event.preventDefault();

    }

    function checkBatchStatus() {
        let allCompleted = true;
        $('#displayTrainingBatch').DataTable().rows().every(function() {
            let rowData = this.data();
            if (rowData.status_batch !== 'Completed') {
                allCompleted = false;
                return false; // Exit the loop early
            }
        });

        if (allCompleted) {
            $('#completeThisBatch').addClass('disabled').removeAttr('href').off('click');
            $('.dropdown').hide(); // Hide the dropdown
        } else {
            $('#completeThisBatch').removeClass('disabled').attr('href', '#').on('click', function(event) {
                event.preventDefault(); // Prevent default behavior
                // Add your click event logic here if needed
            });
            $('.dropdown').show(); // Show the dropdown
        }
    }

    function openAddTrainingDetails(id, day, course_id, schedule_id, element) {
        var urlParams = new URLSearchParams(window.location.search);
        var urlId = urlParams.get('id');
        if (!urlId) {
            urlId = '';
        }

        // Get the DataTable instance
        var dataTable = $('#displayTrainingBatch').DataTable();

        // Get the closest row to the clicked button
        var closestRow = $(element).closest('tr');

        // Get the data of the closest row
        var rowData = dataTable.row(closestRow).data();

        // Extract the date from the rowData
        var datefrom = rowData.datefrom;
        var addTrainingURL1 = 'add-training-details.php?id=' + urlId + '&day=' + day + '&course_id=' + course_id + '&schedule_id=' + schedule_id + '&datefrom=' + datefrom;
        var trainingsDetailsParams = 'id=' + urlId + '&day=' + day + '&course_id=' + course_id + '&schedule_id=' + schedule_id + '&datefrom=' + datefrom;

        // console.log('Constructed URL for add-training-details.php:', addTrainingURL1); // Log the constructed URL for the first page

        // Set the href attribute dynamically for the "Add" link in the dropdown menu of the current row
        element.setAttribute('href', addTrainingURL1); // Set href attribute for the first link

        // Redirect to trainings-details.php and pass the data as query parameters
        window.location.href = 'trainings-details.php?' + trainingsDetailsParams;
    }

    function ViewBatchDetails(id, day, course_id, schedule_id, element) {
        var urlParams = new URLSearchParams(window.location.search);
        var urlId = urlParams.get('id');
        var datefrom = element.dataset.datefrom;
        var starttime = element.dataset.starttime; // Retrieve starttime value from the data attribute of the element
        var endtime = element.dataset.endtime; // Retrieve endtime value from the data attribute of the element
        var location = element.dataset.location; // Retrieve location value from the data attribute of the element

        if (!urlId) {
            urlId = '';
        }

        // Construct the URL for the view-batch-details.php page with parameters
        var viewBatchDetails = 'view-batch-details.php?id=' + urlId + '&day=' + day + '&course_id=' + course_id + '&schedule_id=' + schedule_id + '&datefrom=' + datefrom + '&starttime=' + starttime + '&endtime=' + endtime;

        // Check if location is empty or "Not Set"
        if (!location || location === "Not Set") {
            Swal.fire({
                icon: "error",
                title: "No data available",
                text: "Location is not set. Please set the location, courses, and topics before viewing batch details!",
                timerProgressBar: true,
                customClass: {
                    confirmButton: 'custom-swal-button'
                }
            });
            return; // Stop further execution
        }

        // Check if CTRL key is pressed
        if (event.ctrlKey || event.metaKey) {
            // Open link in new tab
            window.open(viewBatchDetails, '_blank');
        } else {
            // Redirect to view-batch-details.php with the constructed URL
            window.location.href = viewBatchDetails;
        }
        // Prevent default click action
        event.preventDefault();
    }


    $(document).ready(function() {
        $('#completeThisBatch').on('click', function(event) {
            event.preventDefault(); // Prevent default behavior of anchor tag

            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            var scheduleId = urlParams.get('schedule_id'); // Get the value of the 'schedule_id' parameter

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                },
                buttonsStyling: false,
                showCloseButton: true, // Show close button (x) in the top-right corner
            });

            swalWithBootstrapButtons.fire({
                title: "Complete <strong>Batch " + id + "</strong> training schedule?",
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
                            batch_number: id,
                            schedule_id: scheduleId,
                            status: 'completed'
                        }, // Data to be sent to the server
                        success: function(response) {
                            // Handle success
                            // console.log(response);
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
        });
    });
</script>

</html>