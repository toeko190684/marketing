<?php 
	session_start();
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	include "../../config/koneksi.php";
	
	$r = $_GET['r'];
	$mod = $_GET['mod'];
	$act = $_GET['act'];
	$id = $_GET['id'];
	
	if($r == "managemodule" and $act == "del"){
		
		$module_id = $_GET['id'];
		try{
			$crud->delete("module","module_id = '$module_id' ");
			$_SESSION['message'] = $crud->message_success("Module ID : ".$module_id." has been deleted successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	if($r == "managemodule" and $act == "add"){
		
		$module_name = $_POST['module_name'];	
		$link = $_POST['link'];
		$menu_id = $_POST['menu_id'];
		$urut = $_POST['urut'];
		$display = $_POST['display'];
		
		try{
			$data = array("module_name" => $module_name,"link" => $link,"menu_id" => $menu_id,"urut" => $urut,"display"=>$display);
			$crud->insert("module",$data);
			$_SESSION['message'] = $crud->message_success("Module : ".$module_name." has been saved successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	if($r == "managemodule" and $act == "update"){
		
		$module_id = $_POST['module_id'];
		$module_name = $_POST['module_name'];	
		$link = $_POST['link'];	
		$menu_id = $_POST['menu_id'];
		$urut = $_POST['urut'];
		$display = $_POST['display'];
		
		try{
			$data = array("module_id" => $module_id,"module_name" => $module_name,"link" => $link,"menu_id" => $menu_id,"urut" => $urut,"display"=>$display);
			$crud->update("module",$data,"module_id = '$module_id' ");
			$_SESSION['message'] = $crud->message_success("Module ID : ".$module_id." has been updated successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
		
	}


?>