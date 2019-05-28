<?php

require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_GET['choice'];

switch($choice){

	case 'dashboardData':
	$totalsurveyQuery = "SELECT count(id) AS total_survey  from  survey_data ";

	$result['total_survey'] =  mysql_fetch_array(mysql_query($totalsurveyQuery))['total_survey'];

	$coverSuryoverQuery = "SELECT count(*) AS total_suryover  from  employee WHERE role_id = 4 ";
	$result['total_suryover'] =  mysql_fetch_array(mysql_query($coverSuryoverQuery))['total_suryover'];

	$totalSupervisorQuery = "SELECT count(*) AS total_supervisor  from  employee WHERE role_id = 3 ";

	$result['total_supervisor'] =  mysql_fetch_array(mysql_query($totalSupervisorQuery))['total_supervisor'];


/*
	$pendingsurveyQuery = "SELECT count(survey_status) as pending_survey from survey_data WHERE survey_status=1 ";
	$result['pending_survey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey from survey_data WHERE survey_status=2 ";
	$result['approve_survey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey from survey_data WHERE survey_status=3 ";
	$result['rejected_survey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];
*/
	$coverdist_surveycount = "SELECT city.name,`district`,count(*) AS sum FROM `survey_data` INNER JOIN city on survey_data.district=city.id GROUP BY `district`";

	$rescoverdist_surveycount=mysql_query($coverdist_surveycount);
	while($row=mysql_fetch_assoc($rescoverdist_surveycount)){

				$result['total_distict_wise'][] = $row;
	}

	$qry = "SELECT count(id) as total, CONCAT(first_name,' ', middle_name, ' ', last_name) as name,survey_remark, house_no, survey_status as status,id, s.full_name FROM survey_data sd JOIN employee e on sd.user_id = e.emp_id JOIN employee s ON s.emp_id = e.supervisor_id WHERE survey_status=1 GROUP BY e.supervisor_id";

	$res=mysql_query($qry);
	while($row=mysql_fetch_assoc($res)){

				$result['approval_pending'][] = $row;
	}
	$qry = "SELECT full_name FROM employee e ";

	$res=mysql_query($qry);
	while($row=mysql_fetch_assoc($res)){

				$result['loggedid_users'][] = $row;
	}
	echo json_encode($result);
	break;

	case 'addEmployee':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$preStr = null;
	$empId = isset($postdata['emp_id'])==true ? $postdata['emp_id'] : null;
	if(!$empId){
		$dbObj->insertRow('employee', $postdata, ['iQTest','roles','skills','']);
	} else {
		foreach($postdata as $key=>$val){
			$notallowded = array('id', 'ngDialogId', 'department', 'same_address', 'fdg', 'iQTest', 'roles');
			if(!in_array($key, $notallowded)){
				if($key == 'dob' || $key == 'doj'){
					$val = date("Y-m-d h:m:s", strtotime($val));
				}
				$preStr .= (strlen($preStr) > 0 ? ", " : "") . "$key='$val'";
			}
		}
		$updateQuery = "UPDATE employee SET $preStr where emp_id='$empId'";
		$responseQuery = mysql_query($updateQuery);
		$affected_rows = mysql_affected_rows();
		if($affected_rows > 0){
			echo json_encode(array('status'=>true, 'success'=>'Employee updated.'));
		}else{
			echo json_encode(array('status'=>false, 'error'=>mysql_error()));
		}
	}
	break;

	case 'deleteEmployee':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$empId = $postdata['emp_id'];
	$deleteQuery = "DELETE FROM employee WHERE emp_id='$empId'";
	$responseQuery = mysql_query($deleteQuery);
	if($responseQuery){
		echo json_encode(array('status'=>true, 'success'=>'Record deleted.'));
	}else{
		echo json_encode(array('status'=>false, 'error'=>mysql_error()));
	}
	break;

	case 'viewEmployee':
	$result = array();
	$postdata = json_decode(file_get_contents('php://input'),true);
	$empId = $postdata['emp_id'];
	if($empId)
	{
		$viewEmpQuery = "SELECT emp.*, DATE_FORMAT(emp.dob, '%Y-%m-%d') AS dob, DATE_FORMAT(emp.doj, '%Y-%m-%d') AS doj, r.name AS department FROM employee emp JOIN roles r ON emp.role_id=r.id WHERE emp_id = '$empId' AND emp.status=1 ORDER BY emp.full_name";
	}
	else{
		$viewEmpQuery = "SELECT emp.*, DATE_FORMAT(emp.dob, '%Y-%m-%d') AS dob, DATE_FORMAT(emp.doj, '%Y-%m-%d') AS doj, r.name AS department,r.name  as department FROM employee emp JOIN roles r ON emp.role_id=r.id WHERE emp.status=1 ORDER BY emp.full_name";
	}
	$response = mysql_query($viewEmpQuery);
	if(mysql_num_rows($response) > 0){
		$i = 0;
		while($row = mysql_fetch_assoc($response)){
			if($postdata['payroll']){
				if($postdata['roleId'] == 10){
					$allowded = array('11', '12', '13', '14');
					//echo $row['role_id'];
					if(in_array($row['role_id'], $allowded)){
						$result[$i] = $row;
						$i++;
					}
				}
				else{
					$result[$i] = $row;
					$i++;
				}
			}
			else{
				$result[$i] = $row;
				$i++;
			}
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false, 'error'=>'No records found'));
	}
	break;

}

?>
