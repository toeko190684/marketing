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
				$cek = $crud->fetch("v_claim_bnk","","claim_number_system = '".$claim_number_system."'");
								
				if(strtoupper($cek[0]['status']) == "PENDING"){
					//cari dulu yang nomor maksimal di sql server sesuai dengan tanggal claim claim
					$journal = $crud_sql->fetch("ap_journal","max(journal_id)as journal_id","journal_date='".$cek[0]['claim_date']."'");
					
					if($journal[0]['journal_id'] == ""){
						$journal_id = str_replace("-","",$cek[0]['claim_date'])."0001";
					}else{
						$journal_id = $journal[0]['journal_id']+1;
					}					
					
					

					/*
						coba insert data ke sql server untuk journal tersebut.
					*/
					
					//prepare data untuk insert ke tabel ap_jurnal database sql server
					$data = array("user_id" => $_SESSION['username'],
									"last_update" => $cek[0]['claim_date'],
									"created_by" => $_SESSION['username'],   
									"company" => "PT MORINAGA KINO INDONESIA",
									"branch" => "JAKARTA",
									"journal_id" => $journal_id,
									"journal_date" => $cek[0]['claim_date'],
									"description" => $cek[0]['claim_number_system'] , //biasanya kombinasi claimnumbersystem dan kode promo
									"vendor_id" => $cek[0]['vendor_id'],
									"po_id" => $cek[0]['claim_number_system'],
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
									"rec_id"=> $cek[0]['claim_number_system']);
					
					//input data ke kinosentraacc
					$ap_journal = $crud_sql->insert("ap_journal",$data);					
					
					/*
						update field journal_id dengan journal_id yang kita generate tadi.
					*/	
					
					$data = array("approve_by" => $_SESSION['username'],"status" => "approved","journal_id" => $journal_id);
					$crud->update("claim_bnk",$data,"claim_number_system = '".$claim_number_system."'");	
					
					$_SESSION['message'] = $crud->message_success("Claim Number System : ".$claim_number_system." has been approved successfully!!<br>
																  Journal Id : ".$journal_id." has been insert to MKIAcc");	
				}else{
					$_SESSION['message'] = $crud->message_error("Claim Number System : ".$claim_number_system." failed to approved because it has been ".$cek[0]['status']." by ".$cek[0]['approve_by']);
				}
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
				//cari tanggal dari claim number system terseubt
				$claim = $crud->fetch("v_claim_bnk_header","distinct claim_date,status,approve_by","claim_number_system = '".$claim_number_system."'");
								
				if(strtoupper($claim[0]['status']) == "PENDING"){
					$data = array("status" => "rejected",
								  "approve_by" => $_SESSION['username'],
								  "tgl_approve" => date("y-m-d H:m:s"));
					
					$crud->update("claim_bnk",$data,"claim_number_system='".$claim_number_system."' and status not in('approved','rejected')");
					$_SESSION['message'] = $crud->message_success("Claim Number System : ".$claim_number_system." has been rejected successfully!!");	
				}else{
					$_SESSION['message'] = $crud->message_error("Claim Number System : ".$claim_number_system." failed to rejected because it has been ".$claim[0]['status']." by ".$claim[0]['approve_by']);
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