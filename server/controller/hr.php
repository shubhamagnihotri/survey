<?php

require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_GET['choice'];

switch($choice){

	case 'addEmployee':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$preStr = null;
	$empId = isset($postdata['emp_id'])==true ? $postdata['emp_id'] : null;
	if(!$empId){
		$dbObj->insertRow('employee', $postdata, ['iQTest','roles','skills','']);
	} else {
		foreach($postdata as $key=>$val){
			$notallowded = array('id', 'ngDialogId', 'department', 'same_address', 'fdg', 'iQTest');
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
		$viewEmpQuery = "SELECT emp.*, DATE_FORMAT(emp.dob, '%Y-%m-%d') AS dob, DATE_FORMAT(emp.doj, '%Y-%m-%d') AS doj, r.name AS department FROM employee emp JOIN roles r ON emp.role_id=r.id WHERE emp_id = '$empId' AND `status`=1 ORDER BY emp.full_name";
	}
	else{
		$viewEmpQuery = "SELECT emp.*, DATE_FORMAT(emp.dob, '%Y-%m-%d') AS dob, DATE_FORMAT(emp.doj, '%Y-%m-%d') AS doj, r.name AS department FROM employee emp JOIN roles r ON emp.role_id=r.id WHERE `status`=1 ORDER BY emp.full_name";
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
