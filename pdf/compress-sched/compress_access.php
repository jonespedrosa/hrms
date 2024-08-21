<?php
    $toggle_access = "d-none";
    if($_SESSION["empno"] == 1348 || $_SESSION["empno"] == 5182  ||$_SESSION["userlevel"] == "master"){
        $toggle_access = "";
    }
    
    // $manage_access = "d-none"; 
    $manage_access = "";
    if(basename($_SERVER['SCRIPT_NAME']) == "printedit_compressed.php" && ($_SESSION["empno"] != 1348 || $_SESSION["empno"] == 5182 ||$_SESSION["userlevel"] == "master")){
        $manage_access = "";
    }
?>

