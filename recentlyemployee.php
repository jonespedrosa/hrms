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
$useradmin = $row['mothercafe'];


$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];



if($userlevel != 'master' AND $userlevel != 'ac' AND $userlevel != 'admin'){

@$_SESSION['useridd'] = $row['userid'];

}else{

@$_SESSION['useridd'] = $_GET['branch'];

}   

$userid = $_SESSION['useridd'];



if($userlevel != 'staff') {
	

if(isset($_GET['add'])){ 

$Employee = $_GET["Employee"];
$Fullname = $_GET["Fullname"];
$Position = $_GET["Position"];
$Hired = $_GET["Hired"];
$branch = $_GET["branch"];
$company = $_GET["company"];
$department = $_GET["department"];
$new_area_type = $_GET["area_type"];

$datetime = date("Y-m-d H:i");

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

header("location:recentlyemployee.php?ho=ho");

}else{

$sql3 = "INSERT INTO user_info (userid,mothercafe, empno, name, branch,department,position,datehired,company,timedate,area_type) 
         VALUES('$userid1','$userid1',  '$Employee', '$Fullname', '$branch','$department','$Position','$Hired','$company','$datetime','$new_area_type')";

$HRconnect->query($sql3);

echo "<script type='text/javascript'>
        window.location.href='recentlyemployee.php?ho=ho&successfully';   
        </script>";
}
}

if(isset($_POST['approve1'])){ 

$Employee = $_POST["Employee"];
$Fullname = $_POST["Fullname"];
$Position = $_POST["Position"];
$Hired = $_POST["Hired"];
$branch = $_POST["branch"];
$company = $_POST["company"];
$department = $_POST["department"];

$sqlupdate1=" SELECT * FROM user_info  
            WHERE branch = '$branch'";
$query1=$HRconnect->query($sqlupdate1);
$row1=$query1->fetch_array();
$userid1 = $row1['userid']; 

$sql3 = "UPDATE user_info SET 
         userid ='$userid1',
        mothercafe = '$userid1',   
         name = '$Fullname' , 
         branch = '$branch' ,
         department = '$department' ,
         position = '$Position',
         datehired ='$Hired',
         company = '$company',
         approval = 'pending2'
          WHERE empno = '$Employee'";

$HRconnect->query($sql3);

	$date_time = date("Y-m-d H:i");
	$empno = $_SESSION['empno'];
	$inserted = "Approved" . "-". $Fullname . "(" . $Employee . ")";
	$action = "Approved New Employee";

	$sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
                VALUES('$empno', '$action', '$inserted','$date_time')";

	$HRconnect->query($sql2);
	
	header("location:recentlyemployee.php?ho=ho&a=1"); 
    }


if(isset($_POST['approve2'])){ 

$Employee = $_POST["Employee"];
$Fullname = $_POST["Fullname"];
$Position = $_POST["Position"];
$Hired = $_POST["Hired"];
$branch = $_POST["branch"];
$company = $_POST["company"];
$department = $_POST["department"];

$sqlupdate1=" SELECT * FROM user_info  
            WHERE branch = '$branch'";
$query1=$HRconnect->query($sqlupdate1);
$row1=$query1->fetch_array();
$userid1 = $row1['userid']; 


$sql3 = "UPDATE user_info SET 
         userid ='$userid1',
        mothercafe = '$userid1',   
         name = '$Fullname' , 
         branch = '$branch' ,
         department = '$department' ,
         position = '$Position',
         datehired ='$Hired',
         company = '$company',
         status = 'active',
         approval = 'approve'
          WHERE empno = '$Employee'";

$HRconnect->query($sql3);

   $date_time = date("Y-m-d H:i");
   $empno = $_SESSION['empno'];
   $inserted = "Approved" . "-". $Fullname . "(" . $Employee . ")";
   $action = "Approved New Employee";

   $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
                VALUES('$empno', '$action', '$inserted','$date_time')";

   $HRconnect->query($sql2);
	header("location:recentlyemployee.php?ho=ho&a=2"); 
    }

?>

