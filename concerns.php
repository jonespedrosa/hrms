
<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
//entry.php  
session_start();

if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
}
?>  

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    
    <style>
    select{
    text-align-last:center;
    }
    
    </style>
    
    <style> 
        table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        padding: 1.5%;
        }

        input.largerCheckbox { 
            width: 25px; 
            height: 25px; 
        }
        
        input[type=checkbox] + label {
          color: #8D9099;
          font-style: italic;
        } 
        input[type=checkbox]:checked + label {
          color: #0000FF;
          font-style: normal;
        } 
    </style>
    
    
</head>

<body class="bg-gradient-muted">

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <a href="index.php" class="navbar-brand">
        <img src="images/logoo.png" height="35" alt=""> <i style="color:#7E0000;font-family:Times New Roman, cursive;font-size:120%;">Mary Grace Café</i>
    </a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto text-center">
            <a href="login.php" class="nav-item nav-link" style="font-family:Times New Roman, cursive;font-size:120%;">Login</a>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Begin Page Content -->
    <div class="container p-3 my-3">
        <div class="row justify-content-center">
            <div class="col-lg-8"> 
                <div class="card border-0 shadow-lg">

                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h5 class="card-title m-0 text-primary">Add Concerns</h5>  
                    </div>

                    <div class="card-body p-0">                                    
                        <?php
                        if (isset($_GET["concern"]) == "concern")
                            $empno = $_GET['empno'];

                        //QUERY TO GET THE EMPLOYEE NAME                               
                        $sql6 = "SELECT * FROM user_info where empno = '$empno'";
                        $query6 = $HRconnect->query($sql6);
                        $row6 = $query6->fetch_array();
                        $EmpName = $row6['name'];

                        // QUERY TO GET THE PENDING CUT OFF DATE USING LEFT JOIN TO ACCESS OTHER INFO
                        $getDateSQL = "SELECT si.datefrom, si.dateto FROM user_info ui LEFT JOIN sched_info si 
                                ON si.empno = ui.empno
                                WHERE si.status = 'Pending' AND ui.empno = $empno ORDER BY `datefrom` ASC";
                        $querybuilder = $HRconnect->query($getDateSQL);
                        $rowCutOff = $querybuilder->fetch_array();

                        //SET THE RANGE OF DATE FOR FILING OF DTR CONCERNS
                        // $mindate = trim($_GET['cutfrom']);
                        // $maxdate = trim($_GET['cutto']);
                        
                        $mindate = $rowCutOff['datefrom'];
                        $maxdate = $rowCutOff['dateto']; {
                            ?>
                            <form action="concerns.php" method="get" class="p-3">                  						
                                <div class="header d-flex flex-row align-items-center justify-content-between">
                                    <a class="btn border-0 btn-outline-primary btn-sm" href="index.php?empno=<?php echo $empno; ?>&SubmitButton=Submit&cutfrom=<?php echo $mindate; ?>&cutto=<?php echo $maxdate; ?>"><i class="fa fa-angle-left" aria-hidden="true" ></i> Back</a>  
                                    <a class="btn border-0 btn-outline-primary btn-sm" href="pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=<?php echo $empno; ?>&cutfrom=<?php echo $mindate; ?> &cutto=<?php echo $maxdate; ?>">View Filed Concerns <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 mb-2">
                                        <label><small> Employee Name </small></label>
                                        <input type ="hidden" name="concern" value = "concern" >                                   
                                        <input type ="hidden" name="empno" value = "<?php echo $empno; ?>" >
                                        <input class="form-control form-control-sm" type="text" value="<?php echo $EmpName; ?>" readonly>
                                    </div>
                                
                                    <div class="col-xl-6 col-lg-6 mb-2">
                                        <label><small>Concern Date</small></label>
                                        <?php

                                        //SELECTION OF DATE OF CONCERN 
                                        if (isset($_GET['date']))
                                            $cdate = $_GET['date']; {
                                            ?>
                                            <input class="form-control form-control-sm" type="date" name="date" placeholder = "YYYY/MM/DD" min="<?php echo $mindate; ?>" value ="<?php echo $cdate; ?>" max="<?php echo $maxdate; ?>" required>

                                        <?php
                                        }
                                        ?>
                                    </div>	
                                    <div class="col-xl-12 col-lg-6 mb-1">
                                        <label><small>Concern</small></label>
                                        
                                        <select id="inputConcern" class="form-control form-control-sm"  name="dtrconcern" required>
                                            <option selected><?php
                                            if (isset($_GET['dtrconcern'])) {
                                                $getConcern = $_GET['dtrconcern'];
                                                if ($getConcern == 'Forgot to click Halfday') {
                                                    echo 'Forgot to click Halfday';
                                                } else if ($getConcern == 'Forgot/Wrong inputs of broken sched') {
                                                    echo 'Forgot/Wrong inputs of broken sched';
                                                } else if ($getConcern == 'Forgot/Wrong time IN/OUT or break OUT/IN') {
                                                    echo 'Forgot/Wrong time IN/OUT or break OUT/IN';
                                                } else if ($getConcern == 'Wrong format/filing of OBP') {
                                                    echo 'Wrong format/filing of OBP';
                                                } else if ($getConcern == 'Not following time interval') {
                                                    echo 'Not following time interval';
                                                } else if ($getConcern == 'Remove Time Inputs') {
                                                    echo 'Remove Time Inputs';
                                                } else if ($getConcern == 'Cancellation of Overtime') {
                                                    echo 'Cancellation of Overtime';
                                                } else if ($getConcern == 'Cancellation of Leave') {
                                                    echo 'Cancellation of Leave';
                                                } else if ($getConcern == 'Sync/Network error') {
                                                    echo 'Sync/Network error';
                                                } else if ($getConcern == 'Wrong Computations') {
                                                    echo 'Wrong Computations';
                                                } else if ($getConcern == 'Emergency time out') {
                                                    echo 'Emergency time out';
                                                } else if ($getConcern == 'Hardware/Persona Malfunction') {
                                                    echo 'Hardware/Persona Malfunction';
                                                } else if ($getConcern == 'Fingerprint problem') {
                                                    echo 'Fingerprint problem';
                                                } else if ($getConcern == 'File Broken Sched OT') {
                                                    echo 'File Broken Sched OT';
                                                }
                                            }
                                            ?></option>
                                            <option>Forgot to click Halfday</option>
                                            <option>Forgot/Wrong inputs of broken sched</option>
                                            <option>Forgot/Wrong time IN/OUT or break OUT/IN</option>
                                            <option>Wrong format/filing of OBP</option>
                                            <option>Not following time interval</option>
                                            <option>Remove Time Inputs</option>
                                            <option>Cancellation of Overtime</option> 
                                            <option>Cancellation of Leave</option>                                      
                                            <option>Sync/Network error</option>
                                            <option>Wrong Computations</option>
                                            <option>Emergency time out</option>
                                            <option>Hardware/Persona Malfunction</option>
                                            <option>Fingerprint problem</option>
                                            <option>File Broken Sched OT</option>
                                        </select>
                                    </div>													                              
                                </div>
                            
                                <div class="d-flex flex-row-reverse">
                                    <div class="p-1">
                                        <input type = "submit" class="btn btn-sm border-0 btn-primary" value="Proceed">                                    
                                                                                                
                                    </div>                                     
                                </div> 
                                <hr class="mb-0">
                            </form>
                    <?php
                        }
                        ?>
                    
                    <?php
                    //IF THE SELECTED CONCERN IS Remove Time inputs 
                    if ($_GET["dtrconcern"] == 'Remove Time Inputs') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="p-4">                            
                                <div class="row">

                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];


                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array(); {

                                         ?>                     
                                        <div class="col-xl-6 col-lg-6">
                                            <label><small>Please Select What Time Inputs to Remove </small></label>
                                            <br>
                                            <select name="inputs" required>
                                                <option></option>
                                                <option>Time In</option>
                                                <option>Break Out</option>
                                                <option>Break In</option>
                                                <option>Time Out</option>
                                                <option>Broken Sched In</option>
                                                <option>Broken Sched Out</option>
                                                <option>All Regular Inputs</option>
                                                <option>All Broken Sched Inputs</option>
                                            </select>
                                    
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" id="othours" name="othours" value=" ">
                                            <input type="hidden" id="otdate" name="otdate" value=" ">
                                            <input type="hidden" id="actualIN" name="actualIN" value=" ">
                                            <input type="hidden" id="actualOUT" name="actualOUT" value=" ">
                                            <input type="hidden" id="actualbrkOUT" name="actualbrkOUT" value=" ">
                                            <input type="hidden" id="actualbrkIN" name="actualbrkIN" value=" ">
                                            <input type="hidden" id="newbrkOUT" name="newbrkOUT" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newbrkIN" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newIN" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newOUT" value=" ">

                                        <?php
                                     }
                                     ?>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6">
                                        <label class="mb-0"><small>Attachment 1 (<span class="text-danger"> IR/HYO FORM </span>)</small></label><br>
                                        <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                        <input type="file" accept="image/*,video/*" name="attachment2" value="i.jpg" style="display:none" />
                                    </div>
                                    <div class="col-xl-6 col-lg-6 mt-2">
                                    
                                    </div>
        
                                    <div class="col-xl-12 col-lg-12 mt-2">
                                        <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                        <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                        <br>
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                        <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                Any falsification of information in this regard may form ground for disciplinary action up to and including dismissal.</em></small></label>
                                        </div>
                                    </div>        
                                </div>
    
                                <div class="d-flex flex-row-reverse mt-3">
                                    <div class="p-1">
                                        <?php

                                        //QUERY EMPLOYEE NAME
                                        $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                        $query8 = $HRconnect->query($sql8);
                                        $row8 = $query8->fetch_array();
                                        $Name = $row8['name'];

                                        ?>
                                        <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                        <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                        <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                        <input type ="hidden" name="dtr" value = "concerns" >
                                        <input type ="hidden" name="filed" value = "done" >
                                        <input type ="hidden" name="d" value = "2" >
                                        <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                    
                                    </div>                                     
                                </div> 
                                           
                            </div>
                        </form>        

                    <?php
                    }
                    ?>

                    <?php
                    //IF THE SELECTED CONCERN IS Wrong Computations 
                    if ($_GET["dtrconcern"] == 'Wrong Computations') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="p-4">                            
                                <div class="row">

                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];


                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array(); {

                                         ?>                     
                                        <div class="col-xl-6 col-lg-6">
                                            <label><small>Cut-off Date:</small></label>
                                            <br>
                                            <label><bold><?php echo $mindate; ?> to <?php echo $maxdate; ?></bold></label>
                                    
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" id="othours" name="othours" value=" ">
                                            <input type="hidden" id="otdate" name="otdate" value=" ">
                                            <input type="hidden" id="actualIN" name="actualIN" value=" ">
                                            <input type="hidden" id="actualOUT" name="actualOUT" value=" ">
                                            <input type="hidden" id="actualbrkOUT" name="actualbrkOUT" value=" ">
                                            <input type="hidden" id="actualbrkIN" name="actualbrkIN" value=" ">
                                            <input type="hidden" id="newbrkOUT" name="newbrkOUT" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newbrkIN" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newIN" value=" ">
                                            <input type="hidden" id="newbrkIN" name="newOUT" value=" ">
                                            <input type="hidden" id="newbrkIN" name="concernReason" value=" ">

                                        <?php
                                     }
                                     ?>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6">
                                        <label><small>Please Select Computations </small></label>
                                        <br>
                                        <select name="computations" required>
                                            <option></option>
                                            <option>Number of Working Days</option>
                                            <option>OT (hours)</option>
                                            <option>Working Off</option>
                                            <option>Holiday</option>
                                            <option>Regular Holiday</option>
                                            <option>Special Holiday</option>
                                            <option>Night Different Holiday</option>
                                            <option>Leave</option>
                                            <option>Late</option>
                                            <option>Undertime</option>
                                        </select>
                                    </div>
                                
                                    <div class="col-xl-12 col-lg-12 mt-2">                                                             
                                        <div class="form-check mb-1 ml-1">
                                            <br>
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                            <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                Any falsification of information in this regard may form ground for 
                                                disciplinary action up to and including dismissal.</em></small>
                                            </label>
                                        </div>
                                    </div>        
                                </div>
    
                                <div class="d-flex flex-row-reverse mt-3">
                                    <div class="p-1">
                                        <?php

                                        //QUERY EMPLOYEE NAME
                                        $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                        $query8 = $HRconnect->query($sql8);
                                        $row8 = $query8->fetch_array();
                                        $Name = $row8['name'];

                                        ?>
                                        <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                        <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                        <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                        <input type ="hidden" name="dtr" value = "concerns" >
                                        <input type ="hidden" name="filed" value = "done" >
                                        <input type ="hidden" name="d" value = "2" >
                                        <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                    </div>                                     
                                </div> 
                                           
                            </div>
                        </form>        

                        <?php
                    }
                    ?>

                        <?php
                        //IF THE SELECTED CONCERN IS FORGOT TO CLICK NO BREAK 
                        if ($_GET["dtrconcern"] == 'Forgot to click Halfday') {
                            $dtrconcern = $_GET["dtrconcern"];

                            ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff was not able to check "No Break" before tapping his/her fingerprint for time out.</small></p>
                                </div>   

                                <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                                <div class="p-4">                            
                                    <div class="row">

                                        <?php
                                        if (isset($_GET['date']))
                                            //GET the employee number and date
                                            $cdate1 = $_GET['date'];
                                        $empID = $_GET['empno'];


                                        //query to get the employees time inputs
                                        $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                        $query7 = $HRconnect->query($sql7);
                                        $row7 = $query7->fetch_array(); {

                                            ?>   

                                        <div class="col-xl-6 col-lg-6">
                                            <label><small>Captured time inputs</small></label>
                                    
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" id="othours" name="othours" value=" ">
                                            <input type="hidden" id="otdate" name="otdate" value=" ">
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualIN" name="actualIN" value="<?php if ($row7['M_timein'] != '') {
                                                echo date('H:i', strtotime($row7['M_timein']));
                                            } else if ($row7['M_timein'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- BREAK OUT FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualbrkOUT" name="actualbrkOUT" value="<?php if ($row7['M_timeout'] == 'No Break') {
                                                echo $row7['M_timeout'];
                                            } else if ($row7['M_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['M_timeout']));
                                            } else if ($row7['M_timeout'] == '' or $row7['M_timeout'] != 'No Break') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- BREAK IN FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualbrkIN" name="actualbrkIN" value="<?php if ($row7['A_timein'] == 'No Break') {
                                                echo $row7['A_timein'];
                                            } else if ($row7['A_timein'] != '') {

                                                echo date('H:i', strtotime($row7['A_timein']));
                                            } else if ($row7['A_timein'] == '' or $row7['A_timein'] != 'No Break') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- TIME OUT FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualOUT" name="actualOUT" value="<?php if ($row7['A_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['A_timeout']));
                                            } else if ($row7['A_timeout'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>
                                   

                                        <?php
                                        }
                                        ?>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6">
                                        <label><small>Requested time inputs</small></label>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['M_timein'] != '') {
                                            echo date('H:i', strtotime($row7['M_timein']));
                                        } else if ($row7['M_timein'] == '') {
                                            echo '';
                                        } ?>" name="newIN" required>
                                        <input class="form-control form-control-sm mb-1" type="text" value="No Break" name="newbrkOUT" id="newbrkOUT"  readonly>
                                        <input class="form-control form-control-sm mb-1" type="text" value="No Break" name="newbrkIN" id="newbrkIN" readonly>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['A_timeout'] != '') {
                                            echo date('H:i', strtotime($row7['A_timeout']));
                                        } else if ($row7['A_timeout'] == '') {
                                            echo '';
                                        } ?>" name="newOUT" required>
                                   
                                    </div>
                                    <div class="col-xl-6 col-lg-6 mt-2">
                                        <label class="mb-0"><small>Attachment 1 (<span class="text-danger"> IR/HYO FORM </span>)</small></label><br>
                                        <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6 mt-2">
                                        <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOG BOOK PICTURE/CCTV </span>)</small></label><br>
                                        <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                    </div>
    
                                    <div class="col-xl-12 col-lg-12 mt-2">
                                        <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                        <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                        <div class="form-check mb-1 ml-1">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                            <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                Any falsification of information in this regard may form ground for 
                                                disciplinary action up to and including dismissal.</em></small>
                                            </label>
                                        </div>
                                    </div>        
                                </div>
    
                                <div class="d-flex flex-row-reverse mt-3">
                                    <div class="p-1">
                                        <?php

                                        //QUERY EMPLOYEE NAME
                                        $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                        $query8 = $HRconnect->query($sql8);
                                        $row8 = $query8->fetch_array();
                                        $Name = $row8['name'];

                                        ?>
                                        <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                        <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                        <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                        <input type ="hidden" name="dtr" value = "concerns" >
                                        <input type ="hidden" name="filed" value = "done" >
                                        <input type ="hidden" name="d" value = "2" >
                                        <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                    </div>                                     
                                </div> 
                                           
                            </div>
                        </form>        

                    <?php
                        }
                        ?>

                    <?php
                    //IF THE SELECTED CONCERN IS FORGOT TO CLICK BROKEN SCHEDULE 
                    if ($_GET["dtrconcern"] == 'Forgot/Wrong inputs of broken sched') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <div>
                                <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                <p style="padding-left: 23px; padding-right: 23px;"><small>The staff forgot to check "BROKEN SCHEDULE" for Gen Meet/Gen Cleaning. It is only applicable if you already completed 4 time inputs for that shift.</small></p>
                            </div>  
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="p-4">                              
                                <div class="row">
                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];

                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array();

                                     $genmIN = date('H:i', strtotime($row7['timein4']));
                                     $genmOUT = date('H:i', strtotime($row7['timeout4'])); {

                                         ?>   
                                        <center><p><i><b class="text-danger">Note!</b> always remember that our system is using millitary time please use correct time format (<b class="text-success"> 00:00</b> ) to prevent errors.</p></i></center>
                                
                                        <div class="col-xl-6 col-lg-6 mb-0">
                                    
                                            <label class="mb-0"><small>Captured time inputs</small></label>                                   
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" id="actualbrkOUT" name="actualbrkOUT" value="No Break">
                                            <input type="hidden" id="actualbrkIN" name="actualbrkIN" value="No Break">
                                            <input type="hidden" id="newbrkOUT" name="newbrkOUT" value="No Break">
                                            <input type="hidden" id="newbrkIN" name="newbrkIN" value="No Break">
                                            <input type="hidden" id="othours" name="othours" value=" ">
                                            <input type="hidden" id="otdate" name="otdate" value=" ">
                                            <br>
                                            <small>Broken Sched IN: </small>
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualIN" name="actualIN" value="<?php if ($row7['timein4'] != '') {
                                                echo date('H:i', strtotime($row7['timein4']));
                                            } else if ($row7['timein4'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- TIME OUT FROM DATABASE -->
                                            <small>Broken Sched OUT: </small>
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualOUT" name="actualOUT" value="<?php if ($row7['timeout4'] != '') {
                                                echo date('H:i', strtotime($row7['timeout4']));
                                            } else if ($row7['timeout4'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>
                                   

                                        <?php
                                     }
                                     ?>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6">
                                        <label class="mb-0"><small>Requested time inputs</small></label>
                                        <br>
                                        <small>Broken Sched IN: </small>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['timein4'] != '') {
                                            echo date('H:i', strtotime($row7['timein4']));
                                        } else if ($row7['timein4'] == '') {
                                            echo '';
                                        } ?>" name="newIN" required>
                                        <small>Broken Sched OUT: </small>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['timeout4'] != '') {
                                            echo date('H:i', strtotime($row7['timeout4']));
                                        } else if ($row7['timeout4'] == '') {
                                            echo '';
                                        } ?>" name="newOUT" required>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 mt-2">
                                        <label class="mb-0"><small>Attachment 1(<span class="text-danger">IR/HYO FORM</span>)</small></label><br>
                                        <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6 mt-2">
                                        <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOG BOOK PICTURE/CCTV </span>)</small></label><br>
                                        <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                    </div>
    
                                    <div class="col-xl-12 col-lg-12 mt-2">
                                        <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                        <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Inputs Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                        <div class="form-check mb-1 ml-1">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                            <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                Any falsification of information in this regard may form ground for 
                                                disciplinary action up to and including dismissal.</em></small>
                                            </label>
                                        </div>
                                    </div>        
                                </div>
    
                                <div class="d-flex flex-row-reverse mt-3">
                                    <div class="p-1">
                                        <?php

                                        //QUERY EMPLOYEE NAME
                                        $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                        $query8 = $HRconnect->query($sql8);
                                        $row8 = $query8->fetch_array();
                                        $Name = $row8['name'];

                                        ?>
                                        <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                        <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                        <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                        <input type ="hidden" name="dtr" value = "concerns" >
                                        <input type ="hidden" name="filed" value = "done" >
                                        <input type ="hidden" name="d" value = "2" >
                                        <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                    </div>                                     
                                </div> 
                                           
                            </div>
                        </form> 

                    <?php
                    }
                    ?>    

                    <?php
                    //IF THE SELECTED CONCERN IS CANCELLATION OF OVERTIME 
                    if ($_GET["dtrconcern"] == 'Cancellation of Overtime') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <div>
                                <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                <p style="padding-left: 23px; padding-right: 23px;"><small>The staff wants to cancel his/her approved overtime possibly due to wrong filing or wrong input of details.</small></p>
                            </div> 
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="a-form p-4">                              
                                <div class="row">
                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];

                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `overunder` where empno = $empID and otstatus = 'approved' and otdatefrom = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array(); {

                                         ?>          

                                            <?php
                                            if ($query7->num_rows == 0) {

                                                ?>
                                                <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                                                    <div class="thanks toast fade show" style="position: fixed; bottom: 20px; right: 5px;">
                                                        <div class="toast-header bg-danger">
                                                          <h4 class="mr-auto my-0 text-white"><i class="fa fa-times-circle" aria-hidden="true"></i> Warning</h5>
                                                         <small class="text-white">just now</small>
                                                        </div>
                                                        <div class="toast-body">
                                                          <b class="text-danger">There is NO FILED OVERTIME for the selected Date, or the filed overtime is still in pending status.</b>. You may cancel your overtime or ask your immediate superior to cancel your filed overtime.
                                                        </div>
                                                    </div>
                                                </div>								
                                                <?php
                                            } else {
                                                ?>
                                    
                                                <div class="input-group input-group-sm mb-3" hidden>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Date</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otdate" name="otdate" value="<?php echo $row7['otdatefrom']; ?>" readonly />																														
                                                </div>																	
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Number of Hours: </span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="othrs" name="othrs" value="<?php echo $row7['othours']; ?>" readonly />
                                        
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Status: </span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otstatus" name="otstatus" value="<?php echo $row7['otstatus']; ?>" readonly />
                                                </div>
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Reason:</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otreason" name="otreason" value="<?php echo $row7['otreason']; ?>" readonly />
                                                </div>
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Partial Approver</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otapprove1" name="otapprove1" value="<?php echo $row7['p_approver']; ?>" readonly />
                                                </div>
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Final Approver</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otapprove2" name="otapprove2" value="<?php echo $row7['approver']; ?>" readonly />
                                                </div>

                                       
                                            <div class="col-xl-12 col-lg-12 mt-2">
                                                <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                                <input type="hidden" name="othours" value="<?php echo $row7['othours']; ?>">
                                                <input type="hidden" name="otdate" value="<?php echo $cdate1; ?>">
                                                <input type="hidden" name="actualIN" value=" ">
                                                <input type="hidden" name="actualbrkOUT" value=" ">
                                                <input type="hidden" name="actualbrkIN" value=" ">
                                                <input type="hidden" name="actualOUT" value=" ">
                                                <input type="hidden" name="newIN" value=" ">
                                                <input type="hidden" name="newbrkOUT" value=" ">
                                                <input type="hidden" name="newbrkIN" value=" ">
                                                <input type="hidden" name="newOUT" value=" ">
                                                <input type="hidden" name="GenMeetIN" value=" ">
                                                <input type="hidden" name="GenMeetOUT" value=" ">
                                                <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                                <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                                <div class="form-check mb-1 ml-1">
                                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                                    <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                        Any falsification of information in this regard may form ground for 
                                                        disciplinary action up to and including dismissal.</em></small>
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>
    
                                        <div class="d-flex flex-row-reverse mt-3">
                                            <div class="p-1">
                                                <?php

                                                //QUERY EMPLOYEE NAME
                                                $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                                $query8 = $HRconnect->query($sql8);
                                                $row8 = $query8->fetch_array();
                                                $Name = $row8['name'];

                                                ?>
                                                <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                                <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                                <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                                <input type ="hidden" name="dtr" value = "concerns" >
                                                <input type ="hidden" name="filed" value = "done" >
                                                <input type ="hidden" name="d" value = "2" >
                                                <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                            </div>                                     
                                        </div> 
                                        <?php
                                            }
                                     }
                                     ?>               
                            </div>
                        </form>                     
                    <?php
                    }
                    ?>

                    <?php
                    //IF THE SELECTED CONCERN IS CANCELLATION OF LEAVE 
                    if ($_GET["dtrconcern"] == 'Cancellation of Leave') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <div>
                                <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                <p style="padding-left: 23px; padding-right: 23px;"><small>The staff wants to cancel his/her approved leave possibly due to wrong filing or wrong input of details.</small></p>
                            </div> 
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="a-form p-4">                              
                                <div class="row">
                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];

                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `vlform` where empno = $empID and vlstatus = 'approved' and vldatefrom = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array(); {

                                         ?>          
                                    
                                            <!-- CHECKING IF THERE ARE EXISTING FILED LEAVE -->

                                            <?php
                                            if ($query7->num_rows == 0) {

                                                ?>
                                                <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                                                    <div class="thanks toast fade show" style="position: fixed; bottom: 20px; right: 5px;">
                                                        <div class="toast-header bg-danger">
                                                          <h4 class="mr-auto my-0 text-white"><i class="fa fa-times-circle" aria-hidden="true"></i> Schedule</h5>
                                                         <small class="text-white">just now</small>
                                                        </div>
                                                        <div class="toast-body">
                                                          <b class="text-danger">There is NO FILED LEAVE for the selected Date, or the filed leave is still in pending status.</b>You may ask your immediate superior to cancel your filed leave.
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                    
                                                <div class="input-group input-group-sm mb-3" hidden>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Date</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="vldate" name="vldate" value="<?php echo $row7['vldatefrom']; ?>" readonly />																														
                                                </div>																	
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Vl Type: </span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="vl" name="vl" value="<?php echo $row7['vltype']; ?>" readonly />
                                        
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Status: </span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="vlstatus" name="vlstatus" value="<?php echo $row7['vlstatus']; ?>" readonly />
                                                </div>
                                    
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Reason:</span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="vlreason" name="vlreason" value="<?php echo $row7['vlreason']; ?>" readonly />
                                                </div>
                                                                        
                                                <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="inputGroup-sizing-sm">Final Approver: </span>
                                                    </div>
                                        
                                                    <input type="text" class="form-control" id="otapprove2" name="otapprove2" value="<?php echo $row7['approver']; ?>" readonly />
                                                </div>

                                            <div class="col-xl-12 col-lg-12 mt-2">
                                                <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                                <input type="hidden" name="othours" value="<?php echo $row7['vltype']; ?>">
                                                <input type="hidden" name="otdate" value="">
                                                <input type="hidden" name="actualIN" value=" ">
                                                <input type="hidden" name="actualbrkOUT" value=" ">
                                                <input type="hidden" name="actualbrkIN" value=" ">
                                                <input type="hidden" name="actualOUT" value=" ">
                                                <input type="hidden" name="newIN" value=" ">
                                                <input type="hidden" name="newbrkOUT" value=" ">
                                                <input type="hidden" name="newbrkIN" value=" ">
                                                <input type="hidden" name="newOUT" value=" ">
                                                <input type="hidden" name="GenMeetIN" value=" ">
                                                <input type="hidden" name="GenMeetOUT" value=" ">
                                                <input type="hidden" name="vlverify" value="<?php echo $row7['vlreason']; ?>">
                                                <label for="exampleFormControlTextarea1" required><small>Reason for Cancellation</small></label>
                                                <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                                <div class="form-check mb-1 ml-1">
                                                    <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                                    <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                        Any falsification of information in this regard may form ground for 
                                                        disciplinary action up to and including dismissal.</em></small>
                                                    </label>
                                                </div>
                                            </div>        
                                        </div>
    
                                        <div class="d-flex flex-row-reverse mt-3">
                                            <div class="p-1">
                                                <?php

                                                //QUERY EMPLOYEE NAME
                                                $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                                $query8 = $HRconnect->query($sql8);
                                                $row8 = $query8->fetch_array();
                                                $Name = $row8['name'];

                                                ?>
                                                <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                                <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                                <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                                <input type ="hidden" name="dtr" value = "concerns" >
                                                <input type ="hidden" name="filed" value = "done" >
                                                <input type ="hidden" name="d" value = "2" >
                                                <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                            </div>                                     
                                        </div> 
                                        <?php
                                            }
                                     }
                                     ?>               
                            </div>
                        </form>                     
                    <?php
                    }
                    ?>

                    <?php
                    //IF THE SELECTED CONCERN IS FILING OF GEN MEET OT 
                    if ($_GET["dtrconcern"] == 'File Broken Sched OT') {
                        $dtrconcern = $_GET["dtrconcern"];

                        ?>
                            <div>
                                <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                <p style="padding-left: 23px; padding-right: 23px;"><small>The staff renders Broken Schedule Overtime. It can be because of General Meeting/Cleaning or other reasons.</small></p>
                            </div> 
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="p-4">                              
                                <div class="row">

                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];

                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array();

                                     //RAW TIME INPUTS FROM DATABASE
                                     $time1 = $row7['timein4'];
                                     $time2 = $row7['timeout4'];

                                     //SEPERATE DATE FROM TIME OF BROKEN SCHED TIME INPUTS
                                     $genmIN = date('H:i', strtotime($row7['timein4']));
                                     $genmOUT = date('H:i', strtotime($row7['timeout4']));

                                     //GET THE MAXIMUM NUMBER OF OT HOURS THAT CAN BE FILED
                                     $maxot = floor((strtotime($time2) - strtotime($time1)) / 3600); {

                                         ?>

                                        <div class="col-xl-12 col-lg-12 mb-1">
                                            <center><p><i><b class="text-danger">Note!</b> always remember to input the correct ot hours before clicking submit. Thank you! </p></i></center>
                                        </div>															
                                
                                        <div class="col-xl-6 col-lg-6 mb-1">
                                            <label><small>Captured time inputs</small></label>                                    
                                    
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" name="actualbrkOUT" value="No Break">
                                            <input type="hidden" name="actualbrkIN" value="No Break">
                                            <input type="hidden" name="newIN" value=" ">
                                            <input type="hidden" name="newbrkOUT" value=" ">
                                            <input type="hidden" name="newbrkIN" value=" ">
                                            <input type="hidden" name="newOUT" value=" ">
                                            <br>
                                            <small>Gen Meet IN: </small>
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualIN" name="actualIN" value="<?php if ($row7['timein4'] != '') {
                                                echo date('H:i', strtotime($row7['timein4']));
                                            } else if ($row7['timein4'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- TIME OUT FROM DATABASE -->
                                            <small>Gen Meet OUT: </small>
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualOUT" name="actualOUT" value="<?php if ($row7['timeout4'] != '') {
                                                echo date('H:i ', strtotime($row7['timeout4']));
                                            } else if ($row7['timeout4'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>
                                    

                                        <?php
                                     }
                                     ?>
                                    </div>
                                
                                    <div class="col-xl-6 col-lg-6 mb-1">
                                        <label class="invisible"><small>.</small></label><br>
                                        <small>Select the type of OT</small>
                                        <select class="form-control form-control-sm mb-1" id="ottype" name="ottype" required>
                                            <option value="1">Gen Meet OT</option>
                                            <option value="2">Gen Clean OT</option>
                                        </select>
                                    
                                        <small>Maximum number of OT Hours that can be filed:<b class="text-danger"> <?php echo $maxot; ?> </b></small>
                                        <input type="number" class="form-control form-control-sm text-center" name="othours" placeholder="Number of OT Hours" max="<?php echo $maxot; ?>" 
                                        min="<?php if ($maxot == 0) {
                                            echo $maxot;
                                        } else {
                                            echo 1;
                                        } ?>" required> 
                                    </div>

                                
                                    <?php
                                    if ($maxot == 0) {
                                        ?>
                                
                                        <div class="alert alert-danger mt-2" role="alert">
                                          You cannot file OVERTIME due to no time inputs for Broken Schedule. You may file another dtr concern "Forgot/Wrong inputs of broken sched" to continue this filing. Thank you!
                                        </div>

                                        <?php
                                    } else {
                                        ?>
                                        <div class="col-xl-12 col-lg-12 mt-2">
                                            <br>
                                            <label for="exampleFormControlTextarea1" required><big>Reason or Purpose of Overtime</big></label>
                                            <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" id="exampleFormControlTextarea1" name="concernReason">
                                    
                                            <div class="form-check mb-1">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                                <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                    Any falsification of information in this regard may form ground for 
                                                    disciplinary action up to and including dismissal.</em></small>
                                                </label>
                                            </div>
                                        </div>        
                                    </div>
    
                                    <div class="d-flex flex-row-reverse mt-3">
                                        <div class="p-1">
                                            <?php

                                            //QUERY EMPLOYEE NAME
                                            $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                            $query8 = $HRconnect->query($sql8);
                                            $row8 = $query8->fetch_array();
                                            $Name = $row8['name'];

                                            ?>
                                            <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                            <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                            <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                            <input type ="hidden" name="dtr" value = "concerns" >
                                            <input type ="hidden" name="filed" value = "done" >
                                            <input type ="hidden" name="d" value = "2" >
                                            <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                
                                        
                                        </div>                                     
                                    </div> 
                                    <?php
                                    }
                                    ?>             
                            </div>
                        </form>                     
                    <?php
                    }
                    ?>

                    <?php
                    //IF THE SELECTED CONCERN IS WRONG FORMAT OF OBP
                    if ($_GET["dtrconcern"] == 'Wrong format/filing of OBP') {
                        $dtrconcern = $_GET["dtrconcern"];

                        $cdate2 = $_GET['date'];
                        $empID1 = $_GET['empno'];
                        $sqlOBP = "SELECT * FROM `obp` where empno = $empID1 and status = 'Approved' and datefromto = '" . $cdate2 . "' ";
                        $queryOBP = $HRconnect->query($sqlOBP);
                        $rowOBP = $queryOBP->fetch_array();

                        ?>

                            <?php
                            if ($queryOBP->num_rows == 0) {

                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff inputs wrong format or details in filing his/her OBP and he/she wants to correct it.</small></p>
                                </div> 
                                <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 100px;">
                                    <div class="thanks toast fade show" style="position: fixed; bottom: 30px; right: 10px;">
                                        <div class="toast-header bg-danger">
                                          <h4 class="mr-auto my-0 text-white"><i class="fa fa-times-circle" aria-hidden="true"></i> Warning</h5>
                                         <small class="text-white">just now</small>
                                        </div>
                                        <div class="toast-body">
                                          <b class="text-danger">You Dont have filed OBP, or the filed OBP is still in pending status.</b> You may ask your immediate superior to cancel your filed OBP.
                                        </div>
                                    </div>
                                </div>
                        
                                <?php
                            } else {
                                ?>
                                <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                                <div class="p-4">                              
                                    <div class="row">
                                        <div class="form-group row col-xl-12 mb-0">
                                
                                            <div class="col-md-12 text-center">
                                                <center><p><i><b class="text-danger">Note!</b> always remember that our system is using millitary time please use correct time format (<b class="text-success"> 00:00</b> ) to prevent errors.</p></i></center>
                                            </div>
                                    
                                            <div class="col-md-12 text-center checkbox">
                                                <input type="checkbox" class="control-input" id="nobreak" onchange="checkDisable()" />
                                                    <label class="control-label" for="nobreak">
                                                        Please check this box if you dont have break time.
                                                    </label>
                                            </div>
                                        </div>


                                         <?php
                                         if (isset($_GET['date']))
                                             //GET the employee number and date
                                             $cdate1 = $_GET['date'];
                                         $empID = $_GET['empno'];

                                         //query to get the employees time inputs
                                         $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                         $query7 = $HRconnect->query($sql7);
                                         $row7 = $query7->fetch_array();

                                         $genmIN = date('H:i', strtotime($row7['timein4']));
                                         $genmOUT = date('H:i', strtotime($row7['timeout4'])); {

                                             ?>                     
                                            <div class="col-xl-6 col-lg-6 mb-1">
                                                <label><small>Captured time inputs</small></label>
                                    
                                                <!-- TIME IN FROM DATABASE -->
                                                <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                                <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                                <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                                <input type="hidden" id="othours" name="othours" value=" ">
                                                <input type="hidden" id="otdate" name="otdate" value=" ">

                                                <input class="form-control form-control-sm mb-1" type="text" id="actualIN" name="actualIN" value="<?php if ($row7['M_timein'] != '') {
                                                    echo date('H:i', strtotime($row7['M_timein']));
                                                } else if ($row7['M_timein'] == '') {
                                                    echo 'No Logs';
                                                } ?>" readonly>

                                                <!-- BREAK OUT FROM DATABASE -->
                                                <input class="form-control form-control-sm mb-1" type="text" id="actualbrkOUT" name="actualbrkOUT" value="<?php if ($row7['M_timeout'] == 'No Break') {
                                                    echo $row7['M_timeout'];
                                                } else if ($row7['M_timeout'] != '') {
                                                    echo date('H:i', strtotime($row7['M_timeout']));
                                                } else if ($row7['M_timeout'] == '' or $row7['M_timeout'] != 'No Break') {
                                                    echo 'No Logs';
                                                } ?>" readonly>

                                                <!-- BREAK IN FROM DATABASE -->
                                                <input class="form-control form-control-sm mb-1" type="text" id="actualbrkIN" name="actualbrkIN" value="<?php if ($row7['A_timein'] == 'No Break') {
                                                    echo $row7['A_timein'];
                                                } else if ($row7['A_timein'] != '') {

                                                    echo date('H:i', strtotime($row7['A_timein']));
                                                } else if ($row7['A_timein'] == '' or $row7['A_timein'] != 'No Break') {
                                                    echo 'No Logs';
                                                } ?>" readonly>

                                                <!-- TIME OUT FROM DATABASE -->
                                                <input class="form-control form-control-sm mb-1" type="text" id="actualOUT" name="actualOUT" value="<?php if ($row7['A_timeout'] != '') {
                                                    echo date('H:i', strtotime($row7['A_timeout']));
                                                } else if ($row7['A_timeout'] == '') {
                                                    echo 'No Logs';
                                                } ?>" readonly>
                                   

                                            <?php
                                         }
                                         ?>
                                        </div>
    
                                        <div class="col-xl-6 col-lg-6 mb-1">
                                            <label><small>Requested time inputs</small></label>
                                            <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['M_timein'] != '') {
                                                echo date('H:i', strtotime($row7['M_timein']));
                                            } else if ($row7['M_timein'] == '') {
                                                echo '';
                                            } ?>" name="newIN" required>
                                            <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['M_timeout'] == 'No Break') {
                                                echo $row7['M_timeout'];
                                            } else if ($row7['M_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['M_timeout']));
                                            } else if ($row7['M_timeout'] == '' or $row7['M_timeout'] != 'No Break') {
                                                echo '';
                                            } ?>" name="newbrkOUT" id="newbrkOUT"  required>
                                            <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['A_timein'] == 'No Break') {
                                                echo $row7['A_timein'];
                                            } else if ($row7['A_timein'] != '') {

                                                echo date('H:i', strtotime($row7['A_timein']));
                                            } else if ($row7['A_timein'] == '' or $row7['A_timein'] != 'No Break') {
                                                echo '';
                                            } ?>" name="newbrkIN" id="newbrkIN" required>
                                            <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['A_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['A_timeout']));
                                            } else if ($row7['A_timeout'] == '') {
                                                echo '';
                                            } ?>" name="newOUT" required>
                                        </div>
                                         <div class="col-xl-6 col-lg-6 mt-2">

                                            <label><small>Attachment 1(<span class="text-danger"> IR/HYO FORM </span>)</small></label><br>
                                            <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                        </div>
    
                                        <div class="col-xl-6 col-lg-6 mt-2">
                                            <label><small>Attachment 2(<span class="text-danger"> SCREENSHOT OF FILED OBP </span>)</small></label><br>
                                            <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                        </div>
                              
                                        <div class="col-xl-12 col-lg-12 mt-2">
                                            <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                            <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                            <div class="form-check mb-1 ml-1">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                                <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                    Any falsification of information in this regard may form ground for 
                                                    disciplinary action up to and including dismissal.</em></small>
                                                </label>
                                            </div>
                                        </div>        
                                    </div>
    
                                    <div class="d-flex flex-row-reverse mt-3">
                                        <div class="p-1">
                                            <?php

                                            //QUERY EMPLOYEE NAME
                                            $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                            $query8 = $HRconnect->query($sql8);
                                            $row8 = $query8->fetch_array();
                                            $Name = $row8['name'];

                                            ?>
                                            <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                            <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                            <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                            <input type ="hidden" name="dtr" value = "concerns" >
                                            <input type ="hidden" name="filed" value = "done" >
                                            <input type ="hidden" name="d" value = "2" >
                                            <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                        </div>                                     
                                    </div> 
                                           
                                </div>
                            </form>

                            <?php
                            }
                    }
                    ?>
                        
                    <?php
                    if ($_GET["dtrconcern"] == 'Forgot/Wrong time IN/OUT or break OUT/IN' || $_GET["dtrconcern"] == 'Not following time interval' || $_GET["dtrconcern"] == 'Sync/Network error' || $_GET["dtrconcern"] == 'Emergency time out' || $_GET["dtrconcern"] == 'Hardware/Persona Malfunction' || $_GET["dtrconcern"] == 'Fingerprint problem') {
                        $dtrconcern = $_GET["dtrconcern"];
                        ?>
                            <?php
                            if ($_GET["dtrconcern"] == 'Forgot/Wrong time IN/OUT or break OUT/IN') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff forgot to tap his/her fingerprint for one of his/her logs.</small></p>
                                </div> 
                                <?php
                            }

                            if ($_GET["dtrconcern"] == 'Not following time interval') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff was not able to follow the time interval in tapping the persona (5 mins for cafe | 30 mins for the head office).</small></p>
                                </div> 
                                <?php
                            }

                            if ($_GET["dtrconcern"] == 'Sync/Network error') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff has time inputs on the persona (based on the logs history) but did not reflect on his/her Web DTR.</small></p>
                                </div> 

                                <?php
                            }

                            if ($_GET["dtrconcern"] == 'Emergency time out') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff had an emergency and need to go home immediately which may possibly cause problem with his/her DTR due to time interval rules.</small></p>
                                </div> 

                                <?php
                            }

                            if ($_GET["dtrconcern"] == 'Hardware/Persona Malfunction') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The Device used for persona is not properly working(defective).</small></p>
                                </div> 
                        
                                <?php
                            }

                            if ($_GET["dtrconcern"] == 'Fingerprint problem') {
                                ?>
                                <div>
                                    <label style="padding-left: 23px; color:blue;"><bold>Description</bold></label>
                                    <p style="padding-left: 23px; padding-right: 23px;"><small>The staff encountered problem with his/her fingerprints causing problem with his/her logs.</small></p>
                                </div> 

                                <?php
                            }
                            ?>
                            <form action="pdf/print_concerns.php" method="post" enctype="multipart/form-data">
                            <div class="a-form p-4">                              
                                <div class="row">						
                                    <div class="form-group row mb-0">
                                        <div class="col-md-12 text-center checkbox">
                                            <center><p><i><b class="text-danger">Note!</b> always remember that our system is using millitary time please use correct time format (<b class="text-success"> 00:00</b> ) to prevent errors.</p></i></center>
                                        </div>
                                    
                                        <div class="col-md-12 text-center checkbox">
                                            <input type="checkbox" class="control-input" id="nobreak" onchange="checkDisable()" />
                                                <label class="control-label" for="nobreak">
                                                    Please check this box if you dont have break time.
                                                </label>
                                        </div>
                                    </div>


                                     <?php
                                     if (isset($_GET['date']))
                                         //GET the employee number and date
                                         $cdate1 = $_GET['date'];
                                     $empID = $_GET['empno'];

                                     //query to get the employees time inputs
                                     $sql7 = "SELECT * FROM `sched_time` where empno = $empID and datefromto = '" . $cdate1 . "' ";
                                     $query7 = $HRconnect->query($sql7);
                                     $row7 = $query7->fetch_array();

                                     $genmIN = date('H:i', strtotime($row7['timein4']));
                                     $genmOUT = date('H:i', strtotime($row7['timeout4'])); {

                                         ?>                     
                                        <div class="col-xl-6 col-lg-6 mb-1">
                                            <label><small>Captured time inputs</small></label>
                                    
                                            <!-- TIME IN FROM DATABASE -->
                                            <input type="hidden" id="GenMeetIN" name="GenMeetIN" value="<?php echo $genmIN; ?>">
                                            <input type="hidden" id="GenMeetOUT" name="GenMeetOUT" value="<?php echo $genmOUT; ?>">
                                            <input type="hidden" id="dtrconcern" name="dtrconcern" value="<?php echo $dtrconcern; ?>">
                                            <input type="hidden" id="othours" name="othours" value=" ">
                                            <input type="hidden" id="ottype" name="ottype" value="">

                                            <input class="form-control form-control-sm mb-1" type="text" id="actualIN" name="actualIN" value="<?php if ($row7['M_timein'] != '') {
                                                echo date('H:i', strtotime($row7['M_timein']));
                                            } else if ($row7['M_timein'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- BREAK OUT FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualbrkOUT" name="actualbrkOUT" value="<?php if ($row7['M_timeout'] == 'No Break') {
                                                echo $row7['M_timeout'];
                                            } else if ($row7['M_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['M_timeout']));
                                            } else if ($row7['M_timeout'] == '' or $row7['M_timeout'] != 'No Break') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- BREAK IN FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualbrkIN" name="actualbrkIN" value="<?php if ($row7['A_timein'] == 'No Break') {
                                                echo $row7['A_timein'];
                                            } else if ($row7['A_timein'] != '') {

                                                echo date('H:i', strtotime($row7['A_timein']));
                                            } else if ($row7['A_timein'] == '' or $row7['A_timein'] != 'No Break') {
                                                echo 'No Logs';
                                            } ?>" readonly>

                                            <!-- TIME OUT FROM DATABASE -->
                                            <input class="form-control form-control-sm mb-1" type="text" id="actualOUT" name="actualOUT" value="<?php if ($row7['A_timeout'] != '') {
                                                echo date('H:i', strtotime($row7['A_timeout']));
                                            } else if ($row7['A_timeout'] == '') {
                                                echo 'No Logs';
                                            } ?>" readonly>
                                   

                                        <?php
                                     }
                                     ?>
                                    </div>
    
                                    <div class="col-xl-6 col-lg-6 mb-0">
                                        <label><small>Requested time inputs</small></label>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['M_timein'] != '') {
                                            echo date('H:i', strtotime($row7['M_timein']));
                                        } else if ($row7['M_timein'] == '') {
                                            echo '';
                                        } ?>" name="newIN" required>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['M_timeout'] == 'No Break') {
                                            echo $row7['M_timeout'];
                                        } else if ($row7['M_timeout'] != '') {
                                            echo date('H:i', strtotime($row7['M_timeout']));
                                        } else if ($row7['M_timeout'] == '' or $row7['M_timeout'] != 'No Break') {
                                            echo '';
                                        } ?>" name="newbrkOUT" id="newbrkOUT"  required>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['A_timein'] == 'No Break') {
                                            echo $row7['A_timein'];
                                        } else if ($row7['A_timein'] != '') {

                                            echo date('H:i', strtotime($row7['A_timein']));
                                        } else if ($row7['A_timein'] == '' or $row7['A_timein'] != 'No Break') {
                                            echo '';
                                        } ?>" name="newbrkIN" id="newbrkIN" required>
                                        <input class="form-control form-control-sm mb-1" type="text" pattern="([01]?[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}" placeholder="00:00" value="<?php if ($row7['A_timeout'] != '') {
                                            echo date('H:i', strtotime($row7['A_timeout']));
                                        } else if ($row7['A_timeout'] == '') {
                                            echo '';
                                        } ?>" name="newOUT" required>
                                    </div>
                                    <?php
                                    if ($_GET["dtrconcern"] == 'Wrong format/filing of OBP') {
                                        ?>
                                         <div class="col-xl-6 col-lg-6">
                                            <label class="mb-0"><small>Attachment 1(<span class="text-danger"> SCREENSHOT OF ONLINE DTR </span>)</small></label><br>
                                            <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                        </div>
    
                                        <div class="col-xl-6 col-lg-6">
                                            <label class="mb-0"><small>Attachment 2(<span class="text-danger"> SCREENSHOT OF FILED OBP </span>)</small></label><br>
                                            <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                        </div>
                                        <?php
                                    } else if ($_GET["dtrconcern"] == 'Hardware/Persona Malfunction') {
                                        ?>
                                            <div class="col-xl-6 col-lg-6 mt-2">
                                                <label class="mb-0"><small>Attachment 1(<span class="text-danger"> PROOF OF HARDWARE/PERSONA MALFUNCTION </span>)</small></label><br>
                                                <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                            </div>
    
                                            <div class="col-xl-6 col-lg-6 mt-2">
                                                <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOG BOOK PICTURE/CCTV </span>)</small></label><br>
                                                <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                            </div>
                                        <?php
                                    } else if ($_GET["dtrconcern"] == 'Fingerprint problem') {
                                        ?>
                                                <div class="col-xl-6 col-lg-6 mt-2">
                                                    <label class="mb-0"><small>Attachment 1(<span class="text-danger"> PROOF OF NOT VERIFYING THE FINGERPRINT </span>)</small></label><br>
                                                    <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                                </div>
    
                                                <div class="col-xl-6 col-lg-6 mt-2">
                                                    <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOG BOOK PICTURE/CCTV </span>)</small></label><br>
                                                    <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                                </div>
                                        <?php
                                    } else if ($_GET["dtrconcern"] == 'Emergency time out') {
                                        ?>
                                                    <div class="col-xl-6 col-lg-6 mt-2">
                                                        <label class="mb-0"><small>Attachment 1(<span class="text-danger"> SCREENSHOT OF ONLINE DTR </span>)</small></label><br>
                                                        <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                                    </div>
    
                                                    <div class="col-xl-6 col-lg-6 mt-2">
                                                        <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOGS HISTORY PICTURE </span>)</small></label><br>
                                                        <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                                    </div>
                                        <?php
                                    } else if ($_GET["dtrconcern"] == 'Sync/Network error') {
                                        ?>
                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                            <label class="mb-0"><small>Attachment 1(<span class="text-danger"> LOGS HISTORY PICTURE </span>)</small></label><br>
                                                            <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                                        </div>
    
                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                            <small><input type="hidden" name="attachment2" /></small>
                                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                            <label class="mb-0"><small>Attachment 1(<span class="text-danger"> IR/HYO FORM </span>)</small></label><br>
                                                            <small><input type="file" accept="image/*,video/*" name="attachment1" required /></small>
                                                        </div>
    
                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                            <label class="mb-0"><small>Attachment 2(<span class="text-danger"> LOG BOOK PICTURE/CCTV </span>)</small></label><br>
                                                            <small><input type="file" accept="image/*,video/*" name="attachment2" required /></small>
                                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-xl-12 col-lg-12 mt-2">
                                        <label for="exampleFormControlTextarea1" required><small>Reason</small></label>
                                        <input type="text" class="form-control form-control-sm mb-1" pattern="^[-@.\/#&+\w\s]*$" placeholder="Input Reason" id="exampleFormControlTextarea1" name="concernReason" required>
                                    
                                        <div class="form-check mb-1 ml-1">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                            <label class="form-check-label" for="exampleCheck1"><em><small>I hereby certify that the above infomation provided is correct. 
                                                Any falsification of information in this regard may form ground for 
                                                disciplinary action up to and including dismissal.</em></small>
                                            </label>
                                        </div>
                                    </div>        
                                </div>
    
                                <div class="d-flex flex-row-reverse mt-3">
                                    <div class="p-1">
                                        <?php

                                        //QUERY EMPLOYEE NAME
                                        $sql8 = "SELECT * FROM user_info where empno = '$empno'";
                                        $query8 = $HRconnect->query($sql8);
                                        $row8 = $query8->fetch_array();
                                        $Name = $row8['name'];

                                        ?>
                                        <input type ="hidden" name="empNAME" value = "<?php echo $Name; ?>" >
                                        <input type ="hidden" name="empNUM" value = "<?php echo $empno; ?>" >
                                        <input type="hidden" name="date" value ="<?php echo $cdate; ?>">
                                        <input type ="hidden" name="dtr" value = "concerns" >
                                        <input type ="hidden" name="filed" value = "done" >
                                        <input type ="hidden" name="d" value = "2" >
                                        <input type="submit" class="btn btn-sm border-0 btn-primary" value="Submit" name="submit" onclick="return confirm('Are you sure you want to submit your dtr concern?');">
                                        
                                    </div>                                     
                                </div>          
                            </div>
                        </form>                     
                    <?php
                    }
                    ?>                                                 
                    </div>
                </div> 
            </div>
        </div>
    </div>		
