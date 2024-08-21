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
								<th><center>ID</center></th>
								<th><center>FULLNAME</center></th>
								<th><center>BRANCH</center></th>
								<th><center># OF DAYS</center></th>
								<th><center>REG.OT</center></th>
								<th><center>W.DAYOFF</center></th>
								<th><center>L.HOL-OT</center></th>
								<th><center>S.HOL-OT</center></th>
								<th><center>N.DIFF</center></th>
							<!-- <th><center>N.OT</center></th> -->
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
							 $branch = $row1['branch'];
				
$sql22="SELECT * FROM generated WHERE empno = '$empno' AND datefrom = '$datefrom' AND dateto = '$dateto'";	
$query22=$HRconnect->query($sql22);  
$daterow22=$query22->fetch_array();

		if(@$daterow22['idgen'] == 0){

				$sql90="SELECT * FROM sched_time 
				WHERE sched_time.empno = '$empno' AND (datefromto between '$datefrom' AND '$dateto') AND status != 'deleted'";
						$query90=$HRconnect->query($sql90);
						while($row90=$query90->fetch_array()){
							@$datefrom2 = $row90['schedfrom'];
							@$dateto2 = $row90['schedto'];
							@$datefromto = $row90['datefromto'];
							@$mtimein1 = $row90['M_timein'];
							@$mtimeout1 = $row90['M_timeout'];
							@$atimein1 = $row90['A_timein'];
							@$atimeout1 = $row90['A_timeout'];	
							@$break1 = $row90['break'];


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



 				// Holiday Date 
 				 $sql13 = "SELECT * FROM holiday 
 				 			WHERE '$datefromto' in(holiday_day,prior1,prior2,prior3)";

                		  $query13=$HRconnect->query($sql13);
                 		  $row13=$query13->fetch_array();


 				 $sql14 = "SELECT * FROM holiday 
 				 			WHERE '$vldate' in(holiday_day,prior1,prior2,prior3)";

                		  $query14=$HRconnect->query($sql14);
                 		  $row14=$query14->fetch_array();

                 if ((@$mtimein1 != '' AND @$row13['idholiday'] != '') OR @$row14['idholiday'] != '' ){
					$holiday = 8;
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
if(($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != '') AND @$statusall == 0){
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
}





// Morning Night Diff 

if (($mtimein1 >= $datefromto ." "."23:00" AND $mtimein1 <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") AND 
	($mtimeout1 >= $datefromto ." "."23:00" AND $mtimeout1 <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00")
	AND $mtimeout1 != "No Break"){

   @$night1 = floor((strtotime($mtimeout1) - strtotime($mtimein1))/3600);

}elseif($mtimein1 >= $datefromto ." "."23:00" AND $mtimeout1 <= $datefromto ." "."23:00" AND $mtimeout1 != "No Break"){

 @$night1 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($mtimeout1))/3600);

}elseif($mtimein1 <= $datefromto ." "."23:00" AND $mtimeout1 >= $datefromto ." "."23:00" AND $mtimeout1 != "No Break"){

 @$night1 = floor((strtotime($mtimeout1) - strtotime($datefromto ." "."22:00"))/3600);

 // Morning Night Diff 
}elseif($mtimein1 <= $datefromto ." "."06:00"){

 @$night1 = floor(( strtotime($datefromto ." "."06:00") - strtotime($datefrom2))/3600);

}



if (($atimein1 >= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00") AND 
	($atimein1 < date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00" AND $atimeout1 < date('Y-m-d', strtotime( $datefromto . " +1 days"))." "."06:00") 
	AND $atimein1 != "No Break"){

@$night2 = floor((strtotime($atimeout1) - strtotime($atimein1))/3600);

}elseif($atimein1 >= $datefromto ." "."23:00" AND $atimeout1 <= $datefromto ." "."23:00" AND $atimein1 != "No Break"){

 @$night2 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($atimeout1))/3600);

}elseif($atimein1 <= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00" AND $atimein1 != "No Break" AND $atimeout1 <= $dateto2){

@$night2 = floor((strtotime($atimeout1) - strtotime($datefromto ." "."22:00"))/3600);

}elseif($atimein1 <= $datefromto ." "."23:00" AND $atimeout1 >= $datefromto ." "."23:00" AND $atimein1 != "No Break" AND $atimeout1 >= $dateto2 AND $dateto2 > date("Y-m-d H:i" ,strtotime($datefromto ." "."22:00"))){

 @$night2 = floor((strtotime($dateto2) - strtotime($datefromto ." "."22:00"))/3600);

}







if (($atimein1 == 'No Break' AND $atimeout1 > $datefromto ." "."23:00" AND $atimeout1 <= $dateto2)){

   @$night1 = floor((strtotime($atimeout1) - strtotime($datefromto ." "."22:00"))/3600);

}elseif(($atimein1 == 'No Break' AND $atimeout1 > $datefromto ." "."23:00" AND $datefromto ." "."23:00" <= $dateto2)){

	 @$night1 = floor((strtotime($dateto2) - strtotime($datefromto ." "."22:00"))/3600);
}


@$total1 += @$night1;
$night1 = 0;

@$total2 += @$night2;
$night2 = 0;

@$total3 += @$night3;
$night3 = 0;

}




if(($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != '') AND @$statusall == 0){
	
				$sql5="SELECT SUM(othours) as OT FROM overunder
			WHERE empno = '$empno' AND otdatefrom = '$datefromto' AND ottype in  ('0','') AND otstatus = 'approved'";
			$query5=$HRconnect->query($sql5);
			$row5=$query5->fetch_array();
if($mtimein1 != '' AND $mtimeout1 != '' AND $atimein1 != '' AND $atimeout1 != ''){
	@$regOT = @$row5['OT'];


	//GEN MEET OT TOTAL
			$gmeetTOTAL="SELECT SUM(othours) as OT FROM overunder
			WHERE empno = '$empno' AND otdatefrom = '$datefromto' AND ottype in ('1','2') AND otstatus = 'approved'";
			$querytotal=$HRconnect->query($gmeetTOTAL);
			$rowtotal=$querytotal->fetch_array();

	@$GenMeetTotalOT = $rowtotal['OT'];

	$otFinal = $regOT + $GenMeetTotalOT;

	@$totalssa += $otFinal;
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
								<td><center><?php echo $empno; ?></center></td>
								<td><center><?php echo html_entity_decode(htmlentities($name)); ?></center></td>
								<td><center><?php echo $row2['branch']; ?></center></td>
								<td><center><?php echo ($row3['COUNT(*)']) -  (@$total + @$minus); ?></center></td>
								<td><center><b><?php 
					if (@$totalssa != '') {
					echo @$totalssa; 
				}else{
					echo "0";
				}
					?></b></center></td>
								<td><center>0</center></td>
								<td><center>0</center></td>
								<td><center>0</center></td>
								<td><center><?php echo @$total1 + @$total2 + @$total3; ?></center></td>
					<td><center><b><?php 
					if (@$holiday != '') {
					echo @$holiday; 
				}else{
					echo "0";
				}                                                                          

					?></b></center></td>
								<td><center><?php echo @$spcregday1 + @$spcregday2; ?></center></td>
								<td><center><?php echo @$row10['vl']; ?></center></td>
								<td><center>0</center></td>
								<td><center><?php echo @$latetotal /60 + @$ada / 60; ?></center></td>
								<td><center><?php echo  (@$totalUT + @$totalUT2) - @$grandgen; ?></center></td>
				
							</tr>		

							<?php

	

		@$mantotal += 1;
		@$total01 += ($row3['COUNT(*)']) -  (@$total + @$minus);
		@$total02 += @$totalssa;
		@$total03 += ( @$total1 + @$total2 + @$total3);
		@$total04 += (@$spcregday1 + @$spcregday2);
		@$total05 += ($row10['vl']);
		@$total06 += ($latetotal /60 + @$ada / 60);
		@$total07 += (@$totalUT + @$totalUT2) - @$grandgen;
		@$total08 += $holiday;


						}


					@$statusall = 0;
						$total1 = 0;
						$total2 = 0;
						$total3 = 0;
						$latetotal = 0;
						@$totalssa = 0;
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
								<td><center><?php echo $daterow22['dayswork']; ?></center></td>
								<td><center><?php echo $daterow22['regularot']; ?></center></td>
								<td><center>0</center></td>
								<td><center>0</center></td>
								<td><center><?php echo $daterow22['specialdayot'];  ?></center></td>
								<td><center><?php echo $daterow22['nightdiff'];  ?></center></td>
								<!-- <td><center>0</center></td> -->
								<td><center><?php echo $daterow22['legalday'];  ?></center></td>
								<td><center><?php echo $daterow22['specialday']; ?></center></td>
								<td><center><?php echo $daterow22['vleave']; ?></center></td>
								<td><center>0</center></td>
								<td><center><?php echo $daterow22['lateover']; ?></center></td>
								<td><center><?php echo $daterow22['undertime']; ?></center></td>
				
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
											<td>0</td>
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