<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (isset($_REQUEST["empNUM"])) {
	$empno = $_POST['empNUM'];
} else {
	$empno = $_GET['empno'];
}

$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si
	ON si.empno = ui.empno
	WHERE si.status = 'Pending' AND ui.empno = $empno;";
$querybuilder = $HRconnect->query($getDateSQL);
$rowCutOff = $querybuilder->fetch_array();
$datestart = $rowCutOff['datefrom'];
$dateend = $rowCutOff['dateto'];


//CANCEL PENDING DTR CONCERN
if (isset($_GET['cancel']) == 'yes') {
	$concernID = $_GET['id'];
	$empid = $_GET['empno'];
	$concernDate = $_GET['date']; // Assuming the concern date is passed as a parameter
	$selectedConcern = $_GET['selectedConcern'];

	$updatecancel = "UPDATE dtr_concerns
			SET status = 'Cancelled'
            WHERE id = '$concernID'";
	$HRconnect->query($updatecancel);

     // Determine type_concern based on selectedConcern value
	 if ($selectedConcern === "Failure/Forgot to time in or time out") {
		$type_concern = 1;
	} else if ($selectedConcern === "Failure/Forgot to break in or break out") {
		$type_concern = 2;
	} else if ($selectedConcern === "Failure/Forgot to click broken schedule") {
		$type_concern = 3;
	} else if ($selectedConcern === "Failure/Forgot to click half day") {
		$type_concern = 4;
	} else if ($selectedConcern === "Wrong filing of overtime") {
		$type_concern = 5;
	} else if ($selectedConcern === "Wrong filing of OBP") {
		$type_concern = 7;
	} else if ($selectedConcern === "Not following break out and break in interval") {
		$type_concern = 8;
	}

	// Update hear_you_out table
	$sql_update = "UPDATE hear_you_out
	SET status = 'Cancelled'
	WHERE empno = '$empno' AND date_submitted = '$concernDate' AND type_concern = '$type_concern'";
	$HRconnect->query($sql_update);

	header("location:print_concerns.php?dtr=filedconcerns&filed=&empno=$empid&c=1&cutfrom=$datestart&cutto=$dateend");
}

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
		@page {
			size: portrait
		}

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
if (isset($_GET["ot"]) == "ot") {
?>

	<body>
		<?php
		@$empno = $_GET['empno'];
		@$datefrom = $_GET['cutfrom'];
		@$dateto = $_GET['cutto'];

		$sql = "SELECT * FROM user_info WHERE empno = '$empno'";
		$query = $HRconnect->query($sql);
		$row = $query->fetch_array()


		?>

		<p style="page-break-before: always">

		<div class="col-12">
			<center>
				<h5><small>Human Resource Department</small><br>Overtime Request</h5>
			</center>

			<div class="row">
				<div class="col-12">
					<p class="text-uppercase">
						Fullname: <b><?php echo $row['name']; ?></b> <br />
						Employee ID : <b><?php echo $row['empno']; ?></b> <br />
						Dept/Branch: <b><?php echo $row['branch']; ?></b> <br />
					</p>
				</div>


			</div>
			<div class="table-responsive">
				<table class="myTable">
					<tr class="text-uppercase">
						<th>
							<center><b>Date Overtime</b></center>
						</th>
						<th>
							<center><b>Reason</b></center>
						</th>
						<th>
							<center><b>Status</b></center>
						</th>
						<th>
							<center><b>Approver</b></center>
						</th>
						<th>
							<center><b>Number of Hours</b></center>
						</th>
					</tr>

					</thead>


					<tbody>



						<?php

						$sql1 = "SELECT * FROM overunder where empno = $empno AND otdatefrom BETWEEN '$datestart' AND '$dateend' ORDER BY otdatefrom DESC ";
						$query1 = $HRconnect->query($sql1);
						while ($row1 = $query1->fetch_array()) {
							@$totalovertime += $row1['othours'];
							$otstatus = $row1['otstatus'];
						?>
							<tr>
								<td>
									<center><?php echo $row1['otdatefrom']; ?><center>
								</td>
								<td>
									<center><?php echo $row1['otreason']; ?></center>
								</td>
								<?php
								if ($otstatus == 'pending2') {
								?>
									<td>
										<center>Partially Approved</center>
									</td>
								<?php
								} else {
								?>
									<td>
										<center><?php echo $row1['otstatus']; ?></center>
									</td>
								<?php
								}
								?>

								<?php
								if ($otstatus == 'pending2') {
								?>
									<td>
										<center><?php echo $row1['p_approver']; ?></center>
									</td>
								<?php
								} else {
								?>
									<td>
										<center><?php echo $row1['approver']; ?></center>
									</td>
								<?php
								}
								?>

								<td>
									<center><?php echo $row1['othours']; ?><center>
								</td>
							</tr>
						<?php
						}
						?>

					</tbody>

					<tfoot>
						<tr>
							<td colspan="3">
								<center></center>
							</td>
							<td class="text-right"><b>Total</b></td>
							<td>
								<center><?php echo @$totalovertime; ?></center>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<hr>
			<center>
				<p class="text-muted"><i>I CERTIFY that the above information provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p>
			</center>

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<div class="text-center">
					<a href="../createovertime.php?ot=ot&empno=<?php echo $row['empno']; ?>"> <button class="btn btn-primary btn-block">
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
if (isset($_GET["ut"]) == "ut") {
?>

	<body>
		<?php


		@$empno = $_GET['empno'];
		@$datefrom = $_GET['datefrom'];
		@$dateto = $_GET['dateto'];

		$sql = "SELECT * FROM user_info WHERE empno = '$empno'";
		$query = $HRconnect->query($sql);
		$row = $query->fetch_array()

		?>

		<p style="page-break-before: always">

		<div class="col-12">
			<center>
				<h5><small>Human Resource Department</small><br>Official Bussiness Permit</h5>
			</center>

			<div class="row">
				<div class="col-12">
					<p class="text-uppercase">
						Fullname: <b><?php echo $row['name']; ?></b> <br />
						Employee ID : <b><?php echo $row['empno']; ?></b> <br />
						Dept/Branch: <b><?php echo $row['branch']; ?></b>
					</p>
				</div>


			</div>
			<div class="table-responsive">
				<table class="myTable">
					<thead>
						<tr class="text-uppercase">
							<th>
								<center><b>Date</b></center>
							</th>
							<th>
								<center><b>Time In</b></center>
							</th>
							<th>
								<center><b>Break Out</b></center>
							</th>
							<th>
								<center><b>Break In</b></center>
							</th>
							<th>
								<center><b>Time Out</b></center>
							</th>
							<th>
								<center><b>Status</b></center>
							</th>
							<th>
								<center><b>Approver</b></center>
							</th>
						</tr>

					</thead>


					<tbody>


						<?php

						$sql2 = "SELECT * FROM obp WHERE empno = '$empno' AND datefromto BETWEEN '$datestart' AND '$dateend' ORDER BY datefromto DESC";
						$query2 = $HRconnect->query($sql2);
						while ($row2 = $query2->fetch_array()) {
							$status = $row2['status'];
						?>
							<tr>
								<td>
									<center><?php echo $row2['datefromto']; ?><center>
								</td>
								<td>
									<center><?php echo $row2['timein']; ?><center>
								</td>
								<td>
									<center><?php echo $row2['breakout']; ?></center>
								</td>
								<td>
									<center><?php echo $row2['breakin']; ?></center>
								</td>
								<td>
									<center><?php echo $row2['timeout']; ?></center>
								</td>
								<?php
								if ($status == 'Pending2') {
								?>
									<td>
										<center>Partially Approved</center>
									</td>
								<?php
								} else {
								?>
									<td>
										<center><?php echo $row2['status']; ?></center>
									</td>
								<?php
								}
								?>

								<?php
								if ($status == 'Pending2') {
								?>
									<td>
										<center><?php echo $row2['p_approval']; ?></center>
									</td>
								<?php
								} else {
								?>
									<td>
										<center><?php echo $row2['approval']; ?></center>
									</td>
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
			<center>
				<p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p>
			</center>

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<div class="text-center">
					<a href="../createovertime.php?ut=ut&empno=<?php echo $row['empno']; ?>"> <button class="btn btn-primary btn-block">
							Back</button></a>
				</div>
			</div>
		</div>

		</p>
	</body>


<?php
}


if (isset($_GET["leave"]) == "leave") {
?>

	<body>

		<?php


		@$empno = $_GET['empno'];
		@$datefrom = $_GET['datefrom'];
		@$dateto = $_GET['dateto'];

		$sql = "SELECT * FROM user_info WHERE empno = '$empno'";
		$query = $HRconnect->query($sql);
		$row = $query->fetch_array()

		?>

		<p style="page-break-before: always">

		<div class="col-12">
			<center>
				<h5><small>Human Resource Department</small><br>Filed Leave</h5>
			</center>

			<div class="row">
				<div class="col-12">
					<p class="text-uppercase">
						Fullname: <b><?php echo $row['name']; ?></b> <br />
						Employee ID : <b><?php echo $row['empno']; ?></b> <br />
						Dept/Branch: <b><?php echo $row['branch']; ?></b>
					</p>
				</div>


			</div>
			<div class="table-responsive">
				<table class="myTable">
					<thead>
						<tr class="text-uppercase">
							<th>
								<center><b>Date</b></center>
							</th>
							<th>
								<center><b>Reason/Purpose</b></center>
							</th>
							<th>
								<center><b>Status</b></center>
							</th>
							<th>
								<center><b>Approver</b></center>
							</th>
						</tr>

					</thead>


					<tbody>
						<?php


						$sql1 = "SELECT * FROM vlform
                    		WHERE empno = $empno and vldatefrom between '2022-01-09' AND '2022-12-31' ORDER BY `vlform`.`vldatefrom` DESC";
						$query1 = $HRconnect->query($sql1);
						while ($row1 = $query1->fetch_array()) {

						?>
							<tr>
								<td>
									<center><?php echo $row1['vldatefrom']; ?><center>
								</td>
								<td>
									<center><?php echo $row1['vlreason']; ?></center>
								</td>
								<td>
									<center><?php echo $row1['vlstatus']; ?></center>
								</td>
								<td>
									<center><?php echo $row1['approver']; ?></center>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
			<hr>
			<center>
				<p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p>
			</center>

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<div class="text-center">
					<a href="../createovertime.php?leave=leave&empno=<?php echo $row['empno']; ?>"> <button class="btn btn-primary btn-block">
							Back</button></a>
				</div>
			</div>
		</div>
		</p>
	</body>

<?php
}
?>

<?php if (@$_GET['m'] == 1) { ?>
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

<?php if (@$_GET['m'] == 2) { ?>
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

<?php if (@$_GET['m'] == 3) { ?>
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


<?php
if (isset($_POST["dtr"]) == "concerns") {
?>

	<body>
		<?php
		//IF DTR CONCERNS WAS SUBMITTED
		//GET DATA FROM CONCERNS
		$empNUM = $_POST['empNUM'];
		$empNAME = $_POST['empNAME'];
		$cdate = $_POST['date'];
		$dtrconcern = $_POST['dtrconcern'];
		$actualIN = $_POST['actualIN'];
		$actualbrkOUT = $_POST['actualbrkOUT'];
		$actualbrkIN = $_POST['actualbrkIN'];
		$actualOUT = $_POST['actualOUT'];
		$newIN = date('H:i', strtotime($_POST['newIN']));
		$newOUT = date('H:i', strtotime($_POST['newOUT']));
		$concernReason = $_POST['concernReason'];
		$gmIN = $_POST['GenMeetIN'];
		$gmOUT = $_POST['GenMeetOUT'];
		$newbrkOUT = $_POST['newbrkOUT'];
		$newbrkIN = $_POST['newbrkIN'];
		$othours = $_POST['othours'];

		if ($newbrkOUT == 'No Break' and $newbrkIN == 'No Break') {
			$newbrkOUT = 'No Break';
			$newbrkIN = 'No Break';
		} else {
			$newbrkOUT = date('H:i', strtotime($_POST['newbrkOUT']));
			$newbrkIN = date('H:i', strtotime($_POST['newbrkIN']));
		}


		if ($gmIN == ' ' and $gmOUT == ' ' or $gmIN == ' ') {
			$gmIN = 'No Logs';
			$gmOUT = 'No Logs';
		} else if ($gmIN == '01:00' and $gmOUT == '01:00' or $gmIN == '08:00' and $gmOUT == '08:00') {
			$gmIN = 'No Logs';
			$gmOUT = 'No Logs';
		} else if ($gmIN == 'No Logs' and $gmOUT == 'No Logs') {
			$gmIN = 'No Logs';
			$gmOUT = 'No Logs';
		} else {
			$gmIN = $_POST['GenMeetIN'];
			$gmOUT = $_POST['GenMeetOUT'];
		}

		$sql8 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern in ('Forgot to click Halfday', 'Forgot/Wrong time IN/OUT or break OUT/IN', 'Wrong format/filing of OBP', 'Not following time interval', 'Sync/Network error', 'Emergency time out', 'Hardware/Persona Malfunction', 'Fingerprint problem') AND ConcernDate = '$cdate' AND status in('Approved','Pending')";
		$query8 = $HRconnect->query($sql8);
		$row8 = $query8->fetch_array();
		$echo0 = $row8['COUNT(*)'];

		//File Broken Sched OT
		$sql15 = "SELECT COUNT(*) FROM overunder where empno = '$empNUM' AND otdatefrom = '$cdate' AND ottype != '0' AND otstatus in('approved','pending','pending2')";
		$query15 = $HRconnect->query($sql15);
		$row15 = $query15->fetch_array();
		$echo1 = $row15['COUNT(*)'];

		$sql16 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern = 'Cancellation of Overtime' AND ConcernDate = '$cdate'  AND status in('Approved','Pending')";
		$query16 = $HRconnect->query($sql16);
		$row16 = $query16->fetch_array();
		$echo2 = $row16['COUNT(*)'];

		$sql17 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern = 'Cancellation of Leave' AND ConcernDate = '$cdate'  AND status in('Approved','Pending')";
		$query17 = $HRconnect->query($sql17);
		$row17 = $query17->fetch_array();
		$echo3 = $row17['COUNT(*)'];

		$sql18 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern = 'Wrong Computations' AND ConcernDate = '$cdate'  AND status in('Approved','Pending')";
		$query18 = $HRconnect->query($sql18);
		$row18 = $query18->fetch_array();
		$echo4 = $row18['COUNT(*)'];

		$sql19 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern = 'Remove Time Inputs' AND ConcernDate = '$cdate'  AND status in('Approved','Pending')";
		$query19 = $HRconnect->query($sql19);
		$row19 = $query19->fetch_array();
		$echo5 = $row19['COUNT(*)'];

		$sql20 = "SELECT COUNT(*) FROM dtr_concerns where empno = '$empNUM' AND concern = 'Forgot/Wrong inputs of broken sched' AND ConcernDate = '$cdate'  AND status in('Approved','Pending')";
		$query20 = $HRconnect->query($sql20);
		$row20 = $query20->fetch_array();
		$echo6 = $row20['COUNT(*)'];


		if ($row8['COUNT(*)'] >= 1 and $echo1 >= 1 and $echo2 >= 1 and $echo3 >= 1 and $echo4 >= 1 and $echo5 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Forgot to click Halfday' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Forgot/Wrong time IN/OUT or break OUT/IN' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Forgot/Wrong inputs of broken sched' and $echo6 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Wrong format/filing of OBP' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Not following time interval' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Sync/Network error' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Emergency time out' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Hardware/Persona Malfunction' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Fingerprint problem' and $echo0 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'File Broken Sched OT' and $echo1 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Cancellation of Overtime' and $echo2 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Cancellation of Leave' and $echo3 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Wrong Computations' and $echo4 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else	if ($dtrconcern == 'Remove Time Inputs' and $echo5 >= 1) {

			echo "<script type='text/javascript'>alert('Failed: DTR Concern is already filed or you did not select any date, please check your filed DTR concern. Thank you!');
        window.location.href='../concerns.php?dtrconcern&concern=concern&empno=$empNUM'
        </script>";
		} else {

			if ($dtrconcern == 'Cancellation of Overtime') {
				$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
				$query0 = $HRconnect->query($sql0);
				$row0 = $query0->fetch_array();
				$branch = $row0['branch'];
				$userid = $row0['userid'];
				$areatype = $row0['area_type'];
				$userlevel = $row0['userlevel'];
				$datenow = date('Y-m-d H:i:s');

				//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
				if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
					$userlevel = 'ac';
				} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
					$userlevel = 'mod';
				} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
					$userlevel = 'staff';
				} else {
					$userlevel = $row0['userlevel'];
				}

				//INSERT DATA INTO THE DATABASE
				$sql4 = "INSERT INTO dtr_concerns
    			(`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`othours`,`reason`,`status`)
    			VALUES
    			('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','User Error', '$othours','$concernReason','Pending')";
				$HRconnect->query($sql4);
			} else if ($dtrconcern == 'Wrong Computations') {
				$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
				$query0 = $HRconnect->query($sql0);
				$row0 = $query0->fetch_array();
				$branch = $row0['branch'];
				$userid = $row0['userid'];
				$areatype = $row0['area_type'];
				$userlevel = $row0['userlevel'];
				$computations = $_POST['computations'];
				$datenow = date('Y-m-d H:i:s');

				//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
				if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
					$userlevel = 'ac';
				} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
					$userlevel = 'mod';
				} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
					$userlevel = 'staff';
				} else {
					$userlevel = $row0['userlevel'];
				}

				//INSERT DATA INTO THE DATABASE
				$sql4 = "INSERT INTO dtr_concerns
    			(`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`vltype`,`reason`,`status`)
    			VALUES
    			('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','System Error', '$computations','$concernReason','Pending')";
				$HRconnect->query($sql4);
			} else if ($dtrconcern == 'Cancellation of Leave') {
				$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
				$query0 = $HRconnect->query($sql0);
				$row0 = $query0->fetch_array();
				$branch = $row0['branch'];
				$userid = $row0['userid'];
				$areatype = $row0['area_type'];
				$userlevel = $row0['userlevel'];
				$datenow = date('Y-m-d H:i:s');

				//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
				if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
					$userlevel = 'ac';
				} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
					$userlevel = 'mod';
				} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
					$userlevel = 'staff';
				} else {
					$userlevel = $row0['userlevel'];
				}

				//INSERT DATA INTO THE DATABASE
				$sql4 = "INSERT INTO dtr_concerns
    			(`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`vltype`,`reason`,`status`)
    			VALUES
    			('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','User Error', '$othours','$concernReason','Pending')";
				$HRconnect->query($sql4);
			} else if ($dtrconcern == 'File Broken Sched OT') {
				$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
				$query0 = $HRconnect->query($sql0);
				$row0 = $query0->fetch_array();
				$branch = $row0['branch'];
				$userid = $row0['userid'];
				$areatype = $row0['area_type'];
				$userlevel = $row0['userlevel'];
				$datenow = date('Y-m-d H:i:s');
				$ottype = $_POST['ottype'];

				//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
				if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
					$userlevel = 'ac';
				} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
					$userlevel = 'mod';
				} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
					$userlevel = 'staff';
				} else {
					$userlevel = $row0['userlevel'];
				}

				//INSERT DATA INTO THE DATABASE
				if ($userlevel == 'mod' || $userlevel == 'ac' || $userlevel == 'admin') {
					$sql4 = "INSERT INTO overunder
    			(`timedate`,`empno`,`otdatefrom`,`othours`,`ottype`,`otreason`,`otstatus`)
    			VALUES
    			('$datenow', '$empNUM',  '" . $cdate . "', '$othours','$ottype','$concernReason','pending2')";
					$HRconnect->query($sql4);

					header("location:print_ot.php?ot=ot&empno=$empNUM&m=1");
				} else {
					$sql4 = "INSERT INTO overunder
    			(`timedate`,`empno`,`otdatefrom`,`othours`,`ottype`,`otreason`,`otstatus`)
    			VALUES
    			('$datenow', '$empNUM',  '" . $cdate . "', '$othours','$ottype','$concernReason','pending')";
					$HRconnect->query($sql4);

					header("location:print_ot.php?ot=ot&empno=$empNUM&m=1");
				}
			} else {

				//FILE UPLOAD
				$target_dir = 'attachments/';
				$filename = basename($_FILES['attachment1']['name']);

				if ($dtrconcern == 'Remove Time Inputs' || $dtrconcern == 'Sync/Network error') {
					$filename2 = basename($_FILES['attachment1']['name']);
				} else {
					$filename2 = basename($_FILES['attachment2']['name']);
				}

				$target_file = $target_dir . $filename;
				$target_file2 = $target_dir . $filename2;
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
				$newFileName = $target_dir . md5_file($_FILES['attachment1']['tmp_name']) . "." . $imageFileType;

				if ($dtrconcern == 'Remove Time Inputs' || $dtrconcern == 'Sync/Network error') {
					$newFileName2 = $target_dir . md5_file($_FILES['attachment1']['tmp_name']) . "." . $imageFileType;
				} else {
					$newFileName2 = $target_dir . md5_file($_FILES['attachment2']['tmp_name']) . "." . $imageFileType;
				}


				// Check if image file is a actual image or fake image
				if (isset($_POST["submit"])) {
					$check = getimagesize($_FILES['attachment1']["tmp_name"]);
					if ($check !== false) {

						$uploadOk = 1;
					} else {
						$uploadOk = 0;
					}
				}


				if (move_uploaded_file($_FILES['attachment1']["tmp_name"], $newFileName)) {
					if ($dtrconcern != 'Remove Time Inputs' and $dtrconcern != 'Sync/Network error') {
						move_uploaded_file($_FILES['attachment2']["tmp_name"], $newFileName2);
					}
				} else {
					echo "<script type='text/javascript'>alert('Failed: Sorry, there was an error uploading your file.');
        </script>";
				}

				//SET TYPE OF ERROR
				if ($dtrconcern == 'Forgot to click Halfday') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Forgot/Wrong inputs of broken sched') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Forgot/Wrong time IN/OUT or break OUT/IN') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Wrong format/filing of OBP') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Not following time interval') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Remove Time Inputs') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Cancellation of Overtime') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Cancellation of Leave') {
					$errortype = 'User Error';
				} else if ($dtrconcern == 'Sync/Network error') {
					$errortype = 'System Error';
				} else if ($dtrconcern == 'Wrong Computations') {
					$errortype = 'System Error';
				} else if ($dtrconcern == 'Emergency time out') {
					$errortype = 'Other Error';
				} else if ($dtrconcern == 'Hardware/Persona Malfunction') {
					$errortype = 'Other Error';
				} else if ($dtrconcern == 'Fingerprint problem') {
					$errortype = 'Other Error';
				} else if ($dtrconcern == 'File Broken Sched OT') {
					$errortype = 'Other Error';
				}

				//QUERY EMPLOYEES INFO
				$sql3 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
				$query3 = $HRconnect->query($sql3);
				$row3 = $query3->fetch_array();
				$branch = $row3['branch'];
				$userid = $row3['userid'];
				$areatype = $row3['area_type'];
				$userlevel = $row3['userlevel'];
				$datenow = date('Y-m-d H:i:s');

				//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
				if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
					$userlevel = 'ac';
				} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
					$userlevel = 'mod';
				} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
					$userlevel = 'staff';
				} else {
					$userlevel = $row3['userlevel'];
				}

				//INSERT DATA INTO THE DATABASE
				if ($dtrconcern == 'Forgot/Wrong inputs of broken sched') {
					//QUERY EMPLOYEES INFO
					$sql3 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
					$query3 = $HRconnect->query($sql3);
					$row3 = $query3->fetch_array();
					$branch = $row3['branch'];
					$userid = $row3['userid'];
					$areatype = $row3['area_type'];
					$userlevel = $row3['userlevel'];
					$datenow = date('Y-m-d H:i:s');

					//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
					if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
						$userlevel = 'ac';
					} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
						$userlevel = 'mod';
					} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
						$userlevel = 'staff';
					} else {
						$userlevel = $row3['userlevel'];
					}

					$sqlbrk = "INSERT INTO dtr_concerns
    (`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`actualIN`,`actualbOUT`,`actualbIN`,`actualOUT`,`newIN`,`newbOUT`,`newbIN`,`newOUT`,`reason`,`attachment1`,`attachment2`,`status`)
    VALUES
    ('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','$errortype', '$gmIN', 'No Break', 'No Break', '$gmOUT', '$newIN', 'No Break', 'No Break', '$newOUT', '$concernReason','$newFileName','$newFileName2','Pending')";
					$HRconnect->query($sqlbrk);
				} else if ($dtrconcern == 'Remove Time Inputs') {
					$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
					$query0 = $HRconnect->query($sql0);
					$row0 = $query0->fetch_array();
					$branch = $row0['branch'];
					$userid = $row0['userid'];
					$areatype = $row0['area_type'];
					$userlevel = $row0['userlevel'];
					$inputs = $_POST['inputs'];
					$datenow = date('Y-m-d H:i:s');

					//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
					if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
						$userlevel = 'ac';
					} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
						$userlevel = 'mod';
					} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
						$userlevel = 'staff';
					} else {
						$userlevel = $row0['userlevel'];
					}

					//INSERT DATA INTO THE DATABASE
					$sql4 = "INSERT INTO dtr_concerns
    			(`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`vltype`,`reason`,`attachment1`,`status`)
    			VALUES
    			('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','$errortype', '$inputs','$concernReason','$newFileName','Pending')";
					$HRconnect->query($sql4);
				} else if ($dtrconcern == 'Sync/Network error') {
					$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
					$query0 = $HRconnect->query($sql0);
					$row0 = $query0->fetch_array();
					$branch = $row0['branch'];
					$userid = $row0['userid'];
					$areatype = $row0['area_type'];
					$userlevel = $row0['userlevel'];
					$datenow = date('Y-m-d H:i:s');

					//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
					if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
						$userlevel = 'ac';
					} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
						$userlevel = 'mod';
					} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
						$userlevel = 'staff';
					} else {
						$userlevel = $row0['userlevel'];
					}

					//INSERT DATA INTO THE DATABASE
					$sql4 = "INSERT INTO dtr_concerns
    		(`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`actualIN`,`actualbOUT`,`actualbIN`,`actualOUT`,`newIN`,`newbOUT`,`newbIN`,`newOUT`,`reason`,`attachment1`,`status`)
    		VALUES
    		('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','$errortype', '$actualIN', '$actualbrkOUT', '$actualbrkIN', '$actualOUT', '$newIN', '$newbrkOUT', '$newbrkIN', '$newOUT', '$concernReason','$newFileName','Pending')";
					$HRconnect->query($sql4);
				} else {
					$sql0 = "SELECT * FROM user_info WHERE empno = '$empNUM'";
					$query0 = $HRconnect->query($sql0);
					$row0 = $query0->fetch_array();
					$branch = $row0['branch'];
					$userid = $row0['userid'];
					$areatype = $row0['area_type'];
					$userlevel = $row0['userlevel'];
					$datenow = date('Y-m-d H:i:s');

					//EMPLOYEE WITH DIFFERENT USERLEVEL IN THE DATABASE
					if ($empNUM == 271 || $empNUM == 107 || $empNUM == 4625) {
						$userlevel = 'ac';
					} elseif ($empNUM == 1348 || $empNUM == 2525 || $empNUM == 1964 || $empNUM == 141) {
						$userlevel = 'mod';
					} elseif ($empNUM == 1910 || $empNUM == 3156 || $empNUM == 3612 || $empNUM == 1509 || $empNUM == 2165 || $empNUM == 1233 || $empNUM == 4072 || $empNUM == 3160 || $empNUM == 3080 || $empNUM == 4001 || $empNUM == 1053 || $empNUM == 2008 || $empNUM == 3735 || $empNUM == 4451) {
						$userlevel = 'staff';
					} else {
						$userlevel = $row0['userlevel'];
					}

					$sql4 = "INSERT INTO dtr_concerns
    (`filing_date`,`empno`,`name`,`userlevel`,`branch`,`userid`,`area`,`ConcernDate`,`concern`,`errortype`,`actualIN`,`actualbOUT`,`actualbIN`,`actualOUT`,`newIN`,`newbOUT`,`newbIN`,`newOUT`,`reason`,`attachment1`,`attachment2`,`status`)
    VALUES
    ('$datenow', '$empNUM', '$empNAME','$userlevel','$branch','$userid','$areatype', '" . $cdate . "', '$dtrconcern','$errortype', '$actualIN', '$actualbrkOUT', '$actualbrkIN', '$actualOUT', '$newIN', '$newbrkOUT', '$newbrkIN', '$newOUT', '$concernReason','$newFileName','$newFileName2','Pending')";
					$HRconnect->query($sql4);
				}
			}
		}

		?>

		<p style="page-break-before: always">

		<div class="col-12">
			<center>
				<h5><small>Human Resource Department</small><br>FILED DTR CONCERNS</h5>
			</center>

			<div class="row">
				<div class="col-12">
					<p class="text-uppercase">
						Fullname: <b><?php echo $empNAME; ?></b> <br />
						Employee ID : <b><?php echo $empNUM; ?></b> <br />
						Dept/Branch: <b><?php echo $branch; ?></b>
					</p>
				</div>


			</div>
			<div class="table-responsive">
				<table class="myTable">
					<thead>
						<tr class="text-uppercase">
							<th>
								<center><b>Date of Concern</b></center>
							</th>
							<th>
								<center><b>Concern</b></center>
							</th>
							<th>
								<center><b>Type of Error</b></center>
							</th>
							<th>
								<center><b>Time In</b></center>
							</th>
							<th>
								<center><b>Break Out</b></center>
							</th>
							<th>
								<center><b>Break In</b></center>
							</th>
							<th>
								<center><b>Time Out</b></center>
							</th>
							<th>
								<center><b>Status</b></center>
							</th>
							<th>
								<center><b>Approver</b></center>
							</th>
							<th>
								<center><b>Remarks</b></center>
							</th>
							<th>
								<center><b></b></center>
							</th>
						</tr>

					</thead>


					<tbody>


						<?php



						$sql5 = "SELECT * FROM dtr_concerns WHERE empno = '$empNUM' AND ConcernDate BETWEEN '$datestart' AND '$dateend' ORDER BY `ConcernDate` ASC";
						$query5 = $HRconnect->query($sql5);
						while ($row5 = $query5->fetch_array()) {
							$status = $row5['status'];
							$concernid = $row5['id'];
							$selectedConcern = $row5['concern'];

						?>
							<tr>
								<td>
									<center><?php echo $row5['ConcernDate']; ?><center>
								</td>
								<td>
									<center><?php echo $row5['concern']; ?><center>
								</td>
								<td>
									<center><?php echo $row5['errortype']; ?><center>
								</td>
								<td>
									<center><?php echo $row5['newIN']; ?><center>
								</td>
								<td>
									<center><?php echo $row5['newbOUT']; ?></center>
								</td>
								<td>
									<center><?php echo $row5['newbIN']; ?></center>
								</td>
								<td>
									<center><?php echo $row5['newOUT']; ?></center>
								</td>
								<td>
									<center><?php echo $row5['status']; ?></center>
								</td>
								<td>
									<center><?php echo $row5['approver']; ?></center>
								</td>
								<td>
									<center><?php echo $row5['remarks']; ?></center>
								</td>
								<?php
								if ($status == 'Pending') {
								?>
									<td>
										<center><a href="print_concerns.php?cancel=yes&id=<?php echo $concernid; ?>&empno=<?php echo $empNUM; ?>&date=<?php echo $row5['ConcernDate']; ?>&selectedConcern=<?php echo $row5['concern']; ?>" class="btn btn-info btn-user btn-block bg-gradient-info" onclick="return confirm('Are you sure you want to Cancel this concern?');">Cancel</a> </center>
									</td>
								<?php




								} else {
								?>
									<td>
										<center></center>
									</td>
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
			<center>
				<p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p>
			</center>

			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<div class="text-center">
					<a href="../index.php?empno=<?php echo $empNUM; ?>&SubmitButton=Submit"><button class="btn btn-primary btn-block">
							Back</button></a>
				</div>
			</div>
		</div>

		</p>

	<?php
}
	?>


	</body>

	<?php
	if (isset($_GET["dtr"]) == "filedconcerns") {
	?>

		<body>
			<?php
			//GET DATA FROM CONCERNS
			$empNUM = $_GET['empno'];

			//QUERY BRANCH
			$sql6 = "SELECT empno, branch, name FROM user_info WHERE empno = '$empNUM'";
			$query6 = $HRconnect->query($sql6);
			$row6 = $query6->fetch_array();
			$branch = $row6['branch'];
			$empNAME = $row6['name'];

			@$backfrom = trim($_GET['cutfrom']);
			@$backto = trim($_GET['cutto']);

			if (isset($_POST["submit"]) == "submit") {
				@$backfrom = trim(date('Y-m-d', strtotime($_POST['datefrom'])));
				@$backto = trim(date('Y-m-d', strtotime($_POST['dateto'])));
			}

			?>

			<p style="page-break-before: always">

			<div class="col-12">
				<center>
					<h5><small>Human Resource Department</small><br>FILED DTR CONCERNS</h5>
				</center>

				<div class="row">
					<div class="col-auto">
						<form method="POST">
							<div class="form-group row">

								<div class="col-auto text-center">
									<label>Date From</label>
									<input type="date" id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />
								</div>

								<div class="col-auto text-center">
									<label>Date To</label>
									<input type="date" id="#datePicker1" class="form-control text-center" name="dateto" placeholder="Insert Date" autocomplete="off" onkeypress="return false;" />
								</div>

								<div class="col-auto text-center d-none d-sm-inline-block">
									<label class="invisible">.</label>
									<div class="form-group row">
										<div class="col-xs-6 ml-2">
											<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit">
										</div> &nbsp

										<div class="col-xs-6">
											<input class="btn btn-danger btn-user btn-block bg-gradient-danger text-center" type="submit" value="Clear">
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
							<th colspan="11" class="text-uppercase">
								<div class="d-flex justify-content-between">
									<b><?php echo $empNAME ?></b>
									<b><?php echo @$backfrom . " - " . @$backto; ?></b>
								</div>
								<div class="d-flex justify-content-between">
									<p class="m-0"><?php echo $empNUM ?></p>
									<b><?php echo $branch ?></b>
								</div>
							</th>
							<tr class="text-uppercase">
								<th>
									<center><b>Date of Concern</b></center>
								</th>
								<th>
									<center><b>Concern</b></center>
								</th>
								<th>
									<center><b>Type of Error</b></center>
								</th>
								<th>
									<center><b>Time In</b></center>
								</th>
								<th>
									<center><b>Break Out</b></center>
								</th>
								<th>
									<center><b>Break In</b></center>
								</th>
								<th>
									<center><b>Time Out</b></center>
								</th>
								<th>
									<center><b>Status</b></center>
								</th>
								<th>
									<center><b>Approver</b></center>
								</th>
								<th>
									<center><b>Remarks</b></center>
								</th>
								<th>
									<center><b></b></center>
								</th>
							</tr>

						</thead>

						<tbody>

							<?php

							// Now proceed with fetching and displaying the list of concerns as you already do
							$sql7 = "SELECT * FROM dtr_concerns WHERE empno = '$empNUM' AND ConcernDate BETWEEN '$backfrom' AND '$backto' ORDER BY `ConcernDate` ASC";
							$query7 = $HRconnect->query($sql7);

							while ($row7 = $query7->fetch_array()) {
								$status = $row7['status'];
								$concernid = $row7['id'];
								$selectedConcern = $row7['concern'];

							?>
								<tr>
									<td>
										<center><?php echo $row7['ConcernDate']; ?><center>
									</td>
									<td>
										<center><?php echo $row7['concern']; ?><center>
									</td>
									<td>
										<center><?php echo $row7['errortype']; ?><center>
									</td>
									<td>
										<center><?php echo $row7['newIN']; ?><center>
									</td>
									<td>
										<center><?php echo $row7['newbOUT']; ?></center>
									</td>
									<td>
										<center><?php echo $row7['newbIN']; ?></center>
									</td>
									<td>
										<center><?php echo $row7['newOUT']; ?></center>
									</td>
									<td>
										<center><?php echo $row7['status']; ?></center>
									</td>
									<td>
										<center><?php echo $row7['approver']; ?></center>
									</td>
									<td>
										<center><?php echo $row7['remarks']; ?></center>
									</td>
									<?php if ($status == 'Pending') { ?>
										<td>
											<center>
												<a href="print_concerns.php?cancel=yes&id=<?php echo $concernid; ?>&empno=<?php echo $empNUM; ?>&date=<?php echo $row7['ConcernDate']; ?>&selectedConcern=<?php echo $row7['concern']; ?>" class="btn btn-info btn-user btn-block bg-gradient-info" onclick="return confirm('Are you sure you want to Cancel this concern?');">Cancel</a>
											</center>
										</td>
									<?php } else { ?>
										<td>
											<center></center>
										</td>
									<?php } ?>
								</tr>
							<?php
							}
							?>

						</tbody>
					</table>
					<br>
				</div>
				<hr>
				<center>
					<p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
							regard may form ground for disciplinary action up to and including dismissal.</i></p>
				</center>

				<div class="d-sm-flex align-items-center justify-content-between mb-4">
					<div class="text-center">
						<a href="../filing-concerns.php?dtrconcern&concern=concern&empno=<?php echo $empNUM; ?>&cutfrom=<?php echo $_GET['cutfrom']; ?>&cutto=<?php echo $_GET['cutto']; ?>"><button class="btn btn-primary btn-block">
								Back</button></a>
					</div>
				</div>
			</div>

			</p>

		<?php
	}
		?>
		</body>

		<?php if (@$_GET['c'] == 1) { ?>
			<script>
				$(function() {
					$(".thanks").delay(2500).fadeOut();

				});
			</script>

			<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
				<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
					<div class="toast-header bg-warning">
						<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
							<small class="text-light">just now</small>
					</div>
					<div class="toast-body">
						You have <b class="text-warning">Successfully Cancelled</b> the DTR Concern that you filed. Thank you!
					</div>
				</div>
			</div>

		<?php } ?>

		<!-- Footer -->
		<?php if (@$_POST['d'] == 2) { ?>
			<script>
				$(function() {
					$(".thanks").delay(2500).fadeOut();

				});
			</script>

			<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
				<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
					<div class="toast-header bg-success">
						<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> DTR Concern</h5>
							<small class="text-light">just now</small>
					</div>
					<div class="toast-body">
						You have <b class="text-success">Successfully Filed</b> your Concern Thank you!
					</div>
				</div>
			</div>

		<?php } ?>

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
			< /html>