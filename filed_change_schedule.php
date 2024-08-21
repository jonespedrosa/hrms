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

$userid = $_SESSION['useridd'];
if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];

Header('Location: filed_change_schedule.php?pending=pending');

}
	

if($userlevel != 'staff') {

$a=array(date("Y-m-30")=>date("Y-m-30"),date("Y-m-31")=>date("Y-m-31"),date("Y-m-01")=>date("Y-m-01"),date("Y-m-02")=>date("Y-m-02"),date("Y-m-03")=>date("Y-m-03"),date("Y-m-04")=>date("Y-m-04"),date("Y-m-05")=>date("Y-m-05"),date("Y-m-06")=>date("Y-m-06"),date("Y-m-07")=>date("Y-m-07"),date("Y-m-08")=>date("Y-m-08"),date("Y-m-09")=>date("Y-m-09"),date("Y-m-10")=>date("Y-m-10"),date("Y-m-11")=>date("Y-m-11"),date("Y-m-12")=>date("Y-m-12"),date("Y-m-13")=>date("Y-m-13"),date("Y-m-14")=>date("Y-m-14"));

if (array_key_exists(date("Y-m-d"),$a))
  {
    $newdate1 = date("Y-m-24", strtotime("-1 months"));
    $newdate2 = date("Y-m-24");

  }
else
  {
   $newdate1 = date("Y-m-24", strtotime("-1 months"));
   $newdate2 = date("Y-m-24");
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
		
		.exportExcel{
			  background-color: #f2f2f2;
			  border-style: solid;
			  border-color: #a1a1a1;
			  border-radius: 5px;
			  border-width: 1px;
			  color: white;
			  padding: 3px 10px;
			  text-align: center;
			  display: inline-block;
			  font-size: 16px;
			  color: black;
			  cursor: pointer;
			  bottom: 0;
		}
	</style>
	
	<style>
		@import url(https://fonts.googleapis.com/css?family=Dosis:300,400);


		
		/* effect-shine */
		a.effect-shine:hover {
		  -webkit-mask-image: linear-gradient(-75deg, rgba(0,0,0,.6) 30%, #000 50%, rgba(0,0,0,.6) 70%);
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
		.ow-break-word {
			overflow-wrap: break-word;
		}
	</style>	

</head>

<body id="page-top" class="sidebar-toggled">

    <?php include("navigation.php"); ?>

<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                
                <?php  
                    if(isset($_GET["pending"]) == "pending")  
                    {  
						// date_default_timezone_set('Asia/Manila');
						// $datenow = date("Y-m-d H:i");
                    ?>   

                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Change Schedule</h4>
							<div class="small">


								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>						
					</div>
                     
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="filed_change_schedule.php?pending=pending"><b>Pending Change Schedule</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link" href="filed_change_schedule.php?csapproved=csapproved"><b>Approved Change Schedule</b></a>
							</li>
						</ul>
						
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>   				
						</div>
						
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%" cellspacing="0">
                                    									
									<thead>
                                        <tr class="bg-gray-200">																																					
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Date</center></th>
											<th><center>Type of Request</center></th>
											<th><center>Status</center></th>
											<th><center>Approver</center></th>	
											<th><center>Action</center></th>		
										</tr>
                                    </thead>

                                        <?php if (@$_SESSION['useridd'] == null) { ?>
                            <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                Choose Cafe/Department to View Pending Change Schedule</a>
                            </div>
                                <?php }?>
									<?php if($_SESSION['empno'] != 3080 AND $_SESSION['empno'] != 1261 AND $_SESSION['empno'] != 1910 AND $_SESSION['empno'] != 3736 
									AND $_SESSION['empno'] != 4070 AND $_SESSION['empno'] != 3770 AND $_SESSION['empno'] != 4206 AND $_SESSION['empno'] != 3160 
									AND $_SESSION['empno'] != 1509 AND $_SESSION['empno'] != 1053 AND $_SESSION['empno'] != 2356 AND $_SESSION['empno'] != 3156 
									AND $_SESSION['empno'] != 3612 AND $_SESSION['empno'] != 4001 AND $_SESSION['empno'] != 5263 AND $_SESSION['empno'] != 5430
									AND $_SESSION['empno'] != 4892 AND $_SESSION['empno'] != 3337 AND $_SESSION['empno'] != 6436 AND $_SESSION['empno'] != 6209
								    AND $_SESSION['empno'] != 6244 AND $_SESSION['empno'] != 6245 AND $_SESSION['empno'] != 6438){?>
									<tbody>	
									<?php 
										// cutoff
										$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
										ON si.empno = ui.empno
										WHERE si.status = 'Pending' AND ui.empno = $empno;";
										$querybuilder=$HRconnect->query($getDateSQL);
										$rowCutOff=$querybuilder->fetch_array();
										$datefrom = $rowCutOff['datefrom'];
										$dateto = $rowCutOff['dateto'];

                                         // TABLE QUERIES
										if (@$_SESSION['useridd'] != null) {
											// Conditionals
											//IT & HR + AUDIT
											if ($userlevel == 'master' OR $empno == 1348 OR $empno == 271) {
												$sql = "SELECT * FROM user_info
                                                JOIN change_schedule on user_info.empno = change_schedule.empno
                                                WHERE user_info.userid = $userid AND change_schedule.datefrom 
												BETWEEN '$datefrom' AND '$dateto' AND cs_status in ('pending','pending2')
												AND user_info.empno != ".$_SESSION['empno']."";
											}
											
											//ML1 & ML2 + AC
											if ($userlevel == 'ac' AND $empno != 2221 AND $empno != 3111 AND $empno != 3071 
											OR $empno == 1 OR $empno == 2 OR $empno == 4) {
												$sql = "SELECT * FROM user_info
                                                JOIN change_schedule on user_info.empno = change_schedule.empno
                                                WHERE user_info.userid = $userid AND change_schedule.datefrom 
												BETWEEN '$datefrom' AND '$dateto' AND cs_status = 'pending2' 
												AND user_info.empno != ".$_SESSION['empno']." ";
											}
											
											//Supervisor & MOD
											if ($userlevel == 'mod' OR $empno == 2221 OR $empno == 3111 AND $empno != 4292) {
												$sql = "SELECT * FROM user_info
                                                JOIN change_schedule on user_info.empno = change_schedule.empno
                                                WHERE user_info.userid = $userid AND change_schedule.datefrom 
												BETWEEN '$datefrom' AND '$dateto' AND cs_status = 'pending' 
												AND user_info.empno != ".$_SESSION['empno']." ";
											}
																					
											
											//Admin
											if ($empno == 3071) {
												$sql = "SELECT * FROM user_info
                                                JOIN change_schedule on user_info.empno = change_schedule.empno
                                                WHERE user_info.userid = $userid AND user_info.empno != 4625
												AND change_schedule.datefrom BETWEEN '$datefrom' AND '$dateto'
												AND cs_status = 'pending2' AND user_info.empno != ".$_SESSION['empno']."";
											}
											if ($empno == 2203) {
												$sql = "SELECT * FROM user_info
                                                JOIN change_schedule on user_info.empno = change_schedule.empno
                                                WHERE user_info.userid = $userid AND user_info.empno != 4625 
												AND change_schedule.datefrom BETWEEN '$datefrom' AND '$dateto'
												AND cs_status = 'pending' AND user_info.empno != ".$_SESSION['empno']."";
											}
											

											// HOOKS
                                                $queryCS = mysqli_query($HRconnect, $sql);
												$rowCS = mysqli_fetch_all($queryCS, MYSQLI_ASSOC);

												$counter = 0;
                                                while($counter < count($rowCS))
                                                {
												
												$cs_status = $rowCS[$counter]['cs_status'];
												$name = $rowCS[$counter]['name'];
												
                                            ?>
                                            <tr>        
                                                <td><center><?php echo $rowCS[$counter]['empno']; ?></center></td>  
                                                <td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>                    
                                                <td><center><?php echo $rowCS[$counter]['datefrom']; ?></center></td>
                                                <td><center><?php echo "Change Schedule";?></center></td>
												<?php 
													if($cs_status == 'pending2'){
													?>
													<td><center>Partially Approved</center></td>
												<?php    
													}else{
													?>
													<td><center><?php echo $rowCS[$counter]['cs_status']; ?></center></td>
												<?php
													}
													?>
													
												<?php 
													if($cs_status == 'pending2'){
													?>
													<td><center><?php echo $rowCS[$counter]['p_approver']; ?></center></td>
												<?php    
													}else{
													?>
													<td><center><?php echo $rowCS[$counter]['approver']; ?></center></td>
												<?php
													}
													?>
                                                <td><center>
                                                <a href="pdf/viewchangesched.php?cs=cs&id=<?php echo $rowCS[$counter]['cs_ID']; ?>" class="btn btn-info btn-user btn-block btn-sm bg-gradient-info">View</a>
                                                </center></td>                                                                                                                                                                                                                                     
                                            </tr>
                                            <?php 
                                        $counter +=1;
                                        }
									}
								}
                                        ?>							
									</tbody>																								
								</table>
                            </div>
                        </div>
                    </div>
				<?php 
                    }
                    ?>
					
				<?php  
                    if(isset($_GET["csapproved"]) == "csapproved")   
                    {  
                    ?> 
					
					<div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Change Schedule</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>						
					</div>
					
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link" href="filed_change_schedule.php?pending=pending"><b>Pending Change Schedule</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="filed_change_schedule.php?csapproved=csapproved"><b>Approved Change Schedule</b></a>
							</li>
						</ul>
					
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>   				
						</div>
						
                        <div class="card-body">	
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%" cellspacing="0">
									<thead>
                                        <tr class="bg-gray-200">																																					
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Reason</center></th>
											<th><center>Type of Request</center></th>												
											<th><center>Date</center></th>										
											<th><center>Approved By</center></th>
										</tr>
                                    </thead>
                            <?php if (@$_SESSION['useridd'] == null) { ?>
                            <div class="alert alert-danger d-none d-sm-block text-center" role="alert">
                                Choose Cafe/Department to View Approved Change Schedule</a>
                            </div>
                                <?php }?>
                                    
                                    <tbody> 
                                        
                                        <?php 

										// cutoff
										$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
										ON si.empno = ui.empno
										WHERE si.status = 'Pending' AND ui.empno = $empno;";
										$querybuilder=$HRconnect->query($getDateSQL);
										$rowCutOff=$querybuilder->fetch_array();

										$cutfrom = '2024-01-09';
										$cutto = '2024-12-23';
										
                                        if (@$_SESSION['useridd'] != null) {
											if ($userlevel == 'master' OR $userlevel == 'admin') {
												$sql = "SELECT * FROM user_info
												JOIN change_schedule on user_info.empno = change_schedule.empno
												WHERE change_schedule.cs_status = 'approved' AND mothercafe = $userid 
												AND change_schedule.datefrom BETWEEN '$cutfrom' AND '$cutto'";
												}else{
												$sql = "SELECT * FROM user_info
												JOIN change_schedule on user_info.empno = change_schedule.empno
												WHERE change_schedule.cs_status = 'approved' AND mothercafe = $userid 
												AND change_schedule.datefrom BETWEEN '$cutfrom' AND '$cutto'";
												}
												
												
                                                $query=$HRconnect->query($sql);
                                                while($row=$query->fetch_array()){
                                                    $name = $row['name'];												
                                            ?>
											
                                            <tr>        
                                                <td class="ow-break-word" style="text-align: center; vertical-align: middle;"><center><?php echo $row['empno']; ?></center></td>
                                                <td class="ow-break-word" style="text-align: center; vertical-align: middle;"><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
                                                <td class="ow-break-word" style="text-align: center; vertical-align: middle; max-width: 500px"><center><?php echo $row['cs_reason']; ?></center></td>
                                                <td class="ow-break-word" style="text-align: center; vertical-align: middle;"><center>
													<?php
													echo "Change Schedule";
													?>
												</center></td>
                                                <td class="ow-break-word" style="text-align: center; vertical-align: middle;"><center><?php echo $row['datefrom']; ?></center></td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;"><center><?php echo $row['approver']; ?></center></td>
											</tr>

                                            <?php 
												}
                                    }
                                        ?>
									</tbody>
									
									<tfoot>
										<tr>
											<td colspan="6"><center></center></td>
										</tr>
									</tfoot>		
								</table>
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
			
			<?php if(@$_GET['m'] == 2){ ?>              
				<script>
					$(function() {
				    $(".thanks").delay(2500).fadeOut();
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
							<h5 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change Schedule</h5>
						<small class="text-light">just now</small>
						</div>
						<div class="toast-body">
							You have <b class="text-success">Successfully Approve</b> Change Schedule. Thank you!
						</div>
					</div>
				</div>
			<?php } ?>
			
			<?php if(@$_GET['m'] == 5){ ?>              
				<script>
					$(function() {
					$(".thanks").delay(2500).fadeOut();
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-warning">
							<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
							<small class="text-light">just now</small>
						</div>
						<div class="toast-body">
							You have <b class="text-warning">Successfully Cancel</b> Change Schedule. Thank you!
						</div>
					</div>
				</div>
			<?php } ?>
					
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
	
	<script>
        $(document).ready(function() {
        $('#example').dataTable( {
        stateSave: true
        } );
        } );
    </script>
	
	<script>
        $(document).ready(function() {
        $('#example1').dataTable( {
        stateSave: true
        } );
        } );
    </script>
	
	
</body>

</html>
<?php } ?>