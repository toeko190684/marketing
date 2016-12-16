<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "budgetapproval" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				$sql = $crud->delete("budget","budget_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been deleted successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	
	if($module == "budgetapproval" and $act == "update"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$budget_id = $_POST['budget_id'];	
				$start_date = $_POST['start_date'];
				$end_date = $_POST['end_date'];

				$data = array("start_date" => $start_date,"end_date" => $end_date);
				
				$crud->update("budget",$data,"budget_id = '$budget_id' ");
				$_SESSION['message'] = $crud->message_success("budget Id : ".$budget_id." has been updated successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}
	
	
	if($module == "budgetapproval" and $act == "approval"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$budget_id = $_GET['id'];
				
				//cari budget id
				$data = $crud->fetch("budget","","budget_id='".$budget_id."'");
				
				if($data[0][approval1] == ""){
					$data = array("approval1" => $_SESSION['username'],"approval1_date"=>date('Y-m-d H:m:s'));
					
					$crud->update("budget",$data,"budget_id = '$budget_id' ");
					$_SESSION['message'] = $crud->message_success("Budget Id : ".$budget_id." has been approved successfully!!");
				}else{
					$_SESSION['message'] = $crud->message_error("Failed to Approve ...!<br>Budget Id : ".$budget_id." Has been approved by ".$data[0]['approval1']);
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