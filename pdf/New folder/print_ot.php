<!-- Begin Page Content --> <!-- Search -->
<?php  
  $ORconnect = mysqli_connect("localhost", "root", "", "db");
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


<?php  
	if(isset($_GET["ot"]) == "ot")  
	{  
	?>

<body>

<p style="page-break-before: always">

		<div class="col-12">
			<table class="myTable">
				<?php 
				
				@$empno = $_GET['empno'];
				@$datefrom = $_GET['datefrom'];
				@$dateto = $_GET['dateto'];

                 $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
                 $query=$HRconnect->query($sql);
                 $row=$query->fetch_array()
                                                
                                        
				?>


				<thead >									
					<tr>
						<th colspan="100%" class="text-muted text-uppercase">
							<center><h5><small>Human Resource Department</small><br>Overtime Request</h5></center>		

							<div class="row">
								
								<div class="col-6">
									<p class="text-uppercase">
									
										Fullname: <b><?php echo $row['name']; ?></b> <br/>
										Position: <b><?php echo $row['position']; ?></b>
										
										 
									</p>
								</div>
								 
								<div class="col-3">
								</div>

								 <div class="col-3">
									<p class="text-uppercase"> 	
										Employee ID :  <b><?php echo $row['empno']; ?></b> <br / >
										Dept/Branch: <b><?php echo $row['branch']; ?></b> 									
								 </div> 
							  
							</div>					
						</th>
				    </tr>
				
					<tr class="text-uppercase">
						<th><center><b>Date Overtime</b></center></th>
						<th><center><b>Reason/Purpose</b></center></th>  
						<th><center><b>Start</b></center></th>   
						<th><center><b>End</b></center></th>
						<th><center><b>No. of OT (Hours)</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>
		


  			 <?php 

                 $sql1 = "SELECT * FROM overunder
                    	 JOIN sched_time on overunder.otdatefrom = sched_time.datefromto
                         WHERE overunder.otstatus = 'approved' AND sched_time.empno = overunder.empno AND ottype = 'Overtime' AND overunder.empno = $empno 
                         AND overunder.otdatefrom BETWEEN '$datefrom' AND '$dateto'";
                 $query1=$HRconnect->query($sql1);
                 while($row1=$query1->fetch_array())
                                                {

                    $ot = strtotime($row1['O_timeout']) - strtotime($row1['O_timein']) ;

                  
                                            ?>
					<tr>
						<td><center><?php echo $row1['otdatefrom']; ?><center></td>
						<td><center><?php echo $row1['otreason']; ?></center></td>

						<?php if($row1['O_timein'] != ''){ ?>
						<td><center><?php echo date('H:i', strtotime($row1['O_timein'])); ?></center></td>
						<?php }else{ ?>
						<td><center><?php echo $row1['O_timein']; ?></center></td>
						<?php } ?>

						<?php if($row1['O_timeout'] != ''){ ?>
						<td><center><?php echo date('H:i', strtotime($row1['O_timeout'])); ?></center></td>
						<?php }else{ ?>
						<td><center><?php echo $row1['O_timeout']; ?></center></td>
						<?php } ?>

				
						<td><center><?php echo number_format($ot /3600, 2, '.', ''); ?></center></td>
					</tr>
			<?php 

			@$total += $ot;
				}
				?>
					
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="4" style="text-align: right"><b>Total</b></td>
						<td><center><b><?php echo number_format(@$total /3600, 2, '.', ''); ?></b></center></td>
					</tr>
					
					<tr>
						<td colspan="5" class="border-white"><p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p></td>
					</tr>
									
				</tfoot>
			
			</table>	
		</div>
</p>
</body>

<br>
<hr>
<br>
	
<?php  
	}
	?>
	
