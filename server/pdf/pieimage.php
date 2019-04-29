<?php
date_default_timezone_set('Asia/Kolkata');

require_once '/home/nlcpr/docs/base.php';
require_once '/home/nlcpr/docs/includes/config.php';
require_once ('/home/nlcpr/docs/includes/main_functions.php');
require_once ('/home/nlcpr/docs/includes/db_adodb.php');
require_once ('/home/nlcpr/docs/includes/db_connect.php');
require_once ('/home/nlcpr/docs/classes/query.class.php');

$q = new DBQuery();
include('/opt/lampp/htdocs/nlcpr/pdf/tcpdf.php');
include('/opt/lampp/htdocs/nlcpr/pdf/config/lang/eng.php');

include('/opt/lampp/htdocs/nlcpr/pdf/jpgraph/jpgraph.php');
include('/opt/lampp/htdocs/nlcpr/pdf/jpgraph/jpgraph_pie.php');
include('/opt/lampp/htdocs/nlcpr/pdf/jpgraph/jpgraph_pie3d.php');

include('/opt/lampp/htdocs/nlcpr/pdf/phpdoc/pie_summery.php');
include('/opt/lampp/htdocs/nlcpr/pdf/phpdoc/graph_summery.php');




?>