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

$aksi = "modul/mod_budget/aksi_budget.php?r=budget&mod=".$_GET['mod'];
$aksi2 = "modul/mod_budgetcontrol/aksi_budgetcontrol.php?r=budgetcontrol&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		if($_POST['year'] <> ""){
			$_SESSION['year'] = $_POST['year'];
		}
		
		?>
			<div class="col-md-12">
				<a href="?r=budget&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				<!--
					** ditiadakan karena sudah dihandle di sini
					<a href="?r=budgetapproval&mod=63&act=update" class="btn btn-primary"><span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span> Pending Approval</a>
				-->
				<h2>List of Budget</h2>
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
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>No.</td>
						<td>Budget Id</td>
						<td>Periode</td>
						<td>Open</td>
						<td>Additional</td>
						<td>Relokasi</td>
						<td>Reco</td>
						<td>Outstanding</td>
						<td>Approval 1</td>
						<td>Open/Close</td>
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
														  and year(start_date)='".$_SESSION['year']."'");
						}else{
							$jumlah_record = $crud->fetch("v_budget_sumamary","","departemen_id = '".$_SESSION['departemen_id']."' 
														  and year(start_date)='".$_SESSION['year']."' and budget_id='".$_POST['budget_id']."'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['budget_id'] == ""){
							$data = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
												 and year(start_date)='".$_SESSION['year']."' limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("v_budget_summary","","departemen_id = '".$_SESSION['departemen_id']."' 
												 and year(start_date)='".$_SESSION['year']."' and budget_id='".$_POST['budget_id']."'
												 limit $start,$per_hal");	
						}
						
						$no = 1;
						if(count($data) == 0){
							
						}else{
							foreach($data as $value){
								if($value['posting'] == 0 ){ 
									$class="label label-success"; 
									$post = "Open";
									$ket = "Close";
								}else{ 
									$class="label label-danger";
									$post = "Close";
									$ket = "Open";
								}
								
								if(strtoupper($value['status']) == "APPROVED" ){ 
									$class="label label-success"; 
								}else if(strtoupper($value['status']) == "REJECTED" ){
									$class="label label-danger";
								}else{ 
									$class="label label-warning";
								}
								
								
								
								echo "<tr>
										<td align=\"center\">".$no++."</td>
										<td>".$value['budget_id']."</td>
										<td>".date('M y',strtotime($value['start_date']))."</td>
										<td align=\"right\">".number_format($value['open_budget'],0,'.',',')."</td>
										<td align=\"right\">".number_format($value['additional_budget'],0,'.',',')."</td>
										<td align=\"right\">".number_format($value['relokasi_budget'],0,'.',',')."</td>
										<td align=\"right\">".number_format($value['reco_budget'],0,'.',',')."</td>
										<td align=\"right\">".number_format($value['outstanding_budget'],0,'.',',')."</td>
										<td align=\"right\">".$value['approval1']."</td>
										<td>
											<a href=\"$aksi2?r=budget&mod=65&act=openclose&id=$value[budget_id]&ket=$ket\" 
												onClick=\"return confirm('Are you sure want to $ket?');\">
												<span class='$class'>".strtoupper($post)."</span>
											</a>
										</td>
										<td align=\"right\"><span class=\"$class\">".strtoupper($value['status'])."</span></td>
										<td>
											<a href=\"?r=detailbudget&mod=".$_GET['mod']."&id=$value[budget_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
											<a href=\"?r=budget&mod=".$_GET['mod']."&act=edit&id=$value[budget_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
											<a href=\"$aksi&act=approval&id=$value[budget_id]&status=approved\" onclick=\"return confirm('Are you sure want to Approve budget id $value[budget_id] ?')\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\"></span> Approve</a> |
											<a href=\"$aksi&act=approval&id=$value[budget_id]&status=rejected\" onclick=\"return confirm('Are you sure want to Reject budget id $value[budget_id] ?')\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span> Reject</a> |
											<a href=\"$aksi&act=del&id=$value[budget_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Delete</a> 									
										</td>
									</tr>";
									
									$open_budget += $value['open_budget'];
									$additional_budget += $value['additional_budget'];
									$reco_budget += $value['reco_budget'];
									$relokasi_budget += $value['relokasi_budget'];
									$outstanding_budget += $value['outstanding_budget'];
							}
						}
					?>
					<tr>
						<td colspan="3" align="center"><strong>Total</strong></td>
						<td align="right"><b><?php echo number_format($open_budget,0,'.',','); ?></b></td>
						<td align="right"><b><?php echo number_format($additional_budget,0,'.',','); ?></b></td>
						<td align="right"><b><?php echo number_format($reco_budget,0,'.',','); ?></b></td>
						<td align="right"><b><?php echo number_format($relokasi_budget,0,'.',','); ?></b></td>
						<td align="right"><b><?php echo number_format($outstanding_budget,0,'.',','); ?></b></td>
						<td colspan="2"></td>
					</tr>
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
					<div class="form-group">
						<label>Start Date : </label>
						<input type="text" class="form-control" name="budget_id" value="<?php echo $data[0]['budget_id']; ?>" readonly>
					</div>
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
			$data = $crud->fetch("budget","","budget_id='$_GET[id]'");			
		?>
			<div class="col-md-4">				
				<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Budget Id </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Periode : </strong></td><td><?php echo date('d M Y',strtotime($data[0]['start_date']))." s/d ".date('d M Y',strtotime($data[0]['end_date'])); ?></td>
					</tr>
					<tr>
						<td><strong>Approval 1 : </strong></td><td><?php echo $data[0]['approval1']." / ".date('d M Y',strtotime($data[0]['approval1_date'])); ?></td>
					</tr>
					<tr>
						<td><strong>Approval 2 : </strong></td><td><?php echo $data[0]['approval2']." / ".date('d M Y',strtotime($data[0]['approval2_date'])); ?></td>
					</tr>
					<tr>
						<td><strong>Status : </strong></td>
						<td>
							<?php 
								if($data[0]['posting'] == 1){ $class = "label label-danger"; $post = "Closed"; $ket = "Open"; }
								else{ $class = "label label-success"; $post = "Open"; $ket = "Close"; }
							?>
							<span class="<?php echo $class; ?>"><?php echo $post; ?></span>
						</td>
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