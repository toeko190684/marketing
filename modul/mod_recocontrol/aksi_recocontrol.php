<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];
	
	
	if($module == "recocontrol" and $act == "approve"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$_GET['id']."'");
				if($cek[0]['posting'] == 0){	
					//cek lagi apakah reco sudah diapprove atau belum
					$cek2 = $crud->fetch("reco_budget","completed,approval1,approval2,status",
										 "budget_id='".$_GET['id']."' and reco_id = '".$_GET['recid']."'");
					
					//jika statusnya pending maka ke pengecekan berikutnya
					if(strtoupper($cek2[0]['status']) == "PENDING"){
						//jika filed completenya masih kosong
						if($cek2[0]['completed'] == ""){
							$data = array("completed"=>$_SESSION['username'],"completed_date" => date('Y-m-d H:m:s'));//jalankan update berdasarkan data yang sudah diubah diatas.
							$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
							$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
						}elseif($cek2[0]['approval1'] == ""){
							//lakukan pengecekan apakah boleh melakukan approval
							$approve_person = $crud->fetch("v_user_authority","approval1","username ='".$_SESSION['username']."'
															and departemen_id='".$_SESSION['departemen_id']."'");
							
							if($approve_person[0]['approval1']  == $_SESSION['username']){
								$data = array("approval1"=>$approve_person[0]['approval1'],"approval1_date" => date('Y-m-d H:m:s'));
								//jalankan update berdasarkan data yang sudah diubah diatas.
								$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
								$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
							}else{
								$_SESSION['message'] = $crud->message_error("You don't have permission to approve reco in ".$_SESSION['departemen_id']." departemen");
							}
						}elseif($cek2[0]['approval2'] == ""){
							//lakukan pengecekan apakah boleh melakukan approval
							$approve_person = $crud->fetch("v_user_authority","approval2","username ='".$_SESSION['username']."'
															and departemen_id='".$_SESSION['departemen_id']."'");
							
							if($approve_person[0]['approval2']  == $_SESSION['username']){
								$data = array("approval2"=>$approve_person[0]['approval2'],"approval2_date" => date("Y-m-d H:m:s"),"status" => "approved");
								//jalankan update berdasarkan data yang sudah diubah diatas.
								$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
								$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
							}else{
								$data = array("" => "");
								$_SESSION['message'] = $crud->message_error("You don't have permission to approve reco in departemen : ".$_SESSION['departemen_id']);
							}
						}else{
							$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because has been ".$cek2[0]['status']." !");
						}						
					}else{
						$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because has been ".$cek2[0]['status']." !");
					}
				}else{
					$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because budget Id : ".$_GET['id']." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=approvalreco&mod=".$mod);
	}
	
	
	
	// aksi untuk mereject reco
	if($module == "recocontrol" and $act == "reject"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$_GET['id']."'");
				if($cek[0]['posting'] == 0){	
					//cek lagi apakah reco sudah diapprove atau belum
					$cek2 = $crud->fetch("reco_budget","completed,approval1,approval2,status",
										 "budget_id='".$_GET['id']."' and reco_id = '".$_GET['recid']."'");
					
					//jika statusnya pending maka ke pengecekan berikutnya
					if(strtoupper($cek2[0]['status']) == "PENDING"){
						//jika filed completenya masih kosong
						if($cek2[0]['completed'] == ""){
							$data = array("completed"=>$_SESSION['username'],"completed_date" => date('Y-m-d H:m:s'),
										  "approval1" => $_SESSION['username'], "approval1_date" => date('Y-m-d H:m:s'),
										  "approval2" => $_SESSION['username'] ,"approval2_date" => date('Y-m-d H:m:s'),
										  "status" => "rejected");//jalankan update berdasarkan data yang sudah diubah diatas.
							$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
							$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
						}elseif($cek2[0]['approval1'] == ""){
							//lakukan pengecekan apakah boleh melakukan approval
							$approve_person = $crud->fetch("v_user_authority","approval1","username ='".$_SESSION['username']."'
															and departemen_id='".$_SESSION['departemen_id']."'");
							
							if($approve_person[0]['approval1']  == $_SESSION['username']){
								$data = array("approval1" => $_SESSION['username'], "approval1_date" => date('Y-m-d H:m:s'),
											  "approval2" => $_SESSION['username'] ,"approval2_date" => date('Y-m-d H:m:s'),
											  "status" => "rejected");
								//jalankan update berdasarkan data yang sudah diubah diatas.
								$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
								$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
							}else{
								$_SESSION['message'] = $crud->message_error("You don't have permission to approve reco in ".$_SESSION['departemen_id']." departemen");
							}
						}elseif($cek2[0]['approval2'] == ""){
							//lakukan pengecekan apakah boleh melakukan approval
							$approve_person = $crud->fetch("v_user_authority","approval2","username ='".$_SESSION['username']."'
															and departemen_id='".$_SESSION['departemen_id']."'");
							
							if($approve_person[0]['approval2']  == $_SESSION['username']){
								$data = array("approval2" => $_SESSION['username'] ,"approval2_date" => date('Y-m-d H:m:s'),
											  "status" => "rejected");
								//jalankan update berdasarkan data yang sudah diubah diatas.
								$sql = $crud->update("reco_budget",$data,"budget_id = '".$_GET['id']."' and reco_id='".$_GET['recid']."'");			
								$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
							}else{
								$data = array("" => "");
								$_SESSION['message'] = $crud->message_error("You don't have permission to approve reco in departemen : ".$_SESSION['departemen_id']);
							}
						}else{
							$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because has been ".$cek2[0]['status']." !");
						}						
					}else{
						$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because has been ".$cek2[0]['status']." !");
					}
				}else{
					$_SESSION['message'] = $crud->message_error("Reco Id : ".$_GET['recid']." can't approve, because budget Id : ".$_GET['id']." has been closed!");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=approvalreco&mod=".$mod);
	}

?>