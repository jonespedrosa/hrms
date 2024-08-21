<!-- Begin Page Content --> <!-- Search -->
<?php  
  $connect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 session_start();

if(empty($_SESSION['user'])){
 header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];

$userid = $row['userid'];


if($userlevel != 'staff') {

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

  <!-- Custom fonts for this template-->
  <link href="../../Projection/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="../../Projection/css/sb-admin.css" rel="stylesheet">
  
  
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
	<style type="text/css" class="init"> </style>
  
	<script type="text/javascript" src="/media/js/site.js?_=09b203e247031aa5935209252694085f"></script>
	<script type="text/javascript" src="/media/js/dynamic.php?comments-page=extensions%2Fbuttons%2Fexamples%2Fhtml5%2FtitleMessage.html" async></script>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../../../examples/resources/demo.js"></script>
	<script type="text/javascript" class="init">
	
	
	$(document).ready(function() {
	var printCounter = 0;
	// Append a caption to the table before the DataTables initialisation
	$('#example').append('<caption style="caption-side: top"></caption>');

	$('#example').DataTable( {
		stateSave: true,
		dom: 'Bfrtip',
		buttons: [
			'copy',
			{
				extend: 'excel',
				
			},
			{
				extend: 'pdf',
				messageBottom: null
			},
			{
				extend: 'print',
				messageTop:	'<center class="text-uppercase">' + 'COSOLIDATED LEAVE REPORT <?php
                            $Today=date('y:m:d');
                            $new=date('F d, Y',strtotime($Today));
                            echo $new; ?>' + '</center>'
				
			}
		]
	} );
	} );

	</script>
	

	<style type="text/css">


	@page {size:portrait}  
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
	</style>
	
	<style>
	.myTable { 
	  width: 100%;
	  font-size: 15px;
	  text-align: center;	
	  background-color: white;
	  border-collapse: collapse; 
	  }
	.myTable th { 
	  text-transform:uppercase;
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


				      <?php if($datefrom == "1970-01-01") { ?>                	
						<div class="col-sm-2 text-center">
                            <label>Cut-Off Date From</label>
                            <input type="date"  id="datePicker" class="form-control text-center" name="datefrom4" placeholder="Insert Date" 
                             autocomplete="off" required onkeypress="return false;" />																												
						</div>                                                      

						
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4" placeholder="Insert Date" 
							 autocomplete="off" required onkeypress="return false;" />
						</div>

					<?php }else{ ?>

					<div class="col-sm-2 text-center">
                            <label>Cut-Off Date From</label>
                            <input type="date"  id="datePicker" class="form-control text-center" name="datefrom4" placeholder="Insert Date" 
                            value="<?php echo $datefrom; ?>" autocomplete="off" required onkeypress="return false;" />																												
						</div>                                                      

						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4" placeholder="Insert Date" 
							value="<?php echo $dateto; ?>" autocomplete="off" required onkeypress="return false;" />
						</div>



					<?php } ?>

			

					<div class="col-xs-3 text-center d-none d-sm-inline-block">  

						<label class="invisible">.</label>

					<div class="col-xs-3 text-center d-none d-sm-inline-block">
				
							</div>
						<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" onclick="return confirm('Are you sure you want to Insert this Data?');" /> 
					</div>
					
					<div class="col-sm-3 text-center d-md-none">
						<label class="invisible">.</label>
						<input class="btn btn-primary btn-user btn-block bg-gradient-primary" type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" onclick="return confirm('Are you sure you want to Insert this Data?');" /> 
					</div>
				</div>
			</form>
			
		
		<br>
			<!-- DataTables Example -->
		<div class="card mb-3 ">		
			<div class="card-header">	
				 CAFE LEAVE - <a href="#"><?php
							$Today=date('y:m:d');
							$new=date('F d, Y',strtotime($Today));
							echo $new; ?></a>
			</div>
				
			<div class="card-body">
				<div class="table-responsive ">
					<table class="myTable table-hover" id="example" width="100%" cellspacing="0">
					
						<thead class="table-secondary text-uppercase">
							<tr>
								<th><center>ID</center></th>
								<th><center>FULLNAME</center></th>
								<th><center>BRANCH</center></th>
								<th><center>APPROVED LEAVE</center></th>
								<th><center>REMAINING LEAVE</center></th>
								<th><center>TOTAL</center></th>

							
							</tr>
						</thead>
						
						<tbody>
						
						<?php



						$userid = 1; //sample na nacaptured nya don sa unang page na sinelect. IT


						// LEAVE_ALL MODIFIED 

							//join_sample2
 						/*	$sql = "SELECT * FROM generated INNER JOIN user_info ON generated.empno = user_info.empno WHERE user_info.mothercafe = '$userid' OR user_info.empno = $empno";
							$query=$HRconnect->query($sql);
							while($row=$query->fetch_array())   

							//join_sample1
 							$sql = "SELECT DISTINCT empno FROM vlform WHERE vlstatus = 'approved' INNER JOIN user_info ON user_info.userid = vlform.empno WHERE user_info.userid = '$userid'";
							$query=$HRconnect->query($sql);
							while($row=$query->fetch_array())
							$selected_userid = $row['userid'];  */


							//select empno where vlstatus approved
						/*	$sql1="SELECT DISTINCT empno FROM vlform WHERE vlstatus = 'approved' AND ($userid) AND vldatefrom BETWEEN '$datefrom' AND '$dateto'";				
							$query1=$HRconnect->query($sql1);
							while($row1=$query1->fetch_array()){
							$empno = $row1['empno'];  */ 		



							//select empno where vlstatus approved.ORIGINAL
							$sql1="SELECT DISTINCT empno FROM vlform WHERE vlstatus = 'approved' AND approver = 'PALO, EMMANUELLE JOHANNES' AND vldatefrom BETWEEN '$datefrom' AND '$dateto'";				
							$query1=$HRconnect->query($sql1);
							while($row1=$query1->fetch_array()){
							$empno = $row1['empno'];  

						
							//vl remaining
							$sql2 = "SELECT * FROM user_info WHERE empno = '$empno'";
							$query2=$HRconnect->query($sql2);  
							$row2=$query2->fetch_array();
							$name = $row2['name']; 
					
							//approve VL
							$sql4="SELECT COUNT(*) FROM vlform
							WHERE empno = '$empno' AND (vldatefrom between '$datefrom' AND '$dateto') AND vlstatus = 'approved'";
							$query4=$HRconnect->query($sql4);
							$row4=$query4->fetch_array(); 
						
							//disregards this is for OT
							$sql10="SELECT * FROM overunder
							WHERE empno = '$empno' AND (otdatefrom between '$datefrom' AND '$dateto') AND otstatus = 'approved'";
							$query10=$HRconnect->query($sql10);
							$row10=$query10->fetch_array();

							//grand total of remaining leave versus approve
							$sumRemainingvsApprove = $row4['COUNT(*)'] + $row2['vl'];

							?>	
							<?php if($row4['COUNT(*)'] != 0){ ?>
							<tr class="text-uppercase">

								<td><center><?php echo $empno; ?></center></td>
								<td><center><?php echo utf8_encode($name); ?></center></td>
								<td><center><?php echo $row2['branch']; ?></center></td>
								<td><center><?php echo $row4['COUNT(*)']; ?></center></td> 
								<td><center><?php echo $row2['vl']; ?></center></td>			
								<td><center><?php echo $sumRemainingvsApprove; ?></center></td>	

							</tr>	
							<?php } ?>												
						<?php 

						$ada = 0;

							}
							?>
						</tbody>
					</table>		
				</div>
			</div>		
			<div class="card-body">
				<a href="../leave.php?pending=pending" class="btn btn-secondary btn-user ">BACK</a> 
			</div>
		</div>
</div> 


 
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
	<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css"/>

    <script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>
	
	<script>
        $("#datePicker").kendoDatePicker({
            disableDates: function (date) {
                var disabled = [];
                if (date && disabled.indexOf(date.getDate()) > -1 ) {
                    return true;
                } else {
                    return false;
                }
            }
        });
		
		$("#datePicker1").kendoDatePicker({
			disableDates: function (date) {
                var disabled = [];
                if (date && disabled.indexOf(date.getDate()) > -1 ) {
                    return true;
                } else {
                    return false;
                }
            }
        });
		
    </script>
	
</body>

</html>
<?php 
}
?>