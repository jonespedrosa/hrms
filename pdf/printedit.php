<?php
$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


$userid = $_SESSION['useridd'];

$id = $_GET['id'];

// redirect if the selected cutoff is Compress Sched ------------------------------------------------------------------------------//
$getSchedTime = "SELECT empno, datefrom, dateto FROM `hrms`.`sched_info` WHERE id = ?";
$stmt = $HRconnect->prepare($getSchedTime);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultSchedTime = $stmt->get_result()->fetch_assoc();
$stmt->close();

$isCompressed = "SELECT sched_type FROM `hrms`.`sched_time` WHERE empno = ? AND datefromto BETWEEN ? AND ? AND sched_type = ?";
$stmt = $HRconnect->prepare($isCompressed);
$schedType = "cmp_sched";
$stmt->bind_param("isss", $resultSchedTime["empno"], $resultSchedTime["datefrom"], $resultSchedTime["dateto"], $schedType);
$stmt->execute();
$resultIsCompressed = $stmt->get_result();
$row_sched = $resultIsCompressed->fetch_all(MYSQLI_ASSOC);

if ($resultIsCompressed->num_rows > 0) {
	$_SESSION["emp_sched"] = array(
		"id" => $id,
		"empno" => $resultSchedTime["empno"],
		"datefrom" => $resultSchedTime["datefrom"],
		"dateto" => $resultSchedTime["dateto"],
	);
	header("location:compress-printedit/printedit_compressed.php?");
} else {
	unset($_SESSION["emp_sched"]);
}
// ---------------------------------------------------------------------------------------------------------------------------------//

$sql4 = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query4 = $HRconnect->query($sql4);
$row4 = $query4->fetch_array();


$mothercafe = $row4['mothercafe'];



$user = $row4['name'];
$userlevel = $row4['userlevel'];



