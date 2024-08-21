<?php
include("Function/global_timestamp.php");

    if(isset($_GET['ResetPassword'])){
        if(isset($_GET['token'])){
            $reset_token = $_GET['token'];

            // validate token
            $sql_token =    "SELECT er.email_token, er.is_expired, er.created_at, ui.empno FROM user_info ui 
                            LEFT JOIN user_email ue ON ui.empno = ue.empno
                            LEFT JOIN email_reset er ON er.email = ue.email 
                            WHERE er.email_token = '$reset_token'";

            $query_token = $HRconnect->query($sql_token);
            $existing_token = $query_token->fetch_array();
            if($query_token->num_rows > 0 && checkTokenExpiry($existing_token['created_at'], $existing_token['email_token'], $timestamp, $HRconnect) && $existing_token['is_expired'] == 0){
            ?>
                <script type="text/javascript">
                $(window).on("load", function() {
                    $("#defaultPasswordModal").modal("show");
                });
                </script>
                <!-- Modal -->
                <div class="modal fade" id="defaultPasswordModal" tabindex="-1" role="dialog" aria-labelledby="defaultPasswordModal" aria-hidden="true" data-backdrop="static"> 
                    <div class="modal-dialog modal-dialog-scrollable" role="document" >
                        <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="ChangePasswordTitle">Input your New Password</h4>
                        </div>
                        <div class="modal-body contentModal" id="ChangePasswordContent">
                            <form action="index.php" method="post">
                                <input type="text" class="d-none" name="empno" value="<?php echo $existing_token['empno'];?>">
                                <div class="row">
                                    <div class="col-lg-5 col-md-12 d-flex align-items-center">
                                        <img src="images/change_password.jpg" alt="" class="img-thumbnail border-0">
                                    </div>
                                    <div class="col-lg-7 col-md-12">
                                        <p class="small-font">Create a strong password to avoid <b class="text-danger">risk!</b></p>
                                        <p class="small-font mt-3">Your password should contain the following:</p>
                                        <ul id="password_requirements">
                                            <li class="small-font text-danger">Min character = 6</li>
                                            <li class="small-font text-danger">Max character = 6</li>
                                            <li class="small-font text-danger">Min 1 lowercase character</li>
                                            <li class="small-font text-danger">Min 1 uppercase character</li>
                                            <li class="small-font text-danger">Min 1 number</li>
                                            <!-- <li class="small-font text-danger">Min 1 special characters</li> -->
                                        </ul>
                                        <p class="m-0 small-font">New Password</p>
                                        <input type="password" class="form-control password_fields" id="new_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,6}$" onkeyup="validateInput()" onfocusout="confirmPassword()" maxLength="6" required>
                                        <p class="m-0 small-font validation-field text-danger mb-2">&nbsp</p>

                                        <p class="m-0 small-font">Confirm Password</p>
                                        <input type="password" name="new_password" class="form-control mb-1 password_fields" id="confirm_password" pattern="^(?=.*[a-z])(?=.*[A-Z]).{6,6}$" onkeyup="confirmPassword()" maxLength="6" required>
                                        <p class="m-0 small-font validation-field text-danger mb-2">&nbsp</p>

                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" name="" id="showPassword" onclick="toggleShowPassword()"> 
                                            <p class="small-font ml-2 mb-0">Show Password</p>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <!-- Change Password Processing -->
                                <div class="d-none" id="changePasswordProcessing">
                                    <div class="d-flex flex-column align-items-center">
                                        <img src="images/change_password_key.gif" alt="" class="img-thumbnail border-0 col-5">
                                        <h5 class="text-primary text-center mb-5">Please wait while we update your password</h5>
                                    </div>
                                </div>
                                
                                <div class="modal-footer contentModal">
                                    <input id="change_password_prompt" value="Change Password" class="btn btn-primary small-font" name="change_password_prompt" onclick="changePasswordConfirm(confirmMessage())" disabled  style="caret-color: transparent">
                                    <input type="submit" id="hidden_btn" class="d-none" disabled>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
                expireToken($existing_token['email_token'], $HRconnect);
            }
            
            else{
                header("location:index.php?m=3");
            }
        }else{
            header("location:index.php?m=3");
        }
    }

    function checkTokenExpiry($created_at, $email_token, $timestamp, $HRconnect){
        $date_created = new DateTime($created_at);
        $interval_time = new DateInterval('PT05M');// Token Expiration Interval
        $expiration_time = $date_created->add($interval_time); 
        $expiration_time_string = $expiration_time->format('Y-m-d H:i:s');

        if(strtotime($timestamp) > strtotime($expiration_time_string)){
            $update_is_expired = "UPDATE `hrms`.`email_reset` SET `is_expired` = '1' WHERE (`email_token` = '$email_token')";
            $query_expired_token = $HRconnect->query($update_is_expired);
            return false;
        }
        return true;
    }

    function expireToken($email_token, $HRconnect){
        $expire_token = "UPDATE `hrms`.`email_reset` SET `is_expired` = '1' WHERE (`email_token` = '$email_token')";
        $query_expired_token = $HRconnect->query($expire_token);
    }

?>