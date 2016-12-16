<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	require_once("../../fpdf/fpdf.php");
	
	$budget_id = $_GET['budget_id'];
	$tahun = $_GET['tahun'];
	$departemen = $_GET['depid'];
	
	/*class PDF extends FPDF
	{
		
		function header()
		{
			$tahun = $_GET['tahun'];
			$departemen = $_GET['depid'];
			$budget_id = $_GET['budget_id'];
			$judul = "BUDGET REPORT TAHUN : ".$tahun."  , DEPARTEMEN : ".$departemen.
					 " , BUDGET : ".$budget_id;
			
			$this->setfont("Arial","B",12);
			$this->cell(0,20,$judul, "0",1,"c");
			
			$this->setfont("Arial","","9");
			$this->setfillcolor(222,222,222);
			$this->CELL(6,6,'NO',1,0,'C',1);
			$this->CELL(35,6,'RECO ID',1,0,'C',1);
			$this->CELL(20,6,'DATE',1,0,'C',1);
			$this->CELL(32,6,'BUDGET ID',1,0,'C',1);
			$this->CELL(50,6,'CLASS PROMO',1,0,'C',1);
			$this->CELL(50,6,'TRANSACTION',1,0,'C',1);
			$this->CELL(26,6,'RECO',1,0,'C',1);
			$this->CELL(26,6,'CLAIM',1,0,'C',1);
			$this->CELL(26,6,'OUTSTANDING',1,0,'C',1);
			
			$this->ln();
		}
	}*/
	

	$pdf = new FPDF();
	$pdf->addpage("L");	
	$pdf->setfont("Arial","B",'c',16);
	$pdf->cell(0,20,"RECO REPORT YEAR : ".$tahun, "0",1,"c");
		
		
	//cari dulu budget apa aja ya
	if(strtoupper($departemen) == "ALL"){
		$budget = $crud->fetch("v_budget_summary","budget_id,departemen_id,departemen_name",
							 "departemen_id in(select departemen_id from user_authority where
							 username = '".$_SESSION['username']."') and year(start_date)='".$tahun."'
							 order by departemen_id,budget_id");
	}else{
		$budget = $crud->fetch("v_budget_summary","budget_id,departemen_id,departemen_name",
							 "departemen_id ='".$departemen."' and year(start_date)='".$tahun."'
							 order by departemen_id,budget_id");
	}
	
	foreach($budget as $row){		
		//bikin header untuk tiap tabel
		$judul = "DEPT.ID : ".$row['departemen_id']." \ ".$row['departemen_name'].
					 " , BUDGET ID : ".$row['budget_id'];
		$pdf->setfont("Arial","B",12);
		$pdf->cell(0,20,$judul, "0",1,"c");
		
		$pdf->setfont("Arial","","9");
		$pdf->setfillcolor(222,222,222);
		$pdf->CELL(6,6,'NO',1,0,'C',1);
		$pdf->CELL(35,6,'RECO ID',1,0,'C',1);
		$pdf->CELL(20,6,'DATE',1,0,'C',1);
		$pdf->CELL(60,6,'CLASS PROMO',1,0,'C',1);
		$pdf->CELL(60,6,'TRANSACTION',1,0,'C',1);
		$pdf->CELL(26,6,'RECO',1,0,'C',1);
		$pdf->CELL(26,6,'CLAIM',1,0,'C',1);
		$pdf->CELL(26,6,'OUTSTANDING',1,0,'C',1);
		$pdf->ln();
		
		$data = $crud->fetch("v_reco_budget","","budget_id='".$row['budget_id']."'
								 order by departemen_id,budget_id");
		
		
		//set kembali ke awal setiap kali perulangan
		$no = 1;
		$reco = 0;
		$claim = 0;
		$outstanding = 0;
		
		$pdf->setFont('arial','',9);
		$pdf->setFillColor(255,255,255);
		foreach($data as $value){		
			$pdf->cell(6,6,$no,1,0,'C',1);
			$pdf->cell(35,6,$value['reco_id'],1,0,'L',1);
			$pdf->cell(20,6,$value['reco_date'],1,0,'L',1);
			$pdf->cell(60,6,$value['class_id']."-".$value['class_name'],1,0,'L',1);
			$pdf->cell(60,6,$value['transaksi'],1,0,'L',1);
			$pdf->CELL(26,6,number_format($value['total_allow_used'],2,',','.'),1,0,'R',1);
			$pdf->CELL(26,6,number_format($value['total_claim'],2,',','.'),1,0,'R',1);
			$pdf->CELL(26,6,number_format($value['outstanding'],2,',','.'),1,0,'R',1);
			
			$no++;
			$pdf->ln();

			$reco += $value['total_allow_used'];
			$claim += $value['total_claim'];
			$outstanding += $value['outstanding'];
		}
		
		$pdf->cell(181,6,"TOTAL",1,0,'C',1);
		$pdf->CELL(26,6,number_format($reco,2,',','.'),1,0,'R',1);
		$pdf->CELL(26,6,number_format($claim,2,',','.'),1,0,'R',1);
		$pdf->CELL(26,6,number_format($outstanding,2,',','.'),1,0,'R',1);
		$pdf->ln();$pdf->ln();
	}
	
	$pdf->output();

	
?>