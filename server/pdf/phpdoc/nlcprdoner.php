<?php

//include('/home/nlcpr/docs/pdf/tcpdf.php');
//include('/home/nlcpr/docs/pdf/config/lang/eng.php');



// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->SetMargins(1, PDF_MARGIN_TOP, -0);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('Courier', '', 14,'',true);
// add a page
$pdf->AddPage();
$pdf->Ln(5);
// create some HTML conten
$html = 'STATE WISE &amp; YEAR WISE SUMMARY OF RELEASE OF FUNDS';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/summery/';
$filename = 'testall.pdf';
$pdf->Output($dir.$filename,'F');
$pdf->Output($dir.$filename,'I');

?>