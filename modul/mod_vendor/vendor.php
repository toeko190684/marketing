<?php 

$aksi = "modul/mod_vendor/aksi_vendor.php?r=vendor&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-md-10" >
				<a href="?r=vendor&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="vendor_name" class="form-control" placeholder="Vendor Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Vendor</td>
						<td>AP Account Type</td>
						<td>AP Account ID</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						require_once "pagelink_top.php";
						
						if($_POST['vendor_name'] == ""){
							$jumlah_record = $crud->fetch("vendor","","");
						}else{
							$jumlah_record = $crud->fetch("vendor","","vendor_name like '%".$_POST['vendor_name']."%'");
						}
												
						if($_POST['vendor_name'] == ""){
							$data = $crud->fetch("vendor","","1 limit $posisi,$batas");			
						}else{
							$data = $crud->fetch("vendor","","vendor_name like '%".$_POST['vendor_name']."%' limit $posisi,$batas");	
						}
						
						foreach($data as $value){
							echo "<tr>
									<td>".$value['vendor_id']." - ".$value['vendor_name']."</td>
									<td>".$value['ap_account_type']."</td>
									<td>".$value['ap_account_id']."</td>
									<td>
										<a href=\"?r=vendor&mod=".$_GET['mod']."&act=view&id=$value[vendor_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=vendor&mod=".$_GET['mod']."&act=edit&id=$value[vendor_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[vendor_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
						<label>Vendor Id : </label>
						<input type="text" name="vendor_id" class="form-control" placeholder ="Vendor Id" required>
					</div>
					<div class="form-group">
						<label>Vendor Name : </label>
						<input type="text" name="vendor_name" class="form-control" placeholder ="Vendor Name" required>
					</div>	
					<div class="form-group">
							<label>AP Account Type : </label>
							<input type="text" name="ap_account_type" class="form-control" >
						</div>
						<div class="form-group">
							<label>AP Account ID : </label>
							<input type="text" name="ap_account_id" class="form-control" >
						</div>						
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("vendor","","vendor_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="form-group">
							<label>Vendor Id : </label>
							<input type="text" name="vendor_id" class="form-control form-sm" value="<?php echo $data[0]['vendor_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Vendor Name : </label>
							<input type="text" name="vendor_name" value="<?php echo $data[0]['vendor_name']; ?>" class="form-control" >
						</div>	
						<div class="form-group">
							<label>AP Account Type : </label>
							<input type="text" name="ap_account_type" value="<?php echo $data[0]['ap_account_type']; ?>" class="form-control" >
						</div>
						<div class="form-group">
							<label>AP Account ID : </label>
							<input type="text" name="ap_account_id" value="<?php echo $data[0]['ap_account_id']; ?>" class="form-control" >
						</div>						
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("vendor","","vendor_id='$_GET[id]'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-4">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Vendor Id </strong></td><td><?php echo $data[0]['vendor_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Vendor Name : </strong></td><td><?php echo $data[0]['vendor_name']; ?></td>
					</tr>
					<tr>
						<td><strong>AP Account Type : </strong></td><td><?php echo $data[0]['ap_account_id']; ?></td>
					</tr>
					<tr>
						<td><strong>AP Account ID : </strong></td><td><?php echo $data[0]['ap_account_id']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>