$(document).ready(function() {
    let cutfrom = "";
    let cutto = "";
    $.ajax({
        url: "Function/breakdown_func.php?breakdown=getCutoff",
        type: "GET",
        success: function(response){
            let jsonResponse = JSON.parse(response);
            cutfrom = jsonResponse.datefrom;
            cutto = jsonResponse.dateto; 
            let flatpickrInstance = flatpickr("#selected-range", {
                defaultDate: [cutfrom, cutto],
                mode: "range",
                dateFormat: "Y-m-d",
            });

            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has("br")){
                setTimeout(function() {
                    $("#range-btn").click();
                }, 100); // Adjust the delay as needed
            }

            // Button click event handler
            $(document).on("click", "#range-btn", function() {
                const selectedDates = flatpickrInstance.selectedDates;
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];
                    cutfrom = moment(startDate).format("YYYY-MM-DD");
                    cutto = moment(endDate).format("YYYY-MM-DD"); 

                    const backdrop = $("<div class='custom-backdrop'></div>");
                    $("body").append(backdrop);

                    Swal.fire({
                        icon: 'info',
                        title: 'Loading...',
                        toast: true,
                        position: 'center', 
                        showConfirmButton: false, 
                        timerProgressBar: true,
                        showCancelButton: false,
                        didOpen: (toast) => {
                            Swal.showLoading(); 
                            toast.style.pointerEvents = 'none';  
                        },
                        willClose: () => {
                            Swal.hideLoading(); 
                        }
                    });

                    let selected_breakdown = $("#select-breakdown").val();
                    let selected_breakdown_name = $("#select-breakdown option:selected").text();

                    $.ajax({
                        url: "Function/breakdown_func.php?breakdown="+selected_breakdown,
                        type: "GET",
                        data: {
                            breakdown: selected_breakdown,
                            datefrom: cutfrom,
                            dateto: cutto
                        },
                        success: function(response){
                            // console.log(response);
                            Swal.close();
                            backdrop.remove();
                            let jsonResponse =  JSON.parse(response);
                            // console.log(jsonResponse);
                            // Change Label Breakdown
                            $("#label-breakdown").html($("#select-breakdown option:selected").text() + " - Department");
                            let columnTitle = "Total Mins";
                            let breakdown_arr = ["obp", "wdo", "leave", "sched", "concern"];

                            if($.inArray(selected_breakdown, breakdown_arr) !== -1){
                                columnTitle = "Total Count";
                            }else if(selected_breakdown == "overtime"){
                                columnTitle = "Total Hours";
                            }

                            $('#dataTable').DataTable().destroy();
                            $('#dataTable').DataTable({
                                data: jsonResponse,
                                "columnDefs": [
                                    { "width": "auto", "targets": "_all" }
                                ],
                                "autoWidth": true,
                                columns: [
                                    {data: "areatype", title: "Area", className: "area_type"},
                                    {data: "branch", title: "Branch/Department", className: "branch"},
                                    {data: "total_hours", title: columnTitle, className: "total_hours"},
                                    {
                                        data: "userid", 
                                        title: "Action", 
                                        className: "action_btns",
                                        render: function(data, type, row) {
                                            // Customize the content of the second "Total Hours" column with HTML
                                            return '<div class="text-center">' +
                                            '<a href="breakdown.php?branch=' + data + '&category=' + selected_breakdown + '&from=' + cutfrom + '&to=' + cutto + '" class="btn btn-sm bg-transparent" target="_blank"><i class="fa fa-eye text-primary" aria-hidden="true"></i></a>' +
                                            '</div>';
                                        }
                                    },
                                ],
                                footerCallback: function (tfoot, data, start, end, display) {
                                    let api = this.api();  
                                    let total_hours = api.column(2).data().toArray().reduce(function (a, b) {
                                        return (parseFloat(a) + parseFloat(b));
                                    }, 0);
                                    let totalCount = api.rows().count(); 
                                    $(api.column(0).footer()).html('Total');
                                    $(api.column(1).footer()).html(parseInt(totalCount).toLocaleString());
                                    $(api.column(2).footer()).html(parseFloat(total_hours).toLocaleString());
                                },
                                stateSave: true,
                                dom: 'lBfrtip', 
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
                                                    let total_branch = 0; 
                                                    let total_hours = 0; 

                                                    // Add the additional header rows to the export data 
                                                    exportData.push(["Area Type",  "Branch/Department",  columnTitle]);
                                                    
                                                    // Add the DataTable data to the export data
                                                    dt.rows().every(function () {
                                                        let rowData = this.data();
                                                        total_branch++;
                                                        total_hours += parseFloat(rowData.total_hours);  
                                                        exportData.push([
                                                            rowData.areatype,
                                                            rowData.branch,
                                                            rowData.total_hours,
                                                        ]);
                                                    });
                                                    exportData.push(["TOTAL", total_branch, total_hours.toFixed(2)]);
                                                    // Convert the export data to a worksheet
                                                    let worksheet = XLSX.utils.aoa_to_sheet(exportData);
                                                    let workbook = XLSX.utils.book_new();
                                                    XLSX.utils.book_append_sheet(workbook, worksheet, 'Timesheet');
                                                    
                                                    // Save the workbook as an Excel file
                                                    XLSX.writeFile(workbook, selected_breakdown_name.toUpperCase() + " " + cutfrom + " - " + cutto + '.xlsx');
                                                }   
                                            },
                                            {
                                                extend: 'csv',
                                                text: '<i class="fas fa-file-csv"></i> CSV'
                                            },
                                            {
                                                extend: 'print',
                                                text: '<i class="fas fa-print"></i> Print',
                                                title: "Department Breakdown Report",
                                                messageTop: cutfrom + " - " + cutto + "<br>" + selected_breakdown_name,
                                                customize: function(win) {  
                                                    // Print Title Tab 
                                                    win.document.title = "Department " + selected_breakdown_name;

                                                    // Certification
                                                    let customContent = $('<div>').html('<p class="mt-3">This document is intended solely for the use of Mary Grace Foods Inc. and may contain confidential information. Any unauthorized use, disclosure, or distribution is prohibited. If you are not the intended recipient, please notify us immediately and delete this document.</p>');
                                                    $(win.document.body).append(customContent);

                                                    let totalSum = 0;
                                                    let count = 0;
                                                    $(win.document.body).find('.total_hours', '.dataTable tbody tr:visible').each(function() {  
                                                        let hours = parseFloat($(this).text()); 
                                                        if (!isNaN(hours)) {
                                                            count++;
                                                            totalSum += hours;
                                                        }
                                                    }); 

                                                    let footerRow = '<tbody><tr><td class="font-weight-bold">Total</td><td>'+ count + '</td><td>' + parseInt(totalSum).toLocaleString() + '</td><td></td></tr></tbody>'; 
                                                    $(win.document.body).find(".dataTable").append(footerRow);
                                                }
                                            },
                                            {
                                                extend: 'pdf',
                                                text: '<i class="fas fa-file-pdf"></i> PDF'
                                            }
                                        ],
                                    },
                                ],
                                order: [[2, 'desc']]
                            });
                        },
                        error: function(jqXHR, errorThrown, textStatus){
                            console.log(jqXHR, errorThrown, textStatus);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention!',
                        text: 'Select both start and end dates.',
                        position: 'top-end', // Position at the top-right corner
                        toast: true, // Display as a toast notification
                        showConfirmButton: false, // Hide the "OK" button
                        timer: 3000 // Auto-close after 3 seconds
                    });
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, errorThrown, textStatus);
        }
    });
    

    
});