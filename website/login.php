<?php
	$message = "";
	if (isset($_POST['submit'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$status = login($username, $password);
		if ($status) {
			session_start();
			$_SESSION['username'] = "sousdey";
			header("Location: evaluation-list.php");
		} else {
			$message = "<p class='text-center text-danger'>Incorrect Password or username</p>";
		}
	}

	function login($username, $password)
	{
		$status = false;
		$user = array(
			"name" => "sousdey",
			"password" => "sousdey@12345"
		);
		if ($username == $user['name'] && $password == $user['password']) {
			$status = true;
		}
		return $status;
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="icon" type="image/png" href="images/icon.png" />
	<style type="text/css">
		.bg-saart{
			background: #e9e9e9;
		}
		.login-logo{
			text-align: center;
			padding-bottom: 30px;
		}
		.login-tell{
			text-align: center;
			padding-bottom: 30px;
			font-size: 25px !important;
		}
		.login-content{
			padding:40px;
		}
		.logo-blackbg{
			width: 300px;
		}
		.btn-blog {
			padding-bottom: 15px;
		}
		.divider-login {
		    height: 1px;
		    text-align: center;
		    position: relative;
		    border-bottom: 1px solid #e9e9e9;
		    margin: 30px auto;
		}
		.divider-label {
		    display: inline-block;
		    position: absolute;
		    margin: 0 0 0 -18px;
		    top: -10px;
		    padding: 0 10px;
		    font-size: 14px;
		    text-transform: uppercase;
		    font-weight: 700;
		    color: #91979f;
		    background: #fff;
		}
		.input-login input {
		    color: #000;
		    background-size: 30px;
		    background-position: 5px center;
		    background-repeat: no-repeat;
		    padding-left: 40px;
		    font-size: 20px;
		    height: 40px;
		}
		input.username {
			background-image: url(images/username-icon.png);
		}
		input.password {
			background-image: url(images/password-icon.png);
		}

	</style>
</head>
<body class="bg-saart">
	<div class="container">
		<br><br>
		<div class="col-md-6 col-md-offset-3">
			<!-- <div class="login-logo">
				<img src="images/logo-blackbg.png" class="logo-blackbg">
			</div> -->
			<div class="panel panel-default">
				<div class="panel-body login-content">
					<h4 class="login-tell">Login Form</h4>
					<?php echo @$message?>
					<form role="form" action="" method="POST">
						<div class="form-group input-login">
							<input required class="form-control username" name="username" placeholder="Username" type="username">
						</div>
						<div class="form-group input-login">
							<input required class="form-control password" name="password" placeholder="Password" type="password">
						</div>
						<div class="btn-blog">
							<button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</body>
</html>