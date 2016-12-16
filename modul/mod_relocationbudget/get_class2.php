<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>
<label>To Class Id : </label>
<select type="text" class="form-control" name="to_class_id" id="to_class_id" required>
	<option value="">-- Choose Class --</option>
	<?php 
		$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_POST['id']."' order by budget_id");
		foreach($data as $value){
			echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
		}
	?>
</select>