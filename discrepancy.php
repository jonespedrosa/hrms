<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


if (empty($_SESSION['user'])) {
    header('location:login.php');
}

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
@$to =  $rowl['bioto'];
@$datefrom2 = date("m-d-Y", strtotime($from));
@$dateto2 = date("m-d-Y", strtotime($to));

if ($userlevel != 'staff') {
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
        <!-- Date Picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- Flat picker CDN -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <!-- Custom styles for this page -->
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
        <!-- DataTables Buttons CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
        <!-- SWAL -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- MOMENT -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
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
        <?php include("navigation.php"); ?>
        <div class="container-fluid">
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div class="mb-1">
                    <h4 class="mb-0">Cut-off Details</h4>
                    <div class="small">
                        <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                        .<?php echo date('F d, Y - h:i:s A'); ?>
                    </div>
                </div>
                <br>


                <?php
                if ($empno == '1073' || $empno == 2684 || $empno == 3178 || $empno == 2525) {
                ?>
                    <div>
                        <a href="generatemanhours.php" class="btn btn-outline-primary  btn-sm mt-1">
                            <i class="fa fa-plus-circle fa-fw"></i> Generate Man Hours
                        </a>
                    </div>
                <?php } ?>
            </div>

            <div class="mt-5 d-sm-flex justify-content-between">
                <div class="">
                    <label for="select-breakdown" class="text-small">Select a breakdown:</label>
                    <select class="form-control p-2 mb-3 w-auto text-small" id="select-breakdown">
                        <option value="overtime" <?php echo (isset($_GET["br"]) && $_GET["br"] == "overtime") ? "selected" : ""; ?>>Overtime</option>
                        <option value="obp" <?php echo (isset($_GET["br"]) && $_GET["br"] == "obp") ? "selected" : ""; ?>>Official Business Permit</option>
                        <option value="wdo" <?php echo (isset($_GET["br"]) && $_GET["br"] == "wdo") ? "selected" : ""; ?>>Working Day Off</option>
                        <option value="leave" <?php echo (isset($_GET["br"]) && $_GET["br"] == "leave") ? "selected" : ""; ?>>Leave</option>
                        <option value="sched" <?php echo (isset($_GET["br"]) && $_GET["br"] == "sched") ? "selected" : ""; ?>>Change Schedule</option>
                        <option value="concern" <?php echo (isset($_GET["br"]) && $_GET["br"] == "concern") ? "selected" : ""; ?>>Concern</option>
                        <option value="late" <?php echo (isset($_GET["br"]) && $_GET["br"] == "late") ? "selected" : ""; ?>>Late</option>
                        <option value="overbreak" <?php echo (isset($_GET["br"]) && $_GET["br"] == "overbreak") ? "selected" : ""; ?>>Overbreak</option>
                        <option value="undertime" <?php echo (isset($_GET["br"]) && $_GET["br"] == "undertime") ? "selected" : ""; ?>>Undertime</option>
                    </select>
                </div>

                <div class="d-flex align-items-end justify-content-sm-end">
                    <?php if ($userlevel == 'master' or $userlevel == 'admin' or $branch == 'AUDIT') { ?>
                        <div>
                            <a href="pdf/generate/timesheet.php" class="btn btn-outline-success btn-sm mt-1 mb-3">
                                <i class="fa fa-plus-circle fa-fw"></i> Generate Employee Timesheet
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>



            <div class="row">
                <div class="col-xl-12 col-lg-12 mb-4">
                    <div class="card border-left-primary shadow">
                        <div class="card-header">
                            <div class="d-sm-flex justify-content-between">
                                <div class="m-3">
                                    <div class="input-group" style="width: fit-content">
                                        <input type="text" id="selected-range" class="form-control rounded-left text-small bg-white" placeholder="Select Date Range" aria-label="Select Date Range" aria-describedby="date-range">
                                        <button class="btn rounded-0 input-group-append bg-primary rounded-right" id="range-btn">
                                            <span class="text-small text-light">Submit</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                        <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                        <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                        <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                    </svg>
                                    <h5 class="m-3 font-weight-bold text-center" id="label-breakdown"></h5>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered" width="100%" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Area</th>
                                            <th>Branch/Department</th>
                                            <th>Total Hours</th>
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

        <?php if (@$_GET["a"] == 1) {
        ?>
            <script>
                window.onload = function() {
                    $("#myModal").modal('show');
                };
            </script>
        <?php
        }
        ?>
        <script src="js/ajax-breakdown.js"></script>
    </body>

    </html>

<?php } ?>