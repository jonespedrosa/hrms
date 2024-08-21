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




    <!-- DataTables CSS 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.9/css/buttons.dataTables.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.9/js/buttons.html5.min.js"></script>  -->








    <style>
        .swal2-actions .swal2-cancel {
            margin-right: 10px;
            /* Adjust as needed */
        }

        .flex-container {
            display: flex;
            flex-direction: column;
        }

        .modal-lg-custom {
            max-width: 80%;
            /* Adjust the width as needed */
        }

        /* Custom class for modal animation */
        .modal-bounce {
            animation: modal-bounce 0.5s;
        }

        .green-header {
            background: linear-gradient(to right, white, #E9EBF3);
            color: white;
        }

        .custom-bg-color {
            background-color: #E9EBF3 !important;
            /* Replace with your desired color */
        }

        @keyframes modal-bounce {

            0%,
            100% {
                transform: translateY(-20px);
                /* Adjust the distance as needed */
            }

            50% {
                transform: translateY(0);
            }
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
            <div class="mb-2">
                <h4 class="mb-0 mr-3 ml-1 font-weight-bold">Receiving Meal Allowance List</h4>
            </div>
        </div>


        <!-- Datatable -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Employee Details</h5>
                <div>
                    <button type="button" class="btn btn-primary font-weight-bold" id="btnAddEmployees" data-toggle="modal" data-target="#modalAddNewEmployees">
                        <i class="mr-1 fas fa-user-plus"></i> Add Employees
                    </button>
                    <button type="button" class="ml-2 btn btn-dark font-weight-bold" id="exportButton">
                        <i class="fas fa-download mr-1"></i> Export
                    </button>
                   <a href="meal-allowance-settings.php">
                   <button type="button" class="ml-2 btn btn-danger font-weight-bold" id="exportButton">
                        <i class="fas fa-cog mr-1"></i> Settings
                    </button>
                   </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="mealAllowanceList" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>Employee No.</th>
                                <th>Name</th>
                                <th>Branch</th>
                                <th>No. of Days</th>
                                <th>No. of Overtime</th>
                                <th>Total Allowance</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Add Select Employees Modal -->
        <div class="modal fade" id="modalAddNewEmployees" tabindex="-1" role="dialog" aria-labelledby="modalAddNewEmployeesTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header green-header">
                        <div class="flex-container">
                            <h5 class="modal-title m-0 font-weight-bold text-primary" id="exampleModalLongTitle">List of Active Employees</h5>
                            <h6 class="m-0 font-weight-bold text-primary">Total of Selected: <span style="color: red;" id="selectedCount">0</span></h6>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary font-weight-bold" id="addSelectedEmployees">
                            <i class="fas fa-plus"></i> Add
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

</body>


<script>
    // Function to show loading indicator with changing text
    function showLoadingIndicator() {
        const messages = [
            "Initializing data load...",
            "Processing calculations...",
            "Verifying overtime records...",
            "Counting active days...",
            "Please wait..."
        ];


        let messageIndex = 0;

        const loadingSwal = Swal.fire({
            title: messages[messageIndex],
            timerProgressBar: true,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                // Change the title text at regular intervals
                const interval = setInterval(() => {
                    messageIndex = (messageIndex + 1) % messages.length;
                    Swal.update({
                        title: messages[messageIndex]
                    });
                }, 1500); // Change text every second

                // Store the interval ID so we can clear it later
                loadingSwal.intervalId = interval;
            },
            willClose: () => {
                // Clear the interval when the Swal is closed
                clearInterval(loadingSwal.intervalId);
            }
        });

        return loadingSwal;
    }

    $(document).ready(function() {

        // Show loading indicator
        var loadingSwal = showLoadingIndicator();



        // Define table variable to hold DataTable instance
        var table = $('#mealAllowanceList').DataTable({
            stateSave: false,
            buttons: ['excel'], // Enable only Excel export button
            ajax: {
                url: 'meal-allowance-list-query.php',
                data: function(d) {
                    d.empno = $('#empnoInput').val();
                },
                dataSrc: function(json) {
                    console.log(json); // Log the JSON response
                    loadingSwal.close();
                    return json;
                },
                error: function(xhr, errorText, thrownError) {
                    loadingSwal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to fetch data!',
                    });
                }
            },
            columns: [{
                    data: 'empno',
                    title: "EMPLOYEE NO."
                },
                {
                    data: 'name',
                    title: "FULL NAME"
                },
                {
                    data: 'branch',
                    title: "BRANCH"
                },
                {
                    data: null,
                    title: "NO. OF DAYS",
                    render: function(data, type, row) {
                        let numberOfDays = 0;
                        if (row.sched_time && Array.isArray(row.sched_time)) {
                            row.sched_time.forEach(function(entry) {
                                if (entry.A_timein && entry.A_timeout && entry.M_timein && entry.M_timeout) {
                                    numberOfDays++;
                                }
                            });
                        }
                        return numberOfDays;
                    }
                },
                {
                    data: null,
                    title: "NO. OF OVERTIME",
                    render: function(data, type, row) {
                        let approvedOvertimeCount = 0;
                        if (row.sched_time && Array.isArray(row.sched_time)) {
                            row.sched_time.forEach(function(entry) {
                                if (entry.otstatus === "approved") {
                                    approvedOvertimeCount++;
                                }
                            });
                        }
                        return approvedOvertimeCount;
                    }
                },
                {
                    data: null,
                    title: "TOTAL ALLOWANCE",
                    render: function(data, type, row) {
                        let numberOfDays = 0;
                        if (row.sched_time && Array.isArray(row.sched_time)) {
                            row.sched_time.forEach(function(entry) {
                                if (entry.A_timein && entry.A_timeout && entry.M_timein && entry.M_timeout) {
                                    numberOfDays++;
                                }
                            });
                        }
                        let totalMeals = numberOfDays * 75;

                        // Calculate total approved overtime hours
                        let totalApprovedOvertimeCount = 0;
                        if (row.sched_time && Array.isArray(row.sched_time)) {
                            row.sched_time.forEach(function(entry) {
                                if (entry.otstatus === "approved") {
                                    totalApprovedOvertimeCount++;
                                }
                            });
                        }

                        // Calculate total allowance including approved overtime hours
                        totalMeals += totalApprovedOvertimeCount * 75;

                        return totalMeals;
                    }
                },
                {
                    data: 'status',
                    title: "STATUS",
                    className: 'text-center',
                    render: function(data, type, row) {
                        let badgeClass = '';
                        switch (row.status) {
                            case 'Inactive':
                                badgeClass = 'badge-warning';
                                break;
                            case 'Active':
                                badgeClass = 'badge-success';
                                break;
                            default:
                                badgeClass = 'badge-secondary';
                                break;
                        }
                        return `<span class="badge ${badgeClass}">${row.status}</span>`;
                    }
                },
                {
                    data: null,
                    title: "ACTION",
                    render: function(data, type, row) {
                        return `<button class="btn btn-sm btn-link delete-icon" data-empno="${row.empno}" data-name="${row.name}">
                        <i class="fa fa-trash text-danger"></i>
                    </button>`;
                    }
                }
            ],
            initComplete: function() {


                // Export button click event
                $('#exportButton').on('click', function() {
                    table.buttons('.buttons-excel').trigger('click'); // Trigger Excel export
                });

                // Delete icon click handler (if needed)
                $('#mealAllowanceList').on('click', '.delete-icon', function() {
                    var empno = $(this).data('empno');
                    var name = $(this).data('name');
                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                    swalWithBootstrapButtons.fire({
                        title: 'Are you sure?',
                        html: `You are about to remove record with <strong>Employee No. ${empno}</strong> and <strong>${name}</strong>.`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'meal-allowance-remove-query.php',
                                method: 'POST',
                                data: {
                                    empno: empno
                                },
                                success: function(response) {
                                    const table = $('#mealAllowanceList').DataTable();
                                    const row = table.row($(`button[data-empno="${empno}"]`).parents('tr'));
                                    row.remove().draw();
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
                                    Toast.fire({
                                        icon: "warning",
                                        title: "The record has been removed successfully."
                                    });
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error('Error deleting record:', textStatus, errorThrown);
                                    swalWithBootstrapButtons.fire({
                                        title: 'Error!',
                                        text: 'Failed to delete the record.',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            }
        });
    });












    $(document).ready(function() {
        var selectedEmployees = {};

        // Function to update selected count
        function updateSelectedCount() {
            var count = Object.keys(selectedEmployees).length;
            $('#selectedCount').text(count);
        }

        $('#btnAddEmployees').click(function() {
            $('#modalAddNewEmployees').addClass('modal-bounce'); // Add bounce class
            setTimeout(function() {
                $('#modalAddNewEmployees').modal('show'); // Show modal after animation
            }, 100); // Adjust the delay as needed
        });

        $.ajax({
            url: 'meal-allowance-query.php',
            method: 'GET',
            success: function(data) {
                var employeeData = JSON.parse(data);

                // Populate the table
                var tableBody = $('#selectEmployeesTable tbody');
                employeeData.forEach(function(employee) {
                    var row = '<tr>' +
                        '<td><input type="checkbox" name="selectEmployee" value="' + employee.empno + '" data-name="' + employee.name + '" data-branch="' + employee.branch + '"></td>' +
                        '<td>' + employee.empno + '</td>' +
                        '<td>' + employee.name + '</td>' +
                        '<td>' + employee.branch + '</td>' +
                        '</tr>';
                    tableBody.append(row);
                });

                // Initialize DataTable
                var table = $('#selectEmployeesTable').DataTable();

                // Handle checkbox clicks
                $('#selectEmployeesTable tbody').on('change', 'input[type="checkbox"]', function() {
                    var empno = $(this).val();
                    if ($(this).is(':checked')) {
                        selectedEmployees[empno] = {
                            empno: empno,
                            name: $(this).data('name'),
                            branch: $(this).data('branch')
                        };
                    } else {
                        delete selectedEmployees[empno];
                    }
                    updateSelectedCount();
                });

                // Handle row clicks
                $('#selectEmployeesTable tbody').on('click', 'tr', function(e) {
                    if (!$(e.target).is('input[type="checkbox"]')) {
                        var checkbox = $(this).find('input[type="checkbox"]');
                        checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
                    }
                });

                // Preserve checkbox state on page change
                table.on('draw', function() {
                    $('#selectEmployeesTable tbody input[type="checkbox"]').each(function() {
                        var empno = $(this).val();
                        if (selectedEmployees[empno]) {
                            $(this).prop('checked', true);
                        }
                    });
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching employee data:', textStatus, errorThrown);
            }
        });


        // Add selected employees
        $('#addSelectedEmployees').on('click', function() {
            var selectedEmployeesArray = Object.values(selectedEmployees);
            console.log(selectedEmployeesArray);

            // Show confirmation dialog using SweetAlert2
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: 'You want to tag these employees to include a meal allowance?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed to insert
                    $.ajax({
                        url: 'meal-allowance-insert.php',
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(selectedEmployeesArray),
                        success: function(response) {
                            $('#modalAddNewEmployees').modal('hide'); // Optionally hide modal

                            // Reload the page after successful insertion
                            location.reload();

                            // Alternatively, reload DataTable after insertion
                            // $('#mealAllowanceList').DataTable().ajax.reload();

                            // Clear selected employees and reset checkboxes
                            selectedEmployees = {};
                            updateSelectedCount();
                            $('#selectEmployeesTable input[type="checkbox"]').prop('checked', false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error adding selected employees:', textStatus, errorThrown);
                            swalWithBootstrapButtons.fire({
                                title: 'Error',
                                text: 'Failed to add selected employees.',
                                icon: 'error'
                            });
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: 'Cancelled',
                        text: 'No employees were added.',
                        icon: 'error'
                    });
                }
            });
        });

    });


































    // Initialize the DataTable
    // $('#mealAllowanceList').dataTable({
    //     stateSave: true
    // });
</script>

</html>