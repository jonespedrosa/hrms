<!-- Begin Page Content --> <!-- Search -->
<?php  
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if(isset($_POST["submit"]) == "submit") {
    @$_SESSION['datedate1'] = date('Y-m-d', strtotime($_POST['datefrom']));
    @$_SESSION['datedate2'] = date('Y-m-d', strtotime($_POST['dateto']));
    
	}

	@$backfrom = $_SESSION['datedate1'];
	@$backto = $_SESSION['datedate2'];
	
	$mindate = '2023-02-09';
    $maxdate = '2023-02-23';

	?>

<!DOCTYPE html>
<html lang="en">
<head>


	<meta charset="utf-8">
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

	if((isset($_GET['cs']) == 'cs'))  
	{  
		// HOOKS
		$SelectingCS = "SELECT ui.name, ui.position, ui.department, ui.company, ui.branch, cs.* FROM change_schedule cs 
		LEFT JOIN user_info ui
		ON cs.empno = ui.empno
		WHERE cs.empno = ".$_GET['empno'].";";
		$QuerySelect = $HRconnect->query($SelectingCS);
		$row = $QuerySelect->fetch_array();

		if(!is_null($row)){
			// variables		
			$empno = $row["empno"];
			$name = $row["name"];
			$branch = $row["branch"];

			// cutoff
			$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
			ON si.empno = ui.empno
			WHERE si.status = 'Pending' AND ui.empno = $empno;";
			$querybuilder=$HRconnect->query($getDateSQL);
			$rowCutOff=$querybuilder->fetch_array();

			$cutfrom = $rowCutOff['datefrom'];
			$cutto = $rowCutOff['dateto'];
			if(isset($_GET['dateto']) || isset($_GET['datefrom'])){
				$datefrom = $_GET['datefrom'];
				$dateto= $_GET['dateto'];
			}else{
				$datefrom = $_GET['cutfrom'];
				$dateto= $_GET['cutto'];
			}
		
			// MODAL FOR SUCCESS;
			if(isset($_GET['success'])){
				echo '
				<script>
					$(function() {
						$(".thanks").delay(2500).fadeOut();
				
					});
				</script>
				<div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
					<div class="thanks toast fade show" style="position: fixed; right: 5px;">
						<div class="toast-header bg-success">
							<h5 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change Schedule</h5>
							<small class="text-light">just now</small>
						</div>
						<div class="toast-body">
							You have <b class="text-success">Successfully Filed</b> your Change Schedule Request Thank you!
						</div>
					</div>
				</div>';
			}
	?>
    
<body>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Change Schedule Request</h5></center>		
		
			<div class="row">				
				<div class="col-auto d-flex">
					<form>
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePickCS" class="form-control text-center" name="datefrom"  value="<?php echo (isset($_GET['datefrom']))? $_GET['datefrom']:"";?>"  placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div> 
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePickerCS2" class="form-control text-center" value="<?php echo(isset($_GET['dateto']))?$_GET['dateto']:""; ?>" name="dateto" placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />
							</div>

							<!-- data to be passed -->
							<input type="text" class="d-none" id="empno" name="empno" value="<?php echo $empno?>" required>
							<input type="text" class="d-none" id="cs" name="cs" value="cs" required>			

							<!-- Date -->
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_change_sched.php?cs=cs&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">									
									</div> &nbsp
								</div>
							</div>										
						</div>
					</form>	
					<div class="col-auto text-center d-none d-sm-inline-block">
						<label class="invisible">.</label>
						<div class="col-xs-6">
							<form method="POST" action="print_change_sched.php?cs=cs&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
								<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear">
							</form>
						</div>
					</div>		
				</div>
			</div>
			
			<div class="table-responsive">	
				<table class="myTable">
					<thead>
						<tr class="text-uppercase">
							<th colspan="7"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $cutfrom; ?> - <?php echo $cutto; ?></b>
							<br> <?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?></b>  
							</th>						  	
						</tr>
										
					</thead>	
					<thead>
						<tr class="text-uppercase">
							<th><center><b>Scheduled Date</b></center></th>						  
							<th><center><b>Reason</b></center></th> 
							<th><center><b>Break(s)</b></center></th>
							<th><center><b>Schedule From</b></center></th>  
							<th><center><b>Schedule To</b></center></th>
							<th><center><b>Status</b></center></th>	
							<th><center><b>Approver</b></center></th>

						</tr>
										
					</thead>			
					
					<tbody>
				
				
				<?php 
					$DisplayTable= "SELECT ui.name, ui.position, ui.department, ui.company, ui.branch, cs.* FROM change_schedule cs 
					JOIN user_info ui
					ON cs.empno = ui.empno
					WHERE cs.empno = ".$_GET['empno']."
					AND cs.datefrom BETWEEN '".$datefrom."' AND '".$dateto."' ORDER BY cs.datefrom DESC;";
					$result = mysqli_query($HRconnect, $DisplayTable);		
					$details = mysqli_fetch_all($result, MYSQLI_ASSOC);
					$counter = 0;
					while($counter < count($details))
							{
								$cs_status = $details[$counter]['cs_status'];	
						?>
						
						<tr>
							<td><center><?php echo $details[$counter]['datefrom']; ?><center></td>						
							<td><center><?php echo $details[$counter]['cs_reason']; ?></center></td>
							<td><center><?php echo $details[$counter]['cs_break']; ?></center></td>
							<td><center><?php echo $details[$counter]['cs_schedfrom']; ?><center></td>
							<td><center><?php echo $details[$counter]['cs_schedto']; ?><center></td>
							<?php 
								if($cs_status == 'pending2'){
								?>
								<td><center>Partially Approved</center></td>
							<?php    
								}else{
								?>
								<td><center><?php echo ucwords($details[$counter]['cs_status']); ?></center></td>
							<?php
								}
								?>
								
							<?php 
								// echo $details[$counter]['p_approver'];
								if($cs_status == 'pending2'){
								?>
								<td><center><?php echo $details[$counter]['p_approver']; ?></center></td>
							<?php    
								}else{
								?>
								<td><center><?php echo $details[$counter]['approver']; ?></center></td>
							<?php
								}
								?>	
						</tr>
				<?php 
						$counter+=1;
					}
					?>	
						
					</tbody>
					
					<tfoot>
						<tr>
							<td colspan="7"><center></center></td>
						</tr>
					</tfoot>
				</table>				
			</div>
		<hr>
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
			regard may form ground for disciplinary action up to and including dismissal.</i></p></center>
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../create-change-sched.php?cs=cs&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
	</div>
		
		
</p>
</body>

<?php 
		}else{
			echo "<script>
				alert('You have no Change Schedule Request present at this moment. Please file if you have one.');
				window.location.replace('../create-change-sched.php?cs=cs&empno=".$_GET['empno']."&cutfrom=".$_GET['cutfrom']."&cutto=".$_GET['cutto']."');
			</script>";
		}
	}
?>
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