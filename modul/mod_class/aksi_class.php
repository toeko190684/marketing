<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "class" and $act == "del"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("class","promotype_id = '".$_GET['id']."' and class_id='".$_GET['id2']."'");			
				$_SESSION['message'] = $crud->message_success("Class Id : ".$_GET['id2']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);
	}
	
	if($module == "class" and $act == "add"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){	
			try{
				$class_id = $_POST['class_id'];	
				$class_name = $_POST['class_name'];
				
				$data = array("class_id" => $class_id,"class_name" => $class_name,"promotype_id" => $_GET['id']);
				$crud->insert("class",$data);
				$_SESSION['message'] = $crud->message_success("Class Id : ".$class_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);
	}
	
	if($module == "class" and $act == "update"){
		$data = $crud->fetch("v_user_module","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$class_id = $_POST['class_id'];	
				$class_name = $_POST['class_name'];

				$data = array("class_name" => $class_name);
				
				$crud->update("class",$data,"class_id = '$class_id'");
				$_SESSION['message'] = $crud->message_success("Class Id : ".$class_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&id=".$_GET['id']."&mod=".$mod);		
	}


?>