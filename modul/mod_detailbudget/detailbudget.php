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
	});
			
</script>

<?php 

$aksi = "modul/mod_detailbudget/aksi_detailbudget.php?r=detailbudget&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		if($_POST['budget_id'] == ""){
			$_SESSION['budget_id'] = $_GET['id'];
		}else{
			$_SESSION['budget_id'] = $_POST['budget_id'];
		}
		
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<a href="?r=budget&mod=13&id=<?php echo $_GET['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=detailbudget&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<h2>List of Detail Budget #<?php echo $_GET['id'];?></h2>
				
				<form method="post"  class="form-inline" >
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Budget Id : </label>
						<select name="budget_id" class="form-control">
							<option value="<?php  echo $_SESSION['budget_id']; ?>"><?php echo $_SESSION['budget_id'];?></option>
							<?php 
								$data = $crud->fetch("budget","","year(start_date)='".$_SESSION['year']."'
													 and departemen_id='".$_SESSION['departemen_id']."'");
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
						<td>Class Id - Name</td>
						<td>Start</td>
						<td>Additional</td>
						<td>Relokasi</td>
						<td>Reco</td>
						<td>Outstanding</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						$jumlah_record = $crud->fetch("v_detail_budget","","budget_id = '".$_SESSION['budget_id']."'");
						
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						$data = $crud->fetch("v_detail_budget","","budget_id = '".$_SESSION['budget_id']."'
											 limit $start,$per_hal");			
						
						
						$no = 1;
						foreach($data as $value){							
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['class_id']." - ".$value['class_name']."</td>
									<td align=\"right\">".number_format($value['start_budget'],0,'.',',')."</td>
									<td align=\"right\">".number_format($value['additional_budget'],0,'.',',')."</td>
									<td align=\"right\">".number_format($value['relokasi_budget'],0,'.',',')."</td>
									<td align=\"right\">".number_format($value['reco_budget'],0,'.',',')."</td>
									<td align=\"right\">".number_format($value['outstanding_budget'],0,'.',',')."</td>
									<td>
										<a href=\"?r=additionalbudget&mod=66&id=$value[budget_id]&classid=$value[class_id]\"><span class=\"glyphicon glyphicon-piggy-bank\" aria-hidden=\"true\"></span> Additional</a> |
										<a href=\"?r=recobudget&mod=69&id=$value[budget_id]&classid=$value[class_id]\"><span class=\"glyphicon glyphicon-cutlery\" aria-hidden=\"true\"></span> Reco</a> |
										<a href=\"?r=relocationbudget&mod=68&id=$value[budget_id]&classid=$value[class_id]\"><span class=\"glyphicon glyphicon-heart\" aria-hidden=\"true\"></span> Relokasi</a> |
										<a href=\"?r=detailbudget&mod=64&act=view&id=$value[budget_id]&classid=$value[class_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=detailbudget&mod=".$_GET['mod']."&act=edit&id=$value[budget_id]&classid=$value[class_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[budget_id]&classid=$value[class_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Delete</a> 									
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
		?>
			<div class="col-sm-12 col-md-3 col-lg-3" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
				<div class="form-group">
					<label>Budget Id : </label>
					<div class="input-group">
						<input class="form-control" name="budget_id" value="<?php echo $_SESSION['budget_id']; ?>" type="text" readonly>
					</div>
				</div>
				<div class="form-group">
					<label>Class Id : </label>
					<div class="input-group">
						<select name="class_id" class="form-control" required>
							<option value="">-- Choose Class Id --</option>
							<?php 
								$account = $crud->fetch("class","","1 order by class_id");
								foreach($account as $value){
									echo "<option value=\"$value[class_id]\">".$value['class_id']." - ".$value['class_name']."</option>";
								}
							?>
						</select>
					</div>
				</div>	
				<div class="form-group">
					<label>Total : </label>
					<div class="input-group">
						<input class="form-control" name="total" value="<?php echo number_format($data[0]['total'],0,'.',','); ?>" type="text">
					</div>
				</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("v_detail_budget","","budget_id='".$_GET['id']."' and class_id='".$_GET['classid']."'");
		?>
				<div class="col-sm-12 col-md-3 col-lg-3" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<div class="form-group">
						<label>Budget Id : </label>
						<div class="input-group">
							<input class="form-control" name="budget_id" value="<?php echo $data[0]['budget_id']; ?>" type="text" readonly>
						</div>
					</div>
					<div class="form-group">
						<label>Class Id : </label>
						<div class="input-group">
							<select name="class_id" class="form-control" required>
								<option value="<?php echo $data[0]['class_id'];?>">
									<?php echo $data[0]['class_id']." - ".$data[0]['class_name']; ?>
								</option>
								<?php 
									$class = $crud->fetch("class","","1 order by class_id");
									foreach($class as $value){
										echo "<option value=\"$value[class_id]\">".$value['class_id']." - ".$value['class_name']."</option>";
									}
								?>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label>Total : </label>
						<div class="input-group">
							<input class="form-control" name="total" value="<?php echo number_format($data[0]['start_budget'],0,'.',','); ?>" type="text">
						</div>
					</div>
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("v_detail_budget","","budget_id='".$_GET['id']."' and class_id='".$_GET['classid']."'");			
		?>
			<div class="col-md-4">				
				<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Budget Id : </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Class Id : </strong></td><td><?php echo $data[0]['class_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Class Name : </strong></td><td><?php echo $data[0]['class_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Total : </strong></td><td><?php echo number_format($data[0]['start_budget'],0,'.',','); ?></td>
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