<div class="modal fade" id="tagEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="tagEmployeeModal"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex flex-column">
                <div class="d-flex w-100">
                    <h5 class="modal-title" id="tagEmployeeModal">Tag Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <br>
                <div>
                    <select class="form-control p-2 mb-3 w-auto text-small" id="choose-department">
                        <option value="">- SELECT A DEPARTMENT -</option>
                    </select>
                </div>

            </div>
            <div class="modal-body">
                <div>
                    <label for="">Employee/s Selected:</label>
                    <div id="selectedEmployees" class="row">
                    </div>

                </div>

                <br>
                <hr>

                <div class="table-responsive">
                    <div class="d-flex justify-content-end">
                        <button id="selectAll" class="btn btn-sm btn-info mb-2">Select All</button>
                    </div>
                    <table class="table table-sm table-hover table-bordered" width="100%" id="employeeDatatable">
                        <thead>
                            <tr>
                                <th>Emp No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="employeeToTag">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewEmployees()">Save changes</button>
            </div>
        </div>
    </div>
</div>