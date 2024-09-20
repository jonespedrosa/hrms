<!-- Begin Page Content --> <!-- Search -->
<?php
$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();
if (empty($_SESSION['user'])) {
	header('location:login.php');
}

$sql = "SELECT name, empno, userlevel, userid FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$user = $row['name'];
$userlevel = $row['userlevel'];
$userid = $row['userid'];

if ($userlevel != 'staff') {

?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title> </title>
		<link rel="icon" href="../images/logoo.png">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

		<!-- Custom fonts for this template-->
		<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link
			href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
			rel="stylesheet">

		<!-- Custom styles for this template-->
		<link href="../css/sb-admin-2.min.css" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="../../Projection/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="../../Projection/css/sb-admin.css" rel="stylesheet">
		<!-- Include DataTables CSS -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
		<!-- Include only one version of jQuery -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<!-- Include DataTables JS after jQuery -->
		<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
		<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
		<!-- Bootstrap core JavaScript-->
		<script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<!-- Core plugin JavaScript-->
		<script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
		<!-- Page level plugin JavaScript-->
		<script src="../../vendor/chart.js/Chart.min.js"></script>
		<!-- Custom scripts for all pages-->
		<script src="../../js/sb-admin.min.js"></script>
		<!-- Demo scripts for this page-->
		<script src="../../js/demo/datatables-demo.js"></script>
		<script src="../../js/demo/chart-area-demo.js"></script>
		<!-- Calendar Restriction-->
		<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css" />
		<script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>


		<!-- Optional additional scripts -->
		<script src="../../../../examples/resources/demo.js"></script>

		<style>
			@page {
				size: portrait
			}

			body {
				page-break-before: avoid;
				font-size: 15px;

			}

			@media print {

				.table td {
					background-color: transparent !important;
				}

				.table th {
					background-color: transparent !important;
				}
			}

			.myTable {
				width: 100%;
				font-size: 15px;
				text-align: center;
				background-color: white;
				border-collapse: collapse;
			}

			.myTable th {
				text-transform: uppercase;
				background-color: secondary;
				color: black;
			}

			.myTable td,
			.myTable th {
				padding: 5px;
				border: 1px solid black;

			}
		</style>
	</head>

	<body>
		<br>
		<div class="container-fluid">
			<?php
			@$_SESSION['datedatefrom'] = $_POST['datefrom4'];
			@$_SESSION['datedateto'] = $_POST['dateto4'];

			@$datefrom = date("Y-m-d", strtotime($_SESSION['datedatefrom']));
			@$dateto = date("Y-m-d", strtotime($_SESSION['datedateto']));
			?>
			<form class="user" method="post">
				<div class="form-group row">
					<?php if ($datefrom == "1970-01-01") { ?>
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date From</label>
							<input type="date" id="datePicker" class="form-control text-center" name="datefrom4" placeholder="Insert Date" autocomplete="off" required onkeypress="return false;" />
						</div>
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4" placeholder="Insert Date" autocomplete="off" required onkeypress="return false;" />
						</div>
					<?php } else { ?>
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date From</label>
							<input type="date" id="datePicker" class="form-control text-center" name="datefrom4" placeholder="Insert Date" value="<?php echo $datefrom; ?>" autocomplete="off" required onkeypress="return false;" />
						</div>
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4" placeholder="Insert Date" value="<?php echo $dateto; ?>" autocomplete="off" required onkeypress="return false;" />
						</div>
					<?php } ?>
					<div class="col-xs-3 text-center d-none d-sm-inline-block">
						<label class="invisible">.</label>
						<div class="col-xs-3 text-center d-none d-sm-inline-block">
						</div>
						<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" name="submit" id="submit" class="btn btn-info" value="Generate" onclick="return confirm('Are you sure you want to generate report?');" />
					</div>
					<div class="col-sm-3 text-center d-md-none">
						<label class="invisible">.</label>
						<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" name="submit" id="submit" class="btn btn-info" value="Generate" onclick="return confirm('Are you sure you want to generate report?');" />
					</div>
				</div>
			</form>
			<br>
			<!-- DataTables Example -->
			<div class="card mb-3 ">
				<div class="card-header">
					<div class="small">
						<span class="fw-500 text-primary"><?php echo date('l'); ?></span>,
						<?php echo date('F d, Y - h:i:s A'); ?>
					</div>
				</div>
				<div class="card-body">
					<div class="table-responsive ">
						<table class="myTable table-hover" id="example" width="100%" cellspacing="0">
							<thead class="table-secondary text-uppercase">
								<tr>
									<th class="text-center">ID</th>
									<th class="text-center">FULLNAME</th>
									<th class="text-center">BRANCH</th>
									<th class="text-center">APPROVED LEAVE</th>
									<th class="text-center">REMAINING LEAVE</th>
									<th class="text-center">TOTAL</th>
									<th class="text-center">ACTION</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$useridleave = $_GET['branch'];
								if ($_GET['branch'] == '') {
									echo "<script type='text/javascript'>alert('No selected department. Please select first.'); window.location.href='../leave.php?pending=pending'</script>";
								} else {
									$sqljoinleave = "SELECT DISTINCT user_info.empno, user_info.name, user_info.branch FROM user_info JOIN vlform ON user_info.empno = vlform.empno WHERE vlstatus = 'approved' AND user_info.userid = $useridleave AND (vldatefrom BETWEEN'$datefrom' AND '$dateto')";
									$query_leave = $HRconnect->query($sqljoinleave);
									while ($row_leave = $query_leave->fetch_array()) {
										$empno = $row_leave['empno'];
										$name = $row_leave['name'];
										$branch = $row_leave['branch'];
										$sqlapprovedleave = "SELECT SUM(vlhours) as sum_vl FROM vlform WHERE empno = '$empno' AND (vldatefrom between '$datefrom' AND '$dateto') AND vlstatus = 'approved'";
										$leaveapprove = $HRconnect->query($sqlapprovedleave);
										$row_vlApproved = $leaveapprove->fetch_array();
										$countApproveLeave = $row_vlApproved['sum_vl'];
										$sqlVLremaining = "SELECT vl FROM user_info WHERE empno = '$empno'";
										$queryRemainingVl = $HRconnect->query($sqlVLremaining);
										$vlRemainingRow = $queryRemainingVl->fetch_array();
										$vlRemaining = $vlRemainingRow['vl'];
										$sumRemainingvsApprove = $countApproveLeave + $vlRemaining;
								?>
										<?php if ($row_vlApproved['sum_vl'] != 0) { ?>
											<tr class="text-uppercase">
												<td>
													<center><?php echo $empno; ?></center>
												</td>
												<td>
													<center><?php echo $name; ?></center>
												</td>
												<td>
													<center><?php echo $branch; ?></center>
												</td>
												<td>
													<center><?php echo $countApproveLeave; ?></center>
												</td>
												<td>
													<center><?php echo $vlRemaining; ?></center>
												</td>
												<td>
													<center><?php echo $sumRemainingvsApprove; ?></center>
												</td>
												<td>
													<center><a href='leave_credits.php?leave=leave&empno=<?php echo $empno; ?>&datefrom=<?php echo $datefrom; ?>&dateto=<?php echo $dateto; ?>' class='btn btn-info btn-user btn-sm btn-block bg-gradient-info ' target='_blank'>VIEW APPROVED</a></center>
												</td>
											</tr>
										<?php } ?>
								<?php
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr class="text-uppercase">
									<th colspan="3">
										<center>TOTAL</center>
									</th>
									<th>
										<center id="totalApprovedLeave"></center>
									</th>
									<th>
										<center id="totalRemainingLeave"></center>
									</th>
									<th>
										<center id="totalLeave"></center>
									</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="card-body">
					<a href="../leave.php?pending=pending" class="btn btn-secondary btn-user ">BACK</a>
				</div>
			</div>
		</div>


		<script>
			$("#datePicker").kendoDatePicker({
				disableDates: function(date) {
					var disabled = [];
					if (date && disabled.indexOf(date.getDate()) > -1) {
						return true;
					} else {
						return false;
					}
				}
			});

			$("#datePicker1").kendoDatePicker({
				disableDates: function(date) {
					var disabled = [];
					if (date && disabled.indexOf(date.getDate()) > -1) {
						return true;
					} else {
						return false;
					}
				}
			});

			$(document).ready(function() {
				if (!$.fn.DataTable.isDataTable('#example')) {
					$('#example').DataTable({
						stateSave: true,
						paging: false, // Disable pagination
						info: false, // Disable table information
						dom: 'Bfrtip',
						buttons: [{
								extend: 'copy',
								text: 'Copy',
								exportOptions: {
									// Specify columns to include, excluding the last column (ACTION column)
									columns: function(index, data, node) {
										// Exclude the last column (ACTION column)
										return index !== 6; // Assuming ACTION column is the last column (index 6)
									}
								},
								customize: function(data) {
									// Include footer totals in the copied data
									var totalApprovedLeave = $("#totalApprovedLeave").text();
									var totalRemainingLeave = $("#totalRemainingLeave").text();
									var totalLeave = $("#totalLeave").text();

									// Add the footer data to the copied text
									data += '\n\nTOTAL\n';
									data += 'Approved Leave: ' + totalApprovedLeave + '\n';
									data += 'Remaining Leave: ' + totalRemainingLeave + '\n';
									data += 'Total Leave: ' + totalLeave;

									return data;
								}
							},
							{
								extend: 'excel',
								customize: function(xlsx) {
									var sheet = xlsx.xl.worksheets['sheet1.xml'];

									// Remove the last column (ACTION column)
									$('row', sheet).each(function() {
										var row = $(this);
										row.find('c:last').remove(); // Remove last cell in each row
									});

									// Adjust the footer cells to match the new column count
									var footerCells = [{
											v: "TOTAL",
											t: "s"
										},
										{
											v: "",
											t: "s"
										},
										{
											v: "",
											t: "s"
										},
										{
											v: $("#totalApprovedLeave").text(),
											t: "s"
										},
										{
											v: $("#totalRemainingLeave").text(),
											t: "s"
										},
										{
											v: $("#totalLeave").text(),
											t: "s"
										}
									];

									// Insert footer row
									var lastRowIndex = $('row', sheet).length + 1;
									var footerRow = '<row r="' + lastRowIndex + '">';
									footerCells.forEach(function(cell, index) {
										var cellRef = String.fromCharCode(65 + index) + lastRowIndex;
										footerRow += '<c t="' + cell.t + '" r="' + cellRef + '"><v>' + cell.v + '</v></c>';
									});
									footerRow += '</row>';

									$('sheetData', sheet).append(footerRow);
								}
							},
							{
								extend: 'pdf',
								text: 'PDF',
								customize: function(doc) {
									// Ensure that content and table are defined
									if (doc.content && doc.content[1] && doc.content[1].table && doc.content[1].table.body) {
										// Remove the last column from each row
										doc.content[1].table.body.forEach(function(row) {
											if (row.length > 0) {
												row.pop(); // Remove the last cell
											}
										});

										// Add footer with totals
										doc.content[1].table.body.push([{
												text: 'TOTAL',
												bold: true
											},
											{},
											{},
											{
												text: $("#totalApprovedLeave").text()
											},
											{
												text: $("#totalRemainingLeave").text()
											},
											{
												text: $("#totalLeave").text()
											}
										]);
									}
								}
							},
							{
								extend: 'print',
								messageTop: '<center class="text-uppercase">CONSOLIDATED LEAVE REPORT <?php echo date('F d, Y'); ?> </center>',
								customize: function(win) {
									// Ensure the total values are correctly retrieved
									var totalApprovedLeave = $("#totalApprovedLeave").text();
									var totalRemainingLeave = $("#totalRemainingLeave").text();
									var totalLeave = $("#totalLeave").text();

									// Wait for the document to be fully ready before modifying it
									$(win.document.body).find('table').each(function() {
										// Ensure we're targeting the correct table
										var table = $(this);

										// Remove the ACTION column from the table
										table.find('thead th:last-child, tbody td:last-child, tfoot th:last-child').remove();

										// Add footer if it doesn't exist
										if (table.find('tfoot').length === 0) {
											table.append('<tfoot><tr class="text-uppercase">' +
												'<th colspan="3"><center>TOTAL</center></th>' +
												'<th><center>' + totalApprovedLeave + '</center></th>' +
												'<th><center>' + totalRemainingLeave + '</center></th>' +
												'<th><center>' + totalLeave + '</center></th>' +
												'</tr></tfoot>');
										} else {
											// Update existing footer
											table.find('tfoot').html('<tr class="text-uppercase">' +
												'<th colspan="3"><center>TOTAL</center></th>' +
												'<th><center>' + totalApprovedLeave + '</center></th>' +
												'<th><center>' + totalRemainingLeave + '</center></th>' +
												'<th><center>' + totalLeave + '</center></th>' +
												'</tr>');
										}
									});
								}
							}
						],
						footerCallback: function(row, data, start, end, display) {
							var api = this.api();

							var intVal = function(i) {
								if (typeof i === 'number') {
									return i;
								} else if (typeof i === 'string') {
									var cleanValue = i.replace(/<\/?[^>]+>/gi, '').trim();
									var numericValue = parseFloat(cleanValue);
									if (isNaN(numericValue)) {
										console.warn("Non-numeric value:", cleanValue);
									}
									return isNaN(numericValue) ? 0 : numericValue;
								} else {
									console.warn("Unexpected value type:", i);
									return 0;
								}
							};

							var totalApprovedLeave = api.column(3).data().reduce(function(a, b) {
								return intVal(a) + intVal(b);
							}, 0);

							var totalRemainingLeave = api.column(4).data().reduce(function(a, b) {
								return intVal(a) + intVal(b);
							}, 0);

							var totalLeave = api.column(5).data().reduce(function(a, b) {
								return intVal(a) + intVal(b);
							}, 0);

							var formatNumber = function(num) {
								return num.toLocaleString('en-US', {
									minimumFractionDigits: 2,
									maximumFractionDigits: 2
								});
							};

							$(api.column(3).footer()).html('<center id="totalApprovedLeave">' + formatNumber(totalApprovedLeave) + '</center>');
							$(api.column(4).footer()).html('<center id="totalRemainingLeave">' + formatNumber(totalRemainingLeave) + '</center>');
							$(api.column(5).footer()).html('<center id="totalLeave">' + formatNumber(totalLeave) + '</center>');
						}
					});
				}
			});
		</script>

	</body>

	</html>
<?php
}
?>