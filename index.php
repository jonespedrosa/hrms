<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
//entry.php
ob_start();
session_start();

include("Function/forgot_password_func.php");
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
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!-- ajax -->
  <script src="js/ajax-call.js"></script>
  <!-- SWAL -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .ensaymada-bg {
      background: url("images/mg.png");
      background-size: contain;
      background-repeat: no-repeat;
      background-position: center;
    }

    .gradient-red {
      background: rgb(125, 20, 36);
      /* background: linear-gradient(281deg, rgba(125,20,36,1) 65%, rgba(63,13,18,1) 100%); */
      background: linear-gradient(281deg, rgba(89, 15, 26, 1) 0%, rgba(150, 39, 53, 1) 53%, rgba(89, 15, 26, 1) 100%);
    }

    .maroon {
      background: #7E0000;
    }

    .text-maroon {
      color: #7E0000;
    }

    .light-gray {
      background: #F1F0F0;
    }

    .box-shadow {
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .small-font {
      font-size: 90%;
    }

    select {
      text-align-last: center;
      width: 140px;
      height: 35px;
      padding: 4px;
      border-radius: 20px;
      box-shadow: 2px 2px 8px #999;
      background: #eee;
      border: none;
      outline: none;
      display: inline-block;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      cursor: pointer;
    }

    label {
      position: relative;
    }

    label:after {
      content: '<>';
      font: 11px "Consolas", monospace;
      color: #666;
      -webkit-transform: rotate(90deg);
      -moz-transform: rotate(90deg);
      transform: rotate(90deg);
      right: 8px;
      top: 10px;
      padding: 0 0 2px;
      border-bottom: 1px solid #ddd;
      position: absolute;
      pointer-events: none;
    }

    label:before {
      content: '';
      right: 6px;
      top: 0px;
      width: 20px;
      height: 20px;
      background: #eee;
      position: absolute;
      pointer-events: none;
      display: block;
    }
  </style>

  <style>
    @media screen and (max-width: 991px) {
      .ensaymada-bg {
        background-position: top -80px left;
        background-size: cover;
      }
    }

    @media screen and (max-width: 767px) {
      .ensaymada-bg {
        background-position: top -60px left;
        background-size: cover;
      }

      #title-login {
        transition: .3s;
        opacity: 1;
      }
    }

    @media screen and (max-width: 480px) {
      .ensaymada-bg {
        background-position: top -90px left -50px;
        background-size: 150%;
      }

      #title-login {
        transition: .3s;
        opacity: 1;
      }
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

  <?php
  // FORGOT PASSWORD TOKEN REQUEST
  include('reset_password.php');

  // SESSION
  if (isset($_SESSION['user_validate']) && !isset($_GET['SubmitButton'])) {
    unset($_SESSION['user_validate']);
  }
  if (isset($_GET['SubmitButton']) == "validate" || isset($_GET['empno'])) {
    if (($_SESSION['user_validate'] ?? null) == $_GET['empno']) {
      $input = $_GET['empno'];
      @$sql = "SELECT * FROM user_info where empno = '$input'";
      @$query = $HRconnect->query($sql);
      @$row = $query->fetch_array();
      @$mothercafe = $row['mothercafe'];
      @$password = $row['secpass'];
      @$branch = $row['branch'];
      @$userid = $row['userid'];
      @$empno = $row['empno'];
      @$name = $row['name'];
      @$status = $row['status'];
      @$userlevel = $row['userlevel'];
      @$show_details = true;
    } else {
      echo '<script>
        $(function() {
        $(".thanks").delay(4000).fadeOut();

        });
        </script>
        <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
          <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
            <div class="toast-header bg-danger">
            <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Access Failed</h5>
            <small class="text-light">Just now</small>
          </div>

          <div class="toast-body">
            You must be <b class="text-danger">validated</b> in order to access the system.
          </div>
          </div>
        </div>';
      unset($_SESSION['user_validate']);
    }
  }

  // force change pass
  if (isset($_POST['change_password_prompt'])) {
    $_SESSION["user_validate"] = $_POST['empno'];
    $input = $_POST['empno'];
    $newpassword = stripslashes($_POST['new_password']);
    $sqli_sanitize = mysqli_real_escape_string($HRconnect, $newpassword);

    $sql_change_pass = "SELECT * FROM user_info where empno = '$input'";
    $query_change_pass = $HRconnect->query($sql_change_pass);
    $row_change_pass = $query_change_pass->fetch_array();

    @$branch = $row_change_pass['branch'];
    @$userid = $row_change_pass['userid'];
    @$empno = $row_change_pass['empno'];
    @$name = $row_change_pass['name'];
    @$status = $row_change_pass['status'];
    @$userlevel = $row_change_pass['userlevel'];
    @$mothercafe = $row_change_pass['mothercafe'];

    // select email
    $sql_email = "SELECT * FROM user_email WHERE empno = ?";
    $stmt = $HRconnect->prepare($sql_email);
    $stmt->bind_param("i", $empno);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_email = $result->fetch_array();
    @$show_details = true;
    @$email = $row_email['email'];
    @$isEmailVerified = $row_email['is_email_verified'];
    // update pass database
    $sql_update_pass = "UPDATE `hrms`.`user_info` SET `secpass` = '" . $sqli_sanitize . "' WHERE (`empno` = '$input');";
    $query_update_pass = $HRconnect->query($sql_update_pass);
    // Notify User of details on where did they change their pass
    if ($email == '' || empty($email) || $isEmailVerified == 0) {
      include("submit_email.php");
    } else {
      echo '<script>
      $(function() {
      $(".thanks").delay(4000).fadeOut();

      });
      </script>
      <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
        <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
          <div class="toast-header bg-success">
          <h5 class="mr-3 my-0 text-light"><i class="fa fa-check-circle mr-3" aria-hidden="true"></i>Validation Success</h5>
          <small class="text-light">Just now</small>
        </div>

        <div class="toast-body">
          You have <b class="text-success">Successfully updated your password!</b>
        </div>
        </div>
        </div>';

      sendSuccessEmail($name, $email, "Successfully Change Password");
    }
  }

  // Validation login. force changepassword if default
  if (isset($_POST['SubmitButton']) == "validate") {
    $_SESSION["user_validate"] = $_POST['empno'];
    $input = $_POST['empno'];
    @$sql = "SELECT * FROM user_info where empno = '$input'";
    @$query = $HRconnect->query($sql);
    @$row = $query->fetch_array();
    @$password = $row['secpass'];

    if ($password != $_POST['password']) {
      header("Location:index.php?&m=1");
    } else {
      if ($_POST['password'] != '1234') {
        @$branch = $row['branch'];
        @$userid = $row['userid'];
        @$empno = $row['empno'];
        @$name = $row['name'];
        @$status = $row['status'];
        @$userlevel = $row['userlevel'];
        @$mothercafe = $row['mothercafe'];
        @$show_details = true;
        // select email
        $sql_email = "SELECT * FROM user_email WHERE empno = ?";
        $stmt = $HRconnect->prepare($sql_email);
        $stmt->bind_param("i", $empno);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_email = $result->fetch_array();

        @$email = $row_email['email'];
        @$isEmailVerified = $row_email['is_email_verified'];

        // email prompt
        if ($email == '' || empty($email) || $isEmailVerified == 0) {
          include("submit_email.php");
        }
      } else {
        unset($_SESSION['user_validate']);
  ?>
        <script type="text/javascript">
          $(window).on("load", function() {
            $("#defaultPasswordModal").modal("show");
          });
        </script>
        <!-- Modal -->
        <div class="modal fade" id="defaultPasswordModal" tabindex="-1" role="dialog" aria-labelledby="defaultPasswordModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="ChangePasswordTitle">Change Your Default Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body contentModal" id="ChangePasswordContent">
                <form action="index.php" method="post">
                  <input type="text" class="d-none" name="empno" value="<?php echo $_POST['empno']; ?>">
                  <div class="row">
                    <div class="col-lg-5 col-md-12 d-flex align-items-center">
                      <img src="images/change_password.png" alt="" class="img-thumbnail border-0">
                    </div>
                    <div class="col-lg-7 col-md-12">
                      <p class="small-font">Your Password might be at <b class="text-danger">risk!</b></p>
                      <p class="small-font mt-3">Your password should contain the following:</p>
                      <ul id="password_requirements">
                        <li class="small-font text-danger">Min character = 6</li>
                        <li class="small-font text-danger">Max character = 6</li>
                        <li class="small-font text-danger">Min 1 lowercase character</li>
                        <li class="small-font text-danger">Min 1 uppercase character</li>
                        <li class="small-font text-danger">Min 1 number</li>
                        <!-- <li class="small-font text-danger">Min 1 special characters</li> -->
                      </ul>
                      <p class="m-0 small-font">New Password</p>
                      <input type="password" class="form-control password_fields" id="new_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,6}$" onkeyup="validateInput()" onfocusout="confirmPassword()" maxLength="6" required>
                      <p class="m-0 small-font validation-field text-danger mb-2">&nbsp</p>

                      <p class="m-0 small-font">Confirm Password</p>
                      <input type="password" name="new_password" class="form-control mb-1 password_fields" id="confirm_password" pattern="^(?=.*[a-z])(?=.*[A-Z]).{6,6}$" onkeyup="confirmPassword()" maxLength="6" required>
                      <p class="m-0 small-font validation-field text-danger mb-2">&nbsp</p>

                      <div class="d-flex align-items-center">
                        <input type="checkbox" name="" id="showPassword" onclick="toggleShowPassword()">
                        <p class="small-font ml-2 mb-0">Show Password</p>
                      </div>
                    </div>
                  </div>
              </div><br>

              <!-- Change Password Processing -->
              <div class="d-none" id="changePasswordProcessing">
                <div class="d-flex flex-column align-items-center">
                  <img src="images/change_password_key.gif" alt="" class="img-thumbnail border-0 col-5">
                  <h5 class="text-primary text-center mb-5">Please wait while we update your password</h5>
                </div>
              </div>

              <div class="modal-footer contentModal">
                <input id="change_password_prompt" value="Change Password" class="btn btn-primary small-font" name="change_password_prompt" onclick="changePasswordConfirm(confirmMessage())" disabled style="caret-color: transparent">
                <input type="submit" id="hidden_btn" class="d-none" disabled>
              </div>
              </form>
            </div>
          </div>
        </div>
  <?php
      }
    }
  }
  if (isset($_GET['m'])) {
    if ($_GET['m'] == 1) {
      echo '<script>
      $(function() {
      $(".thanks").delay(4000).fadeOut();

    });
    </script>
    <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
      <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
        <div class="toast-header bg-danger">
          <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Validation Failed</h5>
          <small class="text-light">Just now</small>
        </div>

        <div class="toast-body">
          The entered password is <b class="text-danger">wrong or the Employee does not exist</b>.
        </div>
      </div>
    </div>';
    } else if ($_GET['m'] == 2) {
      echo '<script>
          $(function() {
          $(".thanks").delay(4000).fadeOut();

          });
          </script>
          <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
            <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
              <div class="toast-header bg-danger">
              <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Access Failed</h5>
              <small class="text-light">Just now</small>
            </div>

            <div class="toast-body">
              You must be <b class="text-danger">validated</b> in order to access the system.
            </div>
            </div>
          </div>';
    } else if ($_GET['m'] == 3) {
      echo '<script>
          $(function() {
          $(".thanks").delay(4000).fadeOut();

          });
          </script>
          <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
            <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
              <div class="toast-header bg-danger">
              <h4 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Token Expired</h4>
              <small class="ml-4 text-light">Just now</small>
            </div>

            <div class="toast-body">
              The token is either <b class="text-danger">expired or invalid</b>.
            </div>
            </div>
          </div>';
    }
  }

  $date = date("Y-m-d");

  $date1 = new DateTime($date);
  $date1->modify('-1 day');
  $newformat1 = $date1->format('Y-m-d');

  @$sqll = "SELECT * FROM sched_time where empno = '$input' AND datefromto = '$newformat1'";
  @$queryy = $HRconnect->query($sqll);
  @$roww = $queryy->fetch_array();
  @$datefromto = $roww['datefromto'];
  @$id = $roww['id'];
  @$status1 = $roww['status'];

  if (@$status1 == 'approved' or $datefromto == $date or $id == '') {

    $newformat = date("Y-m-d");
  } else {

    $newformat = $newformat1;
  }

  @$sql1 = "SELECT * FROM sched_time where empno = '$empno' AND datefromto = '$newformat' AND status != 'deleted'";
  @$query1 = $HRconnect->query($sql1);
  @$row1 = $query1->fetch_array();

  @$Timein = $row1['M_timein'];
  @$Breakout = $row1['M_timeout'];
  @$Breakin = $row1['A_timein'];
  @$Timeout = $row1['A_timeout'];
  @$OTin = $row1['O_timein'];
  @$OTout = $row1['O_timeout'];


  @$sqldate = "SELECT COUNT(*) FROM sched_time where empno = '$empno' AND datefromto = '$newformat' AND status != 'deleted'";
  @$querydate = $HRconnect->query($sqldate);
  @$rowdate = $querydate->fetch_array();

  if (isset($_GET['SubmitButton1'])) {


    $empno2 = $_GET['empno2'];
    $userid = $_GET['userid'];

    $sql11 = "SELECT * FROM user_info WHERE userid = '$userid' AND userlevel in('mod','ac','admin')";

    $query11 = $HRconnect->query($sql11);
    $row11 = $query11->fetch_array();
    @$mod = $row11['empno'];



    $time = date("Y-m-d H:i");
    $empno = $_GET['empno'];
    $date = $_GET['date'];
    $date2 = date("Y-m-d");


    $sql2 = " UPDATE sched_time
            SET M_timein = '$time',
                min_empno = '$mod',
                m_in_status = 'Approved'
            WHERE empno = '$empno' AND datefromto = '$date' AND status != 'deleted'";

    $HRconnect->query($sql2);


    $date_time = date("Y-m-d H:i");
    $inserted = "Successfully Time IN Code Used:" . $empno2;
    $action = "Time IN - " . $time;

    $sql3 = "INSERT INTO log (empno, action, inserted, date_time)
         VALUES('$empno', '$action', '$inserted','$date_time')";

    $HRconnect->query($sql3);

    header("location:index.php?modal=$empno");
  }

  if (isset($_GET['SubmitButton2'])) {



    $empno2 = $_GET['empno2'];
    $userid = $_GET['userid'];

    $sql11 = "SELECT * FROM user_info WHERE userid = '$userid' AND userlevel in('mod','ac','admin')";

    $query11 = $HRconnect->query($sql11);
    $row11 = $query11->fetch_array();


    $time = date("Y-m-d H:i");
    $timebreak = $_GET['timebreak'];
    $empno = $_GET['empno'];
    $date = $_GET['date'];
    $date2 = date("Y-m-d");

    $sql2 = " UPDATE sched_time
            SET M_timeout = '$time',
                m_o_status = 'Approved',
                break = '$timebreak'

            WHERE empno = '$empno' AND datefromto = '$date' AND status != 'deleted'";

    $HRconnect->query($sql2);

    $date_time = date("Y-m-d h:i");
    $inserted = "Successfully Break OUT Code Used:" . $empno2;
    $action = "Break OUT - " . $time . " - " . $timebreak;
    $sql3 = "INSERT INTO log (empno, action, inserted, date_time)
         VALUES('$empno', '$action', '$inserted','$date_time')";

    $HRconnect->query($sql3);


    header("location:index.php?modal=$empno");
  }


  if (isset($_GET['SubmitButton3'])) {


    $empno2 = $_GET['empno2'];
    $userid = $_GET['userid'];

    $sql11 = "SELECT * FROM user_info WHERE userid = '$userid' AND userlevel in('mod','ac','admin')";

    $query11 = $HRconnect->query($sql11);
    $row11 = $query11->fetch_array();
    @$mod = $row11['empno'];


    $time = date("Y-m-d H:i");
    $empno = $_GET['empno'];
    $date = $_GET['date'];
    $date2 = date("Y-m-d");

    $sql2 = " UPDATE sched_time
            SET A_timein = '$time',
                a_in_status = 'Approved'

            WHERE empno = '$empno' AND datefromto = '$date' AND status != 'deleted'";

    $HRconnect->query($sql2);


    $date_time = date("Y-m-d h:i");
    $inserted = "Successfully Break IN Code Used:" . $empno2;
    $action = "Break IN - " . $time;

    $sql3 = "INSERT INTO log (empno, action, inserted, date_time)
         VALUES('$empno', '$action', '$inserted','$date_time')";

    $HRconnect->query($sql3);

    header("location:index.php?modal=$empno");
  }


  if (isset($_GET['SubmitButton4'])) {

    $empno2 = $_GET['empno2'];
    $userid = $_GET['userid'];

    $sql11 = "SELECT * FROM user_info WHERE userid = '$userid' AND userlevel in('mod','ac','admin')";

    $query11 = $HRconnect->query($sql11);
    $row11 = $query11->fetch_array();
    @$mod = $row11['empno'];

    $time = date("Y-m-d H:i");
    $empno = $_GET['empno'];
    $date = $_GET['date'];
    $date2 = date("Y-m-d");

    $sql2 = " UPDATE sched_time
            SET A_timeout = '$time',
            a_o_status = 'Approved'
            WHERE empno = '$empno' AND datefromto = '$date' AND status != 'deleted'";

    $HRconnect->query($sql2);

    $date_time = date("Y-m-d h:i");
    $inserted = "Successfully Time OUT Code Used:" . $empno2;
    $action = "Time OUT - " . $time;

    $sql3 = "INSERT INTO log (empno, action, inserted, date_time)
         VALUES('$empno', '$action', '$inserted','$date_time')";

    $HRconnect->query($sql3);


    header("location:index.php?modal=$empno");
  }


  if (isset($_GET['Nobreak'])) {
    $empno2 = $_GET['empno2'];
    $userid = $_GET['userid'];

    $sql11 = "SELECT COUNT(*) as pass FROM user_info WHERE userid = '$userid' AND userlevel in('mod','ac','admin')";

    $query11 = $HRconnect->query($sql11);
    $row11 = $query11->fetch_array();


    $time = 'No Break';
    $empno = $_GET['empno'];
    $date = $_GET['date'];
    $date2 = date("Y-m-d");


    $sql2 = " UPDATE sched_time
            SET M_timeout = '$time',
            m_o_status = 'Approved',
            a_in_status = 'Approved',
            A_timein = '$time',
			break = '1'
            WHERE empno = '$empno' AND datefromto = '$date' AND status != 'deleted'";

    $HRconnect->query($sql2);


    $date_time = date("Y-m-d h:i");
    $inserted = "Successfully No Break Code Used:" . $empno2;
    $action = "Break OUT/IN - " . $time;

    $sql3 = "INSERT INTO log (empno, action, inserted, date_time)
         VALUES('$empno', '$action', '$inserted','$date_time')";

    $HRconnect->query($sql3);

    header("location:index.php?modal=$empno");
  }
  ?>