<?php  
	if(isset($_GET["ut"]) == "ut")  
	{  
	?>
<body>
	<?php 


	@$empno = $_GET['empno'];
	@$datefrom = $_GET['datefrom'];
	@$dateto = $_GET['dateto'];

                 $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
                 $query=$HRconnect->query($sql);
                 $row=$query->fetch_array()
                                                


	?>

<p style="page-break-before: always">

		<div class="col-12">
			<table class="myTable">
				<thead >									
					<tr>
						<th colspan="100%" class="text-muted text-uppercase">
							<center><h5><small>Human Resource Department</small><br>Undertime Request</h5></center>		
						
							<div class="row">
								
								<div class="col-6">
									<p class="text-uppercase">
									
										Fullname: <b><?php echo $row['name']; ?></b> <br/>
										Position: <b><?php echo $row['position']; ?></b>
										
										 
									</p>
								</div>
								 
								<div class="col-3">
								</div>

								 <div class="col-3">
									<p class="text-uppercase"> 	
										Employee ID :  <b><?php echo $row['empno']; ?></b> <br / >
										Dept/Branch: <b><?php echo $row['branch']; ?></b> 									
								 </div> 
							  
							</div>						
						</th>
				    </tr>
				
					<tr class="text-uppercase">
						<th><center><b>Date Overtime</b></center></th>
						<th><center><b>Reason/Purpose</b></center></th>  
						<th><center><b>Schedule</b></center></th>
						<th><center><b>Time Out</b></center></th>   
						<th><center><b>No. of Undertime (Min)</b></center></th>	
					</tr>
									
				</thead>
				
				
				<tbody>

		
				<?php 
					
                 $sql2 = "SELECT * FROM overunder
                    	 JOIN sched_time on overunder.otdatefrom = sched_time.datefromto
                         WHERE otstatus = 'approved' AND sched_time.empno = overunder.empno AND ottype = 'Undertime' AND overunder.empno = '$empno'";
                 $query2=$HRconnect->query($sql2);
                 while($row2=$query2->fetch_array())
                                                {
                        $ut =  strtotime($row2['schedto']) - strtotime($row2['A_timeout']);
                                            ?>
					<tr>
						<td><center><?php echo $row2['otdatefrom']; ?><center></td>
						<td><center><?php echo $row2['otreason']; ?></center></td>
						<td><center><?php echo date('H:i', strtotime($row2['schedto']));  ?></center></td>
						<td><center><?php echo date('H:i', strtotime($row2['A_timeout'])); ?></center></td>
						<td><center><?php echo number_format($ut /3600, 2, '.', ''); ?></center></td>
					</tr>
			<?php 

			@$total2 += $ut;
				}
				?>
					
				</tbody>
				
				<tfoot>
					<tr>
						<td colspan="4" style="text-align: right"><b>Total</b></td>
						<td><center><b><?php echo number_format(@$total2 /3600, 2, '.', ''); ?></b></center></td>
					</tr>
					
					<tr>
						<td colspan="5" class="border-white"><p class="text-muted"><i>I CERTIFY that the above inforamtion provided is correct. Any falsification of information in this
						regard may form ground for disciplinary action up to and including dismissal.</i></p></td>
					</tr>
									
				</tfoot>
			
			</table>	
		</div>
<br>
		
</p>
</body>


<?php  
}


	if(isset($_GET["leave"]) == "leave")  
	{  
	?>
	
<body>

<p style="page-break-before: always">

		<div class="col-12">
			<table class="myTable">
				<thead >									
					<tr>
						<th colspan="100%" class="text-muted text-uppercase">
							<center><h5><small>Human Resource Department</small><br>Approved Leaves <?php
														$Today=date('y:m:d');
														$new=date('Y',strtotime($Today));
														echo $new; ?> </h5></center>		
							<?php 

				@$empno = $_GET['empno'];
				@$datefrom = $_GET['datefrom'];
				@$dateto = $_GET['dateto'];

                 $sql = "SELECT * FROM user_info WHERE empno = '$empno'";
                 $query=$HRconnect->query($sql);
                 $row=$query->fetch_array()

                 ?>
							<div class="row">
								
								<div class="col-6">
									<p class="text-uppercase">
									
										Fullname: <b><?php echo $row['name']; ?></b> <br/>
										Position: <b><?php echo $row['position']; ?></b>
										
										 
									</p>
								</div>
								 
								<div class="col-3">
								</div>

								 <div class="col-3">
									<p class="text-uppercase"> 	
										Employee ID :  <b><?php echo $row['empno']; ?></b> <br / >
										Dept/Branch: <b><?php echo $row['branch']; ?></b> 									
								 </div> 
							  
							</div>					
						</th>
				    </tr>
				
					<tr class="text-uppercase">
						<th><center><b>Date</b></center></th>
						<th><center><b>Reason/Purpose</b></center></th>  
					</tr>
									
				</thead>
				
				
				<tbody>
			<?php 


                 $sql1 = "SELECT * FROM vlform
                    		WHERE vlstatus = 'approved' AND empno = $empno
                    		ORDER BY `vlform`.`vldatefrom` DESC";
                 $query1=$HRconnect->query($sql1);
                 while($row1=$query1->fetch_array())
                                                {

                                                	?>
					<tr>
						<td><center><?php echo $row1['vldatefrom']; ?><center></td>
						<td><center><?php echo $row1['vlreason']; ?></center></td>
					</tr>
			<?php 
				}
					?>
				</tbody>
			</table>	
		</div>
<br>

		
</p>

	<footer class="sticky-footer">				
				<div class="container my-auto">
			<hr>	
					<div class="text-center">
						<a style="color:#7E0000;font-family:Times New Roman, cursive;font-size:100%;" href="/video/tutorial.php"><i>Tutorial</i></a>
					&nbsp &nbsp &nbsp &nbsp	
					<a style="color:#7E0000;font-family:Times New Roman, cursive;font-size:100%;" href="/video/faqs.php"><i>FAQs</i></a>
					</div>
				<br>
					<div class="copyright text-center my-auto">
						 <span>Copyright © Mary Grace Foods Inc. 2019</span>
					</div>								
				</div>
			</footer>
</body>

<?php
	}
	?>		
</html>