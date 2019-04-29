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
include('/opt/lampp/htdocs/nlcpr/pdf/jpgraph/jpgraph_bar.php');


include('/opt/lampp/htdocs/nlcpr/pdf/phpdoc/sectorwise.php');
include('/opt/lampp/htdocs/nlcpr/pdf/phpdoc/sector_wise_chart.php');


?>