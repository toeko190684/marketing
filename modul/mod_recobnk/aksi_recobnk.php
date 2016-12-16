<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	$module = $_GET['r'];
	$act = $_GET['act'];
	$mod = $_GET['mod'];

	//jika actionannya adalah upload data
	if($module == "recobnk" and $act == "upload"){
		$data = $crud->fetch("v_user_data","distinct departemen_id,group_id,module_id,c,r,u,d",
		                     "departemen_id='$_SESSION[departemen_id]' and group_id = '$_SESSION[group_id]' 
							 and module_id='".$mod."'");
		if($data[0]['u'] == 1){	
			try{
				if (($handle = fopen("reco.csv", "r")) !== FALSE) {
					while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
						echo $data[0]."<br><Br>";
					} //end while
					fclose($handle);
				} //end if
			}catch(exception $e){
				$_SESSION['message'] = $crud->message_error($e->getmessage());
				echo $_SESSION['message'];
			}
		}else{
			$_SESSION['message'] = $crud->module_alert();	
		}
		//header("location:../../user.php?r=$module&mod=".$mod."&act=upload");	
	}


?>