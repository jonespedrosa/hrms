<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 session_start();

 set_time_limit(60000000);



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

Header('Location: '.$_SERVER['PHP_SELF']);

}


$sqll="SELECT * FROM sched_date 
              WHERE userid = '$userid'";
            $queryl=$HRconnect->query($sqll);
            $rowl=$queryl->fetch_array();
            @$datefrom2 = $rowl['biofrom'];
            @$dateto2 = $rowl['bioto']; 


if(isset($_POST["Import"]))
{

   $filename=$_FILES["file"]["tmp_name"];

    if($_FILES["file"]["size"] > 0)
    {
        $file = fopen($filename, "r");
        //$sql_data = "SELECT * FROM prod_list_1 ";
        fgetcsv($file);
        while ((@$emapData = fgetcsv($file, 10000, ",")) !== FALSE)
        {
            

            if ($emapData[1] != ''){

                $sql=" UPDATE sched_time 
                    SET timein = '$emapData[5]',
                     breakout = '$emapData[6]',
                     breakin = '$emapData[7]',
                     timeout = '$emapData[8]',
                     overin = '$emapData[9]',
                     overout = '$emapData[10]'
                 WHERE empno = '$emapData[0]' AND  
                 (DATE_FORMAT(datefromto , '%m/%d/%Y') = '$emapData[4]' OR 
                  DATE_FORMAT(datefromto , '%MM/%d/%Y') = '$emapData[4]' OR 
                  DATE_FORMAT(datefromto , '%M/%d/%Y') = '$emapData[4]' OR
                  DATE_FORMAT(datefromto , '%m-%d-%Y') = '$emapData[4]' OR 
                  DATE_FORMAT(datefromto , '%MM-%d-%Y') = '$emapData[4]' OR 
                  DATE_FORMAT(datefromto , '%M-%d-%Y') = '$emapData[4]')";


            $querydate=$HRconnect->query($sql);



        }else {

           fclose($file);

        }
    } 
}       
    header("location:biologs.php?a=1");
}

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

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php?branch=<?php echo $_SESSION['useridd']; ?>">
                <div class="sidebar-brand-icon">
                    <img src="images/logoo.png" width="40" height="45">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="home.php?branch=<?php echo $_SESSION['useridd']; ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
        
        <?php if($userlevel != 'staff') 
            { 
            ?>
            <!-- Heading -->
            <div class="sidebar-heading">
                Information
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
        
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Employee</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"> Record-Keeping</h6>
                        <a class="collapse-item" href="employeelist.php?active=active">Employee List</a>
                        <a class="collapse-item" href="viewsched.php?current=current">Cut-Off Schedule</a>
                    </div>
                </div>
            </li>
		<?php if($empno != '4451') 
            { 
            ?>
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <span>Filed Documents</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Documents-Keeping</h6>						
                        <a class="collapse-item" href="overtime.php?pending=pending">Filed Overtime</a>
						<a class="collapse-item" href="overtime.php?pendingut=pendingut">Filed OBP</a> 
						<a class="collapse-item" href="leave.php?pending=pending">Filed Leave</a>
						<a class="collapse-item" href="filedconcerns.php?pending=pending">Filed Concern</a>	
                        <a class="collapse-item" href="filed_change_schedule.php?pending=pending">Filed Change Schedule</a>
                        <a class="collapse-item" href="working_dayoff.php?pending=pending">Filed Working Day Off</a>
                    <!--    <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a> -->
                    </div>
                </div>
            </li>
        <?php 
            } 
            ?>   

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Reports
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="discrepancy.php">
                    <i class="fas fa-chart-bar"></i>
                    <span>Cut-off Details</span></a>
            </li>
        
            
            <!-- Divider -->
            <hr class="sidebar-divider">
        <?php 
            } 
            ?>
        <!--  Heading -->
            <div class="sidebar-heading">
                Others
            </div>
			
			<!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed d-none" href="#" data-toggle="collapse" data-target="#collapseUtilities2"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <span>Helpdesk</span>
                </a>
                <div id="collapseUtilities2" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Support/Services</h6>
                        <a class="collapse-item" href="" data-toggle="modal" data-target="#exampleModal1">Create Ticket</a>	
						<a class="collapse-item" href="concerns.php">View Concerns</a>						
                    </div>
                </div>
            </li>
					
            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities1"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-sitemap" aria-hidden="true"></i>
                    <span>Systems</span>
                </a>
                <div id="collapseUtilities1" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">MGFI System</h6>
                        <a class="collapse-item" href="../popr/home.php">PO/PR System</a>	
						<a class="collapse-item" href="#">ISS System</a>
						<a class="collapse-item" href="../video/stock_out.php?fg=fg">Ordering System</a>                       					
                    </div>
                </div>
            </li>
			
			<!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
					<i class="fas fa-clock fa-chart-area"></i>
                    <span>Time-in</span></a>
            </li>
         
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                 
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                         <i class="fa fa-bars"></i>
                    </button>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                    
                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow">                         
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                                                                                            
								<?php 
                                if($userlevel != 'master' AND $userlevel != 'admin' AND $userlevel != 'ac' OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
									OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3336){
									?>
									<span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp <?php echo $_SESSION['user']['username']; ?> </span>
                                <?php    
                                }else{
								?>
								
								<?php
                                }
                                ?>                              
                            </a>                            
                        </li>
                        
                    <?php 
                        if($userlevel == 'master' OR $userlevel == 'admin' OR $userlevel == 'ac' AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
							AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 3336){
                        ?>
                        
                        <li class="nav-item dropdown no-arrow">                         
                            <form method="GET">                                                         
								<span class="nav-link text-gray-600 text-uppercase"><i class='fas fa-store fa-sm'></i>
                                <select class="nav-link text-gray-600 small border-0 text-uppercase bg-white" name="branch" onchange='this.form.submit()' style="width: 100%;">                            
                                        <option><?php echo $areatype; ?> - 
                                        <?php 
                                        if ($_SESSION['useridd'] != ""){
                                            @$sql2 = "SELECT DISTINCT branch FROM user_info where userid = '".$_SESSION['useridd']."'";
                                            $query2=$HRconnect->query($sql2);
                                            $row2=$query2->fetch_array();
                                            echo $row2['branch'];
                                            }else{
                                                echo "<a class='text-danger'>Please Select Cafe/Dept</a>";
                                            } 
                                            ?>
                                        </option>   
                                    <?php 
                                        if ($userlevel == 'master' OR $userlevel == 'admin'){
                                        $sql2 = "SELECT * FROM user where areatype in('HO','Prod','South','North','MFO','KIOSK') ORDER BY `areatype` DESC, `username`";
										}else{
										$sql2 = "SELECT * FROM user where areatype = '$areatype' ORDER BY `areatype` DESC, `username` ";	
										}
																				
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                        $sql2 = "SELECT * FROM user where areatype = 'South' OR userid = 109 ORDER BY `areatype` DESC, `username` ";
										
										}
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170){
                                        $sql2 = "SELECT * FROM user where areatype = 'MFO' OR userid = 109 ORDER BY `areatype` DESC, `username` ";
										
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
                                        $sql2 = "SELECT * FROM user where areatype = 'North' OR userid = 109 ORDER BY `areatype` DESC, `username` ";
										
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){
                                        $sql2 = "SELECT * FROM user where areatype in('South','North','MFO') OR userid = 109 ORDER BY `areatype` DESC, `username` ";
										
                                        }

                                        if($userlevel == 'ac' AND $_SESSION['empno'] == 3071){
                                            $sql2 = "SELECT * FROM user where userid in (82,155) ORDER BY `areatype` DESC, `username` ";
                                            
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 1233){
                                        $sql2 = "SELECT * FROM user where areatype in('HO','South','North','MFO') AND userid != 119 ORDER BY `areatype` DESC, `username` ";
										
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 2165){
                                        $sql2 = "SELECT * FROM user where userid in (4,2) ORDER BY `areatype` DESC, `username` ";
										
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 4072){
                                        $sql2 = "SELECT * FROM user where areatype in('Prod') OR userid in (82,156,1,174) ORDER BY `areatype` DESC, `username` ";
										
                                        }
										
										if($userlevel == 'ac' AND $_SESSION['empno'] == 3080 OR $_SESSION['empno'] == 1261 OR $_SESSION['empno'] == 1910 OR $_SESSION['empno'] == 3160 OR $_SESSION['empno'] == 1509 OR $_SESSION['empno'] == 1053 OR $_SESSION['empno'] == 2356 OR $_SESSION['empno'] == 3156 OR $_SESSION['empno'] == 3612 OR $_SESSION['empno'] == 4001){
                                        $sql2 = "SELECT * FROM user where areatype in('HO','Prod','South','North','MFO','KIOSK') AND userid != 88 ORDER BY `areatype` DESC, `username` ";
										
                                        }
                                        $query2=$ORconnect->query($sql2);
                                         while($row2=$query2->fetch_array())
                                        {
                                        $userid1 = $row2['userid'];
                                      
                                        $sql1 = "SELECT DISTINCT branch,userid FROM user_info where userid = '$userid1'";
                                        $query1=$HRconnect->query($sql1);
                                        $row1=$query1->fetch_array();

                                        $sql3 = "SELECT * FROM user where userid = '$userid1'";
                                        $query3=$ORconnect->query($sql3);
                                        $row3=$query3->fetch_array();
                                        ?>
                                        <option value="<?php echo @$row1['userid']; ?>"><?php echo @$row3['areatype']; ?> - <?php echo @$row1['branch']; ?></option>
                                    <?php                            
                                        }
                                        ?>
                                </select>
                                </span>
                            </form> 
                        </li>   
                    <?php   
                        }
                        ?>    
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                               
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user;?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                <a class="dropdown-item d-md-none" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400 d-md-none"></i>
                                    <?php echo $user;?>
                                </a>                                
                                
                                <div class="dropdown-divider d-md-none"></div>
                                
                                <a class="dropdown-item" href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
                                    <i class="fa fa-address-card fa-sm fa-fw mr-2 text-gray-400 "></i>
                                    Profile
                                </a>
                                
                            <?php 
                                if($userlevel == 'master' OR $userlevel == 'admin' AND $mothercafe == 137){
                                ?>    
                                <a class="dropdown-item" href="database.php">
                                    <i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Database
                                </a>
                            <?php   
                                }
                                ?>
                            
                            <?php 
                                if($userlevel == 'master' OR $userlevel == 'admin' OR $mothercafe == 137){
                                ?>   
                                <a class="dropdown-item" href="activitylogs.php">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Logs
                                </a>
                            <?php   
                                }
                                ?> 
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

