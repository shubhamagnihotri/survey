<?php
    
    date_default_timezone_set('Asia/Kolkata');
    
    $q = new DBQuery();
    
    //total of sub total 1
    $from = 1998; //1998
    $current_month=date("m");
    $r=date("Y") - 7;
    if($current_month<=3){
        $to=$r;
        $to1=$r;
    }else{
        $to=$r+1;
        $to1=$r+1;
    }
    $from = $to;
    $current_month=date("m");


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
        $htmlYear .= '<td align="center" style="font-size:22px;">'.substr($x1,-2).'-'.substr($x3,-2).'</td>';
        if(!$x1){ break; }
        $x1 = $x1 + 1;			
    }



    $n=0;

    $q->addTable("tasks");
    $q->addTable("state");
    $q->addQuery("distinct task_state, state_name");
    $q->addWhere("state_id=task_state");
    $q->addWhere("task_nlcpr_project=1");
    $q->addOrder("state_id = 82, state_name");
    $arr_state= $q->loadList();
    $q->clear();

    $n = 1;
    $htmlCont = "";
    $grandTotal = array();
    $appCost1 = 0;
    $rowProj = 0;
    $Tcolsp=$colsp+1;
    foreach($arr_state as $row_state)
    {

        $sta_id = $row_state['task_state'];
        $q->addTable("tasks");
        $q->addQuery("distinct task_id");
        $q->addWhere("task_state = '$sta_id'");
        $q->addWhere("task_nlcpr_project=1");
        $log_check = $q->loadList();
        $q->clear();

        $tasklist="";

        foreach($log_check as $row_check){
                $tasklist_id = $row_check['task_id'];
                $tasklist =  $tasklist_id. ",".$tasklist ;
                if(!$row_check){ break; }
        }

        $tasklist = $tasklist."0";
        $q->addTable("tasks");
        $q->addQuery("sum(task_target_budget) as task_target_budget, sum(task_no_of_nlcpr_project) as No_of_project");
        $q->addWhere("task_state = '$sta_id'");
        $q->addWhere("task_nlcpr_project=1");
        $row_task = $q->loadHash();
        $q->clear();


        $yearfrom = $fromyear;
        $yearto = $toyear;

        $year1 = array();
        $year2 = array();
        $sql_sanction = array();
        $log_sanction = array();
        $row_sanction = array();

        $i=0;
        $appCost = $row_task["task_target_budget"]/100;
        $appCost1 += $appCost;
        $rowProj += $row_task["No_of_project"];
        $headColor = "#ff7dff";
        if($n%2 == 0){$colorCode = '#FFC1FF';} else{$colorCode = '#FFFFFF';}

        //loop from 1998
        $ii=0;
        $colsp1=1;
        $yearlyTotal3 = 0;
        $yearfrom3 = '1998';
        $yearto3 = $to1-1;
        $yearGrandTotal3='0';
       
        $x11=$yearfrom3;
        $x21=$yearto3;
        while($x11 <= $x21)
        {
            $colsp1 = $colsp1 + 1;
            if(!$x11){ break; }
            $x11 = $x11 + 1;			
        }
        while($ii<$colsp1-1)
        {

            $year33[$ii] = str_pad($yearfrom3,10,"/04/01");
            $year44[$ii] = str_pad($yearfrom3+1,10,"/03/31");

            $q->addTable("sanction");
            $q->addQuery("sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1");
            $q->addWhere("sanction_state = '$sta_id'");
            $q->addWhere("sanction_first_date between '$year33[$ii]' and '$year44[$ii]' and sanction_task in ($tasklist)");
            $q->addOrder("sanction_first_date");
            $row_sanction11[$ii] = $q->loadHash();
            $q->clear();

            $yearlyTotal3 = ($row_sanction11[$ii]['rel']+$row_sanction11[$ii]['rel1'])/100;

            $yearGrandTotal3 += $yearlyTotal3;

            if($n == 1){
                    $grandTotal3[$ii] = $yearlyTotal3;
            }else{
                    $grandTotal3[$ii] = $grandTotal3[$ii]+$yearlyTotal3;
            }

            $yearfrom3 = $yearfrom3 + 1;
            $ii = $ii + 1;
        }


        $htmlCont .= '<tr>';
        $htmlCont .=  '<td align="left" bgcolor="'.$colorCode.'" style="font-size:22px;">'.$row_state["state_name"].'</td>';
        $htmlCont .=  '<td align="center" bgcolor="'.$colorCode.'" style="font-size:22px;">'.$row_task["No_of_project"].'</td>';
        $htmlCont .=  '<td align="center" bgcolor="'.$colorCode.'" style="font-size:22px;" >'.  number_format($appCost,2,'.','').'</td>'; 
        $htmlCont .= '<td align="center" bgcolor="'.$colorCode.'" style="font-size:22px;">'. number_format($yearGrandTotal3,2,'.','')."</td>";
        $state_total_rel=0;
        $htmlCont1 ='';
        $yearlyTotal = 0;
        $yearGrandTotal = 0;


        while($i < $colsp-1)
        {
            $year1[$i] = str_pad($yearfrom,10,"/04/01");
            $year2[$i] = str_pad($yearfrom+1,10,"/03/31");

            $q->addTable("sanction");
            $q->addQuery("sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1");
            $q->addWhere("sanction_state = '$sta_id'");
            $q->addWhere("sanction_first_date between '$year1[$i]' and '$year2[$i]' and sanction_task in ($tasklist)");
            $q->addOrder("sanction_first_date");
            $row_sanction[$i] = $q->loadHash();
            $q->clear();

            $yearlyTotal = ($row_sanction[$i]['rel']+$row_sanction[$i]['rel1'])/100;

            $yearGrandTotal += $yearlyTotal;

            if($n == 1){
                $grandTotal[$i] = $yearlyTotal;
            }else{
                $grandTotal[$i] = $grandTotal[$i]+$yearlyTotal;
            }

            $htmlCont1 .= '<td align="center" bgcolor="'.$colorCode.'" style="font-size:22px;">'. number_format(($yearlyTotal), 2, '.', '').'</td>';

            $yearfrom = $yearfrom + 1;
            $i = $i + 1;

            if ($i == $colsp-1){
                    //$htmlCont1 .= '<td align="right" bgcolor="'.$colorCode.'" style="font-size:22px;" >'. number_format($yearGrandTotal,2,'.','').'</td>'; 
            }

        } 


        $htmlCont .= $htmlCont1;

        $n += 1;
        if(!$row_state){ break; }


        $ii2=0;
        $colsp2=1;
        $yearfrom4 = '1998';
        if($current_month<=3){
            $yearto4=date("Y")-1;
        }else{
            $yearto4=date("Y");
        }

        $yearlyTotal1 = 0;
        $yearGrandTotal1 = 0;
        $x111=$yearfrom4;
        $x211=$yearto4;
        while($x111 <= $x211)
        {
            $colsp2 = $colsp2 + 1;
            if(!$x111){ break; }
            $x111 = $x111 + 1;			
        }
        while($ii2<$colsp2-1)
        {

            $year3[$ii2] = str_pad($yearfrom4,10,"/04/01");
            $year4[$ii2] = str_pad($yearfrom4+1,10,"/03/31");

            $q->addTable("sanction");
            $q->addQuery("sum(sanction_dept_amount) as rel, sum(sanction_loan_amount) as rel1");
            $q->addWhere("sanction_state = '$sta_id'");
            $q->addWhere("sanction_first_date between '$year3[$ii2]' and '$year4[$ii2]' and sanction_task in ($tasklist)");
            $q->addOrder("sanction_first_date");
            $row_sanction1[$ii2] = $q->loadHash();
            $q->clear();

            $yearlyTotal1 = ($row_sanction1[$ii2]['rel']+$row_sanction1[$ii2]['rel1'])/100;

            $yearGrandTotal1 += $yearlyTotal1;

            if($n == 1){
                    $grandTotal1[$ii2] = $yearlyTotal1;
            }else{
                    $grandTotal1[$ii2] = $grandTotal1[$ii2]+$yearlyTotal1;
            }

            $yearfrom4 = $yearfrom4 + 1;
            $ii2 = $ii2 + 1;
        }
        
        $htmlCont .='<td align="right" bgcolor="'.$colorCode.'" style="font-size:22px;">'. number_format($yearGrandTotal1,2,'.','').'</td>';

        $htmlCont .= "</tr>";
    }



    foreach($grandTotal3 as $val3){
        $html3 .= '<td align="center" style="font-size:22px;">'. number_format($val3,2,'.','')."</td>";
        $lastTotal3 += $val3;
    }

    $ys=date('Y');
    $n = 0;
    $html2 = '';
    $lastTotal1 = 0;
    $htmlCont .= '<tr bgcolor="'.$headColor.'">';
    $htmlCont .= '<td align="center" height="20"><table cellspacing="1" cellpadding="1" border="0"><tr><td style="font-size:23px;">GRAND TOTAL</td></tr></table></td>';
    $htmlCont .= '<td align="center" style="font-size:22px;">'. $rowProj."</td>";
    $htmlCont .= '<td align="center" style="font-size:22px;">'. number_format($appCost1,2,'.','')."</td>";
    $htmlCont .= '<td align="center" style="font-size:22px;">'. number_format($lastTotal3,2,'.','')."</td>";
    foreach($grandTotal as $val){
            $html2 .= '<td align="center" style="font-size:22px;">'. number_format($val,2,'.','')."</td>";
            $lastTotal1 += $val;
    }


    $htmlCont .= $html2;
    //$htmlCont .= '<td align="right" style="font-size:22px;">'. number_format($lastTotal1,2,'.','')."</td>";

    $finaltotal=$lastTotal3+$lastTotal1;

    $htmlCont .='<td align="right" style="font-size:22px;">'. number_format($finaltotal,2,'.','')."</td></tr>";

    $html1 = '<table border="0" cellspacing="2" cellpadding="1">
    <tr  bgcolor="#ff00ff">
        <td colspan="20" bgcolor="#ff00ff" align="center" style="font-size:38px;color:#ffffff;" >STATE WISE &amp; YEAR WISE SUMMARY OF RELEASE OF FUNDS</td>
    </tr>

    <tr  bgcolor="#ff00ff">
        <td colspan="20" bgcolor="#ff00ff" align="center" style="font-size:34px;color:#ffffff;">From (1998-'.$ys.')</td>
    </tr>

    <tr>
        <td colspan="20" align="right">(Rs. in Crore)</td>
    </tr>

    <tr bgcolor="'.$headColor.'">
        <td width="8%" rowspan="2" align="center">State</td>
        <td width="6%" rowspan="2" align="center" style="font-size:22px;">Projects approved as on date</td>
        <td width="8%" rowspan="2" align="center" style="font-size:22px;">Approved cost</td>
        <td width="80.6%" style="font-size:29px;" colspan="9"  align="center">Release</td>
    </tr>

    <tr bgcolor="#ff7dff"><td align="center" style="font-size:22px;">1998 - '.$to1.'</td> '.$htmlYear.'

        <td align="center" style="font-size:22px;">Total</td>
    </tr>'.$htmlCont.'



    </table>';
////<td align="center" style="font-size:22px;">Sub Total<br/><span align="center">&nbsp;&nbsp;&nbsp;&nbsp;(II)</span></td>
//echo $html1; 
    $final=$html1;
 //echo $final;die;

    class MYPDFSUM extends TCPDF {

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

            $this->Rect(0,280, 450, 15, 'F','',$fill_color = array(227,225,152));
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

    // create new PDF document
    $pdf = new MYPDFSUM(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



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
    $pdf->SetFont('dejavusans', '', 10);

    // add a page
    $pdf->AddPage();
    $pdf->Ln(5);

    //echo $final; die();

    // output the HTML content
    $pdf->writeHTML($final, true, true, true, true, '');

    $dir= '/home/nlcpr/docs/writereaddata/nlcprpdf/summery/';

    //$dir= '/opt/lampp/htdocs/nlcpr/writereaddata/nlcprpdf/summery/';



    $filename = 'all.pdf';

    $pdf->Output($dir.$filename,'I');

?>