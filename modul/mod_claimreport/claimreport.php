<script> 
	$(document).ready(function(){
		$("#tahun").change(function(){
			var depid = $("#departemen_id").val();
			
			$.get("modul/mod_budgetreport/get_budget.php?id="+$(this).val()+"&depid="+depid,function(data){
				$("#budgetid").html(data);
			});
		});
		
		$("#departemen_id").change(function(){
			var tahun = $("#tahun").val();
			if(tahun != ""){
				$("#tahun").change();
			}
		});
	});
</script>

<?php 
$aksi = "modul/mod_budgetreport/control.php";
switch($_GET['act']){
	default :
		?>
			<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi."?uid=".$_GET['uid']; ?>" target="__blank()">	
					<div class="form-group">
						<label>Choose Departemen : </label>
						<select class="form-control" name="departemen_id" id="departemen_id" required>
							<option value="All">-- All --</option>
							<?php 
								$data = $crud->fetch("v_user_authority","distinct departemen_id,departemen_name",
													 "username = '".$_SESSION['username']."' and 
													 group_id='".$_SESSION['group_id']."' order by departemen_name");
								foreach($data as $value){
									echo "<option value=".$value['departemen_id'].">".$value['departemen_name']."</option>";
								}
							?>
						</select>						
					</div>
					<div class="form-group">
						<label>Choose Year : </label>	
						<select type="text" class="form-control" name="tahun" id="tahun" required>
							<option value="">-- Choose Year --</option>
							<?php 
								for($i=0;$i<=5;$i++){
									$year = date("Y")-$i;
									echo "<option value=\"$year\">".$year."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group" id="budgetid">
						<label>Choose Budget ID : </label>
						<select class="form-control" name="budget_id" id="budget_id" required>
							<option value="All">-- All --</option>
						</select>						
					</div>
					<div class="form-group">
						<label class="radio-inline">
							<input type="radio" name="print_id" value="pdf" checked> PDF
						</label>
						<label class="radio-inline">
							<input type="radio" name="print_id" value="excel"> EXCEL
						</label>
					</DIV>
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
					</form>
			</div>
		<?php 
	break;	
}


?>