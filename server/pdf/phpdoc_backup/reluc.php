<?php
error_reporting(0);
ini_set('max_execution_time',1100);
$original_mem = ini_get('memory_limit');


class MYPDFRELUC extends TCPDF {

		public function Header() 
	{
		 
		$this->Rect(0,0,450,17,'F','',$fill_color = array(222,221,170));
		$this->setFontSubsetting(false);
		$this->SetFont('helvetica', 'B', 15);
 
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

$state_id=array(11,12,13,14,15,16,17);



foreach($state_id as $statename){

	
	 $from = '1998/04/01'; //1998
	 $current_month=date("m");
	 $today = date("Y/m/d");

	if($current_month<=3){
		$to=date("Y")-1;
	}else{
		$to=date("Y");
	}
	
	$fromyear = $from;
	$toyear =  $to;

	$x1 = $from;
	$x3 = $from;
	$x2 = $to;
	$colsp = 1;
	$htmlYear = '';
	while($x1 <= $x2)
		{
			$x3 = $x3+1;
			$colsp = $colsp + 1;
			//echo substr($x1To,-2).'-'.substr($x3To,-2).'<br>';;
			$htmlYear .= '<td align="center" style="font-size:24px;">'.substr($x1,-2).'-'.substr($x3,-2).'</td>';
			if(!$x1){ break; }
			$x1 = $x1 + 1;			
		}


	
	$sql_project = "select distinct task_project, project_name from nlp_tasks, nlp_projects where project_id=task_project and task_start_date between '$from' and '$today' and task_state='$statename' and task_nlcpr_project=1 order by project_name";
	
	 $log_project = mysql_query($sql_project);
	
	$htmlCont = "";
	$n=0;
	$grand_total_rel=0.00;
	$grand_total_uc=0.00;
	$grand_total_app=0.00;
	$grand_total_project=0;



	while($row_project = mysql_fetch_array($log_project))
	{
	

		$proj_id = $row_project[0];
			//$proj_id = 892;
		$sql_task =  " select task_id, task_name,task_start_date,task_target_budget,task_actual_finish_date,
		task_no_of_nlcpr_project, task_end_date, task_revise_finish_date from nlp_tasks
		where task_project=$proj_id and task_start_date between '$from' and '$today' and task_state='$statename' and task_nlcpr_project=1 order by task_start_date DESC";
		
		
		
		//echo ($sql_task);
		//die();
		$log_task = mysql_query( $sql_task);

	    $htmlCont .= '<tr>';
		$htmlCont .=  '<td  colspan="11" align="left" style="font-size:12;"><strong>'.$row_project["project_name"].'</strong></td></tr>';



	$sector_total_rel=0.00;
	$sector_total_uc=0.00;
	$sector_total_app=0.00;
	$sector_total_project=0;

	while($row_task = mysql_fetch_array($log_task))
    {
		$tk_id = $row_task["task_id"];

        $sql_sanction = "select sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1 from nlp_sanction where sanction_task = $tk_id";
		$log_sanction = mysql_query($sql_sanction);
		$row_sanction = mysql_fetch_array($log_sanction);
		$total_rel = ($row_sanction["rel"]+$row_sanction["rel1"]);
		$full_rel = $row_sanction["rel"];  				//It would be more than or equal to 90% of the project cost and considered as full release.

        $sql_utilisation = "select sum(utilisation_amount) as util from nlp_utilisation where utilisation_task = $tk_id";
		$log_utilisation = mysql_query($sql_utilisation);
		$row_utilisation = mysql_fetch_array($log_utilisation);
		$total_uc = $row_utilisation["util"];

		if ($row_task["task_start_date"] == 0000-00-00){
			$SencDate = '-';	
		}else {
			$SencDate = date("d/m/Y",strtotime($row_task["task_start_date"]));				
		}

		if ($row_task["task_revise_finish_date"] == 0000-00-00){
			$TaskFinishDate = '-';	
		}else {
			$TaskFinishDate = date("d/m/Y",strtotime($row_task["task_revise_finish_date"]));				
		}

		if ($row_task["task_end_date"] == 0000-00-00){
			$TaskEndDate = '-';	
		}else {
			$TaskEndDate = date("d/m/Y",strtotime($row_task["task_end_date"]));				
		}



		
		$date1 = 0;
		$date2 = $row_task["task_end_date"];

		if (($ach_fin_date != null) and ($ach_fin_date != '0000-00-00')) {
			$date1 = $ach_fin_date;
		}
		else {
			$date1 = $today;
		}

	// The following string calculates number of months.

  		 $diff_date = floor((strtotime($date1, 0) - strtotime($date2, 0))/2592000);


        if (!$fullrelease && ((trim($delay_from) == Null) or (trim($delay_to) == Null)))
        {
			$n = $n+1;
			/*if($n > 313)
			{
			 break;
			}*/
                                                
                $css ='';  //for css style
                
                if ($n%2==0) {
               
                 $css = ' class=CellBorder bgcolor=#cee2f9 align=center ';
                    
               }

			$sector_total_app = ($sector_total_app + $row_task["task_target_budget"]);
			$grand_total_app = ($grand_total_app + $row_task["task_target_budget"]);
			$sector_total_project = ($sector_total_project + $row_task["task_no_of_nlcpr_project"]);
			$grand_total_project = ($grand_total_project + $row_task["task_no_of_nlcpr_project"]);

			$sector_total_rel = ($sector_total_rel + $total_rel);
			$grand_total_rel = ($grand_total_rel + $total_rel);

			$sector_total_uc = ($sector_total_uc + $total_uc);
			$grand_total_uc = ($grand_total_uc + $total_uc);

			$test = $row_task["task_id"];

			$task_target_budget=$row_task["task_target_budget"]/100;
			$total_rel=$total_rel/100;
			$total_uc=$total_uc/100;
		}



		$htmlCont .= '<tr>';
		$htmlCont .= '<td  align="left" bgcolor="'.$colorCode.'">'.$n.'.</td>
		<td  align="left" bgcolor="'.$colorCode.'">'.$row_task["task_name"].'</td>
		<td  align="left" bgcolor="'.$colorCode.'">'.$SencDate.'</td>
		<td  align="right" bgcolor="'.$colorCode.'">'.number_format($task_target_budget,2,'.','').'</td>
		<td  align="left" bgcolor="'.$colorCode.'">&nbsp;</td>
		<td  align="right" bgcolor="'.$colorCode.'">'.number_format($total_rel,2,'.','').'</td>
		<td  align="left" bgcolor="'.$colorCode.'">&nbsp;</td>
		<td  align="right" bgcolor="'.$colorCode.'">'.number_format($total_uc,2,'.','').'</td>
		<td  align="center" bgcolor="'.$colorCode.'">'.round(($total_uc/$total_rel)*100).'&nbsp;%</td>				
		<td  align="center" bgcolor="'.$colorCode.'">'.$TaskEndDate.'</td>				
		<td  align="center" bgcolor="'.$colorCode.'">'.$TaskFinishDate.'</td>';


		//if ($diff_date>=0) {$htmlCont .=  '<td  align="left" bgcolor="'.$colorCode.'"> '.$diff_date.'</td></tr>'; }  
		$htmlCont .='</tr>';


$htmlCont .= '<tr bgcolor="#E781FF">
  <td  align="left">&nbsp;</td>
  <td  align="left">&nbsp;</td>
  <td  align="left">&nbsp;</td>
  <td  align="left">&nbsp;</td>
  <td colspan="2" align="left"><table width="100%" border="0" cellspacing="1" cellpadding="1">';
$sql_rel = "select sanction_first_date, sanction_dept_amount, sanction_loan_amount from nlp_sanction where sanction_task = $tk_id order by sanction_first_date";
$log_rel = mysql_query($sql_rel);
while ($row_rel = mysql_fetch_array($log_rel))
{
//$rel_date = intval($row_rel["sanction_first_date"]) ? new CDate( $row_rel["sanction_first_date"]) : null;

	if ($row_rel["sanction_first_date"] == 0000-00-00){
			$SecFDate = '-';	
		}else {
			$SecFDate = date("d/m/Y",strtotime($row_rel["sanction_first_date"]));				
		}

	$section_amt=($row_rel["sanction_dept_amount"]+$row_rel["sanction_loan_amount"])/100;

$htmlCont .= '<tr><td align="left" width="65%">'.$SecFDate.'</td>
      <td align="right" width="35%">'.number_format($section_amt,2,'.','').'</td>
    </tr>';
	
}
$htmlCont .= ' </table></td>


  <td colspan="2" ><table width="100%" border="0" cellspacing="1" cellpadding="1">';
  $sql_uc = "select utilisation_date, utilisation_amount from nlp_utilisation where utilisation_task = $tk_id order by utilisation_date";
							$log_uc = mysql_query($sql_uc);
							while ($row_uc = mysql_fetch_array($log_uc))
								{

									if ($row_uc["utilisation_date"] == 0000-00-00){
			$UtiDate = '-';	
		}else {
			$UtiDate = date("d/m/Y",strtotime($row_uc["utilisation_date"]));				
		}

$uc_amt=$row_uc["utilisation_amount"]/100;
								//$uc_date = intval($row_uc["utilisation_date"]) ? new CDate( $row_uc["utilisation_date"]) : null;

$htmlCont .='<tr><td align="left" width="65%">'.$UtiDate.'</td>
<td align="right" width="35%">'.number_format($uc_amt,2,'.','').'</td></tr>';
								}

$htmlCont .='</table></td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>

</tr>';

}

$sector_total_app=$sector_total_app/100;
$sector_total_rel=$sector_total_rel/100;
$sector_total_uc=$sector_total_uc/100;

$htmlCont .='<tr bgcolor="#CC00CC">
<td  align="right" colspan="3" >Total of '.$row_project["project_name"].' sector ('.$sector_total_project.' Projects)</td>
<td  align="right" >'.number_format($sector_total_app,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="right">'.number_format($sector_total_rel,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="right">'.number_format($sector_total_uc,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>
</tr>
	<tr><td colspan="11">&nbsp;</td></tr>
';


	}


$grand_total_app=$grand_total_app/100;
$grand_total_rel=$grand_total_rel/100;
$grand_total_uc=$grand_total_uc/100;

$htmlCont .='<tr bgcolor="#CC00CC">
<td  align="right" colspan="3">GRAND TOTAL '.$grand_total_project.' Projects</td>
<td  align="right">'.number_format($grand_total_app,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="right">'.number_format($grand_total_rel,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="right">'.number_format($grand_total_uc,2,'.','').'</td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>
<td  align="left">&nbsp;</td>
</tr>';



// create some HTML content
	$sql_state = "select state_name from nlp_state where state_id='$statename'";
	$log_state = mysql_query($sql_state);
	$row_state = mysql_fetch_array($log_state);
	$state = $row_state[0];
	
$html = '<table border="0" cellspacing="1" cellpadding="1" width="100%">
  <tr  bgcolor="#CC00CC">
    <td colspan="11" bgcolor="#CC00CC" align="center" style="color:#ffffff">
	DETAILED STATUS OF PROJECTS SANCTIONED - '.$state.'</td>
  </tr>
  <tr>
    <td colspan="11" align="right">(Rs. in Crore)</td>
  </tr>

<tr bgcolor="#E781FF">
<td width="4%" rowspan="2" align="center">Sl. No.</td>
<td width="18%" rowspan="2" align="center">Project</td>
<td width="10%" rowspan="2" align="center">Sanction Date </td>
<td width="8%" rowspan="2" align="center">Approved Cost </td>
<td width="17%" colspan="2"  align="center">Release Break-up</td>
<td width="16%" colspan="2"  align="center">Utilisation Break-up</td>
<td width="8%" rowspan="2"  align="center">%age Utilisation </td>
<td width="10%" rowspan="2"  align="center">Schedule Date of<br/>Completion</td>
<td width="9%" rowspan="2"  align="center">Revised Date of<br />Completion</td>
</tr>
<tr bgcolor="#E781FF">
  <td  align="center">Date</td>
  <td  align="center">Amount</td>
  <td  align="center">Date</td>
  <td align="center">Amount</td>
</tr>'.$htmlCont.'</table>';

//echo $html;

$pdf = new MYPDFRELUC('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
//$pdf->setFontSubsetting(false);
$pdf->SetFont('dejavusans', '', 8);
// add a page
$pdf->AddPage();
$pdf->Ln(5);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
	
$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/rel_uc/';
$filename = $state.'.pdf';
ob_clean();
$pdf->Output($dir.$filename,'F');
}

ini_set('memory_limit',$original_mem);
ini_set('max_execution_time',60);

?>