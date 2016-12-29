<?php 

if($_POST['year'] != ""){
	$_SESSION['year'] = $_POST['year'];
}


$aksi = "modul/mod_claimbnk/aksi_claimbnk.php?r=claimbnk&mod=".$_GET['mod'];

switch($_GET['act']){
	default :	
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h2>List of Claim BNK</h2>
				
				<form method="post"  class="form-inline" >
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Year : </label>
						<select name="year" class="form-control">
							<option value="<?php  echo $_SESSION['year']; ?>"><?php echo $_SESSION['year'];?></option>
							<?php 
								$data = $crud->fetch("v_claim_bnk_header","distinct year(claim_date) as year","year(claim_date)='".$_SESSION['year']."'");
								foreach($data as $value){
									echo "<option value=\"$value[year]\">".$value['year']."</option>";
								}
							?>
						</select>
						<label>Claim Number : </label>
						<input type="text" name="claim_number_system" class="form-control">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<td>No.</td>
						<td>Claim Number</td>
						<tD>Claim Date</td>
						<td>Distributor Id</td>
						<td>Cost of promo</td>
						<td>Claim Approved Ammount</td>
						<tD>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						require_once "pagelink_top.php";
						
						$per_hal = 10;
						if(trim($_POST['claim_number_system']) == ""){
							$jumlah_record = $crud->fetch("v_claim_bnk_header","","year(claim_date) = '".$_SESSION['year']."'");
						}else{
							$jumlah_record = $crud->fetch("v_claim_bnk_header","","year(claim_date) = '".$_SESSION['year']."' and claim_number_system='".$_POST['claim_number_system']."'");
						}						
						
						$no = 1 + $posisi;
						
						if(trim($_POST['claim_number_system']) == ""){							
							$data = $crud->fetch("v_claim_bnk_header","","year(claim_date) = '".$_SESSION['year']."' limit $posisi,$batas");
						}else{
							$data = $crud->fetch("v_claim_bnk_header","","year(claim_date) = '".$_SESSION['year']."' and claim_number_system='".$_POST['claim_number_system']."'");	
						}						
						
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
									<td>".$value['claim_number_system']."</td>
									<td>".$crud->cetak_tanggal($value['claim_date'])."</td>
									<td>".$value['distributor_id']."</td>
									<td align=\"right\">".number_format($value[claim_approved_ammount],0,'.',',')."</td>
									<td><span class='$class'>".strtoupper($value['status'])."</span></td>
									<td>
										<a href=\"$aksi&act=approve&id=$value[claim_number_system]\" onclick=\"return confirm('Are you sure want to approve  claim number : $value[claim_number_system]? ')\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\" ></span> Approve</a> |									
										<a href=\"$aksi&act=reject&id=$value[claim_number_system]\" onclick=\"return confirm('Are you sure want to reject claim number : $value[claim_number_system]? ')\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\" ></span> Reject</a> |									
										<a href=\"?r=claimbnk&mod=".$_GET['mod']."&act=view&id=$value[claim_number_system]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=claimbnk&mod=".$_GET['mod']."&act=edit&id=$value[claim_number_system]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> 
										</td>
								</tr>";
						}
					?>
				</tbody>
				</table>
				
				<?php 
					include "pagelink_bottom.php";
				?>
				
			</div>
		<?php
	break;

	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("v_claim_bnk","","claim_number_system='$_GET[id]'");
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<a href="?r=claimbnk&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $data[0][claim_number_system]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
						<br><br>
					</div>
			</div>
			
			<div class="col-sm-12 col-md-12 col-lg-12" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<div class="col-sm-12 col-md-12 col-lg-3" >
						<div class="form-group">
							<label>Claim Number system : </label>
							<div class="form-group">
								<input class="form-control" name="claim_number_system" value="<?php echo $data[0]['claim_number_system']; ?>" readonly>
							</div>
						</div>								
						<div class="form-group">
							<label>Claim Date : </label>
							<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
								<input class="form-control" name="claim_date" size="10" type="text" placeholder="YYYY-MM-DD" value="<?= $data[0]['claim_date'];?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</div>
						</div>
						<div class="form-group">
							<label>Claim Number Dist : </label>
							<div class="form-group">
								<input class="form-control" name="claim_number_dist" value="<?php echo $data[0]['claim_number_dist']; ?>" readonly>
							</div>
						</div>		
						<div class="form-group">
							<label>Distributor ID : </label>
							<select type="text" class="form-control" name="distributor_id" id="distributor_id">
								<option><?php echo $data[0]['distributor_id'];?></option>
								<?php 
									$budget = $crud->fetch("distributor","","1 order by distributor_name");
									foreach($budget as $value){
										echo "<option value=".$value['distributor_id'].">".$value['distributor_id']." - ".$value['distributor_name']."</option>";
									}
								?>
							</select>
						</div>	
						<div class="form-group">
							<label>Kode Promo : </label>
							<div class="form-group">
								<?php
									$reco = $crud->fetch("v_claim_promo","","claim_number_system='".$data[0]['claim_number_system']."'");
									foreach($reco as $row ){
										if($row['cek'] == 0){ $class = "label label-danger"; }else{ $class = "label label-success"; }
										echo $row['kode_promo']." : ".number_format($row['costofpromo'],0,'.',',').
											 " <span class='$class'>".$row['status']."</span><br>";
									}
								?>
							</div>
						</div>
						<div class="form-group">
							<label>Cost of Promo : </label>
							<div class="form-group">
								<input class="form-control" name="costofpromo" value="<?= number_format($data[0]['costofpromo'],0,'.',','); ?>" readonly>
							</div>
						</div>		
						<div class="form-group">
							<label>Cost of Promo Left : </label>
							<div class="form-group">
								<input class="form-control" name="costofpromoleft" value="<?= number_format($data[0]['claim_number_system'],0,'.',','); ?>" readonly>
							</div>
						</div>	
					</div>
					<div class="col-sm-12 col-md-12 col-lg-3" >	
						<div class="form-group">
							<label>PO SO Number : </label>
							<div class="form-group">
								<input class="form-control" name="po_so_number" value="<?= $data[0]['po_so_number']; ?>" readonly>
							</div>
						</div>			
						<div class="form-group" >
							<label>Account Id : </label>
							<select type="text" class="form-control" name="account_id">
								<option value="<?php echo $data[0]['coa'];?>"><?php echo $data[0]['coa']." - ".$data[0]['coa_name']; ?></option>
								<?php 
									$account = $crud->fetch("account","","1 order by account_id");
									foreach($account as $value){
										echo "<option value=".$value['account_id'].">".$value['account_id']." - ".$value['account_name']."</option>";
									}
								?>
							</select>
						</div>				
						<div class="form-group" >
							<label>Vendor Id : </label>
							<select type="text" class="form-control" name="vendor_id">
								<option value="<?php echo $data[0]['vendor_id'];?>"><?php echo $data[0]['vendor_id']." - ".$data[0]['vendor_name']; ?></option>
								<?php 
									$vendor = $crud->fetch("vendor","","1 order by vendor_id");
									print_r($vendor);
									foreach($vendor as $value){
										echo "<option value=".$value['vendor_id'].">".$value['vendor_id']." - ".$value['vendor_name']."</option>";
									}
								?>
							</select>
						</div>	
						<div class="form-group">
							<label>AP Account Id : </label>
							<div class="form-group">
								<input class="form-control" name="ap_account_id" value="<?= $data[0]['ap_account_id'] ?>" readonly> 
							</div>
						</div>
						<div class="form-group">
							<label>AP Account Type : </label>
							<div class="form-group">
								<input class="form-control" name="ap_account_type" value="<?= $data[0]['ap_account_type'] ?>" readonly> 
							</div>
						</div>
						<div class="form-group">
							<label>PPN : </label>
							<div class="form-group">
								<input class="form-control" name="ppn" value="<?= number_format($data[0]['ppn'],0,'.',','); ?>" readonly>
							</div>
						</div>				
						<div class="form-group">
							<label>Nomor Faktur Pajak : </label>
							<div class="form-group">
								<input class="form-control" name="nomor_faktur_pajak" value="<?= $data[0]['nomor_faktur_pajak']; ?>" readonly>
							</div>
						</div>	
					</div>
					<div class="col-sm-12 col-md-12 col-lg-3" >	
						<div class="form-group">
							<label>Description : </label>
							<div class="form-group">
								<textarea class="form-control" name="deskripsi" required><?php echo $data[0]['deskripsi']; ?></textarea>
							</div>
						</div>						
						<div class="form-group">
							<label>Claim Approved Ammount : </label>
							<div class="form-group">
								<input class="form-control" name="claim_approved_ammount" value="<?php echo number_format($data[0]['claim_approved_ammount'],0,'.',','); ?>" readonly> 
							</div>
						</div>		
										
						<div class="form-group">
							<label>Total Claim Approved Ammount : </label>
							<div class="form-group">
								<input class="form-control" name="total_claim_approved_ammount" value="<?php echo number_format($data[0]['total_claim_approved_ammount'],0,'.',','); ?>" readonly> 
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
		$data = $crud->fetch("v_claim_bnk","","claim_number_system='$_GET[id]'");			
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<a href="?r=claimbnk&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $data[0][claim_number_system]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
						<a href="<?= $aksi; ?>&act=approve&id=<?= $data[0][claim_number_system]; ?>" onclick="return confirm('Are you sure want approve claim number : <?= $data[0]['claim_number_system']; ?> ? ')" class="btn btn-success"><span class="glyphicon glyphicon-ok" aria-hidden="true" ></span> Approve</a> 									
						<a href="<?= $aksi; ?>&act=reject&id=<?= $data[0][claim_number_system]; ?>" onclick="return confirm('Are you sure want reject claim number : <?= $data[0]['claim_number_system']; ?> ? ')" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true" ></span> Reject</a> 									
						<br><br>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-4 col-lg-4">						
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Claim Number System </strong></td><td><?php echo $data[0]['claim_number_system']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Date : </strong></td><td><?php echo $crud->cetak_tanggal($data[0]['claim_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Claim Number Dist : </strong></td><td><?php echo $data[0]['claim_number_dist']; ?></td>
							</tr>
							<tr>
								<td><strong>Kode Promo : </strong></td>
								<td>
									<?php 
										$reco = $crud->fetch("v_claim_promo","","claim_number_system='".$_GET['id']."'");
										foreach($reco as $row){
											if($row['cek'] == 0){ $class = "label label-danger"; }else{ $class = "label label-success";}
											echo $row['kode_promo']." = ".
												 number_format($row['costofpromo'],0,'.',',')." ".
												 "<span class=$class>".$row['status']."</span>"; 
										}
									?>
								</td>
							</tr>
							<tr>
								<td><strong>COA : </strong></td><td><?php echo $data[0]['coa']." / ".$data[0]['coa_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Distributor : </strong></td><td><?php echo $data[0]['distributor_id']." / ".$data[0]['distributor_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Cost of Promo: </strong></td><td><?php echo number_format($data[0]['costofpromo'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Cost of Promo Left: </strong></td><td><?php echo number_format($data[0]['costofpromoleft'],0,'.',','); ?></td>
							</tr>					
							<tr>
								<td><strong>PO SO Number : </strong></td><td><?php echo $data[0]['po_so_number']; ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4">	
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>PPN : </strong></td><td><?= number_format($data[0]['ppn'],2,',','.'); ?> %</td>
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
						</table>
					</div>
					<div class="col-sm-12 col-md-4 col-lg-4">	
						<table class="table table-stripped table-hover">	
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
							<tr>
								<td><strong>Journal Id : </strong></td><td><?php echo $data[0]['journal_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Approve By : </strong></td><td><?php echo $data[0]['approve_by']." / ".$crud->cetak_tanggal($data[0]['approve_date']); ?></td>
							</tr>							
							<tr>
								<td><strong>Created By : </strong></td><td><?php echo $data[0]['created_by']." / ".$crud->cetak_tanggal($data[0]['created_date']); ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<?php
	break;
	
}

?>