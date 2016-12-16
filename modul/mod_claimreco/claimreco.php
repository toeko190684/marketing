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
		
		$("#reco_id").focusout(function(){
			$.post("modul/mod_claimreco/get_reco.php",{ id : $(this).val() },function(data){
				var obj = jQuery.parseJSON(data);
				
				
				$("#distributor_id").val(obj.distributor_id);
				$("#distributor_name").val(obj.distributor_name);
				$("#reco_outstanding").val(obj.outstanding);
				$("#description").val(obj.description);
				$("#account_id").val(obj.account_id);
				$("#account_name").val(obj.account_name);
				
				//fokus pada vendor id
				$("#vendor_id").focus();
			});
		});
		
		$("#vendor_id").change(function(){
			var vendor_id = $(this).val();
			
			$.post("modul/mod_claimreco/get_vendor.php",{ id : vendor_id },function(data){
				var obj = jQuery.parseJSON(data);
				
				$("#vendor_name").val(obj.vendor_name);
				$("#ap_account_type").val(obj.ap_account_type);
				$("#ap_account_id").val(obj.ap_account_id);
				
				//fokus pada po so number_format
				$("#po_so_number").focus();
			});
		});
		
		$("#claim_approved_ammount").focusout(function(){
			var claim_ap = $(this).val();
			var reco_out = $("#reco_outstanding").val();
			var ppn = $("#ppn").val();
			if(eval(claim_ap) > eval(reco_out)){
				alert("claim approved ammout is bigger than reco outstanding!!");
				$(this).val("0");
				$("#total_claim_approved_ammount").val("0");
				$(this).focus();
			}else{
				var total = (100+eval(ppn))*claim_ap/100;
				$("#total_claim_approved_ammount").val(total);
			}
		});
	});
			
</script>


<?php 

