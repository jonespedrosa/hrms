<?php
$btnDisabled = false; // Default value
$selectedConcern = isset($_GET['Concern']) ? $_GET['Concern'] : null;

// Determine the label text based on the value of $selectedConcern
$attachment1Label = '';
$attachment2Label = '';

if ($selectedConcern === "Hardware malfunction") {
    $attachment1Label = "(Proof of hardware malfunction)";
    $attachment2Label = "(Proof of Logbook)";
} elseif ($selectedConcern === "Persona error") {
    $attachment1Label = "(Proof of persona applications error or device error)";
    $attachment2Label = "(Proof of Logbook)";
} elseif ($selectedConcern === "Emergency time out") {
    $attachment1Label = "(Proof of emergency)";
    $attachment2Label = "(Proof of Logbook)";
} elseif ($selectedConcern === "Fingerprint problem") {
    $attachment1Label = "(Proof of faded fingerprint or unreadable by Persona)";
    $attachment2Label = "(Proof of Logbook)";

}
?>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'Persona error'); ?></strong>
        </h5>
        <p style="margin-top: 0;">The Persona device application encountered an error on Windows.</p>
        <hr>
        <div>
            <div>
                <!-- Attachments image 1 select Section -->
                <div class="attachments-container">
                    <p style="font-weight:bold; margin-bottom: 0;">Attachments 1
                        <i id="attachmentLabel" style="color: #2E59D9;"><?php echo htmlspecialchars($attachment1Label); ?></i>
                        <span style="color:red;">*</span>
                    </p>
                    <div class="input-group d-flex justify-content-center">
                        <!-- File input field -->
                        <input type="file" name="attachment1" id="attachment1" class="form-control" style="height: 45px;" accept=".jpg,.jpeg,.png" />
                    </div>
                </div>
                <!-- Attachments image 2 select Section -->
                <div class="attachments-container mt-3">
                    <p style="font-weight:bold; margin-bottom: 0;">Attachments 2
                        <i id="attachmentLabel" style="color: #2E59D9;"><?php echo htmlspecialchars($attachment2Label); ?></i>
                        <span style="color:red;">*</span>
                    </p>
                    <div class="input-group d-flex justify-content-center">
                        <!-- File input field -->
                        <input type="file" name="attachment2" id="attachment2" class="form-control" style="height: 45px;" accept=".jpg,.jpeg,.png" />
                    </div>
                </div>
                <!-- Table-like structure for time inputs -->
                <div class="time-inputs-container mt-3">
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
                <!-- Table-like structure for proposed inputs -->
                <div class="time-inputs-container">
                    <!-- Container for checkbox with proper spacing -->
                    <div class="checkbox-section" style="display: flex; justify-content: space-between; align-items: center;">
                        <p style="font-weight:bold; margin-bottom: 0;">Proposed Time Inputs <span style="color:red;">*</span></p>
                        <!-- Checkbox with text "With 1 hour break" -->
                        <div class="checkbox-container">
                            <label style="font-weight:bold;">
                                <input type="checkbox" id="oneHourBreakCheckbox">
                                Check If No Break
                            </label>
                        </div>
                    </div>
                    <!-- Header Row -->
                    <div class="time-inputs-header">
                        <div class="header-item">TIME IN</div>
                        <div class="header-item">BREAK OUT</div>
                        <div class="header-item">BREAK IN</div>
                        <div class="header-item">TIME OUT</div>
                    </div>
                    <!-- Input Row -->
                    <div class="time-inputs proposed-inputs">
                        <input type="text" id="proposedTimeIn" class="form-control" placeholder="00:00">
                        <input type="text" id="proposedBreakOut" class="form-control" placeholder="00:00">
                        <input type="text" id="proposedBreakIn" class="form-control" placeholder="00:00">
                        <input type="text" id="proposedTimeOut" class="form-control" placeholder="00:00">
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
                <div class="text-right mt-3 mb-3">
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
</div>