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
										Cut off :  <b> <?php echo date("m-d-Y", strtotime($_GET["cutfrom"])); ?> - <?php echo date("m-d-Y", strtotime($_GET["cutto"])); ?></b> <br / >
										Position: <b><?php echo $post; ?></b> 
										
										
								 </div> 
							  
							</div>					
						</th>
				    </tr>
				
						<tr class="text-uppercase">
						<th rowspan="2" colspan="2"><center><b>Date</b></center></th>
						<th rowspan="2" colspan="2"><center><b>Schedule</b></center></th>  
							<th rowspan="2"><center><b>Break</b></center></th>   
							<th colspan="4"><center><b></b></center></th>
							<th rowspan="2"><center><b>OT Hours</b></center></th>
							<th rowspan="2"><center><b>Broken <br>Sched OT</b></center></th>
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

							$brokentime_in = $row1['timein4'];
							$brokentime_out = $row1['timeout4'];		
															
							$break = $row1['break'];
							$breaktotal = $break * 10000;


				$equal = (strtotime($row1['schedto']) - strtotime($row1['schedfrom']))/3600;

				 if(($equal == 19 AND $row1['break'] == 10) OR ($equal == 17 AND $row1['break'] == 9) OR ($equal == 16 AND $row1['break'] == 8) OR ($equal == 15 AND $row1['break'] == 7) OR ($equal == 14 AND $row1['break'] == 6) OR ($equal == 13 AND $row1['break'] == 5) OR ($equal == 12 AND $row1['break'] == 4) OR ($equal == 11 AND $row1['break'] == 3) OR ($equal == 10 AND $row1['break'] == 2) OR ($equal == 9 AND $row1['break'] == 1) OR ($equal == 8 AND $row1['break'] == 0)) { 
			
		
				}else{

					if($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != ''){
					@$minus += 1;
					@$statusall += 1;
					}

				}

				@$othours = '';
						

						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){

							$sql11 = "SELECT * FROM overunder
									WHERE empno = $empid AND ottype in ('','0')	AND otdatefrom = '$datefromto' AND otstatus != 'canceled' ";
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

						//NIGHT DIFF CALCULATION
						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
							
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

							if($mtimeout === "No Break" && $break == 0){
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

							@$regndtotal -= ($cus_ndbreak);							
	
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

						}
						//END OF NIGHT DIFF CALCULATION

						if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){

									$sql8 = " SELECT ADDTIME('$mtimeout','$breaktotal') as zxc FROM sched_time
													WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto')
													AND M_timeout != 'null'";
						
												$query8=$HRconnect->query($sql8);
												$row8=$query8->fetch_array();
												$totals = $row8['zxc'];

						if ($atimein > $totals)
						{	

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

                 		  @$vldate = $row9['vldatefrom']; 

				 $sql15 = "SELECT * FROM obp
                    	  WHERE empno = $empid AND datefromto = '$datefromto'";
                		  $query15=$HRconnect->query($sql15);
                 		  $row15=$query15->fetch_array(); 

         //CHECK THE STATUS OF THE FILED DTR CONCERNS
         $sqlDTR = "SELECT * FROM dtr_concerns
                    	  WHERE empno = $empid AND status = 'Pending' AND ConcernDate = '$datefromto'";
                		  $queryDTR=$HRconnect->query($sqlDTR);
                 		  $rowDTR=$queryDTR->fetch_array(); 

         $sqlDTR2 = "SELECT * FROM dtr_concerns
                    	  WHERE empno = $empid AND status = 'Approved' AND ConcernDate = '$datefromto'";
                		  $queryDTR2=$HRconnect->query($sqlDTR2);
                 		  $rowDTR2=$queryDTR2->fetch_array(); 

					

				
				//HOLIDAY LEGAL AND SPECIAL	

          //NEW CODE FOR LEGAL HOLIDAYS
          $sqllegalhol = "SELECT * FROM holiday WHERE type = '0' AND holiday_day = '$datefromto' ";     
          $querylegalhol=$HRconnect->query($sqllegalhol);
          while($rowlegalhol=$querylegalhol->fetch_array()){  

          $legalhday = $rowlegalhol['holiday_day'];		
          $hdayprevdate1 = $rowlegalhol['prior1'];
          $hdayprevdate2 = $rowlegalhol['prior2'];	
          $hdayprevdate3 = $rowlegalhol['prior3'];

          	//LEGAL HOLIDAY NIGHT DIFF
							$lh_ndstarta = strtotime($datefromto. " "."00:00");
							$lh_ndenda = strtotime($datefromto." "."06:00");
							$lh_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$lh_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$lh_cmtimein = strtotime($mtimein) < strtotime($datefrom) ? strtotime($datefrom) : strtotime($mtimein); //base time in AM
							$lh_cmtimeout = !($mtimeout === "No Break") ? strtotime($mtimeout) : $mtimeout;
							$lh_catimein = $atimein;
							if(!($mtimeout === "No Break")){
								$lh_catimein = (strtotime($atimein)-$lh_cmtimeout)<=3600 ? ($lh_cmtimeout + 3600):strtotime($atimein);
							}
							$lh_catimeout= strtotime($atimeout) > strtotime($dateto) ? strtotime($dateto) : strtotime($atimeout);//base time out PM
							$lh_ndbreak = 0;
							$lh_ndoverbreak = 0;

							if($mtimeout === "No Break" && $break == 0){
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

								@$lhndtotal -= ($lh_ndbreak);
	
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

          	//LEGAL HOLIDAY OT COMPUTATION
        			$legal_hot="SELECT SUM(othours) as LHOT FROM overunder WHERE empno = '$empid' AND ottype in ('','0') AND otstatus = 'approved' AND otdatefrom = '$legalhday'";
							$query_HOT=$HRconnect->query($legal_hot);
							$row_HOT=$query_HOT->fetch_array();
							@$totalssalhot += @$row_HOT['LHOT'];

							$cus_approvedlhot = $row_HOT['LHOT'];
							$cus_lht1 =strtotime($dateto) + ($cus_approvedlhot * 3600);
							$cus_lht2 =strtotime($datefromto ." "."22:00");
							$cus_lht3 =$cus_lht2+(8*3600);
							$cus_lhregot = $cus_approvedlhot;

							if(($cus_lht1 > $cus_lht2) AND $cus_approvedlhot > 0 ){
								$cus_lhndot=0;
								if(strtotime($dateto)<$cus_lht2){
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
							$sqllhBOT="SELECT SUM(othours) as LHBOT FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$legalhday' ";
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

					//CONDITION TO CHECK THE LEGAL HOLIDAYS	HOURS	
          if($datefromto == $legalhday && $mtimein != '' && $atimeout != ''){
        			$legal_holiday += 8;

        	}

          	if($datefromto == $legalhday && $mtimein == '' && $atimeout == '') {
          			
          			//query prior dates in the sched time
          			$sqldate2 ="SELECT * FROM sched_time WHERE sched_time.empno = '$empid' AND (datefromto between '$hdayprevdate3' AND '$hdayprevdate1') AND status != 'deleted' ORDER BY datefromto ASC";
								$querydate2=$HRconnect->query($sqldate2);

								while($rowdate2=$querydate2->fetch_array()){
									$datefromto2 = $rowdate2['datefromto'];
									$mtimein2 = $rowdate2['M_timein'];
									$atimeout2 = $rowdate2['A_timeout'];

									if($mtimein2 != '' && $atimeout2 != '') {
          					$legal_holiday += 8;	
          					break;  

        					}else{
        						//check if there are approved leaves that falls under legal holiday
        						$sql_holleave ="SELECT * FROM vlform WHERE empno = '$empid' AND vlstatus = 'approved' AND vldatefrom = '$datefromto2' ORDER BY vldatefrom ASC";
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
          
          if($datefromto == $specialhday && $mtimein != '' && $atimeout != ''){

          //SPECIAL HOLIDAY NIGHT DIFF	
          	$sh_ndstarta = strtotime($datefromto. " "."00:00");
							$sh_ndenda = strtotime($datefromto." "."06:00");
							$sh_ndstartb = strtotime($datefromto ." "."22:00"); //10:00 PM CURRENT DAY
							$sh_ndendb = strtotime($datefromto . " ". "22:00") +(8*3600); //6:00 AM Next day
							$sh_cmtimein = strtotime($mtimein) < strtotime($datefrom) ? strtotime($datefrom) : strtotime($mtimein); //base time in AM
							$sh_cmtimeout = !($mtimeout === "No Break") ? strtotime($mtimeout) : $mtimeout;
							$sh_catimein = $atimein;

							if(!($mtimeout === "No Break")){
								$sh_catimein = (strtotime($atimein)-$sh_cmtimeout)<=3600 ? ($sh_cmtimeout + 3600):strtotime($atimein);
							}
							$sh_catimeout= strtotime($atimeout) > strtotime($dateto) ? strtotime($dateto) : strtotime($atimeout);//base time out PM
							$sh_ndbreak = 0;
							$sh_ndoverbreak = 0;

							if($mtimeout === "No Break" && $break == 0){
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

							@$shndtotal -= ($sh_ndbreak);
	
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
        		$special_hot="SELECT SUM(othours) as SHOT FROM overunder WHERE empno = '$empid' AND ottype in ('','0') AND otstatus = 'approved' AND otdatefrom = '$specialhday'";
							$query_SHOT=$HRconnect->query($special_hot);
							$row_SHOT=$query_SHOT->fetch_array();
							@$totalssashot += @$row_SHOT['SHOT'];
		
							$cus_approvedshot = $row_SHOT['SHOT'];
							$cus_sht1 =strtotime($dateto) + ($cus_approvedshot * 3600);
							$cus_sht2 =strtotime($datefromto ." "."22:00");
							$cus_sht3 =$cus_sht2+(8*3600);
							$cus_shregot = $cus_approvedshot;

							if(($cus_sht1 > $cus_sht2) AND $cus_approvedshot > 0 ){
								$cus_shndot=0;
								if(strtotime($dateto)<$cus_sht2){
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
							$sqlshBOT="SELECT SUM(othours) as SHBOT FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$specialhday' ";
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
          if($datefromto == $specialhday && $mtimein != '' && $atimeout != ''){
          	$sh_in = strtotime($mtimein);
          	$sh_out = strtotime($atimeout);

          	$spechol1 = (($sh_out - $sh_in)/3600);
          	$totalspechol = $spechol1 - $break;

          		if($totalspechol > 8){
        				$special_holiday += 8;
        			}else{
        				$special_holiday += $totalspechol;
        			}
        	
        	}

        	/*if($datefromto == $specialhday && $mtimein == '' && $atimeout == '') {

        		//check if there are approved leaves that falls under legal holiday
   					$sql_holleave2 ="SELECT * FROM vlform WHERE empno = '$empid' AND vlstatus = 'approved' AND vldatefrom = '$datefromto' ";
						$query_holleave2=$HRconnect->query($sql_holleave2);
											
						if(mysqli_num_rows($query_holleave2) > 0){
        			$special_holiday += 8;
							//break; 
						}

 		     	}*/


 	if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){

			$sql5="SELECT SUM(othours) as OT FROM overunder WHERE empno = '$empid'  AND ottype in ('','0') AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
							$query5=$HRconnect->query($sql5);
							$row5=$query5->fetch_array();
							@$totalssa += @$row5['OT'];

			//INSERT NORMAL OT CODE HERE
							$cus_approvedot = $row5['OT'];
							$cus_t1 =strtotime($dateto) + ($cus_approvedot * 3600);
							$cus_t2 =strtotime($datefromto ." "."22:00");
							$cus_t3 =$cus_t2+(8*3600);
							$cus_regot = $cus_approvedot;

							if(($cus_t1 > $cus_t2) AND $cus_approvedot > 0 ){
								$cus_ndot=0;
								if(strtotime($dateto)<$cus_t2){
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
		$sqlBOT="SELECT SUM(othours) as BOT FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otdatefrom = '$datefromto' AND otstatus = 'approved'";
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
					<td colspan="2"><center><?php echo  date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
					<td colspan="2"><center></center></td>	
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
					<td><center></center></td>
			
					<td class="text-uppercase" colspan="3">
						<center>
							<!-- DISPLAY REMARKS OF LEAVE-->
							<?php echo $row1['remarks']; ?> <?php echo @$row9['vltype']; ?>
							
							<!-- DISPLAY REMARKS OF DTR CONCERNS-->
							<?php if(@$rowDTR['status'] == 'Pending'){
								echo " <i class='text-danger'>".@$rowDTR['status']. " DTR CONCERN " ."</i>";
							}elseif (@$rowDTR['status'] == 'Approved') {
								echo "<i class='text-success'>".@$rowDTR['status']. " DTR CONCERN " ."</i>";
							} 
							?>

							<!-- DISPLAY REMARKS OF OBP-->
							<?php if(@$row15['status'] == 'Pending' OR @$row15['status'] == 'Pending2'){ 
								echo "<i class='text-danger'>"."OBP " .@$row15['status']. "</i>"; 
							}elseif(@$row15['status'] == 'Approved'){ 
								echo "<i class='text-success'>"."OBP " .@$row15['status']. "</i>"; } ?> 
						</center>
					</td>
				</tr>
			<?php 
					}
				?>

				<?php 


					if($row1['M_timein'] != ''){
				?>

				<tr>
					<td colspan="2"><center><?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
				<?php if(($equal == 19 AND $row1['break'] == 10) OR ($equal == 17 AND $row1['break'] == 9) OR ($equal == 16 AND $row1['break'] == 8) OR ($equal == 15 AND $row1['break'] == 7) OR ($equal == 14 AND $row1['break'] == 6) OR ($equal == 13 AND $row1['break'] == 5) OR ($equal == 12 AND $row1['break'] == 4) OR ($equal == 11 AND $row1['break'] == 3) OR ($equal == 10 AND $row1['break'] == 2) OR ($equal == 9 AND $row1['break'] == 1) OR ($equal == 8 AND $row1['break'] == 0)) { 
					?>
				<td colspan="2">
				<?php
				}else{
					if(($mtimein != '' AND $mtimeout != '' AND $atimein != '' AND $atimeout != '') AND @$statusall == 0){
					}
					?>

					<td class="text-danger" colspan="2">
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

						<td style="color: #1e81b0;"><center><b>
						<?php 
							//if(@$_GET['backtrack'] != 'backtrack'){
						?>
						<?php

						// GEN MEET OT HOURS
						$brknschedOT = "SELECT * FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$datefromto' ";
						$queryOT2=$HRconnect->query($brknschedOT);
						$rowOT2=$queryOT2->fetch_array();

						$brknschedOTSUM = "SELECT SUM(othours) as BOT FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otstatus = 'approved' AND otdatefrom = '$datefromto' ";
						$queryBOT=$HRconnect->query($brknschedOTSUM);
						$rowBOT=$queryBOT->fetch_array();
						$totalBOT = $rowBOT['BOT'];

						$brknschedOTP = "SELECT * FROM overunder WHERE empno = '$empid' AND ottype in ('1','2') AND otstatus in ('pending','pending2') AND otdatefrom = '$datefromto' ";
						$queryOTP=$HRconnect->query($brknschedOTP);
						$rowOTP=$queryOTP->fetch_array();
							
							if($queryOTP->num_rows >= 1){
								echo "Pending";	
							}else if($queryOT2->num_rows == 0){
								echo " ";									
							}else if($rowOT2['othours'] == 0){
								echo " "; 							
							}else{
								echo $rowOT2['othours'];
							}


						?>
						<?php
						//}else{
						?>
						<?php
						//for backtrack Timesheet
						// GEN MEET OT HOURS
						//$brknschedOT = "SELECT * FROM dtr_concerns WHERE empno = '$empid' AND concern = 'File Broken Sched OT' AND status = 'Approved' AND ConcernDate = '$datefromto' ";
						//$queryOT2=$HRconnect->query($brknschedOT);
						//$rowOT2=$queryOT2->fetch_array();

							//if($queryOT2->num_rows == 0){
								//echo " ";									
							//}else if($rowOT2['othours'] == 0){
								//echo " "; 							
							//}else{
								//echo $rowOT2['othours'];
							//}
						?>
						<?php
						
						//}
						
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
					<td class="text-uppercase" colspan="3">
						<center>
							<!-- DISPLAY REMARKS OF LEAVE-->
							<?php echo $row1['remarks']; ?><?php echo @$row9['vltype']; ?>

							<!-- DISPLAY REMARKS OF DTR CONCERN-->
							<?php if($queryDTR->num_rows >= 1){
								echo " <i class='text-danger'>".@$rowDTR['status']. " DTR CONCERN " ."</i>";
							}elseif($queryDTR2->num_rows >= 1) {
								echo "<i class='text-success'>".@$rowDTR2['status']. " DTR CONCERN " ."</i>";
							} 
							?>							 
							<!-- DISPLAY REMARKS OF OBP-->
							<?php if(@$row15['status'] == 'Pending' OR @$row15['status'] == 'Pending2'){ 
								echo "<i class='text-danger'>"."OBP " .@$row15['status']. "</i>"; 
							}elseif(@$row15['status'] == 'Approved'){ 
								echo "<i class='text-success'>"."OBP " .@$row15['status']. "</i>"; } ?> 
					</center></td>
				</tr>
			<?php
		}

		@$statusall = 0;

		}
		
		?>

			 </tbody>
			 
				<tr class="table-secondary text-uppercase"> 									
					<td colspan="4"><center><b>ATTENDANCE</b></center></td>
					<td colspan="3"><center><b>ORDINARY DAY</b></center></td>
					<td colspan="4"><center><b>SPECIAL HOLIDAY</b></center></td>
					<td colspan="4"><center><b>LEGAL HOLIDAY</b></center></td>	
					<td rowspan = "2"><center><b>WORKING <br> OFF</b></center></td>						
        </tr>
        <tr class="table-secondary text-uppercase"> 									
					<td><center>WORKDAYS</center></td>
					<td><center>LATE</center></td>
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
					<td><center>ND.OT </center></td>			
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
				<?php
						@$allholiday_OT = $lhregularothours + $shregularothours + $regularbrokenSHOT + $regularbrokenLHOT;
						@$allholidaynd_OT = $totallhndothours + $totalshndothours + $totalbrokenSHNDOT + $totalbrokenLHNDOT;
						@$allregular_OT = $regularothours + $regularbrokenOT - $allholiday_OT; 
						@$allnd_OT = $totalndothours + $totalbrokenNDOT - $allholidaynd_OT;
						@$allholidayND = $shndtotal + $lhndtotal;
					?>
					<td><center><b><?php echo $row3['COUNT(*)'] - @$minus; ?></b></center></td>
					<td><center><b><?php echo $latetotal + @$ada / 60; ?></b></center></td>
					<td><center><b><?php echo  @$totalUT2 - @$grandgen; ?></b></center></td>
					<td><center><b><?php echo $row10['vl']; ?></b></center></td>
					<!--ORDINARY NIGHT DIFF-->
					<td><center><b><?php if(@$regndtotal - $allholidayND != ''){
																echo round($regndtotal - $allholidayND); 
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
					<td><center><b><?php echo $row['workoff']; ?></b></center></td>
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
					<td><center><b>0</b></center></td>
					<td><center><b>0</b></center></td>
					<td><center><b>0</b></center></td>
					<td><center><b>0</b></center></td>
					<td><center><b>0</b></center></td>
				</tr>

			<?php }else{ ?>
				<tr>
					<!--ATTENDANCE-->		
					<td><center><b><?php echo $rowo['dayswork']; ?></b></center></td>
					<td><center><b><?php echo $rowo['lateover']; ?></b></center></td>
					<td><center><b><?php echo $rowo['undertime']; ?></b></center></td>
					<td><center><b><?php echo $rowo['vleave']; ?></b></center></td>
					<!--ORDINARY DAY-->
					<td><center><b><?php echo $rowo['nightdiff']; ?></b></center></td>		
					<td><center><b><?php echo $rowo['regularot']; ?></b></center></td>
					<td><center><b><?php echo $rowo['nightdiffot']; ?></b></center></td>
					<!--SPECIAL HOLIDAY-->
					<td><center><b><?php echo $rowo['specialday']; ?></b></center></td>
					<td><center><b><?php echo $rowo['specialdaynd']; ?></b></center></td>
					<td><center><b><?php echo $rowo['specialdayot']; ?></b></center></td>
					<td><center><b><?php echo $rowo['specialdayndot']; ?></b></center></td>
					<!--LEGAL HOLIDAY-->
					<td><center><b><?php echo $rowo['legalday']; ?></b></center></td>
					<td><center><b><?php echo $rowo['legaldaynd']; ?></b></center></td>
					<td><center><b><?php echo $rowo['legaldayot']; ?></b></center></td>
					<td><center><b><?php echo $rowo['legaldayndot']; ?></b></center></td>
					<!--WORKING OFF-->
					<td><center><b><?php echo $rowo['workdayoiff']; ?></b></center></td>
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