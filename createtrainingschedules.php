<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$sql = "SELECT userid, mothercafe, branch, userlevel, name, empno FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$userlevel = $row['userlevel'];
$empno = $row['empno'];

$sql1 = "SELECT areatype, username FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
$query1 = $ORconnect->query($sql1);
$row1 = $query1->fetch_array();
$areatype = $row1['areatype'];

$user = $row['name'];
$userlevel = $row['userlevel'];
$mothercafe = $row['mothercafe'];

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
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- Add this line in the head section of your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<style>
    .my-actions-class .swal2-actions {
        display: flex;
        justify-content: space-between;
    }

    .my-actions-class .swal2-cancel {
        margin-right: 10px;
        /* Adjust as needed */
        justify-self: flex-start;
        /* Add this line */
    }

    .swal-button {
        background-color: #007BFF !important;
        /* Green */
        color: white;
    }

    #batchNumber {
        width: 300px;
    }

    #course {
        width: 300px !important;
    }

    .selected-course {
        background-color: #36B9CC;
        /* Light green background color */
        padding: 5px 10px;
        /* Adjust padding as needed */
        border-radius: 0.25rem;
        /* Rounded corners */
        display: inline-block;
        /* Display as inline block to maintain spacing */
        margin-right: 10px;
        /* Adjust margin as needed */
        color: white;
        font-weight: bold;
    }

    .close-button {
        color: red;
        /* Change color to red or any desired color */
        cursor: pointer;
        /* Make it appear as a pointer when hovered over */
        margin-left: 5px;
        /* Add margin between the button and text */
        font-weight: bold;
    }

    .btn-primary {
        font-weight: bold;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .datetimepicker input[type="text"] {
        background-color: white;
    }

    /* New media query for screen widths 400px or less */
    @media screen and (max-width: 500px) {

        #LabelTrainingSchedule,
        #addTrainingBatch,
        #slash,
        #addTrainingDay {
            font-size: 1.2rem !important;
            /* Adjust the font size as needed */
        }

        .card-header {
            display: block !important;
            /* Remove flexbox display */
            text-align: center !important;
            /* Center the content */
        }

        .card-header h6 {
            margin-bottom: 1rem;
            /* Add some spacing between the heading and the button */
        }

        #createScheduleBtn {
            margin-top: 1rem;
            display: block;
            width: 100%;
            text-align: center;
        }
    }
</style>

