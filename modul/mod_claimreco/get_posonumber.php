<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/sqlsvr_connect.php";

	$data = $crud_sql->fetch("epo","po_id,replace(convert(nvarchar,getdate(),106), '','/')as po_date,vendor_id,remark","po_id='".$_POST['po_id']."'");
	$tanda = array("[","]");
	echo str_replace($tanda,"",json_encode($data));	
?>