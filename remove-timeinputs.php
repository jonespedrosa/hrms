<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Retrieve the parameters from the URL
$empno = isset($_GET['empno']) ? $_GET['empno'] : null;
$concernDate = isset($_GET['concernDate']) ? $_GET['concernDate'] : null;
$name = isset($_GET['name']) ? $_GET['name'] : null;
$position = isset($_GET['position']) ? $_GET['position'] : null;
$selectedConcern = isset($_GET['Concern']) ? $_GET['Concern'] : null;
$type_concern = isset($_GET['type_concern']) ? $_GET['type_concern'] : null;
$type_errors = isset($_GET['type_errors']) ? $_GET['type_errors'] : null;
$btnDisabled = false; // Default value

if ($empno && $concernDate) {
    // Prepare and execute the query using type_concern
    $sql = "SELECT COUNT(*) AS submission_count FROM hear_you_out WHERE empno = ? AND date_submitted = ? AND type_concern = ?";
    $stmt = $HRconnect->prepare($sql);
    $stmt->bind_param("sss", $empno, $concernDate, $type_concern);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the user has already submitted the form
    $formSubmitted = $row['submission_count'] > 0;

    // Construct the URL based on whether the form was submitted or not
    if ($formSubmitted) {
        $hearYouOutUrl = "hear-you-out-view-only.php?empno=$empno&type_concern=" . urlencode($type_concern) . "&ConcernDate=" . urlencode($concernDate);
        $linkText = "You already submitted HYO form, click to review";
        $linkAction = "window.open('$hearYouOutUrl', '_blank'); return false;";
    } else {
        $hearYouOutUrl = "hear-you-out.php?empno=$empno&name=" . urlencode($name) . "&position=" . urlencode($position) . "&ConcernDate=" . urlencode($concernDate) . "&type_concern=" . urlencode($type_concern) . "&Concern=" . urlencode($selectedConcern) . "&type_errors=" . urlencode($type_errors);
        $linkText = "Click here to create and submit your HYO form";
        $linkAction = "window.location.href='$hearYouOutUrl'; return false;";
        $btnDisabled = true; // Disable the button if the form has not been submitted
    }
}
?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'Remove time inputs'); ?></strong>
        </h5>
        <p style="margin-top: 0;">The employee forgot to use their fingerprint for one of their time entries.</p>
        <hr>
        <div class="form-group">
            <!-- Table-like structure for time inputs -->
            <div class="time-inputs-container">
                <p style="font-weight:bold; margin-bottom: 0;">Captured Time Inputs</p>
                <!-- Header Row -->
                <div class="time-inputs-header">
                    <div class="header-item">TIME IN</div>
                    <div class="header-item">BREAK OUT</div>
                    <div class="header-item">BREAK IN</div>
                    <div class="header-item">TIME OUT</div>
                </div>
                <!-- Input Row -->
                <div class="time-inputs captured-inputs d-flex justify-content-center align-items-center">
                    <input type="text" id="capturedTimeIn" class="form-control" disabled>
                    <input type="text" id="capturedBreakOut" class="form-control" disabled>
                    <input type="text" id="capturedBreakIn" class="form-control" disabled>
                    <input type="text" id="capturedTimeOut" class="form-control" disabled>
                </div>
            </div>
            <!-- Specific Remove time inputs Dropdown -->
            <div class="mb-3">
                <label for="removeTimeinputs" class="mb-0">
                    <h6><strong>Select what time inputs to remove</strong> <span style="color: red;">*</span></h6>
                </label>
                <select class="form-control" id="removeTimeinputs" required>
                    <option value="" disabled selected>Select what time inputs to remove</option>
                    <option value="timeIn">Time In</option>
                    <option value="breakOut">Break Out</option>
                    <option value="breakIn">Break In</option>
                    <option value="timeOut">Time Out</option>
                    <option value="brokenSchedIn">Broken Sched In</option>
                    <option value="brokenSchedOut">Broken Sched Out</option>
                    <option value="allRegularInputs">All Regular Inputs</option>
                    <option value="allBrokenSchedInputs">All Broken Sched Inputs</option>
                </select>
            </div>
            <!-- reason -->
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Reason <span style="color: red;">*</span></div>
                <textarea id="concern_reason" name="concern_reason" style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: none;" rows="3" required placeholder="Please, enter your reason"></textarea>
            </div>
            <!-- Agreement Section -->
            <div class="agreement-container mt-2">
                <p style="font-weight: bold; margin-bottom: 10px;">Agreement <span style="color: red;">*</span></p>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="agreementCheckbox" required>
                    <label class="form-check-label" for="agreementCheckbox">
                        I hereby certify that the above information provided is correct. Any falsification of
                        information in this regard may form grounds for disciplinary action up to and
                        including dismissal.
                    </label>
                </div>
            </div>
            <!-- Proceed Button -->
            <div class="text-right mt-3">
                <button
                    type="button"
                    id="btnSubmit"
                    class="btn btn-primary"
                    style="font-weight: bold; margin-top: 20px; <?php echo $btnDisabled ? 'cursor: not-allowed;' : ''; ?>"
                    <?php echo $btnDisabled ? 'disabled' : ''; ?>>
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    textarea:focus {
        border-color: #999;
        /* Change the border color on focus */
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        /* Optional: Add a subtle shadow for better focus indication */
        outline: none;
        /* Ensure no default outline appears */
    }
</style>