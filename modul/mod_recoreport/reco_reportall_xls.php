<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	
	
	$budget_id = $_GET['budget_id'];
	$tahun = $_GET['tahun'];
	$departemen = $_GET['depid'];
?>	




	
	<div id="tampil">
		<h3>BUDGET REPORT YEAR : <?php echo $tahun; ?></h3>
		<table>
			<tr>
				<td>No</td>
				<tD>RECO ID</td>
				<td>RECO DATE</td>
				<td>BUDGET ID</td>
				<td>DEPARTEMEN ID</td>
				<td>DEPARTEMEN NAME</td>
				<td>ACCOUNT ID</td>
				<td>ACCOUNT NAME</td>
				<td>AREA ID</td>
				<td>AREA NAME</td>
				<td>DISTRIBUTOR ID</td>
				<td>DISTRIBUTOR NAME</td>
				<td>GROUP PROMO ID</td>
				<td>PROMO TYPE ID</td>
				<td>CLASS ID</td>
				<td>CLASS NAME</td>
				<td>START DATE</td>
				<td>END DATE</td>
				<td>TRANSAKSI</td>
				<td>DESCRIPTION</td>
				<td>CLAIM TRADE OFF</td>
				<td>TYPE OF COST</td>
				<td>FIX VAR</td>
				<td>GROUP OUTLET ID</td>
				<td>GROUP OUTLET NAME</td>
				<td>SALES TARGET</td>
				<td>TOTAL</td>
				<td>TOTAL ALLOW USED</td>
				<td>COST RASIO</td>
				<td>TOTAL CLAIM</td>
				<td>OUTSTANDING</td>
				<td>STATUS</td>
				<td>COMPLETED</td>
				<td>COMPLETED DATE</td>
				<td>APPROVAL 1</td>
				<td>APPROVAL 1 DATE</td>
				<td>APPROVAL 2</td>
				<td>APPROVAL 2 DATE</td>
				<td>CREATED BY</td>
				<td>CREATED DATE</td>
				<td>UPDATE BY</td>
				<td>UPDATE DATE</td>				
			</tr>
			<?php 
				if(strtoupper($departemen) == "ALL"){
					$data = $crud->fetch("v_reco_budget","","departemen_id in(select departemen_id from user_authority where
										 username = '".$_SESSION['username']."') and year(start_date)='".$tahun."'
										 order by departemen_id,budget_id");
				}else{
					$data = $crud->fetch("v_reco_budget","","departemen_id='".$departemen."' 
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
							<td>".$value['reco_id']."</td>
							<td>".$crud->cetak_tanggal($value['reco_date'])."</td>
							<td>".$value['budget_id']."</td>
							<td>".$value['departemen_id']."</td>
							<td>".$value['departemen_name']."</td>
							<td>".$value['account_id']."</td>
							<td>".$value['account_name']."</td>
							<td>".$value['area_id']."</td>
							<td>".$value['area_name']."</td>
							<td>".$value['distributor_id']."</td>
							<td>".$value['distributor_name']."</td>
							<td>".$value['grouppromo_id']."</td>
							<td>".$value['promotype_id']."</td>
							<td>".$value['class_id']."</td>
							<td>".$value['class_name']."</td>
							<td>".$crud->cetak_tanggal($value['start_date'])."</td>
							<td>".$crud->cetak_tanggal($value['end_date'])."</td>
							<td>".$value['transaksi']."</td>
							<td>".$value['description']."</td>
							<td>".$value['claimtradeoff']."</td>
							<td>".$value['typeofcost']."</td>
							<td>".$value['fix_var']."</td>
							<td>".$value['groupoutlet_id']."</td>
							<td>".$value['groupoutlet_name']."</td>
							<td>".$value['sales_target']."</td>
							<td>".$value['total']."</td>
							<td>".$value['total_allow_used']."</td>
							<td>".$value['cost_rasio']."</td>
							<td>".$value['total_claim']."</td>
							<td>".$value['outstanding']."</td>
							<td>".$value['status']."</td>
							<td>".$value['completed']."</td>
							<td>".$crud->cetak_tanggal($value['completed_date'])."</td>
							<td>".$value['approval1']."</td>
							<td>".$crud->cetak_tanggal($value['approval1_date'])."</td>
							<td>".$value['approval2']."</td>
							<td>".$crud->cetak_tanggal($value['approval2_date'])."</td>
							<td>".$value['created_by']."</td>
							<td>".$crud->cetak_tanggal($value['created_date'])."</td>
							<td>".$value['update_by']."</td>
							<td>".$crud->cetak_tanggal($value['update_date'])."</td>						
						  </tr>";
					$no++;
					
					
				}
			?>			
		</table>
		
	</div>
	
<script>
	var tampil = document.getElementById("tampil");
	window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tampil.innerHTML));
    e.preventDefault();
	window.close();
</script>