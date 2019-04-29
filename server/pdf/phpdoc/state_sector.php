<?php

$dbprefix = ' nlp_';
$q = new DBQuery();
// create new PDF document

class MYPDFSTATESECTOR extends TCPDF {

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
    // Position at 12 mm from bottom

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

$pdf = new MYPDFSTATESECTOR('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


//$pdf->SetMargins(3, PDF_MARGIN_TOP, 3);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetMargins(1, PDF_MARGIN_TOP, 1, true);

$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '',9);

// add a page
$pdf->AddPage();
$pdf->Ln(5);
$year_from = date("Y/m/d",strtotime("1998/04/01"));
$year_to = date("Y/m/d");
$from = substr($year_from, 8, 2)."/".substr($year_from, 5, 2)."/".substr($year_from, 0, 4);
$to = substr($year_to, 8, 2)."/".substr($year_to, 5, 2)."/".substr($year_to, 0, 4);
$q->addTable("tasks");
$q->addTable("state");
$q->addQuery("distinct task_state, state_name");
$q->addWhere("state_id=task_state");
$q->addWhere("task_nlcpr_project=1");
$q->addOrder("state_id = 82, state_name");
$log_state= $q->loadList();
$q->clear();
	
$sector_html='';
$sector_html.='<table border="0" cellspacing="1" cellpadding="1" width="100%">';
$x1 = $year_from;
$x2 = $year_to;
$x3 = $x1;

$colsp = 0;
$proj_id = array();
$proj_name = array();

$q->addTable("projects");
$q->addQuery("project_id, project_name");
$q->addOrder("project_name");
$log_proj= $q->loadList();
$q->clear();
	
foreach($log_proj as $row_proj) {
    $colsp= $colsp + 1;
    $proj_id[$colsp] = $row_proj['project_id'];
    $proj_name[$colsp] = $row_proj['project_name'];

    if(!$row_proj){ break; }
}
    $colsp4 = $colsp+4;
    $colsp1 = $colsp+1;
    $sector_html.='<tr bgcolor="#389635"> <td style="color:#ffffff;" colspan="'.$colsp4.'" align="center">SECTOR WISE DISTRIBUTION OF SANCTIONED PROJECTS & APPROVED COST</td> </tr>
    <tr> <td colspan="'.$colsp4.'" align="right">(Rs. in Crore)</td> </tr>
    <tr cellspacing="3" bgcolor="#9BDE9B">
    <td rowspan="2" align="center" width="4%">Sl. No.</td>
    <td rowspan="2" align="center" width="10%">State</td>
    <td rowspan="2" align="center" width="8%">No. of Sanctioned Projects & Approved Cost</td>
    <td colspan="'.$colsp1.'" align="center" width="78%">Economic & Social Sectors</td> </tr><tr bgcolor="#9BDE9B">';

    $x1 = $year_from;
    $x2 = $year_to;
    $x3 = $x1;

    $m=0;
    $sector_heading ='';

    while($m < $colsp) {
        $m = $m + 1;
        $sector_heading .= '<td align="center" width="7%">'. $proj_name[$m].'</td>';
    }
    $sector_html .= $sector_heading.'<td align="center" width="8%">Total</td> </tr>';

    $n=0;
    $m=0;
    $grand_project_total= array();
    $grand_project_cost = array();


