<?php 

$aksi = "modul/mod_recobnk/aksi_recobnk.php?r=recobnk&mod=".$_GET['mod'];


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
				
				<h2>List of Reco BNK</h2>
				
				<form method="post"  class="form-inline" >
					<div class="form-group nav navbar-right" style="padding-right:15px">	
						<label>Year : </label>
						<select name="year" class="form-control">
							<option value="<?php  echo $_SESSION['year']; ?>"><?php echo $_SESSION['year'];?></option>
							<?php 
								$data = $crud->fetch("reco_bnk","distinct year(tgl_promo)as year","");
								foreach($data as $value){
									echo "<option value=\"$value[year]\">".$value['year']."</option>";
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
						<td>Kode Promo</td>
						<tD>Promo Date</td>
						<td>Account Id</td>
						<td>Title</td>
						<td>Cost of Promo</td>
						<td>Status</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php						
						//ini adalah halaman paging
						
						$per_hal = 10;
						$jumlah_record = $crud->fetch("reco_bnk","","year(tgl_promo)='".$_SESSION['year']."'");
						
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						$data = $crud->fetch("reco_bnk","","year(tgl_promo)='".$_SESSION['year']."'
											 limit $start,$per_hal");			
						
						
						$no = 1;
						foreach($data as $value){							
							if(strtoupper($value['status'])== "APPROVED"){ $class = "label label-success"; }else{ $class = "label label-danger";}
							echo "<tr>
									<td align=\"center\">".$no++."</td>
									<td>".$value['kode_promo']."</td>
									<td>".date('d M Y',strtotime($value['tgl_promo']))."</td>
									<td>".$value['account_id']."</td>
									<td>".$value['title']."</td>
									<td align=\"right\">".number_format($value[cost_of_promo],0,'.',',')."</td>
									<td><span class='$class'>".$value['status']."</span></td>
									<td>
										<a href=\"?r=recobnk&mod=".$_GET['mod']."&act=view&id=$value[kode_promo]&recid=$value[kode_promo]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> Detail</a>
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

/*   jika pilihan kondisinya adalah view */ 
	case "view":			
		$data = $crud->fetch("reco_bnk","","kode_promo='".$_GET[recid]."'");			
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">	
					<div class="col-sm-12 col-md-12 col-lg-12">	
						<a href="?r=recobnk&mod=<?php echo $_GET[mod]; ?>" class="btn btn-primary" ><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>
						<br><br>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">				
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Kode Promo </strong></td><td><?php echo $data[0]['kode_promo']; ?></td>
							</tr>
							<tr>
								<td><strong>Promo Date : </strong></td><td><?php echo date('d M Y',strtotime($data[0]['tgl_promo'])); ?></td>
							</tr>
							<tr>
								<td><strong>Area : </strong></td><td><?= $data[0]['area_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Distributor : </strong></td><td><?= $data[0]['distributor_id']." / ".$data[0]['distributor_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Group Promo Id : </strong></td><td><?php echo $data[0]['grouppromo_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Promo Type Id : </strong></td><td><?php echo $data[0]['promotype_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Class : </strong></td><td><?php echo $data[0]['class_id']." / ".$data[0]['class_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Account : </strong></td><td><?= $data[0]['account_id'] ?></td>
							</tr>
							<tr>
								<td><strong>Kode Budget : </strong></td><td><?php echo $data[0]['kode_budget']; ?></td>
							</tr>
							<tr>
								<td><strong>Periode : </strong></td><td><?= $crud->cetak_tanggal($data[0]['tgl_awal'])." s/d ".$crud->cetak_tanggal($data[0]['tgl_akhir']); ?></td>
							</tr>
							<tr>
								<td><strong>Total Sales Target : </strong></td><td><?= number_format($data[0]['sales_target'],0,'.',','); ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">	
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Background  :</strong></td><td><?= $data[0]['background']; ?></td>
							</tr>
							<tr>
								<td><strong>Promo Mechanisme : </strong></td><td><?= $data[0]['promo_mechanisme']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Mechanisme : </strong></td><td><?= $data[0]['claim_mechanisme']; ?></td>
							</tr>
							<tr>
								<td><strong>Claim Trade Off : </strong></td><td><?= $data[0]['claimtradeoff']; ?></td>
							</tr>
							<tr>
								<td><strong>Cost Of Promo : </strong></td><td><?= number_format($data[0]['cost_of_promo'],0,'.',','); ?></td>
							</tr>
							<tr>
								<td><strong>Type of Cost : </strong></td><td><?= $data[0]['typeofcost'] ?></td>
							</tr>
							<tr>
								<td><strong>Cost Rasio : </strong></td><td><?= $data[0]['cost_rasio']; ?></td>
							</tr>
						</table>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4">	
						<table class="table table-stripped table-hover">
							<tr>
								<?php
									if($data[0]['status'] == "pending"){ $class = "label label-warning"; }
									elseif($data[0]['status'] == "approved"){ $class = "label label-success"; }
									elseif($data[0]['status'] == "rejected"){ $class = "label label-danger"; }
								?>
								<td><strong>Status : </strong></td><td><?= $class ?><span class="<?= $class ?>"><?php echo $data[0]['status']; ?></span></td>
							</tr>
							<tr>
								<td><strong>Complete :  </strong></td><td><?= $data[0]['complete']." / ".$crud->cetak_tanggal($data[0]['tgl_complete']); ?></td>
							</tr>
							<tr>
								<td><strong>Approval 1 : </strong></td><td><?= $data[0]['approval1']." / ".$crud->cetak_tanggal($data[0]['tgl_approval1']); ?></td>
							</tr>
							<tr>
								<td><strong>Approval 2 : </strong></td><td><?= $data[0]['approval2']." / ".$crud->cetak_tanggal($data[0]['tgl_approval2']); ?></td>
							</tr>
							<tr>
								<td><strong>Create : </strong></td><td><?= $data[0]['created_by']." / ".$crud->cetak_tanggal($data[0]['created_date']); ?></td>
							</tr>
						</table>
					</div>
				<div>
			</div>
		<?php
	break;
}

?>