<?php
$btnDisabled = false; // Default value
?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'File broken sched overtime'); ?></strong>
        </h5>
        <p style="margin-top: 0;">The staff can file for overtime due to general meetings or cleaning activities.</p>
        <hr>
        <div class="form-group">
            <!-- Table-like structure for time inputs -->
            <div class="time-inputs-container">
                <p style="font-weight:bold; margin-bottom: 0;">Captured Time Inputs</p>
                <!-- Header Row -->
                <div class="time-inputs-header">
                    <div class="header-item">BROKEN SCHEDULE IN</div>
                    <div class="header-item">BROKEN SCHEDULE OUT</div>
                </div>
                <!-- Input Row -->
                <div class="time-inputs captured-inputs d-flex justify-content-center align-items-center">
                    <input type="text" id="capturedBrokenSchedIn" name="capturedBrokenSchedIn" class="form-control text-center" disabled>
                    <input type="text" id="capturedBrokenSchedOut" name="capturedBrokenSchedOut" class="form-control text-center" disabled>
                </div>
            </div>
            <!-- Table-like structure for proposed inputs -->
            <div class="time-inputs-container">
                <div style="margin-bottom: 10px;">
                    <div style="font-weight: bold;">Maximim number of OT hours that can be filed: <span id="current_othours" style="color: red;"> 0 </span> </div>
                    <input type="number" id="othours" name="othours" style="width: 20%; font-weight: bold; padding: 5px; border: 1px solid #ccc; border-radius: 4px; text-align: center; box-sizing: border-box;">
                </div>
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

    /* Add styles for the input field on focus */
    #othours:focus {
        border-color: #999;
        /* Change the border color on focus */
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        /* Optional: Add a subtle shadow for better focus indication */
        outline: none;
        /* Ensure no default outline appears */
    }
</style>