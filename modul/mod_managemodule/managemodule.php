<?php 

$aksi = "modul/mod_managemodule/aksi_managemodule.php?r=managemodule";

switch($_GET['act']){
	default :		
		?>
			<div class="col-md-9" >
				<a href="?r=managemodule&mod=<?php echo $_GET['mod']; ?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post" action="" class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="module_name" class="form-control" placeholder="Module Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Module</td>
						<td>Link</td>
						<td>Parent Module</td>
						<td>Sort</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						//ini adalah halaman paging
						$per_hal = 10;
						if($_POST['module_name'] == ""){
							$jumlah_record = $crud->fetch("module","","");
						}else{
							$jumlah_record = $crud->fetch("module","","module_name like '%".$_POST['module_name']."%'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['module_name'] == ""){
							$data = $crud->fetch("module","","1 limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("module","","module_name like '%".$_POST['module_name']."%' limit $start,$per_hal");	
						}
						
						$no = 1;
						foreach($data as $value){
							echo "<tr>
									<td>".$value['module_id']." - ".$value['module_name']."</td>
									<td>".$value['link']."</td>
									<td>".$value['menu_id']."</td>
									<td>".$value['urut']."</td>
									<td>
										<a href=\"?r=managemodule&mod=".$_GET['mod']."&act=view&id=".$value['module_id']."\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=managemodule&mod=".$_GET['mod']."&act=edit&id=".$value['module_id']."\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&mod=".$_GET['mod']."&id=".$value['module_id']."\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=add" >
					<div class="form-group">
						<label>Module Name : </label>
						<input type="text" name="module_name" class="form-control" placeholder ="Module Name" required>
					</div>
					<div class="form-group">
						<label>Link : </label>
						<input type="text" name="link" class="form-control" placeholder ="Link" required>
					</div>
					<div class="form-group">
						<label>Parent Module : </label>
						<select name="menu_id" class="form-control">
							<option>--Parent Module--</option>
							<?php 
								$data = $crud->fetch("menu_module","","");
								foreach($data as $value){
									echo "<option value=".$value['menu_id'].">".$value['menu_name']."</option>"; 
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Sort</label>
						<input type="text" name="urut" class="form-control">
					</div>
					<div class="form-group">
						<label>Display Module : </label>
					</div>
					<div class="form-group">
						<label class="radio-inline">
							<input type="radio" name="display" value="1" checked> Yes
						</label>
						<label class="radio-inline">
							<input type="radio" name="display" value="0"> No
						</label>
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("module","","module_id='$_GET[id]'");

		?>
			<div class="col-md-4">
			    <button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
				<br><br>
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Module ID </strong></td><td>:</td><td><?php echo $data[0]['module_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Module Name : </strong></td><td>:</td><td><?php echo $data[0]['module_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Link : </strong></td><td>:</td><td><?php echo $data[0]['link']; ?></td>
					</tr>
					<tr>
						<td><strong>Parent Module : </strong></td><td>:</td><td><?php echo $data[0]['menu_id']; ?></td>
					</tr>
					<tr>
						<?php 
							if($data[0]['display']==1){ $display = "Yes"; }else{ $display = "No"; }
						?>
						<td><strong>Display Module : </strong></td><td>:</td><td><?php echo $display; ?></td>
					</tr>
				</table>				
			</div>
		<?php
	break;
	
/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("module","","module_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
				<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
					<div class="form-group">
						<label>Module ID : </label>
						<input type="text" name="module_id" class="form-control" value="<?php echo $data[0]['module_id']; ?>" readonly>
					</div>
					<div class="form-group">
						<label>Module Name : </label>
						<input type="text" name="module_name" class="form-control" value="<?php echo $data[0]['module_name']; ?>">
					</div>
					<div class="form-group">
						<label>Link : </label>
						<input type="text" name="link" class="form-control" value="<?php echo $data[0]['link']; ?>">
					</div>
					<div class="form-group">
						<label>Parent Module : </label>
						<select name="menu_id" class="form-control">
							<option value="<?php echo $data[0]['menu_id']; ?>"><?php echo $data[0]['menu_id']; ?></option>
							<?php 
								$data = $crud->fetch("menu_module","","");
								foreach($data as $value){
									echo "<option value=".$value['menu_id'].">".$value['menu_name']."</option>"; 
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Sort : </label>
						<input type="text" name="urut" class="form-control" value="<?php echo $data[0]['urut']; ?>">
					</div>
					<div class="form-group">
						<label>Display Module : </label>
					</div>
					<div class="form-group">
						<?php 
							if($value['display']==1){ 
							?>
								<label class="radio-inline">
									<input type="radio" name="display" value="1" checked> Yes
								</label>
								<label class="radio-inline">
									<input type="radio" name="display" value="0"> No
								</label>
							<?php
							}else{
							?>
								<label class="radio-inline">
									<input type="radio" name="display" value="1"> Yes
								</label>
								<label class="radio-inline">
									<input type="radio" name="display" value="0" checked> No
								</label>
							<?php
							}
						?>
						
					</div>
					<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
}

?>