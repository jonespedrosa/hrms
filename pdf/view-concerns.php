<?php
// Database connections and session start
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();

// Redirect to login if not logged in
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Prepare and execute query to fetch information of the logged-in user
$loggedInEmpno = $_SESSION['empno'];
$sqlUserInfo = "SELECT empno, userid, name, userlevel, branch, position FROM user_info WHERE empno = ?";
$stmtUserInfo = $HRconnect->prepare($sqlUserInfo);
$stmtUserInfo->bind_param("s", $loggedInEmpno);
$stmtUserInfo->execute();
$queryUserInfo = $stmtUserInfo->get_result();
$row_userInfo = $queryUserInfo->fetch_assoc();
$branch = $_SESSION['userid'];
$name = $row_userInfo['name'];
$empnoWhoLogin = $row_userInfo['empno'];
$userlevel = $row_userInfo['userlevel'];
$departmentBranch = $row_userInfo['branch'];
$position = $row_userInfo['position'];

// Fetch data from URL
$empno = isset($_GET['empno']) ? $_GET['empno'] : '';
$dtrconcerns = isset($_GET['dtrconcerns']) ? $_GET['dtrconcerns'] : '';
$dateConcerns = isset($_GET['date']) ? $_GET['date'] : '';

// Prepare and execute query to fetch user database information
$username = $_SESSION['user']['username'];
$sqlUserdB = "SELECT username, areatype, userid FROM user WHERE username = ?";
$stmtUserdB = $ORconnect->prepare($sqlUserdB);
$stmtUserdB->bind_param("s", $username);
$stmtUserdB->execute();
$queryUserdB = $stmtUserdB->get_result();
$row_userdB = $queryUserdB->fetch_assoc();
$areatype = $row_userdB['areatype'];
$userid = $_SESSION['userid'];

// Prepare and execute query to fetch DTR concerns data based on empno, ConcernDate, and concern
$sqlDTRConcerns = "SELECT empno, name, userlevel, branch, userid, area, ConcernDate, concern, errortype, ottype, othours, vltype, actualIN, actualbOUT, actualbIN, actualOUT, newIN, newbOUT, newbIN, newOUT, attachment1, attachment2, status, reason, approver
FROM dtr_concerns
WHERE empno = ? AND ConcernDate = ? AND concern = ?";
$stmtDTRConcerns = $HRconnect->prepare($sqlDTRConcerns);
$stmtDTRConcerns->bind_param("sss", $empno, $dateConcerns, $dtrconcerns); // Bind all three parameters
$stmtDTRConcerns->execute();
$queryDTRConcerns = $stmtDTRConcerns->get_result();
$row_dtrConcerns = $queryDTRConcerns->fetch_assoc();

