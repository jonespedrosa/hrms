<!-- Begin Page Content --> <!-- Search -->
<?php  
    $ORconnect = mysqli_connect("localhost", "root", "", "db");
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    session_start();
    unset($_SESSION['viewPrintSched']);
    unset($_SESSION['emp_sched']); 
if(empty($_SESSION['user'])){
 header('location:login.php');
}
if(isset($_GET["m"])){
    if($_GET["m"] == 2){
        echo '
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(function() {
        $(".thanks").delay(2500).fadeOut();
        
        });
        </script>

        <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px; z-index: 9999;">
            <div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
                <div class="toast-header bg-warning">
                <h4 class="mr-auto my-0 text-light"> Alert</h5>
                <small class="text-light">just now</small>
                </div>
                <div class="toast-body">
                You have to<b class="text-warning"> Select an employee </b>to view schedule. Thank you!
                </div>
            </div>
        </div>
        ';
    }
}
$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();
$userlevel = $row['userlevel'];
$empno = $row['empno'];

$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];

$userid = $_SESSION['useridd'];

if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];

header('Location:viewsched.php?current=current');

}


if(isset($_POST["submit"]) == "submit") {
    @$_SESSION['datedate1'] = date('Y-m-d', strtotime($_POST['datefrom']));
    @$_SESSION['datedate2'] = date('Y-m-d', strtotime($_POST['dateto']));
    
}

 @$backfrom = $_SESSION['datedate1'];
 @$backto = $_SESSION['datedate2'];


