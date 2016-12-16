<?php
/*
created by toeko triyanto
index.php for security application 
*/
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

require_once("config/koneksi.php");

if($_POST['departemen_id'] <> ""){
	$data = $crud->fetch("departemen","","departemen_id='".$_POST['departemen_id']."'");
	$_SESSION['departemen_id'] = $data[0]['departemen_id'];
	$_SESSION['departemen_name'] = $data[0]['departemen_name'];
}

$data = $crud->fetch("user","","username='".$_SESSION['username']."' and password='".$_SESSION['password']."'");

if(count($data) <= 0  ){
	$_SESSION['message'] = $crud->message_error("Access Denied ! You must login to access the system!!");
	header("location:index.php");
}else{ 
?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>Marketing SKProject</title>
		<link href='assets/bootstrap-3.3.6/css/bootstrap.min.css' rel='stylesheet'>
		<link href='assets/bootstrap-3.3.6/css/bootstrap-theme.min.css' rel='stylesheet'>
		<link href="assets/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
		
		<script src='assets/jquery-1.10.2.js'></script>
		<script src='assets/jquery-2.1.4.min.js'></script>
		<script src='assets/bootstrap-3.3.6/js/bootstrap.min.js'></script>
		<script type ="text/javascript" src="assets/datepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<script type ="text/javascript" src="assets/datepicker/js/locales/bootstrap-datetimepicker.id.js" charset="UTf-8"></script>
		<script type="text/javascript">
			$('.form_date').datetimepicker({
				language:  'id',
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 2,
				minView: 2,
				forceParse: 0
			});
		</script> 		
	</head>
	<body>
		<?php include "menu.php"; ?>
		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<?php 
					$data = $crud->fetch("module","","module_id='".$_GET['mod']."'");
				?>
				<ul class="breadcrumb">
				  <li><a href="#">Home</a> <span class="divider"></span></li>
				  <li><a href="index.php?r=<?php echo $_GET['r'];?>&mod=<?php echo $_GET['mod']; ?>"><?php echo $data[0]['module_name']; ?></a>
				   <span class="divider"></span></li>
				  <li class="active">Data</li>
				</ul>				
			</div>
				
			<div class="col-md-12 col-lg-12 col-sm-12">				
				<?php 
					// menampilkan pesan session message
					if($_SESSION['message'] != ""){
						echo $_SESSION['message'];
						$_SESSION['message'] = "";
					}
					
					include "content.php"; 
				?> 
			</div>
			<div class="col-md-12 col-lg-12 col-sm-12" id='footer'>
					<br><BR><bR><pre style='text-align:center'>Copyright <?php echo date('Y');?> PT Morinaga Kino Indonesia</pre>
			</div>
			</div>
		</div>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		
	</body>
	</html>
	
<?php 
} 
?>
