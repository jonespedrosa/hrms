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

	if((isset($_GET['wdo']) == 'wdo'))  
	{  
		// HOOKS
		$SelectingWDO = "SELECT ui.name, ui.position, ui.department, ui.company, ui.branch, wdo.* FROM working_dayoff wdo 
		LEFT JOIN user_info ui
		ON wdo.empno = ui.empno
		WHERE wdo.empno = ".$_GET['empno'].";";
		$QuerySelect = $HRconnect->query($SelectingWDO);
		$row = $QuerySelect->fetch_array();

		if(!is_null($row)){
		// variables		
		$empno = $row["empno"];
		$name = $row["name"];
		$branch = $row["branch"];
	
		// captured time inputs and hour/details
		$dateOT = $row["datefrom"];
		$wdoReason = $row["wdo_reason"];
		$wdoStatus = $row["wdostatus"];
		$workingTimeIn = $row["working_timein"];
		$workingTimeOut = $row["working_timeout"];
		$workingBreakIn = $row["working_breakin"];
		$workingBreakOut = $row["working_breakout"];
		$workingHours = $row["working_hours"];

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
						<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> WDO Request</h5>
						<small class="text-light">just now</small>
					</div>
					<div class="toast-body">
						You have <b class="text-success">Successfully Filed</b> your Working Day Off Thank you!
					</div>
				</div>
			</div>';
		}
	?>
    
<body>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Working Day Off Request</h5></center>		
		
			<div class="row">				
				<div class="col-auto d-flex">
					<form>
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePickeWDO" class="form-control text-center" name="datefrom"  value="<?php echo (isset($_GET['datefrom']))? $_GET['datefrom']:"";?>"  placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div> 
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePickerWDO1" class="form-control text-center" value="<?php echo(isset($_GET['dateto']))?$_GET['dateto']:""; ?>" name="dateto" placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />
							</div>

							<!-- data to be passed -->
							<input type="text" class="d-none" id="empno" name="empno" value="<?php echo $empno?>" required>
							<input type="text" class="d-none" id="wdo" name="wdo" value="wdo" required>			

							<!-- Date -->
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_ot.php?wdo=wdo&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">									
									</div> &nbsp
								</div>
							</div>										
						</div>
					</form>	
					<div class="col-auto text-center d-none d-sm-inline-block">
						<label class="invisible">.</label>
						<div class="col-xs-6">
							<form method="POST" action="print_ot.php?wdo=wdo&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
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
							<th colspan="6"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $cutfrom; ?> - <?php echo $cutto; ?></b>
							<br> <?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?></b>  
							</th>						  	
						</tr>
										
					</thead>	
					<thead>
						<tr class="text-uppercase">
							<th><center><b>Date Overtime</b></center></th>						  
							<th><center><b>Reason</b></center></th> 
							<th><center><b>Workday</b></center></th>  
							<th><center><b>Status</b></center></th>
							<th><center><b>Approver</b></center></th>
							<th><center><b>Number of Hours</b></center></th>	
						</tr>
										
					</thead>			
					
					<tbody>
				
				
				<?php 

					$DisplayTable= "SELECT ui.name, ui.position, ui.department, ui.company, ui.branch, wdo.* FROM working_dayoff wdo 
					JOIN user_info ui
					ON wdo.empno = ui.empno
					WHERE wdo.empno = ".$_GET['empno']."
					AND wdo.datefrom BETWEEN '".$datefrom."' AND '".$dateto."' ORDER BY wdo.datefrom DESC;";
					$result = mysqli_query($HRconnect, $DisplayTable);		
					$details = mysqli_fetch_all($result, MYSQLI_ASSOC);
					$counter = 0;
					while($counter < count($details))
							{
								@$totalovertime += (int)$details[$counter]['working_hours'];
								$otstatus = $details[$counter]['wdostatus'];	
						?>
						
						<tr>
							<td><center><?php echo $details[$counter]['datefrom']; ?><center></td>						
							<td><center><?php echo $details[$counter]['wdo_reason']; ?></center></td>
							<td><center><?php echo "Working Day Off"; ?></center></td>
							<?php 
								if($otstatus == 'pending2'){
								?>
								<td><center>Partially Approved</center></td>
							<?php    
								}else{
								?>
								<td><center><?php echo ucwords($details[$counter]['wdostatus']); ?></center></td>
							<?php
								}
								?>
								
							<?php 
								// echo $details[$counter]['p_approver'];
								if($otstatus == 'pending2'){
								?>
								<td><center><?php echo $details[$counter]['p_approver']; ?></center></td>
							<?php    
								}else{
								?>
								<td><center><?php echo $details[$counter]['approver']; ?></center></td>
							<?php
								}
								?>	
							
							<td><center><?php echo $details[$counter]['working_hours']; ?><center></td>
						</tr>
				<?php 
						$counter+=1;
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
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
			regard may form ground for disciplinary action up to and including dismissal.</i></p></center>
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../createovertime.php?wdo=wdo&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
	</div>
		
		
</p>
</body>

<?php 
		}else{
			echo "<script>
				alert('You have no Working Day Off present at this moment. Please file WDO if you have one.');
				window.location.replace('../createovertime.php?wdo=wdo&empno=".$_GET['empno']."&cutfrom=".$_GET['cutfrom']."&cutto=".$_GET['cutto']."');
			</script>";
		}
	}
	?>
