<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="../images/logoo.png">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker-standalone.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Include SweetAlert2 CSS and JS files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style type="text/css">
        html {
            background-color: #F1F0F0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F1F0F0;
        }

        .header {
            background: linear-gradient(to right, #5B101B, #932634, #932634, #5B101B);
            color: white;
            padding: 10px;
            text-align: center;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .custom-button {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.2));
            border: none;
            border-radius: 5%;
            width: 150px;
            height: 150px;
            display: flex;
            flex-direction: column;
            /* Stack items vertically */
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 18px;
            font-family: Poppins, sans-serif;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #333;
            outline: none;
            /* Remove outline */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Remove any existing shadow */
        }

        .custom-button:focus,
        .custom-button:active {
            outline: none;
            /* Ensure no outline on focus */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Ensure no change in shadow on focus */
        }

        .custom-button i {
            font-size: 24px;
            margin-bottom: 8px;
            /* Space between icon and text */
        }

        .custom-button span {
            padding: 10px;
            display: block;
        }

        .custom-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        /* Additional styles */
        .header img {
            margin-right: 10px;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            font-size: 4em;
            /* Adjust this value to increase or decrease the size */
            line-height: 1.2;
            /* Adjusts the spacing between lines */
            color: #333;
            /* Adjust text color if needed */
        }

        .custom-button:focus {
            box-shadow: 0 0 0 3px rgba(51, 51, 51);
            /* Focus indicator color changed to #932634 */
            /* Adds a visible focus indicator */
        }

        .custom-button:disabled {
            background-color: #e0e0e0;
            color: #b0b0b0;
            cursor: not-allowed;
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="images/logoo.png" height="40" alt="Logo">
        <h4><i class="text-light ml-3 mr-2 py-3" style="font-family: 'Times New Roman', cursive; font-size: 100%;">Mary Grace Café</i></h4>
    </div>
    <h1>DA Forms</h1>
    <div class="button-container">
        <button class="custom-button" id="coreValueButton"
            data-empno="2008"
            data-name="Pedrosa Jr., Quirino Orellano"
            data-position="IT Programmer">
            <i class="fa fa-users"></i>
            <span>CORE VALUE</span>
        </button>
        <button class="custom-button" disabled>
            <i class="fa fa-volume-up"></i>
            <span>HEAR YOU OUT</span>
        </button>
        <button class="custom-button" disabled>
            <i class="fa fa-ticket-alt"></i>
            <span>TICKETING</span>
        </button>
        <button class="custom-button" disabled>
            <i class="fa fa-dollar-sign"></i>
            <span>PAYROLL CONCERN</span>
        </button>
        <button class="custom-button" disabled>
            <i class="fa fa-file-alt"></i>
            <span>PAYSLIP</span>
        </button>
    </div>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to get URL parameters
        function getQueryParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                empno: params.get('empno'),
                name: params.get('name'),
                position: params.get('position')
            };
        }

        // Extract parameters
        const {
            empno,
            name,
            position
        } = getQueryParams();

        // Get the Core Value button
        const coreValueButton = document.getElementById('coreValueButton');

        // Update button data attributes if parameters are present
        if (coreValueButton && empno && name && position) {
            coreValueButton.setAttribute('data-empno', empno);
            coreValueButton.setAttribute('data-name', decodeURIComponent(name));
            coreValueButton.setAttribute('data-position', decodeURIComponent(position));
        }

        // Add click event listener to open URL in new tab
        coreValueButton.addEventListener('click', function() {
            const empno = this.getAttribute('data-empno');
            const name = encodeURIComponent(this.getAttribute('data-name'));
            const position = encodeURIComponent(this.getAttribute('data-position'));

            // // Construct the URL
            // const url = `/hrms/corevalue.php?empno=${empno}&name=${name}&position=${position}`;

            // // Redirect to the URL in the same tab
            // window.location.href = url;

            // Construct the URL
            const url = `/hrms/corevalue.php?empno=${empno}&name=${name}&position=${position}`;

            // Open the URL in a new tab
            window.open(url, '_blank');
        });
    });
</script>

</html>