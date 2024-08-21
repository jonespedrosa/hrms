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
$empno2 = $row['empno'];


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


  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");




if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {

        $empno = $_POST['empno'];
        $datefrom = $_POST['datefrom'];
        $reason = $_POST['reason'];
        $remarks = $_POST['remarks'];
        $type = $_POST['type'];
        $system = $_POST['system'];
        $bio = $_POST['bio'];
       
        $imgData = addslashes(file_get_contents($_FILES['userImage']['tmp_name']));
        $imageProperties = getimageSize($_FILES['userImage']['tmp_name']);
        
        $sql = "INSERT INTO attachment(empno,atdatefrom,attype,system,bio,atreason,atremarks,imageType ,imageData)
    VALUES('{$empno}','{$datefrom}','{$type}','{$system}','{$bio}','{$reason}','{$remarks}','{$imageProperties['mime']}', '{$imgData}')";
        $current_id = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($HRconnect));



$date_time = date("Y-m-d h:i");
$inserted = "Successfully Filed Attachments";
$action = "Problems on ". $type ." - ". $empno;

$sql3 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno2', '$action', '$inserted','$date_time')";

$HRconnect->query($sql3);



        if (isset($current_id)) {
            header("Location: discrepancy.php?discrepancy=discrepancy");
        }
    }
}

if(isset($_POST["Cancel"]) == "Cancel") {

   $empno = $_POST['empno1'];
   $date  = $_POST['atdatefrom'];
   $attype = $_POST['attype'];


 $update1=" UPDATE attachment 
             SET atstatus = 'canceled'
             WHERE empno = '$empno' AND atdatefrom = '$date' AND attype = '$attype'";
        
       $HRconnect->query($update1);

header("Location: discrepancy.php?discrepancy=discrepancy");

}


if(isset($_POST["Approveds"]) == "Approveds") { 

   $empno = $_POST['empno1'];
   $date  = $_POST['atdatefrom'];
   $date2  = date("Y", strtotime($_POST['atdatefrom']))."-".date("d", strtotime($_POST['atdatefrom']))."-".date("m", strtotime($_POST['atdatefrom']));
   $attype = $_POST['attype'];
   $time1 = $date2." ".$_POST['origtime'];
   $time2 = $date2." ".$_POST['time1'].":".$_POST['time2'];
   
 $update1=" UPDATE attachment 
      SET atstatus = 'approved'
      WHERE empno = '$empno' AND atdatefrom = '$date' AND attype = '$attype'";
        
       $HRconnect->query($update1);

       $update2="INSERT INTO editeddisc (empno, disdatefrom, distype, disbefore, disafter, disapproval) 
         VALUES('$empno', '$date', '$attype','$time1','$time2','$empno2')";

                $HRconnect->query($update2);

if($attype == 'Timein'){
  
    $update3=" UPDATE sched_time 
      SET M_Timein = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}

if($attype == 'Breakout'){
  
    $update3=" UPDATE sched_time 
      SET M_Timeout = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}

if($attype == 'Breakin'){
  
    $update3=" UPDATE sched_time 
      SET A_Timein = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}

if($attype == 'Timeout'){
  
    $update3=" UPDATE sched_time 
      SET A_Timeout = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}

if($attype == 'OvertimeIn'){
  
    $update3=" UPDATE sched_time 
      SET O_Timein = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}

if($attype == 'OvertimeOut'){
  
    $update3=" UPDATE sched_time 
      SET O_Timeout = '$time2'
      WHERE empno = '$empno' AND DATE_FORMAT(datefromto, '%m-%d-%Y') = '$date'";
        
       $HRconnect->query($update3);
       
}




$date_time = date("Y-m-d h:i");
$inserted = "Successfully Approve Attachment";
$action = $attype.":(". $date .")(From:".$_POST['origtime'] .")(To:". $_POST['time1'].":".$_POST['time2'] .")";

$sql3 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno2', '$action', '$inserted','$date_time')";

$HRconnect->query($sql3);





  header("Location: discrepancy.php?discrepancy=discrepancy");
} 
?>  




<!DOCTYPE html>
<html lang="en">

<head>


