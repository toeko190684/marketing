<?php 

$aksi = "modul/mod_groupuser/aksi_groupuser.php?r=groupuser&mod=".$_GET['mod'];

switch($_GET['act']){
	default :		
		?>
			<div class="col-md-9" >
				<a href="?r=groupuser&mod=<?php echo $_GET['mod']; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post" action="" class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="group_name" class="form-control" placeholder="Group Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Group</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						require_once "pagelink_top.php";
						
						if($_POST['group_name'] == ""){
							$jumlah_record = $crud->fetch("group_user","","");
						}else{
							$jumlah_record = $crud->fetch("group_user","","group_name like '%".$_POST['group_name']."%'");
						}
												
						if($_POST['group_name'] == ""){
							$data = $crud->fetch("group_user","","1 limit $posisi,$batas");			
						}else{
							$data = $crud->fetch("group_user","","group_name like '%".$_POST['group_name']."%' limit $posisi,$batas");	
						}
						
						$no = 1 + $posisi;
						foreach($data as $value){
							echo "<tr>
									<td>".$value['group_id']." - ".$value['group_name']."</td>
									<td>
										<a href=\"?r=groupuser&mod=".$_GET['mod']."&act=module&id=".$value['group_id']."\"><span class=\"glyphicon glyphicon-briefcase\" aria-hidden=\"true\"></span> Module</a> |
										<a href=\"?r=groupuser&mod=".$_GET['mod']."&act=view&id=".$value['group_id']."\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=groupuser&mod=".$_GET['mod']."&act=edit&id=".$value['group_id']."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&mod=".$_GET['mod']."&id=".$value['group_id']."\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
									</td>
								</tr>";
						}
					?>
				</tbody>
				</table>				
								
				<?php
					require_once "pagelink_bottom.php";
				?>
				
			</div>
		<?php
	break;

/*  jika pilihannya adalah add atau tambah */	
	case  "add":
		?>
			<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
					<div class="form-group">
						<label>Group Name : </label>
						<input type="text" name="group_name" class="form-control" placeholder ="Group Name" required>
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("group_user","","group_id='$_GET[id]'");

		?>
			<div class="col-md-4">
			    <button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Group ID </strong></td><td>:</td><td><?php echo $data[0]['group_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Group Name : </strong></td><td>:</td><td><?php echo $data[0]['group_name']; ?></td>
					</tr>
				</table>				
			</div>
		<?php
	break;
	
/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("group_user","","group_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<div class="form-group">
						<label>Group ID : </label>
						<input type="text" name="group_id" class="form-control" value="<?php echo $data[0]['group_id']; ?>" readonly>
					</div>
					<div class="form-group">
						<label>Group Name : </label>
						<input type="text" name="group_name" class="form-control" value="<?php echo $data[0]['group_name']; ?>">
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	case "module":
		$data = $crud->fetch("group_user","","group_id='".$_GET['id']."'");
		?>
			<div class="row">
				<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="col-sm-12 col-md-12 col-lg-3" style="margin:20px">
					<form class="form-horizontal" method="post" action="<?php echo $aksi; ?>&act=module">
						<div class="form-group">
							<label>Group ID </label>
							<input type="hidden" name="group_id" class="form-control" value="<?php echo $data[0]['group_id']; ?>" readonly>
							<input type="text" name="group_name" class="form-control" value="<?php echo $data[0]['group_name']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Module </label>
							<select name="module_id" class="form-control"required>
								<option value="">-- Choose Modul --</option>
								<?php 
									$data = $crud->fetch("module","","");
									foreach($data as $value){
										echo "<option value=".$value['module_id'].">".$value['module_name']."</option>";
									}								
								?>
							</select>
						</div>
						<div class="form-group">
							<label class="checkbox-inline">
								<input type="checkbox" name="c" value="1"> Create 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" name="r" value="1"> Read 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" name="u" value="1"> Update 
							</label>
							<label class="checkbox-inline">
								<input type="checkbox" name="d" value="1"> Delete 
							</label>
						</div>
						<button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
						<button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
					</form>
				</div>
				<div class="col-md-6">
					<table class="table table-stripped table-hover">
					<tr>
						<td>Module</td>
						<td>Create</td>
						<td>Read</td>
						<td>Update</td>
						<td>Delete</td>
						<td>Action</td>
					</tr>
					<?php 
						$data = $crud->fetch("v_group_modul","","group_id='".$_GET['id']."' ");						
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
							echo "<tr>
										<tD>".$value['module_id']." - ".$value['module_name']." </td>
										<td>".$c."</td>
										<td>".$r."</td>
										<td>".$u."</td>
										<td>".$d."</td>
										<tD><a href='$aksi&act=del_module&id=".$_GET['id']."&id2=".$value['module_id']."' onclick='return confirm(\"Are you sure ?\");'><span class='glyphicon glyphicon-trash'></span> Delete</a></td>
								  </tr>";
						}
					?>
					<tr>
					
					</tr>
					</table>
				</div>
				</div>
			</div>
		<?php 	
	break;
	
}

?>