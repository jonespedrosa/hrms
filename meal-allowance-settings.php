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
        .swal2-actions .swal2-cancel {
            margin-right: 10px;
            /* Adjust as needed */
        }

        .flex-container {
            display: flex;
            flex-direction: column;
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
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <div class="d-flex">
                    <a href="meal-allowance-list.php">
                        <h4 class="mb-0 mr-3" style="font-weight: bold;">Receiving Meal Allowance List</h4>
                    </a>
                    <h4 class="mr-3">/</h4>
                    <h4 class="mb-0 mr-3" id="mealCostSettings">Settings</h4>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="card shadow mb-4">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Food Cost</h5>
                <!-- <div>
                    <button type="button" class="btn btn-primary font-weight-bold" id="btnSetCost">
                        <i class="fas fa-coins mr-1"></i> Set Cost
                    </button>
                </div> -->
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="mealCostDataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>ID</th>
                                <th>COST</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table rows will be populated dynamically -->
                        </tbody>
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
</body>


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#mealCostDataTable').DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: 'meal-allowance-displaycost.php',
                type: 'POST',
                dataSrc: function(response) {
                    console.log('Data received from server:', response); // Log the received data
                    return response.data; // Return the 'data' array for DataTables to process
                }
            },
            columns: [{
                    data: 'id'
                }, // Column for ID
                {
                    data: 'food_cost'
                }, // Column for COST
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<button class="btn btn-info btn-sm update-btn font-weight-bold" data-id="' + row.id + '"><i class="mr-1 fas fa-pencil-alt"></i> Update</button>';
                    }
                }
            ],
            columnDefs: [{
                    width: '10%',
                    targets: 0
                }, // Set the width of the first column (ID) to 10%
                {
                    width: '20%',
                    targets: 1
                }, // Set the width of the second column (COST) to 20%
                {
                    width: '10%',
                    targets: 2
                } // Set the width of the third column (ACTION) to 10%
            ]
        });

        // Add event listener for update button click
        $('#mealCostDataTable tbody').on('click', '.update-btn', async function() {
            // Get the row data using DataTable's API
            var row = $(this).closest('tr');
            var rowData = table.row(row).data();

            // Extract the current value from the "COST" column
            var currentCost = rowData.food_cost;
            var id = rowData.id;

            // Display the SweetAlert2 modal with the current value in the input
            const {
                value: number
            } = await Swal.fire({
                input: 'number',
                inputLabel: 'Enter a number',
                inputValue: currentCost, // Set the current value as the default value
                inputPlaceholder: 'Type a number here...',
                inputAttributes: {
                    'aria-label': 'Type a number here',
                    'min': '0'
                },
                showCancelButton: true
            });

            if (number) {
                // Send an AJAX request to update the database
                $.ajax({
                    url: 'meal-allowance-settings-query.php',
                    type: 'POST',
                    data: {
                        id: id,
                        food_cost: number
                    },
                    success: function(response) {
                        console.log('Response from server:', response); // Log the server response
                        if (response === 'success') {
                            Swal.fire('Updated!', `Number updated to: ${number}`, 'success').then(() => {
                                location.reload(); // Refresh the entire page
                            });
                        } else {
                            Swal.fire('Error', 'There was an error updating the number. Please try again.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'There was an error updating the number. Please try again.', 'error');
                    }
                });
            }
        });
    });


    // Initialize the DataTable
    // $('#mealCostDataTable').dataTable({
    //     stateSave: true
    // });
</script>

</html>