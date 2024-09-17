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

?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'Wrong computation'); ?></strong>
        </h5>
        <p style="margin-top: 0;">Identifying mistakes in system daily time record computations.</p>
        <hr>
        <div class="form-group">
            <!-- Specific Wrong computation Dropdown -->
            <div class="mb-3">
                <label for="wrongComputation" class="mb-0">
                    <h6><strong>Select wrong computation</strong> <span style="color: red;">*</span></h6>
                </label>
                <select class="form-control" id="wrongComputation" required>
                    <option value="" disabled selected>Select wrong computation</option>
                    <option value="working_days">Number of Working Days</option>
                    <option value="overtime">Overtime Hours</option>
                    <option value="undertime">Undertime Hours</option>
                    <option value="regular_holiday">Regular Holiday</option>
                    <option value="special_holiday">Special Holiday</option>
                    <option value="night_differential">Night Differential Pay</option>
                    <option value="leave">Leave Taken</option>
                    <option value="late">Late Arrival</option>
                    <option value="working_day_off">Working Day Off</option>
                </select>
            </div>
            <!-- reason -->
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Reason <span style="color: red;">*</span></div>
                <textarea id="concern_reason" name="concern_reason" style="width: 100%; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: none;" rows="3" required placeholder="Please, enter your reason"></textarea>
            </div>
            <hr>
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