<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 session_start();


setcookie("reloaded","true");

if(empty($_SESSION['user'])){
 header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$userid = $_SESSION['useridd'];
$empno = $row['empno'];


if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];

Header('Location: '.$_SERVER['PHP_SELF']);

}

$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];
if($userlevel != 'staff') {

if(isset($_POST["submit"]) == "submit") {
    @$_SESSION['datedate1'] = date('Y-m-d', strtotime($_POST['datefrom']));
    @$_SESSION['datedate2'] = date('Y-m-d', strtotime($_POST['dateto']));
    
}

 @$backfrom = $_SESSION['datedate1'];
 @$backto = $_SESSION['datedate2'];

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

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

       <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>

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



<body id="page-top">

	<?php include("navigation.php"); ?>
<!-- Begin Page Content -->
                <div class="container-fluid">
					<form method="POST">
                    <!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-2">
						<form method="POST">
                            <div class="form-group row">                                

								<div class="col-auto text-center">
                                    <label>Date From</label>
                                    <input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />                                                                                                              
                                </div>
                                                        
                                <div class="col-aut text-center">
                                    <label>Date To</label>
                                    <input type="date" id="#datePicker1" class="form-control text-center" name="dateto" placeholder="Insert Date" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
                                </div>
                                                        
								<div class="col-auto text-center d-none d-sm-inline-block">
                                    <label class="invisible">.</label>
                                    <div class="form-group row">
										<div class="col-xs-6 ml-2">
											<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="generatemanhours.php">
										</div> &nbsp
									</div>
								</div>
								
								<div class="col-sm-3 text-center d-md-none">
                                    <label class="invisible">.</label>
									<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" type="submit" name="submit" value="Submit" formaction="viewsched.php?backtrack=backtrack"> 
									<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear" formaction="viewsched.php?current=current">
								</div>
                            </div>
						</form>
					 
					</div>
										
                    <div class="row">

						<?php 

							$sql3="SELECT COUNT(*) as counting FROM sched_time
							WHERE userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != ''";
							$query3=$HRconnect->query($sql3);
							$row3=$query3->fetch_array();

							$sql4="SELECT SUM(time_to_sec(TIMEDIFF(A_timeout, M_timein))) as totalhours FROM sched_time
							WHERE userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != '' AND A_timeout != ''";
							$query4=$HRconnect->query($sql4);
							$row4=$query4->fetch_array();

                            $sql8="SELECT SUM(time_to_sec(TIMEDIFF(M_timeout, M_timein))) as totalhours1 FROM sched_time
                            WHERE userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != '' AND M_timeout != '' AND A_timein = '' AND A_timeout = ''";
                            $query8=$HRconnect->query($sql8);
                            $row8=$query8->fetch_array();

							$sql7="SELECT SUM(othours) as totalot FROM overunder
                            JOIN user_info on overunder.empno = user_info.empno
							WHERE user_info.userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND overunder.otdatefrom BETWEEN '$backfrom' AND '$backto' AND overunder.otstatus = 'approved'";
							$query7=$HRconnect->query($sql7);
							$row7=$query7->fetch_array();

							$sql5="SELECT SUM(break) as totalbreak FROM sched_time
							WHERE userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != '' AND A_timeout != 'No Break' AND M_timeout != 'No Break' AND A_timeout != '' AND break = 1";
							$query5=$HRconnect->query($sql5);
							$row5=$query5->fetch_array();

							$sql6="SELECT COUNT(*) as nobreak FROM sched_time
							WHERE userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3) AND datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != '' AND (A_timeout = 'No Break' OR M_timeout = 'No Break') AND A_timeout != ''";
							$query6=$HRconnect->query($sql6);
							$row6=$query6->fetch_array();

            

							$totalhours = ($row4['totalhours'] + $row8['totalhours1'] - $row7['totalot'])/3600;

							?>
							
						
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Manpower</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row3['counting']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Hours</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo round($totalhours - $row5['totalbreak'],2); ?>
                                                    
                                                </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Man Head</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo round(($totalhours - $row5['totalbreak'])/8,2 ); ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-child fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						

                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Break Hours</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row5['totalbreak']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-drumstick-bite fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<!-- Total Expenses (Monthly) -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total No Break</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row6['nobreak']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-exclamation-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
						<!-- Total Expenses (Monthly) -->
                        <div class="col-xl-2 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Overtime</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $row7['totalot']; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-hourglass-start fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					
					</div>	
                        </form>
						
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-left-primary">
                            <h6 class="m-0 font-weight-bold text-primary d-md-none">Daily Posting</h6>
						</div>
                        

						<div class="card-body border-left-primary">
                            <div class="table-responsive">     
                                <table class="table table-bordered table-hover text-uppercase" id="dataTable" width="100%" cellspacing="0">

                                    <thead>
                                        <tr class="bg-gray-200">																										
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Date</center></th>
                                            <th><center>Time</center></th>
											<th><center>Time IN</center></th>
											<th><center>Break OUT</center></th>	
                                            <th><center>Break IN</center></th>	
                                            <th><center>Time OUT</center></th>	 								
										</tr>
                                    </thead>
  
									<tbody>																												

                                   
												
 <?php 


 $sql1 = "SELECT * FROM sched_time where datefromto BETWEEN '$backfrom' AND '$backto' AND M_timein != '' AND A_timeout != '' AND userid in (225,216,215,214,173,172,171,169,168,167,166,165,164,92,80,3)";
 $query1=$HRconnect->query($sql1);  
 while($row1=$query1->fetch_array()){
    $empno = $row1['empno'];
    $id = $row1['id'];
	@$datefromto = $row1['datefromto']; 
    @$timeform = $row1['schedfrom']; 
    @$timeto = $row1['schedto']; 
    @$Timein = $row1['M_timein']; 
    @$Breakout = $row1['M_timeout'];
    @$Breakin = $row1['A_timein']; 
    @$Timeout = $row1['A_timeout']; 
    @$remarks   = $row1['remarks'];


    $sql2 = "SELECT * FROM user_info where empno = '$empno'";
        $query2=$HRconnect->query($sql2);  
        $row2=$query2->fetch_array();
        $empno1 = $row2['empno'];
        $name1 = $row2['name'];
        $position1 = $row2['position'];


 ?>         	        	

								<tr>

                             

												<td><center><?php echo $empno; ?></center></td>
												<td><center><?php echo utf8_encode($name1); ?></a></center></td>
												<td><center><?php echo $datefromto; ?></center></td>
                                                <td><center><?php echo date('H:i', strtotime($timeform)); ?> - <?php echo date('H:i', strtotime($timeto));?></a></center></td>
                                                <?php if($Timein != ''){ ?>
												<td><center><?php echo date('H:i', strtotime($Timein)); ?></center></td>   
                                                <?php }else{ ?>
                                                <td><center><?php echo $Timein; ?></center></td>
                                                <?php } ?>

                                                <?php if($Breakout != '' AND $Breakout != 'No Break'){ ?>
                                                <td><center><?php echo date('H:i', strtotime($Breakout)); ?></center></td>   
                                                <?php }else{ ?>
                                                <td><center><?php echo $Breakout; ?></center></td>
                                                <?php } ?>

                                                <?php if($Breakin != '' AND $Breakout != 'No Break'){ ?>
                                                <td><center><?php echo date('H:i', strtotime($Breakin)); ?></center></td>   
                                                <?php }else{ ?>
                                                <td><center><?php echo $Breakin; ?></center></td>
                                                <?php } ?>

                                                <?php if($Timeout != ''){ ?>
                                                <td><center><?php echo date('H:i', strtotime($Timeout)); ?></center></td>   
                                                <?php }else{ ?>
                                                <td><center><?php echo $Timeout; ?></center></td>
                                                <?php } ?>

                                            </tr>
											
											<?php 
                                             }
                                             ?>	

                                        									
									</tbody>	
							               														
							</table>
                        </div>
            
                        </div>  
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer --><footer class="sticky-footer">
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>


</body>

</html>

<?php } ?>
