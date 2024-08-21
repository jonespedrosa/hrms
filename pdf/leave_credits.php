<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

<!------ AUTO PRINT CODE <script>window.print();</script> -->

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
.myTable { 
  width: 100%;
  text-align: left;
  background-color: white;
  border-collapse: collapse; 
  }
.myTable th { 
  background-color: secondary;
  color: black; 
  }
.myTable td, 
.myTable th { 
  padding: 5px;
  border: 2px solid black;
    
  }
  
 
</style>


</head>


<?php  
	if(isset($_GET["ot"]) == "ot")  
	{  
	?>

<body>
	<?php 

	@$empno = $_GET['empno'];
	@$datefrom = $_GET['datefrom'];
	@$dateto = $_GET['dateto'];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()
	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Overtime Request</h5></center>		
			
			<div class="row">								
				<div class="col-12">
					<p class="text-uppercase">									
						Fullname: <b><?php echo $row['name']; ?></b> <br/>
						Employee ID :  <b><?php echo $row['empno']; ?></b>  <br/>
						Dept/Branch: <b><?php echo $row['branch']; ?></b>
					</p>
				</div>
			
							  
			</div>
		<div class="table-responsive">	
			<table class="myTable">
					<tr class="text-uppercase">
						<th><center><b>Date Overtime</b></center></th>						  
						<th><center><b>Reason</b></center></th> 
						<th><center><b>Type of OT</b></center></th>  
						<th><center><b>Status</b></center></th>
						<th><center><b>Approver</b></center></th>
						<th><center><b>Number of Hours</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>
		


  			 <?php 

                 $sql1 = "SELECT * FROM overunder where empno = $empno AND otdatefrom BETWEEN '2022-10-24' AND '2022-11-08' ORDER BY otdatefrom DESC ";
                 $query1=$HRconnect->query($sql1);
                 while($row1=$query1->fetch_array())
                                                {
						@$totalovertime += $row1['othours'];
						$otstatus = $row1['otstatus'];	
                      ?>
					<tr>
						<td><center><?php echo $row1['otdatefrom']; ?><center></td>						
						<td><center><?php echo $row1['otreason']; ?></center></td>
						<td><center>
							<?php
							$ottype1 = $row1['ottype'];
							if($ottype1 == 1){ 
							echo 'GEN MEET OT'; 
							}elseif($ottype1 == 2){ 
							echo 'GEN CLEAN OT'; 
							}else{ 
							echo 'NORMAL OT'; 
							}
							?>
						</center></td>
						<?php 
							if($otstatus == 'pending2'){
							?>
							<td><center>Partially Approved</center></td>
						<?php    
							}else{
							?>
							<td><center><?php echo $row1['otstatus']; ?></center></td>
						<?php
							}
							?>
							
						<?php 
							if($otstatus == 'pending2'){
							?>
							<td><center><?php echo $row1['p_approver']; ?></center></td>
						<?php    
							}else{
							?>
							<td><center><?php echo $row1['approver']; ?></center></td>
						<?php
							}
							?>	
						
						<td><center><?php echo $row1['othours']; ?><center></td>
					</tr>
			<?php 
				}
				?>
					
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="4"><center></center></td>
						<td class="text-right"><b>Total</b></td>
						<td><center><?php echo @$totalovertime; ?></center></td>
					</tr>
				</tfoot>
			</table>				
		</div>
		<hr>
		
	</div>
		
		
</p>
</body>

<?php  
	}
	?>
	
