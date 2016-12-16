<?php 
	if($_POST['budget_id'] == "All"){
		if($_POST['print_id'] == "pdf"){
			header("location:budgetreportall_print.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}else{
			header("location:budget_reportall_xls.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}
	}else{
		if($_POST['print_id'] == "pdf"){
			header("location:budgetreportdetail_print.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}else{
			header("location:budget_reportdetail_xls.php?budget_id=".$_POST['budget_id']."&tahun=".$_POST['tahun'].
			       "&depid=".$_POST['departemen_id']);
		}
	}

?>