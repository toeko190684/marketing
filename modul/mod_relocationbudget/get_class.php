<?php 
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
?>
<script>
	$(document).ready(function(){
		$("#class_id").change(function(){
			$budgetid = $("#budget_id").val();
			$classid = $(this).val();
			
			$.get("modul/mod_relocationbudget/get_outstanding.php?buid="+$budgetid+"&classid="+$classid,function(data){
				$("#outstanding_budget").val(data);
			});			
		});
	});

</script>
<label>Class Id : </label>
<select type="text" class="form-control" name="class_id" id="class_id" required>
	<option value="">-- Choose Class --</option>
	<?php 
		$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_POST['id']."' order by budget_id");
		foreach($data as $value){
			echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
		}
	?>
</select>