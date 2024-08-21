<?php
session_start();
require("timesheet-data.php");
require("../../Function/cut_off.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mary Grace Foods Inc.</title>
	<link rel="icon" href="../../images/logoo.png">
	<!-- Custom fonts for this template -->
	<link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">
	<!-- Custom styles for this template -->
	<link href="../../css/sb-admin-2.min.css" rel="stylesheet">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- SWAL -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- Styles -->
	<link rel="stylesheet" href="timesheet-style.css">
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
	<!-- DataTables Buttons CSS -->
	<link rel="stylesheet" type="text/css"
		href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
	<!-- DataTables JS -->
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
	<!-- DataTables Buttons JS -->
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<!-- JSZip -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<!-- PDFMake -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<!-- Buttons HTML5 -->
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
	<!-- Buttons Print -->
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
	<!-- SheetJS -->
	<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
	<!-- Popper.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<!-- Bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<!-- Kendo UI -->
	<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css" />
	<script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>
	<!-- Ajax Timesheet -->
	<script src="ajax-timesheet.js"></script>
</head>

<body>
	<div class="container-fluid mt-4">
		<form id="form-get-date" action="" method="GET">
			<div class="form-group row">
				<div class="col-sm-4 col-md-3 col-lg-2 text-center">
					<label>Cut-Off Date From</label>
					<input type="date" id="cutfrom" class="form-control text-center" name="cutfrom"
						placeholder="Insert Date" value="" required />
				</div>

				<div class="col-sm-4 col-md-3 col-lg-2 text-center">
					<label>Cut-Off Date To</label>
					<input type="date" id="cutto" class="form-control text-center" name="cutto"
						placeholder="Insert Date" value="" required />
				</div>

				<div class="col-sm-3 col-md-2 col-lg-1 text-center">
					<label class="invisible">.</label>
					<input class="btn btn-primary btn-block bg-gradient-primary rounded-pill" type="submit"
						name="submit" id="submit" value="Submit"
						onclick="return confirm('Are you sure you want to Insert this Data?');" />
				</div>
			</div>
		</form>
	</div>
	<br>

	<div class="container-fluid">
		<!-- DataTables Example -->
		<div class="card mb-3 ">
			<div class="card-header d-flex align-items-center justify-content-between">
				<div class="d-flex align-items-center">
					<i class="fa fa-clock d-none d-lg-block" aria-hidden="true"></i>
					<p class="m-0 mx-2 d-none d-lg-block">TIMESHEET -</p>
					<a href="#">
						<?php echo $CURRENT_DATE; ?>
					</a>
				</div>
				<div class="d-flex align-items-center bg-white px-3">
					<?php require("timesheet-dept.php"); ?>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive ">
					<form method="get">
						<table class="myTable table-hover" id="generateTimesheetTable" width="100%" cellspacing="0">
							<thead class="table-secondary">
								<?php
								require("timesheet-head.php");
								require("timesheet-placeholder.php");
								?>
							</thead>
							<tbody id="timesheet-body">
							</tbody>
							<tfoot>
								<tr>
									<th class="text-center"></th>
									<th colspan="2" class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
									<th class="text-center"></th>
								</tr>
							</tfoot>
						</table>
					</form>
				</div>
			</div>
			<div class="container-fluid d-flex justify-content-between mb-3">
				<a href="../../discrepancy.php" class="btn btn-secondary btn-user ">Back</a>
				<div class="d-flex">
					<a class="text-decoration-none btn" id="regenerate-record">Regenerate Timesheet</a>
					<button class="btn btn-primary btn-user d-flex align-items-center" id="save-record">
						<span class="" role="status" aria-hidden="true"></span>
						<p class="m-0 d-sm-block d-none">Save Record</p>
					</button>
				</div>

			</div>
		</div>
	</div>

	<div id="cutfrom_date" type="text" class="d-none">
		<?php echo date("Y-m-d", strtotime($current_cutfrom)); ?>
	</div>
	<div id="cutto_date" type="text" class="d-none">
		<?php echo date("Y-m-d", strtotime($current_cutto)); ?>
	</div>
</body>

</html>