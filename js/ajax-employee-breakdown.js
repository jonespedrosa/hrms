$(document).ready(function (){ 
    let urlSearchParams = new URLSearchParams(window.location.search);
    let branch = urlSearchParams.get("branch");
    let category = urlSearchParams.get("category");
    let cutfrom = urlSearchParams.get("from");
    let cutto = urlSearchParams.get("to");
    let category_name;
    let categoryMappings = {
        "overtime": "Overtime",
        "obp": "OBP",
        "wdo": "WDO",
        "sched": "Change Schedule",
        "concern": "Concern",
        "leave": "Leave",
        "late": "Late",
        "overbreak": "Overbreak",
        "undertime": "Undertime"
    };
    if (categoryMappings[category]) {
        category_name = categoryMappings[category];
    } else {
        category_name = "Unknown";
    }

    $.ajax({
        url: "Function/breakdown_employee_func.php?action=getBreakdown",
        type: "GET",
        data: {
            category: category,
            branch: branch,
            cutfrom: cutfrom,
            cutto: cutto
        },
        success: function(response){
            $("#dataTable").DataTable().destroy();

            let columnTitle = "Total Mins";
            let breakdown_arr = ["obp", "wdo", "leave", "sched", "concern"];
            if($.inArray(category, breakdown_arr) !== -1){
                columnTitle = "Total Count";
            }else if(category == "overtime"){
                columnTitle = "Total Hours";
            }

            let jsonResponse = JSON.parse(response);
            let branch_name = jsonResponse[0].branch;
            $('#dataTable').DataTable({
                data: jsonResponse,
                "columnDefs": [
                    { "width": "auto", "targets": "_all" }
                ],
                "autoWidth": true,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], 
                pageLength: 100,
                columns: [
                    {data: "empno", title: "Emp no.", className: "empno"},
                    {data: "name", title: "Name", className: "name"},
                    {data: "branch", title: "Branch/Department", className: "branch"},
                    {data: "total_hours", title: columnTitle, className: "total_hours"},
                ],
                footerCallback: function (tfoot, data, start, end, display) {
                    let api = this.api();  
                    let total_hours = api.column(3).data().toArray().reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    let totalCount = api.rows().count(); 
                    $(api.column(0).footer()).html('Total');
                    $(api.column(1).footer()).html(totalCount);
                    $(api.column(3).footer()).html(parseFloat(total_hours).toLocaleString());
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
                                    exportData.push(["Emp No.",  "Name", "Branch/Department",  columnTitle]);
                                    
                                    // Add the DataTable data to the export data
                                    dt.rows().every(function () {
                                        let rowData = this.data();
                                        total_branch++;
                                        total_hours += parseFloat(rowData.total_hours);  
                                        exportData.push([
                                            rowData.empno,
                                            rowData.name,
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
                                    XLSX.writeFile(workbook, branch_name.toUpperCase() + " " + category_name.toUpperCase() + " " + cutfrom + " - " + cutto + '.xlsx');
                                }   
                            },
                            {
                                extend: 'csv',
                                text: '<i class="fas fa-file-csv"></i> CSV'
                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print"></i> Print', 
                                title: "Employee Breakdown Report",
                                messageTop: cutfrom + " - " + cutto + "<br>" + branch_name + " " + category_name,
                                customize: function(win) {  
                                    // Print Title Tab 
                                    win.document.title = branch_name + " " + category_name;

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

                                    let footerRow = '<tbody><tr><td class="font-weight-bold">Total</td><td colspan = "2">'+ count +'</td><td>' + parseInt(totalSum).toLocaleString() + '</td></tr></tbody>'; 
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
                order: [[3, 'desc']]
            }); 
        },
        error: function(jqXHR, errorThrown, textStatus){
            console.log(jqXHR, errorThrown, textStatus);
        }
    });
});