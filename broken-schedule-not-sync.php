<?php
$btnDisabled = false; // Default value
?>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'Broken schedule did not sync'); ?></strong>
        </h5>
        <p style="margin-top: 0;">The staff has broken schedule time inputs on the persona <i>(based on the logs history)</i> but did not reflect on his/her Web DTR.</p>
        <hr>
        <div class="form-group">
            <!-- Attachments image select Section -->
            <div class="attachments-container">
            <p style="font-weight:bold; margin-bottom: 0;">Attachments <i style="color: #2E59D9;">(Logs History)</i> <span style="color:red;">*</span></p>
                <div class="input-group mt-3 d-flex justify-content-center">
                    <!-- File input field -->
                    <input type="file" name="attachment1" id="attachment1" class="form-control" style="height: 45px;" accept=".jpg,.jpeg,.png" />
                </div>
            </div>
            <hr>
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
                <!-- Container for checkbox with proper spacing -->
                <div class="checkbox-section" style="display: flex; justify-content: space-between; align-items: center;">
                    <p style="font-weight:bold; margin-bottom: 0;">Proposed Time Inputs <span style="color:red;">*</span></p>
                </div>
                <!-- Header Row -->
                <div class="time-inputs-header">
                    <div class="header-item">BROKEN SCHEDULE IN</div>
                    <div class="header-item">BROKEN SCHEDULE OUT</div>
                </div>
                <!-- Input Row -->
                <div class="time-inputs proposed-inputs">
                    <input type="text" id="proposedBrokenSchedIn" class="form-control" placeholder="00:00">
                    <input type="text" id="proposedBrokenSchedOut" class="form-control" placeholder="00:00">
                </div>
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