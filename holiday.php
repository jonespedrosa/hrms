
<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
  include("Function/global_timestamp.php");
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

Header('Location: leave.php?pending=pending');

}

if($userlevel != 'staff') {

    $a=array(date("Y-m-30")=>date("Y-m-30"),date("Y-m-31")=>date("Y-m-31"),date("Y-m-01")=>date("Y-m-01"),date("Y-m-02")=>date("Y-m-02"),date("Y-m-03")=>date("Y-m-03"),date("Y-m-04")=>date("Y-m-04"),date("Y-m-05")=>date("Y-m-05"),date("Y-m-06")=>date("Y-m-06"),date("Y-m-07")=>date("Y-m-07"),date("Y-m-08")=>date("Y-m-08"),date("Y-m-09")=>date("Y-m-09"),date("Y-m-10")=>date("Y-m-10"),date("Y-m-11")=>date("Y-m-11"),date("Y-m-12")=>date("Y-m-12"),date("Y-m-13")=>date("Y-m-13"),date("Y-m-14")=>date("Y-m-14"));

if (array_key_exists(date("Y-m-d"),$a))
  {
    $newdate1 = date("Y-m-24", strtotime("-1 months"));
    $newdate2 = date("Y-m-08");
  }
else
  {
   $newdate1 = date("Y-m-09");
   $newdate2 = date("Y-m-23");
  }
  
  
  if(isset($_POST['submit'])){ 

$holidate = $_POST["holidate"];
$type = $_POST["type"];
$prior1 = $_POST["prior1"];
$prior2 = $_POST["prior2"];
$prior3 = $_POST["prior3"];


$employee_number = $_SESSION['empno'];
$sql3 = "INSERT INTO holiday (holiday_day, type, prior1, prior2, prior3, created_at,created_by_id) 
         VALUES('$holidate', '$type', '$prior1', '$prior2', '$prior3','$timestamp','$employee_number')";
$HRconnect->query($sql3); 


header("location:holiday.php?");


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
    </style>

</head>

<body id="page-top" class="sidebar-toggled">

	<?php include("navigation.php"); ?>
<!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Holidays</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>
						
						<?php if($userlevel == 'master'){ ?>
							<div class="btn-group mb-2">
								<a href="#" type="button" class="btn border-0 btn-sm btn-outline-primary" data-toggle="modal" data-target="#exampleModal">
									<span><i class="fas fa-calendar-plus"></i></span>                   
									&nbsp <span class="text"> Add Holiday</span>
								</a>							
							</div>
						<?php } ?>
					
					</div>					
		
					<!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary "></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-uppercase" id="example1" width="100%" cellspacing="0">
                                                                        
                                    <thead>
                                        <tr class="bg-gray-200">                                                                                                                                                    
                                            <th><center>Holiday Date</center></th>
											<th><center>Type</center></th>
                                            <th><center>Prior Dates</center></th>
                                                          
                                        </tr>
                                    </thead>
                                    
                                    <tbody> 

                                        <?php 
                                        $select_yearly_leave = "SELECT base_year, current_year FROM `hrms`.`holiday_yearly_leave`";
                                        $query_yearly_leave = $HRconnect->query($select_yearly_leave);
                                        $yearly_leave = $query_yearly_leave->fetch_array();

                                        if(date('Ymd', strtotime($timestamp)) > date('Ymd', strtotime($yearly_leave['current_year']))){
                                          $increment_current_year = date('Y-m-d', strtotime('+1 year', strtotime($yearly_leave['current_year'])));
                                          $base_year = date('Ymd', strtotime($timestamp));
                                          echo $base_year;

                                          $update_holiday_yearly_leave = "UPDATE `hrms`.`holiday_yearly_leave` SET `base_year` = '".$base_year."', `current_year` = '".$increment_current_year."';";
                                          $result = mysqli_query($HRconnect, $update_holiday_yearly_leave);
                                        }
                                        // requeue
                                        $select_yearly_leave = "SELECT base_year, current_year FROM `hrms`.`holiday_yearly_leave`";
                                        $query_yearly_leave = $HRconnect->query($select_yearly_leave);
                                        $yearly_leave = $query_yearly_leave->fetch_array();
                                        $sql = "SELECT * FROM holiday WHERE holiday_day BETWEEN '".$yearly_leave['base_year']."' and '".$yearly_leave['current_year']."' ORDER BY holiday_day DESC ";
                                                $query=$HRconnect->query($sql);
                                                while($row=$query->fetch_array())
                                                {
                                        

                                            ?>
                                            <tr>   
                                                <td><center><?php echo $row['holiday_day']; ?></center></td>
												<?php if($row['type'] == 0 )  { ?>
												<td><center>Legal Holiday</center></td>
												<?php }else { ?>
													<td><center>Special Holiday</center></td>
												<?php } ?>
                                                <td><center><?php echo $row['prior1']; ?> - <?php echo $row['prior3']; ?>	
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
    
	
	<!-- Create Holiday Modal-->
    <form class="user" name="add_name" id="add_name" method="post">

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
		  <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-calendar-plus fa-fw"></i> Create Holidays</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		  </div>
		  
		   
		  <div class="modal-body">
			
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Holiday Type:</label>
				<select class="form-control" name="type" >
					<option>Choose..</option>
					<option value="0">Legal Holiday</option>
					<option value="1">Special Holiday</option>
				</select>
			</div>
			
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Holiday Date:</label>
				<input type="date" class="form-control text-center" name="holidate" 
                required onkeypress="return false;" autocomplete="off" />
			</div>
		  
			<div class="form-group">
				<label for="recipient-name" class="col-form-label">Date Priors:</label>				
				<input type="date" class="form-control text-center mb-3" name="prior1" 
                onkeypress="return false;" autocomplete="off" />
				
				<input type="date" class="form-control text-center mb-3" name="prior2" 
                onkeypress="return false;" autocomplete="off" />
				
				<input type="date" class="form-control text-center mb-3" name="prior3" 
                onkeypress="return false;" autocomplete="off" />
				
			</div>       

	</form>
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
			<button type="submit" name="submit" id="submit" class="btn btn-success bg-gradient-success" onclick="return confirm('Are you sure you want to submit this Holiday form?');">Create</button>

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
        $('#example1').dataTable( {
        stateSave: true
        } );
        } );
    </script>
	
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
		  filename: 'Approved Leave',
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