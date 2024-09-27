<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
//entry.php
session_start();
if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
}

$_GET['empno'] = $_SESSION['user_validate'];
if (isset($_POST['obp'])) {
    @$Employee = $_POST["empno"];
    @$userlevel = $_POST["userlevel"];
    $sql6 = "SELECT is_compressed, allowed_nobreak FROM user_info WHERE empno = $Employee";
    $query6 = $HRconnect->query($sql6);
    $row6 = $query6->fetch_array();
    $is_compressed = $row6['is_compressed'];
    $is_allowed_nobreak = $row6['allowed_nobreak'];
    @$date = $_POST["date"];
    @$Reason = $_POST["Reason"];
    @$location = $_POST["location"];
    @$timein = $_POST["timein"];
    @$breakout = $_POST["breakout"];
    @$breakin = $_POST["breakin"];
    @$timeout = $_POST["timeout"];
    @$break = $_POST["break"];
    @$cutfrom = $_POST["cutfrom"];
    @$cutto = $_POST["cutto"];
    $target_dir = 'pdf/attachments/';
    $filename = basename($_FILES['attachment1']['name']);
    $filename2 = basename($_FILES['attachment2']['name']);
    $target_file = $target_dir . $filename;
    $target_file2 = $target_dir . $filename2;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $newFileName = $target_dir . md5_file($_FILES['attachment1']['tmp_name']) . "." . $imageFileType;
    $newFileName2 = $target_dir . md5_file($_FILES['attachment2']['tmp_name']) . "." . $imageFileType;

    $sql5 = "SELECT COUNT(*) FROM obp where empno = '$Employee'
	AND datefromto = '$date' AND status in('Approved','Pending','Pending2')";
    $query5 = $HRconnect->query($sql5);
    $row5 = $query5->fetch_array();


    if ($row5['COUNT(*)'] > 0) {

        echo "<script type='text/javascript'>alert('Failed: OBP are already filed or you did not select any date, please check your filed obp. Thank you!');
        window.location.href='createovertime.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto'
        </script>";
    }

    if ($row5['COUNT(*)'] == 0) {
        // attachment obp


        if ($is_compressed == 1) {

            $break = 1;
            if ($is_allowed_nobreak == 1) {
                $break = 0;
            }

            $sql4 = "INSERT INTO obp (break, empno, datefromto, timein,breakout, breakin, timeout,obploc,obpreason,timedate,status,attachment_1,attachment_2) VALUES('$break','$Employee', '$date', '$timein', '$breakout', '$breakin', '$timeout', '$location', '$Reason', '" . date("Y-m-d H:i") . "', 'Pending2', '$newFileName', '$newFileName2')";

            $HRconnect->query($sql4);
            move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName);
            move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
            header("location:pdf/print_ot.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=2");

        } else {
            if (
                $Employee == 2525 or $userlevel == 'admin' or $userlevel == 'mod' or $userlevel == 'ac'
                or $Employee == 167 or $Employee == 2111 or $Employee == 5327 or $Employee == 2243 or $Employee == 3332 or $Employee == 3693
                or $Employee == 4000 or $Employee == 4814 or $Employee == 3780 or $Employee == 2485 or $Employee == 4890 or $Employee == 1844
                or $Employee == 401 or $Employee == 5515
            ) {

                $sql4 = "INSERT INTO obp (break, empno, datefromto, timein,breakout, breakin, timeout,obploc,obpreason,timedate,status,attachment_1,attachment_2)
                VALUES('$break','$Employee', '$date', '$timein', '$breakout', '$breakin', '$timeout', '$location', '$Reason', '" . date("Y-m-d H:i") . "', 'Pending2', '$newFileName', '$newFileName2')";

                $HRconnect->query($sql4);
                move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName);
                move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
                header("location:pdf/print_ot.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=2");
            }

            if (
                $Employee == 2008 or $Employee == 5182 or $userlevel == 'staff'
                and $Employee != 167 and $Employee != 2111 and $Employee != 5327 and $Employee != 2243 and $Employee != 3332 and $Employee != 3693
                and $Employee != 4000 and $Employee != 4814 and $Employee != 3780 and $Employee != 2485 and $Employee != 4890 and $Employee != 1844
                or $Employee == 401
            ) {

                $sql4 = "INSERT INTO obp (break, empno, datefromto, timein,breakout, breakin, timeout,obploc,obpreason,timedate,status,attachment_1,attachment_2)
                VALUES('$break','$Employee', '$date', '$timein', '$breakout', '$breakin', '$timeout', '$location', '$Reason', '" . date("Y-m-d H:i") . "', 'Pending', '$newFileName', '$newFileName2')";
                $HRconnect->query($sql4);
                move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName);
                move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
                header("location:pdf/print_ot.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=2");
            }
        }

    }

}

    //     if (
    //         $Employee == 2525 or $userlevel == 'admin' or $userlevel == 'mod' or $userlevel == 'ac'
    //         or $Employee == 167 or $Employee == 2111 or $Employee == 5753 or $Employee == 6021 or $Employee == 5327 or $Employee == 2243 or $Employee == 3332 or $Employee == 3693
    //         or $Employee == 4000 or $Employee == 4814 or $Employee == 3780 or $Employee == 2485 or $Employee == 4890 or $Employee == 401
    //         or $Employee == 4888 or $Employee == 5975 or $Employee == 6216 or $Employee == 4139 or $Employee == 6379 or $Employee == 6082
    //         or $Employee == 3777 or $Employee == 2363 or $Employee == 2807 or $Employee == 5712 or $Employee == 4068 or $Employee == 6121 or $Employee == 6483
    //     ) {

    //         $sql4 = "INSERT INTO obp (break, empno, datefromto, timein,breakout, breakin, timeout,obploc,obpreason,timedate,status,attachment_1,attachment_2)
	// 		VALUES('$break','$Employee', '$date', '$timein', '$breakout', '$breakin', '$timeout', '$location', '$Reason', '" . date("Y-m-d H:i") . "', 'Pending2', '$newFileName', '$newFileName2')";

    //         $HRconnect->query($sql4);
    //         move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName);
    //         move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
    //         header("location:pdf/print_ot.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=2");
    //     }

    //     if (
    //         ($Employee == 2008 or $Employee == 5182 or $Employee == 6114 or $Employee == 6115 or $userlevel == 'staff')
    //         and ($Employee != 167 and $Employee != 2111 and $Employee != 5753 and $Employee != 6021 and $Employee != 5327 and $Employee != 2243 and $Employee != 3332 and $Employee != 3693
    //             and $Employee != 4000 and $Employee != 4814 and $Employee != 3780 and $Employee != 2485 and $Employee != 4890 and $Employee != 401
    //             and $Employee != 4888 and $Employee != 5975 and $Employee != 6216 and $Employee != 4139 and $Employee != 6379 and $Employee != 6082
    //             and $Employee != 3777 and $Employee != 2363 and $Employee != 2807 and $Employee != 5712 and $Employee != 4068 and $Employee != 6121 and $Employee != 6483)
    //     ) {

    //         $sql4 = "INSERT INTO obp (break, empno, datefromto, timein,breakout, breakin, timeout,obploc,obpreason,timedate,status,attachment_1,attachment_2)
	// 		VALUES('$break','$Employee', '$date', '$timein', '$breakout', '$breakin', '$timeout', '$location', '$Reason', '" . date("Y-m-d H:i") . "', 'Pending', '$newFileName', '$newFileName2')";
    //         $HRconnect->query($sql4);
    //         move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName);
    //         move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
    //         header("location:pdf/print_ot.php?ut=ut&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=2");
    //     }
    // }








