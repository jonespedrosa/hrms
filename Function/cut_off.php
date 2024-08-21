<?php
    $cutoff_all = "SELECT DISTINCT datefrom, dateto FROM `hrms`.`sched_info`";
    $stmt = $HRconnect->prepare($cutoff_all);
    $stmt->execute();
    $stmt->bind_result($datefrom, $dateto);

    $data = array(); // Array to store the fetched data

    while ($stmt->fetch()) {
        $data[] = array(
            'datefrom' => $datefrom,
            'dateto' => $dateto
        );
    }
    $stmt->close();

    $current_cutfrom = $data[count($data) - 1]["datefrom"];
    $current_cutto = $data[count($data) - 1]["dateto"]; 
?>