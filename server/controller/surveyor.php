<?php


require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_REQUEST['choice'];

switch($choice){
	
	case 'dashboardData':
	$id = $_REQUEST['userId'];
	$result = [];
	$totalsurveyQuery = "SELECT count(id) AS total_survey  from  survey_data WHERE user_id = '$id'";
	$result['totalSurvey'] =  mysql_fetch_array(mysql_query($totalsurveyQuery))['total_survey'];

	$coverdistictQuery = "SELECT count(DISTINCT(`district`)) AS cover_distict FROM survey_data WHERE user_id = '$id'";
	$result['totalDistCovered'] =  mysql_fetch_array(mysql_query($coverdistictQuery))['cover_distict'];
	
	
	$pendingsurveyQuery = "SELECT count(id) as pending_survey from survey_data WHERE survey_status='1' AND user_id = '$id'";
	$result['pendingSurvey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey from survey_data WHERE survey_status='2' AND user_id = '$id'";
	$result['approvedSurvey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey from survey_data WHERE survey_status='3' AND user_id = '$id'";
	$result['rejectedSurvey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];
	
	$today = date('Y-m-d');
	$qry = "SELECT count(id) AS today_survey  from  survey_data WHERE DATE(created_at) = '$today' AND user_id = '$id'";
	$result['todayCovered'] =  mysql_fetch_array(mysql_query($qry))['today_survey'];
	
	$qry = "SELECT daily_task from  employee WHERE emp_id = '$id'";
	$daily_task =  mysql_fetch_array(mysql_query($qry))['daily_task'];
	$result['todayPending'] = $daily_task - $result['todayCovered'];
	
	echo json_encode(array('status'=>true, 'success'=>$result));
	
	break;
	
	case 'getCoveredDistrict':
	$id = $_REQUEST['userId'];
	$result = [];	
	$qry = "SELECT c.name, count(sd.id) AS cover_distict, district as district_id FROM survey_data sd join city c ON sd.district = c.id WHERE user_id = '$id' GROUP BY district";	
	$res = mysql_query($qry);
	$num_rows = mysql_num_rows($res);
	if($num_rows > 0) {
		$i = 0;
		while($row = mysql_fetch_assoc($res)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false, 'error'=>'No records found'));
	}
	break;
	
	
	
	case 'getAllSurvey':
	$id = $_REQUEST['userId'];
	$type = $_REQUEST['type'];
	
	$district_id = $_REQUEST['district_id'];
	if($type){
		$where = "AND survey_status = '$type'";
	}

	if($district_id){
		$where .= "AND district = '$district_id'";
	}
	$result = [];	
	$qry = "SELECT CONCAT(first_name,' ', middle_name, ' ', last_name) as name, house_no, survey_status as status,id FROM survey_data WHERE user_id = '$id' $where";	
	$res = mysql_query($qry);
	$num_rows = mysql_num_rows($res);
	if($num_rows > 0) {
		$i = 0;
		while($row = mysql_fetch_assoc($res)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true, 'success'=>$result));
	}else{
		echo json_encode(array('status'=>false, 'error'=>'No records found'));
	}
	break;
	
	$result['totalDistCovered'] =  mysql_fetch_array(mysql_query($coverdistictQuery))['cover_distict'];
	
	
	$pendingsurveyQuery = "SELECT count(id) as pending_survey from survey_data WHERE survey_status='1' AND user_id = '$id'";
	$result['pendingSurvey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey from survey_data WHERE survey_status='2' AND user_id = '$id'";
	$result['approvedSurvey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey from survey_data WHERE survey_status='3' AND user_id = '$id'";
	$result['rejectedSurvey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];
	
	$today = date('Y-m-d');
	$qry = "SELECT count(id) AS today_survey  from  survey_data WHERE DATE(created_at) = '$today' AND user_id = '$id'";
	$result['todayCovered'] =  mysql_fetch_array(mysql_query($qry))['today_survey'];
	
	$qry = "SELECT daily_task from  employee WHERE emp_id = '$id'";
	$daily_task =  mysql_fetch_array(mysql_query($qry))['daily_task'];
	$result['todayPending'] = $daily_task - $result['todayCovered'];
	
	echo json_encode(array('status'=>true, 'success'=>$result));
	
	break;

	case 'serveyDone':

	$_REQUEST['ownership_of_assets'] = json_encode($_REQUEST['ownership_of_assets']);
	
	$household_roster =$_REQUEST['household_roster'];

	$_REQUEST['awarness_saving_details'] = json_encode($_REQUEST['awarness_saving_details']);
	
	$_REQUEST['tool_used'] = json_encode($_REQUEST['tool_used']);

	$_REQUEST['training_details'] = json_encode($_REQUEST['training_details']);
	$_REQUEST['required_handi_training_detail'] = json_encode($_REQUEST['required_handi_training_detail']);
	$_REQUEST['artisans_member_detail'] = json_encode($_REQUEST['required_handi_training_detail']);

	$insertSurveyDetail= $dbObj->insertRow('survey_detail',$_REQUEST,['household_roster','userId','choice','type']);
	if($insertSurveyDetail){
		if($_REQUEST['household_roster']){
			$i=0;
			while($_REQUEST['household_roster'][$i]){
			
			$_REQUEST['household_roster'][$i]['survey_detail_id'] = $insertSurveyDetail;
			$insertHouseHold = $dbObj->insertRow('survey_detail',$_REQUEST['household_roster'][$i],[]);
			$i++;
			}
		}	
		echo json_encode(array('status'=>true, 'success'=>'Record added.'));
	}else{
		echo json_encode(array('status'=>false, 'success'=>'Record not inserted'));
	}

	break;


		
	case 'getAllSurveyDetail':
	$id = $_REQUEST['survey_id'];
	$status_type = $_REQUEST['type'];
	$today = $_REQUEST['today'];
	$district = $_REQUEST['district'];
	$where = '';
	if($id){
		$where = "WHERE id = '$id'"; 
	}
	if($status_type){
		!empty($where) ? $where .=" AND survey_status = $status_type":$where =" WHERE survey_status = $status_type";
		
	}
	if($today && $today==true){
		$to_day_date = date('Y-m-d');
		!empty($where) ? $where .= " AND DATE (created_at) = '$to_day_date'":$where="WHERE  DATE (created_at) = '$to_day_date'";
		
	}
	if($district){
		!empty($where) ? $where .= " AND district = $district":$where="WHERE  district = $district";
	}
	 $query= "SELECT sd.mobile,sd.survey_status,sd.survey_remark,CONCAT(sd.first_name,' ',sd.middle_name,' ',sd.last_name) full_name,city.name district,DATE_FORMAT(sd.created_at,'%Y-%m-%d') AS created_at,roles.name postion_type,employee.full_name as employee_name FROM survey_data sd INNER JOIN city ON sd.district=city.id INNER JOIN employee ON sd.user_id = employee.emp_id INNER JOIN
	 roles ON roles.id = employee.role_id $where";
	$res = mysql_query($query);
	$result = [];
	if(mysql_num_rows($res) >0){
		$i=0;
		while($row= mysql_fetch_assoc($res)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true,'success'=>$result));	
	}else{
		echo json_encode(array('status'=>true,'error'=>'Data not exist'));
	}

	break;


	$dbObj->disconnect();

}





?>