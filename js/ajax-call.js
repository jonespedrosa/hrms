// FORGOT PASSWORD
$(document).ready(function () {
    $('#form-forgot-password').submit(function (event) {
        event.preventDefault(); // prevent default form submission

        // toggling spinner
        let submit_btn = $("#submit_btn");
        let span_spinner = submit_btn.find("span").eq(0);
        let icon_spinner = submit_btn.find("i").eq(0);
        let p_spinner = submit_btn.find("p").eq(0);

        submit_btn.attr('disabled', '');
        icon_spinner.toggleClass("d-none");
        span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
        p_spinner.html("Sending");

        let formData = $(this).serialize(); // get form data
        $.ajax({
            type: 'POST',
            url: "Function/forgot_password_func.php?function=forgotPassword",
            data: formData,

            success: function (response) {
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success' && validateEmail(jsonResponse.email)) {
                    $('#email_forgot_password').val("");
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Send");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // handle error response here
                Swal.fire({
                    title: 'Error!',
                    text: 'The provided email is invalid',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Send");
            }
        });
    });
});

// SUBMIT EMAIL
$(document).ready(function () {
    $('#form-submit-email').submit(function (event) {
        event.preventDefault();
        // toggling spinner  
        let submit_btn = $("#submit_btn");
        let span_spinner = submit_btn.find("span").eq(0);
        let icon_spinner = submit_btn.find("i").eq(0);
        let p_spinner = submit_btn.find("p").eq(0);

        submit_btn.attr('disabled', '');
        icon_spinner.toggleClass("d-none");
        span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
        p_spinner.html("Sending");
        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "Function/submit_email_func.php?function=submitEmail",
            data: formData,

            success: function (response) {
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success' && validateEmail(jsonResponse.email)) {
                    $("#user-email").val("");
                    let submit_email_modal = $('#submit_email_modal_div');
                    submit_email_modal.find("input").eq(0).removeAttr('required');
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });

                    $('#form-submit-email').toggleClass("d-none");
                    $('#form-submit-otp').toggleClass("d-none");
                } else if (jsonResponse.email_registered == true) {
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Submit Email");
            },

            error: function (jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error!',
                    text: 'The provided email is invalid',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: false
                });
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Submit Email");
            }
        });
    });
});

// OTP EMAIL
$(document).ready(function () {
    $('#form-submit-otp').submit(function (event) {
        event.preventDefault();
        // toggling spinner  
        let submit_btn = $("#submit_otp");
        let span_spinner = submit_btn.find("span").eq(0);
        let icon_spinner = submit_btn.find("i").eq(0);
        let p_spinner = submit_btn.find("p").eq(0);

        submit_btn.attr('disabled', '');
        icon_spinner.toggleClass("d-none");
        span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
        p_spinner.html("Sending");
        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "Function/submit_email_func.php?function=submitOTP",
            data: formData,

            success: function (response) {
                let jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success' && validateOTP(jsonResponse.otp)) {
                    $('#submitEmailModal').modal("hide");
                    Swal.fire({
                        title: jsonResponse.title,
                        text: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false
                    });

                } else {
                    Swal.fire({
                        title: "Invalid OTP",
                        text: "The provided OTP is invalid",
                        icon: "error",
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Validate OTP");
            },

            error: function (response) {
                let jsonResponse = JSON.parse(response);
                Swal.fire({
                    title: jsonResponse.title,
                    text: jsonResponse.text,
                    icon: jsonResponse.status,
                    timer: 3000,
                    showConfirmButton: false
                });
                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Validate OTP");
            }
        });
    });
});

// EMAIL VALIDATION
function validateEmail(email) {
    const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return regex.test(email);
}

// OTP VALIDATION
function validateOTP(otp) {
    if (otp.length === 6) {
        return true;
    } else {
        return false;
    }
}


// ------------------------------------- FOR FILING DOCUMENTS ----------------------------------------------- //

