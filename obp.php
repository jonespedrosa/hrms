<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


if (empty($_SESSION['user'])) {
	header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$empno = $row['empno'];


$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];

// cutoff
$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
ON si.empno = ui.empno
WHERE si.status = 'Pending' AND ui.empno = $empno;";
$querybuilder = $HRconnect->query($getDateSQL);
$rowCutOff = $querybuilder->fetch_array();

$cutfrom = $rowCutOff['datefrom'];
$cutto = $rowCutOff['dateto'];
if (isset($_GET['branch'])) {
	@$_SESSION['useridd'] = $_GET['branch'];

	Header('Location: obp.php?pendingut=pendingut');

}


if ($userlevel != 'staff') {

	$a = array(date("Y-m-30") => date("Y-m-30"), date("Y-m-31") => date("Y-m-31"), date("Y-m-01") => date("Y-m-01"), date("Y-m-02") => date("Y-m-02"), date("Y-m-03") => date("Y-m-03"), date("Y-m-04") => date("Y-m-04"), date("Y-m-05") => date("Y-m-05"), date("Y-m-06") => date("Y-m-06"), date("Y-m-07") => date("Y-m-07"), date("Y-m-08") => date("Y-m-08"), date("Y-m-09") => date("Y-m-09"), date("Y-m-10") => date("Y-m-10"), date("Y-m-11") => date("Y-m-11"), date("Y-m-12") => date("Y-m-12"), date("Y-m-13") => date("Y-m-13"), date("Y-m-14") => date("Y-m-14"));

	if (array_key_exists(date("Y-m-d"), $a)) {
		$newdate1 = date("Y-m-24", strtotime("-1 months"));
		$newdate2 = date("Y-m-24");

	} else {
		$newdate1 = date("Y-m-24", strtotime("-1 months"));
		$newdate2 = date("Y-m-24");
	}
	$sql = "SELECT * FROM sched_info WHERE empno = $empno and status = 'Pending'";
	$query = $HRconnect->query($sql);
	$row = $query->fetch_array();
	$datefrom = $row['datefrom'];
	$dateto = $row['dateto'];

	$cutfrom = '2024-01-09';
	$cutto = '2024-12-23';
	?>


	<!DOCTYPE html>
	<html lang="en">

	<head>

		<meta charset="uft-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

			.ow-break-word {
				overflow-wrap: break-word;
			}
		</style>

	</head>

	<body id="page-top" class="sidebar-toggled">

		<?php include("navigation.php"); ?>
		<!-- Begin Page Content -->
		<div class="container-fluid">

			<!-- Page Heading -->

			<!-- OBP -->
			<?php
			if (isset($_GET["pendingut"]) == "pendingut") {
				?>
				<!-- DataTales Example -->
				<div class="d-sm-flex align-items-center justify-content-between mb-2">
					<div class="mb-3">
						<h4 class="mb-0">OBP</h4>
						<div class="small">
							<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
							.<?php echo date('F d, Y - h:i:s A'); ?>
						</div>
					</div>
				</div>

				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="effect-shine nav-link active" href="obp.php?pendingut=pendingut"><b>Pending OBP</b></a>
					</li>

					<li class="nav-item">
						<a class="effect-shine nav-link" href="obp.php?utapproved=utapproved"><b>Approved OBP</b></a>
					</li>
				</ul>

				<div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover text-uppercase" id="example1" width="100%"
								cellspacing="0">

								<thead>
									<tr class="bg-gray-200">
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
											<center>Status</center>
										</th>
										<th>
											<center>Approver</center>
										</th>
										<th>
											<center>Action</center>
										</th>
									</tr>
								</thead>
								<?php if (@$_SESSION['useridd'] == null) { ?>
									<div class="alert alert-danger d-none d-sm-block text-center" role="alert">
										Choose Cafe/Department to View OBP Filled</a>
									</div>
								<?php } ?>
								<?php
								if (
									$_SESSION['empno'] != 3080 and $_SESSION['empno'] != 1261 and $_SESSION['empno'] != 1910 and $_SESSION['empno'] != 3736
									and $_SESSION['empno'] != 4070 and $_SESSION['empno'] != 3770 and $_SESSION['empno'] != 4206 and $_SESSION['empno'] != 3160
									and $_SESSION['empno'] != 1509 and $_SESSION['empno'] != 1053 and $_SESSION['empno'] != 2356 and $_SESSION['empno'] != 3156
									and $_SESSION['empno'] != 3612 and $_SESSION['empno'] != 4001 and $_SESSION['empno'] != 5263 and $_SESSION['empno'] != 5430
									and $_SESSION['empno'] != 4892 and $_SESSION['empno'] != 3337 and $_SESSION['empno'] != 6436 and $_SESSION['empno'] != 6209
									and $_SESSION['empno'] != 6244 and $_SESSION['empno'] != 6245 and $_SESSION['empno'] != 6438
								) {
									?>
									<tbody>
										<?php
										if (@$_SESSION['useridd'] != null) {
											//IT & HR + AUDIT
											if ($userlevel == 'master' or $empno == 1348 or $empno == 271) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$datefrom' AND '$dateto' AND obp.status in ('Pending','Pending2')
												AND user_info.empno != " . $_SESSION['empno'] . "";
											}

											//ML1 & ML2 + AC
											if (
												$userlevel == 'ac' and $empno != 2221 and $empno != 3111 and $empno != 5928
												or $empno == 1 or $empno == 2 or $empno == 4
											) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$datefrom' AND '$dateto' AND obp.status = 'Pending2'
												AND user_info.empno != " . $_SESSION['empno'] . "";
											}

											//Supervisor & MOD
											if ($userlevel == 'mod' or $empno == 2221 or $empno == 3111) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$datefrom' AND '$dateto' AND obp.status = 'Pending'
												AND user_info.empno != " . $_SESSION['empno'] . "";
											}

											//Admin
											if ($empno == 5928) {
												$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND user_info.empno in (957,2070,3166,3228,5153,5096,2803,884,6484,2891,2483,1075,5152,4725,3441,2807,3072)
												AND obp.datefromto BETWEEN '$datefrom' AND '$dateto' AND obp.status = 'Pending2'
												AND user_info.empno != " . $_SESSION['empno'] . "";

											}


											$query = $HRconnect->query($sql);
											while ($row = $query->fetch_array()) {
												$status = $row['status'];
												$name = $row['name'];

												?>
												<tr>
													<td>
														<center><?php echo $row['empno']; ?></center>
													</td>
													<td>
														<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
													</td>
													<td>
														<center><?php echo $row['datefromto']; ?></center>
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
															<center><?php echo $row['status']; ?></center>
														</td>
														<?php
													}
													?>

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
														<center><a href="pdf/viewot.php?ut=ut&id=<?php echo $row['obpid']; ?>"
																class="btn btn-info btn-user btn-block btn-sm bg-gradient-info">Details</a>
														</center>
													</td>
												</tr>

												<?php
											}
										}
										?>
									</tbody>
								<?php } ?>
							</table>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<?php
			if (isset($_GET["utapproved"]) == "utapproved") {
				?>
				<!-- DataTales Example -->
				<div class="d-sm-flex align-items-center justify-content-between mb-2">
					<div class="mb-3">
						<h4 class="mb-0">OBP</h4>
						<div class="small">
							<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
							.<?php echo date('F d, Y - h:i:s A'); ?>
						</div>
					</div>
				</div>

				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="effect-shine nav-link" href="obp.php?pendingut=pendingut"><b>Pending OBP</b></a>
					</li>

					<li class="nav-item">
						<a class="effect-shine nav-link active" href="obp.php?utapproved=utapproved"><b>Approved OBP</b></a>
					</li>
				</ul>

				<div class="card shadow mb-4">
					<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
						<h6 class="m-0 font-weight-bold text-primary d-md-none"></h6>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-sm table-bordered table-hover text-uppercase" id="example" width="100%"
								cellspacing="0">
								<thead>
									<tr class="bg-gray-200">
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
											<center>Timein</center>
										</th>
										<th>
											<center>Breaout</center>
										</th>
										<th>
											<center>Breakin</center>
										</th>
										<th>
											<center>Timeout</center>
										</th>
										<th>
											<center>Attachments</center>
										</th>
										<th>
											<center>Reason</center>
										</th>
										<th>
											<center>Location</center>
										</th>
										<th>
											<center>Approved By</center>
										</th>
									</tr>
								</thead>

								<?php if (@$_SESSION['useridd'] == null) { ?>
									<div class="alert alert-danger d-none d-sm-block text-center" role="alert">
										Choose Cafe/Department to View Approved OBP</a>
									</div>
								<?php } ?>

								<tbody>

									<?php
									if (@$_SESSION['useridd'] != null) {
										//IT & HR + AUDIT
										if ($userlevel == 'master' or $empno == 1348 or $empno == 271) {
											$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$cutfrom' AND '$cutto' AND obp.status in ('Approved')
												AND user_info.empno != " . $_SESSION['empno'] . "";
										}

										//ML1 & ML2 + AC
										if (
											$userlevel == 'ac' and $empno != 2221 and $empno != 3111 and $empno != 3071
											or $empno == 1 or $empno == 2 or $empno == 4
										) {
											$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$cutfrom' AND '$cutto' AND obp.status = 'Approved'
												AND user_info.empno != " . $_SESSION['empno'] . "";
										}

										//Supervisor & MOD
										if ($userlevel == 'mod' or $empno == 2221 or $empno == 3111) {
											$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND obp.datefromto 
												BETWEEN '$cutfrom' AND '$cutto' AND obp.status = 'Approved'
												AND user_info.empno != " . $_SESSION['empno'] . "";
										}

										//Admin
										if ($empno == 3071) {
											$sql = "SELECT * FROM user_info
                                                JOIN obp on user_info.empno = obp.empno
                                                WHERE user_info.userid = $userid AND user_info.empno != 4625
												AND obp.datefromto BETWEEN '$cutfrom' AND '$cutto' AND obp.status = 'Approved'
												AND user_info.empno != " . $_SESSION['empno'] . "";

										}

										$query = $HRconnect->query($sql);
										while ($row = $query->fetch_array()) {
											$status = $row['status'];
											$name = $row['name'];

											?>
											<tr>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['empno']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo html_entity_decode(htmlentities($name)); ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['datefromto']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['timein']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['breakout']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['breakin']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['timeout']; ?></center>
												</td>
												<td class="ow-break-word"
													style="text-align: center; vertical-align: middle; max-width: 500px">
													<center>
														<small>
															<a href="<?php echo $row['attachment_1']; ?>" target="_blank">Document 1</a>
														</small>
														<br>
														<small>
															<a href="<?php echo $row['attachment_2']; ?>" target="_blank">Document 2</a>
														</small>
													</center>
												</td>
												<td class="ow-break-word"
													style="text-align: center; vertical-align: middle; max-width: 500px">
													<center><?php echo $row['obpreason']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['obploc']; ?></center>
												</td>
												<td class="ow-break-word" style="text-align: center; vertical-align: middle;">
													<center><?php echo $row['approval']; ?></center>
												</td>

											</tr>

											<?php
											@$total = floor((strtotime($row['otto']) - strtotime($row['otfrom'])) / 3600);

											@$totals += $total;
										}
									}
									?>

								</tbody>

							</table>
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

		<?php if (@$_GET['m'] == 6) { ?>
			<script>
				$(function () {
					$(".thanks").delay(2500).fadeOut();

				});
			</script>

			<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
				<div class="thanks toast fade show" style="position: fixed; top: 20px; right: 5px;">
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
		<script src="js/demo/datatables-demo.js"></script>

		<script>
			$(document).ready(function () {
				$('#example1').dataTable({
					stateSave: true
				});
			});
		</script>

		<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>


		<script>
			$(document).ready(function () {
				var table = $('#example').DataTable({
					stateSave: true,
					dom: 'Bfrtip',
					buttons: [
						{
							extend: 'excel',
							text: 'Excel',
							className: 'exportExcel',
							filename: 'Approved Overtime',
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