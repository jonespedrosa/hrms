 <?php  
 //logout.php  
 session_start();  
	 $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

      $date_time = date("Y-m-d H:i");
      $empno = $_SESSION['empno'];
      $inserted = "Logout Successfully";
      $action = "Logout Account";

      $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
         		VALUES('$empno', '$action', '$inserted','$date_time')";
      $HRconnect->query($sql2);



 session_destroy();  
 header("location:login.php");  
 ?>  