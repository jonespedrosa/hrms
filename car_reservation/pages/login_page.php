<div class="container mt-5">
    <div class="row justify-content-center">

        <!-- LOGIN START -->
        <div class="col-lg-6">
            <div class="card box-shadow p-3 rounded d-flex align-items-center">
                <div class="card-body">
                    <p class="card-title text-center text-dark mb-0 h3" style="font-family: 'times new roman'">Car Reservation</p>
                    <div class="d-flex mt-4"> 
                        <form action="" method="POST" id="login-form" class="d-flex flex-column justify-content-center align-items-center ">
                            <div class="">
                                <p class="m-0 d-none text-danger" id="error-message" style="font-size: 85%"></p>
                                <input type="text" class="form-control mb-2 p-2" placeholder="Employee No." id="employee-id" onkeyup = "LimitToFour()" required>
                                <input type="password" class="form-control mb-2 p-2" placeholder="Password" id="password-id" required>
                            </div> 
                            <button type="submit" class="btn btn-primary mb-2 d-flex align-items-center px-4" id="login-id">
                                <span class="d-none spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                                <span class="">Login</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="pb-4" style="margin-top: 15vh">				
    <div class="container my-auto"> 
        <hr>	
        <br>
        <div class="copyright text-center my-auto">
            <span>Copyright © Mary Grace Foods Inc. 2019</span>
        </div>								
    </div>
</footer>