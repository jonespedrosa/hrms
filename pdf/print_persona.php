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
						
						 
						</b>
						 </div>
						<center><a href="../viewsched.php?current=current"><img src ="../images/logoo.png"></center></a>
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
										Cut off :  <b> <?php echo date("m-d-Y", strtotime($_GET["cutfrom"])); ?> - <?php echo date("m-d-Y", strtotime($_GET["cutto"])); ?></b> <br / >
										Position: <b><?php echo $post; ?></b> 
										
										
								 </div> 
							  
							</div>					
						</th>
				    </tr>
				
					<tr class="text-uppercase">
						<th rowspan="2"><center><b>Date</b></center></th>
						<th rowspan="2"><center><b>Schedule</b></center></th>  
							<th rowspan="2"><center><b>Break</b></center></th>   
							<th colspan="4"><center><b></b></center></th>
							<th colspan="2"><center><b>Overtime</b></center></th>
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
						<th><center><b>Time in</b></center></th>
						<th><center><b>Time Out</b></center></th>
					</tr>
				
				
				</thead>
				
				
			<tbody>
			<?php

		
				$sql1="SELECT * FROM sched_time 
				WHERE sched_time.empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND status != 'deleted' ORDER BY datefromto ASC";
						$query1=$HRconnect->query($sql1);
						while($row1=$query1->fetch_array()){
							@$status = $row1['status'];
							$datefromto = $row1['datefromto'];

							$datefrom = $row1['schedfrom'];
							$dateto = $row1['schedto'];

							$mtimein = $row1['M_timein'];
							$mtimeout = $row1['M_timeout'];
							$atimein = $row1['A_timein'];
							$atimeout = $row1['A_timeout'];
							$otimein = $row1['O_timein'];
							$otimeout = $row1['O_timeout'];
							$timein4 = $row1['timein4'];
							$timeout4 = $row1['timeout4'];

							$break = $row1['break'];
							$breaktotal = $break * 10000;




if (($datefromto == '2021-02-12' OR $datefromto == '2021-02-25')  AND $mtimein != '' AND $atimeout != '' AND ($datefrom <= $mtimein AND $dateto >= $atimeout)){
	
	@$spcregday1 = floor((strtotime($atimeout) - strtotime($datefrom))/3600) - $break;

}elseif (($datefromto == '2021-02-12' OR $datefromto == '2021-02-25') AND $mtimein != '' AND $atimeout != '' AND $datefrom >= $mtimein AND $dateto <= $atimeout){

	@$spcregday1 = floor((strtotime($dateto) - strtotime($datefrom))/3600) - $break;

}elseif (($datefromto == '2021-02-12' OR $datefromto == '2021-02-25') AND $mtimein != '' AND $atimeout != '' AND $datefrom < $mtimein AND $dateto < $atimeout){

	@$spcregday1 = floor((strtotime($dateto) - strtotime($datefrom))/3600) - $break;

}elseif (($datefromto == '2021-02-12' OR $datefromto == '2021-02-25') AND $mtimein != '' AND $atimeout != '' AND $datefrom > $mtimein AND $dateto > $atimeout){

	@$spcregday1 = floor((strtotime($atimeout) - strtotime($datefrom))/3600) - $break;

}

if (($datefromto == '2021-02-12' OR $datefromto == '2021-02-25') AND $otimein != '' AND $otimeout != '' AND (date("Y-m-d", strtotime($otimeout)) == '2021-02-12'  OR date("Y-m-d", strtotime($otimeout)) == '2021-02-25')){
	
	$spcregday2 = floor((strtotime($otimeout) - strtotime($otimein))/3600);

}elseif(($datefromto == '2021-02-12') AND $otimein != '' AND (date("Y-m-d", strtotime($otimeout)) == '2021-02-13')){
 
	$spcregday2 = floor((strtotime('2021-02-13 00:00') - strtotime($dateto))/3600);

}elseif(($datefromto == '2021-02-25') AND $otimein != '' AND (date("Y-m-d", strtotime($otimeout)) == '2021-02-26')){
 
	$spcregday2 = floor((strtotime('2021-02-26 00:00') - strtotime($dateto))/3600);
}




if (($mtimein >= $datefromto ." "."22:00" AND $mtimein <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00") AND 
	($mtimeout >= $datefromto ." "."22:00" AND $mtimeout <= date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00")
	AND $mtimeout != "No Break"){

   @$night1 = floor((strtotime($mtimeout) - strtotime($mtimein))/3600);

}elseif($mtimein > $datefromto ." "."22:00" AND $mtimeout < $datefromto ." "."22:00" AND $mtimeout != "No Break"){

 @$night1 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($mtimeout))/3600);

}elseif($mtimein < $datefromto ." "."22:00" AND $mtimeout > $datefromto ." "."22:00" AND $mtimeout != "No Break"){

 @$night1 = floor((strtotime($mtimeout) - strtotime($datefromto ." "."22:00"))/3600);

}

if (($atimein > $datefromto ." "."22:00" AND $atimeout > $datefromto ." "."22:00") AND 
	($atimein < date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00" AND $atimeout < date('Y-m-d', strtotime( $datefromto . " +1 days"))." "."06:00") 
	AND $atimein != "No Break"){

  @$night2 = floor((strtotime($atimeout) - strtotime($atimein))/3600);

}elseif($atimein > $datefromto ." "."22:00" AND $atimeout < $datefromto ." "."22:00" AND $atimein != "No Break"){

 @$night2 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($atimeout))/3600);

}elseif($atimein < $datefromto ." "."22:00" AND $atimeout > $datefromto ." "."22:00" AND $atimein != "No Break"){

 @$night2 = floor((strtotime($atimeout) - strtotime($datefromto ." "."22:00"))/3600);

}


