<?php
$aksi="modul/mod_home/aksi_home.php";

switch($_GET['act']){
  // Tampil master_users
  default:
		$data = $crud->fetch("user","","username='".$_SESSION['username']."'");
		?>		
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-2 col-lg-2">
						<img src = "<?php echo $data[0]['foto']; ?>" alt="<?php echo $data[0]['foto']; ?>" width='150px'  style='border-style:solid;border-width:1px;padding:5px'/><br><Br>
					</div>
					<div class="col-md-5">
						Username  : <?php echo $data[0]['username']; ?><br>
						Full Name : <?php echo ucwords($data[0]['full_name']); ?></br></br>
						<span class="glyphicon glyphicon-phone"></span> Phone   : <?php echo $data[0]['hp']; ?></br>
						<span class="glyphicon glyphicon-envelope"></span> Email   : <?php echo $data[0]['email']; ?></br><br>
						
						<form method="post" action="?r=home&act=edit&id=<?php echo $data[0]['username'];?>">
						<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span> Edit Profile</button>
						</form>
					</div>
				</div>
			</div><br><br>
			<div class="row'>
				<div class="col-sm-12 col-md-12 col-lg-12">
					<div class="col-sm-12 col-md-12 col-lg-12">
						<ul class="nav nav-tabs">
						  <li role="presentation" class="active"><a href="#authority" aria-controls="home" role="tab" data-toggle="tab">Authority</a></li>
						  <li role="presentation"><a href="#module"  aria-controls="home" role="tab" data-toggle="tab">Module</a></li>
						</ul>
						
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="authority">
								<div class="row">
									<div class="col-md-4" style="margin:20px">
										<table class="table table">
											<tr>
												<td>No.</td><tD>Departemen</td><td>Group Id</td><tD>Default</td>
											</tr>
											<?php 
												$i = 1;
												$data = $crud->fetch("v_user_authority","","username='".$_SESSION['username']."'");
												foreach($data as $value){
													if($value['cek'] == 1){ 
														$cek = "<span class='glyphicon glyphicon-star-empty'></span>"; 
													}else{
														$cek = "<span class='glyphicon glyphicon-star'></span>";
													}
													echo "<tr>
															<td>".$i++."</td>
															<td>".$value['departemen_id']." - ".$value['departemen_name']."</td>
															<td>".$value['group_id']."</td>
															<td>".$cek."</td>
														</tr>";
												}
											?>
										</table>
									</div>
								</div>	
							</div>
							<div role="tabpanel" class="tab-pane" id="module">
								<div class="col-md-6" style="margin:20px">
									<table class="table table-stripped">
									<tr>
										<td>Module</td>
										<td>Create</td>
										<td>Read</td>
										<td>Update</td>
										<td>Delete</td>
									</tr>
									<?php 
										$data = $crud->fetch("v_user_module","","username='".$_SESSION['username']."' 
															  and departemen_id='".$_SESSION['departemen_id']."'
															  and group_id='".$_SESSION['group_id']."'");
										foreach($data as $value){
											if($value['c'] == 1){ 
												$c = "<span class='glyphicon glyphicon-ok-circle'></span>";
											}else{
												$c = "<span class='glyphicon glyphicon-remove-circle'></span>";
											}
											
											if($value['r'] == 1){ 
												$r = "<span class='glyphicon glyphicon-ok-circle'></span>";
											}else{
												$r = "<span class='glyphicon glyphicon-remove-circle'></span>";
											}
											
											if($value['u'] == 1){ 
												$u = "<span class='glyphicon glyphicon-ok-circle'></span>";
											}else{
												$u = "<span class='glyphicon glyphicon-remove-circle'></span>";
											}
											
											if($value['d'] == 1){ 
												$d = "<span class='glyphicon glyphicon-ok-circle'></span>";
											}else{
												$d = "<span class='glyphicon glyphicon-remove-circle'></span>";
											}
											
											echo "<tR>
													<td>".$value['module_id']." - ".$value['module_name']."</td>
													<td>".$c."</td>
													<td>".$r."</td>
													<td>".$u."</td>
													<td>".$d."</td>
												</tr>";	
										}
									?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php 
    break;
 
    case "edit":
		$data = $crud->fetch("user",""."username='".$_GET['id']."'");
		
		?>
			<div class="col-md-3">
				<form method="post" action="<?php echo $aksi; ?>?r=home&act=update">
				  <div class="form-group">
					<label for="username">Username</label>
					<input type="username" class="form-control" name="username" value="<?php echo $data[0]['username']; ?>" readonly>
				  </div>
				  <div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" name="password" > * kosongkan jika tidak dirubah
				  </div>
				  <div class="form-group">
					<label for="full_name">Full Name</label>
					<input type="text" class="form-control" name="full_name" value="<?php echo $data[0]['full_name']; ?>">
				  </div>
				  <div class="form-group">
					<label for="hp">Handphone</label>
					<input type="text" class="form-control" name="hp" value="<?php echo $data[0]['hp']; ?>">
				  </div>
				  <div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" name="email' value="<?php echo $data[0]['email']; ?>">
				  </div>
				  <div class="form-group">
					<label for="foto">Foto</label>
					<input type="file" name="foto">
					<p class="help-block">Please browse your picture profile.</p>
				  </div>
				  <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Submit</button>
				  <button type="button" class="btn btn-danger"  onclick="history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>		
			</div>
		<?php
    break;  
}
?>
