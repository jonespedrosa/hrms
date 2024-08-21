<?php

?>
<!-- Modal -->
<div class="modal fade" id="compress-sched-create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content p-3">
            <div class="modal-header d-flex align-items-center">
                <div>
                    <div class="d-flex align-items-center">
                        <h5 class="modal-title font-weight-bold text-dark" id="exampleModalLabel">Schedule Management</h5>
                        <p class="m-0 ml-2 font-size-small font-weight-bold text-success d-none" id="edit-mode-subtitle">Edit Mode</p>
                    </div>
                    <p class="m-0 font-size-small">Cut-off Details:</p>
                    <p class="m-0 font-size-small" id="cutoff-details"></p>
                </div>

                <button type="button" id="edit-btn" class="btn btn-outline-success border-0 <?php echo $manage_access;?>" disabled>
                    <i class="bi bi-pencil-square" style="font-size:150%"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- UPPER MOON -->
                <div class="row">
                    <div class="col-lg-6 col-sm-12 d-flex flex-column">
                        <div class="custom-control custom-switch <?php echo $toggle_access;?>" id="switch-div">
                            <input type="checkbox" class="custom-control-input" id="enableCompress">
                            <label class="custom-control-label" for="enableCompress" style="user-select:none">Compress</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12 d-flex flex-column justify-content-end align-items-start">
                    <!-- empty -->
                    </div>
                </div>

                <!-- LOWER MOON -->
                <div class="mt-3 font-weight-normal">
                    <div class="container">
                        <p class="m-0">Work Hours</p>
                    </div>
                    <div class="container-fluid rounded box-shadow bg-dirty-white p-3 disabled" id="work-hrs-container">
                        <div class="row" id="work-hrs-div">
                            
                        </div>
                    </div>
                </div>

                <!-- NO MOON -->
                <div class="mt-5 font-weight-normal" id="default-schedule-container">
                    <div class="container d-flex align-items-center">
                        <a class="m-0">Default Schedule:</a>
                        <a class="m-0 ml-2 font-size-small vertical-align-end" data-toggle="collapse" href="#defaultScheduleCollapse" role="button" id="toggleButton">show</a>
                    </div>
                    <div class="container-fluid rounded box-shadow bg-dirty-white p-3 collapse" id="defaultScheduleCollapse">
                        <div class="row " id="default-schedule">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer-btns">
                <div id="for-saving" class="">
                    <div class="row">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> 
                        <button type="button" class="btn btn-primary d-flex align-items-center ml-3" id="save-btn" disabled>
                            <div class="spinner-border spinner-border-sm mr-2 d-none" role="status"></div>
                            <p class="m-0">Save</p>
                        </button>
                    </div>
                </div>


                <!-- FOR EDIT -->
                <div id="for-edit" class="d-none">
                    <div class="row">
                        <button type="button" class="btn btn-danger" id="cancel-btn">Cancel</button> 
                        <button type="button" class="btn btn-primary d-flex align-items-center ml-3" id="update-btn">
                            <div class="spinner-border spinner-border-sm mr-2 d-none" role="status"></div>
                            <p class="m-0">Update</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