if ($row_dtrConcerns) {
    // Extract values
    $concernName = $row_dtrConcerns['name'];
    $ConcernDate = $row_dtrConcerns['ConcernDate'];
    $concernType = $row_dtrConcerns['concern'];
    $errorType = $row_dtrConcerns['errortype'];
    $vltype = $row_dtrConcerns['vltype'];
    $actualIN = $row_dtrConcerns['actualIN'];
    $actualbOUT = $row_dtrConcerns['actualbOUT'];
    $actualbIN = $row_dtrConcerns['actualbIN'];
    $actualOUT = $row_dtrConcerns['actualOUT'];
    $newIN = $row_dtrConcerns['newIN'];
    $newbOUT = $row_dtrConcerns['newbOUT'];
    $newbIN = $row_dtrConcerns['newbIN'];
    $newOUT = $row_dtrConcerns['newOUT'];
    $reason = $row_dtrConcerns['reason'];
    $attachment1 = $row_dtrConcerns['attachment1'];
    $attachment2 = $row_dtrConcerns['attachment2'];

    // Prepare and execute query to fetch data from sched_time, overunder, vlform, and user_info
    $sqlSchedTime = "
                    SELECT
                        sched_time.schedto,
                        sched_time.M_timein,
                        sched_time.M_timeout,
                        sched_time.A_timein,
                        sched_time.A_timeout,
                        sched_time.timein4,
                        sched_time.timeout4,
                        overunder.otdatefrom,
                        overunder.ottype,
                        overunder.othours,
                        vlform.vldatefrom,
                        vlform.vlhours,
                        vlform.timedate,
                        user_info.vl
                    FROM sched_time
                    LEFT JOIN overunder
                        ON sched_time.empno = overunder.empno
                        AND sched_time.datefromto = overunder.otdatefrom
                    LEFT JOIN vlform
                        ON sched_time.empno = vlform.empno
                        AND sched_time.datefromto = vlform.vldatefrom
                    LEFT JOIN user_info
                        ON sched_time.empno = user_info.empno
                    WHERE sched_time.empno = ? AND sched_time.datefromto = ? ORDER BY `vlform`.`timedate` DESC";

    $stmtSchedTime = $HRconnect->prepare($sqlSchedTime);
    $stmtSchedTime->bind_param("ss", $empno, $ConcernDate); // Bind parameters
    $stmtSchedTime->execute();
    $querySchedTime = $stmtSchedTime->get_result();
    $row_schedTime = $querySchedTime->fetch_assoc();

    if ($row_schedTime) {
        $schedto = $row_schedTime['schedto'];

        $M_timein = $row_schedTime['M_timein'];
        $M_timeout = $row_schedTime['M_timeout'];
        $A_timein = $row_schedTime['A_timein'];
        $A_timeout = $row_schedTime['A_timeout'];
        $timein4 = $row_schedTime['timein4'];
        $timeout4 = $row_schedTime['timeout4'];

        $otdatefrom = $row_schedTime['otdatefrom'];
        $ottype = $row_schedTime['ottype'];
        $othours = $row_schedTime['othours'];
        $vldatefrom = $row_schedTime['vldatefrom'];
        $vlhours = $row_schedTime['vlhours'];
        $vl = $row_schedTime['vl'];

        // You can now use these values in your logic
        // echo "schedto: " . htmlspecialchars($schedto) . "<br>";
        // echo "M_timein: " . htmlspecialchars($M_timein) . "<br>";
        // echo "M_timeout: " . htmlspecialchars($M_timeout) . "<br>";
        // echo "A_timein: " . htmlspecialchars($A_timein) . "<br>";
        // echo "A_timeout: " . htmlspecialchars($A_timeout) . "<br>";
        // echo "timein4: " . htmlspecialchars($timein4) . "<br>";
        // echo "timeout4: " . htmlspecialchars($timeout4) . "<br>";
        // echo "OT Date: " . htmlspecialchars($otdatefrom) . "<br>";
        // echo "OT Type: " . htmlspecialchars($ottype) . "<br>";
        // echo "OT Hours: " . htmlspecialchars($othours) . "<br>";
        // echo "VL Date: " . htmlspecialchars($vldatefrom) . "<br>";
        // echo "VL Hours: " . htmlspecialchars($vlhours) . "<br>";
        // echo "VL Column: " . htmlspecialchars($vl) . "<br>";

    } else {
        echo "No data found.";
    }
} else {
    echo "No records found.";
}

// echo "empno: " . htmlspecialchars($empno) . "<br>";
// echo "ConcernDate: " . htmlspecialchars($attachment1) . "<br>";

