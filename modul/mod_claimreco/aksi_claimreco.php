<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	require_once "../../config/koneksi.php";
	require_once "../../config/sqlsvr_connect.php";
	
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
		$claim_prefix = "/CL-".$_SESSION['departemen_id']."/".date("m",strtotime($_POST['claim_date']))."/".date("Y",strtotime($_POST['claim_date']));
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
		
				
		$cari_claim = $crud->fetch("v_claimreco_number","","departemen_id='".$_SESSION['departemen_id']."' and periode ='".$yyyy_mm."'");
		
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
								  "status" => "pending",								  
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
	
	if($module == "claimreco" and $act == "update"){
		$claim_id = $_POST['claim_id'];
		$claim_date = $_POST['claim_date'];
		$claim_number_dist = $_POST['claim_number_dist'];
		$distributor_id = $_POST['distributor_id'];
		$reco_id = $_POST['reco_id'];
		$po_so_number = $_POST['po_so_number'];
		$ppn = $_POST['ppn'];
		$pph = $_POST['pph'];
		$nomor_faktur_pajak = $_POST['nomor_faktur_pajak'];
		$deskripsi = $_POST['description'];
		$claim_approved_ammount = str_replace(",","",$_POST['claim_approved_ammount']);
		$account_id = $_POST['account_id'];
		$vendor_id = $_POST['vendor_id'];
		$status = $_POST['status'];
		
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				//cek status reko apakah sudah posting
				$cek = $crud->fetch("v_reco_budget","outstanding","reco_id='".$reco_id."'");
				if(($cek[0]['outstanding']+$claim_approved_ammount) >= $claim_approved_ammount){	
					$data = array("claim_date" => $claim_date,
								  "claim_number_dist" => $claim_number_dist,
								  "distributor_id" => $distributor_id,
								  "reco_id" => $reco_id,
								  "po_so_number" => $po_so_number,
								  "ppn" => $ppn,
								  "pph" => $pph,
								  "nomor_faktur_pajak" => $nomor_faktur_pajak,
								  "deskripsi" => $deskripsi,
								  "claim_approved_ammount" => $claim_approved_ammount,
								  "account_id" => $account_id,
								  "vendor_id" => $vendor_id,
								  "status" => "pending",								  
								  "update_by" => $_SESSION['username'],
								  "update_date" => date('Y-m-d H:m:s'));
					
					$crud->update("claim_reco",$data,"claim_id='".$claim_id."'");
					$_SESSION['message'] = $crud->message_success("Claim Id : ".$claim_id." has been updated successfully!!");						
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
	
	if($module == "claimreco" and $act == "approve"){
		$claim_id = $_GET['clid'];
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$cek = $crud->fetch("v_claim_reco","claim_id,claim_date,reco_id,status,approve_by,approve_date,vendor_id,
									nomor_faktur_pajak,ppn,ap_account_type,ap_account_id,claim_approved_ammount",
									"claim_id='".$claim_id."'");
				
				if(strtoupper($cek[0]['status']) == "PENDING"){
					$data = array("status" => "approved", "approve_by" => $_SESSION['username'], "approve_date" => date('Y-m-d'));
					
					$step1 = $crud->update("claim_reco",$data,"claim_id = '".$claim_id."'");
					
					$pesan = "Claim Id : ".$claim_id." has been approved successfully!!";	
					
					
					/*==========================================================
						buat nomor journal id berdasarkan tanggal claim date
					==========================================================*/
											
					$no =  $crud_sql->fetch("ap_journal","max(journal_id) as journal_id",
											"journal_date='".date('Y-m-d',strtotime($cek[0]['claim_date']))."'");
					
					
					if($no[0]['journal_id'] == ""){
						$journal_id = date(Ymd,strtotime($cek[0]['claim_date']))."0001";
					}else{
						$journal_id = $no[0]['journal_id']+1;
					}
					
					
					//cari data account type di sql server nyebelin 
					$account = $crud_sql->fetch("account","account_id,account_type","account_id='".$cek[0]['account_id']."'");
					
					
					
					//cari term of pay dari vendor nyebelin
					$term_of_pay = $crud_sql->fetch("vendor","term_of_pay","vendor_id='".$cek[0]['vendor_id']."'");
					
					//cari paid date didapat dari penambahan tanggal claim date sebanyak term_of_pay vendor_id 
					$paid_date =  date('Y-m-d',strtotime("+".floor($term_of_pay[0][term_of_pay])." days",strtotime($cek[0]['claim_date'])));
					
					//cari untuk mengisi po_id dan rec_id, digit hanya 15 digit disediakan di sql server sehinggal pemisah kodenya ("/" dan "-")dipisahkan
					$separator = array("/","-");
					$po_rec_id = str_replace($separator,"",$cek[0]['claim_id']);
					
					
					//prepare data untuk insert ke tabel ap_jurnal database sql server
					$data = array("user_id" => $_SESSION['username'],
									"last_update" => $cek[0]['claim_date'],
									"created_by" => $_SESSION['username'],   
									"company" => "PT MORINAGA KINO INDONESIA",
									"branch" => "JAKARTA",
									"journal_id" => $journal_id,
									"journal_date" => $cek[0]['claim_date'],
									"description" => $cek[0]['claim_id']."-".$cek[0]['reco_id'] ,
									"vendor_id" => $cek[0]['vendor_id'],
									"po_id" => $po_rec_id,
									"po_rev" => "",
									"debet" => 0,
									"credit" => $cek[0]['claim_approved_ammount'],
									"due_date" => $cek[0]['claim_date'],
									"paid" => 0,
									"paid_date" => $paid_date ,
									"posted" => 0,
									"ok" => 0,
									"account_type" => "" ,
									"account_id" => "",
									"check_no" => "",
									"check_date" => $cek[0]['claim_date'],
									"c_symbol" => "IDR",
									"ppn_no" => $cek[0]['nomor_faktur_pajak'],
									"ppn" => $cek[0]['ppn'],
									"vat_date" => $cek[0]['claim_date'],
									"vinvoice_id" => "",
									"vinvoice_date" => $cek[0]['claim_date'],
									"ap_account_type" => $cek[0]['ap_account_type'],
									"ap_account_id" => $cek[0]['ap_account_id'],
									"as_account_type" => $account[0]['account_id'],
									"as_account_id" => $account[0]['account_type'],
									"vat_account_type" => "" ,
									"vat_account_id" => "",
									"transaction_id" => "",
									"rec_id"=> $po_rec_id);
					
					//input data ke kinosentraacc
					$ap_journal = $crud_sql->insert("ap_journal",$data);
					
					//update field journal_id di tabel claim_reco
					$data = array("journal_id" => $journal_id);
					$crud->update("claim_reco",$data,"claim_id='".$cek[0]['claim_id']."'");
						
					$pesan = $pesan."<br>Journal Id : ".$journal_id." has been insert successfully!!";	
					$_SESSION['message'] = $crud->message_success($pesan);
				}else{
					$_SESSION['message'] = $crud->message_error("Claim Id : ".$claim_id." can't approved, because claim Id : ".$claim_id." has been ".$cek[0]['status']." by ".$cek[0]['approve_by']." !");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);		
	}
	
	if($module == "claimreco" and $act == "reject"){
		$claim_id = $_GET['clid'];
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$cek = $crud->fetch("claim_reco","status,approve_by,approve_date","claim_id='".$claim_id."'");
				if(strtoupper($cek[0]['status']) == "PENDING"){
					$data = array("status" => "rejected", "approve_by" => $_SESSION['username'], "approve_date" => date('Y-m-d'));
					$crud->update("claim_reco",$data,"claim_id = '".$claim_id."'");
					$_SESSION['message'] = $crud->message_success("Claim Id : ".$claim_id." has been rejected successfully!!");				
				}else{
					$_SESSION['message'] = $crud->message_error("Claim Id : ".$claim_id." can't reject, because claim Id : ".$claim_id." has been ".$cek[0]['status']." by ".$cek[0]['approve_by']." !");
				}
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=$module&mod=".$mod);			
				
		header("location:../../user.php?r=$module&mod=".$mod);	
	}


?>