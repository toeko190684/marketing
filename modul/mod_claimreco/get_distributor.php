<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>

	<?php 
		$data = $crud->fetch("distributor","distributor_id,distributor_name","distributor_id='".$_POST['id']."'");
		echo trim($data[0]['distributor_name']);
	?>