<?php  
    $login_link = "login.php";
    $home_link = "index.php";
    if($_SERVER["REQUEST_URI"] == "/video/tutorial.php"){
        $login_link = "../hrms/login.php";
        $home_link = "../hrms/index.php";
    }
?>
<nav class="navbar navbar-expand-lg gradient-red sticky-top py-3 navbar-dark box-shadow" style="border-radius: 0 0 10px 10px">
    <div class="container">
        <a href="<?php echo $home_link?>" class="navbar-brand">
            <img src="images/logoo.png" height="40" alt=""> 
            <i class="text-light ml-3 mr-2 py-3" style="font-family:Times New Roman, cursive;font-size:100%;">Mary Grace Café</i>
        </a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="navbar-collapse collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto text-center d-flex align-items-center">
                <a href="<?php echo $login_link;?>" class="nav-item nav-link text-light font-italic" style="font-family: Times New Roman; font-size:100%;">
                <i class="far fa-user text-dark bg-light rounded-circle p-2 mr-3"></i>Login</a>
            </div>
        </div>
    </div>
</nav>

<?php
    if($_SERVER["REQUEST_URI"] != "/video/tutorial.php"){
?>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script> 
<?php
    }
?>
