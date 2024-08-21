<?php
$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();
// gawa ka nalng ng session kapag magcclick ka na ng href unset nalang kapag umalis na????

if (isset($_SESSION['viewPrintSched'])) {
    $empid = $_GET["empid"];
    $datefrom = $_GET["cutfrom"];
    $dateto = $_GET["cutto"];

    // select sched info if cmp_sched
    $select_sched_info = "SELECT sched_type FROM `hrms`.`sched_info` WHERE empno = ? AND datefrom = ? AND  dateto= ? AND sched_type = ?";
    $stmt = $HRconnect->prepare($select_sched_info);
    $schedType = "cmp_sched";
    $stmt->bind_param("isss", $empid, $datefrom, $dateto, $schedType);
    $stmt->execute();
    $resultIsCompressed = $stmt->get_result();
    $row_sched = $resultIsCompressed->fetch_array();

    if ($resultIsCompressed->num_rows > 0) {
        header("Location:compress-sched/print_compressed_sched.php?empno=$empid&cutfrom=$datefrom&cutto=$dateto");
    }
} else {
    if (!isset($_SESSION['user_validate'])) {
        header("Location:../index.php?&m=2");
    }
    $empid = $_SESSION["user_validate"];
    $datefrom = $_GET["cutfrom"];
    $dateto = $_GET["cutto"];

    // select sched info if cmp_sched
    $select_sched_info = "SELECT sched_type FROM `hrms`.`sched_info` WHERE empno = ? AND datefrom = ? AND  dateto= ? AND sched_type = ?";
    $stmt = $HRconnect->prepare($select_sched_info);
    $schedType = "cmp_sched";
    $stmt->bind_param("isss", $empid, $datefrom, $dateto, $schedType);
    $stmt->execute();
    $resultIsCompressed = $stmt->get_result();
    $row_sched = $resultIsCompressed->fetch_array();

    if ($resultIsCompressed->num_rows > 0) {
        $_SESSION["compressedDTR"] = true;
        header("Location:compress-sched/print_compressed_sched.php?empno=$empid&cutfrom=$datefrom&cutto=$dateto");
    }
}

// // Easter Egg
// if(!(is_numeric($empid))){
// 	header("Location:../pageNotFound.php");
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../images/logoo.png">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="../js/ajax-regular-sched.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <!------ AUTO PRINT CODE <script>window.print();</script> -->

    <style type="text/css">
        @page {
            size: portrait
        }

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

        .myTable td {
            text-align: center;
            padding: 5px;
            border: 2px solid black;
        }

        .myTable th {
            padding: 5px;
            border: 2px solid black;
        }

        .custom-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black */
        }
    </style>
</head>

<body>
    <p style="page-break-before: always">
    <div class="col-12">
        <table class="myTable">
            <thead>
                <!-- HEADER -->
                <?php require("print-schedule-head.php"); ?>
            </thead>
            <tbody id="regular-tbody">
                <!-- INSERT DYNAMIC TABLE -->
            </tbody>
            <tfoot>
                <!-- INSERT DYNAMIC FOOTER -->
                <?php require("print-schedule-footer.php"); ?>
            </tfoot>
        </table>
        <p class="text-muted">
            <i>I CERTIFY on my honor that the above is a true and correct report of the hours of work performed, report
                of which was made daily at the time of arrival at the departure from office.</i>
        </p>
    </div>
    <div class="container-fluid">
        <div class="border border-1 p-3 mb-2">
            <p class="m-0 font-weight-bold">Legend: </p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <p class="m-0">RD - Rest Day</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">AB - Absent</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">LWP - Leave w/o Pay</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">NWD - No Work Day</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <p class="m-0">WL - Wellness Leave</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">ML - Maternity Leave</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">PL - Paternity Leave</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">SPL - Solo Parent Leave</p>
                    </div>
                </div>
                <div class="col-sm-6">

                    <div class="col-sm-12">
                        <p class="m-0">CS - Change Schedule</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">WDO - Working Day Off</p>
                    </div>
                    <div class="col-sm-12">
                        <p class="m-0">OBP - Official Business Permit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </p>
</body>

</html>