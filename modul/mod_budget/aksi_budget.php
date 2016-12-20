<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "budget" and $act == "del"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['d'] == 1){	
			try{
				//cek dulu apakah budget tersebut sudah di approve atau belum
				$cek = $crud->fetch("budget","approval1,status","budget_id='".$_GET['id']."'");
				if(strtoupper($cek[0]['status']) == "PENDING"){
					$sql = $crud->delete("budget","budget_id = '".$_GET['id']."'");			
					$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been deleted successfully !!");				
				}else{
					$_SESSION['message'] = $crud->message_error("[X] Failed to Delete.! Budget Id : ".$_GET['id']." Has been ".$cek[0]['status']." by ".$cek[0]['approval1']);
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "budget" and $act == "add"){
		$budget_prefix = $_POST['budget_prefix'];
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$yyyy_mm = substr($_POST['start_date'],0,7);
		$from_budget_id = $_POST['from_budget_id'];
		
		
		$cari_budget = $crud->fetch("budget","","departemen_id='".$_SESSION['departemen_id']."' and substring(start_date,1,7)='".$yyyy_mm."' order by budget_id desc limit 1");
		
		if(count($cari_budget) <= 0){
			$budget_id = "001".$budget_prefix;
		}else{ 
			$budget_id =  substr("000".strval(substr($cari_budget[0]['budget_id'],0,3)+1),1,3);	
			$budget_id = strrev(substr(strrev($budget_id),0,3));
			$budget_id .="/".substr($cari_budget[0]['budget_id'],4,15);			
		}
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				$data = array("budget_id" => $budget_id, "departemen_id" => $_SESSION['departemen_id'], "start_date" => $start_date, "end_date" => $end_date, "created_by" => $_SESSION['username']);
				$sql = $crud->insert("budget",$data);
				
				if($from_budget_id != ""){
					//masukan detail budget id 
					$detail = $crud->fetch("detail_budget","","budget_id='".$from_budget_id."'");
					foreach($detail as $value){
						try{ 
							$data = array("budget_id" => $budget_id,"class_id" => $value['class_id'], "total" => $value['total'],"created_by" => $_SESSION['username']);
							$crud->insert("detail_budget",$data);

						}catch(exception $e){
							$_SESSION['message'] = $crud->message_error($e->getmessage());
						}
					}	
				}	
				
				$_SESSION['message'] = $crud->message_success("Budget ID : ".$budget_id." has been added successfully!!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);
	}
	
	if($module == "budget" and $act == "update"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$budget_id = $_POST['budget_id'];	
				$start_date = $_POST['start_date'];
				$end_date = $_POST['end_date'];

				//cek dulu apakah statusnya sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
				
				if($cek[0]['posting'] == 0){
					$data = array("start_date" => $start_date,"end_date" => $end_date);
					
					$crud->update("budget",$data,"budget_id = '$budget_id' ");
					$_SESSION['message'] = $crud->message_success("budget Id : ".$_POST['budget_id']." has been updated successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("[X] Failed to update.! Budget Id : ".$budget_id." Has been approved");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}
	
	if($module == "budget" and $act == "approval"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$budget_id = $_GET['id'];
				$status = $_GET['status'];
				//cari budget id
				$data = $crud->fetch("budget","","budget_id='".$budget_id."'");
				
				if(strtoupper($data[0]['status']) == "PENDING"){
					$data = array("approval1" => $_SESSION['username'],"approval1_date"=>date('Y-m-d H:m:s'),"status" => $status);
					
					$crud->update("budget",$data,"budget_id = '$budget_id' ");
					$_SESSION['message'] = $crud->message_success("Budget Id : ".$budget_id." has been $status successfully!!");
				}else{
					$_SESSION['message'] = $crud->message_error("Failed to Approve ...!<br>Budget Id : ".$budget_id." Has been ".$data[0]['status']." by ".$data[0]['approval1']);
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