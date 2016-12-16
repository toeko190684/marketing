<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "managedepartemen" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("departemen","departemen_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Departemen Id : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "managedepartemen" and $act == "add"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$departemen_id = $_POST['departemen_id'];	
				$departemen_name = $_POST['departemen_name'];
				$aktif = $_POST['aktif'];
				
				$data = array("departemen_id" => $departemen_id,"departemen_name" => $departemen_name,"aktif" => $aktif);
				$crud->insert("departemen",$data);
				$_SESSION['message'] = $crud->message_success("Departemen Id : ".$departemen_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "managedepartemen" and $act == "update"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$departemen_id = $_POST['departemen_id'];	
				$departemen_name = $_POST['departemen_name'];
				$approval1 = $_POST['approval1'];
				$approval2 = $_POST['approval2'];
				$aktif = $_POST['aktif'];
				
				$data = array("departemen_name" => $departemen_name,"approval1" => $approval1, 
							  "approval2" => $approval2,"aktif" => $aktif);
				
				$crud->update("departemen",$data,"departemen_id = '$departemen_id' ");
				$_SESSION['message'] = $crud->message_success("Departemen Id : ".$departemen_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>