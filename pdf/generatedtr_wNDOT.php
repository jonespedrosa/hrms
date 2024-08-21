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
$mothercafe = $row['mothercafe'];
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
  <link href="../..//vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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
			{
				extend: 'excel',
				
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
				<i class="fa fa-clock" aria-hidden="true"></i> TIMESHEET - <a href="#"><?php
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
								<th colspan="3"><center></center></th>
								<th><center>ATTENDANCE</center></th>
								<th></th>
								<th></th>
								<th></th>
								<th><center>ORDINARY DAY</center></th>
								<th></th>
								<th></th>
								<th><center>SPECIAL HOLIDAY</center></th>
								<th></th>
								<th></th>
								<th></th>
								<th><center>LEGAL HOLIDAY</center></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>ID</td>
								<td>FULL NAME</td>
								<td>BRANCH</td>
								<td><center>WORKDAYS</center></td>
								<td><dcenter>LATE</center></td>
								<td><center>UT</center></td>
								<td><center>LEAVE</center></td>
								<td><center>ND</center></td>
								<td><center>OT</center></td>
								<td><center>ND.OT</center></td>
								<td><center>HRS</center></td>
								<td><center>ND</center></td>
								<td><center>OT</center></td>
								<td><center>ND.OT</center></td>
								<td><center>HRS</center></td>
								<td><center>ND</center></td>
								<td><center>OT</center></td>
								<td><center>ND.OT</center></td>
								<td>WORKING <br> OFF</td>
							</tr>
						
						<?php

							$sql1="SELECT * FROM user_info 
							WHERE mothercafe = '$userid'";
					

									$query1=$HRconnect->query($sql1);
									while($row1=$query1->fetch_array()){
							 $empno = $row1['empno'];
							 $name = $row1['name'];
							 $branch = $row1['branch'];
				
$sql22="SELECT * FROM generated WHERE empno = '$empno' AND datefrom = '$datefrom' AND dateto = '$dateto'";	
$query22=$HRconnect->query($sql22);  
$daterow22=$query22->fetch_array();

		if(@$daterow22['idgen'] == 0){


				$sql90="SELECT * FROM sched_time 
				WHERE sched_time.empno = '$empno' AND (datefromto between '$datefrom' AND '$dateto') AND status != 'deleted'";
						$query90=$HRconnect->query($sql90);
						@$regularothours=0;
						@$totalndothours=0;
						@$totalbrokenNDOT=0;
						@$regularbrokenOT = 0;
						@$totalbrokenSHNDOT = 0;
						@$regularbrokenSHOT = 0;
						@$totalbrokenLHNDOT = 0;
						@$regularbrokenLHOT = 0;
						//variables for holiday
						@$legalhday = '';		
          	@$hdayprevdate1 = '';
          	@$hdayprevdate2 = '';	
          	@$hdayprevdate3 = '';	
          	@$specialhday = '';
          	@$totalspechol = 0;
          	@$legal_holiday = 0;
          	@$special_holiday = 0;
          	@$totallhndothours = 0;
          	@$lhregularothours = 0;
          	@$totalshndothours = 0;
						@$shregularothours = 0;
						@$shndtotal = 0;
						@$shndtotal1 = 0;
						@$lhndtotal = 0;


						while($row90=$query90->fetch_array()){
							@$datefrom2 = $row90['schedfrom'];
							@$dateto2 = $row90['schedto'];
							@$datefromto = $row90['datefromto'];
							@$mtimein1 = $row90['M_timein'];
							@$mtimeout1 = $row90['M_timeout'];
							@$atimein1 = $row90['A_timein'];
							@$atimeout1 = $row90['A_timeout'];	
							@$break1 = $row90['break'];
							@$brokentime_in = $row90['timein4'];
							@$brokentime_out = $row90['timeout4'];	


// Kapag mali ang schedule magiging ($statusall = 1);
@$statusall= 0;
		 $sql9 = "SELECT * FROM vlform
                    	  WHERE vlstatus = 'approved' AND empno = $empno
                    	  AND vldatefrom = '$datefromto'";
                		  $query9=$HRconnect->query($sql9);
                 		  $row9=$query9->fetch_array();

// kapag may leave si staff 
@$vldate = $row9['vldatefrom']; 


//Total Breaktime hours
 $equal = (strtotime($dateto2) - strtotime($datefrom2))/3600;

//Breaktime Condition
if(($equal == 19 AND $break1 == 10) OR ($equal == 17 AND $break1 == 9) OR ($equal == 16 AND $break1 == 8) OR ($equal == 15 AND $break1 == 7) OR ($equal == 14 AND $break1 == 6) OR ($equal == 13 AND $break1 == 5) OR ($equal == 12 AND $break1 == 4) OR ($equal == 11 AND $break1 == 3) OR ($equal == 10 AND $break1 == 2) OR ($equal == 9 AND $break1 == 1) OR ($equal == 8 AND $break1 == 0)) { 

				}else{
					
					if($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != ''){
					@$minus += 1;
					@$statusall += 1;
					}
				}

				//HOLIDAY LEGAL AND SPECIAL	
          //NEW CODE FOR LEGAL HOLIDAYS
          $sqllegalhol = "SELECT * FROM holiday WHERE type = '0' AND holiday_day = '$datefromto' ";     
          $querylegalhol=$HRconnect->query($sqllegalhol);
          while($rowlegalhol=$querylegalhol->fetch_array()){  

          $legalhday = $rowlegalhol['holiday_day'];		
          $hdayprevdate1 = $rowlegalhol['prior1'];
          $hdayprevdate2 = $rowlegalhol['prior2'];	
          $hdayprevdate3 = $rowlegalhol['prior3'];

          if($datefromto == $legalhday && $mtimein1 != '' && $atimeout1 != ''){

          	//LEGAL HOLIDAY NIGHT DIFF
							$lh_ndstarta = strtotime($datefromto. " "."00:00");
							$lh_ndenda = strtotime($datefromto." "."06:00");
							$lh_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$lh_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$lh_cmtimein = strtotime($mtimein1) < strtotime($datefrom2) ? strtotime($datefrom2) : strtotime($mtimein1); //base time in AM
							$lh_cmtimeout = !($mtimeout1 === "No Break") ? strtotime($mtimeout1) : $mtimeout1;
							$lh_catimein = $atimein1;
							if(!($mtimeout1 === "No Break")){
								$lh_catimein = (strtotime($atimein1)-$lh_cmtimeout)<=3600 ? ($lh_cmtimeout + 3600):strtotime($atimein1);
							}
							$lh_catimeout= strtotime($atimeout1) > strtotime($dateto2) ? strtotime($dateto2) : strtotime($atimeout1);//base time out PM
							$lh_ndbreak = 0;
							$lh_ndoverbreak = 0;

							if($mtimeout1 === "No Break" && $break1 == 0){
								if(!($lh_catimeout-$sh_cmtimein <= (8*3600))){
									if($lh_catimeout >= $lh_ndstarta AND $lh_catimeout <= $lh_ndenda) $lh_ndbreak++;
									if($lh_catimeout >= $lh_ndstartb AND $lh_catimeout <= $lh_ndendb) $lh_ndbreak++;
								}
							}else{
				
								if(($lh_cmtimeout >= $lh_ndstarta AND $lh_cmtimeout <= $lh_ndenda) OR ($lh_catimein > $lh_ndstarta AND $lh_catimein < $lh_ndenda)){
									$lh_ndbreak++;
								}
								if(($lh_cmtimeout >= $lh_ndstartb AND $lh_cmtimeout <= $lh_ndendb) OR ($lh_catimein > $lh_ndstartb AND $lh_catimein < $lh_ndendb)){
									$lh_ndbreak++;
								}
							}

								@$lhndtotal -= $lh_ndbreak;
	
							if(!($lh_cmtimein < $lh_ndstarta AND $lh_catimeout <$lh_ndstarta) AND !($lh_cmtimein >$lh_ndenda AND $lh_catimeout >$lh_ndenda)){
								$lh_ndinlog = $lh_cmtimein < $lh_ndstarta ? $lh_ndstarta : $lh_cmtimein;
								$lh_ndoutlog = $lh_catimeout > $lh_ndenda ? $lh_ndenda : $lh_catimeout;
							
								@$lhndtotal += (($lh_ndoutlog - $lh_ndinlog)/3600);
							}
							if(!($lh_cmtimein < $lh_ndstartb AND $lh_catimeout <$lh_ndstartb) AND !($lh_cmtimein >$lh_ndendb AND $lh_catimeout >$lh_ndendb)){
								$lh_ndinlog = $lh_cmtimein < $lh_ndstartb ? $lh_ndstartb : $lh_cmtimein;
								$lh_ndoutlog = $lh_catimeout > $lh_ndendb ? $lh_ndendb : $lh_catimeout;
								@$lhndtotal += (($lh_ndoutlog - $lh_ndinlog)/3600);
							}

						}
          	//LEGAL HOLIDAY OT COMPUTATION
        			$legal_hot="SELECT SUM(othours) as LHOT FROM overunder WHERE empno = '$empno' AND ottype in ('','0') AND otstatus = 'approved' AND otdatefrom = '$legalhday'";
							$query_HOT=$HRconnect->query($legal_hot);
							$row_HOT=$query_HOT->fetch_array();
							@$totalssalhot += @$row_HOT['LHOT'];

							$cus_approvedlhot = $row_HOT['LHOT'];
							$cus_lht1 =strtotime($dateto2) + ($cus_approvedlhot * 3600);
							$cus_lht2 =strtotime($datefromto ." "."22:00");
							$cus_lht3 =$cus_lht2+(8*3600);
							$cus_lhregot = $cus_approvedlhot;

							if(($cus_lht1 > $cus_lht2) AND $cus_approvedlhot > 0 ){
								$cus_lhndot=0;
								if(strtotime($dateto2)<$cus_lht2){
									$cus_lhndot= ($cus_lht1-$cus_lht2)/3600;


								}else{
									$cus_lhndot = $cus_approvedlhot;
									
								}
					
								$cus_lhregot = $cus_approvedlhot-$cus_lhndot;
							
								@$totallhndothours += $cus_lhndot;								

							}

							@$lhregularothours += $cus_lhregot;

							if(($cus_lht1 > $cus_lht3) AND $cus_approvedlhot > 0){
								@$totallhndothours -= (($cus_lht1-$cus_lht3)/3600);
								@$lhregularothours += (($cus_lht1-$cus_lht3)/3600);


							}	

							//CHECKING LEGAL HOLIDAY ND OT OF BROKEN SCHED
							$sqllhBOT="SELECT SUM(othours) as LHBOT FROM overunder WHERE empno = '$empno' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$legalhday' ";
							$querylhBOT=$HRconnect->query($sqllhBOT);
							$rowlhBOT=$querylhBOT->fetch_array();
							@$totalssaLH += @$rowlhBOT['LHBOT'];

							$lh_approvedbot = $rowlhBOT['LHBOT'];
							$lh_bt1 =strtotime($brokentime_out) + ($lh_approvedbot * 3600);
							$lh_bt2 =strtotime($datefromto ." "."22:00");
							$lh_bt3 =$lh_bt2+(8*3600);
							$lh_bregot = $lh_approvedbot;

							if(($lh_bt1 > $lh_bt2) AND $lh_approvedbot > 0 ){
								$lh_bndot=0;
								if(strtotime($brokentime_out)<$lh_bt2){
									$lh_bndot= ($lh_bt1-$lh_bt2)/3600;


								}else{
									$lh_bndot = $lh_approvedbot;
									
								}
					
								$lh_bregot = $lh_approvedbot-$lh_bndot;
							
								@$totalbrokenLHNDOT += $lh_bndot;								

							}

							@$regularbrokenLHOT += $lh_bregot;

							if(($lh_bt1 > $lh_bt3) AND $lh_approvedbot > 0){
								@$totalbrokenLHNDOT -= (($lh_bt1-$lh_bt3)/3600);
								@$regularbrokenLHOT += (($lh_bt1-$lh_bt3)/3600);


							}

        	}

					//CONDITION TO CHECK THE LEGAL HOLIDAYS		
          if($datefromto == $legalhday && $mtimein1 != '' && $atimeout1 != ''){
        			$legal_holiday += 8;

        	}

          	if($datefromto == $legalhday && $mtimein1 == '' && $atimeout1 == '') {
          			
          			//query prior dates in the sched time
          			$sqldate2 ="SELECT * FROM sched_time WHERE sched_time.empno = '$empno' AND (datefromto between '$hdayprevdate3' AND '$hdayprevdate1') AND status != 'deleted' ORDER BY datefromto ASC";
								$querydate2=$HRconnect->query($sqldate2);

								while($rowdate2=$querydate2->fetch_array()){
									$datefromto3 = $rowdate2['datefromto'];
									$mtimein3 = $rowdate2['M_timein'];
									$atimeout3 = $rowdate2['A_timeout'];

									if($mtimein3 != '' && $atimeout3 != '') {
          					$legal_holiday += 8;	
          					break;  

        					}else{
        						//check if there are approved leaves that falls under legal holiday
        						$sql_holleave ="SELECT * FROM vlform WHERE empno = '$empno' AND vlstatus = 'approved' AND vldatefrom = '$datefromto3' ORDER BY vldatefrom ASC";
										$query_holleave=$HRconnect->query($sql_holleave);
											
											if(mysqli_num_rows($query_holleave) > 0){
												$legal_holiday += 8;	
												break; 
											}

 		     					}
								}
        			}


          //NEW CODE FOR SPECIAL HOLIDAYS
          $sqlspechol = "SELECT * FROM holiday WHERE type = '1' AND holiday_day = '$datefromto' ";     
          $queryspechol=$HRconnect->query($sqlspechol);
          while($rowspechol=$queryspechol->fetch_array()){  
          	$specialhday = $rowspechol['holiday_day'];
          
          if($datefromto == $specialhday && $mtimein1 != '' && $atimeout1 != ''){
          //SPECIAL HOLIDAY NIGHT DIFF	
          	$sh_ndstarta = strtotime($datefromto. " "."00:00");
							$sh_ndenda = strtotime($datefromto." "."06:00");
							$sh_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$sh_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$sh_cmtimein = strtotime($mtimein1) < strtotime($datefrom2) ? strtotime($datefrom2) : strtotime($mtimein1); //base time in AM
							$sh_cmtimeout = !($mtimeout1 === "No Break") ? strtotime($mtimeout1) : $mtimeout1;
							$sh_catimein = $atimein1;

							if(!($mtimeout1 === "No Break")){
								$sh_catimein = (strtotime($atimein1)-$sh_cmtimeout)<=3600 ? ($sh_cmtimeout + 3600):strtotime($atimein1);
							}
							$sh_catimeout= strtotime($atimeout1) > strtotime($dateto2) ? strtotime($dateto2) : strtotime($atimeout1);//base time out PM
							$sh_ndbreak = 0;
							$sh_ndoverbreak = 0;

							if($mtimeout1 === "No Break" && $break1 == 0){
								if(!($sh_catimeout-$sh_cmtimein <= (8*3600))){
									if($sh_catimeout >= $sh_ndstarta AND $sh_catimeout <= $sh_ndenda) $sh_ndbreak++;
									if($sh_catimeout >= $sh_ndstartb AND $sh_catimeout <= $sh_ndendb) $sh_ndbreak++;
								}
							}else{
				
								if(($sh_cmtimeout >= $sh_ndstarta AND $sh_cmtimeout <= $sh_ndenda) OR ($sh_catimein > $sh_ndstarta AND $sh_catimein < $sh_ndenda)){
									$sh_ndbreak++;
								}
								if(($sh_cmtimeout >= $sh_ndstartb AND $sh_cmtimeout <= $sh_ndendb) OR ($sh_catimein > $sh_ndstartb AND $sh_catimein < $sh_ndendb)){
									$sh_ndbreak++;
								}
							}

							@$shndtotal -= $sh_ndbreak;
	
							if(!($sh_cmtimein < $sh_ndstarta AND $sh_catimeout <$sh_ndstarta) AND !($sh_cmtimein >$sh_ndenda AND $sh_catimeout >$sh_ndenda)){
								$sh_ndinlog = $sh_cmtimein < $sh_ndstarta ? $sh_ndstarta : $sh_cmtimein;
								$sh_ndoutlog = $sh_catimeout > $sh_ndenda ? $sh_ndenda : $sh_catimeout;
							
								@$shndtotal += (($sh_ndoutlog - $sh_ndinlog)/3600);
							}
							if(!($sh_cmtimein < $sh_ndstartb AND $sh_catimeout <$sh_ndstartb) AND !($sh_cmtimein >$sh_ndendb AND $sh_catimeout >$sh_ndendb)){
								$sh_ndinlog = $sh_cmtimein < $sh_ndstartb ? $sh_ndstartb : $sh_cmtimein;
								$sh_ndoutlog = $sh_catimeout > $sh_ndendb ? $sh_ndendb : $sh_catimeout;
								@$shndtotal += (($sh_ndoutlog - $sh_ndinlog)/3600);
							}
          	
						}

          	//SPECIAL HOLIDAY OT COMPUTATION
        		$special_hot="SELECT SUM(othours) as SHOT FROM overunder WHERE empno = '$empno' AND ottype in ('','0') AND otstatus = 'approved' AND otdatefrom = '$specialhday'";
							$query_SHOT=$HRconnect->query($special_hot);
							$row_SHOT=$query_SHOT->fetch_array();
							@$totalssashot += @$row_SHOT['SHOT'];
		
							$cus_approvedshot = $row_SHOT['SHOT'];
							$cus_sht1 =strtotime($dateto2) + ($cus_approvedshot * 3600);
							$cus_sht2 =strtotime($datefromto ." "."22:00");
							$cus_sht3 =$cus_sht2+(8*3600);
							$cus_shregot = $cus_approvedshot;

							if(($cus_sht1 > $cus_sht2) AND $cus_approvedshot > 0 ){
								$cus_shndot=0;
								if(strtotime($dateto2)<$cus_sht2){
									$cus_shndot= ($cus_sht1-$cus_sht2)/3600;


								}else{
									$cus_shndot = $cus_approvedshot;
									
								}
					
								$cus_shregot = $cus_approvedshot-$cus_shndot;
							
								@$totalshndothours += $cus_shndot;								

							}

							@$shregularothours += $cus_shregot;

							if(($cus_sht1 > $cus_sht3) AND $cus_approvedshot > 0){
								@$totalshndothours -= (($cus_sht1-$cus_sht3)/3600);
								@$shregularothours += (($cus_sht1-$cus_sht3)/3600);


							}

							//CHECKING SPECIAL HOLIDAY ND OT OF BROKEN SCHED
							$sqlshBOT="SELECT SUM(othours) as SHBOT FROM overunder WHERE empno = '$empno' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$specialhday' ";
							$queryshBOT=$HRconnect->query($sqlshBOT);
							$rowshBOT=$queryshBOT->fetch_array();
							@$totalssaSH += @$rowshBOT['SHBOT'];

							$sh_approvedbot = $rowshBOT['SHBOT'];
							$sh_bt1 =strtotime($brokentime_out) + ($sh_approvedbot * 3600);
							$sh_bt2 =strtotime($datefromto ." "."22:00");
							$sh_bt3 =$sh_bt2+(8*3600);
							$sh_bregot = $sh_approvedbot;


							if(($sh_bt1 > $sh_bt2) AND $sh_approvedbot > 0 ){
								$sh_bndot=0;
								if(strtotime($brokentime_out)<$sh_bt2){
									$sh_bndot= ($sh_bt1-$sh_bt2)/3600;


								}else{
									$sh_bndot = $sh_approvedbot;
									
								}
					
								$sh_bregot = $sh_approvedbot-$sh_bndot;
							
								@$totalbrokenSHNDOT += $sh_bndot;								

							}

							@$regularbrokenSHOT += $sh_bregot;

							if(($sh_bt1 > $sh_bt3) AND $sh_approvedbot > 0){
								@$totalbrokenSHNDOT -= (($sh_bt1-$sh_bt3)/3600);
								@$regularbrokenSHOT += (($sh_bt1-$sh_bt3)/3600);


							}
	
        	}

					//CONDITION TO CHECK THE SPECIAL HOLIDAYS		
          if($datefromto == $specialhday && $mtimein1 != '' && $atimeout1 != ''){
          	$sh_in = strtotime($mtimein1);
          	$sh_out = strtotime($atimeout1);

          	$spechol1 = (($sh_out - $sh_in)/3600);
          	$totalspechol = $spechol1 - $break1;

          		if($totalspechol > 8){
        				$special_holiday += 8;
        			}else{
        				$special_holiday += $totalspechol;
        			}
        	
        	}

	if(($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != '') AND @$statusall == 0){

// Genmeet Condition
if($row90['timein4'] != '' AND $row90['timeout4'] != ''){
$start = strtotime($row90['timein4']);
$end = strtotime($row90['timeout4']);
$mins1 = ($end - $start) / 60;


if($row90['schedto'] > $row90['A_timeout']){
$start = strtotime($row90['schedto']);
$end = strtotime($row90['A_timeout']);
$mins2 = ($start - $end) / 60;
}         		  

 if(@$mins1 > @$mins2){

 @$gentotal = @$mins2;

 }else{

 @$gentotal = @$mins1;

 }

@$grandgen = @$gentotal;


}

//Undertime Condition kapag yung timeout mas mababa sa Schedule timeout
		$sql12="SELECT SUM(time_to_sec(TIMEDIFF(schedto, A_timeout))) as timeout2 FROM sched_time
		WHERE sched_time.empno = '$empno' AND datefromto = '$datefromto' AND schedto > A_timeout ";
		$query12=$HRconnect->query($sql12);
		$row12=$query12->fetch_array();
	    $UT2 = $row12['timeout2'] /60;
	   @$totalUT2 += $UT2;


	if($mtimeout1 != '' OR $atimeout1 != ''){

		//Overtime Condtion if approved ilalabas nya
			$sql6="SELECT SUM(othours) as timeout FROM overunder
							WHERE empno = '$empno' AND otdatefrom = '$datefromto' AND otstatus = 'approved'
							";
						$query6=$HRconnect->query($sql6);
						$row6=$query6->fetch_array();
		}

			
							$breaktotal1 = $break1 * 10000;

			$sql8 = " SELECT ADDTIME('$mtimeout1','$breaktotal1') as zxc FROM sched_time
							WHERE empno = '$empno' AND (datefromto between '$datefrom' AND '$dateto')
							AND M_timeout != 'null'";

						$query8=$HRconnect->query($sql8);
						$row8=$query8->fetch_array();
						$totals = $row8['zxc'];

	}		



// SPECIAL HOLIDAY
/*if(($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != '') AND @$statusall == 0){
	// example ng multiple special holiday sa isang cutoff
	//($datefromto == '2021-04-03' OR $datefromto == '2021-04-04' OR $datefromto == '2021-04-05')
if (($datefromto == '2022-08-21')  AND $mtimein1 != '' AND $atimeout1 != '' AND ($datefrom2 <= $mtimein1 AND $dateto2 >= $atimeout1)){
	//kapag ang timeinputs may late at undertime or equal sa schedule.
	@$spcregday1 = floor((strtotime($atimeout1) - strtotime($datefrom2))/3600) - $break1;

}elseif (($datefromto == '2022-08-21') AND $mtimein1 != '' AND $atimeout1 != '' AND $datefrom2 >= $mtimein1 AND $dateto2 <= $atimeout1){
	//kapag ang timeinputs walang late at undertime or equal sa schedule.
	@$spcregday1 = floor((strtotime($dateto2) - strtotime($datefrom2))/3600) - $break1;

}elseif (($datefromto == '2022-08-21') AND $mtimein1 != '' AND $atimeout1 != '' AND $datefrom2 < $mtimein1 AND $dateto2 < $atimeout1){
	//kapag ang timeinputs may late pero walang undertime.
	@$spcregday1 = floor((strtotime($dateto2) - strtotime($datefrom2))/3600) - $break1;

}elseif (($datefromto == '2022-08-21') AND $mtimein1 != '' AND $atimeout1 != '' AND $datefrom2 > $mtimein1 AND $dateto2 > $atimeout1){
		//kapag ang timeinputs walang late pero may undertime.
	@$spcregday1 = floor((strtotime($atimeout1) - strtotime($datefrom2))/3600) - $break1;
}*/


						
							//NIGHT DIFF NEW CODE
							$cus_ndstarta = strtotime($datefromto. " "."00:00");
							$cus_ndenda = strtotime($datefromto." "."06:00");
							$cus_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$cus_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$cus_cmtimein = strtotime($mtimein1) < strtotime($datefrom) ? strtotime($datefrom2) : strtotime($mtimein1); //base time in AM
							$cus_cmtimeout = !($mtimeout1 === "No Break") ? strtotime($mtimeout1) : $mtimeout1;
							$cus_catimein = $atimein1;
							if(!($mtimeout1 === "No Break")){
								$cus_catimein = (strtotime($atimein1)-$cus_cmtimeout)<=3600 ? ($cus_cmtimeout + 3600):strtotime($atimein1);
							}
							$cus_catimeout= strtotime($atimeout1) > strtotime($dateto2) ? strtotime($dateto2) : strtotime($atimeout1);//base time out PM
							$cus_ndbreak = 0;
							$cus_ndoverbreak = 0;

							if($mtimeout1 === "No Break" && $break1 == 0){
								if(!($cus_catimeout-$cus_cmtimein <= (8*3600))){
									if($cus_catimeout >= $cus_ndstarta AND $cus_catimeout <= $cus_ndenda) $cus_ndbreak++;
									if($cus_catimeout >= $cus_ndstartb AND $cus_catimeout <= $cus_ndendb) $cus_ndbreak++;
								}

							}else{
				
								if(($cus_cmtimeout >= $cus_ndstarta AND $cus_cmtimeout <= $cus_ndenda) OR ($cus_catimein > $cus_ndstarta AND $cus_catimein < $cus_ndenda)){
									$cus_ndbreak++;
								}
								if(($cus_cmtimeout >= $cus_ndstartb AND $cus_cmtimeout <= $cus_ndendb) OR ($cus_catimein > $cus_ndstartb AND $cus_catimein < $cus_ndendb)){
									$cus_ndbreak++;
								}
							}

							@$regndtotal -= $cus_ndbreak;

							
	
							if(!($cus_cmtimein < $cus_ndstarta AND $cus_catimeout <$cus_ndstarta) AND !($cus_cmtimein >$cus_ndenda AND $cus_catimeout >$cus_ndenda)){
								$cus_ndinlog = $cus_cmtimein < $cus_ndstarta ? $cus_ndstarta : $cus_cmtimein;
								$cus_ndoutlog = $cus_catimeout > $cus_ndenda ? $cus_ndenda : $cus_catimeout;
							
								@$regndtotal += (($cus_ndoutlog - $cus_ndinlog)/3600);
							}
							if(!($cus_cmtimein < $cus_ndstartb AND $cus_catimeout <$cus_ndstartb) AND !($cus_cmtimein >$cus_ndendb AND $cus_catimeout >$cus_ndendb)){
								$cus_ndinlog = $cus_cmtimein < $cus_ndstartb ? $cus_ndstartb : $cus_cmtimein;
								$cus_ndoutlog = $cus_catimeout > $cus_ndendb ? $cus_ndendb : $cus_catimeout;
								@$regndtotal += (($cus_ndoutlog - $cus_ndinlog)/3600);
							}

						//END OF NIGHT DIFF CALCULATION

// Morning Night Diff 

//if (($mtimein1 >= $datefromto ." "."23:00" AND $mtimein1 <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") AND 
	//($mtimeout1 >= $datefromto ." "."23:00" AND $mtimeout1 <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00")
	//AND $mtimeout1 != "No Break"){

   //@$night1 = floor((strtotime($mtimeout1) - strtotime($mtimein1))/3600);

//}elseif($mtimein1 >= $datefromto ." "."23:00" AND $mtimeout1 <= $datefromto ." "."23:00" AND $mtimeout1 != "No Break"){

 //@$night1 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($mtimeout1))/3600);

//}elseif($mtimein1 <= $datefromto ." "."23:00" AND $mtimeout1 >= $datefromto ." "."23:00" AND $mtimeout1 != "No Break"){

 //@$night1 = floor((strtotime($mtimeout1) - strtotime($datefromto ." "."22:00"))/3600);

 // Morning Night Diff 
//}elseif($mtimein1 <= $datefromto ." "."06:00"){

 //@$night1 = floor(( strtotime($datefromto ." "."06:00") - strtotime($datefrom2))/3600);

//}



//if (($atimein1 >= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00") AND 
	//($atimein1 < date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00" AND $atimeout1 < date('Y-m-d', strtotime( $datefromto . " +1 days"))." "."06:00") 
	//AND $atimein1 != "No Break"){

//@$night2 = floor((strtotime($atimeout1) - strtotime($atimein1))/3600);

//}elseif($atimein1 >= $datefromto ." "."23:00" AND $atimeout1 <= $datefromto ." "."23:00" AND $atimein1 != "No Break"){

 //@$night2 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($atimeout1))/3600);

//}elseif($atimein1 <= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00" AND $atimein1 != "No Break" AND $atimeout1 <= $dateto2){

//@$night2 = floor((strtotime($atimeout1) - strtotime($datefromto ." "."22:00"))/3600);

//}elseif($atimein1 <= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00" AND $atimein1 != "No Break" AND $atimeout1 >= $dateto2 AND $dateto2 > date("Y-m-d H:i" ,strtotime($datefromto ." "."22:00"))){

 //@$night2 = floor((strtotime($dateto2) - strtotime($datefromto ." "."22:00"))/3600);

//}







//if (($atimein1 == 'No Break' AND $atimeout1 > $datefromto ." "."23:00" AND $atimeout1 <= $dateto2)){

  // @$night1 = floor((strtotime($atimeout1) - strtotime($datefromto ." "."22:00"))/3600);

//}elseif(($atimein1 == 'No Break' AND $atimeout1 > $datefromto ." "."23:00" AND $datefromto ." "."23:00" <= $dateto2)){

	// @$night1 = floor((strtotime($dateto2) - strtotime($datefromto ." "."22:00"))/3600);
//}


//@$total1 += @$night1;
//$night1 = 0;

//@$total2 += @$night2;
//$night2 = 0;

//@$total3 += @$night3;
//$night3 = 0;

}




if(($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != '') AND @$statusall == 0){
	
//			$sql5="SELECT SUM(othours) as OT FROM overunder
//			WHERE empno = '$empno' AND otdatefrom = '$datefromto' AND ottype in  ('0','') AND otstatus = 'approved'";
//			$query5=$HRconnect->query($sql5);
//			$row5=$query5->fetch_array();
//if($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != ''){
//	@$regOT = @$row5['OT'];


	//GEN MEET OT TOTAL
//			$gmeetTOTAL="SELECT SUM(othours) as OT FROM overunder
//			WHERE empno = '$empno' AND otdatefrom = '$datefromto' AND ottype in ('1','2') AND otstatus = 'approved'";
//			$querytotal=$HRconnect->query($gmeetTOTAL);
//			$rowtotal=$querytotal->fetch_array();

//	@$GenMeetTotalOT = $rowtotal['OT'];

//	$otFinal = $regOT + $GenMeetTotalOT;

//	@$totalssa += $otFinal;

		$sql5="SELECT SUM(othours) as OT FROM overunder WHERE empno = '$empno'  AND ottype in ('','0') AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
							$query5=$HRconnect->query($sql5);
							$row5=$query5->fetch_array();
							@$totalssa += @$row5['OT'];

	//INSERT OT CODE HERE
							$cus_approvedot = $row5['OT'];
							$cus_t1 =strtotime($dateto2) + ($cus_approvedot * 3600);
							$cus_t2 =strtotime($datefromto ." "."22:00");
							$cus_t3 =$cus_t2+(8*3600);
							$cus_regot = $cus_approvedot;

							if(($cus_t1 > $cus_t2) AND $cus_approvedot > 0 ){
								$cus_ndot=0;
								if(strtotime($dateto2)<$cus_t2){
									$cus_ndot= ($cus_t1-$cus_t2)/3600;


								}else{
									$cus_ndot = $cus_approvedot;
									
								}
					
								$cus_regot = $cus_approvedot-$cus_ndot;
							
								@$totalndothours += $cus_ndot;								

							}

							@$regularothours += $cus_regot;

							if(($cus_t1 > $cus_t3) AND $cus_approvedot > 0){
								@$totalndothours -= (($cus_t1-$cus_t3)/3600);
								@$regularothours += (($cus_t1-$cus_t3)/3600);


							}


		//CHECKING ND OT OF BROKEN SCHED
		$sqlBOT="SELECT SUM(othours) as BOT FROM overunder WHERE empno = '$empno' AND ottype in ('1','2') AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
							$queryBOT=$HRconnect->query($sqlBOT);
							$rowBOT=$queryBOT->fetch_array();
							@$totalssa1 += @$rowBOT['BOT'];

							$cus_approvedbot = $rowBOT['BOT'];
							$cus_bt1 =strtotime($brokentime_out) + ($cus_approvedbot * 3600);
							$cus_bt2 =strtotime($datefromto ." "."22:00");
							$cus_bt3 =$cus_bt2+(8*3600);
							$cus_bregot = $cus_approvedbot;

							if(($cus_bt1 > $cus_bt2) AND $cus_approvedbot > 0 ){
								$cus_bndot=0;
								if(strtotime($brokentime_out)<$cus_bt2){
									$cus_bndot= ($cus_bt1-$cus_bt2)/3600;


								}else{
									$cus_bndot = $cus_approvedbot;
									
								}
					
								$cus_bregot = $cus_approvedbot-$cus_bndot;
							
								@$totalbrokenNDOT += $cus_bndot;								

							}

							@$regularbrokenOT += $cus_bregot;

							if(($cus_bt1 > $cus_bt3) AND $cus_approvedbot > 0){
								@$totalbrokenNDOT -= (($cus_bt1-$cus_bt3)/3600);
								@$regularbrokenOT += (($cus_bt1-$cus_bt3)/3600);


						}



if ($atimein1 > $totals)
{	
	

		$totalsss = strtotime($atimein1) - strtotime($totals);

		@$ada+=$totalsss;
}



			$sql4="SELECT SUM(time_to_sec(TIMEDIFF(M_timein,schedfrom))) as timein FROM sched_time
							WHERE empno = '$empno' AND datefromto = '$datefromto' AND schedfrom < M_timein 
							";
						$query4=$HRconnect->query($sql4);
						$row4=$query4->fetch_array();

						@$latetotal += $row4['timein'];			

}
						

}


							$sql2 = "SELECT * FROM user_info where empno = '$empno'";
							$query2=$HRconnect->query($sql2);  
							$row2=$query2->fetch_array();
							$name = $row2['name'];


		$sql10="SELECT COUNT(*) as vl FROM vlform
							INNER JOIN sched_time ON vlform.empno = sched_time.empno
							WHERE vlform.empno = '$empno' AND (datefromto between '$datefrom' AND '$dateto') AND datefromto = vldatefrom AND vlstatus = 'approved'";
						$query10=$HRconnect->query($sql10);
						$row10=$query10->fetch_array();



$sql2="SELECT * FROM generated WHERE empno = '$empno' AND datefrom = '$datefrom' AND dateto = '$dateto'";	
$query2=$HRconnect->query($sql2);  
$daterow2=$query2->fetch_array();

			$sql3="SELECT COUNT(*) FROM sched_time
							WHERE empno = '$empno' AND (datefromto between '$datefrom' AND '$dateto')  AND (M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '')";
						$query3=$HRconnect->query($sql3);
						$row3=$query3->fetch_array();             


if ($row3['COUNT(*)'] == 0 AND $row10['vl'] == 0){
$zero = 1;
}else{
$zero = 0;
}
							?>

							<?php if(($name != '' AND $zero == 0)){ ?>

							<?php if(@$total == 0){ ?>

							<?php }else{?>
							<tr style="background-color:#ff8080">	
							<?php } 
							?>
							<?php
								@$allholiday_OT = $lhregularothours + $shregularothours + $regularbrokenSHOT + $regularbrokenLHOT;
								@$allholidaynd_OT = $totallhndothours + $totalshndothours + $totalbrokenSHNDOT + $totalbrokenLHNDOT;
								@$allregular_OT = $regularothours + $regularbrokenOT - $allholiday_OT; 
								@$allnd_OT = $totalndothours + $totalbrokenNDOT - $allholidaynd_OT;
								@$allholidayND = $shndtotal + $lhndtotal;
							?>
								<td><center><?php echo $empno; ?></center></td>
								<td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
								<td><center><?php echo $row2['branch']; ?></cente r></td>
								<td><center><?php echo ($row3['COUNT(*)']) -  (@$total + @$minus); ?></center></td>
								<td><center><?php echo @$latetotal /60 + @$ada / 60; ?></center></td>
								<td><center><?php echo  (@$totalUT + @$totalUT2) - @$grandgen; ?></center></td>
								<td><center><?php echo @$row10['vl']; ?></center></td>
								<!--ORDINARY NIGHT DIFF-->
								<td><center><b><?php if(@$regndtotal - $allholidayND != ''){
																			echo ($regndtotal - $allholidayND); 
																		}else{
																			echo "0";
																		}
																?>
								</b></center></td>
								<!--REGULAR OVERTIME-->
								<td><center><b><?php if (@$allregular_OT != '') {
																				echo round($allregular_OT);
																		}else{
																			echo "0";
																		}
																?>
								</b></center></td>
								<!--NIGHT DIFF OVERTIME-->
								<td><center><b><?php echo round($allnd_OT); ?></b></td>
								<!--Special holiday-->
								<td><center><b><?php if(@$special_holiday != ''){
																			echo round($special_holiday); 
																		}else{
																			echo "0";
																		}
																?>
								</b></center></td>
								<td><center><b><?php echo round($shndtotal); ?></b></center></td>
								<td><center><b><?php echo round($shregularothours + $regularbrokenSHOT); ?></b></center></td>	
								<td><center><b><?php echo round($totalshndothours + $totalbrokenSHNDOT); ?></b></center></td>
								<!--Legal holiday-->
								<td><center><b><?php if (@$legal_holiday != '') {
																			echo round($legal_holiday); 
																		}else{
																			echo "0";
																		}
																	?>
								</b></center></td>
								<td><center><b><?php echo round($lhndtotal); ?></b></center></td>
								<td><center><b><?php echo round($lhregularothours + $regularbrokenLHOT); ?></b></center></td>
								<td><center><b><?php echo round($totallhndothours + $totalbrokenLHNDOT); ?></b></center></td>
								<td><center><b>0</b></center></td>
							</tr>		

							<?php

	

		@$mantotal += 1;
		@$total01 += ($row3['COUNT(*)']) -  (@$total + @$minus);
		@$total02 += @$regularothours + $regularbrokenOT;
		@$total03 += $regndtotal;
		@$total04 += (@$spcregday1 + @$spcregday2);
		@$total05 += ($row10['vl']);
		@$total06 += ($latetotal /60 + @$ada / 60);
		@$total07 += (@$totalUT + @$totalUT2) - @$grandgen;
		@$total08 += $holiday;
		@$total09 += $totalndothours + $totalbrokenNDOT;


						}


					@$statusall = 0;
						$regndtotal = 0;
						//$total2 = 0;
						//$total3 = 0;
						$latetotal = 0;
						//@$totalssa = 0;
						$ada = 0;
						$spcregday1 = 0;
						$spcregday2 = 0;
						$totalUT = 0;
						$totalUT2 = 0;
						$total = 0;
						$holiday = 0;
						$mins1 = 0;
						$mins2 = 0;
						$gentotal = 0;
						$grandgen = 0;
						$minus = 0;
				

						}elseif($daterow22['idgen'] != 0) {

								?>

		
						
							<tr>
								<td><center><?php echo $empno; ?></center></td>
								<td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
								<td><center><?php echo $branch; ?></center></td>
								<!--ATTENDANCE-->
								<td><center><?php echo $daterow22['dayswork']; ?></center></td>
								<td><center><?php echo $daterow22['lateover']; ?></center></td>
								<td><center><?php echo $daterow22['undertime']; ?></center></td>
								<td><center><?php echo $daterow22['vleave']; ?></center></td>
								<!--ORDINARY DAY-->
								<td><center><?php echo $daterow22['nightdiff'];  ?></center></td>
								<td><center><?php echo $daterow22['regularot']; ?></center></td>
								<td><center><?php echo $daterow22['nightdiffot']; ?></center></td>
								<!--SPECIAL HOLIDAY-->
								<td><center><?php echo $daterow22['specialday']; ?></center></td>
								<td><center><?php echo $daterow22['specialdaynd'];  ?></center></td>
								<td><center><?php echo $daterow22['specialdayot'];  ?></center></td>
								<td><center><?php echo $daterow22['specialdayndot'];  ?></center></td>
								<!--LEGAL HOLIDAY-->
								<td><center><?php echo $daterow22['legalday'];  ?></center></td>
								<td><center><?php echo $daterow22['legaldaynd'];  ?></center></td>
								<td><center><?php echo $daterow22['legaldayot'];  ?></center></td>
								<td><center><?php echo $daterow22['legaldayndot'];  ?></center></td>
								<!--WORKING OFF-->
								<td><center><?php echo $daterow22['workdayoiff'];  ?></center></td>
							</tr>

								


<?php
}

}


?>

<!--
<?php

							if(@$daterow22['idgen'] != 0){
							?>

										<tr>
											<td>Total</td>
											<td><?php echo $mantotal; ?></td>
											<td></td>
											<td><?php echo $total01; ?></td>
											<td><?php echo $total02; ?></td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td><?php echo $total03; ?></td>
											<td><?php echo $total08; ?></td>
											<td><?php echo $total04; ?></td>
											<td><?php echo $total05; ?></td>
											<td><?php echo $total09; ?></td>
											<td><?php echo $total06; ?></td>
											<td><?php echo $total07; ?></td>
											

										</tr>
									<?php } ?>
-->
						</tbody>


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