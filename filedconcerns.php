<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
$emnum = $_SESSION['empno'];

//For approval concern: para hindi sila makapag approve.
//ito yong current cut off
$queryCutOff = "SELECT id, datefrom, dateto
            FROM sched_info
            WHERE status = 'Pending'
            AND empno = '$emnum'
            ORDER BY id ASC
            LIMIT 1;";
$queryCutOff = $HRconnect->query($queryCutOff);
$rowCutOff = $queryCutOff->fetch_array();
$datestart = $rowCutOff['datefrom'];
$dateend = $rowCutOff['dateto'];

//cut-off date start
// $datestart = '2024-09-24';
//cut-off date end
// $dateend = '2024-10-08';

//ito yong inayos ngayon na sasahorin to
//previous cut-off date start
$prevdate1 = '2024-01-09';
//previous cut-off date end
$prevdate2 = '2024-12-23';

$sqlName = "SELECT DISTINCT userid,empno, name, branch, mothercafe, department, area_type FROM user_info WHERE empno = '$emnum'";
$queryName = $HRconnect->query($sqlName);
$rowN = $queryName->fetch_array();
$motherCAFE = $rowN['userid'];

if (empty($_SESSION['user'])) {
    header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];
if (isset($_GET['branch'])) {
    @$_SESSION['useridd'] = $_GET['branch'];

    Header('Location: filedconcerns.php?pending=pending');
}