<body id="page-top" class="sidebar-toggled">
    <?php include("navigation.php"); ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="mb-3">
                <div class="d-flex">
                    <a href="training_schedules.php">
                        <h4 class="mb-0 mr-3" id="LabelTrainingSchedule" style="font-weight: bold;">Training Schedule</h4>
                    </a>
                    <h4 class="mr-3" id="slash">/</h4>
                    <h4 class="mb-0 mr-3" id="addTrainingBatch">New Batch Schedule</h4>
                </div>
            </div>
        </div>
        <form id="trainingSchedulesForm" class="bg-white rounded-sm shadow">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Employee Schedules & Details</h6>
                <!-- Button with Font Awesome icon -->
                <button id="createScheduleBtn" class="btn btn-primary pt-2 text-center">
                    <h6 class="mb-1 font-weight-bold">
                        <i class="mr-1 fas fa-users"></i> Create Batch Schedule
                    </h6>
                </button>
            </div>
            <div class="form-group p-3">
                <div class="d-flex justify-content-between">
                    <label class="mb-1" for="">Select Date Schedules:</label>
                </div>
                <!-- Date Picker Start Date and End Date  -->
                <div class="d-flex">
                    <div class="datetimepicker">
                        <input type="text" class="form-control" id="dateStartSched" placeholder="Select Start Date" required>
                    </div>
                    <label for="" class="ml-2 mr-2 mt-2">To:</label>
                    <div class="datetimepicker">
                        <input type="text" class="form-control" id="dateEndSched" placeholder="Select End Date" required>
                    </div>
                </div>
                <!-- Batch Number -->
                <div class="mt-1">
                    <label class="mb-1" for="">Input Batch Number:</label>
                    <input id="batchNumber" type="number" class="form-control" min="1" max="5" placeholder="0000" required>
                </div>
                <!-- Drop Down Selection Course  -->
                <div class="mt-2" id="listOfCourse">
                    <label class="mb-1" for="">Select Course:</label>
                    <select class="form-control p-2 mb-3 w-auto text-small" name="course" id="course">
                        <option value="" disabled selected>Select at least one course</option>
                        <!-- // Fetch data from the database -->
                        <?php
                        $sql = "SELECT
                                course_id,
                                MAX(course_name) AS course_name,
                                MAX(userid) AS userid,
                                MAX(empno) AS empno,
                                MAX(employee_name) AS employee_name,
                                MAX(branch) AS branch,
                                MAX(department) AS department
                                FROM (
                                SELECT
                                    tc.id AS course_id,
                                    tc.name AS course_name,
                                    ed.userid,
                                    ui.empno,
                                    ui.name AS employee_name,
                                    ui.branch,
                                    ui.department
                                FROM
                                    lnd_training_courses tc
                                INNER JOIN
                                    lnd_enrolled_dept ed ON tc.id = ed.course_id
                                INNER JOIN
                                    user_info ui ON ed.userid = ui.userid
                                WHERE
                                    ui.status IN ('active', '')
                                ) AS subquery
                                GROUP BY course_id
                                ORDER BY course_id;";
                        $result = mysqli_query($HRconnect, $sql);

                        // Populate dropdown options
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['course_id'] . "'>" . $row['course_name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No courses available</option>";
                        }
                        ?>
                    </select>
                    <div id="selectedCourses" class="d-flex">
                        <!-- Display the selected courses -->
                    </div>
                </div>
            </div>
        </form>

        <!-- Datatable -->
        <div class="card shadow mt-2">
            <div class="table-responsive">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <!-- <h6 class="m-0 font-weight-bold text-primary">Employee List</h6> -->
                    <h6 class="m-0 font-weight-bold text-primary">Total Selected: <span id="selectedCounts">0</span></h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover text-uppercase" id="newTrainingSched" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <!-- Remove the ID from the "Select All" checkbox in the DataTable -->
                                <th class="text-center"><input type="checkbox" class="selectAll"></th>
                                <th class="text-center">EMPLOYEE ID</th>
                                <th class="text-center">EMPLOYEE NAME</th>
                                <th class="text-center">DEPARTMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table body will be populated dynamically using JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- End of Main Content -->
    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright © Mary Grace Foods Inc. 2019.</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>
    <!-- Calendar Restriction-->
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2020.3.1118/styles/kendo.default-v2.min.css" />
    <script src="https://kendo.cdn.telerik.com/2020.3.1118/js/kendo.all.min.js"></script>

</body>

