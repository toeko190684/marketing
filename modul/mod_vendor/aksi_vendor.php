<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "vendor" and $act == "del"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("vendor","vendor_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Vendor Id : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "vendor" and $act == "add"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$vendor_id = $_POST['vendor_id'];	
				$vendor_name = $_POST['vendor_name'];
				$ap_account_type = $_POST['ap_account_type'];
				$ap_account_id = $_POST['ap_account_id'];
				
				$data = array("vendor_id" => $vendor_id,"vendor_name" => $vendor_name,
							"ap_account_type" => $ap_account_type, "ap_account_id" => $ap_account_id);
				$crud->insert("vendor",$data);
				$_SESSION['message'] = $crud->message_success("Vendor Id : ".$vendor_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "vendor" and $act == "update"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$vendor_id = $_POST['vendor_id'];	
				$vendor_name = $_POST['vendor_name'];
				$ap_account_type = $_POST['ap_account_type'];
				$ap_account_id = $_POST['ap_account_id'];
				
				$data = array("vendor_name" => $vendor_name,"ap_account_type" => $ap_account_type, "ap_account_id" => $ap_account_id);
				
				$crud->update("vendor",$data,"vendor_id = '$vendor_id' ");
				$_SESSION['message'] = $crud->message_success("Vendor Id : ".$vendor_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>