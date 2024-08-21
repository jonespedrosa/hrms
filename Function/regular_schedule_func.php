<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
require("regular_schedule_collections_func.php");

if (isset($_GET["action"])) {
    if ($_GET["action"] == "getSched") {

        if (isset($_SESSION["user_validate"])) {
            $empno = $_SESSION["user_validate"];
        } else {
            $empno = $_SESSION["empno"];
        }
        if (isset($_SESSION["viewPrintSched"])) {
            if ($_SESSION["viewPrintSched"] == true) {
                $empno = $_GET["empid"];
            }
        }
        echo json_encode(getTimeInputs($empno, $_GET["cutfrom"], $_GET["cutto"], $HRconnect));
    }
}
