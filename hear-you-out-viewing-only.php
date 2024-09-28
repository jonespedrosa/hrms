
<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// Check connection
if (mysqli_connect_errno()) {
    echo json_encode(array('error' => 'Failed to connect to database: ' . mysqli_connect_error()));
    exit();
}

// Get empno and date_concern from URL
$empno = mysqli_real_escape_string($HRconnect, $_GET['empno']);
$date_concern = mysqli_real_escape_string($HRconnect, $_GET['ConcernDate']);
$type_concern = mysqli_real_escape_string($HRconnect, $_GET['type_concern']);


// Query to fetch data from the database
$query = "SELECT responses, attachment FROM hear_you_out WHERE empno = '$empno' AND date_submitted = '$date_concern' AND type_concern = '$type_concern' AND status = 'Active'";
$result = mysqli_query($HRconnect, $query);


if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $responses = json_decode($row['responses'], true); // Decode JSON

    // Extract data from the decoded JSON
    $employeeDetails = $responses['employee_details'];
    $name = $employeeDetails['name'];
    $position = $employeeDetails['position'];
    $typeOfEmployment = $employeeDetails['type_of_employment'];
    $placeOfIncident = $employeeDetails['place_of_incident'];
    $stateOfIncident = $employeeDetails['state_of_incident'];
    $nameSuperior = $employeeDetails['name_superior'];
    $dateOfServing = $employeeDetails['date_of_serving'];
    $employeeExplanation = $employeeDetails['employee_explanation'];
    $stateYourGoal = $employeeDetails['state_your_goal'];
    $stateRealities = $employeeDetails['state_your_realities'];
    $stateOptions = $employeeDetails['state_your_option'];
    $wayForward = $employeeDetails['way_forward'];

    // Format the date and time to YYYY-MM-DDTHH:MM
    $dateOfServingFormatted = date('Y-m-d\TH:i', strtotime($dateOfServing));

    // Retrieve the attachment file name
    $attachment = $row['attachment'];
    $attachmentUrl = !empty($attachment) ? "hyo_attachments/" . htmlspecialchars($attachment) : "";

    // Query to count DTR concerns
    $dtrConcernQuery = "SELECT COUNT(id) AS dtr_concern_count FROM dtr_concerns WHERE empno = '$empno' AND ConcernDate = '$date_concern' AND concern = '$stateOfIncident' AND status IN('Pending','Approved')";
    $dtrConcernResult = mysqli_query($HRconnect, $dtrConcernQuery);

    if ($dtrConcernResult) {
        $dtrConcernRow = mysqli_fetch_assoc($dtrConcernResult);
        $dtrConcernCount = $dtrConcernRow['dtr_concern_count'];
    } else {
        $dtrConcernCount = 0; // Default to 0 if no result is found
    }

    // // Prepare the response with both the hear_you_out data and DTR concern count
    // $response = array(
    //     'name' => $name,
    //     'position' => $position,
    //     'type_of_employment' => $typeOfEmployment,
    //     'place_of_incident' => $placeOfIncident,
    //     'state_of_incident' => $stateOfIncident,
    //     'name_superior' => $nameSuperior,
    //     'date_of_serving' => $dateOfServingFormatted,
    //     'employee_explanation' => $employeeExplanation,
    //     'state_your_goal' => $stateYourGoal,
    //     'state_realities' => $stateRealities,
    //     'state_options' => $stateOptions,
    //     'way_forward' => $wayForward,
    //     'attachment_url' => $attachmentUrl,
    //     'dtr_concern_count' => $dtrConcernCount, // Include DTR concern count
    //     'date_concern' => $date_concern // Include DTR concern count
    // );

    // // Send the response in JSON format
    // echo json_encode($response);
} else {
    echo "No record found.";
}
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
            font-weight: bold !important;
            /* Makes the text bold */
            margin: 0 10px !important;
            /* Adds space between buttons */
        }

        .swal-button-green:hover {
            background-color: #3EA371 !important;
            /* Darker shade on hover */
        }

        .swal-button-red {
            background-color: #FF6F61 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            outline: none !important;
            font-weight: bold !important;
            /* Makes the text bold */
            margin: 0 10px !important;
            /* Adds space between buttons */
        }


        .swal-button-red:hover {
            background-color: #E35D52 !important;
            /* Darker shade on hover */
        }

        /* Style for the image modal */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Modal content (image) */
        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
        }

        /* Close button */
        .close {
            position: absolute;
            top: 30px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        button:disabled {
            cursor: not-allowed;
            opacity: 0.6;
            /* Optional: makes the button look more visually disabled */
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
        <div class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div class="card-header" style="background-color: #6D4C13; color: white; font-weight: bold;">
                <!-- Card header content if needed -->
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Content -->
                        <div class="p-4">
                            <h2 style="font-weight: bold; color:black;">Hear You Out Form</h2>
                            <hr>
                            <div class="form-group mt-3">
                                <label for="fullName">Employee's Name <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter Employee's Name" readonly required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="position">Position <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="position" value="<?php echo htmlspecialchars($position); ?>" placeholder="Enter Position" readonly required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="typeEmployment">Type of Employment <span style="color:red;">*</span></label>
                                <div class="border p-3 rounded">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="probationary" value="Probationary" <?php echo ($typeOfEmployment == 'Probationary') ? 'checked' : ''; ?> disabled>
                                        <label class="form-check-label" for="probationary">Probationary</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="regular" value="Regular" <?php echo ($typeOfEmployment == 'Regular') ? 'checked' : ''; ?> disabled>
                                        <label class="form-check-label" for="regular">Regular</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="typeEmployment" id="seasonal" value="Seasonal" <?php echo ($typeOfEmployment == 'Seasonal') ? 'checked' : ''; ?> disabled>
                                        <label class="form-check-label" for="seasonal">Seasonal</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="placeIncident">Place of Incident <i>(Where did the incident take place?)</i> <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="placeIncident" value="<?php echo htmlspecialchars($placeOfIncident); ?>" placeholder="Enter Place of Incident" readonly required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="stateofIncident">State the Incident <span style="color:red;">*</span></label>
                                <textarea class="form-control" id="stateofIncident" placeholder="Enter State of Incident" rows="4" readonly required><?php echo htmlspecialchars($stateOfIncident); ?></textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label for="nameSuperior">Name of Immediate Superior <i>(Surname, First Name, Middle Initial)</i> <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" id="nameSuperior" value="<?php echo htmlspecialchars($nameSuperior); ?>" placeholder="Enter Immediate Superior" readonly required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="dateServingIncident">Date of Serving the incident/offense to the Employee <span style="color:red;">*</span></label>
                                <input type="datetime-local" class="form-control" id="dateServingIncident" value="<?php echo htmlspecialchars($dateOfServingFormatted); ?>" style="max-width: 220px; width: 100%;" readonly required>
                            </div>
                            <div class="form-group mt-3">
                                <label for="employeeExplanation">Employee's Explanation <span style="color:red;">*</span></label>
                                <textarea class="form-control" id="employeeExplanation" placeholder="Enter Employee's Explanation" rows="4" readonly required><?php echo htmlspecialchars($employeeExplanation); ?></textarea>
                            </div>
                            <div class="form-group mt-3" id="attachmentImagesContainer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="attachmentImages">Attachment <span style="color:red;">*</span></label>
                                    <em><strong style="color:red;">Note:</strong> Click the image to view it in full size.</em>
                                </div>
                                <?php if (!empty($attachmentUrl)): ?>
                                    <div class="border p-3 rounded d-flex justify-content-center">
                                        <img id="thumbnailImage" src="<?php echo $attachmentUrl; ?>" alt="Attachment" class="rounded-sm" style="max-width: 900px; height: 300px; cursor: pointer;">
                                    </div>
                                <?php else: ?>
                                    <p>No attachment available.</p>
                                <?php endif; ?>
                            </div>
                            <!-- Modal Image Attachment -->
                            <div id="imageModal" class="image-modal">
                                <span class="close">&times;</span>
                                <img class="modal-content" id="fullImage">
                            </div>
                            <div class="form-group mt-3" id="attachmentImagesEditContainer" hidden>
                                <label for="attachmentImagesEdit">Attachment (<em>Logbook or CCTV Picture</em>) <span style="color:red;">*</span></label>
                                <input type="file" class="form-control-file" id="attachmentImagesEdit" name="attachmentImagesEdit" accept=".jpeg, .jpg, .png" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div class="card-header" style="background-color: #6D4C13; color: white; font-weight: bold;">
                <!-- Card header content if needed -->
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Content -->
                    <div class="p-4">
                        <h5 style="font-weight: bold">Let's Talk About It Form</h5>
                        <h6>This portion must be filled-up by the Employee concerned during the coaching session with his Immediate Superior</h6>
                        <hr>
                        <div class="form-group mt-3">
                            <label for="stateYourGoal">State your Goal <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateYourGoal" placeholder="Your answer" rows="4" readonly required><?php echo htmlspecialchars($stateYourGoal); ?></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="stateRealities">State the <strong>Realities</strong> why the offense or incident happened. <span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateRealities" placeholder="Your answer" rows="4" readonly required><?php echo htmlspecialchars($stateRealities); ?></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="stateOptions">State your <strong>Options</strong> to prevent the same offense or incident from happening again.<span style="color:red;">*</span></label>
                            <textarea class="form-control" id="stateOptions" placeholder="Your answer" rows="4" readonly required><?php echo htmlspecialchars($stateOptions); ?></textarea>
                        </div>
                        <div class="form-group mt-3">
                            <label for="wayForward"><strong>Way Forward</strong> State your commitment.<span style="color:red;">*</span></label>
                            <textarea class="form-control" id="wayForward" placeholder="Your answer" rows="4" readonly required><?php echo htmlspecialchars($wayForward); ?></textarea>
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <button id="closeButton" class="btn btn-secondary">Close</button>
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
    var dtrConcernCount = <?php echo $dtrConcernCount; ?>;

    document.addEventListener('DOMContentLoaded', function() {

        // Get the modal
        var modal = document.getElementById("imageModal");

        // Get the image and insert it inside the modal - use its "alt" text as a caption
        var img = document.getElementById("thumbnailImage");
        var modalImg = document.getElementById("fullImage");

        img.onclick = function() {
            modal.style.display = "block";
            modalImg.src = this.src;
        };

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        };

        // Close the modal when the user clicks anywhere outside the image
        modal.onclick = function(event) {
            if (event.target !== modalImg) {
                modal.style.display = "none";
            }
        };
    });

    function displayImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('uploadedImage').style.display = 'block';
                document.getElementById('uploadedImage').src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]); // Convert image file to base64 string
        }
    }

    document.getElementById('closeButton').addEventListener('click', function() {
        // This will only work if the page was opened by a script
        window.close();
    });
</script>

</body>

</html>