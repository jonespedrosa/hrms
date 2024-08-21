<?php  
session_start();
 $HRconnect = mysqli_connect("localhost", "root", "", "hrms");


@$datefrom 				= mysqli_real_escape_string($HRconnect, $_POST['datefrom']);
@$dateto 				  = mysqli_real_escape_string($HRconnect, $_POST['dateto']);
@$tfrom           = mysqli_real_escape_string($HRconnect, $_POST['tfrom']);
@$tto             = mysqli_real_escape_string($HRconnect, $_POST['tto']);
@$position 				= mysqli_real_escape_string($HRconnect, $_POST['position']);
@$name 				   	= mysqli_real_escape_string($HRconnect, $_POST['name']);
@$workoff 				= mysqli_real_escape_string($HRconnect, $_POST['workoff']);
@$spcregday 			= mysqli_real_escape_string($HRconnect, $_POST['spcregday']);
@$regholiday 			= mysqli_real_escape_string($HRconnect, $_POST['regholiday']);
@$spcholiday 			= mysqli_real_escape_string($HRconnect, $_POST['spcholiday']);
@$nightdiff      = mysqli_real_escape_string($HRconnect, $_POST['nightdiff']);
@$vl 					    = mysqli_real_escape_string($HRconnect, $_POST['vl']);
@$sl 					    = mysqli_real_escape_string($HRconnect, $_POST['sl']);
@$employee 				= mysqli_real_escape_string($HRconnect, $_POST['employee']);



@$fromdate          = mysqli_real_escape_string($HRconnect, $_POST['fromdate']);
@$todate            = mysqli_real_escape_string($HRconnect, $_POST['todate']);
@$empid             = mysqli_real_escape_string($HRconnect, $_POST['empid2']);

@$fromdate2          = $_GET['cutfrom'];
@$todate2            = $_GET['cutto'];
@$empid2             = $_GET['empid'];
@$userid2             = $_GET['userid'];



@$userid = $_SESSION['useridd'];

@$username = $_SESSION['user']['username'];


$begin = new DateTime($_SESSION['datedatefrom']);
$end = new DateTime($_SESSION['datedateto']);





$from = $begin->format('Y-m-d');
$to = $end->format('Y-m-d');

$begin1 = new DateTime($datefrom);
$end2 = new DateTime($dateto);


$from1 = $begin1->format('Y-m-d');
$to2 = $end2->format('Y-m-d');

$row_data = array();



if(isset($_GET["date"]) == "date")  
	{ 


$sql = "SELECT * FROM sched_date
			WHERE userid = '$userid'";
$query=$HRconnect->query($sql);  
$row=$query->fetch_array();
$useridd = $row['userid'];



$sql1 = "SELECT * FROM user_info
      WHERE userid = '$userid'";
$query1 =$HRconnect->query($sql1);  
$row1=$query1->fetch_array();
$branch = $row1['branch'];



             if (strtotime($from) < strtotime($to)){

if($userid != $useridd)
{

	$orderquery="INSERT INTO sched_date (userid,username,datefrom,dateto,schedfrom,schedto) 
						     values ('$userid','$branch','$from','$to','$tfrom','$tto')";

    	$HRconnect->query($orderquery);


}else{

	$update1=" UPDATE sched_date 
			SET datefrom = '$from',
			dateto 	= '$to',
      schedfrom = '$tfrom',
      schedto  = '$tto'  

      WHERE userid = '$useridd'";
				
				$HRconnect->query($update1);
}

$message = "Successfully Inserted Data";


$date_time = date("Y-m-d h:i");
$inserted = "Successfully Insert Sched";
$action = $from. " - ". $to;
$empno = $_SESSION['empno'];

$sql3 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno', '$action', '$inserted','$date_time')";

  $HRconnect->query($sql3);

header("location:/hrms/createsched.php?message=$message");

}else{


//echo "<script type='text/javascript'>alert('Wrong Date Input');
        //window.location.href='createsched.php';
        //</script>";


}

}


