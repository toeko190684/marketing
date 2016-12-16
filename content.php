<?php
if ($_GET['r']=='home'){
    include "modul/mod_home/home.php";
}elseif (($_GET['r'])=='departemen'){
	include "modul/mod_departemen/departemen.php";
}elseif (($_GET['r'])=='grouppromo'){
	include "modul/mod_grouppromo/grouppromo.php";
}elseif (($_GET['r'])=='promotype'){
	include "modul/mod_promotype/promotype.php";
}elseif (($_GET['r'])=='class'){
	include "modul/mod_class/class.php";
}elseif (($_GET['r'])=='area'){
	include "modul/mod_area/area.php";
}elseif (($_GET['r'])=='subdist'){
	include "modul/mod_subdist/subdist.php";
}elseif (($_GET['r'])=='groupoutlet'){
	include "modul/mod_groupoutlet/groupoutlet.php";
}elseif (($_GET['r'])=='account'){
	include "modul/mod_account/account.php";
}elseif (($_GET['r'])=='vendor'){
	include "modul/mod_vendor/vendor.php";
}elseif (($_GET['r'])=='budget'){
	include "modul/mod_budget/budget.php";
}elseif (($_GET['r'])=='budgetapproval'){
	include "modul/mod_budgetapproval/budgetapproval.php";
}elseif (($_GET['r'])=='detailbudget'){
	include "modul/mod_detailbudget/detailbudget.php";
}elseif (($_GET['r'])=='budget_control'){
	include "modul/mod_budgetcontrol/aksi_budgetcontrol.php";
}elseif (($_GET['r'])=='additionalbudget'){
	include "modul/mod_additionalbudget/additionalbudget.php";
}elseif (($_GET['r'])=='recobudget'){
	include "modul/mod_recobudget/recobudget.php";
}elseif (($_GET['r'])=='approvalreco'){
	include "modul/mod_approvalreco/approvalreco.php";
}elseif (($_GET['r'])=='relocationbudget'){
	include "modul/mod_relocationbudget/relocationbudget.php";
}elseif (($_GET['r'])=='claimreco'){
	include "modul/mod_claimreco/claimreco.php";
}elseif (($_GET['r'])=='recobnk'){
	include "modul/mod_recobnk/recobnk.php";
}elseif (($_GET['r'])=='claimbnk'){
	include "modul/mod_claimbnk/claimbnk.php";
}elseif (($_GET['r'])=='budgetreport'){
	include "modul/mod_budgetreport/budgetreport.php";
}elseif (($_GET['r'])=='recoreport'){
	include "modul/mod_recoreport/recoreport.php";
}elseif (($_GET['r'])=='claimreport'){
	include "modul/mod_claimreport/claimreport.php";
}elseif (($_GET['r'])=='managemodule'){
	include "modul/mod_managemodule/managemodule.php";
}elseif (($_GET['r'])=='groupuser'){
	include "modul/mod_groupuser/groupuser.php";
}elseif (($_GET['r'])=='manageusers'){
	include "modul/mod_manageusers/manageusers.php";
}elseif (($_GET['r'])=='managedepartemen'){
	include "modul/mod_managedepartemen/managedepartemen.php";
}else{
    echo "<script>$.messager.alert('SKProject','Maaf $_SESSION[user_id], Modul belum ada ..! ','info');</script>";
}
?>











