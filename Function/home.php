<?php
    include("../hrms/Function/Database_Connection.php");
    include("../Ticketing/functions.php");
    include("../hrms/Function/hrms_home.php");


if($userlevel == 'master'){
?>  
<!-- <input type="file" accept="image/*;capture=camera"> -->
<?php } ?>
<!DOCTYPE html>
    <style type="text/css">
    .badge {
      position: absolute;
      top: -10px;
      right: -10px;
      padding: 5px 10px;
      border-radius: 50%;
      background-color: red;
      color: white;
    } 

    .badge1 {
      position: static ;
      top: -10px;
      right: -10px;
      padding: 1px 5px;
      border-radius: 20%;
      background-color: red;
      color: white;
    }   
    </style>
    
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    <style>
        .example {
          width: 100%;
          height: 453px;
          overflow-y: scroll; /* Add the ability to scroll */    
        }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .example::-webkit-scrollbar {
            display: ;
        }
        
        /* width */
        .example::-webkit-scrollbar {
          width: 10px;
        }

        /* Track */
        .example::-webkit-scrollbar-track {
          background: #f1f1f1; 
        }
         
        /* Handle */
        .example::-webkit-scrollbar-thumb {
          background: #888; 
        }

        /* Handle on hover */
        *::-webkit-scrollbar-thumb:hover {
          background: #555; 
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .example {
          -ms-overflow-style: none;  /* IE and Edge */
          scrollbar-width: none;  /* Firefox */
        }
    </style>
    
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
            text-align: center;
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
        
        .exportExcel{
              background-color: #f2f2f2;
              border-style: solid;
              border-color: #a1a1a1;
              border-radius: 5px;
              border-width: 1px;
              color: white;
              padding: 3px 10px;
              text-align: center;
              display: inline-block;
              font-size: 16px;
              color: black;
              cursor: pointer;
              bottom: 0;
        }
    </style>
	
	<style>
		@import url(https://fonts.googleapis.com/css?family=Dosis:300,400);


		
		/* effect-shine */
		a.effect-shine:hover {
		  -webkit-mask-image: linear-gradient(-75deg, rgba(0,0,0,.6) 30%, #000 50%, rgba(0,0,0,.6) 70%);
		  -webkit-mask-size: 200%;
		  animation: shine 2s infinite;
		}

		@-webkit-keyframes shine {
		  from {
			-webkit-mask-position: 150%;
		  }
		  
		  to {
			-webkit-mask-position: -50%;
		  }
		}
	</style>
    
</head>

<body id="page-top" class="sidebar-toggled">


<?php include("navigation.php"); ?>
 
                 
<!-- Begin Page Content -->
                <div class="container-fluid">
					<!-- Page Heading -->
					<div class="d-sm-flex align-items-center justify-content-between mb-2">
						<div class="mb-3">
							<h4 class="mb-0">Dashboard</h4>
							<div class="small">
								<span class="fw-500 text-primary"><?php echo date('l'); ?></span>
								.<?php echo date('F d, Y - h:i:s A'); ?>
							</div>
						</div>
						
						<div class="btn-group mb-2">
							<a href="holiday.php" type="button" class="btn border-0 btn-sm btn-outline-primary">
								<span><i class="far fa-calendar-alt"></i></span>                   
								&nbsp <span class="text"> Holidays</span>
							</a>							
						</div>
					</div>

                    <!-- Content Row -->
                    <div class="row">
                <?php 
					if($userlevel == 'master' OR $userlevel == 'admin')
					{
					?>                         
                        <!-- Total Employee -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Active Employee
                                            </div>
                                            
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    
                                                <?php if($userlevel == 'admin' OR $userlevel == 'master' OR $userlevel == 'ac' OR $userlevel == 'mod'){ 

                                                    if($userid == ''){
														echo $Activeall;

														}else{ 
												   
														echo $ActiveSingle;
                                                        } 

                                                         }
                                                    
                                                    ?>      
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                        <!-- Newly Hired Employee -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                               <a href="recentlyemployee.php?ho=ho" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown"> Newly Hired Employee  (<?php echo date("M",     strtotime("-1 month", strtotime(date("Y/m/d")))) . "-". date("M") ; ?>)
                                                </a>
                                               </div>
                                         <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $NewlyHired; ?>

                                         </div>
                                            <span class="badge">
                                               <?php
                                                    $query32 = "SELECT COUNT(*) FROM user_info where approval in('pending','pending2')";
                                                    $result32 = mysqli_query($HRconnect, $query32) or die(mysqli_error($HRconnect));
                                                    while ($row32 = mysqli_fetch_array($result32)) {
                                                    echo "$row32[0]";
                                                }
                                                ?>
                                           </span>
                                        </div>
                                       
                                        <div class="col-auto">
                                            <i class="fa fa-user-plus fa-2x text-gray-300"></i>
                                        </div>
            
                                    </div>

                                </div>
                            </div>
                                        
                        </div>

                        <!-- Total Branch -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                <a class="text-info" href="branch.php?bcafe=bcafe" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown">Total Branch</a>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php 
                                        echo $TotalBranch;
                                                    ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-store fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Rendered Hours (Per Cut-off) -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Rendered Hours
											</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                         <?php  echo $TotalRendered; ?>
                                             Hours</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    
                
                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-4 mb-2">
                                
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Employment Status</h6>
                                </div>
                                <div class="example card-body">
                                    <h6>Total Employee's                                   
                                        <span class="float-right font-weight-bold"> 
                                        <?php if($userid == ''){ 

                                            echo $TotalEmployeeAdmin;

											}else{ 

                                            echo $TotalEmployeeStaff;											

                                            } ?>   

                                        <span></h6>
                                    <hr>
                                    

                                    <h6>Inactive Employee's 
                                        <span class="float-right font-weight-bold">
                                        <?php if($userid == ''){ 
                                        
                                        echo $Inactiveall;

                                        }else{ 

                                        echo $InactiveSingle;

                                        } 
                                        ?>

                                        <span></h6>
                                    <hr>
                                    
                                    <h6>Resigned Employee's                                     
                                        <span class="float-right font-weight-bold">
                                        <?php if($userid == ''){ 

                                        echo $Resignall;

                                        }else{ 

                                        echo $ResignSingle;

                                        } ?>

                                        <span></h6>
                                    <hr>
                                    
                                    <h6>Pin Code Employee's                                     
                                        <span class="float-right font-weight-bold">
                                        <?php if($userid == ''){ 

                                        echo $pincodeall;

                                        }else{ 

                                        echo $pincodeSingle;

                                         } ?>

                                        <span></h6>
                                    <hr>
                                    
                                    <h6 class="m-0 font-weight-bold text-primary">Rank & File</h6>
                                    <hr>
                                    
                                    <h6>Department Heads <span class="small float-right font-weight-bold"> 
                                        No Data Available   
                                    <span></h6>                                     
                                    <hr>
                                    
                                    <h6>Supervisory Employee's <span class="small float-right font-weight-bold"> 
                                        No Data Available   
                                    <span></h6>                                     
                                    <hr>
                                    
                                    <h6>Regular Employee's <span class="small float-right font-weight-bold">
                                            No Data Available
                                        </span></h6>
                                    <hr>
                                    
                                    <h6>Probationary Employee's <span class="small float-right font-weight-bold">
                                        No Data Available
                                    </span></h6>
                                    
                                    <hr>
                                    
                                    <h6>Contractual Employee's <span class="small float-right font-weight-bold">
                                        No Data Available
                                    </span></h6>    
                                        
                                </div>
                                
                                <div class="card-footer bg-white">  
                                    <?php if($userid == ''){ ?>
                                        <a class="float-right" rel="nofollow" href="pdf/viewallemp.php">View all &rarr;</a>
                                    <?php }else{ ?> 
                                        <a class="float-right" rel="nofollow" href="employeelist.php?active=active">View all &rarr;</a>
                                    <?php } ?>  
                                </div>
                            </div>
                        </div>
                        
                        
                
                    <div class="col-xl-8 col-lg-8 mb-3">
                        <div class="card shadow">
                            <!-- Card Header -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <?php
                                $cut_off1 = '2022-09-09';
                                $cut_off2 = '2022-09-23'; 
                                ?>
                                <h6 class="card-title m-0 font-weight-bold text-primary">Cut-off Details (<?php echo $cut_off1." to ".$cut_off2; ?>)</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">    
                                        <a href="#collapseot" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseot">
                                            <h6 class="m-0 font-weight-bold text-primary">                                                                                    
												Total Overtime - 
                                                <?php
                                                //QUERY TOTAL REGULAR OVERTIME
                                                $sqlOT = "SELECT SUM(othours) FROM overunder WHERE otstatus = 'approved' AND otdatefrom BETWEEN '$cut_off1' AND '$cut_off2'";
                                                $queryOT=$HRconnect->query($sqlOT);
                                                $rowOT=$queryOT->fetch_array();

                                                //QUERY TOTAL BROKEN SCHED OVERTIME
                                                $sqlOTB = "SELECT SUM(othours) FROM dtr_concerns WHERE status = 'Approved' AND ConcernDate BETWEEN '$cut_off1' AND '$cut_off2'";
                                                $queryOTB=$HRconnect->query($sqlOTB);
                                                $rowOTB=$queryOTB->fetch_array();

                                                $TotalOT1 = $rowOT['SUM(othours)'];
                                                $TotalOT2 = $rowOTB['SUM(othours)'];
                                                $ALL_OT = $TotalOT1 + $TotalOT2;

                                                echo $ALL_OT;

                                                ?>
                                            </h6>
                                        </a>

                                        <div class="collapse show" id="collapseot">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Department/Branch</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <?php
                                                            //$sqltry2 = "SELECT SUM(othours) FROM overunder 
                                                            //INNER JOIN overunder ON userinfo.empno = overunder.empno
                                                            //WHERE mothercafe =  $mothercafe AND status = 'approved' AND otdatefrom between '$cut_off1' AND '$cut_off2'";
                                                            //$result1 = mysqli_query($HRconnect, $sqltry2) or die(mysqli_error($HRconnect));
                                                            //while ($row1 = mysqli_fetch_array($result1)) 
                                                            //{ 
                                                            //    @$OT += $row1[0]; echo $OT;
                                                            //}
                                                        ?>
                                                        <?php
                                                        $sqldept = "SELECT DISTINCT `userid`,`branch` FROM user_info  LIMIT 5";
                                                        $querydept=$HRconnect->query($sqldept);
                                                        while($rowdept=$querydept->fetch_array())
                                                        {
                                                            $dept = $rowdept['userid'];
                                                        ?>
                                                        <tr>
                                                            <td>
                                                            <?php
                                                                echo $rowdept['branch'];
                                                            ?>                                                                
                                                            </td>


                                                            <?php
                                                            $sqltry = "SELECT DISTINCT SUM(othours), `userid`,`branch`, `otdatefrom`, `otstatus`, user_info.empno, user_info.branch, user_info.department FROM user_info 
                                                                INNER JOIN overunder ON user_info.empno = overunder.empno 
                                                                WHERE otstatus = 'approved' AND userid = '$dept' AND otdatefrom BETWEEN '$cut_off1' and '$cut_off2'";

                                                            $querytry=$HRconnect->query($sqltry);
                                                            while($rowtry=$querytry->fetch_array()){
                                                            ?> 
                                                            <td id = "otS">
                                                            <?php
                                                                echo $rowtry['SUM(othours)'];
                                                            ?>   
                                                            </td>
                                                            <td><a href="overtime-breakdown.php" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="discrepancy.php">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>

                                        <a href="#collapseleave" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseleave">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                Total Leave - 200
                                            </h6>
                                        </a>

                                        <div class="collapse" id="collapseleave">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Department</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="#">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>

                                        <a href="#collapseobp" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseobp">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                Total OBP - 200
                                            </h6>
                                        </a>

                                        <div class="collapse" id="collapseobp">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Department</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="#">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <a href="#collapselate" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapselate">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                Total Late - 500
                                            </h6>
                                        </a>

                                        <div class="collapse show" id="collapselate">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Department</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="#">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>

                                        <a href="#collapseut" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseut">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                Total Undertime - 1000
                                            </h6>
                                        </a>

                                        <div class="collapse" id="collapseut">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Department</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="#">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>

                                        <a href="#collapseconcern" class="d-block card-header collapsed" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseconcern">
                                            <h6 class="m-0 font-weight-bold text-primary">
                                                Total Concern - 1500
                                            </h6>
                                        </a>

                                        <div class="collapse" id="collapseconcern">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover text-center mt-2">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Branch</th>
                                                            <th>Department</th>
                                                            <th>Total Hours</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                    
                                                    <tbody>
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>

                                                        <tr>
                                                            <td>Head Office</td>
                                                            <td>Information Technology</td>
                                                            <td>100</td>
                                                            <td><a href="pending-mrf-view" class="btn border-0 btn-outline-primary btn-sm" data-toggle="tooltip" title="View"><i class="fas fa-eye"></i></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table> 
                                                <hr>
                                                <center><small>Showing 5 of 100 erntries</small></center>
                                                
                                                <h6 class="m-0 font-weight-bold text-primary mb-3 small">
                                                    <div class="d-flex flex-row-reverse">
                                                        <div><a href="#">View More</a></div>
                                                    </div>                  
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                                          
                    </div> 
				<?php   
					}
					?>
					
						<div class="col-xl-3 col-md-6 mb-4">
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
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
													echo $Totalpendingot += $Totalpendingoth;
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
													echo $Totalpendingot;
													
													}if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
													echo $Totalpendingot;												
																									
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
														OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027){ 
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
										if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
														AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 ){
										?>	
										<a href="pdf/approvals.php?ot=ot" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>    
                                    <?php   
										}
										?>
									</div>
                                </div>
                            </div>
                        </div>
						
						<div class="col-xl-3 col-md-6 mb-4">
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
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 1073){ 
													echo $Totalpendingobp += $Totalpendingobph;
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 3071){ 
													echo $Totalpendingobp;
													
													}if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221){ 
													echo $Totalpendingobp;												
																									
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
														OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027){ 
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
										if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
														AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 ){
										?>
										<a href="pdf/approvals.php?obp=obp" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a> 
									<?php   
										}
										?>	
									</div>
									 
                                </div>
                            </div>
                        </div>

						<div class="col-xl-3 col-md-6 mb-4">
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
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 1073 ){ 
													echo $Totalpendingvl += $Totalpendingvlh;
													
													}if($userlevel == 'ac' AND $_SESSION['empno'] == 3071 ){ 
													echo $Totalpendingvl;
													
													}if($_SESSION['empno'] == 107 OR $_SESSION['empno'] == 2221 ){ 
													echo $Totalpendingvl;
						
													}if($_SESSION['empno'] == 271 OR $userlevel == 'ac' AND $_SESSION['empno'] == 71 OR $_SESSION['empno'] == 1964 OR $_SESSION['empno'] == 3294 OR $_SESSION['empno'] == 3107 OR $_SESSION['empno'] == 3336 OR $_SESSION['empno'] == 3111
														OR $_SESSION['empno'] == 159 OR $_SESSION['empno'] == 3235 OR $_SESSION['empno'] == 3027){ 
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
										if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111
														AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 ){
										?>	
										<a href="pdf/approvals.php?vl=vl" data-toggle="tooltip" data-placement="top" title="Click to view detailed breakdown" class="effect-shine float-right" rel="nofollow" href="#">View &rarr;</a>   
                                    <?php   
										}
										?>
									</div>
                                </div>
                            </div>
                        </div>

						<div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-bottom-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Pending Concern (Per Cut-off)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <?php
                                    $datestart = '2024-04-09';
                                    $dateend = '2024-04-23';
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

                                    }else if($userlevel == 'ac' AND  $_SESSION['empno'] != 819 AND $_SESSION['empno'] != 4378 AND $_SESSION['empno'] != 1331 AND $_SESSION['empno'] != 1073 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 76 AND $_SESSION['empno'] != 109 AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 37 AND $_SESSION['empno'] != 53 AND $_SESSION['empno'] != 45 AND $_SESSION['empno'] != 69 AND $_SESSION['empno'] != 124 AND $_SESSION['empno'] != 2720 AND $_SESSION['empno'] != 63 AND $_SESSION['empno'] != 88 AND $_SESSION['empno'] != 97 AND $_SESSION['empno'] != 170 AND $_SESSION['empno'] != 38 AND $_SESSION['empno'] != 112 AND $_SESSION['empno'] != 254 AND $_SESSION['empno'] != 302 AND $_SESSION['empno'] != 460 AND $_SESSION['empno'] != 2094 AND $_SESSION['empno'] != 159){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency', '$FPError', '$forgot1','$BrokenOT', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2') AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";        
                                        
                                    }else if($_SESSION['empno'] == 1){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(4378,3336,3294,3235,3111,3107,3071,3027,2221,1331,1073,271,107,24,4625,6472) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 2){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE empno in(3177,4625,885,4378,3336,3294,3235,3111,3107,3071,3027,2221,1331,1073,271,107,24,4625,6472) AND status = 'Pending' AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 4){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(107) AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval','$BrokenOT', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 4378){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(1348,1964,2957,4349,2111,2243,3332,3693,4000) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 1331){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(819,109,76,71,167) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 1073){    
                                         $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid in (3,80,167,92, 170,168, 169) AND concern IN ('$emergency', '$FPError', '$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) OR empno = 1844 AND concern IN ('$emergency', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

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
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(38,112,254,302) AND concern IN ('$emergency', '$BrokenOT','$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 38 || $_SESSION['empno'] == 112 || $_SESSION['empno'] == 254 || $_SESSION['empno'] == 302 || $_SESSION['empno'] == 460 || $_SESSION['empno'] == 2094 ){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND concern IN ('$emergency', '$FPError','$BrokenOT', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' ) AND area = 'NORTH' AND userlevel = 'mod' AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 71){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND empno in(309,197,158) AND concern IN ('$emergency','$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 6538){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND userid = '$userid' AND concern IN ('$emergency','$BrokenOT', '$FPError', '$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 3107){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(3167,1075,957,884) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";

                                    }else if($_SESSION['empno'] == 3235){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(159) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    

                                    }else if($_SESSION['empno'] == 3336){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(401,3780,4814) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    
                                    }else if($_SESSION['empno'] == 2221){
                                        $sql0 = "SELECT COUNT(*) FROM dtr_concerns WHERE status = 'Pending' AND empno in(1262) AND concern IN ('$emergency', '$FPError', '$BrokenOT','$forgot1', '$forgot2', '$forgot3', '$wrong', '$timeInterval', '$removeLogs', '$cancel1', '$cancel2' )  AND ConcernDate BETWEEN '$datestart' AND '$dateend'";    
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
										if($userlevel == 'master' OR $userlevel == 'admin' AND $_SESSION['empno'] != 1348 AND $_SESSION['empno'] != 271 OR $userlevel == 'ac' AND $_SESSION['empno'] != 71 AND $_SESSION['empno'] != 1964 AND $_SESSION['empno'] != 3294 AND $_SESSION['empno'] != 3107 AND $_SESSION['empno'] != 2221 AND $_SESSION['empno'] != 3336 AND $_SESSION['empno'] != 3111 AND $_SESSION['empno'] != 159 AND $_SESSION['empno'] != 3235 AND $_SESSION['empno'] != 3027 AND $_SESSION['empno'] != 107 AND $_SESSION['empno'] != 2684 AND $_SESSION['empno'] != 3071 AND $_SESSION['empno'] != 4298 AND $_SESSION['empno'] != 3178){
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
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer">
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
    
    <!-- Settings Modal-->
    <form method="POST" enctype="multipart/form-data">

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-cogs fa-fw"></i> Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          </div>
          
           
          <div class="modal-body">

            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Login Name:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="loginname" value="<?php echo $_SESSION['user']['loginname']; ?>" readonly>
            </div>
          
            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Username:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="username" value="<?php echo $_SESSION['user']['username']; ?>" readonly>
            </div>
            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Fullname:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="username" value="<?php echo $user; ?>" readonly>
            </div>
              

            <div class="form-group ">
                <label for="recipient-name" class="col-form-label">Default/Old Password:</label>
                <input type="password" class="form-control text-center"  name="password1" maxlength="8" required> 
            </div>

            
            <div class="form-group ">
                <label for="recipient-name" class="col-form-label">New Password:</label>
                <input type="password" pattern="[0-9]*" inputmode="numeric" placeholder="Please input numbers only maximum of 8 numbers" maxlength="8" class="form-control text-center" id="myInput" 
                name="password2" required> 
            </div>
            
            <div class="float-right">   
                <input  type="checkbox" onclick="myFunction()"> <small class="text-muted" >Show Password</small>
            </div> 
            
            <input type="text" name="empno1" hidden value="<?php echo $_SESSION['empno']; ?>"> 

    </form>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="update" class="btn btn-success bg-gradient-success">Update</button>
          </div>
        </div>
        </div>
    </div>
	
	<!-- Force change pass Modal-->
    <form method="POST" enctype="multipart/form-data">

    <div id="changepass" class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"> <i class="fas fa-cogs fa-fw"></i> Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          </div>
          
           
          <div class="modal-body">

            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Login Name:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="loginname" value="<?php echo $_SESSION['user']['loginname']; ?>" readonly>
            </div>
          
            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Username:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="username" value="<?php echo $_SESSION['user']['username']; ?>" readonly>
            </div>
            <div class="form-group d-none">
                <label for="recipient-name" class="col-form-label">Fullname:</label>
                <input type="text" class="form-control text-center" id="recipient-name" name="username" value="<?php echo $user; ?>" readonly>
            </div>
              

            <div class="form-group ">
                <label for="recipient-name" class="col-form-label">Default/Old Password:</label>
                <input type="password" class="form-control text-center"  name="password1" maxlength="8" required> 
            </div>

            
            <div class="form-group ">
                <label for="recipient-name" class="col-form-label">New Password:</label>
                <input type="password" pattern="[0-9]*" inputmode="numeric" placeholder="Please input numbers only maximum of 8 numbers" maxlength="8" class="form-control text-center" id="myInput" 
                name="password2" required> 
            </div>
            
            <div class="float-right">   
                <input  type="checkbox" onclick="myFunction()"> <small class="text-muted" >Show Password</small>
            </div> 
            
            <input type="text" name="empno1" hidden value="<?php echo $_SESSION['empno']; ?>"> 

    </form>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary bg-gradient-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="update" class="btn btn-success bg-gradient-success">Update</button>
          </div>
        </div>
        </div>
    </div>
    
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
    
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
    
	<!-- <script>
	$(document).ready(function(){
		$("#changepass").modal('show');
	});
	</script> -->

    <script>
        function myFunction() {
            var x = document.getElementById("myInput");
            if (x.type === "password") {
            x.type = "text";
            } else {
            x.type = "password";
            }
            
            var x = document.getElementById("myInput1");
            if (x.type === "password") {
            x.type = "text";
            } else {
            x.type = "password";
            }
        }
    </script>
    
    <script type="text/javascript">
        // object literal holding data for option elements
        var Select_List_Data = {
        
        'choices': { // name of associated select box
        
    <?php
         $sql11 = "SELECT DISTINCT mothercafe FROM category ";
         $query11=$TKconnect->query($sql11);
        while($row11=$query11->fetch_array()){

                   $mothercafe = $row11['mothercafe']; 
                                                ?>

            <?php echo $row11['mothercafe'];  ?>: {
                    text: ["",<?php 

                         $sql2 = "SELECT * FROM category where mothercafe = '$mothercafe'";
                      $query2=$TKconnect->query($sql2);
                while($row2=$query2->fetch_array()){


                        echo "'".$row2['catname']. "',";

        }
                        ?>],

                value: ["",<?php 

                         $sql3 = "SELECT * FROM category where mothercafe = '$mothercafe'";
                      $query3=$TKconnect->query($sql3);
                while($row3=$query3->fetch_array()){


                        echo "'".$row3['catid']. "',"; 

        }
                        ?>]    

                },
                 

                 <?php } ?>
            }   
          
         
        };


        var Select_List_Data2 = {
            
            'choices2': { // name of associated select box
                
                <?php
                      $sql1 = "SELECT * FROM category ";
                      $query1=$TKconnect->query($sql1);
                while($row1=$query1->fetch_array()){
                   $catid = $row1['catid']; 
                                                ?>

            <?php echo $row1['catid']; ?>: {
                    text: ["", <?php 

                         $sql2 = "SELECT * FROM subcategory where catid = '$catid'";
                      $query2=$TKconnect->query($sql2);
                while($row2=$query2->fetch_array()){


                        echo "'".$row2['subname']. "',"; 

        }
                        ?>],

                value: ["",<?php 

                         $sql3 = "SELECT * FROM subcategory where catid = '$catid'";
                      $query3=$TKconnect->query($sql3);
                while($row3=$query3->fetch_array()){


                        echo "'".$row3['subid']. "',"; 

        }
                        ?>]    

                },
                 

                 <?php } ?>
            }   
        };

        // removes all option elements in select box 
        // removeGrp (optional) boolean to remove optgroups
        function removeAllOptions(sel, removeGrp) {
            var len, groups, par;
            if (removeGrp) {
                groups = sel.getElementsByTagName('optgroup');
                len = groups.length;
                for (var i=len; i; i--) {
                    sel.removeChild( groups[i-1] );
                }
            }
            
            len = sel.options.length;
            for (var i=len; i; i--) {
                par = sel.options[i-1].parentNode;
                par.removeChild( sel.options[i-1] );
            }
        }

        function removeAllOptions2(sel2, removeGrp) {
            var len, groups, par;
            if (removeGrp) {
                groups = sel2.getElementsByTagName('optgroup');
                len = groups.length;
                for (var i=len; i; i--) {
                    sel2.removeChild( groups[i-1] );
                }
            }
            
            len = sel2.options.length;
            for (var i=len; i; i--) {
                par = sel2.options[i-1].parentNode;
                par.removeChild( sel2.options[i-1] );
            }
        }

        function appendDataToSelect(sel, obj) {
            var f = document.createDocumentFragment();
            var labels = [], group, opts;
            
            function addOptions(obj) {
                var f = document.createDocumentFragment();
                var o;
                
                for (var i=0, len=obj.text.length; i<len; i++) {
                    o = document.createElement('option');
                    o.appendChild( document.createTextNode( obj.text[i] ) );
                    
                    if ( obj.value ) {
                        o.value = obj.value[i];
                    }
                    
                    f.appendChild(o);
                }
                return f;
            }
            
            if ( obj.text ) {
                opts = addOptions(obj);
                f.appendChild(opts);
            } else {
                for ( var prop in obj ) {
                    if ( obj.hasOwnProperty(prop) ) {
                        labels.push(prop);
                    }
                }
                
                for (var i=0, len=labels.length; i<len; i++) {
                    group = document.createElement('optgroup');
                    group.label = labels[i];
                    f.appendChild(group);
                    opts = addOptions(obj[ labels[i] ] );
                    group.appendChild(opts);
                }
            }
            sel.appendChild(f);
        }


        function appendDataToSelect2(sel2, obj2) {
            var f = document.createDocumentFragment();
            var labels = [], group, opts;
            
            function addOptions(obj2) {
                var f = document.createDocumentFragment();
                var o;
                
                for (var i=0, len=obj2.text.length; i<len; i++) {
                    o = document.createElement('option');
                    o.appendChild( document.createTextNode( obj2.text[i] ) );
                    
                    if ( obj2.value ) {
                        o.value = obj2.value[i];
                    }
                    
                    f.appendChild(o);
                }
                return f;
            }
            
            if ( obj2.text ) {
                opts = addOptions(obj2);
                f.appendChild(opts);
            } else {
                for ( var prop in obj2 ) {
                    if ( obj2.hasOwnProperty(prop) ) {
                        labels.push(prop);
                    }
                }
                
                for (var i=0, len=labels.length; i<len; i++) {
                    group = document.createElement('optgroup');
                    group.label = labels[i];
                    f.appendChild(group);
                    opts = addOptions(obj2[ labels[i] ] );
                    group.appendChild(opts);
                }
            }
            sel2.appendChild(f);
        }


        // anonymous function assigned to onchange event of controlling select box
        document.forms['demoForm'].elements['dept'].onchange = function(e) {
            // name of associated select box
            var relName = 'choices';
            var relName2 = 'choices2';

            // reference to associated select box 
            var relList = this.form.elements[ relName ];
             var relList2 = this.form.elements[ relName2 ];
            
            // get data from object literal based on selection in controlling select box (this.value)
            var obj = Select_List_Data[ relName ][ this.value ];
            var obj2 = Select_List_Data2[ relName2 ][ this.value ];

            // remove current option elements
            removeAllOptions(relList, true);
            removeAllOptions2(relList2, true);
            
            // call function to add optgroup/option elements
            // pass reference to associated select box and data for new options
            appendDataToSelect(relList, obj);
            appendDataToSelect2(relList2, obj2);
        };

        document.forms['demoForm'].elements['choices'].onchange = function(e) {
            // name of associated select box
            var relName2 = 'choices2';
            
            // reference to associated select box 
            var relList2 = this.form.elements[ relName2 ];
            
            // get data from object literal based on selection in controlling select box (this.value)
            var obj2 = Select_List_Data2[ relName2 ][ this.value ];
            
            // remove current option elements
            removeAllOptions2(relList2, true);
            
            // call function to add optgroup/option elements
            // pass reference to associated select box and data for new options
            appendDataToSelect2(relList2, obj2);
        };


        // populate associated select box as page loads
        (function() { // immediate function to avoid globals
            
            var form = document.forms['demoForm'];
            
            // reference to controlling select box
            var sel = form.elements['dept'];
            var sel2 = form.elements['choices'];
            sel.selectedIndex = 0;
            sel2.selectedIndex = 0;
            
            // name of associated select box
            var relName = 'choices';
            var relName2 = 'choices2';

            // reference to associated select box
            var rel = form.elements[ relName ];
            var rel2 = form.elements[ relName2 ];
            
            // get data for associated select box passing its name
            // and value of selected in controlling select box
            var data = Select_List_Data[ relName ][ sel.value ];
            var data2 = Select_List_Data2[ relName2 ][ sel2.value ];

            // add options to associated select box
            appendDataToSelect(rel, data);
            appendDataToSelect2(rel2, data2);

        }());

        (function() { // immediate function to avoid globals
            
            var form = document.forms['demoForm'];
            
            // reference to controlling select box
            var sel2 = form.elements['choices'];
            sel2.selectedIndex = 0;
            
            // name of associated select box
            var relName2 = 'choices2';
            // reference to associated select box
            var rel2 = form.elements[ relName2 ];
            
            // get data for associated select box passing its name
            // and value of selected in controlling select box
            var data2 = Select_List_Data2[ relName2 ][ sel2.value ];
            
            // add options to associated select box
            appendDataToSelect(rel2, data2);
            
        }());
    </script>
    
          
</body>

</html>