if (isset($_GET['add'])) {
    @$Employee = $_GET["empno"];
    @$userlevel = $_GET["userlevel"];
    @$hours = $_GET["timehour"];
    @$Reason = $_GET["Reason"];
    @$datefrom = $_GET['datefrom'];
    @$timedate = date("Y-m-d H:i");
    @$cutfrom = $_GET["cutfrom"];
    @$cutto = $_GET["cutto"];

    $sql4 = "SELECT COUNT(*) FROM overunder where empno = '$Employee'
    AND otdatefrom = '$datefrom' AND ottype = '0' AND otstatus in('approved','pending','pending2')";
    $query4 = $HRconnect->query($sql4);
    $row4 = $query4->fetch_array();


    $sql_check_nwd = "SELECT work_hours FROM `hrms`.`sched_time` WHERE datefromto = ? AND empno = ?";
    $stmt = $HRconnect->prepare($sql_check_nwd);
    $stmt->bind_param("si", $datefrom, $Employee);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC);
    $isNWD = 0;


    if ($result["work_hours"] == "NWD") {
        $isNWD = 1;
    }

    if ($row4['COUNT(*)'] > 0) {
        echo "<script type='text/javascript'>alert('Failed: overtime are already filed or you did not select any date, please check your filed overtime. Thank you!');
            window.location.href='/hrms/createovertime.php?ot=ot&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto'
            </script>";
    }

    if ($row4['COUNT(*)'] == 0) {

        if (
            $Employee == 2525 or $userlevel == 'admin' or $userlevel == 'mod' or $userlevel == 'ac'
            or $Employee == 167 or $Employee == 2111 or $Employee == 5753 or $Employee == 6021 or $Employee == 5327 or $Employee == 2243 or $Employee == 3332 or $Employee == 3693
            or $Employee == 4000 or $Employee == 4814 or $Employee == 3780 or $Employee == 2485 or $Employee == 4890 or $Employee == 401
            or $Employee == 4888 or $Employee == 5975 or $Employee == 6216 or $Employee == 4139 or $Employee == 6379 or $Employee == 6082
            or $Employee == 3777 or $Employee == 2363 or $Employee == 2807 or $Employee == 5712 or $Employee == 4068 or $Employee == 6121 or $Employee == 6483
        ) {
            // echo "testing";

            $sql3 = "INSERT INTO overunder (empno, ottype, otdatefrom,othours, otreason, timedate, otstatus, isNWD)
                VALUES('$Employee', '0', '$datefrom', '$hours', '$Reason', '$timedate', 'pending2', $isNWD)";

            $HRconnect->query($sql3);

            header("location:pdf/print_ot.php?ot=ot&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=1");
        }

        if (
            ($userlevel == 'staff' or $Employee == 2008 or $Employee == 5182 or $Employee == 6114 or $Employee == 6115)
            and ($Employee != 167 and $Employee != 2111 and $Employee != 5753 and $Employee != 6021 and $Employee != 5327 and $Employee != 2243 and $Employee != 3332 and $Employee != 3693
                and $Employee != 4000 and $Employee != 4814 and $Employee != 3780 and $Employee != 2485 and $Employee != 4890 and $Employee != 401
                and $Employee != 4888 and $Employee != 5975 and $Employee != 6216 and $Employee != 4139 and $Employee != 6379 and $Employee != 6082
                and $Employee != 3777 and $Employee != 2363 and $Employee != 2807 and $Employee != 5712 and $Employee != 4068 and $Employee != 6121 and $Employee != 6483)
        ) {
            $sql3 = "INSERT INTO overunder (empno, ottype, otdatefrom,othours, otreason, timedate, otstatus, isNWD)
                VALUES('$Employee', '0', '$datefrom', '$hours', '$Reason', '$timedate', 'pending', $isNWD)";

            $HRconnect->query($sql3);

            header("location:pdf/print_ot.php?ot=ot&empno=$Employee&cutfrom=$cutfrom&cutto=$cutto&m=1");
        }
    }
}



function random_alphanumeric_string($length)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $length);
}

// Output — FtUqw9QpC1




