<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "detailbudget" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$_GET['id']."' and posting=0 and status='approved'");
				if(count($cek) > 0){		
					$sql = $crud->delete("detail_budget","budget_id = '".$_GET['id']."' and class_id='".$_GET['classid']."'");			
					$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." and Class Id : ".$_GET['classid']." has been deleted successfully !!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Class Id : ".$_GET['classid']." can't delete, because budget Id : ".$_GET['id']." has been closed or rejected!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod."&id=".$_GET['id']);
	}
	
	if($module == "detailbudget" and $act == "add"){
		$budget_id = $_POST['budget_id'];
		$class_id = $_POST['class_id'];
		$total = str_replace(",","",$_POST['total']);
				
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting,status","budget_id='".$budget_id."' and posting=0 and status='approved'");
				if(count($cek) > 0){				
					$data = array("budget_id" => $budget_id, "class_id" => $class_id, "total" => $total,
								  "created_by" => $_SESSION['username']);
					$sql = $crud->insert("detail_budget",$data);
					
					$_SESSION['message'] = $crud->message_success("Budget ID : ".$budget_id." and class Id : ".$class_id." has been added successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Class Id : ".$class_id." can't insert, because budget Id : ".$budget_id." has been closed or rejected!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod."&id=".$budget_id);
	}
	
	if($module == "detailbudget" and $act == "update"){
		$budget_id = $_POST['budget_id'];	
		$class_id = $_POST['class_id'];
		$total = str_replace(",","",$_POST['total']);
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{				
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
				if($cek[0]['posting'] == 0){
					$data = array("class_id" => $class_id,"total" => $total);
					
					$crud->update("detail_budget",$data,"budget_id = '$budget_id' and class_id='".$class_id."' ");
					$_SESSION['message'] = $crud->message_success("Budget Id : ".$budget_id." and class Id : ".$class_id." has been updated successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Class Id : ".$class_id." can't update, because budget Id : ".$budget_id." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod."&id=".$budget_id);		
	}


?>