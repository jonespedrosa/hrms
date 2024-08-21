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
	$empno = $row['empno'];
	$userid = $_SESSION['useridd'];

	// cut off viewing date
	$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
	ON si.empno = ui.empno
	WHERE si.status = 'Pending' AND ui.empno = $empno;";
	$querybuilder=$HRconnect->query($getDateSQL);
	$rowCutOff=$querybuilder->fetch_array();
	$schedStart = $rowCutOff['datefrom']; 
	$schedEnd = $rowCutOff['dateto'];
		if (isset($_GET['branch'])){
		@$_SESSION['useridd'] = $_GET['branch'];
		Header('Location: pincodeapproved.php?pincode=approved');
		}
		if($userlevel != 'staff'){
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
	</style>	
</head>
<body id="page-top" class="sidebar-toggled">
    <?php include("navigation.php"); ?>
<!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->      
                <?php  
                    if(isset($_GET["pincode"]) == "approved")  
                    {  
                    ?>   
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Staff using Pincode</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								: <?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>						
					</div>                    
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link" href="filedpincode.php?pending=pending"><b>Pending Pincode</b></a>
							</li>	
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="pincodeapproved.php?pincode=approved"><b>Approved Pincode</b></a>
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
						<th><center>FULL NAME</center></th>
						<th><center>DATE</center></th>	
						<th><center>APPROVER</center></th>
						<th><center>DATE OF APPROVAL</center></th>		
				
					</tr>
                 </thead>
								<?php if (@$_SESSION['useridd'] == null) { ?>
								<div class="alert alert-danger d-none d-sm-block text-center" role="alert">
								Choose Cafe/Department to View Pending Staff using Pincode</a>
								</div>
								<?php }?>
				<tbody>	
            	<?php 
					if (@$_SESSION['useridd'] != null) {
					if ($userlevel == 'master' OR $userlevel == 'admin' OR $userlevel == 'mod' OR $userlevel == 'ac') 
					{				
							// $sql = "SELECT user_info.empno, user_info.name, user_info.userlevel, sched_time.userid, sched_time.datefromto, sched_time.m_in_status, sched_time.M_timein, sched_time.M_timeout, sched_time.A_timein, sched_time.A_timeout FROM user_info JOIN sched_time ON user_info.empno = sched_time.empno WHERE sched_time.userid = 2008 AND m_in_status = 'Approved' AND userlevel IN ('staff','ac','master') AND datefromto BETWEEN '" .$schedStart. "' AND '" .$schedEnd. "'";	
							$sql = "SELECT user_info.empno, user_info.name, user_info.userlevel, sched_time.userid, sched_time.datefromto, sched_time.m_in_status, sched_time.min_empno, sched_time.oin_empno, sched_time.M_timein, sched_time.M_timeout, sched_time.A_timein, sched_time.A_timeout 
							FROM user_info JOIN sched_time ON user_info.empno = sched_time.empno 
							WHERE sched_time.min_empno = $empno AND (sched_time.m_in_status = 'Approved' OR sched_time.m_o_status = 'Approved'  OR sched_time.a_in_status = 'Approved'  OR sched_time.a_o_status = 'Approved') AND userlevel IN ('staff','ac','master','mod') AND datefromto BETWEEN '" .$schedStart. "' AND '" .$schedEnd. "'";	
							$query=$HRconnect->query($sql);
							while($row=$query->fetch_array())
							{
							$empnoPINCODE =  $row['empno'];
							$m_in_status = $row['m_in_status'];
							$datefromto = $row['datefromto'];
							$name = $row['name'];
							$m_timein = strtotime($row['M_timein']);
							$m_breakout = strtotime($row['M_timeout']);
							$a_breakin = strtotime($row['A_timein']);
							$a_timeout = strtotime($row['A_timeout']);
							$dateofapproval = $row['oin_empno']; //mo_empno column is use to stored date of approval
							?>
								<tr>        
								<td><center><?php echo $empnoPINCODE;?></center></td>  
								<td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>                    
								<td><center><?php echo $datefromto;?></center></td>		
								<td><center><?php echo $user;?></center></td>  
								<td><center><?php echo $dateofapproval;?></center></td>  
								</tr> 
								<?php
                            }
						}	
							}?>								
					</tbody>																								
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
			<?php } ?>
			<?php if(@$_GET['m'] == 5){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				});
				</script>			   
			<?php } ?>
			<!-- Footer -->
			<footer class="sticky-footer">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						 <span>Copyright © Mary Grace Foods Inc. 2023</span>
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