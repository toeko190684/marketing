<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "/../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "area" and $act == "del"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("area","area_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Area Id : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "area" and $act == "add"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$area_id = $_POST['area_id'];	
				$area_name = $_POST['area_name'];
				
				$data = array("area_id" => $area_id,"area_name" => $area_name);
				$crud->insert("area",$data);
				$_SESSION['message'] = $crud->message_success("Area Id : ".$area_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "area" and $act == "update"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$area_id = $_POST['area_id'];	
				$area_name = $_POST['area_name'];

				$data = array("area_name" => $area_name);
				
				$crud->update("area",$data,"area_id = '$area_id' ");
				$_SESSION['message'] = $crud->message_success("Area Id : ".$area_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>