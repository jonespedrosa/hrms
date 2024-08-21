<?php


$sql = "SELECT * FROM user_info WHERE empno = '".$_SESSION['empno']."'";
$query=$HRconnect->query($sql);
$row=$query->fetch_array();
$user = $row['name'];
$empno= $row['empno'];
$secpass = $row['secpass'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];
$_SESSION['password2'] = $row['secpass'];


// QUERY TO GET THE PENDING CUT OFF DATE USING LEFT JOIN TO ACCESS OTHER INFO
$getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
ON si.empno = ui.empno
WHERE si.status = 'Pending' AND ui.empno = $empno;";
$querybuilder=$HRconnect->query($getDateSQL);
$rowCutOff=$querybuilder->fetch_array();

$cutfrom = $rowCutOff['datefrom'];
$cutto = $rowCutOff['dateto'];

// Changing Department or Cafe ----------------------------------------------------
if($userlevel != 'master' AND $userlevel != 'admin' AND $userlevel != 'ac' AND $_SESSION['empno'] != 4491 AND $_SESSION['empno'] != 6728 AND $_SESSION['empno'] != 5717 AND $_SESSION['empno'] != 5432 AND $_SESSION['empno'] != 5051 AND $_SESSION['empno'] != 3339
OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 3183 
OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 OR $_SESSION['empno'] == 3071 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 5928 
OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111 OR $_SESSION['empno'] == 24 OR $_SESSION['empno'] == 159 
OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 1075){

@$_SESSION['useridd'] = $row['userid'];

}else{

@$_SESSION['useridd'] = $_GET['branch'];

}   
// --------------------------------------------------------------------------------


// Modal for Ticketing System -----------------------------------------------------

$sql133 = "SELECT * FROM user WHERE username = '".$_SESSION['user']['username']."'";
$query133=$ORconnect->query($sql133);
$row133=$query133->fetch_array();

@$areatype = $row133['areatype'];
@$emailadd = $row133['emailadd'];

$userid = $_SESSION['useridd'];

if(isset($_GET["modal"]) == "modal") {

    echo "<script type='text/javascript'>alert('Ticket Successfully Created');
        window.location.href='home.php';
        </script>";


    }
// ---------------------------------------------------------------------------------




// Update Password -----------------------------------------------------------------

if(isset($_POST["update"]) == "update") {

$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$empno1 = $_POST['empno1'];


if ($password1 == $_SESSION['password2']){

  $update5 = " UPDATE user_info 
      SET secpass = '$password2'
      WHERE empno = '$empno1'";
        $HRconnect->query($update5);


    echo "<script type='text/javascript'>alert('Password Successfully Change');
        window.location.href='home.php';
        </script>";


}elseif($password1 != $password2){


     echo "<script type='text/javascript'>alert('Incorrect Password');
        window.location.href='home.php';
        </script>";
}

}
// ----------------------------------------------------------------------------------





// Change Date Submit ----------------------------------------

if(isset($_POST["submit"]) == "submit") {
    @$_SESSION['datedate11'] = date('Y-m-d', strtotime($_POST['datefrom']));
    @$_SESSION['datedate22'] = date('Y-m-d', strtotime($_POST['dateto']));
    
}

 @$backfrom = $_SESSION['datedate11'];
 @$backto = $_SESSION['datedate22'];

// -----------------------------------------------------------




// Newly Hired -----------------------------------------------------

$from = date("Y-m-01" ,strtotime("-1 month", strtotime(date("Y/m/d"))));
$to = date("Y-m-d");

$query = "SELECT COUNT(*) FROM user_info where approval = 'approve' and datehired between '$from' and '$to'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
             $NewlyHired = "$row[0]";
  }

// ----------------------------------------------------------------




// Total Employee  -----------------------------------------------

$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$TotalEmployeeAdmin = "$row[0]";
                                }



$query = "SELECT COUNT(*)FROM user_info where userid = '$userid'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$TotalEmployeeStaff = "$row[0]";
            }

