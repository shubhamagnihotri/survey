<?php 

/*
define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');

include('/home/nlcpr/docs/pdf/connection.php');
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');

include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_bar.php');
*/

// Create the graph. These two calls are always required
$c_y=date("Y");
$graph2 = new Graph(700,500,'auto');
$graph2->SetScale("textlin");
$graph2->img->SetMargin(80,40,50,70);
$graph2->SetMarginColor(array(255,255,255));
$graph2->yaxis->scale->SetGrace(10);
$graph2->yaxis->SetTickPositions(array(0,500,1000,1500,2000,2500,3000,3500));

$graph2->SetBox(false);
$graph2->ygrid->SetFill(false);

$graph2->yaxis->title->SetMargin(30);
$graph2->yaxis->title->Set("Amount (Rs. in Crore)");
$graph2->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);

$graph2->xaxis->title->SetMargin(10);
$graph2->xaxis->title->Set("State");

$graph2->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);


$graph2->title->Set("TOTAL COST OF APPROVED PROJECTS, RELEASES & UCs RECEIVED");
$graph2->subtitle->Set('(1998-'.$c_y.')');
$graph2->title->SetFont(FF_ARIAL,FS_BOLD,14); 
$graph2->subtitle->SetFont(FF_ARIAL,FS_BOLD,14); 
$graph2->title->SetColor("black");


  $sql_project = "SELECT s.state_name ,SUM(task_no_of_nlcpr_project) AS No_of_project,SUM(task_target_budget) AS task_target_budget ,(SELECT  (SUM(sanction_dept_amount) + SUM( sanction_loan_amount))  FROM nlp_sanction, nlp_tasks, nlp_state WHERE sanction_state=state_id  AND sanction_task =task_id
AND sanction_state=task_state   AND task_nlcpr_project=1 AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND state_id= s.state_id ) AS rel,
(SELECT SUM(utilisation_amount) FROM nlp_utilisation, nlp_state WHERE utilisation_date <=REPLACE(CURDATE(),'-','/') AND utilisation_task IN (SELECT task_id FROM nlp_tasks WHERE utilisation_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND  task_state=state_id  AND task_nlcpr_project=1)   
AND state_id=s.state_id) AS uc FROM nlp_tasks AS t,nlp_state AS s WHERE task_state=s.state_id  AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND task_nlcpr_project=1  GROUP BY s.state_id order by s.state_name asc";

 $log_project = mysql_query($sql_project); 




while($row_project = mysql_fetch_array($log_project))
{
	
	if($row_project['state_name']=='Common to NER')
	{
		
	}
	else
	{
	
	//print_r($row_project['uc']/100);

		$data1y.=($row_project['task_target_budget']/100).',';
		$data2y.=($row_project['rel']/100).',';
		$data3y.=($row_project['uc']/100).',';
		$datax.=$row_project['state_name'].',';


	}
}


$data1y=array(rtrim($data1y,','));
$data2y=array(rtrim($data2y,','));
$data3y=array(rtrim($data3y,','));
$datax=array(rtrim($datax,','));


foreach($data1y as $val)
   {
     $data1y=explode(",",$val);

   }

foreach($data2y as $val)
   {
     $data2y=explode(",",$val);

   }

   foreach($data3y as $val)
   {
     $data3y=explode(",",$val);

   }
   
foreach($datax as $val)
   {
     $datax=explode(",",$val);

   }

$data1y =$data1y;
$data2y =$data2y;
$data3y =$data3y;
$datax =$datax;



$graph2->xaxis->SetFont(FF_ARIAL,FS_NORMAL,9);

$graph2->xaxis->SetTickLabels($datax);
//$graph2->xaxis->SetLabelAngle(90);

$graph2->yaxis->HideLine(false);
$graph2->yaxis->HideTicks(false,false);



// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
$b3plot = new BarPlot($data3y);

// Create the bar pot



$b1plot->SetWidth(0.6);
$b1plot->SetLegend("Total Approved Cost of Sanctioned Project");


$b2plot->SetWidth(0.8);
$b2plot->SetLegend("Release");

$b3plot->SetWidth(1.0);
$b3plot->SetLegend("UCs Received");


$b1plot->value->SetFormat('%.2f');
$b1plot->value->Show();
$b1plot->value->SetAngle(90);

$b2plot->value->SetFormat('%.2f');
$b2plot->value->Show();
$b2plot->value->SetAngle(90);

$b3plot->value->SetFormat('%.2f');
$b3plot->value->Show();
$b3plot->value->SetAngle(90);



// Must use TTF fonts if we want text at an arbitrary angle
$b1plot->value->SetFont(FF_ARIAL,FS_BOLD);
// Black color for positive values and darkred for negative values
$b1plot->value->SetColor("blue","darkred");
#800080

$b2plot->value->SetFont(FF_ARIAL,FS_BOLD);
// Black color for positive values and darkred for negative values
$b2plot->value->SetColor("purple","darkred");

$b3plot->value->SetFont(FF_ARIAL,FS_BOLD);
// Black color for positive values and darkred for negative values
$b3plot->value->SetColor("black","darkred");

// Create the grouped bar plot ,$b3plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot));
// ...and add it to the graPH


$b1plot->SetFillColor("#85DB30");
$b2plot->SetFillColor("#FFEF0F");
$b3plot->SetFillColor("#DE1F27");

$graph2->Add($gbplot);

//$graph2->Stroke();


$gdImgHandler = $graph2->Stroke(_IMG_HANDLER);

//$dir= $root."/writereaddata/nlcprpdf/chart/release_of_fund_chart.png";

$fileName = '/home/nlcpr/docs/writereaddata/nlcprpdf/chart/release_of_fund_chart.png';

$graph2->img->Stream($fileName);
 
// Send it back to browser
//$graph2->img->Headers();
//$graph2->img->Stream();

?>