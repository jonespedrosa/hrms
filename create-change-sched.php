<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
//entry.php  
session_start();

if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
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
        if (isset($_GET['cs']) == 'cs') {
            $empno = $_SESSION['user_validate'];
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
                $statusCS = $_GET['statusCS'];
                $SelectingCS = "SELECT datefrom, empno, cs_status FROM `hrms`.`change_schedule` WHERE empno = " . $_GET['empno'] . " AND datefrom LIKE '" . $_GET['datefromCS'] . "' AND cs_status in ('pending','pending2','approved')";
                $QuerySelect = $HRconnect->query($SelectingCS);
                $row_cs = $QuerySelect->fetch_array();

                if (is_null($row_cs)) {
                    $inserting = "INSERT INTO `hrms`.`change_schedule` (`empno`, `datefrom`, `cs_schedfrom`, `cs_schedto`, `cs_break`,`cs_status`, `cs_reason`) VALUES ('" . $_GET['empno'] . "', '" . $_GET['datefromCS'] . "', '" . $_GET['schedfrom_req'] . "', '" . $_GET['schedto_req'] . "', '" . $_GET['break_req'] . "','" . $_GET['statusCS'] . "', '" . $_GET['csReason'] . "');";
                    $QueryInsert = $HRconnect->query($inserting);

                    header("location:pdf/print_change_sched.php?cs=cs&success&empno=" . $_GET['empno'] . "&cutfrom=$cutfrom&cutto=$cutto");
                } else {
                    echo '
                        <script>
                            $(function() {
                                $(".thanks").delay(2500).fadeOut();
                        
                            });
                        </script>
                        <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                            <div class="thanks toast fade show" style="position: fixed; right: 5px;">
                                <div class="toast-header bg-danger">
                                    <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle" aria-hidden="true"></i> Change Schedule</h5>
                                    <small class="text-light">just now</small>
                                </div>
                                <div class="toast-body">
                                    You have <b class="text-danger">Already Filed</b> your Change Schedule Request Thank you!
                                </div>
                            </div>
                        </div>';
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
                                                <br>Change Schedule Request
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
                                                        your scheduled date and requested scheduled date before clicking
                                                        submit. Thank you!</p></i>
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
                                                    <input type="hidden" name="cs" value="dateFromSubmit">
                                                    <input type="submit" name="changeSched_req" value="Proceed"
                                                        class="btn btn-primary btn-user btn-block bg-gradient-primary">
                                                </div>
                                            </div>
                                        </form>
                                        <hr>
                                        <!-- ENTRY LOGS -->
                                        <?php
                                        if (isset($_GET['changeSched_req']) == "Proceed") {
                                            // header("Location:pdf/print_ot.php?location=".$_SERVER['HTTP_REFERER']);
                                            if ($_SERVER['REQUEST_METHOD'] == "GET") {
                                                $pick_date = date('Y-m-d', strtotime($_GET['datefrom']));
                                                // QUERY START
                                                $sql_date = "SELECT * FROM `sched_time` where empno = $empno and datefromto = '" . $pick_date . "' ";
                                                $queryDate = $HRconnect->query($sql_date);
                                                $row_result = $queryDate->fetch_array();

                                                $sched_from = trim($row_result["schedfrom"]);
                                                $sched_to = trim($row_result["schedto"]);
                                                $hasBreak = trim($row_result["break"]);
                                            }

                                            ?>
                                            <center>
                                                <p><i><b class="text-danger">Note!</b> always remember to input military time.
                                                </p></i>
                                            </center>
                                            <form method="GET">
                                                <div class="d-flex">
                                                    <div class="col-xl-6 col-lg-6">
                                                        <label><small>Actual Schedule</small></label>
                                                        <!-- START CAPTURE -->
                                                        <div class="form-group row d-flex flex-column">
                                                            <div class="col-sm-12 text-center mb-2">
                                                                <input type="text"
                                                                    pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                                    class="form-control text-center" placeholder="00:00" value="<?php
                                                                    echo date('H:i', strtotime($sched_from));
                                                                    ?>" readonly />
                                                            </div>
                                                            <div class="col-sm-12 text-center mb-2">
                                                                <input type="text"
                                                                    pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                                    class="form-control text-center" placeholder="00:00" value="<?php
                                                                    echo date('H:i', strtotime($sched_to));
                                                                    ?>" readonly />
                                                            </div>
                                                        </div>
                                                        <label><small>Actual Break Hours</small></label>
                                                        <div class="col-sm-12 text-center mb-2 p-0">
                                                            <input type="number" min="0" class="form-control text-center" value="<?php
                                                            echo $hasBreak; ?>" readonly />
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="col-xl-6 col-lg-6 border border-top-0 border-bottom-0 border-right-0 border-left-1">
                                                        <label><small>Requested Schedule</small></label>
                                                        <div class="form-group row  d-flex flex-column">
                                                            <div class="col-sm-12 text-center mb-2">
                                                                <input type="text"
                                                                    pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                                    class="form-control text-center" name="schedfrom_req"
                                                                    onkeyup="addColonTime(schedfrom_req)"
                                                                    onfocusout="addColonTimeUnfocus(schedfrom_req)"
                                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                    maxlength="5" placeholder="00:00" required />
                                                            </div>
                                                            <div class="col-sm-12 text-center mb-2">
                                                                <input type="text"
                                                                    pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}"
                                                                    class="form-control text-center" name="schedto_req"
                                                                    onkeyup="addColonTime(schedto_req)"
                                                                    onfocusout="addColonTimeUnfocus(schedto_req)"
                                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                                    maxlength="5" placeholder="00:00" required />
                                                            </div>
                                                        </div>
                                                        <label><small>Requested Break Hours</small></label>
                                                        <div class="col-sm-12 text-center mb-2 p-0">
                                                            <input type="number" min="0" class="form-control text-center"
                                                                name="break_req" value="<?php
                                                                echo $hasBreak; ?>" required />
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- SHOWS LOGS -->
                                                <div class="col-xl-12 col-lg-12 mt-2">
                                                    <label for="exampleFormControlTextarea1"
                                                        required><small>Reason</small></label>
                                                    <input type="text" class="form-control form-control-sm mb-1"
                                                        pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="CSreason"
                                                        name="csReason" required>

                                                    <div class="form-check mb-1 ml-1">
                                                        <input type="checkbox" class="form-check-input" id="exampleCheck1"
                                                            required>
                                                        <label class="form-check-label" for="exampleCheck1"><em><small>I hereby
                                                                    certify that the above infomation provided is correct.
                                                                    Any falsification of information in this regard may form
                                                                    ground for
                                                                    disciplinary action up to and including
                                                                    dismissal.</em></small>
                                                        </label>
                                                    </div>

                                                    <input type="text" class="d-none" id="empno" name="empno"
                                                        value="<?php echo $empno ?>" required>
                                                    <input type="text" class="d-none" id="datefromCS" name="datefromCS"
                                                        value="<?php echo $pick_date ?>" required>
                                                    <input type="text" class="d-none" id="cutto" name="cutto"
                                                        value="<?php echo $cutto ?>" required>
                                                    <input type="text" class="d-none" id="cutfrom" name="cutfrom"
                                                        value="<?php echo $cutfrom ?>" required>
                                                    <input type="text" class="d-none" id="cs" name="cs" value="cs" required>
                                                    <input type="text" class="d-none" id="statusCS" name="statusCS" value="<?php
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
                                                        ($userlevel == 'staff' or $Employee == 2008 or $Employee == 5182 or $Employee == 6114 or $Employee == 6115)
                                                        and ($Employee != 167 and $Employee != 2111 and $Employee != 5753 and $Employee != 6021 and $Employee != 5327 and $Employee != 2243 and $Employee != 3332 and $Employee != 3693
                                                        and $Employee != 4000 and $Employee != 4814 and $Employee != 3780 and $Employee != 2485 and $Employee != 4890 and $Employee != 401
                                                        and $Employee != 4888 and $Employee != 5975 and $Employee != 6216 and $Employee != 4139 and $Employee != 6379 and $Employee != 6082
                                                        and $Employee != 3777 and $Employee != 2363 and $Employee != 2807 and $Employee != 5712 and $Employee != 4068 and $Employee != 6121 and $Employee != 6483)
                                                    ) {
                                                        echo "pending";
                                                    }

                                                    ?>">

                                                    <div class="d-flex justify-content-end">
                                                        <input type="submit" class="btn btn-sm border-0 btn-primary"
                                                            value="Submit" name="submit"
                                                            onclick="return confirm('Are you sure you want to submit your Change Schedule request?')">
                                                    </div>
                                                </div>
                                            </form>
                                            <?php
                                        }
                                        ?>
                                        <!-- FOOTER -->
                                        <div class="text-center mt-4">
                                            <a class="small float-left"
                                                href="index.php?empno=<?php echo $row['empno']; ?>&SubmitButton=Submit"><i
                                                    class="fa fa-angle-left" aria-hidden="true"></i> Back</a>
                                            <a class="small float-right"
                                                href="pdf/print_change_sched.php?cs=cs&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">View
                                                Filed Change Schedule Request <i class="fa fa-angle-right"
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

                breakout.value = "";
                breakin.value = "";
                breaktime.value = "1";

                document.getElementById("breakout").readOnly = false;
                document.getElementById("breakin").readOnly = false;
                document.getElementById("breaktime").readOnly = true;
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


                breakout1.value = "";
                breakin1.value = "";
                breaktime1.value = "1";

                document.getElementById("breakout1").readOnly = false;
                document.getElementById("breakin1").readOnly = false;
                document.getElementById("breaktime1").readOnly = true;

            }

        }
    </script>
    <script type="text/javascript">

        $(document).ready(function () {
            var postURL = "/addmore.php";
            var i = 1;


            $('#add').click(function () {
                i++;
                $('#dynamic_field').append('<tr id="row' + i + '" class="dynamic-added"><td class="border-white"><input class="form-control form-control-user text-center" type="date" name="id[]" min="2023-01-09" max="2023-12-23" required /></td><td class="border-white"><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn-user bg-gradient-danger btn_remove">X</button></td></tr>');
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
    </script>


    <!-- TIME CONVERTER -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        function addColonTime(input_name) {
            let input_length = input_name.value.length;
            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
                console.log(true);
                if (input_length == 2) {
                    input_name.value += ":";
                }
            }
        }
        function addColonTimeUnfocus(input_name) {
            let input_length = input_name.value.length;
            if (input_length == 4) {
                let value = input_name.value;
                let conv = moment(value, 'hh:mm').format('HH:mm');

                input_name.value = conv;
            }
        }
    </script>

</html>