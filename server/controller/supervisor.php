<?php

require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_REQUEST['choice'];

switch($choice){
	
	case 'surveyorCount':
	$role_id=$_REQUEST['role_id'];

	$query = "SELECT count(emp_id) AS numberCount FROM employee WHERE role_id=$role_id";
	$run_query = mysql_query($query);
	if(mysql_num_rows($run_query) > 0){
	$countrow = mysql_fetch_array($run_query);
	$emp_detail = "SELECT * FROM employee where role_id=$role_id";
	$run_emp_detail= mysql_query($emp_detail);
	$result =[];$i=0;
	 while($row = mysql_fetch_assoc($run_emp_detail)){
		$result[$i]= $row;
	 	$i++;
	 }	
	 echo json_encode(array('status'=>true,'success'=>$result,'numberCount'=>$countrow['numberCount']));
	}else{
		echo json_encode(array('status'=>false,'error'=>'Data Does Not exist'));
	}
	break;

	case 'updateSurveyorStatus':
	$emp_id = $_REQUEST['emp_id'];
	$status = $_REQUEST['status'];
	$query = "UPDATE employee SET status=$status WHERE emp_id=$emp_id";
	$run_query= mysql_query($query);
	if(mysql_affected_rows() > 0){
		 $emp_detail = "SELECT * FROM employee where emp_id = $emp_id";
		//$run_emp_detail=mysql_query($emp_detail);
		$data= mysql_fetch_array(mysql_query($emp_detail));
		echo json_encode(array('status'=>true,'msg'=>'Record updated Successfully','emp_id'=>$emp_id,'success'=>$data));
	}else{
		echo json_encode(array('status'=>false,'error'=>'not updated '));
	}

}



?>