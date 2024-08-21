<?php 
 $connect = mysqli_connect("localhost", "root", "", "db");
 $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 ?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mary Grace Foods Inc. - Approved Employee's </title>
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


	<style type="text/css">


	 @page {size:portrait}  
	body {
		page-break-before: avoid;
	   
	   
		 }


	  @media print {


		.table td {
		  background-color: transparent !important;
		}

			.table th {
		  background-color: transparent !important;
		}
	  }
</style>
	
</head>

<body id="body">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
				<br>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800 d-sm-inline-block mb-2">Recently Approved Employee's</h1>						
					</div>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered text-uppercase" id="example" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>																																																 
											<th><center>Approval Date</center></th> 
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Department</center></th>	
											<th><center>Position</center></th>
											<th><center>Company</center></th>	
										</tr>
                                    </thead>
									
                                    <tbody class="text-center">
                                        <?php
											$from = date("Y-m-01" ,strtotime("-1 month", strtotime(date("Y/m/d"))));
                                            $to = date("Y-m-d");	
											$sql = "SELECT * FROM user_info where approval = 'approve' and datehired between '$from' and '$to'
											ORDER BY empno ASC";

											$query=$HRconnect->query($sql);
											while($row=$query->fetch_array())										
											{
											$name = $row['name'];
											$department = $row['department'];	
											?>
										
											<tr>
												<td><?php echo $row['timedate']; ?></td>
												<td><?php echo $row['empno']; ?></td>
												<td><?php echo html_entity_decode(htmlentities($name)); ?></td>
											<?php if($department = null){ ?>
												<td><?php echo $row['branch']; ?></td>
											<?php }else{ ?>
												<td><?php echo $row['department']; ?></td>
											<?php } ?>																						
												<td><?php echo $row['position']; ?></td>
												<td><?php echo $row['company']; ?></td>
											</tr>
										<?php
											}
											?>		
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
					
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
												
							<div class="text-center">          
								<a href="../recentlyemployee.php?ho=ho"> <button class="btn btn-primary">
									Back</button></a>
							</div>	
					</div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

          
        </div>
        <!-- End of Content Wrapper -->


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