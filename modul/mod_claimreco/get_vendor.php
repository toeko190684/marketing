<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";

	$data = $crud->fetch("vendor","","vendor_id='".$_POST['id']."'");
	$tanda = array("[","]");
	echo str_replace($tanda,"",json_encode($data));
	
?>