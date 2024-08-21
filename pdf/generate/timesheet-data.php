<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    include("../../Function/global_timestamp.php");
    $CURRENT_DATE = $datetime->format('F d, Y'); 

    // SELECT ALL DEPARTMENT -----------------------------------------------------
    $departments = "SELECT DISTINCT db.userid, db.areatype, ui.branch, CONCAT(db.areatype,'-', ui.branch) AS concat_dept FROM `db`.`user` db
    LEFT JOIN `hrms`.`user_info` ui ON ui.userid = db.userid 
    WHERE ui.status = 'active' 
    ORDER BY areatype DESC;";
    $stmt = $HRconnect->prepare($departments);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $row_dept = $result->fetch_all(MYSQLI_ASSOC);

    // 
?>  