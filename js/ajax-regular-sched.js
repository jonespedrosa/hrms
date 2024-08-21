$(document).ready(function (){
    let urlSearchParams = new URLSearchParams(window.location.search);
    let empid = urlSearchParams.get("empid"); 
    let cutfrom = urlSearchParams.get("cutfrom");
    let cutto = urlSearchParams.get("cutto");

    Swal.fire({ 
        html: `
        <div style="display: flex; flex-direction: column; justify-content: flex-end; align-items: center; height: 100%;">
            <img src="../images/fetching-data.gif" width="250" height="200">
            <p class="mt-3 font-weight-bold h4 text-center">Getting Data from Cloud</p>
        </div>
        `,
        toast: true,
        position: 'center', 
        showConfirmButton: false, 
        timerProgressBar: true,
        showCancelButton: false,
        didOpen: (toast) => {
            // Swal.showLoading(); 
            Swal.hideLoading(); 
            toast.style.pointerEvents = 'none';  
        },
        willClose: () => {
            Swal.hideLoading(); 
        }
    });
    const backdrop = $("<div class='custom-backdrop'></div>");
    $("body").append(backdrop);

    $.ajax({
        type: 'GET',
        url: "../Function/regular_schedule_func.php?action=getSched",
        data: {
            cutfrom: cutfrom,
            cutto: cutto, 
            empid: empid
        },
        success: function(response){
            Swal.close();
            backdrop.remove();
            
            let jsonResponse = JSON.parse(response); 
            console.log(jsonResponse);           
            for (let element of jsonResponse.time_inputs) {       
                $("#regular-tbody").append('<tr>' +
                    '<td colspan="2">' + element.datefromto + '</td>' +
                    '<td colspan="2" class="schedule '+ element.sched.flag +'">' + element.sched.schedfrom + ' - ' + element.sched.schedto + '</td>' +
                    '<td class="breaks">' + element.break + '</td>' +
                    '<td class="timein '+ element.M_timein.flag +'">' + element.M_timein.timein + '</td>' +
                    '<td class="breakout">' + element.M_timeout + '</td>' +
                    '<td class="breakin ' + element.A_timein.flag + '">' + element.A_timein.breakin + '</td>' +
                    '<td class="timeout '+ element.A_timeout.flag +'">' + element.A_timeout.timeout + '</td>' +
                    '<td class="text-primary font-weight-bold">' + element.othours + '</td>' +
                    '<td class="text-primary font-weight-bold">' + element.broken_othours + '</td>' +
                    '<td>' + element.gen_timein + '</td>' +
                    '<td>' + element.gen_timeout + '</td>' +
                    '<td colspan="3">' +
                        '<div class="d-flex align-items-center flex-column">' + 
                        '<p class="my-1 ' + element.remarks.vl.flag + '">' + element.remarks.vl.remarks + '</p>' +
                        '<div class="d-flex align-items-center flex-column"><p class="my-1 ' + element.remarks.concern.flag + '">' + element.remarks.concern.remarks + '</p></div>' +
                        '<p class="my-1 ' + element.remarks.obp.flag + '">' + element.remarks.obp.remarks + '</p>' +
                        '<p class="my-1 ' + element.remarks.wdo.flag + '">' + element.remarks.wdo.remarks + '</p>' +
                        '<p class="my-1 ' + element.remarks.cs.flag + '">' + element.remarks.cs.remarks + '</p>' +
                        '<p class="my-1 font-italic">' + element.remarks.remarks + '</p>' +
                        '</div>' +
                    '</td>' +
                '</tr>'); 
            }   

            console.log(jsonResponse.computation);
            $("#regular-tfoot").append(
                '<td id="workdays" class="font-weight-bold">'+ jsonResponse.computation.workdays +'</td>' +
                '<td id="late" class="font-weight-bold">'+ jsonResponse.computation.late +'</td>' +
                '<td id="undertime" class="font-weight-bold">'+ jsonResponse.computation.undertime +'</td>' +
                '<td id="leave" class="font-weight-bold">'+ jsonResponse.computation.leave +'</td>' +
                '<td id="od-nd" class="font-weight-bold">'+ jsonResponse.computation.ordinary_nd +'</td>' +
                '<td id="od-ot" class="font-weight-bold">'+ jsonResponse.computation.ordinary_ot +'</td>' +
                '<td id="od-ndot" class="font-weight-bold">'+ jsonResponse.computation.ordinary_ndot +'</td>' +
                '<td id="sph-hrs" class="font-weight-bold">'+ jsonResponse.computation.special_hrs +'</td>' +
                '<td id="sph-nd" class="font-weight-bold">'+ jsonResponse.computation.special_nd +'</td>' +
                '<td id="sph-ot" class="font-weight-bold">'+ jsonResponse.computation.special_ot +'</td>' +
                '<td id="sph-ndot" class="font-weight-bold">'+ jsonResponse.computation.special_ndot +'</td>' +
                '<td id="lh-hrs" class="font-weight-bold">'+ jsonResponse.computation.legal_hrs +'</td>' +
                '<td id="lh-nd" class="font-weight-bold">'+ jsonResponse.computation.legal_nd +'</td>' +
                '<td id="lh-ot" class="font-weight-bold">'+ jsonResponse.computation.legal_ot +'</td>' +
                '<td id="lh-ndot" class="font-weight-bold">'+ jsonResponse.computation.legal_ndot +'</td>' +
                '<td id="workingoff" class="font-weight-bold">'+ jsonResponse.computation.working_off +'</td>'
            );
        }, 
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
});