</head>

<?php if (@$_GET["modal"] != '') { ?>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="loginModalLabel">
            Time Input
          </h6><a type="button" class="text-muted" href="index.php">x</a>
        </div>

        <div class="modal-body">
          <center>
            <img src="images/verified.gif" alt="" style="width:100px;height:100px;">
            <h4>Verified</h4>
            <?php
            // Get the current date and time with day of the week
            $currentDateTimeWithDay = date('l, Y-m-d H:i:s');

            // Display the result
            echo "" . $currentDateTimeWithDay;
            ?>
          </center>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(window).on('load', function() {
      $('#loginModal').modal({
        backdrop: 'static',
        keyboard: false
      });
    });
  </script>
<?php } ?>


<body class="bg-gradient-muted">

  <?php include("navbar-header-mg.php"); ?>

  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-12 mt-5">
        <div class="card o-hidden border-0 box-shadow mt-3 light-gray">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-5">
                <div class="p-4">
                  <!-- <div class="user p-3"> -->
                  <div class="form-group">
                    <form method="POST" action="index.php" id="employee_details_form">
                      <?php
                      if (!isset($show_details)) {
                        echo '
                                                  <div class="">
                                                  <p class="m-0" style="font-size:100%">Employee no.</p>
                                                  <input type="number"
                                                      class="form-control py-4 text-center box-shadow"
                                                      id="empid"
                                                      name="empno"
                                                      autofocus
                                                      required
                                                      style="font-size:100%"
                                                      onkeyup="enableProceed()"
                                                    >
                                                    <p class="m-0 pt-2" style="font-size:100%">Password</p>
                                                    <input type="password" name="password" id="passwordFieldModal" class="form-control py-4 text-center box-shadow"
                                                      onkeyup="enableProceed()" required>

													<div class="d-flex justify-content-end py-2">
														<a class="m-0" href="forgotpassword.php" style="font-size:100%">Forgot password?</a>
													</div>

                                                  <div class="buttons d-flex mt-3 justify-content-between">
                                                    <a href="index.php" class="btn btn-outline-danger text-underline rounded " style="width: 45%; font-size:80%"><u>Clear</u></a>
                                                    <button type="submit" id="submit_empid" name="SubmitButton" class="btn btn-primary rounded d-flex align-items-center justify-content-center" style="width: 45%; font-size:80%" onclick="validatePassword()" disabled>
                                                      <span class="" role="status" aria-hidden="true"></span>
                                                      <p class="m-0 text-center">Validate</p>
                                                      </button>
                                                  </div>

                                                  </div>';
                      }

                      ?>


                      <?php
                      $coreValuesLink = '';
                      if (isset($show_details)) {
                        if ($show_details == true) {
                          echo '
                                                  <div class="">
                                                    <p class="m-0 text-dark" style="font-size:16px; font-weight:bold;">Employee Details</p>
                                                    <input type="text" class="form-control py-4 box-shadow text-center text-uppercase" readonly
                                                      id="empname" aria-describedby="empname"
                                                      placeholder="" value="' . @$name . '" style="font-size:12px; font-weight:bold;">
                                                    <input type="text" class="form-control py-4 mt-2 box-shadow text-center text-uppercase" readonly
                                                      id="empbranch" aria-describedby="empbranch"
                                                      placeholder="" value="' . @$branch . '" style="font-size:12px; font-weight:bold;">
                                                    <hr>
                                                    <div class="d-flex justify-content-end align-items-center">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right text-danger mr-2" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                                      </svg>
                                                      <a href="index.php" class="text-danger text-decoration-none" style="font-size:16px; font-weight:bold;">Logout</a>
                                                    </div>
                                                  </div>
                                                  ';

                          // Assuming $empno, $name, and $position are already set
                          $empno = @$row['empno'];
                          $name = @$row['name'];
                          $position = @$row['position'];

                          // URL encode the variables to ensure special characters are properly handled
                          $empno = urlencode($empno);
                          $name = urlencode($name);
                          $position = urlencode($position);

                          // Set the link variable if show_details is true
                          $coreValuesLink = '<a style="font-family: \'Alkatra\', cursive; font-size: 120%; color: #7E0000" href="forms-category.php?empno=' . $empno . '&name=' . $name . '&position=' . $position . '" target="_blank"><i>Forms</i></a>';
                        }
                      }
                      ?>
                    </form>
                  </div>
                  <?php
                  if ((@$name != '' and $status != 'Saved' and $rowdate['COUNT(*)'] > 0)) {
                  ?>
                    <?php $asd = "<span id='date_time2'></span>"; ?>
                    <form class="user">
                      <div class="form-group">
                        <form method="GET">

                          <?php
                          $IDnumber = array(24, 1964, 5752, 1331, 76, 109, 819, 4378, 9999);
                          if (in_array($empno, $IDnumber)) {
                          ?>
                            <hr>
                            <center>
                              <h6><i class="fa fa-clock" aria-hidden="true"></i> Current Time</h6>
                              <h1>
                                <span id="date_time"></span>
                                <script type="text/javascript">
                                  window.onload = date_time('date_time');

                                  function date_time(id) {
                                    date = new Date();
                                    year = date.getFullYear();
                                    month = date.getMonth();
                                    months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
                                    d = date.getDate();
                                    day = date.getDay();
                                    days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                                    h = date.getHours();
                                    if (h < 10) {
                                      h = "0" + h;
                                    }
                                    m = date.getMinutes();
                                    if (m < 10) {
                                      m = "0" + m;
                                    }
                                    s = date.getSeconds();
                                    if (s < 10) {
                                      s = "0" + s;
                                    }
                                    result = h + ':' + m + ':' + s;
                                    document.getElementById(id).innerHTML = result;
                                    setTimeout('date_time("' + id + '");', '1000');
                                    return true;
                                  }
                                </script>
                              </h1>
                            </center>
                          <?php
                          }

                          ?>

                          <input type="text" value="<?php echo $userid; ?>" hidden name="userid">
                          <input type="text" hidden name="empno" value="<?php echo $empno; ?>">
                          <input type="text" hidden name="date" value="<?php echo $newformat; ?>">

                          <?php

                          $IDnumber = array(24, 1964, 5752, 1331, 76, 109, 819, 4378, 9999);
                          if (in_array($empno, $IDnumber)) {

                            if ($Timein == '' and $rowdate['COUNT(*)'] > 0) { ?>
                              <div class="p-3">
                                <input type="button" class="btn btn-primary rounded btn-block bg-outline-gradient-primary" value="Time In" data-toggle="modal" data-target="#myModal">
                              </div>

                            <?php
                            } elseif ($Breakout == '' and $rowdate['COUNT(*)'] > 0) { ?>

                              <div class="p-3">
                                <input min="1" max="5" type="number" class="form-control form-control-user text-center" name="timebreak" placeholder="Enter Break Time Hour" required>
                              </div>
                              <div class="p-3">
                                <input type="button" class="btn btn-primary rounded btn-block bg-outline-gradient-primary mb-2" value="Break Out" data-toggle="modal" data-target="#myModal">
                              </div>


                            <?php
                            } elseif ($Breakin == '' and $rowdate['COUNT(*)'] > 0) { ?>
                              <div class="p-3">
                                <input type="button" class="btn btn-primary rounded btn-block bg-outline-gradient-primary" value="Break In" data-toggle="modal" data-target="#myModal">
                              </div>
                            <?php
                            } elseif ($Timeout == '' and $rowdate['COUNT(*)'] > 0) { ?>
                              <div class="p-3">
                                <input type="button" class="btn btn-primary rounded btn-block bg-outline-gradient-primary" value="Time Out" data-toggle="modal" data-target="#myModal">
                              </div>
                            <?php
                            } elseif ($Timeout != '' and $rowdate['COUNT(*)'] > 0) { ?>
                              <div class="p-3">
                                <center>
                                  <p>You have already completed your time inputs; please review your DTR.</p>
                                </center>
                              </div>
                        <?php
                            }
                          }
                        }
                        ?>
                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                          <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">×</button>
                              </div>
                              <div class="modal-body">
                                <center>
                                  <h5> Do you want to add this time inputs? </h5>
                                </center>
                              </div>
                              <div class="modal-footer">

                                <?php
                                if ($Timein == '' and $rowdate['COUNT(*)'] > 0) { ?>
                                  <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="SubmitButton1">
                                <?php
                                } elseif ($Breakout == '' and $rowdate['COUNT(*)'] > 0) { ?>


                                  <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="SubmitButton2">
                                  <br>

                                <?php
                                } elseif ($Breakin == '' and $rowdate['COUNT(*)'] > 0) { ?>

                                  <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="SubmitButton3">

                                <?php
                                } elseif ($Timeout == '' and $rowdate['COUNT(*)'] > 0) { ?>

                                  <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="SubmitButton4">

                                <?php
                                } elseif ($OTin == '' and $status != 'Saved' and $rowdate['COUNT(*)'] < 0) { ?>

                                  <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="SubmitButton5">

                                <?php
                                } elseif ($OTout == '' and $status != 'Saved' and $rowdate['COUNT(*)'] < 0) { ?>

                                  <input type="submit" class="btn btn-primary btn-user btn-block btn1" value="Confirm" name="SubmitButton6">

                                <?php
                                }
                                ?>
                              </div>
                            </div>

                          </div>
                        </div>

                        </form>


                        <form method="GET">

                          <div id="myModal1" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                              <!-- Modal content-->
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body">
                                  <center>
                                    <h5> Do you want to add this time inputs? </h5>
                                  </center>
                                </div>
                                <div class="modal-footer">
                                  <input type="text" value="<?php echo $userid; ?>" hidden name="userid">
                                  <?php if ($Breakout == '' and @$name != '' and $Timein != '') { ?>



                                    <input type="text" hidden name="empno" value="<?php echo $empno ?>">
                                    <input type="text" hidden name="date" value="<?php echo $newformat ?>">
                                    <input type="submit" class="btn btn-primary btn-user btn-block bg-outline-gradient-primary" value="Confirm" name="Nobreak">

                                  <?php
                                  }
                                  ?>

                                </div>
                              </div>

                            </div>
                          </div>

                          <?php
                          $IDnumber = array(24, 1964, 5752, 1331, 76, 109, 819, 4378, 9999);
                          if (@in_array($empno, $IDnumber)) {

                            if ($Breakout == '' and @$name != '' and $Timein != '') { ?>



                              <input type="text" hidden name="empno" value="<?php echo $empno ?>">
                              <input type="text" hidden name="date" value="<?php echo $newformat ?>">

                              <div class="px-3">
                                <input type="button" class="btn btn-warning rounded btn-block bg-outline-gradient-warning" value="No Break" data-toggle="modal" data-target="#myModal1">
                              </div>

                          <?php
                            }
                          }
                          ?>
                        </form>
                      </div>

                </div>
                <!-- Half of the col+ -->
                <?php if ($rowdate['COUNT(*)'] <= 0) { ?>
                  <div class="col-lg-7 col-mg-12">
                    <div class="ensaymada-bg d-flex align-items-center justify-content-end" style="height: 100%">
                      <!-- <div class="ribbon bg-warning mr-3" style="width:2%; height: 100%"></div> -->
                      <div class="d-flex bg-light pl-5 box-shadow" id="title-login" style="border-radius: 50px 0 0 50px">
                        <p class="py-3 mr-3 mt-3" style="font-family: 'Alkatra', cursive; font-size: 120%; color: #7E0000">HRMS</p>
                        <p class="border-left py-3 pl-3 mt-3 pr-4" style="font-family: 'Alkatra', cursive; font-size: 120%; color: #7E0000">Employee Portal</p>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>

              <!-- HALF OF THE COL -->
              <?php if (@$empno != '' and @$name != '' and $rowdate['COUNT(*)'] != 0) {
                @$sql4 = "SELECT * FROM sched_info where empno = '$input' AND status = 'Pending' ORDER BY datefrom ASC";
                @$query4 = $HRconnect->query($sql4);
                @$row4 = $query4->fetch_array();
                @$datefrom4 = $row4['datefrom'];
                @$dateto4 = $row4['dateto'];
                @$userid = $row4['userid'];
                @$status = $row['status'];
              ?>

                <!-- HALF -->
                <div class="col-lg-7 col-md-12">
                  <div class="ensaymada-bg d-flex flex-column align-items-end justify-content-center" style="height: 100%;">
                    <div class="list-group text-right gradient-red pl-2 shadow-lg" style="border-radius: 20px 0 0 20px">
                      <p style="font-family: Times New Roman, cursive" class="font-italic m-4 text-light">Is there
                        anything you'd like to file?</p>
                      <div class="list-group-item list-group-item-action d-flex flex-column bg-light box-shadow">
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="createovertime.php?ot=ot&empno=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>">Overtime
                        </a>
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="createovertime.php?ut=ut&empno=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>">OBP</a>
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="create-leave.php?leave=leave&empno=<?php echo $empno ?>">Leave</a>
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="concerns.php?concern=concern&dtrconcern&empno=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>">Concerns
                        </a>
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="createovertime.php?wdo=wdo&empno=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>">Working
                          Day Off</a>
                        <a class="list-group-item list-group-item-action btn text-center small-font effect-shine text-maroon" href="create-change-sched.php?cs=cs&empno=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>">Change
                          Schedule</a>
                      </div>


                      <div class="d-flex">


                        <a style="font-size: 80%" class="effect-shine text-light m-4" href="../hrms/my-training-courses.php?empno=<?php echo $empno ?>" target="_blank">
                          <i class="fa fa-chalkboard-teacher text-dark bg-light rounded-circle p-2"></i>
                          &nbspView Trainings
                          <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </a>


                        <a style="font-size: 80%" class="effect-shine text-light m-4" href="../hrms/pdf/print_schedule.php?empid=<?php echo $empno ?>&cutfrom=<?php echo $datefrom4; ?>&cutto=<?php echo $dateto4; ?>&userid=<?php echo $userid; ?>" target="_blank">
                          <i class="far fa-calendar text-dark bg-light rounded-circle p-2"></i>
                          &nbspView DTR
                          <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </a>

                      </div>


                    </div>
                  </div>
                </div>
              <?php } ?>
              <div class="col-lg-12">
                <footer class="pb-4">
                  <div class="container my-auto">
                    <hr>
                    <div class="text-center">
                      <a style="font-family: 'Alkatra', cursive; font-size: 120%; color: #7E0000" href="/video/tutorial.php"><i>Tutorial</i></a>
                      &nbsp &nbsp &nbsp &nbsp
                      <a style="font-family: 'Alkatra', cursive; font-size: 120%; color: #7E0000" href="/video/faqs.php"><i>FAQs</i></a>
                      &nbsp &nbsp &nbsp &nbsp
                      <?php echo $coreValuesLink; ?>
                    </div>
                    <br>
                    <div class="copyright text-center my-auto">
                      <span><i>Copyright © Mary Grace Foods Inc. 2019</i></span>
                    </div>
                  </div>
                </footer>
              </div>
            </div>
          </div>
          <!-- <a href="car_reservation/login.php" class="float-right mt-2" style="font-size:80%">Reserve a car?</a>      -->
        </div>
      </div>
    </div>
    <div class="container d-flex justify-content-end">

    </div>
    <?php echo @$message;

    ?>

    <?php
    if ($rowdate['COUNT(*)'] == 0 and @$name != '') {
    ?>
      <script>
        $(function() {
          $(".thanks").delay(6000).fadeOut();

        });
      </script>

      <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
        <div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px; z-index: 9999">
          <div class="toast-header bg-danger">
            <h4 class="mr-auto my-0 text-white"><i class="fa fa-times-circle" aria-hidden="true"></i> Schedule</h5>
              <small class="text-white">just now</small>
          </div>
          <div class="toast-body">
            <b class="text-danger">You Dont have Schedule</b>. please coordinate to your immediate superior. Thank you!
          </div>
        </div>
      </div>
    <?php
    } else if (isset($_POST['SubmitButton']) == "validate") {
      if ($_POST['password'] != '1234') {
        echo '<script>
        $(function() {
        $(".thanks").delay(4000).fadeOut();

        });
        </script>
        <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
          <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
            <div class="toast-header bg-success">
            <h5 class="mr-3 my-0 text-light"><i class="fa fa-check-circle mr-3" aria-hidden="true"></i>Validation Success</h5>
            <small class="text-light">Just now</small>
          </div>

          <div class="toast-body">
            You have <b class="text-success">Successfully validated!</b>
          </div>
          </div>
          </div>';
      }
    }
    ?>


    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript">
      // Change Password Confirmation
      window.addEventListener('show-alert', event => {
        swal(event.detail.title, event.detail.message, event.detail.type);
      });

      function changePasswordConfirm(valueFromMessage) {
        if (valueFromMessage == true) {
          // display none modal content
          let modalContents = document.getElementsByClassName('contentModal');
          modalContents[0].classList.add('d-none');
          modalContents[1].classList.add('d-none');

          let changePasswordProcessing = document.getElementById('changePasswordProcessing');
          changePasswordProcessing.classList.remove('d-none');

          let hidden_btn = document.getElementById('hidden_btn');
          hidden_btn.removeAttribute('disabled');
          hidden_btn.click();
        } else {
          return false;
        }
      }

      function confirmMessage() {
        return confirm("Is this your new password?");
      }

      // password validation
      function validateInput() {
        let input_field = document.getElementById('new_password');
        let list_items = document.getElementById('password_requirements').getElementsByTagName('li');
        let special_chars = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

        // Min 6 character
        if (input_field.value.length >= 6) {
          list_items[0].classList.remove('text-danger');
          list_items[0].classList.add('text-success');
        } else {
          list_items[0].classList.remove('text-success');
          list_items[0].classList.add('text-danger');
        }

        // Max 15 character
        if (input_field.value.length > 0) {
          list_items[1].classList.remove('text-danger');
          list_items[1].classList.add('text-success');
        } else {
          list_items[1].classList.remove('text-success');
          list_items[1].classList.add('text-danger');
        }

        // Contains lowercase
        if (Boolean(input_field.value.match(/[a-z]/))) {
          list_items[2].classList.remove('text-danger');
          list_items[2].classList.add('text-success');
        } else {
          list_items[2].classList.remove('text-success');
          list_items[2].classList.add('text-danger');
        }

        // Contains uppercase
        if (Boolean(input_field.value.match(/[A-Z]/))) {
          list_items[3].classList.remove('text-danger');
          list_items[3].classList.add('text-success');
        } else {
          list_items[3].classList.remove('text-success');
          list_items[3].classList.add('text-danger');
        }

        // Contains uppercase
        if (Boolean(input_field.value.match(/[0-9]/))) {
          list_items[4].classList.remove('text-danger');
          list_items[4].classList.add('text-success');
        } else {
          list_items[4].classList.remove('text-success');
          list_items[4].classList.add('text-danger');
        }

        // Contains special char
        // if(special_chars.test(input_field.value)){
        //   list_items[5].classList.remove('text-danger');
        //   list_items[5].classList.add('text-success');
        // }else{
        //   list_items[5].classList.remove('text-success');
        //   list_items[5].classList.add('text-danger');
        // }
      }

      function confirmPassword() {
        let input_field = document.getElementById('new_password');
        let confirm_field = document.getElementById('confirm_password');
        let change_pass_btn = document.getElementById('change_password_prompt');
        let validation_field = document.getElementsByClassName('validation-field');
        let isMatch = input_field.value == confirm_field.value;

        if (validateRequirements() && isMatch) {
          change_pass_btn.removeAttribute('disabled');
          validation_field[0].innerHTML = "";
          validation_field[1].innerHTML = "";
        } else if (isMatch) {
          change_pass_btn.setAttribute('disabled', '');
          // validation_field[0].innerHTML = "Password does not meet requirements";
          validation_field[1].innerHTML = "Password does not meet requirements";
        } else {
          change_pass_btn.setAttribute('disabled', '');
          // validation_field[0].innerHTML = "Password does not match";
          validation_field[1].innerHTML = "Password does not match";
        }
      }

      function validateRequirements() {
        let confirm_requirements = document.getElementById('password_requirements').getElementsByTagName('li');
        for (let ctr = 0; ctr < confirm_requirements.length; ctr++) {
          if (!(confirm_requirements[ctr].classList.contains('text-success'))) {
            return false;
          }
        }
        return true;
      }

      function toggleShowPassword() {
        let password_fields = document.getElementsByClassName("password_fields");
        if (password_fields[0].type === "password") {
          password_fields[0].type = "text";
          password_fields[1].type = "text";
        } else {
          password_fields[0].type = "password";
          password_fields[1].type = "password";
        }
      }

      // Every time a modal is shown, if it has an autofocus element, focus on it.
      $('.modal').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
      });
      // window.onload = enableProceed();

      // entering automatic
      let passwordFieldModal = document.getElementById('passwordFieldModal');
      let empidField = document.getElementById('empid');
      passwordFieldModal.addEventListener('keyup', function(e) {
        let inputLength = passwordFieldModal.value.length;
        if (inputLength > 0) {
          document.getElementById('submit_empid').removeAttribute('disabled');
        } else {
          document.getElementById('submit_empid').setAttribute('disabled', '');
        }

        if (e.key == "Enter") {
          let spinnerValidate = document.getElementById('submit_empid');
          spinnerValidate.click();
          if (passwordFieldModal.value.length > 0) {
            e.preventDefault();
            let span_spinnerValidate = spinnerValidate.getElementsByTagName('span')[0];
            let text_spinnerValidate = spinnerValidate.getElementsByTagName('p')[0];

            let classesArr = ['spinner-border', 'spinner-border-sm', 'mr-2'];
            span_spinnerValidate.classList.add(...classesArr);
            text_spinnerValidate.innerHTML = 'Validating';
            document.getElementById('submit_empid').setAttribute('disabled', '');
          }

        }
      });

      function enableProceed() {
        let empIdLength = document.getElementById('empid').value.length;
        if (empIdLength > 4) {
          document.getElementById('empid').value = document.getElementById('empid').value.substr(0, 4);
        }
      }

      function validatePassword() {
        let emp_form = document.getElementById('employee_details_form');
        let spinnerValidate = document.getElementById('submit_empid');
        let span_spinnerValidate = spinnerValidate.getElementsByTagName('span')[0];
        let text_spinnerValidate = spinnerValidate.getElementsByTagName('p')[0];

        let classesArr = ['spinner-border', 'spinner-border-sm', 'mr-2'];
        span_spinnerValidate.classList.add(...classesArr);
        text_spinnerValidate.innerHTML = 'Validating';
      }
    </script>
</body>

</html>

<?php
ob_end_flush();
?>