// Clean up
$stmtUserInfo->close();
$stmtUserdB->close();
$stmtDTRConcerns->close();
$stmtSchedTime->close();
$ORconnect->close();
$HRconnect->close();
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

    <!-- Custom fonts and styles -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .time-inputs-container {
            margin-bottom: 20px;
        }

        .time-inputs-header {
            display: flex;
            background-color: #F4F5F8;
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .custom-width {
            width: 150%;
            /* Adjust as needed */
        }


        .header-item {
            flex: 1;
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }

        .time-inputs {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .time-inputs .form-control {
            flex: 1;
            margin: 0 5px;
            min-width: 150px;
        }

        .captured-inputs .form-control {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .time-inputs.proposed-inputs .form-control {
            text-align: center;
            width: 100px;
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group .form-control {
            border-radius: 0.25rem;
        }

        .input-group .btn {
            border-radius: 0 0.25rem 0.25rem 0;
            height: 38px;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .input-group .btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
            color: #fff;
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

        .swal-button-red {
            background-color: #D52B1A !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            outline: none !important;
        }

        .swal-button-red:hover {
            background-color: #D52B1A !important;
        }


        /* Default styles for larger screens */
        .responsive-container {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            /* Align the boxes to the top */
            text-align: center;
            gap: 10px;
            margin-top: 20px;
        }

        .box {
            width: 500px;
            min-height: 200px;
            /* Set a minimum height */
            display: flex;
            flex-direction: column;
            /* Ensure content inside stacks vertically */
            justify-content: flex-start;
            /* Align content to the top */
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 20px;
        }

        .content {
            width: 100%;
            text-align: right;
            /* Align text to the right */
        }

        /* Responsive styles for screens 760px or less */
        @media (max-width: 760px) {
            .responsive-container {
                flex-direction: column;
                /* Stack boxes vertically */
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
            <li class="nav-item">
                <a class="nav-link" href="../home.php?branch=<?php echo $_SESSION['userid']; ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <!-- Add dynamic PHP-based menus here -->

            <?php if ($userlevel != 'staff'): ?>
                <!-- Heading -->
                <div class="sidebar-heading">Information</div>

                <!-- Nav Item - Employee Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Employee</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Record-Keeping</h6>
                            <a class="collapse-item" href="../employeelist.php?active=active">Employee List</a>
                            <a class="collapse-item" href="../viewsched.php?current=current">Cut-Off Schedule</a>
                        </div>
                    </div>
                </li>
                <?php if ($empno != 4349): ?>
                    <!-- Nav Item - Filed Documents Collapse Menu -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                            aria-expanded="true" aria-controls="collapseUtilities">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>Filed Documents</span>
                        </a>
                        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Documents-Keeping</h6>
                                <a class="collapse-item" href="../overtime.php?pending=pending">Filed Overtime</a>
                                <a class="collapse-item" href="../obp.php?pendingut=pendingut">Filed OBP</a>
                                <?php
                                $excludedEmpnos = ['5047', '5051', '3339', '2620', '927', '5717', '4491'];
                                if (!in_array($empno, $excludedEmpnos)): ?>
                                    <a class="collapse-item" href="../leave.php?pending=pending">Filed Leave</a>
                                    <a class="collapse-item" href="../filedconcerns.php?pending=pending">Filed Concern</a>
                                <?php endif; ?>

                                <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change Schedule</a>
                                <a class="collapse-item" href="../working_dayoff.php?pending=pending">Filed Working Day Off</a>
                                <a class="collapse-item" href="../filedpincode.php?pending=pending">Filed Staff's Pincode</a>
                            </div>
                        </div>
                    </li>
                    <hr class="sidebar-divider">
                    <?php if (in_array($userlevel, ['master', 'admin']) || $branch == 'AUDIT' || $empno == '1073'): ?>
                        <!-- Heading -->
                        <div class="sidebar-heading">Reports</div>
                        <!-- Nav Item - Cut-off Details -->
                        <li class="nav-item">
                            <a class="nav-link" href="../discrepancy.php">
                                <i class="fas fa-chart-bar"></i>
                                <span>Cut-off Details</span>
                            </a>
                        </li>
                        <hr class="sidebar-divider">
                        <!-- Nav Item - Employee Portal -->
                        <li class="nav-item">
                            <a class="nav-link" href="../index.php">
                                <i class="fa fa-address-card" aria-hidden="true"></i>
                                <span>Employee Portal</span></a>
                        </li>
                        <hr class="sidebar-divider">
                        <!-- Sidebar Toggler (Sidebar) -->
                        <div class="text-center d-none d-md-inline">
                            <button class="rounded-circle border-0" id="sidebarToggle"></button>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-gray-600 small text-uppercase">
                                    <i class='fas fa-store'></i>&nbsp
                                    <?php echo $_SESSION['user']['username']; ?>
                                </span>
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $name; ?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item d-md-none" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400 d-md-none"></i>
                                    <?php echo $name; ?>
                                </a>
                                <div class="dropdown-divider d-md-none"></div>
                                <a class="dropdown-item" href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
                                    <i class="fa fa-address-card fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                                </a>
                                <?php if ($userlevel == 'master') { ?>
                                    <a class="dropdown-item" href="database.php">
                                        <i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i> Database
                                    </a>
                                <?php } ?>
                                <?php if ($userlevel == 'master' || $userlevel == 'admin') { ?>
                                    <a class="dropdown-item" href="activitylogs.php">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Activity Logs
                                    </a>
                                <?php } ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!----------------------------------------------------------------------------------------------------------------------------->
                <!-- Page Content Header Start -->
                <div class="d-sm-flex align-items-center mb-2 ml-2">
                    <h4 class="mb-0">Concern Details</h4>
                </div>
                <div id="dynamicDiv" class="container-fluid" style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                </div>
                <!-- Page Content End -->
                <!----------------------------------------------------------------------------------------------------------------------------->

            </div>
            <!-- Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Mary Grace Foods Inc. 2019</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

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

    <!-- JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom JavaScript -->

    <script>
        $(document).ready(function() {
            // Pass PHP variables to JavaScript
            var dtrconcerns = "<?php echo $dtrconcerns; ?>";
            var ConcernDate = "<?php echo $ConcernDate; ?>";
            var concernType = "<?php echo $concernType; ?>";
            var errorType = "<?php echo $errorType; ?>";
            var vltype = "<?php echo $vltype; ?>";
            var actualIN = "<?php echo $actualIN; ?>";
            var actualbOUT = "<?php echo $actualbOUT; ?>";
            var actualbIN = "<?php echo $actualbIN; ?>";
            var actualOUT = "<?php echo $actualOUT; ?>";
            var newIN = "<?php echo $newIN; ?>";
            var newbOUT = "<?php echo $newbOUT; ?>";
            var newbIN = "<?php echo $newbIN; ?>";
            var newOUT = "<?php echo $newOUT; ?>";
            var schedto = "<?php echo $schedto; ?>";
            var M_timein = "<?php echo $M_timein; ?>";
            var M_timeout = "<?php echo $M_timeout; ?>";
            var A_timein = "<?php echo $A_timein; ?>";
            var A_timeout = "<?php echo $A_timeout; ?>";
            var timein4 = "<?php echo $timein4; ?>";
            var timeout4 = "<?php echo $timeout4; ?>";
            var othours = "<?php echo $othours; ?>";
            var vlhours = "<?php echo $vlhours; ?>";
            var vl = "<?php echo $vl; ?>";
            var attachment1 = "<?php echo $attachment1; ?>";
            var attachment2 = "<?php echo $attachment2; ?>";


            // Extract time from datetime (assuming format: YYYY-MM-DD HH:mm)
            var schedto_ = schedto.substring(11, 16);
            var M_timein_ = M_timein.substring(11, 16);
            var M_timeout_ = M_timeout.substring(11, 16);
            var A_timein_ = A_timein.substring(11, 16);
            var A_timeout_ = A_timeout.substring(11, 16);
            var timein4_ = timein4.substring(11, 16);
            var timeout4_ = timeout4.substring(11, 16);
            const empno = "<?php echo htmlspecialchars($empno); ?>";
            const concernName = "<?php echo htmlspecialchars($concernName); ?>";


            // Log all variables
            console.log({
                dtrconcerns,
                ConcernDate,
                concernType,
                errorType,
                vltype,
                actualIN,
                actualbOUT,
                actualbIN,
                actualOUT,
                newIN,
                newbOUT,
                newbIN,
                newOUT,
                schedto,
                M_timein,
                M_timeout,
                A_timein,
                A_timeout,
                timein4,
                timeout4,
                othours,
                vlhours,
                vl,
                attachment1,
                attachment2,
                schedto_,
                M_timein_,
                M_timeout_,
                A_timein_,
                A_timeout_,
                timein4_,
                timeout4_,
                empno,
                concernName
            });

            // Check the value of the concerns variable
            if (dtrconcerns === "Failure/Forgot to time in or time out" || dtrconcerns === "Failure/Forgot to break in or break out" || dtrconcerns === "Failure/Forgot to click half day" || dtrconcerns === "Wrong filing of OBP" || dtrconcerns === "Not following break out and break in interval") {
                // Determine the type of concern based on dtrconcerns
                let type_concern;
                // Determine type_concern based on selectedConcern value
                if (dtrconcerns === "Failure/Forgot to time in or time out") {
                    type_concern = 1;
                } else if (dtrconcerns === "Failure/Forgot to break in or break out") {
                    type_concern = 2;
                } else if (dtrconcerns === "Failure/Forgot to click half day") {
                    type_concern = 4;
                } else if (dtrconcerns === "Wrong filing of OBP") {
                    type_concern = 7;
                } else if (dtrconcerns === "Not following break out and break in interval") {
                    type_concern = 8;
                }

                const url = `concerns-failure-forgot-time-in-out-break-out-in.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&type_of_concern=${encodeURIComponent(type_concern)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#capturedTimeIn').val(actualIN);
                    $('#capturedBreakOut').val(actualbOUT);
                    $('#capturedBreakIn').val(actualbIN);
                    $('#capturedTimeOut').val(actualOUT);
                    $('#newTimeIN').val(newIN);
                    $('#newBreakOut').val(newbOUT);
                    $('#newBreakIn').val(newbIN);
                    $('#newTimeOut').val(newOUT);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();

                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }

                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        newIN: newIN,
                                        newbOUT: newbOUT,
                                        newbIN: newbIN,
                                        newOUT: newOUT,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            } else if (dtrconcerns === "Failure/Forgot to click broken schedule") {
                // Determine the type of concern based on dtrconcerns
                let type_concern;
                // Determine type_concern based on selectedConcern value
                if (dtrconcerns === "Failure/Forgot to click broken schedule") {
                    type_concern = 3;
                }
                const url = `concerns-failure-forgot-broken-sched.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&type_of_concern=${encodeURIComponent(type_concern)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#capturedTimeIn').val(actualIN);
                    $('#capturedTimeOut').val(actualOUT);
                    $('#newTimeIN').val(newIN);
                    $('#newTimeOut').val(newOUT);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission

                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();

                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }

                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        newIN: newIN,
                                        newOUT: newOUT,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                });
            } else if (dtrconcerns === "Wrong filing of overtime") {
                // Determine the type of concern based on dtrconcerns
                let type_concern;
                // Determine type_concern based on selectedConcern value
                if (dtrconcerns === "Wrong filing of overtime") {
                    type_concern = 4;
                }
                const url = `concerns-wrong-filing-of-overtime.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&type_of_concern=${encodeURIComponent(type_concern)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    // Set the input values to the extracted time
                    $('#capturedScheduleOut').val(schedto_);
                    $('#capturedTimeOut').val(A_timeout_);
                    $('#filedOThours').val(othours);

                    // Function to convert time (HH:mm) to minutes
                    function timeToMinutes(time) {
                        var timeParts = time.split(':');
                        return parseInt(timeParts[0]) * 60 + parseInt(timeParts[1]);
                    }

                    // Convert actualOUT and schedto times to minutes
                    var actualOUTMinutes = timeToMinutes(A_timeout_);
                    var schedtoMinutes = timeToMinutes(schedto_);

                    // Calculate overtime in minutes (if actualOUT is greater than schedto)
                    var overtimeMinutes = actualOUTMinutes - schedtoMinutes;

                    // Calculate full overtime hours by only considering full 60-minute blocks
                    var overtimeHours = Math.floor(overtimeMinutes / 60); // Only count full hours

                    // Display overtime in the console log
                    if (overtimeMinutes >= 60) { // Only log overtime if there's at least 1 hour
                        console.log("Overtime: " + overtimeHours + " hour(s)");
                    } else {
                        console.log("No overtime. Worked within the scheduled time or less than 1 hour overtime.");
                    }

                    $('#overTimeCalculation').val(overtimeHours);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            } else if (dtrconcerns === "Wrong filing of leave") {

                const url = `concerns-wrong-filing-of-leave.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#currentTotalLeave').val("<?php echo $vl; ?>");
                    $('#filedLeave').val("<?php echo $vlhours; ?>");

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        vl: vl,
                                        vlhours: vlhours,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern

                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            } else if (dtrconcerns === "Remove time inputs") {
                // Determine the type of concern based on dtrconcerns
                let type_concern;
                // Determine type_concern based on selectedConcern value
                if (dtrconcerns === "Remove time inputs") {
                    type_concern = 9;
                }
                const url = `concerns-remove-time-inputs.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&type_of_concern=${encodeURIComponent(type_concern)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#capturedTimeIn').val(M_timein_);
                    $('#capturedBreakOut').val(M_timeout_);
                    $('#capturedBreakIn').val(A_timein_);
                    $('#capturedTimeOut').val(A_timeout_);
                    $('#brokenSchedTimeIn').val(timein4_);
                    $('#brokenSchedTimeOut').val(timeout4_);
                    $('#removeTimeInputs').val(vltype);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {

                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        vltype: vltype,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern

                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });

            } else if (dtrconcerns === "Time inputs did not sync" || dtrconcerns === "Misaligned time inputs" || dtrconcerns === "Persona error" || dtrconcerns === "Hardware malfunction" || dtrconcerns === "Emergency time out" || dtrconcerns === "Fingerprint problem") {

                const url = `concerns-time-inputs-not-sync.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&attachment1=${encodeURIComponent(attachment1)}&attachment2=${encodeURIComponent(attachment2)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#capturedTimeIn').val(M_timein_);
                    $('#capturedBreakOut').val(M_timeout_);
                    $('#capturedBreakIn').val(A_timein_);
                    $('#capturedTimeOut').val(A_timeout_);
                    $('#newTimeIN').val(newIN);
                    $('#newBreakOut').val(newbOUT);
                    $('#newBreakIn').val(newbIN);
                    $('#newTimeOut').val(newOUT);

                    // Use plain JavaScript to hide or show attachment2
                    var attachment2Div = document.getElementById('attachment2DivDisplay');

                    if (dtrconcerns === "Time inputs did not sync" || dtrconcerns === "Misaligned time inputs") {
                        attachment2Div.setAttribute('style', 'display: none !important;'); // Force hide
                    } else {
                        attachment2Div.setAttribute('style', 'display: block !important;'); // Force show
                    }

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {

                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        newIN: newIN,
                                        newbOUT: newbOUT,
                                        newbIN: newbIN,
                                        newOUT: newOUT,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern

                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });


            } else if (dtrconcerns === "Broken Schedule did not sync") {

                const url = `concerns-broken-schedules-did-not-sync.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}&attachment1=${encodeURIComponent(attachment1)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#capturedBrokenSchedTimeIn').val(timein4_);
                    $('#capturedBrokenSchedTimeOut').val(timeout4_);
                    $('#brokenSchedTimeIn').val(newIN);
                    $('#brokenSchedTimeOut').val(newOUT);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        newIN: newIN,
                                        newOUT: newOUT,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            } else if (dtrconcerns === "Wrong computation") {

                const url = `concerns-wrong-computation.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(ConcernDate)}`;

                // Use AJAX to load the PHP file content into #dynamicDiv with the constructed URL
                $('#dynamicDiv').load(url, function() {
                    // Once the content is loaded, set the form field values
                    $('#dateOfConcerns').val(ConcernDate);
                    $('#typeOfConcerns').val(concernType);
                    $('#typeOfError').val(errorType);
                    $('#employeeName').val("<?php echo $concernName; ?>");
                    $('#employeeNumber').val("<?php echo $empno; ?>");
                    $('#employeeBranch').val("<?php echo $departmentBranch; ?>");
                    $('#wrongComputation').val(vltype);

                    // Add click event listener for the "Approved" button
                    $(document).on('click', 'input[name="btnApproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #16A270; font-weight: bold;">Approve</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-green'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {

                                // Perform the AJAX call to update the concern
                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        vltype: vltype,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'approve' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });

                    $(document).on('click', 'input[name="btnDisapproved"]', function(event) {
                        event.preventDefault(); // Prevent the default form submission
                        // Get the value from the remarks textarea
                        const approverRemarks = $('#approverRemarks').val();
                        // Check if approverRemarks is empty or null
                        if (!approverRemarks || approverRemarks.trim() === "") {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Reason Required',
                                text: 'You must provide a reason for the concern before submitting.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                },
                            });
                            return; // Prevent form submission
                        }
                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmation',
                            html: `Do you want to <span style="color: #C92818; font-weight: bold;">Disapprove</span> this concern?`,
                            confirmButtonText: 'Yes',
                            showCloseButton: true, // Show the "X" button in the top-right corner
                            customClass: {
                                confirmButton: 'swal-button-red'
                            },
                            reverseButtons: true // Optional: reverse the order of the buttons
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Perform the AJAX call to update the concern

                                $.ajax({
                                    url: 'update-concerns.php',
                                    type: 'POST',
                                    data: {
                                        empno: empno,
                                        ConcernDate: ConcernDate,
                                        dtrconcerns: dtrconcerns, // Include the concern type
                                        approverRemarks: approverRemarks, // Pass the remarks value
                                        action: 'disapprove' // Add the action parameter
                                    },
                                    success: function(response) {
                                        // Handle success
                                        Swal.fire({
                                            position: "center",
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern has been updated successfully!',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Hide the "OK" button
                                            willClose: () => {
                                                setTimeout(() => {
                                                    window.location.href = '/hrms/filedconcerns.php?pending=pending';
                                                }, 1500);
                                            }
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Something went wrong! Please try again.',
                                        });
                                    }
                                });
                            }
                        });
                    });
                });
            }
        });
    </script>

</body>

</html>