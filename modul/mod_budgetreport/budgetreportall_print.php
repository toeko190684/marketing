<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	require_once("../../fpdf/fpdf.php");
	
	$budget_id = $_GET['budget_id'];
	$tahun = $_GET['tahun'];
	$departemen = $_GET['depid'];
	
	class PDF extends FPDF
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
			$this->CELL(35,6,'BUDGET ID',1,0,'C',1);
			$this->CELL(65,6,'DEPARTEMEN',1,0,'C',1);
			$this->CELL(28,6,'OPEN',1,0,'C',1);
			$this->CELL(28,6,'ADDITIIONAL',1,0,'C',1);
			$this->CELL(28,6,'RELOCATION',1,0,'C',1);
			$this->CELL(28,6,'RECO',1,0,'C',1);
			$this->CELL(28,6,'OUTSTANDING',1,0,'C',1);
			
			$this->ln();
		}
	}
	
	if(strtoupper($departemen) == "ALL"){
		$data = $crud->fetch("v_budget_summary","","departemen_id in(select departemen_id from user_authority where
							 username = '".$_SESSION['username']."') and year(start_date)='".$tahun."'
							 order by departemen_id,budget_id");
	}else{
		$data = $crud->fetch("v_budget_summary","","departemen_id='".$departemen."' 
							 and year(start_date)='".$tahun."' order by departemen_id,budget_id");
	}
	
	
	$pdf = new PDF();
	$pdf->addpage("L");	
	
	$pdf->setFont('arial','',9);
	$pdf->setFillColor(255,255,255);

	$no = 1;
	$open_budget = 0;
	$additional_budget = 0;
	$relokasi_budget = 0;
	$reco_budget = 0;
	$outstanding_budget = 0;
	
	foreach($data as $value){		
		$pdf->cell(6,6,$no,1,0,'C',1);
		$pdf->cell(35,6,$value['budget_id'],1,0,'L',1);
		$pdf->cell(65,6,$value['departemen_id']."-".$value['departemen_name'],1,0,'L',1);
		$pdf->CELL(28,6,number_format($value['open_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['additional_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['relokasi_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['reco_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['outstanding_budget'],2,',','.'),1,0,'R',1);
		
		$no++;
		$pdf->ln();

		$open_budget += $value['open_budget'];
		$additional_budget += $value['additional_budget'];
		$relokasi_budget += $value['relokasi_budget'];
		$reco_budget += $value['reco_budget'];
		$outstanding_budget += $value['outstanding_budget'];
	}
	
	$pdf->cell(106,6,"TOTAL",1,0,'C',1);
	$pdf->CELL(28,6,number_format($open_budget,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($additional_budget,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($relokasi_budget,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($reco_budget,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($outstanding_budget,2,',','.'),1,0,'R',1);
	
	$pdf->output();
	
	
?>