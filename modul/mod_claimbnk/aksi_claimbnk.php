<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	require_once( "../../config/koneksi.php");
	require_once( "../../config/sqlsvr_connect.php");
	
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
		$claim_number_system = $_GET['id'];
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				//cari tanggal dari claim number system terseubt
				$claim = $crud->fetch("v_claim_bnk_header","claim_date","claim_number_system = '".$claim_number_system."'");
								
				//cari dulu yang nomor maksimal di sql server sesuai dengan tanggal claim claim
				$journal = $crud_sql->fetch("ap_journal","max(journal_id)as journal_id","journal_date='2016-12-18'");
				
				if($journal[0]['journal_id'] == ""){
					$journal_id = str_replace("-","",$claim[0]['claim_date'])."0001";
				}else{
					$journal_id = $journal[0]['journal_id']+1;
				}
				
				
				/*
					update field journal_id dengan journal_id yang kita generate tadi.
				*/
				
				$data = array("approve_by" => $_SESSION['username'],"status" => "approved","journal_id" => $journal_id);
				$crud->update("claim_bnk",$data,"claim_number_system = '".$claim_number_system."' and status not in('approved','rejected')");
				
				
				$_SESSION['message'] = $crud->message_success("Claim Number System : ".$claim_number_system." has been rejected successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
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
				
				$crud->update("claim_bnk",$data,"claim_number_system='".$claim_number_system."' and status not in('approved','rejected')");
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