<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "account" and $act == "del"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("account","account_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Account Id : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "account" and $act == "add"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$account_id = $_POST['account_id'];	
				$account_name = $_POST['account_name'];
				$fix_var = $_POST['fix_var'];
				$operational_promotion = $_POST['operational_promotion'];
				
				$data = array("departemen_id"=>$_SESSION['departemen_id'],"account_id" => $account_id,
							  "account_name" => $account_name, "fix_var" => $fix_var, 
							  "operational_promotion" => $operational_promotion);
				$crud->insert("account",$data);
				$_SESSION['message'] = $crud->message_success("Account Id : ".$account_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "account" and $act == "update"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$account_id = $_POST['account_id'];	
				$account_name = $_POST['account_name'];
				$fix_var = $_POST['fix_var'];
				$operational_promotion = $_POST['operational_promotion'];
				
				$data = array("departemen_id"=>$_SESSION['departemen_id'],"account_name" => $account_name,
							  "fix_var" => $fix_var,"operational_promotion" => $operational_promotion);
				
				$crud->update("account",$data,"account_id = '$account_id' ");
				$_SESSION['message'] = $crud->message_success("Account Id : ".$account_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>