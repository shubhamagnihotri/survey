<?php
ini_set('max_execution_time',420);
	$original_mem = ini_get('memory_limit');
// then set it to the value you think you need (experiment)

ini_set('memory_limit','256M');
ini_set('post_max_size','128M');
set_time_limit(0);




	$dbprefix = ' nlp_';
	$sql_state = "select distinct task_state, state_name from nlp_tasks, nlp_state where state_id=task_state and task_nlcpr_project=1 order by task_state";
	$log_state = mysql_query( $sql_state );
	
	$sector_html ='';
	$sector_state = '';
	
	while($row_state = mysql_fetch_array($log_state)){
	$st_id = $row_state[0];

	$sql_task = "select task_id, task_name,task_start_date,task_target_budget,task_actual_finish_date, task_no_of_nlcpr_project from nlp_tasks where task_state=$st_id and task_nlcpr_project=1 order by task_start_date DESC";

	$log_task = mysql_query( $sql_task);

	$sector_state .= '<tr><td colspan="8" align="left" style="font-size:12;"><strong>'.$row_state["state_name"].'</strong></td></tr>';

	$sector_total_rel=0.00;
	$sector_total_uc=0.00;
	$sector_total_app=0.00;
	$sector_total_project=0;
	$sector_state_inner='';
	
		while($row_task = mysql_fetch_array($log_task)){
			$n = $n+1;
			$css ='';
			if ($n%2==0) { $css = 'bgcolor="#E4C8F1"'; }
			$tk_id = $row_task["task_id"];
			$sector_total_app = ($sector_total_app + $row_task["task_target_budget"]/100);
			$grand_total_app = ($grand_total_app + $row_task["task_target_budget"]/100);
			$sector_total_project = ($sector_total_project + $row_task["task_no_of_nlcpr_project"]);
			$grand_total_project = ($grand_total_project + $row_task["task_no_of_nlcpr_project"]);
			$sql_sanction = "select sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1 from ".$dbprefix."sanction where sanction_task = $tk_id";
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
	
			if ($row_task["task_start_date"] == 0000-00-00){
				$SencDate = '-';	
			}else {
				$SencDate = date("d/m/Y",strtotime($row_task["task_start_date"]));				
			}
			
			if ($row_task["task_actual_finish_date"] == 0000-00-00){
				$CompDate = '-';	
			}else {
				$CompDate = date("d/m/Y",strtotime($row_task["task_actual_finish_date"]));				
			}
			
			
			//$sanc_date = intval($row_task["task_start_date"]) ? new CDate( $row_task["task_start_date"]) : null;
			//$finish_date = intval($row_task["task_actual_finish_date"]) ? new CDate( $row_task["task_actual_finish_date"]) : null;

			$apdCost = $row_task["task_target_budget"]/100;
			$sector_state_inner .= '<tr>
				<td align="left" '.$css.'> '.$n.'</td>
	
			   <td align="left" '.$css.'>'.$row_task["task_name"].'</td>
	
			   <td align="centre" '.$css.'>'.$SencDate.'</td>
	
			   <td align="right" '.$css.'>'.number_format($apdCost,2,'.','').'</td>
	
			   <td align="right" '.$css.'>'.number_format($total_rel,2,'.','').'</td>
	
			   <td align="right" '.$css.'>'.number_format($total_uc,2,'.','').'</td>';
				
				if ($total_rel == 0 ) { $total_rel = 1;} 

			   $sector_state_inner .= '<td align="right" '.$css.'>'.round(($total_uc/$total_rel)*100).' %</td>
	
			   <td align="centre" '.$css.'>'.$CompDate.'</td>
				 </tr>';
		}

//bgcolor="#CC99FE"
			$sector_state .= $sector_state_inner;
	  	 	$sector_state .='<tr>
	  	    <td colspan="3" align="right">Total for '.$row_state["state_name"].' ('. $sector_total_project.' Projects)</td>
			<td align="right">'.number_format($sector_total_app,2,'.','').'</td>
	  	    <td align="right">'.number_format($sector_total_rel,2,'.','').'</td>
 	        <td align="right">'.number_format($sector_total_uc,2,'.','').'</td>
  	        <td align="right"></td>
  	        <td align="right"></td>
	  	    </tr>';
			if($row_state["state_name"] == 'Common to NER')
			{
			
			}
			else
			{
			'
			<tr><td colspan="8">&nbsp;</td></tr>';
$sector_state .='<br pagebreak="true"/>';
			}
			
			}

	$sector_html .= '<table width="100%" cellSpacing="1" cellPadding="1"  align="center">
	<tr bgcolor="#9A00FF"><td colSpan="8" align="center" style="color:#ffffff;">STATE WISE  & PROJECT WISE UTILISATION OF RELEASED FUNDS  </td> </tr>
	<tr> <td colSpan="8" align="right">(Rs. in Crore)</td> </tr>
    <tr bgcolor="#CC99FE">
		<td width="6%" align="center">Sl. No.</td>
		<td width="35%" align="center">NLCPR Project</td>
		<td width="12%" align="center">Date of Sanction</td>
		<td width="9%" align="center">Approved Cost</td>
		<td width="9%" align="center">Total Release</td>
		<td width="8%" align="center">Total Utilisation</td>
		<td width="8%" align="center">%age Utilisation</td>
		<td width="13%" align="center">Date of Completion</td>
   </tr>'.$sector_state.'

	<tr><td colspan="8">&nbsp;</td></tr>

   <tr  bgcolor="#CC99FE">
   <td colspan="3" align="right">GRAND TOTAL ('.$grand_total_project.' Projects)
	        </td>
	        <td align="right"><u>'. number_format($grand_total_app,2,'.','').'</u></td>
	        <td align="right"><u>'.number_format($grand_total_rel,2,'.','').'</u></td>
	        <td align="right"><u>'.number_format($grand_total_uc,2,'.','').'</u></td>
	        <td align="right"></td>
  	        <td align="right"></td>
        </tr>
</table>';



class MYPDFSSECTOR extends TCPDF {

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

$pdf = new MYPDFSSECTOR('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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

$pdf->setFontSubsetting(false);
// set font
$pdf->SetFont('dejavusans', '', 9);

// add a page
$pdf->AddPage();
$pdf->Ln(5);


$pdf->writeHTML($sector_html, true, false, true, false, '');
$pdf->lastPage();

	$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/sector/';
	//$dir= "C:/xampp/htdocs/nlcpr/writereaddata/nlcprpdf/sector/";
	//$dir= "C:/xampp/htdocs/nlcpr/writereaddata/nlcprpdf/sector/";
	$filename = $dir.'all.pdf';
	ob_clean();
	$pdf->Output($filename,'F');



	$pdf->Output($filename, 'I');
ini_set('memory_limit', $original_mem);
ini_set('max_execution_time',60);
?>