<!-- approve HO newly hired -->	 
	<?php 
        if(isset($_POST['but_update'])){

            if(isset($_POST['update'])){
                foreach($_POST['update'] as $updateid){

						$timedate = date("Y-m-d H:i");
				
                        $updateUser = "UPDATE user_info SET
							status = 'active',
							approval = 'approve',
							timedate = '$timedate'
							WHERE id = ".$updateid;
                        mysqli_query($HRconnect,$updateUser);

                  header("location:recentlyemployee.php?ho=ho&m=1");  
                }
            }
            
        }
    ?>

<!-- approve Cafe newly hired -->	 
	<?php 
        if(isset($_POST['but_update1'])){

            if(isset($_POST['update1'])){
                foreach($_POST['update1'] as $updateid){

						$timedate = date("Y-m-d H:i");
				
                        $updateUser = "UPDATE user_info SET
							status = 'active',
							approval = 'approve',
							timedate = '$timedate'
							WHERE id = ".$updateid;
                        mysqli_query($HRconnect,$updateUser);

                  header("location:recentlyemployee.php?cafe=cafe&m=2");  
                }
            }
            
        }
    ?>

<!-- approve Kiosk newly hired -->	 
	<?php 
        if(isset($_POST['but_update2'])){

            if(isset($_POST['update2'])){
                foreach($_POST['update2'] as $updateid){

						$timedate = date("Y-m-d H:i");
				
                        $updateUser = "UPDATE user_info SET
							status = 'active',
							approval = 'approve',
							timedate = '$timedate'
							WHERE id = ".$updateid;
                        mysqli_query($HRconnect,$updateUser);

                  header("location:recentlyemployee.php?kiosk=kiosk&m=3");  
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


	<style type="text/css">
	
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
	</style>

	<style> 
        input.largerCheckbox { 
            width: 18px; 
            height: 18px; 
        }
		
		input[type=checkbox] + label {
		  color: #ccc;
		  font-style: italic;
		} 
		input[type=checkbox]:checked + label {
		  color: #0000FF;
		  font-style: normal;
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
							<h4 class="mb-0">Newly Hired Employee</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>
						
						<?php 
                        if($userlevel == 'master'){
                        ?> 
							
						<a href="#" class="d-sm-inline-block btn btn-sm btn-primary btn-icon-split bg-gradient-primary" data-toggle="modal" data-target="#exampleModal">
                            <span class="icon text-white-50">
                                <i class="fas fa-user-plus"></i>
                            </span>
                            <span class="text">Add Employee</span>
                        </a>	
                    <?php 	
						}
						?>
					</div>
					
            <!-- Head office -->
			
				<?php  
					if(isset($_GET["ho"]) == "ho") {  
                        if (isset($_GET["successfully"])) {
                            echo '<script>
                            $(function() {
                          $(".thanks").delay(2500).fadeOut();
                          
                        });
                        </script>
                        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                        <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                            <div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
                                <div class="toast-header bg-success">
                                  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Newly Hired</h5>
                                 <small class="text-light">just now</small>
                                </div>
                                <div class="toast-body">
                                  You have <b class="text-success">Successfully Add Employee. </b> Thank you!
                                </div>
                            </div>
                        </div>';
                        }

					?>
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="recentlyemployee.php?ho=ho"><b>Head Office</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?cafe=cafe"><b>Cafe</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?kiosk=kiosk"><b>Kiosk</b></a>
							</li>
						</ul>
					
					<div class="card shadow mb-4">                       
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
							<a href="pdf/generateapproved.php">Recently Approved</a>
                        </div>
						
						<div class="card-body">
                        <form method='post' action=''>    
							<div class="table-responsive">                            
                                <table class="table table-sm table-bordered table-hover text-uppercase tbl-sm" id="dataTable" width="100%" cellspacing="0">
                                    <?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
										<div class="d-sm-flex align-items-center justify-content-between mb-1">
											<center><input type='checkbox' id='checkAll'> <label for="checkAll"> SELECT ALL</label></center>													
											<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE' name='but_update' onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');"></center>
										</div>	
									<hr />
								<?php } ?>	
									<thead>
                                        <tr class="bg-gray-200">																																																
										<?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
											<th><center></center></th>
										<?php } ?>	
											<th><center>Date Added</center></th>
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Department</center></th>	
											<th><center>Position</center></th>
											<th><center>Status</center></th>
											<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
											<th><center>Edit</center></th>
											<?php } ?>	
										</tr>
                                    </thead>
									
									<tbody>	
									
									<?php
										                               
                                        $from = date("Y-m-01 00:00" ,strtotime("-1 month", strtotime(date("Y/m/d"))));
                                        $to = date("Y-m-d H:i");
										
										if($userlevel == 'master'){ 
										$sql = "SELECT * FROM user_info where (approval in ('pending','pending2') AND timedate between '$from' AND '$to' ) AND mothercafe != 4 AND branch != '' AND department != ''";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072){ 
										$sql = "SELECT * FROM user_info where (approval = 'pending' AND timedate between '$from' AND '$to' ) AND mothercafe != 4 AND branch != '' AND department != ''";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2 ){ 
										$sql = "SELECT * FROM user_info where (approval = 'pending2' AND timedate between '$from' AND '$to' ) AND mothercafe != 4 AND branch != '' AND department != ''";
										}
										
										$query=$HRconnect->query($sql);
										while($row=$query->fetch_array())										
										{
										$name = $row['name'];
										$id = $row['id'];						
										?>		
											<tr>												
											<?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>
												<td><center><input class="largerCheckbox" type='checkbox' name='update[]' value='<?= $id ?>'></center></td>
											<?php } ?>	
												<td><center><?php echo $row['timedate']; ?></center></td>
												<td><center><?php echo $row['empno']; ?></center></td>
												<td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
												<td><center><?php echo $row['branch']; ?></center></td>
												<td><center><?php echo $row['position']; ?></center></td>
												<td><center><?php  
                                                if($row['approval'] == 'pending'){
                                                    echo "<i class='m-0 font-weight-bold text-danger'>FOR HR APPROVAL</i>";
                                                }elseif($row['approval'] == 'pending2'){
                                                    echo "<i class='m-0 font-weight-bold text-warning'>FOR ML1 APPROVAL</i>";                                             
                                                }elseif($row['approval'] == 'approve'){
                                                    echo "<i class='m-0 font-weight-bold text-success'>Approved</i>";
                                                }

                                                ?></center></td>
												<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
												<td><center><a  href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['empno']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></i></center></td>									
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
                             <input type="date" class="form-control text-center text-uppercase" id="Hired" name="Hired" value="<?php echo $row['datehired']; ?>" required />
                        
                    </div>
                    
                    <label for="recipient-name" class="col-form-label">Position:</label>
                    <input type="text" class="form-control text-center text-uppercase" id="Position" name="Position"  value="<?php echo $row['position']; ?>" required />
                    
                    <label for="recipient-name" class="col-form-label">Department:</label>
                        <select id="center" class="form-control"  name="department">
                            <option  selected><?php echo $row['department']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT department FROM user_info ORDER BY department";
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
                                $sql1 = "SELECT DISTINCT branch FROM user_info ORDER BY branch";
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
                                $sql1 = "SELECT DISTINCT company FROM user_info ORDER BY company";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['company']; ?></option>
                            <?php } ?>
                        </select>        
                        
                
                        
                </div>
            </div>      
            
          </div>   