if($userlevel != 'staff') {

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

	<?php include("navigation.php"); ?>	
<!-- Begin Page Content -->
    
	<form method="POST">           
				<div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Schedules</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>	
                </div>
					
                <form method="POST">
							<div class="col-xl-2 col-sm-4 mb-2">
								<p>Historical DTR</p>
							</div>
							<div class="row mb-3">
								<div class="col-xl-2 col-sm-4 mb-2">
									<input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />
								</div>
								
								<div class="col-xl-2 col-sm-4 mb-2">
									<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" placeholder="Insert Date" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
								</div>
								
								<div class="col-xl-4 col-sm-4">
									<div class="row text-center">									
										<div class="col-xl-3 col-sm-6 mb-2">
											<input class="btn btn-primary btn-block btn-user bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="viewsched.php?backtrack=backtrack">
										</div>
										
										<div class="col-xl-3 col-sm-6 float-right">
											<input class="btn btn-danger btn-block btn-user bg-gradient-danger text-center" type="submit" value="Clear" formaction="viewsched.php?current=current">
										</div>
									</div>
								</div>
							</div>						
					</form>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Default Schedule: 08:00 to 17:00</h6>
							
                            <!-- FOR CELLPHONE VIEWING
							<div>
								<a href="createsched.php" data-toggle="tooltip" data-placement="top" title="click to create schedule">
                                <i class="fa fa-calendar-plus fa-sm text-primary-50 d-md-none"></i></a>     		
                            </div>-->
                            
						</div>

                        <div class="card-body">
                            <div class="d-flex d-lg-none mb-2">
								<div class="p-2">	
                                    <a href="changesched.php" class="btn btn-sm btn-primary btn-icon-split bg-gradient-primary">
                                        <small>
                                            <span class="icon text-white-50">
                                                <i class="fas fa-calendar-plus"></i>
                                            </span>
                                            <span class="text">Edit Schedule</span>
                                        </small>
                                    </a>  
                                    <?php if($userlevel == 'master') { ?>
                                        <a href="createsched.php" class="d-none d-sm-inline-block btn btn-sm btn-primary btn-icon-split bg-gradient-primary">
                                            <small>
                                                <span class="icon text-white-50">
                                                <i class="fas fa-calendar-plus"></i>
                                                </span>
                                                <span class="text">Create Schedule</span>
                                            </small>
                                    </a>  
                                    <?php }?>
                                    <a class="btn btn-info btn-sm bg-gradient-info btn-icon-split" href="dailypost.php" data-toggle="tooltip" 
                                    data-placement="top" title="Click to Post Daily DTR Logs">
                                        <small>
                                            <span class="icon text-white-50">
                                                <i class="fa fa-calendar-check" aria-hidden="true"></i> 
                                            </span>
                                            <span class="text">Daily DTR</span>
                                        </small>
                                    </a>
								</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%" cellspacing="0">
									<thead>
										<tr>																										
											<th colspan="8">
                                                <div class="d-flex">
													<?php if($userlevel == 'master') { ?>
													<div class="p-2">	
                                                        <a href="createsched.php" class="d-sm-none d-lg-block btn btn-sm btn-primary btn-icon-split bg-gradient-primary">
                                                                <small>
                                                                    <span class="icon text-white-50">
                                                                    <i class="fas fa-calendar-plus"></i>
                                                                    </span>
                                                                    <span class="text">Create Schedule</span>
                                                                </small>
                                                        </a>  
													</div>		
													<?php }?>
													<div class="p-2">
                                                        <a href="changesched.php" class="d-sm-none d-lg-block btn btn-sm btn-primary btn-icon-split bg-gradient-primary">
                                                            <small>
                                                                <span class="icon text-white-50">
                                                                    <i class="fas fa-calendar-plus"></i>
                                                                </span>
                                                                <span class="text">Edit Schedule</span>
                                                            </small>
                                                        </a>  
													</div>	
                                                    <div class="p-2">
                                                        <a class="d-sm-none d-lg-block btn btn-info btn-sm bg-gradient-info btn-icon-split" href="dailypost.php" data-toggle="tooltip" 
                                                        data-placement="top" title="Click to Post Daily DTR Logs">
                                                            <small>
                                                                <span class="icon text-white-50">
                                                                    <i class="fa fa-calendar-check" aria-hidden="true"></i> 
                                                                </span>
                                                                <span class="text">Daily DTR</span>
                                                            </small>
                                                        </a>
													</div>	
												</div>
											</th>
										</tr>
										
									
                                        <tr class="bg-gray-200">																										
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Position</center></th>
											<th><center>Cut-off</center></th>
                                            <th><center>Type</center></th>
											<th><center></center></th>
										</tr>
                                    </thead>
                  <?php  
					if(isset($_GET["current"]) == "current")  
					{  
					?>	
				
					<?php
						if($_SESSION['empno'] != 3080 AND $_SESSION['empno'] != 1261 AND $_SESSION['empno'] != 1910 AND $_SESSION['empno'] != 3736 
						AND $_SESSION['empno'] != 3819 AND $_SESSION['empno'] != 5359 AND $_SESSION['empno'] != 4070 AND $_SESSION['empno'] != 3770 
						AND $_SESSION['empno'] != 4206 AND $_SESSION['empno'] != 3160 AND $_SESSION['empno'] != 1053 AND $_SESSION['empno'] != 2356 
                        AND $_SESSION['empno'] != 3156 AND $_SESSION['empno'] != 3612 AND $_SESSION['empno'] != 4001 AND $_SESSION['empno'] != 1533 
                        AND $_SESSION['empno'] != 5263 AND $_SESSION['empno'] != 5430 AND $_SESSION['empno'] !=4892 AND $_SESSION['empno'] != 3337 
                        AND $_SESSION['empno'] != 6436 AND $_SESSION['empno'] != 6209 AND $_SESSION['empno'] != 6244 AND $_SESSION['empno'] != 6245 
                        AND $_SESSION['empno'] != 6438 AND $_SESSION['empno'] != 5265 AND $_SESSION['empno'] != 1509
                        AND $_SESSION['empno'] != 4073 AND $_SESSION['empno'] != 1509 AND $_SESSION['empno'] != 2203 
                        AND $_SESSION['empno'] != 5612 AND $_SESSION['empno'] != 5356)
						{
						?>
                    <tbody>		

                        <?php										
                            if ($userlevel == 'master' OR $userlevel == 'admin') {
                            $sql3 = "SELECT * FROM user_info where userid = '$userid'";
                            }else{
                            $sql3 = "SELECT * FROM user_info where userid = '$userid'";
                            }
							
                            
                            //Engineering																		
                            if ($empno == 5585) {
                            $sql3 = "SELECT * FROM user_info where empno in (3070,3046,5586,2605,4899,1796,5588,5716,5790,5791,5842,5843,5955,5957,5958)
                            ";
                            }
                            
							//TSD
							if ($empno == 3183) {
                            $sql3 = "SELECT * FROM user_info where empno in (4294,3183,4388,166,6038,5973,44,167,3974)
                            ";
                            }
							if ($empno == 71) {
                            $sql3 = "SELECT * FROM user_info where empno != 167 AND empno != 3974 AND empno != 4294 AND empno != 4388 AND empno != 5158 AND 
									empno != 44 AND empno != 166 AND empno != 3183 AND userid = '$userid'
                            ";
                            }
							
							//IT
							if ($empno == 3075) {
                            $sql3 = "SELECT * FROM user_info where empno in (2234,2550,3075,4071,4139,5700,5701) AND userid = '$userid'
                            ";
                            }
							
							if ($empno == 3334) {
                            $sql3 = "SELECT * FROM user_info where empno in (3334,3707,3773,3774,2559) AND userid = '$userid'
                            ";
                            }

                            if ($empno == 4647 OR $empno == 5615) {
                                $sql3 = "SELECT * FROM user_info where empno != 4625 AND userid = '$userid'
                                ";
                                }
                        
                            $query3=$HRconnect->query($sql3);
                            while($row3=$query3->fetch_array()){
                            $empno12 = $row3['empno'];
                        
                            $name = $row3['name'];
                            $position = $row3['position'];
                            $userid = $row3['userid'];

                            if ($userlevel == 'master' OR $userlevel == 'admin') {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'";
                            }else{
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'";
                            }
							
                            
                            //Engineering																		
                            if ($empno12 == 5585) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
                            if ($empno12 == 5584) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
							
							//TSD
							if ($empno == 3183) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
							if ($empno == 71) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
							
							//IT
							if ($empno == 3075) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
							
							if ($empno == 3334) {
                            $sql = "SELECT * FROM sched_info where empno = '$empno12' AND status = 'Pending'
                            ";
                            }
                            
                                
                            $query=$HRconnect->query($sql);
                            while($row=$query->fetch_array()){
                            
                            $empno = $row['empno'];
                            $id = $row['id'];

                        if ($empno != "") {
                            ?>	
                                <tr>	
                                    <td><center><?php echo $empno; ?></center></td>
                                    <td><center><a href="pdf/print_schedule.php?empid=<?php $_SESSION['viewPrintSched']=true; echo $empno ?>&cutfrom=<?php echo $row['datefrom']; ?>&cutto=<?php echo $row['dateto']; ?>&userid=<?php echo $userid; ?>"><?php echo html_entity_decode(htmlentities($name)); ?></a></center></td>
                                    <td><center><?php echo $position; ?></center></td>
                                    <td><center><?php echo $row['datefrom']; ?> - <?php echo $row['dateto']; ?></center></td>		    
                                    <td><center><?php echo ($row['sched_type'] == "" || $row['sched_type'] == null)? "Regular Schedule": "Compressed Schedule"?></center></td>		                                           											
                                    <td><center><a href="../hrms/pdf/printedit.php?id=<?php echo $id; ?>" class="btn btn-warning btn-user btn-sm btn-block bg-gradient-warning" >Edit</a>	
                                </tr>
                        <?php
                            }
                            }
                            }
                            ?>  		
                        </tbody>
						<?php } ?>
					
					<?php 
					}
					?>

<!-- Backtracking tbody -->			

						<?php  
							if(isset($_GET["backtrack"]) == "backtrack")  
							{  
							?>	   						
									<tbody>		

									<?php
				
 
										$sql = "SELECT * FROM sched_info 
                                        JOIN user_info ON sched_info.empno = user_info.empno
                                        where user_info.mothercafe= '$userid' AND sched_info.status = 'saved' AND datefrom = '$backfrom' AND dateto = '$backto'
										";
										$query=$HRconnect->query($sql);
										while($row=$query->fetch_array())
										{
                                       $empno = $row['empno'];
                                       $id = $row['id'];
                                  

                                       $sql1 = "SELECT * FROM user_info 
                                       WHERE empno = '$empno'
                                        ";
                                       $query1=$HRconnect->query($sql1);
                                       $row1=$query1->fetch_array();
                                       $name = $row1['name'];
                                       $position = $row1['position'];
                                       $userid = $row1['userid'];

										?>	
											<tr>	
												<td><center><?php echo $empno; ?></center></td>
												<td><center><a target="_blank" href="pdf/print_schedule.php?empid=<?php $_SESSION['viewPrintSched']=true; echo $empno ?>&cutfrom=<?php echo $row['datefrom']; ?>&cutto=<?php echo $row['dateto']; ?>&userid=<?php echo $userid; ?>&backtrack=backtrack"><?php echo  utf8_encode($name); ?></a></center></td>
												<td><center><?php echo $position; ?></center></td>
												<td><center><?php echo $row['datefrom']; ?> - <?php echo $row['dateto']; ?></center></td>	                                               																						
												<td><center><?php echo $row['status']; ?></center></td>
                                                <td><center></center></td>
											</tr>
                                    <?php
                                        }
                                        ?>  		
									</tbody>
							<?php 
								}
								?>
								
								</table>
                              </form>
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
						 <span>Copyright © Mary Grace Foods Inc. 2019.</span>
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

	<script>
		$(document).ready(function() {
		$('#example').dataTable( {
        stateSave: true
		} );
		} );
	</script>
</body>

</html>


<?php } ?>