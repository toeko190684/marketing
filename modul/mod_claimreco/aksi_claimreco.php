<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "claimreco" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				//cek jika claim sudah di approve tidak bisa di delete
				$cek = $crud->fetch("claim_reco","status","claim_id = '".$_GET['id']."'");	
				if(strtoupper($cek[0]['status'])=="PENDING"){
					$sql = $crud->delete("claim_reco","claim_id = '".$_GET['id']."'");			
					$_SESSION['message'] = $crud->message_success("Claim ID : ".$_GET['id']." has been deleted successfully !!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Claim ID : ".$_GET['id']." cannot delete, because status is ".$cek[0]['status']);
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "claimreco" and $act == "add"){
		$claim_prefix = $_POST['claim_prefix'];
		$claim_date = $_POST['claim_date'];
		$claim_number_dist = $_POST['claim_number_dist'];
		$distributor_id = $_POST['distributor_id'];
		$reco_id = $_POST['reco_id'];
		$po_so_number = $_POST['po_so_number'];
		$ppn = $_POST['ppn'];
		$pph = $_POST['pph'];
		$nomor_faktur_pajak = $_POST['nomor_faktur_pajak'];
		$deskripsi = $_POST['deskripsi'];
		$claim_approved_ammount = str_replace(",","",$_POST['claim_approved_ammount']);
		$account_id = $_POST['account_id'];
		$vendor_id = $_POST['vendor_id'];
		$status = $_POST['status'];
		
		$yyyy_mm = substr($_POST['claim_date'],0,7);
				
		$cari_claim = $crud->fetch("v_claimreco_number","","reco_id='".$reco_id."'");
		
		if(count($cari_claim) <= 0){
			$claim_id = "0001".$claim_prefix;
		}else{ 
			$claim_id = $cari_claim[0]['claim_id'];				
		}
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("v_reco_budget","outstanding","reco_id='".$reco_id."'");
				if($cek[0]['outstanding'] >= $claim_approved_ammount){	
					$data = array("claim_id" => $claim_id, 
								  "claim_date" => $claim_date,
								  "claim_number_dist" => $claim_number_dist,
								  "distributor_id" => $distributor_id,
								  "reco_id" => $reco_id,
								  "po_so_number" => $po_so_number,
								  "ppn" => $ppn,
								  "pph" => $pph,
								  "nomor_faktur_pajak" => $nomor_faktur_pajak,
								  "deskripsi" => $description,
								  "claim_approved_ammount" => $claim_approved_ammount,
								  "account_id" => $account_id,
								  "vendor_id" => $vendor_id,
								  "status" => $status,								  
								  "created_by" => $_SESSION['username'],
								  "created_date" => date('Y-m-d H:m:s'));
					$sql = $crud->insert("claim_reco",$data);					
					
					$_SESSION['message'] = $crud->message_success("Claim ID : ".$claim_id." has been added successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Claim Id : ".$claim_id." can't insert, because reco Id : ".$reco_id." is not enough!");
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
	
	if($module == "claimreco" and $act == "approve"){
		$_SESSION['message'] = $crud->message_success("Demo version only : </br>Claim Id : ".$_GET['clid']." has been approved successfully!!");
		header("location:../../user.php?r=$module&mod=".$mod);	
	}
	
	if($module == "claimreco" and $act == "reject"){
		$_SESSION['message'] = $crud->message_success("Demo version only : </br>Claim Id : ".$_GET['clid']." has been rejected successfully!!");
		header("location:../../user.php?r=$module&mod=".$mod);	
	}


?>