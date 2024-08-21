$(document).ready(function() {
    // Cut Off Dates
    let cutfrom = $("#cutfrom").val();
    let cutto = $("#cutto").val();
    let default_refresh = 0;

    // Total Rendered Hours
    dashboard_rendered_hours(cutfrom, cutto);
    // Total Late Minutes
    dashboard_late_minutes(cutfrom, cutto);
    // Total Overbreak Minutes
    dashboard_overbreak_minutes(cutfrom, cutto);
    // Total Undertime Minutes
    dashboard_undertime_minutes(cutfrom, cutto);


    // Overtime Breakdown
    dashboard_overtime(cutfrom, cutto, default_refresh);
    $("#refresh-overtime-breakdown").on("click", function(){
        default_refresh = 1;
        $("#overtime-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_overtime(cutfrom, cutto, default_refresh);
    });

    // OBP Breakdown
    dashboard_obp(cutfrom, cutto, default_refresh);
    $("#refresh-obp-breakdown").on("click", function(){
        default_refresh = 1;
        $("#obp-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_obp(cutfrom, cutto, default_refresh);
    });

    // Leave Breakdown
    dashboard_leave(cutfrom, cutto, default_refresh);
    $("#refresh-leave-breakdown").on("click", function(){
        default_refresh = 1;
        $("#leave-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_leave(cutfrom, cutto, default_refresh);
    });

    // Concern Breakdown
    dashboard_concern(cutfrom, cutto, default_refresh);
    $("#refresh-concern-breakdown").on("click", function(){
        default_refresh = 1;
        $("#concern-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_concern(cutfrom, cutto, default_refresh);
    });

    // Change Schedule Breakdown
    dashboard_change_schedule(cutfrom, cutto, default_refresh);
    $("#refresh-cs-breakdown").on("click", function(){
        default_refresh = 1;
        $("#cs-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_change_schedule(cutfrom, cutto, default_refresh);
    });

    // Working Day Off Breakdown
    dashboard_working_day_off(cutfrom, cutto, default_refresh);
    $("#refresh-wdo-breakdown").on("click", function(){
        default_refresh = 1;
        $("#wdo-breakdown-spinner").html('<li class="list-group-item d-flex justify-content-between align-items-center"><div class="d-flex justify-content-center align-items-center"><div class="spinner-border" role="status"></div><p class="m-0 ml-3">Fetching Data...</p></div></li>');
        dashboard_working_day_off(cutfrom, cutto, default_refresh);
    });
});

function dashboard_rendered_hours(cutfrom, cutto){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=renderedHours",
        data: {
            cutfrom: cutfrom,
            cutto: cutto
        },
        success: function(response){
            
            let jsonResponse = JSON.parse(response); 
            $("#total-rendered-hours").fadeOut(300, function() {
                $(this).text(jsonResponse.total_rendered_hours).fadeIn(300);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_late_minutes(cutfrom, cutto){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=lateMin",
        data: {
            cutfrom: cutfrom,
            cutto: cutto
        },
        success: function(response){ 
            // console.log(response);
            let jsonResponse = JSON.parse(response);  
            $("#total-late-minutes").fadeOut(300, function() {
                $(this).text(parseInt(jsonResponse.total_late_minutes).toLocaleString()).fadeIn(300);
                $(this).append('<br><a href="discrepancy.php?br=late" class="text-small">View report</br>').fadeIn(300);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
        }
    });
}

function dashboard_overbreak_minutes(cutfrom, cutto){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=overbreakMin",
        data: {
            cutfrom: cutfrom,
            cutto: cutto
        },
        success: function(response){ 
            let jsonResponse = JSON.parse(response);  
            // console.log(response);
            $("#total-overbreak-minutes").fadeOut(300, function() {
                $(this).text(parseInt(jsonResponse.total_overbreak_minutes).toLocaleString()).fadeIn(300);
                $(this).append('<br><a href="discrepancy.php?br=overbreak" class="text-small">View report</br>').fadeIn(300);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
}

function dashboard_undertime_minutes(cutfrom, cutto){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=undertimeMin",
        data: {
            cutfrom: cutfrom,
            cutto: cutto
        },
        success: function(response){
            // console.log(response);
            let jsonResponse = JSON.parse(response); 
            // console.log(jsonResponse);
            $("#total-undertime-hours").fadeOut(300, function() {
                $(this).text(parseInt(jsonResponse.total_undertime_hours).toLocaleString()).fadeIn(300);
                $(this).append('<br><a href="discrepancy.php?br=undertime" class="text-small">View report</br>').fadeIn(300);
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_overtime(cutfrom, cutto, default_refresh){ 
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=overtime",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){
            
            let jsonResponse = JSON.parse(response); 
           // Fade out the spinner content
            $("#overtime-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html("");  
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                    const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=overtime&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                        '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                        '<span class="d-flex align-items-center">' +
                        '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_overtime_hours).toLocaleString() + ' hr(s)</p>' +
                        '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                        '</span></a>';
                    $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?br=overtime" class="text-center p-3">View all Overtime Breakdown</a>').fadeIn(300);
                }
                
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_obp(cutfrom, cutto, default_refresh){ 
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=obp",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){
            
            let jsonResponse = JSON.parse(response);
            
           // Fade out the spinner content
            $("#obp-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html(""); 
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                        const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=obp&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                            '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                            '<span class="d-flex align-items-center">' +
                            '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_obp_count).toLocaleString() + '</p>' +
                            '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                            '</span></a>';
                        $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?br=obp" class="text-center p-3">View all OBP Breakdown</a>').fadeIn(300);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_leave(cutfrom, cutto, default_refresh){ 
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=leave",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){
            let jsonResponse = JSON.parse(response);  
           // Fade out the spinner content
            $("#leave-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html(""); 
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                        const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=leave&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                            '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                            '<span class="d-flex align-items-center">' +
                            '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_leave_count).toLocaleString() + '</p>' +
                            '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                            '</span></a>';
                        $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?br=leave" class="text-center p-3">View all Leave Breakdown</a>').fadeIn(300);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_concern(cutfrom, cutto, default_refresh){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=concern",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){
            
            let jsonResponse = JSON.parse(response);
            
           // Fade out the spinner content
            $("#concern-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html(""); 
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                        const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=concern&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                            '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                            '<span class="d-flex align-items-center">' +
                            '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_concern_count).toLocaleString() + '</p>' +
                            '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                            '</span></a>';
                        $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?br=concern" class="text-center p-3">View all Concern Breakdown</a>').fadeIn(300);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_change_schedule(cutfrom, cutto, default_refresh){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=cs",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){
            
            let jsonResponse = JSON.parse(response); 
           // Fade out the spinner content
            $("#cs-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html(""); 
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                        const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=sched&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                            '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                            '<span class="d-flex align-items-center">' +
                            '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_cs_count).toLocaleString() + '</p>' +
                            '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                            '</span></a>';
                        $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?br=sched" class="text-center p-3">View all Change Schedule Breakdown</a>').fadeIn(300);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function dashboard_working_day_off(cutfrom, cutto, default_refresh){
    $.ajax({
        type: 'GET',
        url: "Function/dashboard_func.php?dashboard=wdo",
        data: {
            cutfrom: cutfrom,
            cutto: cutto,
            is_refresh: default_refresh
        },
        success: function(response){ 
            let jsonResponse = JSON.parse(response);  
           // Fade out the spinner content
            $("#wdo-breakdown-spinner").fadeOut(300, function() {
                // Clear the spinner content
                $(this).html(""); 
                if(jsonResponse.result.length <= 0){
                    const itemHtml = '<div class="d-flex justify-content-center mt-5"><svg class="bg-secondary p-4 rounded-circle" xmlns="http://www.w3.org/2000/svg" height="6rem" viewBox="0 0 640 512" style="opacity: 0.2; width: fit-content"><path d="M425.7 256c-16.9 0-32.8-9-41.4-23.4L320 126l-64.2 106.6c-8.7 14.5-24.6 23.5-41.5 23.5-4.5 0-9-.6-13.3-1.9L64 215v178c0 14.7 10 27.5 24.2 31l216.2 54.1c10.2 2.5 20.9 2.5 31 0L551.8 424c14.2-3.6 24.2-16.4 24.2-31V215l-137 39.1c-4.3 1.3-8.8 1.9-13.3 1.9zm212.6-112.2L586.8 41c-3.1-6.2-9.8-9.8-16.7-8.9L320 64l91.7 152.1c3.8 6.3 11.4 9.3 18.5 7.3l197.9-56.5c9.9-2.9 14.7-13.9 10.2-23.1zM53.2 41L1.7 143.8c-4.6 9.2.3 20.2 10.1 23l197.9 56.5c7.1 2 14.7-1 18.5-7.3L320 64 69.8 32.1c-6.9-.8-13.5 2.7-16.6 8.9z"/></svg></div>' +
                    '<h5 class="text-center mt-4">Nothing here at the moment.</h5>' +
                    "<small class='mb-2 text-center'>Updated as of "+ jsonResponse.timestamp +"</small>";
                    $(this).append(itemHtml).fadeIn(300); 
                }else{
                    $(this).append("<small class='mb-2'>Updated as of "+ jsonResponse.timestamp +"</small>").fadeIn(300);
                    for (const details of jsonResponse.result) { 
                        const itemHtml = '<a href="breakdown.php?branch=' + details.userid + '&category=wdo&from=' + cutfrom + '&to=' + cutto + '" class="list-group-item hover-list" target="_blank">' +
                            '<p class="m-0 text-primary text-uppercase font-weight-bold">' + details.branch + '</p>' +
                            '<span class="d-flex align-items-center">' +
                            '<p class="m-0 font-weight-bold text-secondary">' + parseInt(details.total_wdo_count).toLocaleString() + '</p>' +
                            '<i class="fa fa-angle-right ml-2" aria-hidden="true"></i>' +
                            '</span></a>';
                        $(this).append(itemHtml).fadeIn(300); 
                    }
                    $(this).append('<a href="discrepancy.php?wdo" class="text-center p-3">View all Working Day Off Breakdown</a>').fadeIn(300);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){

        }
    });
}

function getTimeDate(){
    let time_now = new Date().toLocaleTimeString();
    let date_now = new Date().toLocaleDateString();

    return date_now + " " + time_now;
}