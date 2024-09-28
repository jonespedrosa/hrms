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

// Extract the concern date and date range from URL
$concernDate = isset($_GET['date']) ? $_GET['date'] : '';
$cutfrom = isset($_GET['cutfrom']) ? $_GET['cutfrom'] : '';
$cutto = isset($_GET['cutto']) ? $_GET['cutto'] : '';

// QUERY TO GET THE EMPLOYEE NAME
$sqlGetEmployee = "SELECT empno, name, position, branch, area_type, userlevel, userid FROM user_info WHERE empno = ?";
$stmtGetEmployee  = $HRconnect->prepare($sqlGetEmployee);
$stmtGetEmployee->bind_param("s", $empno); // Assuming empno is a string
$stmtGetEmployee->execute();
$resultGetEmployee = $stmtGetEmployee->get_result();
$employeeData = $resultGetEmployee->fetch_array(MYSQLI_ASSOC);
$empno = $employeeData['empno'];
$name = $employeeData['name'];
$position = $employeeData['position'];
$branch = $employeeData['branch'];
$area_type = $employeeData['area_type'];
$userlevel = $employeeData['userlevel'];
$userid = $employeeData['userid'];

// QUERY TO GET THE PENDING CUT-OFF DATE USING LEFT JOIN
$getDateSQL = "SELECT si.datefrom, si.dateto, si.empno
            FROM user_info ui
            LEFT JOIN sched_info si ON si.empno = ui.empno
            WHERE si.status = 'Pending' AND ui.empno = ?
            ORDER BY si.datefrom ASC";
$stmtDate = $HRconnect->prepare($getDateSQL);
$stmtDate->bind_param("s", $empno); // Assuming empno is a string
$stmtDate->execute();
$resultDate = $stmtDate->get_result();
$rowCutOff = $resultDate->fetch_array(MYSQLI_ASSOC);

$mindate = $rowCutOff['datefrom'];
$maxdate = $rowCutOff['dateto'];

// Set default concernDate to $mindate if it's empty
if (empty($concernDate)) {
    $concernDate = $mindate;
}

// NEW QUERY TO GET TIME INPUTS
$sqlTimeInputs = "SELECT empno, datefromto, schedfrom, schedto, break, M_timein, M_timeout, A_timein, A_timeout, timein4, timeout4
                FROM sched_time
                WHERE empno = ? AND datefromto = ?";
$stmtTimeInputs = $HRconnect->prepare($sqlTimeInputs);
$stmtTimeInputs->bind_param("ss", $empno, $concernDate); // Assuming empno and concernDate are strings
$stmtTimeInputs->execute();
$resultTimeInputs = $stmtTimeInputs->get_result();
$timeInputs = $resultTimeInputs->fetch_array(MYSQLI_ASSOC);

$M_timein = isset($timeInputs['M_timein']) ? $timeInputs['M_timein'] : null;
$M_breakout = isset($timeInputs['M_timeout']) ? $timeInputs['M_timeout'] : null;
$A_breakin = isset($timeInputs['A_timein']) ? $timeInputs['A_timein'] : null;
$A_timeout = isset($timeInputs['A_timeout']) ? $timeInputs['A_timeout'] : null;

$timein4 = isset($timeInputs['timein4']) ? $timeInputs['timein4'] : null; // broken schedule in
$timeout4 = isset($timeInputs['timeout4']) ? $timeInputs['timeout4'] : null; // broken schedule out

// Embed PHP variables into JavaScript
echo "<script>
    const M_timein = '$M_timein';
    const M_breakout = '$M_breakout';
    const A_breakin = '$A_breakin';
    const A_timeout = '$A_timeout';
    const timein4 = '$timein4';
    const timeout4 = '$timeout4';
    const mindate = '$mindate';
    const maxdate = '$maxdate';
</script>";


