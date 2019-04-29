<?php

ini_set('max_execution_time',180);

require_once '../base.php';
require_once '../includes/config.php';
require_once ('../includes/main_functions.php');
require_once ('../includes/db_adodb.php');
require_once ('../includes/db_connect.php');
require_once ('../classes/query.class.php');

$q = new DBQuery();
include('../pdf/tcpdf.php');
include('../pdf/config/lang/eng.php');

//include('../pdf/phpdoc/summery.php');
//include('../pdf/phpdoc/lateuc.php');
//include('../pdf/phpdoc/state.php');
//include('../pdf/phpdoc/state_sector.php');

include('phpdoc/summery.php');
include('phpdoc/lateuc.php');
include('phpdoc/state.php');
include('phpdoc/state_sector.php');

ini_set('max_execution_time',60);
?>
