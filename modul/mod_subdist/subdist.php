<?php 

$aksi = "modul/mod_subdist/aksi_subdist.php?r=subdist&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-xs-12 col-sm-6 col-md-8" >
				<a href="?r=area&mod=17" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=subdist&mod=<?php echo $_GET['mod'];?>&id=<?php echo $_GET['id']; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="subdist_name" class="form-control" placeholder="Subdist Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Subdist</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						//ini adalah halaman paging
						$per_hal = 10;
						if($_POST['subdist_name'] == ""){
							$jumlah_record = $crud->fetch("subdist","","area_id='".$_GET['id']."'");
						}else{
							$jumlah_record = $crud->fetch("subdist","","area_id='".$_GET['id']."' and subdist_name like '%".$_POST['subdist_name']."%'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['subdist_name'] == ""){
							$data = $crud->fetch("subdist","","area_id='".$_GET['id']."' limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("subdist","","area_id='".$_GET['id']."' and subdist_name like '%".$_POST['subdist_name']."%' limit $start,$per_hal");	
						}
						
						foreach($data as $value){
							echo "<tr>
									<td>".$value['subdist_id']." - ".$value['subdist_name']."</td>
									<td>
										<a href=\"?r=subdist&mod=".$_GET['mod']."&id=".$_GET['id']."&act=view&id2=$value[subdist_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=subdist&mod=".$_GET['mod']."&id=".$_GET['id']."&act=edit&id2=$value[subdist_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=".$_GET['id']."&id2=$value[subdist_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
			<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&id=<?php echo $_GET['id'];?>&act=add" >
					<div class="form-group">
						<label>Subdist Id : </label>
						<input type="text" name="subdist_id" class="form-control" placeholder ="Subdist Id" required>
					</div>
					<div class="form-group">
						<label>Subdist Name : </label>
						<input type="text" name="subdist_name" class="form-control" placeholder ="Subdist Name" required>
					</div>					
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("subdist","","area_id='$_GET[id]' and subdist_id='".$_GET['id2']."'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&id=<?php echo $_GET['id']; ?>&act=update" >
						<div class="form-group">
							<label>Subdist Id : </label>
							<input type="text" name="subdist_id" class="form-control form-sm" value="<?php echo $data[0]['subdist_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Subdist Name : </label>
							<input type="text" name="subdist_name" value="<?php echo $data[0]['subdist_name']; ?>" class="form-control" >
						</div>					
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("subdist","","area_id='$_GET[id]' and subdist_id='".$_GET['id2']."'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-4">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Subdist Id </strong></td><td><?php echo $data[0]['subdist_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Subdist Name : </strong></td><td><?php echo $data[0]['subdist_name']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>