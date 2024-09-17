<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

$empno = isset($_GET['empno']) ? $_GET['empno'] : null;
$ConcernDate = isset($_GET['concernDate']) ? $_GET['concernDate'] : null;
$attachment1 = isset($_GET['attachment1']) ? $_GET['attachment1'] : null;

// Use the attachment1 value as is, no need to prepend 'hrms/pdf/'
$attachmentUrl = $attachment1;  // This already contains the correct path

// Debugging: You can remove this echo later
// echo "attachment1: " . htmlspecialchars($attachment1) . "<br>";

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
            <!-- Dynamic Division -->
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
        <!-- Dynamic Division -->
        <div id="">
            <div class="form-group mb-1" style="text-align: left;">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Captured Time Inputs:</label>
                <div class="table-responsive">
                    <table class="table table-bordered rounded-">
                        <thead>
                            <tr>
                                <th>Broken Sched In</th>
                                <th>Broken Sched Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedBrokenSchedTimeIn" name="capturedBrokenSchedTimeIn" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedBrokenSchedTimeOut" name="capturedBrokenSchedTimeOut" placeholder="--:--" readonly></td>
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
                                <th>Broken Sched In</th>
                                <th>Broken Sched Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="brokenSchedTimeIn" name="brokenSchedTimeIn" placeholder="--:--" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="brokenSchedTimeOut" name="brokenSchedTimeOut" placeholder="--:--" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group text-center mb-4">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Attached Document's:</label>
                <div class="d-flex justify-content-center">
                    <a href="<?php echo htmlspecialchars($attachmentUrl); ?>" target="_blank" class="btn btn-primary mt-1 w-100" style="display: block; font-size: 1.1rem; font-weight: bold;  max-width: 400px;">
                        Click here to view logs history attachment
                    </a>
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
        /* Ensure the border-radius is applied even to table content */
    }

    th {
        text-align: center;
        vertical-align: middle;
        background-color: #f0f0f0;
        /* Light gray background */
        padding: 5px 10px !important;
        /* Top and bottom padding 5px, left and right 10px */
    }
</style>