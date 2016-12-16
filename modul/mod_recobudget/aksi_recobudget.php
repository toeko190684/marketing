<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "recobudget" and $act == "del"){
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
	
	if($module == "recobudget" and $act == "add"){
		$reco_prefix = $_POST['reco_prefix'];
		$reco_id = $_POST['reco_id'];
		$reco_date = $_POST['reco_date'];
		
		//mencari area dari distributor yang ada 
		$distributor_id = $_POST['distributor_id'];
		$dist = $crud->fetch("distributor","area_id"," distributor_id='".$distributor_id."'");
		$area_id = $dist[0]['area_id'];		
		
		//mencari group promo id dan promotype dari class id yang ada 
		$class_id = $_POST['class_id'];
		$vclass = $crud->fetch("v_class","","class_id='".$class_id."'");
		$promotype_id = $vclass[0]['promotype_id'];
		$grouppromo_id = $vclass[0]['grouppromo_id'];		
		
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$groupoutlet_id = $_POST['groupoutlet_id'];
		$sales_target = $_POST['sales_target'];
		$budget_id = $_POST['budget_id'];
		$account_id = $_POST['account_id'];
		$outstanding = str_replace(",","",$_POST['outstanding_budget']);
		$claimtradeoff = $_POST['claimtradeoff'];
		$transaksi = $_POST['transaksi'];
		$description = $_POST['description'];
		$total = str_replace(",","",$_POST['total']);
		$total_allow_used = str_replace(",","",$_POST['total_allow_used']);
		$complete = $_POST['complete'];
		$yyyy_mm = substr($_POST['reco_date'],0,7);
		
		
		$cari_budget = $crud->fetch("v_recobudget_number","","budget_id='".$budget_id."'");
		
		if(count($cari_budget) <= 0){
			$reco_id = "0001".$reco_prefix;
		}else{ 
			$reco_id = $cari_budget[0]['reco_id'];				
		}
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				if($complete == ""){
						$data = array("reco_id" => $reco_id,
									  "reco_date" => $reco_date,
									  "area_id" => $area_id,
									  "distributor_id" => $distributor_id,
									  "grouppromo_id" => $grouppromo_id,
									  "promotype_id" => $promotype_id,
									  "class_id" => $class_id,
									  "start_date" => $start_date,
									  "end_date" => $end_date,
									  "groupoutlet_id" => $groupoutlet_id,
									  "sales_target" => $sales_target,
									  "budget_id" => $budget_id,
									  "account_id" => $account_id,
									  "claimtradeoff" => $claimtradeoff,
									  "transaksi" => $transaksi,
									  "description" => $description,
									  "total" => $total,
									  "total_allow_used" => $total_allow_used,
									  "created_by" => $_SESSION['username'],
									  "created_date" => date('Y-m-d H:m:s'),
									  "update_by" => $_SESSION['username'],
									  "update_date" => date('Y-m-d H:m:s'));
					}else{
						$data = array("reco_id" => $reco_id,
									  "reco_date" => $reco_date,
									  "area_id" => $area_id,
									  "distributor_id" => $distributor_id,
									  "grouppromo_id" => $grouppromo_id,
									  "promotype_id" => $promotype_id,
									  "class_id" => $class_id,
									  "start_date" => $start_date,
									  "end_date" => $end_date,
									  "groupoutlet_id" => $groupoutlet_id,
									  "sales_target" => $sales_target,
									  "budget_id" => $budget_id,
									  "account_id" => $account_id,
									  "claimtradeoff" => $claimtradeoff,
									  "transaksi" => $transaksi,
									  "description" => $description,
									  "total" => $total,
									  "total_allow_used" => $total_allow_used,
									  "completed" => $complete,
									  "completed_date" => date('Y-m-d H:m:s'),
									  "created_by" => $_SESSION['username'],
									  "created_date" => date('Y-m-d H:m:s'),
									  "update_by" => $_SESSION['username'],
									  "update_date" => date('Y-m-d H:m:s'));
					}
					$sql = $crud->insert("reco_budget",$data);					
					
					$_SESSION['message'] = $crud->message_success("Reco ID : ".$reco_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}	

?>