<?php  
	if(isset($_GET["ut"]) == "ut")  
	{  
	?>
<body>
	<?php 


	@$empno = $_GET['empno'];
	@$datefrom = $_GET['datefrom'];
	@$dateto = $_GET['dateto'];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()

	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Official Bussiness Permit</h5></center>		
			
			<div class="row">								
				<div class="col-12">
					<p class="text-uppercase">									
						Fullname: <b><?php echo $row['name']; ?></b> <br/>
						Employee ID :  <b><?php echo $row['empno']; ?></b>  <br/>
						Dept/Branch: <b><?php echo $row['branch']; ?></b>
					</p>
				</div>
			
							  
			</div>		
		<div class="table-responsive">	
			<table class="myTable">					
				<thead>									
					<tr class="text-uppercase">
						<th><center><b>Date</b></center></th>
						<th><center><b>Time In</b></center></th>
						<th><center><b>Break Out</b></center></th>  
						<th><center><b>Break In</b></center></th>
						<th><center><b>Time Out</b></center></th>
						<th><center><b>Status</b></center></th>
						<th><center><b>Approver</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>

		
				<?php 
					
                $sql2 = "SELECT * FROM obp WHERE empno = '$empno' AND datefromto BETWEEN '2022-10-24' AND '2022-11-08' ORDER BY datefromto DESC ";
                $query2=$HRconnect->query($sql2);
                while($row2=$query2->fetch_array())
                {
				$status = $row2['status'];	
                ?>
					<tr>
						<td><center><?php echo $row2['datefromto']; ?><center></td>
						<td><center><?php echo $row2['timein']; ?><center></td>
						<td><center><?php echo $row2['breakout']; ?></center></td>
						<td><center><?php echo $row2['breakin']; ?></center></td>
						<td><center><?php echo $row2['timeout']; ?></center></td>
						<?php 
							if($status == 'Pending2'){
							?>
							<td><center>Partially Approved</center></td>
						<?php    
							}else{
							?>
							<td><center><?php echo $row2['status']; ?></center></td>
						<?php
							}
							?>

						<?php 
							if($status == 'Pending2'){
							?>
							<td><center><?php echo $row2['p_approval']; ?></center></td>
						<?php    
							}else{
							?>
							<td><center><?php echo $row2['approval']; ?></center></td>
						<?php
							}
							?>								
					</tr>
			<?php 
				}
				?>
					
				</tbody>
			</table>
			<br>				
		</div>
		<hr>
		
		
		
	</div>
	
</p>
</body>


<?php  
}


	if(isset($_GET["leave"]) == "leave")  
	{  
	?>
	
<body>

	<?php 


	@$empno = $_GET['empno'];
	@$datefrom = $_GET['datefrom'];
	@$dateto = $_GET['dateto'];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()

	?>

<p style="page-break-before: always">

	<div class="col-12">
			<center><h5><small>Human Resource Department</small><br>Filed Leave</h5></center>		
			
			<div class="row">								
				<div class="col-12">
					<p class="text-uppercase">									
						Fullname: <b><?php echo $row['name']; ?></b> <br/>
						Employee ID :  <b><?php echo $row['empno']; ?></b>  <br/>
						Dept/Branch: <b><?php echo $row['branch']; ?></b>
					</p> 
				</div>
			
							  
			</div>
		<div class="table-responsive">	
			<table class="myTable">
				<thead>									
					<tr class="text-uppercase">
						<th><center><b>Date</b></center></th>
						<th><center><b>Reason/Purpose</b></center></th>  
						<th><center><b>Status</b></center></th>
						<th><center><b>Approver</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>
			<?php 
				$datefrom = $_GET['datefrom'];
				$dateto = $_GET['dateto'];

                 $sql1 = "SELECT * FROM vlform 
                    		WHERE empno = $empno AND vlstatus = 'approved' AND vldatefrom BETWEEN '$datefrom' AND '$dateto' ORDER BY `vlform`.`vldatefrom` DESC";
                 $query1=$HRconnect->query($sql1);
                 while($row1=$query1->fetch_array())
                 {
               		  ?>
						<tr>
							<td><center><?php echo $row1['vldatefrom']; ?><center></td>
							<td><center><?php echo $row1['vlreason']; ?></center></td>
							<td><center><?php echo $row1['vlstatus']; ?></center></td>
							<td><center><?php echo $row1['approver']; ?></center></td>
						</tr>
					<?php 
				}
					?>
				</tbody>
			</table>	
		</div>
		<hr>
		
		</div>
	</div>	
</p>
</body>

<?php
	}
	?>	

<?php if(@$_GET['m'] == 1){ ?>              
	<script>
		$(function() {
	  $(".thanks").delay(2500).fadeOut();
	  
	});
	</script>

	<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
		<div class="thanks toast fade show" style="position: fixed; bottom: 20px; right: 5px;">
			<div class="toast-header bg-success">
			  <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
			 <small class="text-light">just now</small>
			</div>
			<div class="toast-body">
			  You have <b class="text-success">Successfully</b> file your overtime. Thank you!
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
		<div class="thanks toast fade show" style="position: fixed; bottom: 20px; right: 5px;">
			<div class="toast-header bg-success">	
				<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> OBP</h5>
			 <small class="text-light">just now</small>
			 
			</div>
			<div class="toast-body">
			  You have <b class="text-success">Successfully</b> file your official bussiness permit. Thank you!
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
		<div class="thanks toast fade show" style="position: fixed; bottom: 20px; right: 5px;">
			<div class="toast-header bg-success">	
				<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Leave</h5>
			 <small class="text-light">just now</small>
			 
			</div>
			<div class="toast-body">
			  You have <b class="text-success">Successfully</b> file your leave. Thank you!
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

	<!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
</html>