//OBP
$(document).ready(function () {
    $('#OBP-date').on('change', function () {
        let chosen_date = $('#OBP-date').val();
        let emp_id = $('#obp-emp-id').val();
        $.ajax({
            url: 'Function/obp_dates.php?dates=getOBP',
            type: 'GET',
            data: {
                date: chosen_date,
                empno: emp_id
            },
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                $('.timeinputs-obp').each(function (index) {
                    const key = Object.keys(jsonResponse)[index];
                    const value = jsonResponse[key];
                    $(this).val(value);
                    if (!(value == "" || value == null || value == undefined)) {
                        $(this).attr('readonly', '');
                    } else {
                        $(this).removeAttr('readonly', '');
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    });
});

// -----------------------------------FOR COMPRESS SCHEDULE----------------------------------------- //
// FOR PRINT EDIT ONLY
$(document).ready(function () {
    errorIndicator();
    let breaks = [];
    let emp_id = $("#compress-sched-btn").val();
    let datefromto = $(".cutoff-sched").eq(0).val();
    let dateto = $(".cutoff-sched").eq(1).val();
    let buttonClicked = false;
    // cutoff details
    let momentDateFrom = moment(datefromto).format("MM/DD/YYYY");
    let momentDateTo = moment(dateto).format("MM/DD/YYYY");
    $("#cutoff-details").html(momentDateFrom + " - " + momentDateTo);
    let url = "../Function/compress_schedule_func.php?sched=isCompressed";
    let url_breaks = "../Function/compress_schedule_func.php?sched=getBreaks";

    // FOR COMPRESSED VIEW
    if (window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1) {
        url = "../../Function/compress_schedule_func.php?sched=isCompressed";
        url_breaks = "../../Function/compress_schedule_func.php?sched=getBreaks";
        $("#switch-div").find("label").html("Uncompress");
        $('#edit-btn').prop('disabled', false);
    } else {
        $("#edit-btn").addClass("d-none");
        $("#default-schedule-container").addClass("d-none");
    }

    $.ajax({
        url: url_breaks,
        type: 'GET',
        data: {
            empno: emp_id,
            datefrom: datefromto,
            dateto: dateto
        },
        success: function (response) {
            let jsonResponse = JSON.parse(response);
            for (let i = 0; i < jsonResponse.result.length; i++) {
                // breaks.push(jsonResponse.result[i].break);
                breaks.push(0);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {

        }
    });
    $("#compress-sched-btn").on("click", function (e) {
        let selection = $(".selection");
        $("#compress-sched-create").modal("show");
        $("#enableCompress").prop('disabled', false);
        $('#edit-btn').prop('disabled', false);

        defaultScheduleChange(selection, "", breaks, "");

        // AJAX CALL 
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                empno: emp_id,
                datefrom: datefromto,
                dateto: dateto
            },
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                $("#enableCompress").prop('checked', false);
                $('#save-btn').prop('disabled', true);
                $("#for-saving").removeClass("d-none");
                $("#for-edit").addClass("d-none");
                $("#edit-mode-subtitle").addClass("d-none");
                if (window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1) {
                    if (jsonResponse.isCompressed) {
                        $("#work-hrs-container").addClass("disabled");
                    } else {
                        $("#work-hrs-container").removeClass("disabled");
                    }
                } else {
                    if (jsonResponse.isCompressed) {
                        $("#work-hrs-container").removeClass("disabled");
                    } else {
                        $("#work-hrs-container").addClass("disabled");
                    }
                }

                fetchDataWorkHours(emp_id, datefromto, dateto).then(function (response) {
                    let jsonResponse = JSON.parse(response);
                    let arr_remarks = ["AB", "RD", "NWD", "LWP", "ML", "PL", "SPL", "BL", "WDL"];
                    // 8 per column
                    if (!(buttonClicked == true)) {
                        buttonClicked = true;
                        // Work Hours
                        for (let i = 0; i < 2; i++) {
                            $("#work-hrs-div").append('<div class="col-lg-6 col-sm-12 hrs-div-' + i + '"></div>');
                            $("#default-schedule").append('<div class="col-lg-6 col-sm-12 sched-div-' + i + '"></div>');
                            for (let a = i * 8; a < (i + 1) * 8 && a < jsonResponse.result.length; a++) {
                                const week = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
                                let day = moment(jsonResponse.result[a].datefromto).format("d");
                                $(".hrs-div-" + i).append('<div class="d-flex align-items-center mb-3"><p class="m-0">' + jsonResponse.result[a].datefromto + ' (' + week[day] + ')</p><select id="select-id-' + a + '" class="ml-3 selection" style="flex-grow: 1;"><option value=""></option><option value="8">8 Hrs</option><option value="10" selected>10 Hrs</option><option value="NWD">No Work Day (NWD)</option><option value="RD">Rest Day (RD)</option><option value="AB">Absent (AB)</option><option value="LWP">Leave w/o Pay (LWP)</option><option value="ML">Maternity Leave (ML)</option><option value="PL">Paternity Leave (PL)</option><option value="SPL">Solo Parent Leave (SPL)</option><option value="WDL">Wedding Leave (WL)</option><option value="BL">Bereavement Leave (BL)</option><option value="NS">No Schedule (NS)</option></select></div>'
                                );

                                if ($.inArray(jsonResponse.result[a].remarks, arr_remarks) !== -1) {
                                    $("#select-id-" + a + " option[value='" + jsonResponse.result[a].remarks + "']").prop("selected", true);
                                }

                                if (jsonResponse.result[a].work_hours != null || jsonResponse.result[a].work_hours != "") {
                                    $("#select-id-" + a + " option[value='" + jsonResponse.result[a].work_hours + "']").prop("selected", true);
                                }

                                let schedfrom = moment(jsonResponse.result[a].schedfrom).format("HH:mm");
                                let schedto = moment(jsonResponse.result[a].schedto).format("HH:mm");
                                $(".sched-div-" + i).append('<div class="col-sm-12"><div class="d-flex flex-column mb-2"><p class="m-0" style="font-size: 90%">' + jsonResponse.result[a].datefromto + '</p><div class="d-flex align-items-center"><p class="m-0" style="font-size: 90%" id="original-sched-' + a + '">' + schedfrom + ' - ' + schedto + '</p><i class="bi bi-arrow-right mx-1"></i><p class="m-0" style="font-size: 90%" id="changed-sched-' + a + '">No Changes</p></div></div></div>'
                                );
                            }
                        }
                    }
                }).catch(function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    });

    $("#enableCompress").on("change", function (e) {
        if (!(window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1)) {
            let switchValue = $(this).is(':checked');
            let selection = $(".selection");
            let selectionValues = [];
            if (switchValue) {
                // $("#work-hrs-container").toggleClass("disabled");
                $('#save-btn').prop('disabled', function (index, oldValue) {
                    return !oldValue;
                });
                $(".selection").each(function () {
                    var selectedValue = $(this).val();
                    if (!selectedValue) {
                        allSelectionsFilled = false;
                        return false;
                    }
                    selectionValues.push(selectedValue);
                });
                defaultScheduleChange(selection, selectionValues, breaks, "");
            } else {
                // $("#work-hrs-container").toggleClass("disabled");
                $('#save-btn').prop('disabled', function (index, oldValue) {
                    return !oldValue;
                });
                defaultScheduleChange(selection, "", breaks, "");
            }
        } else {
            let switchValue = $(this).is(':checked');
            let selection = $(".selection");
            if (switchValue) {
                $('#save-btn').prop('disabled', function (index, oldValue) {
                    return !oldValue;
                });
                $('#edit-btn').prop('disabled', true);
                defaultScheduleChange(selection, 8, breaks, "");
            } else {
                $('#save-btn').prop('disabled', function (index, oldValue) {
                    return !oldValue;
                });
                $('#edit-btn').prop('disabled', false);
                defaultScheduleChange(selection, "", breaks, "");
            }
        }
    });

    // Default schedule Preview
    $(document).on("change", ".selection", function (e) {
        const selectValue = $(this).val();
        const position = $(".selection").index(this);
        let selection = $(".selection");
        defaultScheduleChange(selection, selectValue, breaks, position);
    });

    $("#toggleButton").click(function () {
        $(this).text(function (i, text) {
            return text === "show" ? "hide" : "show";
        });
    });

    // SAVE BUTTON
    $("#save-btn").on("click", function () {
        // loader spinner
        $(this).find("div").removeClass("d-none");
        $(this).find("p").html("Saving");
        if (!(window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1)) {
            var allSelectionsFilled = true;
            let selectionValues = [];
            // let breaks = [];

            // $(".breaks").each(function() {
            //     let selectedBreaks = $(this).val();
            //     breaks.push(selectedBreaks);
            // });
            $(".selection").each(function () {
                var selectedValue = $(this).val();
                if (!selectedValue) {
                    allSelectionsFilled = false;
                    return false;
                }
                selectionValues.push(selectedValue);
            });
            if (allSelectionsFilled) {
                saveDataWorkHours(emp_id, datefromto, dateto, breaks, selectionValues).then(function (response) {
                    console.log(response);
                    let jsonResponse = JSON.parse(response);
                    $("#save-btn").find("div").addClass("d-none");
                    $("#save-btn").find("p").html("Save");
                    Swal.fire({
                        title: jsonResponse.text,
                        icon: jsonResponse.status,
                        timer: 3000,
                        showConfirmButton: false,
                        willClose: function () {
                            location.reload();
                        }
                    });
                }).catch(function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR, textStatus, errorThrown);
                });
            } else {
                $("#save-btn").find("div").addClass("d-none");
                $("#save-btn").find("p").html("Save");
                Swal.fire({
                    title: 'Wait!',
                    text: 'One or more selections are empty. Please fill all selections before saving.',
                    icon: 'warning',
                    confirmButtonText: 'Understood'
                });
            }
        } else {
            Swal.fire({
                title: 'Confirm',
                text: 'Are you sure you want to perform this action?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    // let breaks = [];

                    // $(".breaks").each(function() {
                    //     let selectedBreaks = $(this).val();
                    //     breaks.push(selectedBreaks);
                    // });
                    uncompressTime(emp_id, datefromto, dateto, breaks).then(function (response) {
                        const id = $("#sched-info-id").val();
                        let jsonResponse = JSON.parse(response);
                        $("#save-btn").find("div").addClass("d-none");
                        $("#save-btn").find("p").html("Save");
                        Swal.fire({
                            title: jsonResponse.text,
                            icon: jsonResponse.status,
                            timer: 3000,
                            showConfirmButton: false,
                            willClose: function () {
                                window.location.href = "/hrms/pdf/printedit.php?id=" + id;
                            }
                        });
                    }).catch(function (jqXHR, textStatus, errorThrown) {
                        $("#save-btn").find("div").addClass("d-none");
                        $("#save-btn").find("p").html("Save");
                        console.log(jqXHR, textStatus, errorThrown);
                    });
                } else {
                    $("#save-btn").find("div").addClass("d-none");
                    $("#save-btn").find("p").html("Save");
                }
            });
        }
    });

    // EDIT BUTTON FOR COMPRESSED SCHED-----------------------------------
    $("#edit-btn").on("click", function () {
        $("#edit-mode-subtitle").removeClass("d-none");
        $("#for-saving").addClass("d-none");
        $("#for-edit").removeClass("d-none");
        $("#enableCompress").prop('disabled', true);
        $("#work-hrs-container").removeClass("disabled");
    });

    $("#cancel-btn").on("click", function () {
        $("#edit-mode-subtitle").addClass("d-none");
        $("#for-saving").removeClass("d-none");
        $("#for-edit").addClass("d-none");
        $("#enableCompress").prop('disabled', false);
        $("#work-hrs-container").addClass("disabled");
    });

    $("#update-btn").on("click", function () {
        $(this).find("div").removeClass("d-none");
        $(this).find("p").html("Updating");
        let allSelectionsFilled = true;
        let selectionValues = [];
        // let breaks = [];

        // $(".breaks").each(function() {
        //     let selectedBreaks = $(this).val();
        //     breaks.push(selectedBreaks);
        // });
        $(".selection").each(function () {
            let selectedValue = $(this).val();
            if (!selectedValue) {
                allSelectionsFilled = false;
                return false;
            }
            selectionValues.push(selectedValue);
        });

        if (allSelectionsFilled) {
            saveDataWorkHours(emp_id, datefromto, dateto, breaks, selectionValues).then(function (response) {
                console.log(response);
                let jsonResponse = JSON.parse(response);
                $("#update-btn").find("div").addClass("d-none");
                $("#update-btn").find("p").html("Update");
                Swal.fire({
                    title: "Schedule Updated",
                    icon: jsonResponse.status,
                    timer: 3000,
                    showConfirmButton: false,
                    willClose: function () {
                        location.reload();
                    }
                });
            }).catch(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            });
        } else {
            $("#update-btn").find("div").addClass("d-none");
            $("#update-btn").find("p").html("Update");
            Swal.fire({
                title: 'Wait!',
                text: 'One or more selections are empty. Please fill all selections before saving.',
                icon: 'warning',
                confirmButtonText: 'Understood'
            });
        }
    });
});

function fetchDataWorkHours(emp_id, datefromto, dateto) {
    if (window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1) {
        return $.ajax({
            url: "../../Function/compress_schedule_func.php?sched=schedTime",
            type: 'GET',
            data: {
                empno: emp_id,
                datefrom: datefromto,
                dateto: dateto
            }
        });
    }
    return $.ajax({
        url: "../Function/compress_schedule_func.php?sched=schedTime",
        type: 'GET',
        data: {
            empno: emp_id,
            datefrom: datefromto,
            dateto: dateto
        }
    });
}

function saveDataWorkHours(emp_id, datefromto, dateto, array_breaks, array) {
    if (window.location.pathname.indexOf("/hrms/pdf/compress-printedit/printedit_compressed.php") !== -1) {
        return $.ajax({
            url: "../../Function/compress_schedule_func.php?sched=saveTime",
            type: 'GET',
            data: {
                empno: emp_id,
                datefrom: datefromto,
                dateto: dateto,
                breaks: array_breaks,
                response: array
            }
        });
    }
    return $.ajax({
        url: "../Function/compress_schedule_func.php?sched=saveTime",
        type: 'GET',
        data: {
            empno: emp_id,
            datefrom: datefromto,
            dateto: dateto,
            breaks: array_breaks,
            response: array
        }
    });
}

function uncompressTime(emp_id, datefromto, dateto, array_breaks) {
    return $.ajax({
        url: "../../Function/compress_schedule_func.php?sched=uncompress",
        type: 'POST',
        data: {
            empno: emp_id,
            datefrom: datefromto,
            dateto: dateto,
            breaks: array_breaks
        }
    });
}

function defaultScheduleChange(selection, selectValue, emp_breaks, position) {
    let calculation;
    if (!isNaN(selectValue)) {
        let timeRange = $("#original-sched-" + position).text();
        const startTime = timeRange.split(" - ")[0];
        const endTime = timeRange.split(" - ")[1];
        calculation = moment(startTime, "HH:mm").add(parseInt(selectValue) + emp_breaks[position], 'hours').format("HH:mm");
        if (endTime == calculation) {
            calculation = "No Changes";
        }
    }

    // Retrieve the value of the select element
    const choices = {
        "": "No Changes",
        10: calculation || "", // Use an empty string as the default value if calculation is undefined
        8: calculation || "", // Use an empty string as the default value if calculation is undefined
        AB: "Absent (AB)",
        NWD: "No Work Day (NWD)",
        RD: "Rest Day (RD)",
        LWP: "Leave w/o Pay (LWP)",
        ML: "Maternity Leave (ML)",
        PL: "Paternity Leave (PL)",
        SPL: "Solo Parent Leave (SPL)",
        BL: "Bereavement Leave (BL)",
        WDL: "Wedding Leave (WDL)",
    };

    if (selectValue == "" || calculation == "No Changes") {
        $("#changed-sched-" + position).removeClass("text-success");
    } else {
        $("#changed-sched-" + position).addClass("text-success");
    }
    $("#changed-sched-" + position).html(choices[selectValue]);
}

function errorIndicator() {
    // hooks
    let userlevel = $("input#userlevel").val();
    let datefrom = $(".date-from");
    let schedfrom = $(".schedfrom");
    let schedto = $(".schedto");
    let breaks = $(".breaks");
    let mtimein = $(".m-time-in");
    let mtimeout = $(".m-time-out");
    let atimein = $(".a-time-in");
    let atimeout = $(".a-time-out");
    let workhours = $("input.work-hours[value]");
    let actual_dateto = $("input.scheduled-out[value]");

    let schedfrom_hour = $(".schedfrom_hour");
    let schedfrom_min = $(".schedfrom_min");
    let schedto_hour = $(".schedto_hour");
    let schedto_min = $(".schedto_min");
    let i = 0;



    workhours.each(function () {
        let actual_out = moment(actual_dateto.eq(i).val(), "YYYY-MM-DD HH:mm").format("MM-DD-YYYY");
        let userlevel_schedule_in;
        let userlevel_schedule_out;
        let userlevel_mtimein;
        let userlevel_mtimeout;
        let userlevel_atimein;
        let userlevel_atimeout;
        if (userlevel == "master") {
            userlevel_schedule_in = moment(datefrom.eq(i).html() + " " + schedfrom.eq(i).val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            userlevel_schedule_out = moment(actual_out + " " + schedto.eq(i).val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            userlevel_mtimein = mtimein.eq(i).val();
            userlevel_mtimeout = mtimeout.eq(i).val();
            userlevel_atimein = atimein.eq(i).val();
            userlevel_atimeout = atimeout.eq(i).val();
        } else {
            userlevel_schedule_in = moment(datefrom.eq(i).html() + " " + schedfrom_hour.eq(i).val() + ":" + schedfrom_min.eq(i).val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            userlevel_schedule_out = moment(actual_out + " " + schedto_hour.eq(i).val() + ":" + schedto_min.eq(i).val(), 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
            userlevel_mtimein = mtimein.eq(i).html();
            userlevel_mtimeout = mtimeout.eq(i).html();
            userlevel_atimein = atimein.eq(i).html();
            userlevel_atimeout = atimeout.eq(i).html();
        }
        let hours = parseInt($(this).val());
        let scheduled_in = moment(userlevel_schedule_in);
        let scheduled_out = moment(userlevel_schedule_out);
        let timein = moment(datefrom.eq(i).html() + " " + userlevel_mtimein, 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
        let breakout = moment(datefrom.eq(i).html() + " " + userlevel_mtimeout, 'MM-DD-YYYY HH:mm');
        let breakin = moment(datefrom.eq(i).html() + " " + userlevel_atimein, 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
        let timeout = moment(datefrom.eq(i).html() + " " + userlevel_atimeout, 'MM-DD-YYYY HH:mm').format('YYYY-MM-DD HH:mm');
        let break_hours = parseInt(breaks.eq(i).val());
        let schedule_diff = (new Date(scheduled_out) - new Date(scheduled_in)) / (1000 * 60 * 60) - break_hours;

        if (new Date(timeout) < new Date(timein)) {
            timeout = moment(datefrom.eq(i).html() + " " + userlevel_atimeout, 'MM-DD-YYYY HH:mm').add(1, "day").format('YYYY-MM-DD HH:mm');
        }
        // Calculate work hours if NWD RD AB LWP
        if ($(this).val() == "AB" || $(this).val() == "LWP" || $(this).val() == "ML" || $(this).val() == "PL" || $(this).val() == "SPL" || $(this).val() == "WDL" || $(this).val() == "BL") {
            hours = schedule_diff;
        }
        else if (hours == 0 || isNaN(hours)) {
            hours = 8;
        }
        // Schedule Conditions
        if (schedule_diff > hours || schedule_diff < hours) {
            if (userlevel == "master") {
                schedfrom.eq(i).addClass("text-danger font-weight-bold");
                schedto.eq(i).addClass("text-danger font-weight-bold");
            } else {
                schedfrom_hour.eq(i).addClass("text-danger font-weight-bold");
                schedfrom_min.eq(i).addClass("text-danger font-weight-bold");
                schedto_hour.eq(i).addClass("text-danger font-weight-bold");
                schedto_min.eq(i).addClass("text-danger font-weight-bold");
            }
        }

        // Time in Conditions
        if (new Date(timein) > new Date(scheduled_in)) {
            mtimein.eq(i).addClass("text-danger font-weight-bold");
        }

        // Break out Conditions
        let break_range = breakout.add(break_hours, "hours");
        let add_break = break_range.format('YYYY-MM-DD HH:mm');

        if (userlevel_mtimeout != 0) {
            if (new Date(add_break) < new Date(breakin)) {
                atimein.eq(i).addClass("text-danger font-weight-bold");
            }
        }

        // Time out Conditions
        if (userlevel_atimeout != "" && (new Date(timeout) < new Date(scheduled_out))) {
            atimeout.eq(i).addClass("text-danger font-weight-bold");
        }
        i++;
    });
}
// FOR PRINT EDIT COMPRESS ONLY