    $sector_main= '';
    foreach($log_state as $row_state) {
        $sta_id = $row_state['task_state'];
        $n = $n+1;
        $css ='';                

        if ($n%2==0) { $css = 'bgcolor="#CAEECA"'; }
        else {$css ='';}

        $sector_total_app = ($sector_total_app + $row_task["task_target_budget"]);
        $grand_total_app = ($grand_total_app + $row_task["task_target_budget"]);

        $sector_inner1='';
        $sector_inner1 .='<tr '.$css.'>
        <td rowspan="2" align="left">'.$n.'</td>
        <td rowspan="2" align="left" >'.$row_state["state_name"].'</td>';

        $state_total_rel=0;
        $total_project = array();
        $cost_project = array();

        $i=0;

        while($i < $colsp)	{
            $i = $i + 1;

            $q->addTable("tasks");
            $q->addQuery("sum(task_target_budget) as task_target_budget, sum(task_no_of_nlcpr_project)  as No_of_project");
            $q->addWhere("task_state=$sta_id and task_project = $proj_id[$i] and task_start_date between '$year_from' and '$year_to' and task_nlcpr_project=1");
            $row_task = $q->loadHash();
            $q->clear();

            $total_project[$i] = $row_task['No_of_project'];
            $cost_project[$i] = $row_task['task_target_budget'];

            if(!$i){ break; }
        }

        $k=0;
        $tot_proj = 0;
        $tot_proj1 = 0;
        $cost_proj = 0;
        $project_total =0;
        $project_cost=0;


        $sector_inner1 .= '<td align="right">Number</td>';
	
        while($tot_proj < $colsp) {
            $tot_proj = $tot_proj + 1;
            $sector_inner1 .= '<td align="right">'.$total_project[$tot_proj].'</td>';
            $project_total = ($project_total + $total_project[$tot_proj]);
            $grand_project_total[$tot_proj] = ($grand_project_total[$tot_proj] + $total_project[$tot_proj]);

            if ($tot_proj == $colsp) {
                $sector_inner1 .= '<td align="right">'.$project_total.'</td>';
            }

            if(!$tot_proj){ break; } 
        }
			
        $sector_inner2 = '';
        $sector_inner2 .='</tr><tr '.$css.'><td align="right">Cost</td>';

        while($tot_proj1 < $colsp)	{
            $tot_proj1 = $tot_proj1 + 1;
            $cost_prj = $cost_project[$tot_proj1]/100;
            $sector_inner2 .= '<td align="right">'.number_format(($cost_prj),2,'.','').'</td>';
            $project_cost = ($project_cost + $cost_prj);
            $grand_project_cost[$tot_proj1] = ($grand_project_cost[$tot_proj1] + $cost_prj);

            if ($tot_proj1 == $colsp) {
            $sector_inner2 .= '<td align="right">'.number_format(($project_cost),2,'.','').'</td>';
            }
            if(!$tot_proj1){ break; }
        }

        $sector_inner2 .= '</tr>';
        $sector_main .= $sector_inner1.$sector_inner2;
        if(!$row_state){ break; }
        }
	$sector_html .= $sector_main;
		
		// Body Part
		
		
        $sector_html .='<tr bgcolor="#9BDE9B">
        <td colspan="2" rowspan="2" align="right">GRAND TOTAL</td>
        <td align="right">Number</td>';

        $k = 0;

        while($k < $colsp) {
            $k = $k + 1;
            $sector_html .= '<td align="right">'.$grand_project_total[$k].'</td>';
            $total = ($total + $grand_project_total[$k]);

            if ($k == $colsp) {
                    $sector_html .= '<td align="right">'.$total.'</td>';
            }
            if(!$k){ break; }
        }
	$sector_html .= '</tr>
	
	<tr bgcolor="#9BDE9B">
            <td align="right">Cost</td>';
	

        $k1 = 0;
        while($k1 < $colsp)	{
            $k1 = $k1 + 1;
            $temp_cost = $grand_project_cost[$k1];
            $sector_html .= '<td align="right">'.number_format(($temp_cost),2,'.','').'</td>';

            $cost = ($cost + $grand_project_cost[$k1]);

            if ($k1 == $colsp) {

                    $sector_html .= '<td align="right">'.number_format(($cost),2,'.','').' </td>';
            }
            if(!$k1){ break; }
        }

	$sector_html .= '</tr>';
	
	
	$sector_html .='<tr bgcolor="#9BDE9B">
        <td colspan="2" rowspan="2" align="right">%age Distribution</td>
        <td align="right">Number</td>';

        $k = 0;
        while($k < $colsp) {
            $k = $k + 1;
            $ntotal = ($grand_project_total[$k]*100)/$total;
            $sector_html .= '<td align="left">'.number_format(($ntotal),2,'.','').'%</td>';
            if ($k == $colsp) {
                    $sector_html .= '<td align="left"></td>';
            }
            if(!$k){ break; }
        }
	$sector_html .= '</tr>
	
	<tr bgcolor="#9BDE9B">
            <td align="right">Cost</td>';
	

        $k1 = 0;
        while($k1 < $colsp)	{
            $k1 = $k1 + 1;
            $ncost = ($grand_project_cost[$k1]*100)/$cost;
            $sector_html .= '<td align="left">'.number_format(($ncost),2,'.','').'%</td>';

            if ($k1 == $colsp) {

                    $sector_html .= '<td align="left"> </td>';
            }
            if(!$k1){ break; }
        }

	
	$sector_html .= '</tr></table>';
echo $sector_html;die;
$pdf->writeHTML($sector_html, true, false, true, false, '');

$dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/state_sector/';

$filename = $dir.'all.pdf';

$pdf->Output($filename,'F');



//$pdf->Output($filename, 'I');

?>