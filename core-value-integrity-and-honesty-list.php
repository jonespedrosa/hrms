<!-- Begin Page Content --> <!-- Search -->
<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
unset($_SESSION['viewPrintSched']);
unset($_SESSION['emp_sched']);
if (empty($_SESSION['user'])) {
    header('location:login.php');
}
$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$userlevel = $row['userlevel'];
$empno = $row['empno'];

$sql1 = "SELECT * FROM user WHERE username = '" . $_SESSION['user']['username'] . "'";
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
    <!-- SWAL -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add this in the <head> section of your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <style>
        .text-response {
            color: #43454F;
        }

        .text-details {
            color: #4E73DF;
        }

        .text-center {
            text-align: center;
        }

        #displayCoreValueResponse td {
            text-align: center;
        }

        @media screen and (max-width: 800px) {
            table {
                border: 0;
            }

            table caption {
                font-size: 1.3em;
            }

            table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table tr {
                border-bottom: 5px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }

            table td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table td:last-child {
                border-bottom: 0;
            }
        }
    </style>
</head>

<body id="page-top" class="sidebar-toggled">
    <?php
    include("navigation.php");
    // include("course/filterModal.php");
    ?>
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <div class="d-flex">
                <a href="core-value-humility.php">
                    <h4 class="mb-0 mr-3" style="font-weight: bold;">Integrity And Honesty</h4>
                </a>
                <h4 class="mr-3">/</h4>
                <h4 class="mb-0 mr-3" id="addTrainingBatch">Response List</h4>
            </div>
        </div>
        <!-- Datatable -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-column align-items-start">
                <h5 class="m-0 font-weight-bold text-details">Person Details</h5>
                <h6 id="responsesName" class="m-0 text-response"></h6>
                <h6 id="evaluatedName" class="m-0 text-response"></h6>
                <h6 id="evaluatedPosition" class="m-0 text-response"></h6>
                <h6 id="evalutedIdNumber" class="m-0 text-response"></h6>
                <h6 id="subject" class="m-0 text-response"></h6>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="displayIntegrityAndHonestyList" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>1A</th>
                                <th>1B</th>
                                <th>1D</th>
                                <th>2B</th>
                                <th>3A</th>
                                <th>3B</th>
                                <th>3C</th>
                                <th>3D</th>
                                <th>4B</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Comments Details  -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-column align-items-start">
                <h5 class="m-0 font-weight-bold text-details">Comment Details</h5>
                <div id="dynamicComment"></div>
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Add DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <!-- JSZip -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <!-- PDFMake -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <!-- Buttons HTML5 -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <!-- Buttons Print -->
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!-- SheetJS -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</body>

