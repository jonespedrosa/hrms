let selectedEmployee = []; 
let lastSelectedCategory = 'all';
$(function() {  
    onGenerateCompressed('all');
    getDepartments().then(function(response){
            let jsonResponse = JSON.parse(response); 
            $.each(jsonResponse, function(index, value) {
                $('#select-department').append($('<option>', {
                    value: value.userid,
                    text: value.department.toUpperCase() + " - " + value.branch.toUpperCase() 
                })); 
                $('#choose-department').append($('<option>', {
                    value: value.userid,
                    text: value.department.toUpperCase() + " - " + value.branch.toUpperCase() 
                }));
            });
        }).catch(function(jqXHR, textStatus, errorThrown){
            console.log(jqXHR, textStatus, errorThrown)
        });

    // Select Department Listener
    $('#select-department').on('change', function (){
        lastSelectedCategory = $(this).val();
        onGenerateCompressed($(this).val());
    });  
    
}); 

// Employee Tag
$(function(){
    $('#employeeDatatable').DataTable();
    $('#choose-department').on('change', function() {
        getDepartmentEmployee($(this).val(), 0).then(function(response) {
            let jsonResponse = JSON.parse(response);
            $('#employeeDatatable').DataTable().destroy();
            let dataTable = $('#employeeDatatable').DataTable({
                data: jsonResponse,
                "columnDefs": [{
                    "width": "auto",
                    "targets": "_all"
                }],
                "autoWidth": true,
                columns: [{
                        data: "empno",
                        title: "Emp No",
                        className: "empno"
                    },
                    {
                        data: "name",
                        title: "Name",
                        className: "name"
                    },
                    {
                        data: "empno",
                        title: "Action",
                        className: "action_btns",
                        render: function(data, type, row) {
                            return `<div class="text-center">
                            <button 
                                onclick="addEmployee('${row.name}', '${row.branch}', '${row.empno}')" 
                                class="btn btn-success btn-sm select-emp" 
                                data-id="${row.empno}" 
                                data-name="${row.name}" 
                                data-branch="${row.branch}">
                                    Add
                                </button>
                            </div>`;
                        }
                    },
                ],
            });
    
            // Add event listener to select all button
            $('#selectAll').on('click', function() {
                $('#employeeDatatable').DataTable().rows().every(function() {
                    var $row = $(this.node()); 
                    var $actionBtn = $row.find('.select-emp'); 
                    var empno = $actionBtn.data('id'); 
                    var name = $actionBtn.data('name'); 
                    var branch = $actionBtn.data('branch');   
            
                    // Check if the selectedEmployee array contains duplicates based on 'empno'
                    let employeeExists = _.includes(selectedEmployee, parseInt(empno)); 
                    if (!employeeExists) {
                        selectedEmployee.push(empno);
            
                        const xmark = `<button onclick="onRemove(${empno})" class="btn btn-sm rounded bg-danger mr-3"><svg class="text-white" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg></button>`;
                        let selectedEmployees = $('#selectedEmployees');  
            
                        selectedEmployees.append(`<div class="col-lg-6 my-1">
                            <div id="added-${empno}" class="d-flex align-items-center mb-3">${xmark}<p class="m-0">${empno} - ${name} - ${branch}</p></div>
                        </div>`); 
                    } 
                }); 
            });
    
        }).catch(function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown)
        });
    });
    
});


function getDepartmentEmployee(departmentId, type){ 
    return $.ajax({
        url: "Function/department_func.php?department=employee",
        type: 'GET', 
        data:{
            departmentId: departmentId,
            type: type
        }
    });
} 

function getDepartments(){
    return $.ajax({
        url: "Function/department_func.php?department=all",
        type: 'GET', 
    });
}