</div>  
    </div>
    
    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                 <span>Copyright © Mary Grace Foods Inc. 2019</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>   

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
        function checkDisable(){
        var nobreak = document.getElementById('nobreak');   
        var breakout = document.getElementById('newbrkOUT');
        var breakin = document.getElementById('newbrkIN');

        if(nobreak.checked){
            breakout.value = "No Break";
            breakin.value = "No Break";

        document.getElementById("newbrkOUT").readOnly = true;
        document.getElementById("newbrkIN").readOnly = true;
        }else{


        breakout.value = "";
        breakin.value = "";

        document.getElementById("newbrkOUT").readOnly = false;
        document.getElementById("newbrkIN").readOnly = false;

        }
       
    }
    </script>
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        
    <script type='text/javascript'>
        $(document).ready(function(){
            // OT
            $('#datepicker').datepicker({
                dateFormat: "yy-mm-dd",
                    minDate: -15,
                    maxDate: 0,
            });

            // OBP
            $('#datepicker1').datepicker({
            dateFormat: "yy-mm-dd",
                    minDate: -15,
                    maxDate: 0,
            }); 
            
            // lateapproval
            $('#datepicker3').datepicker({
            dateFormat: "yy-mm-dd",
                    minDate: -15,
                    maxDate: 0,
            });
            
            $('#datepicker4').datepicker({
            dateFormat: "yy-mm-dd",
                    minDate: -15,
                    maxDate: 0,
            });
                
        });
        </script>
    



</body>

</html>