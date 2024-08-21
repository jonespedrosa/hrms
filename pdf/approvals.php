<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


if (empty($_SESSION['user'])) {
	header('location:../login.php');
}
include ("../../hrms/Function/hrms_home.php");
$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];


?>

<!-- approve OBP -->
<?php
if (isset($_POST['but_update'])) {

	if (isset($_POST['update'])) {
		foreach ($_POST['update'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			// select the date and empno of the employee with OBPID
			$select_obp = "SELECT empno, datefromto, timein, breakout, breakin, timeout FROM `hrms`.`obp` where obpid = ?";
			$stmt = $HRconnect->prepare($select_obp);
			$stmt->bind_param("i", $updateid);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_array();

			$breakout = $row["datefromto"] . " " . $row['breakout'];
			$breakin = $row["datefromto"] . " " . $row['breakin'];
			if (strcasecmp($row['breakout'], 'No Break') == 0 || strcasecmp($row['breakin'], 'No Break') == 0) {
				$breakout = $row['breakout'];
				$breakin = $row['breakin'];
			}

			// update timeinputs schedtime
			$updateSchedTime = "UPDATE `hrms`.`sched_time` SET M_timein = '" . $row["datefromto"] . " " . $row['timein'] . "', M_timeout = '" . $breakout . "', A_timein = '" . $breakin . "', A_timeout = '" . $row["datefromto"] . " " . $row['timeout'] . "' WHERE empno = " . $row['empno'] . " AND datefromto = '" . $row['datefromto'] . "';";
			mysqli_query($HRconnect, $updateSchedTime);

			// udpate approval obp
			$updateUser = "UPDATE obp SET 
						status = 'Approved',
						approval = '$user',
						app_timedate = '$timedate'
						WHERE obpid = " . $updateid;
			mysqli_query($HRconnect, $updateUser);


		}

		header("location:approvals.php?obp=obp&m=1");
	}

}
?>

<!-- approve OBP head -->
<?php
if (isset($_POST['but_updateh'])) {

	if (isset($_POST['updateh'])) {
		foreach ($_POST['updateh'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			// select the date and empno of the employee with OBPID
			$select_obp = "SELECT empno, datefromto, timein, breakout, breakin, timeout FROM `hrms`.`obp` where obpid = ?";
			$stmt = $HRconnect->prepare($select_obp);
			$stmt->bind_param("i", $updateid);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_array();

			$breakout = $row["datefromto"] . " " . $row['breakout'];
			$breakin = $row["datefromto"] . " " . $row['breakin'];
			if (strcasecmp($row['breakout'], 'No Break') == 0 || strcasecmp($row['breakin'], 'No Break') == 0) {
				$breakout = $row['breakout'];
				$breakin = $row['breakin'];
			}

			// update timeinputs schedtime
			$updateSchedTime = "UPDATE `hrms`.`sched_time` SET M_timein = '" . $row["datefromto"] . " " . $row['timein'] . "', M_timeout = '" . $breakout . "', A_timein = '" . $breakin . "', A_timeout = '" . $row["datefromto"] . " " . $row['timeout'] . "' WHERE empno = " . $row['empno'] . " AND datefromto = '" . $row['datefromto'] . "';";
			mysqli_query($HRconnect, $updateSchedTime);

			// udpate approval obp
			$updateUser = "UPDATE obp SET 
						status = 'Approved',
						approval = '$user',
						app_timedate = '$timedate'
						WHERE obpid = " . $updateid;
			mysqli_query($HRconnect, $updateUser);


		}
		header("location:approvals.php?obp=obp&m=1");
	}

}
?>

<!-- approve OT -->
<?php
if (isset($_POST['but_update1'])) {

	if (isset($_POST['update1'])) {
		foreach ($_POST['update1'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			$updateUser = "UPDATE overunder SET 
							otstatus = 'approved',
							approver = '$user',
							apptimedate = '$timedate'
							WHERE ovid = " . $updateid;
			mysqli_query($HRconnect, $updateUser);


		}
		header("location:approvals.php?ot=ot&m=2");
	}

}

?>

<!-- approve OT head -->
<?php
if (isset($_POST['but_update1h'])) {

	if (isset($_POST['update1h'])) {
		foreach ($_POST['update1h'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			$updateUser = "UPDATE overunder SET 
							otstatus = 'approved',
							approver = '$user',
							apptimedate = '$timedate'
							WHERE ovid = " . $updateid;
			mysqli_query($HRconnect, $updateUser);


		}
		header("location:approvals.php?ot=ot&m=2");
	}

}

// approve change sched
if (isset($_POST['but_update2h'])) {

	if (isset($_POST['update1h'])) {
		foreach ($_POST['update1h'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			$select_cs = "SELECT cs_schedfrom, cs_schedto, cs_break, datefrom, empno FROM `hrms`.`change_schedule` WHERE cs_ID = ?";
			$stmt = $HRconnect->prepare($select_cs);
			$stmt->bind_param("i", $updateid);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_array();

			// update timeinputs schedtime
			$updateSchedTime = "UPDATE `hrms`.`sched_time` SET schedfrom = '" . $row["datefrom"] . " " . $row["cs_schedfrom"] . "', schedto = '" . $row["datefrom"] . " " . $row["cs_schedto"] . "', break = " . $row["cs_break"] . " WHERE empno = " . $row["empno"] . " AND datefromto = '" . $row["datefrom"] . "'";
			mysqli_query($HRconnect, $updateSchedTime);

			// udpate approval obp
			$updateUser = "UPDATE `hrms`.`change_schedule` SET 
						cs_status = 'approved',
						approver = '$user',
						apptimedate = '$timedate'
						WHERE cs_ID = $updateid";
			mysqli_query($HRconnect, $updateUser);

		}

		header("location:approvals.php?cs=cs&m=10");
	}

}

// staff filed cs
if (isset($_POST['but_update2'])) {

	if (isset($_POST['update1'])) {
		foreach ($_POST['update1'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			$select_cs = "SELECT cs_schedfrom, cs_schedto, cs_break, datefrom, empno FROM `hrms`.`change_schedule` WHERE cs_ID = ?";
			$stmt = $HRconnect->prepare($select_cs);
			$stmt->bind_param("i", $updateid);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_array();

			// update timeinputs schedtime
			$updateSchedTime = "UPDATE `hrms`.`sched_time` SET schedfrom = '" . $row["datefrom"] . " " . $row["cs_schedfrom"] . "', schedto = '" . $row["datefrom"] . " " . $row["cs_schedto"] . "', break = " . $row["cs_break"] . " WHERE empno = " . $row["empno"] . " AND datefromto = '" . $row["datefrom"] . "'";
			mysqli_query($HRconnect, $updateSchedTime);

			// udpate approval obp
			$updateUser = "UPDATE `hrms`.`change_schedule` SET 
						cs_status = 'approved',
						approver = '$user',
						apptimedate = '$timedate'
						WHERE cs_ID = $updateid";
			mysqli_query($HRconnect, $updateUser);

		}
		header("location:approvals.php?cs=cs&m=10");
	}

}


// stafff filed wdo
if (isset($_POST['but_update3'])) {

	if (isset($_POST['update1'])) {
		foreach ($_POST['update1'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			// udpate approval obp
			$updateUser = "UPDATE `hrms`.`working_dayoff` SET 
						wdostatus = 'approved',
						approver = '$user',
						apptimedate = '$timedate'
						WHERE wodID = $updateid";
			mysqli_query($HRconnect, $updateUser);
		}
		header("location:approvals.php?wdo=wdo&m=8");
	}

}

if (isset($_POST['but_update3h'])) {

	if (isset($_POST['update1h'])) {
		foreach ($_POST['update1h'] as $updateid) {

			$timedate = date("Y-m-d H:i");

			// udpate approval obp
			$updateUser = "UPDATE `hrms`.`working_dayoff` SET 
						wdostatus = 'approved',
						approver = '$user',
						apptimedate = '$timedate'
						WHERE wodID = $updateid";
			mysqli_query($HRconnect, $updateUser);
		}
		header("location:approvals.php?wdo=wdo&m=8");
	}

}
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

		.exportExcel {
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

	<style>
		@import url(https://fonts.googleapis.com/css?family=Dosis:300,400);



		/* effect-shine */
		a.effect-shine:hover {
			-webkit-mask-image: linear-gradient(-75deg, rgba(0, 0, 0, .6) 30%, #000 50%, rgba(0, 0, 0, .6) 70%);
			-webkit-mask-size: 200%;
			animation: shine 2s infinite;
		}

		@-webkit-keyframes shine {
			from {
				-webkit-mask-position: 150%;
			}

			to {
				-webkit-mask-position: -50%;
			}
		}
	</style>

	<style>
		input.largerCheckbox {
			width: 18px;
			height: 18px;
		}

		input[type=checkbox]+label {
			color: #ccc;
			font-style: italic;
		}

		input[type=checkbox]:checked+label {
			color: #0000FF;
			font-style: normal;
		}
	</style>

</head>

<body id="body">
	<!-- Logout Modal -->
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

			<?php if ($userlevel != 'staff') {
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
				<?php if ($empno != '4451') {
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
								<a class="collapse-item" href="../filed_change_schedule.php?pending=pending">Filed Change
									Schedule</a>
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

			<!-- Nav Item - Tables -->
			<li class="nav-item">
				<a class="nav-link" href="index.php">
					<i class="fa fa-address-card" aria-hidden="true"></i>
					<span>Employee Portal</span></a>
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
								<span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp
									<?php echo $_SESSION['user']['username']; ?> </span>
							</a>
						</li>

						<div class="topbar-divider d-none d-sm-block"></div>

						<!-- Nav Item - User Information -->
						<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user; ?></span>
								<img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
								aria-labelledby="userDropdown">

								<a class="dropdown-item d-md-none" href="#">
									<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400 d-md-none"></i>
									<?php echo $user; ?>
								</a>

								<div class="dropdown-divider d-md-none"></div>

								<a class="dropdown-item"
									href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
									<i class="fa fa-address-card fa-sm fa-fw mr-2 text-gray-400 "></i>
									Profile
								</a>


								<?php
								if ($userlevel == 'master') {
									?>
									<a class="dropdown-item" href="database.php">
										<i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i>
										Database
									</a>
									<?php
								}
								?>

								<?php
								if ($userlevel == 'master' or $userlevel == 'ac' or $userlevel == 'admin') {
									?>
									<a class="dropdown-item" href="activitylogs.php">
										<i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
										Activity Logs
									</a>
									<?php
								}
								?>
								<!-- Logout Modal
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
								</div> -->

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
					if (isset($_GET["obp"]) == "obp") {
						?>
						<!-- Page Heading -->
						<div class="d-sm-flex align-items-center justify-content-between mb-2">
							<div class="mb-3">
								<h4 class="mb-0">Pending - OBP</h4>
								<div class="small">
									<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
									.<?php echo date('F d, Y - h:i:s A'); ?>
								</div>
							</div>

							<span>
								<select class="custom-select custom-select-sm" onchange="location = this.value;">
									<option value="#">Select Category:</option>
									<option value="approvals.php?ot=ot">PENDING OT</option>
									<option selected="selected" value="approvals.php?obp=obp">PENDING OBP</option>
									<option value="approvals.php?vl=vl">PENDING LEAVE</option>
									<option value="approvalsconcern.php">PENDING CONCERN</option>
									<option value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
									<option value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>

								</select>
							</span>
						</div>

						<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 4 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION["empno"] == 204) { ?>
							<div class="card shadow mb-4">
								<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

									<?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
										<h6 class="m-0 font-weight-bold text-primary">Manager's Filed OBP</h6>
									<?php } ?>

									<?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
										<h6 class="m-0 font-weight-bold text-primary">Head's Filed OBP</h6>
									<?php } ?>
								</div>
								<div class="card-body">
									<form method='post' action=''>
										<div class="table-responsive">
											<table class="table table-bordered table-hover text-uppercase table-sm"
												id="example2" width="100%" cellspacing="0">
												<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
													<div class="d-sm-flex align-items-center justify-content-between mb-1">
														<center><input type='checkbox' id='checkAllh'> <label for="checkAllh">
																SELECTALL</label></center>
														<center><input type='submit' class="btn btn-outline-primary btn-user"
																value='APPROVE' name='but_updateh'
																onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
														</center>
													</div>
													<hr />
												<?php } ?>
												<thead>
													<tr class="bg-gray-200">
														<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
															<th>
																<center></center>
															</th>
														<?php } ?>
														<th>
															<center>Department</center>
														</th>
														<th>
															<center>ID</center>
														</th>
														<th>
															<center>Fullname</center>
														</th>
														<th>
															<center>Date</center>
														</th>
														<th>
															<center>Reason</center>
														</th>
														<th>
															<center>Approver</center>
														</th>
														<th>
															<center></center>
														</th>
													</tr>
												</thead>

												<tbody>

													<?php
													if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE obp.status = 'Pending2' AND user_info.userlevel in ('ac','admin') AND datefromto BETWEEN '$cutfrom' AND '$cutto'";
													}
													if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno                                     
												WHERE obp.status = 'Pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
													}
													if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,2203,5612) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (1348,1964,6082,2957,4349) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 24) { //new added jones
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE obp.status = 'Pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,71,1404) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (5361,3178,5515,4811,2648) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (82,155) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (37,53,2720,69,124,40) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (63,88,97,170) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
														$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.empno in (254,302,112,2094,460,141) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}
													if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
														$sql = "SELECT * FROM user_info
													JOIN obp on user_info.empno = obp.empno
													WHERE obp.status = 'Pending2' AND user_info.department = 'NORTH' AND user_info.userlevel in ('mod') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

													}


													$query = $HRconnect->query($sql);
													while ($row = $query->fetch_array()) {
														$department = $row['department'];
														$status = $row['status'];
														$obpid = $row['obpid'];
														$name = $row['name'];
														?>

														<tr>
															<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
																<td>
																	<center><input class="largerCheckbox" type='checkbox'
																			name='updateh[]' value='<?= $obpid ?>'></center>
																</td>
															<?php } ?>

															<td>
																<center><?php echo $row['branch']; ?></center>
															</td>
															<td>
																<center><?php echo $row['empno']; ?></center>
															</td>
															<td>
																<center><?php echo html_entity_decode(htmlentities($name)); ?>
																</center>
															</td>
															<td>
																<center><?php echo $row['datefromto']; ?></center>
															</td>
															<td>
																<center><?php echo $row['obpreason']; ?></center>
															</td>
															<?php
															if ($status == 'Pending2') {
																?>
																<td>
																	<center><?php echo $row['p_approval']; ?></center>
																</td>
																<?php
															} else {
																?>
																<td>
																	<center><?php echo $row['approval']; ?></center>
																</td>
																<?php
															}
															?>
															<td>
																<center><a href="viewot.php?ut=ut&id=<?php echo $row['obpid']; ?>"
																		class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
																</center>
															</td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
									</form>
								</div>
							</div>
						</div>

						<hr>
					<?php } ?>


					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
							<h6 class="m-0 font-weight-bold text-primary">Staff's Filed OBP</h6>
						</div>
						<div class="card-body">
							<form method='post' action=''>
								<div class="table-responsive">
									<table class="table table-bordered table-hover text-uppercase table-sm" id="example"
										width="100%" cellspacing="0">
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<div class="d-sm-flex align-items-center justify-content-between mb-1">
												<center><input type='checkbox' id='checkAll'> <label for="checkAll">
														SELECTALL</label></center>
												<center><input type='submit' class="btn btn-outline-primary btn-user"
														value='APPROVE' name='but_update'
														onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
												</center>
											</div>
											<hr />
										<?php } ?>
										<thead>
											<tr class="bg-gray-200">
												<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
													<th>
														<center></center>
													</th>
												<?php } ?>
												<th>
													<center>Department</center>
												</th>
												<th>
													<center>ID</center>
												</th>
												<th>
													<center>Fullname</center>
												</th>
												<th>
													<center>Date</center>
												</th>
												<th>
													<center>Reason</center>
												</th>
												<th>
													<center>Approver</center>
												</th>
												<th>
													<center></center>
												</th>
											</tr>
										</thead>

										<tbody>

											<?php
											if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE obp.status = 'Pending2' AND user_info.userlevel in ('master','mod','staff') AND datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno                                     
												WHERE obp.status = 'Pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (98) AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218,228,229,231) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

												//new added by jones        
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE obp.status = 'Pending2' AND user_info.userid = 9999";


											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (168,214,80,164,166,165,167,173,172,171,169,215,216,225,92,3,236) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (82,155) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,229,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,204,228,231,234) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
												WHERE obp.status = 'Pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,218,223,224,238,213) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
												$sql = "SELECT * FROM user_info
													JOIN obp on user_info.empno = obp.empno
													WHERE obp.status = 'Pending2' AND user_info.department = 'NORTH' AND user_info.userlevel in ('staff') AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto'";

											}


											$query = $HRconnect->query($sql);
											while ($row = $query->fetch_array()) {
												$department = $row['department'];
												$status = $row['status'];
												$obpid = $row['obpid'];
												$name = $row['name'];
												?>

												<tr>
													<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
														<td>
															<center><input class="largerCheckbox" type='checkbox' name='update[]'
																	value='<?= $obpid ?>'></center>
														</td>
													<?php } ?>


													<td>
														<center><?php echo $row['branch']; ?></center>
													</td>
													<td>
														<center><?php echo $row['empno']; ?></center>
													</td>
													<td>
														<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
													</td>
													<td>
														<center><?php echo $row['datefromto']; ?></center>
													</td>
													<td>
														<center><?php echo $row['obpreason']; ?></center>
													</td>
													<?php
													if ($status == 'Pending2') {
														?>
														<td>
															<center><?php echo $row['p_approval']; ?></center>
														</td>
														<?php
													} else {
														?>
														<td>
															<center><?php echo $row['approval']; ?></center>
														</td>
														<?php
													}
													?>
													<td>
														<center><a href="viewot.php?ut=ut&id=<?php echo $row['obpid']; ?>"
																class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
														</center>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
							</form>
						</div>
					</div>
				</div>

				<?php
					}
					?>

			<?php
			if (isset($_GET["ot"]) == "ot") {
				?>

				<!-- Page Heading -->
				<div class="d-sm-flex align-items-center justify-content-between mb-2">
					<div class="mb-3">
						<h4 class="mb-0">Pending - Overtime</h4>
						<div class="small">
							<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
							.<?php echo date('F d, Y - h:i:s A'); ?>
						</div>
					</div>

					<span>
						<select class="custom-select custom-select-sm" onchange="location = this.value;">
							<option value="#">Select Category:</option>
							<option selected="selected" value="approvals.php?ot=ot">PENDING OT</option>
							<option value="approvals.php?obp=obp">PENDING OBP</option>
							<option value="approvals.php?vl=vl">PENDING LEAVE</option>
							<option value="approvalsconcern.php">PENDING CONCERN</option>
							<option value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
							<option value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>
						</select>
					</span>
				</div>

				<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 4 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION["empno"] == 204) { ?>
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

							<?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
								<h6 class="m-0 font-weight-bold text-primary">Manager's Filed Overtime</h6>
							<?php } ?>

							<?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
								<h6 class="m-0 font-weight-bold text-primary">Head's Filed Overtime</h6>
							<?php } ?>

						</div>
						<div class="card-body">
							<form method='post' action=''>
								<div class="table-responsive">
									<table class="table table-bordered table-hover text-uppercase table-sm" id="example"
										width="100%" cellspacing="0">
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<div class="d-sm-flex align-items-center justify-content-between mb-1">
												<center><input type='checkbox' id='checkAll1h'> <label for="checkAll1h">
														SELECTALL</label></center>
												<center><input type='submit' class="btn btn-outline-primary btn-user"
														value='APPROVE' name='but_update1h'
														onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
												</center>
											</div>
											<hr />
										<?php } ?>
										<thead>
											<tr class="bg-gray-200">
												<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
													<th>
														<center></center>
													</th>
												<?php } ?>
												<th>
													<center>Department</center>
												</th>
												<th>
													<center>ID</center>
												</th>
												<th>
													<center>Fullname</center>
												</th>
												<th>
													<center>Date</center>
												</th>
												<th>
													<center>Reason</center>
												</th>
												<th>
													<center>Hour/s</center>
												</th>
												<th>
													<center>Approver</center>
												</th>
												<th>
													<center></center>
												</th>
											</tr>
										</thead>

										<tbody>

											<?php
											if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
                                                WHERE overunder.otstatus = 'pending2' AND user_info.userlevel in ('ac','admin') AND otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno                                     
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,2203,5612) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (1348,1964,6082,2957,4349) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

												//new added by jones	
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
                                                WHERE overunder.otstatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,71,1404) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
												//end
									

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (5361,3178,5515,4811,2648) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (82,155) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (63,88,97,170) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
												$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

											}
											if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
												$sql = "SELECT * FROM user_info
											JOIN overunder on user_info.empno = overunder.empno
											WHERE overunder.otstatus = 'pending2' AND user_info.department = 'NORTH' AND user_info.userlevel in ('mod') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
											}
											$query = $HRconnect->query($sql);
											while ($row = $query->fetch_array()) {
												$department = $row['department'];
												$otstatus = $row['otstatus'];
												$ovid = $row['ovid'];
												$name = $row['name'];
												?>
												<tr>
													<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
														<td>
															<center><input class="largerCheckbox" type='checkbox' name='update1h[]'
																	value='<?= $ovid ?>'></center>
														</td>
													<?php } ?>

													<td>
														<center><?php echo $row['branch']; ?></center>
													</td>
													<td>
														<center><?php echo $row['empno']; ?></center>
													</td>
													<td>
														<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
													</td>
													<td>
														<center><?php echo $row['otdatefrom']; ?></center>
													</td>
													<td>
														<center><?php echo $row['otreason']; ?></center>
													</td>
													<td>
														<center><?php echo $row['othours']; ?>
													</td>
													<?php
													if ($otstatus == 'pending2') {
														?>
														<td>
															<center><?php echo $row['p_approver']; ?></center>
														</td>
														<?php
													} else {
														?>
														<td>
															<center><?php echo $row['approver']; ?></center>
														</td>
														<?php
													}
													?>
													<td>
														<center><a href="viewot.php?ot=ot&id=<?php echo $row['ovid']; ?>"
																class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
														</center>
													</td>
												</tr>
												<?php
											}
											?>
										</tbody>
									</table>
							</form>
						</div>
					</div>
				</div>

				<hr>

			<?php } ?>


			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Staff's Filed Overtime</h6>
				</div>
				<div class="card-body">
					<form method='post' action=''>
						<div class="table-responsive">
							<table class="table table-bordered table-hover text-uppercase table-sm" id="example2"
								width="100%" cellspacing="0">
								<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
									<div class="d-sm-flex align-items-center justify-content-between mb-1">
										<center><input type='checkbox' id='checkAll1'> <label for="checkAll1"> SELECTALL</label>
										</center>
										<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE'
												name='but_update1'
												onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
										</center>
									</div>
									<hr />
								<?php } ?>
								<thead>
									<tr class="bg-gray-200">
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<th>
												<center></center>
											</th>
										<?php } ?>
										<th>
											<center>Department</center>
										</th>
										<th>
											<center>ID</center>
										</th>
										<th>
											<center>Fullname</center>
										</th>
										<th>
											<center>Date</center>
										</th>
										<th>
											<center>Reason</center>
										</th>
										<th>
											<center>Hour/s</center>
										</th>
										<th>
											<center>Approver</center>
										</th>
										<th>
											<center></center>
										</th>
									</tr>
								</thead>

								<tbody>

									<?php
									if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
                                                WHERE overunder.otstatus = 'pending2' AND user_info.userlevel in ('master','mod','staff') AND otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno                                     
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (98) AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218,223,224,228,229,231) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 24) { //added by jones
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
                                                WHERE overunder.otstatus = 'pending2' AND user_info.userid = 9999 AND user_info.userlevel in ('ac') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {


										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (82,155) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,229,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";


									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {

										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,204,228,231,234) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
										$sql = "SELECT * FROM user_info
                                                JOIN overunder on user_info.empno = overunder.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,218,223,224,238,213) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
										$sql = "SELECT * FROM user_info
											JOIN overunder on user_info.empno = overunder.empno
											WHERE overunder.otstatus = 'pending2' AND user_info.department = 'NORTH' AND user_info.userlevel in ('staff') AND overunder.otdatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									$query = $HRconnect->query($sql);
									while ($row = $query->fetch_array()) {
										$department = $row['department'];
										$otstatus = $row['otstatus'];
										$ovid = $row['ovid'];
										$name = $row['name'];
										?>
										<tr>
											<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
												<td>
													<center><input class="largerCheckbox" type='checkbox' name='update1[]'
															value='<?= $ovid ?>'></center>
												</td>
											<?php } ?>

											<td>
												<center><?php echo $row['branch']; ?></center>
											</td>
											<td>
												<center><?php echo $row['empno']; ?></center>
											</td>
											<td>
												<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
											</td>
											<td>
												<center><?php echo $row['otdatefrom']; ?></center>
											</td>
											<td>
												<center><?php echo $row['otreason']; ?></center>
											</td>
											<td>
												<center><?php echo $row['othours']; ?>
											</td>
											<?php
											if ($otstatus == 'pending2') {
												?>
												<td>
													<center><?php echo $row['p_approver']; ?></center>
												</td>
												<?php
											} else {
												?>
												<td>
													<center><?php echo $row['approver']; ?></center>
												</td>
												<?php
											}
											?>
											<td>
												<center><a href="viewot.php?ot=ot&id=<?php echo $row['ovid']; ?>"
														class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
												</center>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
					</form>
				</div>
			</div>
		</div>

		<?php
			}
			?>

	<!-- PENDING CS -->
	<?php
	if (isset($_GET["cs"]) == "cs") {
		?>

		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-2">
			<div class="mb-3">
				<h4 class="mb-0">Pending - Change Schedule</h4>
				<div class="small">
					<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
					.<?php echo date('F d, Y - h:i:s A'); ?>
				</div>
			</div>

			<span>
				<select class="custom-select custom-select-sm" onchange="location = this.value;">
					<option value="#">Select Category:</option>
					<option value="approvals.php?ot=ot">PENDING OT</option>
					<option value="approvals.php?obp=obp">PENDING OBP</option>
					<option value="approvals.php?vl=vl">PENDING LEAVE</option>
					<option value="approvalsconcern.php">PENDING CONCERN</option>
					<option value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
					<option selected="selected" value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>
				</select>
			</span>
		</div>

		<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 4 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 204) { ?>
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

					<?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Manager's Filed Change Schedule</h6>
					<?php } ?>

					<?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Head's Filed Change Schedule</h6>
					<?php } ?>

				</div>
				<div class="card-body">
					<form method='post' action=''>
						<div class="table-responsive">
							<table class="table table-bordered table-hover text-uppercase table-sm" id="example" width="100%"
								cellspacing="0">
								<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
									<div class="d-sm-flex align-items-center justify-content-between mb-1">
										<center><input type='checkbox' id='checkAll1h'> <label for="checkAll1h"> SELECTALL</label>
										</center>
										<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE'
												name='but_update2h'
												onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
										</center>
									</div>
									<hr />
								<?php } ?>
								<thead>
									<tr class="bg-gray-200">
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<th>
												<center></center>
											</th>
										<?php } ?>
										<th>
											<center>Department</center>
										</th>
										<th>
											<center>ID</center>
										</th>
										<th>
											<center>Fullname</center>
										</th>
										<th>
											<center>Date of CS</center>
										</th>
										<th>
											<center>Reason</center>
										</th>
										<th>
											<center>Approver</center>
										</th>
										<th>
											<center></center>
										</th>
									</tr>
								</thead>

								<tbody>

									<?php
									if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '.$cutto.'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno                                     
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (98) AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218,223,224,228,229,231) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid = 9999 AND user_info.userlevel in ('ac') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (82,155) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,229,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,204,228,231,234) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
										$sql = "SELECT * FROM user_info
																JOIN change_schedule on user_info.empno = change_schedule.empno
																WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,218,223,224,238,213) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
										$sql = "SELECT * FROM user_info
																	JOIN change_schedule on user_info.empno = change_schedule.empno
																	WHERE change_schedule.cs_status = 'pending2' AND user_info.department = 'NORTH' AND user_info.userlevel in ('staff') AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}


									$query = $HRconnect->query($sql);
									while ($row = $query->fetch_array()) {
										$department = $row['department'];
										$status = $row['cs_status'];
										$cs_id = $row['cs_ID'];
										$name = $row['name'];
										?>
										<tr>
											<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
												<td>
													<center><input class="largerCheckbox" type='checkbox' name='update1h[]'
															value='<?= $cs_id ?>'></center>
												</td>
											<?php } ?>

											<td>
												<center><?php echo $row['branch']; ?></center>
											</td>
											<td>
												<center><?php echo $row['empno']; ?></center>
											</td>
											<td>
												<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
											</td>
											<td>
												<center><?php echo $row['datefrom']; ?></center>
											</td>
											<td>
												<center><?php echo $row['cs_reason']; ?></center>
											</td>
											<?php
											if ($status == 'pending2') {
												?>
												<td>
													<center><?php echo $row['p_approver']; ?></center>
												</td>
												<?php
											} else {
												?>
												<td>
													<center><?php echo $row['approver']; ?></center>
												</td>
												<?php
											}
											?>
											<td>
												<center><a href="viewchangesched.php?cs=cs&id=<?php echo $row['cs_ID']; ?>"
														class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
												</center>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
					</form>
				</div>
			</div>
			</div>

			<hr>

		<?php } ?>


		<div class="card shadow mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Staff's Filed Change Schedule</h6>
			</div>
			<div class="card-body">
				<form method='post' action=''>
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-uppercase table-sm" id="example2" width="100%"
							cellspacing="0">
							<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
								<div class="d-sm-flex align-items-center justify-content-between mb-1">
									<center><input type='checkbox' id='checkAll1'> <label for="checkAll1"> SELECTALL</label>
									</center>
									<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE'
											name='but_update2'
											onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
									</center>
								</div>
								<hr />
							<?php } ?>
							<thead>
								<tr class="bg-gray-200">
									<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
										<th>
											<center></center>
										</th>
									<?php } ?>
									<th>
										<center>Department</center>
									</th>
									<th>
										<center>ID</center>
									</th>
									<th>
										<center>Fullname</center>
									</th>
									<th>
										<center>Date</center>
									</th>
									<th>
										<center>Reason</center>
									</th>
									<th>
										<center>Approver</center>
									</th>
									<th>
										<center></center>
									</th>
								</tr>
							</thead>

							<tbody>

							<tbody>

								<?php
								if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.userlevel in ('ac','admin','master','staff','mod') AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno                                     
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {

									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,5612) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (1348,1964,6082,2957,4349) AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									//new added by jones	
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,71,1404) AND change_schedule datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									//end
							

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (5361,3178,5515,4811,2648) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (82,155) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND change_schedule.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (63,88,97,170) AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
									$sql = "SELECT * FROM user_info
								JOIN change_schedule on user_info.empno = change_schedule.empno
								WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
									$sql = "SELECT * FROM user_info
									JOIN change_schedule on user_info.empno = change_schedule.empno
									WHERE change_schedule.cs_status = 'pending2' AND user_info.userlevel in ('mod') AND user_info.department = 'NORTH' AND change_schedule.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								$query = $HRconnect->query($sql);
								while ($row = $query->fetch_array()) {
									$department = $row['department'];
									$status = $row['cs_status'];
									$cs_id = $row['cs_ID'];
									$name = $row['name'];
									?>
									<tr>
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<td>
												<center><input class="largerCheckbox" type='checkbox' name='update1[]'
														value='<?= $cs_id ?>'></center>
											</td>
										<?php } ?>

										<td>
											<center><?php echo $row['branch']; ?></center>
										</td>
										<td>
											<center><?php echo $row['empno']; ?></center>
										</td>
										<td>
											<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
										</td>
										<td>
											<center><?php echo $row['datefrom']; ?></center>
										</td>
										<td>
											<center><?php echo $row['cs_reason']; ?></center>
										</td>
										<?php
										if ($status == 'pending2') {
											?>
											<td>
												<center><?php echo $row['p_approver']; ?></center>
											</td>
											<?php
										} else {
											?>
											<td>
												<center><?php echo $row['approver']; ?></center>
											</td>
											<?php
										}
										?>
										<td>
											<center><a href="viewchangesched.php?cs=cs&id=<?php echo $row['cs_ID']; ?>"
													class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
											</center>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
				</form>
			</div>
		</div>
		</div>

		<?php
	}
	?>
	<!-- PENDING WDO -->
	<?php
	if (isset($_GET["wdo"]) == "wdo") {
		?>

		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-2">
			<div class="mb-3">
				<h4 class="mb-0">Pending - Working Day Off</h4>
				<div class="small">
					<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
					.<?php echo date('F d, Y - h:i:s A'); ?>
				</div>
			</div>

			<span>
				<select class="custom-select custom-select-sm" onchange="location = this.value;">
					<option value="#">Select Category:</option>
					<option value="approvals.php?ot=ot">PENDING OT</option>
					<option value="approvals.php?obp=obp">PENDING OBP</option>
					<option value="approvals.php?vl=vl">PENDING LEAVE</option>
					<option value="approvalsconcern.php">PENDING CONCERN</option>
					<option selected="selected" value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
					<option value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>
				</select>
			</span>
		</div>

		<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 4 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 204) { ?>
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

					<?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Manager's Filed Working Day Off</h6>
					<?php } ?>

					<?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Head's Filed Working Day Off</h6>
					<?php } ?>

				</div>
				<div class="card-body">
					<form method='post' action=''>
						<div class="table-responsive">
							<table class="table table-bordered table-hover text-uppercase table-sm" id="example" width="100%"
								cellspacing="0">
								<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
									<div class="d-sm-flex align-items-center justify-content-between mb-1">
										<center><input type='checkbox' id='checkAll1h'> <label for="checkAll1h"> SELECTALL</label>
										</center>
										<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE'
												name='but_update3h'
												onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
										</center>
									</div>
									<hr />
								<?php } ?>
								<thead>
									<tr class="bg-gray-200">
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<th>
												<center></center>
											</th>
										<?php } ?>
										<th>
											<center>Department</center>
										</th>
										<th>
											<center>ID</center>
										</th>
										<th>
											<center>Fullname</center>
										</th>
										<th>
											<center>Date</center>
										</th>
										<th>
											<center>Reason</center>
										</th>
										<th>
											<center>Hour/s</center>
										</th>
										<th>
											<center>Approver</center>
										</th>
										<th>
											<center></center>
										</th>
									</tr>
								</thead>

								<tbody>

									<?php

									if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
                                                WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userlevel in ('ac','admin','master','staff','mod') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno                                     
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {

										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,5612) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (1348,1964,6082,2957,4349) AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

										//new added by jones	
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
                                                WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,71,1404) AND working_dayoff datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
										//end
							

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = oveworking_dayoffrunder.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (5361,3178,5515,4811,2648) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (82,155) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (63,88,97,170) AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
										$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									}
									if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
										$sql = "SELECT * FROM user_info
													JOIN working_dayoff on user_info.empno = working_dayoff.empno
													WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userlevel in ('mod') AND user_info.department = 'NORTH' AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
									}
									$query = $HRconnect->query($sql);
									while ($row = $query->fetch_array()) {
										$department = $row['department'];
										$otstatus = $row['wdostatus'];
										$ovid = $row['wodID'];
										$name = $row['name'];
										?>
										<tr>
											<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
												<td>
													<center><input class="largerCheckbox" type='checkbox' name='update1h[]'
															value='<?= $ovid ?>'></center>
												</td>
											<?php } ?>

											<td>
												<center><?php echo $row['branch']; ?></center>
											</td>
											<td>
												<center><?php echo $row['empno']; ?></center>
											</td>
											<td>
												<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
											</td>
											<td>
												<center><?php echo $row['datefrom']; ?></center>
											</td>
											<td>
												<center><?php echo $row['wdo_reason']; ?></center>
											</td>
											<td>
												<center><?php echo $row['working_hours']; ?>
											</td>
											<?php
											if ($otstatus == 'pending2') {
												?>
												<td>
													<center><?php echo $row['p_approver']; ?></center>
												</td>
												<?php
											} else {
												?>
												<td>
													<center><?php echo $row['approver']; ?></center>
												</td>
												<?php
											}
											?>
											<td>
												<center><a href="viewwdo.php?wdo=wdo&id=<?php echo $row['wodID']; ?>"
														class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
												</center>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
					</form>
				</div>
			</div>
			</div>

			<hr>

		<?php } ?>


		<div class="card shadow mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Staff's Filed Working Day Off</h6>
			</div>
			<div class="card-body">
				<form method='post' action=''>
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-uppercase table-sm" id="example2" width="100%"
							cellspacing="0">
							<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
								<div class="d-sm-flex align-items-center justify-content-between mb-1">
									<center><input type='checkbox' id='checkAll1'> <label for="checkAll1"> SELECTALL</label>
									</center>
									<center><input type='submit' class="btn btn-outline-primary btn-user" value='APPROVE'
											name='but_update3'
											onclick="return confirm('Are you sure you want to approve selected rows? Click OK to proceed. Thank you!');">
									</center>
								</div>
								<hr />
							<?php } ?>
							<thead>
								<tr class="bg-gray-200">
									<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
										<th>
											<center></center>
										</th>
									<?php } ?>
									<th>
										<center>Department</center>
									</th>
									<th>
										<center>ID</center>
									</th>
									<th>
										<center>Fullname</center>
									</th>
									<th>
										<center>Date</center>
									</th>
									<th>
										<center>Reason</center>
									</th>
									<th>
										<center>Hour/s</center>
									</th>
									<th>
										<center>Approver</center>
									</th>
									<th>
										<center></center>
									</th>
								</tr>
							</thead>

							<tbody>

								<?php
								if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
                                                WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '.$cutto.'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno                                     
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (98) AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218,223,224) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
                                                WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid = 9999 AND user_info.userlevel in ('ac') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (168,214,80,164,166,165,167,173,172,171,169,215,216,225,92,3,236) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (82,155) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									// SOUTH
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,229,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									// MFO
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,204,228,231,234) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

									// NORTH
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
									$sql = "SELECT * FROM user_info
                                                JOIN working_dayoff on user_info.empno = working_dayoff.empno
												WHERE overunder.otstatus = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,218,223,224,238,213) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom  BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";

								}

								// NORTH
								if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 112)) {
									$sql = "SELECT * FROM user_info
													JOIN working_dayoff on user_info.empno = working_dayoff.empno
													WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userlevel in ('staff') AND user_info.department = 'NORTH' AND working_dayoff.datefrom BETWEEN '" . $cutfrom . "' AND '" . $cutto . "'";
								}
								$query = $HRconnect->query($sql);
								while ($row = $query->fetch_array()) {
									$department = $row['department'];
									$otstatus = $row['wdostatus'];
									$ovid = $row['wodID'];
									$name = $row['name'];
									?>
									<tr>
										<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1 or $_SESSION['empno'] == 2 or $_SESSION['empno'] == 1348) { ?>
											<td>
												<center><input class="largerCheckbox" type='checkbox' name='update1[]'
														value='<?= $ovid ?>'></center>
											</td>
										<?php } ?>

										<td>
											<center><?php echo $row['branch']; ?></center>
										</td>
										<td>
											<center><?php echo $row['empno']; ?></center>
										</td>
										<td>
											<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
										</td>
										<td>
											<center><?php echo $row['datefrom']; ?></center>
										</td>
										<td>
											<center><?php echo $row['wdo_reason']; ?></center>
										</td>
										<td>
											<center><?php echo $row['working_hours']; ?>
										</td>
										<?php
										if ($otstatus == 'pending2') {
											?>
											<td>
												<center><?php echo $row['p_approver']; ?></center>
											</td>
											<?php
										} else {
											?>
											<td>
												<center><?php echo $row['approver']; ?></center>
											</td>
											<?php
										}
										?>
										<td>
											<center><a href="viewwdo.php?wdo=wdo&id=<?php echo $row['wodID']; ?>"
													class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
											</center>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
				</form>
			</div>
		</div>
		</div>

		<?php
	}
	?>
	<?php
	if (isset($_GET["vl"]) == "vl") {
		?>

		<!-- Page Heading -->
		<div class="d-sm-flex align-items-center justify-content-between mb-2">
			<div class="mb-3">
				<h4 class="mb-0">Pending - Leave</h4>
				<div class="small">
					<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
					.<?php echo date('F d, Y - h:i:s A'); ?>
				</div>
			</div>

			<span>
				<select class="custom-select custom-select-sm" onchange="location = this.value;">
					<option value="#">Select Category:</option>
					<option value="approvals.php?ot=ot">PENDING OT</option>
					<option value="approvals.php?obp=obp">PENDING OBP</option>
					<option selected="selected" value="approvals.php?vl=vl">PENDING LEAVE</option>
					<option value="approvalsconcern.php">PENDING CONCERN</option>
					<option value="approvals.php?wdo=wdo">PENDING WORKING DAY OFF</option>
					<option value="approvals.php?cs=cs">PENDING CHANGE SCHEDULE</option>
				</select>
			</span>
		</div>

		<?php if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] != 4 or $_SESSION['empno'] == 1331 or $_SESSION['empno'] == 24 or $_SESSION['empno'] == 1073 or $_SESSION['empno'] == 38 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 109 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 45 or $_SESSION['empno'] == 4378 or $_SESSION['empno'] == 204) { ?>
			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">

					<?php if ($_SESSION['empno'] == 1 or $_SESSION['empno'] == 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Manager's Filed Leave</h6>
					<?php } ?>

					<?php if ($_SESSION['empno'] != 1 and $_SESSION['empno'] != 2) { ?>
						<h6 class="m-0 font-weight-bold text-primary">Head's Filed Leave</h6>
					<?php } ?>

				</div>
				<div class="card-body">
					<form method='post' action=''>
						<div class="table-responsive">
							<table class="table table-bordered table-hover text-uppercase table-sm" id="example" width="100%"
								cellspacing="0">

								<thead>
									<tr class="bg-gray-200">
										<th>
											<center>Department</center>
										</th>
										<th>
											<center>ID</center>
										</th>
										<th>
											<center>Fullname</center>
										</th>
										<th>
											<center>Reason</center>
										</th>
										<th>
											<center></center>
										</th>
									</tr>
								</thead>

								<tbody>

									<?php
									if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
                                                WHERE vlstatus = 'pending' AND user_info.userlevel in ('ac','admin') AND vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno                                     
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,5612) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (1348,1964,6082,2957,4349) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";


									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 24) { //new added jones
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
                                                WHERE vlform.vlstatus = 'pending' AND user_info.empno in (38,63,76,97,109,124,819,45,71,1404) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";


									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (5361,3178,5515,4811,2648) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (82,155) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (37,53,2720,69,124,40) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (63,88,97,170,63) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (254,302,112,2094,460,141,204) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
										$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
													JOIN user_info ON vlform.empno = user_info.empno
													WHERE vlform.vlstatus = 'pending' AND user_info.userlevel in ('mod') AND user_info.department = 'NORTH' AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

									}
									$query = $HRconnect->query($sql);
									while ($row = $query->fetch_array()) {
										$department = $row['department'];
										$vlnumber = $row['vlnumber'];
										$name = $row['name'];
										?>
										<tr>
											<td>
												<center><?php echo $row['branch']; ?></center>
											</td>
											<td>
												<center><?php echo $row['empno']; ?></center>
											</td>
											<td>
												<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
											</td>
											<td>
												<center><?php echo htmlspecialchars($row['vlreason'], ENT_QUOTES, 'UTF-8') ?>
												</center>
											</td>

											<td>
												<center><a
														href="viewot.php?leave=leave&empno=<?php echo $row['empno']; ?>&vlnumber=<?php echo $row['vlnumber']; ?>"
														class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
												</center>
											</td>

										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
					</form>
				</div>
			</div>
			</div>

			<hr>
		<?php } ?>

		<!-- Page Heading -->

		<div class="card shadow mb-4">
			<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
				<h6 class="m-0 font-weight-bold text-primary">Staff's Filed Leave</h6>
			</div>
			<div class="card-body">
				<form method='post' action=''>
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-uppercase table-sm" id="example2" width="100%"
							cellspacing="0">

							<thead>
								<tr class="bg-gray-200">
									<th>
										<center>Department</center>
									</th>
									<th>
										<center>ID</center>
									</th>
									<th>
										<center>Fullname</center>
									</th>
									<th>
										<center>Reason</center>
									</th>
									<th>
										<center></center>
									</th>
								</tr>
							</thead>

							<tbody>

								<?php
								if ($userlevel == 'master' or $userlevel == 'admin' and $_SESSION['empno'] == 1348) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
                                                WHERE vlstatus = 'pending' AND user_info.userlevel in ('master','mod','staff') AND vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 1) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno                                     
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 2) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'admin' and $_SESSION['empno'] == 4) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 4378) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.empno in (1348,1964,6082,2957,4349) AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218,223,224,228,229,231) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";
								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 24) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
                                                WHERE vlform.vlstatus = 'pending' AND user_info.userid = 9999 AND user_info.userlevel in ('ac') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 1073) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (82,155) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,229,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170 or $_SESSION['empno'] == 88) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,204,228,231,234) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {

									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
                                                JOIN user_info ON vlform.empno = user_info.empno
												WHERE vlform.vlstatus = 'pending' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,218,223,224,238,213) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								if ($userlevel == 'ac' and ($_SESSION['empno'] == 204 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 38)) {
									$sql = "SELECT DISTINCT vltype,user_info.empno,name,branch,vlreason,vlnumber,department,user_info.userlevel,user_info.userid FROM vlform
													JOIN user_info ON vlform.empno = user_info.empno
													WHERE vlform.vlstatus = 'pending' AND user_info.userlevel in ('staff') AND user_info.department = 'NORTH' AND vlform.vldatefrom BETWEEN '$cutfrom' AND '$cutto'";

								}
								$query = $HRconnect->query($sql);
								while ($row = $query->fetch_array()) {
									$department = $row['department'];
									$vlnumber = $row['vlnumber'];
									$name = $row['name'];
									?>
									<tr>
										<td>
											<center><?php echo $row['branch']; ?></center>
										</td>
										<td>
											<center><?php echo $row['empno']; ?></center>
										</td>
										<td>
											<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
										</td>
										<td>
											<center><?php echo $row['vlreason']; ?></center>
										</td>
										<td>
											<center><a
													href="viewot.php?leave=leave&empno=<?php echo $row['empno']; ?>&vlnumber=<?php echo $row['vlnumber']; ?>"
													class="btn btn-info btn-user btn-sm btn-block bg-gradient-info">Details</a>
											</center>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
				</form>
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


	<?php if (@$_GET['m'] == 1) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
				<div class="toast-header bg-success">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> OBP</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-success">Successfully Approve</b> OBP. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>


	<?php if (@$_GET['m'] == 2) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-success">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-success">Successfully Approve</b> Overtime. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 3) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-success">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Wellness Leave
						</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-success">Successfully Approve</b> Wellness Leave. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 4) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-warning">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Wellness Leave
						</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-warning">Successfully Cancel</b> Wellness Leave. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 5) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-warning">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Overtime</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-warning">Successfully Cancel</b> Overtime. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 6) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-warning">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> OBP</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-warning">Successfully Cancel</b> OBP. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 7) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-warning">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> WDO</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-warning">Successfully Cancel</b> WDO. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>
	<?php if (@$_GET['m'] == 8) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-success">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> WDO</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-success">Successfully Approve</b> WDO. Thank you!
				</div>
			</div>
		</div>

	<?php } ?>

	<?php if (@$_GET['m'] == 9) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-warning">
					<h5 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change
						Schedule</h5>
					<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-warning">Successfully Cancel</b> Change Schedule. Thank you!
				</div>
			</div>
		</div> <?php } ?>
	<?php if (@$_GET['m'] == 10) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>

		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-success">
					<h5 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change
						Schedule</h5>
					<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					You have <b class="text-success">Successfully Approve</b> Change Schedule. Thank you!
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if (@$_GET['m'] == 11) { ?>
		<script>
			$(function () {
				$(".thanks").delay(2500).fadeOut();

			});
		</script>
		<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
			<div class="thanks toast fade show" style="position: fixed; top: 15px; right: 5px;">
				<div class="toast-header bg-danger">
					<h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> WDO</h5>
						<small class="text-light">just now</small>
				</div>
				<div class="toast-body">
					<b class="text-danger">WDO request is invalid</b>. Kindly check the remarks in change schedule.
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
	<!-- End of Footer -->
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

	<!-- Script -->

	<script type="text/javascript">
		$(document).ready(function () {

			// Check/Uncheck ALl
			$('#checkAll').change(function () {
				if ($(this).is(':checked')) {
					$('input[name="update[]"]').prop('checked', true);
				} else {
					$('input[name="update[]"]').each(function () {
						$(this).prop('checked', false);
					});
				}
			});

			// Checkbox click
			$('input[name="update[]"]').click(function () {
				var total_checkboxes = $('input[name="update[]"]').length;
				var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

				if (total_checkboxes_checked == total_checkboxes) {
					$('#checkAll').prop('checked', true);
				} else {
					$('#checkAll').prop('checked', false);
				}
			});
		});

		$(document).ready(function () {

			// Check/Uncheck ALl
			$('#checkAllh').change(function () {
				if ($(this).is(':checked')) {
					$('input[name="updateh[]"]').prop('checked', true);
				} else {
					$('input[name="updateh[]"]').each(function () {
						$(this).prop('checked', false);
					});
				}
			});

			// Checkbox click
			$('input[name="updateh[]"]').click(function () {
				var total_checkboxes = $('input[name="updateh[]"]').length;
				var total_checkboxes_checked = $('input[name="updateh[]"]:checked').length;

				if (total_checkboxes_checked == total_checkboxes) {
					$('#checkAllh').prop('checked', true);
				} else {
					$('#checkAllh').prop('checked', false);
				}
			});
		});

		$(document).ready(function () {

			// Check/Uncheck ALl
			$('#checkAll1').change(function () {
				if ($(this).is(':checked')) {
					$('input[name="update1[]"]').prop('checked', true);
				} else {
					$('input[name="update1[]"]').each(function () {
						$(this).prop('checked', false);
					});
				}
			});

			// Checkbox click
			$('input[name="update1[]"]').click(function () {
				var total_checkboxes = $('input[name="update1[]"]').length;
				var total_checkboxes_checked = $('input[name="update1[]"]:checked').length;

				if (total_checkboxes_checked == total_checkboxes) {
					$('#checkAll1').prop('checked', true);
				} else {
					$('#checkAll1').prop('checked', false);
				}
			});
		});

		$(document).ready(function () {

			// Check/Uncheck ALl
			$('#checkAll1h').change(function () {
				if ($(this).is(':checked')) {
					$('input[name="update1h[]"]').prop('checked', true);
				} else {
					$('input[name="update1h[]"]').each(function () {
						$(this).prop('checked', false);
					});
				}
			});

			// Checkbox click
			$('input[name="update1h[]"]').click(function () {
				var total_checkboxes = $('input[name="update1h[]"]').length;
				var total_checkboxes_checked = $('input[name="update1h[]"]:checked').length;

				if (total_checkboxes_checked == total_checkboxes) {
					$('#checkAll1h').prop('checked', true);
				} else {
					$('#checkAll1h').prop('checked', false);
				}
			});
		});	
	</script>

	<script>
		$(document).ready(function () {
			$('#example').dataTable({
				stateSave: true
			});
		});
	</script>

	<script>
		$(document).ready(function () {
			$('#example2').dataTable({
				stateSave: true
			});
		});
	</script>

</body>

</html>