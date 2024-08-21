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

if($userlevel != 'staff') {

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
                        <a class="collapse-item" href="employeelist.php">Employee List</a>
                        <a class="collapse-item" href="viewsched.php?current=current">Cut-Off Schedule</a>
                        <a class="collapse-item" href="emptimesheets.php">Attendance Timesheet</a>                      
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fa fa-file" aria-hidden="true"></i>
                    <span>Filed Documents</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Documents-Keeping</h6>
                        <a class="collapse-item active" href="leave.php">Leave Tracker</a>
                        <a class="collapse-item" href="overtime.php">Over Time Forms</a>
                        <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
                 <?php if($userlevel == 'admin'){ ?>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Biologs
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="biologs.php">
                    <i class="fas fa-clock fa-chart-area"></i>
                    <span>Employee Biologs</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="discrepancy.php">
                    <i class="fa fa-balance-scale" aria-hidden="true"></i>
                    <span>Discrepancy</span></a>
            </li>
        <?php } ?>

            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Heading -->
            <div class="sidebar-heading">
                Others
            </div>

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
                        <a class="collapse-item" href="../purchasing">PO/PR System</a>	
						<a class="collapse-item" href="#">ISS System</a>
						<a class="collapse-item" href="../projection">Ordering System</a>                       					
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
                                if($userlevel != 'ac' AND $userlevel != 'admin'){
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
                        if($userlevel == 'ac' OR $userlevel == 'admin'){
                        ?>
                        
                        <li class="nav-item dropdown no-arrow">                         
                            <form method="GET">                                                         
								<span class="nav-link text-gray-600 text-uppercase"><i class='fas fa-store fa-sm'></i>
								<select class="nav-link text-gray-600 small border-0 text-uppercase bg-white" name="branch" onchange='this.form.submit()' style="width: 100%;">                            
                                        <option><?php echo $areatype; ?> - 
                                        <?php 
                                            @$sql2 = "SELECT DISTINCT branch FROM user_info where userid = '".$_SESSION['useridd']."'";
                                            $query2=$HRconnect->query($sql2);
                                            $row2=$query2->fetch_array();
                                            echo $row2['branch']; 
                                            ?>
                                        </option>   
                                    <?php 
                                        if ($userlevel == 'admin'){
                                        $sql2 = "SELECT * FROM user";
                                       
                                        }else{
                                        $sql2 = "SELECT * FROM user where areatype = '$areatype'";
                                
                                        
                                        }
                                        $query2=$ORconnect->query($sql2);
                                         while($row2=$query2->fetch_array())
                                        {
                                        $userid1 = $row2['userid'];
                                      
                                        $sql1 = "SELECT DISTINCT branch,userid FROM user_info where userid = '$userid1'";
                                        $query1=$HRconnect->query($sql1);
                                        $row1=$query1->fetch_array()
                                        ?>
                                        <option value="<?php echo @$row1['userid']; ?>"><?php echo @$row1['branch']; ?></option>
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
                                
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                            <?php 
                                if($userlevel == 'ac' OR $userlevel == 'admin' ){
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
                        <h1 class="h3 mb-0 text-gray-800 d-none d-sm-inline-block">Attachments</h1>                      
                    </div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary d-md-none">Attachments                               
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-uppercase" id="example" width="100%" cellspacing="0">
                                                                        
                                    <thead>
                                        <tr class="bg-gray-200">                                                                                                                                                    
                                            <th><center>Remarks</center></th>
                                            <th><center>Explanation</center></th>
                                            <th><center>Attachments</center></th>                             
                                        </tr>
                                    </thead>
                                    
                                    <tbody> 
										<td><center>System Error</center></td>
										<td><center>imply dummy text of the printing and typesetting industry.</center></td>
										<td><center><a href="#"><i class="fa fa-eye" aria-hidden="true"></i></a></center></td>
                                    </tbody>                                                                                                
                                </table>
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
    <script src="../js/demo/datatables-demo.js"></script>
    
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