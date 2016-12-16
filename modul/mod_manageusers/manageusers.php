<?php 

$aksi = "modul/mod_manageusers/aksi_manageusers.php?r=manageusers&mod=".$_GET['mod'];

switch($_GET['act']){
	default :		
		?>
			<div class="col-md-12" >
				<a href="?r=manageusers&mod=<?php echo $_GET['mod']; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post" action="" class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="username" class="form-control" placeholder="Username">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Username</td>
						<td>Full Name</td>
						<td>Handphone</td>
						<td>Email</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						//ini adalah halaman paging
						$per_hal = 10;
						if($_POST['username'] == ""){
							$jumlah_record = $crud->fetch("user","","");
						}else{
							$jumlah_record = $crud->fetch("user","","username like '%".$_POST['username']."%'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['username'] == ""){
							$data = $crud->fetch("user","","1 limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("user","","username like '%".$_POST['username']."%' limit $start,$per_hal");	
						}
						
						
						$no = 1;
						foreach($data as $value){
							echo "<tr>
									<td>".$value['username']."</td>
									<td>".$value['full_name']."</td>
									<td>".$value['hp']."</td>
									<td>".$value['email']."</td>
									<td>
										<a href=\"?r=manageusers&mod=".$_GET['mod']."&act=departemen&id=".$value['username']."\"><span class=\"glyphicon glyphicon-briefcase\" aria-hidden=\"true\"></span> Dept</a> |
										<a href=\"?r=manageusers&mod=".$_GET['mod']."&act=view&id=".$value['username']."\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=manageusers&mod=".$_GET['mod']."&act=edit&id=".$value['username']."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&mod=".$_GET['mod']."&id=".$value['username']."\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
			<div class="col-md-3" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" enctype="multipart/form-data">
					<div class="form-group">
						<label>Username : </label>
						<input type="text" name="username" class="form-control" placeholder ="Username" required>
					</div>
					<div class="form-group">
						<label>Password : </label>
						<input type="password" name="password" class="form-control" placeholder ="password" required>
					</div>
					<div class="form-group">
						<label>Fullname : </label>
						<input type="text" name="fullname" class="form-control" placeholder ="Fullname" required>
					</div>
					<div class="form-group">
						<label>Handphone : </label>
						<input type="text" name="handphone" class="form-control" placeholder ="Handphone" required>
					</div>
					<div class="form-group">
						<label>Email : </label>
						<input type="email" name="email" class="form-control" placeholder ="E-mail" required>
					</div>
					<div class="form-group">
						<label for="foto">Browse Foto</label>
						<input type="file" name="foto">
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("user","","username='$_GET[id]'");
		?>
			<div class="row">
				<div class="col-sm-12 col-md-9 col-lg-9">
					<div class="col-sm-12 col-md-9 col-lg-9">
						<button class="btn btn-primary" onclick="window.history.go(-1)"><span class="glyphicon glyphicon-backward"></span> Back</button>	
						<br><br>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-9 col-lg-9">
							<div class="col-sm-2 col-md-2 col-lg-2">
								<img src = "<?php echo $data[0]['foto']; ?>" alt="<?php echo $data[0]['foto']; ?>" width='150'  style='border-style:solid;border-width:1px;padding:5px'/><br><Br>
							</div>
							<div class="col-md-1 col-lg-1"></div>
							<div class="col-md-5">
								<span class="glyphicon glyphicon-user"></span> Username  : <?php echo $data[0]['username']; ?> ( <b><?php echo ucwords($data[0]['full_name']); ?></b> )<br>
								<span class="glyphicon glyphicon-lock"></span> Password  : ****************<br>
								<br><br>
								<span class="glyphicon glyphicon-phone"></span> Phone   : <?php echo $data[0]['hp']; ?></br>
								<span class="glyphicon glyphicon-envelope"></span> Email   : <?php echo $data[0]['email']; ?></br><br>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php
	break;
	
/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("user","","username='$_GET[id]'");
		?>
			<div class="col-md-3" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" enctype="multipart/form-data">
					<div class="form-group">
						<label>Username : </label>
						<input type="text" name="username" class="form-control" value="<?php echo $data[0]['username']; ?>" readonly>
					</div>
					<div class="form-group">
						<label>Password : </label>
						<input type="password" name="password" class="form-control" placeholder ="password">
					</div>
					<div class="form-group">
						<label>Fullname : </label>
						<input type="text" name="fullname" class="form-control" value="<?php echo $data[0]['full_name']; ?>" required>
					</div>
					<div class="form-group">
						<label>Handphone : </label>
						<input type="text" name="handphone" class="form-control" value="<?php echo $data[0]['hp']; ?>" required>
					</div>
					<div class="form-group">
						<label>Email : </label>
						<input type="email" name="email" class="form-control" value="<?php echo $data[0]['email']; ?>" required>
					</div>
					<div class="form-group">
						<label for="foto">Browse Foto</label>
						<input type="file" name="foto">
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	case "departemen":
		$data = $crud->fetch("user","","username='".$_GET['id']."'");
		?>
			<div class="row">
				<div class="col-md-6">
					<a href = "?r=manageusers&mod=<?php echo $_GET['mod'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward"></span> Back</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3" style="margin:20px">
					<form class="form-horizontal" method="post" action="<?php echo $aksi; ?>&act=departemen">
						<div class="form-group">
							<label>Username </label>
							<input type="text" name="username" class="form-control" value="<?php echo $data[0]['username']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Departemen </label>
							<select name="departemen_id" class="form-control"required>
								<option value="">-- Choose Departemen --</option>
								<?php 
									$data = $crud->fetch("departemen","","");
									foreach($data as $value){
										echo "<option value=".$value['departemen_id'].">".$value['departemen_name']."</option>";
									}								
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Group User </label>
							<select name="group_id" class="form-control"required>
								<option value="">-- Choose Group --</option>
								<?php 
									$data = $crud->fetch("group_user","","");
									foreach($data as $value){
										echo "<option value=".$value['group_id'].">".$value['group_name']."</option>";
									}								
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
						<button type="button" class="btn btn-danger" onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
					</form>
				</div>
				<div class="col-md-6">
					<table class="table table-stripped table-hover">
					<tr>
						<td>Group</td>
						<td>Departemen</td>
						<td>Default</td>
						<td>Action</td>
					</tr>
					<?php 
						$data = $crud->fetch("v_user_authority","","username='".$_GET['id']."' ");						
						foreach($data as $value){
							if($value['cek'] == 1){ 
								$cek = "<span class='glyphicon glyphicon-star-empty'></span>"; 
							}else{
								$cek = "<span class='glyphicon glyphicon-star'></span>";
							}
							
							echo "<tr>
										<tD>".$value['group_id']." - ".$value['group_name']." </td>
										<td>".$value['departemen_id']." - ".$value['departemen_name']."</td>
										<td>
											<a href='".$aksi."&mod=".$_GET['mod']."&act=default&
											id=".$value['username']."&dep=".$value['departemen_id']."
											&group_id=".$value['group_id']."' 
											onclick=\"return confirm('Set to default ?'); \">".$cek."</a>
										</td>
										<tD>
											<a href='".$aksi."&mod=".$_GET['mod']."&act=del_departemen&
											id=".$value['username']."&dep=".$value['departemen_id']."
											&group_id=".$value['group_id']."' 
											onclick=\"return confirm('Yakin akan keluar ?'); \">
											<span class='glyphicon glyphicon-trash'></span> Delete</a>
										</td>
								  </tr>";
						}
					?>
					<tr>
					
					</tr>
					</table>
				</div>
			</div>
		<?php 	
	break;
	
}

?>