// ---------------------------------------------------------------






// Total Employee  ---------------------------------------------------

$query = "SELECT COUNT(*) FROM user WHERE user_type = 'Kiosk' OR areatype in ('South','MFO','North')";
$result = mysqli_query($ORconnect, $query) or die(mysqli_error($ORconnect));
while ($row = mysqli_fetch_array($result)) {
$TotalBranch = "$row[0]";
  }

// ------------------------------------------------------------------




// Total Rendered Hours ----------------------------------------------

$query1 = "SELECT SUM(dayswork) FROM generated 
INNER JOIN user_info ON generated.empno = user_info.empno
WHERE  datefrom between '$backfrom' AND '$backto'";
$result1 = mysqli_query($HRconnect, $query1) or die(mysqli_error($HRconnect));
 while ($row1 = mysqli_fetch_array($result1)) {

    $TotalRendered = number_format(round($row1[0] * 8,2));
}

// ------------------------------------------------------------------



// Employment Status -------------------------------------------------------

// >>>>>>>>>>>>>>>>>>>>>>> Active Employee <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'active'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {  

$Activeall = "$row[0]"; 

}


$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'active' AND userid = '$userid'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {  

 $ActiveSingle = "$row[0]"; 

}


// >>>>>>>>>>>>>>>>>>>>>>> Inactive Employee <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'inactive'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$Inactiveall = "$row[0]";

}


$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'inactive' AND userid = '$userid'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$InactiveSingle = "$row[0]";

}


// >>>>>>>>>>>>>>>>>>>>>>> Resigned Employee <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'resigned'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$Resignall = "$row[0]";

}

$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND status = 'resigned' AND userid = '$userid'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$ResignSingle =  "$row[0]";

}



// >>>>>>>>>>>>>>>>>>>>>>> Resigned Employee <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<



$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND picture != ''";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$pincodeall =  "$row[0]";

 }



$query = "SELECT COUNT(*) FROM user_info WHERE approval = 'approve' AND picture != '' AND userid = '$userid'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {

$pincodeSingle = "$row[0]";

}




//------------------------------------------------------------------------------



// ------------------------------------------------------------------------


// Total Pending OT  -----------------------------------------------

if($userlevel == 'master'){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 1){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6472,6619,2525) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 2){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}


if($userlevel == 'admin' AND $_SESSION['empno'] == 4){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (107) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (98) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (1348,1964,6082,2957) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 204){

  $query = "SELECT COUNT(*) FROM overunder 
  JOIN user_info on overunder.empno = user_info.empno 
  WHERE overunder.otstatus = 'pending2' AND user_info.department = 'NORTH' AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingoth = "$row[0]";
  }
  }


if($_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus in ('pending','pending2') AND user_info.userid = '$userid' AND userlevel in ('master','ac','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}


//new added jones test

if($userlevel == 'ac' AND $_SESSION['empno'] == 24){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (241) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

$query = "SELECT COUNT(*) FROM overunder
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (2684,3178,1844,3877,4298) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}


if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3111){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending' AND user_info.userid = '$userid' AND userlevel in ('master','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 5928 
OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 
OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684
OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834
OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 
OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 /* END */ OR $_SESSION['empno'] == 3071 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid = '$userid' AND userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 76){

$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 76){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

}


if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (63,88,97,170) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

}

if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213) AND user_info.userlevel in ('master','mod','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingoth = "$row[0]";
}
}

}
if($userlevel == 'mod'){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('master','staff') AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}

//Sir Leon

if($empno == 1964){
$query = "SELECT COUNT(*) FROM overunder 
JOIN user_info on overunder.empno = user_info.empno 
WHERE overunder.otstatus = 'pending2' AND user_info.empno in (71) AND overunder.otdatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingot = "$row[0]";
}
}


// ------------------------------------------------------------------------


// Total Pending obp  -----------------------------------------------

