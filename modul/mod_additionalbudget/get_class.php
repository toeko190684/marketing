<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>

<label>Class Id : </label>
<select type="text" class="form-control" name="class_id">
	<option></option>
	<?php 
		$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_POST['id']."' order by budget_id");
		foreach($data as $value){
			echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
		}
	?>
</select>