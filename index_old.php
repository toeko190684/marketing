 <?php
 session_start();
/*======================================================
 created by toeko triyanto
 this file is used to login user
======================================================*/


error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

?>

<!doctype html>
<html lang="en">
<head>
	<title>Login - SKProject - PT Morinaga Kino Indonesia</title>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-3.3.6/css/bootstrap-theme.min.css">
	<script language="javascript" type="script/javascript" src="assets/bootstrap-3.3.6/js/bootstrap.min.js" ></script>
</head>
<body>
	<div class="container">
		<div class="row"
			<div class="col-xs-6 col-sm-4" style="text-align:center;margin:50px">
				<img src='images/logo_company.jpg' width='300px'>
			</div>
		<div>		
		<div class="row">
			<div class="col-md-8">
			<?php 
				// menampilkan pesan session message
				if($_SESSION['message'] != ""){
					echo $_SESSION['message'];
					$_SESSION['message'] = "";
				}
			?> 
			</div>
		</div>
		<div class="row">			
			<div class="col-md-1"></div>
			<div class="col-md-5">
				<form class="form-horizontal" method="post" action=<?php echo "cek_login.php"; ?>>
					<fieldset><legend>SKProject User Login</legend>
					<div class="form-group">
						<label for="username" class="col-sm-2 control-label">Username</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="username" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-6">
							<input type="password" class="form-control" name="password" placeholder="Password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<div class="checkbox">
								<label>
								<input type="checkbox"> Remember me
								</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-primary">Sign in</button>
							<button type="reset" class="btn btn-danger">Cancel</button>
						</div>
					</div>
					</fieldset>
				</form>
			</div>
			<div class='col-md-4' style="margin:50px">
			    <h4>Visi & Misi</h4>
				<blockquote><em>"Menciptakan produk makanan & minuman yang enak, menyenangkan dan sehat..!"</em></blockquote>	
				<br><h4>Support</h4>
				<blockquote><em>"Jika ada problem IT silahkan kontak ke IT support"</em></blockquote>
			</div>
		</div>
		<div class="row" style="text-align:center">
			<pre>Copyright <?php echo date('Y') ?><br>Version Control : 2.0 (Last Update : 19 Juni 2016) </pre>
		</div>
	</div>		
</body>
</html>