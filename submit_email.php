<script type="text/javascript">
    $(window).on("load", function () {
        $("#submitEmailModal").modal("show");
    });

    function limitToSix() {
        let OTP_field = document.getElementById("OTPfield").value;

        // Remove whitespace and special characters from the OTP field
        OTP_field = OTP_field.replace(/[^\d\s]/g, '');

        // Limit the OTP field to a maximum of six digits
        if (OTP_field.length > 6) {
            OTP_field = OTP_field.substr(0, 6);
        }
        document.getElementById("OTPfield").value = OTP_field;
    }
    function viewOTP() {
        $("#form-submit-email").toggleClass("d-none");
        $("#form-submit-otp").toggleClass("d-none");
    }
</script>
<!-- Modal -->
<div class="modal fade" id="submitEmailModal" tabindex="-1" role="dialog" aria-labelledby="submitEmailModal"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ChangePasswordTitle">You're almost there...</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body contentModal" id="ChangePasswordContent">

                <form id="form-submit-email">
                    <input type="text" class="d-none" name="empno" value="<?php echo $_POST['empno']; ?>">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 d-flex align-items-center">
                            <img src="images/connected.png" alt="" class="img-thumbnail border-0">
                        </div>
                        <div class="col-lg-7 col-md-12" id="submit_email_modal_div">
                            <p class="small-font">Never miss an update from your account! Simply provide your email to
                                <b class="text-primary">stay connected</b>.
                            </p>
                            <p class="m-0 small-font">Email Address</p>
                            <input id="user-email" class="form-control" type="email" name="user-email" required>
                            <p class="m-0 small-font text-primary float-right mt-1" onclick="viewOTP()"
                                style="cursor:pointer">Already have OTP?</p>
                        </div>
                    </div>
                    <br>

                    <div class="modal-footer contentModal">
                        <button class="btn btn-primary d-flex align-items-center" id="submit_btn">
                            <i class="fa fa-paper-plane mr-2" aria-hidden="true"></i>
                            <span class="" role="status" aria-hidden="true"></span>
                            <p class="m-0">Submit Email</p>
                        </button>
                    </div>
                </form>

                <form id="form-submit-otp" class="d-none">
                    <input type="text" class="d-none" name="empno" value="<?php echo $_POST['empno']; ?>">
                    <div class="row">
                        <div class="col-lg-5 col-md-12 d-flex align-items-center">
                            <img src="images/connected.png" alt="" class="img-thumbnail border-0">
                        </div>
                        <div class="col-lg-7 col-md-12" id="submit_otp_modal_div">
                            <p class="small-font">Input the <b class="text-primary small-font">6-digit OTP</b> that was
                                sent to your email.</p>
                            <input type="number" pattern="[0-9]{6}" inputmode="numeric" class="form-control text-center"
                                name="otp" placeholder="Enter 6-digit OTP" id="OTPfield" onkeyup="limitToSix()">
                        </div>
                    </div>
                    <br>
                    <div class="modal-footer contentModal d-flex justify-content-between">
                        <p class="m-0 small-font text-primary" onclick="viewOTP()" style="cursor:pointer"><i
                                class="fa fa-angle-left mr-2" aria-hidden="true"></i>Back</p>
                        <button class="btn btn-primary d-flex align-items-center" id="submit_otp">
                            <i class="fa fa-paper-plane mr-2" aria-hidden="true"></i>
                            <span class="" role="status" aria-hidden="true"></span>
                            <p class="m-0">Validate OTP</p>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>