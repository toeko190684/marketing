<?php
	session_start();
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>WebApp - MKI</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/bootstrap-3.3.6/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/bootstrap-3.3.6/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/bootstrap-3.3.6/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="bootstrap/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/bootstrap-3.3.6/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-6">
				<?php 
					if($_SESSION['message'] <>""){
						echo $_SESSION['message'];
						$_SESSION['message'] = "";
					}
				?>
			</div>
		</div>
		<div class="row">
			<form  method="post" action="cek_login.php" class="form-signin" >
				<h2 class="form-signin-heading">Please sign in</h2>
				<input type="text" id="inputEmail" name="username" class="form-control" placeholder="Username" required autofocus><br>
				<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" >
				<div class="checkbox">
				<label>
					<input type="checkbox" value="remember-me"> Remember me
				</label>
				</div>
				<input type="submit" name="signin" class="btn btn-lg btn-primary btn-block" value="Sign in">
			</form>
		</div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/bootstrap-3.3.6/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
