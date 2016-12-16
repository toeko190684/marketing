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
			$.post("modul/mod_approvalreco/get_class.php",{ id : $(this).val() },function(data){
				$("#classid").html(data);
				$("#outstanding_budget").val("0");
			});
		});	
			
		
		$("#total").focusout(function(){
			//cari nilai outstanding
			var out = $("#outstanding_budget").val();
			outstanding = out.replace(/,/g,"");
			
			//cari nilai total
			var tot = $("#total").val();
			total = tot.replace(/,/g,"");
			
			//hitung selisih outstanding di kurang total;
			var selisih = eval(outstanding) - eval(total);
			if(selisih < 0 ){
				alert("Total is bigger than Outstanding !!!");
				$(this).val("0");
				$("#total_allow_used").val("0");
			}else{
				$(this).val(total);
				$("#total_allow_used").val(total);
			}
		});	

		$("#total_allow_used").focusout(function(){
			//cari nilai total
			var tot = $("#total").val();
			total = tot.replace(/,/g,"");
			
			//cari nilai total
			var totused = $(this).val();
			totalused = totused.replace(/,/g,"");			
			
			if(totalused > total ){
				alert("Total allow used is bigger than Total !!!");
				$(this).val("0");
			}else{
				$(this).val(totalused);
			}			
		});	
	});				
</script>

<?php 

