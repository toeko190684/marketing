<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "claimbnk" and $act == "update"){
		$additional_id = $_POST['additional_id'];
		$budget_id = $_POST['budget_id'];
		$additional_date = $_POST['additional_date'];
		$account_id = $_POST['account_id'];
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
								  "account_id" => $account_id,
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
	
	if($module == "claimbnk" and $act == "approve"){
		$_SESSION['message'] = $crud->message_success("Demo version only : </br>Claim Id : ".$_GET['clid']." has been approved successfully!!");
		header("location:../../user.php?r=$module&mod=".$mod);	
	}
	
	if($module == "claimbnk" and $act == "reject"){
		$_SESSION['message'] = $crud->message_success("Demo version only : </br>Claim Id : ".$_GET['clid']." has been rejected successfully!!");
		header("location:../../user.php?r=$module&mod=".$mod);	
	}


?>