<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	
	$budget_id = $_GET['budget_id'];
	$tahun = $_GET['tahun'];
	$departemen = $_GET['depid'];
?>	




	
	<div id="tampil">
		<h3>BUDGET REPORT TAHUN : <?php echo $tahun; ?>, DEPARTEMEN  : <?= $departemen ?>, BUDGET : <?= $budget_id ?></h3>
		<table>
			<tr>
				<td>No</td>
				<tD>BUDGET ID</td>
				<td>DEPARTEMEN</td>
				<td>OPEN</td>
				<td>ADDITIONAL</td>
				<td>RELOCATION</td>
				<td>RECO</td>
				<td>OUTSTANDING</td>
			</tr>
			<?php 
				if(strtoupper($departemen) == "ALL"){
					$data = $crud->fetch("v_budget_summary","","departemen_id in(select departemen_id from user_authority where
										 username = '".$_SESSION['username']."') and year(start_date)='".$tahun."'
										 order by departemen_id,budget_id");
				}else{
					$data = $crud->fetch("v_budget_summary","","departemen_id='".$departemen."' 
										 and year(start_date)='".$tahun."' order by departemen_id,budget_id");
				}
	
				$no =1 ;
				$open_budget = 0;
				$additional_budget = 0;
				$relokasi_budget = 0;
				$reco_budget = 0;
				$outstanding_budget = 0;
				
				foreach($data as $value){
					echo "<tr>	
							<td>".$no."</td>
							<td>".$value['budget_id']."</td>
							<td>".$value['departemen_id']." - ".$value['departemen_name']."</td>
							<td>".$value['open_budget']."</td>
							<td>".$value['additional_budget']."</td>
							<td>".$value['relokasi_budget']."</td>
							<td>".$value['reco_budget']."</td>
							<td>".$value['outstanding_budget']."</td>
						  </tr>";
					$no++;
					
					$open_budget += $value['open_budget'];
					$additional_budget += $value['additional_budget'];
					$relokasi_budget += $value['relokasi_budget'];
					$reco_budget += $value['reco_budget'];
					$outstanding_budget += $value['outstanding_budget'];
				}
			?>
			
			
			<tR>
				<td colspan="3" align="center">TOTAL</td>
				<td><?= $open_budget; ?></td>
				<td><?= $additional_budget ?></td>
				<td><?= $relokasi_budget ?></td>
				<td><?= $reco_budget ?></td>
				<td><?= $outstanding_budget ?></td>
			</tr>
		</table>
		
	</div>
	
<script>
	var tampil = document.getElementById("tampil");
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tampil.innerHTML));
    e.preventDefault();
	window.close();
</script>