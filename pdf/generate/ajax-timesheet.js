$(document).ready(function () {
    $("#cutfrom").kendoDatePicker({
        format: "yyyy-MM-dd",
        // disableDates: function (date) {
        //     var disabled = [1,2,3,4,5,6,7,8,10,11,12,13,14,17,18,19,20,21,22,23,25,26,27,28,29,30,31];
        //     if (date && disabled.indexOf(date.getDate()) > -1 ) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
    });

    $("#cutto").kendoDatePicker({
        format: "yyyy-MM-dd",
        // disableDates: function (date) {
        //     var disabled = [1,2,3,4,5,6,7,9,10,11,12,13,14,17,18,19,20,21,22,24,25,26,27,28,29,30,31];
        //     if (date && disabled.indexOf(date.getDate()) > -1 ) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // }
    });
    let cutfrom;
    let cutto;
    let cutfromHTML = $('#cutfrom_date').html();
    let cuttoHTML = $('#cutto_date').html();
    let department = $("#department-selector").val();
    let regenerate = false;
    if ($("#cutto").val() == "" || $("#cutfrom").val() == "") {
        $("#cutfrom").val(cutfromHTML);
        $("#cutto").val(cuttoHTML);
        cutfrom = $("#cutfrom").val();
        cutto = $("#cutto").val();
    }

    $("#form-get-date").submit(function (e) {
        e.preventDefault();
        let formData = $(this).serialize();
        let deparam = new URLSearchParams(formData);

        let deparam_cutfrom = deparam.get("cutfrom");
        let deparam_cutto = deparam.get("cutto");

        $("#cutfrom").val(deparam_cutfrom);
        $("#cutto").val(deparam_cutto);
        cutfrom = $("#cutfrom").val();
        cutto = $("#cutto").val();
        regenerate = false;

        Swal.fire({
            title: 'Date Adjusted',
            position: 'top-end',
            icon: "success",
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1200,
            backdrop: false,
        });
        checkData(cutfrom, cutto, department, regenerate);
    });

    $("#department-selector").on("change", function () {
        department = $(this).val();
        regenerate = false;
        checkData(cutfrom, cutto, department, regenerate);
    });

    // checkData(cutfrom, cutto, department, regenerate);

    $("#save-record").on("click", function () {
        checkIfExisting(cutfrom, cutto);
    });

    $("#regenerate-record").on("click", function () {
        regenerate = true;
        Swal.fire({
            title: 'Regenerate Timesheet?',
            text: "Regenerating can take time",
            icon: 'warning',
            iconHtml: '<i class="fas fa-exclamation-circle fa-sm"></i>',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Regenerate'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Timesheet Regenarated',
                    position: 'top-end',
                    icon: 'success',
                    iconHtml: '<i class="fas fa-check-circle fa-sm"></i>',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 2000,
                    backdrop: false,
                });
                checkData(cutfrom, cutto, department, regenerate);
            }
        })
    });
});

