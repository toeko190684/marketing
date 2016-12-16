<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	require_once("../../config/koneksi.php");
?>

<script>
	$(document).ready(function(){
		$("#to_budget_id").change(function(){
			$.post("modul/mod_relocationbudget/get_class2.php",{ id : $(this).val() },function(data){
				$("#toclassid").html(data);
			});
		});
	});
</script>



<label>To Budget ID : </label>
<select type="text" class="form-control" name="to_budget_id" id="to_budget_id">
	<option value="">-- Choose budget --</option>
	<?php 
		$data = $crud->fetch("budget","","departemen_id='".$_GET['buid']."' and approval1<>'' and posting=0 order by budget_id");
		foreach($data as $value){
			echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
		}
	?>
</select>