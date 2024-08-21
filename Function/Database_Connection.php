<?php 

  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
  $TKconnect = mysqli_connect("localhost", "root", "", "ticketing");

session_start();

if(empty($_SESSION['user'])){
 header('location:login.php');
}



?>
