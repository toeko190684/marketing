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
			$.post("modul/mod_relocationbudget/get_class.php",{ id : $(this).val() },function(data){
				$("#classid").html(data);
			});
		});
		
		$("#class_id").change(function(){
			$budgetid = $("#budget_id").val();
			$accid = $(this).val();
			
			$.get("modul/mod_relocationbudget/get_outstanding.php?buid="+$budgetid+"&accid="+$accid,function(data){
				$("#outstanding_budget").val(data);
			});			
		});
		
		$("#departemen_id").change(function(){
			var departemen = $(this).val();
			
			$.get("modul/mod_relocationbudget/get_budget.php?buid="+departemen,function(data){
				$("#tobudgetid").html(data);
			});
		});

		$("#total").focusout(function(){
			//cari nilai outstanding
			var out = $("#outstanding_budget").val();
			outstanding = out.replace(/,/g,"");
			
			//cari nilai total
			var tot = $("#total").val();
			total = tot.replace(/,/g,"");
			
			//jika outstanding lebih kecil dari total maka di tolak
			if(eval(outstanding) < eval(total)){
				alert("Total is bigger than outstanding !!");
				$(this).val("0");
			}
			
		});
	});
			
</script>

<?php 

$aksi = "modul/mod_relocationbudget/aksi_relocationbudget.php?r=relocationbudget&mod=".$_GET['mod'];


if($_GET['id'] == ""){
	if($_POST['budget_id'] == ""){
		if($_SESSION['budget_id'] == ""){
			$budget = $crud->fetch("budget","budget_id","departemen_id='".$_SESSION['departemen_id']."' 
									and approval1<>'' and posting = 0 order by start_date asc");
			$_SESSION['budget_id'] = $budget[0]['budget_id'];
		}else{
			$_SESSION['budget_id'] = $_SESSION['budget_id'];
		}
	}else{
		$_SESSION['budget_id'] = $_POST['budget_id'];
	}
}else{
	$_SESSION['budget_id'] = $_GET['id'];
}


switch($_GET['act']){
	default :	
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<a href="?r=detailbudget&mod=64&id=<?php echo $_SESSION['budget_id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=relocationbudget&mod=<?php echo $_GET[mod]; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<h2>List of Relocation Budget</h2>
				
				<form method="post"  class="form-inline" >
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Budget Id : </label>
						<select name="budget_id" class="form-control">
							<option value="<?php  echo $_SESSION['budget_id']; ?>"><?php echo $_SESSION['budget_id'];?></option>
							<?php 
								$data = $crud->fetch("budget","","year(start_date)='".$_SESSION['year']."'
													 and departemen_id='".$_SESSION['departemen_id']."' and approval1<>''");
								foreach($data as $value){
									echo "<option value=\"$value[budget_id]\">".$value['budget_id']."</option>";
								}
							?>
						</select>
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
						<td>Class Id</td>
						<td>Description</td>
						<td>Total</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						$jumlah_record = $crud->fetch("v_relokasi_budget","","budget_id='".$_SESSION['budget_id']."'
													  and departemen_id='".$_SESSION['departemen_id']."'");
						
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						$data = $crud->fetch("v_relokasi_budget","","budget_id='".$_SESSION['budget_id']."' 
											 and departemen_id='".$_SESSION['departemen_id']."'
											 limit $start,$per_hal");			
						
						
						$no = 1;
						foreach($data as $value){							
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['relokasi_id']."</td>
									<td>".date('d M Y',strtotime($value['relokasi_date']))."</td>
									<td>".$value['class_id']." - ".$value['class_name']."</td>
									<td>".$value['description']."</td>
									<td align=\"right\">".number_format($value[total],0,'.',',')."</td>
									<td>
										<a href=\"?r=relocationbudget&mod=".$_GET['mod']."&act=view&id=$value[budget_id]&relid=$value[relokasi_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> 
									</td>
								</tr>";
						}
					?>
				</tbody>
				</table>
				
				<?php 
					include "footer_pagination.php";
				?>
				
			</div>
		<?php
	break;

/*  jika pilihannya adalah add atau tambah */	
	case  "add":
		$relokasi_id = "/RL-".$_SESSION['departemen_id']."/".date("m")."/".date("Y");
		?>
			<div class="col-sm-12 col-md-12 col-lg-12" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
				<div class="col-sm-12 col-md-12 col-lg-3" >					
						<input type="hidden" name="relokasi_prefix" value="<?php echo $relokasi_id; ?>">			
						<div class="form-group">
							<label>Relocation Date : </label>
							<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="relokasi_date" size="10" type="text" value="<?php echo date('Y-m-d'); ?>" placeholder="YYYY-MM-DD" required>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>	
						<div class="form-group">
							<label>From Budget ID : </label>
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
						<div class="form-group" id="classid">
							<label>From Class Id : </label>
							<select type="text" class="form-control" name="class_id" id="classid">
								<option value="">-- Choose Class --</option>
								<?php 
									$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_SESSION['budget_id']."' order by budget_id");
									foreach($data as $value){
										echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Outstanding : </label>
							<div class="form-group">
								<input class="form-control" name="outstanding_budget" id="outstanding_budget" value="0" readonly>
							</div>
						</div>	
				</div>
				<div class="col-sm-12 col-md-12 col-lg-3">
						<div class="form-group">
							<label>To Departemen ID : </label>
							<select type="text" class="form-control" name="departemen_id" id="departemen_id">
								<option value="">-- Choose Departemen --</option>
								<?php 
									$data = $crud->fetch("v_user_authority","","username='".$_SESSION['username']."'");
									foreach($data as $value){
										echo "<option value=".$value['departemen_id'].">".$value['departemen_name']."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group" id="tobudgetid">
							<label>To Budget ID : </label>
							<select type="text" class="form-control" name="to_budget_id" id="to_budget_id">
								<option value="">-- Choose Budget Id--</option>
								<?php 
									$data = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' 
														 and approval1<>'' and posting=0 order by budget_id");
									foreach($data as $value){
										echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group" id="toclassid">
							<label>To Class Id : </label>
							<select type="text" class="form-control" name="to_class_id" id="to_class_id">
								<option></option>
								<?php 
									$data = $crud->fetch("v_detail_budget","class_id,class_name","budget_id='".$_SESSION['budget_id']."' order by budget_id");
									foreach($data as $value){
										echo "<option value=".$value['class_id'].">".$value['class_id']." - ".$value['class_name']."</option>";
									}
								?>
							</select>
						</div>			
						<div class="form-group">
							<label>Total : </label>
							<div class="form-group">
								<input class="form-control" name="total" id="total" placeholder="0" value="0" required>
							</div>
						</div>
									
						<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</div>
				</form>
			</div>
		<?php
	break;
	
/*   jika pilihan kondisinya adalah view */ 
	case "view":			
		$data = $crud->fetch("v_relokasi_budget","","budget_id='$_GET[id]' and relokasi_id='".$_GET[relid]."'");			
		?>
			<div class="col-md-4">				
				<a href="?r=relocationbudget&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $_GET[id]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Budget Id </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Relocation Id : </strong></td><td><?php echo $data[0]['relokasi_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Account ID : </strong></td><td><?php echo $data[0]['account_id']." / ".$data[0]['account_name']; ?></td>
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