if (isset($_GET['SubmitButton'])) {


	foreach ($_GET["id"] as $row => $value) {

		if ($userlevel == 'master') {

			$datefromto = mysqli_real_escape_string($HRconnect, $_GET['datefromto'][$row]);
			$fromtime1 = mysqli_real_escape_string($HRconnect, $_GET['fromtime'][$row]);
			$totime1 = mysqli_real_escape_string($HRconnect, $_GET['totime'][$row]);
			$break = mysqli_real_escape_string($HRconnect, $_GET['break'][$row]);
			$remarks = mysqli_real_escape_string($HRconnect, $_GET['remarks'][$row]);
			$idd = mysqli_real_escape_string($HRconnect, $_GET['idd'][$row]);
			// $timein1 = mysqli_real_escape_string($HRconnect, $_GET['timein1'][$row]);
			// $timein2 = mysqli_real_escape_string($HRconnect, $_GET['timein2'][$row]);
			// $timein3 = mysqli_real_escape_string($HRconnect, $_GET['timein3'][$row]);
			// $timein4 = mysqli_real_escape_string($HRconnect, $_GET['timein4'][$row]);
			// $timeaa = mysqli_real_escape_string($HRconnect, $_GET['timeaa'][$row]);
			// $timebb = mysqli_real_escape_string($HRconnect, $_GET['timebb'][$row]);
			// $timecc = mysqli_real_escape_string($HRconnect, $_GET['timecc'][$row]);
			// $timedd = mysqli_real_escape_string($HRconnect, $_GET['timedd'][$row]);
			$timein1 = "";
			$timein2 = "";
			$timein3 = "";
			$timein4 = "";
			$timeaa = "";
			$timebb = "";
			$timecc = "";
			$timedd = "";
			if ($totime1 >= '00:00' and $totime1 <= '07:00') {

				$out = date('Y-m-d', strtotime($datefromto . ' +1 day'));
				$outtime = $out . " " . $totime1;
			} else {

				$out = $datefromto;
				$outtime = $out . " " . $totime1;
			}

			$intime = date('Y-m-d', strtotime($datefromto)) . " " . $fromtime1;


			if ($timein1 == '') {
				$timein11 = "";
			} else {
				$timein11 = $datefromto . " " . $timein1;
			}

			if ($timein2 == '') {
				$timein22 = "";
			} elseif ($timein2 == 'No Break') {

				$timein22 = "No Break";
			} else {
				$timein22 = $datefromto . " " . $timein2;
			}

			if ($timein3 == '') {
				$timein33 = "";
			} elseif ($timein3 == 'No Break') {

				$timein33 = "No Break";
			} else {
				$timein33 = $datefromto . " " . $timein3;
			}

			if ($timein4 == '') {
				$timein44 = "";
			} else {

				if ($timein4 >= '00:00' and $timein4 <= '07:00') {

					$totaldate = date('Y-m-d', strtotime($datefromto . ' +1 day'));
				} else {

					$totaldate = $datefromto;
				}

				$timein44 = $totaldate . " " . $timein4;
			}


			$sqlupdate = " UPDATE sched_time 
      SET schedfrom  = '$intime',
        schedto  = '$outtime',
        break  = '$break',
       --  M_timein  = '$timein11',
       --  m_in_status  = 'Approved',
        -- M_timeout  = '$timein22',
        -- m_o_status  = 'Approved',
       --  A_timein  = '$timein33',
       --  a_in_status  = 'Approved',
       --  A_timeout  = '$timein44',
       --  a_o_status  = 'Approved',
      --   timein  = '$timeaa',
      --   breakout  = '$timebb',
      --   breakin  = '$timecc',
      --   timeout  = '$timedd',
      remarks  = '$remarks'  
      WHERE id = '$value'";

			$HRconnect->query($sqlupdate);
		}

		if (($userlevel == 'ac' or $userlevel == 'mod' or $userlevel == 'admin')) {

			$datefromto = mysqli_real_escape_string($HRconnect, $_GET['datefromto'][$row]);
			@$timefrom1 = mysqli_real_escape_string($HRconnect, $_GET['timefrom1'][$row]);
			@$timefrom2 = mysqli_real_escape_string($HRconnect, $_GET['timefrom2'][$row]);

			@$timeto1 = mysqli_real_escape_string($HRconnect, $_GET['timeto1'][$row]);
			@$timeto2 = mysqli_real_escape_string($HRconnect, $_GET['timeto2'][$row]);

			@$fromtime = $timefrom1 . ":" . $timefrom2;
			@$totime = $timeto1 . ":" . $timeto2;


			$break = mysqli_real_escape_string($HRconnect, $_GET['break'][$row]);
			$remarks = mysqli_real_escape_string($HRconnect, $_GET['remarks'][$row]);
			$idd = mysqli_real_escape_string($HRconnect, $_GET['idd'][$row]);

			if ($totime >= '00:00' and $totime <= '07:00') {

				$out = date('Y-m-d', strtotime($datefromto . ' +1 day'));
				$outtime = $out . " " . $totime;
			} else {

				$out = $datefromto;
				$outtime = $out . " " . $totime;
			}

			$intime = date('Y-m-d', strtotime($datefromto)) . " " . $fromtime;
			$arr_remarks = array(
				"AB" => "AB",
				"RD" => "RD",
				"NWD" => "NWD",
				"LWP" => "LWP",
				"ML" => "ML",
				"PL" => "PL",
				"NS" => "NS",
				"SPL" => "SPL",
				"BL" => "BL",
				"WDL" => "WDL",
			);

			$sqlupdate = "UPDATE sched_time 
		SET schedfrom = '$intime',
			schedto = '$outtime',
			break = '$break',
			remarks = '$remarks'" . (array_key_exists($remarks, $arr_remarks) ? ", work_hours = '{$arr_remarks[$remarks]}'" : "") . "  
		WHERE id = '$value'";

			$HRconnect->query($sqlupdate);
		}
	}

	$empno = mysqli_real_escape_string($HRconnect, $_GET['emp']);

	@$sqll = "SELECT * FROM user_info 
    where empno = '$empno'";
	@$queryy = $HRconnect->query($sqll);
	@$roww = $queryy->fetch_array();
	$name = $roww['name'];

	$date_time = date("Y-m-d h:i");
	$empno = $_SESSION['empno'];
	$inserted = "Successfully Saved";
	$action = $name . " - Edit Schedule";

	$sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno', '$action', '$inserted','$date_time')";
	$HRconnect->query($sql2);


	header("location:../pdf/printedit.php?id=$idd");
}



$sql7 = "SELECT * FROM sched_info WHERE id = '$id'";
$query7 = $HRconnect->query($sql7);
$row7 = $query7->fetch_array();

