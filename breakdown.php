<?php
    session_start(); 
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    $userid = $_GET["branch"];

    $sql_branch = "SELECT branch FROM `hrms`.`user_info` WHERE userid = ?";
    $stmt = $HRconnect->prepare($sql_branch);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array(MYSQLI_ASSOC); 
    $branch_name = strtoupper($result["branch"]);

    $category = "";
    $categoryMappings = array(
        "overtime" => "Overtime",
        "obp" => "OBP",
        "wdo" => "WDO",
        "sched" => "Change Schedule",
        "concern" => "Concern",
        "leave" => "Leave",
        "late" => "Late",
        "overbreak" => "Overbreak",
        "undertime" => "Undertime"
    );
    
    if (isset($_GET["category"]) && isset($categoryMappings[$_GET["category"]])) {
        $category = $categoryMappings[$_GET["category"]];
    } else { 
        $category = "Unknown";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png"> 
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"/>
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css"/>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <!-- JSZip -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- PDFMake -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <!-- Buttons HTML5 -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <!-- Buttons Print -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!-- SheetJS -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Ajax -->
    <script src="js/ajax-employee-breakdown.js"></script>
    <style>
        #dataTable{
            font-size: 0.8rem;
        }
        .dt-buttons,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate .paginate_button,
        .dataTables_wrapper .dataTables_info {
            font-size: 0.8rem;
        }

        .dt-buttons{
            margin-left: 30px
        }
        table{
            min-width: 1000px;
        }

        @media print {
            .dt-print-view h1{
                font-size: 1.5rem;
                font-weight: bold;
            }
        }
    </style>
</head>
<body> 
    <div class="d-flex justify-content-center align-items-center mt-5"> 
        <h4 class="mr-3 font-weight-bold">Employee</h4>
        <img src="images/logoo.png" height = "80px" alt="">
        <h4 class="ml-3 font-weight-bold">Breakdown</h4>
    </div>
    <div class="container card mt-4 shadow-sm">
        <div class="card-header bg-transparent">
            <h5 class="font-weight-bold mt-2 text-primary" id="branch-label"><?php echo $branch_name." - ".$category; ?></h5>
            <p class="font-weight-bold mt-2 text-primary" id="cutoff"><?php echo $_GET["from"]." - ".$_GET["to"]; ?></p>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" width="100%" id="dataTable">												
                    <thead>
                        <th>Emp no.</th>
                        <th>Name</th>
                        <th>Branch/Department</th>
                        <th>Total Count</th>    
                    </thead>
            
                    <tbody class="text-uppercase" id="breakdown-emp-body">                             
                        <!-- Content -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th> 
                            <th colspan="2"></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>               
    </div> 
</body>
</html>