<script>
    // Function to get query parameters from URL
    function getQueryParam(param) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    const integrityAndHonestyMapping = {
        'integrity-and-honesty-1a': ['integrity-and-honesty-1a-champion', 'integrity-and-honesty-1a-positive', 'integrity-and-honesty-1a-noComment', 'integrity-and-honesty-1a-negative', 'integrity-and-honesty-1a-drag'],
        'integrity-and-honesty-1b': ['integrity-and-honesty-1b-champion', 'integrity-and-honesty-1b-positive', 'integrity-and-honesty-1b-noComment', 'integrity-and-honesty-1b-negative', 'integrity-and-honesty-1b-drag'],
        'integrity-and-honesty-1d': ['integrity-and-honesty-1d-champion', 'integrity-and-honesty-1d-positive', 'integrity-and-honesty-1d-noComment', 'integrity-and-honesty-1d-negative', 'integrity-and-honesty-1d-drag'],
        'integrity-and-honesty-2b': ['integrity-and-honesty-2b-champion', 'integrity-and-honesty-2b-positive', 'integrity-and-honesty-2b-noComment', 'integrity-and-honesty-2b-negative', 'integrity-and-honesty-2b-drag'],
        'integrity-and-honesty-3a': ['integrity-and-honesty-3a-champion', 'integrity-and-honesty-3a-positive', 'integrity-and-honesty-3a-noComment', 'integrity-and-honesty-3a-negative', 'integrity-and-honesty-3a-drag'],
        'integrity-and-honesty-3b': ['integrity-and-honesty-3b-champion', 'integrity-and-honesty-3b-positive', 'integrity-and-honesty-3b-noComment', 'integrity-and-honesty-3b-negative', 'integrity-and-honesty-3b-drag'],
        'integrity-and-honesty-3c': ['integrity-and-honesty-3c-champion', 'integrity-and-honesty-3c-positive', 'integrity-and-honesty-3c-noComment', 'integrity-and-honesty-3c-negative', 'integrity-and-honesty-3c-drag'],
        'integrity-and-honesty-3d': ['integrity-and-honesty-3d-champion', 'integrity-and-honesty-3d-positive', 'integrity-and-honesty-3d-noComment', 'integrity-and-honesty-3d-negative', 'integrity-and-honesty-3d-drag'],
        'integrity-and-honesty-4b': ['integrity-and-honesty-4b-champion', 'integrity-and-honesty-4b-positive', 'integrity-and-honesty-4b-noComment', 'integrity-and-honesty-4b-negative', 'integrity-and-honesty-4b-drag']
    };

    // Function to fetch core values and populate the DataTable
    function fetchIntegrityAndHonestyValues() {
        const id = getQueryParam('id');

        $.ajax({
            url: 'fetch_response_list.php', // Your PHP file
            type: 'POST',
            data: {
                id: id
            },
            success: function(response) {
                console.log(response); // Log the entire response for debugging
                // Parse the JSON response
                let data = JSON.parse(response);
                let tableData = [];
                let dynamicCommentHtml = '';

                // Iterate through each record in the response
                data.forEach(record => {
                    let responses = record.responses;
                    let row = [];

                    // Iterate through all integrityAndHonestyMapping keys
                    Object.keys(integrityAndHonestyMapping).forEach(key => {
                        let subKeys = integrityAndHonestyMapping[key];
                        let value = '';

                        if (responses[key]) {
                            // Find the first non-empty sub-key value
                            subKeys.some(subKey => {
                                if (responses[key][subKey]) {
                                    value = responses[key][subKey];
                                    return true; // Stop at the first non-empty value
                                }
                                return false;
                            });

                            // Check for short-explanation
                            let explanationKey = key + '-short-explanation'; // Use the key directly
                            if (responses[key] && responses[key][explanationKey]) {
                                dynamicCommentHtml += `
                            <div style="margin-bottom: 2px;">
                                <strong>${key.toUpperCase()}:</strong> "${responses[key][explanationKey]}"
                            </div>`;
                            }
                        }

                        row.push(value || ''); // Default to an empty string if no value found
                    });

                    // Push the data into tableData array
                    tableData.push(row);
                });

                // Log the dynamic comments HTML
                console.log(dynamicCommentHtml); // Temporary log for debugging

                // Set the dynamic comments
                document.getElementById('dynamicComment').innerHTML = dynamicCommentHtml;

                // Initialize DataTable with data
                $('#displayIntegrityAndHonestyList').DataTable({
                    stateSave: true,
                    data: tableData,
                    destroy: true // To reinitialize DataTable
                    // columns: Object.keys(integrityAndHonestyMapping).map(key => ({
                    //     title: key.toUpperCase().replace(/-/g, ' ')
                    // }))
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });
    }

    function setPersonDetails() {
        const responseName = getQueryParam('response_name');
        const evaluatedName = getQueryParam('evaluated_name');
        const evaluatedPosition = getQueryParam('evaluated_position');
        const evaluatedIdNumber = getQueryParam('evaluated_idnumber');
        document.getElementById('responsesName').innerHTML = 'Response Name: <strong>' + (responseName || 'N/A') + '</strong>';
        document.getElementById('evaluatedName').innerHTML = 'Evaluated Name: <strong>' + (evaluatedName || 'N/A') + '</strong>';
        document.getElementById('evaluatedPosition').innerHTML = 'Evaluated Position: <strong>' + (evaluatedPosition || 'N/A') + '</strong>';
        document.getElementById('evalutedIdNumber').innerHTML = 'Evaluated Employee Number: <strong>' + (evaluatedIdNumber || 'N/A') + '</strong>';
        document.getElementById('subject').innerHTML = 'Subject: <strong>Integrity and Honesty</strong>';
    }

    // Call functions on page load
    window.onload = function() {
        setPersonDetails();
        fetchIntegrityAndHonestyValues();
    };

    // Initialize DataTable
    $(document).ready(function() {
        $('#displayIntegrityAndHonestyList').DataTable({
            stateSave: true
        });
    });
</script>

</html>