<?php 

$aksi = "modul/mod_groupoutlet/aksi_groupoutlet.php?r=groupoutlet&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-xs-12 col-sm-6 col-md-8" >
				<a href="?r=groupoutlet&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="groupoutlet_name" class="form-control" placeholder="Group Outlet Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Group Outlet</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						require_once "pagelink_top.php";
						
						if($_POST['groupoutlet_name'] == ""){
							$jumlah_record = $crud->fetch("group_outlet","","");
						}else{
							$jumlah_record = $crud->fetch("group_outlet","","groupoutlet_name like '%".$_POST['groupoutlet_name']."%'");
						}
												
						if($_POST['groupoutlet_name'] == ""){
							$data = $crud->fetch("group_outlet","","1 limit $posisi,$batas");			
						}else{
							$data = $crud->fetch("group_outlet","","groupoutlet_name like '%".$_POST['group_outlet_name']."%' limit $posisi,$batas");	
						}
						
						foreach($data as $value){
							echo "<tr>
									<td>".$value['groupoutlet_id']." - ".$value['groupoutlet_name']."</td>
									<td>
										<a href=\"?r=groupoutlet&mod=".$_GET['mod']."&act=view&id=$value[groupoutlet_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=groupoutlet&mod=".$_GET['mod']."&act=edit&id=$value[groupoutlet_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[groupoutlet_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
						<label>Group Outlet Id : </label>
						<input type="text" name="groupoutlet_id" class="form-control" placeholder ="Group Outlet Id" required>
					</div>
					<div class="form-group">
						<label>Group Promo Name : </label>
						<input type="text" name="groupoutlet_name" class="form-control" placeholder ="Group Outlet Name" required>
					</div>					
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("group_outlet","","groupoutlet_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="form-group">
							<label>Group Outlet Id : </label>
							<input type="text" name="groupoutlet_id" class="form-control form-sm" value="<?php echo $data[0]['groupoutlet_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Group Outlet Name : </label>
							<input type="text" name="groupoutlet_name" value="<?php echo $data[0]['groupoutlet_name']; ?>" class="form-control" >
						</div>					
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("group_outlet","","groupoutlet_id='$_GET[id]'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-4">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Group Outlet Id </strong></td><td><?php echo $data[0]['groupoutlet_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Group Outlet Name : </strong></td><td><?php echo $data[0]['groupoutlet_name']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>