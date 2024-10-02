<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

$empno = isset($_GET['empno']) ? $_GET['empno'] : null;
$ConcernDate = isset($_GET['concernDate']) ? $_GET['concernDate'] : null;
$attachment1 = isset($_GET['attachment1']) ? $_GET['attachment1'] : null;
$attachment2 = isset($_GET['attachment2']) ? $_GET['attachment2'] : null;

// Use the attachment1, attachment2 value as is, no need to prepend 'hrms/pdf/'
$attachment1Url = $attachment1;  // This already contains the correct path
$attachment2Url = $attachment2;  // This already contains the correct path

// Debugging: You can remove this echo later
// echo "attachment1: " . htmlspecialchars($attachment1) . "<br>";
// echo "attachment2: " . htmlspecialchars($attachment2) . "<br>";

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
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Captured Time Inputs:</label>
                <div class="table-responsive mb-0">
                    <table class="table table-bordered rounded-">
                        <thead>
                            <tr>
                                <th>Time In</th>
                                <th>Break Out</th>
                                <th>Break In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedTimeIn" name="capturedTimeIn" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="capturedBreakOut" name="capturedBreakOut" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="capturedBreakIn" name="capturedBreakIn" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedTimeOut" name="capturedTimeOut" placeholder="--:--" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Requested Time Inputs:</label>
                <div class="table-responsive">
                    <table class="table table-bordered rounded-">
                        <thead>
                            <tr>
                                <th>Time In</th>
                                <th>Break Out</th>
                                <th>Break In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="newTimeIN" name="newTimeIN" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="newBreakOut" name="newBreakOut" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="newBreakIn" name="newBreakIn" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="newTimeOut" name="newTimeOut" placeholder="--:--" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- attachment 1  -->
            <div class="form-group text-center">
                <label for="" class="mt-0" style="font-weight: bold; margin-right: 10px; margin-bottom: 0px;">Attached Document's:</label>
                <div class="d-flex justify-content-center">
                    <a href="<?php echo htmlspecialchars($attachment1Url); ?>" target="_blank" class="btn btn-primary mt-1 w-100" style="display: block; font-size: 1.1rem; font-weight: bold;  max-width: 400px;">
                        Click here to view attachment 1
                    </a>
                </div>
            </div>
            <!-- attachment 2  -->
            <div class="form-group text-center" id="attachment2DivDisplay">
                <div class="d-flex justify-content-center">
                    <a href="<?php echo htmlspecialchars($attachment2Url); ?>" target="_blank" class="btn btn-primary mt-1 w-100" style="display: block; font-size: 1.1rem; font-weight: bold;  max-width: 400px;">
                        Click here to view attachment 2
                    </a>
                </div>
            </div>
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Approver's Remarks: <span style="color: red;">*</span></label>
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