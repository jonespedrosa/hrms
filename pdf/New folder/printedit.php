<?php 
 $connect = mysqli_connect("localhost", "root", "", "db");
 $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

 session_start();



$userid = $_SESSION['useridd'];

$id = $_GET['id'];

$sql4 = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query4=$HRconnect->query($sql4);
$row4=$query4->fetch_array();


$mothercafe = $row4['mothercafe'];



$user = $row4['name'];
$userlevel = $row4['userlevel'];



if(isset($_GET['SubmitButton'])){


    foreach ($_GET["id"] as $row => $value){

if($userlevel == 'master' OR $userid == 4){

$datefromto = mysqli_real_escape_string($HRconnect, $_GET['datefromto'][$row]);
$fromtime1 = mysqli_real_escape_string($HRconnect, $_GET['fromtime'][$row]);
$totime1 = mysqli_real_escape_string($HRconnect, $_GET['totime'][$row]);
$break = mysqli_real_escape_string($HRconnect, $_GET['break'][$row]);
$remarks = mysqli_real_escape_string($HRconnect, $_GET['remarks'][$row]);
$idd = mysqli_real_escape_string($HRconnect, $_GET['idd'][$row]);
$timein1 = mysqli_real_escape_string($HRconnect, $_GET['timein1'][$row]);
$timein2 = mysqli_real_escape_string($HRconnect, $_GET['timein2'][$row]);
$timein3 = mysqli_real_escape_string($HRconnect, $_GET['timein3'][$row]);
$timein4 = mysqli_real_escape_string($HRconnect, $_GET['timein4'][$row]);
$timein5 = mysqli_real_escape_string($HRconnect, $_GET['timein5'][$row]);
$timein6 = mysqli_real_escape_string($HRconnect, $_GET['timein6'][$row]);
$othours = mysqli_real_escape_string($HRconnect, $_GET['othours'][$row]);
$timeaa = mysqli_real_escape_string($HRconnect, $_GET['timeaa'][$row]);
$timebb = mysqli_real_escape_string($HRconnect, $_GET['timebb'][$row]);
$timecc = mysqli_real_escape_string($HRconnect, $_GET['timecc'][$row]);
$timedd = mysqli_real_escape_string($HRconnect, $_GET['timedd'][$row]);
$timeee = mysqli_real_escape_string($HRconnect, $_GET['timeee'][$row]);
$timeff = mysqli_real_escape_string($HRconnect, $_GET['timeff'][$row]);
 $otid = mysqli_real_escape_string($HRconnect, $_GET['otid'][$row]);

if($totime1 >= '00:00' AND $totime1 <= '07:00'){

       $out = date('Y-m-d', strtotime($datefromto . ' +1 day'));
       $outtime = $out ." ". $totime1;

    }else{

        $out = $datefromto;
        $outtime = $out ." ". $totime1;

    }

    $intime = date('Y-m-d', strtotime($datefromto)) ." ". $fromtime1;


if($timein1 == ''){
$timein11 = "";
}else{
$timein11 = $datefromto. " " . $timein1;	
}

if($timein2 == ''){
$timein22 = "";

}elseif($timein2 == 'No Break'){

$timein22 = "No Break";

}else{
$timein22 = $datefromto. " " . $timein2;	
}

if($timein3 == ''){
$timein33 = "";

}elseif($timein3 == 'No Break'){

$timein33 = "No Break";

}else{
$timein33 = $datefromto. " " . $timein3;	
}

if($timein4 == ''){
$timein44 = "";
}else{

	if($timein4 >= '00:00' AND $timein4 <= '07:00'){

       $totaldate = date('Y-m-d', strtotime($datefromto . ' +1 day'));
   }else{

   	$totaldate = $datefromto;
   }

$timein44 = $totaldate. " " . $timein4;	


}

if($timein5 == ''){
$timein55 = "";
}else{

	if($timein5 >= '00:00' AND $timein5 <= '07:00'){

       $totaldate1 = date('Y-m-d', strtotime($datefromto . ' +1 day'));
   }else{

   	$totaldate1 = $datefromto;
   }



$timein55 = $totaldate1. " " . $timein5;

}

if($timein6 == ''){
$timein66 = "";
}else{


	if($timein6 >= '00:00' AND $timein6 <= '07:00'){

       $totaldate2 = date('Y-m-d', strtotime($datefromto . ' +1 day'));
   }else{

   	$totaldate2 = $datefromto;
   }


$timein66 = $totaldate2. " " . $timein6;	

}


$sqlupdate = " UPDATE sched_time 
      SET schedfrom  = '$intime',
        schedto  = '$outtime',
        break  = '$break',
        M_timein  = '$timein11',
        m_in_status  = 'Approved',
        M_timeout  = '$timein22',
        m_o_status  = 'Approved',
        A_timein  = '$timein33',
        a_in_status  = 'Approved',
        A_timeout  = '$timein44',
        a_o_status  = 'Approved',
        O_timein  = '$timein55',
        o_in_status  = 'Approved',
        O_timeout  = '$timein66',
        o_o_status  = 'Approved',
        timein  = '$timeaa',
        breakout  = '$timebb',
        breakin  = '$timecc',
        timeout  = '$timedd',
        overin  = '$timeee',
        overout  = '$timeff',
      remarks  = '$remarks'  
      WHERE id = '$value'";

$sqlupdate1 = " UPDATE overunder 
      SET othours  = '$othours'
        WHERE ovid = '$otid'";


    $HRconnect->query($sqlupdate);
     $HRconnect->query($sqlupdate1);



}

if(($userlevel == 'ac' OR $userlevel == 'mod' OR $userlevel == 'admin') AND $userid != 4){

$datefromto = mysqli_real_escape_string($HRconnect, $_GET['datefromto'][$row]);
@$timefrom1 = mysqli_real_escape_string($HRconnect, $_GET['timefrom1'][$row]);
@$timefrom2 = mysqli_real_escape_string($HRconnect, $_GET['timefrom2'][$row]);

@$timeto1 = mysqli_real_escape_string($HRconnect, $_GET['timeto1'][$row]);
@$timeto2 = mysqli_real_escape_string($HRconnect, $_GET['timeto2'][$row]);

@$fromtime = $timefrom1 .":". $timefrom2;
@$totime = $timeto1 .":". $timeto2;


$break = mysqli_real_escape_string($HRconnect, $_GET['break'][$row]);
$remarks = mysqli_real_escape_string($HRconnect, $_GET['remarks'][$row]);
$idd = mysqli_real_escape_string($HRconnect, $_GET['idd'][$row]);
$othours = mysqli_real_escape_string($HRconnect, $_GET['othours'][$row]);
$otid = mysqli_real_escape_string($HRconnect, $_GET['otid'][$row]);

if($totime >= '00:00' AND $totime <= '07:00'){

       $out = date('Y-m-d', strtotime($datefromto . ' +1 day'));
       $outtime = $out ." ". $totime;

    }else{

        $out = $datefromto;
        $outtime = $out ." ". $totime;

    }

    $intime = date('Y-m-d', strtotime($datefromto)) ." ". $fromtime;

$sqlupdate = " UPDATE sched_time 
      SET schedfrom  = '$intime',
        schedto  = '$outtime',
        break  = '$break',
      remarks  = '$remarks'  
      WHERE id = '$value'";


    $HRconnect->query($sqlupdate);


$sqlupdate1 = " UPDATE overunder 
      SET othours  = '$othours'
        WHERE ovid = '$otid'";
     $HRconnect->query($sqlupdate1);

}
}

$empno = mysqli_real_escape_string($HRconnect, $_GET['emp']);

@$sqll = "SELECT * FROM user_info 
    where empno = '$empno'";
@$queryy=$HRconnect->query($sqll);  
@$roww=$queryy->fetch_array();
 $name = $roww['name'];

$date_time = date("Y-m-d H:i");
$empno = $_SESSION['empno'];
$inserted = "Successfully Saved";
$action = $name ." - Edit Schedule";

$sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
         VALUES('$empno', '$action', '$inserted','$date_time')";
$HRconnect->query($sql2);


header("location:../pdf/printedit.php?id=$idd");
}



