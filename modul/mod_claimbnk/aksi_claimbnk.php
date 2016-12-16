<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "claimbnk" and $act == "update"){
		$claim_number_system = $_POST['claim_number_system'];
		$distributor_id = $_POST['distributor_id'];
		$coa = $_POST['account_id'];
		$vendor_id = $_POST['vendor_id'];
		$deskripsi = $_POST['deskripsi'];
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$data = array("distributor_id" => $distributor_id,
							  "coa" => $coa,
							  "vendor_id" => $vendor_id,
							  "deskripsi" => $deskripsi);
				
				$crud->update("claim_bnk",$data,"claim_number_system='".$claim_number_system."'");
				$_SESSION['message'] = $crud->message_success("Claim Number System : ".$claim_number_system." has been updated successfully!!");				
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
		$claim_number_system = $_GET['id'];
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$data = array("status" => "rejected",
							  "approve_by" => $_SESSION['username'],
							  "tgl_approve" => date("y-m-d H:m:s"));
				
				$crud->update("claim_bnk",$data,"claim_number_system='".$claim_number_system."'");
				$_SESSION['message'] = $crud->message_success("Claim Number System : ".$claim_number_system." has been rejected successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}


?>