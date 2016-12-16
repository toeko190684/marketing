<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "approvalreco" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$_GET['id']."'");
				if($cek[0]['posting'] == 0){				
					//cek lagi jika apakah status rekonya masih pending
					$cek2 = $crud->fetch("reco_budget","status","budget_id='".$_GET['id']."' and reco_id = '".$_GET['recid']."'");
					if(strtoupper($cek2[0]['status']) == "PENDING"){
						$sql = $crud->delete("reco_budget","budget_id='".$_GET['id']."' and reco_id = '".$_GET['recid']."'");			
						$_SESSION['message'] = $crud->message_success("Reco ID : ".$_GET['recid']." has been deleted successfully !!");				
					}else{
						$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't delete, because has been ".$cek2[0]['status']." !");
					}
				}else{
					$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't delete, because budget Id : ".$_GET['id']." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "approvalreco" and $act == "update"){
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
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				if($total > $outstanding){
					$_SESSION['message'] = $crud->message_error("Total is bigger than outstanding !!");
					header("location:../../user.php?r=$module&mod=".$mod."&act=edit&id=".$budget_id."&recid=".$reco_id);
				}elseif($total_allow_used > $total){
					$_SESSION['message'] = $crud->message_error("Total Allow Used is bigger than total !!");
					header("location:../../user.php?r=$module&mod=".$mod."&act=edit&id=".$budget_id."&recid=".$reco_id);
				}else{				
					//cek status budget apakah sudah posting
					$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
					if($cek[0]['posting'] == 0){
						//mencari data reco untuk di cek apakah statusnya sudah di approve atau belum
						$cekreco = $crud->fetch("reco_budget","status","budget_id='".$budget_id."' and reco_id='".$reco_id."'");
						if(strtoupper($cekreco[0]['status']) == "PENDING"){					
							if($completed == ""){
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
											  "update_by" => $_SESSION['username'],
											  "update_date" => date('Y-m-d H:m:s'));
							}
							$crud->update("reco_budget",$data,"budget_id='".$budget_id."' and reco_id='".$reco_id."'");
							$_SESSION['message'] = $crud->message_success("Reco Id : ".$reco_id." has been updated successfully!!");				
						}else{
							$_SESSION['message'] = $crud->message_error("Reco Id : ".$reco_id." can't update, because the status is ".$cekreco[0]['status']." !");
						}
					}else{
						$_SESSION['message'] = $crud->message_error("Reco Id : ".$reco_id." can't update, because budget Id : ".$budget_id." has been closed!");
					}
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