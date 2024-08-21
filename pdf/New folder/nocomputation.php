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

$userid = $_SESSION['useridd'];




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
				messageTop:	'<center class="text-uppercase">' + 'COSOLIDATED DTR-REPORT <?php
                            $Today=date('y:m:d');
                            $new=date('F d, Y',strtotime($Today));
                            echo $new; ?>' + '</center>'
				
			}
		]
	} );
	} );

	</script>
	
 
	
	<style type="text/css">


	@page {size:landscape}  
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
			$date = "SELECT * FROM sched_date where userid = $userid";
					$query=$HRconnect->query($date);  
					$daterow=$query->fetch_array();
					@$datefrom = $daterow['fromcut'];
					@$dateto = $daterow['tocut'];

					?>
					
			
			<form class="user" method="post" action="../update.php?dtr=dtr">
			
				
					<div class="form-group row">								
					<?php if($datefrom == '') { ?>	

						<div class="col-sm-2 text-center">
							<label>Cut-Off Date From</label>
							<input type="date" id="datePicker"  class="form-control text-center" name="datefrom4" placeholder="Insert Date" autocomplete="off" required onkeypress="return false;" />
						</div>
						
						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4"  placeholder="Insert Date" autocomplete="off" required onkeypress="return false;" />
						</div>
			
			
				<?php } if($datefrom != ''){ ?>                            	
						<div class="col-sm-2 text-center">
                            <label>Cut-Off Date From</label>
                            <input type="date"  id="datePicker" class="form-control text-center" name="datefrom4" placeholder="Insert Date" value="<?php echo  $datefrom; ?>" autocomplete="off" required onkeypress="return false;" />																												
						</div>                                                      

						<div class="col-sm-2 text-center">
							<label>Cut-Off Date To</label>
							<input type="date" id="datePicker1" class="form-control text-center" name="dateto4" placeholder="Insert Date" value="<?php echo  $dateto; ?>" autocomplete="off" required onkeypress="return false;" />
						</div>
                    
                    <?php } ?>
					<div class="col-xs-3 text-center d-none d-sm-inline-block">
						<label class="invisible">.</label>
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
				<i class="fa fa-clock" aria-hidden="true"></i> Consolidation CAFE - <a href="#"><?php
							$Today=date('y:m:d');
							$new=date('F d, Y',strtotime($Today));
							echo $new;


							 ?></a>


			</div>


 
			<div class="card-body">
				<div class="table-responsive ">
										<form method="get">	
					<table class="myTable table-hover" id="example" width="100%" cellspacing="0">
					
						<thead class="table-secondary">
							<tr>
								<th><center>ID</center></th>
								<th><center>FULLNAME</center></th>
								<th><center>BRANCH</center></th>
								<th><center># OF DAYS</center></th>
								<th><center>REG.OT</center></th>
								<th><center>W.DAYOFF</center></th>
								<th><center>L.HOL-OT</center></th>
								<th><center>S.HOL-OT</center></th>
								<th><center>N.DIFF</center></th>
								<th><center>L.HOL</center></th>
								<th><center>S.HOL</center></th>
								<th><center>L.WPAY</center></th>
								<th><center>S.WPAY</center></th>
								<th><center>LATE.OB</center></th>
								<th><center>UT</center></th>
							</tr>
						</thead>
						
						<tbody>
						
						<?php

							$sql1="SELECT * FROM user_info 
							WHERE mothercafe = '$userid'";
					        $query1=$HRconnect->query($sql1);
							while($row1=$query1->fetch_array()){


							$empno = $row1['empno'];
							$name = $row1['name'];




							
							

$sql2="SELECT * FROM generated WHERE empno = '$empno' AND datefrom = '$datefrom' AND dateto = '$dateto'";
$query2=$HRconnect->query($sql2);  
$daterow2=$query2->fetch_array();


if (@$daterow2['dayswork'] != null){
?>

							<tr>		
								<td><center><?php echo $empno; ?></center></td>
								<td><center><?php echo utf8_encode($name); ?></center></td>
								<td><center><?php echo $row1['branch']; ?></center></td>
								<td><center><?php echo $daterow2['dayswork']; ?></center></td>
								<td><center><?php echo $daterow2['regularot']; ?></center></td>
								<td><center>0</center></td>
								<td><center>0</center></td>
								<td><center><?php echo $daterow2['specialdayot'];  ?></center></td>
								<td><center><?php echo $daterow2['nightdiff'];  ?></center></td>
								<td><center><?php echo $daterow2['legalday'];  ?></center></td>
								<td><center><?php echo $daterow2['specialday']; ?></center></td>
								<td><center><?php echo $daterow2['vleave']; ?></center></td>
								<td><center>0</center></td>
								<td><center><?php echo $daterow2['lateover']; ?></center></td>
								<td><center><?php echo $daterow2['undertime']; ?></center></td>
				
							</tr>		

<?php }










} ?>

						</tbody>



					<?php if($userlevel == 'master'){ ?>
				
						<tfoot>
				
							<tr>
								<td colspan="14"></td>
								<td><center><input class="btn btn-success btn-user btn-block bg-gradient-success" type="submit" name="SubmitButton" class="btn btn-info" value="Submit" onclick="return confirm('Are you sure you want to Insert this Data?');" /></center></td>						
							</tr>

						</tfoot>
					
					<?php } ?>	
					</table>		
						</form>
				</div>
			<div class="card-body">
				<a href="../discrepancy.php" class="btn btn-secondary btn-user ">BACK</a>
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
                var disabled = [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19,20,21,22,23,25,26,27,28,29,30,31];
                if (date && disabled.indexOf(date.getDate()) > -1 ) {
                    return true;
                } else {
                    return false;
                }
            }
        });
		
		$("#datePicker1").kendoDatePicker({
			disableDates: function (date) {
                var disabled = [1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17,18,19,20,21,22,24,25,26,27,28,29,30,31];
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
<?php } ?> 