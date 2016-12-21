<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "relocationbudget" and $act == "add"){
		$relokasi_prefix = $_POST['relokasi_prefix'];
		$relokasi_date = $_POST['relokasi_date'];
		$budget_id = $_POST['budget_id'];
		$class_id = $_POST['class_id'];
		$outstanding = $_POST['outstanding_budget'];
		
		$departemen_id = $_POST['departemen_id'];
		$to_relokasi_prefix = "/RL-".$_POST['departemen_id']."/".date("m")."/".date("Y");
		$to_budget_id = $_POST['to_budget_id'];
		$to_class_id = $_POST['to_class_id'];
		$total = $_POST['total'];
		$total = str_replace(",","",$_POST['total']);
		$yyyy_mm = substr($_POST['additional_date'],0,7);
		
		//cari nomor relokasi untuk departemen dan budget asal
		$cari_budget = $crud->fetch("v_relokasibudget_number","","budget_id='".$budget_id."'");
		
		if(count($cari_budget) <= 0){
			$relokasi_id = "0001".$relokasi_prefix;
		}else{ 
			$relokasi_id = $cari_budget[0]['relokasi_id'];				
		}
				
		//akhir dari cari nomor relokasi untuk departemen dan budget asal
		
		//cari nomor relokasi untuk departemen dan budget tujuan
		$cari_budget = $crud->fetch("v_relokasibudget_number","","budget_id='".$to_budget_id."'");
		
		if(count($cari_budget) <= 0){
			if($_SESSION['departemen_id'] != $departemen_id){
				$to_relokasi_id = "0001".$to_relokasi_prefix;
			}else{
				$to_relokasi_id = "0002".$to_relokasi_prefix;
			}
		}else{ 
			$to_relokasi_id = $cari_budget[0]['relokasi_id'];				
		}
		//akhir dari cari nomor relokasi untuk departemen dan budget tujuan
					
		$description_to = "Relocation from  Relocation id :".$relokasi_id.", from budget : ".$budget_id.", Class : ".$class_id. 
						  " for IDR : ".number_format($total,0,'.',',');	
		
		//buat descripsi untuk relokasi asal dan tujuan
		$description_from = "Relocation to Relocation id : ".$to_relokasi_id.", Departemen : ".$departemen_id.
						  ", Budget : ".$to_budget_id.", Class : ".$to_class_id." for IDR : ".
						  number_format($total,0,'.',',');		  
		
		//akhir dari buat dekripsi relokasi asal dan tujuan
		
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['c'] == 1){				
			try{
				//cek status budget apakah sudah posting
				$cek = $crud->fetch("budget","posting","budget_id='".$budget_id."'");
				if($cek[0]['posting'] == 0){	
					$data = array("relokasi_id" => $relokasi_id, 
								  "relokasi_date" => $relokasi_date,
								  "budget_id" => $budget_id,
								  "class_id" => $class_id,
								  "description" => $description_from,
								  "total" => $total*-1,
								  "created_by" => $_SESSION['username'],
								  "created_date" => date('Y-m-d H:m:s'));
					$sql = $crud->insert("relokasi_budget",$data);
					
					//script untuk memasukan relokasi tujuan					
					$data = array("relokasi_id" => $to_relokasi_id, 
								  "relokasi_date" => $relokasi_date,
								  "budget_id" => $to_budget_id,
								  "class_id" => $to_class_id,
								  "description" => $description_to,
								  "total" => $total,
								  "created_by" => $_SESSION['username'],
								  "created_date" => date('Y-m-d H:m:s'));
											
					try{ 
						$sql = $crud->insert("relokasi_budget",$data);
						$_SESSION['message'] = $crud->message_success("Relocation ID : ".$relokasi_id." has been added successfully!!<br>
																	   Relocation ID : ".$to_relokasi_id." has been added successfully!!");				
					
					}catch(exception $e){
						$crud->delete("relokasi_budget","relokasi_id = '".$relokasi_id."'");
						$_SESSION['message'] = $crud->message_error($e->getmessage());
					}					
				}else{
					$_SESSION['message'] = $crud->message_error("Relocation ID : ".$relocation_id." can't insert, because budget Id : ".$budget_id." has been closed!");
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