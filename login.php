<?php
include('achat/database_connection.php'); 
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");
$ORconnect = mysqli_connect("localhost", "root", "", "db");
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
      header("location:home.php");  
    exit;
}

if(isset($_POST['login']))
{


$_SESSION['empno'] = $_POST['empno'];

$sql1_count = "SELECT COUNT(*) FROM user_info WHERE empno = '".$_POST['empno']."'";
$query1_count=$HRconnect->query($sql1_count);
$row1_count=$query1_count->fetch_array();

if($row1_count["COUNT(*)"] <= 0){
    echo '
    <script src="vendor/jquery/jquery.min.js"></script>
    <script>
    $(function() {
    $(".thanks").delay(4000).fadeOut();
    
    });
    </script>
    <div aria-live="polite" aria-atomic="true" style="position: absolute; min-height: 100px;">
        <div class="thanks toast fade show" style="position: fixed; top: 50px; right: 15px; z-index: 9999;">
            <div class="toast-header bg-danger">
                <h5 class="mr-auto my-0 text-light"><i class="fa fa-times-circle mr-3" aria-hidden="true"></i>Validation Failed</h5>
                <small class="text-light">Just now</small>
            </div>

            <div class="toast-body">
                The entered password is <b class="text-danger">wrong or the Employee does not exist</b>.
            </div>
        </div>
    </div>';
}else{

        $sql1 = "SELECT * FROM user_info WHERE empno = '".$_POST['empno']."'";
        $query1=$HRconnect->query($sql1);
        $row1=$query1->fetch_array();
        
        @$user1 = $row1['userid'];
        $userlevel = $row1['userlevel'];
        $mothercafe = $row1['mothercafe'];
        
        
        $sql2 = "SELECT * FROM user WHERE userid = '".$mothercafe."'";
        $query2=$ORconnect->query($sql2);
        $row2=$query2->fetch_array();
        // echo $sql2;
        $loginname = $row2['loginname'];
        $user2 = $row2['userid'];
        
        if($_POST["password"] == $row1["secpass"]){
        
            $query = "
                SELECT * FROM user 
                WHERE loginname = :loginname
            ";
        
            $statement = $connect->prepare($query);
            $statement->execute(
                array(
                    ':loginname' => $loginname
                )
            );  
            $count = $statement->rowCount();
            if($count > 0)
            {
                $result = $statement->fetchAll();
                foreach($result as $row)
                {
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['username'] = $row['username'];

                    $sub_query = "
                    INSERT INTO login_details 
                    (userid) 
                    VALUES ('".$row['userid']."')
                    ";
                    $statement = $connect->prepare($sub_query);
                    $statement->execute();
                    $_SESSION['login_details_id'] = $connect->lastInsertId();
                    $_SESSION['user']=array('userid'=>$row['userid'],'loginname'=>$row['loginname'],'username'=>$row['username'],'password'=>$row['password'],'userlevel'=>$row['userlevel']);
                    $userlevel=$_SESSION['user']['userlevel'];
                

                    $date_time = date("Y-m-d H:i");
                    $empno = $_SESSION['empno'];
                    $inserted = "Login Successfully";
                    $action = "Login Account";

                    $sql2 = "INSERT INTO log (empno, action, inserted, date_time) 
                            VALUES('$empno', '$action', '$inserted','$date_time')";
                    $HRconnect->query($sql2);
                    header('location:home.php');
                    switch($userlevel)
                    {
                        case 'Master':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin1':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin2':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin3':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin4':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin5':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin6':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin7':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'Admin8':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'User':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'User1':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'User2':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'User3':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                        case 'User4':
                        session_start();
                        header('location:home.php');
                        $_SESSION["loggedin"] = true;
                        break;
                    }
                }
            }
            else
            {
                $message = '<label>Wrong Username</label>';
            }
        }else{
        
        $messages = "<p class='text-danger text-center'>Invalid ID OR Password Please Try Again</p>";
        }
    }
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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-muted">
<br>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-3">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block"><img src ="images/employee.gif"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-3"><img src="images/logoo.png" width="100" height="105"><br>
											<span style="color:#7E0000;font-family:Times New Roman, cursive;font-size:90%;">
										</h1>
                                    </div>
									<form class="login100-form validate-form user" method="post">
                                                    <?php echo @$messages; ?>										
										<div class="form-group">
                                            <input type="number" class="form-control form-control-user text-center"
                                             name="empno" id="first-name" placeholder="Employee Number" required>
                                        </div>
                                
                                        <div class="form-group text-center">							
											<div class="message text-danger"></div>
											<input type="password" class="form-control form-control-user text-center"
											id="password" type="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck" onclick="myFunction()">
                                                <label class="custom-control-label float-right" for="customCheck">Show Password</label>
                                            </div>
                                        </div>
									
                                        <input type="submit" name="login" value="Login" class="btn btn-primary btn-user btn-block bg-gradient-primary mb-3" >
                                                              
									<div class="text-center">
                                        <a class="small" href="index.php">Back to Employee Portal</a>
                                    </div>                                    
                                </form>								
                                </div>								
								<div class="copyright text-center small mb-2">
									<span>Copyright © Mary Grace Foods Inc. 2019</span>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
			
			
	<!-- End of Footer -->
	
	<script>
	function myFunction() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
		x.type = "text";
	  } else {
		x.type = "password";
	  }
	}
	</script>
	
	<script>	
	const password = document.querySelector('#password');
	const message = document.querySelector('.message');

	password.addEventListener('keyup', function (e) {
		if (e.getModifierState('CapsLock')) {
			message.textContent = 'Caps lock is on';
		} else {
			 message.textContent = '';
		}
	});	
	</script>

	<!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    
<!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>

<!--===============================================================================================-->
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
    <script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
    <script src="js/main.js"></script>

</body>
</html>