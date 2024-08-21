// Leave
$(document).ready(function () {
    $('#leaveToggle').click(function (e) {
        e.preventDefault();
        $('#leaveCollapse').collapse('toggle');
    });

    console.log(window.location.pathname);
    if (window.location.pathname == "/hrms/create-leave.php") {
        let empid = $("#empno").val();
        let cutfrom = $("#cutfrom").val();
        let cutto = $("#cutto").val();

        fetchLeave(empid);

        let holidates = [];
        $.ajax({
            url: 'Function/holidays.php?dates=getHolidays',
            type: 'GET',
            dataType: 'json',

            success: function (response) {
                for (let i = 0; i < response.holiday_dates.length; i++) {
                    holidates.push(response.holiday_dates[i].holiday_day);
                }

                // View Holiday Dates
                $('#view-holiday-dates').on('click', function () {
                    let date = response.holiday_dates[0].holiday_day;
                    let year = date.split('-')[0];
                    let bodyText = "<p>Listed Holidays for " + year + "</p></ul>";
                    for (let ctr = 0; ctr < response.holiday_dates.length; ctr++) {
                        bodyText += "<li>" + response.holiday_dates[ctr].holiday_day + "</li>";
                    }
                    bodyText += "</ul>";

                    Swal.fire({
                        title: 'Holiday Dates',
                        html: bodyText,
                        iconHtml: '<i class="fas fa-info-circle mt-0"></i>',
                        icon: 'info',
                        confirmButtonText: 'I understand',
                        showConfirmButton: true
                    });
                })

                flatpickr("#leaveDate", {
                    mode: "multiple",
                    dateFormat: "Y-m-d",
                    minDate: cutfrom,
                    maxDate: cutto,
                    disable: holidates,
                    onChange: function (selectedDates, dateStr, instance) {
                        let selected_leave_arr = [];
                        let deducted_leave_calendar = parseFloat($("#deducted-leave").html());

                        selectedDates.forEach(function (date, index) {
                            let mostRecentDate = date;
                            let year = mostRecentDate.getFullYear();
                            let month = (mostRecentDate.getMonth() + 1).toString().padStart(2, '0');
                            let day = mostRecentDate.getDate().toString().padStart(2, '0');
                            let formattedDate = "'" + year + '-' + month + '-' + day + "'";
                            selected_leave_arr.push(formattedDate);
                        });
                        let selected_leave_str = selected_leave_arr.join(',');
                        getWorkHoursLeave(empid, selected_leave_str).then(function (response) {
                            // Calculation
                            let remaining_leave = $("#remain-leave").html();

                            let jsonResponse = JSON.parse(response);
                            $("#selectedLeave").empty(); //reset date 
                            $.each(jsonResponse.leave_sched, function (index, obj) {
                                let date = moment(obj.date).format("ddd MMMM D, YYYY");
                                let wholeDay = obj.whole;
                                let halfDay = obj.half;
                                // Append the regular select element for other indices
                                $("#selectedLeave").append('<div class="col-lg-6 col-sm-12 my-1 leave-container"><p name="leave-date[]" class="m-0 text-small text-primary leave-date font-weight-bold">' + date + '</p><select name="duration[]" class="p-0 form-control text-small text-primary duration" style="width: 60%" onchange="calculateLeave(' + remaining_leave + ', event)"><option value="' + wholeDay + '">Whole Day</option><option value="' + halfDay + '">Half Day</option></select></div>');
                            });

                            calculateLeave(remaining_leave);
                        }).catch(function (response) {
                            console.log(response);
                        });
                    }
                });
            },

            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });

        $("#next-leave").on("click", function (e) {
            let selectedLeaveDates = $(".leave-date");
            let selectedDuration = $(".duration");
            let remainingLeave = $("#remain-leave")
            let isCheck = $("#customCheck").prop('checked');
            let reason = $("#reason").val();
            let empno = $("#empno").val();

            let checkDates = [];
            selectedLeaveDates.each(function () {
                let leaveDate = formatDate($(this).text());
                checkDates.push(leaveDate);
            });

            if (checkDates.length <= 0) {
                checkDates = null;
            }
            $.ajax({
                url: "Function/leave_func.php?sched=isExist",
                type: "GET",
                data: {
                    selected_dates: checkDates,
                    empno: empno
                },
                success: function (response) {
                    let jsonResponse = JSON.parse(response);

                    if (jsonResponse.status == true) {
                        let listContent = '';
                        jsonResponse.dates.forEach(item => {
                            listContent += '<li class="text-center">' + item.vldatefrom + '</li>';
                        });
                        listContent += '';
                        if (jsonResponse.dates.length >= 1) {
                            Swal.fire({
                                title: 'Selected Leave is already filed',
                                html: listContent,
                                icon: 'warning',
                                confirmButtonText: 'Understood'
                            });
                        } else if (selectedLeaveDates.length <= 0) {
                            Swal.fire({
                                title: 'Leave Date Selection Required',
                                text: 'Please select a leave date first',
                                icon: 'warning',
                                confirmButtonText: 'Understood'
                            });
                        } else if (!isCheck) {
                            Swal.fire({
                                title: 'Certification Required',
                                text: 'Please tick the checkbox below',
                                icon: 'warning',
                                confirmButtonText: 'Understood'
                            });
                        } else if (reason.trim() == "") {
                            Swal.fire({
                                title: 'Leave Reason Required',
                                text: 'Please provide a reason for taking a Leave',
                                icon: 'warning',
                                confirmButtonText: 'Understood'
                            });
                        } else {
                            $("#leave-modal").modal('show');
                            $("#current-leave").html($("#remain-leave").html());
                            $("#estimated-leave").html($("#deducted-leave").html());
                            $("#leave-calculation").empty(); // reset content

                            let leave_credit = 0;
                            $(".leave-container").each(function () {
                                $("#leave-calculation").append('<div class="col-lg-6 col-sm-12"><li class="font-weight-bold m-0">' + $(this).find('.leave-date').text() + '</li><p class="text-small text-danger">-' + $(this).find('.duration').val() + ' (' + $(this).find('.duration option:selected').text() + ')</p></div>');

                                leave_credit += parseFloat($(this).find('.duration').val());
                            });

                            $("#leave-calculation").append('<div class="col-sm-12 d-flex mt-3"><p class="m-0 mr-3 text-small">Total leave credits consumed: </p><p class="m-0 text-small text-danger" id="total-deducted-leave">' + leave_credit + ' credit(s)</p></div>');

                            $("#leave-calculation").append('<div class="col-sm-12 mt-3"><p class="text-small">(The deducted credit leaves will be adjusted based on your scheduled work hours)</p></div>');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                }
            });
        });
    }

    $('#submit-leave').on("click", function () {
        $(this).prop('disabled', true);
        let empno = $("#empno").val();
        let reason = $("#reason").val();
        let leaveTypes = $("#leave-types").val();
        let selectedLeave = $(".leave-container");
        let vlnumber = empno + generateRandomString(5);
        let leave_form = [];
        let compiled_leave = [];
        let emp_details = {
            "empno": empno,
            "leaveTypes": leaveTypes,
            "reason": reason,
            "vlnumber": vlnumber
        };
        selectedLeave.each(function (date, index) {
            let leaveDate = formatDate($(this).find('.leave-date').text());
            let leaveVal = $(this).find('.duration').val();
            let leaveDuration = $(this).find('.duration option:selected').text();

            compiled_leave.push({
                date: leaveDate,
                value: leaveVal,
                duration: leaveDuration
            });
        });
        $.ajax({
            url: "Function/leave_func.php?sched=postLeave",
            type: 'POST',
            data: {
                leave_details: compiled_leave,
                emp_details: emp_details
            },
            success: function (response) {
                Swal.fire({
                    title: 'Leave(s) Filed Succesfully',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-circle fa-sm"></i>',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000,
                }).then((result) => {
                    window.location.href = 'pdf/print_ot.php?leave=leave&empno=' + empno;
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    });
});


function fetchLeave(empid) {
    getLeave(empid).then(function (response) {
        let jsonResponse = JSON.parse(response);
        if (jsonResponse.remainingLeave <= 0) {
            $("#has-leave").addClass("d-none");
            $("#no-leave").removeClass("d-none");
        } else {
            $("#has-leave").removeClass("d-none");
            $("#no-leave").addClass("d-none");
        }
    }).catch(function (jqXHR, errorThrown, textStatus) {
        console.log(jqXHR, errorThrown, textStatus);
    });
}

function calculateLeave(remaining_leave, event) {
    let current_leave = remaining_leave;
    let deductedLeave = parseFloat($("#deducted-leave").html());
    let selection = 0;

    if ($('.duration').length === 0) {
        $("#fa-arrow-right").addClass("d-none");
        $("#deducted-leave").addClass("d-none");
    } else {
        $("#fa-arrow-right").removeClass("d-none");
        $("#deducted-leave").removeClass("d-none");
        $(".duration").each(function () {
            selection += parseFloat($(this).val());
        });
        let diff = current_leave - selection;

        diff = diff.toFixed(2);
        $("#deducted-leave").html(diff);
    }
}

function formatDate(inputDate) {
    // Parse the inputDate string into a Date object
    let dateObj = new Date(inputDate);
    // Get the year, month, and day from the date object
    let year = dateObj.getFullYear();
    let month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
    let day = dateObj.getDate().toString().padStart(2, '0');

    // Combine the parts into the desired format "yyyy-mm-dd"
    let formattedDate = year + '-' + month + '-' + day;
    return formattedDate;
}

function generateRandomString(length) {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let randomString = '';
    for (let i = 0; i < length; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
    }
    return randomString;
}
// AJAX CALLS
function getLeave(emp_id) {
    return $.ajax({
        url: "Function/leave_func.php?sched=leave",
        type: 'GET',
        data: {
            empno: emp_id,
        }
    });
}

function getWorkHoursLeave(emp_id, datechosen) {
    return $.ajax({
        url: "Function/leave_func.php?sched=workHours",
        type: 'GET',
        data: {
            empno: emp_id,
            datefrom: datechosen
        }
    });
}
// ------------------------------------------------------------------------------------------------- //


// ------------------------------------ APPROVING LEAVE DETAILS ------------------------------------ //
$(document).ready(function () {
    if (window.location.pathname == "/hrms/pdf/viewot.php") {
        let empno = $("#empno").val();
        let reason = $("#reason").text();
        $(".approve-leave").click(function () {
            Swal.fire({
                title: 'Approve Leave?',
                icon: 'info',
                iconHtml: '<i class="fas fa-info-circle fa-sm"></i>',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Approve',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    let content = $(this).closest(".input-group");
                    approvalLeave(empno, reason, $(this).val()).then(function (response) {
                        console.log(response);
                        let jsonResponse = JSON.parse(response);
                        console.log(jsonResponse.remaining_leave);
                        $("#remaining-leave").html(jsonResponse.remaining_leave);

                        if (jsonResponse.status == true && jsonResponse.is_approved == false) {
                            Swal.fire({
                                title: 'Leave Approved',
                                position: 'top-end',
                                icon: 'success',
                                iconHtml: '<i class="fas fa-check-circle fa-sm"></i>',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500,
                                backdrop: false,
                            });
                            content.remove();
                        } else if (jsonResponse.is_approved == true) {
                            Swal.fire({
                                title: 'Leave Already Approved',
                                position: 'top-end',
                                icon: 'warning',
                                iconHtml: '<i class="fas fa-exclamation-circle fa-sm">',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500,
                                backdrop: false,
                            });
                        } else {
                            Swal.fire({
                                title: 'Insufficient Leave Credit(s)',
                                position: 'top-end',
                                icon: 'error',
                                iconHTML: '<i class="fas fa-exclamation-circle fa-sm">',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500,
                                backdrop: false,
                            });
                        }

                        checkLengthLeaveForm();
                    }).catch(function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    });
                }
            })
        });

        $(".cancel-leave").click(function () {
            Swal.fire({
                title: 'Disapprove Leave?',
                icon: 'warning',
                iconHtml: '<i class="fas fa-exclamation-circle fa-sm"></i>',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Disapprove',
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    let content = $(this).closest(".input-group");
                    declineLeave(empno, reason, $(this).val()).then(function (response) {
                        let jsonResponse = JSON.parse(response);
                        $("#remaining-leave").html(jsonResponse.remaining_leave);

                        if (jsonResponse.status == true) {
                            Swal.fire({
                                title: 'Leave Disapproved',
                                position: 'top-end',
                                icon: 'warning',
                                iconHtml: '<i class="fas fa-exclamation-circle fa-sm"></i>',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500,
                                backdrop: false,
                            });
                            content.remove();
                        } else {
                            Swal.fire({
                                title: 'Insufficient Leave Credit(s)',
                                position: 'top-end',
                                icon: 'error',
                                iconHTML: '<i class="fas fa-exclamation-circle fa-sm">',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 1500,
                                backdrop: false,
                            });
                        }

                        checkLengthLeaveForm();
                    }).catch(function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR, textStatus, errorThrown);
                    });
                }
            })
        });
    }
});

function approvalLeave(emp_id, reason, datechosen) {
    return $.ajax({
        url: "../Function/leave_func.php?sched=approval",
        type: 'POST',
        data: {
            empno: emp_id,
            reason: reason,
            datefrom: datechosen
        }
    });
}

function declineLeave(emp_id, reason, datechosen) {
    return $.ajax({
        url: "../Function/leave_func.php?sched=decline",
        type: 'POST',
        data: {
            empno: emp_id,
            reason: reason,
            datefrom: datechosen
        }
    });
}

function checkLengthLeaveForm() {
    let length = $(".input-group").length;

    if (length <= 0) {
        $.ajax({
            url: "../Function/leave_func.php?sched=redirection",
            type: 'GET',
            data: null,
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                let userlevel = jsonResponse.session.userlevel.toLowerCase();
                let empno = parseInt(jsonResponse.session.empno);

                let moveToEmployeeLeave = [271, 71, 3294, 3107, 2221, 3336, 3111, 159, 3235, 3027, 5356, 107, 6538]; // filed leave redirection
                let moveToApproval = [1348, 1, 2, 1331, 3071, 1073] // approvals redirection

                // console.log(userlevel);
                if ($.inArray(empno, moveToEmployeeLeave) !== -1 || userlevel == "mod") {
                    window.location.href = "../leave.php?pending=pending";
                }
                else if ($.inArray(empno, moveToApproval) !== -1 || userlevel == "master" || userlevel == "admin" || userlevel == "ac") {
                    window.location.href = "approvals.php?vl=vl&m=3";
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }
}
// ------------------------------------------------------------------------------------------------- //