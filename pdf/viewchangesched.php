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


$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];

if($userlevel != 'staff') {
    // FOR NOT STAFF CHANGE SCHED
    if(isset($_GET['csapprove'])){
        $id = $_GET["id"];
        $empname = $_GET['name'];
        $Employee = $_GET["empno"];
        @$datefrom = trim($_GET["datefrom"]);
        $schedfrom = $_GET["sched_timein"];
        $schedto = $_GET["sched_timeout"];
        $request_in = trim($_GET["req_timein"]);
        $request_out = trim($_GET["req_timeout"]);
        $request_break = trim($_GET["req_breaks"]);
        $timedate = date("Y-m-d H:i");
        $prev_breaks = trim($_GET['sched_breaks']);


        $isNextDay = (strtotime($request_out) <= strtotime($request_in)) ? date("Y-m-d", strtotime("+1 day", strtotime($datefrom))) : $datefrom;
        if ($userlevel == 'ac' OR $userlevel == 'admin' OR $userlevel == 'master' OR $_SESSION['empno'] == '4292'){
            $update1=" UPDATE change_schedule 
                    SET cs_status = 'approved',
                    apptimedate = '$timedate',
                    approver = '$user'
                    WHERE cs_ID = '$id'";
            
            $sched_update = "UPDATE `hrms`.`sched_time` SET `schedfrom` = '".$datefrom." ".$request_in."', `schedto` = '".$isNextDay." ".$request_out."', `break` = '".$request_break."'
            WHERE (`empno` = '".$Employee."' AND `datefromto` = '".$datefrom."');";

            // LOGS
            $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
            VALUES ('".$empno."','Approved ".$empname." ".$Employee." previous sched (breaks: ".$prev_breaks." sched: ".$schedfrom." - ".$schedto.") new sched (breaks: ".$request_break." sched: ".$request_in." - ".$request_out.") - Change Schedule', 'Successfully Saved', '".$timedate."');";
            $HRconnect->query($sql_insert_log);
            
            $HRconnect->query($update1);
            $HRconnect->query($sched_update);
        }else{
    
            $update1=" UPDATE change_schedule 
            SET cs_status = 'pending2',
            p_apptimedate = '$timedate',
            p_approver = '$user'
            WHERE cs_ID = '$id'";

            // LOGS
            $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
            VALUES ('".$empno."','Partially Approved ".$empname." ".$Employee." previous sched (breaks: ".$prev_breaks." sched: ".$schedfrom." - ".$schedto.") new sched (breaks: ".$request_break." sched: ".$request_in." - ".$request_out.") - Change Schedule', 'Successfully Saved', '".$timedate."');";
            $HRconnect->query($sql_insert_log);
        }
        if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2 
		OR $userlevel == 'ac' AND $_SESSION['empno'] == 1331 OR $_SESSION['empno'] == 3071 OR $_SESSION['empno'] == 1073){
            $HRconnect->query($update1);
            header("location:approvals.php?cs=cs&m=10");	

        }if($userlevel == 'ac' AND $_SESSION['empno'] != 271 OR $_SESSION['empno'] != 24 OR $userlevel != 'ac' AND $_SESSION['empno'] != 71 
		OR $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 OR $_SESSION['empno'] != 3107 OR $_SESSION['empno'] != 2221 OR $_SESSION['empno'] != 3336 
		OR $_SESSION['empno'] != 3111 OR $_SESSION['empno'] != 159 OR $_SESSION['empno'] != 5752 OR $_SESSION['empno'] != 3027 
		OR $_SESSION['empno'] != 885 OR $_SESSION['empno'] != 5975 OR $_SESSION['empno'] != 5356 OR $_SESSION['empno'] != 5584
		OR $_SESSION['empno'] != 5361 OR $_SESSION['empno'] != 3178 OR $_SESSION['empno'] != 5515 OR $_SESSION['empno'] == 5452 
		OR $_SESSION['empno'] != 4811 OR $_SESSION['empno'] != 2684 OR $_SESSION['empno'] != 884
		OR $_SESSION['empno'] != 107){
            $HRconnect->query($update1);
            header("location:approvals.php?cs=cs&m=10");	

        }if($_SESSION['empno'] == 271 OR $_SESSION['empno'] == 271 OR $userlevel == 'ac' AND $_SESSION['empno'] == 71 
		OR $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3336 
		OR $_SESSION['empno'] == 3111 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 
		OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 5356 OR $_SESSION['empno'] == 5584
		OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 
		OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 884
		OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 24){
            $HRconnect->query($update1);
            header("location:../filed_change_schedule.php?pending=pending&m=2");

        }if($userlevel == 'mod'){
            $HRconnect->query($update1);
            header("location:../filed_change_schedule.php?pending=pending&m=2");
        }
    }
    if(isset($_GET['cscancel'])){
        $id = $_GET["id"]; 
        
        @$Employee = $_GET["empno"];
        @$type	  = $_GET["type"];
        @$datefrom = $_GET["datefrom"];
        @$timedate = date("Y-m-d H:i");

        $update1=" UPDATE change_schedule 
                SET cs_status = 'cancelled',
                apptimedate = '$timedate',
                approver = '$user'
                WHERE cs_ID = $id";

        if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 1 OR $_SESSION['empno'] == 2 
		OR $userlevel == 'ac' AND $_SESSION['empno'] == 1331 OR $_SESSION['empno'] == 3071 OR $_SESSION['empno'] == 1073){
            $HRconnect->query($update1);
            header("location:approvals.php?cs=cs&m=9");	

        }if($userlevel == 'ac' AND $_SESSION['empno'] != 271 OR $_SESSION['empno'] != 24 OR $userlevel != 'ac' AND $_SESSION['empno'] != 71 
		OR $_SESSION['empno'] != 3294 or $_SESSION['empno'] != 6538 or $_SESSION['empno'] != 229 OR $_SESSION['empno'] != 3107 OR $_SESSION['empno'] != 2221 OR $_SESSION['empno'] != 3336 
		OR $_SESSION['empno'] != 3111 OR $_SESSION['empno'] != 159 OR $_SESSION['empno'] != 5752 OR $_SESSION['empno'] != 3027
		OR $_SESSION['empno'] != 885 OR $_SESSION['empno'] != 5975 OR $_SESSION['empno'] != 5356 OR $_SESSION['empno'] != 5584
		OR $_SESSION['empno'] != 5361 OR $_SESSION['empno'] != 3178 OR $_SESSION['empno'] != 5515 OR $_SESSION['empno'] == 5452 
		OR $_SESSION['empno'] != 4811 OR $_SESSION['empno'] != 2684 OR $_SESSION['empno'] != 884
		OR $_SESSION['empno'] != 107){
            $HRconnect->query($update1);
            header("location:approvals.php?cs=cs&m=9");	
            
        }if($_SESSION['empno'] == 271 OR $_SESSION['empno'] == 271 OR $userlevel == 'ac' AND $_SESSION['empno'] == 71 
		OR $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6538 or $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3336 
		OR $_SESSION['empno'] == 3111 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027
		OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 5356 OR $_SESSION['empno'] == 5584
		OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 OR $_SESSION['empno'] == 5452 
		OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 884	
		OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 24){
            $HRconnect->query($update1);
            header("location:../filed_change_schedule.php?pending=pending&m=5");	

        }if($userlevel == 'mod'){
            $HRconnect->query($update1);
            header("location:../filed_change_schedule.php?pending=pending&m=5");
        }
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
	<link rel="icon" href="../images/logoo.png">

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../home.php">
                <div class="sidebar-brand-icon">    
                    <img src="../images/logoo.png" width="40" height="45">
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../home.php?branch=<?php echo $_SESSION['useridd']; ?>">
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
                        <a class="collapse-item" href="../employeelist.php?active=active">Employee List</a>
                        <a class="collapse-item" href="../viewsched.php?current=current">Cut-Off Schedule</a>
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
                        <a class="collapse-item" href="../overtime.php?pending=pending">Filed Overtime</a>
						<a class="collapse-item" href="../obp.php?pendingut=pendingut">Filed OBP</a> 
						<a class="collapse-item" href="../leave.php?pending=pending">Filed Leave</a>
						<a class="collapse-item" href="../filedconcerns.php?pending=pending">Filed Concern</a>	
                        <a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change Schedule</a>	
                        <a class="collapse-item" href="../working_dayoff.php?pending=pending">Filed Working Day Off</a>		
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
                <a class="nav-link" href="../discrepancy.php">
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
						<a class="collapse-item" href="../concerns.php">View Concerns</a>						
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
                        <a class="collapse-item" href="#">PO/PR System</a>	
						<a class="collapse-item" href="#">ISS System</a>
						<a class="collapse-item" href="../../video/stock_out.php?fg=fg">Ordering System</a>                       					
                    </div>
                </div>
            </li>
			
			<!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
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
									<span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp <?php echo $_SESSION['user']['username']; ?> </span>                             
                            </a>                            
                        </li>
						<div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">                               
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user;?></span>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
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
                                if($userlevel == 'master'){
                                ?>    
                                <a class="dropdown-item" href="database.php">
                                    <i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Database
                                </a>
                            <?php   
                                }
                                ?>
							
							<?php 
								if($userlevel == 'master' OR $userlevel == 'ac' OR $userlevel == 'admin' ){
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
				
				<?php  
					if(isset($_GET["cs"]) == "cs"){  
                        $id = $_GET['id'];
                        $sql = "SELECT cs.cs_ID, ui.empno, ui.name, ui.userid, ui.branch,
                                cs.datefrom as cs_datefrom, cs.cs_schedfrom, cs.cs_schedto, 
                                cs.cs_break, cs.cs_status, cs.cs_reason,
                                st.schedto, st.schedfrom, st.datefromto, st.break
                                FROM user_info ui
                                JOIN change_schedule cs ON ui.empno = cs.empno
                                JOIN sched_time st ON ui.empno = st.empno
                                WHERE cs.cs_ID = $id AND cs.datefrom = st.datefromto";

                        $query=$HRconnect->query($sql);
                        $row=$query->fetch_array();
                        $request_type = "Change Schedule";
                        $datefrom = $row['cs_datefrom'];
                        $breaks = $row['cs_break'];
                        $sched_breaks = $row['break'];
                        $scheduled_time_in = $row['schedfrom'];
                        $scheduled_time_out = $row['schedto'];
                        $time_in = $row['cs_schedfrom'];
                        $time_out = $row['cs_schedto'];
                        ?>	
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <div class="mb-3">
                                <h4 class="mb-0">Change Schedule - Details</h4>
                                <div class="small">
                                    <span class="fw-500 text-primary"><?php echo date('l'); ?></span>
                                    <?php echo date('F d, Y - h:i:s A'); ?>
                                </div>
                            </div>						
                        </div>

                    <form class="user" method="GET">

                        <div class="row justify-content-center">								
                            <div class="col-xl-6 col-lg-12 col-md-9">				
                                <div class="card o-hidden border-0 shadow-lg my-2">
                                    <div class="card-body p-0">
                                        <!-- Nested Row within Card Body -->
                                        <div class="row">																				
                                            <div class="col-lg-12">
                                                <div class="p-4">														
                                                        <div class="form-group">
                                                            <input type="text" name="name" class="form-control form-control-user bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo $row['name']; ?>" style="font-size:100%" readonly />
                                                        </div>
                                                            
                                                        <div class="form-group row">								
                                                            <div class="col-sm-6 mb-sm-0 text-center">
                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center  text-uppercase"
                                                                name="empno" value="<?php echo $row['empno']; ?>" style="font-size:100%" readonly />
                                                            </div>
                                                            
                                                                
                                                            <div class="col-sm-6 text-center">		
                                                                <input type="text" class="form-control form-control-user bg-gray-100 text-center" id="Branch"
                                                                value="<?php echo $row['branch']; ?>" style="font-size:100%" readonly />
                                                            </div>
                                                        </div>
                                                        
                                                        <hr>
                                                        
                                                    <div class="form-group text-center text-uppercase">
                                                        <div class="form-group">
                                                        
                                                            <label>Change Schedule Date</label>

                                                            <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                            value="<?php echo $row['cs_datefrom'];?> " style="font-size:100%" readonly />
                                                            <input type="text" hidden name="id" value="<?php echo $id ?>">
                                                        </div>				
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-6 ">                                
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">Scheduled Sched-From</small></label>
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo date("H:i",strtotime($scheduled_time_in)); ?>" name="sched_timein" readonly/>
                                                            </div>
                                                            <div class="col-sm-12 text-center">      
                                                                <label><small class="text-uppercase">Scheduled Sched-To</small></label>
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo date("H:i",strtotime($scheduled_time_out)); ?>"  name="sched_timeout" readonly/>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-6">                                
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">Requested Sched-From</small></label>	
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo date("H:i",strtotime($time_in));  ?>" name="req_timein" readonly/>
                                                            </div>
                                                            <div class="col-sm-12 text-center">      
                                                                <label><small class="text-uppercase">Requested Sched-To</small></label>
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase" value=" <?php echo date("H:i",strtotime($time_out)); ?>" name="req_timeout" readonly/>
                                                            </div>
                                                        </div>    
                                                        <div class="form-group col-6">    
                                                            <center>                         
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">Scheduled Break(s)</small></label>	
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo $sched_breaks." Hr(s)";?>"readonly/>
                                                                <input type="number" class="d-none"
                                                                value="<?php echo $sched_breaks;?>" name="sched_breaks" readonly/>
                                                            </div>
                                                            </center>   
                                                        </div>   
                                                        <div class="form-group col-6">    
                                                            <center>                         
                                                            <div class="col-sm-12 mb-3 mb-sm-0 text-center">
                                                                <label><small class="text-uppercase">Requested Break(s)</small></label>	
                                                                <input type="text" class="form-control bg-gray-100 text-center text-uppercase"
                                                                value="<?php echo $breaks." Hr(s)";?>"readonly/>
                                                                <input type="number" class="d-none"
                                                                value="<?php echo $breaks;?>" name="req_breaks" readonly/>
                                                            </div>
                                                            </center>   
                                                        </div>  
                                                    </div>
                                                    

                                                    <div class="form-group text-center">
                                                        <label>Reason Or Purpose</label>

                                                        <textarea style="height:60px;" maxlength="50" type="date" class="form-control bg-gray-100 text-center text-uppercase"
                                                            id="date" readonly><?php echo $row['cs_reason']; ?></textarea>

                                                    </div>

                                                        <input type="text" class="d-none" value=" <?php echo $datefrom; ?>" name="datefrom" readonly/>
                                                        
                                                    <input type="submit" name="csapprove" class="btn btn-success btn-user btn-block bg-gradient-success" value="Approved" onclick="return confirm('Are you sure you want to Approved this Change Schedule?');">

                                                    <input type="submit" name="cscancel" class="btn btn-danger btn-user btn-block bg-gradient-danger" value="Cancel" onclick="return confirm('Are you sure you want to Cancel out this Change Schedule');">												                                   
                                                </form>
                                                    
                                                    <hr>
                                                    <div class="text-center">
                                                    <?php 
                                                        if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' 
														AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 6538 AND $_SESSION['empno'] != 229 AND $_SESSION['empno'] != 3107 
														AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 
														AND $_SESSION['empno'] != 5752 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 885 AND $_SESSION['empno'] != 5975 AND $_SESSION['empno'] != 5356 
														AND $_SESSION['empno'] != 5361 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 5515 AND $_SESSION['empno'] != 5452 
														AND $_SESSION['empno'] != 4811 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 884
														AND $_SESSION['empno'] != 5584 AND $_SESSION['empno'] != 107){
                                                        ?>
                                                        <a class="small float-right" href="approvals.php?cs=cs">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                    <?php    
                                                        }else{
                                                        ?>
                                                        <a class="small float-right" href="../filed_change_schedule.php?pending=pending">Back <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                    <?php
                                                        }
                                                        ?>
                                                    <br>
                                                    </div>                               
                                            </div>
                                            </div>																				
                                        </div>
                                    </div>
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
                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
	

	<!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>
<?php } ?>