if($userlevel == 'master'){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 1){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 2 ){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}


if($userlevel == 'admin' AND $_SESSION['empno'] == 4 ){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (107) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status in ('Pending2') AND user_info.userid in (98) AND userlevel in ('master','ac','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (1348,1964,6082,2957) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 204){

  $query = "SELECT COUNT(*) FROM obp 
  JOIN user_info on obp.empno = user_info.empno 
  WHERE obp.status = 'Pending2' AND user_info.department = 'NORTH' AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingobp= "$row[0]";
  }
}

if($_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status in ('Pending','Pending2') AND user_info.userid = '$userid' AND userlevel in ('master','ac','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}


if($_SESSION['empno'] == 1331){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193,218) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}


// new added jones
if($userlevel == 'ac' AND $_SESSION['empno'] == 24){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (241) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (3,80,92,164,165,166,167,168,169,171,172,173,214,215,216,217,225,236) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (2684,3178,1844,3877,4298) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}


if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3111){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 5928 
OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 
OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684 
OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834 
OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 
OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 /* END */ OR $_SESSION['empno'] == 3071 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid = '$userid' AND userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 76){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){
$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (37,53,2720,69,124,40) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}

}


if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (63,88,97,170) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}

}


if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){

$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213) AND user_info.userlevel in ('master','mod','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (254,302,112,2094,460,141) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobph = "$row[0]";
}
}

}


if($userlevel == 'mod'){
$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('master','staff') AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}

// Sir Leon
if($empno == 1964){
$query = "SELECT COUNT(*) FROM obp 
JOIN user_info on obp.empno = user_info.empno 
WHERE obp.status = 'Pending2' AND user_info.empno in (71) AND obp.datefromto BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingobp = "$row[0]";
}
}

// Total Pending leave  -----------------------------------------------

								
if($userlevel == 'master'){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 1){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 2){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}


if($userlevel == 'admin' AND $_SESSION['empno'] == 4){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (107) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (1348,1964,6082,2957) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}

if($userlevel == 'admin' AND $_SESSION['empno'] == 1348){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (1220,1233,2114,2165,3013,3778,4072,5583,3332) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,152,149,154,193) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 24){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (241) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

}

if($userlevel == 'ac' AND $_SESSION['empno'] == 204){

  $query = "SELECT COUNT(*) FROM vlform 
  JOIN user_info on vlform.empno = user_info.empno 
  WHERE vlform.vlstatus in ('pending','pending2') AND user_info.department ='NORTH' AND user_info.userlevel in ('mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingvl = "$row[0]";
  }
}
if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (2684,3178,1844,3877,4298) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}


if($_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 3111){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid = '$userid' AND userlevel in ('master','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}
}


if($_SESSION['empno'] == 271 OR $userlevel == 'ac' AND  $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 5928 
OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 
OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684
OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834 /* HR */ 
OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 OR $_SESSION['empno'] == 5834 OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 
OR $_SESSION['empno'] == 2165 OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20 /* END */ OR $_SESSION['empno'] == 3071){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid = '$userid' AND userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}
}


if($userlevel == 'ac' AND $_SESSION['empno'] == 76){

$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 76){ 
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (37,53,2720,69,124,40) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}

}


if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (63,88,97,170,63) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}

}

if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213) AND user_info.userlevel in ('master','mod','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}

if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (254,302,112,2094,460,141) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvlh = "$row[0]";
}
}

}


if($userlevel == 'mod'){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('master','staff') AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}
}

//Sir Leon 
if($empno == 1964){
$query = "SELECT COUNT(*) FROM vlform 
JOIN user_info on vlform.empno = user_info.empno 
WHERE vlform.vlstatus = 'pending' AND user_info.empno in (71) AND vlform.vldatefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
$result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
while ($row = mysqli_fetch_array($result)) {
$Totalpendingvl = "$row[0]";
}
}

