<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>

<label>Choose Budget ID : </label>
<select class="form-control" name="budget_id" id="budget_id" required>
	<option value="All">-- All --</option>

<?php 
	$tahun = $_GET['id'];
	$departemen = $_GET['depid'];
	
	if(strtoupper($departemen) == "ALL"){
		$data = $crud->fetch("v_budget_summary","","departemen_id in 
							 (select departemen_id from user_authority where username ='".$_SESSION['username']."') 
							  and year(start_date)=".$tahun);
	}else{	
		$data = $crud->fetch("v_budget_summary","","departemen_id='".$departemen."' and year(start_date)=".$tahun);
	}
	foreach($data as $value){
		echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
	}
?>
</select>