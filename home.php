<?php
include ("../hrms/Function/Database_Connection.php");
include ("../Ticketing/functions.php");
include ("../hrms/Function/hrms_home.php");
// select userlevel
$select_userlevel = "SELECT userlevel FROM `hrms`.`user_info` WHERE empno = {$_SESSION['empno']}";
$query = $HRconnect->query($select_userlevel);
$row = $query->fetch_array();

$_SESSION["userlevel"] = $row["userlevel"];

if ($userlevel == 'master') {
    ?>
    <!-- <input type="file" accept="image/*;capture=camera"> -->
<?php } ?>
<!DOCTYPE html>
<style type="text/css">
    .box-shadow {
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .badge {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px 10px;
        border-radius: 50%;
        background-color: red;
        color: white;
    }

    .badge1 {
        position: static;
        top: -10px;
        right: -10px;
        padding: 1px 5px;
        border-radius: 20%;
        background-color: red;
        color: white;
    }

    .text-small {
        font-size: .8rem
    }
</style>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Ajax Timesheet -->
    <script src="js/ajax-report-dashboard.js"></script>
    <style>
        .btn-hover:hover {
            color: #007BFF;
        }

        .hover-list:hover {
            background: #F8F9FA;
        }

        .example {
            width: 100%;
            height: 412px;
            overflow-y: scroll;
            /* Add the ability to scroll */
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .example::-webkit-scrollbar {
            display: ;
        }

        /* width */
        .example::-webkit-scrollbar {
            width: 10px;
        }

        /* Track */
        .example::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        .example::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        *::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .example {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
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

</head>

<body id="page-top" class="sidebar-toggled">

    <?php
    // QUERY TO GET THE PENDING CUT OFF DATE USING LEFT JOIN TO ACCESS OTHER INFO
    $getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
    ON si.empno = ui.empno
    WHERE si.status = 'Pending' AND ui.empno = $empno;";
    $querybuilder = $HRconnect->query($getDateSQL);
    $rowCutOff = $querybuilder->fetch_array();

    $cut_off1 = $rowCutOff['datefrom'];
    $cut_off2 = $rowCutOff['dateto'];
    include ("navigation.php");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <h4 class="mb-0">Dashboard</h4>
                <h6 class="m-0">Cut-off Details (<?php echo $cut_off1 . " to " . $cut_off2; ?>)</h6>
                <div class="small">
                    <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                    .<?php echo date('F d, Y - h:i:s A'); ?>
                </div>
            </div>

            <div class="btn-group mb-2">
                <a href="holiday.php" type="button" class="btn border-0 btn-sm btn-outline-primary">
                    <span><i class="far fa-calendar-alt"></i></span>
                    &nbsp <span class="text"> Holidays</span>
                </a>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <?php
            if ($userlevel == 'master' or $userlevel == 'admin' or $empno == '1233' or $empno == '5583' or $empno == '2165' or $empno == '4072' or $empno == '3332') {
                ?>
                <!-- Total Employee -->
                <div class="col-xl-3 col-md-6 mb-4">

                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Total Active Employee
                                    </div>

                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">

                                                <?php if ($userlevel == 'admin' or $userlevel == 'master' or $userlevel == 'ac' or $userlevel == 'mod') {

                                                    if ($userid == '') {
                                                        echo $Activeall;
                                                    } else {
                                                        echo $ActiveSingle;
                                                    }
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Newly Hired Employee -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="recentlyemployee.php?ho=ho" data-toggle="tooltip" data-placement="top"
                                            title="Click to view detailed breakdown"> Newly Hired Employee
                                            (<?php echo date("M", strtotime("-1 month", strtotime(date("Y/m/d")))) . "-" . date("M"); ?>)
                                        </a>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $NewlyHired; ?>

                                    </div>

                                </div>
                                <div class="col-auto">
                                    <span class="badge">
                                        <?php
                                        $query32 = "SELECT COUNT(*) FROM user_info where approval in('pending','pending2')";
                                        $result32 = mysqli_query($HRconnect, $query32) or die(mysqli_error($HRconnect));
                                        while ($row32 = mysqli_fetch_array($result32)) {
                                            echo "$row32[0]";
                                        }
                                        ?>
                                    </span>
                                    <i class="fa fa-user-plus fa-2x text-gray-300"></i>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- Total Branch -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a class="text-info" href="branch.php?bcafe=bcafe" data-toggle="tooltip"
                                            data-placement="top" title="Click to view detailed breakdown">Total Branch</a>
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php
                                        echo $TotalBranch;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-store fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Rendered Hours (Per Cut-off) -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Total Rendered Hours
                                    </div>
                                    <div class="h5 mb-0text-gray-800">
                                        <p class="m-0 font-weight-bold" id="total-rendered-hours">Fetching Data...</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>


        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-lg-6 mb-2">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Employment Status</h6>
                    </div>
                    <div class="example card-body">
                        <h6>Total Employee
                            <span class="float-right font-weight-bold">
                                <?php if ($userid == '') {

                                    echo $TotalEmployeeAdmin;
                                } else {

                                    echo $TotalEmployeeStaff;
                                } ?>

                                <span>
                        </h6>
                        <hr>


                        <h6>Inactive Employee
                            <span class="float-right font-weight-bold">
                                <?php if ($userid == '') {

                                    echo $Inactiveall;
                                } else {

                                    echo $InactiveSingle;
                                }
                                ?>

                                <span>
                        </h6>
                        <hr>

                        <h6>Resigned Employee
                            <span class="float-right font-weight-bold">
                                <?php if ($userid == '') {

                                    echo $Resignall;
                                } else {

                                    echo $ResignSingle;
                                } ?>

                                <span>
                        </h6>
                        <hr>

                        <h6>Pin Code Employee
                            <span class="float-right font-weight-bold">
                                <?php if ($userid == '') {

                                    echo $pincodeall;
                                } else {

                                    echo $pincodeSingle;
                                } ?>

                                <span>
                        </h6>
                        <hr>

                        <h6 class="m-0 font-weight-bold text-primary">Rank & File</h6>
                        <hr>

                        <h6>Department Heads <span class="small float-right font-weight-bold">
                                No Data Available
                                <span></h6>
                        <hr>

                        <h6>Supervisory Employee<span class="small float-right font-weight-bold">
                                No Data Available
                                <span></h6>
                        <hr>

                        <h6>Regular Employee<span class="small float-right font-weight-bold">
                                No Data Available
                            </span></h6>
                        <hr>

                        <h6>Probationary Employee<span class="small float-right font-weight-bold">
                                No Data Available
                            </span></h6>

                        <hr>

                        <h6>Contractual Employee<span class="small float-right font-weight-bold">
                                No Data Available
                            </span></h6>

                    </div>

                    <div class="card-footer bg-white">
                        <?php if ($userid == '') { ?>
                            <a class="float-right" rel="nofollow" href="pdf/viewallemp.php">View all &rarr;</a>
                        <?php } else { ?>
                            <a class="float-right" rel="nofollow" href="employeelist.php?active=active">View all &rarr;</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">

                <div class="card shadow">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

                        <input type="text" id="cutfrom" class="d-none" value=<?php echo $cut_off1; ?>>
                        <input type="text" id="cutto" class="d-none" value=<?php echo $cut_off2; ?>>
                        <h6 class="card-title m-0 font-weight-bold text-primary">Pending Filed Documents</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row">
                            <!-- pending ot obp leave -->

                            <div class="col-lg-6 col-md-12 mt-2">
                                <div class="d-flex flex-column">
                                    <!-- OT -->
                                    <a href="<?php echo (isViewable($userlevel)) ? "pdf/approvals.php?ot=ot" : "#"; ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4">
                                        <div class="row justify-content-between align-items-center">
                                            <div>
                                                <b>Overtime</b>
                                                <span class="badge">
                                                    <?php
                                                    if ($userlevel == 'master') {
                                                        echo $Totalpendingot;
                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 271) {
                                                        echo $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                        // new added by jones
                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
                                                        echo $Totalpendingot;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
                                                        echo $Totalpendingot += $Totalpendingoth;


                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                                        echo $Totalpendingot;


                                                    }
                                                    if ($_SESSION['empno'] == 107 or $_SESSION['empno'] == 2221) {
                                                        echo $Totalpendingot;

                                                    }
                                                    if (
                                                        $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1964 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 4647
                                                        or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 5928 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
                                                        or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 3178
                                                        or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515
                                                        or $_SESSION['empno'] == 5452 or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684
                                                        or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 957 or $_SESSION['empno'] == 1075 or $_SESSION['empno'] == 5834 or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 6207
                                                        /* HR */ or $_SESSION['empno'] == 1233 or $_SESSION['empno'] == 5583 or $_SESSION['empno'] == 2165
                                                        or $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                                    ) {
                                                        echo $Totalpendingot;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 76) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97) {
                                                        echo $Totalpendingot += $Totalpendingoth;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
                                                        echo $Totalpendingot;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819) {
                                                        echo $Totalpendingot += $Totalpendingoth;




                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 204) {
                                                        echo $Totalpendingoth;
                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                                        echo $Totalpendingot;
                                                    }
                                                    if ($userlevel == 'mod') {
                                                        echo $Totalpendingot;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>

                                    <!-- OBP -->
                                    <a href="<?php echo (isViewable($userlevel)) ? "pdf/approvals.php?obp=obp" : "#"; ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4 mt-3">
                                        <div class="row justify-content-between align-items-center">
                                            <div>
                                                <b>Official Business Permit</b>
                                                <span class="badge">
                                                    <?php
                                                    if ($userlevel == 'master') {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 271) {
                                                        echo $Totalpendingobph;

                                                    }
                                                    if ($_SESSION['empno'] == 1331) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 24) { //added by jones
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($_SESSION['empno'] == 107 or $_SESSION['empno'] == 2221) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if (
                                                        $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1964 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 4647
                                                        or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 5928 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
                                                        or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 3178
                                                        or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515
                                                        or $_SESSION['empno'] == 5452 or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684
                                                        or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 957 or $_SESSION['empno'] == 1075 or $_SESSION['empno'] == 5834 or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 6207
                                                        /* HR */ or $_SESSION['empno'] == 1233 or $_SESSION['empno'] == 5583 or $_SESSION['empno'] == 2165
                                                        or $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                                    ) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 76) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819) {
                                                        echo $Totalpendingobp += $Totalpendingobph;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 204) {
                                                        echo $Totalpendingobp;

                                                    }
                                                    if ($userlevel == 'mod') {
                                                        echo $Totalpendingobp;

                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>

                                    <!-- LEAVE -->
                                    <?php
                                    // $url = $userlevel == 'master' ? "pdf/approvals.php?vl=vl" : "#";
                                    $url = isViewable($userlevel) ? "pdf/approvals.php?vl=vl" : "#";
                                    ?>
                                    <a href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4 mt-3">
                                        <div class="row justify-content-between align-items-center">
                                            <div>
                                                <b>Leave</b>
                                                <span class="badge">
                                                    <?php
                                                    if ($userlevel == 'master') {
                                                        echo $Totalpendingvl;
                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
                                                        echo $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1348) {
                                                        echo $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {  //new added jones
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($_SESSION['empno'] == 107 or $_SESSION['empno'] == 2221) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if (
                                                        $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1964
                                                        or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 4647 or $_SESSION['empno'] == 5928 or $_SESSION['empno'] == 3336
                                                        or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027
                                                        or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515
                                                        or $_SESSION['empno'] == 5452 or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684
                                                        or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 957 or $_SESSION['empno'] == 1075 or $_SESSION['empno'] == 5834
                                                        or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 6207 /* HR */ or $_SESSION['empno'] == 1233 or $_SESSION['empno'] == 5583
                                                        or $_SESSION['empno'] == 2165 or $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                                    ) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 76) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819) {
                                                        echo $Totalpendingvl += $Totalpendingvlh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 204) {
                                                        echo $Totalpendingvl;

                                                    }
                                                    if ($userlevel == 'mod') {
                                                        echo $Totalpendingvl;

                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12 mt-2">
                                <div class="d-flex flex-column">
                                    <!-- Concern -->
                                    <a href="<?php echo (isViewable($userlevel)) ? "pdf/approvalsconcern.php" : "#"; ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4">
                                        <div class="row justify-content-between align-items-center">
                                            <div>
                                                <b>Concern</b>
                                                <span class="badge">
                                                    <?php
                                                    // QUERY TO GET THE PENDING CUT OFF DATE USING LEFT JOIN TO ACCESS OTHER INFO
                                                    $getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
                                                                ON si.empno = ui.empno
                                                                WHERE si.status = 'Pending' AND ui.empno = $empno;";
                                                    $querybuilder = $HRconnect->query($getDateSQL);
                                                    $rowCutOff = $querybuilder->fetch_array();
                                                    $datestart = $rowCutOff['datefrom'];
                                                    $dateend = $rowCutOff['dateto'];

                                                    $emergency = 'Emergency time out';
                                                    $FPError = 'Fingerprint problem';
                                                    $BrokenOT = 'File Broken Sched OT';
                                                    $forgot1 = 'Forgot to click no break';
                                                    $forgot2 = 'Forgot/Wrong inputs of broken sched';
                                                    $forgot3 = 'Forgot/Wrong time IN/OUT or break OUT/IN';
                                                    $wrong = 'Wrong format/filing of OBP';
                                                    $timeInterval = 'Not following time interval';
                                                    $removeLogs = 'Remove Time Inputs';
                                                    $cancel1 = 'Cancellation of Overtime';
                                                    $cancel2 = 'Cancellation of Leave';

                                                    if ($userlevel == 'master') {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('Hardware/Persona Malfunction','Sync/Network error','Wrong Computations' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if (
                                                        $userlevel == 'ac' and $_SESSION['empno'] != 819 and $_SESSION['empno'] != 4378 and $_SESSION['empno'] != 1331 and $_SESSION['empno'] != 24 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 1844 and $_SESSION['empno'] != 1073
                                                        and $_SESSION['empno'] != 4298 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5361 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5515
                                                        and $_SESSION['empno'] != 5452 and $_SESSION['empno'] != 4811 and $_SESSION['empno'] != 2684
                                                        and $_SESSION['empno'] != 3071 and $_SESSION['empno'] != 76 and $_SESSION['empno'] != 109 and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 5928 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 37
                                                        and $_SESSION['empno'] != 53 and $_SESSION['empno'] != 45 and $_SESSION['empno'] != 69 and $_SESSION['empno'] != 124 and $_SESSION['empno'] != 2720 and $_SESSION['empno'] != 63 and $_SESSION['empno'] != 88 and $_SESSION['empno'] != 97
                                                        and $_SESSION['empno'] != 170 and $_SESSION['empno'] != 38 and $_SESSION['empno'] != 112 and $_SESSION['empno'] != 254 and $_SESSION['empno'] != 302 and $_SESSION['empno'] != 460 and $_SESSION['empno'] != 2094 and $_SESSION['empno'] != 159
                                                        and $_SESSION['empno'] != 4484 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 5834 and $_SESSION['empno'] != 5834 and $_SESSION['empno'] != 3183 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 6207
                                                    ) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1','$BrokenOT', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2') AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 1) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,5834,6207,6619) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 2) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE empno in(5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,5834,6207,6619) AND status = 'Pending' AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 4) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(107) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval','$BrokenOT', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 4378) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(1348,1964,6082,2957) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 1331) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(38,63,76,97,109,124,819,45,71,1404,3183) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";


                                                    } else if ($_SESSION['empno'] == 24) { //jones added
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(241) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";


                                                    } else if ($_SESSION['empno'] == 1073) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (3,80,167,92,168,169,217,166) AND concern IN ('$emergency', '$FPError', '$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) OR empno = 1844 AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 4298) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (171,172) AND concern IN ('$emergency', '$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 3178) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (170) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 2684) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (166,165,232) AND concern IN ('$emergency', '$FPError','$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 3071) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(2203,2264) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$BrokenOT','$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 76) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(37,53,45,69,124,2720) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3','$BrokenOT', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 37 || $_SESSION['empno'] == 53 || $_SESSION['empno'] == 45 || $_SESSION['empno'] == 69 || $_SESSION['empno'] == 124 || $_SESSION['empno'] == 2720) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'SOUTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 109) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(63,88,97,170) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 63 || $_SESSION['empno'] == 88 || $_SESSION['empno'] == 97 || $_SESSION['empno'] == 170) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 819) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(38,112,254,302,4484,1562,4709) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 38 || $_SESSION['empno'] == 112 || $_SESSION['empno'] == 254 || $_SESSION['empno'] == 302 || $_SESSION['empno'] == 460 || $_SESSION['empno'] == 2094 || $_SESSION['empno'] == 4484) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError','$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'NORTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 71) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158) AND concern IN ('$emergency','$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 5928) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(3167,1075,5928,884) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 5752) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(159) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                                    } else if ($_SESSION['empno'] == 3336) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(401,3780,4814,4888) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 3111) {

                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(469,4408,5132,5184,5611) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 2221) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 1844) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(2485) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 3183) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(167,3974,4294,4388,5158,44,166,5973) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 5584) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 6207) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 885) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 6538) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 5834) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else if ($_SESSION['empno'] == 204) {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND department = 'NORTH' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    } else {
                                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1','$BrokenOT', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND userlevel = 'staff' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                    }

                                                    $query0 = $HRconnect->query($sql0);
                                                    $row0 = $query0->fetch_array();

                                                    $totalconcerns = $row0['COUNT(*)'];


                                                    echo $totalconcerns;
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>

                                    <!-- WDO -->
                                    <a href="<?php echo (isViewable($userlevel)) ? "pdf/approvals.php?wdo=wdo" : "#"; ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4 mt-3">
                                        <div class="row justify-content-between align-items-center">
                                            <div class="">
                                                <b>Working Day Off</b>
                                                <span class="badge">
                                                    <?php
                                                    // From pdf hrms 
                                                    if ($userlevel == 'master') {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 271) {
                                                        echo $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
                                                        // echo $Totalpendingwdo +=  $Totalpendingwdoh;													
                                                        echo $Totalpendingwdo;
                                                        // new added by jones
                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($_SESSION['empno'] == 107 or $_SESSION['empno'] == 2221) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if (
                                                        $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1964 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 4647
                                                        or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 5928 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
                                                        or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 3178
                                                        or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515
                                                        or $_SESSION['empno'] == 5452 or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684
                                                        or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 957 or $_SESSION['empno'] == 1075 or $_SESSION['empno'] == 5834
                                                        or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 6207 /* HR */ or $_SESSION['empno'] == 1233 or $_SESSION['empno'] == 5583 or $_SESSION['empno'] == 2165
                                                        or $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                                    ) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 76) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819) {
                                                        echo $Totalpendingwdo += $Totalpendingwdoh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                                        echo $Totalpendingwdo;
                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 204) {
                                                        echo $Totalpendingwdo;

                                                    }
                                                    if ($userlevel == 'mod') {
                                                        echo $Totalpendingwdo;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>

                                    <!-- Change Schedule -->
                                    <a href="<?php echo (isViewable($userlevel)) ? "pdf/approvals.php?cs=cs" : "#"; ?>"
                                        class="btn border-left-primary rounded box-shadow w-100 h-100 py-2 px-4 mt-3">
                                        <div class="row justify-content-between align-items-center">
                                            <div>
                                                <b>Change Schedule</b>
                                                <span class="badge">
                                                    <?php
                                                    // From pdf hrms 
                                                    if ($userlevel == 'master') {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 271) {
                                                        echo $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                        // new added by jones
                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
                                                        echo $Totalpendingcs;

                                                        // new added by jones
                                                    }
                                                    if ($userlevel == 'mod' and $_SESSION['empno'] == 4292) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($_SESSION['empno'] == 107 or $_SESSION['empno'] == 2221) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if (
                                                        $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1964 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619 or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 4647
                                                        or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 5928 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
                                                        or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 3178
                                                        or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515
                                                        or $_SESSION['empno'] == 5452 or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684
                                                        or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 957 or $_SESSION['empno'] == 1075 or $_SESSION['empno'] == 5834
                                                        or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 6207 /* HR */ or $_SESSION['empno'] == 1233 or $_SESSION['empno'] == 5583 or $_SESSION['empno'] == 2165
                                                        or $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                                    ) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 76) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819) {
                                                        echo $Totalpendingcs += $Totalpendingcsh;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'ac' and $_SESSION['empno'] == 204) {
                                                        echo $Totalpendingcs;

                                                    }
                                                    if ($userlevel == 'mod') {
                                                        echo $Totalpendingcs;
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card shadow d-flex" style="height: 231px">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="card-title m-0 font-weight-bold text-primary">Pending Time inputs</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <a href="filedpincode.php?pending=pending" class="text-decoration-none">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Using Pincode
                                    <span>
                                        <?php
                                        if ($userlevel == 'master') {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $userlevel == 'admin'
                                            and $_SESSION['empno'] == 1
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'admin'
                                            and $_SESSION['empno'] == 2
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'admin'
                                            and $_SESSION['empno'] == 4
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 4378
                                        ) {
                                            echo $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'admin'
                                            and $_SESSION['empno'] == 1348
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 1331
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 24
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 1073
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 3071
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $_SESSION['empno'] == 107
                                            or $_SESSION['empno'] == 2221
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $_SESSION['empno'] == 271
                                            or $userlevel == 'ac'
                                            and $_SESSION['empno'] == 71
                                            or $_SESSION['empno'] == 1964
                                            or $_SESSION['empno'] == 3294
                                            or $_SESSION['empno'] == 6619
                                            or $_SESSION['empno'] == 6082
                                            or $_SESSION['empno'] == 4647
                                            or $_SESSION['empno'] == 3183
                                            or $_SESSION['empno'] == 5928
                                            or $_SESSION['empno'] == 3336
                                            or $_SESSION['empno'] == 3111
                                            or $_SESSION['empno'] == 159
                                            or $_SESSION['empno'] == 5752
                                            or $_SESSION['empno'] == 3027
                                            or $_SESSION['empno'] == 3178
                                            or $_SESSION['empno'] == 5361
                                            or $_SESSION['empno'] == 3178
                                            or $_SESSION['empno'] == 5515
                                            or $_SESSION['empno'] == 5452
                                            or $_SESSION['empno'] == 4811
                                            or $_SESSION['empno'] == 2684
                                            or $_SESSION['empno'] == 5975
                                            or $_SESSION['empno'] == 885
                                            or $_SESSION['empno'] == 957
                                            or $_SESSION['empno'] == 1075 /* HR */
                                            or $_SESSION['empno'] == 5834
                                            or $_SESSION['empno'] == 1233
                                            or $_SESSION['empno'] == 5583
                                            or $_SESSION['empno'] == 2165
                                            or $_SESSION['empno'] == 4072
                                            or $_SESSION['empno'] == 3332
                                            or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 189 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 3685 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 69 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 40 or $_SESSION['empno'] == 20 /* END */
                                        ) {
                                            echo $TotalpendingPincode;
                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 76
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 109
                                            or $_SESSION['empno'] == 97
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 63
                                            or $_SESSION['empno'] == 170
                                            or $_SESSION['empno'] == 88
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 38
                                            or $_SESSION['empno'] == 819
                                        ) {
                                            echo $TotalpendingPincode += $TotalpendingPincode_2;

                                        }
                                        if (
                                            $userlevel == 'ac'
                                            and $_SESSION['empno'] == 254
                                            or $_SESSION['empno'] == 302
                                            or $_SESSION['empno'] == 112
                                            or $_SESSION['empno'] == 2094
                                            or $_SESSION['empno'] == 460
                                        ) {
                                            echo $TotalpendingPincode;

                                        }
                                        if ($userlevel == 'mod') {
                                            echo $TotalpendingPincode;

                                        }
                                        ?>
                                    </span>
                                </li>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>

            <?php
            function isViewable($userlevel)
            {
                // return ($userlevel == 'master' or $userlevel == 'admin') and $_SESSION['empno'] != 1348 and $_SESSION['empno'] != 271 /*or $userlevel == 'ac'*/and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6082 and $_SESSION['empno'] != 957 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111
                //     and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027 and $_SESSION['empno'] != 107 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5361
                //     and $_SESSION['empno'] != 2684 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 6207 and $_SESSION['empno'] != 5585
                //     /* HR */    and $_SESSION['empno'] != 1233 and $_SESSION['empno'] != 5583 and $_SESSION['empno'] != 2165 and $_SESSION['empno'] != 4072; /* END */
            
                $employeeAccess = [1, 2, 4, 1348, 1331, 76, 45, 38, 63, 69, 819, 97, 109, 124, 4378, 170, 112, 1073, 37, 53];
                return $userlevel == 'master' || in_array($_SESSION['empno'], $employeeAccess);
            }

            function staffViewable($empno, $HRconnect)
            {
                $select_branch = "SELECT userlevel FROM `hrms`.`user_info` WHERE empno = ? AND userlevel IN ('master','admin')";
                $query = $HRconnect->prepare($select_branch);
                $query->bind_param('i', $empno);
                $query->execute();
                $result = $query->get_result()->fetch_array();
                return isset($result);
            }
            ?>

        </div>
        <?php
        if (staffViewable($empno, $HRconnect)) {
            ?>
            <!-- <div class="col-xl-3 col-lg-3 mb-3">
                                <div class="card shadow border-left-warning">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">                            
                                        
                                    </div> 
                                </div> 
                            </div> -->
            <div class="row">
                <!-- Total Late (Per Cut-off) -->
                <div class="col-xl-4 col-md-12 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Late - Mins
                                    </div>
                                    <div class="h5 mb-0text-gray-800">
                                        <p class="m-0 font-weight-bold" id="total-late-minutes">Fetching Data...</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-hourglass-end fa-2x text-gray-300" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Late (Per Cut-off) -->
                <div class="col-xl-4 col-md-12 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Overbreak - Mins
                                    </div>
                                    <div class="h5 mb-0text-gray-800">
                                        <p class="m-0 font-weight-bold" id="total-overbreak-minutes">Fetching Data...</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-hourglass-end fa-2x text-gray-300" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Undertime (Per Cut-off) -->
                <div class="col-xl-4 col-md-12 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Undertime - Mins
                                    </div>
                                    <div class="h5 mb-0text-gray-800">
                                        <p class="m-0 font-weight-bold" id="total-undertime-hours">Fetching Data...</p>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-hourglass-half fa-2x text-gray-300" aria-hidden="true"></i>
                                    <!-- <i class="fa fa-clock "></i> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-warning h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">Overtime Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-overtime-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="overtime-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-primary h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">OBP Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-obp-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="obp-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-info h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">Leave Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-leave-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="leave-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-success h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">Concern Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-concern-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="concern-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-danger h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">Change Schedule Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-cs-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="cs-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-3 mb-3">
                    <div class="card shadow border-left-warning h-100">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="card-title m-0 font-weight-bold text-primary">Working Day Off Breakdown</h6>
                            <button class="btn btn-sm btn-hover" id="refresh-wdo-breakdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z" />
                                    <path
                                        d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group" id="wdo-breakdown-spinner">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="m-0 ml-3">Fetching Data...</p>
                                    </div>
                                </li>
                            </ul>
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

    <!-- Settings Modal-->
    <form method="POST" enctype="multipart/form-data">

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-cogs fa-fw"></i> Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="modal-body">

                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Login Name:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="loginname"
                                value="<?php echo $_SESSION['user']['loginname']; ?>" readonly>
                        </div>

                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Username:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="username"
                                value="<?php echo $_SESSION['user']['username']; ?>" readonly>
                        </div>
                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Fullname:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="username"
                                value="<?php echo $user; ?>" readonly>
                        </div>


                        <div class="form-group ">
                            <label for="recipient-name" class="col-form-label">Default/Old Password:</label>
                            <input type="password" class="form-control text-center" name="password1" maxlength="8"
                                required>
                        </div>


                        <div class="form-group ">
                            <label for="recipient-name" class="col-form-label">New Password:</label>
                            <input type="password" pattern="[0-9]*" inputmode="numeric"
                                placeholder="Please input numbers only maximum of 8 numbers" maxlength="8"
                                class="form-control text-center" id="myInput" name="password2" required>
                        </div>

                        <div class="float-right">
                            <input type="checkbox" onclick="myFunction()"> <small class="text-muted">Show
                                Password</small>
                        </div>

                        <input type="text" name="empno1" hidden value="<?php echo $_SESSION['empno']; ?>">

    </form>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update" class="btn btn-success bg-gradient-success">Update</button>
    </div>
    </div>
    </div>
    </div>

    <!-- Force change pass Modal-->
    <form method="POST" enctype="multipart/form-data">

        <div id="changepass" class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-cogs fa-fw"></i> Settings</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <div class="modal-body">

                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Login Name:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="loginname"
                                value="<?php echo $_SESSION['user']['loginname']; ?>" readonly>
                        </div>

                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Username:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="username"
                                value="<?php echo $_SESSION['user']['username']; ?>" readonly>
                        </div>
                        <div class="form-group d-none">
                            <label for="recipient-name" class="col-form-label">Fullname:</label>
                            <input type="text" class="form-control text-center" id="recipient-name" name="username"
                                value="<?php echo $user; ?>" readonly>
                        </div>


                        <div class="form-group ">
                            <label for="recipient-name" class="col-form-label">Default/Old Password:</label>
                            <input type="password" class="form-control text-center" name="password1" maxlength="8"
                                required>
                        </div>


                        <div class="form-group ">
                            <label for="recipient-name" class="col-form-label">New Password:</label>
                            <input type="password" pattern="[0-9]*" inputmode="numeric"
                                placeholder="Please input numbers only maximum of 8 numbers" maxlength="8"
                                class="form-control text-center" id="myInput" name="password2" required>
                        </div>

                        <div class="float-right">
                            <input type="checkbox" onclick="myFunction()"> <small class="text-muted">Show
                                Password</small>
                        </div>

                        <input type="text" name="empno1" hidden value="<?php echo $_SESSION['empno']; ?>">

    </form>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update" class="btn btn-success bg-gradient-success">Update</button>
    </div>
    </div>
    </div>
    </div>

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

    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>

    <!-- <script>
    $(document).ready(function(){
        $("#changepass").modal('show');
    });
    </script> -->

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

            var x = document.getElementById("myInput1");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

    <script type="text/javascript">
        // object literal holding data for option elements
        var Select_List_Data = {

            'choices': { // name of associated select box

                <?php
                $sql11 = "SELECT DISTINCT mothercafe FROM category ";
                $query11 = $TKconnect->query($sql11);
                while ($row11 = $query11->fetch_array()) {

                    $mothercafe = $row11['mothercafe'];
                    ?>

                                                            <?php echo $row11['mothercafe']; ?>: {
                        text: ["", <?php

                        $sql2 = "SELECT * FROM category where mothercafe = '$mothercafe'";
                        $query2 = $TKconnect->query($sql2);
                        while ($row2 = $query2->fetch_array()) {


                            echo "'" . $row2['catname'] . "',";
                        }
                        ?>],

                        value: ["", <?php

                        $sql3 = "SELECT * FROM category where mothercafe = '$mothercafe'";
                        $query3 = $TKconnect->query($sql3);
                        while ($row3 = $query3->fetch_array()) {


                            echo "'" . $row3['catid'] . "',";
                        }
                        ?>]

                    },


                <?php } ?>
            }


        };


        var Select_List_Data2 = {

            'choices2': { // name of associated select box

                <?php
                $sql1 = "SELECT * FROM category ";
                $query1 = $TKconnect->query($sql1);
                while ($row1 = $query1->fetch_array()) {
                    $catid = $row1['catid'];
                    ?>

                                                            <?php echo $row1['catid']; ?>: {
                        text: ["", <?php

                        $sql2 = "SELECT * FROM subcategory where catid = '$catid'";
                        $query2 = $TKconnect->query($sql2);
                        while ($row2 = $query2->fetch_array()) {


                            echo "'" . $row2['subname'] . "',";
                        }
                        ?>],

                        value: ["", <?php

                        $sql3 = "SELECT * FROM subcategory where catid = '$catid'";
                        $query3 = $TKconnect->query($sql3);
                        while ($row3 = $query3->fetch_array()) {


                            echo "'" . $row3['subid'] . "',";
                        }
                        ?>]

                    },


                <?php } ?>
            }
        };

        // removes all option elements in select box 
        // removeGrp (optional) boolean to remove optgroups
        function removeAllOptions(sel, removeGrp) {
            var len, groups, par;
            if (removeGrp) {
                groups = sel.getElementsByTagName('optgroup');
                len = groups.length;
                for (var i = len; i; i--) {
                    sel.removeChild(groups[i - 1]);
                }
            }

            len = sel.options.length;
            for (var i = len; i; i--) {
                par = sel.options[i - 1].parentNode;
                par.removeChild(sel.options[i - 1]);
            }
        }

        function removeAllOptions2(sel2, removeGrp) {
            var len, groups, par;
            if (removeGrp) {
                groups = sel2.getElementsByTagName('optgroup');
                len = groups.length;
                for (var i = len; i; i--) {
                    sel2.removeChild(groups[i - 1]);
                }
            }

            len = sel2.options.length;
            for (var i = len; i; i--) {
                par = sel2.options[i - 1].parentNode;
                par.removeChild(sel2.options[i - 1]);
            }
        }

        function appendDataToSelect(sel, obj) {
            var f = document.createDocumentFragment();
            var labels = [],
                group, opts;

            function addOptions(obj) {
                var f = document.createDocumentFragment();
                var o;

                for (var i = 0, len = obj.text.length; i < len; i++) {
                    o = document.createElement('option');
                    o.appendChild(document.createTextNode(obj.text[i]));

                    if (obj.value) {
                        o.value = obj.value[i];
                    }

                    f.appendChild(o);
                }
                return f;
            }

            if (obj.text) {
                opts = addOptions(obj);
                f.appendChild(opts);
            } else {
                for (var prop in obj) {
                    if (obj.hasOwnProperty(prop)) {
                        labels.push(prop);
                    }
                }

                for (var i = 0, len = labels.length; i < len; i++) {
                    group = document.createElement('optgroup');
                    group.label = labels[i];
                    f.appendChild(group);
                    opts = addOptions(obj[labels[i]]);
                    group.appendChild(opts);
                }
            }
            sel.appendChild(f);
        }


        function appendDataToSelect2(sel2, obj2) {
            var f = document.createDocumentFragment();
            var labels = [],
                group, opts;

            function addOptions(obj2) {
                var f = document.createDocumentFragment();
                var o;

                for (var i = 0, len = obj2.text.length; i < len; i++) {
                    o = document.createElement('option');
                    o.appendChild(document.createTextNode(obj2.text[i]));

                    if (obj2.value) {
                        o.value = obj2.value[i];
                    }

                    f.appendChild(o);
                }
                return f;
            }

            if (obj2.text) {
                opts = addOptions(obj2);
                f.appendChild(opts);
            } else {
                for (var prop in obj2) {
                    if (obj2.hasOwnProperty(prop)) {
                        labels.push(prop);
                    }
                }

                for (var i = 0, len = labels.length; i < len; i++) {
                    group = document.createElement('optgroup');
                    group.label = labels[i];
                    f.appendChild(group);
                    opts = addOptions(obj2[labels[i]]);
                    group.appendChild(opts);
                }
            }
            sel2.appendChild(f);
        }


        // anonymous function assigned to onchange event of controlling select box
        document.forms['demoForm'].elements['dept'].onchange = function (e) {
            // name of associated select box
            var relName = 'choices';
            var relName2 = 'choices2';

            // reference to associated select box 
            var relList = this.form.elements[relName];
            var relList2 = this.form.elements[relName2];

            // get data from object literal based on selection in controlling select box (this.value)
            var obj = Select_List_Data[relName][this.value];
            var obj2 = Select_List_Data2[relName2][this.value];

            // remove current option elements
            removeAllOptions(relList, true);
            removeAllOptions2(relList2, true);

            // call function to add optgroup/option elements
            // pass reference to associated select box and data for new options
            appendDataToSelect(relList, obj);
            appendDataToSelect2(relList2, obj2);
        };

        document.forms['demoForm'].elements['choices'].onchange = function (e) {
            // name of associated select box
            var relName2 = 'choices2';

            // reference to associated select box 
            var relList2 = this.form.elements[relName2];

            // get data from object literal based on selection in controlling select box (this.value)
            var obj2 = Select_List_Data2[relName2][this.value];

            // remove current option elements
            removeAllOptions2(relList2, true);

            // call function to add optgroup/option elements
            // pass reference to associated select box and data for new options
            appendDataToSelect2(relList2, obj2);
        };


        // populate associated select box as page loads
        (function () { // immediate function to avoid globals

            var form = document.forms['demoForm'];

            // reference to controlling select box
            var sel = form.elements['dept'];
            var sel2 = form.elements['choices'];
            sel.selectedIndex = 0;
            sel2.selectedIndex = 0;

            // name of associated select box
            var relName = 'choices';
            var relName2 = 'choices2';

            // reference to associated select box
            var rel = form.elements[relName];
            var rel2 = form.elements[relName2];

            // get data for associated select box passing its name
            // and value of selected in controlling select box
            var data = Select_List_Data[relName][sel.value];
            var data2 = Select_List_Data2[relName2][sel2.value];

            // add options to associated select box
            appendDataToSelect(rel, data);
            appendDataToSelect2(rel2, data2);

        }());

        (function () { // immediate function to avoid globals

            var form = document.forms['demoForm'];

            // reference to controlling select box
            var sel2 = form.elements['choices'];
            sel2.selectedIndex = 0;

            // name of associated select box
            var relName2 = 'choices2';
            // reference to associated select box
            var rel2 = form.elements[relName2];

            // get data for associated select box passing its name
            // and value of selected in controlling select box
            var data2 = Select_List_Data2[relName2][sel2.value];

            // add options to associated select box
            appendDataToSelect(rel2, data2);

        }());
    </script>


</body>

</html>