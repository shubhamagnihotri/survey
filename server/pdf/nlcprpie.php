<?php

define('TTF_DIR','../pdf/jpgraph/ttf/');
require_once '../base.php';
require_once '../includes/config.php';
require_once ('../includes/main_functions.php');
require_once ('../includes/db_adodb.php');
require_once ('../includes/db_connect.php');
require_once ('../classes/query.class.php');

$q = new DBQuery();

include('../pdf/tcpdf.php');
include('../pdf/config/lang/eng.php');

include('../pdf/jpgraph/jpgraph.php');
include('../pdf/jpgraph/jpgraph_pie.php');
include('../pdf/jpgraph/jpgraph_pie3d.php');

include('../pdf/phpdoc/graph_summery.php');
include('../pdf/phpdoc/pie_summery.php');

?>