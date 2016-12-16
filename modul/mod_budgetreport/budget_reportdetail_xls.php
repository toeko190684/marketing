<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	
	$budget_id = $_GET['budget_id'];
	$tahun = $_GET['tahun'];
?>	




	
	<div id="tampil">
		<h3>DETAIL BUDGET REPORT YEAR <?= $tahun ?></h3>
		<table>
			<tr>
				<td>No</td>
				<tD>CLASS ID</td>
				<td>CLASS NAME</td>
				<td>OPEN</td>
				<td>ADDITIONAL</td>
				<td>RELOKASI</td>
				<td>RECO</td>
				<td>OUTSTANDING</td>
			</tr>
			<?php 
				$no =1 ;
				$open = 0;
				$additional = 0;
				$relocation = 0;
				$reco = 0;
				$outstanding = 0;
				
				$data = $crud->fetch("v_detail_budget","","budget_id='".$budget_id."'");
				
				foreach($data as $value){
					echo "<tr>	
							<td>".$no."</td>
							<td>".$value['budget_id']."</td>
							<td>".$value['class_id']."</td>
							<td>".$value['class_name']."</td>
							<td align=\"right\">".$value['start_budget']."</td>
							<td align=\"right\">".$value['additional_budget']."</td>
							<td align=\"right\">".$value['relokasi_budget']."</td>
							<td align=\"right\">".$value['reco_budget']."</td>
							<td align=\"right\">".$value['outstanding_budget']."</td>
						  </tr>";
					$no++;
					
					$open += $value['start_budget'];
					$additional += $value['additional_budget'];
					$relocation += $value['relokasi_budget'];
					$reco += $value['reco_budget'];
					$outstanding += $value['outstanding_budget'];
				}
			?>
			
			
			<tR>
				<td colspan="4" align="center">TOTAL</td>
				<td align=\"right\"><?= $open; ?></td>
				<td align=\"right\"><?= $additional ?></td>
				<td align=\"right\"><?= $relocation ?></td>
				<td align=\"right\"><?= $reco ?></td>
				<td align=\"right\"><?= $outstanding ?></td>
			</tr>
		</table>
		
	</div>
	
<script>
	var tampil = document.getElementById("tampil");
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tampil.innerHTML));
    e.preventDefault();
	window.close();
</script>