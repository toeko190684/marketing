<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>

<label>Account Id : </label>
<select type="text" class="form-control" name="account_id">
	<option></option>
	<?php 
		$data = $crud->fetch("v_detail_budget","account_id,account_name","budget_id='".$_POST['id']."' order by budget_id");
		foreach($data as $value){
			echo "<option value=".$value['account_id'].">".$value['account_id']." - ".$value['account_name']."</option>";
		}
	?>
</select>