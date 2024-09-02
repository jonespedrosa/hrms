<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
$btnDisabled = false; // Default value
?>
<div class="card border-0 shadow-sm mt-3">
    <div class="card-body p-0 ml-4 mr-4">
        <h5 class="mt-4" style="margin-bottom: 0;">
            <strong><?php echo htmlspecialchars($selectedConcern ?? 'Wrong filing of leave'); ?></strong>
        </h5>
        <p style="margin-top: 0;">The staff want to cancel his/her approved leave possibly due to wrong filing or wrong input of details. </p>
        <hr>
        <div class="form-group">
            <div style="text-align: center; margin-bottom: 15px;">
                <h5 style="font-weight: bold;">Leave Details</h5>
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Leave Type:</div>
                <input type="text" id="vltype" name="vltype" style="width: 100%; font-weight: bold; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; cursor: not-allowed;" disabled>
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Status:</div>
                <input type="text" id="vlstatus" name="vlstatus" style="color: green; width: 100%; font-weight: bold; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; cursor: not-allowed;" disabled>
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Reason:</div>
                <textarea id="vlreason" name="vlreason" style="width: 100%; font-weight: bold; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; cursor: not-allowed; resize: none;" rows="3" disabled></textarea>
            </div>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: bold;">Final Approver:</div>
                <input type="text" id="final-approver" name="final-approver" style="width: 100%; font-weight: bold; padding: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; cursor: not-allowed;" disabled>
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