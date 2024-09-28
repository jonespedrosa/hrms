<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
session_start();

// Ensure the user is validated
if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
    exit();
}

// Echo the session variable (optional for debugging)
// echo "User Validate: " . $_SESSION['user_validate'] . "<br>";

// Ensure $empno is defined from the URL
if (isset($_GET['empno'])) {
    $empno = $_GET['empno'];
} else {
    die("Error: Employee number is not provided in the URL.");
}

// Compare $empno from the URL with the session value
if ($empno !== $_SESSION['user_validate']) {
    // If they don't match, redirect to the logout page
    header("Location:index.php?&m=2");
    exit();
}

// If empno matches the session value, you can proceed with the rest of the code
// echo "Employee Number: " . $empno;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        #cardContent label,
        #cardContent h2,
        h6,
        h5,
        p,
        #cardContent .form-check-label,
        #cardContent i {
            color: black !important;
        }

        .card-body {
            text-align: justify;
        }

        /* Optional: To ensure the last line is also justified, add this */
        .card-body::after {
            content: "";
            display: block;
            width: 100%;
            height: 0;
            clear: both;
        }

        .swal-button-green {
            background-color: #48BF81 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            outline: none !important;
        }

        .swal-button-green:hover {
            background-color: #48BF81 !important;
            /* Optional: darker shade on hover */
        }
    </style>
</head>

