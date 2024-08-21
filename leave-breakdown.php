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
$userrid = $row['userid'];
$mothercafe = $row['mothercafe'];
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];

if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];

Header('Location: '.$_SERVER['PHP_SELF']);

}
 $userid = $_SESSION['useridd'];

$sqll="SELECT * FROM sched_date 
              WHERE userid = '$userid'";
      $queryl=$HRconnect->query($sqll);
      $rowl=$queryl->fetch_array();
     @$from = $rowl['biofrom'];
     @$to =  $rowl['bioto'];

   @$datefrom2 = date("m-d-Y", strtotime($from));
   @$dateto2 = date("m-d-Y", strtotime($to));





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
                <div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-1">
							<h4 class="mb-0">Cut-off Details - Leave</h4>
							<div>
								<a href="details-leave.php"><span class="text-secondary fw-500">List</span></a>
								/ 
								<span class="text-primary fw-500 font-strong">Breakdown</span>
							</div>
						</div>           
					</div>

					<div class="row">
						<div class="col-xl-12 col-lg-12 mb-4">
							<div class="card shadow">
								<div class="card-header">Alabang Town Center</div>
								<!-- Card Body -->
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-sm table-hover text-center" id="example">												
											<thead>
												<tr>
													<th>ID</th>
													<th>Fullname</th>												
													<th>Total Hours</th>
													<th>Action</th>
												</tr>
											</thead>
									
											<tbody>                             
												<tr>
													<td><center>9999</center></td>
													<td><center>Juan Dela Cruz</center></td>
													<td><center><span class="badge badge-primary">300</span></center></td>
													<td>
														<center><button class="btn border-0 btn-outline-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" title="View Breakdown"><i class="fas fa-eye"></i></button></center>                                          
													</td> 	
												</tr>																								
											</tbody>
										</table>
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
	
	<!-- Breakdown Overtime -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Juan Dela Cruz</h5>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">						
						<table class="table table-sm table-hover text-center" id="example">												
							<thead>
								<tr>
									<th>Date</th>
									<th>Reason</th>									                                      
									<th>Approver</th>
									<th>Date Approve</th>
									<th>OT Hours</th>	
								</tr>
							</thead>
									
							<tbody>                             
								<tr>
									<td><center>2022-08-28</center></td>
									<td><center>NAGSLICE NG PUDDING</center></td>									
									<td><center>DOMINGUEZ, LOVELY MANILYN MACARANIAG</center></td>
									<td><center>2022-08-28</center></td>
									<td><center><span class="badge badge-primary">300</span></center></td>	
								</tr>																								
							</tbody>
						</table>
					</div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
	
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

    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <?php if(@$_GET["a"] == 1)
    {   
    ?>
    <script>
        window.onload = function(){
            $("#myModal").modal('show');
        };
    </script>
    <?php 
    }
    ?>
    
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
