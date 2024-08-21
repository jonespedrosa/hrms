<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow d-flex justify-content-center align-items-center"> 
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    
    <p class="m-0 font-weight-bold">Mary Grace | Car Booking</p>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto d-flex align-items-center">
        <?php 
            $sql = "SELECT name FROM `hrms`.`user_info` WHERE empno = ?";
            $stmt = $HRconnect->prepare($sql);
            $stmt->bind_param("i", $_SESSION["car_reservation_user"]);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_array();
            $stmt->close();
        ?>
        <p class="m-0 d-sm-block d-none"><?php echo $result["name"];?></p>
        <i class="fa fa-user ml-3" aria-hidden="true"></i>
    </ul> 
</nav>
<!-- End of Topbar -->