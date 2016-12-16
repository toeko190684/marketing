<?php
	error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
	session_start();
	include "../../config/koneksi.php";
	require_once("../../fpdf/fpdf.php");
	
	$budget_id = $_GET['budget_id'];
	
	
	class PDF extends FPDF
	{
		
		function header()
		{
			$budget_id = $_GET['budget_id'];
			$tahun = $_GET['tahun'];
			$judul = "DETAIL BUDGET REPORT TAHUN ".$tahun;
			$this->setfont("Arial","B",12);
			$this->cell(0,20,$judul, "0",1,"c");
			
			$this->setfont("Arial","","9");
			$this->setfillcolor(222,222,222);
			$this->CELL(10,6,'NO',1,0,'C',1);
			$this->CELL(32,6,'BUDGET ID',1,0,'C',1);
			$this->CELL(96,6,'CLASS',1,0,'C',1);
			$this->CELL(28,6,'OPEN',1,0,'C',1);
			$this->CELL(28,6,'ADDITIONAL',1,0,'C',1);
			$this->CELL(28,6,'RELOCATION',1,0,'C',1);
			$this->CELL(28,6,'RECO',1,0,'C',1);
			$this->CELL(28,6,'OUTSTANDING',1,0,'C',1);
			
			$this->ln();
		}
	}
	
	
	$data = $crud->fetch("v_detail_budget","","budget_id='".$budget_id."'");
	
	
	
	$pdf = new PDF();
	$pdf->addpage("L");	
	
	$pdf->setFont('arial','',9);
	$pdf->setFillColor(255,255,255);

	$no = 1;
	$open = 0;
	$additional = 0;
	$relocation = 0;
	$reco = 0;
	$outstanding = 0;
	foreach($data as $value){				
		$pdf->cell(10,6,$no,1,0,'C',1);
		$pdf->cell(32,6,$value['budget_id'],1,0,'C',1);
		$pdf->cell(96,6,$value['class_id']." - ".$value['class_name'],1,0,'L',1);
		$pdf->CELL(28,6,number_format($value['start_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['additional_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['relokasi_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['reco_budget'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($value['outstanding'],2,',','.'),1,0,'R',1);
		
		$no++;
		$pdf->ln();

		$open += $value['start_budget'];
		$additional += $value['additional_budget'];
		$relocation += $value['relokasi_budget'];
		$reco += $value['reco_budget'];
		$outstanding += $value['$outstanding'];
	}
	
	$pdf->cell(138,6,"TOTAL",1,0,'C',1);
	$pdf->CELL(28,6,number_format($open,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($additional,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($relocation,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($reco,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($outstanding,2,',','.'),1,0,'R',1);
	
	$pdf->output();
	
	
?>