<?php

/*
define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');

include('/home/nlcpr/docs/pdf/connection.php');
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');

include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_bar.php');
*/

class MYPDFSSECTOR extends TCPDF {

	public function Header() 
	{
		$this->Rect(0,0,450,17,'F','',$fill_color = array(222,221,170));
		$this->SetFont('helvetica', 'B', 15);
		$this->Cell(0, 12, '', 0, True, 'C', 0, '', 0, false, 'M', 'M');
		$this->Cell(0, 12, 'Ministry of Development of North Eastern Region', 0, True, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 12);
		$this->Cell(0, 12, 'Non-Lapsable Central Pool of Resource (NLCPR)', 0, True, 'C', 0, '', 0, false, 'M', 'M');
		$this->setPageMark();
	}

public function Footer() 
	{
		
		$this->Rect(0,285, 450, 25, 'F','',$fill_color = array(227,225,152));
		$this->SetY(-12);

		// Set font
		$this->SetFont('helvetica', '', 12);
		$this->SetTextColor(0,0,0);
		
		$timeReport = 'Report Date: '.date("F j, Y, g:i a");

		$this->Cell(0, 12, $timeReport, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 12, ' Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');



	}
}

//$pdf = new MYPDFSSECTOR('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf = new MYPDFSSECTOR(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
//$pdf->SetFont('helvetica', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Image Clipping using geometric functions', '', 0, 'C', 2, 0, false, false, 0);

//Start Graphic Transformation
$pdf->StartTransform();

// set clipping mask
// draw jpeg image to be clipped


//$pdf->Image('C:\Program Files\xampp\htdocs\nlcpr\writereaddata\nlcprpdf\chart\sector_wise_chart.png', 10, 20, 400, 250, '', '', '', true, 72);
//$dir='\Program Files\xampp\htdocs\nlcpr\writereaddata\nlcprpdf\chart/';
//$filename = 'sectorwise_pdf.pdf';

$pdf->Image('/home/nlcpr/docs/writereaddata/nlcprpdf/chart/sector_wise_chart.png', 16, 40, 180, 140, '', '', '', true, 100);

$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/chart/';


$filename = 'sectorwise_pdf.pdf';

$pdf->Output($dir.$filename,'F');

//$pdf->Output($dir.$filename,'I');

?>