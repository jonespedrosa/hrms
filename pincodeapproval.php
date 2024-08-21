<?php  
//START
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
 session_start();
if(empty($_SESSION['user'])){
 header('location:login.php');
}
// Updating Pending timeinputs to Approved to show his/her timeinputs
$userid = $_SESSION['useridd'];
$sql = "UPDATE sched_time SET 
m_in_status = 'Approved', 
m_o_status = 'Approved', 
a_in_status = 'Approved', 
a_o_status = 'Approved' WHERE empno = ".$_GET['empno']." AND datefromto = '".$_GET['date']."'";
$query=$HRconnect->query($sql);

// Set time Philipines
date_default_timezone_set('Asia/Manila');
$datenow = date("Y-m-d H:i"); 

// Inserting empno who approver to min_empno
$sql1 = "UPDATE sched_time SET 
min_empno = ".$_SESSION['empno'].", 
mo_empno = ".$_SESSION['empno'].", 
ain_empno = ".$_SESSION['empno'].", 
ao_empno = ".$_SESSION['empno'].", 
oin_empno = '". $datenow ."' WHERE empno = ".$_GET['empno']." AND datefromto = '".$_GET['date']."'";
$query1=$HRconnect->query($sql1);

// After approve then back to Form
header("location:filedpincode.php?&m&pending=pending");

//END
?>


