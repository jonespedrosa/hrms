<?php
$connect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();

require ("printedit_data.php");
include ("../compress-sched/compress_access.php");
// if(!isset($_SESSION['user_validate'])){
//     header("Location:../index.php?&m=2");
// }
// validate if empsched is present
if (!isset($_SESSION["emp_sched"])) {
    header("location:../../viewsched.php?current=current&m=2");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../../images/logoo.png">
    <!-- CSS libraries -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- JavaScript libraries -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="../../js/ajax-call.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- MOMENT JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Include jQuery -->

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

        .myTable td,
        .myTable th {
            padding: 5px;
            border: 2px solid black;

        }

        input[type=number] {
            width: 50%;
        }

        .box-shadow {
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        }

        .bg-dirty-white {
            background: #F6F6F6;
        }

        .disabled {
            pointer-events: none;
            user-select: none;
            opacity: 0.4;
        }

        .font-size-small {
            font-size: 80%;
        }
    </style>
</head>

<body>
    <p style="page-break-before: always">
    <div class="col-12">

        <div class="d-flex">
            <a class="text-decoration-none text-primary d-flex align-items-center mb-2"
                href="../../viewsched.php?current=current">
                <i class="fa fa-angle-left mr-3" aria-hidden="true"></i> Back
            </a>
        </div>
        <div class="border border-1 p-3 mb-2" style="width: 40em">
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
                    <div class="col-sm-12">
                        <p class="m-0">BL - Bereavement Leave</p>
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
                    <div class="col-sm-12">
                        <p class="m-0">WDL - Wedding Leave</p>
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
                    <div class="col-sm-12">
                        <p class="m-0">NS - No Schedule</p>
                    </div>
                </div>
            </div>
        </div>
        <form method="POST">
            <table class="myTable">
                <thead>
                    <?php include ("printedit-compress-head.php"); ?>
                </thead>
                <tbody>
                    <?php include ("printedit-compress-body.php"); ?>
                </tbody>
            </table>
        </form>
    </div>
    </p>
</body>

</html>