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
$empno = $_SESSION['empno'];
$mothercafe = $row['mothercafe'];


$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];


$userid = $_SESSION['useridd'];

if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];


header('Location:employeelist2.php?inactive=inactive');


}



if($userlevel != 'staff') {
	

if(isset($_GET['add'])){ 

$Employee = $_GET["Employee"];
$Fullname = $_GET["Fullname"];
$Position = $_GET["Position"];
$Hired = $_GET["Hired"];
$branch = $_GET["branch"];
$company = $_GET["company"];
$department = $_GET["department"];

$sqlupdate1=" SELECT * FROM user_info  
            WHERE branch = '$branch'";

    $query1=$HRconnect->query($sqlupdate1);
    $row1=$query1->fetch_array();
    $userid1 = $row1['userid']; 


$sqlupdate=" SELECT COUNT(*) as numbers FROM user_info  
            WHERE empno = '$Employee'";
$query=$HRconnect->query($sqlupdate);
$row=$query->fetch_array();
$numbers = $row['numbers']; 

if($numbers > 0){


echo "<script type='text/javascript'>alert('Employee Number Already Exist! Please try again');
        window.location.href='employeelist.php?branch=$userid';
        </script>";

header("location:employeelist.php?branch=$userid");

}else{


$sql3 = "INSERT INTO user_info (userid,mothercafe, empno, name, branch,department,position,datehired,company) 
         VALUES('$userid1','$userid1',  '$Employee', '$Fullname', '$branch','$department','$Position','$Hired','$company')";

$HRconnect->query($sql3);

echo "<script type='text/javascript'>alert('Successfully Add Employee');
        window.location.href='employeelist.php?branch=$userid';
        </script>";
}
}


if(isset($_POST['update'])){ 

$Employee = $_POST["Employee"];
$Fullname = $_POST["Fullname"];
$Position = $_POST["Position"];
$Hired = $_POST["Hired"];
$branch = $_POST["branch"];
$company = $_POST["company"];
$department = $_POST["department"];
$pwd = $_POST["pwd"];
$status = $_POST["status"];


if ($pwd == 'YES'){

    $picture = 'PWD';

}else{
    
    $picture = '';
}

$sqlupdate1=" SELECT * FROM user_info WHERE branch = '$branch'";
$query1=$HRconnect->query($sqlupdate1);
$row1=$query1->fetch_array();
$userid1 = $row1['userid']; 


$sql3 = "UPDATE user_info SET 
         userid ='$userid1',
         name = '$Fullname' , 
         branch = '$branch' ,
         department = '$department' ,
         position = '$Position',
         datehired ='$Hired',
         company = '$company',
          status = '$status',
           picture = '$picture',
         approval = 'approve'
          WHERE empno = '$Employee'";

$HRconnect->query($sql3);

   $date_time = date("Y-m-d H:i");
   $empno = $_SESSION['empno'];
   $inserted = "Edited" . "-". $Fullname . "(" . $Employee . ")";
   $action = "Edit Employee Details";

   $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
                VALUES('$empno', '$action', '$inserted','$date_time')";

   $HRconnect->query($sql2);

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
		
		#center{
		text-align-last:center;
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

                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Employee List</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>						
					</div>

				<!-- Inactive -->        
				<?php  
					if(isset($_GET["inactive"]) == "inactive")   
					{  
					?>	
					<!-- DataTales Example -->
						
					<ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="effect-shine nav-link" href="employeelist.php?active=active"><b>Active</b></a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="effect-shine nav-link active" href="employeelist2.php?inactive=inactive"><b>Inactive</b></a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="effect-shine nav-link" href="employeelist3.php?resigned=resigned"><b>Resigned</b></a>
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
											<th><center>Position</center></th>
											<th><center>Branch</center></th>	
											<th><center>Leave Credit</center></th>											
                                            <th><center>PIN CODE</center></th>
											<th><center>P.Print</center></th>
											<th><center>S.Print</center></th>
										</tr>
                                    </thead>
									
									<?php if (@$_SESSION['useridd'] == null) { ?>
										<div class="alert alert-danger d-none d-sm-block text-center" role="alert">
											Please Choose Branch/Department to view data.</a>
										</div>
									<?php }?>
									
									<tbody>	
									
									<?php
										if (@$_SESSION['useridd'] != null) {
										
										if ($userlevel == 'master' OR $userlevel == 'admin') {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND mothercafe = ".$_SESSION['useridd']."
										";
										}else{
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND mothercafe = ".$_SESSION['useridd']."
										";
										}
										
										//Engineering									
										if ($empno == 4292) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno in (5102)
										";
										}										
										if ($empno == 5585) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno in (3070,3046,5586,2605,4899,1796,5588,5716,5790,5791,5842,5843,5955,5957,5958)
										";
										}
										// TSD
										if ($empno == 3183) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno in (4294,3183,4388,166,6038,5973,44,167,3974)
										";
										}
										if ($empno == 71) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno != 167 AND empno != 3974 AND empno != 4294 AND empno != 4388 AND empno != 5158 AND 
										empno != 44 AND empno != 166 AND empno != 3183 AND mothercafe = ".$_SESSION['useridd']."
										";
										}
										
										//IT
										if ($empno == 3075) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno in (2234,2550,2559,3075,4071,5700,5701)
										";
										}
										
										if ($empno == 3334) {
										$sql = "SELECT * FROM user_info where status = 'inactive'
                                        AND empno in (3334,3707,3773,3774)
										";
										}
										
										$query=$HRconnect->query($sql);
										while($row=$query->fetch_array())										
										{
										$name = $row['name'];	
										?>	
											<tr>     
                                                <td><center><?php echo $row['empno']; ?></center></td>
                                                <td><center><a  href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['empno']; ?>"><?php echo html_entity_decode(htmlentities($name)); ?></a></center></td>
                                                <td><center><?php echo $row['position']; ?></center></td>   
                                                <?php if($row['branch'] != '' AND $row['branch'] != 'HO') { ?>
                                                <td><center><?php echo $row['branch']; ?></center></td>
                                            <?php }elseif($row['branch'] == 'HO') { ?>
                                                <td><center><?php echo $row['department']; ?></center></td>
                                            <?php } ?>
                                                <td><center><?php echo $row['vl']; ?></center></td>                                            
                                            <?php if($row['picture'] == "" )  { ?>
                                                <td><center>No</center></td>
                                            <?php }else { ?>
                                                <td><center>Yes</center></td>
                                            <?php } ?>										
											<?php if($row['template'] == null )  { ?>
												<td><center>Not Registered</center></td>
                                            <?php }else { ?>
                                                <td><center>Registered</center></td>
                                            <?php } ?>
											<?php if($row['template2'] == null )  { ?>
												<td><center>Not Registered</center></td>
                                            <?php }else { ?>
                                                <td><center>Registered</center></td>
                                            <?php } ?>
                                            
                                            </tr>       
                                            <!-- View Approved Modal-->
                                            <div class="modal fade" id="exampleModal<?php echo $row['empno']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                      
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel"> <i class="fa fa-tasks" aria-hidden="true"></i> Approve Employee <b class="d-none"><?php echo $row['empno']; ?></b> </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
    <form method="POST"  enctype="multipart/form-data">
 <div class="modal-body">
            
            <div class="text-center">
                <h1 class="h5 text-gray-900 mb-sm-0">Employee Details
            </div>
            
            <div class="form-group mb-1">
                <label for="recipient-name" class="col-form-label">Fullname:</label>
                <input type="text" class="form-control text-center text-uppercase " id="Fullname" name="Fullname" value="<?php echo $row['name']; ?>" required />

            </div>
          
            <div class="form-group">
                <div class= "form-row">
                    <div class="col-6">
                        <label for="recipient-name" class="col-form-label">Employee No.:</label>
                        <input type="number" min="1" class="form-control text-center text-uppercase" id="Employee" name="Employee" readonly value="<?php echo $row['empno']; ?>" required />
                    </div>
                    
                    <div class="col-6">
                        <label for="recipient-name" class="col-form-label">Date Hired:</label>
                             <input type="text" class="form-control text-center text-uppercase" id="Hired" name="Hired" readonly value="<?php echo $row['datehired']; ?>" required />
                        
                    </div>
                    
                    <label for="recipient-name" class="col-form-label">Position:</label>
                    <input type="text" class="form-control text-center text-uppercase" id="Position" name="Position"  value="<?php echo $row['position']; ?>" required />
                    
                    <label for="recipient-name" class="col-form-label">Department:</label>
                        <select id="center" class="form-control"  name="department">
                            <option  selected><?php echo $row['department']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT department FROM user_info";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['department']; ?></option>
                            <?php } ?>
                        </select>
                    
                    <label for="recipient-name" class="col-form-label">Branch</label>
                        <select id="center" class="form-control text-uppercase" name="branch" required>
                            <option  selected><?php echo $row['branch']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT branch FROM user_info";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['branch']; ?></option>
                            <?php } ?>
                        </select>
                    
                   <label for="recipient-name" class="col-form-label">Company</label>
                        <select id="center" class="form-control text-uppercase" name="company" required>
                            <option  selected><?php echo $row['company']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT company FROM user_info";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['company']; ?></option>
                            <?php } ?>
                        </select>
                    
                    <div class="col-6"> 
                    <label for="recipient-name" class="col-form-label">PIN CODE</label>
                        <select id="center" class="form-control text-uppercase" name="pwd" >
                            <option selected hidden><?php  
                            if($row['picture'] == ''){
                                echo "NO";
                            }else{
                                   echo "YES";
                            }
                        ?></option>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                            
                        </select>
                    </div>
                    
                    <div class="col-6">
                    <label for="recipient-name" class="col-form-label">Status</label>
                    <select id="center" class="form-control text-uppercase" name="status" >
                            <option selected hidden><?php echo $row['status']; ?></option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Resigned">Resigned</option>                            
                        </select>
                    </div>      
                </div>
            </div>      
            
          </div>   


<div class="modal-footer">
    <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>

    <button type="submit" name="update" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to update this employee?');">Update</button>

                                                            </div>
                                                             </form>
                                                        </div>
                                                    </div>
                                                
                                            </div>                                          
                                    <?php
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
	
	<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
	
	
	<script>
		$(document).ready(function() {
	  var table = $('#example').DataTable({
		stateSave: true,
		dom: 'Bfrtip',
		buttons: [
		{
		  extend: 'excel',
		  text: 'Excel',
		  className: 'exportExcel',
		  filename: 'Employee List',
		  exportOptions: {
			modifier: {
			  page: 'all'
			}
		  }
		}, 
		
		{
		 
		}]
	  });

	});
	</script>	
	
</body>

</html>

<?php } ?>