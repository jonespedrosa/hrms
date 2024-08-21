// Define an array with the keys in the order they are expected to appear in $_POST
$key_order = array("empno", "datefromto", "hour_from", "minute_from", "hour_to", "minute_to", "break", "remarks");

// Create an empty employee_change_sched array
$employee_change_sched = array();

// Loop through each $_POST value, and assign it to the corresponding property of $emp_details
foreach ($key_order as $key) {
    if (isset($_POST[$key]) && $_POST[$key] !== "" && $key != "change_sched" && $key != "dataTable_length") {
        $emp_details->$key = $_POST[$key];
    }
}

// Push $emp_details into the $employee_change_sched array, and reset $emp_details to a new stdClass
if (!empty((array)$emp_details)) {
    array_push($employee_change_sched, $emp_details);
    $emp_details = new stdClass();
}


<div class="d-flex flex-wrap justify-content-center">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Overtimes (Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php if($userlevel == 'master'){ 
                                echo $Totalpendingot;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1 ){ 
                                echo $Totalpendingot += $Totalpendingoth;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 2 ){ 
                                echo $Totalpendingot += $Totalpendingoth;

                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 4 ){ 
                                echo $Totalpendingot += $Totalpendingoth;	
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 4378 ){ 
                                echo $Totalpendingoth;												
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271 ){ 
                                echo $Totalpendingoth;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1331 ){ 
                                echo $Totalpendingot += $Totalpendingoth;													
                                                                        
                                // new added by jones
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 24 ){ 
                                echo  $Totalpendingoth;                                                   
                                                                        
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
                                echo $Totalpendingot += $Totalpendingoth;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
                                echo $Totalpendingot;
                                
                                }if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
                                echo $Totalpendingot;												
                                                                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
                                    OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5048 OR $_SESSION['empno'] == 885
                                    /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 /* END */ ){ 
                                echo $Totalpendingot;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76 ){
                                echo $Totalpendingot += $Totalpendingoth;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                echo $Totalpendingot;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
                                echo $Totalpendingot += $Totalpendingoth;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
                                echo $Totalpendingot;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 ){
                                echo $Totalpendingot += $Totalpendingoth;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460){
                                echo $Totalpendingot;
                                
                                }if($userlevel == 'mod'){
                                echo $Totalpendingot;
                    
                                } ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
                                    AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885
                                    /* HR */ AND $_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072 /* END */ ){
                    ?>	
                    <a href="pdf/approvals.php?ot=ot" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>    
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending OBPs (Per-Cut-off)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php if($userlevel == 'master'){ 
                                echo $Totalpendingobp;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1){ 
                                echo $Totalpendingobp += $Totalpendingobph ;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 2){ 
                                echo $Totalpendingobp += $Totalpendingobph;

                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 4){ 
                                echo $Totalpendingobp += $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){ 
                                echo $Totalpendingobph;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271){ 
                                echo $Totalpendingobph;

                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){ 
                                echo $Totalpendingobp += $Totalpendingobph;

                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 24){ //added by jones
                                echo $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){ 
                                echo $Totalpendingobp += $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 3071){ 
                                echo $Totalpendingobp;
                                
                                }if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221){ 
                                echo $Totalpendingobp;												
                                                                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
                                    OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5048 OR $_SESSION['empno'] == 885
                                    /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 /* END */){ 
                                echo $Totalpendingobp;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){
                                echo $Totalpendingobp += $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                echo $Totalpendingobp;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
                                echo $Totalpendingobp += $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88 ){
                                echo $Totalpendingobp;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
                                echo $Totalpendingobp += $Totalpendingobph;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460){
                                echo $Totalpendingobp;
                                
                                }if($userlevel == 'mod'){
                                echo $Totalpendingobp;
                    
                                } ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 
                    AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 
                    AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885
                    /* HR */ AND $_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072 /* END */){
                    ?>
                    <a href="pdf/approvals.php?obp=obp" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a> 
                <?php   
                    }
                    ?>	
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Leaves (Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php if($userlevel == 'master'){ 
                                echo $Totalpendingvl;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1 ){ 
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 2 ){ 
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 4 ){ 
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 4378 ){ 
                                echo $Totalpendingvlh;
                                
                                }if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 ){ 
                                echo $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1331 ){ 
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 24 ){  //new added jones
                                echo $Totalpendingvlh;


                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
                                echo $Totalpendingvl;
                                
                                }if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
                                echo $Totalpendingvl;
    
                                }if($_SESSION['empno'] == 271 OR $userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
                                    OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5048 OR $_SESSION['empno'] == 885
                                    /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 /* END */){ 
                                echo $Totalpendingvl;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                echo $Totalpendingvl;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
                                echo $Totalpendingvl;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
                                echo $Totalpendingvl += $Totalpendingvlh;
                                
                                }if($userlevel == 'ac' AND $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460){
                                echo $Totalpendingvl;
                                
                                }if($userlevel == 'mod'){
                                echo $Totalpendingvl;
                    
                                } ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
                                    AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885
                                    /* HR */ AND $_SESSION['empno'] != 1233 AND $_SESSION['empno'] != 2165 AND $_SESSION['empno'] != 4072 /* END */){
                    ?>	
                    <a href="pdf/approvals.php?vl=vl" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Concern (Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                <?php
                $datestart = '2023-02-09';
                $dateend = '2023-02-23';
                $emergency = 'Emergency time out';   
                $FPError = 'Fingerprint problem';  
                $BrokenOT = 'File Broken Sched OT'; 
                $forgot1 = 'Forgot to click no break';
                $forgot2 = 'Forgot/Wrong inputs of broken sched';
                $forgot3 = 'Forgot/Wrong time IN/OUT or break OUT/IN';
                $wrong = 'Wrong format/filing of OBP';
                $timeInterval = 'Not following time interval';
                $removeLogs = 'Remove Time Inputs';   
                $cancel1 = 'Cancellation of Overtime'; 
                $cancel2 = 'Cancellation of Leave';

                if($userlevel == 'master'){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('Hardware/Persona Malfunction','Sync/Network error','Wrong Computations' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($userlevel == 'ac' AND  $_SESSION['empno'] != 819 AND $_SESSION['empno'] != 4378 AND $_SESSION['empno'] != 1331 AND $_SESSION['empno'] != 24 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 1844 AND $_SESSION['empno'] != 1073 
                AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 76 AND $_SESSION['empno'] != 109 AND $_SESSION['empno'] != 71 
                AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 37 AND $_SESSION['empno'] != 53 AND $_SESSION['empno'] != 45 AND $_SESSION['empno'] != 69 AND $_SESSION['empno'] != 124 AND $_SESSION['empno'] != 2720 
                AND $_SESSION['empno'] != 63 AND $_SESSION['empno'] != 88 AND $_SESSION['empno'] != 97 AND $_SESSION['empno'] != 170 AND $_SESSION['empno'] != 38 AND $_SESSION['empno'] != 112 AND $_SESSION['empno'] != 254 AND $_SESSION['empno'] != 302 
                AND $_SESSION['empno'] != 460 AND $_SESSION['empno'] != 2094 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 4484 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1','$BrokenOT', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2') AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";        
                    
                }else if($_SESSION['empno'] == 1){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(4378,3336,3294,3235,3111,957,3071,3027,2221,1331,1073,271,107,24,4625,36) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 2){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE empno in(3177,4625,885,4378,3336,3294,3235,3111,957,3071,3027,2221,1331,1073,271,107,24,4625,5117,619,1975) AND status = 'Pending' AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 4){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(107) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval','$BrokenOT', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 4378){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(1348,1964,2957,4349,2111,2243,3332,3693,4000,4825,4826,4898) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 1331){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(819,109,76,71,167) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";


                }else if($_SESSION['empno'] == 24){ //jones added
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(819,109,76,71,167) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";


                }else if($_SESSION['empno'] == 1073){    
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (3,80,167,92, 170,168, 169,217,166) AND concern IN ('$emergency', '$FPError', '$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) OR empno = 1844 AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 4298){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (171,172) AND concern IN ('$emergency', '$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 3178){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (170) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 2684){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (166,173,165) AND concern IN ('$emergency', '$FPError','$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 3071){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(2203,2264) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$BrokenOT','$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 76){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(37,53,45,69,124,2720) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3','$BrokenOT', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 37 || $_SESSION['empno'] == 53 || $_SESSION['empno'] == 45 || $_SESSION['empno'] == 69 || $_SESSION['empno'] == 124 || $_SESSION['empno'] == 2720 ){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'SOUTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 109){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(63,88,97,170) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 63 || $_SESSION['empno'] == 88 || $_SESSION['empno'] == 97 || $_SESSION['empno'] == 170 ){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'MFO' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 819){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(38,112,254,302,4484,1562,4709) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 38 || $_SESSION['empno'] == 112 || $_SESSION['empno'] == 254 || $_SESSION['empno'] == 302 || $_SESSION['empno'] == 460 || $_SESSION['empno'] == 2094 || $_SESSION['empno'] == 4484){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError','$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'NORTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 71){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158) AND concern IN ('$emergency','$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 957){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(3167,1075,957,884) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                }else if($_SESSION['empno'] == 3235){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(159) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    

                }else if($_SESSION['empno'] == 3336){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(401,3780,4814,4888) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    
                }else if($_SESSION['empno'] == 3111){

                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(469,4408,5132,5184) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    
                }else if($_SESSION['empno'] == 2221){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(1262) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'"; 

                }else if($_SESSION['empno'] == 1844){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(2485) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";   

                }else if($_SESSION['empno'] == 885){
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";   

                }else{
                    $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1','$BrokenOT', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND userlevel = 'staff' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";
                }

                    $query0=$HRconnect->query($sql0);
                    $row0=$query0->fetch_array();                                      
                    
                    $totalconcerns = $row0['COUNT(*)'];
                        

                    echo $totalconcerns;

                            ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885){
                    ?>	
                    <a href="pdf/approvalsconcern.php" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Working Day Off (Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                                // From pdf hrms 
                                if($userlevel == 'master'){ 
                                    echo $Totalpendingwdo;
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 1 ){ 
                                    echo $Totalpendingwdo += $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 2 ){ 
                                    echo $Totalpendingwdo += $Totalpendingwdoh;

                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 4 ){ 
                                    echo $Totalpendingwdo += $Totalpendingwdoh;	
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 4378 ){ 
                                    echo $Totalpendingwdoh;												
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271 ){ 
                                    echo $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 1331 ){ 
                                    echo $Totalpendingwdo += $Totalpendingwdoh;													
                                                                            
                                    // new added by jones
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 24 ){ 
                                    echo  $Totalpendingwdoh;                                                   
                                                                            
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
                                    echo $Totalpendingwdo += $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
                                    echo $Totalpendingwdo;
                                    
                                    }if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
                                    echo $Totalpendingwdo;												
                                                                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
                                        OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5048 OR $_SESSION['empno'] == 885
                                        /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 /* END */ ){ 
                                    echo $Totalpendingwdo;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76 ){
                                    echo $Totalpendingwdo += $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                    echo $Totalpendingwdo;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
                                    echo $Totalpendingwdo += $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
                                    echo $Totalpendingwdo;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 ){
                                    echo $Totalpendingwdo += $Totalpendingwdoh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460){
                                    echo $Totalpendingwdo;
                                    
                                    }if($userlevel == 'mod'){
                                    echo $Totalpendingwdo;
                                    }
                            ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885){
                    ?>	
                    <a href="pdf/approvals.php?wdo=wdo" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Change Schedule (Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php
                                // From pdf hrms 
                                if($userlevel == 'master'){ 
                                    echo $Totalpendingcs;
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 1 ){ 
                                    echo $Totalpendingcs += $Totalpendingcsh;
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 2 ){ 
                                    echo $Totalpendingcs += $Totalpendingcsh;

                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 4 ){ 
                                    echo $Totalpendingcs += $Totalpendingcsh;	
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 4378 ){ 
                                    echo $Totalpendingcsh;												
                                    
                                    }if($userlevel == 'admin' AND $_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271 ){ 
                                    echo $Totalpendingcsh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 1331 ){ 
                                    echo $Totalpendingcs += $Totalpendingcsh;													
                                                                            
                                    // new added by jones
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 24 ){ 
                                    echo  $Totalpendingcs;                                                   
                                                                            
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
                                    echo $Totalpendingcs += $Totalpendingcsh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
                                    echo $Totalpendingcs;
                                    
                                    }if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
                                    echo $Totalpendingcs;												
                                                                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
                                        OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5048 OR $_SESSION['empno'] == 885
                                        /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 /* END */ ){ 
                                    echo $Totalpendingcs;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76 ){
                                    echo $Totalpendingcs += $Totalpendingcsh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 69 ){
                                    echo $Totalpendingcs;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
                                    echo $Totalpendingcs += $Totalpendingcsh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
                                    echo $Totalpendingcs;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 ){
                                    echo $Totalpendingcs += $Totalpendingcsh;
                                    
                                    }if($userlevel == 'ac' AND $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460){
                                    echo $Totalpendingcs;
                                    
                                    }if($userlevel == 'mod'){
                                    echo $Totalpendingcs;
                                    }
                            ?>
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885){
                    ?>	
                    <a href="pdf/approvals.php?cs=cs" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-bottom-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Pending Staff Pincode(Per Cut-off)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                <!-- PHP entry -->
                        </div>
                    </div>
                <?php 
                    if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 957 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 5048 AND $_SESSION['empno'] != 885){
                    ?>	
                    <a href="pdf/approvalsconcern.php" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                <?php   
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>