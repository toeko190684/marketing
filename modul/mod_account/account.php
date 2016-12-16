<?php 

$aksi = "modul/mod_account/aksi_account.php?r=account&mod=".$_GET['mod'];

switch($_GET['act']){
	default :
		?>
			<div class="col-xs-12 col-sm-6 col-md-8" >
				<a href="?r=account&mod=<?php echo $_GET['mod'];?>&act=add" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a>	
				
				<form method="post"  class="form-inline">
					<div class="form-group nav navbar-right" style="padding-right:15px">						
						<input type="text" name="account_name" class="form-control" placeholder="Account Name">
						<button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
					</div>
				</form>
				
				<br><br>
				<table class="table table-bordered table-stripped">
				<thead>
					<tr>
						<td>Account</td>
						<td>Fix/Var</td>
						<td>Operation/Promotion</td>
						<td>Action</td>
					</tr>
				</thead>
				<tbody>
					<?php 
						//ini adalah halaman paging
						$per_hal = 10;
						if($_POST['account_name'] == ""){
							$jumlah_record = $crud->fetch("account","","departemen_id='".$_SESSION['departemen_id']."'");
						}else{
							$jumlah_record = $crud->fetch("account","","departemen_id='".$_SESSION['departemen_id']."' and 
														account_name like '%".$_POST['account_name']."%'");
						}
						$jum = count($jumlah_record);
						$halaman = ceil($jum/$per_hal);
						$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1; // jika $page kosong maka beri nilai 1 jika ada gunakan nilai page 
						$start = ($page - 1) * $per_hal;
						
						if($_POST['account_name'] == ""){
							$data = $crud->fetch("account","","departemen_id='".$_SESSION['departemen_id']."' limit $start,$per_hal");			
						}else{
							$data = $crud->fetch("account","","departemen_id='".$_SESSION['departemen_id']."' and account_name like '%".$_POST['account_name']."%' limit $start,$per_hal");	
						}
						
						foreach($data as $value){
							echo "<tr>
									<td>".$value['account_id']." - ".$value['account_name']."</td>
									<td>".$value['fix_var']."</td>
									<td>".$value['operational_promotion']."</td>
									<td>
										<a href=\"?r=account&mod=".$_GET['mod']."&act=view&id=$value[account_id]\"><span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span> View</a> |
										<a href=\"?r=account&mod=".$_GET['mod']."&act=edit&id=$value[account_id]\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a> |
										<a href=\"$aksi&act=del&id=$value[account_id]\" onclick=\"return confirm('This record will be deleted, Are you sure ? ')\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\" ></span> Del</a> 									
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
						<label>Account Id : </label>
						<input type="text" name="account_id" class="form-control" placeholder ="Accout Id" required>
					</div>
					<div class="form-group">
						<label>Account Name : </label>
						<input type="text" name="account_name" class="form-control" placeholder ="Accout Name" required>
					</div>	
					<div class="form-group">
						<label>Fix (F) / Variabel (V) : </label>
						<select name="fix_var" class="form-control" required>
							<option value="F" selected>F</option>
							<option value="V">V</option>
						</select>
					</div>
					<div class="form-group">
						<label>Operational (O) / Promotion (P) : </label>
						<select name="operational_promotion" class="form-control" required>
							<option value="O" selected>O</option>
							<option value="P">P</option>
						</select>
					</div>								
					<button type="submit" name="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>
					<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
				</form>
			</div>
		<?php
	break;
	
	/*  jika pilihan kondisinya adalah edit */	
	case "edit":
			$data = $crud->fetch("account","","account_id='$_GET[id]'");
		?>
				<div class="col-md-4" >
					<form name="form1" method="post" action="<?php echo $aksi; ?>&act=update" >
						<div class="form-group">
							<label>Account Id : </label>
							<input type="text" name="account_id" class="form-control form-sm" value="<?php echo $data[0]['account_id']; ?>" readonly>
						</div>
						<div class="form-group">
							<label>Account Name : </label>
							<input type="text" name="account_name" value="<?php echo $data[0]['account_name']; ?>" class="form-control" >
						</div>	
						<div class="form-group">
							<label>Fix (F) / Variabel (V) : </label>
							<select name="fix_var" class="form-control" >
								<option value="<?php echo $data[0]['fix_var']; ?>"><?php echo $data[0]['fix_var']; ?></option>
								<option value="F">F</option>
								<option value="V">V</option>
							</select>
						</div>
						<div class="form-group">
							<label>Operational (O) / Promotion (P) : </label>
							<select name="operational_promotion" class="form-control" >
								<option value="<?php echo $data[0]['operational_promotion']; ?>"><?php echo $data[0]['operational_promotion']; ?></option>
								<option value="O">O</option>
								<option value="P">P</option>
							</select>
						</div>						
						<button type="submit" name="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
						<button type="button" class="btn btn-danger " onclick="window.history.go(-1)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel</button>
					</form>
				</div>
		<?php
	break;

/*   jika pilihan kondisinya adalah view */ 
	case "view":
			$data = $crud->fetch("account","","account_id='$_GET[id]'");
			
		?>
			<button type="button" class="btn btn-primary" onclick ="window.history.go(-1)"><span class="glyphicon glyphicon-backward" aria-hidden="true"></span> Back</button>
			<br><br>
			<div class="col-md-6">				
				<table class="table table-stripped table-hover">
					<tr>
						<td><strong>Account Id </strong></td><td><?php echo $data[0]['account_id']; ?></td>
					</tr>
					<tr>
						<td><strong>Account Name </strong></td><td><?php echo $data[0]['account_name']; ?></td>
					</tr>
					<tr>
						<td><strong>Fix ( F ) / Variabel ( V ) </strong></td><td><?php echo $data[0]['fix_var']; ?></td>
					</tr>					
					<tr>
						<td><strong>Operational ( O )/ Promotion ( P ) : </strong></td><td><?php echo $data[0]['operational_promotion']; ?></td>
					</tr>
				</table>
			</div>
		<?php
	break;
	
}

?>