function checkData(cutfrom, cutto, department, regenerate) {
    Swal.fire({
        title: 'Generating...',
        html: 'Please wait',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        type: 'GET',
        url: "../../Function/generate_timesheet_func.php?generate=timesheet",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            dept: department,
            isRegen: regenerate
        },
        success: function (response) {
            // console.log(response);
            let jsonResponse = JSON.parse(response);
            department_text = $("#department-selector").find("option:selected").text();
            let branch_rendered = department_text.split('-')[1];
            if (cutfrom != "" && cutto != "" && department != "") {
                Swal.close();
                $('#generateTimesheetTable').DataTable().destroy();
                let dataTable = $('#generateTimesheetTable').DataTable({
                    data: jsonResponse,
                    columns: [
                        { data: 'empno', title: 'ID', className: 'empno-column' },
                        { data: 'name', title: 'FULL NAME', className: 'name-column' },
                        { data: 'branch', title: 'BRANCH', className: 'branch-column' },
                        // ATTENDANCE
                        { data: 'total_work_hours', title: 'WORKDAYS', className: 'workdays-column' },
                        { data: 'late', title: 'LATE', className: 'late-column' },
                        { data: 'undertime', title: 'UT', className: 'undertime-column' },
                        { data: 'leave', title: 'LEAVE', className: 'leave-column' },
                        // ORDINARY DAY
                        { data: 'ordinary_nd', title: 'ND', className: 'ordinary-nd-column' },
                        { data: 'ordinary_ot', title: 'OT', className: 'ordinary-ot-column' },
                        { data: 'ordinary_ndot', title: 'ND.OT', className: 'ordinary-ndot-column' },
                        // SPECIAL HOLIDAY
                        { data: 'special_hrs', title: 'HRS', className: 'special-hrs-column' },
                        { data: 'special_nd', title: 'ND', className: 'special-nd-column' },
                        { data: 'special_ot', title: 'OT', className: 'special-ot-column' },
                        { data: 'special_ndot', title: 'ND.OT', className: 'special-ndot-column' },
                        // LEGAL HOLIDAY
                        { data: 'legal_hrs', title: 'HRS', className: 'legal-hrs-column' },
                        { data: 'legal_nd', title: 'ND', className: 'legal-nd-column' },
                        { data: 'legal_ot', title: 'OT', className: 'legal-ot-column' },
                        { data: 'legal_ndot', title: 'ND.OT', className: 'legal-ndot-column' },
                        // WORKING OFF 
                        { data: 'working_off', title: 'WORKING OFF', className: 'working-off-column' },
                    ],
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    pageLength: -1,
                    dom: 'lBfrtip', //lBfrtip Bfrtip
                    buttons: [
                        {
                            extend: 'collection',
                            text: '<i class="fas fa-file-export"></i> Export',
                            buttons: [
                                {
                                    extend: 'excel',
                                    text: '<i class="fas fa-file-excel"></i> Excel',
                                    action: function (e, dt, button, config) {
                                        let exportData = [];
                                        let total_emp = 0;
                                        let work_days = 0;
                                        let late = 0;
                                        let ut = 0;
                                        let leave = 0;
                                        let ord_nd = 0;
                                        let ord_ot = 0;
                                        let ord_ndot = 0;
                                        let spe_hrs = 0;
                                        let spe_nd = 0;
                                        let spe_ot = 0;
                                        let spe_ndot = 0;
                                        let leg_hrs = 0;
                                        let leg_nd = 0;
                                        let leg_ot = 0;
                                        let leg_ndot = 0;
                                        let wdo = 0;
                                        // Add the additional header rows to the export data
                                        exportData.push(["", "", "", "ATTENDANCE", "", "", "", "ORDINARY DAY", "", "", "SPECIAL HOLIDAY", "", "", "", "LEGAL HOLIDAY", "", "", ""]);
                                        exportData.push(["ID", "FULL NAME", "BRANCH", "WORKDAYS", "LATE", "UT", "LEAVE", "ND", "OT", "ND.OT", "HRS", "ND", "OT", "ND.OT", "HRS", "ND", "OT", "ND.OT", "WORKING OFF"]);

                                        // Add the DataTable data to the export data
                                        dt.rows().every(function () {
                                            let rowData = this.data();
                                            total_emp++;
                                            work_days += rowData.total_work_hours;
                                            late += rowData.late;
                                            ut += rowData.undertime;
                                            leave += rowData.leave;
                                            ord_nd += rowData.ordinary_nd;
                                            ord_ot += rowData.ordinary_ot;
                                            ord_ndot += rowData.ordinary_ndot;
                                            spe_hrs += rowData.special_hrs;
                                            spe_nd += rowData.special_nd;
                                            spe_ot += rowData.special_ot;
                                            spe_ndot += rowData.special_ndot;
                                            leg_hrs += rowData.legal_hrs;
                                            leg_nd += rowData.legal_nd;
                                            leg_ot += rowData.legal_ot;
                                            leg_ndot += rowData.legal_ndot;
                                            wdo += rowData.working_off;

                                            exportData.push([
                                                rowData.empno,
                                                rowData.name,
                                                rowData.branch,
                                                rowData.total_work_hours,
                                                rowData.late,
                                                rowData.undertime,
                                                rowData.leave,
                                                rowData.ordinary_nd,
                                                rowData.ordinary_ot,
                                                rowData.ordinary_ndot,
                                                rowData.special_hrs,
                                                rowData.special_nd,
                                                rowData.special_ot,
                                                rowData.special_ndot,
                                                rowData.legal_hrs,
                                                rowData.legal_nd,
                                                rowData.legal_ot,
                                                rowData.legal_ndot,
                                                rowData.working_off
                                            ]);
                                        });
                                        exportData.push(["TOTAL", total_emp, "", work_days, late, ut, leave, ord_nd, ord_ot, ord_ndot, spe_hrs, spe_nd, spe_ot, spe_ndot, leg_hrs, leg_nd, leg_ot, leg_ndot, wdo]);
                                        // Convert the export data to a worksheet
                                        let worksheet = XLSX.utils.aoa_to_sheet(exportData);
                                        let workbook = XLSX.utils.book_new();
                                        XLSX.utils.book_append_sheet(workbook, worksheet, 'Timesheet');

                                        // Save the workbook as an Excel file
                                        XLSX.writeFile(workbook, branch_rendered + " " + cutfrom + " - " + cutto + '.xlsx');
                                    }
                                },
                                {
                                    extend: 'csv',
                                    text: '<i class="fas fa-file-csv"></i> CSV'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print"></i> Print',
                                    title: "Timesheet",
                                    messageTop: cutfrom + " - " + cutto + "<br> <p class='mb-3 font-weight-bold'>" + branch_rendered + " Timesheet</p>",
                                    customize: function (win) {
                                        $(win.document.body).find("h1").css("display", "none");
                                        // Certification
                                        let customContent = $('<div>').html('<p class="mt-3">This document is intended solely for the use of Mary Grace Foods Inc. and may contain confidential information. Any unauthorized use, disclosure, or distribution is prohibited. If you are not the intended recipient, please notify us immediately and delete this document.</p>');
                                        $(win.document.body).append(customContent);

                                        let thead_main = $('<tr>').html('<th colspan=3 class="text-center"></th><th colspan=4 class="text-center">ATTENDANCE</th><th colspan=3 class="text-center">ORDINARY DAY</th><th colspan=4 class="text-center">SPECIAL HOLIDAY</th><th colspan=4 class="text-center">LEGAL HOLIDAY</th><th></th>');
                                        $(win.document.body).find("thead").prepend(thead_main);

                                        let workdays = 0;
                                        let late = 0;
                                        let undertime = 0;
                                        let leave = 0;
                                        let ordinary_nd_column = 0;
                                        let ordinary_ot_column = 0;
                                        let ordinary_ndot_column = 0;
                                        let special_hrs_column = 0;
                                        let special_nd_column = 0;
                                        let special_ot_column = 0;
                                        let special_ndot_column = 0;
                                        let legal_nd_column = 0;
                                        let legal_ot_column = 0;
                                        let legal_ndot_column = 0;
                                        let legal_hrs_column = 0;
                                        let working_off_column = 0;
                                        let count = 0;
                                        $(win.document.body).find('.workdays-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                count++;
                                                workdays += hours;
                                            }
                                        });
                                        $(win.document.body).find('.late-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                late += hours;
                                            }
                                        });
                                        $(win.document.body).find('.undertime-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                undertime += hours;
                                            }
                                        });
                                        $(win.document.body).find('.leave-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseFloat($(this).text());
                                            if (!isNaN(hours)) {
                                                leave += hours;
                                            }
                                        });
                                        $(win.document.body).find('.ordinary-nd-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                ordinary_nd_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.ordinary-ot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                ordinary_ot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.ordinary-ndot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                ordinary_ndot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.special-hrs-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                special_hrs_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.special-nd-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                special_nd_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.special-ot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                special_ot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.special-ndot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                special_ndot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.legal-hrs-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                legal_hrs_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.legal-nd-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                legal_nd_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.legal-ot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                legal_ot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.legal-ndot-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                legal_ndot_column += hours;
                                            }
                                        });
                                        $(win.document.body).find('.working-off-column', '.dataTable tbody tr:visible').each(function () {
                                            let hours = parseInt($(this).text());
                                            if (!isNaN(hours)) {
                                                working_off_column += hours;
                                            }
                                        });

                                        let tfoot_main = $('<tr>').html('<td class="text-center font-weight-bold">TOTAL</td><td colspan=2>' + count + '</td><td>' + workdays + '</td><td>' + late + '</td><td>' + undertime + '</td><td>' + leave + '</td><td>' + ordinary_nd_column + '</td><td>' + ordinary_ot_column + '</td><td>' + ordinary_ndot_column + '</td><td>' + special_hrs_column + '</td><td>' + special_nd_column + '</td><td>' + special_ot_column + '</td><td>' + special_ndot_column + '</td><td>' + legal_hrs_column + '</td><td>' + legal_nd_column + '</td><td>' + legal_ot_column + '</td><td>' + legal_ndot_column + '</td><td>' + working_off_column + '</td>');
                                        $(win.document.body).find("tbody").append(tfoot_main);
                                    }
                                },
                                {
                                    extend: 'pdf',
                                    text: '<i class="fas fa-file-pdf"></i> PDF',
                                }
                            ]
                        }
                    ],
                    footerCallback: function (tfoot, data, start, end, display) {
                        let api = this.api();
                        let total_WorkDays = api.column(3).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_Late = api.column(4).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_UT = api.column(5).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_Leave = parseFloat(api.column(6).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0));
                        let total_ODND = api.column(7).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_ODOT = api.column(8).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_ODNDOT = api.column(9).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_SDHRS = api.column(10).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_SDND = api.column(11).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_SDOT = api.column(12).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_SDNDOT = api.column(13).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_LDHRS = api.column(14).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_LND = api.column(15).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_LDOT = api.column(16).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_LDNDOT = api.column(17).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let total_WorkingOff = api.column(18).data().toArray().reduce(function (a, b) {
                            return a + b;
                        }, 0);
                        let totalCount = api.rows().count();
                        $(api.column(0).footer()).html('Total');
                        $(api.column(1).footer()).html(totalCount);
                        $(api.column(3).footer()).html(total_WorkDays);
                        $(api.column(4).footer()).html(total_Late);
                        $(api.column(5).footer()).html(total_UT);
                        $(api.column(6).footer()).html(total_Leave.toFixed(2));
                        $(api.column(7).footer()).html(total_ODND);
                        $(api.column(8).footer()).html(total_ODOT);
                        $(api.column(9).footer()).html(total_ODNDOT);
                        $(api.column(10).footer()).html(total_SDHRS);
                        $(api.column(11).footer()).html(total_SDND);
                        $(api.column(12).footer()).html(total_SDOT);
                        $(api.column(13).footer()).html(total_SDNDOT);
                        $(api.column(14).footer()).html(total_LDHRS);
                        $(api.column(15).footer()).html(total_LND);
                        $(api.column(16).footer()).html(total_LDOT);
                        $(api.column(17).footer()).html(total_LDNDOT);
                        $(api.column(18).footer()).html(total_WorkingOff);
                    }
                });
            } else {
                $("#timesheet-body").html("<tr><td colspan='19' class='text-danger font-weight-bold p-3'>- NO DATA FOUND -</td></tr>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
            $("#timesheet-body").html("<tr><td colspan='19' class='text-danger font-weight-bold p-3'>- ERROR DATA -</td></tr>");
        }
    });

}