if (($otimein > $datefromto ." "."22:00" AND $otimeout > $datefromto ." "."22:00") AND 
	($otimein < date('Y-m-d', strtotime( $datefromto . " +1 days")) ." "."06:00" AND $otimeout < date('Y-m-d', strtotime( $datefromto . " +1 days"))." "."06:00")){
  @$night3 = floor((strtotime($otimeout) - strtotime($otimein))/3600);

}elseif($otimein > $datefromto ." "."22:00" AND $otimeout < $datefromto ." "."22:00"){

 @$night3 = floor((strtotime(date('Y-m-d', strtotime($datefromto ." "."22:00"))) - strtotime($otimeout))/3600);

}elseif($otimein < $datefromto ." "."22:00" AND $otimeout > $datefromto ." "."22:00"){

 @$night3 = floor((strtotime($otimeout) - strtotime($datefromto ." "."22:00"))/3600);

} 

if (($atimein == 'No Break' AND $atimeout > $datefromto ." "."22:00")){

   @$night1 = floor((strtotime($atimeout) - strtotime($datefromto ." "."22:00"))/3600);

}




@$total1 += @$night1;
$night1 = 0;

@$total2 += @$night2;
$night2 = 0;

@$total3 += @$night3;
$night3 = 0;



			$sql8 = " SELECT ADDTIME('$mtimeout','$breaktotal') as zxc FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')
							AND M_timeout != 'null'";

						$query8=$HRconnect->query($sql8);
						$row8=$query8->fetch_array();
						$totals = $row8['zxc'];

if ($atimein > $totals)
{	

		$totalsss = strtotime($atimein) - strtotime($totals);

		@$ada+=$totalsss;

}



				 $sql9 = "SELECT * FROM vlform
                    	  WHERE vlstatus = 'approved' AND empno = $empid
                    	  AND vldatefrom = '$datefromto'";
                		  $query9=$HRconnect->query($sql9);
                 		  $row9=$query9->fetch_array();

                 $sql11 = "SELECT * FROM overunder
                    	  WHERE empno = $empid
                    	  AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
                		  $query11=$HRconnect->query($sql11);
                 		  $row11=$query11->fetch_array();