$aksi = "modul/mod_approvalreco/aksi_approvalreco.php?r=approvalreco&mod=".$_GET['mod'];
$aksi2 = "modul/mod_recocontrol/aksi_recocontrol.php?r=recocontrol&mod=71";


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
				<a href="?r=recobudget&mod=67" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				
				<h2>List of Pending Reco</h2>
				
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
						<td>Reco Id</td>
						<tD>Reco Date</td>
						<td>Class Id</td>
						<td>Description</td>
						<td>Complete</td>
						<td>Approval1</td>
						<td>Approval2</td>
						<td>Total</td>
						<td>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						$jumlah_record = $crud->fetch("v_reco_budget","","budget_id='".$_SESSION['budget_id']."' 
													  and status='pending'");
						
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						$data = $crud->fetch("v_reco_budget","","budget_id='".$_SESSION['budget_id']."' 
											 and status='pending' limit $start,$per_hal");			
						
						
						$no = 1;
						foreach($data as $value){							
							if(strtoupper($value['status'])== "APPROVED"){ $class = "label label-success"; }else{ $class = "label label-danger";}
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['reco_id']."</td>
									<td>".date('d M Y',strtotime($value['reco_date']))."</td>
									<td>".$value['class_id']." - ".$value['class_name']."</td>
									<td>".$value['description']."</td>
									<td>".$value['completed']."<br>".$crud->cetak_tanggal($value['completed_date'])."</td>
									<td>".$value['approval1']."<br>".$crud->cetak_tanggal($value['approval1_date'])."</td>
									<td>".$value['approval2']."<br>".$crud->cetak_tanggal($value['approval2_date'])."</td>
									<td align=\"right\">".number_format($value[total],0,'.',',')."</td>
									<td><span class='$class'>".$value['status']."</span></td>
									<td>
										<a href=\"$aksi2&act=approve&id=$value[budget_id]&recid=$value[reco_id]\" onclick=\"return confirm('Are you sure want approve ? ')\"><span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true\" ></span> Approve</a> | 									
										<a href=\"$aksi2&act=reject&id=$value[budget_id]&recid=$value[reco_id]\" onclick=\"return confirm('Are you sure want reject ? ')\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\" ></span> Reject</a> | 									
										<a href=\"?r=approvalreco&mod=".$_GET['mod']."&act=view&id=$value[budget_id]&recid=$value[reco_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a> |
										<a href=\"?r=approvalreco&mod=".$_GET['mod']."&act=edit&id=$value[budget_id]&recid=$value[reco_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[budget_id]&recid=$value[reco_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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


	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("v_reco_budget","","budget_id='$_GET[id]' and reco_id='".$_GET['recid']."'");
		?>
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="col-sm-12 col-md-12 col-lg-3" >
							<br><br>								
							<div class="form-group">
								<label>Reco Id : </label>
								<div class="form-group">
									<input class="form-control" name="reco_id" value="<?php echo $data[0]['reco_id']; ?>" readonly>
								</div>
							</div>			
							<div class="form-group">
								<label>Reco Date : </label>
								<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="reco_date" size="10" type="text" placeholder="YYYY-MM-DD" value="<?php echo $data[0]['reco_date'];?>">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>	
							<div class="form-group">
								<label>Distributor Id : </label>
								<div class="form-group">
									<select class="form-control" name="distributor_id" required>
										<option value="<?php echo $data[0]['distributor_id']; ?>"><?php echo $data[0]['distributor_id']." - ".ucfirst($data[0]['distributor_name']);?></option>
										<?php
											$dist = $crud->fetch("distributor","distributor_id,distributor_name"," 1 order  by distributor_name asc");
											foreach($dist as $value){
												echo "<option value=\"".$value['distributor_id']."\">".$value['distributor_id']." - ".ucfirst($value['distributor_name'])."</option>";
				
											}
										?>
									</select>
								</div>
							</div>								
							<!--
							<div class="form-group">
								<label>Group Promo Id : </label>
								<div class="form-group">
									<select class="form-control" name="grouppromo_id" required>
										<option value="<?php echo $data[0]['grouppromo_id']; ?>"><?php echo $data[0]['grouppromo_id']." - ".ucfirst($data[0]['grouppromo_name']);?></option>
										<?php
											$grouppromo = $crud->fetch("group_promo","grouppromo_id,grouppromo_name"," 1 order  by grouppromo_name asc");
											foreach($grouppromo as $value){
												echo "<option value=\"".$value['grouppromo_id']."\">".$value['grouppromo_id']." - ".ucfirst($value['grouppromo_name'])."</option>";
				
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label>Promo Type Id : </label>
								<div class="form-group">
									<select class="form-control" name="distributor_id" required>
										<option value="<?php echo $data[0]['distributor_id']; ?>"><?php echo $data[0]['distributor_id']." - ".ucfirst($data[0]['distributor_name']);?></option>
										<?php
											$dist = $crud->fetch("distributor","distributor_id,distributor_name"," 1 order  by distributor_name asc");
											foreach($dist as $value){
												echo "<option value=\"".$value['distributor_id']."\">".$value['distributor_id']." - ".ucfirst($value['distributor_name'])."</option>";
				
											}
										?>
									</select>
								</div>
							</div>
							-->
							<div class="form-group">
								<label>Start Date : </label>
								<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="start_date" size="10" type="text" placeholder="YYYY-MM-DD" value="<?php echo $data[0]['start_date'];?>">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<div class="form-group">
								<label>End Date : </label>
								<div class="input-group date form_date" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
									<input class="form-control" name="end_date" size="10" type="text" placeholder="YYYY-MM-DD" value="<?php echo $data[0]['end_date'];?>">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
							<div class="form-group">
								<label>Group Outlet : </label>
								<div class="form-group">
									<select class="form-control" name="groupoutlet_id" required>
										<option value="<?php echo $data[0]['groupoutlet_id']; ?>"><?php echo $data[0]['groupoutlet_id']." - ".ucfirst($data[0]['groupoutlet_name']);?></option>
										<?php
											$groupoutlet = $crud->fetch("group_outlet","groupoutlet_id,groupoutlet_name"," 1 order  by groupoutlet_name asc");
											foreach($groupoutlet as $value){
												echo "<option value=\"".$value['groupoutlet_id']."\">".$value['groupoutlet_id']." - ".ucfirst($value['groupoutlet_name'])."</option>";
											}
										?>
									</select>
								</div>
							</div>	
						</div>
						<div class="col-sm-12 col-md-12 col-lg-3" >
							<br><br>
							<div class="form-group">
								<label>Sales Target : </label>
								<div class="form-group">
									<input class="form-control" name="sales_target" value="<?php echo number_format($data[0]['sales_target'],0,'.',','); ?>" required> 
								</div>
							</div>	
							<div class="form-group">
								<label>Budget ID : </label>
								<select type="text" class="form-control" name="budget_id" id="budget_id">
									<option value="<?php echo $data[0]['budget_id'];?>"><?php echo $data[0]['budget_id'];?></option>
									<?php 
										$budget = $crud->fetch("budget","","departemen_id ='".$_SESSION['departemen_id']."' 
															 and approval1<>'' and posting=0 order by budget_id");
										foreach($budget as $value){
											echo "<option value=".$value['budget_id'].">".$value['budget_id']."</option>";
										}
									?>
								</select>
							</div>
							<div class="form-group" id="classid">
								<label>Class Id : </label>
								<div class="form-group">
									<select class="form-control" name="class_id" id="class_id" required>
										<option value="<?php echo $data[0]['class_id']; ?>"><?php echo $data[0]['class_id']." - ".ucfirst($data[0]['class_name']);?></option>
										<?php
											$dist = $crud->fetch("v_detail_budget","class_id,class_name"," budget_id='".$data[0]['budget_id']."'");
											foreach($dist as $value){
												echo "<option value=\"".$value['class_id']."\">".$value['class_id']." - ".ucfirst($value['class_name'])."</option>";
				
											}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label>Outstanding Budget : </label>
								<div class="form-group">
									<input class="form-control" name="outstanding_budget" id="outstanding_budget" value="<?php echo number_format($data[0]['outstanding_budget'],0,'.',','); ?>" readonly> 
								</div>
							</div>	
							<div class="form-group" id="account_id">
								<label>Account Id : </label>
								<select type="text" class="form-control" name="account_id" id="accountid">
									<option value="<?php echo $data[0]['account_id'];?>"><?php echo $data[0]['account_id']." - ".$data[0]['account_name']; ?></option>
									<?php 
										$account = $crud->fetch("account","account_id,account_name","departemen_id='".$_SESSION['departemen_id']."' order by departemen_id");
										foreach($account as $value){
											echo "<option value=".$value['account_id'].">".$value['account_id']." - ".$value['account_name']."</option>";
										}
									?>
								</select>
							</div>	
							<div class="form-group">
								<label>Claim Trade Off :</label><br>
								<?php
									if($data[0]['claimtradeoff'] == "uang"){ ?>
										<label class="radio-inline">
											<input type="radio" name="claimtradeoff" value="uang" checked="check">  Uang
										</label>
										<label class="radio-inline">
											<input type="radio" name="claimtradeoff" value="barang">  Barang
										</label>											
									<?php }else{ ?>
										<label class="radio-inline">
											<input type="radio" name="claimtradeoff" value="uang" >  Uang
										</label>
										<label class="radio-inline">
											<input type="radio" name="claimtradeoff" value="barang" checked="check">  Barang
										</label>
									<?php }
								?>
								
							</div>												
						</div>
						<div class="col-sm-12 col-md-12 col-lg-3" >										
							<br><br>
							<div class="form-group">
								<label>Transaction : </label>
								<div class="form-group">
									<input class="form-control" name="transaksi" value="<?php echo $data[0]['transaksi']; ?>" required>
								</div>
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
									<input class="form-control" name="total" id="total" value="<?php echo number_format($data[0]['total'],0,'.',','); ?>" required> 
								</div>
							</div>	
							<div class="form-group">
								<label>Total Allow Used : </label>
								<div class="form-group">
									<input class="form-control" name="total_allow_used" id="total_allow_used" value="<?php echo number_format($data[0]['total_allow_used'],0,'.',','); ?>" required> 
								</div>
							</div>	
							<div class="form-group">
								<label>Complete to : </label>
								<select type="text" class="form-control" name="complete">
									<option value="<?php echo $data[0]['completed'];?>"><?php echo $data[0]['completed'];?></option>
									<?php 
										$user = $crud->fetch("v_user_data","distinct username,full_name","departemen_id ='".$_SESSION['departemen_id']."'
															 order by full_name");
										foreach($user as $value){
											echo "<option value=".$value['username'].">".$value['username']."</option>";
										}
									?>
								</select>
							</div>													
							<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
							<button type="button" class="btn btn-danger" onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
						</div>
					</form>
				</div>				
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":			
		$data = $crud->fetch("v_reco_budget","","budget_id='$_GET[id]' and reco_id='".$_GET[recid]."'");			
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">	
					<div class="col-sm-12 col-md-12 col-lg-12">	
						<a href="?r=approvalreco&mod=<?php echo $_GET[mod]; ?>&id=<?php echo $_GET[id]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
						<a href="?r=approvalreco&mod=<?php echo $_GET[mod]?>&act=edit&id=<?php echo $data[0][budget_id]; ?>&recid=<?php echo $data[0][reco_id]; ?>" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
						<a href="<?php echo $aksi2; ?>&act=approve&id=<?php echo $value[budget_id];?>&recid=<?php echo $value[reco_id]; ?>" class="btn btn-primary" onclick="return confirm('Are you sure to approve ?');"><span class="glyphicon glyphicon-ok"></span> Approve</a>
						<a href="<?php echo $aksi2; ?>&act=reject&id=<?php echo $value[budget_id]; ?>&recid=<?php echo $value[reco_id]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure to reject ?');"><span class="glyphicon glyphicon-remove"></span> Reject</a>
						<br><br>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">				
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Reco Id </strong></td><td><?php echo $data[0]['reco_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Reco Date : </strong></td><td><?php echo date('d M Y',strtotime($data[0]['reco_date'])); ?></td>
							</tr>
							<tr>
								<td><strong>Budget ID : </strong></td><td><?php echo $data[0]['budget_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Departemen : </strong></td><td><?php echo $data[0]['departemen_id']." / ".$data[0]['departemen_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Account : </strong></td><td><?php echo $data[0]['account_id']." / ". $data[0]['account_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Area : </strong></td><td><?php echo $data[0]['area_id']." / ".$data[0]['area_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Distributor : </strong></td><td><?php echo $data[0]['distributor_id']." / ".$data[0]['distributor_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Class : </strong></td><td><?php echo $data[0]['class_id']." / ".$data[0]['class_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Periode : </strong></td><td><?php echo date('d M Y',strtotime($data[0]['start_date']))." s/d ".date('d M Y',strtotime($data[0]['end_date'])); ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">	
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Transaction </strong></td><td><?php echo $data[0]['transaksi']; ?></td>
							</tr>
							<tr>
								<td><strong>Description : </strong></td><td><?php echo $data[0]['description']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Trade Off : </strong></td><td><?php echo $data[0]['claimtradeoff']; ?></td>
							</tr>
							<tr>
								<td><strong>Type of Cost : </strong></td><td><?php echo $data[0]['typeofcost']; ?></td>
							</tr>
							<tr>
								<td><strong>Sales Target : </strong></td><td><?php echo number_format($data[0]['sales_target'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Total : </strong></td><td><?php echo number_format($data[0]['total'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Total allow Max : </strong></td><td><?php echo number_format($data[0]['total_allow_max'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Cost Rasio : </strong></td><td><?php echo $data[0]['cost_rasio']; ?></td>
							</tr>
							<tr>
								<?php
									if($data[0]['status'] == "pending"){ $class = "label label-warning"; }
									elseif($data[0]['status'] == "approved"){ $class = "label label-success"; }
									elseif($data[0]['status'] == "rejected"){ $class = "label label-danger"; }
								?>
								<td><strong>Status : </strong></td><td><span class="<?= $class;?>"><?= strtoupper($data[0]['status']); ?></span></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">	
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Complete :  </strong></td><td><?php echo $data[0]['completed']." / ".$crud->cetak_tanggal($data[0]['completed_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Approval 1 : </strong></td><td><?php echo $data[0]['approval1']." / ".$crud->cetak_tanggal($data[0]['approval1_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Approval 2 : </strong></td><td><?php echo $data[0]['approval2']." / ".$crud->cetak_tanggal($data[0]['approval2_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Create : </strong></td><td><?php echo $data[0]['created_by']." / ".$crud->cetak_tanggal($data[0]['created_date']); ?></td>
							</tr>
							<tr>
								<td><strong>Update : </strong></td><td><?php echo $data[0]['update_by']." / ".$crud->cetak_tanggal($data[0]['update_date']); ?></td>
							</tr>
						</table>
					</div>
				<div>
			</div>
		<?php
	break;
	
}

?>