$aksi = "modul/mod_claimreco/aksi_claimreco.php?r=claimreco&mod=".$_GET['mod'];


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
				<a href="?r=recobudget&mod=67&id=<?php echo $_SESSION['budget_id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=claimreco&mod=<?php echo $_GET[mod]; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<h2>List of Claim</h2>
				
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
						<td>Claim Id</td>
						<tD>Claim Date</td>
						<td>Reco Id</td>
						<td>Account Id</td>
						<td>Outstanding</td>
						<td>Claim Approved Ammount</td>
						<tD>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						$jumlah_record = $crud->fetch("v_claim_reco","","budget_id='".$_SESSION['budget_id']."'
													  and departemen_id = '".$_SESSION['departemen_id']."'");
						
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						$data = $crud->fetch("v_claim_reco","","budget_id='".$_SESSION['budget_id']."' 
											 and departemen_id = '".$_SESSION['departemen_id']."'
											 limit $start,$per_hal");			
						
						
						$no = 1;
						foreach($data as $value){							
							if(strtoupper($value['status']) == "PENDING"){
								$class = "label label-warning";
							}elseif(strtoupper($value['status']) == "APPROVED"){
								$class = "label label-success";
							}else{
								$class = "label label-danger";
							}
							
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['claim_id']."</td>
									<td>".$crud->cetak_tanggal($value['claim_date'])."</td>
									<td>".$value['reco_id']."</td>
									<td>".$value['account_id']." - ".$value['account_name']."</td>
									<td align=\"right\">".number_format($value[outstanding],0,'.',',')."</td>
									<td align=\"right\">".number_format($value[claim_approved_ammount],0,'.',',')."</td>
									<td><span class='$class'>".strtoupper($value['status'])."</span></td>
									<td>
										<a href=\"$aksi&act=approve&id=$value[budget_id]&clid=$value[claim_id]\" onclick=\"return confirm('Are you sure want to approve ? ')\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\" ></span> Approve</a> |									
										<a href=\"$aksi&act=reject&id=$value[budget_id]&clid=$value[claim_id]\" onclick=\"return confirm('Are you sure want to reject ? ')\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\" ></span> Reject</a> |									
										<a href=\"?r=claimreco&mod=".$_GET['mod']."&act=view&id=$value[claim_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=claimreco&mod=".$_GET['mod']."&act=edit&id=$value[claim_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[claim_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
		$claim_id = "/CL-".$_SESSION['departemen_id']."/".date("m")."/".date("Y");
		?>
			<div class="col-sm-12 col-md-12 col-lg-12" >				
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
						<div class="col-sm-12 col-md-3 col-lg-3" >						
							<input type="hidden" name="claim_prefix" value="<?php echo $claim_id; ?>">
							<div class="form-group">
								<label>Claim Id : </label>
								<div class="form-group">
									<input class="form-control" name="claim_id" readonly>
								</div>
							</div>		
							<div class="form-group">
								<label>Claim Date : </label>
								<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="claim_date" size="10" type="text" value="<?php echo date('Y-m-d'); ?>" placeholder="YYYY-MM-DD">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<div class="form-group">
								<label>Claim Number Dist : </label>
								<div class="form-group">
									<input class="form-control" name="claim_number_dist" id="claim_number_dist">
								</div>
							</div><div class="form-group">
								<label>Reco Id : </label>
								<div class="form-group">
									<input class="form-control" name="reco_id" id="reco_id" required>
								</div>
							</div>
							<div class="form-group">
								<label>Distributor Id : </label>
								<div class="form-group">
									<input class="form-control" name="distributor_id" id="distributor_id" readonly>
								</div>
							</div>
							<div class="form-group">
								<label>Distributor Name : </label>
								<div class="form-group">
									<input class="form-control" name="distributor_name" id="distributor_name" readonly>
								</div>
							</div>
							<div class="form-group">
								<label>Reco Outstanding : </label>
								<div class="form-group">
									<input class="form-control" name="reco_outstanding" id="reco_outstanding" value="0" readonly>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-3 col-lg-3" >
							<div class="form-group">
								<label>Account Id : </label>
								<div class="form-group">
									<input class="form-control" name="account_id" id="account_id" placeholder="Account Id" readonly>
								</div>
							</div>
							<div class="form-group">
								<label>Account Name : </label>
								<div class="form-group">
									<input class="form-control" name="account_name" id="account_name" placeholder="Account Name" readonly>
								</div>
							</div>
							<div class="form-group" id="account_id">
								<label>Vendor Id : </label>
								<select type="text" class="form-control" name="vendor_id" id="vendor_id" required>
									<option></option>
									<?php 
										$data = $crud->fetch("vendor","vendor_id,vendor_name","1 order by vendor_id");
										foreach($data as $value){
											echo "<option value=".$value['vendor_id'].">".$value['vendor_id']." - ".$value['vendor_name']."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label>Vendor Name : </label>
								<div class="form-group">
									<input class="form-control" name="vendor_name" id="vendor_name" placeholder="Vendor Name" readonly>
								</div>
							</div>
							<div class="form-group">
								<label>AP Account Type : </label>
								<div class="form-group">
									<input class="form-control" name="ap_account_type" id="ap_account_type" placeholder="AP Account Type" readonly>
								</div>
							</div>
							<div class="form-group">
								<label>AP Account Id : </label>
								<div class="form-group">
									<input class="form-control" name="ap_account_id" id="ap_account_id" placeholder="AP Account Id" readonly>
								</div>
							</div>	
							<div class="form-group">
								<label>PO SO Number : </label>
								<div class="form-group">
									<input class="form-control" name="po_so_number" id="po_so_number">
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-3 col-lg-3" >
							<div class="form-group">
								<label>PPN : </label>
								<div class="form-group">
									<input class="form-control" name="ppn" id="ppn" value="0">
								</div>
							</div>
							<div class="form-group">
								<label>No Faktur Pajak : </label>
								<div class="form-group">
									<input class="form-control" name="nomor_faktur_pajak">
								</div>
							</div>
							<div class="form-group">
								<label>Description : </label>
								<div class="form-group">
									<textarea class="form-control" name="description" id="description" rows="5" required></textarea>
								</div>
							</div>
							<div class="form-group">
								<label>Claim Approved Ammount : </label>
								<div class="form-group">
									<input class="form-control" name="claim_approved_ammount" id="claim_approved_ammount" value="0" required>
								</div>
							</div>
							<div class="form-group">
								<label>Claim Approved Ammount + ppn : </label>
								<div class="form-group">
									<input class="form-control" name="total_claim_approved_ammount" id="total_claim_approved_ammount" value="0" readonly>
								</div>
							</div>						
							<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
							<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
						</div>						
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
					<div class="form-group" id="account_id">
						<label>Account Id : </label>
						<select type="text" class="form-control" name="account_id">
							<option value="<?php echo $data[0]['account_id'];?>"><?php echo $data[0]['account_id']." - ".$data[0]['account_name']; ?></option>
							<?php 
								$account = $crud->fetch("v_detail_budget","account_id,account_name","budget_id='".$_SESSION['budget_id']."' order by budget_id");
								foreach($account as $value){
									echo "<option value=".$value['account_id'].">".$value['account_id']." - ".$value['account_name']."</option>";
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
		$data = $crud->fetch("v_claim_reco","","claim_id='$_GET[id]'");			
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<a href="?r=claimreco&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $data[0][budget_id]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
						<a href="<?= $aksi; ?>&act=approve&id=<?= $data[0][claim_id]; ?>" onclick="return confirm('Are you sure want approve claim id : <?= $data[0]['claim_id']; ?> ? ')" class="btn btn-success"><span class="glyphicon glyphicon-ok" aria-hidden="true" ></span> Approve</a> 									
						<a href="<?= $aksi; ?>&act=reject&id=<?= $data[0][claim_id]; ?>" onclick="return confirm('Are you sure want reject claim id : <?= $data[0]['claim_id']; ?> ? ')" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span> Reject</a> 									
						<br><br>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-4 col-lg-4">						
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Claim Id </strong></td><td><?php echo $data[0]['claim_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Date : </strong></td><td><?php echo $crud->cetak_tanggal($data[0]['claim_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Claim Number Dist : </strong></td><td><?php echo $data[0]['claim_number_dist']; ?></td>
							</tr>
							<tr>
								<td><strong>Budget Id : </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Reco Id : </strong></td><td><?php echo $data[0]['reco_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Account Id : </strong></td><td><?php echo $data[0]['account_id']." / ".$data[0]['account_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Distributor : </strong></td><td><?php echo $data[0]['distributor_id']." / ".$data[0]['distributor_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Description : </strong></td><td><?php echo $data[0]['description']; ?></td>
							</tr>
							<tr>
								<td><strong>Total Reco: </strong></td><td><?php echo number_format($data[0]['total_reco'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Total Allow Used Reco: </strong></td><td><?php echo number_format($data[0]['total_allow_used_reco'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Total Claim: </strong></td><td><?php echo number_format($data[0]['total_claim'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Outstanding: </strong></td><td><?php echo number_format($data[0]['outstanding'],0,'.',','); ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4">	
						<table class="table table-stripped table-hover">					
							<tr>
								<td><strong>PO SO Number : </strong></td><td><?php echo $data[0]['po_so_number']; ?></td>
							</tr>
							<tr>
								<td><strong>PPN : </strong></td><td><?= $data[0]['ppn']; ?> %</td>
							</tr>
							<tr>
								<td><strong>PPH : </strong></td><td><?php echo $data[0]['pph']; ?></td>
							</tr>	
							<tr>
								<td><strong>Nomor Faktur Pajak : </strong></td><td><?php echo $data[0]['nomor_faktur_pajak']; ?></td>
							</tr>
							<tr>
								<td><strong>Deskripsi : </strong></td><td><?php echo $data[0]['deskripsi']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Approved Ammount : </strong></td><td><?= number_format($data[0]['claim_approved_ammount'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Total Claim Approved Ammount : </strong></td><td><?= number_format($data[0]['total_claim_approved_ammount'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Vendor Id : </strong></td><td><?php echo $data[0]['vendor_id']." / ".$data[0]['vendor_name']; ?></td>
							</tr>
							<tr>
								<td><strong>AP Account Type : </strong></td><td><?php echo $data[0]['ap_account_type']; ?></td>
							</tr>
							<tr>
								<td><strong>AP Account ID : </strong></td><td><?php echo $data[0]['ap_account_id']; ?></td>
							</tr>
							<?php 
								if(strtoupper($data[0]['status']) == "PENDING"){
									$class = "label label-warning";
								}elseif(strtoupper($data[0]['status']) == "APPROVED"){
									$class = "label label-success";
								}else{
									$class = "label label-danger";
								}							
							?>
							<tr>
								<td><strong>Status : </strong></td><td><span class='<?=$class?>'><?= strtoupper($data[0]['status']) ?></span></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4">	
						<table class="table table-stripped table-hover">	
							<tr>
								<td><strong>Journal Id : </strong></td><td><?php echo $data[0]['journal_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Approve By : </strong></td><td><?php echo $data[0]['approve_by']." / ".$crud->cetak_tanggal($data[0]['approve_date']); ?></td>
							</tr>							
							<tr>
								<td><strong>Created By : </strong></td><td><?php echo $data[0]['created_by']." / ".$crud->cetak_tanggal($data[0]['created_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Update By : </strong></td><td><?php echo $data[0]['update_by']." / ".$crud->cetak_tanggal($data[0]['update_date']); ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<?php
	break;
	
}

?>