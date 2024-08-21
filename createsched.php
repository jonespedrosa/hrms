<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 session_start();


if(empty($_SESSION['user'])){
 header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$empno = $row['empno'];

$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];




if($userlevel != 'staff') {

$userid = $_SESSION['useridd'];

// UPDATE

$sqll="SELECT * FROM sched_date 
              WHERE userid = '$userid'";
            $query=$HRconnect->query($sqll);
            $row=$query->fetch_array();
            @$datefrom2 = $row['datefrom'];
            @$dateto2 = $row['dateto']; 
            @$timefrom = $row['schedfrom'];
            @$timeto = $row['schedto'];  

  $HRconnect->query($sqll);


    if(!empty($_POST["id"])){

    foreach ($_POST["id"] as $key => $value) {

            $sql2 = "SELECT COUNT(*) FROM user_info 
              WHERE empno = '$value' AND userid = '$userid' AND status = 'active' AND approval = 'approve'";

            $query2=$HRconnect->query($sql2);
            $row2=$query2->fetch_array();



            if ($row2['COUNT(*)'] > 0){

  $query1 = "INSERT INTO sched_info(userid,empno,datefrom,dateto,schedfrom,schedto) 
  SELECT '$userid','$value','$datefrom2','$dateto2','$timefrom','$timeto' FROM DUAL WHERE Not 
  EXISTS(SELECT * FROM sched_info WHERE empno = '$value' AND  datefrom = '$datefrom2' AND  dateto = '$dateto2') LIMIT 1";


$HRconnect->query($query1);

$date_time = date("Y-m-d H:i");
$inserted = "Successfully Add Employee";
$action =$value . " - Add Employee Schedule" ;
$empno = $_SESSION['empno'];

$sql3 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno', '$action', '$inserted','$date_time')";

$HRconnect->query($sql3);

foreach ($_POST["date"] as $key => $date) {

    if($timeto >= '00:00' AND $timeto <= '03:00'){

       $out = date('Y-m-d', strtotime($date . ' +1 day'));
       $outtime = $out ." ". $timeto;

    }else{

        $out = $date;
        $outtime = $out ." ". $timeto;

    }

    $intime = date('Y-m-d', strtotime($date)) ." ". $timefrom;

if($date >= date('Y-m-d')){

  $query1 = "INSERT INTO sched_time(userid,empno,datefromto,schedfrom,schedto) 
  SELECT '$userid','$value','$date','$intime','$outtime' FROM DUAL WHERE Not 
  EXISTS(SELECT * FROM sched_time WHERE empno = '$value' AND  datefromto = '$date') LIMIT 1";


}else{
    $approved = "approved";

    $query1 = "INSERT INTO sched_time(userid,empno,datefromto,schedfrom,schedto,status) 
  SELECT '$userid','$value','$date','$intime','$outtime','$approved' FROM DUAL WHERE Not 
  EXISTS(SELECT * FROM sched_time WHERE empno = '$value' AND  datefromto = '$date') LIMIT 1";

  
}

  $HRconnect->query($query1);




	header("location:/hrms/viewsched.php?current=current");
}




}else{



echo "<script type='text/javascript'>alert('Employee Number Not Active or Pending for Approval');
        window.location.href='createsched.php';
        </script>";

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

    <title>Mary Grace Foods Inc.</title>
	<link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

	<!-- New Form -->	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<style>
	.block 
	{	
	width:50%;
	}
	</style>
	
	<style>
	select{
    text-align-last:center;
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
							<h4 class="mb-0">Add Schedule</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>						
					</div>
			
                    <div class="row justify-content">
						<div class="col-xl-6 col-lg-12 col-md-9">
							<div class="card o-hidden border-0 shadow-lg">
								<div class="card-body p-0">
									<!-- Nested Row within Card Body -->
									<div class="row">                
										<div class="col-lg-12">
											<div class="p-5">										
												<div class="text-center">

												<?php 
														$date = "SELECT * FROM sched_date where userid = $userid";
														$query=$HRconnect->query($date);  
														$daterow=$query->fetch_array();
														@$datefrom = $daterow['datefrom'];
														@$dateto = $daterow['dateto'];   
														@$ff = $daterow['schedfrom'];
														@$tt = $daterow['schedto'];  
														
												?>
                                                <?php 
                                                @$messages = $_GET['message'];
                                                ?>
                                         
												
												<h1 class="h5 text-gray-800 mb-3">Schedule Details</h1>
												</div>
												
												<center><a class="text-success"><?php echo @$messages; ?></a></center>
												<form class="user" method="post">

<?php 
$a=array(date("Y-m-23")=>date("Y-m-23"),date("Y-m-24")=>date("Y-m-24"),date("Y-m-25")=>date("Y-m-25"),date("Y-m-26")=>date("Y-m-26"),date("Y-m-27")=>date("Y-m-27"),date("Y-m-28")=>date("Y-m-28"),date("Y-m-29")=>date("Y-m-29"),date("Y-m-30")=>date("Y-m-30"),date("Y-m-31")=>date("Y-m-31"));

$b=array(date("Y-m-01")=>date("Y-m-01"),date("Y-m-02")=>date("Y-m-02"),date("Y-m-03")=>date("Y-m-03"),date("Y-m-04")=>date("Y-m-04"),date("Y-m-05")=>date("Y-m-05"),date("Y-m-06")=>date("Y-m-06"),date("Y-m-07")=>date("Y-m-07"));

if (array_key_exists(date("Y-m-d"),$a))
  /* 9 to 23 cut-off */
  {
    $newdate1 = date("Y-m-24");
    $newdate2 = date("Y-m-08" , strtotime("+1 months"));
  }
  /* end */
elseif (array_key_exists(date("Y-m-d"),$b))
  {

   $newdate1 = date("Y-m-24", strtotime("-1 months"));
   $newdate2 = date("Y-m-08");

  }
  else
  {
/* 24 to 08 cut-off */
   $newdate1 = date("Y-m-09");
   $newdate2 = date("Y-m-23");
  }
/* end */
  $_SESSION['datedatefrom'] =  $newdate1;
  $_SESSION['datedateto'] =  $newdate2;


                                    if($datefrom == '') { ?>
													<div class="form-group row">								
														<div class="col-sm-6 mb-3 mb-sm-0 text-center">
															<label>Cut-Off Date From</label>
															<input type="date" id="datePicker"  class="form-control text-center" name="datefrom" placeholder="Insert Date" autocomplete="off" value="<?php echo  $newdate1; ?>" required onkeypress="return false;" />
														</div>
														
														<div class="col-sm-6 text-center">
															<label>Cut-Off Date To</label>
															<input type="date" id="datePicker1" class="form-control text-center" name="dateto"  placeholder="Insert Date" autocomplete="off" value="<?php echo  $newdate2; ?>" required onkeypress="return false;" />
														</div>
													</div>
			

<?php } if($datefrom != ''){ ?>

                                        <div class="form-group row">                                
                                                        <div class="col-sm-6 mb-3 mb-sm-0 text-center">
                                                            <label>Cut-Off Date From</label>
                                                            <input type="date"  id="datePicker" disabled class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo  $newdate1; ?>" autocomplete="off" required onkeypress="return false;" />																												
														</div>
                                                        
                                                        <div class="col-sm-6 text-center">
                                                            <label>Cut-Off Date To</label>
                                                            <input type="date" id="datePicker1"  disabled class="form-control text-center" name="dateto" placeholder="Insert Date" value="<?php echo  $newdate2; ?>" autocomplete="off" required onkeypress="return false;" />
                                                        </div>
                                                    </div>
                

                                        <?php } ?>

                                                    <center><label>Time of Schedule</label></center>
                                                    
                                                    <div class="form-group row">  

                                                        <div class="col-sm-6 mb-3 mb-sm-0">
                             
                                            <select class="form-control text-center" name="tfrom" required>
                                                        
                                                <option  selected hidden><?php echo date("H:i", strtotime($ff)); ?></option>
                                                <option>00:00</option>
                                                <option>01:30</option>
                                                <option>02:00</option>
                                                <option>02:30</option>
                                                <option>03:00</option>
                                                <option>03:30</option>
                                                <option>04:00</option>
                                                <option>04:30</option>
                                                <option>05:00</option>
                                                <option>05:30</option>
                                                <option>06:00</option>
                                                <option>06:30</option>
                                                <option>07:00</option>
                                                <option>07:30</option>
                                                <option>08:00</option>
                                                <option>08:30</option>
                                                <option>09:00</option>
                                                <option>09:30</option>
                                                <option>10:00</option>
                                                <option>10:30</option>
                                                <option>11:00</option>
                                                <option>11:30</option>
                                                <option>12:00</option>
                                                <option>12:30</option>
                                                <option>13:00</option>
                                                <option>13:30</option>
                                                <option>14:00</option>
                                                <option>14:30</option>
                                                <option>15:00</option>
                                                <option>15:30</option>
                                                <option>16:00</option>
                                                <option>16:30</option>
                                                <option>17:00</option>
                                                <option>17:30</option>
                                                <option>18:00</option>
                                                <option>18:30</option>
                                                <option>19:00</option>
                                                <option>19:30</option>
                                                <option>20:00</option>
                                                <option>20:30</option>
                                                <option>21:00</option>
                                                <option>21:30</option>
                                                <option>22:00</option>
                                                <option>22:30</option>
                                                <option>23:00</option>
                                                <option>23:30</option>

                                            </select>
                                           
                                   
                                                        </div>

                                                        <div class="col-sm-6">
                                                   


                    
                                     
                                            <select class="form-control text-center"  name="tto" required>
                                                        
                                                <option  selected hidden><?php echo date("H:i", strtotime($tt)); ?></option>
                                                <option>00:00</option>
                                                <option>01:30</option>
                                                <option>02:00</option>
                                                <option>02:30</option>
                                                <option>03:00</option>
                                                <option>03:30</option>
                                                <option>04:00</option>
                                                <option>04:30</option>
                                                <option>05:00</option>
                                                <option>05:30</option>
                                                <option>06:00</option>
                                                <option>06:30</option>
                                                <option>07:00</option>
                                                <option>07:30</option>
                                                <option>08:00</option>
                                                <option>08:30</option>
                                                <option>09:00</option>
                                                <option>09:30</option>
                                                <option>10:00</option>
                                                <option>10:30</option>
                                                <option>11:00</option>
                                                <option>11:30</option>
                                                <option>12:00</option>
                                                <option>12:30</option>
                                                <option>13:00</option>
                                                <option>13:30</option>
                                                <option>14:00</option>
                                                <option>14:30</option>
                                                <option>15:00</option>
                                                <option>15:30</option>
                                                <option>16:00</option>
                                                <option>16:30</option>
                                                <option>17:00</option>
                                                <option>17:30</option>
                                                <option>18:00</option>
                                                <option>18:30</option>
                                                <option>19:00</option>
                                                <option>19:30</option>
                                                <option>20:00</option>
                                                <option>20:30</option>
                                                <option>21:00</option>
                                                <option>21:30</option>
                                                <option>22:00</option>
                                                <option>22:30</option>
                                                <option>23:00</option>
                                                <option>23:30</option>

                                            </select>
                                           
                     
                                                        </div>
                                                    </div>

													<hr>
													<center><small class="text-muted"><i>Please Insert Schedule Details First Before you Add Employee</i></small></center>
													<br>
													<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" value="Insert Schedule" formaction="update.php?date=date" onclick="return confirm('Are you sure you want to Insert this Data?');">
												</form>
											</div>
										</div>
									</div>
								</div>
							</div><br>
						</div>
					
						<div class="col-xl-6 col-lg-12 col-md-9">
							<div class="card o-hidden border-0 shadow-lg">
								<div class="card-body p-0">
									<!-- Nested Row within Card Body -->
									<div class="row">                
										<div class="col-lg-12">
											<div class="p-5">										
												<div class="text-center">                                                  
													<h1 class="h5 text-gray-800 mb-3">List Of Employee </h1>
													<p class="text-danger"><?php echo @$message; ?></p>
													<div class="container">
														<div class="form-group">
															<form class="user" name="add_name" id="add_name" method="post">
															
																	<?php   
																		$sql = "SELECT * FROM sched_date WHERE userid = '$userid'";
																		$query=$HRconnect->query($sql);  
																		$row=$query->fetch_array();
																		@$useridd = $row['userid'];
																		@$begin = new DateTime($datefrom);
																		@$end = new DateTime($dateto);

																				if($useridd > 0){
																					$end->modify('+1 day');
																					}

																					$interval = DateInterval::createFromDateString('1 day');
																					$period = new DatePeriod($begin, $interval, $end);

																					$from = $begin->format('Y-m-d');
																					$to = $end->format('Y-m-d');

																				foreach ($period as $dt => $value) {
																				$newDate = $value->format('m-d-Y');

																				$asd = $value->format('Y-m-d');

																				$var = NULL; 
																			?>

																			 <input class="text-center" hidden type="text" name="date[]" value="<?php echo $asd; ?>" readonly / >

																			<?php } ?>
																<div class="table-responsive">  
																	<table class="table table-bordered" id="dynamic_field">  
																		<tr>  
																			<td class="border-white"><input class="form-control form-control-user text-center" type="text" name="id[]" placeholder="Employee ID" class="form-control name_list" required="" /></td>  
																			<td class="border-white"><button type="button" name="add" id="add" class="btn btn-success btn-user bg-gradient-success"><i class="fa fa-plus"></i></button></td>  
																		</tr>  
																	</table>  
																	<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" onclick="return confirm('Are you sure you want to Insert this Data?');" />  
																</div>
															</form>  
														</div> 
													</div>
												</div>                                        
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>
				
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
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

	<!-- Add input script-->
	<script type="text/javascript">
		$(document).ready(function(){      
			var postURL = "/addmore.php";
			var i=1;  


			$('#add').click(function(){  
				i++;  
				$('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td class="border-white"><input class="form-control form-control-user text-center" type="text" name="id[]" placeholder="Employee ID" class="form-control name_list" required /></td><td class="border-white"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn-user bg-gradient-danger btn_remove">X</button></td></tr>');  
			});


			$(document).on('click', '.btn_remove', function(){  
				var button_id = $(this).attr("id");   
				$('#row'+button_id+'').remove();  
			});  


			$('#submit').click(function(){            
				$.ajax({  
					url:postURL,  
					method:"POST",  
					data:$('#add_name').serialize(),
					type:'json',
					success:function(data)  
					{
						i=1;
						$('.dynamic-added').remove();
						$('#add_name')[0].reset();
								alert('Record Inserted Successfully.');
					}  
			   });  
		  });


		});  
	</script>
	
	<!-- Calendar Restriction-->
	<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css"/>
    <script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>
	
	<script>
        $("#datePicker").kendoDatePicker({
            disableDates: function (date) {
                var disabled = [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20,21,22,23,25,26,27,28,29,30,31];
                if (date && disabled.indexOf(date.getDate()) > -1 ) {
                    return true;
                } else {
                    return false;
                }
            }
        });
		
		$("#datePicker1").kendoDatePicker({
			disableDates: function (date) {
                var disabled = [1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26,27,28,29,30,31];
                if (date && disabled.indexOf(date.getDate()) > -1 ) {
                    return true;
                } else {
                    return false;
                }
            }
        });
		
    </script>
	
</body>

</html>

<?php } ?>