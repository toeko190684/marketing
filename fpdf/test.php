<?php
	session_start();
	include "../koneksi.php";
	require_once("fpdf.php");
	
	class PDF extends FPDF
	{
		function header()
		{
			$judul = "BUDGET REPORT TAHUN : ".date("Y");
			$this->setfont("Arial","B",12);
			$this->cell(0,20,$judul, "0",1,"c");
			
			$this->setfont("Arial","","9");
			$this->setfillcolor(222,222,222);
			$this->CELL(10,6,'NO',1,0,'C',1);
			$this->CELL(100,6,'COACODE',1,0,'C',1);
			$this->CELL(28,6,'START',1,0,'C',1);
			$this->CELL(28,6,'ADDITIONAL',1,0,'C',1);
			$this->CELL(28,6,'REALISASI',1,0,'C',1);
			$this->CELL(28,6,'RELOKASI',1,0,'C',1);
			$this->CELL(28,6,'OUTSTANDING',1,0,'C',1);
			
			$this->ln();
		}
	}
	
	$data = $crud->fetch("v_detail_budget","coacode,coaname,total","");
	
	
	
	$pdf = new PDF();
	$pdf->addpage("L");	
	
	$pdf->setFont('arial','',9);
	$pdf->setFillColor(255,255,255);

	$no = 1;
	foreach($data as $value){		
		$outstanding = 0;
		$add = $crud->fetch("v_total_additional","","budget_id='".$value['budget_id']."' and coacode='".$value['coacode']."'");
		$reali = $crud->fetch("v_total_realisasi","","budget_id='".$value['budget_id']."' and coacode='".$value['coacode']."'");
		$relok = $crud->fetch("v_total_relokasi","","budget_id='".$value['budget_id']."' and coacode='".$value['coacode']."'");
		$outstanding = $value['total'] + $add[0]['additional']- $reali[0]['realisasi'] + $relok[0]['relokasi'];
			
		
		$pdf->cell(10,6,$no,1,0,'C',1);
		$pdf->cell(100,6,$value['coacode']." - ".$value['coaname'],1,0,'L',1);
		$pdf->CELL(28,6,number_format($value['total'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($add[0]['additional'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($reali[0]['realisasi'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($relok[0]['relokasi'],2,',','.'),1,0,'R',1);
		$pdf->CELL(28,6,number_format($outstanding,2,',','.'),1,0,'R',1);
		
		$no++;
		$pdf->ln();

		$total += $value['total'];
		$additional += $add[0]['additional'];
		$realisasi += $reali[0]['realisasi'];
		$relokasi += $relok[0]['relokasi'];
		$total_outstanding += $outstanding;
	}
	
	$pdf->cell(110,6,"TOTAL",1,0,'C',1);
	$pdf->CELL(28,6,number_format($total,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($additional,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($realisasi,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($relokasi,2,',','.'),1,0,'R',1);
	$pdf->CELL(28,6,number_format($total_outstanding,2,',','.'),1,0,'R',1);
	
	$pdf->output();
	
	
?>