// Close the prepared statements and connection when done
$stmtGetEmployee->close();
$stmtDate->close();
$stmtTimeInputs->close();
$HRconnect->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Existing meta tags, title, and links -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        .custom-select-width {
            width: 70% !important;
            /* Adjust the width percentage as needed */
        }

        .time-inputs-container {
            margin-bottom: 20px;
        }

        .time-inputs-header {
            display: flex;
            background-color: #f5f5f5;
            /* Gray background similar to DataTable */
            padding: 5px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .header-item {
            flex: 1;
            text-align: center;
            font-weight: bold;
            padding: 5px;
        }

        .time-inputs {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .time-inputs .form-control {
            flex: 1;
            margin: 0 5px;
            min-width: 150px;
            /* Adjust as needed */
        }

        .captured-inputs .form-control {
            background-color: #e9ecef;
            /* Light gray for disabled state */
            cursor: not-allowed;
            /* Indicate that the input is not editable */
        }

        .time-inputs.proposed-inputs .form-control {
            text-align: center;
            /* Center text inside inputs */
            width: 100px;
            /* Adjust width as needed */
        }
        .time-inputs.captured-inputs .form-control {
            text-align: center;
            /* Center text inside inputs */
            width: 100px;
            /* Adjust width as needed */
        }

        .input-group {
            display: flex;
            align-items: center;
            /* Vertically center the input and button */
        }

        .input-group .form-control {
            border-radius: 0.25rem;
            /* Rounded corners for the input field */
        }

        .input-group .btn {
            border-radius: 0 0.25rem 0.25rem 0;
            /* Round only the right corners */
            height: 38px;
            /* Match button height to input field */
            font-size: 0.875rem;
            /* Slightly smaller font size */
            padding: 0.375rem 0.75rem;
            /* Adjust padding for a smaller button */
        }

        .input-group .btn-primary {
            background-color: #007bff;
            /* Bootstrap primary button color */
            border: 1px solid #007bff;
            /* Match button border with color */
            color: #fff;
            /* Text color inside button */
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

        @media (max-width: 440px) {
            .form-flex {
                flex-direction: column;
                justify-content: start;
                align-items: flex-start;
                /* Ensures content aligns to the left */
            }

            .ml-2 {
                margin-left: 0 !important;
            }

            .w-50 {
                width: 100% !important;
            }

            /* Ensure h6 text aligns to the left */
            h6 {
                text-align: left !important;
            }

            /* Optional: Ensure label aligns to the left */
            label {
                text-align: left !important;
                width: 100%;
                /* To ensure label spans full width */
            }
        }
    </style>
</head>

<body class="bg-gradient-muted">
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <a href="index.php" class="navbar-brand">
            <img src="images/logoo.png" height="35" alt=""> <i style="color:#7E0000;font-family:Times New Roman, cursive;font-size:120%;">Mary Grace Café</i>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto text-center">
                <a href="login.php" class="nav-item nav-link" style="font-family:Times New Roman, cursive;font-size:120%;">Login</a>
            </div>
        </div>
    </nav>

    <div class="container p-3 my-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h5 class="card-title m-0 text-primary" style="font-weight: bold;">Filing Concerns</h5>
                    </div>
                    <div class="header d-flex flex-row align-items-center justify-content-between mt-2 mr-2 ml-2">
                        <a class="ml-3 mr-3" style="font-weight: bold;" href="index.php?empno=<?php echo $empno; ?>&SubmitButton=Submit&cutfrom=<?php echo $mindate; ?>&cutto=<?php echo $maxdate; ?>">Go Back</a>
                        <a class="ml-3 mr-3" style="font-weight: bold;" href="pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=<?php echo $empno; ?>&cutfrom=<?php echo $mindate; ?> &cutto=<?php echo $maxdate; ?>">View Filed Concerns</a>
                    </div>
                    <hr class="ml-4 mr-4">
                    <div class="card-body p-0 ml-4 mr-4">
                        <h5 style="margin-bottom: 0;"><strong>Select Concern Type & Date</strong></h5>
                        <p style="margin-top: 0;">Choose the category of your concern and specify the date it occurred.</p>
                        <hr>



                        <div class="form-group">
                            <!-- Employees Name -->
                            <div class="d-flex form-flex justify-content-between align-items-center mb-3">
                                <label for="employeeName" class="mb-0">
                                    <h6><strong>Employee Name </strong><span style="color: red;">*</span></h6>
                                </label>
                                <input type="text" class="form-control custom-select-width w-50 ml-2" id="employeeName" value="<?php echo htmlspecialchars($name); ?>" required disabled>
                            </div>
                            <!-- Concern Date with Date-Time Picker -->
                            <div class="d-flex form-flex justify-content-between align-items-center mb-3">
                                <label for="concernDate" class="mb-0">
                                    <h6><strong>Concern Date </strong><span style="color: red;">*</span></h6>
                                </label>
                                <input type="date" class="form-control custom-select-width w-50 ml-2" id="concernDate" name="date"
                                    placeholder="Select the date"
                                    min="<?php echo htmlspecialchars($cutfrom); ?>"
                                    max="<?php echo htmlspecialchars($cutto); ?>"
                                    value="<?php echo htmlspecialchars($concernDate); ?>"
                                    required>
                            </div>
                            <!-- Concern Type Dropdown -->
                            <div class="d-flex form-flex justify-content-between align-items-center mb-3">
                                <label for="concernType" class="mb-0">
                                    <h6><strong>Concern Type</strong> <span style="color: red;">*</span></h6>
                                </label>
                                <select class="form-control custom-select-width w-50 ml-2" id="concernType" required>
                                    <option value="" disabled selected>Select Concern Type</option>
                                    <option value="userError">User Error</option>
                                    <option value="systemError">System Error</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                            <!-- Specific Concern Dropdown -->
                            <div class="d-flex form-flex justify-content-between align-items-center mb-3">
                                <label for="specificConcern" class="mb-0">
                                    <h6><strong>Specific Concern</strong> <span style="color: red;">*</span></h6>
                                </label>
                                <select class="form-control custom-select-width w-50 ml-2" id="specificConcern" required>
                                    <option value="" disabled selected>Select Specific Concern</option>
                                </select>
                            </div>


                            <!-- Proceed Button -->
                            <div class="text-right mt-3">
                                <button type="button" class="btn btn-primary" id="btnProceed" style="font-weight: bold;">Proceed</button>
                            </div>
                        </div>






                    </div>
                </div>
                <div class="card border-0 shadow-sm mt-3" id="displayConcernSelected">
                    <!-- Dispaly Selected Concern  -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright © Mary Grace Foods Inc. 2019</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

    <!-- Existing Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // Get the URL parameters
            const urlParams = new URLSearchParams(window.location.search);

            // Extract the parameters
            const concernDate = urlParams.get('date');
            const dtrConcern = urlParams.get('dtrconcern');
            const typeErrors = urlParams.get('type_errors');

            // Flag to check if conditions are met
            let shouldProceed = true;

            // Set the values in the form fields
            if (concernDate) {
                document.getElementById('concernDate').value = concernDate;
            } else {
                shouldProceed = false;
            }

            if (typeErrors) {
                const typeErrorValue =
                    typeErrors === 'User Error' ? 'userError' :
                    typeErrors === 'System Error' ? 'systemError' :
                    typeErrors === 'Others' ? 'others' : '';

                if (typeErrorValue) {
                    document.getElementById('concernType').value = typeErrorValue;
                } else {
                    shouldProceed = false;
                }
            } else {
                shouldProceed = false;
            }

            if (dtrConcern) {
                const specificConcernDropdown = document.getElementById('specificConcern');
                let optionExists = false;
                for (let i = 0; i < specificConcernDropdown.options.length; i++) {
                    if (specificConcernDropdown.options[i].value === dtrConcern) {
                        optionExists = true;
                        break;
                    }
                }

                if (!optionExists) {
                    const newOption = document.createElement('option');
                    newOption.value = dtrConcern;
                    newOption.text = decodeURIComponent(dtrConcern);
                    newOption.selected = true;
                    specificConcernDropdown.appendChild(newOption);
                } else {
                    specificConcernDropdown.value = dtrConcern;
                }
            } else {
                shouldProceed = false;
            }

            // Only trigger the button click if all conditions are met
            if (shouldProceed) {
                document.getElementById('btnProceed').click();
            }
        });

        // Define concerns data
        const concerns = {
            userError: [
                "Failure/Forgot to time in or time out",
                "Failure/Forgot to break in or break out",
                "Failure/Forgot to click broken schedule",
                "Failure/Forgot to click half day",
                "Wrong filing of OBP",
                "Not following break out and break in interval",
            ],
            systemError: [
                "Time inputs did not sync",
                "Misaligned time inputs",
                "Broken Schedule did not sync",
                "Persona error",
                "Hardware malfunction",
                "Wrong computation"
            ],
            others: [
                "Wrong filing of overtime",
                "Wrong filing of leave",
                "Remove time inputs",
                "Emergency time out",
                "Fingerprint problem",
                "File broken sched overtime"
            ]
        };

        // Update Specific Concern dropdown based on selected Concern Type
        document.getElementById('concernType').addEventListener('change', function() {
            const concernType = this.value;
            const specificConcernSelect = document.getElementById('specificConcern');

            // Clear previous options
            specificConcernSelect.innerHTML = '<option value="" disabled selected>Select Specific Concern</option>';

            if (concerns[concernType]) {
                concerns[concernType].forEach(concern => {
                    const option = document.createElement('option');
                    option.value = concern;
                    option.textContent = concern;
                    specificConcernSelect.appendChild(option);
                });
            }
        });

        // Handle displaying selected concern and fetching time data
        document.getElementById('btnProceed').addEventListener('click', function() {
            const selectedConcern = document.getElementById('specificConcern').value;
            const concernDate = document.getElementById('concernDate').value;
            const type_error = document.getElementById('concernType').value;
            const displayDiv = document.getElementById('displayConcernSelected');
            const empno = "<?php echo htmlspecialchars($empno); ?>"; // Adjust according to your server-side variables
            const name = "<?php echo htmlspecialchars($name); ?>";
            const position = "<?php echo htmlspecialchars($position); ?>";
            let type_concern;

            // Check if the selected concernDate is within the range of mindate and maxdate
            if (concernDate < mindate || concernDate > maxdate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    html: `You are not allowed to select a date outside the range of <strong>Cut-off</strong> <strong>${mindate}</strong> and <strong>${maxdate}</strong>.`,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'swal-button-green'
                    }
                }).then(() => {
                    // Reload the page after the modal is dismissed
                    window.location.reload();
                });
                return; // Stop further execution if the date is invalid
            }

            // Map concernType to its descriptive label
            let type_errors =
                type_error === 'userError' ? 'User Error' :
                type_error === 'systemError' ? 'System Error' :
                type_error === 'others' ? 'Others' : '';

            if (!concernDate) {
                Swal.fire({
                    icon: 'error',
                    title: 'Date required',
                    text: 'Please select a concern date before proceeding!'
                });
                return;
            }

            if (!selectedConcern) {
                Swal.fire({
                    icon: 'error',
                    title: 'Concern Category Required',
                    text: 'Please select concern category before proceeding!'
                });
                return;
            }

            // Helper function to extract time from datetime
            function extractTime(datetime) {
                if (!datetime || datetime === "No Break") return datetime; // Return "No Break" if it's not a valid datetime
                const date = new Date(datetime);
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${hours}:${minutes}`;
            }

            // Map concernType to its descriptive label
            let concernTypeLabel =
                type_error === 'userError' ? 'User Error' :
                type_error === 'systemError' ? 'System Error' :
                type_error === 'others' ? 'Others' : '';

            // Map userlevel to its descriptive label
            let userlevelMapped;
            const userlevel = "<?php echo htmlspecialchars($userlevel); ?>";

            // Assign userlevel based on empno
            if (empno == 271 || empno == 107 || empno == 4625) {
                userlevelMapped = 'ac';
            } else if (empno == 1348 || empno == 2525 || empno == 1964 || empno == 141) {
                userlevelMapped = 'mod';
            } else if (empno == 3612 || empno == 1509 || empno == 4072 || empno == 3080 || empno == 2008 || empno == 5182) {
                userlevelMapped = 'staff';
            } else if (userlevel == 'master') {
                userlevelMapped = 'staff';
            } else {
                userlevelMapped = "<?php echo htmlspecialchars($employeeData['userlevel']); ?>";
            }

            // Determine type_concern based on selectedConcern value
            if (selectedConcern === "Failure/Forgot to time in or time out") {
                type_concern = 1;
            } else if (selectedConcern === "Failure/Forgot to break in or break out") {
                type_concern = 2;
            } else if (selectedConcern === "Failure/Forgot to click broken schedule") {
                type_concern = 3;
            } else if (selectedConcern === "Failure/Forgot to click half day") {
                type_concern = 4;
            } else if (selectedConcern === "Wrong filing of overtime") {
                type_concern = 5;
            } else if (selectedConcern === "Wrong filing of OBP") {
                type_concern = 7;
            } else if (selectedConcern === "Not following break out and break in interval") {
                type_concern = 8;
            } else if (selectedConcern === "Remove time inputs") {
                type_concern = 9;
            }

            if (selectedConcern === "Failure/Forgot to time in or time out" || selectedConcern === "Failure/Forgot to break in or break out" || selectedConcern === "Failure/Forgot to click half day" || selectedConcern === "Not following break out and break in interval") {
                // Construct the URL with additional parameters
                const url = `forgot-wrong-time-in-out-or-break-out-in.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Check if the concern is "Not following break out and in interval" and update the <p> tag text
                        if (selectedConcern === "Not following break out and break in interval") {
                            const pTag = displayDiv.querySelector('p');
                            if (pTag) {
                                pTag.textContent = "The employee did not observe the required interval between break out and break in times. "; // Update the text
                            }

                            // Check if the concern is "Failure/Forgot to click half day" and update the <p> tag text
                        } else if (selectedConcern === "Failure/Forgot to click half day") {
                            const pTag = displayDiv.querySelector('p');
                            if (pTag) {
                                pTag.textContent = "The employee did not follow the requirement to click the half-day button when taking a half day. "; // Update the text
                            }
                        }

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);
                                            // Display data on proposedTimeIn and proposedBreakOut proposedBreakIn, proposedTimeOut
                                            if (selectedConcern === "Failure/Forgot to click half day") {
                                                document.getElementById('proposedTimeIn').value = extractTime(data.M_timein);
                                                document.getElementById('proposedBreakOut').value = "No Break";
                                                document.getElementById('proposedBreakIn').value = "No Break";
                                                document.getElementById('proposedTimeOut').value = extractTime(data.A_timeout);
                                            } else {
                                                document.getElementById('proposedTimeIn').value = extractTime(data.M_timein);
                                                document.getElementById('proposedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                                document.getElementById('proposedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                                document.getElementById('proposedTimeOut').value = extractTime(data.A_timeout);
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }
                        // Fetch and set the time data based on the selected concern date
                        fetchTimeData(concernDate);

                        // Add event listener for the checkbox in the loaded content
                        document.getElementById('oneHourBreakCheckbox').addEventListener('change', function() {
                            const oneHourBreakChecked = this.checked;
                            const proposedBreakOut = document.getElementById('proposedBreakOut');
                            const proposedBreakIn = document.getElementById('proposedBreakIn');

                            // Get the captured values
                            const capturedBreakOut = document.getElementById('capturedBreakOut').value;
                            const capturedBreakIn = document.getElementById('capturedBreakIn').value;

                            // Set the value based on checkbox state
                            const breakOutValue = oneHourBreakChecked ? "No Break" : capturedBreakOut;
                            const breakInValue = oneHourBreakChecked ? "No Break" : capturedBreakIn;

                            proposedBreakOut.value = breakOutValue;
                            proposedBreakIn.value = breakInValue;
                        });

                        // Disable inputs based on selectedConcern
                        if (selectedConcern === "Failure/Forgot to time in or time out") {
                            // Disable proposedBreakIn and proposedBreakOut
                            document.getElementById('proposedBreakIn').disabled = true;
                            document.getElementById('proposedBreakOut').disabled = true;
                        } else if (selectedConcern === "Failure/Forgot to break in or break out" || selectedConcern === "Not following break out and break in interval") {
                            // Disable proposedTimeIn and proposedTimeOut
                            document.getElementById('proposedTimeIn').disabled = true;
                            document.getElementById('proposedTimeOut').disabled = true;
                        } else if (selectedConcern === "Failure/Forgot to click half day") {
                            // Disable proposedBreakIn and proposedBreakOut
                            document.getElementById('proposedBreakIn').disabled = true;
                            document.getElementById('proposedBreakOut').disabled = true;

                            // Automatically check the oneHourBreakCheckbox
                            document.getElementById('oneHourBreakCheckbox').checked = true;
                            // Trigger the change event manually to update the proposedBreakIn and proposedBreakOut values
                            document.getElementById('oneHourBreakCheckbox').dispatchEvent(new Event('change'));

                            // Disable the oneHourBreakCheckbox
                            oneHourBreakCheckbox.disabled = true;
                        }

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const M_timein = document.getElementById('capturedTimeIn').value;
                            const M_timeout = document.getElementById('capturedBreakOut').value;
                            const A_timein = document.getElementById('capturedBreakIn').value;
                            const A_timeout = document.getElementById('capturedTimeOut').value;
                            const proposedTimeIn = document.getElementById('proposedTimeIn').value; // New input
                            const proposedBreakOut = document.getElementById('proposedBreakOut').value; // New input
                            const proposedBreakIn = document.getElementById('proposedBreakIn').value; // New input
                            const proposedTimeOut = document.getElementById('proposedTimeOut').value; // New input
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;

                            // Check if any of the proposed time inputs are empty based on selectedConcern
                            if (selectedConcern === "Failure/Forgot to time in or time out") {
                                if (!proposedTimeIn || !proposedTimeOut) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Missing Inputs',
                                        text: 'Please enter the proposed Time In and Time Out before submitting.',
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'swal-button-green'
                                        },
                                    });
                                    return; // Prevent form submission
                                }
                            } else if (selectedConcern === "Failure/Forgot to break in or break out") {
                                if (!proposedBreakOut || !proposedBreakIn) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Missing Inputs',
                                        text: 'Please enter the proposed Break Out and Break In before submitting.',
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'swal-button-green'
                                        },
                                    });
                                    return; // Prevent form submission
                                }
                            }

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                actualIN: M_timein,
                                actualbOUT: M_timeout,
                                actualBIN: A_timein,
                                actualOUT: A_timeout,
                                proposedTimeIn: proposedTimeIn,
                                proposedBreakOut: proposedBreakOut,
                                proposedBreakIn: proposedBreakIn,
                                proposedTimeOut: proposedTimeOut,
                                status: "Pending"
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Failure/Forgot to click broken schedule") {

                // Handle loading the "Failure/Forgot to click broken schedule" form
                const url = `forgot-to-click-broken-schedule.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            // Set the broken schedule inputs
                                            document.getElementById('capturedBrokenSchedIn').value = data.timein4 ? extractTime(data.timein4) : "";
                                            document.getElementById('capturedBrokenSchedOut').value = data.timeout4 ? extractTime(data.timeout4) : "";
                                            // Display data on proposedBrokenSchedIn and proposedBrokenSchedOut
                                            document.getElementById('proposedBrokenSchedIn').value = data.timein4 ? extractTime(data.timein4) : "";
                                            document.getElementById('proposedBrokenSchedOut').value = data.timeout4 ? extractTime(data.timeout4) : "";
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const capturedBrokenSchedIn = document.getElementById('capturedBrokenSchedIn');
                            const capturedBrokenSchedOut = document.getElementById('capturedBrokenSchedOut');
                            const proposedBrokenSchedIn = document.getElementById('proposedBrokenSchedIn').value; // New input
                            const proposedBrokenSchedOut = document.getElementById('proposedBrokenSchedOut').value; // New input
                            const timein4 = capturedBrokenSchedIn ? capturedBrokenSchedIn.value : '';
                            const timeout4 = capturedBrokenSchedOut ? capturedBrokenSchedOut.value : '';
                            // Handle timein4 and timeout4 values
                            const timein4Processed = timein4 !== '' ? timein4 : '';
                            const timeout4Processed = timeout4 !== '' ? timeout4 : '';

                            // Check if any of the proposed time inputs are empty
                            if (!proposedBrokenSchedIn || !proposedBrokenSchedOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                capturedBrokenSchedIn: timein4Processed,
                                capturedBrokenSchedOut: timeout4Processed,
                                proposedBrokenSchedIn: proposedBrokenSchedIn,
                                proposedBrokenSchedOut: proposedBrokenSchedOut,
                                status: "Pending"
                            };
                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Wrong filing of overtime") {
                // Handle loading the "Wrong filing of overtime" form
                const url = `wrong-filing-of-overtime.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            // Display the fetched data in the input fields
                                            document.getElementById('othours').value = data.othours || ''; // Set to empty string if null/undefined
                                            document.getElementById('otstatus').value = data.otstatus || '';
                                            document.getElementById('otreason').value = data.otreason || '';
                                            document.getElementById('partial-approver').value = data.p_approver || ''; // Assuming p_approver is partial approver
                                            document.getElementById('final-approver').value = data.approver || ''; // Assuming approver is final approver
                                            // Check if othours is empty after setting the value
                                            if (document.getElementById('othours').value === "" || document.getElementById('otstatus').value == 'pending') {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'You don\'t have a filed overtime on this date',
                                                    text: 'Ensure all overtime submissions are approved before proceeding.',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'swal-button-green'
                                                    },
                                                }).then(function() {
                                                    // Redirect to the specific page with dynamic parameters
                                                    const empno = "<?php echo $empno; ?>";
                                                    const mindate = "<?php echo $mindate; ?>";
                                                    const maxdate = "<?php echo $maxdate; ?>";
                                                    window.location.href = `filing-concerns.php?concern=concern&dtrconcern&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                });
                                                return; // Prevent further processing
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const othours = document.getElementById('othours').value;
                            const otstatus = document.getElementById('otstatus').value;
                            const otreason = document.getElementById('otreason').value;
                            const partialApprover = document.getElementById('partial-approver').value;
                            const finalApprover = document.getElementById('final-approver').value;
                            const concern_reason = document.getElementById('concern_reason').value;

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if concern_reason is empty or null
                            if (!concern_reason || concern_reason.trim() === "") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Reason Required',
                                    text: 'You must provide a reason for the concern before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                status: "Pending",
                                othours: othours,
                                otstatus: otstatus,
                                otreason: otreason,
                                partialApprover: partialApprover,
                                finalApprover: finalApprover,
                                concern_reason: concern_reason
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Wrong filing of leave") {

                // Handle loading the "Wrong filing of overtime" form
                const url = `wrong-filing-of-leave.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            // Display the fetched data in the input fields
                                            document.getElementById('vltype').value = data.vltype || ''; // Set to empty string if null/undefined
                                            document.getElementById('vlstatus').value = data.vlstatus || '';
                                            document.getElementById('vlreason').value = data.vlreason || '';
                                            document.getElementById('final-approver').value = data.vl_approver || ''; // Assuming vl_approver is final approver
                                            // Check if vltype is empty after setting the value
                                            if (document.getElementById('vlstatus').value !== 'approved') {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'You don\'t have a filed leave on this date',
                                                    text: 'Ensure all leave submissions are approved before proceeding.',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'swal-button-green'
                                                    },
                                                }).then(function() {
                                                    // Redirect to the specific page with dynamic parameters
                                                    const empno = "<?php echo $empno; ?>";
                                                    const mindate = "<?php echo $mindate; ?>";
                                                    const maxdate = "<?php echo $maxdate; ?>";
                                                    window.location.href = `filing-concerns.php?concern=concern&dtrconcern&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                });
                                                return; // Prevent further processing
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const vltype = document.getElementById('vltype').value;
                            const vlstatus = document.getElementById('vlstatus').value;
                            const finalApprover = document.getElementById('final-approver').value;
                            const concern_reason = document.getElementById('concern_reason').value;

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if concern_reason is empty or null
                            if (!concern_reason || concern_reason.trim() === "") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Reason Required',
                                    text: 'You must provide a reason for the concern before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                status: "Pending",
                                vltype: vltype,
                                vlstatus: vlstatus,
                                concern_reason: concern_reason,
                                finalApprover: finalApprover,
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Wrong filing of OBP") {

                // Construct the URL with additional parameters
                const url = `wrong-filing-of-obp.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Helper function to extract time from datetime
                        function extractTime(datetime) {
                            if (!datetime || datetime === "No Break") return datetime; // Return "No Break" if it's not a valid datetime
                            const date = new Date(datetime);
                            const hours = String(date.getHours()).padStart(2, '0');
                            const minutes = String(date.getMinutes()).padStart(2, '0');
                            return `${hours}:${minutes}`;
                        }

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);

                                            // Check if obp_status is null or pending directly from the fetched data
                                            if (!data.obp_status || data.obp_status === 'pending') {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'You don\'t have a filed OBP on this date',
                                                    text: 'Ensure all OBP submissions are approved before proceeding.',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'swal-button-green'
                                                    },
                                                }).then(function() {
                                                    // Redirect to the specific page with dynamic parameters
                                                    const empno = "<?php echo $empno; ?>";
                                                    const mindate = "<?php echo $mindate; ?>";
                                                    const maxdate = "<?php echo $maxdate; ?>";
                                                    window.location.href = `filing-concerns.php?concern=concern&dtrconcern&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                });
                                                return; // Prevent further processing
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }
                        // Fetch and set the time data based on the selected concern date
                        fetchTimeData(concernDate);

                        // Add event listener for the checkbox in the loaded content
                        document.getElementById('oneHourBreakCheckbox').addEventListener('change', function() {
                            const oneHourBreakChecked = this.checked;
                            const proposedBreakOut = document.getElementById('proposedBreakOut');
                            const proposedBreakIn = document.getElementById('proposedBreakIn');

                            const value = oneHourBreakChecked ? "No Break" : "";
                            proposedBreakOut.value = value;
                            proposedBreakIn.value = value;
                        });

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const M_timein = document.getElementById('capturedTimeIn').value;
                            const M_timeout = document.getElementById('capturedBreakOut').value;
                            const A_timein = document.getElementById('capturedBreakIn').value;
                            const A_timeout = document.getElementById('capturedTimeOut').value;
                            const proposedTimeIn = document.getElementById('proposedTimeIn').value; // New input
                            const proposedBreakOut = document.getElementById('proposedBreakOut').value; // New input
                            const proposedBreakIn = document.getElementById('proposedBreakIn').value; // New input
                            const proposedTimeOut = document.getElementById('proposedTimeOut').value; // New input
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;

                            // Check if any of the proposed time inputs are empty
                            if (!proposedTimeIn || !proposedBreakOut || !proposedBreakIn || !proposedTimeOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                actualIN: M_timein,
                                actualbOUT: M_timeout,
                                actualBIN: A_timein,
                                actualOUT: A_timeout,
                                proposedTimeIn: proposedTimeIn,
                                proposedBreakOut: proposedBreakOut,
                                proposedBreakIn: proposedBreakIn,
                                proposedTimeOut: proposedTimeOut,
                                status: "Pending"
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Remove time inputs") {

                // Handle loading the "Wrong filing of overtime" form
                const url = `remove-timeinputs.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const concern_reason = document.getElementById('concern_reason').value;
                            const removeTimeinputs = document.getElementById('removeTimeinputs').value;


                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if reason is empty or null
                            if (!concern_reason || concern_reason.trim() === "") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Reason Required',
                                    text: 'You must provide a reason for the concern before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if removeTimeinputs is not selected
                            if (!removeTimeinputs) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Selection Required',
                                    text: 'You must select a time input to remove before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Map removeTimeinputs to its descriptive label
                            let removeTimeinputsLabel =
                                removeTimeinputs === 'timeIn' ? 'Time In' :
                                removeTimeinputs === 'breakOut' ? 'Break Out' :
                                removeTimeinputs === 'breakIn' ? 'Break In' :
                                removeTimeinputs === 'timeOut' ? 'Time Out' :
                                removeTimeinputs === 'brokenSchedIn' ? 'Broken Sched In' :
                                removeTimeinputs === 'brokenSchedOut' ? 'Broken Sched Out' :
                                removeTimeinputs === 'allRegularInputs' ? 'All Regular Inputs' :
                                removeTimeinputs === 'allBrokenSchedInputs' ? 'All Broken Sched Inputs' : '';

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                removeTimeinputs: removeTimeinputsLabel, // Use the mapped label
                                concern_reason: concern_reason,
                                status: "Pending",
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Time inputs did not sync") {
                // Construct the URL with additional parameters
                const url = `time-inputs-did-not-sync.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);
                                            // Display on proposed time inputs
                                            document.getElementById('proposedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('proposedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break";
                                            document.getElementById('proposedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break";
                                            document.getElementById('proposedTimeOut').value = extractTime(data.A_timeout);

                                            // Disable the input fields if they are not empty or not "No Break"
                                            if (proposedTimeIn.value) proposedTimeIn.disabled = true;
                                            if (proposedBreakOut.value && proposedBreakOut.value !== "No Break") proposedBreakOut.disabled = true;
                                            if (proposedBreakIn.value && proposedBreakIn.value !== "No Break") proposedBreakIn.disabled = true;
                                            if (proposedTimeOut.value) proposedTimeOut.disabled = true;

                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }
                        // Fetch and set the time data based on the selected concern date
                        fetchTimeData(concernDate);

                        // Add event listener for the checkbox in the loaded content
                        document.getElementById('oneHourBreakCheckbox').addEventListener('change', function() {
                            const oneHourBreakChecked = this.checked;
                            const proposedBreakOut = document.getElementById('proposedBreakOut');
                            const proposedBreakIn = document.getElementById('proposedBreakIn');

                            // Get the captured values
                            const capturedBreakOut = document.getElementById('capturedBreakOut').value;
                            const capturedBreakIn = document.getElementById('capturedBreakIn').value;

                            // Set the value based on checkbox state
                            const breakOutValue = oneHourBreakChecked ? "No Break" : capturedBreakOut;
                            const breakInValue = oneHourBreakChecked ? "No Break" : capturedBreakIn;

                            proposedBreakOut.value = breakOutValue;
                            proposedBreakIn.value = breakInValue;
                        });

                        document.getElementById('oneHourBreakCheckbox').disabled = true;

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const M_timein = document.getElementById('capturedTimeIn').value;
                            const M_timeout = document.getElementById('capturedBreakOut').value;
                            const A_timein = document.getElementById('capturedBreakIn').value;
                            const A_timeout = document.getElementById('capturedTimeOut').value;
                            const proposedTimeIn = document.getElementById('proposedTimeIn').value; // New input
                            const proposedBreakOut = document.getElementById('proposedBreakOut').value; // New input
                            const proposedBreakIn = document.getElementById('proposedBreakIn').value; // New input
                            const proposedTimeOut = document.getElementById('proposedTimeOut').value; // New input
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const attachmentConcerns = document.getElementById('attachment1');

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if any of the proposed time inputs are empty
                            if (!proposedTimeIn || !proposedBreakOut || !proposedBreakIn || !proposedTimeOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the file input is empty (no file selected)
                            if (attachmentConcerns.files.length === 0) {
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

                            // Prepare the data to send
                            const data = new FormData();
                            data.append('empno', "<?php echo htmlspecialchars($empno); ?>");
                            data.append('name', "<?php echo htmlspecialchars($name); ?>");
                            data.append('userlevel', userlevelMapped);
                            data.append('branch', "<?php echo htmlspecialchars($branch); ?>");
                            data.append('userid', "<?php echo htmlspecialchars($userid); ?>");
                            data.append('area', "<?php echo htmlspecialchars($area_type); ?>");
                            data.append('concernDate', concernDate);
                            data.append('selectedConcern', selectedConcern);
                            data.append('concernType', concernTypeLabel);
                            data.append('actualIN', M_timein);
                            data.append('actualbOUT', M_timeout);
                            data.append('actualBIN', A_timein);
                            data.append('actualOUT', A_timeout);
                            data.append('proposedTimeIn', proposedTimeIn);
                            data.append('proposedBreakOut', proposedBreakOut);
                            data.append('proposedBreakIn', proposedBreakIn);
                            data.append('proposedTimeOut', proposedTimeOut);
                            data.append('status', "Pending");

                            // Append the file to FormData
                            const fileInput = document.getElementById('attachment1');
                            if (fileInput.files.length > 0) {
                                data.append('attachment1', fileInput.files[0]);
                            }

                            // Send the data using fetch
                            fetch('insert-concerns-with-attachment.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));
                {

                }
            } else if (selectedConcern === "Misaligned time inputs") {

                // Construct the URL with additional parameters
                const url = `misaligned-time-inputs.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);
                                            // Display data on proposedTimeIn and proposedBreakOut proposedBreakIn, proposedTimeOut
                                            document.getElementById('proposedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('proposedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('proposedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('proposedTimeOut').value = extractTime(data.A_timeout);
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }
                        // Fetch and set the time data based on the selected concern date
                        fetchTimeData(concernDate);

                        // Add event listener for the checkbox in the loaded content
                        document.getElementById('oneHourBreakCheckbox').addEventListener('change', function() {
                            const oneHourBreakChecked = this.checked;
                            const proposedBreakOut = document.getElementById('proposedBreakOut');
                            const proposedBreakIn = document.getElementById('proposedBreakIn');

                            // Get the captured values
                            const capturedBreakOut = document.getElementById('capturedBreakOut').value;
                            const capturedBreakIn = document.getElementById('capturedBreakIn').value;

                            // Set the value based on checkbox state
                            const breakOutValue = oneHourBreakChecked ? "No Break" : capturedBreakOut;
                            const breakInValue = oneHourBreakChecked ? "No Break" : capturedBreakIn;

                            proposedBreakOut.value = breakOutValue;
                            proposedBreakIn.value = breakInValue;
                        });

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const M_timein = document.getElementById('capturedTimeIn').value;
                            const M_timeout = document.getElementById('capturedBreakOut').value;
                            const A_timein = document.getElementById('capturedBreakIn').value;
                            const A_timeout = document.getElementById('capturedTimeOut').value;
                            const proposedTimeIn = document.getElementById('proposedTimeIn').value; // New input
                            const proposedBreakOut = document.getElementById('proposedBreakOut').value; // New input
                            const proposedBreakIn = document.getElementById('proposedBreakIn').value; // New input
                            const proposedTimeOut = document.getElementById('proposedTimeOut').value; // New input
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const attachmentConcerns = document.getElementById('attachment1');

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if any of the proposed time inputs are empty
                            if (!proposedTimeIn || !proposedBreakOut || !proposedBreakIn || !proposedTimeOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the file input is empty (no file selected)
                            if (attachmentConcerns.files.length === 0) {
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

                            // Prepare the data to send
                            const data = new FormData();
                            data.append('empno', "<?php echo htmlspecialchars($empno); ?>");
                            data.append('name', "<?php echo htmlspecialchars($name); ?>");
                            data.append('userlevel', userlevelMapped);
                            data.append('branch', "<?php echo htmlspecialchars($branch); ?>");
                            data.append('userid', "<?php echo htmlspecialchars($userid); ?>");
                            data.append('area', "<?php echo htmlspecialchars($area_type); ?>");
                            data.append('concernDate', concernDate);
                            data.append('selectedConcern', selectedConcern);
                            data.append('concernType', concernTypeLabel);
                            data.append('actualIN', M_timein);
                            data.append('actualbOUT', M_timeout);
                            data.append('actualBIN', A_timein);
                            data.append('actualOUT', A_timeout);
                            data.append('proposedTimeIn', proposedTimeIn);
                            data.append('proposedBreakOut', proposedBreakOut);
                            data.append('proposedBreakIn', proposedBreakIn);
                            data.append('proposedTimeOut', proposedTimeOut);
                            data.append('status', "Pending");

                            // Append the file to FormData
                            const fileInput = document.getElementById('attachment1');
                            if (fileInput.files.length > 0) {
                                data.append('attachment1', fileInput.files[0]);
                            }

                            // Send the data using fetch
                            fetch('insert-concerns-with-attachment.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));
                {

                }

            } else if (selectedConcern === "Broken Schedule did not sync") {

                // Handle loading the "Broken Schedule did not sync" form
                const url = `broken-schedule-not-sync.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            // Set the broken schedule inputs
                                            document.getElementById('capturedBrokenSchedIn').value = data.timein4 ? extractTime(data.timein4) : "";
                                            document.getElementById('capturedBrokenSchedOut').value = data.timeout4 ? extractTime(data.timeout4) : "";
                                            // Display data on proposedBrokenSchedIn and proposedBrokenSchedOut
                                            document.getElementById('proposedBrokenSchedIn').value = data.timein4 ? extractTime(data.timein4) : "";
                                            document.getElementById('proposedBrokenSchedOut').value = data.timeout4 ? extractTime(data.timeout4) : "";
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const capturedBrokenSchedIn = document.getElementById('capturedBrokenSchedIn');
                            const capturedBrokenSchedOut = document.getElementById('capturedBrokenSchedOut');
                            const proposedBrokenSchedIn = document.getElementById('proposedBrokenSchedIn').value; // New input
                            const proposedBrokenSchedOut = document.getElementById('proposedBrokenSchedOut').value; // New input
                            const timein4 = capturedBrokenSchedIn ? capturedBrokenSchedIn.value : 'No Logs';
                            const timeout4 = capturedBrokenSchedOut ? capturedBrokenSchedOut.value : 'No Logs';
                            const attachmentConcerns = document.getElementById('attachment1');
                            // Handle timein4 and timeout4 values
                            const timein4Processed = timein4 !== '' ? timein4 : 'No Logs';
                            const timeout4Processed = timeout4 !== '' ? timeout4 : 'No Logs';

                            // Check if any of the proposed time inputs are empty
                            if (!proposedBrokenSchedIn || !proposedBrokenSchedOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the file input is empty (no file selected)
                            if (attachmentConcerns.files.length === 0) {
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

                            // Prepare the data to send
                            const data = new FormData();
                            data.append('empno', "<?php echo htmlspecialchars($empno); ?>");
                            data.append('name', "<?php echo htmlspecialchars($name); ?>");
                            data.append('userlevel', userlevelMapped); // Ensure this is a string or properly formatted value
                            data.append('branch', "<?php echo htmlspecialchars($branch); ?>");
                            data.append('userid', "<?php echo htmlspecialchars($userid); ?>");
                            data.append('area', "<?php echo htmlspecialchars($area_type); ?>");
                            data.append('concernDate', concernDate);
                            data.append('selectedConcern', selectedConcern);
                            data.append('concernType', concernTypeLabel); // Ensure this is a string or properly formatted value
                            data.append('capturedBrokenSchedIn', timein4Processed);
                            data.append('actualbOUT', "No Break");
                            data.append('actualBIN', "No Break");
                            data.append('capturedBrokenSchedOut', timeout4Processed);
                            data.append('proposedBrokenSchedIn', proposedBrokenSchedIn);
                            data.append('newbIN', "No Break");
                            data.append('newbOUT', "No Break");
                            data.append('proposedBrokenSchedOut', proposedBrokenSchedOut);
                            data.append('status', "Pending");

                            // Append the file to FormData
                            const fileInput = document.getElementById('attachment1');
                            if (fileInput.files.length > 0) {
                                data.append('attachment1', fileInput.files[0]);
                            }

                            // Directly log FormData contents
                            for (let [key, value] of data.entries()) {
                                if (value instanceof File) {
                                    console.log(`${key}: [File] ${value.name}`);
                                } else {
                                    console.log(`${key}: ${value}`);
                                }
                            }

                            // Send the data using fetch
                            fetch('insert-concerns-with-attachment.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));

            } else if (selectedConcern === "Persona error" || selectedConcern === "Hardware malfunction" || selectedConcern === "Emergency time out" || selectedConcern === "Fingerprint problem") {

                // Construct the URL with additional parameters
                const url = `persona-error.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Check if the concern is "Not following break out and in interval" and update the <p> tag text
                        if (selectedConcern === "Hardware malfunction") {
                            const pTag = displayDiv.querySelector('p');
                            if (pTag) {
                                pTag.textContent = "The device used for digital persona is not properly working (defective). "; // Update the text
                            }
                        } else if (selectedConcern === "Fingerprint problem") {
                            const pTag = displayDiv.querySelector('p');
                            if (pTag) {
                                pTag.textContent = "The staff's fingerprints are not being detected by the Persona device, likely due to fading or issues with the thumbprint. "; // Update the text
                            }
                        } else if (selectedConcern === "Emergency time out") {
                            const pTag = displayDiv.querySelector('p');
                            if (pTag) {
                                pTag.textContent = "The employee has experienced an emergency and cannot continue using the Persona device."; // Update the text
                            }
                        }

                        document.querySelectorAll('.time-inputs input').forEach(input => {
                            input.addEventListener('input', function() {
                                let value = this.value;
                                // Remove all non-numeric characters except colon
                                value = value.replace(/[^0-9:]/g, '');

                                // Format the input to match "00:00"
                                if (value.length > 2 && value[2] !== ':') {
                                    value = value.slice(0, 2) + ':' + value.slice(2);
                                }

                                // Limit length to "00:00"
                                value = value.slice(0, 5);

                                // Validate the time format
                                if (value.length === 5) {
                                    const [hours, minutes] = value.split(':').map(num => parseInt(num, 10));
                                    if (hours >= 24 || minutes >= 60) {
                                        // Invalid time; reset the value
                                        this.value = '';
                                        return;
                                    }

                                    if (hours > 23 || (hours === 23 && minutes > 59)) {
                                        // Adjust hours and minutes to be within valid range
                                        if (hours > 23) {
                                            this.value = '23:' + value.slice(3);
                                        } else if (minutes > 59) {
                                            this.value = value.slice(0, 3) + '59';
                                        }
                                        return;
                                    }
                                }

                                // Set the cleaned and validated value
                                this.value = value;
                            });
                        });

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            document.getElementById('capturedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('capturedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('capturedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('capturedTimeOut').value = extractTime(data.A_timeout);
                                            // Display data on proposedTimeIn and proposedBreakOut proposedBreakIn, proposedTimeOut
                                            document.getElementById('proposedTimeIn').value = extractTime(data.M_timein);
                                            document.getElementById('proposedBreakOut').value = data.M_timeout !== "No Break" ? extractTime(data.M_timeout) : "No Break"; // Using M_timeout for BreakOut
                                            document.getElementById('proposedBreakIn').value = data.A_timein !== "No Break" ? extractTime(data.A_timein) : "No Break"; // Using A_timein for BreakIn
                                            document.getElementById('proposedTimeOut').value = extractTime(data.A_timeout);
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }
                        // Fetch and set the time data based on the selected concern date
                        fetchTimeData(concernDate);

                        // Add event listener for the checkbox in the loaded content
                        document.getElementById('oneHourBreakCheckbox').addEventListener('change', function() {
                            const oneHourBreakChecked = this.checked;
                            const proposedBreakOut = document.getElementById('proposedBreakOut');
                            const proposedBreakIn = document.getElementById('proposedBreakIn');

                            // Get the captured values
                            const capturedBreakOut = document.getElementById('capturedBreakOut').value;
                            const capturedBreakIn = document.getElementById('capturedBreakIn').value;

                            // Set the value based on checkbox state
                            const breakOutValue = oneHourBreakChecked ? "No Break" : capturedBreakOut;
                            const breakInValue = oneHourBreakChecked ? "No Break" : capturedBreakIn;

                            proposedBreakOut.value = breakOutValue;
                            proposedBreakIn.value = breakInValue;
                        });

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const M_timein = document.getElementById('capturedTimeIn').value;
                            const M_timeout = document.getElementById('capturedBreakOut').value;
                            const A_timein = document.getElementById('capturedBreakIn').value;
                            const A_timeout = document.getElementById('capturedTimeOut').value;
                            const proposedTimeIn = document.getElementById('proposedTimeIn').value; // New input
                            const proposedBreakOut = document.getElementById('proposedBreakOut').value; // New input
                            const proposedBreakIn = document.getElementById('proposedBreakIn').value; // New input
                            const proposedTimeOut = document.getElementById('proposedTimeOut').value; // New input
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const attachmentConcerns = document.getElementById('attachment1');

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if any of the proposed time inputs are empty
                            if (!proposedTimeIn || !proposedBreakOut || !proposedBreakIn || !proposedTimeOut) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Missing Inputs',
                                    text: 'Please enter all proposed time inputs before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if the file input is empty (no file selected)
                            if (attachmentConcerns.files.length === 0) {
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

                            // Prepare the data to send
                            const data = new FormData();
                            data.append('empno', "<?php echo htmlspecialchars($empno); ?>");
                            data.append('name', "<?php echo htmlspecialchars($name); ?>");
                            data.append('userlevel', userlevelMapped);
                            data.append('branch', "<?php echo htmlspecialchars($branch); ?>");
                            data.append('userid', "<?php echo htmlspecialchars($userid); ?>");
                            data.append('area', "<?php echo htmlspecialchars($area_type); ?>");
                            data.append('concernDate', concernDate);
                            data.append('selectedConcern', selectedConcern);
                            data.append('concernType', concernTypeLabel);
                            data.append('actualIN', M_timein);
                            data.append('actualbOUT', M_timeout);
                            data.append('actualBIN', A_timein);
                            data.append('actualOUT', A_timeout);
                            data.append('proposedTimeIn', proposedTimeIn);
                            data.append('proposedBreakOut', proposedBreakOut);
                            data.append('proposedBreakIn', proposedBreakIn);
                            data.append('proposedTimeOut', proposedTimeOut);
                            data.append('status', "Pending");

                            // Append the file to FormData
                            const fileInput = document.getElementById('attachment1');
                            if (fileInput.files.length > 0) {
                                data.append('attachment1', fileInput.files[0]);
                            }

                            // Send the data using fetch
                            fetch('insert-concerns-with-attachment.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });

                    })
                    .catch(error => console.error('Error fetching content:', error));
                {

                }
            } else if (selectedConcern === "File broken sched overtime") {

                // Handle loading the "file-broken-sched-overtime" form
                const url = `file-broken-sched-overtime.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                        if (data) {
                                            // Set the broken schedule inputs
                                            document.getElementById('capturedBrokenSchedIn').value = data.timein4 ? extractTime(data.timein4) : "";
                                            document.getElementById('capturedBrokenSchedOut').value = data.timeout4 ? extractTime(data.timeout4) : "";

                                            function calculateHours(timein, timeout) {
                                                // Convert the time strings into Date objects
                                                const timeInDate = new Date(timein);
                                                const timeOutDate = new Date(timeout);
                                                // Calculate the difference in milliseconds
                                                const differenceInMs = timeOutDate - timeInDate;
                                                // Convert the difference from milliseconds to hours
                                                const hours = differenceInMs / (1000 * 60 * 60);
                                                // Return the total hours, rounded down to the nearest whole number
                                                return Math.floor(hours);
                                            }

                                            // Calculate and display the total hours
                                            const totalOThours = data.timein4 && data.timeout4 ? calculateHours(data.timein4, data.timeout4) : "";
                                            document.getElementById('current_othours').textContent = totalOThours ? `${totalOThours}` : '0';

                                            // Function to handle validation on the 'othours' input field
                                            function validateOThoursInput() {
                                                const othoursInput = document.getElementById('othours');
                                                const maxOThours = parseInt(document.getElementById('current_othours').textContent, 10);

                                                othoursInput.addEventListener('input', function() {
                                                    let inputValue = parseInt(othoursInput.value, 10);
                                                    // Check if the input value is negative
                                                    if (inputValue < 0) {
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'Invalid Input',
                                                            text: 'Negative values are not allowed.',
                                                            confirmButtonText: 'OK',
                                                            customClass: {
                                                                confirmButton: 'swal-button-green'
                                                            },
                                                        });
                                                        // Reset the input value to 1
                                                        othoursInput.value = 1;
                                                        inputValue = 1; // Update the value after resetting
                                                    }
                                                    // Check if the input value exceeds the maximum allowed OT hours
                                                    if (inputValue > maxOThours) {
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'Overtime Hours Exceeded',
                                                            text: `You cannot file more than ${maxOThours} OT hours.`,
                                                            confirmButtonText: 'OK',
                                                            customClass: {
                                                                confirmButton: 'swal-button-green'
                                                            },
                                                        }).then(() => {
                                                            // Reset the input value to maxOThours after the alert is dismissed
                                                            othoursInput.value = maxOThours;
                                                            inputValue = maxOThours; // Update the input value
                                                        });
                                                    }
                                                });
                                            }

                                            // Display the totalOThours value in the input field with id='othours'
                                            document.getElementById('othours').value = totalOThours ? `${totalOThours}` : '0';

                                            // Call this function after setting the OT hours value
                                            validateOThoursInput();

                                            // Check if either capturedBrokenSchedIn or capturedBrokenSchedOut is empty or null
                                            if (!document.getElementById('capturedBrokenSchedIn').value || !document.getElementById('capturedBrokenSchedOut').value) {
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'No time inputs!',
                                                    text: 'You cannot file overtime if you don\'t have broken schedule time in or out.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK',
                                                    customClass: {
                                                        confirmButton: 'swal-button-green'
                                                    },
                                                }).then(function() {
                                                    // Redirect to the specific page with dynamic parameters
                                                    const empno = "<?php echo $empno; ?>";
                                                    const mindate = "<?php echo $mindate; ?>";
                                                    const maxdate = "<?php echo $maxdate; ?>";
                                                    window.location.href = `filing-concerns.php?concern=concern&dtrconcern&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                });
                                                return; // Prevent further processing
                                            }
                                        }
                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const othours = document.getElementById('othours').value;
                            const concern_reason = document.getElementById('concern_reason').value;

                            // Get the employee number and user level from PHP
                            const empno = "<?php echo htmlspecialchars($empno); ?>";
                            const userlevel = "<?php echo htmlspecialchars($userlevel); ?>";

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Prepare the data to send
                            const data = new FormData();
                            data.append('empno', empno);
                            data.append('selectedConcern', selectedConcern);
                            data.append('concernDate', concernDate);
                            data.append('ottype', "1");
                            data.append('othours', othours);
                            data.append('concern_reason', concern_reason);

                            // Change the status based on the employee number or user level
                            if (empno === '2525' || userlevel === 'mod') {
                                data.append('status', 'pending2');
                            } else {
                                data.append('status', 'pending');
                            }

                            // Directly log FormData contents
                            for (let [key, value] of data.entries()) {
                                if (value instanceof File) {
                                    console.log(`${key}: [File] ${value.name}`);
                                } else {
                                    console.log(`${key}: ${value}`);
                                }
                            }

                            // Send the data using fetch
                            fetch('insert-concerns-with-attachment.php', {
                                    method: 'POST',
                                    body: data
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Broken sched overtime successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_ot.php?ot=ot&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });



                    })
                    .catch(error => console.error('Error fetching content:', error));
            } else if (selectedConcern === "Wrong computation") {

                // Handle loading the "Wrong filing of overtime" form
                const url = `wrong-computation.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${encodeURIComponent(type_concern)}&type_errors=${encodeURIComponent(type_errors)}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        displayDiv.innerHTML = html;

                        // Fetch and display time data based on concernDate
                        function fetchTimeData(concernDate) {
                            const empno = "<?php echo htmlspecialchars($empno); ?>";

                            if (concernDate) {
                                fetch('fetch-time-inputs.php?empno=' + encodeURIComponent(empno) + '&date=' + encodeURIComponent(concernDate))
                                    .then(response => response.json())
                                    .then(data => {
                                        console.log('Fetched data:', data); // Log the entire fetched data object

                                    })
                                    .catch(error => console.error('Error fetching time inputs:', error));
                            }
                        }

                        // Call the fetchTimeData function immediately after defining it
                        fetchTimeData(concernDate);

                        document.getElementById('btnSubmit').addEventListener('click', function() {
                            // Gather data from form fields
                            const concernDate = document.getElementById('concernDate').value;
                            const selectedConcern = document.getElementById('specificConcern').value;
                            const concernType = document.getElementById('concernType').value; // e.g., userError
                            const agreementCheckbox = document.getElementById('agreementCheckbox').checked;
                            const concern_reason = document.getElementById('concern_reason').value;
                            const wrongComputation = document.getElementById('wrongComputation').value;

                            // Check if the agreement checkbox is not checked
                            if (!agreementCheckbox) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Agreement Required',
                                    text: 'You must agree to the terms before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if reason is empty or null
                            if (!concern_reason || concern_reason.trim() === "") {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Reason Required',
                                    text: 'You must provide a reason for the concern before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Check if removeTimeinputs is not selected
                            if (!wrongComputation) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Selection Required',
                                    text: 'You must select a wrong computation before submitting.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    },
                                });
                                return; // Prevent form submission
                            }

                            // Map wrongComputation to its descriptive label
                            let wrongComputationLabel =
                                wrongComputation === 'working_days' ? 'Number of Working Days' :
                                wrongComputation === 'overtime' ? 'Overtime Hours' :
                                wrongComputation === 'undertime' ? 'Undertime Hours' :
                                wrongComputation === 'regular_holiday' ? 'Regular Public Holiday' :
                                wrongComputation === 'special_holiday' ? 'Special Public Holiday' :
                                wrongComputation === 'night_differential' ? 'Night Differential Pay' :
                                wrongComputation === 'leave' ? 'Leave Taken' :
                                wrongComputation === 'late' ? 'Late Arrival' :
                                wrongComputation === 'working_day_off' ? 'Working Day Off' :
                                '';

                            const data = {
                                empno: "<?php echo htmlspecialchars($empno); ?>",
                                name: "<?php echo htmlspecialchars($name); ?>",
                                userlevel: userlevelMapped, // Use the mapped userlevel
                                branch: "<?php echo htmlspecialchars($branch); ?>",
                                userid: "<?php echo htmlspecialchars($userid); ?>",
                                area: "<?php echo htmlspecialchars($area_type); ?>",
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                concernDate: concernDate,
                                selectedConcern: selectedConcern,
                                concernType: concernTypeLabel, // Use the descriptive label
                                wrongComputation: wrongComputationLabel, // Use the mapped label
                                concern_reason: concern_reason,
                                status: "Pending",
                            };

                            // Log the data to the console
                            console.log('Submitting data:', data);

                            fetch('insert-concerns.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify(data)
                                })
                                .then(response => response.json())
                                .then(result => {
                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: 'Concern successfully submitted!',
                                            timer: 2000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                const empno = "<?php echo $empno; ?>";
                                                const mindate = "<?php echo $mindate; ?>";
                                                const maxdate = "<?php echo $maxdate; ?>";
                                                const redirectUrl = `/hrms/pdf/print_concerns.php?dtr=filedconcerns&filed=&empno=${empno}&cutfrom=${mindate}&cutto=${maxdate}`;
                                                window.location.href = redirectUrl;
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: result.message || 'There was an issue submitting the concern.',
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                        });
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    })
                    .catch(error => console.error('Error fetching content:', error));

            }

        });
    </script>

</body>

</html>