<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    //entry.php  
    ob_start();
    session_start();

    // FUNCTIONS FOR FORGOT PASSWORD
    include("Function/forgot_password_func.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mary Grace Foods Inc.</title>
    <link rel="stylesheet" href="css/navbar-header-mg.css">
    <link rel="icon" href="images/logoo.png">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alkatra:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.2.min.js" integrity="sha256-2krYZKh//PcchRtd+H+VyyQoZ/e3EcrkxhM8ycwASPA=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- ajax -->
    <script src="js/ajax-call.js"></script>
</head>
<body>
    <?php     
        // Navbar
        include("navbar-header-mg.php");
    ?>

    <!-- FORGOT PASSWORD -->
    <div class="container justify d-flex justify-content-center my-5">
        <div class="card col-xl-7 col-lg-6 rounded box-shadow p-0">
            <div class="card-header">
				<h5 class="text-center m-0">Forgot Password?</h5>
            </div>

            <div class="card-body card-white-bg">
                <div class="d-flex flex-column justify-content-center">
					<center><img src="images/forgotpass.png" alt="" class="img-thumbnail border-0" width="300" height="300"><center>	
					<p class="container text-center text-dark small-font">To assist you in recovering your password, enter your active email address, and the system will send you a link to reset your password.</p>
                    <!-- FORM PASSWORD SENDING EMAIL -->
                    <form id="form-forgot-password">
                        <div class="input-group p-3">
                            <input id="email_forgot_password" class="form-control" type="email" name="email_resetpass" placeholder="Email" required>
                            <div class="input-group-append">
                                <button class="btn btn-primary d-flex align-items-center" id="submit_btn">
                                    <i class="fa fa-paper-plane mr-2" aria-hidden="true"></i>
                                    <span class="" role="status" aria-hidden="true"></span>
                                    <p class="m-0 d-sm-block d-none">Send</p>
                                </button>
                            </div>
                        </div>
                    </form>           
                </div>
            </div>

            <div class="card-footer">
                <a href="index.php" class="small-font">
                    <i class="fa fa-angle-left mr-3" aria-hidden="true"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="pb-4" style="margin-top: 30vh">				
        <div class="container my-auto"> 
            <hr>	
            <div class="text-center">
                <a style="color:#7E0000;font-family:Times New Roman, cursive;font-size:100%;" href="/video/tutorial.php"><i>Tutorial</i></a>
                <a style="color:#7E0000;font-family:Times New Roman, cursive;font-size:100%;" href="/video/faqs.php"><i>FAQs</i></a>
            </div>
            <br>
            <div class="copyright text-center my-auto">
                <span>Copyright © Mary Grace Foods Inc. 2019</span>
            </div>								
        </div>
    </footer>
</body>
</html>


<?php
    ob_end_flush();
?>