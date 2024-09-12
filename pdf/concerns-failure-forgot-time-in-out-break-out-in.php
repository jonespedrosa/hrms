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
                <div class="form-group mb-1" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Date of Concerns:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="dateOfConcerns" id="dateOfConcerns" style="font-size:100%" readonly />
                </div>
                <div class="form-group mb-1" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Type of Concerns:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="typeOfConcerns" id="typeOfConcerns" style="font-size:100%" readonly />
                </div>
                <div class="form-group mb-1" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold;">Type of Errors:</label>
                    <input type="text" class="form-control bg-gray-100 text-center text-uppercase" name="typeOfError" id="typeOfError" style="font-size:100%" readonly />
                </div>
                <div class="form-group mb-1" style="text-align: left;">
                    <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Captured Time Inputs:</label>
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
                                    <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedTimeIn" name="capturedTimeIn" readonly></td>
                                    <td><input class="form-control bg-gray-100 text-center" type="text" id="capturedBreakOut" name="capturedBreakOut" readonly></td>
                                    <td><input class="form-control bg-gray-100 text-center" type="text" id="capturedBreakIn" name="capturedBreakIn" readonly></td>
                                    <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="capturedTimeOut" name="capturedTimeOut" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box">
        <div id="">
            <h5 class="" style="color: #434343; font-weight: bold; text-align: left; display: block;">
                Requested Details
            </h5>
            <hr style="margin: 0; margin-bottom: 10px">
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
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="newTimeIN" name="newTimeIN" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="newBreakOut" name="newBreakOut" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center" type="text" id="newBreakIn" name="newBreakIn" readonly></td>
                                <td><input class="form-control bg-gray-100 text-center text-uppercase" type="text" id="newTimeOut" name="newTimeOut" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group text-center mb-4">
                <label for="" class="mb-1" style="font-weight: bold; margin-right: 10px;">Attached Document's:</label>
                <div class="d-flex justify-content-center">
                    <a href="../hear-you-out-view-only.php?empno=<?php echo $empno; ?>&type_concern=<?php echo $type_concern; ?>&ConcernDate=<?php echo $ConcernDate; ?>" target="_blank" class="btn btn-primary mt-1 w-100" style="display: block; font-size: 0.9rem; font-weight: bold;  max-width: 300px;">Click here to view HYO attachment</a>
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
    /* Make the table border rounded */
    .table {
        border-radius: 2px;
        overflow: hidden; /* Ensure the border-radius is applied even to table content */
    }

    /* Center text inside <th> elements and set background color to gray */
    th {
        text-align: center;
        vertical-align: middle;
        background-color: #f0f0f0; /* Light gray background */
        padding: 5px 10px !important; /* Top and bottom padding 5px, left and right 10px */
    }
</style>