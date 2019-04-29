<?php

define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');

require_once '../base.php';
require_once '../includes/config.php';
require_once ('../includes/main_functions.php');
require_once ('../includes/db_adodb.php');
require_once ('../includes/db_connect.php');
require_once ('../classes/query.class.php');

$q = new DBQuery();
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');



include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_bar.php');

include('/home/nlcpr/docs/pdf/phpdoc/sector_wise_chart.php');
include('/home/nlcpr/docs/pdf/phpdoc/sectorwise.php');


?>
