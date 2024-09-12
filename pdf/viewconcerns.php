<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();

unset($_SESSION['viewPrintSched']);
if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$branch = $_SESSION['useridd'];
$user = $row['name'];
$userlevel = $row['userlevel'];

$empno = $_GET["empno"];
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
                $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
                or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
            ) {


                $HRconnect->query($update2);


                header("location:approvals.php?vl=vl&m=3");
            }
            if (
                $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
                or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
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
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            header("location:approvals.php?vl=vl&m=4");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
            or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
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
        if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348 or $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $userlevel == 'ac' and $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 3071 or $_SESSION['empno'] == 1073) {


            $HRconnect->query($update1);

            header("location:approvals.php?ot=ot&m=2");
        }
        if (
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {


            $HRconnect->query($update1);

            header("location:approvals.php?ot=ot&m=2");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
            or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
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
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            $HRconnect->query($update1);


            header("location:approvals.php?ot=ot&m=5");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
            or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
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
        $breakout = $datefrom . " " . $_GET["breakout"];
        $breakin = $datefrom . " " . $_GET["breakin"];
        $timeout = $datefrom . " " . $_GET["timeout"];
        $break = $_GET["break"];


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
                  Break = '0',
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
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {


            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=1");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
            or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
        ) {


            $HRconnect->query($update1);

            header("location:../overtime.php?pendingut=pendingut&m=1");
        }
        if ($userlevel == 'mod') {


            $HRconnect->query($update1);

            header("location:../overtime.php?pendingut=pendingut&m=1");
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
            $userlevel == 'ac' and $_SESSION['empno'] != 271 or $_SESSION['empno'] != 24 or $userlevel != 'ac' and $_SESSION['empno'] != 71 or $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 or $_SESSION['empno'] != 3107 or $_SESSION['empno'] != 2221 or $_SESSION['empno'] != 3336 or $_SESSION['empno'] != 3111
            or $_SESSION['empno'] != 159 or $_SESSION['empno'] != 5752 or $_SESSION['empno'] != 3027 or $_SESSION['empno'] != 107
        ) {
            $HRconnect->query($update1);

            header("location:approvals.php?obp=obp&m=6");
        }
        if (
            $_SESSION['empno'] == 271 or $_SESSION['empno'] == 24 or $userlevel == 'ac' and $_SESSION['empno'] == 71 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 or $_SESSION['empno'] == 3107 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111
            or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 107
        ) {
            $HRconnect->query($update1);


            header("location:../overtime.php?pendingut=pendingut&m=6");
        }
        if ($userlevel == 'mod') {

            $HRconnect->query($update1);

            header("location:../overtime.php?pendingut=pendingut&m=6");
        }
    }


    //DTR CONCERNS APPROVED
    if (isset($_POST['approved_button'])) {
        $id = $_POST["id"];
        $Employee = $_POST["empno"];
        @$datefrom = $_POST["date"];
        $cdate = "'" . $datefrom . "'";
        $backconcern = $_POST['backconcern'];
        $concern = $_POST['concern'];
        $timedate = date("Y-m-d H:i");
        $remarks = $_POST['remarks'];
        $in = $_POST["newIN"];
        $breakout = $_POST["newbrkOUT"];
        $breakin = $_POST["newbrkIN"];
        $out = $_POST["newOUT"];
        $newIn = $datefrom . $in;
        $broken = "Forgot/Wrong inputs of broken sched";
        $cancelleave = 'Cancellation of Leave';
        $cancelovertime = 'Cancellation of Overtime';
        $sync = 'Sync/Network error';
        $brokenOT = 'File Broken Sched OT';
        $hard = 'Hardware/Persona Malfunction';
        $computation = 'Wrong Computations';
        $remove = 'Remove Time Inputs';

        //$update1=" UPDATE dtr_concerns
        //		SET status = 'Approved',
        //             date_approved = '$timedate',
        //            approver = '$user',
        //            remarks = '$remarks'
        //        WHERE id = '$id'";
        //$HRconnect->query($update1);


        if ($breakout == 'No Break') {
            $newBout = "No Break";
        } else if ($breakout != 'No Break' && $breakout > $in) {
            $newBout = $datefrom . $breakout;
        } else {
            $newdate = $datefrom . $breakout;
            $newBout = date('Y-m-d H:i', strtotime($newdate . '+1 day'));
        }

        if ($breakin == 'No Break') {
            $newBin = "No Break";
        } else if ($breakin != 'No Break' && $breakin > $breakout) {
            $newBin = $datefrom . $breakin;
        } else {
            $newdate2 = $datefrom . $breakin;
            $newBin = date('Y-m-d H:i', strtotime($newdate2 . '+1 day'));
        }

        if ($breakin != 'No Break' && $out > $breakin) {
            $newOut = $datefrom . $out;
        } else if ($breakin == 'No Break' && $out > $in) {
            $newOut = $datefrom . $out;
        } else {
            $newdate3 = $datefrom . $out;
            $newOut = date('Y-m-d H:i', strtotime($newdate3 . '+1 day'));
        }


        if (strcmp(trim($concern), trim($broken)) == 0) {

            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);

            $updatebs = " UPDATE sched_time
			SET timein4 = '$newIn',
                timeout4 = '$newOut'
            WHERE empno = '$Employee' and datefromto = $cdate ";
            $HRconnect->query($updatebs);

            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } elseif (strcmp(trim($concern), trim($remove)) == 0) {
            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);


            $inputs = $_POST['inputs'];

            if ($inputs == 'Time In') {
                $sqlremove = "UPDATE sched_time SET M_timein = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'Break Out') {
                $sqlremove = "UPDATE sched_time SET M_timeout = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'Break In') {
                $sqlremove = "UPDATE sched_time SET A_timein = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'Time Out') {
                $sqlremove = "UPDATE sched_time SET A_timeout = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'Broken Sched In') {
                $sqlremove = "UPDATE sched_time SET timein4 = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'Broken Sched Out') {
                $sqlremove = "UPDATE sched_time SET timeout4 = ''  WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } elseif ($inputs == 'All Regular Inputs') {
                $sqlremove = "UPDATE sched_time SET M_timein = '', M_timeout = '', A_timein = '', A_timeout = '' WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            } else {
                $sqlremove = "UPDATE sched_time SET timein4 = '', timeout4 = '' WHERE empno = '$Employee' and datefromto = $cdate";
                $HRconnect->query($sqlremove);
            }

            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
            $update1 = "UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);

            //$sqlgenmeet = "UPDATE dtr_concerns SET status = 'Approved',  date_approved = '$timedate', approver = '$user', remarks = '$remarks' WHERE id = '$id'";
            //$HRconnect->query($sqlgenmeet);


            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } elseif (strcmp(trim($concern), trim($computation)) == 0) {
            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);

            //$sqlgenmeet = "UPDATE dtr_concerns SET status = 'Approved',  date_approved = '$timedate', approver = '$user', remarks = '$remarks' WHERE id = '$id'";
            //$HRconnect->query($sqlgenmeet);


            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($computation)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } elseif (strcmp(trim($concern), trim($cancelleave)) == 0) {
            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);


            $sqlleave = "UPDATE vlform SET vlstatus = 'canceled', apptimedate = '$timedate', approver = '$user' WHERE empno = '$Employee' AND vlstatus = 'approved' AND vldatefrom = $cdate ";
            $HRconnect->query($sqlleave);

            $sqlLeaveSelect = "SELECT vlhours FROM `hrms`.`vlform` WHERE empno = '$Employee' AND vldatefrom = $cdate";
            $querySelectLeave = $HRconnect->query($sqlLeaveSelect);
            $rowSelectLeave = $querySelectLeave->fetch_array();

            $sqluser = "SELECT * FROM user_info WHERE empno = '$Employee'";
            $queryuser = $HRconnect->query($sqluser);
            $rowuser = $queryuser->fetch_array();
            $vl = $rowuser['vl'];
            $totalVL = $vl + floatval($rowSelectLeave['vlhours']);

            $addleave = "UPDATE user_info SET vl = '$totalVL' WHERE empno = '$Employee' ";
            $HRconnect->query($addleave);

            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } elseif (strcmp(trim($concern), trim($cancelovertime)) == 0) {
            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);

            $sqlot = "UPDATE overunder SET otstatus = 'canceled', apptimedate = '$timedate', approver = '$user' WHERE empno = '$Employee' AND otstatus = 'approved' AND otdatefrom = $cdate ";
            $HRconnect->query($sqlot);

            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
                header("location:../filedconcerns.php?brokenot=approval&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        } else {
            $update1 = " UPDATE dtr_concerns
            SET status = 'Approved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";
            $HRconnect->query($update1);



            $updateTime = " UPDATE sched_time
			SET M_timein = '$newIn',
                M_timeout = '$newBout',
                A_timein = '$newBin',
                A_timeout = '$newOut',
                m_in_status = 'Approved',
                m_o_status = 'Approved',
                a_in_status = 'Approved',
                a_o_status = 'Approved'
            WHERE empno = '$Employee' and datefromto = $cdate ";
            $HRconnect->query($updateTime);

            if (strcmp(trim($backconcern), trim($sync)) == 0) {
                header("location:../filedconcerns.php?error=system&m=3");
            } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
                header("location:../filedconcerns.php?other=hardware&m=3");
            } else {
                header("location:../filedconcerns.php?pending=pending&m=3");
            }

            if (isset($_POST['ml']) == 2) {
                header("location:approvalsconcern.php?pending=pending&m=3");
            }
        }
    }

    //DTR CONCERNS CANCELLED
    if (isset($_POST['disapproved_button'])) {
        $id = $_POST["id"];
        $Employee = $_POST["empno"];
        @$datefrom = $_POST["date"];
        $cdate = "'" . $datefrom . "'";
        $timedate = date("Y-m-d H:i");
        $remarks = $_POST['remarks'];
        $concern = $_POST['concern'];
        $backconcern = $_POST['backconcern'];
        $sync = 'Sync/Network error';
        $brokenOT = 'File Broken Sched OT';
        $hard = 'Hardware/Persona Malfunction';



        $update1 = " UPDATE dtr_concerns
			SET status = 'Disapproved',
                date_approved = '$timedate',
                approver = '$user',
                remarks = '$remarks'
            WHERE empno = '$Employee' and id = '$id' and concern = '$backconcern' and ConcernDate = $cdate";

        $HRconnect->query($update1);

        if (strcmp(trim($backconcern), trim($sync)) == 0) {
            header("location:../filedconcerns.php?error=system&m=4");
        } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
            header("location:../filedconcerns.php?other=hardware&m=4");
        } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
            header("location:../filedconcerns.php?brokenot=approval&m=4");
        } else {
            header("location:../filedconcerns.php?pending=pending&m=4");
        }

        if (isset($_POST['ml']) == 2) {
            header("location:approvalsconcern.php?pending=pending&m=4");
        }
    }

    //DTR CONCERNS CHANGE ERROR
    if (isset($_POST['change_button'])) {
        $id = $_POST["id"];
        $Employee = $_POST["empno"];
        @$datefrom = $_POST["date"];
        $cdate = "'" . $datefrom . "'";
        $timedate = date("Y-m-d H:i");
        $remarks = $_POST['remarks'];
        $concern = $_POST['concern'];
        $concern2 = $_POST['changeconcern'];
        $backconcern = $_POST['backconcern'];
        $sync = 'Sync/Network error';
        $brokenOT = 'File Broken Sched OT';
        $hard = 'Hardware/Persona Malfunction';
        $errortype = '';

        //SET TYPE OF ERROR
        if ($concern2 == 'Forgot to click Halfday') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Forgot to click broken sched') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Forgot to time/out or break out/in') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Wrong Time in/out or Break out/in') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Wrong format/filing of OBP') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Not following time interval') {
            $errortype = 'User Error';
        } else if ($concern2 == 'Sync/Network error') {
            $errortype = 'System Error';
        } else if ($concern2 == 'Emergency time out') {
            $errortype = 'Other Error';
        } else if ($concern2 == 'Hardware/Persona malfunction') {
            $errortype = 'Other Error';
        } else if ($concern2 == 'Fingerprint problem') {
            $errortype = 'Other Error';
        }

        $update1 = " UPDATE dtr_concerns
			SET concern = '$concern2',
                errortype = '$errortype',
                remarks = '$remarks'
            WHERE empno = '$Employee' and concern = '$backconcern' and ConcernDate = $cdate";

        $HRconnect->query($update1);

        if (strcmp(trim($backconcern), trim($sync)) == 0) {
            header("location:../filedconcerns.php?error=system&m=5");
        } elseif (strcmp(trim($backconcern), trim($hard)) == 0) {
            header("location:../filedconcerns.php?other=hardware&m=5");
        } elseif (strcmp(trim($backconcern), trim($brokenOT)) == 0) {
            header("location:../filedconcerns.php?brokenot=approval&m=5");
        } else {
            header("location:../filedconcerns.php?pending=pending&m=5");
        }

        if (isset($_POST['ml']) == 2) {
            header("location:approvalsconcern.php?pending=pending&m=5");
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
                    <?php if ($empno != '4349') {
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
                                    <?php if ($empno != '5047' and $empno != '4451' and $empno != '5051' and $empno != '3339' and $empno != '2620' and $empno != '927' and $empno != '5717' and $empno != '4491') {
                                    ?>
                                        <a class="collapse-item" href="../leave.php?pending=pending">Filed Leave</a>
                                        <a class="collapse-item" href="../filedconcerns.php?pending=pending">Filed Concern</a>
                                    <?php
                                    }
                                    ?>
                                    <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change
                                        Schedule</a>
                                    <a class="collapse-item" href="../working_dayoff.php?pending=pending">Filed Working Day Off</a>
                                    <a class="collapse-item" href="../filedpincode.php?pending=pending">Filed Staff's Pincode</a>
                                    <!--    <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a> -->
                                </div>
                            </div>
                        </li>


                        <hr class="sidebar-divider">
                        <?php if ($userlevel == 'master' or $userlevel == 'admin' or $branch == 'AUDIT' or $empno == '1073') {
                        ?>
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
                        <?php
                        }
                        ?>

                    <?php
                    }
                    ?>
                <?php
                }
                ?>
                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">
                        <i class="fa fa-address-card" aria-hidden="true"></i>
                        <span>Employee Portal</span></a>
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
                        if (isset($_GET["ot"]) == "ot") {

                            $id = $_GET['id'];



                            $sql = "SELECT * FROM user_info
           			JOIN overunder on user_info.empno = overunder.empno
                    JOIN sched_time on user_info.empno = sched_time.empno
                    WHERE overunder.ovid = '$id' AND otdatefrom = datefromto";
                            $query = $HRconnect->query($sql);
                            $row = $query->fetch_array();


                            $time1 = strtotime($row['A_timeout']);
                            $time2 = strtotime($row['schedto']);
                            $time = ($time1 - $time2) / 60 / 60;
                        ?>
                            <!-- Page Heading -->
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">Overtime Request</h1>
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
                                                                    <label>Overtime Date</label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo $row['otdatefrom']; ?> "
                                                                        style="font-size:100%" readonly />
                                                                    <input type="text" hidden name="id"
                                                                        value="<?php echo $id ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                    <label><small class="text-uppercase">Schedule
                                                                            Timeout</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo "" . date("H:i", strtotime($row['schedto'])); ?>"
                                                                        name="fromtime" />
                                                                </div>


                                                                <div class="col-sm-6 text-center">
                                                                    <label><small class="text-uppercase">Actual
                                                                            Timeout</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php
                                                                                if ($row['A_timeout'] == '') {
                                                                                    echo "No Out Yet";
                                                                                } else {
                                                                                    echo "" . date("H:i", strtotime($row['A_timeout']));
                                                                                }
                                                                                ?>" name="totime" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small class="text-uppercase">Actual Rendered
                                                                            Hours</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php
                                                                                if ($time > 0) {
                                                                                    echo "" . round($time, 2);
                                                                                } else {

                                                                                    echo "0";
                                                                                }
                                                                                ?>" readonly autocomplete="off" />
                                                                </div>


                                                                <div class="col-sm-6 text-center">
                                                                    <label><small class="text-uppercase">Filed OT
                                                                            Hours</small></label>
                                                                    <input type="text"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        value="<?php echo "" . $row['othours']; ?>" readonly
                                                                        autocomplete="off" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group text-center">
                                                                <label>Reason Or Purpose</label>

                                                                <input type="text" style="height:60px;" maxlength="50"
                                                                    type="date"
                                                                    class="form-control bg-gray-100 text-center text-uppercase"
                                                                    id="date" value="<?php echo $row['otreason']; ?>" readonly>
                                                            </div>

                                                            <?php
                                                            if ($row['A_timeout'] != '' and $time >= $row['othours']) { ?>

                                                                <input type="submit" name="otapprove"
                                                                    class="btn btn-success btn-user btn-block bg-gradient-success"
                                                                    value="Approved"
                                                                    onclick="return confirm('Are you sure you want to Approved this OT?');">

                                                            <?php } else { ?>
                                                                <div class="alert alert-danger d-none d-sm-block text-center"
                                                                    role="alert">
                                                                    <small>You cannot approve this overtime, please check employee
                                                                        schedule or actual rendered hours vs. employee filed ot.
                                                                        Thank you!</small>
                                                                </div>
                                                            <?php } ?>
                                                            <input type="submit" name="otcancel"
                                                                class="btn btn-danger btn-user btn-block bg-gradient-danger"
                                                                value="Cancel"
                                                                onclick="return confirm('Are you sure you want to Cancel out this OT');">
                            </form>

                            <hr>
                            <div class="text-center">
                                <?php
                                if (
                                    $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 271 or $userlevel == 'ac' and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111
                                    and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027 and $_SESSION['empno'] != 107
                                ) {
                                ?>
                                    <a class="small float-right" href="approvals.php?ot=ot">Back <i class="fa fa-angle-right"
                                            aria-hidden="true"></i></a>
                                <?php
                                } else {
                                ?>
                                    <a class="small float-right" href="../overtime.php?pending=pending">Back <i
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
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">Leave Request</h1>
        </div>
        <form class="user" method="GET">

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
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                id="name" value="<?php echo $row['name']; ?>" style="font-size:100%"
                                                readonly />
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center"
                                                    name="empno" value="<?php echo $row['empno']; ?>" style="font-size:100%"
                                                    readonly />
                                            </div>


                                            <div class="col-sm-6 text-center">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center"
                                                    id="Branch" value="<?php echo $row['datehired']; ?>"
                                                    style="font-size:100%" readonly />
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                    id="empno" value="<?php echo $row['company']; ?>" style="font-size:100%"
                                                    readonly />
                                            </div>


                                            <div class="col-sm-6 text-center">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center"
                                                    id="Branch" value="<?php echo $row['branch']; ?>" style="font-size:100%"
                                                    readonly />
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <input type="text" hidden
                                                class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                id="name" name="type" value="<?php echo $row['vltype']; ?>"
                                                style="font-size:100%" readonly />

                                            <input type="text" hidden
                                                class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                id="name" name="vlnumber" value="<?php echo $vlnumber; ?>"
                                                style="font-size:100%" readonly />
                                        </div>


                                        <div class="form-group text-center">
                                            <label><small>Reason Or Purpose</small></label>
                                            <textarea maxlength="50" type="date"
                                                class="form-control bg-gray-100 text-center text-uppercase" id="date"
                                                value="" readonly><?php echo $row['vlreason']; ?></textarea>
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
                                                <small>
                                                    <p>Employee Remaining Leave(s) - <b class="text-primary">
                                                            <?php echo $row['vl']; ?>
                                                        </b>
                                                </small>
                                        </div>

                                        <div class="form-group">
                                            <?php
                                            $sql1 = "SELECT * FROM vlform
											                    WHERE empno = '$empno' AND vlnumber = '$vlnumber' AND vlstatus = 'pending'";
                                            $query1 = $HRconnect->query($sql1);
                                            while ($row1 = $query1->fetch_array()) {

                                            ?>
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center" id="name"
                                                    name="date[]" value="<?php echo $row1['vldatefrom']; ?>"
                                                    style="font-size:100%" readonly />
                                            <?php
                                            }
                                            ?>

                                        </div>
                                        <?php if ($row['mothercafe'] == 109 and ($userlevel == 'master' or $userlevel == 'admin' or $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 76)) { ?>
                                            <input type="submit" class="btn btn-success btn-user btn-block bg-gradient-success"
                                                value="Approved" name="approved"
                                                onclick="return confirm('Are you sure you want to Approved this Leave?');">
                                        <?php } elseif ($row['mothercafe'] != 109) { ?>
                                            <input type="submit" class="btn btn-success btn-user btn-block bg-gradient-success"
                                                value="Approved" name="approved"
                                                onclick="return confirm('Are you sure you want to Approved this Leave?');">

                                        <?php } ?>
        </form>


        <input type="submit" class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel" name="cancel"
            onclick="return confirm('Are you sure you want to Cancel this Leave?'); ">

        <hr>
        <div class="text-center">
            <?php
            if (
                $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 271 or $userlevel == 'ac' and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111
                and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027 and $_SESSION['empno'] != 107
            ) {
            ?>
                <a class="small float-right" href="approvals.php?vl=vl">Back <i class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
            <?php
            } else {
            ?>
                <a class="small float-right" href="../leave.php?pending=pending">Back <i class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
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

        $id = $_GET['id'];


        $sql = "SELECT * FROM user_info
                    JOIN obp on user_info.empno = obp.empno
                    WHERE obpid= '$id' ";
        $query = $HRconnect->query($sql);
        $row = $query->fetch_array();


    ?>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between">
            <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">
                OBP Request
            </h1>
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
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                    value="<?php echo $row['name']; ?>" style="font-size:100%" readonly />
                                            </div>

                                            <div class="form-group">
                                                <input type="text"
                                                    class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                    name="empno" readonly value="<?php echo $row['empno']; ?>"
                                                    style="font-size:100%" readonly />
                                            </div>

                                            <hr>

                                            <div class="form-group row mb-3">
                                                <div class="col-sm-6 text-center">
                                                    <label><small>OBP DATE</small></label>
                                                    <input name="datefrom" type="text"
                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                        value="<?php echo $row['datefromto']; ?>" readonly
                                                        autocomplete="off" />
                                                </div>


                                                <div class="col-sm-6 text-center">
                                                    <label><small>LOCATION</small></label>
                                                    <input type="text" name="obploc"
                                                        class="form-control bg-gray-100 text-center" id="Branch"
                                                        value="<?php echo $row['obploc']; ?>" readonly>
                                                </div>
                                            </div>

                                            <center><label>TIME INPUTS</label></center>
                                            <div class="form-group text-center row">

                                                <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control bg-gray-100 text-center  text-uppercase"
                                                        name="timein" readonly value="<?php echo $row['timein']; ?>"
                                                        autocomplete="off" />
                                                </div>

                                                <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control bg-gray-100 text-center  text-uppercase"
                                                        name="breakout" readonly value="<?php echo $row['breakout']; ?>"
                                                        autocomplete="off" />
                                                </div>

                                                <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control bg-gray-100 text-center  text-uppercase"
                                                        name="breakin" readonly value="<?php echo $row['breakin']; ?>"
                                                        autocomplete="off" />
                                                </div>

                                                <div class="col-sm-3 mb-3 mb-sm-0 text-center">
                                                    <input type="text"
                                                        class="form-control bg-gray-100 text-center  text-uppercase"
                                                        name="timeout" readonly value="<?php echo $row['timeout']; ?>"
                                                        autocomplete="off" />
                                                </div>

                                            </div>

                                            <div class="form-row mb-3">
                                                <div class="col-sm-4 text-center">

                                                </div>
                                                <div class="col-sm-4 text-center">
                                                    <label><small>No. of Break(s)</small></label>
                                                    <input type="text"
                                                        class="form-control bg-gray-100 text-center  text-uppercase"
                                                        name="break" readonly value="<?php echo $row['break']; ?>"
                                                        autocomplete="off" />
                                                </div>
                                            </div>

                                            <div class="form-group text-center">
                                                <label>Reason Or Purpose</label>
                                                <input type="text" style="height:60px;" name="obpreason" type="date"
                                                    class="form-control bg-gray-100 text-center text-uppercase" id="date"
                                                    value="<?php echo $row['obpreason']; ?>" readonly>
                                            </div>

                                            <input type="submit" name="utapprove"
                                                class="btn btn-success btn-user btn-block bg-gradient-success"
                                                value="Approved"
                                                onclick="return confirm('Are you sure you want to Approved this OBP?');">


                                            <input type="submit" name="utcancel"
                                                class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel"
                                                onclick="return confirm('Are you sure you want to Cancel out this OBP?');">
        </form>
        <hr>
        <div class="text-center">

            <?php
            if (
                $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 271 or $userlevel == 'ac' and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111
                and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027 and $_SESSION['empno'] != 107
            ) {
            ?>
                <a class="small float-right" href="approvals.php?obp=obp">Back <i class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
            <?php
            } else {
            ?>
                <a class="small float-right" href="../overtime.php?pendingut=pendingut">Back <i class="fa fa-angle-right"
                        aria-hidden="true"></i></a>
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

    <!-- Begin Page Content for Approval of DTR Concerns -->
    <div class="container-fluid">

        <?php
        if (isset($_GET["dtr"]) == "concerns") {

            $empNUM = $_GET['empno'];
            $cdate = $_GET['date'];
            $date = " ' " . $cdate . "'";
            $dtrconcerns = $_GET['dtrconcerns'];


            if ($dtrconcerns == 'File Broken Sched OT') {
                $sqlb = "SELECT * FROM dtr_concerns WHERE `ConcernDate` = $date AND `empno` = $empNUM  AND `status` = 'Pending' AND `concern` = 'File Broken Sched OT' ";
                $queryb = $HRconnect->query($sqlb);
                $rowb = $queryb->fetch_array();
                //$idb = $rowb['id'];

            } else {
                $sql8 = "SELECT * FROM dtr_concerns WHERE `ConcernDate` = $date AND `empno` = $empNUM  AND `status` = 'Pending' AND `concern` != 'File Broken Sched OT'  ";
                $query8 = $HRconnect->query($sql8);
                $row8 = $query8->fetch_array();
                $id = $row8['id'];
            }


            $sqlot = "SELECT * FROM sched_time WHERE `datefromto` = $date AND `empno` = $empNUM";
            $queryot = $HRconnect->query($sqlot);
            $rowot = $queryot->fetch_array();
            $schedOUT = date("H:i", strtotime($rowot['schedto']));
            $OUT = date("H:i", strtotime($rowot['A_timeout']));
            $sched1 = $rowot['schedto'];
            $out2 = $rowot['A_timeout'];
            $ot = floor((strtotime($out2) - strtotime($sched1)) / 3600);
            $gmeetin = date("H:i", strtotime($rowot['timein4']));
            $gmeetout = date("H:i", strtotime($rowot['timeout4']));
            $gmeet1 = $rowot['timein4'];
            $gmeet2 = $rowot['timeout4'];
            $gmeetot = floor((strtotime($gmeet2) - strtotime($gmeet1)) / 3600);

        ?>
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-2">
                <div class="mb-3">
                    <h4 class="mb-0">Concerns</h4>
                    <div>
                        <?php
                        if (isset($_GET['ml'])) {
                        ?>
                            <a href="approvalsconcern.php"><span class="text-secondary fw-500">List</span></a>
                        <?php
                        } else if ($dtrconcerns == 'Sync/Network error') {
                        ?>
                            <a href="../filedconcerns.php?error=system"><span class="text-secondary fw-500">List</span></a>
                        <?php
                        } else if ($dtrconcerns == 'Hardware/Persona Malfunction') {
                        ?>
                            <a href="../filedconcerns.php?other=hardware"><span class="text-secondary fw-500">List</span></a>
                        <?php
                        } else if ($dtrconcerns == 'File Broken Sched OT') {
                        ?>
                            <a href="../filedconcerns.php?brokenot=approval"><span class="text-secondary fw-500">List</span></a>
                        <?php
                        } else {
                        ?>
                            <a href="../filedconcerns.php?pending=pending"><span class="text-secondary fw-500">List</span></a>
                        <?php
                        }
                        ?>
                        /
                        <span class="text-primary fw-500 font-strong">Details</span>
                    </div>
                </div>
            </div>

            <form class="user" action="viewconcerns.php" method="POST">

                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-12 col-md-9">
                        <div class="card o-hidden border-0 shadow-lg my-2">
                            <div class="card-body p-0">
                                <!-- Nested Row within Card Body -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="p-4">
                                            <?php
                                            if ($dtrconcerns == 'File Broken Sched OT') {
                                            ?>
                                                <div class="form-group">
                                                    <input type="text"
                                                        class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                        name='empNAME' value="<?php echo $rowb['name']; ?>"
                                                        style="font-size:100%" readonly />
                                                </div>

                                                <div class="form-group row">
                                                    <div class="col-sm-6 mb-sm-0 text-center">

                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                            name="empno" value="<?php echo $rowb['empno']; ?>"
                                                            style="font-size:100%" readonly />
                                                    </div>

                                                    <div class="col-sm-6 text-center">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center"
                                                            id="Branch" value="<?php echo $rowb['branch']; ?>"
                                                            style="font-size:100%" readonly />
                                                    </div>
                                                </div>

                                                <hr>

                                                <div class="form-group text-center text-uppercase">
                                                    <div class="form-group">
                                                        <label>Date of Concern</label>
                                                        <input type="hidden" name="concern" value="File Broken Sched OT">
                                                        <input type="text"
                                                            class="form-control bg-gray-100 text-center text-uppercase"
                                                            name="date" value="<?php echo $rowb['ConcernDate']; ?> "
                                                            style="font-size:100%" readonly />
                                                    </div>

                                                <?php
                                            } else {
                                                ?>
                                                    <div class="form-group">
                                                        <input type="text"
                                                            class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                            name='empNAME' value="<?php echo $row8['name']; ?>"
                                                            style="font-size:100%" readonly />
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-sm-6 mb-sm-0 text-center">

                                                            <input type="text"
                                                                class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                                name="empno" value="<?php echo $row8['empno']; ?>"
                                                                style="font-size:100%" readonly />
                                                        </div>

                                                        <div class="col-sm-6 text-center">
                                                            <input type="text"
                                                                class="form-control form-control-user bg-gray-100 text-center"
                                                                id="Branch" value="<?php echo $row8['branch']; ?>"
                                                                style="font-size:100%" readonly />
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="form-group text-center text-uppercase">
                                                        <div class="form-group">
                                                            <label>Date of Concern</label>
                                                            <input type="hidden" name="concern"
                                                                value="<?php echo $dtrconcerns; ?> ">
                                                            <input type="text"
                                                                class="form-control bg-gray-100 text-center text-uppercase"
                                                                name="date" value="<?php echo $row8['ConcernDate']; ?> "
                                                                style="font-size:100%" readonly />
                                                        </div>

                                                    <?php
                                                }
                                                    ?>
                                                    <div class="form-group">
                                                        <label>CONCERN</label>
                                                        <input type="text"
                                                            class="form-control bg-gray-100 text-center text-uppercase"
                                                            name="backconcern" value="<?php echo $dtrconcerns; ?> "
                                                            style="font-size:100%" readonly />
                                                    </div>
                                                    <?php
                                                    if ($dtrconcerns == 'File Broken Sched OT') {
                                                    ?>
                                                        <div class="form-group">
                                                        </div>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="form-group">
                                                            <label>TYPE OF ERROR</label>
                                                            <input type="text"
                                                                class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo $row8['errortype']; ?> "
                                                                style="font-size:100%" readonly />
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    </div>
                                                    <?php
                                                    //IF CONCERN IS CANCELLATION OF LEAVE
                                                    if ($dtrconcerns == 'Cancellation of Leave') {
                                                    ?>
                                                        <div class="form-group text-center">
                                                            <label>Type of Leave </label>
                                                            <input type="hidden" name="newIN" value="1">
                                                            <input type="hidden" name="newbrkOUT" value="1">
                                                            <input type="hidden" name="newbrkIN" value="1">
                                                            <input type="hidden" name="newOUT" value="1">
                                                            <input type="hidden" name="concern" value="Cancellation of Leave">
                                                            <input type="hidden" name="empno" value="<?php echo $row8['empno']; ?>">
                                                            <input type="text" style="height:60px;" maxlength="50" type="date"
                                                                class="form-control bg-gray-100 text-center text-uppercase"
                                                                id="date" value="<?php echo $row8['vltype']; ?>" readonly>
                                                        </div>

                                                    <?php
                                                        //IF CONCERN IS CANCELLATION OF OVERTIME
                                                    } else if ($dtrconcerns == 'Cancellation of Overtime') {
                                                    ?>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                <input type="hidden" name="newIN" value="1">
                                                                <input type="hidden" name="newbrkOUT" value="1">
                                                                <input type="hidden" name="newbrkIN" value="1">
                                                                <input type="hidden" name="newOUT" value="1">
                                                                <input type="hidden" name="concern"
                                                                    value="Cancellation of Overtime">
                                                                <input type="hidden" name="empno"
                                                                    value="<?php echo $row8['empno']; ?>">
                                                                <label><small class="text-uppercase">Schedule Time
                                                                        Out</small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualIN" value="<?php echo $schedOUT; ?>"
                                                                    readonly>
                                                                <label><small class="text-uppercase">Maximum OT Hours that can be
                                                                        Filed</small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $ot; ?>" readonly>
                                                            </div>

                                                            <div class="col-sm-6 text-center">
                                                                <label><small class="text-uppercase">Actual Time Out
                                                                    </small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $OUT; ?>" readonly>
                                                                <label><small class="text-uppercase">Filed OT Hours</small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $row8['othours']; ?>"
                                                                    readonly>

                                                            </div>
                                                        </div>

                                                    <?php
                                                        //IF CONCERN IS Remove Time Inputs
                                                    } else if ($dtrconcerns == 'Remove Time Inputs') {
                                                    ?>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <input type="hidden" name="newIN" value="1">
                                                                <input type="hidden" name="newbrkOUT" value="1">
                                                                <input type="hidden" name="newbrkIN" value="1">
                                                                <input type="hidden" name="newOUT" value="1">
                                                                <input type="hidden" name="concern" value="Remove Time Inputs">
                                                                <input type="hidden" name="empno"
                                                                    value="<?php echo $row8['empno']; ?>">
                                                                <label>
                                                                    <bold class="text-uppercase">Time Inputs To Be Removed:</bold>
                                                                </label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="inputs" value="<?php echo $row8['vltype']; ?>"
                                                                    readonly>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="hidden" name="actualIN" value="" readonly>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="hidden" name="newIN" value="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">ATTACHMENT 1</small></label>
                                                                <label><small>IR/ HYO FORM</small></label>
                                                                <a href="<?php echo $row8['attachment1']; ?>"
                                                                    class="form-control bg-gray-100 text-center text-uppercase"
                                                                    target="_blank"> Click here to view attachment</a>
                                                            </div>

                                                        </div>

                                                    <?php
                                                        //IF CONCERN IS Wrong Computations
                                                    } else if ($dtrconcerns == 'Wrong Computations') {
                                                    ?>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <input type="hidden" name="newIN" value="1">
                                                                <input type="hidden" name="newbrkOUT" value="1">
                                                                <input type="hidden" name="newbrkIN" value="1">
                                                                <input type="hidden" name="newOUT" value="1">
                                                                <input type="hidden" name="concern" value="Wrong Computations">
                                                                <input type="hidden" name="empno"
                                                                    value="<?php echo $row8['empno']; ?>">
                                                                <label>
                                                                    <bold class="text-uppercase">What to fix?</bold>
                                                                </label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="computations"
                                                                    value="<?php echo $row8['vltype']; ?>" readonly>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="hidden" name="actualIN" value="" readonly>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="hidden" name="newIN" value="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">

                                                        </div>

                                                    <?php
                                                        //IF CONCERN IS BROKEN SCHED OT APPROVAL
                                                    } else if ($dtrconcerns == 'File Broken Sched OT') {
                                                    ?>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">
                                                                        <?php echo $rowb['ottype']; ?> Time IN
                                                                    </small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualIN" value="<?php echo $gmeetin; ?>"
                                                                    readonly>
                                                                <label><small class="text-uppercase">Maximum OT Hours that can be
                                                                        Filed</small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $gmeetot; ?>"
                                                                    readonly>
                                                            </div>

                                                            <div class="col-sm-6 text-center">
                                                                <label><small class="text-uppercase">
                                                                        <?php echo $rowb['ottype']; ?> Time OUT
                                                                    </small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $gmeetout; ?>"
                                                                    readonly>
                                                                <label><small class="text-uppercase">Filed OT Hours</small></label>
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $rowb['othours']; ?>"
                                                                    readonly>

                                                            </div>
                                                        </div>

                                                    <?php
                                                    } else {
                                                    ?>
                                                        <div class="form-group row">
                                                            <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">Captured time
                                                                        inputs</small></label>

                                                                <!-- TIME IN FROM DATABASE -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualIN"
                                                                    value="<?php echo $row8['actualIN']; ?>" readonly>

                                                                <!-- BREAK OUT FROM DATABASE -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualbrkOUT"
                                                                    value="<?php echo $row8['actualbOUT']; ?>" readonly>

                                                                <!-- BREAK IN FROM DATABASE -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualbrkIN"
                                                                    value="<?php echo $row8['actualbIN']; ?>" readonly>

                                                                <!-- TIME OUT FROM DATABASE -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="actualOUT"
                                                                    value="<?php echo $row8['actualOUT']; ?>" readonly>

                                                            </div>

                                                            <div class="col-sm-6 text-center">
                                                                <label><small class="text-uppercase">Requested Time
                                                                        Inputs</small></label>

                                                                <!-- REQUESTED TIME IN -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newIN" value="<?php echo $row8['newIN']; ?>"
                                                                    readonly>

                                                                <!-- REQUESTED BREAK OUT -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newbrkOUT"
                                                                    value="<?php echo $row8['newbOUT']; ?>" readonly>

                                                                <!-- REQUESTED BREAK IN -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newbrkIN"
                                                                    value="<?php echo $row8['newbIN']; ?>" readonly>

                                                                <!-- REQUESTED TIME OUT -->
                                                                <input class="form-control bg-gray-100 text-center text-uppercase"
                                                                    type="text" name="newOUT" value="<?php echo $row8['newOUT']; ?>"
                                                                    readonly>

                                                            </div>
                                                        </div>

                                                        <?php
                                                        if ($_GET["dtrconcerns"] == 'Wrong format/filing of OBP') {
                                                        ?>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small class="text-uppercase">ATTACHMENT 1</small></label>
                                                                    <label><small>SCREENSHOT OF ONLINE DTR</small></label>
                                                                    <a href="<?php echo $row8['attachment1']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 1</a>
                                                                </div>

                                                                <div class="col-sm-6 text-center">
                                                                    <label><small class="text-uppercase">ATTACHMENT 2</small></label>
                                                                    <label><small>SCREENSHOT OF FILED OBP</small></label>
                                                                    <a href="<?php echo $row8['attachment2']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 2</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label style="padding-left: 10px; color:blue;">
                                                                    <bold>Description</bold>
                                                                </label>
                                                                <p style="padding-left: 10px; padding-right: 10px;">The staff inputs
                                                                    wrong format or details in filing his/her OBP and he/she wants to
                                                                    correct it.</p>
                                                            </div>
                                                        <?php
                                                        } else if ($_GET["dtrconcerns"] == 'Hardware/Persona Malfunction') {
                                                        ?>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small>Attachment 1</small></label><br>
                                                                    <label><small>PROOF OF HARDWARE/PERSONA MALFUNCTION</small></label>
                                                                    <a href="<?php echo $row8['attachment1']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 1</a>
                                                                </div>

                                                                <div class="col-sm-6 text-center">
                                                                    <label><small>Attachment 2</small></label><br>
                                                                    <label><small>LOG BOOK PICTURE</small></label>
                                                                    <a href="<?php echo $row8['attachment2']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 2</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label style="padding-left: 10px; color:blue;">
                                                                    <bold>Description</bold>
                                                                </label>
                                                                <p style="padding-left: 10px; padding-right: 10px;">The Device used for
                                                                    persona is not properly working(defective).</p>
                                                            </div>
                                                        <?php
                                                        } else if ($_GET["dtrconcerns"] == 'Fingerprint problem') {
                                                        ?>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small>Attachment 1</small></label><br>
                                                                    <label><small>PROOF OF NOT VERIFYING THE FINGERPRINT</small></label>
                                                                    <a href="<?php echo $row8['attachment1']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 1</a>
                                                                </div>

                                                                <div class="col-sm-6 text-center">
                                                                    <label><small>Attachment 2</small></label><br>
                                                                    <label><small>LOG BOOK PICTURE</small></label>
                                                                    <a href="<?php echo $row8['attachment2']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 2</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label style="padding-left: 10px; color:blue;">
                                                                    <bold>Description</bold>
                                                                </label>
                                                                <p style="padding-left: 10px; padding-right: 10px;">The staff
                                                                    encountered problem with his/her fingerprints causing problem with
                                                                    his/her logs.</p>
                                                            </div>
                                                        <?php
                                                        } else if ($_GET["dtrconcerns"] == 'Sync/Network error') {
                                                            // cutoff
                                                            $getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si
                                                            ON si.empno = ui.empno
                                                            WHERE si.status = 'Pending' AND ui.empno = $empno;";
                                                            $querybuilder = $HRconnect->query($getDateSQL);
                                                            $rowCutOff = $querybuilder->fetch_array();

                                                            $datestart = $rowCutOff['datefrom'];
                                                            $dateend = $rowCutOff['dateto'];
                                                        ?>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small>Attachment 1</small></label><br>
                                                                    <label><small>LOGS HISTORY PICTURE</small></label>
                                                                    <a href="<?php echo $row8['attachment1']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 1</a>
                                                                </div>

                                                                <div class="col-sm-6 text-center">
                                                                    <label><small>Attachment 2</small></label><br>
                                                                    <label><small>WEB DTR</small></label>
                                                                    <a href="print_schedule.php?empid=<?php $_SESSION['viewPrintSched'] = true;
                                                                                                        echo $row8['empno']; ?>&cutfrom=<?php echo $datestart; ?>&cutto=<?php echo $dateend; ?>&userid=<?php echo $row8['userid']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view the WEB DTR</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label style="padding-left: 10px; color:blue;">
                                                                    <bold>Description</bold>
                                                                </label>
                                                                <p style="padding-left: 10px; padding-right: 10px;">The staff has time
                                                                    inputs on the persona (based on the logs history) but did not
                                                                    reflect on his/her Web DTR.</p>
                                                            </div>
                                                        <?php
                                                        } else if ($_GET["dtrconcerns"] == 'Emergency time out') {
                                                        ?>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small>Attachment 1</small></label><br>
                                                                    <label><small>SCREENSHOT OF WEB DTR</small></label>
                                                                    <a href="<?php echo $row8['attachment1']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 1</a>
                                                                </div>

                                                                <div class="col-sm-6 text-center">
                                                                    <label><small>Attachment 2</small></label><br>
                                                                    <label><small>LOGS HISTORY PICTURE</small></label>
                                                                    <a href="<?php echo $row8['attachment2']; ?>"
                                                                        class="form-control bg-gray-100 text-center text-uppercase"
                                                                        target="_blank"> Click here to view attachment 2</a>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label style="padding-left: 10px; color:blue;">
                                                                    <bold>Description</bold>
                                                                </label>
                                                                <p style="padding-left: 10px; padding-right: 10px;">The staff had an
                                                                    emergency and need to go home immediately which may possibly cause
                                                                    problem with his/her DTR due to time interval rules.</p>
                                                            </div>
                                                        <?php
                                                        } else {
                                                            if ($_GET["dtrconcerns"] == 'Failure/Forgot to time in or time out') {
                                                                $type_concern = 1;
                                                            ?>
                                                                <div class="col-sm-6 mb-sm-0 text-center">
                                                                    <label><small>Attachment</small></label><br>
                                                                    <a href="../hear-you-out-view-only.php?empno=<?php echo $empno; ?>&type_concern=<?php echo $type_concern; ?>&ConcernDate=<?php echo $cdate; ?>" target="_blank">Click here to view attachment</a>
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The employee forgot to use their fingerprint for one of their time entries.</small></p>
                                                                </div>
                                                            <?php
                                                            }

                                                            if ($_GET["dtrconcerns"] == 'Not following time interval') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff was
                                                                            not able to follow the time interval in tapping the persona (5
                                                                            mins for cafe | 30 mins for the head office).</small></p>
                                                                </div>
                                                            <?php
                                                            }
                                                            if ($_GET["dtrconcerns"] == 'Forgot to click Halfday') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff was
                                                                            not able to check "No Break" before tapping his/her fingerprint
                                                                            for time out.</small></p>
                                                                </div>
                                                            <?php
                                                            }

                                                            if ($_GET["dtrconcerns"] == 'Forgot/Wrong inputs of broken sched') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff
                                                                            forgot to check "BROKEN SCHEDULE" for Gen Meet/Gen Cleaning. It
                                                                            is only applicable if you already completed 4 time inputs for
                                                                            that shift.</small></p>
                                                                </div>
                                                            <?php
                                                            }

                                                            if ($_GET["dtrconcerns"] == 'Cancellation of Overtime') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff
                                                                            wants to cancel his/her approved overtime possibly due to wrong
                                                                            filing or wrong input of details.</small></p>
                                                                </div>
                                                            <?php
                                                            }

                                                            if ($_GET["dtrconcerns"] == 'Cancellation of Leave') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff
                                                                            wants to cancel his/her approved leave possibly due to wrong
                                                                            filing or wrong input of details.</small></p>
                                                                </div>
                                                            <?php
                                                            }

                                                            if ($_GET["dtrconcerns"] == 'File Broken Sched OT') {
                                                            ?>
                                                                <div>
                                                                    <label style="padding-left: 10px; color:blue;">
                                                                        <bold>Description</bold>
                                                                    </label>
                                                                    <p style="padding-left: 10px; padding-right: 10px;"><small>The staff
                                                                            renders Broken Schedule Overtime. It can be because of General
                                                                            Meeting/Cleaning or other reasons.</small></p>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>

                                                        <?php
                                                        }
                                                        ?>
                                                    <?php
                                                    }
                                                    ?>

                                                    <div class="form-group text-center">
                                                        <label>Reason Or Purpose</label>
                                                        <?php
                                                        if ($dtrconcerns == 'File Broken Sched OT') {
                                                        ?>
                                                            <textarea style="height:60px;" type="date"
                                                                class="form-control bg-gray-100 text-center text-uppercase"
                                                                id="date" readonly><?php echo $rowb['reason']; ?></textarea>
                                                        <?php

                                                        } else {
                                                        ?>
                                                            <textarea style="height: 60px;" class="form-control bg-gray-100 text-center text-uppercase" id="date" readonly><?php echo $row8['reason']; ?></textarea>
                                                        <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if (isset($_GET['change'])) {
                                                            $sys = $_GET['change'];
                                                            if ($sys == "ok") {

                                                        ?>
                                                                <br>
                                                                <span>
                                                                    <label>Change the type of Concern</label>
                                                                    <select id="inputConcern" class="form-control form-control-sm"
                                                                        name="changeconcern">
                                                                        <option selected>Choose...</option>
                                                                        <option>Forgot to click Halfday</option>
                                                                        <option>Forgot to click broken sched</option>
                                                                        <option>Failure/Forgot to time in or time out</option>
                                                                        <option>Wrong format/filing of OBP</option>
                                                                        <option>Not following time interval</option>
                                                                        <option>Cancellation of Overtime</option>
                                                                        <option>Cancellation of Leave</option>
                                                                        <option>Sync/Network error</option>
                                                                        <option>Wrong Computations</option>
                                                                        <option>Emergency time out</option>
                                                                        <option>Hardware/Persona Malfunction</option>
                                                                        <option>Fingerprint problem</option>
                                                                        <option>File Broken Sched OT</option>
                                                                    </select>
                                                                </span>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <br>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        <br><label>Approver's Remarks</label>
                                                        <input type="text" pattern="^[-@.\/#&+\w\s]*$" style="height:60px;"
                                                            maxlength="1000" type="date"
                                                            class="form-control bg-gray-100 text-center text-uppercase"
                                                            id="remarks" name="remarks" required>
                                                    </div>
                                                    <?php
                                                    if (isset($_GET['ml'])) {
                                                    ?>
                                                        <input type="hidden" name="ml" value="2" />
                                                    <?php
                                                    }
                                                    ?>
                                                    <input type="hidden" name="dtr" value="dtrapproved" />
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                    <input type="hidden" name="concern" id="concern"
                                                        value="<?php echo $row8['concern']; ?> ">
                                                    <?php
                                                    if (isset($_GET['change'])) {
                                                        $sys = $_GET['change'];
                                                        if ($sys == "ok") {

                                                    ?>

                                                            <input type="submit" name="change_button"
                                                                class="btn btn-success btn-user btn-block bg-gradient-success"
                                                                value="Change"
                                                                onclick="return confirm('Are you sure you want to Change the type of error for this DTR Concern?');">

                                                            <!-- <input type="submit" name="change_button" class="btn btn-success btn-user btn-block bg-gradient-success" value="Change" onclick="return confirm('Are you sure you want to Change the type of error for this DTR Concern?');"> -->
                                                    <?php
                                                        }
                                                    }
                                                    ?>
                                                    <input type="submit" name="approved_button"
                                                        class="btn btn-success btn-user btn-block bg-gradient-success"
                                                        value="Approved"
                                                        onclick="return confirm('Are you sure you want to Approved this DTR Concern?');">
                                                    <input type="submit" name="disapproved_button"
                                                        class="btn btn-danger btn-user btn-block bg-gradient-danger"
                                                        value="Disapproved"
                                                        onclick="return confirm('Are you sure you want to Disapproved this DTR Concern?');">
                                                </div>
            </form>
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
    <div class="d-sm-flex align-items-center justify-content-between">
        <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">Leave Request</h1>
    </div>
    <form class="user" method="GET">

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
                                        <input type="text"
                                            class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                            id="name" value="<?php echo $row['name']; ?>" style="font-size:100%"
                                            readonly />
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center"
                                                name="empno" value="<?php echo $row['empno']; ?>" style="font-size:100%"
                                                readonly />
                                        </div>


                                        <div class="col-sm-6 text-center">
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center"
                                                id="Branch" value="<?php echo $row['datehired']; ?>"
                                                style="font-size:100%" readonly />
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                id="empno" value="<?php echo $row['company']; ?>" style="font-size:100%"
                                                readonly />
                                        </div>


                                        <div class="col-sm-6 text-center">
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center"
                                                id="Branch" value="<?php echo $row['branch']; ?>" style="font-size:100%"
                                                readonly />
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="form-group">
                                        <input type="text" hidden
                                            class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                            id="name" name="type" value="<?php echo $row['vltype']; ?>"
                                            style="font-size:100%" readonly />

                                        <input type="text" hidden
                                            class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                            id="name" name="vlnumber" value="<?php echo $vlnumber; ?>"
                                            style="font-size:100%" readonly />
                                    </div>


                                    <div class="form-group text-center">
                                        <label><small>Reason Or Purpose</small></label>
                                        <textarea maxlength="50" type="date"
                                            class="form-control bg-gray-100 text-center text-uppercase" id="date"
                                            value="" readonly><?php echo $row['vlreason']; ?></textarea>
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
                                            <small>
                                                <p>Employee Remaining Leave(s) - <b class="text-primary">
                                                        <?php echo $row['vl']; ?>
                                                    </b>
                                            </small>
                                    </div>

                                    <div class="form-group">
                                        <?php
                                        $sql1 = "SELECT * FROM vlform
											                    WHERE empno = '$empno' AND vlnumber = '$vlnumber' AND vlstatus = 'pending'";
                                        $query1 = $HRconnect->query($sql1);
                                        while ($row1 = $query1->fetch_array()) {

                                        ?>
                                            <input type="text"
                                                class="form-control form-control-user bg-gray-100 text-center" id="name"
                                                name="date[]" value="<?php echo $row1['vldatefrom']; ?>"
                                                style="font-size:100%" readonly />
                                        <?php
                                        }
                                        ?>

                                    </div>
                                    <?php if ($row['mothercafe'] == 109 and ($userlevel == 'master' or $userlevel == 'admin' or $_SESSION['empno'] == 71 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 76)) { ?>
                                        <input type="submit" class="btn btn-success btn-user btn-block bg-gradient-success"
                                            value="Approved" name="approved"
                                            onclick="return confirm('Are you sure you want to Approved this Leave?');">
                                    <?php } elseif ($row['mothercafe'] != 109) { ?>
                                        <input type="submit" class="btn btn-success btn-user btn-block bg-gradient-success"
                                            value="Approved" name="approved"
                                            onclick="return confirm('Are you sure you want to Approved this Leave?');">

                                    <?php } ?>
    </form>


    <input type="submit" class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel" name="cancel"
        onclick="return confirm('Are you sure you want to Cancel this Leave?'); ">

    <hr>
    <div class="text-center">
        <?php
        if (
            $userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 271 or $userlevel == 'ac' and $_SESSION['empno'] != 71 and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6538 and $_SESSION['empno'] != 229 and $_SESSION['empno'] != 3107 and $_SESSION['empno'] != 2221 and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111
            and $_SESSION['empno'] != 159 and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 3027 and $_SESSION['empno'] != 107
        ) {
        ?>
            <a class="small float-right" href="approvals.php?vl=vl">Back <i class="fa fa-angle-right"
                    aria-hidden="true"></i></a>
        <?php
        } else {
        ?>
            <a class="small float-right" href="../leave.php?pending=pending">Back <i class="fa fa-angle-right"
                    aria-hidden="true"></i></a>
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