<div class="modal-footer">
    <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
  <?php if($row['approval'] == 'approve'){ ?>
    <button type="submit" name="update" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Update</button>
  <?php }elseif($row['approval'] != 'approve'){

if($useradmin != 137 AND $row['approval'] == 'pending'){
   
   ?>
     <button type="submit" name="approve1" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

<?php }elseif(($useradmin == 137 OR $userlevel == 'master') AND $row['approval'] == 'pending2'){ ?>


     <button type="submit" name="approve2" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

 <?php } } ?>

                                                            </div>
                                                              </form>
                                                        </div>
                                                    </div>
                                                
                                            </div>  								
									<?php
										}
										?>	
									</tbody>																																
								</table>   
                        </form>    
							</div>
                        </div>
                    </div>
				<?php 
					}
					?>
			
			
			<!-- Cafe -->
			
				<?php  
					if(isset($_GET["cafe"]) == "cafe")   
					{  
					?>
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?ho=ho"><b>Head Office</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="recentlyemployee.php?cafe=cafe"><b>Cafe</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?kiosk=kiosk"><b>Kiosk</b></a>
							</li>
						</ul>
					
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
							<a href="pdf/generateapproved.php">Recently Approved</a>
                        </div>
						
                        <div class="card-body">
                        <form method='post' action=''>    
							<div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-uppercase tbl-sm" id="dataTable" width="100%" cellspacing="0">
                                    <?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
										<div class="d-sm-flex align-items-center justify-content-between mb-1">
											<center><input type='checkbox' id='checkAll1'> <label for="checkAll1"> SELECT ALL</label></center>													
											<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE' name='but_update1' onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');"></center>
										</div>	
									<hr />	
									<?php } ?>
									<thead>
                                        <tr class="bg-gray-200">
										<?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
											<th><center></center></th>
										<?php } ?>	
											<th><center>Date Added</center></th>
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Department</center></th>	
											<th><center>Position</center></th>
											<th><center>Status</center></th>
											<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
											<th><center>Edit</center></th>
											<?php } ?>
										</tr>
                                    </thead>
									
									<tbody>	
							 <?php

                                
                                        $from = date("Y-m-01" ,strtotime("-1 month", strtotime(date("Y/m/d"))));
                                        $to = date("Y-m-d");
                                        $variable = null;
											
										if($userlevel == 'master'){ 
										$sql = "SELECT * FROM user_info where approval in ('pending','pending2')  AND (department IS NULL OR department = '')";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583  OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072){ 
										$sql = "SELECT * FROM user_info where approval = 'pending'  AND (department IS NULL OR department = '')";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2 ){ 
										$sql = "SELECT * FROM user_info where approval = 'pending2'  AND (department IS NULL OR department = '')";
										}
										
                                        $query=$HRconnect->query($sql);
                                        while($row=$query->fetch_array())                                       
                                        {
                                        $name = $row['name'];
                                        $id = $row['id'];                        
                                        ?>      
                                            <tr>
											<?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
												<td><center><input class="largerCheckbox" type='checkbox' name='update1[]' value='<?= $id ?>'></center></td>
                                            <?php } ?>   
												<td><center><?php echo $row['timedate']; ?></center></td>
                                                <td><center><?php echo $row['empno']; ?></center></td>
                                                <td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
                                                <td><center><?php echo $row['branch']; ?></center></td>
                                                <td><center><?php echo $row['position']; ?></center></td>
                                                <td><center><?php  
                                                if($row['approval'] == 'pending'){
                                                    echo "<i class='m-0 font-weight-bold text-danger'>FOR HR APPROVAL</i>";
                                                }elseif($row['approval'] == 'pending2'){
                                                    echo "<i class='m-0 font-weight-bold text-warning'>FOR ML1 APPROVAL</i>";                                             
                                                }elseif($row['approval'] == 'approve'){
                                                    echo "<i class='m-0 font-weight-bold text-success'>Approved</i>";
                                                }

                                                ?></center></td>
												<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
												<td><center><a  href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['empno']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></i></center></td>                                   
												<?php } ?> 
											</tr>       
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
                              <input type="date" class="form-control text-center text-uppercase" id="Hired" name="Hired" value="<?php echo $row['datehired']; ?>" required />
                        
                    </div>
                    
                    <label for="recipient-name" class="col-form-label">Position:</label>
                    <input type="text" class="form-control text-center text-uppercase" id="Position" name="Position"  value="<?php echo $row['position']; ?>" required />
                    
                    <label for="recipient-name" class="col-form-label">Department:</label>
                        <select id="center" class="form-control"  name="department">
                            <option  selected><?php echo $row['department']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT department FROM user_info ORDER BY department";
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
                                $sql1 = "SELECT DISTINCT branch FROM user_info ORDER BY branch";
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
                                $sql1 = "SELECT DISTINCT company FROM user_info ORDER BY company";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['company']; ?></option>
                            <?php } ?>
                        </select>
                        
                </div>
            </div>      
            
          </div>   


