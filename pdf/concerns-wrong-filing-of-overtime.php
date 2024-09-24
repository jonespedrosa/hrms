<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

$empno = isset($_GET['empno']) ? $_GET['empno'] : null;
$ConcernDate = isset($_GET['concernDate']) ? $_GET['concernDate'] : null;
$type_concern = isset($_GET['type_of_concern']) ? $_GET['type_of_concern'] : null;

// echo "empno: " . htmlspecialchars($empno) . "<br>";
// echo "ConcernDate: " . htmlspecialchars($ConcernDate) . "<br>";
// echo "type_of_concern: " . htmlspecialchars($type_concern) . "<br>";

?>
<div class="responsive-container">
    <div class="box">
        <div class="content">
            <h5 class="" style="color: #434343; font-weight: bold; text-align: left; display: block;">
                Employee Details
            </h5>
            <hr style="margin: 0; margin-bottom: 10px">
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold;">Employee ID:</label>
                <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase" name="employeeNumber" id="employeeNumber" style="font-size:100%" readonly />
            </div>
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold;">Employee Name:</label>
                <input type="text" class="form-control form-control-user bg-gray-100 text-center text-uppercase mb-2" name="employeeName" id="employeeName" style="font-size: 1rem;" readonly />
            </div>
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold;">Branch:</label>
                <input type="text" class="form-control form-control-user bg-gray-100 text-center" name="employeeBranch" id="employeeBranch" style="font-size:100%" readonly />
            </div>
            <div id="">
                <h5 class="mt-3" style="color: #434343; font-weight: bold; text-align: left; display: block;">
                    Concerns Details
                </h5>
                <hr style="margin: 0; margin-bottom: 10px">
                <div class="form-group mb-1 mt-2" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Date of Concerns:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="dateOfConcerns" id="dateOfConcerns" style="font-size:100%" readonly />
                </div>
                <div class="form-group mb-1 mt-2" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Type of Concerns:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="typeOfConcerns" id="typeOfConcerns" style="font-size:100%" readonly />
                </div>
                <div class="form-group mb-1 mt-2" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Type of Errors:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="typeOfError" id="typeOfError" style="font-size:100%" readonly />
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div id="">
        <div class="form-group mb-1" style="text-align: left;">
                    <div class="table-responsive">
                        <table class="table table-bordered rounded-">
                            <thead>
                                <tr>
                                    <th>Schedule Time Out</th>
                                    <th>Actual Time Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedScheduleOut" name="capturedScheduleOut" readonly></td>
                                    <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedTimeOut" name="capturedTimeOut" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <div class="form-group mb-1" style="text-align: left;">
                <div class="table-responsive">
                    <table class="table table-bordered rounded-">
                        <thead>
                            <tr>
                                <th>Overtime Calculation</th>
                                <th>Filed OT Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="overTimeCalculation" name="overTimeCalculation" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="filedOThours" name="filedOThours" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Approver's Remarks:</label>
                <textarea pattern="^[-@.\/#&+\w\s]*$" style="height:100px;" maxlength="1000" class="form-control text-left" id="approverRemarks" name="approverRemarks" placeholder="Enter your remark" required></textarea>
            </div>
            <div class="d-flex flex-column mt-4">
                <input type="submit" name="btnApproved" class="btn btn-success btn-user font-weight-bold mb-2" value="Approved">
                <input type="submit" name="btnDisapproved" class="btn btn-danger btn-user font-weight-bold mb-2" value="Disapproved">
            </div>
        </div>
    </div>
</div>
<style>
    .table {
        border-radius: 2px;
        overflow: hidden;
    }

    th {
        text-align: center;
        vertical-align: middle;
        background-color: #f0f0f0;
        padding: 5px 10px !important;
    }
</style>