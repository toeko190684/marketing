<?php 
	session_start();
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	include "../../config/koneksi.php";
	
	$r = $_GET['r'];
	$mod = $_GET['mod'];
	$act = $_GET['act'];
	$id = $_GET['id'];
	
	if($r == "groupuser" and $act == "del"){
		
		$group_id = $_GET['id'];
		try{
			$crud->delete("group_user","group_id = '$group_id' ");
			$_SESSION['message'] = $crud->message_success("Group ID : ".$group_id." has been deleted successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	if($r == "groupuser" and $act == "add"){
		
		$group_name = $_POST['group_name'];	
		
		try{
			$data = array("group_name" => $group_name);
			$crud->insert("group_user",$data);
			$_SESSION['message'] = $crud->message_success("Group  : ".$group_name." has been saved successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=$r&mod=$mod");
	}
	
	if($r == "groupuser" and $act == "update"){
		
		$group_id = $_POST['group_id'];
		$group_name = $_POST['group_name'];
		
		try{
			$data = array("group_name" => $group_name);
			$crud->update("group_user",$data,"group_id = '$group_id' ");
			$_SESSION['message'] = $crud->message_success("Group ID : ".$group_id." has been updated successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		header("location:../../user.php?r=$r&mod=$mod");
		
	}
	
	if($r == "groupuser" and $act == "module"){
		$xx = $r;
		$group_id = $_POST['group_id'];
		$module_id = $_POST['module_id'];
		$c = $_POST['c'];
		$r = $_POST['r'];
		$u = $_POST['u'];
		$d = $_POST['d'];
		
		try{
			$data = array("group_id" => $group_id,"module_id" => $module_id, "c" => $c,"r" => $r, "u" =>$u, "d" => $d);
			$crud->insert("group_modul",$data);
			$_SESSION['message'] = $crud->message_success("Modul  : ".$module_id." has been added to group : ".$group_id." successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=".$xx."&mod=".$mod."&act=module&id=".$group_id);
	}
	
	if($r == "groupuser" and $act == "del_module"){
		$xx = $r;
		$group_id = $_GET['id'];
		$module_id = $_GET['id2'];
		
		try{
			$crud->delete("group_modul","group_id = '$group_id' and module_id='$module_id' ");
			$_SESSION['message'] = $crud->message_success("Modul  : ".$module_id." has been deleted from group : ".$group_id." successfully !!");
		}catch(exception $e){
			$_SESSION['message'] = $crud->message_error($e->getmessage());
		}
		
		header("location:../../user.php?r=".$xx."&mod=".$mod."&act=module&id=".$group_id);
	}


?>