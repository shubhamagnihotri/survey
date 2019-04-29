<?php

define('TTF_DIR','/home/nlcpr/docs/pdf/jpgraph/ttf/');

require_once '/home/nlcpr/docs/base.php';
require_once '/home/nlcpr/docs/includes/config.php';
require_once ('/home/nlcpr/docs/includes/main_functions.php');
require_once ('/home/nlcpr/docs/includes/db_adodb.php');
require_once ('/home/nlcpr/docs/includes/db_connect.php');
require_once ('/home/nlcpr/docs/classes/query.class.php');

$q = new DBQuery();
include('/home/nlcpr/docs/pdf/tcpdf.php');
include('/home/nlcpr/docs/pdf/config/lang/eng.php');



include('/home/nlcpr/docs/pdf/jpgraph/jpgraph.php');
include('/home/nlcpr/docs/pdf/jpgraph/jpgraph_bar.php');

include('/home/nlcpr/docs/pdf/phpdoc/sector_wise_chart.php');
include('/home/nlcpr/docs/pdf/phpdoc/sectorwise.php');


?>