$empid = $row7["empno"];
$cutfrom = $row7["datefrom"];
$cutto = $row7["dateto"];
$schedfrom = $row7["schedfrom"];
$schedto = $row7["schedto"];


?>
<!DOCTYPE html>
<html lang="en">

<head>


	<title>Mary Grace Foods Inc.</title>
	<link rel="icon" href="../images/logoo.png">

	<!-- CSS libraries -->
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<!-- JavaScript libraries -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
		integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
		crossorigin="anonymous"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

	<!-- AJAX -->
	<script src="../js/ajax-call.js"></script>
	<!-- SWEET ALERT -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- MOMENT JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


	<script type="text/javascript">
		$('#time').timepicker({
			timeFormat: 'H:i',
			'scrollDefaultNow': 'true',
			'closeOnWindowScroll': 'true',
			'showDuration': false,
			'ignoreReadonly': true,
		})
	</script>

	<!------ Include the above in your HEAD tag ---------->

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

		.box-shadow {
			box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
		}

		.bg-dirty-white {
			background: #F6F6F6;
		}

		.disabled {
			pointer-events: none;
			user-select: none;
			opacity: 0.4;
		}

		.font-size-small {
			font-size: 80%;
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

		input[type=number] {
			width: 50%;
		}
	</style>


</head>


<body>

	<p style="page-break-before: always">

	<div class="col-12">
		<div class="d-flex">
			<a class="text-decoration-none text-primary d-flex align-items-center mb-2"
				href="../viewsched.php?current=current">
				<i class="fa fa-angle-left mr-3" aria-hidden="true"></i> Back
			</a>
		</div>

		<div class="border border-1 p-3 mb-2" style="width: 40em">
			<p class="m-0 font-weight-bold">Legend: </p>
			<div class="row">
				<div class="col-sm-6">
					<div class="col-sm-12">
						<p class="m-0">RD - Rest Day</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">AB - Absent</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">LWP - Leave w/o Pay</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">NWD - No Work Day</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">BL - Bereavement Leave</p>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="col-sm-12">
						<p class="m-0">WL - Wellness Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">ML - Maternity Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">PL - Paternity Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">SPL - Solo Parent Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">WDL - Wedding Leave</p>
					</div>
				</div>
				<div class="col-sm-6">

					<div class="col-sm-12">
						<p class="m-0">CS - Change Schedule</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">WDO - Working Day Off</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">OBP - Official Business Permit</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">NS - No Schedule</p>
					</div>
				</div>
			</div>
		</div>

		<form method="GET">
			<table class="myTable">

				<thead>

					<?php



					$sql = "SELECT * FROM sched_info 
							WHERE id = '$id'
							";
					$query = $HRconnect->query($sql);
					$row = $query->fetch_array();


					$sql1 = "SELECT * FROM user 
							WHERE userid = '$userid'
							";
					$query1 = $connect->query($sql1);
					$row1 = $query1->fetch_array();
					$name = $row1['username'];

					$sql2 = "SELECT * FROM user_info 
							WHERE empno = '$empid'
							";
					$query2 = $HRconnect->query($sql2);
					$row2 = $query2->fetch_array();
					$name1 = $row2['name'];
					$branch1 = $row2['branch'];
					$post = $row2['position'];

					include ("compress-sched/compress_access.php");
					?>

					<tr>
						<th colspan="100%" class="text-muted">
							<div class="d-flex align-items-center justify-content-between text-uppercase">
								<p class="m-0">Employee # : <b class="text-danger">
										<?php echo $empid; ?>
									</b></p>
								<div class="<?php echo $toggle_access; ?>">
									<button type="button" class="btn-sm btn-primary" id="compress-sched-btn"
										value="<?php echo $empid ?>">Manage Schedule</button>
								</div>
							</div>
							<div class="row">
								<div class="col-5 text-uppercase">
									</b>
								</div>
							</div>
							<div class="row d-flex align-items-end">
								<div class="col-5">
									<p class="text-uppercase">
										Name: <b>
											<?php echo $name1; ?>
										</b><br />
										Branch/Dept: <b>
											<?php echo $branch1; ?>
										</b>
									</p>
								</div>

								<div class="col-3">
								</div>

								<div class="col-4">
									<?php
									include ("../Function/compress_schedule_func.php");
									include ("compress-sched/compress_schedule_modals.php");
									?>

									<input type="text" class="d-none cutoff-sched" value="<?php echo $cutfrom; ?>">
									<input type="text" class="d-none cutoff-sched" value="<?php echo $cutto; ?>">
									<p class="text-uppercase">
									<p class="font-weight-bold text-secondary text-uppercase float-right">Regular
										Schedule</p>
									</p>
								</div>
							</div>
						</th>
					</tr>

					<tr class="text-uppercase">
						<th rowspan="2" colspan="2">
							<center><b>Cut-off Date</b></center>
						</th>
						<th rowspan="2" width="23%">
							<center><b>Schedule</b></center>
						</th>
						<th rowspan="2" width="10%">
							<center><b>Break</b></center>
						</th>
						<th colspan="4">
							<center><b></b></center>
						</th>
						<th rowspan="2" colspan="2">
							<center><b>Remarks</b></center>
						</th>
						<th rowspan="2">
							<center><b>Action</b></center>
						</th>

					</tr>

					<tr class="text-uppercase">
						<th>
							<center><b>Time in</b></center>
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
					</tr>


				</thead>


				<tbody>
					<?php

					$sql1 = "SELECT * FROM sched_time 
				WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND status != 'deleted' ORDER BY datefromto ASC ";
					$query1 = $HRconnect->query($sql1);
					while ($row1 = $query1->fetch_array()) {

						$status = $row1['status'];
						$datefrom = $row1['schedfrom'];
						$datefromto = $row1['datefromto'];
						$dateto = $row1['schedto'];

						@$mtimein = $row1['M_timein'];
						@$m_in_status = $row1['m_in_status'];
						@$min_empno = $row1['min_empno'];

						@$mtimeout = $row1['M_timeout'];
						@$m_o_status = $row1['m_o_status'];
						@$mo_empno = $row1['mo_empno'];

						@$atimein = $row1['A_timein'];
						@$a_in_status = $row1['a_in_status'];
						@$ain_empno = $row1['ain_empno'];


						@$atimeout = $row1['A_timeout'];
						@$a_o_status = $row1['a_o_status'];
						@$ao_empno = $row1['ao_empno'];

						@$otimein = $row1['O_timein'];
						@$o_in_status = $row1['o_in_status'];
						@$oin_empno = $row1['oin_empno'];

						@$otimeout = $row1['O_timeout'];
						@$o_o_status = $row1['o_o_status'];
						@$oo_empno = $row1['oo_empno'];

						@$break = $row1['break'];
						@$breaktotal = $break * 10000;

						$sql8 = " SELECT ADDTIME('$mtimeout','$breaktotal') as zxc FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')
							AND M_timeout != 'null'";

						$query8 = $HRconnect->query($sql8);
						$row8 = $query8->fetch_array();
						$totals = $row8['zxc'];

						if ($atimein > $totals) {


							$totalsss = strtotime($atimein) - strtotime($totals);

							@$ada += $totalsss;
						}

						$sql11 = "SELECT * FROM overunder
                    	  WHERE otstatus = 'approved' AND empno = $empid
                    	  AND otdatefrom = '$datefromto'";
						$query11 = $HRconnect->query($sql11);
						$row11 = $query11->fetch_array();

						$datecutoff = date("Y-m-d");


						?>

						<?php
						// Para Hindi ma posted yung schedule  icomment to /* OR ($cutto < $datecutoff  AND $userlevel != 'master') */		
						if (($status == 'approved' and $userlevel != 'master' and $userlevel != 'mod' and $userlevel != 'ac' and $userlevel != 'admin' and $mothercafe != 109) /* OR ($cutto < $datecutoff  AND $userlevel != 'master') */) {

							?>

							<tr>

								<td colspan="2">
									<center>
										<?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?>
										<center>
								</td>
								<td>
									<center>
										<?php echo date("H:i", strtotime($datefrom)); ?> -
										<?php echo date("H:i", strtotime($dateto)); ?>
									</center>
								</td>
								<td>
									<center>
										<?php echo $break; ?>
									</center>
								</td>
								<td>
									<center>
										<?php if ($m_in_status == 'Approved' or $min_empno != '' or $row1['M_timein'] == '') {

											if ($row1['M_timein'] != '') {
												echo date('H:i', strtotime($row1['M_timein']));
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>
									</center>
								</td>
								<td>
									<center>
										<?php if ($m_o_status == 'Approved' or $mo_empno != '' or $row1['M_timeout'] == '') {


											if ($row1['M_timeout'] != '' and $row1['M_timeout'] != 'No Break') {
												echo date('H:i', strtotime($row1['M_timeout']));
											} elseif ($row1['M_timeout'] == 'No Break') {
												echo $row1['M_timeout'];
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>
									</center>
								</td>
								<td>
									<center>
										<?php if ($a_in_status == 'Approved' or $ain_empno != '' or $row1['A_timein'] == '') {

											if ($row1['A_timein'] != '' and $row1['A_timein'] != 'No Break') {
												echo date('H:i', strtotime($row1['A_timein']));
											} elseif ($row1['A_timein'] == 'No Break') {
												echo $row1['A_timein'];
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>
									</center>
								</td>
								<td>
									<center>

										<?php if ($a_o_status == 'Approved' or $ao_empno != '' or $row1['A_timeout'] == '') {

											if ($row1['A_timeout'] != '') {
												echo date('H:i', strtotime($row1['A_timeout']));
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>
									</center>
								</td>

								<td class="text-uppercase" colspan="2">
									<center>
										<?php echo $row1['remarks']; ?>
									</center>
								</td>
								<td class="text-success">
									<center><b>POSTED</b></center>
								</td>
							</tr>
							<?php
						} elseif (($userlevel == 'mod' or $userlevel == 'ac' or $userlevel == 'admin') and $userlevel != 'master') {


							$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
							if (($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) {
							} else {
							}


							?>

							<tr>
								<input type="text" name="idd[]" hidden value="<?php echo $id; ?>">
								<input type="text" name="datefromto[]" hidden value="<?php echo $row1['datefromto']; ?>">
								<input type="text" name="id[]" hidden value="<?php echo $row1['id']; ?>">
								<input type="text" name="emp" hidden value="<?php echo $row1['empno']; ?>">
								<td colspan="2">
									<center>
										<?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?>
										<center>
								</td>
								<td>
									<center>

										<label>
											<select class="custom-select" <?php
											$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
											if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {
											} elseif ($row1['M_timein'] == '') {
											} else {
												echo 'style="color :red;"';
											} ?> name="timefrom1[]">
												<option selected>
													<?php echo date("H", strtotime($row1['schedfrom'])); ?>
												</option>
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
										<b>:</b>
										<label>
											<select class="custom-select" <?php
											$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
											if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {
											} elseif ($row1['M_timein'] == '') {
											} else {
												echo 'style="color :red;"';
											} ?> name="timefrom2[]">
												<option selected>
													<?php echo date("i", strtotime($row1['schedfrom'])); ?>
												</option>
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


										&nbsp to &nbsp

										<label>

											<select class="custom-select" <?php
											$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
											if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {
											} elseif ($row1['M_timein'] == '') {
											} else {
												echo 'style="color :red;"';
											} ?> name="timeto1[]">
												<option selected>
													<?php echo date("H", strtotime($row1['schedto'])); ?>
												</option>
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
										<b>:</b>
										<label>
											<select class="custom-select" <?php

											$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
											if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {
											} elseif ($row1['M_timein'] == '') {
											} else {
												echo 'style="color :red;"';
											} ?> name="timeto2[]">
												<option selected>
													<?php echo date("i", strtotime($row1['schedto'])); ?>
												</option>
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
								</td>

								<td>
									<center><input type="number" min="0" max="9" name="break[]"
											class="form-control text-center breaks" value="<?php echo $row1['break']; ?>">
										<center>
								</td>

								<?php
								if ($datefrom < $mtimein) {
									?>

									<td class="text-danger"><b>

											<?php
								} else {
									?>
									<td>
										<?php
								}
								?>
									<center>
										<?php if ($m_in_status == 'Approved' or $min_empno != '' or $row1['M_timein'] == '') {

											if ($row1['M_timein'] != '') {
												echo date('H:i', strtotime($row1['M_timein']));
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>

									</center></b>
								</td>
								<td>
									<center>


										<?php if ($m_o_status == 'Approved' or $mo_empno != '' or $row1['M_timeout'] == '') {


											if ($row1['M_timeout'] != '' and $row1['M_timeout'] != 'No Break') {
												echo date('H:i', strtotime($row1['M_timeout']));
											} elseif ($row1['M_timeout'] == 'No Break') {
												echo $row1['M_timeout'];
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}


										?>

										<b></b>
									</center>
								</td>


								<?php
								if ($totals < $atimein and $atimein != 'No Break') {
									?>
									<td class="text-danger"><b>

											<?php
								} else {
									?>
									<td>

										<?php
								}
								?>
									<center>

										<?php if ($a_in_status == 'Approved' or $ain_empno != '' or $row1['A_timein'] == '') {

											if ($row1['A_timein'] != '' and $row1['A_timein'] != 'No Break') {
												echo date('H:i', strtotime($row1['A_timein']));
											} elseif ($row1['A_timein'] == 'No Break') {
												echo $row1['A_timein'];
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}


										?>
									</center></b>
								</td>

								<?php

								$outtime = strtotime($atimeout);
								$startime = strtotime($dateto);

								if ($startime > $outtime) {
									?>

									<td class="text-danger"><b>

											<?php
								} else {
									?>
									<td>


										<?php
								}
								?>
									<center>

										<?php if ($a_o_status == 'Approved' or $ao_empno != '' or $row1['A_timeout'] == '') {

											if ($row1['A_timeout'] != '') {
												echo date('H:i', strtotime($row1['A_timeout']));
											} else {
												echo "";
											}
										} else {

											echo "Pending";
										}

										?>

										</b>
									</center>
								</td>
								<td class="text-uppercase" colspan="2">
									<center>
										<select class="m-0 px-3" name="remarks[]">
											<option value=""></option>
											<option value="RD" <?php echo ($row1['remarks'] == "RD") ? "selected" : ""; ?>>RD
											</option>
											<option value="AB" <?php echo ($row1['remarks'] == "AB") ? "selected" : ""; ?>>AB
											</option>
											<option value="BL" <?php echo ($row1['remarks'] == "BL") ? "selected" : ""; ?>>BL
											</option>
											<option value="NS" <?php echo ($row1['remarks'] == "NS") ? "selected" : ""; ?>>NS
											</option>
											<option value="WDL" <?php echo ($row1['remarks'] == "WDL") ? "selected" : ""; ?>>WDL
											</option>
											<option value="ML" <?php echo ($row1['remarks'] == "ML") ? "selected" : ""; ?>>ML
											</option>
											<option value="PL" <?php echo ($row1['remarks'] == "PL") ? "selected" : ""; ?>>PL
											</option>
											<option value="SPL" <?php echo ($row1['remarks'] == "SPL") ? "selected" : ""; ?>>SPL
											</option>
											<option value="LWP" <?php echo ($row1['remarks'] == "LWP") ? "selected" : ""; ?>>LWP
											</option>
											<?php
											if (!in_array($row1['remarks'], ["LWP", "RD", "AB", "BL", "WDL", "SPL", "PL", "ML", "NS", ""])) {
												echo '<option value="' . $row1['remarks'] . '" selected>' . $row1['remarks'] . '</option>';
											}
											?>
										</select>
									</center>
								</td>
								<td>
									<input type="submit" class="btn btn-outline-success btn-user btn-block btn1" value="Save"
										name="SubmitButton"
										onclick="return confirm('Are you sure you want to Save This Record?');">
								</td>
							</tr>
							<?php
						}

						if ($userlevel == 'master') {

							?>


							<tr>
								<input type="text" name="idd[]" hidden value="<?php echo $id; ?>">
								<input type="text" name="datefromto[]" hidden value="<?php echo $row1['datefromto']; ?>">
								<input type="text" name="id[]" hidden value="<?php echo $row1['id']; ?>">
								<input type="text" name="emp" hidden value="<?php echo $row1['empno']; ?>">

								<td colspan="2">
									<center>
										<?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?>
										<center>
								</td>
								<td>
									<center><input type="text" <?php
									$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
									if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {
									} elseif ($row1['M_timein'] == '') {

									} else {
										echo 'style="color :red;"';
									} ?> class="form-control text-center" name="fromtime[]"
											value="<?php echo date("H:i", strtotime($row1['schedfrom'])); ?>"> - <input
											type="text" <?php
											$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom'])) / 3600;
											if ((($equal == 19 and $row1['break'] == 10) or ($equal == 17 and $row1['break'] == 9) or ($equal == 16 and $row1['break'] == 8) or ($equal == 15 and $row1['break'] == 7) or ($equal == 14 and $row1['break'] == 6) or ($equal == 13 and $row1['break'] == 5) or ($equal == 12 and $row1['break'] == 4) or ($equal == 11 and $row1['break'] == 3) or ($equal == 10 and $row1['break'] == 2) or ($equal == 9 and $row1['break'] == 1) or ($equal == 8 and $row1['break'] == 0)) and $row1['M_timein'] != '') {


											} elseif ($row1['M_timein'] == '') {

											} else {
												echo 'style="color :red;"';
											} ?> name="totime[]" class="form-control text-center"
											value="<?php echo date("H:i", strtotime($row1['schedto'])); ?>"></center>

								</td>
								<td>
									<center><input type="text" name="break[]" class="form-control text-center breaks"
											value="<?php echo $row1['break']; ?>">
										<center>
								</td>

								<td>
									<center>
										<span><?php echo $row1['M_timein'] != '' ? date("H:i", strtotime($row1['M_timein'])) : ''; ?></span>
										<center>
								</td>
								<?php if ($row1['M_timeout'] == 'No Break') { ?>
									<td>
										<center><span><?php echo $row1['M_timeout']; ?></span>
										<?php } else { ?>
									<td>
										<center>
											<span><?php echo $row1['M_timeout'] != '' ? date("H:i", strtotime($row1['M_timeout'])) : ''; ?></span>
											<center>
									</td>
								<?php }

								if ($row1['A_timein'] == 'No Break') { ?>
									<td>
										<center><span><?php echo $row1['A_timein']; ?></span>
										<?php } else { ?>
									<td>
										<center>
											<span><?php echo $row1['A_timein'] != '' ? date("H:i", strtotime($row1['A_timein'])) : ''; ?></span>
											<center>
									</td>
								<?php } ?>

								<td>
									<center>
										<span><?php echo $row1['A_timeout'] != '' ? date("H:i", strtotime($row1['A_timeout'])) : ''; ?></span>
										<center>
								</td>
								<td class="text-uppercase" colspan="2">
									<center>
										<select class="m-0 px-3" name="remarks[]">
											<option value=""></option>
											<option value="RD" <?php echo ($row1['remarks'] == "RD") ? "selected" : ""; ?>>RD
											</option>
											<option value="AB" <?php echo ($row1['remarks'] == "AB") ? "selected" : ""; ?>>AB
											</option>
											<option value="BL" <?php echo ($row1['remarks'] == "BL") ? "selected" : ""; ?>>BL
											</option>
											<option value="NS" <?php echo ($row1['remarks'] == "NS") ? "selected" : ""; ?>>NS
											</option>
											<option value="WDL" <?php echo ($row1['remarks'] == "WDL") ? "selected" : ""; ?>>WDL
											</option>
											<option value="ML" <?php echo ($row1['remarks'] == "ML") ? "selected" : ""; ?>>ML
											</option>
											<option value="PL" <?php echo ($row1['remarks'] == "PL") ? "selected" : ""; ?>>PL
											</option>
											<option value="SPL" <?php echo ($row1['remarks'] == "SPL") ? "selected" : ""; ?>>SPL
											</option>
											<option value="LWP" <?php echo ($row1['remarks'] == "LWP") ? "selected" : ""; ?>>LWP
											</option>
											<?php
											if (!in_array($row1['remarks'], ["LWP", "RD", "AB", "BL", "WDL", "SPL", "PL", "ML", "NS", ""])) {
												echo '<option value="' . $row1['remarks'] . '" selected>' . $row1['remarks'] . '</option>';
											}
											?>
										</select>
									</center>
								</td>
								<td><input type="submit" class="btn btn-outline-success btn-user btn-block btn1" value="Save"
										name="SubmitButton"
										onclick="return confirm('Are you sure you want to Save This Record?');"></td>
							</tr>

							<?php
						}
					}

					?>

				</tbody>


			</table>



		</form>

	</div>






	</p>
</body>


</html>