<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();

// live
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


    if (isset($_GET['approved'])) {

        $Employee = $_GET["empno"];
        $type = $_GET["type"];
        $reason = $_GET["reason"];
        $vlnumber1 = $_GET["vlnumber"];
        $timedate = date("Y-m-d H:i");

        foreach ($_GET["date"] as $key => $value) {
            $date = 1;

            @$total += $date;

        }


        $sql1 = "SELECT * FROM user_info WHERE empno = $Employee";
        $query1 = $HRconnect->query($sql1);
        $row1 = $query1->fetch_array();

        $vl = $row1['vl'];



        if ($vl >= $total) {
            $update1 = " UPDATE vlform 
            SET vlstatus = 'approved',
            apptimedate = '$timedate',
            approver = '$user'
            WHERE empno = '$Employee' AND vlnumber = '$vlnumber1' AND vlstatus = 'pending'";
            $HRconnect->query($update1);
            $overall = $vl - $total;

            $update2 = " UPDATE user_info 
            SET vl = '$overall'
            WHERE empno = '$Employee'";


            if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {


                $HRconnect->query($update2);


                header("location:approvals.php?vl=vl&m=3");


            }
            if (
                $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
                or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
                or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
            ) {


                $HRconnect->query($update2);

                header("location:approvals.php?vl=vl&m=3");

            }
            if (
                $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
                or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
                or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
                or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
                or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
                or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
            ) {


                $HRconnect->query($update2);

                header("location:../leave.php?pending=pending&m=3");

            }
            if ($userlevel == 'mod') {


                $HRconnect->query($update2);

                header("location:../leave.php?pending=pending&m=3");

            }

        } else {

            if ($userlevel == 'ac' or $userlevel == 'admin' or $userlevel == 'master') {
                echo "<script type='text/javascript'>alert('Failed: Not enough leave please cancel and refile your request exact to your leave credit. Thank you!');
        window.location.href='approvals.php?vl=vl';    
		</script>";

            }
            if ($userlevel == 'mod') {
                echo "<script type='text/javascript'>alert('Failed: Not enough leave please cancel and refile your request exact to your leave credit. Thank you!');
        window.location.href='/hrms/leave.php?pending=pending';
        </script>";
            }
        }
    }


    if (isset($_GET['cancel'])) {

        $Employee = $_GET["empno"];
        $type = $_GET["type"];
        $reason = $_GET["reason"];


        $update1 = " UPDATE vlform 
			SET vlstatus = 'canceled',
             apptimedate = '$timedate',
            approver = '$user'
            WHERE empno = '$Employee' AND vltype = '$type' AND vlreason = '$reason' AND vlstatus = 'pending'";

        $HRconnect->query($update1);

        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {
            header("location:approvals.php?vl=vl&m=4");

        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
            or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            header("location:approvals.php?vl=vl&m=4");

        }
        if (
            $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
            or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
            or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {
            header("location:../leave.php?pending=pending&m=4");

        }
        if ($userlevel == 'mod') {
            header("location:../leave.php?pending=pending&m=4");
        }
    }


    if (isset($_GET['otapprove'])) {
        $id = $_GET["id"];
        $Employee = $_GET["empno"];
        @$datefrom = $_GET["datefrom"];
        $timedate = date("Y-m-d H:i");


        if ($userlevel == 'ac' or $userlevel == 'admin' or $userlevel == 'master') {
            $update1 = " UPDATE overunder 
			SET otstatus = 'approved',
                apptimedate = '$timedate',
                approver = '$user'
            WHERE ovid = '$id'";
            $HRconnect->query($update1);
        } else {

            $update1 = " UPDATE overunder 
            SET otstatus = 'pending2',
                p_apptimedate = '$timedate',
                 p_approver = '$user'
            WHERE ovid = '$id'";


        }
        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073 and $_SESSION['empno'] != 204) {


            $HRconnect->query($update1);

            header("location:approvals.php?ot=ot&m=2");

        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
            or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {


            $HRconnect->query($update1);

            header("location:approvals.php?ot=ot&m=2");

        }
        if (
            $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
            or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
            or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {


            $HRconnect->query($update1);

            header("location:../overtime.php?pending=pending&m=2");


        }
        if ($userlevel == 'mod') {

            $HRconnect->query($update1);

            header("location:../overtime.php?pending=pending&m=2");

        }
    }


    if (isset($_GET['otcancel'])) {
        $id = $_GET["id"];

        @$Employee = $_GET["empno"];
        @$type = $_GET["type"];
        @$datefrom = $_GET["datefrom"];
        @$timedate = date("Y-m-d H:i");

        $update1 = " UPDATE overunder 
			SET otstatus = 'canceled',
             apptimedate = '$timedate',
            approver = '$user'
            WHERE ovid = $id";


        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {
            $HRconnect->query($update1);


            header("location:approvals.php?ot=ot&m=5");

        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
            or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            $HRconnect->query($update1);


            header("location:approvals.php?ot=ot&m=5");


        }
        if (
            $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
            or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
            or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {
            $HRconnect->query($update1);


            header("location:../overtime.php?pending=pending&m=5");

        }
        if ($userlevel == 'mod') {

            $HRconnect->query($update1);

            header("location:../overtime.php?pending=pending&m=5");

        }

    }



    if (isset($_GET['utapprove'])) {

        $timedate = date("Y-m-d H:i");

        $id = $_GET["id"];
        $Employee = $_GET["empno"];
        $datefrom = $_GET["datefrom"];
        $obploc = $_GET["obploc"];
        $obpreason = $_GET["obpreason"];
        $timein = $datefrom . " " . $_GET["timein"];
        $breakout = $_GET["breakout"];
        $breakin = $_GET["breakin"];
        $timeout = $datefrom . " " . $_GET["timeout"];
        $break = $_GET["break"];


        if (strtotime($timein) > strtotime($timeout)) {
            $timeout = date("Y-m-d H:i", strtotime($timeout . " +1 day"));
        }
        if ($userlevel == 'mod') {
            $update1 = " UPDATE obp 
            SET status = 'Pending2',
              p_app_timedate = '$timedate',
                p_approval = '$user'
            WHERE obpid = '$id'";
        } else {

        }
        if ($userlevel == 'ac' or $userlevel == 'admin' or $userlevel == 'master') {

            $update1 = " UPDATE obp 
            SET status = 'Approved',
                app_timedate = '$timedate',
                approval = '$user'
            WHERE obpid = '$id'";


            if ($_GET["breakout"] != 'No Break' and $_GET["breakin"] != 'No Break') {
                $update2 = "UPDATE sched_time 
              SET M_Timein = '$timein',
                  M_timeout = '$breakout',
                  A_timein = '$breakin',
                  A_timeout = '$timeout',
                  Break = '$break',
                  m_in_status = 'Approved',
                  m_o_status = 'Approved',
                  a_in_status = 'Approved',
                  a_o_status = 'Approved' 
                  WHERE empno = '$Employee' AND datefromto = '$datefrom' ";
            } else {

                $update2 = "UPDATE sched_time 
              SET M_Timein = '$timein',
                  M_timeout = 'No Break',
                  A_timein = 'No Break',
                  A_timeout = '$timeout',
                  Break = '$break',
                  m_in_status = 'Approved',
                  m_o_status = 'Approved',
                  a_in_status = 'Approved',
                  a_o_status = 'Approved' 
                  WHERE empno = '$Employee' AND datefromto = '$datefrom' ";

            }

            $HRconnect->query($update2);


        }
        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {


            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=1");

        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
            or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {


            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=1");

        }
        if (
            $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
            or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
            or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {


            $HRconnect->query($update1);

            header("location:../obp.php?pendingut=pendingut&m=1");

        }
        if ($userlevel == 'mod') {


            $HRconnect->query($update1);

            header("location:../obp.php?pendingut=pendingut&m=1");

        }

    }


    if (isset($_GET['utcancel'])) {

        $id = $_GET["id"];
        $timedate = date("Y-m-d H:i");
        $update1 = " UPDATE obp 
            SET status = 'canceled',
           app_timedate = '$timedate',
                approval = '$user'
            WHERE obpid = '$id'";

        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {
            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=6");

        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 
            or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 4647 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=6");


        }
        if (
            $_SESSION['empno'] == 271 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 4647
            or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 159
            or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 5356 or $_SESSION['empno'] == 885 or $_SESSION['empno'] == 5975
            or $_SESSION['empno'] == 5361 or $_SESSION['empno'] == 3178 or $_SESSION['empno'] == 5515 or $_SESSION['empno'] == 5452
            or $_SESSION['empno'] == 4811 or $_SESSION['empno'] == 2684 or $_SESSION['empno'] == 884
            or $_SESSION['empno'] == 5584 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 24
        ) {
            $HRconnect->query($update1);


            header("location:../obp.php?pendingut=pendingut&m=6");

        }
        if ($userlevel == 'mod') {

            $HRconnect->query($update1);

            header("location:../obp.php?pendingut=pendingut&m=6");

        }
    }


    ?>  
 

        <!DOCTYPE html>
        <html lang="en">

        <head>

            <meta charset="uft-8"/>
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
            <!-- SWAL -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
            <!-- JavaScript -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                 <!-- AJAX -->
                 <script src="../js/ajax-leave.js"></script>

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
                                                <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change Schedule</a>	
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
                        if (isset($_GET["ot"]) == "ot") {

                            $id = $_GET['id'];



                            $sql = "SELECT * FROM user_info 
           			JOIN overunder on user_info.empno = overunder.empno
                    JOIN sched_time on user_info.empno = sched_time.empno
                    WHERE overunder.ovid = '$id' AND otdatefrom = datefromto";
                            $query = $HRconnect->query($sql);
                            $row = $query->fetch_array();

                            $ottype2 = $row['ottype'];


                            $time1 = strtotime($row['A_timeout']);
                            $time2 = strtotime($row['schedto']);
                            $time = ($time1 - $time2) / 60 / 60;
                            $gmeetin = date("H:i", strtotime($row['timein4']));
                            $gmeetout = date("H:i", strtotime($row['timeout4']));
                            $gmeet1 = $row['timein4'];
                            $gmeet2 = $row['timeout4'];
                            $gmeetot = floor((strtotime($gmeet2) - strtotime($gmeet1)) / 3600);
                            ?>    
                                    <!-- Page Heading -->
                                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                                        <div class="mb-3">
                                            <h4 class="mb-0">Overtime - Details</h4>
                                            <div class="small">
                                                <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                                                .<?php echo date('F d, Y - h:i:s A'); ?>
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
                                                                            <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                                                value="<?php echo $row['name']; ?>" style="font-size:100%" readonly />
                                                                        </div>
                                                        
                                                                        <div class="form-group row">								
                                                                            <div class="col-sm-6 mb-sm-0 text-center">
                                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                                                name="empno" value="<?php echo $row['empno']; ?>" style="font-size:100%" readonly />
                                                                            </div>
                                                            
                                                                
                                                                            <div class="col-sm-6 text-center">		
                                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center" id="Branch"
                                                                                value="<?php echo $row['branch']; ?>" style="font-size:100%" readonly />
                                                                            </div>
                                                                        </div>
                                                        
                                                                        <hr>
                                                        
                                                                        <div class="form-group text-center text-uppercase">
                                                                            <div class="form-group">
                                                                            <?php
                                                                            if ($ottype2 == 1) {
                                                                                ?>
                                                                                        <label>Gen Meet Overtime Date</label>
                                                                                    <?php
                                                                            } elseif ($ottype2 == 2) {
                                                                                ?>
                                                                                        <label>Gen Clean Overtime Date</label>
                                                                                    <?php
                                                                            } else {
                                                                                ?>
                                                                                        <label>Overtime Date</label>
                                                                                    <?php
                                                                            }
                                                                            ?>
                                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                value="<?php echo $row['otdatefrom']; ?> " style="font-size:100%" readonly />
                                                                                <input type="text" hidden name="id" value="<?php echo $id ?>">
                                                                            </div>				
                                                                        </div>


                                                                        <?php
                                                                        if ($ottype2 >= 1) {

                                                                            ?>
                                                                                <div class="form-group row">                                
                                                                                    <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                                        <?php
                                                                                        if ($ottype2 == 1) {
                                                                                            ?>
                                                                                                    <label><small class="text-uppercase">Gen Meet Time-IN</small></label>
                                                                                                <?php
                                                                                        } else {
                                                                                            ?>    
                                                                                                    <label><small class="text-uppercase">Gen Clean Time-IN</small></label>
                                                                                                <?php
                                                                                        }
                                                                                        ?>
                                                                                            <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                        value="<?php echo $gmeetin; ?>" name="fromtime" />
                                                                                    </div>
                                                            
                                                                
                                                                                    <div class="col-sm-6 text-center">      
                                                                                        <?php
                                                                                        if ($ottype2 == 1) {
                                                                                            ?>
                                                                                                    <label><small class="text-uppercase">Gen Meet Time-OUT</small></label>
                                                                                                <?php
                                                                                        } else {
                                                                                            ?>    
                                                                                                    <label><small class="text-uppercase">Gen Clean Time-OUT</small></label>
                                                                                                <?php
                                                                                        }
                                                                                        ?>
                                                                                        <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                            value="<?php echo $gmeetout; ?>" />
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                        } else if ($ottype2 == 0 && $row["isNWD"] == 1) {

                                                                            $time1 = strtotime($row['A_timeout']);
                                                                            $time2 = strtotime($row['M_timein']);
                                                                            $time = (($time1 - $time2) / 3600) - intval($row['break']);
                                                                            ?>
                                                                                        <div class="form-group row">                                
                                                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                                                    <label><small class="text-uppercase">NWD Actual Timein</small></label>	
                                                                                                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                                value="<?php echo "" . date("H:i", strtotime($row['M_timein'])); ?>" name="fromtime" />
                                                                                            </div>
                                                            
                                                                
                                                                                            <div class="col-sm-6 text-center">      
                                                                                                <label><small class="text-uppercase">NWD Actual Timeout</small></label>
                                                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                                    value="<?php
                                                                                                    if ($row['A_timeout'] == '') {
                                                                                                        echo "No Out Yet";
                                                                                                    } else {
                                                                                                        echo "" . date("H:i", strtotime($row['A_timeout']));
                                                                                                    }
                                                                                                    ?>" name="totime" />
                                                                                            </div>
                                                                                        </div>
                                                                                <?php
                                                                        } else { ?>
                                                                                            <div class="form-group row">                                
                                                                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                                                        <label><small class="text-uppercase">Schedule Timeout</small></label>	
                                                                                                        <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                                    value="<?php echo "" . date("H:i", strtotime($row['schedto'])); ?>" name="fromtime" />
                                                                                                </div>
                                                                
                                                                    
                                                                                                <div class="col-sm-6 text-center">      
                                                                                                    <label><small class="text-uppercase">Actual Timeout</small></label>
                                                                                                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                                        value="<?php
                                                                                                        if ($row['A_timeout'] == '') {
                                                                                                            echo "No Out Yet";
                                                                                                        } else {
                                                                                                            echo "" . date("H:i", strtotime($row['A_timeout']));
                                                                                                        }
                                                                                                        ?>" name="totime" />
                                                                                                </div>
                                                                                            </div>
                                                                                <?php
                                                                        }
                                                                        ?>                                                                   
                                                        
                                                                        <div class="form-group row">
                                                                            <?php
                                                                            if ($ottype2 >= 1) {
                                                                                ?>                                
                                                                                    <div class="col-sm-6 mb-sm-0 text-center">
                                                                                        <label><small class="text-uppercase">Actual Rendered Overtime</small></label>
                                                                                        <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                        value="<?php echo $gmeetot; ?>" readonly  autocomplete="off" />
                                                                                    </div>
                                                                                <?php
                                                                            } else {
                                                                                ?>                            
                                                                                    <div class="col-sm-6 mb-sm-0 text-center">
                                                                                        <label><small class="text-uppercase">Actual Rendered Hours</small></label>
                                                                                        <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                        value="<?php
                                                                                        if ($time > 0) {
                                                                                            echo "" . round($time, 2);
                                                                                        } else {

                                                                                            echo "0";
                                                                                        }
                                                                                        ?>" readonly  autocomplete="off" />
                                                                                    </div>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                            
                                                                
                                                                            <div class="col-sm-6 text-center">		
                                                                                <label><small class="text-uppercase">Filed OT Hours</small></label>
                                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                value="<?php echo "" . $row['othours']; ?>" readonly  autocomplete="off" />
                                                                            </div>
                                                                        </div> 
                                                                        <div class="form-group text-center">
                                                                            <label>Reason Or Purpose</label>

                                                                            <textarea style="height:60px;" maxlength="50" type="date" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                id="date" readonly><?php echo $row['otreason']; ?></textarea>

                                                                        </div>
                                
                                                        
                                                                    <?php
                                                                    if ($ottype2 == 0 || $ottype2 == '') {
                                                                        if (($row['A_timeout'] != '' and $time >= $row['othours']) || $row['isNWD'] == 1) { ?>
                                                            
                                                                                        <input type="submit" name="otapprove" class="btn btn-success btn-user btn-block bg-gradient-success" value="Approved" onclick="return confirm('Are you sure you want to Approved this OT?');">

                                                                                <?php } else { ?>      
                                                                                           <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                                                                            <small>You cannot approve this overtime, please check employee schedule or actual rendered hours vs. employee filed ot. Thank you!</small>
                                                                                           </div>        
                                                                                <?php } ?>
                                                        
                                                                            <?php
                                                                    } else {
                                                                        ?>
                                                                                <input type="submit" name="otapprove" class="btn btn-success btn-user btn-block bg-gradient-success" value="Approved" onclick="return confirm('Are you sure you want to Approved this OT?');">
                                                                            <?php
                                                                    }
                                                                    ?>    
                                                                        <input type="submit" name="otcancel" class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel" onclick="return confirm('Are you sure you want to Cancel out this OT');">												                                   
                                                                    </form>
                                                    
                                                                    <hr>
                                                                    <div class="text-center">
                                                                    <?php
                                                                    if (
                                                                        $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 1348 and $_SESSION['empno'] != 271 or $userlevel == 'ac'
                                                                        and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 4647 and $_SESSION['empno'] != 3071
                                                                        and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 5584
                                                                        and $_SESSION['empno'] != 5361 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5515 and $_SESSION['empno'] != 5452
                                                                        and $_SESSION['empno'] != 4811 and $_SESSION['empno'] != 2684 and $_SESSION['empno'] != 884
                                                                        and $_SESSION['empno'] != 3183 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                                                                        and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027
                                                                        and $_SESSION['empno'] != 107 and $_SESSION['empno'] != 4378 and $_SESSION['empno'] != 24
                                                                    ) {
                                                                        ?>
                                                                                <a class="small float-right" href="approvals.php?ot=ot">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                                <a class="small float-right" href="../overtime.php?pending=pending">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
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

                        <?php
                        if (isset($_GET["leave"]) == "leave") {
                            $empno = $_GET['empno'];
                            $vlnumber = $_GET['vlnumber'];

                            $sql = "SELECT * FROM user_info 
                                        JOIN vlform on user_info.empno = vlform.empno
                                    WHERE user_info.empno = '$empno' AND vlnumber = '$vlnumber' AND vlstatus = 'pending'";
                            $query = $HRconnect->query($sql);
                            $row = $query->fetch_array();
                            $type = $row['vltype'];


                            ?>
                                                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                                                        <div class="mb-3">
                                                            <h4 class="mb-0">Leave - Details</h4>
                                                            <div class="small">
                                                                <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                                                                .<?php echo date('F d, Y - h:i:s A'); ?>
                                                            </div>
                                                        </div>						
                                                    </div>
                
                                                        <div class="row justify-content">
                                                            <div class="col-xl-1 col-lg-12 col-md-9">
                                                            </div>
                                            
                                                            <div class="col-xl-5 col-lg-12 col-md-9">				
                                                                <div class="card o-hidden border-0 shadow-lg my-2">
                                                                    <div class="card-body p-0">
                                                                        <!-- Nested Row within Card Body -->
                                                                        <div class="row">																				
                                                                            <div class="col-lg-12">
                                                                                <div class="p-4">
                                                                                        <div class="form-group">
                                                                                            <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase" id="name" 
                                                                                                value="<?php echo $row['name']; ?>"  style="font-size:100%" readonly />
                                                                                        </div>
                                                                        
                                                                                        <div class="form-group row">								
                                                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                                                <input type="text" id="empno" class="form-control form-control-user bg-gray-100 text-center"
                                                                                                name="empno" value="<?php echo $row['empno']; ?>"  style="font-size:100%" readonly />
                                                                                            </div>
                                                                            
                                                                                
                                                                                            <div class="col-sm-6 text-center">		
                                                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center" id="Branch"
                                                                                                value="<?php echo $row['datehired']; ?>"  style="font-size:100%" readonly />
                                                                                            </div>
                                                                                        </div>
                                                                        
                                                                                        <div class="form-group row">								
                                                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                                                                value="<?php echo $row['company']; ?>"  style="font-size:100%" readonly />
                                                                                            </div>
                                                                            
                
                                                                                            <div class="col-sm-6 text-center">		
                                                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center" id="Branch"
                                                                                                value="<?php echo $row['branch']; ?>"  style="font-size:100%" readonly />
                                                                                            </div>
                                                                                        </div>
                                                                        
                                                                                        <hr>
                                                                        
                                                                                        <div class="form-group">
                                                                                            <input type="text" hidden class="form-control form-control-user bg-gray-100 text-center text-uppercase" id="name" 
                                                                                                name="type"  value="<?php echo $row['vltype']; ?>"  style="font-size:100%" readonly />
                
                                                                                            <input type="text" hidden class="form-control form-control-user bg-gray-100 text-center text-uppercase" id="name" 
                                                                                                name="vlnumber" value="<?php echo $vlnumber; ?>"  style="font-size:100%" readonly />
                                                                                        </div>
                                                            
                                                                        
                                                                                        <div class="form-group text-center">
                                                                                            <label><small>Reason Or Purpose</small></label>
                                                                                            <textarea maxlength="50" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                            id="reason" value="" readonly><?php echo $row['vlreason']; ?></textarea>
                                                                                                <input type="text" hidden name="reason" value="<?php echo $row['vlreason']; ?>">
                                                                                        </div>			
                                                                                </div>
                                                                            </div>																				
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                            
                                                            <div class="col-xl-5 col-lg-12 col-md-9">				
                                                                <div class="card o-hidden border-0 shadow-lg my-2">
                                                                    <div class="card-body p-0">
                                                                        <!-- Nested Row within Card Body -->
                                                                        <div class="row">																				
                                                                            <div class="col-lg-12">
                                                                                <div class="p-4">
                                                                                    <div class="text-center">
                                                                                        <h1 class="h5 text-gray-600 mb-3"><small>Inclusive Date(s)</small>
                                                                                        <small><p>Employee Remaining Leave(s) -  <b class="text-primary" id="remaining-leave"><?php echo number_format(floatval($row['vl']), 2); ?></b> </small>
                                                                                    </div>
                                                                
                                                                                        <div class="form-group">
                                                                                                <?php
                                                                                                $sql1 = "SELECT * FROM vlform WHERE empno = '$empno' AND vlnumber = '$vlnumber' AND vlstatus = 'pending'";
                                                                                                $query1 = $HRconnect->query($sql1);
                                                                                                while ($row1 = $query1->fetch_array()) {

                                                                                                    ?>
                                                                                                        <div class="input-group m-2">
                                                                                                            <input type="text" class="form-control form-control-user bg-gray-100 text-center" id="name" name="date[]" value="<?php echo $row1['vldatefrom'] . ' ( ' . $row1['vlduration'] . ' - ' . $row1["vlhours"] . ' )'; ?>" style="font-size:100%" readonly/>
                                                                                                            <div class="input-group-append"> 
                                                                                                                <button type="submit" class="btn btn-danger btn-user bg-gradient-danger cancel-leave" value="<?php echo $row1['vldatefrom']; ?>" name="cancel">
                                                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                                                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                                                                                    </svg>
                                                                                                                </button>
                                                                                                                <?php if ($row['mothercafe'] == 109 and ($userlevel == 'master' or $userlevel == 'admin' or $_SESSION['empno'] == 1964 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 3183)) { ?>
                                                                                                                            <button type="submit" class="btn btn-success btn-user bg-gradient-success approve-leave" value="<?php echo $row1['vldatefrom']; ?>" name="approved">
                                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                                                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                                                                                                </svg>
                                                                                                                            </button>     
                                                                                                                <?php } elseif ($row['mothercafe'] != 109) { ?> 
                                                                                                                            <button type="submit" class="btn btn-success btn-user bg-gradient-success approve-leave" value="<?php echo $row1['vldatefrom']; ?>" name="approved">
                                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                                                                                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                                                                                                </svg>
                                                                                                                            </button>       
                                                                                    
                                                                                                                <?php } ?>    
                                                                                                            </div>
                                                                                                        </div>
                                                                                                <?php
                                                                                                }
                                                                                                ?>
                
                                                                                        </div> 
                                                                                    <hr>
                                                                                    <div class="text-center">
                                                                                    <?php
                                                                                    if (
                                                                                        $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 1348 and $_SESSION['empno'] != 271 or $userlevel == 'ac'
                                                                                        and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 4647 and $_SESSION['empno'] != 3071
                                                                                        and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 5584
                                                                                        and $_SESSION['empno'] != 5361 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5515 and $_SESSION['empno'] != 5452
                                                                                        and $_SESSION['empno'] != 4811 and $_SESSION['empno'] != 2684 and $_SESSION['empno'] != 884
                                                                                        and $_SESSION['empno'] != 3183 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                                                                                        and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027
                                                                                        and $_SESSION['empno'] != 107 and $_SESSION['empno'] != 4378 and $_SESSION['empno'] != 24
                                                                                    ) {
                                                                                        ?>
                                                                                                <a class="small float-right" href="approvals.php?vl=vl">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                                                        <?php
                                                                                    } else {
                                                                                        ?>
                                                                                                <a class="small float-right" href="../leave.php?pending=pending">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
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



                        <?php
                        if (isset($_GET["ut"]) == "ut") {
                            // obp view
                            $id = $_GET['id'];


                            $sql = "SELECT * FROM user_info 
                    JOIN obp on user_info.empno = obp.empno
                    WHERE obpid= '$id' ";
                            $query = $HRconnect->query($sql);
                            $row = $query->fetch_array();


                            ?>  
                                    <!-- Page Heading -->
                                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
                                        <div class="mb-3">
                                            <h4 class="mb-0">OBP - Details</h4>
                                            <div class="small">
                                                <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                                                .<?php echo date('F d, Y - h:i:s A'); ?>
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
                                                                            <input type="text" hidden value="<?php echo $id; ?>" name="id" />

                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                                                value="<?php echo $row['name']; ?>" style="font-size:100%" readonly />
                                                                        </div>
                                                        
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                                                name="empno" readonly value="<?php echo $row['empno']; ?>" style="font-size:100%" readonly />
                                                                        </div>
                                                        
                                                                        <hr>
                                                        
                                                                        <div class="form-group row mb-3">                                
                                                                            <div class="col-sm-6 text-center">                                            
                                                                                <label><small>OBP DATE</small></label>
                                                                                <input name="datefrom" type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                value="<?php echo $row['datefromto']; ?>" readonly  autocomplete="off" />
                                                                            </div>
                                                            
                                                                
                                                                            <div class="col-sm-6 text-center">      
                                                                                <label><small>LOCATION</small></label>
                                                                                <input type="text" name="obploc" class="form-control bg-gray-100 text-center" id="Branch"
                                                                                value="<?php echo $row['obploc']; ?>" readonly >
                                                                            </div>
                                                                        </div>

                                                                            <center><label>TIME INPUTS</label></center> 
                                                                        <div class="form-group text-center row">
                                                       
                                                                            <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                                                <input type="text" class="form-control bg-gray-100 text-center  text-uppercase"
                                                                                name="timein" readonly value="<?php echo $row['timein']; ?>"   autocomplete="off" />
                                                                            </div>
                                                            
                                                                            <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                                                <input type="text" class="form-control bg-gray-100 text-center  text-uppercase"
                                                                                name="breakout" readonly value="<?php echo $row['breakout']; ?>"   autocomplete="off" />
                                                                            </div>
                                                                    
                                                                            <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                                                <input type="text" class="form-control bg-gray-100 text-center  text-uppercase"
                                                                                name="breakin" readonly value="<?php echo $row['breakin']; ?>"   autocomplete="off" />
                                                                            </div>
                                                                    
                                                                            <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                                                <input type="text" class="form-control bg-gray-100 text-center  text-uppercase"
                                                                                name="timeout" readonly value="<?php echo $row['timeout']; ?>"   autocomplete="off" />
                                                                            </div>
                                                              
                                                                        </div>
                                                           
                                                                        <div class="form-row mb-3">
                                                                                <div class="col-sm-4 text-center">
                                                              
                                                                                </div>
                                                                                   <div class="col-sm-4 text-center">
                                                                                    <label><small>No. of Break(s)</small></label>
                                                                                    <input type="text" class="form-control bg-gray-100 text-center  text-uppercase"
                                                                                    name="break" readonly value="<?php echo $row['break']; ?>"   autocomplete="off" />
                                                                                </div>
                                                                        </div>

                                                                        <div class="form-group row">                                
                                                                            <div class="col-sm-6 mb-sm-0 text-center">
                                                                                <label><small>Attachment 1</small></label><br>
                                                                                <label><small>PROOF OF JOB ORDER</small></label>
                                                                                <a href="<?php echo "../" . $row['attachment_1']; ?>" class="form-control bg-gray-100 text-center text-uppercase" target="_blank"> Click here to view attachment 1</a>
                                                                            </div>
    
                                                                            <div class="col-sm-6 text-center">  
                                                                                <label><small>Attachment 2</small></label><br>
                                                                                <label><small>PROOF OF ONSITE SELFIE</small></label>
                                                                                <a href="<?php echo "../" . $row['attachment_2']; ?>"class="form-control bg-gray-100 text-center text-uppercase" target="_blank"> Click here to view attachment 2</a>
                                                                            </div>
                                                                        </div>

                                                                        <div>

                                                                        <div class="form-group text-center">
                                                                            <label>Reason Or Purpose</label>

                                                                            <textarea type="text" style="height:60px;" name="obpreason" type="date" class="form-control bg-gray-100 text-center text-uppercase"
                                                                                id="date" readonly><?php echo $row['obpreason']; ?> </textarea> 


                                                                        </div>
                                

                                                              
                                                                        <input type="submit" name="utapprove" class="btn btn-success btn-user btn-block bg-gradient-success" value="Approved" onclick="return confirm('Are you sure you want to Approved this OBP?');">
                                                       
                                                     
                                                                        <input type="submit" name="utcancel" class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel" onclick="return confirm('Are you sure you want to Cancel out this OBP?');">                                                                                     
                                                                    </form>
                                                                    <hr>
                                                                    <div class="text-center">
                                                        
                                                                    <?php
                                                                    if (
                                                                        $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 1348 and $_SESSION['empno'] != 271 or $userlevel == 'ac'
                                                                        and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 1964 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 4647 and $_SESSION['empno'] != 3071
                                                                        and $_SESSION['empno'] != 5356 and $_SESSION['empno'] != 885 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 5584
                                                                        and $_SESSION['empno'] != 5361 and $_SESSION['empno'] != 3178 and $_SESSION['empno'] != 5515 and $_SESSION['empno'] != 5452
                                                                        and $_SESSION['empno'] != 4811 and $_SESSION['empno'] != 2684 and $_SESSION['empno'] != 884
                                                                        and $_SESSION['empno'] != 3183 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336
                                                                        and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027
                                                                        and $_SESSION['empno'] != 107 and $_SESSION['empno'] != 4378 and $_SESSION['empno'] != 24
                                                                    ) {
                                                                        ?>
                                                                                <a class="small float-right" href="approvals.php?obp=obp">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>													    
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                                <a class="small float-right" href="../obp.php?pendingut=pendingut">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
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