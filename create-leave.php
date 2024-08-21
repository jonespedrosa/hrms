<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    session_start();

    if(!(isset($_SESSION['user_validate']))){
        header("Location:index.php?&m=2");
    } 
    $empid = $_SESSION['user_validate'];

    // Select Employee Details
    $select_details = "SELECT name, branch, department,userid, position, vl, datehired FROM `hrms`.`user_info` WHERE empno = ?";
    $stmt = $HRconnect->prepare($select_details);
    $stmt->bind_param("i", $empid);
    $stmt->execute();
    $employee_details = $stmt->get_result()->fetch_array();
    $stmt->close();

    $name = $employee_details["name"];
    $date_hired = $employee_details["datehired"];
    $branch = $employee_details["branch"];
    $department = $employee_details["department"];
    $position = $employee_details["position"];
    $leave = $employee_details["vl"];

    // cutoff
    $sql4 = "SELECT datefrom, dateto FROM sched_info where empno = ".$empid." AND status = 'Pending'";
    $query4=$HRconnect->query($sql4);  
    $row4=$query4->fetch_array();  
    $cutfrom = $row4['datefrom'];
    $cutto = $row4['dateto']; 

    // Get Leave Types
    $leaves = "SELECT name FROM `hrms`.`leave_types` WHERE status = 1";
    $stmt = $HRconnect->prepare($leaves);
    $stmt->execute();
    $leave_details = $stmt->get_result()->fetch_all();
    $stmt->close();
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
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Date Picker -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->

        <!-- SWAL -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- AJAX -->
        <script src="js/ajax-leave.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <!-- JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <style>
            select{
                text-align-last:center;
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
            .text-small{
                font-size: 80%;
            }

            @media (max-width: 991px) {
                .border-right {
                    border-right: none !important; 
                }
            }
            @media (min-width: 991px) {
                .border-top {
                    border-top: none !important; 
                }
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
            <div class="card o-hidden border-0 shadow-lg my-2">
                <!-- card body -->
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center">
                        <h1 class="h5 text-gray-900 mb-3 text-center">
                            <small>Human Resource Department</small> 
                            <p class="m-0">Leave Request Form</p>
                        </h1>
                    </div> 

                    <div class="row">
                        <div class="col-lg-6 col-sm-12 border-right pt-3 mt-3">
                            <div class="container">
                                <p class="">Employee Details</p>
                                <div class="row"> 
                                    <div class="col-lg-12"> 
                                        <input id="empno" type="text" class="d-none" value="<?php echo $empid;?>">
                                        <input id="cutfrom" type="text" class="d-none" value="<?php echo $cutfrom;?>">
                                        <input id="cutto" type="text" class="d-none" value="<?php echo $cutto;?>">
                                        <input type="text" class="form-control form-control-user bg-gray-100 text-small text-center rounded-pill" value="<?php echo $name;?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12">
                                        <input type="text" class="form-control bg-gray-100 text-small text-center rounded-pill mt-3" value="<?php echo $position;?>" readonly>
                                    </div>
                                    <!-- <div class="col-lg-6 col-sm-12">
                                        <input type="text" class="form-control bg-gray-100 text-small text-center rounded-pill mt-3" value="<?php echo $date_hired;?>" readonly>
                                    </div> -->
                                </div>
                            </div> 
                        </div>
                        
                        <div class="col-lg-6 col-sm-12 pt-3 mt-3 border-top"> 
                            <div class="container">
                                <div class="d-flex align-items-center mb-3"> 
                                    <p class="mr-2 mb-0">Remaining Leave(s): </p>
                                    <p class="font-weight-bold m-0 <?php echo ($leave <= 0) ? 'text-danger': 'text-success'?>" id="remain-leave"><?php echo $leave;?></p>
                                    <i class="fa fa-arrow-right mx-2 d-none" id="fa-arrow-right" aria-hidden="true"></i>
                                    <p class="font-weight-bold m-0 text-danger d-none" id="deducted-leave"><?php echo $leave;?></p>
                                </div>
                                
                                <div class="form-group">
                                    <select id="leave-types" class="form-control text-small" name="type" required>
                                        <option value="Wellness Leave" selected>Wellness Leave</option>
                                        <?php
                                            for($counter = 1; $counter < count($leave_details); $counter++){
                                                echo '<option value="'.$leave_details[$counter][0].'">'.$leave_details[$counter][0].'</option>';
                                            }
                                        ?>
                                    </select> 
                                </div>

                                <div class="form-group">
                                    <input type="text" id="reason" placeholder="Reason or Purpose of Leave" class="form-control text-center text-small">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
                <!-- end card body -->
                <hr>
                <!-- card body -->
                <div class="card-body" id="has-leave">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12 mb-3">
                            <div class="container">
                                <p class="m-0 mb-2 small-font text-primary" id="view-holiday-dates" style="cursor:pointer;  width: fit-content">View Holiday Dates</p>
                                <button id="leaveDate" class="btn btn-md btn-primary d-flex justify-content-center align-items-center">
                                    <p class="m-0">Open Calendar</p>
                                    <i class="fa fa-calendar ml-3" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-12 mt-lg-0 mt-sm-3">                             
                            <div class="container">
                                <p class="" id="text">Selected Dates:</p>
                            </div>
                            <div class="container">
                                <div class="row" id="selectedLeave">
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body mx-5 d-none" id="no-leave">
                    <div class="row">  
                        <div class="col-lg-6 col-sm-12 mb-4">
                            <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                                <div class="d-flex flex-column">
                                    <h5 class="text-muted">You have no remaining leave(s)</h5>
                                    <p class="fs-1">If there are problems with your leave, please contact the HR department</p>
                                </div> 
                            </div> 
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                                <img src="images/no-leave.png" alt="" width="250">
                            </div> 
                        </div> 
                    </div>
                </div>
                <!-- end card body -->

                <!-- card footer -->
                <div class="card-footer">
                    <div class="container">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" id="customCheck" required>
                                <label class="custom-control-label" for="customCheck" required>
                                    I hereby Certify that the above infomation provided is correct. Any falsification
                                    of information in this regar may form ground for disciplinary action up to and including dismissal.
                                </label>
                            </div>                          
                        </div>
                        <div class="d-flex justify-content-between border-top py-3">
                            <a class="btn btn-secondary bg-gradient-secondary" href="index.php?empno=5182&SubmitButton=Submit">Back</a>
                            <button id="next-leave" class="btn btn-primary bg-gradient-primary">Next</button>
                        </div>
                        <div class="d-flex justify-content-end border-top py-2">
                            <a class="small float-right" href="pdf/print_ot.php?leave=leave&empno=<?php echo $empid; ?>">View Filed Leave <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
                <!-- end card footer -->
            </div>
        </div> 
    </body>
</html>

<!-- Flat picker CDN -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<!-- Modal -->
<div class="modal fade" id="leave-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Selected Leave Dates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body" id="leave-body">
                <div class="container d-flex flex-column justify-content-center align-items-center">
                    <div class="container d-flex justify-content-center align-items-center">
                        <p class="font-weight-bold m-0 mx-2 text-success" id="current-leave">0</p>
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        <p class="display-4 font-weight-bold m-0 mx-2 text-danger" id="estimated-leave">0</p>
                    </div> 
                    <p>Estimated Remaining Leave(s)</p>
                    <p class="text-small">Note: Remaining leaves will vary if the filed leave is approved</p>
                </div>

                <hr>
                <div class="mt-3 font-weight-normal" id="">
                    <div class="container d-flex align-items-center"> 
                        <a class="m-0 ml-2 text-small vertical-align-end" data-toggle="collapse" href="#leaveCollapse" role="button" id="leaveToggle">Hide Leave Calculation</a>
                    </div>
                    <div class="container-fluid rounded box-shadow bg-dirty-white p-3 collapse show" id="leaveCollapse">
                        <div class="container">
                            <div class="row" id="leave-calculation">
                                <!-- <div class="col-lg-6 col-sm-12">
                                    <li class="font-weight-bold m-0">2023-06-09</li>
                                    <p class="text-small text-danger">-1 (Whole Day)</p>
                                </div>
                                <div class="col-lg-6 col-sm-12">
                                    <li class="font-weight-bold m-0">2023-06-09</li>
                                    <p class="text-small text-danger">-1 (Whole Day)</p>
                                </div>
                                <div class="col-sm-12 d-flex mt-3">
                                    <p class="m-0 mr-3 text-small">Total leave credits consumed: </p>
                                    <p class="m-0 text-small text-danger" id="deducted-leave">2</p>
                                </div> -->
                                <!-- <div class="col-sm-12 mt-3">
                                    <p class="text-small">(Deducted credit leaves will vary depending on your scheduled work hours)</p>
                                </div> -->
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submit-leave">Submit</button>
            </div>
        </div>
    </div>
</div>