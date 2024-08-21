<?php
$ORconnect = mysqli_connect("localhost", "root", "", "db");
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

session_start();
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
</head>

<style>
    /* Additional styles to make #topicContainer scrollable */
    #topicContainer {
        max-height: 300px;
        /* Adjust this height as needed */
        overflow-y: auto;
    }

    .topic {
        display: flex;
        margin-bottom: 15px;
    }

    .text-white {
        color: red !important;
    }

    .topic-title,
    .topic-textarea {
        width: 100%;
    }

    .badge-info {
        background-color: #1CC88A !important;
        color: #fff;
        padding: 0.25em 0.5em;
        border-radius: 1.5em;
        font-weight: bold !important;
        font-size: 0.9em;
        text-transform: uppercase;
        cursor: pointer;
        position: relative;
        -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
    }

    .badge-info:before,
    .badge-info:after {
        content: "";
        position: absolute;
        z-index: -1;
        -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
        -moz-box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.8);
        top: 50%;
        bottom: 0;
        left: 10px;
        right: 10px;
        -moz-border-radius: 100px / 10px;
        border-radius: 100px / 10px;
    }

    .badge-info:after {
        right: 10px;
        left: auto;
        -webkit-transform: skew(8deg) rotate(3deg);
        -moz-transform: skew(8deg) rotate(3deg);
        -ms-transform: skew(8deg) rotate(3deg);
        -o-transform: skew(8deg) rotate(3deg);
        transform: skew(8deg) rotate(3deg);
    }

    .badge-info:hover {
        background-color: #20E19E !important;
        /* Change this to the desired hover color */
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
                    <a href="courses.php">
                        <h4 class="mb-0 mr-3" style="font-weight: bold;">Training Course</h4>
                    </a>
                    <h4 class="mr-3">/</h4>
                    <h4 id="page-title" class="mb-0 course-text" style="font-weight: bold;">
                        <?php
                        if (isset($_GET['id'])) {
                            echo "View Course";
                        } else {
                            echo "Add Course";
                        }
                        ?>
                    </h4>
                </div>
            </div>
        </div>
        <form id="courseForm" class="d-none">
            <!-- Add Topics Button -->
            <div class="d-flex justify-content-end mb-3">
                <?php
                if (isset($_GET['id'])) {
                    echo '<button type="button" class="btn btn-success d-flex justify-content-between align-items-center mr-3"
                                id="courseEdit">
                                <span class="mr-3">Edit</span> 
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                </svg>
                                </button>';
                }
                ?>
                <button type="button" class="btn btn-primary font-weight-bold <?php echo isset($_GET['id']) ? 'd-none' : '' ?>" id="addTopicButton">
                    <i class="mr-1 fas fa-list-alt"></i> + Add New Topics
                </button>
            </div>
            <hr>
            <div class="trainings-box row">
                <div class="container-fluid col-md-6">
                    <!-- course name  -->
                    <div>
                        <h5 class="font-weight-bold">Title</h5>
                        <div class="input-group mb-3">
                            <input type="text" id="courseTitle" name="courseName" class="form-control" placeholder="Courses Name" aria-label="Courses Name" aria-describedby="basic-addon2" value="" required>
                        </div>
                    </div>

                    <!-- course description  -->
                    <div>
                        <h5 class="font-weight-bold">Description</h5>
                        <div class="input-group mb-3">
                            <textarea class="form-control custom-textarea" id="courseDescription" name="courseDescription" placeholder="Course Description" aria-label="Course Description" aria-describedby="basic-addon2" required></textarea>
                        </div>
                    </div>
                    <!-- department dropdown  -->
                    <div>
                        <h5 class="enroll-department font-weight-bold <?php echo isset($_GET['id']) ? 'd-none' : '' ?>">
                            Enroll Departments
                        </h5>
                        <div class="d-flex justify-content-between mt-2" id="listOfDepartment">
                            <select class="form-control p-2 mb-3 w-auto text-small enroll-department <?php echo isset($_GET['id']) ? 'd-none' : '' ?>" name="" id="departmentSelector">
                                <option value="">- Select Area -</option>
                                <option value="HO">Head Office</option>
                                <option value="commi">Commissary</option>
                                <option value="prod">Production</option>
                                <option value="south">South Area</option>
                                <option value="north">North Area</option>
                                <option value="MFO">MFO Area</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- 2 container -->
                <div class="container-fluid col-md-6 justify-content-end mb-3">
                    <!-- Topic Container -->
                    <div>
                        <div class="">
                            <!-- title topic -->
                            <div>
                                <h5 class="font-weight-bold">Topic/s</h5>
                                <!-- <div class="d-flex justify-content-between">
                                        <h7 class="mr-5 font-weight-bold">Title</h7>
                                        <h7 class="font-weight-bold">Description</h7>
                                        <h7 class="font-weight-bold"></h7>
                                    </div> -->
                            </div>
                        </div>
                    </div>
                    <div id="topicContainer" class="p-2">
                        <?php
                        if (!isset($_GET['id'])) { ?>
                            <div class="topic d-flex mb-3">
                                <!-- title topic -->
                                <textarea class="form-control topic-title mr-3" id="topicTitle" name="topicTitle[]" placeholder="Topic Title" aria-label="Topic Title" aria-describedby="basic-addon2" required></textarea>
                                <!-- topic description -->
                                <textarea class="form-control topic-textarea" id="topicDescription" name="topicDescription[]" placeholder="Topic Description" aria-label="Topic Description" aria-describedby="basic-addon2"></textarea>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="pr-3 pl-2 pt-0 mb-4">
                        <div class="d-flex flex-wrap" id="enrolled-departments">
                            <!-- Content Here -->
                        </div>
                    </div>

                    <div class="p-3 d-flex justify-content-between">
                        <a href="courses.php">
                            <button type="button" class="btn btn-secondary">
                                <?php if (isset($_GET['id'])) {
                                    echo 'Back';
                                } else {
                                    echo 'Cancel';
                                } ?>
                            </button>
                        </a>
                        <?php
                        if (!isset($_GET['id'])) {
                            echo '<button type="submit" class="btn-create-course btn btn-primary" id="createCourseBtn" style="font-weight:bold;">Submit Course</button>';
                        }
                        ?>
                        <button type="submit" class="btn-create-course btn btn-primary d-none" id="updateCourseBtn" style="font-weight:bold;">Update Course</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-body mx-5 d-none" id="no-course">
        <div class="row">
            <div class="col-lg-6 col-sm-12 mb-4">
                <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                    <div class="d-flex flex-column">
                        <h5 class="text-muted">No topic found. Create one now!</h5>
                        <p class="fs-1">If there are problems, please contact the IT Department</p>
                        <a href="editcourses.php" class="btn btn-sm btn-primary p-3">Create Course</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                    <img src="images/no-leave.png" alt="" width="250">
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

    <?php include('course/tagDepartmentModal.php'); ?>

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
    <script src="js/add-edit-course.js"></script>
</body>

</html>