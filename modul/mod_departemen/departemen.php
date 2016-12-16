<?php
$aksi="modul/mod_departemen/aksi_departemen.php";

switch($_GET[act]){
  default:
    $data = $crud->fetch("v_user_authority","","username='".$_SESSION['username']."'");
	?>
		<div class="col-md-4">
			<table class="table table-bordered table-hover">
			<tr>
				<td>No</td><td>Departemen</td>
			</tr>
			<?php
				$i = 1;
				foreach($data as $value){
					echo "<tr>
								<td>".$i++."</td>
								<td>".$value['departemen_id']." - ".$value['departemen_name']."</td>
						  </tr>";
				}
			?>
			</table>
		</div>		
	<?php 
   break;  
}
?>
