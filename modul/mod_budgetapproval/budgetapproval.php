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

$aksi = "modul/mod_budgetapproval/aksi_budgetapproval.php?r=budgetapproval&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		if($_POST['year'] <> ""){
			$_SESSION['year'] = $_POST['year'];
		}
		
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<a href="?r=budget&mod=13" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<h2>List of Pending Budget</h2>
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Year : </label>
						<select name="year" class="form-control">
							<option value="<?php  echo $_SESSION['year']; ?>"><?php echo $_SESSION['year'];?></option>
							<?php 
								$tahun = date('Y');
								for($i = 0;$i<=4;$i++){
									$tahun = $tahun - $i;
									echo "<option value=\"$tahun\">".$tahun."</option>";
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
						<td>Budget Id</td>
						<td>Periode</td>
						<td>Open</td>
						<td>Approval 1</td>
						<td>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						if($_POST['budget_id'] == ""){
							$jumlah_record = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
														  and year(start_date)='".$_SESSION['year']."' and approval1=''");
						}else{
							$jumlah_record = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
														  and year(start_date)='".$_SESSION['year']."'and approval1=''
														  and budget_id='".$_POST['budget_id']."'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['budget_id'] == ""){
							$data = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
												 and year(start_date)='".$_SESSION['year']."' and approval1='' 
												 limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
												 and year(start_date)='".$_SESSION['year']."' and approval1='' 
											     and budget_id='".$_POST['budget_id']."'
												 limit $start,$per_hal");	
						}
						
						$no = 1;
						foreach($data as $value){							
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['budget_id']."</td>
									<td>".date('M y',strtotime($value['start_date']))."</td>
									<td align=\"right\">".number_format($value['open_budget'],0,'.',',')."</td>
									<td align=\"right\">".$value['approval1']."</td>
									<td><span class='label label-warning'>Pending</span></td>
									<td>
										<a href=\"$aksi&act=approval&id=$value[budget_id]\" onclick=\"return confirm('Are you sure want to Approve this budget ?')\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Approve</a> |
										<a href=\"?r=detailbudget&mod=64&id=$value[budget_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=budgetapproval&mod=".$_GET['mod']."&act=edit&id=$value[budget_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[budget_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
		$budget_id = "/MS-".$_SESSION['departemen_id']."/".date("m")."/".date("Y");
		?>
			<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
					<input type="hidden" name="budget_prefix" value="<?php echo $budget_id; ?>">
					<div class="form-group">
						<label>Start Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="start_date" size="10" type="text" placeholder="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
					<div class="form-group">
						<label>End Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="end_date" size="10" type="text" placeholder="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>	
					<div class="form-group">
						<label>Copy Detail from Budget ID : </label>
						<select type="text" class="form-control" name="from_budget_id">
							<option></option>
							<?php 
								$data = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' order by budget_id");
								foreach($data as $value){
									echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
								}
							?>
						</select>
					</div>					
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("budget","","budget_id='$_GET[id]'");
		?>
				<div class="col-sm-12 col-md-3 col-lg-3" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<input type="hidden" name="budget_id" value="<?php echo $data[0]['budget_id']; ?>">
					<div class="form-group">
						<label>Start Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="start_date" value="<?php echo $data[0]['start_date']; ?>" size="10" type="text" placeholder="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>
					<div class="form-group">
						<label>End Date : </label>
						<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
							<input class="form-control" name="end_date" value="<?php echo $data[0]['end_date']; ?>" size="10" type="text" placeholder="YYYY-MM-DD">
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
						</div>
					</div>	
					<div class="form-group">
						<label>Copy Detail from Budget ID : </label>
						<select type="text" class="form-control" name="from_budget_id">
							<option></option>
							<?php 
								$data = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' order by budget_id");
								foreach($data as $value){
									echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
								}
							?>
						</select>
					</div>						
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":			
		?>
			//tidak ada kode
		<?php
	break;
	
}

?>