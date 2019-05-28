<?php

require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_REQUEST['choice'];

switch($choice){
	
	case 'surveyorCount':
	$supervisor_id= $_REQUEST['supervisor_id'];
	$role_id=4;
	
	$investigatorquery = "SELECT full_name,email,status,emp_id,phone FROM employee WHERE supervisor_id=$supervisor_id AND role_id=$role_id";
		
		$runinvestigatorquery=mysql_query($investigatorquery);
	if(mysql_num_rows($runinvestigatorquery) > 0 ){
			$result=[];$i=0;

			while($row = mysql_fetch_assoc($runinvestigatorquery)){
				$result[$i]= $row;
			}	
		echo json_encode(array('status'=>true,'success'=>$result));
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
	break;



	case 'dashboard':
	$result = [];
	$supervisor_id= $_REQUEST['supervisor_id'];

	 $totalinvestigator = "SELECT count(emp_id) AS totalinvestigator from  employee where role_id='4' AND supervisor_id = '$supervisor_id'";
	$result['totalinvestigator'] =  mysql_fetch_array(mysql_query($totalinvestigator))['totalinvestigator'];
	$totalsurveyQuery = "SELECT count(id) AS total_survey from survey_data sd JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id'";

	 $result['restotal_survey'] =  mysql_fetch_array(mysql_query($totalsurveyQuery))['total_survey'];

	$coverdistictQuery = "SELECT count(DISTINCT(`district`)) AS cover_distict FROM survey_data sd JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id'";
	$result['cover_distict'] =  mysql_fetch_array(mysql_query($coverdistictQuery))['cover_distict'];
	
	
	$pendingsurveyQuery = "SELECT count(survey_status) as pending_survey  from survey_data sd JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id' AND survey_status=1 ";
	$result['pending_survey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey  from survey_data sd JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id' and survey_status=2 ";
	$result['approve_survey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey  from survey_data sd JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id' and survey_status=3 ";
	$result['rejected_survey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];

	echo json_encode($result);
	
	break;


	case 'getCoveredDistrict':

		$supervisor_id= $_REQUEST['supervisor_id'];
		
		 $coverdist_surveycount = "SELECT city.name,`district`,count(*) AS sum FROM survey_data sd INNER JOIN city on sd.district=city.id JOIN employee e on sd.user_id = e.emp_id Where e.supervisor_id = '$supervisor_id' GROUP BY district";

		$rescoverdist_surveycount=mysql_query($coverdist_surveycount);
		while($row=mysql_fetch_assoc($rescoverdist_surveycount)){

					$result['total_distict_wise'][] = $row;
		}
		echo json_encode($result);
	
	break;




	case 'addEmployeeData':



	$employeeData = $_REQUEST;
	//print_r($employeeData['role_id']);die();
	$insertData = $dbObj->insertRow('employee',$employeeData,['choice'],'emp_id');
	
	if($insertData){
		echo json_encode(array('status'=>true, 'success'=>'Record added.'));
	}else{
			echo json_encode(array('status'=>false, 'success'=>'Record not inserted'));
	}
	break;

	case 'getEmployeeData':

	$empId= $_REQUEST['emp_id'];
	$query = "SELECT * FROM employee WHERE emp_id= '$empId'";
	$run_query = mysql_query($query);
	if(mysql_num_rows($run_query) > 0){
		$i = 0;
		while($row = mysql_fetch_assoc($run_query)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false,'error'=>'not exist '));
	}

	break;

	case 'updateEmployeeData':

	$employeeData = ($_REQUEST);
	//$empId = $_REQUEST['emp_id'];
	//$employeeData['id']= $emp_id;  
	$insertData = $dbObj->updateRow('employee',$employeeData,['choice','id'],'emp_id');
	if($insertData){
		//echo $insertData;
		echo json_encode(array('status'=>true, 'success'=>'Record added.'));
	}else{
			echo json_encode(array('status'=>false, 'success'=>'Record not inserted'));
	}

	break;


	case 'getSuperviserActionList':

	$supervisor_id= $_REQUEST['supervisor_id'];
	$query = "SELECT sd.*,CONCAT(first_name,' ', middle_name, ' ', last_name) as name,survey_remark, house_no, survey_status as status,id FROM survey_data sd JOIN employee e on sd.user_id = e.emp_id WHERE supervisor_id = $supervisor_id";

	//$query="SELECT * FROM survey_data WHERE user_id = $supervisor_id ";
	$run_query = mysql_query($query);
	if(mysql_num_rows($run_query) > 0){
		$i = 0;
		while($row = mysql_fetch_assoc($run_query)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false,'error'=>'not exist '));
	}
	break;

	case 'updateSuperviserActionList':
	$survey_id= $_REQUEST['survey_id'];
	$status = $_REQUEST['status'];
	
	$updateData ="UPDATE survey_data SET survey_status=$status WHERE id=$survey_id";
	$runupdateDataqry= mysql_query($updateData);
	 //mysql_affected_rows($runupdateDataqry);die();
	if(mysql_affected_rows()){
		//echo $insertData;
		echo json_encode(array('status'=>true, 'success'=>'Record updated.'));
	}else{
			echo json_encode(array('status'=>false, 'success'=>'Record not updated'));
	}
	break;


	case 'getSurveyDetailAccordingStatus':
	$supervisor_id= $_REQUEST['supervisor_id'];
	$status = $_REQUEST['status'];
	$query = "SELECT sd.*,CONCAT(first_name,' ', middle_name, ' ', last_name) as name,survey_remark, house_no, survey_status as status,id FROM survey_data sd JOIN employee e on sd.user_id = e.emp_id WHERE supervisor_id = $supervisor_id AND survey_status=$status";
	$runQuery= mysql_query($query);
	if(mysql_num_rows($runQuery) > 0){
		$i = 0;
		while($row = mysql_fetch_assoc($runQuery)){
			$result[$i] = $row;
			$i++;
		}
			echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
			echo json_encode(array('status'=>false,'error'=>'not exist '));
	}
	break;	

	$dbObj->disconnect();



}



?>