$sql7="SELECT * FROM sched_info WHERE id = '$id'";
$query7=$HRconnect->query($sql7);
$row7=$query7->fetch_array();

$empid = $row7["empno"];
$cutfrom = $row7["datefrom"];
$cutto = $row7["dateto"];
$schedfrom = $row7["schedfrom"];
$schedto = $row7["schedto"];


 ?>
<!DOCTYPE html>
<html lang="en">
<head>


	<title>Mary Grace Foods Inc.</title>
	<link rel="icon" href="../images/logoo.png">
	
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<script type="text/javascript">
		$('#time').timepicker({
        timeFormat: 'H:i',
        'scrollDefaultNow'      : 'true',
        'closeOnWindowScroll'   : 'true',
        'showDuration'          : false,
        'ignoreReadonly'        : true,

})
	</script>

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
  
	input[type=number] {
	width: 50%;
	}

</style>


</head>
       

<body>

<p style="page-break-before: always">

		<div class="col-12">
		<form method="GET">
			 <table class="myTable">

				 <thead >
					
					<?php 

			
			
						$sql="SELECT * FROM sched_info 
							WHERE id = '$id'
							";
						$query=$HRconnect->query($sql);
						$row=$query->fetch_array();


						$sql1="SELECT * FROM user 
							WHERE userid = '$userid'
							";
						$query1=$connect->query($sql1);
						$row1=$query1->fetch_array();
						$name = $row1['username'];


						$sql2="SELECT * FROM user_info 
							WHERE empno = '$empid'
							";
						$query2=$HRconnect->query($sql2);
						$row2=$query2->fetch_array();
						$name1 = $row2['name'];
						$post = $row2['position'];
										

					?>
										
					<tr>
						<th colspan="100%" class="text-muted text-uppercase">
						Employee # : <b class="text-danger"><?php echo $empid; ?></b>
					
					
						<div class="row">
						<div class="col-5 text-uppercase">
						
						 
						</b>
						 </div>
						<center><a href="../viewsched.php?current=current"><img src ="../images/logoo.png" width="90" height="90"></a></center>
						</div>

							
				<input type="text" name="emp" hidden value="<?php echo $empid; ?>">
						
							<div class="row">
								
							
								 <div class="col-5">
									<p class="text-uppercase">
									
										Branch: <b><?php echo $name; ?></b> <br/>
										Name: <b><?php echo $name1; ?></b>
										
										 
									</p>
								 </div>
								 
								<div class="col-3">
								</div>

								 <div class="col-4">
									<p class="text-uppercase"> 	
										Cut off :  <b> <?php echo date("m-d-Y", strtotime($cutfrom)); ?> - <?php echo date("m-d-Y", strtotime($cutto)); ?></b> <br / >
										Position: <b><?php echo $post; ?></b> 
										
										
								 </div> 
							  
							</div>					
						</th>
				    </tr>
				
					<tr class="text-uppercase">
						<th rowspan="2" colspan="2"><center><b>Cut-off Date</b></center></th>
						<th rowspan="2" width = "23%"><center><b>Schedule</b></center></th>  
						<th rowspan="2" width = "10%"><center><b>Break</b></center></th>   
						<th colspan="4"><center><b></b></center></th>
						<th colspan="2"><center><b>Overtime</b></center></th>
						<th rowspan="2"><center><b>UT/OT</b></center></th>
						<th rowspan="2" colspan="2"><center><b>Remarks</b></center></th>
						<th rowspan="2"><center><b>Action</b></center></th>
								
					</tr>
					
					<tr class="text-uppercase">											
						<th><center><b>Time in</b></center></th>
						<th><center><b>Break Out</b></center></th>
						<th><center><b>Break In</b></center></th>
						<th><center><b>Time Out</b></center></th>	
						<th><center><b>Time in</b></center></th>
						<th><center><b>Time Out</b></center></th>
					</tr>
			
				</thead>
				
				
			<tbody>
			<?php
		
				$sql1="SELECT * FROM sched_time 
				WHERE empno = '$empid' AND (datefromto between '$cutfrom' AND '$cutto') AND status != 'deleted' ORDER BY datefromto ASC ";
						$query1=$HRconnect->query($sql1);
						while($row1=$query1->fetch_array()){

							$status = $row1['status'];
							$datefrom = $row1['schedfrom'];
							$datefromto = $row1['datefromto'];
							$dateto = $row1['schedto'];

							@$mtimein = $row1['M_timein'];
							@$m_in_status = $row1['m_in_status'];
							@$min_empno = $row1['min_empno'];

							@$mtimeout = $row1['M_timeout'];
							@$m_o_status = $row1['m_o_status'];
							@$mo_empno = $row1['mo_empno'];

							@$atimein = $row1['A_timein'];
							@$a_in_status = $row1['a_in_status'];
							@$ain_empno = $row1['ain_empno'];


							@$atimeout = $row1['A_timeout'];
							@$a_o_status = $row1['a_o_status'];
							@$ao_empno = $row1['ao_empno'];

							@$otimein = $row1['O_timein'];
							@$o_in_status = $row1['o_in_status'];
							@$oin_empno = $row1['oin_empno'];

							@$otimeout = $row1['O_timeout'];
							@$o_o_status = $row1['o_o_status'];
							@$oo_empno = $row1['oo_empno'];

							@$break = $row1['break'];
							@$breaktotal = $break * 10000;

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

					$sql11 = "SELECT * FROM overunder
                    	  WHERE otstatus = 'approved' AND empno = $empid
                    	  AND otdatefrom = '$datefromto'";
                		  $query11=$HRconnect->query($sql11);
                 		  $row11=$query11->fetch_array();

                 	     $datecutoff = date("Y-m-d");
                 	  //
										
			?>

			<?php 
			// OR ($cutto < $datecutoff AND $userid !=  4 AND $userlevel != 'master')
		
		if(($status == 'approved' AND $userlevel != 'master' AND $userlevel != 'mod' AND $userlevel != 'ac'AND $userlevel != 'admin' AND $mothercafe != 109 ) ){

						?>
				
				<tr>
					<td colspan="2"><center><?php echo  date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
					<td><center><?php echo date("H:i", strtotime($datefrom)); ?> - <?php echo date("H:i", strtotime($dateto)); ?></center></td>
					<td><center><?php echo $break; ?></center></td>
					<td><center><?php if($m_in_status == 'Approved' OR $min_empno != '' OR $row1['M_timein'] == '')
						{ 

							if($row1['M_timein'] != ''){
						echo date('H:i', strtotime($row1['M_timein'])); 
							}else{
							echo "";
							}


						}else{

						echo "Pending";
						}

						?></center></td>
					<td><center><?php if($m_o_status == 'Approved' OR $mo_empno != '' OR $row1['M_timeout'] == '')
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

						?></center></td>
					<td><center>  <?php if($a_in_status == 'Approved' OR $ain_empno != '' OR $row1['A_timein'] == '')
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

						?></center></td>
					<td><center>

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

						?></center></td>
					<td><center><?php if(@$o_in_status == 'Approved' OR @$oin_empno != '' OR @$row1['O_timein'] == '')
						{ 

							if(@$row1['O_timein'] != ''){
						echo date('H:i', strtotime($row1['O_timein'])); 
							}else{
							echo "";
							}

						}else{

						echo "Pending";
						}

						?></center></td>
					<td><center><?php if(@$o_o_status == 'Approved' OR @$oo_empno != '' OR @$row1['O_timeout'] == '')
						{ 

							if(@$row1['O_timeout'] != ''){
						echo date('H:i', strtotime($row1['O_timeout'])); 
							}else{
							echo "";
							}


						}else{

						echo "Pending";
						}

						?></center></td>
					<td class="text-uppercase"><center><?php echo @$row11['othours']; ?></center></td>
					<td class="text-uppercase" colspan="2"><center><?php echo $row1['remarks']; ?></center></td>
					<td class="text-success"><center><b>POSTED</b></center></td>
				</tr>
			<?php 
					}elseif(($userlevel == 'mod' OR $userlevel == 'ac' OR $userlevel == 'admin')  AND $userlevel != 'master' AND $userid !=  4){
				?>

	

				<tr>
					<input type="text" name="idd[]" hidden value="<?php echo $id; ?>">
					<input type="text" name="datefromto[]" hidden value="<?php echo $row1['datefromto']; ?>">
					<input type="text" name="id[]" hidden value="<?php echo $row1['id']; ?>">
					<td colspan="2"><center><?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
					<td><center>

					<label> 
													<select class="custom-select" name="timefrom1[]">
														<option selected><?php echo date("H", strtotime($row1['schedfrom'])); ?></option>
														<option>00</option>
														<option>01</option>
														<option>02</option>
														<option>03</option>
														<option>04</option>
														<option>05</option>
														<option>06</option>
														<option>07</option>
														<option>08</option>
														<option>09</option>
														<option>10</option>
														<option>11</option>
														<option>12</option>
														<option>13</option>
														<option>14</option>
														<option>15</option>
														<option>16</option>
														<option>17</option>
														<option>18</option>
														<option>19</option>
														<option>20</option>
														<option>21</option>
														<option>22</option>
														<option>23</option>
													</select>
												</label>  
												<b>:</b>
												<label>
													<select class="custom-select" name="timefrom2[]">
														<option selected><?php echo date("i", strtotime($row1['schedfrom'])); ?></option>
														<option>00</option>
														<option>01</option>
														<option>02</option>
														<option>03</option>
														<option>04</option>
														<option>05</option>
														<option>06</option>
														<option>07</option>
														<option>08</option>
														<option>09</option>
														<option>10</option>
														<option>11</option>
														<option>12</option>
														<option>13</option>
														<option>14</option>
														<option>15</option>
														<option>16</option>
														<option>17</option>
														<option>18</option>
														<option>19</option>
														<option>20</option>
														<option>21</option>
														<option>22</option>
														<option>23</option>
														<option>24</option>
														<option>25</option>
														<option>26</option>
														<option>27</option>
														<option>28</option>
														<option>29</option>
														<option>30</option>
														<option>31</option>
														<option>32</option>
														<option>33</option>
														<option>34</option>
														<option>35</option>
														<option>36</option>
														<option>37</option>
														<option>38</option>
														<option>39</option>
														<option>40</option>
														<option>41</option>
														<option>42</option>
														<option>43</option>
														<option>44</option>
														<option>45</option>
														<option>46</option>
														<option>47</option>
														<option>48</option>
														<option>49</option>
														<option>50</option>
														<option>51</option>
														<option>52</option>
														<option>53</option>
														<option>54</option>
														<option>55</option>
														<option>56</option>
														<option>57</option>
														<option>58</option>
														<option>59</option>
													</select>
												</label>


&nbsp to &nbsp
 
	<label> 

									<select class="custom-select" name="timeto1[]">
														<option selected><?php echo date("H", strtotime($row1['schedto'])); ?></option>
														<option>00</option>
														<option>01</option>
														<option>02</option>
														<option>03</option>
														<option>04</option>
														<option>05</option>
														<option>06</option>
														<option>07</option>
														<option>08</option>
														<option>09</option>
														<option>10</option>
														<option>11</option>
														<option>12</option>
														<option>13</option>
														<option>14</option>
														<option>15</option>
														<option>16</option>
														<option>17</option>
														<option>18</option>
														<option>19</option>
														<option>20</option>
														<option>21</option>
														<option>22</option>
														<option>23</option>
													</select>
												</label>  
												<b>:</b>
												<label>
													<select class="custom-select" name="timeto2[]">
														<option selected><?php echo date("i", strtotime($row1['schedto'])); ?></option>
														<option>00</option>
														<option>01</option>
														<option>02</option>
														<option>03</option>
														<option>04</option>
														<option>05</option>
														<option>06</option>
														<option>07</option>
														<option>08</option>
														<option>09</option>
														<option>10</option>
														<option>11</option>
														<option>12</option>
														<option>13</option>
														<option>14</option>
														<option>15</option>
														<option>16</option>
														<option>17</option>
														<option>18</option>
														<option>19</option>
														<option>20</option>
														<option>21</option>
														<option>22</option>
														<option>23</option>
														<option>24</option>
														<option>25</option>
														<option>26</option>
														<option>27</option>
														<option>28</option>
														<option>29</option>
														<option>30</option>
														<option>31</option>
														<option>32</option>
														<option>33</option>
														<option>34</option>
														<option>35</option>
														<option>36</option>
														<option>37</option>
														<option>38</option>
														<option>39</option>
														<option>40</option>
														<option>41</option>
														<option>42</option>
														<option>43</option>
														<option>44</option>
														<option>45</option>
														<option>46</option>
														<option>47</option>
														<option>48</option>
														<option>49</option>
														<option>50</option>
														<option>51</option>
														<option>52</option>
														<option>53</option>
														<option>54</option>
														<option>55</option>
														<option>56</option>
														<option>57</option>
														<option>58</option>
														<option>59</option>
													</select>
												</label>
													</td>

					<td><center><input type="number" min="0" max="9" name="break[]" class="form-control text-center" value="<?php echo $row1['break']; ?>" ><center></td>
					
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
					if($totals < $atimein AND $atimein != 'No Break'){	
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
			<?php 
					if($otimein != null AND $otimeout != null){
				?>	<td class="text-primary"><b>
			<?php 
					}else{
				?>	
				<td>	
			<?php 
				}
				?>	
					<center>
							
						<?php if(@$o_in_status == 'Approved' OR $oin_empno != '' OR @$row1['O_timein'] == '')
						{ 

							if(@$row1['O_timein'] != ''){
						echo date('H:i', strtotime($row1['O_timein'])); 
							}else{
							echo "";
							}
						}

						?>
						</center></b></td>

			<?php 
					if(@$otimein != null AND $otimeout != null){
				?>	<td class="text-primary"><b>
			<?php 
					}else{
				?>	
				<td>	
			<?php 
				}
				?>	
					<center>
						
						<?php if(@$o_o_status == 'Approved' OR @$oo_empno != '' OR @$row1['O_timeout'] == '')
						{ 

							if(@$row1['O_timeout'] != ''){
						echo date('H:i', strtotime($row1['O_timeout'])); 
							}else{
							echo "";
							}

						}else{

						echo "Pending";
						}

						?>
					</center></b></td>
						<td class="text-uppercase"><center><input type="text" name="othours[]" class="form-control text-center" value="<?php echo @$row11['othours']; ?>"><input type="text" name="otid[]" hidden  value="<?php echo @$row11['ovid']; ?>"> <?php echo @$row11['ottype']; ?></center></td>
					<td class="text-uppercase" colspan="2"><center><input type="text" name="remarks[]" class="form-control" value="<?php echo $row1['remarks']; ?>"></center></td>
					<td>
						
						<input type="submit" class="btn btn-outline-success btn-user btn-block btn1" value="Save" name="SubmitButton" onclick="return confirm('Are you sure you want to Save This Record?');">
			

					</td>														
				</tr>
 <?php
		}
	
if($userlevel == 'master' OR  $userid ==  4){

		?>


			<tr>
					<input type="text" name="idd[]" hidden value="<?php echo $id; ?>">
					<input type="text" name="datefromto[]" hidden value="<?php echo $row1['datefromto']; ?>">
					<input type="text" name="id[]" hidden value="<?php echo $row1['id']; ?>">
					<input type="text" name="emp" hidden value="<?php echo $row1['empno']; ?>">
					<td colspan="2"><center><?php echo date("m-d-Y", strtotime($row1['datefromto'])); ?><center></td>
					<td><center><input type="text" class="form-control text-center" name="fromtime[]" value="<?php echo date("H:i", strtotime($row1['schedfrom'])); ?>"> - <input type="text" name="totime[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['schedto'])); ?>"></center></td>
					<td><center><input type="text" name="break[]" class="form-control text-center" value="<?php echo $row1['break']; ?>"><center></td>
					<?php if($row1['M_timein'] == ''){ ?>
					<td><center><input type="text" name="timein1[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['timein']; ?>" name ="timeaa[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein1[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['M_timein'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['timein']; ?>" name ="timeaa[]"><center></td>
					<?php } ?>

					<?php if($row1['M_timeout'] == ''){ ?>
					<td><center><input type="text" name="timein2[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['breakout']; ?>" name ="timebb[]"><center></td>
					<?php }elseif($row1['M_timeout'] == 'No Break'){ ?>
					<td><center><input type="text" name="timein2[]" class="form-control text-center" value="<?php echo $row1['M_timeout']; ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['breakout']; ?>" name ="timebb[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein2[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['M_timeout'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['breakout']; ?>" name ="timebb[]"><center></td>
					<?php } ?>

					<?php if($row1['A_timein'] == ''){ ?>
					<td><center><input type="text" name="timein3[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['breakin']; ?>" name ="timecc[]"><center></td>
					<?php }elseif($row1['A_timein'] == 'No Break'){ ?>
					<td><center><input type="text" name="timein3[]" class="form-control text-center" value="<?php echo $row1['A_timein']; ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['breakin']; ?>" name ="timecc[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein3[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['A_timein'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['breakin']; ?>" name ="timecc[]"><center></td>
					<?php } ?>

					<?php if($row1['A_timeout'] == ''){ ?>
					<td><center><input type="text" name="timein4[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['timeout']; ?>" name ="timedd[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein4[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['A_timeout'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['timeout']; ?>" name ="timedd[]"><center></td>
					<?php } ?>

					<?php if($row1['O_timein'] == ''){ ?>
					<td><center><input type="text" name="timein5[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['overin']; ?>" name ="timeee[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein5[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['O_timein'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['overin']; ?>" name ="timeee[]"><center></td>
					<?php } ?>

					<?php if($row1['O_timeout'] == ''){ ?>
					<td><center><input type="text" name="timein6[]" class="form-control text-center" value=""><input type="text" class="form-control text-center" value="<?php echo $row1['overout']; ?>" name ="timeff[]"><center></td>
					<?php }else{ ?>
					<td><center><input type="text" name="timein6[]" class="form-control text-center" value="<?php echo date("H:i", strtotime($row1['O_timeout'])); ?>"><input type="text" class="form-control text-center" value="<?php echo $row1['overout']; ?>" name ="timeff[]"><center></td>
					<?php } ?>

					<td class="text-uppercase"><center><input type="text" name="othours[]" class="form-control text-center" value="<?php echo @$row11['othours']; ?>"><input type="text" name="otid[]" hidden value="<?php echo @$row11['ovid']; ?>"> <?php echo @$row11['ottype']; ?></center></td>
					<td class="text-uppercase" colspan="2"><center><input type="text" name="remarks[]" class="form-control" value="<?php echo $row1['remarks']; ?>"></center></td>
					<td><input type="submit" class="btn btn-outline-success btn-user btn-block btn1" value="Save" name="SubmitButton" onclick="return confirm('Are you sure you want to Save This Record?');"></td>	
			</tr>

<?php
	}

		
		}

		?>	

			</tbody>
						
			
		  </table>
		  
		  
		  
		</form>
		
		</div>
		
			
			
		

	
</p>
</body>
	
	
</html>