if ($userlevel != 'staff') {

    $a = array(date("Y-m-30") => date("Y-m-30"), date("Y-m-31") => date("Y-m-31"), date("Y-m-01") => date("Y-m-01"), date("Y-m-02") => date("Y-m-02"), date("Y-m-03") => date("Y-m-03"), date("Y-m-04") => date("Y-m-04"), date("Y-m-05") => date("Y-m-05"), date("Y-m-06") => date("Y-m-06"), date("Y-m-07") => date("Y-m-07"), date("Y-m-08") => date("Y-m-08"), date("Y-m-09") => date("Y-m-09"), date("Y-m-10") => date("Y-m-10"), date("Y-m-11") => date("Y-m-11"), date("Y-m-12") => date("Y-m-12"), date("Y-m-13") => date("Y-m-13"), date("Y-m-14") => date("Y-m-14"));

    if (array_key_exists(date("Y-m-d"), $a)) {
        $newdate1 = date("Y-m-24", strtotime("-1 months"));
        $newdate2 = date("Y-m-08");
    } else {
        $newdate1 = date("Y-m-09");
        $newdate2 = date("Y-m-23");
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <title>Mary Grace Foods Inc.</title>
        <link rel="icon" href="images/logoo.png">

        <!-- Custom fonts for this template -->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Custom styles for this page -->
        <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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

        <?php include("navigation.php"); ?>
        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div class="mb-3">
                    <h4 class="mb-0">Concerns</h4>
                    <div class="small">
                        <span class="fw-500 text-primary">
                            <?php echo date('l'); ?>
                        </span>
                        .
                        <?php echo date('F d, Y - h:i:s A'); ?>
                    </div>
                </div>
            </div>
            <?php
            // VIEW DETAILS OF DTR CONCERNS OF EMPLOYEE

            if (isset($_GET["summary"]) == "okay") {
                $empno = $_GET['empno'];
                $sqlN = "SELECT DISTINCT `name`,`empno` FROM dtr_concerns WHERE empno = '$empno' ";
                $queryN = $HRconnect->query($sqlN);
                $rowN = $queryN->fetch_array();

                $name = $rowN['name'];

                if (isset($_GET['track'])) {
                    $prevdate1 = $_GET['datestart'];
                    $prevdate2 = $_GET['dateend'];
                }

            ?>
                <!-- DataTales Example -->
                <form method="GET">
                    <div class="form-group row">

                        <div class="col-sm-2 text-center">
                            <label>Cut-Off Date From</label>
                            <input type="hidden" name="summary" value="okay">
                            <input type="hidden" name="adminview" value="ok">
                            <input type="hidden" name="empno" value="<?php echo $rowN['empno']; ?>">
                            <input type="hidden" name="track" value="1">
                            <input type="date" id="#datePicker" class="form-control text-center" name="datestart"
                                placeholder="Insert Date" value="<?php echo $prevdate1; ?>" autocomplete="off"
                                onkeypress="return false;" />
                        </div>

                        <div class="col-sm-2 text-center">
                            <label>Cut-Off Date To</label>
                            <input type="date" id="#datePicker1" class="form-control text-center" name="dateend"
                                placeholder="Insert Date" value="<?php echo $prevdate2; ?>" autocomplete="off"
                                onkeypress="return false;" />
                        </div>

                        <div class="col-sm-3 text-center d-none d-sm-inline-block">
                            <label class="invisible">.</label>
                            <div class="form-group row">
                                <div class="col-xs-6">

                                    <?php
                                    if ($emnum == 1233 || $emnum == 2165 || $emnum == 4072) {
                                    ?>
                                        <input type="hidden" name="spc" value="">
                                        <input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center"
                                            type="submit" name='submit1' value="Submit"
                                            formaction="filedconcerns.php?spc=&summary=okay&adminview=ok">
                                </div> &nbsp
                                <div class="col-xs-6">
                                    <input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center"
                                        type="submit" value="Clear"
                                        formaction="filedconcerns.php?spc=&summary=okay&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>">
                                <?php
                                    } else if ($emnum == 1348 || $emnum == 271 || $emnum == 2957) {
                                ?>
                                    <input type="hidden" name="spc2" value="">
                                    <input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center"
                                        type="submit" value="Submit"
                                        formaction="filedconcerns.php?spc2=&summary=okay&adminview=ok">
                                </div> &nbsp
                                <div class="col-xs-6">
                                    <input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center"
                                        type="submit" value="Clear"
                                        formaction="filedconcerns.php?spc2=&summary=okay&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>">
                                <?php
                                    } else {
                                ?>
                                    <input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center"
                                        type="submit" value="Submit"
                                        formaction="filedconcerns.php?spc=&summary=okay&adminview=ok">
                                </div> &nbsp
                                <div class="col-xs-6">
                                    <input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center"
                                        type="submit" value="Clear"
                                        formaction="filedconcerns.php?summary=okay&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>">
                                <?php
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <?php
                        if (isset($_GET['all'])) {
                        ?>
                            <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Back to View
                                    Summary</b></a>
                        <?php
                        } else if (isset($_GET['spc'])) {
                        ?>
                            <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Back to View
                                    Summary</b></a>
                        <?php
                        } else if (isset($_GET['spc2'])) {
                        ?>
                            <a class="effect-shine nav-link active" href="filedconcerns.php?sum=admin"><b>Back to View
                                    Summary</b></a>
                        <?php
                        } else {
                        ?>
                            <a class="effect-shine nav-link active" href="filedconcerns.php?view=summary"><b>Back to View
                                    Summary</b></a>
                        <?php
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <p class="effect-shine nav-link"><b>EMPLOYEE ID:
                                <?php echo $rowN['empno']; ?> <br>
                                <?php echo " " . " NAME: " . html_entity_decode(htmlentities($name)); ?>
                            </b></p>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center>Attachment1</center>
                                        </th>
                                        <th>
                                            <center>Attachment2</center>
                                        </th>
                                        <th>
                                            <center>Status</center>
                                        </th>
                                        <th>
                                            <center>Approver</center>
                                        </th>
                                        <th>
                                            <center>Remarks</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>

                                    <?php
                                    if (isset($_GET['adminview'])) {
                                        $sql = "SELECT * FROM dtr_concerns WHERE empno = '$empno' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a href="pdf/<?php echo $row['attachment1']; ?>" target="_blank"> Click here to
                                                            view attachment </a></center>
                                                </td>
                                                <td>
                                                    <center><a href="pdf/<?php echo $row['attachment2']; ?>" target="_blank"> Click here to
                                                            view attachment</a></center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['status']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php
                                    } else {
                                    ?>
                                        <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                            Choose Cafe/Department to View DTR Concerns Filled</a>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $sql = "SELECT * FROM dtr_concerns WHERE empno = '$empno' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <?php
                                                if ($row['concern'] == 'Wrong filing of overtime' || $row['concern'] == 'Wrong filing of leave' || $row['concern'] == 'Wrong computation' || $row['concern'] == 'File broken sched overtime') {
                                                ?>
                                                    <td>
                                                        <center>Attachment is <br> not needed</center>
                                                    </td>
                                                    <td>
                                                        <center>Attachment is <br> not needed</center>
                                                    </td>
                                                <?php
                                                } else if ($row['concern'] == 'Remove time inputs') {
                                                ?>
                                                    <td>
                                                        <center><a href="pdf/<?php echo $row['attachment1']; ?>" target="_blank"> Click here
                                                                to view attachment </a></center>
                                                    </td>
                                                    <td>
                                                        <center>Attachment is <br> not needed</center>
                                                    </td>
                                                <?php
                                                } else if ($row['concern'] == 'Time inputs did not sync' || $row['concern'] == 'Misaligned time inputs' || $row['concern'] == 'Broken Schedule did not sync' || $row['concern'] == 'Persona error' || $row['concern'] == 'Wrong computation') {
                                                ?>
                                                    <td>
                                                        <center><a href="pdf/<?php echo $row['attachment1']; ?>" target="_blank"> Click here
                                                                to view attachment </a></center>
                                                    </td>
                                                    <td>
                                                        <center>Attachment is <br> not needed</center>
                                                    </td>
                                                <?php
                                                } else {
                                                ?>
                                                    <td>
                                                        <center><a href="pdf/<?php echo $row['attachment1']; ?>" target="_blank"> Click here
                                                                to view attachment </a></center>
                                                    </td>
                                                    <td>
                                                        <center><a href="pdf/<?php echo $row['attachment2']; ?>" target="_blank"> Click here
                                                                to view attachment</a></center>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                                <td>
                                                    <center>
                                                        <?php echo $row['status']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS HR TEAM GENNIE EMILY AND MAAM TIN

            if (
                isset($_GET["pending"]) == "pending" and $emnum == 2165 || $emnum == 4072 || $emnum == 1233
                /*Audit*/ || $emnum == 3612 || $emnum == 3736 || $emnum == 1533 || $emnum == 4206 || $emnum == 3770
                || $emnum == 4001 || $emnum == 3080 || $emnum == 3819 || $emnum == 5359 || $emnum == 4073 || $emnum == 3156
                || $emnum == 3160 || $emnum == 1509 || $_SESSION['empno'] == 5263 || $_SESSION['empno'] == 5430
                || $_SESSION['empno'] == 4892 || $_SESSION['empno'] == 3337 || $_SESSION['empno'] == 6436 || $_SESSION['empno'] == 6209
                || $_SESSION['empno'] == 6244 || $_SESSION['empno'] == 6245 || $_SESSION['empno'] == 6438  /*End*/
            ) {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Leave</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {

                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';

                                        $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `userid` = '$userid' AND `ConcernDate` BETWEEN '$prevdate1' AND '$prevdate2' GROUP BY `empno` DESC";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];

                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concerncount']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="filedconcerns.php?spc=&dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php

                                        }
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MAAM CHONA MAAM RESSIE SIR CARL

            if (isset($_GET["sum"]) == "admin") {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?sum=admin"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Leave</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {

                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';


                                        if ($emnum == 2957) {
                                            $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `ConcernDate` BETWEEN '$prevdate1' AND '$prevdate2' GROUP BY `empno` DESC";
                                        } else {
                                            $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `userid` = '$userid'  AND `ConcernDate` BETWEEN '$prevdate1' AND '$prevdate2' GROUP BY `empno` DESC";
                                        }


                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                            $id = $row['id'];


                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concerncount']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="filedconcerns.php?spc2=&dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php

                                        }
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MASTER - PENDING

            if (isset($_GET["pending"]) == "pending" and $userlevel == 'master') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?error=system"><b>System Error</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?other=hardware"><b>Hardware/Device
                                Malfunction</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>
                <br>
                <form method="get">
                    <label>
                        <bold>Select by Area: </bold>
                    </label>
                    <select name="area">
                        <option>
                            <?php
                            if (isset($_GET['area'])) {
                                echo $_GET['area'];
                            } else {
                                echo "";
                            }

                            ?>
                        </option>
                        <?php
                        $sqlarea = "SELECT DISTINCT `area_type` FROM user_info ";
                        $queryarea = $HRconnect->query($sqlarea);
                        while ($rowarea = $queryarea->fetch_array()) {
                        ?>
                            <option>
                                <?php echo $rowarea['area_type']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="pending" value="pending">
                    <input type="submit"
                        style="background-color: #008CBA; color: white; border-radius: 7px; border:none; width: 3%;" name=""
                        value="Go!" formaction="filedconcerns.php?pending=pending">
                </form>
                <br>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (isset($_GET['area'])) {
                                        $area = $_GET['area'];
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';
                                        $forgot1 = 'Failure/Forgot to click half day';
                                        $forgot2 = 'Failure/Forgot to click broken schedule';
                                        $forgot3 = 'Failure/Forgot to time in or time out';
                                        $forgot4 = 'Failure/Forgot to break in or break out';
                                        $wrong = 'Wrong filing of OBP';
                                        $timeInterval = 'Not following break out and break in interval';
                                        $removeLogs = 'Remove time inputs';
                                        $cancel1 = 'Wrong filing of overtime';
                                        $cancel2 = 'Wrong filing of leave';
                                        $sql = "SELECT DISTINCT `name`,`id`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel in ('admin','ac','mod','staff','master') AND area = '$area' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <?php

                                        if (@$_SESSION['useridd'] != null) {
                                            $emergency = 'Emergency time out';
                                            $FPError = 'Fingerprint problem';
                                            $BrokenOT = 'File broken sched overtime';
                                            $forgot1 = 'Failure/Forgot to click half day';
                                            $forgot2 = 'Failure/Forgot to click broken schedule';
                                            $forgot3 = 'Failure/Forgot to time in or time out';
                                            $forgot4 = 'Failure/Forgot to break in or break out';
                                            $wrong = 'Wrong filing of OBP';
                                            $timeInterval = 'Not following break out and break in interval';
                                            $removeLogs = 'Remove time inputs';
                                            $cancel1 = 'Wrong filing of overtime';
                                            $cancel2 = 'Wrong filing of leave';
                                            if ($userlevel == 'master') {

                                                if (isset($_GET['area'])) {
                                                    $area = $_GET['area'];
                                                }

                                                $sql = "SELECT DISTINCT `name`,`id`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel in ('admin','ac','mod','staff','master') AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                $query = $HRconnect->query($sql);
                                                while ($row = $query->fetch_array()) {
                                                    $name = $row['name'];
                                        ?>
                                                    <tr>
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <td>
                                                            <center>
                                                                <?php echo $row['branch']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['ConcernDate']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['empno']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo html_entity_decode(htmlentities($name)); ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['concern']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['errortype']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center><a
                                                                    href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                                    class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                            </center>
                                                        </td>
                                                    </tr>

                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS MASTER - FOR SYSTEM ERROR

            if (isset($_GET["error"]) == 'system' and $userlevel == 'master') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?error=system"><b>System Error</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?other=hardware"><b>Hardware/Device
                                Malfunction</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <br>
                <form method="get">
                    <label>
                        <bold>Select by Area: </bold>
                    </label>
                    <select name="area">
                        <option>
                            <?php
                            if (isset($_GET['area'])) {
                                echo $_GET['area'];
                            } else {
                                echo "";
                            }

                            ?>
                        </option>
                        <?php
                        $sqlarea = "SELECT DISTINCT `area_type` FROM user_info ";
                        $queryarea = $HRconnect->query($sqlarea);
                        while ($rowarea = $queryarea->fetch_array()) {
                        ?>
                            <option>
                                <?php echo $rowarea['area_type']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="error" value="system">
                    <input type="submit"
                        style="background-color: #008CBA; color: white; border-radius: 7px; border:none; width: 3%;" name=""
                        value="Go!" formaction="filedconcerns.php?error=system">
                </form>
                <br>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>
                                    <?php
                                    if (isset($_GET['area'])) {
                                        $area = $_GET['area'];
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';
                                        $sql = "SELECT DISTINCT `name`,`id`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel in ('admin','ac','mod','staff','master') AND area = '$area' AND errortype = 'System Error' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>

                                        <?php
                                        if (@$_SESSION['useridd'] != null) {
                                            if ($userlevel == 'master') {
                                                $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND errortype = 'System Error' AND userid = '$userid' AND  userlevel in ('admin','ac','mod','staff','master') AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                $query = $HRconnect->query($sql);
                                                while ($row = $query->fetch_array()) {
                                                    $name = $row['name'];
                                        ?>
                                                    <tr>
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <td>
                                                            <center>
                                                                <?php echo $row['branch']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['ConcernDate']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['empno']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo html_entity_decode(htmlentities($name)); ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['concern']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['errortype']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center><a
                                                                    href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&change=ok&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                                    class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                            </center>
                                                        </td>
                                                    </tr>

                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MASTER - FOR HARDWARE ERROR

            if (isset($_GET["other"]) == "hardware" and $userlevel == 'master') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?error=system"><b>System Error</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?other=hardware"><b>Hardware/Device
                                Malfunction</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <br>
                <form method="get">
                    <label>
                        <bold>Select by Area: </bold>
                    </label>
                    <select name="area">
                        <option>
                            <?php
                            if (isset($_GET['area'])) {
                                echo $_GET['area'];
                            } else {
                                echo "";
                            }

                            ?>
                        </option>
                        <?php
                        $sqlarea = "SELECT DISTINCT `area_type` FROM user_info ";
                        $queryarea = $HRconnect->query($sqlarea);
                        while ($rowarea = $queryarea->fetch_array()) {
                        ?>
                            <option>
                                <?php echo $rowarea['area_type']; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                    <input type="hidden" name="other" value="hardware">
                    <input type="submit"
                        style="background-color: #008CBA; color: white; border-radius: 7px; border:none; width: 3%;" name=""
                        value="Go!" formaction="filedconcerns.php?other=hardware">
                </form>
                <br>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (isset($_GET['area'])) {
                                        $area = $_GET['area'];
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';
                                        $hardwareError = 'Hardware malfunction';
                                        $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel in ('admin','ac','mod','staff','master') AND area = '$area' AND concern = '$hardwareError' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>

                                        <?php
                                        if (@$_SESSION['useridd'] != null) {
                                            $hardwareError = 'Hardware malfunction';
                                            if ($userlevel == 'master') {
                                                $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND concern = '$hardwareError' AND userid = '$userid' AND  userlevel in ('admin','ac','mod','staff','master') AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                                $query = $HRconnect->query($sql);
                                                while ($row = $query->fetch_array()) {
                                                    $name = $row['name'];
                                        ?>
                                                    <tr>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['branch']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['ConcernDate']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['empno']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo html_entity_decode(htmlentities($name)); ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['concern']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center>
                                                                <?php echo $row['errortype']; ?>
                                                            </center>
                                                        </td>
                                                        <td>
                                                            <center><a
                                                                    href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&change=ok&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                                    class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                            </center>
                                                        </td>
                                                    </tr>

                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>



                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS MASTER - APPROVED CONCERNS

            if (isset($_GET["approved"]) == "approved" and $userlevel == 'master') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?error=system"><b>System Error</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?other=hardware"><b>Hardware/Device
                                Malfunction</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center>Remarks</center>
                                        </th>
                                        <th>
                                            <center>Approver</center>
                                        </th>
                                        <th>
                                            <center>Date of Approval</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Leave</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $sql = "SELECT DISTINCT `name`,`branch`,`approver`,`ConcernDate`,`empno`,`concern`,`errortype`,`remarks`,`filing_date`,`date_approved` FROM dtr_concerns WHERE status = 'Approved' AND userid = '$userid' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['date_approved']; ?>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MASTER - VIEW SUMMARY

            if (isset($_GET["view"]) == "summary" and $userlevel == 'master') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?error=system"><b>System Error</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?other=hardware"><b>Hardware/Device
                                Malfunction</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Leave</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {

                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';

                                        $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `userid` = '$userid' AND `ConcernDate` BETWEEN '$prevdate1' AND '$prevdate2' GROUP BY `empno` DESC";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];

                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concerncount']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="filedconcerns.php?dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php

                                        }
                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS ADMIN - PENDING

            if (isset($_GET["pending"]) == "pending" and $userlevel == 'admin') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <?php
                    if ($emnum == 1348 || $emnum == 271) {
                    ?>
                        <li class="nav-item">
                            <a class="effect-shine nav-link" href="filedconcerns.php?sum=admin"><b>View Summary</b></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    $emergency = 'Emergency time out';
                                    $FPError = 'Fingerprint problem';
                                    $BrokenOT = 'File broken sched overtime';
                                    $forgot1 = 'Failure/Forgot to click half day';
                                    $forgot2 = 'Failure/Forgot to click broken schedule';
                                    $forgot3 = 'Forgot/Wrong time IN/OUT or break OUT/IN';
                                    $wrong = 'Wrong filing of OBP';
                                    $timeInterval = 'Not following break out and break in interval';
                                    $removeLogs = 'Remove time inputs';
                                    $cancel1 = 'Wrong filing of overtime';
                                    $cancel2 = 'Wrong filing of leave';
                                    if (@$_SESSION['useridd'] != null) {

                                        if ($emnum == 1) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(4378,3336,3294,5752,3111,5928,3071,3027,2221,1331,1073,271,107,24,4625,5752,5834) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 2) {
                                            $sql = "SELECT * FROM dtr_concerns WHERE userid = '$userid' AND empno in(3177,4625,885,4378,3336,3294,5752,3111,5928,3071,3027,2221,1331,1073,271,107,24,4625,5752,5834) AND status = 'Pending' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 4) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(107) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 1348) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 271) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>


                                    <?php

                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS ADMIN - BROKEN OT

            if (isset($_GET["brokenot"]) == "approval" and $userlevel == 'admin') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?brokenot=approval" hidden><b>Broken
                                Sched OT Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link " href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of OT</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';

                                        if ($emnum == 1) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(4378,3336,3294,5752,3111,5928,3071,3027,2221,1331,1073,271,107,24,4625,5752,5834) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 2) {
                                            $sql = "SELECT * FROM dtr_concerns WHERE userid = '$userid' AND empno in(3177,4625,885,4378,3336,3294,5752,3111,5928,3071,3027,2221,1331,1073,271,107,24,4625,5752,5834) AND status = 'Pending' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 4) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(107) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 1348) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(1220,1233,2114,2165,3013,3778,4072) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        if ($emnum == 271) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ottype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    <?php

                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS ADMIN - View summary of all area

            if (isset($_GET["view"]) == "summary" and $userlevel == 'admin') {
            ?>
                <!-- DataTales Example -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link active" href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php

                                    $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `ConcernDate` BETWEEN '$datestart' AND '$dateend' GROUP BY `empno` DESC";
                                    $query = $HRconnect->query($sql);
                                    while ($row = $query->fetch_array()) {
                                        $name = $row['name'];

                                    ?>
                                        <tr>
                                            <td>
                                                <center>
                                                    <?php echo $row['branch']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo $row['empno']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo html_entity_decode(htmlentities($name)); ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo $row['concerncount']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center><a
                                                        href="filedconcerns.php?adminview=ok&all=ok&dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                        class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                </center>
                                            </td>
                                        </tr>

                                    <?php


                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS ADMIN - APPROVED CONCERNS

            if (isset($_GET["approved"]) == "approved" and $userlevel == 'admin') {
            ?>
                <!-- DataTales Example -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link " href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <?php
                    if ($emnum == 1348 || $emnum == 271) {
                    ?>
                        <li class="nav-item">
                            <a class="effect-shine nav-link" href="filedconcerns.php?sum=admin"><b>View Summary</b></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center>Remarks</center>
                                        </th>
                                        <th>
                                            <center>Approver</center>
                                        </th>
                                        <th>
                                            <center>Date of Approval</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Concerns</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $sql = "SELECT DISTINCT `name`,`approver`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype`,`remarks`,`filing_date`,`date_approved` FROM dtr_concerns WHERE status = 'Approved' AND userid = '$userid' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['date_approved']; ?>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS AC - PENDING

            if (
                isset($_GET["pending"]) == "pending" and $userlevel == 'ac' and $emnum != 2165 and $emnum != 1053 and $emnum != 1509 and $emnum != 1233
                and $emnum != 4072 and $emnum != 1910 and $emnum != 3080 and $emnum != 3156 and $emnum != 3612 and $emnum != 4001 and $emnum != 5263
                and $emnum != 5430 and $emnum != 4892 and $emnum != 3337 and $emnum != 6436 and $emnum != 6209 and $emnum != 6244 and $emnum != 6245
                and $emnum != 6438
            ) {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    $emergency = 'Emergency time out';
                                    $FPError = 'Fingerprint problem';
                                    $BrokenOT = 'File broken sched overtime';
                                    $forgot1 = 'Failure/Forgot to click half day';
                                    $forgot2 = 'Failure/Forgot to click broken schedule';
                                    $forgot3 = 'Failure/Forgot to time in or time out';
                                    $forgot4 = 'Failure/Forgot to break in or break out';
                                    $wrong = 'Wrong filing of OBP';
                                    $timeInterval = 'Not following break out and break in interval';
                                    $removeLogs = 'Remove time inputs';
                                    $cancel1 = 'Wrong filing of overtime';
                                    $cancel2 = 'Wrong filing of leave';
                                    if (@$_SESSION['useridd'] != null) {


                                        if ($emnum == 4378) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(1348,1964,2957,4349,2111,2243,3332,3693,4000) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1331) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(819,109,76,71,167,45) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 24) { //jones added
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5048) { //jones added
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1073) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno NOT IN(1073,2221,6119,5832) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 4298) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3178) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 2684) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3071) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(2203,2264) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 76) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(37,53,45,69,124,2720,40,20,3685,189,229) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 37 || $emnum == 53 || $emnum == 45 || $emnum == 69 || $emnum == 124 || $emnum == 2720 || $emnum == 40 || $emnum == 20 || $emnum == 3685 || $emnum == 189 || $emnum == 229) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'SOUTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 109) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(63,88,97,170) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 63 || $emnum == 88 || $emnum == 97 || $emnum == 170) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 819) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(38,112,254,302,4484,1562,4709,204,4301) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 38 || $emnum == 112 || $emnum == 254 || $emnum == 302 || $emnum == 460 || $emnum == 2094) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'NORTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 71) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158,4209) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5752) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(159) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3336) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3111) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 2221) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' /* AND empno in(1262,5832) removed by loede.jones */ AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1844) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(2485) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 885) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 957) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 6538) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5356) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1964) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5834) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5928) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5584) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3294) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 6207) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5361 or $emnum == 3178 or $emnum == 5515 or $emnum == 5452 or $emnum == 4811 or $emnum == 2684 or $emnum == 884) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 6082) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }


                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>


                                    <?php
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS AC - BROKEN OT

            if (
                isset($_GET["brokenot"]) == "approval" and $userlevel == 'ac' and $emnum != 1053 || $emnum != 1509 || $emnum != 1910
                || $emnum != 3080 || $emnum != 3156 || $emnum != 3612 || $emnum != 4001 || $emnum != 5263 || $emnum != 5430
                || $emnum != 4892 || $emnum != 3337 || $emnum != 6436 || $emnum != 6209 || $emnum != 6244 || $emnum != 6245
                || $emnum != 6438 || $emnum != 1964
            ) {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?brokenot=approval" hidden><b>Broken
                                Sched OT Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link " href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of OT</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';

                                        if ($emnum == 4378) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(1348,1964,2957,4349,2111,2243,3332,3693,4000) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1331) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(819,109,76,71,167,45) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1073) {
                                            if ($userid == 170) {
                                                $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno = 1844  AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                            } else {
                                                $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid'AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                            }
                                        } else if ($emnum == 4298) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3178) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 2684) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 3071) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(2203,2264) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 76) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(37,53,45,69,124,2720,40,20,3685,189,229) AND errortype = 'User Error' OR errortype = 'Other Error' AND concern = '$emergency' AND concern = '$FPError'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 37 || $emnum == 53 || $emnum == 45 || $emnum == 69 || $emnum == 124 || $emnum == 2720 || $emnum == 40 || $emnum == 20 || $emnum == 3685 || $emnum == 189 || $emnum == 229) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT' AND area = 'SOUTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 109) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(63,88,97,170) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 63 || $emnum == 88 || $emnum == 97 || $emnum == 170) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT' AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 819) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(38,112,254,302,4484,1562,4709,204,4301) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 38 || $emnum == 112 || $emnum == 254 || $emnum == 302 || $emnum == 460 || $emnum == 2094) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT' AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 71) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158,4209) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5928) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 885) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 957) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 1964) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5834) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5752) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(159) AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5584) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 6207) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 5361 or $emnum == 3178 or $emnum == 5515 or $emnum == 5452 or $emnum == 4811 or $emnum == 2684 or $emnum == 884) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else if ($emnum == 6082) {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno != '" . $_SESSION['empno'] . "' AND errortype = 'Other Error' AND concern = '$BrokenOT'  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        } else {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND errortype = 'Other Error' AND concern = '$BrokenOT' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        }

                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ottype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    <?php

                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS AC - View summary of all area

            if (
                isset($_GET["view"]) == "summary" and $userlevel == 'ac' and $emnum != 1053 || $emnum != 1509 || $emnum != 1910
                || $emnum != 3080 || $emnum != 3156 || $emnum != 3612 || $emnum != 4001 || $emnum != 5263 || $emnum != 5430
                || $emnum != 4892 || $emnum != 3337 || $emnum != 6436 || $emnum != 6209 || $emnum != 6244 || $emnum != 6245
                || $emnum != 6438
            ) {
            ?>
                <!-- DataTales Example -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link active" href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php

                                    $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `ConcernDate` BETWEEN '$datestart' AND '$dateend' GROUP BY `empno` DESC";
                                    $query = $HRconnect->query($sql);
                                    while ($row = $query->fetch_array()) {
                                        $name = $row['name'];

                                    ?>
                                        <tr>
                                            <td>
                                                <center>
                                                    <?php echo $row['branch']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo $row['empno']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo html_entity_decode(htmlentities($name)); ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <?php echo $row['concerncount']; ?>
                                                </center>
                                            </td>
                                            <td>
                                                <center><a
                                                        href="filedconcerns.php?adminview=ok&all=ok&dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                        class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                </center>
                                            </td>
                                        </tr>

                                    <?php


                                    }

                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS AC - APPROVED CONCERNS

            if (
                isset($_GET["approved"]) == "approved" and $userlevel == 'ac' and $emnum != 1053 || $emnum != 1509 || $emnum != 1910
                || $emnum != 3080 || $emnum != 3156 || $emnum != 3612 || $emnum != 4001 || $emnum != 5263 || $emnum != 5430 || $emnum != 4892
                || $emnum != 3337 || $emnum != 6436 || $emnum != 6209 || $emnum != 6244 || $emnum != 6245 || $emnum != 6438
            ) {
            ?>
                <!-- DataTales Example -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link " href="filedconcerns.php?view=summary"><b>View Summary of All
                                Area</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center>Remarks</center>
                                        </th>
                                        <th>
                                            <center>Approver</center>
                                        </th>
                                        <th>
                                            <center>Date of Approval</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Concerns</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $sql = "SELECT DISTINCT `name`,`approver`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype`,`remarks`,`filing_date`,`date_approved` FROM dtr_concerns WHERE status = 'Approved' AND userid = '$userid' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['date_approved']; ?>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>


            <?php
            // IF USER LEVEL IS MOD - PENDING

            if (isset($_GET["pending"]) == "pending" and $userlevel == 'mod' and $emnum != 309 || $emnum != 158 || $emnum != 3110 || $emnum != 4451) {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                    <?php
                    if ($emnum == 2957) {
                    ?>
                        <li class="nav-item">
                            <a class="effect-shine nav-link" href="filedconcerns.php?sum=admin"><b>View Summary</b></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';
                                        $forgot1 = 'Failure/Forgot to click half day';
                                        $forgot2 = 'Failure/Forgot to click broken schedule';
                                        $forgot3 = 'Failure/Forgot to time in or time out';
                                        $forgot4 = 'Failure/Forgot to break in or break out';
                                        $wrong = 'Wrong filing of OBP';
                                        $timeInterval = 'Not following break out and break in interval';
                                        $removeLogs = 'Remove time inputs';
                                        $cancel1 = 'Wrong filing of overtime';
                                        $cancel2 = 'Wrong filing of leave';

                                        if ($userlevel == 'mod') {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel = 'staff' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$forgot4', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                            $query = $HRconnect->query($sql);
                                            while ($row = $query->fetch_array()) {
                                                $name = $row['name'];
                                    ?>
                                                <tr>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['branch']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['ConcernDate']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['empno']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo html_entity_decode(htmlentities($name)); ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['concern']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['errortype']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center><a
                                                                href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                                class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                        </center>
                                                    </td>
                                                </tr>

                                    <?php
                                            }
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MOD - BROKEN OT

            if (isset($_GET["brokenot"]) == "approval" and $userlevel == 'mod') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link " href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?brokenot=approval" hidden><b>Broken
                                Sched OT Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">

                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of OT</center>
                                        </th>
                                        <th>
                                            <center></center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View DTR Concerns Filled</a>
                                    </div>
                                <?php } ?>
                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';
                                        $BrokenOT = 'File broken sched overtime';
                                        if ($userlevel == 'mod') {
                                            $sql = "SELECT DISTINCT `name`,`branch`,`ConcernDate`,`ottype`,`empno`,`concern`,`errortype` FROM dtr_concerns WHERE status = 'Pending' AND userlevel = 'staff' AND userid = '$userid' AND concern = '$BrokenOT' AND ConcernDate BETWEEN '$prevdate1' AND '$prevdate2'";
                                            $query = $HRconnect->query($sql);
                                            while ($row = $query->fetch_array()) {
                                                $name = $row['name'];
                                    ?>
                                                <tr>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['branch']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['ConcernDate']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['empno']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo html_entity_decode(htmlentities($name)); ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['concern']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <?php echo $row['ottype']; ?>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center><a
                                                                href="pdf/view-concerns.php?dtrconcerns=<?php echo $row['concern']; ?>&dtr=concerns&empno=<?php echo $row['empno']; ?>&date=<?php echo $row['ConcernDate']; ?>"
                                                                class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                        </center>
                                                    </td>
                                                </tr>

                                    <?php
                                            }
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php
            // IF USER LEVEL IS MOD - APPROVED CONCERNS

            if (isset($_GET["approved"]) == "approved" and $userlevel == 'mod') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item" hidden>
                        <a class="effect-shine nav-link" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                    <?php
                    if ($emnum == 2957) {
                    ?>
                        <li class="nav-item">
                            <a class="effect-shine nav-link" href="filedconcerns.php?sum=admin"><b>View Summary</b></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Date of Concern</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Concerns</center>
                                        </th>
                                        <th>
                                            <center>Type of Error</center>
                                        </th>
                                        <th>
                                            <center>Remarks</center>
                                        </th>
                                        <th>
                                            <center>Approver</center>
                                        </th>
                                        <th>
                                            <center>Date of Approval</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Concerns</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {
                                        $sql = "SELECT DISTINCT `name`,`approver`,`branch`,`ConcernDate`,`empno`,`concern`,`errortype`,`remarks`,`filing_date`,`date_approved` FROM dtr_concerns WHERE status = 'Approved' AND userlevel = 'staff' AND userid = '$userid' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];
                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['ConcernDate']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concern']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['errortype']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['remarks']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['approver']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['date_approved']; ?>
                                                    </center>
                                                </td>
                                            </tr>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <?php
            // IF USER LEVEL IS MOD - VIEW SUMMARY

            if (isset($_GET["view"]) == "summary" and $userlevel == 'mod') {
            ?>
                <!-- DataTales Example -->

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?pending=pending"><b>Pending DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?brokenot=approval" hidden><b>Broken Sched OT
                                Approval</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link" href="filedconcerns.php?approved=approved"><b>Approved DTR
                                Concerns</b></a>
                    </li>
                    <li class="nav-item">
                        <a class="effect-shine nav-link active" href="filedconcerns.php?view=summary"><b>View Summary</b></a>
                    </li>
                </ul>

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
                                cellspacing="0">

                                <thead>
                                    <tr class="bg-gray-200">
                                        <th>
                                            <center>Branch</center>
                                        </th>
                                        <th>
                                            <center>Employee ID</center>
                                        </th>
                                        <th>
                                            <center>Fullname</center>
                                        </th>
                                        <th>
                                            <center>Total Number of DTR Concerns</center>
                                        </th>
                                        <th>
                                            <center>Details</center>
                                        </th>
                                    </tr>
                                </thead>


                                <?php if (@$_SESSION['useridd'] == null) { ?>
                                    <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                        Choose Cafe/Department to View Approved Leave</a>
                                    </div>
                                <?php } ?>

                                <tbody>

                                    <?php
                                    if (@$_SESSION['useridd'] != null) {

                                        $emergency = 'Emergency time out';
                                        $FPError = 'Fingerprint problem';

                                        $sql = "SELECT `id`, `name`,`branch`,`ConcernDate`,`empno`,`errortype`,`concern`, count(*) as `concerncount` FROM dtr_concerns WHERE `userid` = '$userid' AND `ConcernDate` BETWEEN '$datestart' AND '$dateend' GROUP BY `empno` DESC";
                                        $query = $HRconnect->query($sql);
                                        while ($row = $query->fetch_array()) {
                                            $name = $row['name'];

                                    ?>
                                            <tr>
                                                <td>
                                                    <center>
                                                        <?php echo $row['branch']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['empno']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo html_entity_decode(htmlentities($name)); ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <?php echo $row['concerncount']; ?>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center><a
                                                            href="filedconcerns.php?dtrconcerns=<?php echo $row['concern']; ?>&summary=okay&empno=<?php echo $row['empno']; ?>&datestart=<?php echo $datestart; ?>&dateend=<?php echo $dateend; ?>"
                                                            class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">View</a>
                                                    </center>
                                                </td>
                                            </tr>

                                <?php

                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->

                    <?php if (@$_GET['m'] == 3) { ?>
                        <script>
                            $(function() {
                                $(".thanks").delay(2500).fadeOut();

                            });
                        </script>

                        <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                            <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                                <div class="toast-header bg-success">
                                    <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR
                                        Concern</h5>
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
                                    <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR
                                        Concern</h5>
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
                                    <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR
                                        Concern</h5>
                                        <small class="text-light">just now</small>
                                </div>
                                <div class="toast-body">
                                    You have <b class="text-warning">Successfully Changed</b> the type of DTR Concern Thank you!
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

        <script>
            $(document).ready(function() {
                $('#example1').dataTable({
                    stateSave: true
                });
            });
        </script>

        <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#example').DataTable({
                    stateSave: true,
                    dom: 'Bfrtip',
                    buttons: [{

                        },

                        {

                        }
                    ]
                });

            });
        </script>


    </body>

    </html>
<?php } ?>