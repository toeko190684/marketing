<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	if($module == "budgetcontrol" and $act == "openclose"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d", 
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				$ket = $_GET['ket'];
				if($ket == "Open"){ $posting=0; }else{ $posting= 1; }
				$data = array("posting"=>$posting);
				$sql = $crud->update("budget",$data,"budget_id = '".$_GET['id']."'");			
				$_SESSION['message'] = $crud->message_success("Budget ID : ".$_GET['id']." has been ".$ket." successfully !!");				
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		header("location:../../user.php?r=budget&mod=13");
	}

?>