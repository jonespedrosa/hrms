<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


if (empty($_SESSION['user'])) {
    header('location:../login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];

// cutoff
$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si
ON si.empno = ui.empno
WHERE si.status = 'Pending' AND ui.empno = $empno;";
$querybuilder = $HRconnect->query($getDateSQL);
$rowCutOff = $querybuilder->fetch_array();

//For Approval
$datestart = $rowCutOff['datefrom'];
$dateend =  $rowCutOff['dateto'];


?>

<!-- approve OBP -->
<?php
if (isset($_POST['but_update'])) {

    if (isset($_POST['update'])) {
        foreach ($_POST['update'] as $updateid) {

            $timedate = date("Y-m-d H:i");

            $updateUser = "UPDATE obp SET
							status = 'Approved',
							approval = '$user',
							app_timedate = '$timedate'
							WHERE obpid = " . $updateid;
            mysqli_query($HRconnect, $updateUser);

            header("location:approvals.php?obp=obp&m=1");
        }
    }
}
?>

<!-- approve OBP head -->
<?php
if (isset($_POST['but_updateh'])) {

    if (isset($_POST['updateh'])) {
        foreach ($_POST['updateh'] as $updateid) {

            $timedate = date("Y-m-d H:i");

            $updateUser = "UPDATE obp SET
							status = 'Approved',
							approval = '$user',
							app_timedate = '$timedate'
							WHERE obpid = " . $updateid;
            mysqli_query($HRconnect, $updateUser);

            header("location:approvals.php?obp=obp&m=1");
        }
    }
}
?>

<!-- approve OT -->
<?php
if (isset($_POST['but_update1'])) {

    if (isset($_POST['update1'])) {
        foreach ($_POST['update1'] as $updateid) {

            $timedate = date("Y-m-d H:i");

            $updateUser = "UPDATE overunder SET
							otstatus = 'approved',
							approver = '$user',
							apptimedate = '$timedate'
							WHERE ovid = " . $updateid;
            mysqli_query($HRconnect, $updateUser);

            header("location:approvals.php?ot=ot&m=2");
        }
    }
}
?>

<!-- approve OT head -->
<?php
if (isset($_POST['but_update1h'])) {

    if (isset($_POST['update1h'])) {
        foreach ($_POST['update1h'] as $updateid) {

            $timedate = date("Y-m-d H:i");

            $updateUser = "UPDATE overunder SET
							otstatus = 'approved',
							approver = '$user',
							apptimedate = '$timedate'
							WHERE ovid = " . $updateid;
            mysqli_query($HRconnect, $updateUser);

            header("location:approvals.php?ot=ot&m=2");
        }
    }
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../images/logoo.png">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">


    <style type="text/css">
        @page {
            size: portrait
        }

        body {
            page-break-before: avoid;


        }


        @media print {


            .table td {
                background-color: transparent !important;
            }

            .table th {
                background-color: transparent !important;
            }
        }
    </style>

    <style>
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
                text-align: center;
            }

            table td::before {
                /*
			* aria-label has no advantage, it won't be read inside a table
			content: attr(aria-label);
			*/
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }

        .exportExcel {
            background-color: #f2f2f2;
            border-style: solid;
            border-color: #a1a1a1;
            border-radius: 5px;
            border-width: 1px;
            color: white;
            padding: 3px 10px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            color: black;
            cursor: pointer;
            bottom: 0;
        }
    </style>

    <style>
        @import url(https://fonts.googleapis.com/css?family=Dosis:300,400);



        /* effect-shine */
        a.effect-shine:hover {
            -webkit-mask-image: linear-gradient(-75deg, rgba(0, 0, 0, .6) 30%, #000 50%, rgba(0, 0, 0, .6) 70%);
            -webkit-mask-size: 200%;
            animation: shine 2s infinite;
        }

        @-webkit-keyframes shine {
            from {
                -webkit-mask-position: 150%;
            }

            to {
                -webkit-mask-position: -50%;
            }
        }
    </style>

    <style>
        input.largerCheckbox {
            width: 18px;
            height: 18px;
        }

        input[type=checkbox]+label {
            color: #ccc;
            font-style: italic;
        }

        input[type=checkbox]:checked+label {
            color: #0000FF;
            font-style: normal;
        }
    </style>

</head>

<body id="body">

    <!-- Logout Modal -->
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
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home.php">
                <div class="sidebar-brand-icon">
                    <img src="../images/logoo.png" width="40" height="45">
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../home.php?branch=<?php echo $_SESSION['useridd']; ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <?php if ($userlevel != 'staff') {
            ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Information
                </div>

                <!-- Nav Item - Pages Collapse Menu -->

                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Employee</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header"> Record-Keeping</h6>
                            <a class="collapse-item" href="../employeelist.php?active=active">Employee List</a>
                            <a class="collapse-item" href="../viewsched.php?current=current">Cut-Off Schedule</a>
                        </div>
                    </div>
                </li>
                <?php if ($empno != '4451') {
                ?>
                    <!-- Nav Item - Utilities Collapse Menu -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                            aria-expanded="true" aria-controls="collapseUtilities">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>Filed Documents</span>
                        </a>
                        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                            data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Documents-Keeping</h6>
                                <a class="collapse-item" href="../overtime.php?pending=pending">Filed Overtime</a>
                                <a class="collapse-item" href="../obp.php?pendingut=pendingut">Filed OBP</a>
                                <a class="collapse-item" href="../leave.php?pending=pending">Filed Leave</a>
                                <a class="collapse-item" href="../filedconcerns.php?pending=pending">Filed Concern</a>
                                <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change Schedule</a>
                                <a class="collapse-item" href="../working_dayoff.php?pending=pending">Filed Working Day Off</a>
                                <!-- <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a> -->
                            </div>
                        </div>
                    </li>
                <?php
                }
                ?>

                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Reports
                </div>

                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="../discrepancy.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Cut-off Details</span></a>
                </li>


                <!-- Divider -->
                <hr class="sidebar-divider">
            <?php
            }
            ?>
            <!--  Heading -->
            <div class="sidebar-heading">
                Others
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed d-none" href="#" data-toggle="collapse" data-target="#collapseUtilities2"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <span>Helpdesk</span>
                </a>
                <div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Support/Services</h6>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#exampleModal1">Create Ticket</a>
                        <a class="collapse-item" href="../concerns.php">View Concerns</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-sitemap" aria-hidden="true"></i>
                    <span>Systems</span>
                </a>
                <div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">MGFI System</h6>
                        <a class="collapse-item" href="#">PO/PR System</a>
                        <a class="collapse-item" href="#">ISS System</a>
                        <a class="collapse-item" href="../../video/stock_out.php?fg=fg">Ordering System</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-clock fa-chart-area"></i>
                    <span>Time-in</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp <?php echo $_SESSION['user']['username']; ?> </span>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user; ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <a class="dropdown-item d-md-none" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400 d-md-none"></i>
                                    <?php echo $user; ?>
                                </a>

                                <div class="dropdown-divider d-md-none"></div>

                                <a class="dropdown-item" href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
                                    <i class="fa fa-address-card fa-sm fa-fw mr-2 text-gray-400 "></i>
                                    Profile
                                </a>


                                <?php
                                if ($userlevel == 'master') {
                                ?>
                                    <a class="dropdown-item" href="database.php">
                                        <i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Database
                                    </a>
                                <?php
                                }
                                ?>

                                <?php
                                if ($userlevel == 'master' or $userlevel == 'ac' or $userlevel == 'admin') {
                                ?>
                                    <a class="dropdown-item" href="activitylogs.php">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Activity Logs
                                    </a>
                                <?php
                                }
                                ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">



                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                        <div class="mb-3">
                            <h4 class="mb-0">Pending - Concern</h4>
                            <div class="small">
                                <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                                .<?php echo date('F d, Y - h:i:s A'); ?>
                            </div>
                        </div>

                        <span>
                            <select class="custom-select custom-select-sm" onchange="location = this.value;">
                                <option value="approvals.php?ot=ot">PENDING OT</option>
                                <option value="approvals.php?obp=obp">PENDING OBP</option>
                                <option value="approvals.php?vl=vl">PENDING LEAVE</option>
                                <option selected="selected" value="approvalsconcern.php">PENDING CONCERN</option>
                                <option value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
                                <option value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>
                            </select>
                        </span>
                    </div>
                    <div>
                        <a href="../filedconcerns.php?view=summary" target="_blank" hidden> <button target="_blank" class="btn btn-primary" hidden> Click to view the Summary of DTR Concerns</button></a>
                    </div>


                    <!--PENDING DTR CONCERNS-->
                    <?php if ($userlevel == 'master' or $userlevel == 'admin' or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 1844 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 88 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302) { ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                                <?php if ($userlevel == 'master') { ?>
                                    <h6 class="m-0 font-weight-bold text-primary">System Error Concerns</h6>
                                <?php } ?>

                                <?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
                                    <h6 class="m-0 font-weight-bold text-primary">Manager's Filed DTR Concerns</h6>
                                <?php } ?>

                                <?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
                                    <h6 class="m-0 font-weight-bold text-primary">Head's Filed DTR Concerns</h6>
                                <?php } ?>
                            </div>
                            <div class="card-body">
                                <form method='post' action=''>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover text-uppercase table-sm" id="example2" width="100%" cellspacing="0">
                                            <thead>
                                                <tr class="bg-gray-200">
                                                    <th>
                                                        <center>Department</center>
                                                    </th>
                                                    <th>
                                                        <center>ID</center>
                                                    </th>
                                                    <th>
                                                        <center>Fullname</center>
                                                    </th>
                                                    <th>
                                                        <center>Date</center>
                                                    </th>
                                                    <th>
                                                        <center>Concern</center>
                                                    </th>
                                                    <th>
                                                        <center>Type of Error</center>
                                                    </th>
                                                    <th>
                                                        <center>Reason</center>
                                                    </th>
                                                    <th>
                                                        <center>Status</center>
                                                    </th>
                                                    <th>
                                                        <center></center>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $emergency = 'Emergency time out';
                                                $FPError = 'Fingerprint problem';
                                                $BrokenOT = 'File broken sched overtime';
                                                $forgot1 = 'Forgot to click no break';
                                                $forgot2 = 'Failure/Forgot to click broken schedule';
                                                $forgot3 = 'Failure/Forgot to time in or time out';
                                                $forgot4 = 'Failure/Forgot to break in or break out';
                                                $wrong = 'Wrong filing of OBP';
                                                $timeInterval = 'Not following break out and break in interval';
                                                $removeLogs = 'Remove time inputs';
                                                $cancel1 = 'Wrong filing of overtime';
                                                $cancel2 = 'Wrong filing of leave';

                                                if ($userlevel == 'master') {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND errortype = 'System Error'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 1) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6472,6619,2525) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 2) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE empno in(5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472) AND status = 'Pending' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 4) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(107) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 4378) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(1348,1964,6082,2957,4349) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 1331) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(819,109,76,71,167,3183) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 1073) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid in (3,168) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) OR empno = 1844 AND empno != 1073 AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 4298) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid in (171,172) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 3178) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid in (170) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 2684) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid in (166,165,232) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 3071) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(2203,2264) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2')  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 76) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(37,53,45,69,124,2720) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 37 || $_SESSION['empno'] == 53 || $_SESSION['empno'] == 45 || $_SESSION['empno'] == 69 || $_SESSION['empno'] == 124 || $_SESSION['empno'] == 2720) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'SOUTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 109) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(63,88,97,170) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 63 || $_SESSION['empno'] == 88 || $_SESSION['empno'] == 97 || $_SESSION['empno'] == 170) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 819) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(38,112,254,302,4484,1562,4709,204) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 38 || $_SESSION['empno'] == 112 || $_SESSION['empno'] == 254 || $_SESSION['empno'] == 302) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'NORTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 71) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158,4209) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 5928) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(3167,1075,5928,884) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 3235) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(159) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 3336) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(401,3780,4814) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 2221) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(1262) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 24) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(819,109,76,71,167) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else if ($_SESSION['empno'] == 1844) {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND empno in(2485) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                } else {
                                                    $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                }
                                                $query = $HRconnect->query($sql);
                                                while ($row = $query->fetch_array()) {
                                                    $department = $row['branch'];
                                                    $status = $row['status'];
                                                    $empid = $row['empno'];
                                                    $name = $row['name'];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <center><?php echo $department; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['empno']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo html_entity_decode(htmlentities($name)); ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['ConcernDate']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['concern']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['errortype']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['reason']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><?php echo $row['status']; ?></center>
                                                        </td>
                                                        <td>
                                                            <center><a href="view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>&ml=1" class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a></center>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- STAFF'FILED DT CONCERN -->
                        <!-- Page Heading -->

                        <hr>
                        <?php if ($_SESSION['empno'] == 1073) { ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <?php if ($_SESSION['empno'] == 1073) { ?>
                                        <h6 class="m-0 font-weight-bold text-primary">Staff's Filed DTR Concerns</h6>
                                    <?php } ?>
                                </div>

                                <div class="card-body">
                                    <form method='post' action=''>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-uppercase table-sm" id="example2" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr class="bg-gray-200">
                                                        <th>
                                                            <center>Department</center>
                                                        </th>
                                                        <th>
                                                            <center>ID</center>
                                                        </th>
                                                        <th>
                                                            <center>Fullname</center>
                                                        </th>
                                                        <th>
                                                            <center>Date</center>
                                                        </th>
                                                        <th>
                                                            <center>Concern</center>
                                                        </th>
                                                        <th>
                                                            <center>Type of Error</center>
                                                        </th>
                                                        <th>
                                                            <center>Reason</center>
                                                        </th>
                                                        <th>
                                                            <center>Status</center>
                                                        </th>
                                                        <th>
                                                            <center></center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $emergency = 'Emergency time out';
                                                    $FPError = 'Fingerprint problem';
                                                    $BrokenOT = 'File Broken Sched OT';
                                                    $forgot1 = 'Forgot to click Halfday';
                                                    $forgot2 = 'Forgot/Wrong inputs of broken sched';
                                                    $forgot3 = 'Forgot/Wrong time IN/OUT or break OUT/IN';
                                                    $wrong = 'Wrong format/filing of OBP';
                                                    $timeInterval = 'Not following time interval';
                                                    $removeLogs = 'Remove Time Inputs';
                                                    $cancel1 = 'Cancellation of Overtime';
                                                    $cancel2 = 'Cancellation of Leave';
                                                    if ($_SESSION['empno'] == 1073) {
                                                        $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid in (80,167,92,168,169,217,166) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) OR empno = 1844 AND empno != 1073 AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else {
                                                        $sql = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    }
                                                    $query = $HRconnect->query($sql);
                                                    while ($row = $query->fetch_array()) {
                                                        $department = $row['branch'];
                                                        $status = $row['status'];
                                                        $empid = $row['empno'];
                                                        $name = $row['name'];
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <center><?php echo $department; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['empno']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo html_entity_decode(htmlentities($name)); ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['ConcernDate']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['concern']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['errortype']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['reason']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><?php echo $row['status']; ?></center>
                                                            </td>
                                                            <td>
                                                                <center><a href="viewconcerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>&ml=1" class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a></center>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        <?php } ?>



                        <?php if ($userlevel == 'master') { ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                                    <?php if ($userlevel == 'master') { ?>
                                        <h6 class="m-0 font-weight-bold text-primary">Hardware/Persona Malfunction Concerns</h6>

                                    <?php } else { ?>
                                        <h6 class="m-0 font-weight-bold text-primary" hidden>Filed Broken Schedule Overtime</h6>
                                    <?php } ?>

                                </div>
                                <div class="card-body">
                                    <form method='post' action=''>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover text-uppercase table-sm" id="example" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr class="bg-gray-200">
                                                        <th>
                                                            <center>Department</center>
                                                        </th>
                                                        <th>
                                                            <center>ID</center>
                                                        </th>
                                                        <th>
                                                            <center>Fullname</center>
                                                        </th>
                                                        <th>
                                                            <center>Date</center>
                                                        </th>
                                                        <th>
                                                            <center>Concern</center>
                                                        </th>
                                                        <th>
                                                            <center>Type of OT</center>
                                                        </th>
                                                        <th>
                                                            <center>Reason</center>
                                                        </th>
                                                        <th>
                                                            <center>Status</center>
                                                        </th>
                                                        <th>
                                                            <center></center>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>


                                                    <?php
                                                    $emergency1 = 'Emergency time out';
                                                    $FPError1 = 'Fingerprint problem';
                                                    $BrokenOT1 = 'File Broken Sched OT';

                                                    if ($userlevel == 'master') {
                                                        $sql0 = "SELECT * FROM dtr_concerns WHERE status = 'Pending' AND concern = 'Hardware/Persona Malfunction'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                        $query0 = $HRconnect->query($sql0);
                                                        while ($row0 = $query0->fetch_array()) {
                                                            $department = $row0['branch'];
                                                            $status = $row0['status'];
                                                            $empid = $row0['empno'];
                                                            $name = $row0['name'];
                                                    ?>

                                                            <tr>


                                                                <td>
                                                                    <center><?php echo $department; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['empno']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo html_entity_decode(htmlentities($name)); ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['ConcernDate']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['concern']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['ottype']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['reason']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><?php echo $row0['status']; ?></center>
                                                                </td>
                                                                <td>
                                                                    <center><a href="viewconcerns.php?dtrconcerns=<?php echo $row0['concern']; ?>&dtr=concerns&empno=<?php echo $row0['empno']; ?>&date=<?php echo $row0['ConcernDate']; ?>&ml=1" class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a></center>
                                                                </td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                    </form>
                                </div>
                            </div>
                </div>
        <?php
                        }
                    }
        ?>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->


        <?php if (@$_GET['m'] == 1) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
                    <div class="toast-header bg-success">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> OBP</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-success">Successfully Approve</b> OBP. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>


        <?php if (@$_GET['m'] == 2) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-success">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-success">Successfully Approve</b> Overtime. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 3) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-success">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Wellness Leave</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-success">Successfully Approve</b> Wellness Leave. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 4) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Wellness Leave</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">Successfully Cancel</b> Wellness Leave. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 5) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">Successfully Cancel</b> Overtime. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 6) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> OBP</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">Successfully Cancel</b> OBP. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>


        <?php if (@$_GET['m'] == 3) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-success">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-success">Successfully Approved</b> the DTR Concern. Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 4) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">Successfully Cancelled</b> the DTR Concern Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 5) { ?>
            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">Successfully Changed</b> the type of DTR Concern Thank you!
                    </div>
                </div>
            </div>

        <?php } ?>

        <?php if (@$_GET['m'] == 6) {
            echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='approvalsconcern.php'
        </script>";
        ?>

            <script>
                $(function() {
                    $(".thanks").delay(2500).fadeOut();

                });
            </script>

            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                    <div class="toast-header bg-warning">
                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
                            <small class="text-light">just now</small>
                    </div>
                    <div class="toast-body">
                        You have <b class="text-warning">You cannot Approve </b> the DTR Concern because you don't have time inputs for the selected date.
                    </div>
                </div>
            </div>

        <?php } ?>



        <!-- Footer -->
        <footer class="sticky-footer">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright © Mary Grace Foods Inc. 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->


    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>

    <!-- Script -->

    <script type="text/javascript">
        $(document).ready(function() {

            // Check/Uncheck ALl
            $('#checkAll').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update[]"]').prop('checked', true);
                } else {
                    $('input[name="update[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            // Checkbox click
            $('input[name="update[]"]').click(function() {
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            });
        });

        $(document).ready(function() {

            // Check/Uncheck ALl
            $('#checkAllh').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="updateh[]"]').prop('checked', true);
                } else {
                    $('input[name="updateh[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            // Checkbox click
            $('input[name="updateh[]"]').click(function() {
                var total_checkboxes = $('input[name="updateh[]"]').length;
                var total_checkboxes_checked = $('input[name="updateh[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAllh').prop('checked', true);
                } else {
                    $('#checkAllh').prop('checked', false);
                }
            });
        });

        $(document).ready(function() {

            // Check/Uncheck ALl
            $('#checkAll1').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update1[]"]').prop('checked', true);
                } else {
                    $('input[name="update1[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            // Checkbox click
            $('input[name="update1[]"]').click(function() {
                var total_checkboxes = $('input[name="update1[]"]').length;
                var total_checkboxes_checked = $('input[name="update1[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll1').prop('checked', true);
                } else {
                    $('#checkAll1').prop('checked', false);
                }
            });
        });

        $(document).ready(function() {

            // Check/Uncheck ALl
            $('#checkAll1h').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update1h[]"]').prop('checked', true);
                } else {
                    $('input[name="update1h[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            // Checkbox click
            $('input[name="update1h[]"]').click(function() {
                var total_checkboxes = $('input[name="update1h[]"]').length;
                var total_checkboxes_checked = $('input[name="update1h[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll1h').prop('checked', true);
                } else {
                    $('#checkAll1h').prop('checked', false);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#example').dataTable({
                stateSave: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#example2').dataTable({
                stateSave: true
            });
        });
    </script>

</body>

</html>