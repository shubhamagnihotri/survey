<?php

$dbprefix = ' nlp_';
$state_name =array(18,11,12,13,14,15,16,17);
//$state_name =array(74);
$sector_html = '';

class MYPDFSSTATE extends TCPDF {

		public function Header() 
	{
		 
		
		//$image_file = '\Program Files\xampp\htdocs\nlcpr\writereaddata\nlcprpdf\logo_img.JPG';
		//$this->Image($image_file, 10, 10, 350, '', 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
		   // restore auto-page-break status
       	//$this->SetTextColor(0,0,0);

		$this->Rect(0,0,450,17,'F','',$fill_color = array(222,221,170));

		$this->SetFont('helvetica', 'B', 15);
 
		$this->Cell(0, 12, 'Ministry of Development of North Eastern Region', 0, True, 'C', 0, '', 0, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 12);
		$this->Cell(0, 12, 'Non-Lapsable Central Pool of Resource (NLCPR)', 0, True, 'C', 0, '', 0, false, 'M', 'M');

		//$this->Cell(($this->w - $this->original_lMargin - $this->original_rMargin), 0, '', 'T', 0, 'C');
		
		// set the starting point for the page content
		$this->setPageMark();
	}

public function Footer() 
	{
		// Position at 15 mm from bottom
		

		$this->Rect(0,405, 450, 15, 'F','',$fill_color = array(227,225,152));
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 12);
		$this->SetTextColor(0,0,0);
		// Page number
		//$timeReport = 'Report Date: '.August 9, 2012, 12:32 pm
		$timeReport = 'Report Date: '.date("F j, Y, g:i a");

		$this->Cell(0, 12, $timeReport, 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 12, ' Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');



	}
}

foreach($state_name as $statename){
	$sql_project = "select distinct task_project, project_name from nlp_tasks, nlp_projects where project_id=task_project and task_state=$statename and (task_actual_finish_date IS NULL or task_actual_finish_date = '0000-00-00') and task_nlcpr_project=1 order by project_name";

	$log_project = mysql_query( $sql_project );
	$sql_state = "select state_name from ".$dbprefix."state where state_id=$statename";
	$log_state = mysql_query($sql_state);
	$row_state = mysql_fetch_array($log_state);
	$state = $row_state[0];

	$sector_html = '<table border="0" cellspacing="3" cellpadding="5" width="100%">
	<tr bgcolor="#0000CC"><td style="color:#ffffff" colspan="7" align="center">
	SECTOR WISE ONGOING PROJECTS UNDER NLCPR SCHEME IN '.strtoupper($state).'</td></tr>
	<tr><td colspan="7" align="right">(Rs. in Crore)</td></tr>
	<tr bgcolor="#9CB4FE">
       <td width="5%" align="center">Sl. No.</td>
       <td width="38%" align="center">NLCPR Project</td>
       <td width="14%" align="center">Date of Sanction</td>
       <td width="11%" align="center">Approved cost</td>
       <td width="11%" align="center">Total Release</td>
       <td width="11%" align="center">Total Utilisation</td>
       <td width="10%" align="center">%age Utilisation</td>
  </tr>';
	$state_inner = '';
	
	$n = 0;
	$grand_total_rel=0.00;
	$grand_total_uc=0.00;
	$grand_total_app=0.00;
	$grand_total_project=0;
   while($row_project = mysql_fetch_array($log_project)){
		$proj_id = $row_project[0];
		$sql_task =  "select task_id, task_name,task_start_date,task_target_budget, task_actual_finish_date, task_no_of_nlcpr_project from ".$dbprefix."tasks where task_project=$proj_id and task_state=$statename and (task_actual_finish_date IS NULL or task_actual_finish_date = '0000-00-00') and task_nlcpr_project=1 order by task_start_date DESC";

		$log_task = mysql_query( $sql_task);
		$state_inner .= '<tr><td colspan="7" align="left" style="font-size:12;"><strong>'.$row_project["project_name"].'</strong></td></tr>';
		
		$sector_total_rel=0.00;
		$sector_total_uc=0.00;
		$sector_total_app=0.00;
		$sector_total_project=0;
		$state_inner_main = '';
		
		while($row_task = mysql_fetch_array($log_task)){
			$n = $n+1;
			
			$css ='';
			
			if ($n%2==0) {$css = 'bgcolor="#CEE2FA"';}
			
			$tk_id = $row_task["task_id"];
			
			
			$sector_total_project = ($sector_total_project + $row_task["task_no_of_nlcpr_project"]);
			$grand_total_project = ($grand_total_project + $row_task["task_no_of_nlcpr_project"]);
			
			$sector_total_app = ($sector_total_app + ($row_task["task_target_budget"]/100));
			$grand_total_app = ($grand_total_app + ($row_task["task_target_budget"]/100));
			
			$sql_sanction = "select sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1  from ".$dbprefix."sanction where sanction_task = $tk_id";
			$log_sanction = mysql_query($sql_sanction);
			$row_sanction = mysql_fetch_array($log_sanction);
			$total_rel = ($row_sanction["rel"]+$row_sanction["rel1"])/100;
			
			$sector_total_rel = ($sector_total_rel + $total_rel);
			$grand_total_rel = ($grand_total_rel + $total_rel);
			
			$sql_utilisation = "select sum(utilisation_amount) as util from ".$dbprefix."utilisation where utilisation_task = $tk_id";
			$log_utilisation = mysql_query($sql_utilisation);
			$row_utilisation = mysql_fetch_array($log_utilisation);
			$total_uc = $row_utilisation["util"]/100;
			
			$sector_total_uc = ($sector_total_uc + $total_uc);
			$grand_total_uc = ($grand_total_uc + $total_uc);
			$target_budget = $row_task["task_target_budget"]/100;
			if ($row_task["task_start_date"] == 0000-00-00){
				$sanc_date = '-';	
			}else {
				$sanc_date = date("d/m/Y",strtotime($row_task["task_start_date"]));				
			}

			$state_inner_main .= '<tr '. $css.'>
			<td align="left">'.$n.'</td>
			<td align="left">'.$row_task["task_name"].'</td>
			<td align=centre>'.$sanc_date.'</td>
			<td align="right">'.number_format($target_budget,2,'.','').'</td>
			<td align="right">'.number_format($total_rel,2,'.','').'</td>
			<td align="right">'. number_format($total_uc,2,'.','').'</td>';
			if ($total_rel == 0 ) { $total_rel = 1;}  $utlia = round(($total_uc/$total_rel)*100);
			$state_inner_main .= '<td align="right">'.$utlia.' %</td>
			</tr>';
		}
		
		$state_inner_main .='<tr bgcolor="#9CB4FE">
	        <td colspan="3" align="right">Total of '.$row_project["project_name"].' ('.$sector_total_project.' Projects)</td>
	        <td align="right">'. number_format($sector_total_app,2,'.','') .'</td>
	        <td align="right">'. number_format($sector_total_rel,2,'.','') .'</td>
	        <td align="right">'. number_format($sector_total_uc,2,'.','') .'</td>
	        <td align="right">&nbsp;</td>
        </tr>';
		$state_inner_main .='<tr>
	        <td colspan="7" >&nbsp;</td></tr>';
		$state_inner .= $state_inner_main;
	}
			$state_inner .='<tr bgcolor="#9CB4FE">
	        <td colspan="3" align="right">GRAND TOTAL ('.$grand_total_project.' Projects)</td>
	        <td align="right">'. number_format($grand_total_app,2,'.','') .'</td>
	        <td align="right">'. number_format($grand_total_rel,2,'.','') .'</td>
	        <td align="right">'. number_format($grand_total_uc,2,'.','') .'</td>
	        <td align="right">&nbsp;</td>
        </tr>';

$sector_html .= $state_inner.'</table>';



$pdf = new MYPDFSSTATE('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetMargins(3, PDF_MARGIN_TOP, 3);

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

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '',10);

// add a page
$pdf->AddPage();
$pdf->Ln(5);

$pdf->writeHTML($sector_html, true, false, true, false, '');

	//$dir= 'C:\Program Files\xampp\htdocs\nlcpr\writereaddata\nlcprpdf\state/';
	
	$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/state/';

$filename = $dir.$state.'_ongoing.pdf';

$pdf->Output($filename,'F');

//$pdf->Output($filename, 'I');
}