if (isset($_GET['id'])) {


    if ($_GET["type"] != '') {


        foreach ($_GET["id"] as $key => $value) {
            $values = 1;
            @$asd += $values;
        }


        $timedate = date("Y-m-d H:i");


        @$total = random_alphanumeric_string(9);


        foreach ($_GET["id"] as $key => $value) {
            $Employee = $_GET["empno"];
            $type = $_GET["type"];
            $reason = $_GET["reason"];

            $sql2 = "SELECT * FROM user_info where empno = '$Employee'";
            $query2 = $HRconnect->query($sql2);
            $row2 = $query2->fetch_array();

            if ($row2['vl'] >= $asd) {

                $sql3 = "SELECT COUNT(*) as numbers FROM vlform where empno = '$Employee' AND vldatefrom = '$value' AND vlstatus != 'canceled'";
                $query3 = $HRconnect->query($sql3);
                $row3 = $query3->fetch_array();

                if ($row3['numbers'] == 0) {

                    $query1 = "INSERT INTO vlform (empno,vltype,vlnumber,vldatefrom,vlreason,timedate)
  VALUES ('$Employee', '$type', '$total', '$value', '$reason', '$timedate')";

                    $HRconnect->query($query1);
                }

                header("location:pdf/print_ot.php?leave=leave&empno=$Employee&m=3");
            } else {

                echo "<script type='text/javascript'>alert('Failed: Filed leave exceeded to your remaining leave creadit this year, please refile base on your remaining leave. Thank you!');
        window.location.href='/hrms/createovertime.php?leave=leave&empno=$Employee';
        </script>";
            }
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
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- AJAX -->
    <script src="js/ajax-call.js"></script>
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        select {
            text-align-last: center;
        }
    </style>

    <style>
        input.largerCheckbox {
            width: 25px;
            height: 25px;
        }

        input[type=checkbox]+label {
            color: #8D9099;
            font-style: italic;
        }

        input[type=checkbox]:checked+label {
            color: #0000FF;
            font-style: normal;
        }
    </style>


</head>

<body class="bg-gradient-muted">

    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <a href="index.php" class="navbar-brand">
            <img src="images/logoo.png" height="35" alt=""> <i
                style="color:#7E0000;font-family:Times New Roman, cursive;font-size:120%;">Mary Grace Café</i>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto text-center">
                <a href="login.php" class="nav-item nav-link"
                    style="font-family:Times New Roman, cursive;font-size:120%;">Login</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        if (isset($_GET['wdo']) == 'wdo') {
            $empno = $_GET['empno'];
            $cutfrom = $_GET["cutfrom"];
            $cutto = $_GET["cutto"];

            $sql = "SELECT * FROM user_info
                WHERE empno = '$empno'";
            $query = $HRconnect->query($sql);
            $row = $query->fetch_array();
            ?>

            <!-- REDIRECT TO PAGE INSERTING TO DATABASE -->
            <?php
            if (isset($_GET['submit']) == "Submit") {
                if ($_GET['workingHoursDayOff'] == "" || $_GET['workingHoursDayOff'] == null) {
                    echo '
                        <script>
                            $(function() {
                                $(".thanks").delay(4000).fadeOut();

                            });
                        </script>
                        <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                            <div class="thanks toast fade show" style="position: fixed; right: 5px;">
                                <div class="toast-header bg-danger">
                                    <h4 class="mr-auto my-0 text-light"><i class="fa fa-times-circle" aria-hidden="true"></i> WDO Request</h5>
                                    <small class="text-light">just now</small>
                                </div>
                                <div class="toast-body">
                                    <b class="text-danger">Invalid Working Day Off</b>. Time inputs are incomplete.
                                </div>
                            </div>
                        </div>';
                } else {
                    $statusWDO = $_GET['statusWDO'];
                    $SelectingWDO = "SELECT datefrom, empno, wdostatus FROM `hrms`.`working_dayoff` WHERE empno = " . $_GET['empno'] . " AND datefrom LIKE '" . $_GET['datefromWDO'] . "' AND wdostatus in ('pending','pending2','approved')";
                    $QuerySelect = $HRconnect->query($SelectingWDO);
                    $row_wdo = $QuerySelect->fetch_array();
                    if (is_null($row_wdo)) {
                        $inserting = "INSERT INTO `hrms`.`working_dayoff` (`empno`, `datefrom`, `working_timein`, `working_breakout`, `working_breakin`, `working_timeout`, `working_hours`, `wdostatus`, `ottype`, `wdo_reason`) VALUES ('" . $_GET['empno'] . "', '" . $_GET['datefromWDO'] . "', '" . $_GET['workdayTimeIn'] . "', '" . $_GET['workdayBreakOut'] . "', '" . $_GET['workdayBreakIn'] . "', '" . $_GET['workdayTimeOut'] . "', '" . $_GET['workingHoursDayOff'] . "', '" . $_GET['statusWDO'] . "', 'WORKING DAY OFF', '" . $_GET['wdoReason'] . "')";
                        $QueryInsert = $HRconnect->query($inserting);

                        header("location:pdf/print_ot.php?wdo=wdo&success&empno=" . $_GET['empno'] . "&cutfrom=$cutfrom&cutto=$cutto");
                    } else {
                        echo '
                            <script>
                                $(function() {
                                    $(".thanks").delay(2500).fadeOut();

                                });
                            </script>
                            <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                                <div class="thanks toast fade show" style="position: fixed; right: 5px;">
                                    <div class="toast-header bg-warning">
                                        <h4 class="mr-auto my-0 text-light"><i class="fa fa-times-circle" aria-hidden="true"></i> WDO Request</h5>
                                        <small class="text-light">just now</small>
                                    </div>
                                    <div class="toast-body">
                                        You have <b class="text-warning">Already Filed</b> your Working Day Off Thank you!
                                    </div>
                                </div>
                            </div>';
                    }
                }
            }
            ?>

            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-2">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-4">
                                        <div class="text-center">
                                            <h1 class="h5 text-gray-900 mb-3"><small>Human Resource Department</small>
                                                <br>Working Day Off Request
                                            </h1>
                                        </div>

                                        <form class="user">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center" id="name"
                                                    placeholder="Fullname" value="<?php echo $row['name']; ?>"
                                                    style="font-size:100%;" readonly>
                                            </div>

                                            <!-- EMP ID -->
                                            <input type="text" name="cutfrom"
                                                class="form-control form-control-user bg-gray-100 text-center d-none"
                                                value="<?php echo $cutfrom; ?>">

                                            <!-- EMP BRANCH -->
                                            <input type="text" name="cutto"
                                                class="form-control form-control-user bg-gray-100 text-center d-none"
                                                value="<?php echo $cutto; ?>">

                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="empno" name="Employee" placeholder="Employee No."
                                                        value="<?php echo $row['empno']; ?>" style="font-size:100%"
                                                        readonly>
                                                </div>

                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center d-none">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="userlevel" name="userlevel"
                                                        value="<?php echo $row['userlevel']; ?>" required
                                                        onkeypress="return false;" autocomplete="off" />
                                                </div>

                                                <div class="col-sm-6 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        id="Branch" placeholder="Branch"
                                                        value="<?php echo $row['branch']; ?>" style="font-size:100%"
                                                        readonly>
                                                </div>
                                            </div>
                                            <hr>
                                            <center>
                                                <p><i><b class="text-danger">Note!</b> always remember to select and check
                                                        your Working Dayoff date and Time Inputs before clicking submit.
                                                        Thank you!</p></i>
                                            </center>

                                            <div class="d-flex flex-row justify-content-between">
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center ">
                                                    <label class="d-block d-sm-none">Click to select date</small></label>
                                                    <input type="date" class="form-control text-center" name="datefrom"
                                                        min="<?php echo $cutfrom; ?>" max="<?php echo $cutto; ?>"
                                                        value="<?php echo (isset($_GET['datefrom'])) ? $_GET['datefrom'] : ""; ?>"
                                                        required />
                                                </div>
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center ">
                                                    <input type="hidden" name="wdo" value="dateFromSubmit">
                                                    <input type="submit" name="addWDO" value="Proceed"
                                                        class="btn btn-primary btn-user btn-block bg-gradient-primary">
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        <!-- ENTRY LOGS -->
                                        <?php
                                        if (isset($_GET['addWDO']) == "Proceed") {
                                            // header("Location:pdf/print_ot.php?location=".$_SERVER['HTTP_REFERER']);
                                            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                                                $pick_date = date('Y-m-d', strtotime($_GET['datefrom']));
                                                // QUERY START
                                                $sql_date = "SELECT * FROM `sched_time` where empno = $empno and datefromto = '" . $pick_date . "' ";
                                                $queryDate = $HRconnect->query($sql_date);
                                                $row_result = $queryDate->fetch_array();

                                                $time_in = trim($row_result["M_timein"]);
                                                $breakout = trim($row_result["M_timeout"]);
                                                $breakin = trim($row_result["A_timein"]);
                                                $time_out = trim($row_result["A_timeout"]);
                                                $sched_from = trim($row_result["schedfrom"]);
                                                $sched_to = trim($row_result["schedto"]);
                                                $hasBreak = trim($row_result["break"]);
                                                $schedType = trim($row_result["sched_type"]);
                                                $overunder = "";
                                            }

                                            if (strtotime($time_in) <= strtotime($sched_from)) {
                                                $time_in = $sched_from;
                                                $early_timein = abs(strtotime(trim($row_result["M_timein"])) - strtotime($sched_from)) / 60;
                                            } else if (strtotime($time_in) >= strtotime($sched_from)) {
                                                $late_timein = abs(strtotime(trim($row_result["M_timein"])) - strtotime($sched_from)) / 60;
                                            }
                                            if (strtotime($time_out) < strtotime($sched_to)) {
                                                $overunder = abs(strtotime(trim($row_result["A_timeout"])) - strtotime($sched_to)) / 60;
                                            } else if (strtotime($time_out) > strtotime($sched_to)) {
                                                $overunder = round(abs(strtotime(trim($row_result["A_timeout"])) - strtotime($sched_to)) / 60 / 60);
                                            }

                                            // HOUR CALCULATION
                                            $message = "";
                                            if ($time_in != '' && $time_out != '' && $breakout != '' && $breakin != '') {
                                                // $diffbreaks = abs(strtotime($breakin) - strtotime($breakout))/60;
                                                // $hourbreaks = abs($diffbreaks/60);

                                                // $diff = abs(strtotime($time_in) - ((strtotime($time_out) > strtotime($sched_to))? strtotime($sched_to): strtotime($time_out)))/60;

                                                // // If emp has break and no break == 1 hour break always
                                                // if(($hasBreak == 1 && $hourbreaks == 0)|| $hasBreak == 1){
                                                //     $hourbreaks = 1;
                                                // }
                                                // $hour = abs(round(($diff/60) - $hourbreaks));
                                                // $message = $hour;
                                                $hour = floor(abs(strtotime($row_result["schedfrom"]) - strtotime($time_out)) / 3600) - $hasBreak;
                                                if ($hour > 8 /*&& $schedType != 'cmp_sched'*/) {
                                                    $hour = 8;
                                                }
                                                $message = $hour;
                                            } else if (($time_in == '' || $time_out == '') || ($breakout == "" || $breakin == "")) {
                                                $message = "Incomplete Time Inputs";
                                            } else {
                                                $message = "No Time Inputs";
                                            }
                                            ?>
                                            <div>
                                                <div class="col-xl-12 col-lg-12">
                                                    <form method="GET">
                                                        <label class="d-flex justify-content-center"><small>Captured time
                                                                inputs</small></label>
                                                        <!-- TIME IN START CAPTURE -->
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <input type="text" id="workdayTimeIn"
                                                                    class="text-center form-control form-control-sm mb-1"
                                                                    name="workdayTimeIn"
                                                                    value="<?php echo (trim($row_result["M_timein"]) == '') ? "No Logs" : date("H:i", strtotime($row_result["M_timein"])); ?>"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" id="workdayBreakOut"
                                                                    class="text-center form-control form-control-sm mb-1"
                                                                    name="workdayBreakOut"
                                                                    value="<?php echo ($breakout == '') ? "No Logs" : (($breakout == "No Break") ? "No Break" : date("H:i", strtotime($breakout))); ?>"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" id="workdayBreakIn"
                                                                    class="text-center form-control form-control-sm mb-1"
                                                                    name="workdayBreakIn"
                                                                    value="<?php echo ($breakin == '') ? "No Logs" : (($breakin == "No Break") ? "No Break" : date("H:i", strtotime($breakin))); ?>"
                                                                    readonly>
                                                            </div>
                                                            <div class="col-3">
                                                                <input type="text" id="workdayTimeOut"
                                                                    class="text-center form-control form-control-sm mb-1"
                                                                    name="workdayTimeOut"
                                                                    value="<?php echo (trim($row_result["A_timeout"]) == '') ? "No Logs" : date("H:i", strtotime($row_result["A_timeout"])); ?>"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                </div>
                                                <!-- SHOWS LOGS -->

                                                <div
                                                    class="col-xl-12 col-lg-12 d-flex flex-column align-items-center text-right mt-4">
                                                    <div class="col-sm-5 text-center">
                                                        <label><small>Working Hours</small></label>
                                                        <!-- TIME IN START CAPTURE -->
                                                        <input type="text" class="form-control form-control-sm mb-1 text-center"
                                                            placeholder="<?php echo $message; ?>"
                                                            value="<?php echo ($message == "Incomplete Time Inputs" || $message == "No Time Inputs") ? "" : $message . " Hr(s)"; ?>"
                                                            readonly required>
                                                    </div>
                                                    <div class="d-none">
                                                        <label><small>OT Hours</small></label>
                                                        <!-- TIME OUT START CAPTURE -->
                                                        <input type="text" class="form-control form-control-sm mb-1"
                                                            value="<?php echo ((strtotime($time_out) < strtotime($sched_to)) || $overunder == 0 || $overunder == "") ? "N/A" : $overunder . " Hr(s)"; ?>"
                                                            readonly required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-12 col-lg-12 mt-2">
                                                <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                                <input type="text" class="form-control form-control-sm mb-1"
                                                    pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="WDOreason"
                                                    name="wdoReason" required>

                                                <div class="form-check mb-1 ml-1">
                                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                                    <label class="form-check-label" for="exampleCheck1"><em><small>I hereby
                                                                certify that the above infomation provided is correct.
                                                                Any falsification of information in this regard may form ground
                                                                for
                                                                disciplinary action up to and including dismissal.</em></small>
                                                    </label>
                                                </div>

                                                <input type="text" class="d-none" id="empno" name="empno"
                                                    value="<?php echo $empno ?>" required>
                                                <input type="text" class="d-none" id="datefromWDO" name="datefromWDO"
                                                    value="<?php echo $pick_date ?>" required>
                                                <input type="text" class="d-none" id="cutto" name="cutto"
                                                    value="<?php echo $cutto ?>" required>
                                                <input type="text" class="d-none" id="cutfrom" name="cutfrom"
                                                    value="<?php echo $cutfrom ?>" required>
                                                <input type="text" class="d-none" id="wdo" name="wdo" value="wdo" required>
                                                <!-- <input type="text" class="d-none" id="wdo" name="ot_hours" value="<?php echo $overunder ?>" required> -->
                                                <input type="text" class="d-none" id="statusWDO" name="statusWDO" value="<?php
                                                $userlevel = $_GET["userlevel"];
                                                $Employee = $_GET["empno"];
                                                if (
                                                    $Employee == 2525 or $userlevel == 'admin' or $userlevel == 'mod' or $userlevel == 'ac'
                                                    or $Employee == 167 or $Employee == 2111 or $Employee == 5753 or $Employee == 6021 or $Employee == 5327 or $Employee == 2243 or $Employee == 3332 or $Employee == 3693
                                                    or $Employee == 4000 or $Employee == 4814 or $Employee == 3780 or $Employee == 2485 or $Employee == 4890 or $Employee == 401
                                                    or $Employee == 4888 or $Employee == 5975 or $Employee == 4139 or $Employee == 6379 or $Employee == 6082
                                                    or $Employee == 3777 or $Employee == 2363 or $Employee == 2807 or $Employee == 5712 or $Employee == 4068 or $Employee == 6121 or $Employee == 6483
                                                ) {
                                                    echo "pending2";
                                                }

                                                if (
                                                    ($Employee == 2008 or $Employee == 5182 or $userlevel == 'staff')
                                                    and ($Employee != 167 and $Employee != 2111 and $Employee != 5753 and $Employee != 6021 and $Employee != 5327 and $Employee != 2243 and $Employee != 3332 and $Employee != 3693
                                                        and $Employee != 4000 and $Employee != 4814 and $Employee != 3780 and $Employee != 2485 and $Employee != 4890 and $Employee != 401
                                                        and $Employee != 4888 and $Employee != 5975 and $Employee != 4139 and $Employee != 6379 and $Employee != 6082
                                                        and $Employee != 3777 and $Employee != 2363 and $Employee != 2807 and $Employee != 5712 and $Employee != 4068 and $Employee != 6121 and $Employee != 6483)
                                                ) {
                                                    echo "pending";
                                                }

                                                ?>">
                                                <!-- readonly will bypass required -->
                                                <input type="text" class="d-none" id="workingHoursDayOff"
                                                    name="workingHoursDayOff"
                                                    value="<?php echo ($message == "Incomplete Time Inputs" || $message == "No Time Inputs") ? "" : $message; ?>">

                                                <div class="d-flex justify-content-end">
                                                    <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit"
                                                        name="submit"
                                                        onclick="return confirm('Are you sure you want to submit your Working Day Off request?')">
                                                </div>
                                            </div>

                                            <?php
                                        }
                                        ?>
                                        <!-- FOOTER -->
                                        <div class="text-center mt-4">
                                            <a class="small float-left"
                                                href="index.php?empno=<?php echo $row['empno']; ?>&SubmitButton=Submit"><i
                                                    class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
                                            <a class="small float-right"
                                                href="pdf/print_ot.php?wdo=wdo&view&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">View
                                                Filed Working Day Off <i class="fa fa-angle-right"
                                                    aria-hidden="true"></i></a>
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
        <?php
        if (isset($_GET["ot"]) == "ot") {
            $empno = $_GET['empno'];
            @$cutfrom = $_GET["cutfrom"];
            @$cutto = $_GET["cutto"];

            $sql = "SELECT * FROM user_info
                    WHERE empno = '$empno'";
            $query = $HRconnect->query($sql);
            $row = $query->fetch_array();

            ?>
            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-6 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-2">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-4">
                                        <div class="text-center">
                                            <h1 class="h5 text-gray-900 mb-3"><small>Human Resource Department</small>
                                                <br>Overtime Request
                                            </h1>
                                        </div>
                                        <form class="user">
                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center" id="name"
                                                    placeholder="Fullname" value="<?php echo $row['name']; ?>"
                                                    style="font-size:100%" readonly>
                                            </div>

                                            <input type="text" name="cutfrom"
                                                class="form-control form-control-user bg-gray-100 text-center d-none"
                                                value="<?php echo $cutfrom; ?>">

                                            <input type="text" name="cutto"
                                                class="form-control form-control-user bg-gray-100 text-center d-none"
                                                value="<?php echo $cutto; ?>">

                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="empno" name="Employee" placeholder="Employee No."
                                                        value="<?php echo $row['empno']; ?>" style="font-size:100%"
                                                        readonly>
                                                </div>

                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center d-none">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="userlevel" name="userlevel"
                                                        value="<?php echo $row['userlevel']; ?>" required
                                                        onkeypress="return false;" autocomplete="off" />
                                                </div>

                                                <div class="col-sm-6 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        id="Branch" placeholder="Branch"
                                                        value="<?php echo $row['branch']; ?>" style="font-size:100%"
                                                        readonly>
                                                </div>
                                            </div>
                                            <hr>
                                            <center>
                                                <p><i><b class="text-danger">Note!</b> always remember to select and check
                                                        your overtime date and ot hours before clicking submit. Thank you!
                                                </p></i>
                                            </center>
                                            <div class="form-group row">

                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <label class="d-block d-sm-none">Click to select date</small></label>
                                                    <input type="date" class="form-control text-center" name="datefrom"
                                                        min="<?php echo $cutfrom; ?>" max="<?php echo $cutto; ?>"
                                                        required />
                                                </div>

                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <input type="number" class="form-control text-center" name="timehour"
                                                        placeholder="Number of OT Hours" max="15" min="1" required>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <input type="text" pattern="^[a-zA-Z0-9_ ]*$"
                                                    title="Special characters are not allowed"
                                                    class="form-control text-center" id="date" name="Reason"
                                                    placeholder="Reason Or Purpose of Overtime" style="height:60px;"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck"
                                                        required>
                                                    <label class="custom-control-label" for="customCheck">
                                                        I hereby Certify that the above infomation provided is correct. Any
                                                        falsification
                                                        of information in this regard may form ground for disciplinary
                                                        action up to and including dismissal.
                                                    </label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="ot" value="ot" readonly>
                                            <input type="submit" name="add" value="Submit"
                                                class="btn btn-primary btn-user btn-block bg-gradient-primary"
                                                onclick="return confirm('Are you sure you want to submit this OT form?');">
                                        </form>
                                        <hr>
                                        <div class="text-center">

                                            <?php
                                            $sql1 = "SELECT * FROM user_info
										JOIN sched_info ON user_info.empno = sched_info.empno
										WHERE user_info.empno = '$empno' AND sched_info.status = 'Pending'";
                                            $query1 = $HRconnect->query($sql1);
                                            $row1 = $query1->fetch_array();
                                            @$datefrom = $row1['datefrom'];
                                            @$dateto = $row1['dateto'];

                                            ?>
                                            <a class="small float-left"
                                                href="index.php?empno=<?php echo $row['empno']; ?>&SubmitButton=Submit"><i
                                                    class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
                                            <a class="small float-right"
                                                href="pdf/print_ot.php?ot=ot&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $datefrom; ?>&cutto=<?php echo $dateto; ?>">View
                                                Filed OT <i class="fa fa-angle-right" aria-hidden="true"></i></a>
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

        <?php
        if (isset($_GET["ut"]) == "ut") {
            $empno = $_GET['empno'];
            @$cutfrom = $_GET["cutfrom"];
            @$cutto = $_GET["cutto"];

            $sql = "SELECT * FROM user_info
                        WHERE empno = '$empno'";
            $query = $HRconnect->query($sql);
            $row = $query->fetch_array();
            $department = $row['department'];
            $branch = $row['branch'];
            ?>
            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-6 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-2">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-4">
                                        <div class="text-center">
                                            <h1 class="h5 text-gray-900 mb-3"><small>Human Resource Department</small>
                                                <br>Official Business Permit
                                            </h1>
                                        </div>
                                        <form method="POST" class="user" enctype="multipart/form-data">

                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center" id="name"
                                                    placeholder="Fullname" value="<?php echo $row['name']; ?>"
                                                    style="font-size:100%" readonly>
                                            </div>

                                            <div class="form-group row d-none">
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="empno" name="Employee" placeholder="Employee No."
                                                        value="<?php echo $row['empno']; ?>" id="obp-emp-id" required />
                                                </div>

                                                <input type="text" name="cutfrom"
                                                    class="form-control form-control-user bg-gray-100 text-center d-none"
                                                    value="<?php echo $cutfrom; ?>">

                                                <input type="text" name="cutto"
                                                    class="form-control form-control-user bg-gray-100 text-center d-none"
                                                    value="<?php echo $cutto; ?>">

                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        name="userlevel" name="userlevel"
                                                        value="<?php echo $row['userlevel']; ?>" required />
                                                </div>


                                                <div class="col-sm-6 text-center">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        id="Branch" placeholder="Branch"
                                                        value="<?php echo $row['branch']; ?>" required
                                                        onkeypress="return false;">
                                                </div>
                                            </div>

                                            <div class="form-group row" id="form-date">
                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                    <label class="d-block d-sm-none">Click to select date</small></label>
                                                    <input id="OBP-date" type="date"
                                                        class="form-control form-control text-center" name="date"
                                                        min="<?php echo $cutfrom; ?>" max="<?php echo $cutto; ?>"
                                                        onkeypress="return false;" autocomplete="off" required />
                                                </div>


                                                <div class="col-sm-6 text-center">
                                                    <input type="text" pattern="^[-@.\/#&+\w\s]*$"
                                                        class="form-control form-control text-center" name="location"
                                                        placeholder="Location" required />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <center>
                                                    <p><i><b class="text-danger">Note!</b> always remember that our system
                                                            is using millitary time please use correct time format (<b
                                                                class="text-success"> 00:00</b> ) to prevent errors.</p></i>
                                                </center>
                                            </div>

                                            <hr>
                                            <?php
                                            if (
                                                $department == 'South' or $department == 'MFO' or $department == 'North' or $branch == 'KIOSK' and $empno != 159 and $empno != 5752 and $empno != 2229 and $empno != 2597
                                                and $empno != 3225 and $empno != 4227 and $empno != 3225 and $empno != 4889
                                            ) {
                                                ?>
                                                <div class="form-group row col-lx-13 mb-0">
                                                    <div class="col-md-12 text-center checkbox">
                                                        <input type="checkbox" class="control-input" id="nobreak"
                                                            onchange="checkDisable()" />
                                                        <label class="control-label" for="nobreak">
                                                            Please check this box if you dont have break time.
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="form-group mb-0">
                                                    <div class="col-md-12 text-center checkbox">
                                                        <input type="checkbox" class="control-input" id="nobreak1"
                                                            onchange="checkDisable1()" />
                                                        <label class="control-label" for="nobreak1">
                                                            Please check this box if you dont have break time.
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>

                                            <div class="form-group row col-lx-13">
                                                <div class="col-sm-3 text-center mb-2">
                                                    <label>Time in</label>
                                                    <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                        class="form-control text-center timeinputs-obp" name="timein"
                                                        placeholder="00:00" required />
                                                </div>

                                                <?php
                                                if (
                                                    $department == 'South' or $department == 'MFO' or $department == 'North' or $branch == 'KIOSK' and $empno != 159 and $empno != 5752 and $empno != 2229 and $empno != 2597
                                                    and $empno != 3225 and $empno != 4227 and $empno != 3225 and $empno != 4889
                                                ) {
                                                    ?>
                                                    <div class="col-sm-3 text-center mb-2">
                                                        <label>Break out</label>
                                                        <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                            id="breakout" class="form-control text-center timeinputs-obp"
                                                            name="breakout" placeholder="00:00" required />
                                                    </div>


                                                    <div class="col-sm-3 text-center mb-2">
                                                        <label>Break in</label>
                                                        <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                            id="breakin" class="form-control text-center timeinputs-obp"
                                                            name="breakin" placeholder="00:00" required />
                                                    </div>

                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="col-sm-3 text-center mb-2">
                                                        <label>Break out</label>
                                                        <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                            id="breakout1" class="form-control text-center timeinputs-obp"
                                                            name="breakout" placeholder="00:00" required />
                                                    </div>


                                                    <div class="col-sm-3 text-center mb-2">
                                                        <label>Break in</label>
                                                        <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                            id="breakin1" class="form-control text-center timeinputs-obp"
                                                            name="breakin" placeholder="00:00" required />
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <div class="col-sm-3 text-center ">
                                                    <label>Time out</label>
                                                    <input type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                        class="form-control text-center timeinputs-obp" name="timeout"
                                                        placeholder="00:00" required />
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-3 text-center">
                                                </div>
                                                <?php
                                                if (
                                                    $department == 'South' or $department == 'MFO' or $department == 'North' or $branch == 'KIOSK' and $empno != 159 and $empno != 5752 and $empno != 2229 and $empno != 2597
                                                    and $empno != 3225 and $empno != 4227 and $empno != 3225 and $empno != 4889
                                                ) {
                                                    ?>
                                                    <div class="col-md-6 text-center">
                                                        <input type="number" min="1" id="breaktime"
                                                            class="form-control text-center d-none" name="break" value="1"
                                                            readonly />
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="col-md-6 text-center">
                                                        <input type="number" min="1" id="breaktime1"
                                                            class="form-control text-center d-none" name="break" value="1"
                                                            readonly />
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                            <hr>
<!--
                                            <div class="form-group text-center">
                                                <input type="text" pattern="^[-@.\/#&+\w\s]*$" style="height:60px;"
                                                    class="form-control text-center" id="Reason" name="Reason"
                                                    placeholder="Reason Or Purpose" required>
                                            </div> -->



                                            <div class="form-group text-center">
                                                <input type="text" pattern="^[a-zA-Z0-9_ ]*$"
                                                   title="Special characters ar e not allowed"
                                                    class="form-control text-center" id="Reason" name="Reason"
                                                    placeholder="Reason Or Purpose of OBP" style="height:60px;"
                                                    required>
                                            </div>


                                            <div class="form-group row">
                                                <div class="col-xl-6 col-lg-6">
                                                    <label class="mb-0"><small>Attachment 1 (<span class="text-danger"> JOB
                                                                ORDER </span>)</small></label><br>
                                                    <small><input type="file" accept="image/*,video/*" id="obp_image1"
                                                            name="attachment1" required /></small>
                                                    <!-- <img id="preview" src="#" alt="Preview" />
                                            /*onchange="previewFile(this);"*/-->
                                                </div>
                                                <div class="col-xl-6 col-lg-6">
                                                    <label class="mb-0"><small>Attachment 2 (<span class="text-danger">
                                                                LOCATION SELFIE </span>)</small></label><br>
                                                    <small><input type="file" accept="image/*,video/*" name="attachment2"
                                                            required /></small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck"
                                                        required>
                                                    <label class="custom-control-label" for="customCheck">
                                                        I hereby certify that the above information provided is correct. Any
                                                        falsification
                                                        of information in this regard may form ground for disciplinary
                                                        action up to and including dismissal.
                                                    </label>
                                                </div>
                                            </div>
                                            <input type="submit" name="obp" value="Submit"
                                                class="btn btn-primary btn-user btn-block bg-gradient-primary">
                                        </form>
                                        <hr>
                                        <div class="text-center">

                                            <?php

                                            $sql1 = "SELECT * FROM user_info
										JOIN sched_info ON user_info.empno = sched_info.empno
										WHERE user_info.empno = '$empno' AND sched_info.status = 'Pending'";
                                            $query1 = $HRconnect->query($sql1);
                                            $row1 = $query1->fetch_array();
                                            @$datefrom = $row1['datefrom'];
                                            @$dateto = $row1['dateto'];

                                            ?>
                                            <a class="small float-left"
                                                href="index.php?empno=<?php echo $row['empno']; ?>&SubmitButton=Submit"><i
                                                    class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
                                            <a class="small float-right"
                                                href="pdf/print_ot.php?ut=ut&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $datefrom; ?>&cutto=<?php echo $dateto; ?>">View
                                                Filed OBP <i class="fa fa-angle-right" aria-hidden="true"></i></a>
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

        <?php
        if (isset($_GET["leave"]) == "leave") {

            $empno = $_GET['empno'];
            header('Location:create-leave.php?leave=leave&empno=' . $empno);
            $sql = "SELECT * FROM user_info
                    WHERE empno = '$empno'";
            $query = $HRconnect->query($sql);
            $row = $query->fetch_array();

            $sql4 = "SELECT * FROM sched_info where empno = " . $row['empno'] . " AND status = 'Pending'";
            $query4 = $HRconnect->query($sql4);
            $row4 = $query4->fetch_array();
            $cutfrom = $row4['datefrom'];
            $cutto = $row4['dateto'];
            ?>

            <!-- Outer Row -->
            <form class="user" name="add_name" id="add_name" method="get">
                <input type="number" name="empno" hidden value="<?php echo $row['empno']; ?>">
                <div class="row justify-content">

                    <div class="col-xl-6 col-lg-12 col-md-9">

                        <div class="card o-hidden border-0 shadow-lg my-2">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-4">
                                            <div class="text-center">
                                                <h1 class="h5 text-gray-900 mb-3"><small>Human Resource Department</small>
                                                    <br>Leave Request Form
                                                </h1>
                                            </div>
                                            <form class="user">

                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center"
                                                        id="name" placeholder="Fullname" value="<?php echo $row['name']; ?>"
                                                        style="font-size:100%" readonly>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center"
                                                            id="Hired" placeholder="Date Hired"
                                                            value="<?php echo $row['datehired']; ?>" required
                                                            onkeypress="return false;" />
                                                    </div>

                                                    <div class="col-sm-6 text-center">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center"
                                                            id="Position" placeholder="Position"
                                                            value="<?php echo $row['position']; ?>" required
                                                            onkeypress="return false;">
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6 mb-3 mb-sm-0 text-center d-none">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center"
                                                            id="Company" placeholder="Company"
                                                            value="<?php echo $row['company']; ?>" required
                                                            onkeypress="return false;" />
                                                    </div>

                                                    <div class="col-sm-6 text-center d-none">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center"
                                                            id="Branch" placeholder="Branch"
                                                            value="<?php echo $row['branch']; ?>" required
                                                            onkeypress="return false;" />
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-group">
                                                    <a class="text-danger">
                                                        <?php echo @$_GET['message']; ?>
                                                    </a>
                                                    <select class="form-control" name="type" required>
                                                        <option selected>Wellness Leave</option>
                                                    </select>
                                                </div>

                                                <div class="form-group text-center">
                                                    <input type="text" pattern="^[-@.\/#&+\w\s]*$" style="height:60px;"
                                                        class="form-control text-center" name="reason"
                                                        placeholder="Reason Or Purpose of Leave" required>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br>

                    <div class="col-xl-6 col-lg-12 col-md-9">
                        <div class="card o-hidden border-0 shadow-lg my-2">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-5">
                                            <center>
                                                <?php
                                                if ($row['vl'] == 0) {
                                                    echo "You dont have remaining leave";
                                                } else {
                                                    echo "Remaining Wellness leave " . $row['vl'] . " ";
                                                }
                                                ?>
                                            </center>

                                            <div>
                                                <center><label>Inclusive Date(s)</label></center>
                                                <p class="text-danger">
                                                    <?php echo @$message; ?>
                                                </p>
                                                <div class="form-group">
                                                    <div class="table-responsive mb-3">
                                                        <table class="table table-bordered" id="dynamic_field">
                                                            <tr>
                                                                <td class="border-0">
                                                                    <p class="m-0 small-font text-primary float-left mt-1"
                                                                        id="view-holiday-dates"
                                                                        style="cursor:pointer; font-size: 80%">View Holiday
                                                                        Dates</p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="border-white">
                                                                    <label class="d-block d-sm-none">Click to select
                                                                        date</small></label>
                                                                    <input type="date" id="leave-date-picker"
                                                                        class="form-control form-control-user text-center"
                                                                        name="id[]" min="<?php echo $cutfrom; ?>"
                                                                        max="<?php echo $cutto; ?>" required
                                                                        onkeypress="return false;" autocomplete="off" />
                                                                </td>
                                                                <td class="border-white"><button type="button" name="add"
                                                                        id="add"
                                                                        class="btn btn-success btn-user bg-gradient-success"><i
                                                                            class="fa fa-plus"></i></button></td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox small">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="customCheck" required>
                                                            <label class="custom-control-label" for="customCheck" required>
                                                                I hereby Certify that the above infomation provided is
                                                                correct. Any falsification
                                                                of information in this regar may form ground for
                                                                disciplinary action up to and including dismissal.
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <?php if ($row['vl'] != 0) { ?>
                                                        <input class="btn btn-primary btn-user btn-block bg-gradient-primary"
                                                            type="submit" name="submit" id="submit" class="btn btn-info"
                                                            value="Submit"
                                                            onclick="return confirm('Are you sure you want to submit this Leave form?');" />
                                                    <?php } else { ?>
                                                        <input class="btn btn-primary btn-user btn-block bg-gradient-primary"
                                                            type="submit" name="submit" disabled id="submit"
                                                            class="btn btn-info" value="Submit"
                                                            onclick="return confirm('Are you sure you want to submit this Leave form?');" />
                                                    <?php } ?>
            </form>
        </div>
        </div>
        <hr>
        <a class="small float-right" href="pdf/print_ot.php?leave=leave&empno=<?php echo $row['empno']; ?>">Filed Leave <i
                class="fa fa-angle-right" aria-hidden="true"></i></a>
        <a class="small float-left" href="index.php?empno=<?php echo $row['empno']; ?>&SubmitButton=Submit"><i
                class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
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

    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright © Mary Grace Foods Inc. 2019</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
        function checkDisable() {
            var nobreak = document.getElementById('nobreak');

            var breakout = document.getElementById('breakout');
            var breakin = document.getElementById('breakin');
            var breaktime = document.getElementById('breaktime');

            if (nobreak.checked) {
                breakout.value = "No Break";
                breakin.value = "No Break";
                breaktime.value = "0";

                document.getElementById("breakout").readOnly = true;
                document.getElementById("breakin").readOnly = true;
                document.getElementById("breaktime").readOnly = true;
            } else {
                breaktime1.value = "1";
                document.getElementById("breaktime1").readOnly = true;

                let chosen_date = $('#OBP-date').val();
                let emp_id = $('#obp-emp-id').val();
                $.ajax({
                    url: 'Function/obp_dates.php?dates=getOBP',
                    type: 'GET',
                    data: {
                        date: chosen_date,
                        empno: emp_id
                    },
                    success: function (response) {
                        let jsonResponse = JSON.parse(response);
                        $('.timeinputs-obp').each(function (index) {
                            const key = Object.keys(jsonResponse)[index];
                            const value = jsonResponse[key];
                            if (index == 1 || index == 2) {
                                $(this).val(value);
                                if (!(value == "" || value == null || value == undefined)) {
                                    $(this).attr('readonly', '');
                                } else {
                                    $(this).removeAttr('readonly');
                                }
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }

        function checkDisable1() {
            var nobreak1 = document.getElementById('nobreak1');
            var breakout1 = document.getElementById('breakout1');
            var breakin1 = document.getElementById('breakin1');
            var breaktime1 = document.getElementById('breaktime1');

            if (nobreak1.checked) {
                breakout1.value = "No Break";
                breakin1.value = "No Break";
                breaktime1.value = "1";

                document.getElementById("breakout1").readOnly = true;
                document.getElementById("breakin1").readOnly = true;
                document.getElementById("breaktime1").readOnly = true;
            } else {
                breaktime1.value = "1";
                document.getElementById("breaktime1").readOnly = true;

                let chosen_date = $('#OBP-date').val();
                let emp_id = $('#obp-emp-id').val();
                $.ajax({
                    url: 'Function/obp_dates.php?dates=getOBP',
                    type: 'GET',
                    data: {
                        date: chosen_date,
                        empno: emp_id
                    },
                    success: function (response) {
                        let jsonResponse = JSON.parse(response);
                        $('.timeinputs-obp').each(function (index) {
                            const key = Object.keys(jsonResponse)[index];
                            const value = jsonResponse[key];
                            if (index == 1 || index == 2) {
                                $(this).val(value);
                                if (!(value == "" || value == null || value == undefined)) {
                                    $(this).attr('readonly', '');
                                } else {
                                    $(this).removeAttr('readonly');
                                }
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    }
                });
            }
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var postURL = "/addmore.php";
            var i = 1;

            $('#add').click(function () {
                i++;
                $('#dynamic_field').append('<tr id="row' + i + '" class="dynamic-added"><td class="border-white"><input class="form-control form-control-user text-center leave-date-picker" type="date" name="id[]" min="<?php echo $cutfrom; ?>" max="<?php echo $cutto; ?>" required /></td><td class="border-white"><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-user bg-gradient-danger btn_remove">X</button></td></tr>');
            });

            $(document).on('click', '.btn_remove', function () {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });

            $('#submit').click(function () {
                $.ajax({
                    url: postURL,
                    method: "POST",
                    data: $('#add_name').serialize(),
                    type: 'json',
                    success: function (data) {
                        i = 1;
                        $('.dynamic-added').remove();
                        $('#add_name')[0].reset();
                        alert('Record Inserted Successfully.');
                    }
                });
            });


        });
        // function previewFile(input) {
        //     var preview = document.getElementById('preview');
        //     var file = input.files[0];
        //     var reader = new FileReader();

        //     reader.onloadend = function() {
        //         preview.src = reader.result;
        //     }

        //     if (file) {
        //         reader.readAsDataURL(file);
        //     } else {
        //         preview.src = "";
        //     }
        // }
    </script>


</html>