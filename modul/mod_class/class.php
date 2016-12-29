<?php 

$aksi = "modul/mod_class/aksi_class.php?r=class&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-xs-12 col-sm-6 col-md-8" >
				<a href="?r=promotype&mod=62&id=<?php echo substr($_GET['id'],0,1); ?>" class="btn btn-primary"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</a>	
				<a href="?r=class&mod=<?php echo $_GET['mod'];?>&id=<?php echo $_GET['id']; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="class_name" class="form-control" placeholder="Class Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Class</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						require_once "pagelink_top.php";
						
						if($_POST['class_name'] == ""){
							$jumlah_record = $crud->fetch("class","","promotype_id='".$_GET['id']."'");
						}else{
							$jumlah_record = $crud->fetch("class","","promotype_id='".$_GET['id']."' and class_name like '%".$_POST['class_name']."%'");
						}
												
						if($_POST['class_name'] == ""){
							$data = $crud->fetch("class","","promotype_id='".$_GET['id']."' limit $posisi,$batas");			
						}else{
							$data = $crud->fetch("class","","promotype_id='".$_GET['id']."' and class_name like '%".$_POST['class_name']."%' 
												limit $posisi,$batas");	
						}
						
						foreach($data as $value){
							echo "<tr>
									<td>".$value['class_id']." - ".$value['class_name']."</td>
									<td>
										<a href=\"?r=class&mod=".$_GET['mod']."&id=".$_GET['id']."&act=view&id2=$value[class_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=class&mod=".$_GET['mod']."&id=".$_GET['id']."&act=edit&id2=$value[class_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=".$_GET['id']."&id2=$value[class_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
				<form name="form1" method="post" action="<?php echo $aksi; ?>&id=<?php echo $_GET['id'];?>&act=add" >
					<div class="form-group">
						<label>Class Id : </label>
						<input type="text" name="class_id" class="form-control" placeholder ="Class Id" required>
					</div>
					<div class="form-group">
						<label>Class Name : </label>
						<input type="text" name="class_name" class="form-control" placeholder ="Class Name" required>
					</div>					
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("class","","class_id='".$_GET['id2']."'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&id=<?php echo $_GET['id']; ?>&act=update" >
						<div class="form-group">
							<label>Class Id : </label>
							<input type="text" name="class_id" class="form-control form-sm" value="<?php echo $data[0]['class_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Class Name : </label>
							<input type="text" name="class_name" value="<?php echo $data[0]['class_name']; ?>" class="form-control" >
						</div>					
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("class","","promotype_id='".$_GET['id']."' and class_id='".$_GET['id2']."'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-4">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Class Id </strong></td><td><?php echo $data[0]['class_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Class Name : </strong></td><td><?php echo $data[0]['class_name']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>