<!-- Begin Page Content --> <!-- Search -->
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
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];

if ($userlevel != 'staff') {

    if (isset($_GET['otapprove'])) {
        $id = $_GET["id"];
        $Employee = $_GET["empno"];
        @$datefrom = $_GET["datefrom"];
        $timedate = date("Y-m-d H:i");

        $remarks_wdo_validation = "SELECT remarks FROM `hrms`.`sched_time` WHERE empno = $Employee and datefromto = '" . $datefrom . "'";
        $query_remarks = $HRconnect->query($remarks_wdo_validation);
        $row_query_remarks = $query_remarks->fetch_array();
        if (strcasecmp($row_query_remarks['remarks'], 'RD') == 0) {
            if ($userlevel == 'ac' or $userlevel == 'admin' or $userlevel == 'master') {
                $update1 = " UPDATE working_dayoff 
            SET wdostatus = 'approved',
            apptimedate = '$timedate',
            approver = '$user'
            WHERE wodID = '$id'";

                // LOGS
                $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
            VALUES ('" . $empno . "','Approved " . $user . " " . $Employee . " (Schedule: " . @$datefrom . ") - Working Dayoff', 'Successfully Saved', '" . $timedate . "');";
                $HRconnect->query($sql_insert_log);
                $HRconnect->query($update1);
            } else {
                $update1 = " UPDATE working_dayoff
                        SET wdostatus = 'pending2',
                        p_apptimedate = '$timedate',
                        p_approver = '$user'
                        WHERE wodID = '$id'";
                // LOGS
                $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
                VALUES ('" . $empno . "','Partially Approved " . $user . " " . $Employee . " (Schedule: " . @$datefrom . ") - Working Dayoff', 'Successfully Saved', '" . $timedate . "');";
                $HRconnect->query($sql_insert_log);
            }


            // REDIRECTING
            if ($userlevel == 'mod') {
                $HRconnect->query($update1);

                header("location:../working_dayoff.php?pending=pending&m=2");
            } else if (
                $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2
                or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073
            ) {
                $HRconnect->query($update1);

                header("location:approvals.php?wdo=wdo&m=8");

            } else if (
                $userlevel == 'ac' and ($_SESSION['empno'] != 271
                    and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 24 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 3071
                    and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                    and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229
                    and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 5585 and $_SESSION['empno'] != 107)
            ) {
                $HRconnect->query($update1);

                header("location:approvals.php?wdo=wdo&m=8");
            } else if (
                $_SESSION['empno'] == 271 or $_SESSION['empno'] == 71
                or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336
                or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027
                or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 5584
                or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
                or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
                or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
            ) {

                $HRconnect->query($update1);

                header("location:../working_dayoff.php?pending=pending&m=2");

            } else if (
                $_SESSION['empno'] == 271 or $_SESSION['empno'] == 71
                or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336
                or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027
                or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 5584
                or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
                or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
                or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
            ) {


                $HRconnect->query($update1);

                header("location:../working_dayoff.php?pending=pending&m=2");
            }
        } else {
            // REDIRECTING not eqaul to wdo loede 
            $update1 = " UPDATE working_dayoff
                        SET wdostatus = 'pending2',
                        p_apptimedate = '$timedate',
                        p_approver = '$user'
                        WHERE wodID = '$id'";
            // LOGS
            $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
            VALUES ('" . $empno . "','Partially Approved " . $user . " " . $Employee . " (Schedule: " . @$datefrom . ") - Working Dayoff', 'Successfully Saved', '" . $timedate . "');";
            $HRconnect->query($sql_insert_log);
            if ($userlevel == 'mod') {

                $HRconnect->query($update1);

                header("location:../working_dayoff.php?pending=pending&m=3");
            } else if (
                $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2
                or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073
            ) {

                $HRconnect->query($update1);

                header("location:approvals.php?wdo=wdo&m=11");
            } else if (
                $_SESSION['empno'] != 271 or $userlevel == 'ac'
                and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 24 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 3071
                and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229
                and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 5585 and $_SESSION['empno'] != 107
            ) {


                $HRconnect->query($update1);

                header("location:approvals.php?wdo=wdo&m=11");
            } else if (
                $_SESSION['empno'] == 271 or $_SESSION['empno'] == 71
                or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336
                or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027
                or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 5584
                or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
                or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
                or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
            ) {


                $HRconnect->query($update1);

                header("location:../working_dayoff.php?pending=pending&m=3");
            }
        }
    }


    if (isset($_GET['otcancel'])) {
        $id = $_GET["id"];

        @$Employee = $_GET["empno"];
        @$type = $_GET["type"];
        @$datefrom = $_GET["datefrom"];
        @$timedate = date("Y-m-d H:i");

        $update1 = " UPDATE working_dayoff 
			SET wdostatus = 'cancelled',
             apptimedate = '$timedate',
            approver = '$user'
            WHERE wodID = $id";

        if (
            $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2
            or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073
        ) {
            $HRconnect->query($update1);


            header("location:../pdf//approvals.php?wdo=wdo&m=7");
        }
        if (
            $_SESSION['empno'] != 71 or $_SESSION['empno'] != 24
            or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336
            or $_SESSION['empno'] != 3111 or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027
            or $_SESSION['empno'] != 885 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 5356 or $_SESSION['empno'] != 5584
            or $_SESSION['empno'] != 5361 or $_SESSION['empno'] != 3178 or $_SESSION['empno'] != 5515 or $_SESSION['empno'] != 5452
            or $_SESSION['empno'] != 4811 or $_SESSION['empno'] != 2684 or $_SESSION['empno'] != 884
            or $_SESSION['empno'] != 107
        ) {
            $HRconnect->query($update1);


            header("location:../pdf/approvals.php?wdo=wdo&m=7");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 71
            or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336
            or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027
            or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 5584
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {
            $HRconnect->query($update1);


            header("location:../working_dayoff.php?pending=pending&m=5");
        }
        if ($userlevel == 'mod') {

            $HRconnect->query($update1);

            header("location:../working_dayoff.php?pending=pending&m=5");
        }
    }
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>

        <meta charset="uft-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

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
                    text-align: right;
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
        </style>

    </head>

    <body id="page-top" class="sidebar-toggled">

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
                                    <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change
                                        Schedule</a>
                                    <a class="collapse-item" href="../working_dayoff.php?pending=pending">Filed Working Day Off</a>
                                    <!--    <a class="collapse-item" href="#" >Additional</a>
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
                            <a class="collapse-item" href="" data-toggle="modal" data-target="#exampleModal1">Create
                                Ticket</a>
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
                                    <span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp
                                        <?php echo $_SESSION['user']['username']; ?>
                                    </span>
                                </a>
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                        <?php echo $user; ?>
                                    </span>
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

                                    <a class="dropdown-item"
                                        href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
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
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
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

                        <?php
                        if (isset($_GET["wdo"]) == "wdo") {

                            $id = $_GET['id'];

                            $sql = "SELECT wdo.wodID, ui.empno, ui.name, ui.userid, ui.branch,
                                wdo.datefrom as wdo_datefrom, wdo.working_timein, wdo.working_timeout, 
                                wdo.working_breakout, wdo.working_breakin, wdo.ottype, wdo.wdostatus, wdo.wdo_reason, wdo.working_hours,
                                st.schedto, st.schedfrom, st.datefromto, st.break
                                FROM user_info ui
                                JOIN working_dayoff wdo ON ui.empno = wdo.empno
                                JOIN sched_time st ON ui.empno = st.empno
                                WHERE wdo.wodID = $id AND wdo.datefrom = st.datefromto";

                            $query = $HRconnect->query($sql);
                            $row = $query->fetch_array();

                            // $timeinss = strtotime($row['schedfrom']);
                            // $timeinin = date('H:i', $timeinss);
                            // echo $timeinin;
                            $ottype2 = $row['ottype'];
                            $time1 = trim($row['working_timeout']);
                            $time2 = strtotime($row['schedto']);

                            $datefrom = $row["wdo_datefrom"];
                            $date_added = $datefrom;

                            $time_in = trim($row["working_timein"]);
                            $breakout = trim($row["working_breakout"]);
                            $breakin = trim($row["working_breakin"]);
                            $time_out = trim($row["working_timeout"]);

                            if ($breakout != "No Break" && $breakin != "No Break") {
                                if ((strtotime($time_in) > strtotime($breakout)) || (strtotime($time_in) > strtotime($breakin))) {
                                    $date_added = strtotime("+1 day", strtotime($datefrom));
                                    $date_added = date("Y-m-d", $date_added);
                                }
                            } else if (strtotime($time_in) > strtotime($time_out)) {
                                $date_added = strtotime("+1 day", strtotime($datefrom));
                                $date_added = date("Y-m-d", $date_added);
                            }

                            $time_in_mod = $datefrom . " " . trim($row["working_timein"]);
                            $breakout_mod = $date_added . " " . trim($row["working_breakout"]);
                            $breakin_mod = $date_added . " " . trim($row["working_breakin"]);
                            $time_out_mod = $date_added . " " . trim($row["working_timeout"]);

                            // ACTUAL HOUR CALCULATION
                            // $diffbreaks = abs(strtotime($breakin_mod) - strtotime($breakout_mod))/60;
                            // $hourbreaks = abs(floor($diffbreaks/60));       
                    
                            $diff = abs(strtotime($time_out_mod) - strtotime($time_in_mod)) / 60;
                            $hour = abs(round($diff / 60, 2) - $row["break"]);
                            ?>
                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                                <div class="mb-3">

                                    <h4 class="mb-0">Working Day Off - Details</h4>
                                    <div class="small">
                                        <span class="fw-500 text-primary">
                                            <?php echo date('l'); ?>
                                        </span>
                                        <?php echo date('F d, Y - h:i:s A'); ?>
                                    </div>
                                </div>
                            </div>

                            <form class="user" method="GET">

                                <div class="row justify-content-center">
                                    <div class="col-xl-6 col-lg-12 col-md-9">
                                        <div class="card o-hidden border-0 shadow-lg my-2">
                                            <div class="card-body p-0">
                                                <!-- Nested Row within Card Body -->
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="p-4">
                                                            <div class="form-group">
                                                                <input type="text"
                                                                    class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                                    value="<?php echo $row['name']; ?>" style="font-size:100%"
                                                                    readonly />
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <input type="text"
                                                                        class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                                        name="empno" value="<?php echo $row['empno']; ?>"
                                                                        style="font-size:100%" readonly />
                                                                </div>


                                                                <div class="col-sm-6 text-center">
                                                                    <input type="text"
                                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                                        id="Branch" value="<?php echo $row['branch']; ?>"
                                                                        style="font-size:100%" readonly />
                                                                </div>
                                                            </div>

                                                            <hr>

                                                            <div class="form-group text-center text-uppercase">
                                                                <div class="form-group">

                                                                    <label>Working Day Off Date</label>

                                                                    <input type="text" name="datefrom"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo $row['wdo_datefrom']; ?> "
                                                                        style="font-size:100%" readonly />
                                                                    <input type="text" hidden name="id"
                                                                        value="<?php echo $id ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                    <label><small class="text-uppercase">Time-In</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo $time_in; ?>" name="fromtime"
                                                                        readonly />
                                                                </div>
                                                                <div class="col-sm-6 text-center">
                                                                    <label><small
                                                                            class="text-uppercase">Time-Out</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo $time_out; ?>" readonly />
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                    <label><small class="text-uppercase">Actual Rendered
                                                                            WDO</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo $hour . " Hrs"; ?>" name="fromtime"
                                                                        readonly />
                                                                </div>
                                                                <div class="col-sm-6 text-center">
                                                                    <label><small class="text-uppercase">Filed WDO
                                                                            Hours</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value=" <?php echo $row['working_hours'] . " Hrs"; ?>"
                                                                        name="totime" readonly />
                                                                </div>
                                                            </div>
                                                            <div class="form-group text-center">
                                                                <label>Reason Or Purpose</label>
                                                                
                                                                <textarea type="text" style="height:60px;" maxlength="50"
                                                                    type="date"
                                                                    class="form-control bg-gray-100 text-center text-uppercase"
                                                                    id="date" readonly><?php echo $row['wdo_reason']; ?></textarea>
                                                                    
                                                            </div>


                                                            <?php
                                                            $remarks_wdo_validation = "SELECT remarks FROM `hrms`.`sched_time` WHERE empno = " . $row['empno'] . " and datefromto = '" . $row['wdo_datefrom'] . "'";
                                                            $query_remarks = $HRconnect->query($remarks_wdo_validation);
                                                            $row_query_remarks = $query_remarks->fetch_array();
                                                            if (strcasecmp(strtolower($row_query_remarks['remarks']), 'rd') !== 0) {
                                                                ?>
                                                                <div class="alert alert-danger d-none d-sm-block text-center"
                                                                    role="alert">
                                                                    <small>You cannot approve this Working Day Off, please check
                                                                        your employee schedule. Thank you!</small>
                                                                </div>
                                                                <?php
                                                            } else {
                                                                ?>
                                                                <input type="submit" name="otapprove"
                                                                    class="btn btn-success btn-user btn-block bg-gradient-success"
                                                                    value="Approved"
                                                                    onclick="return confirm('Are you sure you want to Approved this WDO?');">
                                                                <?php
                                                            }
                                                            ?>
                                                            <input type="submit" name="otcancel"
                                                                class="btn btn-danger btn-user btn-block bg-gradient-danger"
                                                                value="Cancel"
                                                                onclick="return confirm('Are you sure you want to Cancel out this WDO');">
                            </form>

                            <hr>
                            <div class="text-center">
                                <?php
                                if (
                                    $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 1348 and $_SESSION['empno'] != 271 or $userlevel == 'ac'
                                    and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 3071
                                    and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                                    and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229
                                    and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 5585 and $_SESSION['empno'] != 107
                                ) {
                                    ?>
                                    <a class="small float-right" href="approvals.php?wdo=wdo">Back <i class="fa fa-angle-right"
                                            aria-hidden="true"></i></a>
                                    <?php
                                } else {
                                    ?>
                                    <a class="small float-right" href="../working_dayoff.php?pending=pending">Back <i
                                            class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    <?php
                                }
                                ?>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>
            </div>
            <?php
                        }
                        ?>

        </div>
        <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

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
                        <a class="btn btn-primary" href="../logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>


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

    </body>

    </html>
<?php } ?>