if(isset($_GET["update"]) == "update")  
  { 
foreach($_POST['date'] as $row=>$date) {

  $break = mysqli_real_escape_string($HRconnect, $_POST['break'][$row]);
    $timefrom = mysqli_real_escape_string($HRconnect, $_POST['timefrom'][$row]);
    $timeto = mysqli_real_escape_string($HRconnect, $_POST['timeto'][$row]);
     $mfrom = mysqli_real_escape_string($HRconnect, $_POST['mfrom'][$row]);
    $mto = mysqli_real_escape_string($HRconnect, $_POST['mto'][$row]);
     $afrom = mysqli_real_escape_string($HRconnect, $_POST['afrom'][$row]);
    $ato = mysqli_real_escape_string($HRconnect, $_POST['ato'][$row]);
     $ofrom = mysqli_real_escape_string($HRconnect, $_POST['ofrom'][$row]);
    $oo = mysqli_real_escape_string($HRconnect, $_POST['oo'][$row]);
     $remarks = mysqli_real_escape_string($HRconnect, $_POST['remarks'][$row]);

  $update2=" UPDATE sched_time 
      SET userid = '$userid',
      empno  = '$employee',
      datefromto = '$date',
        schedfrom  = '$timefrom',
        schedto  = '$timeto',
        break  = '$break',
      M_timein = '$mfrom',
        M_timeout  = '$mto',
      A_timein = '$afrom',
        A_timeout  = '$ato',
      O_timein = '$ofrom',
       O_timeout = '$oo',
      remarks  = '$remarks'  
      WHERE userid = '$userid' AND empno = '$empid' AND datefromto = '$date' AND status = ''";


  $HRconnect->query($update2);
 }

  $update3 = " UPDATE sched_info 
      SET userid = '$userid',
      username  = '$username',
      empno = '$employee',
        name  = '$name',
        post  = '$position',
      workoff = '$workoff',
        spcregday  = '$spcregday',
      regholiday = '$regholiday',
       spcholiday = '$spcholiday',
       nightdiff = '$nightdiff',
      vl = '$vl',
      sl  = '$sl'  
      WHERE userid = '$userid' AND empno = '$empid' AND datefrom = '$fromdate' AND dateto = '$todate'";


  $HRconnect->query($update3);

header("location:/projection/pdf/scheduler_edit.php?empid=$empid&cutfrom=$fromdate&cutto=$todate&userid=$userid");

}




if(isset($_GET["status"]) == "status")  
  { 

    $saved = "Saved";

  $update4 = " UPDATE sched_info 
      SET status = '$saved' 
      WHERE userid = '$userid2' AND empno = '$empid2' AND datefrom = '$fromdate2' AND dateto = '$todate2' AND status != 'deleted'";


  $HRconnect->query($update4);

header("location:/projection/emptimesheets.php");

}






if(isset($_GET["approve"]) == "approve")  
  { 

    
@$id = $_GET['id'];
@$time = $_POST['timein'];
@$employeeid = $_SESSION['empno'];
@$date = date("Y-m-d");




if(isset($_GET["m_in"]) == "m_in")  
  { 

  $update5 = " UPDATE sched_time 
      SET m_in_status = 'Approved',
        min_empno = '$employeeid',
       M_timein = '$date $time'
      WHERE id = '$id'";
        $HRconnect->query($update5);

   @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);  
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved Time-IN" ;
      $action = $name ." - ". $time ;

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);

      
}
  
if(isset($_GET["m_out"]) == "m_out")  
  {    
    
if ($time != 'No Break'){

$update5 = " UPDATE sched_time 
      SET m_o_status = 'Approved',
      mo_empno = '$employeeid',
       M_timeout = '$date $time'
      WHERE id = '$id'";
        $HRconnect->query($update5);

}else{

  $update5 = " UPDATE sched_time 
      SET m_o_status = 'Approved',
      mo_empno = '$employeeid',
       M_timeout = '$time'
      WHERE id = '$id'";
        $HRconnect->query($update5);
}

  

   @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved Break-OUT" ;
      $action = $name ." - ". $time ;

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);
      
}

if(isset($_GET["a_in"]) == "a_in")  
  { 

 

  if ($time != 'No Break'){

 $update5 = " UPDATE sched_time 
      SET a_in_status = 'Approved',
      ain_empno = '$employeeid',
       A_timein = '$date $time' 
      WHERE id = '$id'";
        $HRconnect->query($update5);

}else{

  $update5 = " UPDATE sched_time 
      SET a_in_status = 'Approved',
      ain_empno = '$employeeid',
       A_timein = '$time' 
      WHERE id = '$id'";
        $HRconnect->query($update5);
}

  @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);  
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved Break-IN" ;
      $action = $name ." - ". $time ;
      

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);
      
}

if(isset($_GET["a_out"]) == "a_out")  
  { 


  $update5 = " UPDATE sched_time 
      SET a_o_status = 'Approved',
      ao_empno = '$employeeid',
       A_timeout = '$date $time'  
      WHERE id = '$id'";
        $HRconnect->query($update5);

   @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);  
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved Time-OUT" ;
      $action = $name ." - ". $time ;
      

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);
      
}