<div class="modal-footer">
    <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
  <?php if($row['approval'] == 'approve'){ ?>
    <button type="submit" name="update" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Update</button>
  <?php }elseif($row['approval'] != 'approve'){

if($useradmin != 137 AND $row['approval'] == 'pending'){
   
   ?>
     <button type="submit" name="approve1" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

<?php }elseif(($useradmin == 137 OR $userlevel == 'master') AND $row['approval'] == 'pending2'){ ?>

     <button type="submit" name="approve2" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

 <?php } } ?>

                                                            </div>
                                                              </form>
                                                        </div>
                                                    </div>
                                                
                                            </div>                                  
                                    <?php
                                        }
                                        ?>  
                                    </tbody>                                                                                                                                
                                </table>
                        </form>          
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                    ?>

				<!-- Cafe -->
			
				<?php  
					if(isset($_GET["kiosk"]) == "kiosk")   
					{  
					?>
					<!-- DataTales Example -->
						<ul class="nav nav-tabs">
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?ho=ho"><b>Head Office</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link" href="recentlyemployee.php?cafe=cafe"><b>Cafe</b></a>
							</li>
							
							<li class="nav-item">
								<a class="effect-shine nav-link active" href="recentlyemployee.php?kiosk=kiosk"><b>Kiosk</b></a>
							</li>
						</ul>
					
					<div class="card shadow mb-4">                       
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
							<a href="pdf/generateapproved.php">Recently Approved</a>
                        </div>
						
						<div class="card-body">
                        <form method='post' action=''>    
							<div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover text-uppercase tbl-sm" id="dataTable" width="100%" cellspacing="0">
                                    <?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>									
										<div class="d-sm-flex align-items-center justify-content-between mb-1">
											<center><input type='checkbox' id='checkAll2'> <label for="checkAll2"> SELECT ALL</label></center>													
											<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE' name='but_update2' onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');"></center>
										</div>	
									<hr />
									<?php } ?>
									<thead>
                                        <tr class="bg-gray-200">																																																
										<?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>	
											<th><center></center></th>
										<?php } ?>	
											<th><center>Date Added</center></th>
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Department</center></th>	
											<th><center>Position</center></th>
											<th><center>Status</center></th>
											<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
											<th><center>Edit</center></th>
											<?php } ?>
										</tr>
                                    </thead>
									
									<tbody>	
								     <?php

                                
                                        $from = date("Y-m-01" ,strtotime("-1 month", strtotime(date("Y/m/d"))));
                                        $to = date("Y-m-d");
										
										if($userlevel == 'master'){ 
										$sql = "SELECT * FROM user_info where approval in ('pending','pending2') AND mothercafe = 4 ";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072){ 
										$sql = "SELECT * FROM user_info where approval = 'pending' AND mothercafe = 4 ";
										}if($userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2 ){ 
										$sql = "SELECT * FROM user_info where approval = 'pending2' AND mothercafe = 4 ";
										}
										
										
                                        $query=$HRconnect->query($sql);
                                        while($row=$query->fetch_array())                                       
                                        {
                                        $name = $row['name'];
                                        $id = $row['id'];                        
                                        ?>      
                                            <tr>                                                
                                            <?php if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2){ ?>    
												<td><center><input class="largerCheckbox" type='checkbox' name='update2[]' value='<?= $id ?>'></center></td>
											<?php } ?>	
												<td><center><?php echo $row['timedate']; ?></center></td>
                                                <td><center><?php echo $row['empno']; ?></center></td>
                                                <td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
                                                <td><center><?php echo $row['branch']; ?></center></td>
                                                <td><center><?php echo $row['position']; ?></center></td>
                                                <td><center><?php  
                                                if($row['approval'] == 'pending'){
                                                    echo "<i class='m-0 font-weight-bold text-danger'>FOR HR APPROVAL</i>";
                                                }elseif($row['approval'] == 'pending2'){
                                                    echo "<i class='m-0 font-weight-bold text-warning'>FOR ML1 APPROVAL</i>";                                             
                                                }elseif($row['approval'] == 'approve'){
                                                    echo "<i class='m-0 font-weight-bold text-success'>Approved</i>";
                                                }

                                                ?></center></td>
												<?php if($_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 5583 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072){ ?>
												<td><center><a  href="#" data-toggle="modal" data-target="#exampleModal<?php echo $row['empno']; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a></i></center></td>                                   
												<?php } ?>
											</tr>       
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
                           <input type="date" class="form-control text-center text-uppercase" id="Hired" name="Hired" value="<?php echo $row['datehired']; ?>" required />
                        
                    </div>
                    
                    <label for="recipient-name" class="col-form-label">Position:</label>
                    <input type="text" class="form-control text-center text-uppercase" id="Position" name="Position"  value="<?php echo $row['position']; ?>" required />
                    
                    <label for="recipient-name" class="col-form-label">Department:</label>
                        <select id="center" class="form-control"  name="department">
                            <option  selected><?php echo $row['department']; ?></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT department FROM user_info ORDER BY department";
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
                                $sql1 = "SELECT DISTINCT branch FROM user_info ORDER BY branch";
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
                                $sql1 = "SELECT DISTINCT company FROM user_info ORDER BY company";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1['company']; ?></option>
                            <?php } ?>
                        </select>
                        
                </div>
            </div>      
            
          </div>   


