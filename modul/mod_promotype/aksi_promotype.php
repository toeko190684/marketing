<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "promotype" and $act == "del"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("promo_type","grouppromo_id = '".$_GET['id']."' and promotype_id='".$_GET['id2']."'");			
				$_SESSION['message'] = $crud->message_success("Promo Type Id : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);
	}
	
	if($module == "promotype" and $act == "add"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$promotype_id = $_POST['promotype_id'];	
				$promotype_name = $_POST['promotype_name'];
				
				$data = array("promotype_id" => $promotype_id,"promotype_name" => $promotype_name,"grouppromo_id" => $_GET['id']);
				$crud->insert("promo_type",$data);
				$_SESSION['message'] = $crud->message_success("Promo Type Id : ".$promotype_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);
	}
	
	if($module == "promotype" and $act == "update"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$promotype_id = $_POST['promotype_id'];	
				$promotype_name = $_POST['promotype_name'];

				$data = array("promotype_name" => $promotype_name);
				
				$crud->update("promo_type",$data,"grouppromo_id = '$_GET[id]' and promotype_id='$promotype_id' ");
				$_SESSION['message'] = $crud->message_success("Promo Type Id : ".$promotype_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);		
	}


?>