<?php 
	//if($_POST['budget_id'] == "All"){
		if($_POST['print_id'] == "pdf"){
			header("location:recoreportall_print.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}else{
			header("location:reco_reportall_xls.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}
	/*}else{
		if($_POST['print_id'] == "pdf"){
			header("location:recoreportdetail_print.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}else{
			header("location:reco_reportdetail_xls.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}
	}*/

?>