<!-- Begin Page Content -->
                <div class="container-fluid">
                                
                    <!-- Page Heading -->                 
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">Biometrics Logs</h1>
                                                                      
<?php if ($userlevel == 'master'){ ?>
                    <!--  <div class="d-sm-flex align-items-center justify-content-between mb-4">
             
                        <a href="pdf/generatebio.php" class="btn btn-sm btn-success shadow-sm bg-gradient-dark"><i
                            class="fas fa-download fa-sm text-white-50"></i> Gwapo Aaron</a>   
      </div> -->

<?php } ?>      
                    <?php 
                        if($userlevel == 'master'){
                        ?>  
                            <form enctype="multipart/form-data" method="post" role="form">
                                <center><a>
                                <input  class="text-primary small" type="file" name="file" id="file" size="150" required>
                                <button type="submit" class="btn btn-sm btn-primary shadow-sm bg-gradient-primary" name="Import" value="Import">
                                <i class="fas fa-download fa-sm text-50"></i> <span class="d-none d-sm-inline-block">Upload</span>
                                </button></a></center>
									
                            </form>

                            <div id="myModal" class="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"> 
                                            <h5>Marygrace Foods Inc.</h5>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <center><h4 class="text-success"><i class="fa fa-check-circle"></i> Biologs was Successfully Uploaded </h4></center>            
                                        </div>
                                        <div class="modal-footer">
                                            <a href="biologs.php" class="btn btn-success btn-user btn-block bg-gradient-success">OK</a>         
                                        </div>
                                    </div>
                                </div>
                            </div>


                    <?php   
                        }
                        ?>  
                    </div>
                
                    <!-- Content Row -->

                  <div class="row">

           

                        <!-- Area Chart -->
                        <div class="col-xl-12 col-lg-7">
                                        <?php 
											if($userlevel == 'master'){
											?>     
											
											<form method="POST">
                                                    <div class="form-group row">                                
                                                        <div class="col-sm-2 text-center mb-2">
                                                            <label>Cut-Off Date From</label>
                                                            <input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo  $datefrom2; ?>" autocomplete="off" required onkeypress="return false;" />                                                                                                              
                                                        </div>
                                                        
                                                        <div class="col-sm-2 text-center mb-2">
                                                            <label>Cut-Off Date To</label>
                                                            <input type="date" id="#datePicker1" class="form-control text-center" name="dateto" placeholder="Insert Date" value="<?php echo  $dateto2; ?>" autocomplete="off" required onkeypress="return false;" />
                                                        </div>
                                                        
                                                        <div class="col-xs-3 text-center d-none d-sm-inline-block">
                                                            <label class="invisible">.</label>
                                                            <input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" value="Submit" formaction="update.php?biodate=biodate">
                                                        </div>
                                                        
                                                        <div class="col-sm-6 d-md-none">
                                                            <input class="btn btn-primary btn-user btn-block shadow-sm  bg-gradient-primary" type="submit" value="Submit" formaction="update.php?biodate=biodate">
                                                        </div>
                                                    </div>
                                                </form>
										<?php   
											}
											?> 		
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary d-md-none">Biometrics Logs</h6>    
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-uppercase" id="example" width="100%" cellspacing="0">
                                                                                
                                            <thead>
                                                <tr class="bg-gray-200">
                                                    <th><center>ID</center></th>                                                   
                                                    <th><center>Fullname</center></th>
                                                    <th><center>branch</center></th>
                                                    <th><center>Date</center></th>
                                                    <th><center>Time in</center></th>   
                                                    <th><center>Breakout</center></th>
                                                    <th><center>Breakin</center></th>
                                                    <th><center>Time out</center></th>      
                                                    <th><center>OT In</center></th>
                                                    <th><center>OT out</center></th>                                            
                                                </tr>
                                            </thead>
                                            
                                            <tbody> 

                                   <?php

                                        $sql2 = "SELECT * FROM sched_time 
                                        JOIN user_info ON sched_time.empno = user_info.empno
                                        WHERE user_info.mothercafe = '$userid' AND datefromto between '$datefrom2' AND '$dateto2'";
                                        $query2=$HRconnect->query($sql2);
                                        while($row2=$query2->fetch_array())                                      
                                        {
                                         $name = $row2['name']; 
                                        ?>  
                                                <tr>        
                                                    <td><center><?php echo $row2['empno']; ?></center></td>
                                                    <td><center><?php echo utf8_encode($name); ?></center></td>
                                                    <td><center><?php echo $row2['branch']; ?></center></td>
                                                    <td><center><?php echo $row2['datefromto']; ?></center></td>
                                                    <td><center><?php echo $row2['timein']; ?></center></td>                 
                                                    <td><center><?php echo $row2['breakout']; ?></center></td>
                                                    <td><center><?php echo $row2['breakin']; ?></center></td>
                                                    <td><center><?php echo $row2['timeout']; ?></center></td>
                                                    <td><center><?php echo $row2['overin']; ?></center></td>   
                                                    <td><center><?php echo $row2['overout']; ?></center></td>                                                                                                                                                                                                                  
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
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

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
