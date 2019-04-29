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
$graph1 = new Graph(700,500,'auto');
$graph1->SetScale("textlin");
$graph1->img->SetMargin(80,40,50,175);
$graph1->SetMarginColor(array(255,255,255));
$graph1->yaxis->scale->SetGrace(10);
$graph1->yaxis->SetTickPositions(array(0,50,100,150,200,250,300,350,400,450));
$graph1->SetBox(false);
$graph1->ygrid->SetFill(false);

$graph1->yaxis->title->SetMargin(20);
$graph1->yaxis->title->Set("Number of Projects");
$graph1->yaxis->title->SetFont(FF_ARIAL,FS_BOLD);
//$graph1->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,10);
$graph1->xaxis->title->SetMargin(100);
$graph1->xaxis->title->Set("Sector");
$graph1->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);
//$graph1->xaxis->title->SetFont(FF_VERDANA,FS_BOLD,10);


$graph1->title->Set("Sector wise Sanctioned & Completed Projects");
$graph1->subtitle->Set('(1998-'.$c_y.')');
$graph1->title->SetFont(FF_ARIAL,FS_BOLD,14); 
$graph1->subtitle->SetFont(FF_ARIAL,FS_BOLD,12); 
//$graph1->title->SetFont(FF_VERDANA,FS_BOLD,14); 
//$graph1->subtitle->SetFont(FF_VERDANA,FS_BOLD,12);
$graph1->title->SetColor("black");


$sql_project = "SELECT p.project_name, SUM(t.task_no_of_nlcpr_project) AS No_of_project ,(SELECT  SUM(task_no_of_nlcpr_project) AS No_of_project FROM nlp_tasks WHERE task_state IN (SELECT state_id FROM nlp_state) AND task_project = p.project_id AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND (task_actual_finish_date IS NOT NULL AND task_actual_finish_date != '0000-00-00') AND task_nlcpr_project=1
) AS completed  FROM nlp_tasks AS t , nlp_projects AS p WHERE  t.task_state IN (SELECT state_id FROM nlp_state)  AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND t.task_nlcpr_project=1 AND t.task_project=p.project_id  GROUP BY t.task_project ORDER BY p.project_name";
$log_project = mysql_query($sql_project);

while($row_project = mysql_fetch_array($log_project))
{
$data1y.=$row_project['No_of_project'].',';
$data2y.=$row_project['completed'].',';
$datax.=$row_project['project_name'].',';
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




$graph1->xaxis->SetTickLabels($datax);
$graph1->xaxis->SetLabelAngle(90);

$graph1->yaxis->HideLine(false);
$graph1->yaxis->HideTicks(false,false);

$graph1->xaxis->SetFont(FF_ARIAL,FS_NORMAL,9);

// Create the bar plots
$b1plot = new BarPlot($data1y);
$b2plot = new BarPlot($data2y);
//$b3plot = new BarPlot($data3y);

// Create the bar pot
//$graph1->legend->SetFont(FF_VERDANA,FS_NORMAL,9);
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
$b2plot->SetFillColor("#CC99FF");

$graph1->Add($gbplot);

//$graph1->Stroke();

$gdImgHandler = $graph1->Stroke(_IMG_HANDLER);


//$fileName = $root."/writereaddata/nlcprpdf/chart/sector_wise_chart.png";
$fileName = '/home/nlcpr/docs/writereaddata/nlcprpdf/chart/sector_wise_chart.png';

$graph1->img->Stream($fileName);
 
// Send it back to browser
//$graph1->img->Headers();
//$graph1->img->Stream();
?>