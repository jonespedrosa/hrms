<?php 
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
        
$date = date("Y-m-d");

$date1 = new DateTime($date);
$date1->modify('-1 day');
$newformat1 = $date1->format('Y-m-d');

                    @$sqll = "SELECT * FROM sched_time where datefromto = '$newformat1'";
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
      WHERE datefromto = '$newformat'";
        $HRconnect->query($update5);
		
	header("location:/video/404.php");


?>