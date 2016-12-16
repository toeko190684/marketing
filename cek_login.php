<?php

	/*======================================================
	created by toeko triyanto
	cek login.php adalah file untuk mengecek login dna 
	menuliskan session yang akan digunakan untuk masuk 
	keseluruh aplikasi
	=========================================================*/

	session_start();

	require_once("config/koneksi.php");
	$username = $_POST['username'];
	$password = md5($_POST['username']."@".$_POST['password']);


	$data = $crud->fetch("user","","username='".$username."' and password='".$password."'");

	$url = 'http://182.23.88.183:2001/marketing/';


	if(count($data) >0 ){
		$_SESSION['username'] = $data[0]['username'];
		$_SESSION['password'] = $data[0]['password'];
		
		//cari departemen dan group default
		$dep = $crud->fetch("v_user_data","","username='".$data[0]['username']."' and cek = 1");
		$_SESSION['group_id'] = $dep[0]['group_id'];
		$_SESSION['departemen_id'] = $dep[0]['departemen_id'];
		$_SESSION['departemen_name'] = $dep[0]['departemen_name'];
		$_SESSION['year'] = date('Y');
		
		header("location:user.php?r=home");
	}else{
		$_SESSION['message'] = $_SESSION['message'] = $crud->message_error("Username or Password is incorrect..!!");
		header("location:index.php");
	}
?>