<script>
    // Get the input element
    var input = document.getElementById('batchNumber');

    // Add event listener for input
    input.addEventListener('input', function(event) {
        // Get the current value of the input
        var value = this.value;

        // Check if the value is longer than 5 characters
        if (value.length > 10) {
            // If longer than 5 characters, truncate the value to only the first 5 characters
            this.value = value.slice(0, 10);
        }
    });

    // DropDown Get the div to display selected courses 
    var selectedCoursesDiv = document.getElementById('selectedCourses');

    // Keep track of selected options
    var selectedOptions = [];

    // Function to display selected courses
    function displaySelectedCourse(selectedOption) {
        // Check if the selected option is not empty
        if (selectedOption.trim() !== "") {
            // Remove previously selected courses
            selectedCoursesDiv.innerHTML = '';

            // Create a new paragraph element to display the selected course
            var courseParagraph = document.createElement('p');
            courseParagraph.textContent = selectedOption;
            courseParagraph.classList.add('selected-course'); // Add the 'selected-course' class

            // Create a span element for the close button
            var closeButton = document.createElement('span');
            closeButton.textContent = 'x';
            closeButton.classList.add('close-button');
            closeButton.style.marginLeft = '5px'; // Add margin to create space between the button and text

            closeButton.addEventListener('click', function() {
                // Remove the corresponding paragraph when the close button is clicked
                courseParagraph.remove();
                // Remove the option from selectedOptions array
                var index = selectedOptions.indexOf(selectedOption);
                if (index !== -1) {
                    selectedOptions.splice(index, 1);
                }

                // Remove the corresponding data from the DataTable
                var table = $('#newTrainingSched').DataTable();
                var rowData = table.rows().data().toArray();
                var newData = rowData.filter(function(row) {
                    return row.course_name !== selectedOption; // Assuming 'course_name' is the key for course names
                });

                // Destroy the existing DataTable instance
                table.destroy();

                // Create a new DataTable with updated data
                table = $('#newTrainingSched').DataTable({
                    data: newData,
                    "columnDefs": [{
                        "width": "auto",
                        "targets": "_all"
                    }],
                    "autoWidth": true,
                    columns: [{
                            // Add dynamic checkbox in the first column
                            data: null,
                            title: "<input type='checkbox' class='selectAll'>",
                            className: "text-center",
                            render: function(data, type, row, meta) {
                                // Return checkbox with a unique class for each row
                                return "<input type='checkbox' class='employee-checkbox'>";
                            }
                        },
                        {
                            data: "empno",
                            title: "EMPLOYEE ID",
                            className: "text-center"
                        },
                        {
                            data: "employee_name",
                            title: "EMPLOYEE NAME",
                            className: "text-center"
                        },
                        {
                            data: "branch",
                            title: "DEPARTMENT",
                            className: "text-center"
                        }
                    ]
                });
            });

            // Append the close button to the paragraph element
            courseParagraph.appendChild(closeButton);

            // Append the paragraph element to the selected courses div
            selectedCoursesDiv.appendChild(courseParagraph);

            // Add the selected option to selectedOptions array
            selectedOptions = [selectedOption];

            // Check if at least one course is selected
            if (selectedOptions.length === 1) {
                // Enable submit button or perform any desired action
            }
        } else {
            // Alert user that at least one course must be selected
            alert("Please select at least one course.");
        }
    }

    // Get the select element
    var selectElement = document.getElementById('course');
    // Initialize selected course ID as an array
    var selectedCourseId = [];

    // Add event listener for change event
    selectElement.addEventListener('change', function() {
        // Clear the selected course IDs array
        selectedCourseId = [];

        // Get the selected option
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var courseId = selectedOption.value; // Get the value of the selected option, which is the course ID
        var courseName = selectedOption.textContent; // Get the text content of the selected option, which is the course name

        // Add the course ID to the array
        selectedCourseId.push(courseId);

        // Log the selected course IDs
        // console.log("Selected course IDs: " + selectedCourseId.join(', '));

        // Display the selected course name
        displaySelectedCourse(courseName);

        // Call the function to display employee data for the selected course
        displayEmployeeData(selectedCourseId);
    });

    // Function to fetch and display employee data for the selected course
    function displayEmployeeData(selectedCourseId) {
        // Send an AJAX request to fetch the employee data for the selected course
        $.ajax({
            url: 'fetch_employee_data.php',
            method: 'POST',
            data: {
                courseId: selectedCourseId
            },
            dataType: 'json',

            success: function(response) {
                // console.log(response);
                // Remove duplicate employee IDs
                var uniqueEmployeeData = removeDuplicateEmployees(response);
                // console.log("Clicked Courses");

                // Destroy the existing DataTable instance
                $('#newTrainingSched').DataTable().destroy();

                // Create a new DataTable with updated data
                $('#newTrainingSched').DataTable({
                    data: uniqueEmployeeData,
                    "columnDefs": [{
                        "width": "auto",
                        "targets": "_all"
                    }],
                    "autoWidth": true,
                    columns: [{
                            // Add dynamic checkbox in the first column
                            data: null,
                            title: "<input type='checkbox' class='selectAll'>",
                            className: "text-center",
                            render: function(data, type, row, meta) {
                                // Return checkbox with a unique class for each row
                                return "<input type='checkbox' class='employee-checkbox'>";
                            }
                        },
                        {
                            data: "empno",
                            title: "EMPLOYEE ID",
                            className: "text-center"
                        },
                        {
                            data: "employee_name",
                            title: "EMPLOYEE NAME",
                            className: "text-center"
                        },
                        {
                            data: "branch",
                            title: "DEPARTMENT",
                            className: "text-center"
                        }
                    ]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching employee data:', error);
            }
        });
    }

    $(document).ready(function() {
        var selectedEmployees = []; // Array to store selected employee IDs

        // Function to update the count of selected checkboxes
        function updateSelectedCount() {
            $('#selectedCounts').text(selectedEmployees.length);
        }

        // Add event listener for DataTable row click
        $('#newTrainingSched tbody').on('click', 'tr', function(event) {
            // Prevent the row click event from bubbling up to the checkbox
            if (event.target.type !== 'checkbox') {
                // Toggle selected class on row click
                $(this).toggleClass('selected');

                // Toggle checkbox state
                var checkbox = $(this).find('.employee-checkbox');
                checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
            }
        });

        // Add event listener for checkbox change
        $('#newTrainingSched tbody').on('change', '.employee-checkbox', function() {
            // Get the data of the clicked row
            var row = $(this).closest('tr');
            var rowData = $('#newTrainingSched').DataTable().row(row).data();
            if (rowData) {
                var empno = rowData.empno;
                // console.log("Selected Empno: " + empno); // Check if empno is retrieved correctly

                // Update selectedEmployees array based on checkbox state
                if ($(this).prop('checked')) {
                    if (!selectedEmployees.includes(empno)) {
                        selectedEmployees.push(empno);
                    }
                } else {
                    var index = selectedEmployees.indexOf(empno);
                    if (index !== -1) {
                        selectedEmployees.splice(index, 1);
                    }
                }
                // console.log("Store on Array Empno: " + selectedEmployees);
                // Update the selected count
                updateSelectedCount();
            } else {
                // console.log("No data found for the clicked row");
            }
        });

        $(document).on('change', '.selectAll', function() {
            var isChecked = $(this).prop('checked');
            var table = $('#newTrainingSched').DataTable();

            if (isChecked) {
                selectedEmployees = [];
                // Use the data() method to get data for all rows
                var allData = table.rows().data();
                $.each(allData, function(index, value) {
                    selectedEmployees.push(value.empno);
                });

                // Select checkboxes on all pages
                for (var i = 0; i < table.page.info().pages; i++) {
                    table.page(i).draw(false); // Move to page i
                    $('.employee-checkbox').prop('checked', true); // Select checkboxes on the current page
                }
                table.page(0).draw(false); // Move back to the first page
            } else {
                // Clear the selectedEmployees array if not all checkboxes are checked
                selectedEmployees = [];
                // Uncheck checkboxes on all pages
                for (var i = 0; i < table.page.info().pages; i++) {
                    table.page(i).draw(false); // Move to page i
                    $('.employee-checkbox').prop('checked', false); // Uncheck checkboxes on the current page
                }
                table.page(0).draw(false); // Move back to the first page
            }

            // console.log("Select all empno " + selectedEmployees);

            // Update the selected count
            updateSelectedCount();
        });

        // Add event listener for the "Create Schedule" button
        $('#createScheduleBtn').on('click', function(event) {
            event.preventDefault();
            // Retrieve the value from the batchNumber input field
            var batchNumber = $('#batchNumber').val().trim(); // Trim whitespace

            // Check if batchNumber is empty or blank
            if (batchNumber === '') {
                // Display a SweetAlert modal indicating that the batchNumber is required
                Swal.fire({
                    icon: 'error',
                    title: 'Batch Number Required',
                    html: 'Please input a valid batch number.',
                    customClass: {
                        confirmButton: 'swal-button' // Apply custom class to the "OK" button
                    }
                });
                return; // Stop further execution of the function
            }

            // Check if selectedEmployees is empty or null
            if (!selectedEmployees || selectedEmployees.length === 0) {
                // Display a SweetAlert modal indicating that at least one employee must be selected
                Swal.fire({
                    icon: 'error',
                    title: 'Employee Required',
                    html: 'No employees selected. Please select at least one employee.',
                    customClass: {
                        confirmButton: 'swal-button' // Apply custom class to the "OK" button
                    }
                });
                return; // Stop further execution of the function
            }

            // Retrieve the values from the date input fields
            var dateStart = $('#dateStartSched').val();
            var dateEnd = $('#dateEndSched').val();

            // Check if dateStart or dateEnd is empty
            if (!dateStart || !dateEnd) {
                // Display a SweetAlert modal indicating that both dates are required
                Swal.fire({
                    icon: 'error',
                    title: 'Date Required',
                    html: 'Both start date and end date must be selected.',
                    customClass: {
                        confirmButton: 'swal-button' // Apply custom class to the "OK" button
                    }
                });
                return; // Stop further execution of the function
            }

            // Encode the array of selected course IDs into JSON format
            var courseIdArray = selectedCourseId;

            // Send an AJAX request to insert the data into the database
            $.ajax({
                url: 'insert_schedule.php', // PHP script to handle the insertion
                method: 'POST',
                data: {
                    dateStart: dateStart,
                    dateEnd: dateEnd,
                    batchNumber: batchNumber,
                    courseId: selectedCourseId[selectedCourseId.length - 1], // Send only the last selected course ID
                    // courseId: courseIdArray, // Send courseId as JSON string
                    selectedEmployees: selectedEmployees // Send selectedEmployees array
                },
                success: function(response) {

                    // Handle the success response, such as displaying a success message or redirecting the user
                    // console.log('Schedule created successfully!', response);
                    // Parse the JSON response
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        // If the response indicates success, display a success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect the user to the training_schedules.php page after the Swal.fire message is closed
                            window.location.href = 'training_schedules.php';
                        });

                    } else {
                        // If the response indicates an error, display an error message using Swal.fire
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error response
                    console.error('Error creating schedule:', error);
                    // You can display an error message to the user if needed
                }
            });
        });

        // Function to prevent key events that would clear the input
        function preventClear(event) {
            if (event.key === 'Backspace' || event.key === 'Delete' || event.key === 'Escape') {
                event.preventDefault();
            }
        }

        // Add event listeners to prevent clearing the inputs
        document.getElementById('dateStartSched').addEventListener('keydown', preventClear);
        document.getElementById('dateEndSched').addEventListener('keydown', preventClear);

        // Initialize the start date picker
        flatpickr("#dateStartSched", {
            enableTime: false, // Disable time selection
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            // minDate: "today",
            defaultDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                // Update the minDate for the end date picker
                dateEndPicker.set('minDate', dateStr);
                // Automatically set the end date to the start date
                dateEndPicker.setDate(dateStr, true);
            }
        });

        // Initialize the end date picker
        var dateEndPicker = flatpickr("#dateEndSched", {
            enableTime: false, // Disable time selection
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            minDate: "today",
            defaultDate: "today"
        });

    });

    // Function to remove duplicate employees based on employee ID
    function removeDuplicateEmployees(employeeData) {
        var uniqueEmployees = [];
        var uniqueEmployeeIds = [];

        // Loop through the employee data
        employeeData.forEach(function(employee) {
            // Check if the employee ID is not already in the uniqueEmployeeIds array
            if (!uniqueEmployeeIds.includes(employee.empno)) {
                // Add the employee to the uniqueEmployees array
                uniqueEmployees.push(employee);
                // Add the employee ID to the uniqueEmployeeIds array
                uniqueEmployeeIds.push(employee.empno);
            }
        });

        return uniqueEmployees;
    }

    // Initialize the DataTable
    $('#newTrainingSched').dataTable({
        stateSave: true
    });













    // BUTTON NOT ALLOWED SAME BATCH NUMBER

    // // Add event listener for the "Create Schedule" button
    // $('#createScheduleBtn').on('click', function(event) {
    //             event.preventDefault();
    //             // Retrieve the value from the batchNumber input field
    //             var batchNumber = $('#batchNumber').val().trim(); // Trim whitespace

    //             // Check if batchNumber is empty or blank
    //             if (batchNumber === '') {
    //                 // Display a SweetAlert modal indicating that the batchNumber is required
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Batch Number Required',
    //                     html: 'Please input a valid batch number.',
    //                     customClass: {
    //                         confirmButton: 'swal-button' // Apply custom class to the "OK" button
    //                     }
    //                 });
    //                 return; // Stop further execution of the function
    //             }

    //             // Check if selectedEmployees is empty or null
    //             if (!selectedEmployees || selectedEmployees.length === 0) {
    //                 // Display a SweetAlert modal indicating that at least one employee must be selected
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Employee Required',
    //                     html: 'No employees selected. Please select at least one employee.',
    //                     customClass: {
    //                         confirmButton: 'swal-button' // Apply custom class to the "OK" button
    //                     }
    //                 });
    //                 return; // Stop further execution of the function
    //             }

    //             // Retrieve the values from the date input fields
    //             var dateStart = $('#dateStartSched').val();
    //             var dateEnd = $('#dateEndSched').val();

    //             // Check if dateStart or dateEnd is empty
    //             if (!dateStart || !dateEnd) {
    //                 // Display a SweetAlert modal indicating that both dates are required
    //                 Swal.fire({
    //                     icon: 'error',
    //                     title: 'Date Required',
    //                     html: 'Both start date and end date must be selected.',
    //                     customClass: {
    //                         confirmButton: 'swal-button' // Apply custom class to the "OK" button
    //                     }
    //                 });
    //                 return; // Stop further execution of the function
    //             }

    //             // Encode the array of selected course IDs into JSON format
    //             var courseIdArray = selectedCourseId;

    //             // Send an AJAX request to insert the data into the database
    //             $.ajax({
    //                 url: 'insert_schedule.php', // PHP script to handle the insertion
    //                 method: 'POST',
    //                 data: {
    //                     dateStart: dateStart,
    //                     dateEnd: dateEnd,
    //                     batchNumber: batchNumber,
    //                     courseId: selectedCourseId[selectedCourseId.length - 1], // Send only the last selected course ID
    //                     // courseId: courseIdArray, // Send courseId as JSON string
    //                     selectedEmployees: selectedEmployees // Send selectedEmployees array
    //                 },
    //                 success: function(response) {

    //                     // Handle the success response, such as displaying a success message or redirecting the user
    //                     // console.log('Schedule created successfully!', response);
    //                     // Parse the JSON response
    //                     var data = JSON.parse(response);
    //                     if (data.status === 'success') {
    //                         // If the response indicates success, display a success message
    //                         Swal.fire({
    //                             title: 'Success!',
    //                             text: data.message,
    //                             icon: 'success',
    //                             timer: 1500,
    //                             timerProgressBar: true,
    //                             showConfirmButton: false
    //                         }).then(() => {
    //                             // Redirect the user to the training_schedules.php page after the Swal.fire message is closed
    //                             window.location.href = 'training_schedules.php';
    //                         });

    //                     } else if (data.status === 'error') {
    //                         // If the response indicates an error
    //                         if (data.message === 'Batch number already exists') {
    //                             // Display an alert if the batch number already exists
    //                             Swal.fire({
    //                                 title: 'Error!',
    //                                 text: data.message,
    //                                 icon: 'error',
    //                                 timer: 1500,
    //                                 timerProgressBar: true,
    //                                 showConfirmButton: false
    //                             });
    //                         } else {
    //                             // Otherwise, display an error message using Swal.fire
    //                             Swal.fire({
    //                                 title: 'Error!',
    //                                 text: data.message,
    //                                 icon: 'error',
    //                                 timer: 1500,
    //                                 timerProgressBar: true,
    //                                 showConfirmButton: false
    //                             });
    //                         }
    //                     }
    //                 },
    //                 error: function(xhr, status, error) {
    //                     // Handle the error response
    //                     console.error('Error creating schedule:', error);
    //                     // You can display an error message to the user if needed
    //                 }
    //             });
    //         });
</script>

</html>