if(isset($_GET["o_in"]) == "o_in")  
  { 


  $update5 = " UPDATE sched_time 
      SET o_in_status = 'Approved',
      oin_empno = '$employeeid',
       O_timein = '$date $time'   
      WHERE id = '$id'";
        $HRconnect->query($update5);


   @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);  
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved OT-IN" ;
      $action = $name ." - ". $time ;

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);
      
}

if(isset($_GET["o_out"]) == "o_out")  
  { 
    


  $update5 = " UPDATE sched_time 
      SET o_o_status = 'Approved',
      oo_empno = '$employeeid',
       O_timeout = '$date $time'    
      WHERE id = '$id'";
        $HRconnect->query($update5);

    @$sqll = "SELECT * FROM sched_time 
    JOIN user_info ON sched_time.empno = user_info.empno
    where sched_time.id = '$id'";
   @$queryy=$HRconnect->query($sqll);  
   @$roww=$queryy->fetch_array();
    $name = $roww['name'];

      $date_time = date("Y-m-d h:i");
      $empno = $_SESSION['empno'];
      $inserted ="Approved OT-OUT" ;
      $action = $name ." - ". $time ;

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
            VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);
      
}

header("location:/hrms/emptimesheets.php");

}





if(isset($_GET["delete"]) == "delete")  
  { 
   

$update6 = " UPDATE sched_info 
      SET status = 'deleted' 
      WHERE empno = '$empid2' AND datefrom = '$fromdate2' AND dateto = '$todate2'";


$update7 = " UPDATE sched_time 
      SET status = 'deleted' 
      WHERE empno = '$empid2' AND status != 'deleted' AND (datefromto between '$fromdate2' AND '$todate2')";


        $HRconnect->query($update6);
        $HRconnect->query($update7);






   header("location:/projection/emptimesheets.php");


}



if(isset($_GET["post"]) == "post")  
  { 


$date = date("Y-m-d");

$date1 = new DateTime($date);
$date1->modify('-1 day');
$newformat1 = $date1->format('Y-m-d');

                    @$sqll = "SELECT * FROM sched_time where datefromto = '$newformat1' AND userid = '$userid'";
                    @$queryy=$HRconnect->query($sqll);  
                    @$roww=$queryy->fetch_array();
                    $datefromto = $roww['datefromto'];
                    $id = $roww['id'];
                    $status1 = $roww['status'];

if (@$status1 == 'approved' OR $id == ''){
  
   $newformat = date("Y-m-d");

}else{

     $newformat = $newformat1;

}




$update5 = " UPDATE sched_time 
      SET status = 'approved' 
      WHERE datefromto = '$newformat' AND userid = '$userid'";
        $HRconnect->query($update5);


$date_time = date("Y-m-d h:i");
$empno = $_SESSION['empno'];
$inserted = "Successfully Posted";
$action = "Daily Post";

$sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno', '$action', '$inserted','$date_time')";

$HRconnect->query($sql2);



header("location:/hrms/dailypost.php");
      
}



if(isset($_GET["biodate"]) == "biodate")  
  { 

  $update1=" UPDATE sched_date 
      SET biofrom = '$from1',
      bioto  = '$to2'";
        
       $HRconnect->query($update1);

header("location:/hrms/biologs.php");


}


if(isset($_GET["dtr"]) == "dtr")  
  { 

@$datefrom3       = mysqli_real_escape_string($HRconnect, $_POST['datefrom4']);
@$dateto3         = mysqli_real_escape_string($HRconnect, $_POST['dateto4']);

echo $datefrom4 = date("Y-m-d", strtotime($datefrom3));  
echo $dateto4 = date("Y-m-d", strtotime($dateto3));


  $update1=" UPDATE sched_date 
      SET fromcut = '$datefrom4',
      tocut  = '$dateto4'";
        
       $HRconnect->query($update1);

header("location:/hrms/pdf/generatedtr.php");


}


if(isset($_GET["gleave"]) == "gleave")  
  { 

@$datefrom3       = mysqli_real_escape_string($HRconnect, $_POST['datefrom4']);
@$dateto3         = mysqli_real_escape_string($HRconnect, $_POST['dateto4']);

echo $datefrom4 = date("Y-m-d", strtotime($datefrom3));  
echo $dateto4 = date("Y-m-d", strtotime($dateto3));


  $update1=" UPDATE sched_date 
      SET fromcut = '$datefrom4',
      tocut  = '$dateto4'";
        
       $HRconnect->query($update1);

header("location:/hrms/pdf/leaveall.php");


}


if(isset($_GET["cutoff"]) == "cutoff")  
  { 

  $update1=" UPDATE sched_info 
      SET status = 'Saved'
      WHERE userid = '$userid'";
        
       $HRconnect->query($update1);

header("location:/hrms/viewsched.php");


}



 ?>
