<!--
//register.php
!-->

<?php

include('database_connection.php');

session_start();

$message = '';

if(isset($_SESSION['userid']))
{
	header('location:index.php');
}

if(isset($_POST["register"]))
{
	$loginname = trim($_POST["loginname"]);
	$username  = trim($_POST["username"]);
	$password  = trim($_POST["password"]);
	$userlevel = ($_POST["userlevel"]);
	$areaid    = ($_POST["areaid"]);
	$check_query = "
	SELECT * FROM user 
	WHERE loginname = :loginname
	";
	$statement = $connect->prepare($check_query);
	$check_data = array(
		':loginname'		=>	$loginname
	);
	if($statement->execute($check_data))	
	{
		if($statement->rowCount() > 0)
		{
			$message .= '<p><label>Username already taken</label></p>';
		}
		else
		{
			if(empty($username))
			{
				$message .= '<p><label>username is required</label></p>';
			}
			if(empty($loginname))
			{
				$message .= '<p><label>Loginname is required</label></p>';
			}
			if(empty($password))
			{
				$message .= '<p><label>Password is required</label></p>';
			}
			else
			{
				if($password != $_POST['confirm_password'])
				{
					$message .= '<p><label>Password not match</label></p>';
				}
			}
			if($message == '')
			{
				$data = array(
					':loginname'	=>	$loginname,
					':username'		=>	$username,
					':userlevel'	=>	$userlevel,
					':areaid'		=>	$areaid,
					':password'		=>	password_hash($password, PASSWORD_DEFAULT)
				);

				$query = "
				INSERT INTO user 
				(loginname,username, password,userlevel,areaid) 
				VALUES (:loginname, :username, :password, :userlevel, :areaid)
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$message = "<label>Registration Completed</label>";
				}
			}
		}
	}
}

?>

<html>  
    <head>  
        <title>Chat Application using PHP Ajax Jquery</title>  
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
			<br />
			
			<h3 align="center">Chat Application using PHP Ajax Jquery</a></h3><br />
			<br />
			<div class="panel panel-default">
  				<div class="panel-heading">Chat Application Register</div>
				<div class="panel-body">
					<form method="post">
						<span class="text-danger"><?php echo $message; ?></span>
						<div class="form-group">
							<label>Enter Department / Cafe Name</label>
							<input type="text" name="username" class="form-control" />
						</div>
						<div class="form-group">
							<label>Enter Login Name</label>
							<input type="text" name="loginname" class="form-control" />
						</div>
						<div class="form-group">
							<label>Enter Password</label>
							<input type="password" name="password" class="form-control" />
						</div>
						<div class="form-group">
							<label>Re-enter Password</label>
							<input type="password" name="confirm_password" class="form-control" />
						</div>

				<div class="form-group">
					<label class="form-check-label" for="exampleCheck1">Area ID</label>
						<select class="form-control" name="areaid">
							<option value="1">Head Office</option>
							<option value="2">Group A</option>
							<option value="3">Group B</option>
							<option value="4">Group C</option>
					</select>
				</div>


				<div class="form-group">
						<label class="form-check-label" for="exampleCheck1">User Level</label>
							<select class="form-control" name="userlevel">
								<option value="Master">Master</option>
								<option value="Admin">Commissary</option>
								<option value="Admin1">Production</option>
								<option value="User">User</option>
								<option value="User1">User1</option>
								<option value="User2">User2</option>
							</select>
				</div>
				<br><br>
						<div class="form-group">
							<input type="submit" name="register" class="btn btn-info" value="Register" />
						</div>

						<div align="center">
							<a href="login.php">Login</a>
						</div>
					</form>
				</div>
			</div>
		</div>
    </body>  
</html>