// Total Pending WDO  -----------------------------------------------
$Totalpendingwdo=0;
$Totalpendingwdoh=0;
if($userlevel == 'master'){

    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }
  }
  

  // TotalPendingWDO + H means it will be added to each other to create a sum
  if($userlevel == 'admin' AND $_SESSION['empno'] == 1){
  
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdoh = "$row[0]";
    }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 2){
  
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdoh = "$row[0]";
    }
  }
  
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 4){
  
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (107) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdoh = "$row[0]";
    }
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){

    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus in ('pending2') AND user_info.userid in (98) AND userlevel in ('master','ac','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }

    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (1348,1964,6082,2957) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
    }
    }
    
    if($userlevel == 'ac' AND $_SESSION['empno'] == 204){
  
      $query = "SELECT COUNT(*) FROM working_dayoff 
      JOIN user_info on working_dayoff.empno = user_info.empno 
      WHERE working_dayoff.wdostatus = 'pending2' AND user_info.department = 'NORTH' AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
      $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
      while ($row = mysqli_fetch_array($result)) {
      $Totalpendingwdo = "$row[0]";
      }
      }
      

    if($_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271){
    
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus in ('pending','pending2') AND user_info.userid = '$userid' AND userlevel in ('master','ac','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdoh = "$row[0]";
    }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){ 
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'"; 
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = $row[0];
    }
  
    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdoh = $row[0];
    }
  }
  
  
  //new added jones test
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 24){
  
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (241) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){
  
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (3,80,92,164,165,166,167,168,169,171,172,173,214,215,216,217,236) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  
  $query = "SELECT COUNT(*) FROM working_dayoff
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (2684,3178,3338,1844,3877,4298) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdoh = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221){
  
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending' AND user_info.userid = '$userid' AND userlevel in ('master','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 5928 
  OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111 OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 
  OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
  OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684
  OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834 
  OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 
  OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20 /* END */ OR $_SESSION['empno'] == 3071){

    $query = "SELECT COUNT(*) FROM working_dayoff 
    JOIN user_info on working_dayoff.empno = user_info.empno 
    WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid = '$userid' AND userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingwdo = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 76){
  
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdoh = "$row[0]";
  }
  }
  
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (63,88,97,170) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdoh = "$row[0]";
  }
  }
  
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213) AND user_info.userlevel in ('master','mod','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdoh = "$row[0]";
  }
  }
  
  }
  
  
  if($userlevel == 'mod'){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('master','staff') AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  }

  //Engr
  if($empno == 1964){
  $query = "SELECT COUNT(*) FROM working_dayoff 
  JOIN user_info on working_dayoff.empno = user_info.empno 
  WHERE working_dayoff.wdostatus = 'pending2' AND user_info.empno in (71) AND working_dayoff.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingwdo = "$row[0]";
  }
  }

  // ------------------------------------------------------------------------


  // Total Pending CS  -----------------------------------------------
	$Totalpendingcs=0;
	$Totalpendingcsh=0;
	if($userlevel == 'master'){

    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }
  }
  

  // TotalPendingCS+ H means it will be added to each other to create a sum
  if($userlevel == 'admin' AND $_SESSION['empno'] == 1){
  
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (96) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcsh = "$row[0]";
    }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 2){
  
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (156,230) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcsh = "$row[0]";
    }
  }
  
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 4){
  
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (122) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }
    
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (107) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcsh = "$row[0]";
    }
	}
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){

    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status in ('pending2') AND user_info.userid in (98) AND userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }

  
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (1348,1964,6082,2957) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcsh = "$row[0]";
    }
    }
    
  if($userlevel == 'ac' AND $_SESSION['empno'] == 204){

    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.department = 'NORTH' AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
    }
    }

    if($_SESSION['empno'] == 1348 OR $_SESSION['empno'] == 271){
    
    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status in ('pending','pending2') AND user_info.userid = '$userid' AND userlevel in ('master','ac','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcsh = "$row[0]";
    }
	}
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,150,152,149,154,193) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (38,63,76,97,109,124,819,45,1404,3183) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcsh = "$row[0]";
  }
  }
  
  
  //new added jones test
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 24){
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (241) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (92,169,80,164,168,236,167) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  
  $query = "SELECT COUNT(*) FROM change_schedule
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (2684,3178,3338,1844,3877,4298) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcsh = "$row[0]";
  }
  }
  
  
  if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 3111){
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending' AND user_info.userid = '$userid' AND userlevel in ('master','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 5928 OR $_SESSION['empno'] == 3336 
  OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178
  OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
  OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684
  OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834 
  OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 
  OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20 /* END */ OR $_SESSION['empno'] == 3071){

    $query = "SELECT COUNT(*) FROM change_schedule 
    JOIN user_info on change_schedule.empno = user_info.empno 
    WHERE change_schedule.cs_status = 'pending2' AND user_info.userid = '$userid' AND userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
    $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
    while ($row = mysqli_fetch_array($result)) {
    $Totalpendingcs = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 76){
  
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (37,53,2720,69,124,40) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcsh = "$row[0]";
  }
  }
  
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (63,88,97,170) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcsh = "$row[0]";
  }
  }
  
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.userid in (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213) AND user_info.userlevel in ('master','mod','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (254,302,112,2094,460,141) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcsh = "$row[0]";
  }
  }
  
  }
  
  
  if($userlevel == 'mod'){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending' AND user_info.userid = '$userid' AND user_info.userlevel in ('master','staff') AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  }
   //Engr
  if($empno == 1964){
  $query = "SELECT COUNT(*) FROM change_schedule 
  JOIN user_info on change_schedule.empno = user_info.empno 
  WHERE change_schedule.cs_status = 'pending2' AND user_info.empno in (71) AND change_schedule.datefrom BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $Totalpendingcs = "$row[0]";
  }
  }

  // ------------------------------------------------------------------------

  // STAFF PINCODE
  