<?php if(($userlevel == 'mod' OR $userlevel == 'admin' OR $userlevel == 'ac' OR $userlevel == 'master') AND @$_GET['insert']=="insert") { ?>
 <form name="frmImage" enctype="multipart/form-data" action="" method="post" class="frmImageUpload">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">Reasons / Remarks</h5>
              <input type="text" hidden value="<?php echo $_GET['date']; ?>" name="datefrom">
              <input type="text" hidden value="<?php echo $_GET['empno']; ?>" name="empno">
              <input type="text" hidden value="<?php echo $_GET['type']; ?>" name="type">
              <input type="text" hidden value="<?php echo $_GET['from']; ?>" name="system">
              <input type="text" hidden value="<?php echo $_GET['to']; ?>" name="bio">
              <input type="text" hidden value="<?php echo $_GET['type']; ?>" name="type">


           <label>  <?php echo $_GET['empno']; ?></la bel> 
            </div>
            <div class="modal-body">
         
                <div class="form-group">
                    <div><center><b>Discrepancy</b></center></div>
              Date: <?php echo $_GET['date']; ?>
              <br> 
              <?php echo $_GET['type']; ?> : 
              <div><center>
                <table>
                <tbody>
                  <tr>
                    <td>  SystemLogs </td>
                    <td>Biologs</td>
                </tr>

                  <tr>
                    <td><input type="empid" class="form-control form-control-user text-center text-uppercase" readonly
                                                id="empname" aria-describedby="empname"
                                                placeholder="No Systemlogs" value="<?php echo $_GET['from']; ?>">  </td>
                    <td><input type="empid" class="form-control form-control-user text-center text-uppercase" readonly
                                                id="empname" aria-describedby="empname"
                                                placeholder="No Biologs" value="<?php echo $_GET['to']; ?>"></td>
                </tr>
                </tbody>
              </table>
              </center>
 

                 </div>                                
            
                <br>
                <br>

                <div><center>REASON</center></div>
                    <textarea type="text" class="form-control form-control-user text-center" required name="reason" placeholder="Specific Reasons" ></textarea>
                </div>

                 <div class="form-group">

                <div><center>REMARKS</center></div>
                    <span class="nav-link text-gray-600 text-uppercase">
                                <select class="nav-link text-gray-600 small border-0 text-uppercase bg-white" name="remarks" style="width: 100%;">                            
                                        <option>USER ERROR</option>   
                                        <option>SYSTEM ERROR</option>
                                    
                                </select>
                                </span>
                </div>


        <label>Upload Image File:</label><br /> <input name="userImage"
            type="file" class="inputFile"  required /> 


            </div>
            <div class="modal-footer">
              <input type="submit" value="Submit" class="btn btn-success" onclick="return confirm('Are you sure you want to Insert This Record?');" />
              <a href="discrepancy.php?discrepancy=discrepancy" class="btn btn-danger">Cancel </a>
            </div>
          </div>
        </div>
      </div>
 </form>
<script type="text/javascript">
$(window).on('load',function(){
        $('#loginModal').modal({backdrop: 'static', keyboard: false});
});
</script>
<?php } ?>


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



