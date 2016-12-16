<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	require_once("../../config/koneksi.php");
	
	$budgetid = $_GET['buid'];
	$classid = $_GET['classid'];
	
	$data = $crud->fetch("v_detail_budget","outstanding_budget","budget_id = '".$budgetid."' and class_id='".$classid."'");
	echo number_format($data[0]['outstanding_budget'],0,'.',',');

?>