function checkIfExisting(cutfrom, cutto) {
    let employees = [];
    let submit_btn = $("#save-record");
    let span_spinner = submit_btn.find("span").eq(0);
    let icon_spinner = submit_btn.find("i").eq(0);
    let p_spinner = submit_btn.find("p").eq(0);

    submit_btn.attr('disabled', '');
    span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
    p_spinner.html("Saving");

    $('#generateTimesheetTable').DataTable().rows().every(function () {
        let data = this.data();
        let timesheet = [];
        timesheet.push(data.empno);
        employees.push(timesheet);
    });
    for (let ctr = 1; ctr < $(".name-column").length - 1; ctr++) {
        let timesheet = [];
        timesheet.push($(".empno-column").eq(ctr).html());
        employees.push(timesheet);
    }

    if (employees.length <= 0) {
        Swal.fire({
            title: 'Unable to save record',
            html: 'Please check cut-off period',
            position: 'top-end',
            icon: "warning",
            iconHtml: '<i class="fas fa-exclamation-circle fa-sm"></i>',
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 3000,
            backdrop: false,
        });
        employees = null;
        submit_btn.removeAttr('disabled');
        icon_spinner.toggleClass("d-none");
        span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
        p_spinner.html("Save Record");
    } else {
        $.ajax({
            url: '../../Function/generate_timesheet_func.php?generate=checkExisting',
            type: 'POST',
            data: {
                cutfrom: cutfrom,
                cutto: cutto,
                employees: JSON.stringify(employees)
            },
            success: function (response) {
                let jsonResponse = JSON.parse(response);
                Swal.fire({
                    title: 'Saving...',
                    html: 'Please wait',
                    position: 'top-end',
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1000,
                    backdrop: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                }).then((result) => {
                    if (jsonResponse.status == true) {
                        Swal.fire({
                            title: 'Overwrite Existing Record?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            iconHtml: '<i class="fas fa-exclamation-circle fa-sm"></i>',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Overwrite'
                        }).then((result) => {
                            submit_btn.attr('disabled', '');
                            span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                            p_spinner.html("Saving");
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Record Saved',
                                    position: 'top-end',
                                    icon: 'success',
                                    iconHtml: '<i class="fas fa-check-circle fa-sm"></i>',
                                    showConfirmButton: false,
                                    timerProgressBar: true,
                                    timer: 2000,
                                    backdrop: false,
                                });
                                saveRecord(cutfrom, cutto, jsonResponse.status);
                            }
                        })

                    } else {

                        submit_btn.attr('disabled', '');
                        span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                        p_spinner.html("Saving");

                        Swal.fire({
                            title: 'Record Saved',
                            position: 'top-end',
                            icon: 'success',
                            iconHtml: '<i class="fas fa-check-circle fa-sm"></i>',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000,
                            backdrop: false,
                        });
                        saveRecord(cutfrom, cutto, jsonResponse.status);
                    }
                })

                submit_btn.removeAttr('disabled');
                icon_spinner.toggleClass("d-none");
                span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
                p_spinner.html("Save Record");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

function saveRecord(cutfrom, cutto, isExisting) {
    let employee_timesheet_arr = [];
    $('#generateTimesheetTable').DataTable().rows().every(function () {
        let data = this.data();
        let timesheet = {
            empno: data.empno,
            cutfrom: cutfrom,
            cutto: cutto,
            dayswork: data.total_work_hours,
            late: data.late,
            undertime: data.undertime,
            leave: data.leave,
            ordinary_nd: data.ordinary_nd,
            ordinary_ot: data.ordinary_ot,
            ordinary_ndot: data.ordinary_ndot,
            special_hrs: data.special_hrs,
            special_nd: data.special_nd,
            special_ot: data.special_ot,
            special_ndot: data.special_ndot,
            legal_hrs: data.legal_hrs,
            legal_nd: data.legal_nd,
            legal_ot: data.legal_ot,
            legal_ndot: data.legal_ndot,
            working_off: data.working_off,
        };
        employee_timesheet_arr.push(timesheet);
    });
    // for (let ctr = 1; ctr < $(".name-column").length - 1; ctr++) {
    //     let timesheet = {
    //         empno: $(".empno-column").eq(ctr).html(),
    //         cutfrom: cutfrom,
    //         cutto: cutto,
    //         dayswork: $(".workdays-column").eq(ctr).html(),
    //         late: $(".late-column").eq(ctr).html(),
    //         undertime: $(".undertime-column").eq(ctr).html(),
    //         leave: $(".leave-column").eq(ctr).html(),
    //         ordinary_nd: $(".ordinary-nd-column").eq(ctr).html(),
    //         ordinary_ot: $(".ordinary-ot-column").eq(ctr).html(),
    //         ordinary_ndot: $(".ordinary-ndot-column").eq(ctr).html(),
    //         special_hrs: $(".special-hrs-column").eq(ctr).html(),
    //         special_nd: $(".special-nd-column").eq(ctr).html(),
    //         special_ot: $(".special-ot-column").eq(ctr).html(),
    //         special_ndot: $(".special-ndot-column").eq(ctr).html(),
    //         legal_hrs: $(".legal-hrs-column").eq(ctr).html(),
    //         legal_nd: $(".legal-nd-column").eq(ctr).html(),
    //         legal_ot: $(".legal-ot-column").eq(ctr).html(),
    //         legal_ndot: $(".legal-ndot-column").eq(ctr).html(),
    //         working_off: $(".working-off-column").eq(ctr).html(),
    //     };

    //     employee_timesheet_arr.push(timesheet);
    // }
    // console.log(employee_timesheet_arr);
    if (employee_timesheet_arr.length <= 0) {
        employee_timesheet_arr = null;
    }

    // console.log(employee_timesheet_arr);
    $.ajax({
        url: '../../Function/generate_timesheet_func.php?generate=save',
        type: 'POST',
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            isExisting: isExisting,
            employee_timesheet: JSON.stringify(employee_timesheet_arr)
        },
        success: function (response) {
            console.log(response);
            let submit_btn = $("#save-record");
            let span_spinner = submit_btn.find("span").eq(0);
            let icon_spinner = submit_btn.find("i").eq(0);
            let p_spinner = submit_btn.find("p").eq(0);

            submit_btn.removeAttr('disabled');
            icon_spinner.toggleClass("d-none");
            span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
            p_spinner.html("Save Record");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            let submit_btn = $("#save-record");
            let span_spinner = submit_btn.find("span").eq(0);
            let icon_spinner = submit_btn.find("i").eq(0);
            let p_spinner = submit_btn.find("p").eq(0);

            submit_btn.removeAttr('disabled');
            icon_spinner.toggleClass("d-none");
            span_spinner.toggleClass("spinner-border spinner-border-sm mr-2");
            p_spinner.html("Save Record");
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
}