<form id="hearYouOutForm" enctype="multipart/form-data">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <img src="images/hearyouout_images_headers.jfif" alt="Header Image" class="img-fluid rounded" style="width: 100%; height: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="contentCard" class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div id="cardHeader" class="card-header" style="background-color: #6D4C13; color: white; font-weight: bold;">
            </div>
            <div class="card-body p-0 ">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Content -->
                        <div id="cardContent" class="p-4">
                            <h2 style="font-weight: bold">Hear You Out Form</h2>
                            <hr>
                            <div class="form-group mt-3">
                                <label for="typeEmployment">Type of Employment <span style="color:red;">*</span></label>
                                <div class="border p-3 rounded">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="probationary" value="Probationary">
                                        <label class="form-check-label" for="probationary">
                                            Probationary
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="regular" value="Regular">
                                        <label class="form-check-label" for="regular">
                                            Regular
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="seasonal" value="Seasonal">
                                        <label class="form-check-label" for="seasonal">
                                            Seasonal
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="placeIncident">Place of Incident <i>(Where did the incident take place?)</i> <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="placeIncident" placeholder="Enter Place of Incident" required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="stateofIncident">State the Incident <span style="color:red;">*</span></label>
                                <textarea class="form-control" id="stateofIncident" placeholder="Enter State of Incident" rows="4" disabled></textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label for="nameSuperior">Name of Immediate Superior <i>(Surname, First Name, Middle Initial)</i> <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="nameSuperior" placeholder="Enter Immediate Superior" required>
                            </div>
                            <div class="form-group mt-3">
                            <label for="dateServingIncident">Date of Serving the incident/offense to the Employee <span style="color:red;">*</span></label>
                                <input type="datetime-local" class="form-control" id="dateServingIncident" style="max-width: 220px; width: 100%;"  disabled>
                            </div>
                            <div class="form-group mt-3">
                                <label for="employeeExplanation">Employee's Explanation <span style="color:red;">*</span></label>
                                <textarea class="form-control" id="employeeExplanation" placeholder="Enter Employee's Explanation" rows="4" required></textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label for="attachmentImages">Attachment (<em>Logbook or CCTV Picture</em>) <span style="color:red;">*</span></label>
                                <input type="file" class="form-control-file" id="attachmentImages" name="attachment" accept=".jpeg, .jpg, .png" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="contentCard" class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div id="cardHeader" class="card-header" style="background-color: #6D4C13; color: white; font-weight: bold;">
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Content -->
                    <div id="cardContent" class="p-4">
                        <h5 style="font-weight: bold">Lets Talk About It Form</h5>
                        <h6>This portion must be filled-up by the Employee concerned during the coaching session with
                            his Immediate Superior</h6>
                        <hr>
                        <div class="form-group mt-3">
                            <label for="stateYourGoal">State your Goal <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateYourGoal" placeholder="Your answer" rows="4"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="stateRealities">State the <strong>Realities</strong> why the offense or incident happened. <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateRealities" placeholder="Your answer" rows="4"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="stateOptions">State your <strong>Options</strong> to prevent the same offense or incident happen again.<span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateOptions" placeholder="Your answer" rows="4"></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="wayForward"><strong>Way Forward</strong> State your commitment.<span style="color:red;">*</span></label>
                            <textarea class="form-control" id="wayForward" placeholder="Your answer" rows="4"></textarea>
                        </div>
                        <div class="form-group mt-3 ml-4">
                            <h6 class="mt-5 mb-5">
                                <input class="form-check-input" type="checkbox" id="certificationCheckbox" style="margin-right: 10px; margin-top: 2px;">
                                <label for="certificationCheckbox" style="display: flex; align-items: center; cursor: pointer;"><i>I hereby certify that the information provided in this form is complete, true, and correct to the best of my knowledge.</i>
                                </label>
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <button id="goBack" class="btn btn-secondary">Back</button>
                            <button id="submitButton" class="btn btn-primary" style="font-weight: bold;">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>
    $(document).ready(function() {
        // Function to get query parameters by name
        function getQueryParam(param) {
            var urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        // Get the current date and time
        var now = new Date();
        var year = now.getFullYear();
        var month = ('0' + (now.getMonth() + 1)).slice(-2);
        var day = ('0' + now.getDate()).slice(-2);
        var hours = ('0' + now.getHours()).slice(-2);
        var minutes = ('0' + now.getMinutes()).slice(-2);

        // Create the date string in the format YYYY-MM-DD HH:MM (without 'T')
        var nowDateTime = `${year}-${month}-${day} ${hours}:${minutes}`;

        // Set the value of the input to the current date and time without the 'T'
        document.getElementById('dateServingIncident').value = nowDateTime;

        // Function to reformat the datetime-local value to 'YYYY-MM-DD HH:MM'
        function formatDateTime(dateTimeLocalValue) {
            // Replace 'T' with a space to match 'YYYY-MM-DD HH:MM'
            return dateTimeLocalValue.replace('T', ' ');
        }

        // Set the Concern value into the stateofIncident textarea
        var concernValue = getQueryParam('Concern');
        if (concernValue) {
            $('#stateofIncident').val(decodeURIComponent(concernValue));
        }

        // Function to update employeeDetails with current form values
        function updateEmployeeDetails() {
            return {
                empno: getQueryParam('empno'),
                name: getQueryParam('name'),
                position: getQueryParam('position'),
                concern_date: getQueryParam('ConcernDate'),
                type_concern: getQueryParam('type_concern'),
                type_errors: getQueryParam('type_errors'),
                type_of_employment: $('input[name="typeEmployment"]:checked').val() || 'Not Specified',
                concern_category: getQueryParam('Concern'),
                place_of_incident: $('#placeIncident').val(),
                state_of_incident: $('#stateofIncident').val(),
                name_superior: $('#nameSuperior').val(),
                date_of_serving: formatDateTime($('#dateServingIncident').val()),
                employee_explanation: $('#employeeExplanation').val(),
                state_your_goal: $('#stateYourGoal').val(),
                state_your_realities: $('#stateRealities').val(),
                state_your_option: $('#stateOptions').val(),
                way_forward: $('#wayForward').val()
            };
        }

        // Event listener for the submit button
        $('#submitButton').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission

            // Update employeeDetails object with the latest values
            var employeeDetails = updateEmployeeDetails();

            const attachment = document.getElementById('attachmentImages');

            // Check if the file input is empty (no file selected)
            if (attachment.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attachment Required',
                    text: 'Please attach a file before submitting.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'swal-button-green'
                    },
                });
                return; // Prevent form submission
            }

            // Validate required fields
            var isValid = true;
            var requiredFields = [
                'type_of_employment', 'place_of_incident', 'state_of_incident',
                'name_superior', 'date_of_serving', 'employee_explanation',
                'state_your_goal', 'state_your_realities', 'state_your_option',
                'way_forward'
            ];

            for (var i = 0; i < requiredFields.length; i++) {
                var field = requiredFields[i];
                if (!employeeDetails[field] || employeeDetails[field].trim() === '') {
                    isValid = false;
                    break;
                }
            }

            // Check if the checkbox is checked
            var isCheckboxChecked = $('#certificationCheckbox').is(':checked');

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Required Missing',
                    text: 'Please check all required fields and fill them in!',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'swal-button-green' // Apply the custom class to the confirm button
                    },
                });
                return; // Stop the function if validation fails
            } else if (!isCheckboxChecked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Certification Required',
                    text: 'Please confirm that the information provided is accurate by checking the certification box.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'swal-button-green' // Apply the custom class to the confirm button
                    },
                });
                return; // Stop the function if the checkbox is not checked
            }

            // Wrap the details in the "employee_details" label
            var wrappedData = {
                employee_details: employeeDetails
            };

            // Prepare FormData to handle file upload along with the form data
            var formData = new FormData($('#hearYouOutForm')[0]);

            // Add other form data
            formData.append('response', JSON.stringify(wrappedData)); // Include your JSON data

            // Send the data to your backend using AJAX
            $.ajax({
                type: 'POST',
                url: 'insert_hear_you_out.php',
                data: formData,
                processData: false, // Prevent jQuery from converting the data into a query string
                contentType: false, // Prevent jQuery from setting the content type header
                success: function(response) {
                    // Handle success response
                    console.log(wrappedData);
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Successfully submitted Hear You Out form!",
                        footer: "Please continue to fill up concern form", // Add footer with the message
                        showConfirmButton: false, // Hide confirm button
                        timer: 3000, // Auto-hide after 1.5 seconds
                        timerProgressBar: true // Show progress bar
                    }).then(() => {
                        // Ensure the redirect happens after the Swal closes
                        var redirectUrl = `/hrms/filing-concerns.php?concern=concern&empno=${encodeURIComponent(employeeDetails.empno)}&date=${encodeURIComponent(employeeDetails.concern_date)}&dtrconcern=${encodeURIComponent(employeeDetails.concern_category)}&type_errors=${encodeURIComponent(employeeDetails.type_errors)}`;
                        window.location.href = redirectUrl;
                    });
                },
                error: function(error) {
                    // Handle error response
                    console.error('Error:', error);
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "An error occurred!",
                        text: "Please try again later.",
                        showConfirmButton: true
                    });
                }
            });
        });
    });

    document.getElementById('goBack').addEventListener('click', function() {
        window.history.back();
    });
</script>

</body>

</html>