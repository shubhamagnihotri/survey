<?php 

/*
define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');
include('/home/nlcpr/docs/pdf/connection.php');
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');

include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_bar.php');
*/
$q = new DBQuery();
// Create the graph. These two calls are always required
$c_y=date("Y");
$graph = new Graph(600,450,'auto');
$graph->SetScale("textlin");
$graph->img->SetMargin(80,40,50,150);
$graph->SetMarginColor(array(255,255,255));
$graph->yaxis->scale->SetGrace(10);
$graph->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350,400,450));
$graph->SetBox(false);
$graph->ygrid->SetFill(false);

$graph->yaxis->title->SetMargin(20);
$graph->yaxis->title->Set("Number of Projects");
$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);

$graph->xaxis->title->SetMargin(100);
$graph->xaxis->title->Set("State");
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);


$graph->title->Set("State wise Sanctioned & Completed Projects");
$graph->subtitle->Set('(1998-'.$c_y.')');
$graph->title->SetFont(FF_ARIAL,FS_BOLD,14); 
$graph->subtitle->SetFont(FF_ARIAL,FS_BOLD,12); 
$graph->title->SetColor("black");

$q->addTable("tasks", "t");
$q->addTable("state", "s");
$q->addQuery("s.state_name, SUM(t.task_no_of_nlcpr_project) AS No_of_project ,(SELECT  SUM(task_no_of_nlcpr_project) AS No_of_project FROM nlp_tasks WHERE task_state=s.state_id AND task_project IN (SELECT project_id FROM nlp_projects) AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND (task_actual_finish_date IS NOT NULL AND task_actual_finish_date != '0000-00-00') AND task_nlcpr_project=1
) AS completed_projects");
$q->addWhere("t.task_project IN (SELECT project_id FROM nlp_projects)  AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND t.task_nlcpr_project=1 AND t.task_state=s.state_id ");
$q->addGroup("t.task_state");
$q->addOrder("s.state_name");
$log_project = $q->loadList();
$q->clear();

foreach($log_project as $row_project)
{
$data1y.=$row_project['No_of_project'].',';
$data2y.=$row_project['completed_projects'].',';
$datax.=$row_project['state_name'].',';
}

$data1y=array(rtrim($data1y,','));
$data2y=array(rtrim($data2y,','));
$datax=array(rtrim($datax,','));

foreach($data1y as $val)
{
    $data1y=explode(",",$val);

}

foreach($data2y as $val)
{
    $data2y=explode(",",$val);

}
   
foreach($datax as $val)
{
    $datax=explode(",",$val);

}

$data1y =$data1y;
$data2y =$data2y;
$datax =$datax;

$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(90);

$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xaxis->SetFont(FF_ARIAL,FS_BOLD,9);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
//$b3plot = new BarPlot($data3y);

// Create the bar pot

$b1plot->SetWidth(0.6);
$b1plot->SetLegend("Sanctioned Project");


$b2plot->SetWidth(0.8);
$b2plot->SetLegend("Completed Project");

$b2plot->value->SetFormat('%d');
$b2plot->value->Show();

$b1plot->value->SetFormat('%d');
$b1plot->value->Show();
// Must use TTF fonts if we want text at an arbitrary angle
$b1plot->value->SetFont(FF_ARIAL);
// Black color for positive values and darkred for negative values
$b1plot->value->SetColor("black","darkred");


$b2plot->value->SetFont(FF_ARIAL);
// Black color for positive values and darkred for negative values
$b2plot->value->SetColor("black","darkred");
// Create the grouped bar plot ,$b3plot
$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
// ...and add it to the graPH


$b1plot->SetFillColor("#99CC00");
$b2plot->SetFillColor("#0066CC");

$graph->Add($gbplot);

//$graph->Stroke();

$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
//$root = realpath($_SERVER["DOCUMENT_ROOT"]);

$fileName = '/home/nlcpr/docs/writereaddata/nlcprpdf/chart/state_wise_chart.png';

$graph->img->Stream($fileName);
 
// Send it back to browser

//$graph->img->Headers();
//$graph->img->Stream();


?>