<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "additionalbudget" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$_GET['id']."'");
				if($cek[0]['posting'] == 0){				
					$sql = $crud->delete("additional_budget","budget_id='".$_GET['id']."' and additional_id = '".$_GET['addid']."'");			
					$_SESSION['message'] = $crud->message_success("Additional ID : ".$_GET['id']." has been deleted successfully !!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Additional Id : ".$_GET['addid']." can't delete, because budget Id : ".$_GET['id']." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "additionalbudget" and $act == "add"){
		$budget_prefix = $_POST['budget_prefix'];
		$budget_id = $_POST['budget_id'];
		$additional_date = $_POST['additional_date'];
		$class_id = $_POST['class_id'];
		$description = $_POST['description'];
		$total = str_replace(",","",$_POST['total']);
		$yyyy_mm = substr($_POST['additional_date'],0,7);
		
		
		$cari_budget = $crud->fetch("v_additionalbudget_number","","budget_id='".$budget_id."'");
		
		if(count($cari_budget) <= 0){
			$additional_id = "0001".$budget_prefix;
		}else{ 
			$additional_id = $cari_budget[0]['additional_id'];				
		}
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
				if($cek[0]['posting'] == 0){	
					$data = array("additional_id" => $additional_id, 
								  "additional_date" => $additional_date,
								  "budget_id" => $budget_id,
								  "class_id" => $class_id,
								  "description" => $description,
								  "total" => $total,
								  "created_by" => $_SESSION['username']);
					$sql = $crud->insert("additional_budget",$data);					
					
					$_SESSION['message'] = $crud->message_success("Additional ID : ".$additional_id." has been added successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Additional Id : ".$additional_id." can't insert, because budget Id : ".$budget_id." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "additionalbudget" and $act == "update"){
		$additional_id = $_POST['additional_id'];
		$budget_id = $_POST['budget_id'];
		$additional_date = $_POST['additional_date'];
		$class_id = $_POST['class_id'];
		$description = $_POST['description'];
		$total = str_replace(",","",$_POST['total']);
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
				if($cek[0]['posting'] == 0){
					$data = array("additional_date" => $additional_date,
								  "budget_id" => $budget_id,
								  "class_id" => $class_id,
								  "description" => $description,
								  "total" => $total,
								  "update_by" => $_SESSION['username'],
								  "update_date" => date('Y-m-d H:m:s'));
					
					$crud->update("additional_budget",$data,"budget_id='".$budget_id."' and additional_id='".$additional_id."'");
					$_SESSION['message'] = $crud->message_success("Additional Id : ".$additional_id." has been updated successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Additional Id : ".$additional_id." can't update, because budget Id : ".$budget_id." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>