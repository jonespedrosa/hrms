$(document).ready(function (e) {
    let empid = $("#employee-id").html();
    let cutoff = $("#cutoff-sched").html().split(" - ");
    let cutfrom = cutoff[0];
    let cutto = cutoff[1];
    $.ajax({
        url: "../../Function/compress_schedule_func.php?sched=timeinputs",
        type: 'GET',
        data: {
            empno: empid,
            datefrom: cutfrom,
            dateto: cutto
        },
        success: function (response) {
            let jsonResponse = JSON.parse(response);
            let schedule_cls = $(".schedule");
            let timein_cls = $(".timein");
            let timeout_cls = $(".timeout");
            let breakin_cls = $(".breakin");
            for (let i = 0; i < jsonResponse.result.length; i++) {
                // Hooks
                let breaks = jsonResponse.result[i].break;
                let schedFrom = new Date(jsonResponse.result[i].schedfrom);
                let schedTo = new Date(jsonResponse.result[i].schedto);
                let schedHours = (Math.abs((schedTo - schedFrom)) / (1000 * 60 * 60)) - breaks;
                let workHours = jsonResponse.result[i].work_hours;
                let timein = new Date(jsonResponse.result[i].M_timein);

                let breakout;
                let breakin;
                if (jsonResponse.result[i].M_timeout == "No Break") {
                    breakout = jsonResponse.result[i].M_timeout
                } else {
                    breakout = new Date(jsonResponse.result[i].M_timeout);
                }
                if (jsonResponse.result[i].A_timein == "No Break") {
                    breakin = jsonResponse.result[i].A_timein;
                } else {
                    breakin = new Date(jsonResponse.result[i].A_timein);
                }

                let timeout = new Date(jsonResponse.result[i].A_timeout);

                // Break Converter
                if (workHours == "RD") {
                    workHours = 8;
                } else if (workHours == "AB" || workHours == "LWP") {
                    workHours = schedHours;
                }

                // Schedule Warning
                if ((schedHours != workHours) && workHours != "NWD") {
                    schedule_cls.eq(i).addClass("text-danger");
                }

                if ((timein != "Invalid Date" && breakout != "Invalid Date" && breakin != "Invalid Date" && timeout != "Invalid Date") && workHours != "NWD") {
                    // Late Warning
                    if (timein > schedFrom) {
                        timein_cls.eq(i).addClass("text-danger");
                    }

                    if (!(breakin == "No Break" && breakout == "No Break")) {
                        let breakHours = breakout.setHours(breakout.getHours() + breaks);

                        if (breakin > new Date(breakHours)) {
                            breakin_cls.eq(i).addClass("text-danger");
                        }
                    }

                    if (timeout < schedTo) {
                        timeout_cls.eq(i).addClass("text-danger");
                    }
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
});