body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
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
                    </div>
                </div>
            </li>

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
                        <a class="collapse-item active" href="leave.php?pending=pending">Leave Tracker</a>
                        <a class="collapse-item" href="overtime.php?pending=pending">Over Time Forms</a>
                        <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
        <?php if($userlevel == 'admin' OR $userlevel == 'master'){ ?>
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
	
		<?php } ?>
            <!-- Nav Item - Tables -->
            <li class="nav-item active">
                <a class="nav-link" href="discrepancy.php">
                    <i class="fa fa-balance-scale" aria-hidden="true"></i>
                    <span>Discrepancy</span></a>
            </li>
        

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
                                        $sql2 = "SELECT * FROM user where areatype != ''";
                                       
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
                                        <th hidden></th>     
                                            <th><center>ID</center></th>      
                                            <th><center>Date</center></th>
                                            <th colspan="2"><center>Discrepancy</center></th>                           
                                            <th><center>Remarks</center></th>
                                            <th><center>Explanation</center></th>
                                            <th><center>Attachments</center></th>
                                             <?php if($userlevel = 'ac') { ?>    
                                            <th><center>Action</center></th>     
                                            <?php } ?>                     
                                        </tr>
                                    </thead>

                                        
                                    <?php 
                                    $empno = $_GET['empno']; 
                                    $date = $_GET['date']; 
                                    $type = $_GET['type']; 

                                    ?>
                                    <tbody> 
							   <form method="POST"> 
                                       <?php
                                        $sql = "SELECT * FROM attachment 
                                        INNER JOIN user_info on attachment.empno = user_info.empno
                                        WHERE attachment.empno = $empno AND atdatefrom = '$date' AND attype = '$type'
                                        ";
                                        $query=$HRconnect->query($sql);
                                        while($row=$query->fetch_array())
                                        {



                                        ?>      
                                                

                                            <tr>
                                                <td hidden>    <input type="text" hidden value="<?php echo $row['empno']; ?>" name="empno1">
                                                <input type="text" hidden value="<?php echo $row['atdatefrom']; ?>" name="atdatefrom">
                                                <input type="text" hidden value="<?php echo $row['attype']; ?>" name="attype">
                                                <input type="text" hidden value="<?php echo date("H:i", strtotime($row['system'])); ?>" name="origtime"></td>
                                                <td><center><?php echo $row['empno']; ?></center></td>
                                                <td><center><?php echo $row['atdatefrom']; ?></center></td>
                                           
                                                 <td><center><?php echo $row['attype']; ?><br>
                                                    Sys <br>
                                              
                                        <div class="text-center"> 
                                        <label> 
                                            <select  name="time1">
                                                <option  selected hidden><?php echo date("H", strtotime($row['system'])); ?></option>
                                                <option>00</option>
                                                <option>01</option>
                                                <option>02</option>
                                                <option>03</option>
                                                <option>04</option>
                                                <option>05</option>
                                                <option>06</option>
                                                <option>07</option>
                                                <option>08</option>
                                                <option>09</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                                <option>13</option>
                                                <option>14</option>
                                                <option>15</option>
                                                <option>16</option>
                                                <option>17</option>
                                                <option>18</option>
                                                <option>19</option>
                                                <option>20</option>
                                                <option>21</option>
                                                <option>22</option>
                                                <option>23</option>
                                            </select>
                                        </label>  
                                        
                                        <label>
                                            <select name="time2">
                                                <option><?php echo date("i", strtotime($row['system'])); ?></option>
                                                <option>00</option>
                                                <option>01</option>
                                                <option>02</option>
                                                <option>03</option>
                                                <option>04</option>
                                                <option>05</option>
                                                <option>06</option>
                                                <option>07</option>
                                                <option>08</option> 
                                                <option>09</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                                <option>13</option>
                                                <option>14</option>
                                                <option>15</option>
                                                <option>16</option>
                                                <option>17</option>
                                                <option>18</option>
                                                <option>19</option>
                                                <option>20</option>
                                                <option>21</option>
                                                <option>22</option>
                                                <option>23</option>
                                                <option>24</option>
                                                <option>25</option>
                                                <option>26</option>
                                                <option>27</option>
                                                <option>28</option>
                                                <option>29</option>
                                                <option>30</option>
                                                <option>31</option>
                                                <option>32</option> 
                                                <option>33</option>
                                                <option>34</option>
                                                <option>35</option>
                                                <option>36</option>
                                                <option>37</option>
                                                <option>38</option>
                                                <option>39</option>
                                                <option>40</option>
                                                <option>41</option>
                                                <option>42</option>
                                                <option>43</option>
                                                <option>44</option>
                                                <option>45</option>
                                                <option>46</option>
                                                <option>47</option>
                                                <option>48</option>
                                                <option>49</option>
                                                <option>50</option>
                                                <option>51</option>
                                                <option>52</option>
                                                <option>53</option>
                                                <option>54</option>
                                                <option>55</option>
                                                <option>56</option>
                                                <option>57</option>
                                                <option>58</option>
                                                <option>59</option>
                                            </select>
                                        </label>    
                                    </div>
                                                </td>
                                                 <td><center><?php echo $row['attype']; ?><br>
                                                    Bio <br>
                                                    <?php echo $row['bio']; ?></center></td>
                                                <td><center><?php echo $row['atremarks']; ?></center></td>
                                                <td><center><?php echo $row['atreason']; ?></center></td>
                                                <td><center><img id="myImg" class="someClass" alt="Attachment" src="imageView.php?image_id=<?php echo $row["imageId"]; ?>" style="width:50%;max-width:60px" onclick="openImgModal('<?php echo $row["imageId"]; ?>')" class="img-responsive"/>
                                                </center>
                                                </td>
                                               <?php if($userlevel = 'ac') { ?>
                                                <td><center>

                                                      <input type="submit" class="btn btn-success btn-user btn-block bg-gradient-success" 
                                                 name="Approveds" value="Approve" class="btn btn-outline-success" onclick="return confirm('Are you sure you want to Approve This Timesheet?');">

                                                <input class="btn btn-danger btn-user btn-block bg-gradient-danger " type="submit"
                                                 name="Cancel" value="Remove" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to Cancel This Timesheet?');">
                                                </center></td>
                                            <?php } ?>


                                            </tr> 
                        
                             
                                    <?php
                                        }
                                        ?>  
                                         </form>        
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
    <script src="/js/demo/datatables-demo.js"></script>
    
    <script>
        $(document).ready(function() {
        $('#example').dataTable( {
        stateSave: true
        } );
        } );
    </script>
    
</body>





<div id="myModal" class="modal">
  <span class="close">X</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script type="text/javascript">
    function showImageModal() {
  modal.style.display = "block";
  modalImg.src = this.src;
  modalImg.alt = this.alt;
  captionText.innerHTML = this.alt;
}

var modal = document.getElementById('myModal');
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
var modalCloseBtn = modal.querySelector(".close");

var images = document.querySelectorAll(".someClass");
for (let i = 0; i < images.length; i++) {
  images[i].addEventListener("click", showImageModal);
}
modalCloseBtn.addEventListener("click", function() {
  modal.style.display = "none";
});
</script>



<?php if (@$_SESSION['pass'] != $_SESSION['password2']){ 
   
    ?>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<form method="GET">
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="loginModalLabel">Input Secondary Password</h5>
           <label><?php echo @$_SESSION['logout']; ?></label> 
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                BACK HOME TO INPUT PASSWORD
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <a href="home.php" class="btn btn-primary">Back Home </a>
            </div>
          </div>
        </div>
      </div>
  </form>
<script type="text/javascript">
$(window).on('load',function(){
        $('#loginModal').modal({backdrop: 'static', keyboard: false});
});
</script>

<?php } ?>


</html>
<?php } ?>