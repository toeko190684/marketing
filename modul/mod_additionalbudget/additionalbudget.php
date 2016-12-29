<script>
	$(document).ready(function(){
		$('.form_date').datetimepicker({
			language:  'id',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0
		});
		
		$("#budget_id").change(function(){
			$.post("modul/mod_additionalbudget/get_class.php",{ id : $(this).val() },function(data){
				$("#class_id").html(data);
			});
		});
	});
			
</script>

<?php 

$aksi = "modul/mod_additionalbudget/aksi_additionalbudget.php?r=additionalbudget&mod=".$_GET['mod'];


if($_POST['budget_id'] == "" ){
	if($_GET['id'] == ""){
		$data = $crud->fetch("budget","budget_id","departemen_id='".$_SESSION['departemen_id']."' 
							  and status='approved' order by budget_id desc limit 1");
		$_SESSION['budget_id'] = $data[0]['budget_id'];
	}else{
		$_SESSION['budget_id'] = $_GET['id'];
	}	
}else{
	$_SESSION['budget_id'] = $_POST['budget_id'];
}


if($_POST['class_id'] == ""){
	$_SESSION['class_id'] = $_GET['classid'];	
}else{
	$_SESSION['class_id'] = $_POST['class_id'];
}

switch($_GET['act']){
	default :	
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<a href="?r=detailbudget&mod=64&id=<?php echo $_SESSION['budget_id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=additionalbudget&mod=<?php echo $_GET[mod]; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<h2>List of Additional Budget</h2>
				
				<form method="post"  class="form-inline" >
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Budget Id : </label>
						<select name="budget_id" id="budget_id" class="form-control">
							<option value="<?php  echo $_SESSION['budget_id']; ?>"><?php echo $_SESSION['budget_id'];?></option>
							<?php 
								$data = $crud->fetch("budget","","year(start_date)='".$_SESSION['year']."'
													 and departemen_id='".$_SESSION['departemen_id']."' and status='approved'");
								foreach($data as $value){
									echo "<option value=\"$value[budget_id]\">".$value['budget_id']."</option>";
								}
							?>
						</select>
						<span id="classid">
							<label>Class Id : </label>
							<select name="class_id" id="class_id" class="form-control">
								<option value="<?php  echo $_SESSION['class_id']; ?>"><?php echo $_SESSION['class_id'];?></option>
								<?php 
									$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_SESSION['budget_id']."'");
									foreach($data as $value){
										echo "<option value=\"$value[class_id]\">".$value['class_id']." - ".$value['class_name']."</option>";
									}
								?>
							</select>
						</span>
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<td>No.</td>
						<td>Additional Id</td>
						<tD>Additional Date</td>
						<td>Account Id</td>
						<td>Description</td>
						<td>Total</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						require_once "pagelink_top.php";
						
						if($_SESSION['class_id'] == ""){
							$jumlah_record = $crud->fetch("v_additional_budget","","budget_id='".$_SESSION['budget_id']."'");
						}else{
							$jumlah_record = $crud->fetch("v_additional_budget","","budget_id='".$_SESSION['budget_id']."'  
									and class_id='".$_SESSION['class_id']."'");
						}
						
						
						if($_SESSION['class_id'] == ""){
							$data = $crud->fetch("v_additional_budget","","budget_id='".$_SESSION['budget_id']."' 
												 limit $posisi,$batas");			
						}else{
							$data = $crud->fetch("v_additional_budget","","budget_id='".$_SESSION['budget_id']."' 
												 and class_id='".$_SESSION['class_id']."' limit $posisi,$batas");	
						}
						
						$no = 1 + $posisi;
						foreach($data as $value){							
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['additional_id']."</td>
									<td>".date('d M Y',strtotime($value['additional_date']))."</td>
									<td>".$value['class_id']." - ".$value['class_name']."</td>
									<td>".$value['description']."</td>
									<td align=\"right\">".number_format($value[total],0,'.',',')."</td>
									<td>
										<a href=\"?r=additionalbudget&mod=64&act=view&id=$value[budget_id]&addid=$value[additional_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=additionalbudget&mod=".$_GET['mod']."&act=edit&id=$value[budget_id]&addid=$value[additional_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[budget_id]&addid=$value[additional_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
									</td>
								</tr>";
						}
					?>
				</tbody>
				</table>
				
				<?php 
					require_once "pagelink_bottom.php";
				?>
				
			</div>
		<?php
	break;

/*  jika pilihannya adalah add atau tambah */	
	case  "add":
		$budget_id = "/AD-".$_SESSION['departemen_id']."/".date("m")."/".date("Y");
		?>
			<div class="col-sm-12 col-md-12 col-lg-3" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
					<input type="hidden" name="budget_prefix" value="<?php echo $budget_id; ?>">
					<div class="form-group">
						<label>Budget ID : </label>
						<select type="text" class="form-control" name="budget_id" id="budget_id">
							<option value="<?php echo $_SESSION['budget_id'];?>"><?php echo $_SESSION['budget_id'];?></option>
							<?php 
								$data = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' 
													 and approval1<>'' and posting=0 order by budget_id");
								foreach($data as $value){
									echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
								}
							?>
						</select>
					</div>			
					<div class="form-group">
						<label>Additional Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="additional_date" size="10" type="text" placeholder="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
					<div class="form-group" id="class_id">
						<label>Class Id : </label>
						<select type="text" class="form-control" name="class_id">
							<option></option>
							<?php 
								$data = $crud->fetch("class","class_id,class_name","1 order by class_id");
								foreach($data as $value){
									echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Description : </label>
						<div class="form-group">
							<textarea class="form-control" name="description" required></textarea>
						</div>
					</div>				
					<div class="form-group">
						<label>Total : </label>
						<div class="form-group">
							<input class="form-control" name="total">
						</div>
					</div>	
								
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("v_additional_budget","","budget_id='$_GET[id]' and additional_id='".$_GET['addid']."'");
		?>
				<div class="col-sm-12 col-md-12 col-lg-3" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<div class="form-group">
						<label>Additional Id : </label>
						<div class="form-group">
							<input class="form-control" name="additional_id" value="<?php echo $data[0]['additional_id']; ?>" readonly>
						</div>
					</div>			
					<div class="form-group">
						<label>Additional Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="additional_date" size="10" type="text" placeholder="YYYY-MM-DD" value="<?php echo $data[0]['additional_date'];?>">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
					<div class="form-group">
						<label>Budget ID : </label>
						<select type="text" class="form-control" name="budget_id" id="budget_id">
							<option><?php echo $data[0]['budget_id'];?></option>
							<?php 
								$budget = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' 
													 and approval1<>'' and posting=0 order by budget_id");
								foreach($budget as $value){
									echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group" id="class_id">
						<label>Class Id : </label>
						<select type="text" class="form-control" name="class_id">
							<option value="<?php echo $data[0]['class_id'];?>"><?php echo $data[0]['class_id']." - ".$data[0]['class_name']; ?></option>
							<?php 
								$account = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_SESSION['budget_id']."' order by budget_id");
								foreach($account as $value){
									echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
								}
							?>
						</select>
					</div>	
					<div class="form-group">
						<label>Description : </label>
						<div class="form-group">
							<textarea class="form-control" name="description" required><?php echo $data[0]['description']; ?></textarea>
						</div>
					</div>						
					<div class="form-group">
						<label>Total : </label>
						<div class="form-group">
							<input class="form-control" name="total" value="<?php echo number_format($data[0]['total'],0,'.',','); ?>" required> 
						</div>
					</div>	
								
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":			
		$data = $crud->fetch("v_additional_budget","","budget_id='$_GET[id]' and additional_id='".$_GET[addid]."'");			
		?>
			<div class="col-md-4">				
				<a href="?r=additionalbudget&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $_GET[id]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Budget Id </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Additional Id : </strong></td><td><?php echo $data[0]['additional_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Class ID : </strong></td><td><?php echo $data[0]['class_id']." / ".$data[0]['class_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Description : </strong></td><td><?php echo $data[0]['description']; ?></td>
					</tr>
					<tr>
						<td><strong>Total : </strong></td><td><?php echo number_format($data[0]['total'],0,'.',','); ?></td>
					</tr>
					<tr>
						<td><strong>Created By : </strong></td><td><?php echo $data[0]['created_by']; ?></td>
					</tr>
					<tr>
						<td><strong>Created Date : </strong></td><td><?php echo date('d M Y',strtotime($data[0]['created_date'])); ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>