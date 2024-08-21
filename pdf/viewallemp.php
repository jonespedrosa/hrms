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
			text-align: center;
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
                        <h1 class="h3 mb-0 text-gray-800 d-sm-inline-block mb-3">Mary Grace Foods Inc. Employee's</h1>													
					
					<!--<span>
							<select class="custom-select">
								<option>Sort By</option>
								<option>Active</option>
								<option>Inactive</option>
								<option>Resigned</option>
								<option>Pin Code</option>
							</select>
						</span> -->	
					</div> 

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary"></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-uppercase" id="example" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
									<!--	<th><center>Approval Date</center></th> -->
											<th><center>ID</center></th>
											<th><center>Fullname</center></th>
											<th><center>Branch</center></th>
											<th><center>Department</center></th>	
											<th><center>Position</center></th>
											<th><center>Company</center></th>
											<th><center>Wellness Leave</center></th>	
										</tr>
                                    </thead>
									
                                    <tbody class="text-center">
                                        <?php	
											$sql = "SELECT * FROM user_info WHERE approval = 'approve' and status != 'resigned' 
											ORDER BY empno DESC";

											$query=$HRconnect->query($sql);
											while($row=$query->fetch_array())										
											{
											$name = $row['name'];
											$department = $row['department'];											
											?>
										
											<tr>
												<td><?php echo $row['empno']; ?></td>
												<td><?php echo html_entity_decode(htmlentities($name)); ?></td>											
												<td><?php echo $row['branch']; ?></td>
												<td><?php echo $row['department']; ?></td>																				
												<td><?php echo $row['position']; ?></td>
												<td><?php echo $row['company']; ?></td>
												<td><?php echo $row['vl']; ?></td>
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
								<a href="../home.php"> <button class="btn btn-primary">
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
	
	<script type="text/javascript">
         function printPage() {

            var body = document.getElementById('body').innerHTML;
            var data = document.getElementById('data').innerHTML;
            document.getElementById('body').innerHTML = data;
            window.print();
            document.getElementById('body').innerHTML = body;
         }
    </script>
	
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