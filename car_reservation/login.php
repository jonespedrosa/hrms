<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Mary Grace Foods Inc.</title>
    
    <link rel="icon" href="../images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/navbar-header-mg.css">

    <!-- AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="js/ajax-car.js"></script>
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
    <?php 
        if(!isset($_SESSION["car_reservation_user"])){ 
            include("../navbar-header-mg.php");
            include("pages/login_page.php");
        }else{
            header("Location:dashboard.php");
        }
    ?>
</body>
</html>

<?php
     if(isset($_GET["verify"])){
        echo '<script>
            $(function() {
            $(".thanks").delay(4000).fadeOut();
            
        });
        </script>
        <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
            <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
            <div class="toast-header bg-danger">
                <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Access Failed</h5>
                <small class="text-light">Just now</small>
            </div>
    
            <div class="toast-body">
                You must be <b class="text-danger">validated to access the system</b>.
            </div>
            </div>
        </div>';
    }
?>
<script src="../vendor/jquery/jquery.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../js/sb-admin-2.min.js"></script>
