<?php
// header('Content-Type: application/json');
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
//entry.php
session_start();
if (!isset($_SESSION['user_validate'])) {
    header("Location:index.php?&m=2");
}

// Ensure $empno is defined from the URL
if (isset($_GET['empno'])) {
    $empno = $_GET['empno'];
} else {
    die("Error: Employee number is not provided in the URL.");
}

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

// Embed PHP variables into JavaScript
echo "<script>
    const M_timein = '$M_timein';
    const M_breakout = '$M_breakout';
    const A_breakin = '$A_breakin';
    const A_timeout = '$A_timeout';
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label for="employeeName" class="mb-0">
                                    <h6><strong>Employee Name </strong><span style="color: red;">*</span></h6>
                                </label>
                                <input type="text" class="form-control custom-select-width w-50 ml-2" id="employeeName" value="<?php echo htmlspecialchars($name); ?>" required disabled>
                            </div>
                            <!-- Concern Date with Date-Time Picker -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
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
                            <div class="d-flex justify-content-between align-items-center mb-3">
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
        // Define concerns data
        const concerns = {
            userError: [
                "Forgot/Wrong to time in or time out",
                "Forgot/Wrong to break in or break out",
                "Forgot to click broken schedule",
                "Wrong filing of overtime",
                "Wrong filing of leave",
                "Wrong filing of OBP",
                "Wrong time in or time out of broken schedule",
                "Not following break out and in interval",
                "Remove Time inputs"
            ],
            systemError: [
                "Time inputs did not sync",
                "Misaligned time inputs",
                "Persona error",
                "Hardware malfunction"
            ],
            others: [
                "Emergency time out",
                "Fingerprint Problem",
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
                        }
                    })
                    .catch(error => console.error('Error fetching time inputs:', error));
            }
        }

        // Handle displaying selected concern and fetching time data
        document.getElementById('btnProceed').addEventListener('click', function() {
            const selectedConcern = document.getElementById('specificConcern').value;
            const concernDate = document.getElementById('concernDate').value;
            const displayDiv = document.getElementById('displayConcernSelected');
            const empno = "<?php echo htmlspecialchars($empno); ?>"; // Adjust according to your server-side variables
            const name = "<?php echo htmlspecialchars($name); ?>";
            const position = "<?php echo htmlspecialchars($position); ?>";
            const type_concern = 1; // type_concern = 1 is for Forgot/Wrong to time in or time out

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

            if (selectedConcern === "Forgot/Wrong to time in or time out") {
                // Construct the URL with additional parameters
                const url = `forgot-wrong-time-in-out.php?empno=${encodeURIComponent(empno)}&concernDate=${encodeURIComponent(concernDate)}&name=${encodeURIComponent(name)}&position=${encodeURIComponent(position)}&Concern=${encodeURIComponent(selectedConcern)}&type_concern=${type_concern}`;

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
                                this.value = value.slice(0, 5); // Limit length to "00:00"
                            });
                        });

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

                            // Map concernType to its descriptive label
                            let concernTypeLabel =
                                concernType === 'userError' ? 'User Error' :
                                concernType === 'systemError' ? 'System Error' :
                                concernType === 'others' ? 'Others' : '';

                            // Map userlevel to its descriptive label
                            let userlevelMapped;
                            const userlevel = "<?php echo htmlspecialchars($userlevel); ?>";

                            if (userlevel === 'master') {
                                userlevelMapped = 'staff';
                            } else {
                                userlevelMapped = userlevel;
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
                                            timer: 2000, // 3 seconds timer
                                            timerProgressBar: true,
                                            showConfirmButton: false, // Remove the OK button
                                            customClass: {
                                                confirmButton: 'swal-button-green'
                                            },
                                            willClose: () => {
                                                // Redirect after the timer ends
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
                                            text: 'There was an issue submitting the concern.',
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