function addEmployee(name,branch,empno){  
    let employeeExists = _.includes(selectedEmployee, parseInt(empno)); 
    if (!employeeExists) { 
        selectedEmployee.push(parseInt(empno));
        
        const xmark = `<button onclick="onRemove(${empno})" class="btn btn-sm rounded bg-danger mr-3"><svg class="text-white" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg></button>`;
        let selectedEmployees = $('#selectedEmployees');  

        selectedEmployees.append(`<div class="col-lg-6 my-1">
        <div id="added-${empno}" class="d-flex align-items-center mb-3">${xmark}<p class="m-0">${empno} - ${name} - ${branch}</p></div>
        </div>`); 
    } else {
        Swal.fire({
            title: 'Duplicate Employee',
            text: 'An employee with the same employee number already exists.',
            icon: 'warning',
            toast: true,
            position: 'top-end', 
            showConfirmButton: false,
            timer: 1600, 
            timerProgressBar: true, 
        });
    } 
}

function onRemove(empno){ 
    $('#added-'+empno).parent().remove();   
    _.pull(selectedEmployee, empno);  
}

function submitNewEmployees(){
    $.ajax({
        url: 'Function/department_func.php?department=newEmployee',
        type: 'POST',
        data: {
            newEmployee: selectedEmployee ?? []
        },
        success: function(response){
            Swal.fire({
                title: 'Added Successfully',
                text: 'Employees are successfully tagged as compressed', 
                icon: 'success',
                timer: 1500, 
                timerProgressBar: true,
                showConfirmButton: false,
            }).then(() => {
                location.reload();
            });
        },
        error: function(jqXHR, textStatus, errorThrown){
            Swal.fire({
                title: 'Adding Failed',
                text: 'Updating employee as compressed has encountered an error', 
                icon: 'danger',
                timer: 1500, 
                timerProgressBar: true,
                showConfirmButton: false,
            })
        }
    });
}

function onGenerateCompressed($category){
    getDepartmentEmployee($category, 1).then(function(response) {
        let jsonResponse = JSON.parse(response);
        $('#compressedEmployees').DataTable().destroy();
        let dataTable = $('#compressedEmployees').DataTable({
            data: jsonResponse,
            "columnDefs": [{
                "width": "auto",
                "targets": "_all"
            }],
            "autoWidth": true,
            columns: [
                {
                    data: "empno",
                    title: "Emp No",
                    className: "empno"
                },
                {
                    data: "name",
                    title: "Name",
                    className: "name"
                },
                {
                    data: "branch",
                    title: "Branch",
                    className: "branch"
                },
                {
                    data: "department",
                    title: "Department",
                    className: "department"
                },
                {
                    data: "empno",
                    title: "Action",
                    className: "action_btns",
                    render: function(data, type, row) {
                        return `<div class="text-center">
                        <button 
                            onclick="uncompressEmployee('${row.name}', '${row.department}', '${row.empno}')" 
                            class="btn btn-danger btn-sm select-emp" 
                            data-id="${row.empno}" 
                            data-name="${row.name}" 
                            data-branch="${row.branch}">
                                Remove
                            </button>
                        </div>`;
                    }
                },
            ],
        });
    });
}

function uncompressEmployee(name, branch, empno){
    Swal.fire({
        title: "Uncompress Employee?",
        html: `
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="mb-1"><strong>Emp No:</strong> ${empno}</p>
                    <p class="mb-1"><strong>Name:</strong> ${name}</p>
                    <p class="mb-1"><strong>Department:</strong> ${branch}</p>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p>Will be tagged as uncompressed.</p>
                </div>
            </div>
        </div>`,
        icon: "warning",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        cancelButtonColor: "#d33",
        confirmButtonColor: "#3085d6",
        confirmButtonText: "Confirm",
        reverseButtons: true 
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'Function/department_func.php?department=uncompress',
                type: 'POST',
                data: {
                    empno: empno ?? []
                },
                success: function(response){
                    Swal.fire({
                        title: "Employee Uncompressed",
                        text: "The employee has been uncompressed successfully.",
                        icon: "success",
                        timer: 1500, 
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(() => {
                        onGenerateCompressed(lastSelectedCategory);
                    }); 
                },
                error: function(jqXHR, textStatus, errorThrown){
                    Swal.fire({
                        title: 'Removing Failed',
                        text: 'Updating employee as uncompressed has encountered an error', 
                        icon: 'danger',
                        timer: 1500, 
                        timerProgressBar: true,
                        showConfirmButton: false,
                    })
                }
            });
        }
    }); 
}