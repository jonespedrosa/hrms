<?php 
	session_start();
    require("compress_dtr_data.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Mary Grace Foods Inc.</title>
        <link rel="icon" href="../../images/logoo.png"> 
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="../../js/compressed-ajax-calculation.js"></script>
        <!------ Include the above in your HEAD tag ---------->

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
            .myTable td{
                text-align: center;
                padding: 5px;
                border: 2px solid black; 
            }
            .myTable th { 
                padding: 5px;
                border: 2px solid black; 
            }
        </style>
    </head>
    <body>
        <p style="page-break-before: always"> 
            <div class="col-12">
                <table class="myTable">
                    <thead> 
                        <!-- HEADER -->
                        <?php require("compress-head.php");?>
                    </thead>
                    <tbody>
                        <!-- INSERT DYNAMIC TABLE -->
                        <?php require("compress-body.php");?>
                    </tbody>
                    <tfoot>
                        <!-- INSERT DYNAMIC FOOTER -->
                        <?php require("compress-footer.php");?>
                    </tfoot>
                </table>
                <p class="text-muted">
                    <i>I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, report of which was made daily at the time of arrival at the departure from office.</i>
                </p>
            </div>
            <div class="container-fluid">
                <div class="border border-1 p-3 mb-2">
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
				</div>
				<div class="col-sm-6">
					<div class="col-sm-12">
						<p class="m-0">WL - Wellness Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">ML - Maternity Leave</p>
					</div>
					<div class="col-sm-12">
						<p class="m-0">SPL - Solo Parent Leave</p>
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
				</div>
			</div>
                </div>
            </div>
        </p>
    </body>
</html>