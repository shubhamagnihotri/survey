<?php

//ini_set('max_execution_time',360);

$root = realpath($_SERVER["DOCUMENT_ROOT"]);


//$pathvalue= "'".$root.'/doner/dotproject/pdf/phpdoc/graph_summery.php'."'";


define('TTF_DIR','/home/tempweb944/docs/doner/dotproject/pdf/jpgraph/ttf/');

include('/home/tempweb944/docs/doner/dotproject/pdf/connection.php');
include('/home/tempweb944/docs/doner/dotproject/pdf/tcpdf.php');
include('/home/tempweb944/docs/doner/dotproject/pdf/config/lang/eng.php');

include('/home/tempweb944/docs/doner/dotproject/pdf/jpgraph/jpgraph.php');
include('/home/tempweb944/docs/doner/dotproject/pdf/jpgraph/jpgraph_bar.php');
include('/home/tempweb944/docs/doner/dotproject/pdf/jpgraph/jpgraph_pie.php');
include('/home/tempweb944/docs/doner/dotproject/pdf/jpgraph/jpgraph_pie3d.php');


include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/graph_summery.php');

//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/sector_wise_chart.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/state_wise_chart.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/release_of_fund_chart.php');


//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/sectorwise.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/release_of_fund.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/statewise.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/pie_summery.php');


//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/summery.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/lateuc.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/state.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/state_sector.php');

//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/reluc.php');
//include('/home/tempweb944/docs/doner/dotproject/pdf/phpdoc/sector.php');



/*
include($root.'/doner/dotproject/pdf/phpdoc/statewise.php');
include('$root/doner/dotproject/pdf/jpgraph/jpgraph.php');
include('$root/doner/dotproject/pdf/jpgraph/jpgraph_bar.php');
include('$root/doner/dotproject/pdf/jpgraph/jpgraph_pie.php');
include('$root/doner/dotproject/pdf/jpgraph/jpgraph_pie3d.php');
include('$root/doner/dotproject/pdf/phpdoc/sector.php');
include('$root/doner/dotproject/pdf/phpdoc/state.php');
include('$root/doner/dotproject/pdf/phpdoc/lateuc.php');
include('$root/doner/dotproject/pdf/phpdoc/statewise.php');
include('$root/doner/dotproject/pdf/phpdoc/state_wise_chart.php');
*/
?>
