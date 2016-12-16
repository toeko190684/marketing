<?php 

$aksi = "modul/mod_managedepartemen/aksi_managedepartemen.php?r=managedepartemen&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-9" >
						<a href="?r=managedepartemen&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
						
						<form method="post"  class="form-inline">
							<div class="form-group nav navbar-right" style="padding-right:15px">						
								<input type="text" name="departemen_name" class="form-control" placeholder="Departemen Name">
								<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
							</div>
						</form>
						
						<br><br>
						<table class="table table-bordered table-stripped">
						<thead>
							<tr>
								<td>Departemen</td>
								<td>Approval 1</td>
								<td>Approval 2</td>
								<td>Aktif</td>
								<td>Action</td>
							</tr>
						</thead>
						<tbody>
							<?php 
								//ini adalah halaman paging
								$per_hal = 10;
								if($_POST['departemen_name'] == ""){
									$jumlah_record = $crud->fetch("departemen","","");
								}else{
									$jumlah_record = $crud->fetch("departemen","","departemen_name like '%".$_POST['departemen_name']."%'");
								}
								$jum = count($jumlah_record);
								$halaman = ceil($jum/$per_hal);
								$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
								$start = ($page - 1) * $per_hal;
								
								if($_POST['departemen_name'] == ""){
									$data = $crud->fetch("departemen","","1 limit $start,$per_hal");			
								}else{
									$data = $crud->fetch("departemen","","departemen_name like '%".$_POST['departemen_name']."%' limit $start,$per_hal");	
								}
								
								foreach($data as $value){
									echo "<tr>
											<td>".$value['departemen_id']." - ".$value['departemen_name']."</td>
											<td>".$value['approval1']."</td>
											<td>".$value['approval2']."</td>
											<td>".$value['aktif']."</td>
											<td>
												<a href=\"?r=managedepartemen&mod=".$_GET['mod']."&act=view&id=$value[departemen_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
												<a href=\"?r=managedepartemen&mod=".$_GET['mod']."&act=edit&id=$value[departemen_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
												<a href=\"$aksi&act=del&id=$value[departemen_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
				</div>
			</div>
		<?php
	break;

/*  jika pilihannya adalah add atau tambah */	
	case  "add":
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="col-sm-12 col-md-12 col-lg-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
						<div class="form-group">
							<label>Departemen Id : </label>
							<input type="text" name="departemen_id" class="form-control" placeholder ="Departemen Id" required>
						</div>
						<div class="form-group">
							<label>Departemen Name : </label>
							<input type="text" name="departemen_name" class="form-control" placeholder ="Departemen Name" required>
						</div>	
						<div class="form-group">
							<label>Aktif : </label>
							<select  name="aktif" class="form-control" >
								<option value="Y">Aktif</option>
								<option value="N">Non Aktif</option>
							</select>
						</div>						
						<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("departemen","","departemen_id='$_GET[id]'");
		?>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="col-sm-12 col-md-12 col-lg-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="form-group">
							<label>Departemen Id : </label>
							<input type="text" name="departemen_id" class="form-control" placeholder ="Departemen Id" value="<?php echo $data[0]['departemen_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Departemen Name : </label>
							<input type="text" name="departemen_name" class="form-control" placeholder ="Departemen Name" value="<?php echo $data[0]['departemen_name']; ?>" required>
						</div>	
						<div class="form-group">
							<label>Approval 1 : </label>
							<select  name="approval1" class="form-control" >
								<option value="<?php echo $data[0]['approval1'];?>"><?php echo $data[0]['approval1']; ?></option>
								<?php
									$user = $crud->fetch("v_user_data","distinct username,full_name",
														 "departemen_id='".$_SESSION['departemen_id']."' order by full_name");
									foreach($user as $value){
										echo "<option value=\"$value[username]\">".$value['full_name']."</option>";
									}
								?>							
							</select>
						</div>
						<div class="form-group">
							<label>Approval 2 : </label>
							<select  name="approval2" class="form-control" >
								<option value="<?php echo $data[0]['approval2'];?>"><?php echo $data[0]['approval2']; ?></option>
								<?php
									$user = $crud->fetch("v_user_data","distinct username,full_name",
														 "departemen_id='".$_SESSION['departemen_id']."' order by full_name");
									foreach($user as $value){
										echo "<option value=\"$value[username]\">".$value['full_name']."</option>";
									}
								?>							
							</select>
						</div>	
						<div class="form-group">
							<label>Aktif : </label>
							<?php
								if($data[0]['aktif'] == "Y"){ $ket ="Aktif"; }else{ $ket = "Non Aktif"; }
							?>
							<select  name="aktif" class="form-control" >
								<option value="<?php echo $data[0]['aktif'];?>"><?php echo $ket; ?></option>
								<option value="Y">Aktif</option>
								<option value="N">Non Aktif</option>
							</select>
						</div>						
						<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
			</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("departemen","","departemen_id='$_GET[id]'");
			
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-4">
						<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
						<br><br>			
						<table class="table table-stripped table-hover">
							<tr>
								<td><strong>Departemen Id : </strong></td><td><?php echo $data[0]['departemen_id']; ?></td>
							</tr>
							<tr>
								<td><strong>Departemen Name : </strong></td><td><?php echo $data[0]['departemen_name']; ?></td>
							</tr>
							<tr>
								<td><strong>Approval 1 : </strong></td><td><?php echo $data[0]['approval1']; ?></td>
							</tr>
							<tr>
								<td><strong>Approval 2 : </strong></td><td><?php echo $data[0]['approval2']; ?></td>
							</tr>
							<tr>
								<td><strong>Aktif : </strong></td><td><?php echo $data[0]['aktif']; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		<?php
	break;
	
}

?>