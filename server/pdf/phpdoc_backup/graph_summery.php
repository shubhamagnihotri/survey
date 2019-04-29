<?php 
/*date_default_timezone_set('Asia/Kolkata');
$link = mysql_connect('localhost', 'root', '');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db('nlcpr'); 

require_once('../jpgraph/jpgraph.php');
require_once('../jpgraph/jpgraph_pie.php');
require_once('../jpgraph/jpgraph_pie3d.php');

define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');

include('/home/nlcpr/docs/pdf/connection.php');
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');

include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_pie.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_pie3d.php');
*/



// Create the Pie Graph.
$graph3 = new PieGraph(725,545);
//$graph3->SetShadow();
$c_y=date("Y");

// Set A title for the plot
$graph3->title->Set("Sector wise Sanctioned Projects");
$graph3->subtitle->Set('(1998-'.$c_y.')');
$graph3->title->SetFont(FF_ARIAL,FS_BOLD,14);
$graph3->subtitle->SetFont(FF_ARIAL,FS_BOLD,14);
$graph3->title->SetColor("black");
$graph3->legend->Pos(0.05,0.1);
//$graph3->legend->SetFont(FF_VERDANA,FS_NORMAL,9);



$sql_project = "SELECT p.project_name, SUM(t.task_no_of_nlcpr_project) AS No_of_project ,(SELECT  SUM(task_no_of_nlcpr_project) AS No_of_project FROM nlp_tasks WHERE task_state IN (SELECT state_id FROM nlp_state) AND task_project = p.project_id AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND (task_actual_finish_date IS NOT NULL AND task_actual_finish_date != '0000-00-00') AND task_nlcpr_project=1
) AS completed  FROM nlp_tasks AS t , nlp_projects AS p WHERE  t.task_state IN (SELECT state_id FROM nlp_state)  AND task_start_date BETWEEN '1998/04/01' AND REPLACE(CURDATE(),'-','/') AND t.task_nlcpr_project=1 AND t.task_project=p.project_id  GROUP BY t.task_project ORDER BY p.project_name";
$log_project = mysql_query($sql_project);

while($row_project = mysql_fetch_array($log_project))
{
		
		$data1y.=$row_project['No_of_project'].',';
		$datax.=$row_project['project_name'].',';
	
}

$data1y=array(rtrim($data1y,','));
$datax=array(rtrim($datax,','));

foreach($data1y as $val)
   {
     $data1y=explode(",",$val);

   }
  
foreach($datax as $val)
   {
     $datax=explode(",",$val);

   }

$data1y =$data1y;
$datax =$datax;

// Create 3D pie plot
$p1 = new PiePlot3d($data1y);
$p1->SetTheme("water");
$p1->SetCenter(0.33);
$p1->SetSize(0.33);
//$p1->SetSize(0.29);
$p1->SetHeight(1);

// Adjust projection angle
$p1->SetAngle(70);

// You can explode several slices by specifying the explode
// distance for some slices in an array

//$p1->Explode(array(50,20,20,20,20,20,10,40,40,50));
$p1->Explode(array(20,5,5,5,5,5,10,20,20,20));

// As a shortcut you can easily explode one numbered slice with
// $p1->ExplodeSlice(3);

$p1->value->SetFont(FF_ARIAL,FS_BOLD,12);
$p1->value->SetFormat('%.2f%%');
$p1->SetLegends($datax);

$graph3->Add($p1);
$gdImgHandler = $graph3->Stroke(_IMG_HANDLER);
 
// Stroke image to a file and browser
 
// Default is PNG so use ".png" as suffix

//$fileName = $root."/writereaddata/nlcprpdf/chart/piechart.png";

$fileName = '/home/nlcpr/docs/writereaddata/nlcprpdf/chart/piechart.png';

$graph3->img->Stream($fileName);
 
// Send it back to browser

//$graph3->img->Headers();
//$graph3->img->Stream();

?>


