<!-- Begin Page Content --> <!-- Search -->
<?php  
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();


setcookie("reloaded","true");


if(empty($_SESSION['user'])){
    header('location:login.php');
}

$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$userid = $_SESSION['useridd'];
$empno = $row['empno'];


// QUERY TO GET THE PENDING CUT OFF DATE USING LEFT JOIN TO ACCESS OTHER INFO
$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
ON si.empno = ui.empno
WHERE si.status = 'Pending' AND ui.empno = $empno;";
$querybuilder=$HRconnect->query($getDateSQL);
$rowCutOff=$querybuilder->fetch_array();

$mindate = $rowCutOff['datefrom'];
$maxdate = $rowCutOff['dateto'];


if (isset($_GET['branch']))

{
@$_SESSION['useridd'] = $_GET['branch'];

Header('Location: '.$_SERVER['PHP_SELF']);

}

$sql1 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query1=$ORconnect->query($sql1);
$row1=$query1->fetch_array();
$areatype = $row1['areatype'];

$userid = $_SESSION['useridd'];
if($userlevel != 'staff') {

if(isset($_POST["submit"]) == "submit") {
    @$_SESSION['datedate1'] = date('Y-m-d', strtotime($_POST['datefrom']));
}

@$backfrom = $_SESSION['datedate1'];
?>  


<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
    <meta name="author" content="">

   <title>Mary Grace Foods Inc.</title>
	<link rel="icon" href="images/logoo.png">

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="style.css">
    <script src="script.js"></script> -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

	<style>
		@media screen and (max-width: 800px) {
		  table {
			border: 0;
		  }

		  table caption {
			font-size: 1.3em;
		  }
		  
		  table thead {
			border: none;
			clip: rect(0 0 0 0);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute;
			width: 1px;
		  }
		  
		  table tr {
			border-bottom: 5px solid #ddd;
			display: block;
			margin-bottom: .625em;
		  }
		  
		  table td {
			border-bottom: 1px solid #ddd;
			display: block;
			font-size: .8em;
			text-align: right;
		  }
		  
		  table td::before {
			/*
			* aria-label has no advantage, it won't be read inside a table
			content: attr(aria-label);
			*/
			content: attr(data-label);
			float: left;
			font-weight: bold;
			text-transform: uppercase;
		  }
		  
		  table td:last-child {
			border-bottom: 0;
		  }
		}
        .ow-break-word {
			overflow-wrap: break-word;
		}
	</style>

</head>



<body id="page-top">

	<?php include("navigation.php"); ?>
<!-- Begin Page Content -->
                <div class="container-fluid">
					<form method="POST">
                    <!-- Page Heading -->
					<div class="d-flex justify-content-md-between flex-md-row flex-column justify-content-center align-items-center mb-2">
						<div class="mb-3">
							<h4 class="mb-0 text-md-left text-center">Change Schedule</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>
							
						<form method="POST">
                            <div class="form-group row">                                

								<div class="col-auto text-center">
                                    <label>Date From</label>
                                    <input type="date"  id="#datePicker" class="form-control text-center" name="datefrom" placeholder="Insert Date" value="<?php echo @$backfrom; ?>" autocomplete="off" onkeypress="return false;" min="<?php echo $mindate;?>" max="<?php echo $maxdate;?>" />                                                                                                              
                                </div>
                                                                    
								<div class="col-auto text-center d-sm-inline-block">
                                    <label class="invisible">.</label>
                                    <div class="form-group row">
										<div class="col-xs-6 ml-2">
											<input class="btn btn-primary btn-user btn-block bg-gradient-primary text-center" name="submit" type="submit" value="Submit" formaction="changesched.php">
										</div> &nbsp
									</div>
								</div>

                            </div>
						</form>
                    </div>
                </form>
						
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <?php
                        if(isset($_POST['change_sched'])){
                            // var_dump($_POST);
                            $employee_change_sched = array();
                            $emp_details = new stdClass();

                            // For Each Category of 8 inputs, (Empno, DateFrom, HourFrom, MinuteFrom, HourTo, MinuteTo, Break, Remarks)
                            $counter = 1;
                            foreach ($_POST as $key => $value) {
                                // echo $key.":   ".$value;
                                if($key == "change_sched" || $key == "dataTable_length"){
                                    continue;
                                }else{
                                    if($counter == 1){
                                        $emp_details->empno = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 2){
                                        $emp_details->datefromto = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 3){
                                        $emp_details->hour_from = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 4){
                                        $emp_details->minute_from = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 5){
                                        $emp_details->hour_to = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 6){
                                        $emp_details->minute_to = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 7){
                                        $emp_details->break = $_POST[$key];
                                        $counter++;
                                    }else if($counter == 8){
                                        $emp_details->remarks = $_POST[$key];
                                        array_push($employee_change_sched, $emp_details);
                                        $emp_details = new stdClass();
                                        $counter = 1;
                                    }
                                }           
                            }
                            
                            $changes = 0;
                            // echo print_r($employee_change_sched);
                            for($emp_counter = 0; $emp_counter < count($employee_change_sched); $emp_counter++){
                                $timein_cs = strtotime($employee_change_sched[$emp_counter]->datefromto . " " . $employee_change_sched[$emp_counter]->hour_from . ":" . $employee_change_sched[$emp_counter]->minute_from);

                                $timeout_cs = strtotime($employee_change_sched[$emp_counter]->datefromto . " " . $employee_change_sched[$emp_counter]->hour_to . ":" . $employee_change_sched[$emp_counter]->minute_to);
                                
                                if ($timeout_cs < $timein_cs) {
                                    $timeout_cs = strtotime('+1 day', $timeout_cs);
                                }
                                $timein_cs_formatted = date("Y-m-d H:i", $timein_cs);
                                $timeout_cs_formatted = date("Y-m-d H:i", $timeout_cs);
                                $sql_select_validate_date = "SELECT st.schedfrom, st.schedto, st.empno, st.remarks,ui.name FROM `hrms`.`sched_time` st 
                                LEFT JOIN user_info ui ON st.empno = ui.empno
                                WHERE st.empno='".$employee_change_sched[$emp_counter]->empno."' AND st.schedfrom = '".$timein_cs_formatted."'
                                AND st.schedto = '".$timeout_cs_formatted."' AND st.remarks ='".$employee_change_sched[$emp_counter]->remarks."'
                                AND st.break = '".$employee_change_sched[$emp_counter]->break."'";

                                $query_select_validate_date = $HRconnect->query($sql_select_validate_date);
                                $row_select_validate_date = $query_select_validate_date->fetch_array();
                                // echo $sql_select_validate_date;
                                // echo print_r($row_select_validate_date);
                                if(is_null($row_select_validate_date)){
                                    // LOGS 
                                    // GET SQL FIRST OF OLD DATA BEFORE UPDATING
                                    $sql_select_validate_emp = "SELECT st.schedfrom, st.schedto, st.break, st.empno, st.remarks, ui.name FROM `hrms`.`sched_time` st 
                                    LEFT JOIN user_info ui ON st.empno = ui.empno
                                    WHERE st.empno='".$employee_change_sched[$emp_counter]->empno."' AND st.datefromto LIKE '%".$employee_change_sched[$emp_counter]->datefromto."'";
                                    $query_select_validate_emp = $HRconnect->query($sql_select_validate_emp);
                                    $row_select_validate_emp = $query_select_validate_emp->fetch_array();
                                    
                                    // HOOKS FOR PREVIOUS
                                    $edited_emp = $row_select_validate_emp['empno'];
                                    $edited_emp_name = $row_select_validate_emp['name'];
                                    $prev_breaks = $row_select_validate_emp['break'];
                                    $prev_remarks = $row_select_validate_emp['remarks'];
                                    $prev_schedto = date("Y-m-d H:i",strtotime($row_select_validate_emp['schedto']));
                                    $prev_schedfrom = date("Y-m-d H:i",strtotime($row_select_validate_emp['schedfrom']));
                                    $date_now = date("Y-m-d H:i:s");

                                    // LOGS
                                    $sql_insert_log = "INSERT INTO `hrms`.`log` (`empno`, `action`, `inserted`, `date_time`) 
                                    VALUES ('".$empno."', '".$edited_emp_name." ".$edited_emp." previous sched (breaks: ".$prev_breaks." sched: ".$prev_schedfrom." - ".$prev_schedto." remarks: ".$prev_remarks.") new sched (breaks: ".$employee_change_sched[$emp_counter]->break." sched: ".$timein_cs_formatted." - ".$timeout_cs_formatted." remarks: ".$employee_change_sched[$emp_counter]->remarks.") - Change Schedule', 'Successfully Saved', '".$date_now."');";
                                    $HRconnect->query($sql_insert_log);

                                    // SQL UPDATE
                                    $sql_update = "UPDATE `hrms`.`sched_time` SET `schedfrom` = '".$timein_cs_formatted."', `schedto` = '".$timeout_cs_formatted."', `break` = '".$employee_change_sched[$emp_counter]->break."', `remarks` = '".$employee_change_sched[$emp_counter]->remarks."'
                                    WHERE (`empno` = '".$employee_change_sched[$emp_counter]->empno."' AND `datefromto` = '".$employee_change_sched[$emp_counter]->datefromto."');";

                                    $HRconnect->query($sql_update);
                                    $changes++;
                                }else{
                                    continue;
                                }
                            }

                            if($changes == 0){
                                echo '
                                <script>
                                    $(function() {
                                        $(".thanks").delay(2500).fadeOut();
                                
                                    });
                                </script>
                                <div aria-live="polite" aria-atomic="true" style="position: absolute; top:-90px; min-height: 100px; z-index: 9999;">
                                    <div class="thanks toast fade show" style="position: fixed; right: 5px;">
                                        <div class="toast-header bg-warning">
                                            <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change Schedule</h5>
                                            <small class="text-light ml-5">just now</small>
                                        </div>
                                        <div class="toast-body">
                                            <b class="text-warning">No changes</b> were made.
                                        </div>
                                    </div>
                                </div>';
                            }else{
                                echo '
                                <script>
                                    $(function() {
                                        $(".thanks").delay(2500).fadeOut();
                                
                                    });
                                </script>
                                <div aria-live="polite" aria-atomic="true" style="position: absolute; top:-90px; min-height: 100px; z-index: 9999;">
                                    <div class="thanks toast fade show" style="position: fixed; right: 5px;">
                                        <div class="toast-header bg-success">
                                            <h4 class="mr-auto my-0 text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> Change Schedule</h5>
                                            <small class="text-light ml-5">just now</small>
                                        </div>
                                        <div class="toast-body">
                                            <b class="text-success">'.$changes.' change(s)</b> were made.
                                        </div>
                                    </div>
                                </div>';
                                
                            }
                        }
                    
                    ?>
                    <form method="POST" action="changesched.php">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-end border-left-primary">
                            <h6 class="m-0 font-weight-bold text-primary d-md-none mr-3">Daily Posting</h6>
                            <input class="btn btn-success btn-user bg-gradient-success text-center " name="change_sched" type="submit" value="Save Changes" onclick="return abc()" >
                        </div>
                    

                        <div class="card-body border-left-primary">
                            <div class="table-responsive">     
                                <table class="table table-bordered table-hover text-uppercase" id="dataTable" width="100%" cellspacing="0">

                                    <thead>
                                        <tr class="bg-gray-200">																										
                                            <th><center>ID</center></th>
                                            <th><center>Fullname</center></th>
                                            <th><center>Date</center></th>
                                            <th><center>Time</center></th>
                                            <th><center>Break</center></th>
                                            <th><center>Time IN</center></th>
                                            <th><center>Break OUT</center></th>	
                                            <th><center>Break IN</center></th>	
                                            <th><center>Time OUT</center></th>	
                                            <th><center>Remarks</center></th>							
                                        </tr>
                                    </thead>

                                    <tbody>																												
                                    <?php 
                                        $sql1 = "SELECT st.*, ui.name, ui.userid FROM sched_time st 
                                        LEFT JOIN user_info ui ON ui.empno = st.empno
                                        where st.datefromto = '$backfrom' AND ui.status != 'resigned'  AND ui.userid = '$userid'";
                                        $query1= mysqli_query($HRconnect, $sql1);
                                        $row1 = mysqli_fetch_all($query1, MYSQLI_ASSOC); 
                                        $counter = 0; 
                                        while($counter < count($row1)){
                                            $empno = $row1[$counter]['empno'];
                                            $id = $row1[$counter]['id'];
                                            @$timeform = $row1[$counter]['schedfrom']; 
                                            @$timeto = $row1[$counter]['schedto'];
                                            @$datefromto = $row1[$counter]['datefromto'];	
                                            @$break = $row1[$counter]['break']; 
                                            @$Timein = $row1[$counter]['M_timein']; 
                                            @$Breakout = $row1[$counter]['M_timeout'];
                                            @$Breakin = $row1[$counter]['A_timein']; 
                                            @$Timeout = $row1[$counter]['A_timeout']; 
                                            @$OTin    = $row1[$counter]['O_timein']; 
                                            @$OTout   = $row1[$counter]['O_timeout'];
                                            @$remarks   = $row1[$counter]['remarks'];
                                            $userid1 = $row1[$counter]['userid']; 
                                            $name1 = $row1[$counter]['name'];
                                    ?>         	        	
								<tr>
                                    <td><center><?php echo $empno; ?><input type="text" class="d-none" name="empno_<?php echo $counter?>" value="<?php echo $empno; ?>"></center></td>
                                    <td><center><?php echo $name1; ?></center></td>
                                    <td><center><?php echo $datefromto; ?><input type="text" class="d-none" name="datefromto_<?php echo $counter?>" value="<?php echo $datefromto; ?>"></center></td>
                                    <td style="min-width: 135px">
                                        <div class="">
                                            <div class="col-xs-12 d-flex justify-content-between align-items-center">
                                                <select class="custom-select" name="hour_from_<?php echo $counter?>" id="">
                                                <!-- SCHEDULE ON FIRST -->
                                                <option class="bg-warning"  value="<?php echo date('H',strtotime($timeform))?>"><?php echo date('H',strtotime($timeform))?></option>
                                                <?php 
                                                // OUTPUTS 23 time format
                                                    $time = "";
                                                    for($counter_time=0; $counter_time < 24; $counter_time++){
                                                        $time = $counter_time;
                                                        if(strlen($time) < 2){
                                                            echo '<option value="0'.$time.'">0'.$time.'</option>';
                                                        }else{
                                                            echo '<option value="'.$time.'">'.$time.'</option>';
                                                        }
                                                    }
                                                ?>
                                                </select>
                                                <b class="p-1">:</b>
                                                <select class="custom-select" name="minute_from_<?php echo $counter?>" id="">
                                                    <!-- SCHEDULE ON FIRST -->
                                                    <option class="bg-warning" value="<?php echo date('i',strtotime($timeform))?>"><?php echo date('i',strtotime($timeform))?></option>
                                                    <?php 
                                                    // OUTPUTS MINUTES of time format
                                                        $time = "";
                                                        for($counter_time=0; $counter_time < 60; $counter_time++){
                                                            $time = $counter_time;
                                                            if(strlen($time) < 2){
                                                                echo '<option value="0'.$time.'">0'.$time.'</option>';
                                                            }else{
                                                                echo '<option value="'.$time.'">'.$time.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <p class="text-lowercase d-flex justify-content-center m-0">to</p>
                                            <!-- SCHEDTO -->
                                            <div class="col-xs-12 d-flex justify-content-between align-items-center">
                                                <select class="custom-select" name="hour_to_<?php echo $counter?>" id="">
                                                <!-- SCHEDULE ON FIRST -->
                                                <option class="bg-warning" value="<?php echo date('H',strtotime($timeto))?>"><?php echo date('H',strtotime($timeto))?></option>
                                                <?php 
                                                // OUTPUTS 23 time format
                                                    $time = "";
                                                    for($counter_time=0; $counter_time < 24; $counter_time++){
                                                        $time = $counter_time;
                                                        if(strlen($time) < 2){
                                                            echo '<option value="0'.$time.'">0'.$time.'</option>';
                                                        }else{
                                                            echo '<option value="'.$time.'">'.$time.'</option>';
                                                        }
                                                    }
                                                ?>
                                                </select>
                                                <b class="p-1">:</b>
                                                <select class="custom-select" name="minute_to_<?php echo $counter?>" id="">
                                                    <!-- SCHEDULE ON FIRST -->
                                                    <option class="bg-warning" value="<?php echo date('i',strtotime($timeto))?>"><?php echo date('i',strtotime($timeto))?></option>
                                                    <?php 
                                                    // OUTPUTS MINUTES of time format
                                                        $time = "";
                                                        for($counter_time=0; $counter_time < 60; $counter_time++){
                                                            $time = $counter_time;
                                                            if(strlen($time) < 2){
                                                                echo '<option value="0'.$time.'">0'.$time.'</option>';
                                                            }else{
                                                                echo '<option value="'.$time.'">'.$time.'</option>';
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="min-width: 100px">
                                        <select class="custom-select" name="breaks_<?php echo $counter?>" id="">
                                            <!-- SCHEDULE ON FIRST -->
                                            <option class="bg-warning" value="<?php echo $break?>"><?php echo $break." hr(s)"?></option>
                                            <?php 
                                                for($counter_time=0; $counter_time < 11; $counter_time++){
                                                    echo '<option value="'.$counter_time.'">'.$counter_time.' hr(s)</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <!-- TIME INPUTS -->
                                    <?php if($Timein != ''){ ?>
                                    <td class="
                                        <?php 
                                            echo (strtotime($Timein) > strtotime($timeform))? "text-danger":"";
                                        ?>">
                                        <center>
                                            <?php echo date('H:i', strtotime($Timein));?>
                                        </center>
                                    </td>   

                                    <?php }else{ ?>
                                        <td><center><?php echo $Timein; ?></center></td>
                                    <?php } ?>

                                    <?php if($Breakout != '' AND $Breakout != 'No Break'){ ?>
                                    <td><center><?php echo date('H:i', strtotime($Breakout)); ?></center></td>   
                                    <?php }else{ ?>
                                    <td><center><?php echo $Breakout; ?></center></td>
                                    <?php } ?>

                                    <?php if($Breakin != '' AND $Breakout != 'No Break'){ ?>
                                    <td class="
                                        <?php 
                                            echo (abs(strtotime($Breakin) - strtotime($Breakout))/60/60 > 1)?"text-danger":"";
                                        ?>">
                                        <center>
                                            <?php echo date('H:i', strtotime($Breakin)); ?>
                                        </center>
                                    </td>   
                                    <?php }else{ ?>
                                    <td><center><?php echo $Breakin; ?></center></td>
                                    <?php } ?>

                                    <?php if($Timeout != ''){ ?>
                                    <td class="<?php echo (strtotime($Timeout) < strtotime($timeto))?"text-danger":"";?>">
                                        <center>
                                            <?php echo date('H:i', strtotime($Timeout)); ?>
                                        </center>
                                    </td>   
                                    <?php }else{ ?>
                                    <td><center><?php echo $Timeout; ?></center></td>
                                    <?php } ?>
                                    <td>
                                        <select class="custom-select" name="remarks_<?php echo $counter?>" id="" 
                                            <?php
                                                $sql_wdo_status       = "SELECT wdostatus FROM `hrms`.`working_dayoff`
                                                                        WHERE empno = $empno and datefrom = '".$datefromto."'";
                                                $query_wdo_stats      = $HRconnect->query($sql_wdo_status);
                                                $row_query_stats      = $query_wdo_stats->fetch_array();
                                                $wdo_status           = $row_query_stats['wdostatus'];
                                                $show = "";
                                                $disable_class = "";
                                                if($wdo_status == 'approved'){
                                                    $show = "disabled";
                                                    $disable_class = "d-none";
                                                }
                                            ?>
                                        >
                                            <option class="bg-warning" value="<?php echo $remarks;?>"><?php echo $remarks;?></option>
                                            <?php  
                                                $remarks_options = array('RD','AB', 'LWP'," ");
                                                for($counter_time=0; $counter_time < count($remarks_options); $counter_time++){
                                                    echo '<option class="'.$disable_class.'" value="'.$remarks_options[$counter_time].'" '.$show.'>'.$remarks_options[$counter_time].'</option>';
                                                }
                                            ?>
                                        </select>

                                        <?php
                                            if($wdo_status == 'approved'){
                                                echo "<p class='m-3 text-success font-italic' style:'font-size: 15px'>remarks approved</p>"; 
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php 
                                        $counter++;
                                    }
                                ?>	
                            </tbody>	
                        </table>
                    </form>
                </div>
            </div>  
        </div>

            </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer --><footer class="sticky-footer">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright © Mary Grace Foods Inc. 2019</span>
					</div>
				</div>
			</footer>
			<!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
	

	<!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <script>
        function abc(){
            
            // Creating Our XMLHttpRequest object 
            var xhr = new XMLHttpRequest();
            
            // Making our connection  
            var url = 'https://jsonplaceholder.typicode.com/todos/1';
            xhr.open("GET", url, true);

            // function execute after request is successful 
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    console.log(this.responseText);
                }
            }
            // Sending our request 
            xhr.send();
        }
    </script>
    
</body>

</html>

<?php } ?>