if($otimein != '' AND $otimeout != ''){

			$sql5="SELECT SUM(time_to_sec(TIMEDIFF(otto, otfrom))) as OT FROM overunder
			WHERE empno = '$empid' AND ottype = 'Overtime' AND (otdatefrom between '$cutfrom' AND '$cutto') AND otstatus = 'approved'";
			$query5=$HRconnect->query($sql5);
			$row5=$query5->fetch_array();

		}





		$sql12="SELECT SUM(time_to_sec(TIMEDIFF(schedto, A_timeout))) as timeout2 FROM sched_time
		WHERE sched_time.empno = '$empid' AND datefromto = '$datefromto' AND schedto > A_timeout ";
		$query12=$HRconnect->query($sql12);
		$row12=$query12->fetch_array();
	    $UT2 = $row12['timeout2'] /60;
	   @$totalUT2 += $UT2;


          		  

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
					<td><center></center></td>
					<td class="text-uppercase" colspan="3"><center><?php echo $row1['remarks']; ?> <?php echo @$row9['vltype']; ?> </center></td>
				</tr>
			<?php 
					}
				?>

			<?php 
				$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom']))/3600;

					if($row1['M_timein'] != ''){
				?>

				<tr>
					<td><center><?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
				<?php if(($equal == 10 AND $row1['break'] == 2) OR ($equal == 9 AND $row1['break'] == 1) OR ($equal == 8 AND $row1['break'] == 0)) { 
					?>
				<td>
				<?php
				}else{
					?>
					<td class="text-danger">
					<?php
		
				}
					?>
	

						<center><?php echo date("H:i", strtotime($row1['schedfrom'])); ?> - <?php echo date("H:i", strtotime($row1['schedto'])); ?> </center></td>
			
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
						<?php 
					

							if($row1['M_timein'] != ''){
						echo date('H:i', strtotime($row1['M_timein'])); 
							}else{
							echo "";
							}
				

						?>
							
						</center></b></td>
					<td><center>


						<?php 

							if($row1['M_timeout'] != ''){
						echo date('H:i', strtotime($row1['M_timeout'])); 
							
							}else{
								echo "";
							}
						?>

						<b></b></center></td>


			<?php 
					if($totals < $atimein){	
				?>	<td class="text-danger"><b>

			<?php 
				}else{
				?>	
					<td>

			<?php 
				}
				?>
					<center>

					  <?php 
							if($row1['A_timein'] != ''){
						echo date('H:i', strtotime($row1['A_timein'])); 
							
							}else{
								echo "";
							}

						

						?>
						</center></b></td>

			<?php 

				 $outtime = strtotime($atimeout);
				 $startime = strtotime($dateto);

				if($startime > $outtime){
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

					   <?php 

							if($row1['A_timeout'] != ''){
						echo date('H:i', strtotime($row1['A_timeout'])); 
							}else{
							echo "";
							} 

						?>

					</b></center></td>
				<td><b>
					<center>
						

						<?php 
						
						if($row1['O_timein'] != '')
						{ 

						echo date('H:i', strtotime(@$row1['O_timein'])); 

						}
						
						?>
						</center></b></td>

			
					<td><b>
			
					<center>
						
						<?php 
					
						if($row1['O_timeout'] != '')
						{ 

						echo date('H:i', strtotime(@$row1['O_timeout'])); 

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
					<td class="text-uppercase" colspan="5"><center><?php echo $row1['remarks']; ?><?php echo @$row9['vltype']; ?> 
					<?php 
					 if(@$row11['ottype'] == 'Undertime' AND @$row11['otstatus'] == 'approved'){
					echo @$row11['othours'] ." UT"; 
				}?></center></td>
				</tr>
			<?php
		}
		}
		?>

			 </tbody>
			 
				<tr class="table-secondary text-uppercase"> 									
					<td><center># of Hours</center></td>
					<td><center>OT (Hours)</center></td>
					<td><center>W.Off</center></td>
					<td><center>Holiday</center></td>					
					<td><center>R.Hol</center></td>
					<td><center>S.Hol</center></td>
					<td colspan="2"><center>N.Diff</center></td>
					<td colspan="2"><center>Leave</center></td>
					<td><center>Late</center></td>
					<td><center>UT</center></td>
									
                </tr>
				
<?php 

			
			


			$sql3="SELECT SUM(time_to_sec(TIMEDIFF(schedto,schedfrom))) as first FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND A_timeout != '' AND M_timein != '' AND
							A_timeout >= schedto";
						$query3=$HRconnect->query($sql3);
						$row3=$query3->fetch_array();

			$sql33="SELECT SUM(time_to_sec(TIMEDIFF(A_timeout,schedfrom))) as second FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND A_timeout != '' AND M_timein != '' AND
							A_timeout < schedto";
						$query33=$HRconnect->query($sql33);
						$row33=$query33->fetch_array();

				$sqlbreak="SELECT SUM(break) as break FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND break > 0";
						$querybreak=$HRconnect->query($sqlbreak);
						$rowbreak=$querybreak->fetch_array();


			$sql4="SELECT SUM(time_to_sec(TIMEDIFF(M_timein,schedfrom))) as timein FROM sched_time
							WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')  AND schedfrom < M_timein AND 
							(m_in_status = 'Approved' OR min_empno != '')
							";
						$query4=$HRconnect->query($sql4);
						$row4=$query4->fetch_array();
 		
		


				$sql10="SELECT COUNT(*) as vl FROM vlform
							INNER JOIN sched_time ON vlform.empno = sched_time.empno
							WHERE vlform.empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND datefromto = vldatefrom AND vlstatus = 'approved'";
						$query10=$HRconnect->query($sql10);
						$row10=$query10->fetch_array();

						?>

				<tr>
					<td><center><b><?php echo round((($row3['first']/3600)+($row33['second']/3600)) - $rowbreak['break'] , 2,); ?></b></center></td>
					<td><center><b><?php echo floor(@$row5['OT'] /3600); ?></b></center></td>
					<td><center><b><?php echo $row['workoff']; ?></b></center></td>
					<td><center><b>0</b></center></td>
					<td><center><b><?php echo $row['regholiday']; ?></b></center></td>
					<td><center><b><?php echo @$spcregday1 + @$spcregday2; ?></b></center></td>
					<td colspan="2"><center><b><?php echo $total1 + $total2 + $total3 ; ?></b></center></td>
					<td colspan="2"><center><b><?php echo $row10['vl']; ?></b></center></td>
					<td><center><b><?php echo $row4['timein'] /60 + @$ada / 60; ?></b></center></td>
					<td><center><b><?php echo  @$totalUT + @$totalUT2; ?></b></center></td>
				</tr>
				
				<tr>
					<td colspan="13" ><b class="invisible">.</b></td>
				</tr>
			
	
			
				
		  </table>
		  
			<p class="text-muted"><i>I CERTIFY on my honor that the above is a true and correct report of the hours 
			of work performed, report of which was made daily at the time of arrival at the departure from office.</i></p>
	
		</div>
		
			
			
		

	
</p>
</body>
	
	
</html>