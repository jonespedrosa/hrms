<?php
    $timezone = new DateTimeZone('UTC'); 
    $datetime = new DateTime('now', $timezone); 
    $datetime->setTimezone(new DateTimeZone('Asia/Singapore')); 
    $timestamp = $datetime->format('Y-m-d H:i:s'); 
?>