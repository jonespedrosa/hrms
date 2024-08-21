<?php
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");


$sql = "SELECT * FROM user_info WHERE empno = '" . $_SESSION['empno'] . "'";
$query = $HRconnect->query($sql);
$row = $query->fetch_array();
$mothercafe = $row['mothercafe'];
$branch = $row['branch'];

?>

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion toggled" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php">
            <div class="sidebar-brand-icon">
                <img src="images/logoo.png" width="40" height="45">
            </div>

        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link" href="home.php?branch=<?php echo $_SESSION['useridd']; ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>


        <?php if ($userlevel != 'staff') {
        ?>
            <!-- Heading -->
            <div class="sidebar-heading">
                Information
            </div>

            <!-- Nav Item - Pages Collapse Menu -->

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Employee</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header"> Record-Keeping</h6>
                        <a class="collapse-item" href="employeelist.php?active=active">Employee List</a>
                        <?php
                        if ($empno != '2169' and $empno != '5715' and $empno != '4625') {
                        ?>
                            <a class="collapse-item" href="viewsched.php?current=current">Cut-Off Schedule</a>
                        <?php
                        }
                        ?>

                        <?php
                        $exemption = [6114, 6115];
                        if (in_array($empno, $exemption) || $userlevel == 'master') {
                        ?>
                            <a class="collapse-item" href="cwwtagger.php">CWW Tagger</a>

                        <?php } ?>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <?php
                if ($empno != '4625') {
                ?>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fa fa-file" aria-hidden="true"></i>
                        <span>Filed Documents</span>
                    <?php
                }
                    ?>
                    </a>
                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Documents-Keeping</h6>
                            <a class="collapse-item" href="overtime.php?pending=pending">Filed Overtime</a>
                            <?php if ($empno != '5361' and $empno != '3178' and $empno != '5515' and $empno != '5452' and $empno != '4811' and $empno != '2684') {
                            ?>
                                <a class="collapse-item" href="obp.php?pendingut=pendingut">Filed OBP</a>
                            <?php
                            }
                            ?>
                            <?php if (
                                $empno != '5047' and $empno != '4451' and $empno != '2620' and $empno != '927' and
                                $empno != '342' and $empno != '321' and $empno != '1800' and $empno != '29' and $empno != '998' and $empno != '5362' and $empno != '165' and $empno != '1675' and
                                $empno != '5626' and $empno != '47' and $empno != '16' and $empno != '930' and $empno != '201' and $empno != '157' and $empno != '137' and
                                $empno != '581' and $empno != '3224' and $empno != '5238' and $empno != '5450' and $empno != '55' and $empno != '54' and $empno != '52' and $empno != '84' and
                                $empno != '352' and $empno != '640' and $empno != '12' and $empno != '5356' and $empno != '2203' and $empno != '6619' and $empno != '1996'
                            ) {
                            ?>

                                <a class="collapse-item" href="leave.php?pending=pending">Filed Leave</a>

                                <?php if ($empno != '3339' and $empno != '5051' and $empno != '5432' and $empno != '5717' and $empno != '5356') {
                                ?>
                                    <a class="collapse-item" href="filedconcerns.php?pending=pending">Filed Concern</a>
                            <?php
                                }
                            }
                            ?>
                            <a class="collapse-item" href="filed_change_schedule.php?pending=pending">Filed Change Schedule</a>
                            <?php if ($empno != '5361' and $empno != '3178' and $empno != '5515' and $empno != '5452' and $empno != '4811' and $empno != '2684') {
                            ?>
                                <a class="collapse-item" href="working_dayoff.php?pending=pending">Filed Working Day Off</a>
                            <?php
                            }
                            ?>
                            <a class="collapse-item" href="filedpincode.php?pending=pending">Filed Staff's Pincode</a>
                            <!--    <a class="collapse-item" href="#" >Additional</a>
                        <a class="collapse-item" href="#">Additional</a> -->
                        </div>
                    </div>
            </li>


            <hr class="sidebar-divider">
            <?php if ($userlevel == 'master' or $userlevel == 'admin' or $branch == 'AUDIT' or $empno == '1073' or $empno == 2684) {
            ?>
                <!-- Heading -->
                <div class="sidebar-heading">
                    Reports
                </div>

                <!-- Nav Item - Tables -->
                <li class="nav-item">
                    <a class="nav-link" href="discrepancy.php">
                        <i class="fas fa-chart-bar"></i>
                        <span>Cut-off Details</span></a>
                </li>
            <?php
            }
            ?>
        <?php
        }
        ?>




        <?php if ($empno  == '6115' || $empno  == '6114' || $empno  == '2525' || $empno  == '5182' || $empno  == '2008' || $empno  == '71' || $empno  == '4209' || $empno  == '158' || $empno  == '2706' || $empno  == '1926' || $empno  == '6144' || $empno  == '6233' || $empno  == '2052' || $empno  == '197') { ?>


            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                Trainings
            </div>

            <!-- Nav Item - for Learning && Development System-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLearningDeveloptment" aria-expanded="true" aria-controls="collapseLearningDeveloptment">
                    <i class="fa fa-chalkboard-teacher" aria-hidden="true"></i>
                    <span>Learning & Development</span>
                </a>
                <div id="collapseLearningDeveloptment" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="courses.php">Courses</a>
                        <a class="collapse-item" href="training_schedules.php">Schedules</a>
                    </div>
                </div>
            </li>
        <?php } ?>


        <?php if ($empno == '2008' || $empno == '6115' || $empno == '6114' || $empno == '2525' || $empno == '5182') { ?>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                SETTINGS
            </div>
            <!-- Nav Item - for Learning && Development System-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" onclick="showResetPasswordModal()" aria-expanded="true" aria-controls="collapseResetPassword">
                    <i class="fa fa-key" aria-hidden="true"></i>
                    <span>Reset Password</span>
                </a>
            </li>
        <?php } ?>

        <?php if ($empno == '2008' || $empno == '1964') { ?>

            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                FORMS
            </div>
            <!-- Nav Item - for Learning && Development System-->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCoreValue" aria-expanded="true" aria-controls="collapseCoreValue">
                    <i class="fa fa-chalkboard-teacher" aria-hidden="true"></i>
                    <span>Core Value</span>
                </a>
                <div id="collapseCoreValue" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="core-value-humility.php">Humility</a>
                        <a class="collapse-item" href="core-value-respect.php">Respect</a>
                        <a class="collapse-item" href="core-value-integrity-and-honesty.php">Integrity and Honesty</a>
                        <a class="collapse-item" href="core-value-service-from-compassion.php">Service From Compassion</a>
                        <a class="collapse-item" href="core-value-thriving-through-excellence.php">Thriving Through Excellence</a>
                    </div>
                </div>
            </li>

        <?php } ?>


        <!-- Divider -->
        <hr class="sidebar-divider">

        <div class="sidebar-heading">
            PORTAL
        </div>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fa fa-address-card" aria-hidden="true"></i>
                <span>Employee Portal</span></a>
        </li>


        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>


    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - Messages -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php
                            if (
                                ($userlevel != 'master' and $userlevel != 'admin' and $userlevel != 'ac' and $_SESSION['empno'] != 4491 and $_SESSION['empno'] != 6728 and $_SESSION['empno'] != 5717 /*HR */ and $_SESSION['empno'] != 6538 /*End */
                                    and $_SESSION['empno'] != 5432 and $_SESSION['empno'] != 5051 and $_SESSION['empno'] != 3339) or ($_SESSION['empno'] == 1964 or $_SESSION['empno'] == 3294 or $_SESSION['empno'] == 6619
                                    or $_SESSION['empno'] == 6082 or $_SESSION['empno'] == 5834 or $_SESSION['empno'] == 4647 or $_SESSION['empno'] == 3183 or $_SESSION['empno'] == 5584
                                    or $_SESSION['empno'] == 6207 or $_SESSION['empno'] == 2221 or $_SESSION['empno'] == 3336 or $_SESSION['empno'] == 3111 or $_SESSION['empno'] == 24
                                    or $_SESSION['empno'] == 159 or $_SESSION['empno'] == 5752 or $_SESSION['empno'] == 107 or $_SESSION['empno'] == 3027 or $_SESSION['empno'] == 3336
                                    or $_SESSION['empno'] == 5975 or $_SESSION['empno'] == 1075)
                            ) {
                            ?>
                                <span class="text-gray-600 small text-uppercase"><i class='fas fa-store'></i>&nbsp
                                    <?php echo $_SESSION['user']['username']; ?>
                                </span>
                            <?php
                            } else {
                            ?>

                            <?php
                            }
                            ?>
                        </a>
                    </li>

                    <?php
                    if (
                        ($userlevel == 'master' or $userlevel == 'admin' or $userlevel == 'ac' or $_SESSION['empno'] == 4491 or $_SESSION['empno'] == 6728 or $_SESSION['empno'] == 5717 /*HR */ or $_SESSION['empno'] == 6538 /*End */
                            or $_SESSION['empno'] == 5432 or $_SESSION['empno'] == 5051 or $_SESSION['empno'] == 3339) and ($_SESSION['empno'] != 1964
                            and $_SESSION['empno'] != 3294 and $_SESSION['empno'] != 6619 and $_SESSION['empno'] != 6082 and $_SESSION['empno'] != 5834 and $_SESSION['empno'] != 4647
                            and $_SESSION['empno'] != 3183 and $_SESSION['empno'] != 5584 and $_SESSION['empno'] != 6207 and $_SESSION['empno'] != 2221
                            and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 3111 and $_SESSION['empno'] != 24 and $_SESSION['empno'] != 159
                            and $_SESSION['empno'] != 5752 and $_SESSION['empno'] != 107 and $_SESSION['empno'] != 3027
                            and $_SESSION['empno'] != 3336 and $_SESSION['empno'] != 5975 and $_SESSION['empno'] != 1075)
                    ) {
                    ?>

                        <li class="nav-item dropdown no-arrow">
                            <form method="GET">
                                <span class="nav-link text-gray-600 text-uppercase"><i class='fas fa-store fa-sm'></i>
                                    <select class="nav-link text-gray-600 small border-0 text-uppercase bg-white" name="branch" onchange='this.form.submit()' style="width: 100%;">
                                        <option>
                                            <?php
                                            if ($_SESSION['useridd'] != "") {
                                                @$sql2 = "SELECT DISTINCT branch,department FROM user_info where userid = '" . $_SESSION['useridd'] . "'";
                                                $query2 = $HRconnect->query($sql2);
                                                $row2 = $query2->fetch_array();
                                                echo $row2['department'];
                                                echo " - ";
                                                echo $row2['branch'];
                                            } else {
                                                echo "<a class='text-danger'>Please Select Cafe/Dept</a>";
                                            }
                                            ?>
                                        </option>
                                        <?php
                                        if ($userlevel == 'master' or $userlevel == 'admin') {
                                            $sql2 = "SELECT * FROM user where areatype in('HO','Prod','COMMI','South','North','MFO','KIOSK') ORDER BY `areatype` DESC, `username`";
                                        } else {
                                            $sql2 = "SELECT * FROM user where areatype = '$areatype' ORDER BY `areatype` DESC, `username` ";
                                        }


                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 45 or $_SESSION['empno'] == 76 or $_SESSION['empno'] == 124 or $_SESSION['empno'] == 37 or $_SESSION['empno'] == 53 or $_SESSION['empno'] == 2720 or $_SESSION['empno'] == 69) {
                                            $sql2 = "SELECT * FROM user where areatype = 'South' OR userid = 185 ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 109 or $_SESSION['empno'] == 97 or $_SESSION['empno'] == 63 or $_SESSION['empno'] == 170) {
                                            $sql2 = "SELECT * FROM user where areatype = 'MFO' OR userid = 185 ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 38 or $_SESSION['empno'] == 819 or $_SESSION['empno'] == 254 or $_SESSION['empno'] == 302 or $_SESSION['empno'] == 112 or $_SESSION['empno'] == 2094 or $_SESSION['empno'] == 460) {
                                            $sql2 = "SELECT * FROM user where areatype = 'North' OR userid = 185 ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 1331) {
                                            $sql2 = "SELECT * FROM user where areatype in('South','North','MFO') OR userid = 185 ORDER BY `areatype` DESC, `username` ";
                                        }


                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 3071) {
                                            $sql2 = "SELECT * FROM user where userid in (82,155) ORDER BY `areatype` DESC, `username` ";
                                        }


                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 2203) {
                                            $sql2 = "SELECT * FROM user where userid in (227) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 71) {
                                            $sql2 = "SELECT * FROM user where userid in (109,212) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //Log
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 5356) {
                                            $sql2 = "SELECT * FROM user where userid in (155,174) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //Commi
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 885) {
                                            $sql2 = "SELECT * FROM user where userid in (2,10,86,221,244) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'mod' and $_SESSION['empno'] == 4491) {
                                            $sql2 = "SELECT * FROM user where userid in (86) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'mod' and $_SESSION['empno'] == 5717) {
                                            $sql2 = "SELECT * FROM user where userid in (2) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'mod' and $_SESSION['empno'] == 5432 or $_SESSION['empno'] == 5051 or $_SESSION['empno'] == 3339) {
                                            $sql2 = "SELECT * FROM user where userid in (10) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'mod' and $_SESSION['empno'] == 6728) {
                                            $sql2 = "SELECT * FROM user where userid in (244) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //QA
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 5928) {
                                            $sql2 = "SELECT * FROM user where userid in (127,226,225,181,217) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 957) {
                                            $sql2 = "SELECT * FROM user where userid in (127,181,225) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //Bakery
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 5361) {
                                            $sql2 = "SELECT * FROM user where userid in (167,168) ORDER BY `areatype` DESC, `username` ";
                                        }
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 3178) {
                                            $sql2 = "SELECT * FROM user where userid in (3,80,92,164,165,166,167,168,169,171,172,173,214,215,216,217,225,236,232) ORDER BY `areatype` DESC, `username` ";
                                        }
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 5515) {
                                            $sql2 = "SELECT * FROM user where userid in (171,172) ORDER BY `areatype` DESC, `username` ";
                                        }
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 5452) {
                                            $sql2 = "SELECT * FROM user where userid in (173,233) ORDER BY `areatype` DESC, `username` ";
                                        }
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 4811) {
                                            $sql2 = "SELECT * FROM user where userid in (173,233) ORDER BY `areatype` DESC, `username` ";
                                        }
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 2684) {
                                            $sql2 = "SELECT * FROM user where userid in (166,165,232) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //End

                                        //Bakery
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 4072 or $_SESSION['empno'] == 3332) {
                                            $sql2 = "SELECT * FROM user where areatype in('HO','COMMI','South','North','MFO','Prod') AND userid != 119 ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 2165) {
                                            $sql2 = "SELECT * FROM user where userid in (4,2,10,86) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 6538) {
                                            $sql2 = "SELECT * FROM user where userid in (155,174,227,137) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        //South
                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 229) {
                                            $sql2 = "SELECT * FROM user where userid in (206) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 189) {
                                            $sql2 = "SELECT * FROM user where userid in (63,149) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 20) {
                                            $sql2 = "SELECT * FROM user where userid in (11,235) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 40) {
                                            $sql2 = "SELECT * FROM user where userid in (28,62) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 37) {
                                            $sql2 = "SELECT * FROM user where userid in (58,229) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 53) {
                                            $sql2 = "SELECT * FROM user where userid in (56,59) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 3685) {
                                            $sql2 = "SELECT * FROM user where userid in (152,243,153) ORDER BY `areatype` DESC, `username` ";
                                        }

                                        if ($userlevel == 'ac' and $_SESSION['empno'] == 2720) {
                                            $sql2 = "SELECT * FROM user where userid in (54,55,51) ORDER BY `areatype` DESC, `username` ";
                                        }


                                        if (
                                            $userlevel == 'ac' and $_SESSION['empno'] == 3080 or $_SESSION['empno'] == 1261 or $_SESSION['empno'] == 1910
                                            or $_SESSION['empno'] == 3736 or $_SESSION['empno'] == 3819 or $_SESSION['empno'] == 5359 or $_SESSION['empno'] == 4073
                                            or $_SESSION['empno'] == 3770 or $_SESSION['empno'] == 4206 or $_SESSION['empno'] == 3160
                                            or $_SESSION['empno'] == 1053 or $_SESSION['empno'] == 2356 or $_SESSION['empno'] == 3156 or $_SESSION['empno'] == 3612
                                            or $_SESSION['empno'] == 4001 or $_SESSION['empno'] == 1533 or $_SESSION['empno'] == 5263 or $_SESSION['empno'] == 5430
                                            or $_SESSION['empno'] == 4892 or $_SESSION['empno'] == 3337 or $_SESSION['empno'] == 6436 or $_SESSION['empno'] == 6209
                                            or $_SESSION['empno'] == 6244 or $_SESSION['empno'] == 6245 or $_SESSION['empno'] == 6438
                                        ) {
                                            $sql2 = "SELECT * FROM user where areatype in('HO','Prod','COMMI','South','North','MFO','KIOSK') AND userid != 88 ORDER BY `areatype` DESC, `username` ";
                                        }
                                        $query2 = $ORconnect->query($sql2);
                                        while ($row2 = $query2->fetch_array()) {
                                            $userid1 = $row2['userid'];

                                            $sql1 = "SELECT DISTINCT branch,userid FROM user_info where userid = '$userid1'";
                                            $query1 = $HRconnect->query($sql1);
                                            $row1 = $query1->fetch_array();

                                            $sql3 = "SELECT * FROM user where userid = '$userid1'";
                                            $query3 = $ORconnect->query($sql3);
                                            $row3 = $query3->fetch_array();
                                        ?>
                                            <option value="<?php echo @$row1['userid']; ?>">
                                                <?php echo @$row3['areatype']; ?> -
                                                <?php echo @$row1['branch']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </span>
                            </form>
                        </li>
                    <?php
                    }
                    ?>


                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                <?php echo $user; ?>
                            </span>
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">

                            <a class="dropdown-item d-md-none" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400 d-md-none"></i>
                                <?php echo $user; ?>
                            </a>
                            <div class="dropdown-divider d-md-none"></div>

                            <a class="dropdown-item" href="viewemployee.php?edit=edit&empno=<?php echo $row['empno']; ?>">
                                <i class="fa fa-address-card fa-sm fa-fw mr-2 text-gray-400 "></i>
                                Profile
                            </a>


                            <?php
                            if ($userlevel == 'master' or $userlevel == 'admin' and $mothercafe == 137) {
                            ?>
                                <a class="dropdown-item" href="database.php">
                                    <i class="fa fa-database fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Database
                                </a>
                            <?php
                            }
                            ?>

                            <?php
                            if ($userlevel == 'master' or $userlevel == 'admin' or $mothercafe == 137) {
                            ?>
                                <a class="dropdown-item" href="activitylogs.php">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Logs
                                </a>
                            <?php
                            }
                            ?>

                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Settings
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->




            <style>
                .swal2-confirm {
                    background-color: #3FC14C !important;
                    /* Change to your desired blue color */
                    color: #fff;
                    /* Text color */
                    border-color: #2e6da4;
                    /* Border color */
                }

                .swal2-confirm:hover {
                    background-color: #3FC14C !important;
                    /* Darker shade when hovered */
                    border-color: #204d74;
                    /* Darker border color when hovered */
                }

                .centered-input {
                    text-align: center;
                }
            </style>

            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>

            <script>
                function showResetPasswordModal() {
                    Swal.fire({
                        title: 'Enter Employee Number',
                        html: 'Please enter correct <b>Employee Number</b> to proceed.',
                        input: 'text',
                        inputPlaceholder: 'Enter Employee Number',
                        inputAttributes: {
                            'aria-label': 'Enter Employee Number',
                            'maxlength': 4,
                            'inputmode': 'numeric',
                            'pattern': '[0-9]*' // Allow only numeric input
                        },
                        inputValidator: (value) => {
                            if (!value || isNaN(value) || value.length < 1 || value.length > 4) {
                                return 'Please enter a valid Employee Number (1 to 4 digits).';
                            }
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        customClass: {
                            input: 'centered-input'
                        }
                    }).then((result) => {
                        if (result.value) {
                            // Send employee number to modal-reset-password.php using AJAX
                            const empno = result.value;
                            fetch('modal-reset-password.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    body: 'empno=' + encodeURIComponent(empno),
                                }).then(response => response.text())
                                .then(data => {
                                    // Handle response from modal-reset-password.php if needed
                                    Swal.fire({
                                        title: 'Password Reset',
                                        text: data, // Display the response message
                                        icon: 'success'
                                    });
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Failed to reset password. Please try again later.',
                                        icon: 'error'
                                    });
                                });
                        }
                    });

                    // Prevent non-numeric input in real-time (optional)
                    Swal.getInput().addEventListener('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
                    });
                }
            </script>