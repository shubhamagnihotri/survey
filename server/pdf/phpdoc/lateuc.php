<?php

//define ('PDF_MARGIN_TOP', 19);

$dbprefix='nlp_';



//$state_name =array(74);

$state_name =array(11,12,13,14,15,16,17,18);

$year_from = '1998/04/01'; 
$year_to = date("Y/m/d"); 
$asdate = date("Y/m/d"); 

$from = substr($year_from, 8, 2)."/".substr($year_from, 5, 2)."/".substr($year_from, 0, 4);
$to = substr($year_to, 8, 2)."/".substr($year_to, 5, 2)."/".substr($year_to, 0, 4);
$asdatefordisplay = substr($asdate, 8, 2)."/".substr($asdate, 5, 2)."/".substr($asdate, 0, 4);


//$state_name =array(74);
$sector_html = '';
class MYPDFUTC extends TCPDF {

		public function Header() 
	{
		 
		


		$this->Rect(0,0,450,17,'F','',$fill_color = array(222,221,170));

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

foreach($state_name as $statename){

$n = 0;	
	$today = $asdate;
	$check = 1;


	
	$sql_project="select distinct(task_id), task_project, max(sanction_first_date) from ".$dbprefix."tasks, ".$dbprefix."sanction
	        where sanction_first_date <= '$today' and sanction_task=task_id and
	        (task_actual_finish_date = '0000-00-00' or task_actual_finish_date is null) and
	        task_start_date between '$year_from' and '$year_to' and task_state=$statename group by task_id order by task_project, task_id";

	 $log_project = mysql_query( $sql_project );
	
	$sql_state = "select state_name from ".$dbprefix."state where state_id=$statename";
	$log_state = mysql_query($sql_state);
	$row_state = mysql_fetch_array($log_state);
		$state = $row_state[0];

	
	$sector_html = '<table border="0" cellspacing="1" cellpadding="1" width="100%">
	<tr bgcolor="#7F8000"><td style="color:#ffffff" colspan="11" align="center">
	PROJECTS WITH OVERDUE UTILISATION - '.strtoupper($state).'</td></tr>
	<tr><td colspan="11" align="right">(Rs. in Crore)</td></tr>
	<tr bgcolor="#D1CC00">
       <td rowspan="2" width="4%" align="center">Sl. No.</td>
       <td rowspan="2" width="18%" align="center">Project</td>
       <td rowspan="2" width="10%" align="center">Sanction Date</td>
       <td rowspan="2" width="8%" align="center">Approved cost</td>
       <td colspan="2" width="18%" align="center">Release Break-up</td>
       <td colspan="2" width="18%" align="center">Utilisation Break-up</td>
       <td rowspan="2" width="8%" align="center">%age Utilisation</td>
       <td rowspan="2" width="7%" align="center">UC awaited (Month)</td>
	   <td rowspan="2" width="7%" align="center">Unspent Balance</td>
  </tr>
  <tr bgcolor="#D1CC00">
  	<td width="10%" align="center">Date</td>
	<td width="9%" align="center">Amount</td>
	<td width="10%" align="center">Date</td>
	<td width="9%" align="center">Amount</td>
  </tr>';

	$state_inner = '';
	
		
 	    $grand_total_rel=0.00;
	    $grand_total_uc=0.00;
		$grand_total_app=0.00;
		$proj_id_prev ='000';
		$proj_id_prev1 ='000';
		$show_total_rel =0.00;
		$show_total_uc=0.00;
		$sector_total_project=0;
		$check_flag = 0;
		
   while($row_project = mysql_fetch_array($log_project)){
	    $state_inner_loop='';

		$task_id = $row_project[0];
		$proj_id = $row_project[1];
		$max_date = $row_project[2];

		$flag="";
		
		$total_rel = 0;
		$total_uc = 0;

		$sql_sanction = "select sanction_dept_amount as rel,sanction_loan_amount as rel1 from ".$dbprefix."sanction where sanction_first_date <= '$today' and sanction_task = $task_id";
		$log_sanction = mysql_query($sql_sanction);

		while ($row_sanction = mysql_fetch_array($log_sanction)) {
			$total_rel = $total_rel + ($row_sanction[0]+$row_sanction[1]);
		}

		$sql_utilisation = "select utilisation_amount as util from ".$dbprefix."utilisation where utilisation_date <= '$today' and utilisation_task = $task_id and utilisation_status = 1"; 
	
		$log_utilisation = mysql_query($sql_utilisation);
	
		while ($row_utilisation = mysql_fetch_array($log_utilisation)) {
			$total_uc = $total_uc + $row_utilisation["util"];
		}
		
		if ($row_task["task_start_date"] == 0000-00-00){
			$sanc_date = '-';	
		}else {
			$sanc_date = date("d/m/Y",strtotime($row_task["task_start_date"]));				
		}
		
		if ($row_task["task_actual_finish_date"] == 0000-00-00){
			$finish_date = '-';	
		}else {
			$finish_date = date("d/m/Y",strtotime($row_task["task_actual_finish_date"]));				
		}


		$sql_lastrel = "select sanction_dept_amount, sanction_loan_amount from ".$dbprefix."sanction where sanction_task= $task_id and sanction_first_date='$max_date'";
		$log_lastrel = mysql_query( $sql_lastrel);
		$row_lastrel = mysql_fetch_array($log_lastrel);
	
		$lastrel = $row_lastrel[0] + $row_lastrel[1];
	
		$diff_amount = $total_rel - $total_uc;
	
			// The following string provides number of month.
		$diff_date = floor((strtotime($today, 0) - strtotime($max_date, 0))/2628000);
	
			// To check total percentage utilisation
		if ($total_rel == 0 ) { $total_rel = 1;}
		
		$util =  round(($total_uc/$total_rel)*100);
		if (($total_rel != $total_uc) && ($diff_amount >= (0.25 * $lastrel)) && ($diff_date >= 12)) {
			 $sql_task =  "select task_name,task_start_date, task_target_budget,task_actual_finish_date, task_no_of_nlcpr_project, task_id from ".$dbprefix."tasks where task_id=$task_id and task_start_date between '$year_from' and '$year_to' and task_state=$statename and task_nlcpr_project=1";
			$log_task = mysql_query( $sql_task);
			$row_task = mysql_fetch_array($log_task);	
				
			
		
			if ($row_task == null) {$flag="n"; }
			else {$flag="y"; $proj_id_prev1 = $proj_id;  }
		}
		else { $flag="n"; }

		if ($flag=="y") {
			$n =$n+ 1;
            $css ='';                
            if (($n%2)==0) {$css =' bgcolor="#FEF900"';}
			$sector_total_project = $sector_total_project + $row_task["task_no_of_nlcpr_project"];

			if ($proj_id_prev != $proj_id_prev1) {

				if ($check_flag >0) {
					$secToPrj = $sector_total_project-$row_task["task_no_of_nlcpr_project"];
					$state_inner .='				
					<tr bgcolor="#D1CC00">
						<td colspan="3" align="right">Total of '.$old_project .' (' .$secToPrj.' Projects) </td>
						<td  colspan="3" align="right">'. number_format($show_total_rel,2, '.', '') .'</td>
						<td  colspan="2" align="right">' .number_format($show_total_uc,2, '.', '') .'</td>
						<td colspan="3">&nbsp;</td>
					</tr>
					
				<tr><td colspan="11">&nbsp;</td></tr>';

				$show_total_uc=0.00;
				$show_total_rel=0.00;
				}
				
				$sql_projname = "select project_name from ".$dbprefix."projects where project_id=$proj_id";
				$log_projname = mysql_query( $sql_projname);
				$row_projname = mysql_fetch_array($log_projname);
				$old_project = $row_projname["project_name"];
				$sector_total_project = $row_task["task_no_of_nlcpr_project"];  //initilisation for every new Sector
				$check_flag = $check_flag+1;
				$state_inner .='<tr>
				<td colspan="11" style="font-size:12"><strong>'. $row_projname["project_name"] .'</strong></td>
				</tr>';
			}
			$task_target_budget = $row_task["task_target_budget"]/100;
			$total_rel_new = $total_rel/100;
			$total_uc_new = $total_uc/100;
			$grand_rel_uc = ($total_rel-$total_uc)/100;
			$state_inner_loop .= '<tr '.$css.'>
			<td align="left">'.$n.'</td>

			<td align="left" >'. $row_task["task_name"] .'</td>

			<td align="center">'. $sanc_date .' </td>

			<td align="right">'. number_format($task_target_budget,2,'.','') .'</td>

			<td colspan="2" align="right">'. number_format($total_rel_new,2,'.','') .'</td>

			<td colspan="2" align="right">'. number_format($total_uc_new,2,'.','') .'</td>

			<td align="right"> '. $util .' %</td>

			<td align="center">  '. $diff_date .' </td>
			<td align="right"> '. number_format($grand_rel_uc,2,'.','') .'</td>
        	</tr>
			
			<tr '.$css.'>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td colspan="2">
				<table>';
				$state_inner_loop1 ='';
					$sql_rel = "select sanction_first_date, sanction_dept_amount, sanction_loan_amount from ".$dbprefix."sanction where sanction_first_date <= '$today' and sanction_task = $task_id order by sanction_first_date desc";
					$log_rel = mysql_query($sql_rel);
					while ($row_rel = mysql_fetch_array($log_rel)) {
						if ($row_rel["sanction_first_date"] == 0000-00-00){
							$rel_date = '-';	
						}else {
							$rel_date = date("d/m/Y",strtotime($row_rel["sanction_first_date"]));				
						}
						  $show_total_rel += ($row_rel["sanction_dept_amount"]/100)+($row_rel["sanction_loan_amount"]/100);

						$dpt_loan = ($row_rel["sanction_dept_amount"] + $row_rel["sanction_loan_amount"])/100;
						$state_inner_loop1 .='<tr '.$css.'>
							<td align="left">'.$rel_date.'</td>
							<td align="right">'. number_format($dpt_loan,2,'.','') .'</td>

						</tr>';
					}
				$state_inner_loop1 .='</table>';
				$state_inner_loop .= $state_inner_loop1;
				$state_inner_loop1 = '';
			$state_inner_loop .= '</td>
			<td colspan="2" >
				<table>';
					$sql_uc = "select utilisation_date, utilisation_amount from ".$dbprefix."utilisation where utilisation_date <='$today' and utilisation_task = $task_id and utilisation_status = 1 order by utilisation_date desc";
					$log_uc = mysql_query($sql_uc);
					while ($row_uc = mysql_fetch_array($log_uc)) {
						if ($row_uc["utilisation_date"] == 0000-00-00){
							$uc_date = '-';	
						}else {
							$uc_date = date("d/m/Y",strtotime($row_uc["utilisation_date"]));				
						}
						$uc_amt = $row_uc["utilisation_amount"]/100;
						$show_total_uc +=$uc_amt;
						$state_inner_loop1 .='<tr '.$css.'>
							<td align="left">'.$uc_date.'</td>
							<td align="right">'. number_format($uc_amt,2,'.','') .'</td>
						</tr>';
					}
				$state_inner_loop1 .= '</table>';
			$state_inner_loop .=$state_inner_loop1;
			$state_inner_loop .= '</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
			<td align="center">&nbsp;</td>
		</tr>';
		
		$state_inner .= $state_inner_loop;
		} // End if Condition for flag id
		
		if ($proj_id_prev1 != $proj_id_prev) {
		$proj_id_prev = $proj_id;
		}
		
   }
   $sector_html .=$state_inner; 
			$sector_html .='<tr bgcolor="#D1CC00">
			<td colspan="3" align="right">Total of '.$old_project .' (' .$sector_total_project.' Projects) </td>
			<td colspan="8"></td>
		</tr>
		<tr><td colspan="11">&nbsp;</td></tr>
		<tr><td colspan="11">&nbsp;</td></tr>
		<tr bgcolor="#D1CC00"><td colspan="11" align="left"><u>Note: </u> The number of months is the difference of "Date of last release" and UC awaited as on '.$asdatefordisplay .'.  </td> </tr>';
	$sector_html .='</table>';





$pdf = new MYPDFUTC('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


//$pdf->SetMargins(3, PDF_MARGIN_TOP, 3);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '',8);

// add a page
$pdf->AddPage();
$pdf->Ln(5);


$pdf->writeHTML($sector_html, true, false, true, false, '');

$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/ucawaited/';

$filename = $dir.$state.'.pdf';

$pdf->Output($filename,'F');

//$pdf->Output($filename, 'I');
}


?>