$state_name =array(18,11,12,13,14,15,16,17);
//$state_name =array(74);
$sector_html = '';
foreach($state_name as $statename){
	$sector_html = '';
	$sql_project = "select distinct task_project, project_name from nlp_tasks, nlp_projects where project_id=task_project and task_state=$statename and (task_actual_finish_date IS NOT NULL and task_actual_finish_date != '0000-00-00') and task_nlcpr_project=1 order by project_name";

	$log_project = mysql_query( $sql_project );
	$sql_state = "select state_name from ".$dbprefix."state where state_id=$statename";
	$log_state = mysql_query($sql_state);
	$row_state = mysql_fetch_array($log_state);
	$state = $row_state[0];

	$sector_html = '<table border="0" cellspacing="3" cellpadding="5" width="100%">
	<tr bgcolor="#A50022"><td style="color:#ffffff" colspan="7" align="center">
	SECTOR WISE COMPLETED PROJECTS UNDER NLCPR SCHEME IN ' .strtoupper($state).'</td></tr>
	<tr><td colspan="7" align="right">(Rs. in Crore)</td></tr>
	<tr bgcolor="#FDA9B8">
       <td width="5%" align="center">Sl. No.</td>
       <td width="38%" align="center">NLCPR Project</td>
       <td width="14%" align="center">Date of Sanction</td>
       <td width="11%" align="center">Approved cost</td>
       <td width="11%" align="center">Total Release</td>
       <td width="11%" align="center">Total Utilisation</td>
       <td width="10%" align="center">%age Utilisation</td>
  </tr>';
	$state_inner = '';
	
	$n = 0;
	$grand_total_rel=0.00;
	$grand_total_uc=0.00;
	$grand_total_app=0.00;
	$grand_total_project=0;
   while($row_project = mysql_fetch_array($log_project)){
		$proj_id = $row_project[0];
		$sql_task =  "select task_id, task_name,task_start_date,task_target_budget, task_actual_finish_date, task_no_of_nlcpr_project from ".$dbprefix."tasks where task_project=$proj_id and task_state=$statename and (task_actual_finish_date IS NOT NULL and task_actual_finish_date != '0000-00-00') and task_nlcpr_project=1 order by task_start_date DESC";

		$log_task = mysql_query( $sql_task);
		$state_inner .= '<tr><td colspan="7" align="left" style="font-size:12;"><strong>'.$row_project["project_name"].'</strong></td></tr>';
		
		$sector_total_rel=0.00;
		$sector_total_uc=0.00;
		$sector_total_app=0.00;
		$sector_total_project=0;
		$state_inner_main = '';
		
		while($row_task = mysql_fetch_array($log_task)){
			$n = $n+1;
			
			$css ='';
			
			if ($n%2==0) {$css = 'bgcolor="#FDD0D7"';}
			
			$tk_id = $row_task["task_id"];
			
			
			$sector_total_project = ($sector_total_project + $row_task["task_no_of_nlcpr_project"]);
			$grand_total_project = ($grand_total_project + $row_task["task_no_of_nlcpr_project"]);
			
			$sector_total_app = ($sector_total_app + ($row_task["task_target_budget"]/100));
			$grand_total_app = ($grand_total_app + ($row_task["task_target_budget"]/100));
			
			$sql_sanction = "select sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1  from ".$dbprefix."sanction where sanction_task = $tk_id";
			$log_sanction = mysql_query($sql_sanction);
			$row_sanction = mysql_fetch_array($log_sanction);
			$total_rel = ($row_sanction["rel"]+$row_sanction["rel1"])/100;
			
			$sector_total_rel = ($sector_total_rel + $total_rel);
			$grand_total_rel = ($grand_total_rel + $total_rel);
			
			$sql_utilisation = "select sum(utilisation_amount) as util from ".$dbprefix."utilisation where utilisation_task = $tk_id";
			$log_utilisation = mysql_query($sql_utilisation);
			$row_utilisation = mysql_fetch_array($log_utilisation);
			$total_uc = $row_utilisation["util"]/100;
			
			$sector_total_uc = ($sector_total_uc + $total_uc);
			$grand_total_uc = ($grand_total_uc + $total_uc);
			$target_budget = $row_task["task_target_budget"]/100;
			if ($row_task["task_start_date"] == 0000-00-00){
				$sanc_date = '-';	
			}else {
				$sanc_date = date("d/m/Y",strtotime($row_task["task_start_date"]));				
			}

			$state_inner_main .= '<tr '. $css.'>
			<td align="left">'.$n.'</td>
			<td align="left">'.$row_task["task_name"].'</td>
			<td align=centre>'.$sanc_date.'</td>
			<td align="right">'.number_format($target_budget,2,'.','').'</td>
			<td align="right">'.number_format($total_rel,2,'.','').'</td>
			<td align="right">'. number_format($total_uc,2,'.','').'</td>';
			if ($total_rel == 0 ) { $total_rel = 1;}  $utlia = round(($total_uc/$total_rel)*100);
			$state_inner_main .= '<td align="right">'.$utlia.' %</td>
			</tr>';
		}
		
		$state_inner_main .='<tr bgcolor="#FDA9B8">
	        <td colspan="3" align="right">Total of '.$row_project["project_name"].' ('.$sector_total_project.' Projects)</td>
	        <td align="right">'. number_format($sector_total_app,2,'.','') .'</td>
	        <td align="right">'. number_format($sector_total_rel,2,'.','') .'</td>
	        <td align="right">'. number_format($sector_total_uc,2,'.','') .'</td>
	        <td align="right">&nbsp;</td>
        </tr>';
		$state_inner_main .='<tr>
	        <td colspan="7" >&nbsp;</td></tr>';
		$state_inner .= $state_inner_main;
	}
			$state_inner .='<tr bgcolor="#FDA9B8">
	        <td colspan="3" align="right">GRAND TOTAL ('.$grand_total_project.' Projects)</td>
	        <td align="right">'. number_format($grand_total_app,2,'.','') .'</td>
	        <td align="right">'. number_format($grand_total_rel,2,'.','') .'</td>
	        <td align="right">'. number_format($grand_total_uc,2,'.','') .'</td>
	        <td align="right">&nbsp;</td>
        </tr>';

$sector_html .= $state_inner.'</table>';



$pdf = new MYPDFSSTATE('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(3, PDF_MARGIN_TOP, 3);

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

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '',10);

// add a page
$pdf->AddPage();
$pdf->Ln(5);

$pdf->writeHTML($sector_html, true, false, true, false, '');


//$dir='C:\Program Files\xampp\htdocs\nlcpr\writereaddata\nlcprpdf\state';

$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/state/';


$filename = $dir.$state.'_completed.pdf';

$pdf->Output($filename,'F');

//$pdf->Output($filename, 'I');

}

?>