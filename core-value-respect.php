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
        .icon-action {
            color: #4E73DF;
            /* Default color */
            transition: color 0.3s ease;
            /* Smooth color transition */
            cursor: pointer;
            /* Pointer cursor on hover */
        }

        .icon-action:hover {
            color: #859FE9;
            /* Color on hover */
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
            <div class="mb-3">
                <h4 class="mb-0 mr-3 font-weight-bold">Respect</h4>
            </div>
        </div>

        <!-- Datatable -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 row m-0">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="generateResultButton" style="font-weight: bold;">
                        <i class="fas fa-download"></i> Generate Result
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table table-sm table-bordered table-hover text-uppercase text-center" id="displayCoreValueResponse" width="100%" cellspacing="0">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>RESPONSE NAME</th>
                                <th>EMPLOYEE NUMBER OF EVALUATED</th>
                                <th>NAME OF EVALUATED</th>
                                <th>POSITION OF EVALUATED</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
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
    <script src="js/ajax-course.js"></script>
</body>

<script>
    $(document).ready(function() {
        var table = $('#displayCoreValueResponse').DataTable({
            stateSave: true,
            ajax: {
                url: 'fetch_core_values.php',
                dataSrc: '',
                complete: function(xhr, textStatus) {
                    console.log('Complete Response:', xhr.responseText);
                }
            },
            columns: [{
                    data: 'response_name',
                    title: 'RESPONSE NAME'
                },
                {
                    data: 'evaluated_idnumber',
                    title: 'EMPNO OF EVALUATED'
                },
                {
                    data: 'evaluated_name',
                    title: 'NAME OF EVALUATED'
                },
                {
                    data: 'evaluated_position',
                    title: 'POSITION OF EVALUATED'
                },
                {
                    data: null,
                    title: 'ACTION',
                    render: function(data, type, row) {
                        return `
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill icon-action" viewBox="0 0 16 16" onclick="redirectToHumilityList(${row.id}, '${row.response_name}', '${row.evaluated_name}', '${row.evaluated_position}', '${row.evaluated_idnumber}')">
                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                        <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                    </svg>
                `;
                    }
                }
            ]
        });

        // Event listener for the Generate Result button
        $('#generateResultButton').on('click', function() {
            var tableData = table.rows().data().toArray();
            var aggregatedData = {};

            // Extract and aggregate values for each respect category
            tableData.forEach(function(row) {
                var evaluatedData = JSON.parse(row.responses);
                Object.keys(evaluatedData).forEach(function(category) {
                    if (category.startsWith('respect')) {
                        var data = evaluatedData[category];
                        Object.keys(data).forEach(function(key) {
                            var value = parseInt(data[key]) || 0;

                            if (!aggregatedData[category]) {
                                aggregatedData[category] = {
                                    'Champion': 0,
                                    'Positive Impression': 0,
                                    'No Comment': '', // Initialize as empty string to handle absence
                                    'Negative Impression': 0,
                                    'Drag': 0
                                };
                            }
                            if (key.includes('champion')) {
                                aggregatedData[category]['Champion'] += value;
                            } else if (key.includes('positive')) {
                                aggregatedData[category]['Positive Impression'] += value;
                            } else if (key.includes('noComment')) {
                                aggregatedData[category]['No Comment'] += value; // Directly set value

                            } else if (key.includes('negative')) {
                                aggregatedData[category]['Negative Impression'] += value;
                            } else if (key.includes('drag')) {
                                aggregatedData[category]['Drag'] += value;
                            }
                        });
                    }
                });
            });

            // Prepare data for Excel
            var formattedData = [];
            var rowTypes = ['Champion', 'Positive Impression', 'No Comment', 'Negative Impression', 'Drag'];
            var columnHeaders = new Set();

            // Determine columns with actual data
            Object.keys(aggregatedData).forEach(function(category) {
                var hasData = rowTypes.some(function(type) {
                    return aggregatedData[category][type] !== 0 && aggregatedData[category][type] !== '';
                });
                if (hasData) {
                    columnHeaders.add(category);
                }
            });

            // Generate formatted data for Excel
            rowTypes.forEach(function(type) {
                var row = {
                    'category': type
                };
                columnHeaders.forEach(function(category) {
                    // Use empty string if no data is available
                    row[category] = aggregatedData[category] && (type === 'No Comment' ?
                        (aggregatedData[category][type] !== '' ? aggregatedData[category][type] : '') :
                        aggregatedData[category][type]) || '';
                });
                formattedData.push(row);
            });

            // Calculate the TOTAL row
            var totalRow = {
                'category': 'TOTAL'
            };
            columnHeaders.forEach(function(category) {
                totalRow[category] = rowTypes.reduce(function(sum, type) {
                    return sum + (parseInt(aggregatedData[category][type]) || 0);
                }, 0);
            });

            // Append the TOTAL row to the formatted data
            formattedData.push(totalRow);
            // Create a new workbook and add the formatted data
            var ws = XLSX.utils.json_to_sheet(formattedData, {
                header: ['category'].concat(Array.from(columnHeaders))
            });
            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Core Values');

            // Export the workbook
            XLSX.writeFile(wb, 'Core_Values_Report.xlsx');
        });
    });

    function redirectToHumilityList(id, responseName, evaluatedName, evaluatedPosition, evaluatedIdNumber) {
        // Encode the parameters to ensure they are URL-safe
        const encodedResponseName = encodeURIComponent(responseName);
        const encodedEvaluatedName = encodeURIComponent(evaluatedName);
        const encodedEvaluatedPosition = encodeURIComponent(evaluatedPosition);
        const encodedEvaluatedIdNumber = encodeURIComponent(evaluatedIdNumber);


        // Redirect to the new page with the parameters
        window.location.href = `core-value-respect-list.php?id=${id}&response_name=${encodedResponseName}&evaluated_name=${encodedEvaluatedName}&evaluated_position=${encodedEvaluatedPosition}&evaluated_idnumber=${encodedEvaluatedIdNumber}`;
    }

    // $('#displayCoreValueResponse').DataTable({
    //     stateSave: true
    // });
</script>

</html>