<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$exemption = [6114, 6115];

$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$department = $row['department'];
$branch = $row['branch'];
$userrid = $row['userid'];
$mothercafe = $row['mothercafe'];
$empno = $row['empno'];

$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

if (isset($_GET['branch'])) {
    @$_SESSION['useridd'] = $_GET['branch'];
    header('Location: ' . $_SERVER['PHP_SELF']);
}
$userid = $_SESSION['useridd'];
$sqll = "SELECT * FROM sched_date WHERE userid = '$userid'";
$queryl = $HRconnect->query($sqll);
$rowl = $queryl->fetch_array();
@$from = $rowl['biofrom'];
@$to = $rowl['bioto'];
@$datefrom2 = date("m-d-Y", strtotime($from));
@$dateto2 = date("m-d-Y", strtotime($to));

if ($userlevel != 'staff' && (in_array($empno, $exemption) || $userlevel == 'master')) {
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
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <!-- Date Picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- Flat picker CDN -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- Custom styles for this page -->
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <!-- DataTables Buttons CSS -->
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
        <!-- SWAL -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- MOMENT -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <!-- Add this in the <head> section of your HTML file -->
        <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

        <style>
            .text-small {
                font-size: 0.8rem;
            }

            #dataTable {
                font-size: 0.8rem;
            }

            .dt-buttons,
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_paginate .paginate_button,
            .dataTables_wrapper .dataTables_info {
                font-size: 0.8rem;
            }

            .custom-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                /* Semi-transparent black */
            }

            table {
                min-width: 1000px;
            }

            @media print {
                .dt-print-view h1 {
                    font-size: 1.5rem;
                    font-weight: bold;
                }
            }
        </style>
    </head>

    <body id="page-top" class="sidebar-toggled">
        <?php
        include("navigation.php");
        include("tagEmployeeModal.php");
        ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div class="mb-1">
                    <h4 class="mb-0">Cut-off Details</h4>
                    <div class="small">
                        <span class="fw-500 text-primary">
                            <?php echo date('l'); ?>
                        </span>
                        .
                        <?php echo date('F d, Y - h:i:s A'); ?>
                    </div>
                </div>
                <br>
            </div>

            <div class="mt-5 d-sm-flex justify-content-between">

            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 mb-4">
                    <div class="card border-left-primary shadow">
                        <div class="card-header">
                            <div class="d-sm-flex justify-content-between">
                                <div class="">
                                    <label for="select-department" class="text-small">Select a deparment:</label>
                                    <select class="form-control p-2 mb-3 w-auto text-small" id="select-department">
                                        <option value="all" selected>ALL</option>
                                        <!-- Other department options -->
                                    </select>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" height=16
                                        fill='currentColor'>
                                        <path
                                            d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm80 64c-8.8 0-16 7.2-16 16v64c0 8.8 7.2 16 16 16H368c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80z" />
                                    </svg>
                                    <h5 class="m-3 font-weight-bold text-center" id="label-breakdown">CWW
                                        Tagger</h5>
                                </div>
                            </div>
                            <div class="float-right">
                                <button class="btn btn-primary btn-sm mt-1 mb-3" data-toggle="modal"
                                    data-target="#tagEmployeeModal">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" height=14
                                            class="text-white" fill="currentColor">
                                            <path
                                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                        </svg>
                                        <span class="ml-2">
                                            Tag Employee
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered" width="100%"
                                    id="compressedEmployees">
                                    <thead>
                                        <tr>
                                            <th>Emp #</th>
                                            <th>Name</th>
                                            <th>Area</th>
                                            <th>Branch/Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody class="text-uppercase" id="breakdown-body">
                                        <!-- Content -->
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright © Mary Grace Foods Inc. 2019.</span>
                </div>
            </div>
        </footer>
        </div>
        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
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

        <script src="js/ajax-cwwtagger.js"></script>
    </body>

    </html>

<?php } ?>