<div class="modal-footer">
    <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
  <?php if($row['approval'] == 'approve'){ ?>
    <button type="submit" name="update" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Update</button>
  <?php }elseif($row['approval'] != 'approve'){

if($useradmin != 137 AND $row['approval'] == 'pending'){
   
   ?>
     <button type="submit" name="approve1" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

<?php }elseif(($useradmin == 137 OR $userlevel == 'master') AND $row['approval'] == 'pending2'){ ?>

     <button type="submit" name="approve2" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to approve this employee?');">Approve</button>

 <?php } } ?>

                                                            </div>
                                                              </form>
                                                        </div>
                                                    </div>
                                                
                                            </div>                                  
                                    <?php
                                        }
                                        ?>  
                                    </tbody>                                                                                                                                
                                </table>
						</form>                                  
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

			
			<?php if(@$_GET['m'] == 1){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				  
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
						  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> H.O Newly hired</h5>
						 <small class="text-light">just now</small>
						</div>
						<div class="toast-body">
						  You have <b class="text-success">Successfully Approve</b> H.O Newly hired employee. Thank you!
						</div>
					</div>
				</div>
								   
			<?php } ?>
			
			<?php if(@$_GET['m'] == 2){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				  
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
						  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Cafe Newly Hired</h5>
						 <small class="text-light">just now</small>
						</div>
						<div class="toast-body">
						  You have <b class="text-success">Successfully Approve</b> Cafe Newly hired employee. Thank you!
						</div>
					</div>
				</div>
								   
			<?php } ?>
			
			<?php if(@$_GET['m'] == 3){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				  
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
						  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Kiosk Newly Hired</h5>
						 <small class="text-light">just now</small>
						</div>
						<div class="toast-body">
						  You have <b class="text-success">Successfully Approve</b> Kiosk Newly hired employee. Thank you!
						</div>
					</div>
				</div>
								   
			<?php } ?>
			
			<?php if(@$_GET['a'] == 1){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				  
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
						  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Newly Hired</h5>
						 <small class="text-light">just now</small>
						</div>
						<div class="toast-body">
						  You have <b class="text-success">Partially Approve</b> Newly hired employee. Thank you!
						</div>
					</div>
				</div>
								   
			<?php } ?>
			
			<?php if(@$_GET['a'] == 2){ ?>              
				<script>
					$(function() {
				  $(".thanks").delay(2500).fadeOut();
				  
				});
				</script>

				<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
						<div class="toast-header bg-success">
						  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Newly Hired</h5>
						 <small class="text-light">just now</small>
						</div>
						<div class="toast-body">
						  You have <b class="text-success">Successfully Approve</b> Newly hired employee. Thank you!
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

    <!-- Add Employee Modal-->
    <form method="GET" class="user" enctype="multipart/form-data">

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-user-plus fa-fw"></i><b> Add Employee</b></h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		  </div>
		  
		   
		  <div class="modal-body">
			
			<div class="text-center">
				<h1 class="h5 text-gray-900 mb-sm-0"><b>EMPLOYEE DETAILS</b>
			</div>
			
			<div class="form-group mb-1">
				<label for="recipient-name" class="col-form-label"><b>Fullname:</b></label>
				<input type="text" class="form-control text-center text-uppercase " id="Fullname" name="Fullname" placeholder="Surname, Name Middle Name" required />
			</div>
		  
			<div class="form-group">
				<div class= "form-row">
					<div class="col-6">
						<label for="recipient-name" class="col-form-label"><b>Employee No.:</b></label>
						<input type="text" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control text-center text-uppercase" id="Employee" name="Employee" required />
					</div>
				
					<div class="col-6">
						<label for="recipient-name" class="col-form-label"><b>Date Hired:</b></label>
						<input type="date" class="form-control text-center text-uppercase" id="Hired" name="Hired" required />
						
					</div>
					
					<label for="recipient-name" class="col-form-label"><b>Position:</b></label>
					<input type="text" class="form-control text-center text-uppercase" id="Position" name="Position" required />
					
					<label for="recipient-name" class="col-form-label"><b>Department:</b></label>
						<select id="center" class="form-control" name="department">
                            <option></option>
                            <?php 
                                $sql1 = "SELECT DISTINCT department FROM user_info ORDER BY department";
                                $query1=$HRconnect->query($sql1);
                                while($row1=$query1->fetch_array()){
                                ?>
                            <option><?php echo $row1 ['department']; ?></option>
                            <?php } ?>
                        </select>
					
					<label for="recipient-name" class="col-form-label"><b>Branch:</b></label>
						<select id="center" class="form-control text-uppercase" name="branch" required>
                            <option></option>
							<?php 
								$sql1 = "SELECT DISTINCT branch FROM user_info ORDER BY branch";
								$query1=$HRconnect->query($sql1);
								while($row1=$query1->fetch_array()){
								?>
                            <option><?php echo $row1 ['branch']; ?></option>
                            <?php } ?>
                        </select>
					
					<label for="recipient-name" class="col-form-label"><b>Company:</b></label>
						<select id="center" class="form-control text-uppercase" name="company" required>
                            <option></option>
							<?php 
								$sql1 = "SELECT DISTINCT company FROM user_info ORDER BY company";
								$query1=$HRconnect->query($sql1);
								while($row1=$query1->fetch_array()){
								?>
                            <option><?php echo $row1 ['company']; ?></option>
                            <?php } ?>
                        </select>

                        <!-- NEW ADDED FOR AREA TYPE -->
                        <label for="recipient-name" class="col-form-label"><b>Area Type:</b></label>
						<select id="center" class="form-control text-uppercase" name="area_type" required>
                            <option></option>
							<?php 
								$sql1 = "SELECT DISTINCT area_type FROM user_info ORDER BY area_type";
								$query1=$HRconnect->query($sql1);
								while($row1=$query1->fetch_array()){
								?>
                            <option><?php echo $row1 ['area_type']; ?></option>
                            <?php } ?>
                        </select>

                        
				</div>
			</div>      
	</form>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
			<button type="submit" name="add" value="Submit" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to Add this Employee?');">Add</button>

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
	
	<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
	
	<!-- Script -->
    
    <script type="text/javascript">
            $(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update[]"]').prop('checked',true);
                    }else{
                        $('input[name="update[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update[]"]').click(function(){
                    var total_checkboxes = $('input[name="update[]"]').length;
                    var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll').prop('checked',true);
                    }else{
                        $('#checkAll').prop('checked',false);
                    }
                });
            });
			
			$(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll1').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update1[]"]').prop('checked',true);
                    }else{
                        $('input[name="update1[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update1[]"]').click(function(){
                    var total_checkboxes = $('input[name="update1[]"]').length;
                    var total_checkboxes_checked = $('input[name="update1[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll1').prop('checked',true);
                    }else{
                        $('#checkAll1').prop('checked',false);
                    }
                });
            });

			$(document).ready(function(){

                // Check/Uncheck ALl
                $('#checkAll2').change(function(){
                    if($(this).is(':checked')){
                        $('input[name="update2[]"]').prop('checked',true);
                    }else{
                        $('input[name="update2[]"]').each(function(){
                            $(this).prop('checked',false);
                        }); 
                    }
                });

                // Checkbox click
                $('input[name="update2[]"]').click(function(){
                    var total_checkboxes = $('input[name="update2[]"]').length;
                    var total_checkboxes_checked = $('input[name="update2[]"]:checked').length;

                    if(total_checkboxes_checked == total_checkboxes){
                        $('#checkAll2').prop('checked',true);
                    }else{
                        $('#checkAll2').prop('checked',false);
                    }
                });
            });	
    </script>
	
</body>

</html>

<?php } ?>