<?php  
	if(isset($_GET["ot"]) == "ot")  
	{  
	?>

<body>
	<?php 

	@$empno = $_GET['empno'];
	@$cutfrom = $_GET["cutfrom"];
	@$cutto = $_GET["cutto"];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()
	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Overtime Request</h5></center>		
		
			<div class="row">				
				<div class="col-auto">
					<form method="POST">
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div>
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" placeholder="Insert Date" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
							</div>
																
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_ot.php?otb=otb&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">									
									</div> &nbsp
									
									<div class="col-xs-6">
										<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear" formaction="print_ot.php?ot=ot&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
									</div>
								</div>
							</div>										
						</div>
					</form>
				</div>
			</div>
			
			<div class="table-responsive">	
				<table class="myTable">
					<thead>
						<tr class="text-uppercase">
							<th colspan="6"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $cutfrom; ?> - <?php echo $cutto; ?></b>
							<br> <?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?></b>  
							</th>						  	
						</tr>
										
					</thead>	
					<thead>
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
					 $sql1 = "SELECT * FROM overunder where empno = $empno AND otdatefrom BETWEEN '$cutfrom' AND '$cutto' ORDER BY otdatefrom DESC ";
					 $query1=$HRconnect->query($sql1);
					 while($row1=$query1->fetch_array())
							{
								@$totalovertime += (int)$row1['othours'];
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
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
			regard may form ground for disciplinary action up to and including dismissal.</i></p></center>
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../createovertime.php?ot=ot&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
	</div>
		
		
</p>
</body>

<?php  
	}
	?>
	
<?php  
	if(isset($_GET["otb"]) == "otb")  
	{  
	?>

<body>
	<?php 

	@$empno = $_GET['empno'];
	@$cutfrom = $_GET["cutfrom"];
	@$cutto = $_GET["cutto"];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()
	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Overtime Request</h5></center>		
		
			<div class="row">				
				<div class="col-12">
					<form method="POST">
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div>
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
							</div>
																
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_ot.php?otb=otb&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">										
									</div> &nbsp
									<div class="col-xs-6">
										<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear" formaction="print_ot.php?ot=ot&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
									</div>
								</div>
							</div>										
						</div>
					</form>
				</div>
			</div>
		<div class="table-responsive">	
			<table class="myTable">
				<thead>
					<tr class="text-uppercase">
						<th colspan="6"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $backfrom; ?> - <?php echo $backto; ?></b>
						<br> </b><?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?>  
						</th>						  	
					</tr>
									
				</thead>
				
				<thead>
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

                 $sql1 = "SELECT * FROM overunder where empno = $empno AND otdatefrom BETWEEN '$backfrom' AND '$backto' ORDER BY otdatefrom DESC ";
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
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
			regard may form ground for disciplinary action up to and including dismissal.</i></p></center>
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../createovertime.php?ot=ot&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
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
	@$cutfrom = $_GET["cutfrom"];
	@$cutto = $_GET["cutto"];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()

	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Official Bussiness Permit</h5></center>		
			
			<div class="row">								
				<div class="col-12">
					<form method="POST">
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div>
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
							</div>
																
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_ot.php?utb=utb&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">										
									</div> &nbsp
									<div class="col-xs-6">
										<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear" formaction="print_ot.php?ut=ut&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
									</div>
								</div>
							</div>										
						</div>
					</form>
				</div>		  
			</div>		
		<div class="table-responsive">	
			<table class="myTable">					
				<thead>
					<tr class="text-uppercase">
						<th colspan="7"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $cutfrom; ?> - <?php echo $cutto; ?></b>
						<br> </b><?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?>  
						</th>						  	
					</tr>
									
				</thead>
				
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
					
                $sql2 = "SELECT * FROM obp WHERE empno = '$empno' AND datefromto BETWEEN '$cutfrom' AND '$cutto' ORDER BY datefromto DESC ";
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
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p></center>	
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../createovertime.php?ut=ut&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
	</div>
	
</p>
</body>

<?php  
	}
	?>
	
<?php  
	if(isset($_GET["utb"]) == "utb")  
	{  
	?>
<body>
	<?php 


	@$empno = $_GET['empno'];
	@$cutfrom = $_GET["cutfrom"];
	@$cutto = $_GET["cutto"];

    $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
    $query=$HRconnect->query($sql);
    $row=$query->fetch_array()

	?>

<p style="page-break-before: always">

	<div class="col-12">
		<center><h5><small>Human Resource Department</small><br>Official Bussiness Permit</h5></center>		
			
			<div class="row">								
				<div class="col-12">
					<form method="POST">
						<div class="form-group row">                                

							<div class="col-auto text-center">
								<label>Date From</label>
								<input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" />                                                                                                              
							</div>
																
							<div class="col-auto text-center">
								<label>Date To</label>
								<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" value="<?php echo @$backto; ?>" autocomplete="off" onkeypress="return false;" />
							</div>
																
							<div class="col-auto text-center d-none d-sm-inline-block">
								<label class="invisible">.</label>
								<div class="form-group row">
									<div class="col-xs-6 ml-2">
										<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="print_ot.php?utb=utb&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">										
									</div> &nbsp
									<div class="col-xs-6">
										<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear" formaction="print_ot.php?ut=ut&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>">
									</div>
								</div>
							</div>										
						</div>
					</form>
				</div>		  
			</div>		
		<div class="table-responsive">	
			<table class="myTable">					
				<thead>
					<tr class="text-uppercase">
						<th colspan="7"><b><?php echo $row['name']; ?> <b class="float-right"><?php echo $backfrom; ?> - <?php echo $backto; ?></b>
						<br> </b><?php echo $row['empno']; ?></b> <b class="float-right"><?php echo $row['branch']; ?>  
						</th>						  	
					</tr>
									
				</thead>
				
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
					
                $sql2 = "SELECT * FROM obp WHERE empno = '$empno' AND datefromto BETWEEN '$backfrom' AND '$backto' ORDER BY datefromto DESC ";
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
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p></center>	
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../createovertime.php?ut=ut&empno=<?php echo $row['empno']; ?>&cutfrom=<?php echo $cutfrom; ?>&cutto=<?php echo $cutto; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
		</div>
	</div>
	
</p>
</body>

<?php  
	}
	?>	


<?php
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
						<th><center><b>Consumed Leave Credit(s)</b></center></th>
						<th><center><b>Duration</b></center></th>
						<th><center><b>Status</b></center></th>
						<th><center><b>Approver</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>
			<?php 


                 $sql1 = "SELECT * FROM vlform
                    		WHERE empno = $empno and vldatefrom between '2023-12-24' AND '2024-12-23' ORDER BY `vlform`.`vldatefrom` DESC";
                 $query1=$HRconnect->query($sql1);
                 while($row1=$query1->fetch_array())
                                                {

                                                	?>
					<tr>
						<td><center><?php echo $row1['vldatefrom']; ?><center></td>
						<td><center><?php echo $row1['vlreason']; ?></center></td>
						<td><center><?php echo $row1['vlhours']; ?></center></td>
						<td><center><?php echo $row1['vlduration']; ?></center></td>
						<td><center><?php echo ucfirst($row1['vlstatus']); ?></center></td>
						<td><center><?php echo $row1['approver']; ?></center></td>
					</tr>
			<?php 
				}
					?>
				</tbody>
			</table>	
		</div>
		<hr>
		<center><p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
			regard may form ground for disciplinary action up to and including dismissal.</i></p></center>
		
		<div class="d-sm-flex align-items-center justify-content-between mb-4">													
			<div class="text-center">          
				<a href="../create-leave.php?leave=leave&empno=<?php echo $row['empno']; ?>"> <button class="btn btn-primary btn-block">
				Back</button></a>
			</div>	
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
                 <span>Copyright © Mary Grace Foods Inc. 2019.</span>
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