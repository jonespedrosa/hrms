<?php 
 $connect = mysqli_connect("localhost", "root", "", "db");
 $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Mary Grace Foods Inc.</title>

		<link rel="icon" href="../images/logoo.png">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<!------ Include the above in your HEAD tag ---------->
		<!------ AUTO PRINT CODE <script>window.print();</script> -->
		<style type="text/css">
			@page {size:portrait}  
			body{
				page-break-before: avoid;
				}
			@media print{
				.table td{
					background-color: transparent !important;
				}
				.table th{
					background-color: transparent !important;
				}
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
			.myTable td,.myTable th { 
				padding: 5px;
				border: 2px solid black;
			}
		</style>
	</head>
	<body>
		<p style="page-break-before: always">
			<div class="col-12">
				<table class="myTable">
					<thead >
						<?php 
							$empid = $_GET["empid"];
							$cutfrom = $_GET["cutfrom"];
							$cutto = $_GET["cutto"];
							$sql="SELECT * FROM sched_info 
								WHERE empno = '$empid'
								";
							$query=$HRconnect->query($sql);
							$row=$query->fetch_array();


							$sql2="SELECT * FROM user_info 
								WHERE empno = '$empid'
								";
							$query2=$HRconnect->query($sql2);
							$row2=$query2->fetch_array();
							$name1 = $row2['name'];
							$post = $row2['position'];
							$branch = $row2['branch'];
						?>
											
						<tr>
							<th colspan="100%" class="text-muted text-uppercase">
								Employee # : <b class="text-danger"><?php echo $_GET["empid"]; ?></b>	
								<div class="row">
									<div class="col-5 text-uppercase">
									</div>
										<?php if(@$_GET["backtrack"] == "backtrack"){ ?>
										<center><a href="../viewsched.php?backtrack=backtrack"><img src ="../images/logoo.png" width="90" height="90"></center></a>
									<?php }else{ ?>
										<center><a href="../viewsched.php?current=current"><img src ="../images/logoo.png" width="90" height="90"></center></a>
									<?php } ?>
								</div>

							
							
								<div class="row">
									<div class="col-5">
										<p class="text-uppercase">
											Branch: <b><?php echo $branch; ?></b> <br/>
											Name: <b><?php echo $name1; ?></b>
										</p>
									</div>
									
									<div class="col-3">
									</div>

									<div class="col-4">
										<p class="text-uppercase"> 	
											Cut off :  <b> <?php echo date("m-d-Y", strtotime($_GET["cutfrom"])); ?> - <?php echo date("m-d-Y", strtotime($_GET["cutto"])); ?></b>
											<br />
											Position: <b><?php echo $post; ?></b> 
										</p>
									</div> 
								
								</div>					
							</th>
						</tr>
						<tr class="text-uppercase">
							<th rowspan="2"><center><b>Date</b></center></th>
							<th rowspan="2"><center><b>Schedule</b></center></th>  
							<th rowspan="2"><center><b>Break</b></center></th>   
							<th colspan="4"><center><b></b></center></th>
							<th rowspan="2"><center><b>OT Hours</b></center></th>
							<th colspan="2"><center><b>Gen Meet/Gen Clean</b></center></th>
							<th rowspan="2" colspan="3"><center><b>Remarks</b></center></th>	
						</tr>
						<tr class="text-uppercase">											
							<th><center><b>Time in</b></center></th>
							<th><center><b>Break Out</b></center></th>
							<th><center><b>Break in</b></center></th>
							<th><center><b>Time Out</b></center></th>	
							<th><center><b>Time in</b></center></th>
							<th><center><b>Time Out</b></center></th>
						</tr>
					</thead>
				<tbody>
				<?php
					$sql1="SELECT * FROM sched_time 
					WHERE sched_time.empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND status != 'deleted' ORDER BY datefromto ASC";
					$query1=$HRconnect->query($sql1);
					@$regularothours=0;
					@$totalndothours=0;
					while($row1=$query1->fetch_array()){
						@$status = $row1['status'];
						$datefromto = $row1['datefromto'];

						$datefrom = $row1['schedfrom'];
						$dateto = $row1['schedto'];

						$mtimein = $row1['M_timein'];
						$m_in_status = $row1['m_in_status'];
						$min_empno = $row1['min_empno'];

						$mtimeout = $row1['M_timeout'];
						$m_o_status = $row1['m_o_status'];
						$mo_empno = $row1['mo_empno'];

						$atimein = $row1['A_timein'];
						$a_in_status = $row1['a_in_status'];
						$ain_empno = $row1['ain_empno'];

						$atimeout = $row1['A_timeout'];
						$a_o_status = $row1['a_o_status'];
						$ao_empno = $row1['ao_empno'];
					
				
						$break = $row1['break'];
						$breaktotal = $break * 10000;
						$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom']))/3600;

						if(($equal == 19 AND $row1['break'] == 10) OR ($equal == 17 AND $row1['break'] == 9) OR ($equal == 16 AND $row1['break'] == 8) OR ($equal == 15 AND $row1['break'] == 7) OR ($equal == 14 AND $row1['break'] == 6) OR ($equal == 13 AND $row1['break'] == 5) OR ($equal == 12 AND $row1['break'] == 4) OR ($equal == 11 AND $row1['break'] == 3) OR ($equal == 10 AND $row1['break'] == 2) OR ($equal == 9 AND $row1['break'] == 1) OR ($equal == 8 AND $row1['break'] == 0)) { 
							//NO STATEMENT
						}else{
							if($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != ''){
								@$minus += 1;
								@$statusall += 1;
							}
						}
						//CALCULATE OT HOURS
						@$othours = '';
						

						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){

							$sql11 = "SELECT * FROM overunder
									WHERE empno = $empid
									AND otdatefrom = '$datefromto' AND otstatus != 'canceled' ";
							$query11=$HRconnect->query($sql11);
							$row11=$query11->fetch_array();
							@$othours = $row11['othours'];
							
						}
						
						// @$totalndothours;;
						//END OF CALCUATE OT HOURS
										
						//CALCULATE LATE HOURS
						if(@$statusall == 0){
							$sql4="SELECT SUM(time_to_sec(TIMEDIFF(M_timein,schedfrom))) as timein FROM sched_time
									WHERE empno = '$empid' AND (datefromto  = '$datefromto')  AND schedfrom < M_timein AND 
									(m_in_status = 'Approved' OR min_empno != '')";
							$query4=$HRconnect->query($sql4);
							$row4=$query4->fetch_array();
							@$latetotal +=  @$row4['timein']/60; 
						}
						//END OF CALCULATE LATE HOURS

						//FOR HOURS CALCULATION

						//SPECIAL HOLIDAY/NIGHT DIFF CALCULATION
						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
							//SPECIAL HOLIDAY
							// $hdaysql = "SELECT COUNT(*) as 'count' FROM holiday WHERE holiday_day = $datefromto";
							// $hdayquery=$HRconnect->query($hdaysql);
							// $rowhday =$hdayquery->fetch_array();

							// if($rowhday['count']>0){
							// 	if ($mtimein != '' AND $atimeout != '' AND ($datefrom <= $mtimein AND $dateto >= $atimeout)){
							// 		@$spcregday1 = floor((strtotime($atimeout) - strtotime($datefrom))/3600) - $break;
							// 	}elseif ($mtimein != '' AND $atimeout != '' AND $datefrom >= $mtimein AND $dateto <= $atimeout){
							// 		@$spcregday1 = floor((strtotime($dateto) - strtotime($datefrom))/3600) - $break;
							// 	}elseif ($mtimein != '' AND $atimeout != '' AND $datefrom < $mtimein AND $dateto < $atimeout){
							// 		@$spcregday1 = floor((strtotime($dateto) - strtotime($datefrom))/3600) - $break;
							// 	}elseif ($mtimein != '' AND $atimeout != '' AND $datefrom > $mtimein AND $dateto > $atimeout){
							// 		@$spcregday1 = floor((strtotime($atimeout) - strtotime($datefrom))/3600) - $break;
							// 	}
							// }
							//END OF SPECIAL HOLIDAY
							//NIGHT DIFF NEW CODE
							$cus_ndstarta = strtotime($datefromto. " "."00:00");
							$cus_ndenda = strtotime($datefromto." "."06:00");
							$cus_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$cus_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$cus_cmtimein = strtotime($mtimein) < strtotime($datefrom) ? strtotime($datefrom) : strtotime($mtimein); //base time in AM
							$cus_cmtimeout = !($mtimeout === "No Break") ? strtotime($mtimeout) : $mtimeout;
							$cus_catimein = $atimein;
							if(!($mtimeout === "No Break")){
								$cus_catimein = (strtotime($atimein)-$cus_cmtimeout)<=3600 ? ($cus_cmtimeout + 3600):strtotime($atimein);
							}
							$cus_catimeout= strtotime($atimeout) > strtotime($dateto) ? strtotime($dateto) : strtotime($atimeout);//base time out PM
							$cus_ndbreak = 0;
							$cus_ndoverbreak = 0;

							if($mtimeout === "No Break"){
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
							
								@$regndtotal += ($cus_ndoutlog - $cus_ndinlog)/3600;
							}
							if(!($cus_cmtimein < $cus_ndstartb AND $cus_catimeout <$cus_ndstartb) AND !($cus_cmtimein >$cus_ndendb AND $cus_catimeout >$cus_ndendb)){
								$cus_ndinlog = $cus_cmtimein < $cus_ndstartb ? $cus_ndstartb : $cus_cmtimein;
								$cus_ndoutlog = $cus_catimeout > $cus_ndendb ? $cus_ndendb : $cus_catimeout;
								@$regndtotal += ($cus_ndoutlog - $cus_ndinlog)/3600;
							}

							// //NIGHT DIFFERENTIAL
							// if (($mtimein >= $datefromto ." "."23:00" AND $mtimein <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") AND 
							// 	($mtimeout >= $datefromto ." "."23:00" AND $mtimeout <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00")
							// 	AND $mtimeout != "No Break"){
							// 	@$night1 = floor((strtotime($mtimeout) - strtotime($mtimein))/3600);
							// //kapag ang time inputs gabi hanggang umaga at tuamam sa 10:00pm to 06:00am ND
							// }elseif(($mtimein <= $datefromto ." "."22:00" AND $atimeout >= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") 
							// 	AND $mtimeout != "No Break"){
							// 	@$night1 = floor((strtotime(date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") - strtotime($datefromto ." "."22:00"))/3600) - 1;
							// //ND ng timein to breakout may late
							// }elseif($mtimein >= $datefromto ." "."23:00" AND $mtimeout <= $datefromto ." "."23:00" AND $mtimeout != "No Break"){
							// 	@$night1 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($mtimeout))/3600);
							// //ND ng timein to breakout walang late
							// }elseif($mtimein <= $datefromto ." "."23:00" AND $mtimeout >= $datefromto ." "."23:00" AND $mtimeout != "No Break"){
							// 	@$night1 = floor((strtotime($mtimeout) - strtotime($datefromto ." "."22:00"))/3600);
							// // Morning Night Diff 
							// }elseif($mtimein <= $datefromto ." "."06:00"){
							// 	@$night1 = floor(( strtotime($datefromto ." "."06:00") - strtotime($datefrom))/3600);
							// }
						

							// if (($atimein >= $datefromto ." "."23:00" AND $atimeout >= $datefromto ." "."23:00") AND 
							// 	($atimein < date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00" AND $atimeout < date('Y-m-d', strtotime( $datefromto . " +1 days"))." "."06:00") 
							// 	AND $atimein != "No Break"){
							// 	@$night2 = floor((strtotime($atimeout) - strtotime($atimein))/3600);
							// }elseif($atimein >= $datefromto ." "."23:00" AND $atimeout <= $datefromto ." "."23:00" AND $atimein != "No Break"){
							// 	@$night2 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($atimeout))/3600);
							// }elseif($atimein <= $datefromto ." "."23:00" AND $atimeout >= $datefromto ." "."23:00" AND $atimein != "No Break" AND $atimeout <= $dateto){
							// 	@$night2 = floor((strtotime($atimeout) - strtotime($datefromto ." "."22:00"))/3600);
							// }elseif($atimein <= $datefromto ." "."23:00" AND $atimeout >= $datefromto ." "."23:00" AND $atimein != "No Break" AND $atimeout >= $dateto AND $dateto >= date("Y-m-d H:i" ,strtotime($datefromto ." "."23:00"))){
							// 	@$night2 = floor((strtotime($dateto) - strtotime($datefromto ." "."22:00"))/3600);
							// }

							// if (($atimein == 'No Break' AND $atimeout >= $datefromto ." "."23:00" AND $atimeout <= $dateto)){
							// 	@$night1 = floor((strtotime($atimeout) - strtotime($datefromto ." "."22:00"))/3600);
							// }elseif(($atimein == 'No Break' AND $atimeout >= $datefromto ." "."23:00" AND $datefromto ." "."23:00" <= $dateto)){
							// 	@$night1 = floor((strtotime($dateto) - strtotime($datefromto ." "."22:00"))/3600);
							// }
							// @$total1 += @$night1;
							// $night1 = 0;

							// @$total2 += @$night2;
							// $night2 = 0;

							// @$total3 += @$night3;
							// $night3 = 0;
								//END OF NIGHT DIFFERENTIAL
						}
						//END OF SPECIAL HOLIDAY/NIGHT DIFF CALCULATION

						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
							$sql8 = "SELECT ADDTIME('$mtimeout','$breaktotal') as zxc FROM sched_time
									WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')
									AND M_timeout != 'null'";
							$query8=$HRconnect->query($sql8);
							$row8=$query8->fetch_array();
							$totals = $row8['zxc'];

							if ($atimein > $totals){	
								$totalsss = strtotime($atimein) - strtotime($totals);
								if(($m_o_status == 'Approved' OR $mo_empno != '') AND ($a_in_status == 'Approved' OR $ain_empno != '')) {
									@$ada+=$totalsss;
								}
							}
						}

						$sql9 = "SELECT * FROM vlform
								WHERE vlstatus = 'approved' AND empno = $empid
								AND vldatefrom = '$datefromto'";
						$query9=$HRconnect->query($sql9);
						$row9=$query9->fetch_array();


						$sql15 = "SELECT * FROM obp
								WHERE empno = $empid AND datefromto = '$datefromto'";
						$query15=$HRconnect->query($sql15);
						$row15=$query15->fetch_array(); 

						@$vldate = $row9['vldatefrom']; 

						$sql13 = "SELECT * FROM holiday 
								WHERE '$datefromto' in(holiday_day,prior1,prior2,prior3)";

						$query13=$HRconnect->query($sql13);
						$row13=$query13->fetch_array();

						$sql14 = "SELECT * FROM holiday 
								WHERE '$vldate' in(holiday_day,prior1,prior2,prior3)";

						$query14=$HRconnect->query($sql14);
						$row14=$query14->fetch_array();

						if ((@$mtimein != '' AND @$row13['idholiday'] != '') OR @$row14['idholiday'] != '' ){
							@$holiday = 8;
						}
						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
							$sql5="SELECT SUM(othours) as OT FROM overunder
								WHERE empno = '$empid' AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
							$query5=$HRconnect->query($sql5);
							$row5=$query5->fetch_array();
							@$totalssa += @$row5['OT'];
							
							//INSERT OT CODE HERE
							$cus_approvedot = $row5['OT'];
							$cus_t1 =strtotime($dateto) + ($cus_approvedot * 3600);
							$cus_t2 =strtotime($datefromto ." "."22:00");
							$cus_t3 =$cus_t2+(8*3600);
							$cus_regot = $cus_approvedot;
							if(($cus_t1 > $cus_t2) AND $cus_approvedot >0){
								$cus_ndot=0;
								if(strtotime($dateto)<$cus_t2){
									$cus_ndot= ($cus_t1-$cus_t2)/3600;
								}else{
									$cus_ndot= $cus_approvedot;
								}

								$cus_regot = $cus_approvedot-$cus_ndot;
								@$totalndothours += $cus_ndot;
							}

							@$regularothours += $cus_regot;
							if(($cus_t1 > $cus_t3) AND $cus_approvedot > 0){
								@$totalndothours -= (($cus_t1-$cus_t3)/3600);
								@$regularothours += (($cus_t1-$cus_t3)/3600);
							}

							$sql12="SELECT SUM(time_to_sec(TIMEDIFF(schedto, A_timeout))) as timeout2 FROM sched_time
								WHERE sched_time.empno = '$empid' AND datefromto = '$datefromto' AND schedto > A_timeout ";
							$query12=$HRconnect->query($sql12);
							$row12=$query12->fetch_array();
							$UT2 = $row12['timeout2'] /60;

							@$totalUT2 += $UT2;

							$red = 0;

							if($row1['timein4'] != '' AND $row1['timeout4'] != ''){
								$start = strtotime($row1['timein4']);
								$end = strtotime($row1['timeout4']);
								$mins1 = ($end - $start) / 60;

								if($row1['schedto'] > $row1['A_timeout']){
									$start = strtotime($row1['schedto']);
									$end = strtotime($row1['A_timeout']);
									$mins2 = ($start - $end) / 60;
								}         		  

								if(@$mins1 >= @$mins2){
									@$gentotal = @$mins2;
									$red = 1;
								}else{
									@$gentotal = @$mins1;
								}
								@$grandgen = @$gentotal;
							}
						}
				?>
						<?php 
						if($row1['M_timein'] == ''){
						?>
							<tr>
								<td><center><?php echo  date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td><center></center></td>
								<td class="text-uppercase" colspan="2"><center><?php echo $row1['remarks']; ?> <?php echo @$row9['vltype']; ?> 
									<?php
									if(@$row15['status'] == 'Pending' OR @$row15['status'] == 'Pending2'){
										echo "<i class='text-danger'>"."OBP " .@$row15['status']. "</i>"; 
									}elseif(@$row15['status'] == 'Approved'){
										echo "<i class='text-success'>"."OBP " .@$row15['status']. "</i>";
									}
									?> </center>
								</td>
							</tr>
						<?php 
						}	
						?>
						
						<?php 
						if($row1['M_timein'] != ''){
						?>

						<tr>
							<td><center><?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
							<?php 
							if(($equal == 19 AND $row1['break'] == 10) OR ($equal == 17 AND $row1['break'] == 9) OR ($equal == 16 AND $row1['break'] == 8) OR ($equal == 15 AND $row1['break'] == 7) OR ($equal == 14 AND $row1['break'] == 6) OR ($equal == 13 AND $row1['break'] == 5) OR ($equal == 12 AND $row1['break'] == 4) OR ($equal == 11 AND $row1['break'] == 3) OR ($equal == 10 AND $row1['break'] == 2) OR ($equal == 9 AND $row1['break'] == 1) OR ($equal == 8 AND $row1['break'] == 0)) { 
							?>
						<td>
					<?php
					}else{
						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
						}
						?>
						<td class="text-danger">
						<?php
					}
						?><center><?php echo date("H:i", strtotime($row1['schedfrom'])); ?> - <?php echo date("H:i", strtotime($row1['schedto'])); ?> </center></td>
						<td><center><?php echo $row1['break']; ?><center></td>
				<?php 
						if($datefrom < $mtimein){
					?>

						<td class="text-danger"><b>

				<?php 
					}else{
					?>	
					<td>						
				<?php 
						}
					?>
						<center>
							<?php if($m_in_status == 'Approved' OR $min_empno != '' OR $row1['M_timein'] == '')
							{ 

								if($row1['M_timein'] != ''){
							echo date('H:i', strtotime($row1['M_timein'])); 
								}else{
								echo "";
								}
							}else{

							echo "Pending";
							}

							?>
								
							</center></b></td>
						<td><center>


							<?php if($m_o_status == 'Approved' OR $mo_empno != '' OR $row1['M_timeout'] == '')
							{ 

								if($row1['M_timeout'] != '' AND $row1['M_timeout'] != 'No Break'){
							echo date('H:i', strtotime($row1['M_timeout'])); 
								}elseif($row1['M_timeout'] == 'No Break'){
								echo $row1['M_timeout'];
								}else{
									echo "";
								}
							}else{

							echo "Pending";
							}

							?>

							<b></b></center></td>


				<?php 
						if(@$totals < $atimein AND $atimein != 'No Break'){	
					?>	<td class="text-danger"><b>

				<?php 
					}else{
					?>	
						<td>

				<?php 
					}
					?>
						<center>

						<?php if($a_in_status == 'Approved' OR $ain_empno != '' OR $row1['A_timein'] == '')
							{ 

								if($row1['A_timein'] != '' AND $row1['A_timein'] != 'No Break'){
							echo date('H:i', strtotime($row1['A_timein'])); 
								}elseif($row1['A_timein'] == 'No Break'){
								echo $row1['A_timein'];
								}else{
									echo "";
								}

							}else{

							echo "Pending";
							}

							?>
							</center></b></td>

				<?php 

					$outtime = strtotime($atimeout);
					$startime = strtotime($dateto);

					if($startime > $outtime){
						if(@$red == 0){
					?>

						<td class="text-danger"><b>

				<?php 
				}else{
	?>
		<td>
	<?php

				}
					}else{
					?>	
					<td>
							
							
				<?php 
						}
					?>
						<center>

						<?php if($a_o_status == 'Approved' OR $ao_empno != '' OR $row1['A_timeout'] == '')
							{ 

								if($row1['A_timeout'] != ''){
							echo date('H:i', strtotime($row1['A_timeout'])); 
								}else{
								echo "";
								} 

							}else{

							echo "Pending";
							}

							?>

						</b></center></td>
					<td class="text-primary"><b>
						<center>
				<?php 
						
								if(@$row11['otstatus'] == 'approved')
							{ 

							echo @$othours; 

							}elseif(@$othours != ''){
						
							echo "Pending";

							}
							
							
							?>

						</center></b></td>


							<td><b>
				
						<center>
							
							<?php 
						
							if($row1['timein4'] != '')
							{ 

							echo date('H:i', strtotime(@$row1['timein4'])); 

							}
							
							?>
						</center></b></td>

							<td><b>
				
						<center>
							
							<?php 
						
							if($row1['timeout4'] != '')
							{ 

							echo date('H:i', strtotime(@$row1['timeout4'])); 

							}
							
							?>
						</center></b></td>
						<td class="text-uppercase" colspan="3"><center><?php echo $row1['remarks']; ?><?php echo @$row9['vltype']; ?> 
						<?php if(@$row15['status'] == 'Pending' OR @$row15['status'] == 'Pending2'){ echo "<i class='text-danger'>"."OBP " .@$row15['status']. "</i>"; 
					}elseif(@$row15['status'] == 'Approved'){ echo "<i class='text-success'>"."OBP " .@$row15['status']. "</i>"; } ?> 
						</center></td>
					</tr>
				<?php
			}

			@$statusall = 0;

			}
			
			?>

				</tbody>
				
					<tr class="table-secondary text-uppercase"> 									
						<td><center># of days</center></td>
						<td><center>Reg OT (Hours)</center></td>
						<td><center>W.Off</center></td>
						<td><center>L.Hol-Ot</center></td>
						<td><center>S.Hol-Ot</center></td>						
						<td><center>R.Hol</center></td>
						<td><center>S.Hol</center></td>
						<td><center>N.Diff</center></td>
						<td><center>N.OT (Hours)<center></td>
						<td><center>Leave</center></td>
						<td><center>Late</center></td>
						<td><center>UT</center></td>					
					</tr>
					
	<?php 


				$sql3="SELECT COUNT(*) FROM sched_time
								WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')  AND (M_timein != '' AND M_timeout != '' AND A_timein != '' AND A_timeout != '')";
							$query3=$HRconnect->query($sql3);
							$row3=$query3->fetch_array();    




					$sql10="SELECT COUNT(*) as vl FROM vlform
								INNER JOIN sched_time ON vlform.empno = sched_time.empno
								WHERE vlform.empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND datefromto = vldatefrom AND vlstatus = 'approved'";
							$query10=$HRconnect->query($sql10);
							$row10=$query10->fetch_array();





							?>

		
	<?php 
	if(@$_GET['backtrack'] != 'backtrack'){
							?>
					<tr>
						<td><center><b><?php echo $row3['COUNT(*)'] - @$minus; ?></b></center></td>
						<td><center><b><?php 
						if (@$regularothours != '') {
						echo @$regularothours; 
					}else{
						echo "0";
					}
						?></b></center></td>
						<td><center><b><?php echo $row['workoff']; ?></b></center></td>
						<td><center><b>l hol</b></center></td>
						<td><center><b>s hol</b></center></td>
						<td><center><b><?php 
						if (@$holiday != '') {
						echo @$holiday; 
					}else{
						echo "0";
					}

						?></b></center></td>
						<td><center><b><?php echo @$spcregday1 + @$spcregday2; ?></b></center></td>
						<!-- <td><center><b><?php echo @$total1 + @$total2 + @$total3; ?></b></center></td> -->
						<td><center><b><?php echo @$regndtotal; ?></b></center></td>
						<td><center><b><?php echo @$totalndothours ?></b></td>
						<td><center><b><?php echo $row10['vl']; ?></b></center></td>
						<td><center><b><?php echo $latetotal + @$ada / 60; ?></b></center></td>
						<td><center><b><?php echo  @$totalUT2 - @$grandgen; ?></b></center></td>
					</tr>

		<?php }else{ 


	$sqlo="SELECT * FROM generated
							WHERE empno = '$empid' AND datefrom = '$cutfrom' AND dateto = '$cutto'";
							$queryo=$HRconnect->query($sqlo);
							$rowo=$queryo->fetch_array();

			?>
			<?php if (@$rowo['dayswork'] == "") { ?>
					
		<tr>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
						<td><center><b>0</b></center></td>
					</tr>

				<?php }else{ ?>
		<tr>
						<td><center><b><?php echo $rowo['dayswork']; ?></b></center></td>
						<td><center><b><?php echo $rowo['regularot']; ?></b></center></td>
						<td><center><b><?php echo $rowo['workdayoiff']; ?></b></center></td>
						<td><center><b>l ot</b></center></td>
						<td><center><b>s ot</b></center></td>
						<td><center><b><?php echo $rowo['legalday']; ?></b></center></td>
						<td><center><b><?php echo $rowo['specialday']; ?></b></center></td>
						<td><center><b><?php echo $rowo['nightdiff']; ?></b></center></td>
						<td><center><b>WIP</b><center></td>
						<td><center><b><?php echo $rowo['vleave']; ?></b></center></td>
						<td><center><b><?php echo $rowo['lateover']; ?></b></center></td>
						<td><center><b><?php echo  $rowo['undertime']; ?></b></center></td>
					</tr>

				<?php } 
			}?>

		
				
					
			</table>
			
				<p class="text-muted"><i>I CERTIFY on my honor that the above is a true and correct report of the hours 
				of work performed, report of which was made daily at the time of arrival at the departure from office.</i></p>
		
			</div>
		
		</p>
	</body>
	
	
</html>