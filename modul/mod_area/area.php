<?php 

$aksi = "modul/mod_area/aksi_area.php?r=area&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-sm-12 col-md-12 col-lg-9" >
				<div class="col-sm-12 col-md-12 col-lg-9" >
					<a href="?r=area&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
					
					<form method="post"  class="form-inline">
						<div class="form-group nav navbar-right" style="padding-right:15px">						
							<input type="text" name="area_name" class="form-control" placeholder="Area Name">
							<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
						</div>
					</form>
					
					<br><br>
					<table class="table table-bordered table-stripped">
					<thead>
						<tr>
							<td>Area</td>
							<td>Action</td>
						</tr>
					</thead>
					<tbody>
						<?php 
							require_once "pagelink_top.php";
							
							if($_POST['area_name'] == ""){
								$jumlah_record = $crud->fetch("area","","");
							}else{
								$jumlah_record = $crud->fetch("area","","area_name like '%".$_POST['area_name']."%'");
							}
							
							
							if($_POST['area_name'] == ""){
								$data = $crud->fetch("area","","1 limit $posisi,$batas");			
							}else{
								$data = $crud->fetch("area","","area_name like '%".$_POST['area_name']."%' limit $posisi,$batas");	
							}
							
							foreach($data as $value){
								echo "<tr>
										<td>".$value['area_id']." - ".$value['area_name']."</td>
										<td>
											<a href=\"?r=subdist&mod=18&id=$value[area_id]\"><span class=\"glyphicon glyphicon-map-marker\" aria-hidden=\"true\"></span> Subdist</a> |
											<a href=\"?r=area&mod=".$_GET['mod']."&act=view&id=$value[area_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
											<a href=\"?r=area&mod=".$_GET['mod']."&act=edit&id=$value[area_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
											<a href=\"$aksi&act=del&id=$value[area_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
			</div>
		<?php
	break;

/*  jika pilihannya adalah add atau tambah */	
	case  "add":
		?>
			<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
					<div class="form-group">
						<label>Area Id : </label>
						<input type="text" name="area_id" class="form-control" placeholder ="Area Id" required>
					</div>
					<div class="form-group">
						<label>Area Name : </label>
						<input type="text" name="area_name" class="form-control" placeholder ="Area Name" required>
					</div>					
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("area","","area_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="form-group">
							<label>Area Id : </label>
							<input type="text" name="area_id" class="form-control form-sm" value="<?php echo $data[0]['area_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Area Name : </label>
							<input type="text" name="area_name" value="<?php echo $data[0]['area_name']; ?>" class="form-control" >
						</div>					
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("area","","area_id='$_GET[id]'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-4">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Area Id </strong></td><td><?php echo $data[0]['area_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Area Name : </strong></td><td><?php echo $data[0]['area_name']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>