// Total Pending Pincode -----------------------------------------------
$startDatePincode = 0;
$endDatePincode = 0;
$TotalpendingPincode = 0;
if($userlevel == 'master'){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending' )
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 1){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (96) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  
  $query = "SELECT COUNT(*) FROM sched_time 
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,5356,5584,2203,4647,5834,6207,6619,2525) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 2){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (156,230) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (5928,3294,271,3027,1073,2221,107,3071,1331,24,5752,3111,3336,4378,4827,5975,885,4625,5356,5584,2203,4647,5834,6207,3745,5053,5952,5972,6041,6472,6619,5612) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 4){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (122) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }

  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (107) 
  AND sched_time.datefromto
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }

  if($userlevel == 'ac' AND $_SESSION['empno'] == 4378){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (1348,1964,6082,2957) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  if($userlevel == 'admin' AND $_SESSION['empno'] == 1348){
  
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (1220,1233,2114,2165,3013,3778,4072,5583,3332) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1331){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (185,11,28,51,52,53,54,55,56,58,59,62,63,95,101,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,12,13,14,15,16,17,18,19,20,22,27,50,81,103,139,158,151,152,149,154,193) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (38,63,76,97,109,124,819,45,1404,3183) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  if($userlevel == 'ac' AND $_SESSION['empno'] == 24){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  on sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (241) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  }
  
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (3,80,92,164,165,166,167,168,169,171,172,173,214,215,216,217,236) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }

  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (2684,3178,3338,1844,3877,4298) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  
  if($_SESSION['empno'] == 2221 OR $_SESSION['empno'] == 107 OR $_SESSION['empno'] == 3111){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid = '$userid' 
  AND userlevel in ('master','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  }
  
  
  if($_SESSION['empno'] == 271 OR $userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 
  OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 6619 OR $_SESSION['empno'] == 4647 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 5928 OR $_SESSION['empno'] == 3336 
  OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 5752 OR $_SESSION['empno'] == 3027 OR $_SESSION['empno'] == 3178
  OR $_SESSION['empno'] == 5361 OR $_SESSION['empno'] == 3178 OR $_SESSION['empno'] == 5515 
  OR $_SESSION['empno'] == 5452 OR $_SESSION['empno'] == 4811 OR $_SESSION['empno'] == 2684
  OR $_SESSION['empno'] == 2684 OR $_SESSION['empno'] == 5975 OR $_SESSION['empno'] == 885 OR $_SESSION['empno'] == 957 OR $_SESSION['empno'] == 1075 OR $_SESSION['empno'] == 3183 OR $_SESSION['empno'] == 6082 OR $_SESSION['empno'] == 5834
  OR $_SESSION['empno'] == 5584 OR $_SESSION['empno'] == 6207 /* HR */ OR $_SESSION['empno'] == 1233 OR $_SESSION['empno'] == 5583 OR $_SESSION['empno'] == 2165 
  OR $_SESSION['empno'] == 4072 OR $_SESSION['empno'] == 3332 OR $_SESSION['empno'] == 6538 OR $_SESSION['empno'] == 229 OR $_SESSION['empno'] == 189 OR $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 124 OR $_SESSION['empno'] == 2720 OR $_SESSION['empno'] == 3685 OR $_SESSION['empno'] == 53 OR $_SESSION['empno'] == 69 OR $_SESSION['empno'] == 37 OR $_SESSION['empno'] == 40 OR $_SESSION['empno'] == 20 /* END */ OR $_SESSION['empno'] == 3071){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid = '$userid' 
  AND userlevel
  IN ('master','mod','staff') 
  AND sched_time.datefromto
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
    $TotalpendingPincode = "$row[0]";
  } 
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 76){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (11,28,51,52,53,54,55,56,58,59,62,63,95,101,153,150,152,149,199,206,235,243,196,40) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 45 OR $_SESSION['empno'] == 76){ 
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (37,53,2720,69,124) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97 OR $_SESSION['empno'] == 63 OR $_SESSION['empno'] == 170 OR $_SESSION['empno'] == 88){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (19,21,23,24,25,26,46,47,48,49,57,60,61,64,65,66,102,182,154,194,218) 
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 109 OR $_SESSION['empno'] == 97){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno in (63,88,97,170) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  }
  
  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819 OR $_SESSION['empno'] == 254 OR $_SESSION['empno'] == 302 OR $_SESSION['empno'] == 112 OR $_SESSION['empno'] == 2094 OR $_SESSION['empno'] == 460 ){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid 
  IN (12,13,14,15,16,17,18,20,22,27,50,81,103,139,158,151,193,213)
  AND user_info.userlevel 
  IN ('master','mod','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }

  if($userlevel == 'ac' AND $_SESSION['empno'] == 38 OR $_SESSION['empno'] == 819){
  $query = "SELECT COUNT(*) FROM sched_time 
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.empno 
  IN (254,302,112,2094,460,141) 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode_2 = "$row[0]";
  }
  }
  
  }
  
  if($userlevel == 'mod' AND $empno != 2230 AND $empno != 4292 AND $empno != 4813 AND $empno != 5241){
  $query = "SELECT COUNT(*) FROM sched_time
  JOIN user_info 
  ON sched_time.empno = user_info.empno 
  WHERE (sched_time.m_in_status = 'Pending' OR sched_time.m_o_status = 'Pending'  OR sched_time.a_in_status = 'Pending'  OR sched_time.a_o_status = 'Pending')
  AND user_info.userid = 1 
  AND user_info.userlevel 
  IN ('master','staff') 
  AND sched_time.datefromto 
  BETWEEN '".$cutfrom."' AND '".$cutto."'";
  $result = mysqli_query($HRconnect, $query) or die(mysqli_error($HRconnect));
  while ($row = mysqli_fetch_array($result)) {
  $TotalpendingPincode = "$row[0]";
  }
  }								
  
  // ------------------------------------------------------------------------
?>