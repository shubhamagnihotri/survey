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
	$result['allTotalSurvey'] =  mysql_fetch_array(mysql_query($totalsurveyQuery))['total_survey'];

	$coverdistictQuery = "SELECT count(DISTINCT(`district`)) AS cover_distict FROM survey_data WHERE user_id = '$id'";
	$result['allTotalDistCovered'] =  mysql_fetch_array(mysql_query($coverdistictQuery))['cover_distict'];
	
	
	$pendingsurveyQuery = "SELECT count(id) as pending_survey from survey_data WHERE survey_status='1' AND user_id = '$id'";
	$result['allPendingSurvey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey from survey_data WHERE survey_status='2' AND user_id = '$id'";
	$result['allApprovedSurvey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey from survey_data WHERE survey_status='3' AND user_id = '$id'";
	$result['allRejectedSurvey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];
	
	$today = date('Y-m-d');

	$todayAssignTask = "SELECT daily_task FROM employee WHERE  DATE(daily_task_assign_date) = '$today' AND 	emp_id = '$id' ";
	$result['todayTotalAssignTask'] =  mysql_fetch_array(mysql_query($todayAssignTask))['daily_task'];


	$todayTotalComplteqry = "SELECT count(id) AS today_survey  from  survey_data WHERE DATE(created_at) = '$today' AND user_id = '$id'";
	$result['todayTotalCompletedTask'] =  mysql_fetch_array(mysql_query($todayTotalComplteqry))['today_survey'];
	if($result['todayTotalAssignTask'] > 0 ){
	$result['todayTotalPendingTask'] = strval($result['todayTotalAssignTask'] - $result['todayTotalCompletedTask']);	
	}else{
		$result['todayTotalPendingTask '] = '0';
	}

	$todayTotalApproveStatusTaskQury = "SELECT count(id) as todayApproveStatus from survey_data WHERE survey_status='2' AND user_id = '$id'";
	$result['todayTotalApproveStatusTask'] =  mysql_fetch_array(mysql_query($todayTotalApproveStatusTaskQury))['todayApproveStatus'];
	$todayTotalPendingStatusTaskQury = "SELECT count(id) as todayPendingStatus from survey_data WHERE survey_status='1' AND user_id = '$id'";
	$result['todayTotalPendingStatusTask'] =  mysql_fetch_array(mysql_query($todayTotalPendingStatusTaskQury))['todayPendingStatus'];
	$todayTotalRejectedStatusTaskQury = "SELECT count(id) as todayRejectedStatus from survey_data WHERE survey_status='3' AND user_id = '$id'";
	$result['todayTotalRejectedStatusTask'] =  mysql_fetch_array(mysql_query($todayTotalRejectedStatusTaskQury))['todayRejectedStatus'];

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
	$qry = "SELECT CONCAT(first_name,' ', middle_name, ' ', last_name) as name,survey_remark, house_no, survey_status as status,id FROM survey_data WHERE user_id = '$id' $where";	
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
	//print_r($_REQUEST);die();
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
			$household_roster = ($_REQUEST['household_roster']);
			echo count($household_roster);
			 echo json_encode($household_roster);
			for($i=0; $i < count($household_roster); $i++ ){
				$_REQUEST['household_roster'][$i]['survey_detail_id'] = $insertSurveyDetail;
				$insertHouseHold = $dbObj->insertRow('household_roster',$_REQUEST['household_roster'][$i],[]);	
			}
			// while($row=$_REQUEST['household_roster'][$i]){

			// // json_encode(array('sadasd'=>$insertSurveyDetail));						
			// $_REQUEST['household_roster'][$i]['survey_detail_id'] = $insertSurveyDetail;
			// //$insertHouseHold = $dbObj->insertRow('household_roster',$_REQUEST['household_roster'][$i],[]);
			// $i++;
			// };
			
		}	
		echo json_encode(array('status'=>true, 'success'=>'Record added.'));
	}else{
		echo json_encode(array('status'=>false, 'success'=>'Record not inserted'));
	}

	break;


		
	case 'getAllSurveyDetail':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$id = $_REQUEST['survey_id'];
	$status_type = $_REQUEST['statusType'];
	if($postdata['fiterBy']){
		$fiterBy = $postdata['fiterBy'];
	}
	if($postdata['fiterValue']){
		$fiterValue = $postdata['fiterValue'];
	}

	$today = $_REQUEST['today'];
	$district = $_REQUEST['district'];
	$where = '';
	if($id){
		$where = "WHERE id = '$id'"; 
	}
	
	if($fiterBy){
			
		switch($fiterBy){
			case 'bySurveyor':
			!empty($where) ? $where .=" AND sd.user_id = $fiterValue":$where =" WHERE sd.user_id = $fiterValue";
				
			break;
			case 'byStatus':
			!empty($where) ? $where .=" AND sd.survey_status = $fiterValue":$where =" WHERE sd.survey_status = $fiterValue";
				
			break;

			case 'byDistrict':
			!empty($where) ? $where .=" AND sd.district = $fiterValue":$where =" WHERE sd.district = $fiterValue";
			break;

		}
	}
	if($postdata['from_date'] || $postdata['to_date']){
		$from_date=$postdata['from_date'];
		$to_date = $postdata['to_date'];
		!empty($where) ? $where .=" AND DATE(sd.created_at) BETWEEN '$from_date' AND '$to_date'":$where =" WHERE DATE(sd.created_at) BETWEEN '$from_date' AND '$to_date'";
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

	case 'getAllRoles':
	$query = "SELECT id,name  FROM roles";
	$resquery = mysql_query($query);
	$result = [];
	if(mysql_num_rows($resquery) > 0){
		$i=0;
		while($row= mysql_fetch_assoc($resquery)){
			$result[$i] = $row; 
			$i++;
		}
		echo json_encode(array('status'=>true,'success'=>$result));

	}else{
		echo json_encode(array('status'=>false,'error'=>'Data not exist'));
	}

	break;

	case 'employeeDetails':
	$type=$_REQUEST['type'];
	$where = '';
	if($type){
		$where = "WHERE roles.id=$type AND em.status=1";
	}
	$query = "SELECT em.emp_id,em.full_name,em.phone,em.daily_task FROM employee em INNER JOIN roles ON em.role_id=roles.id $where";
	$resquery= mysql_query($query);

	if(mysql_num_rows($resquery) > 0){
		$result=[];$i=0;
		while($row=mysql_fetch_assoc($resquery)){
			$result[$i] = $row;
			$i++;
		}
		echo json_encode(array('status'=>true,'success'=>$result));
	}else{
		echo json_encode(array('status'=>false,'error'=>'Data not exist'));
	}

	break;



	case 'getDataForGoogleMap':
	$postdata = json_decode(file_get_contents('php://input'),true);
	$where = '';
	//$postdata= $_REQUEST;
	$typeName=$postdata['typeName'];	
	if($typeName){
		$typevalue=$postdata['typevalue'];
			
		if($typeName == 'bySpecificdistrict'){
		$select = "latitude,longnitude";	
		empty($where) ? $where = "WHERE district= $typevalue":" AND district= $typevalue";
		}else if($typeName == 'bySurveyor'){
			$select = "	latitude,longnitude";	
			empty($where) ? $where = "WHERE user_id= $typevalue":" AND user_id= $typevalue";
		}else if($typeName == 'byAlldistrict' ){
			$select = "DISTINCT(district),district_lat,district_long";
		}
	}

	$query = " SELECT $select FROM survey_data $where";
	$run_query = mysql_query($query);

	if(mysql_num_rows($run_query) > 0 ){
		$i=0;
		$result= [];
		while($row=mysql_fetch_assoc($run_query)){
			$result[$i] = $row; 
			$i++;
		}
		echo json_encode(array('status'=>true,'success'=>$result));
	}else{
		echo json_encode(array('status'=>false,'error'=>'Data Not Exist'));
	}
	break;


	case 'getSurveyDetail':
	$survey_data_id = $_REQUEST['survey_data_id'];
	$getSurvetDetailQuery = "SELECT * FROM survey_data WHERE id='$survey_data_id'";
	$run_query = mysql_query($getSurvetDetailQuery);
	if(mysql_num_rows($run_query) > 0 ){
		$i=0;
		$result= [];
		while($row=mysql_fetch_assoc($run_query)){
			$result[$i] = $row; 
			$i++;
		}
		echo json_encode(array('status'=>true,'success'=>$result));
	}else{
		echo json_encode(array('status'=>false,'error'=>'Data Not Exist'));
	}
	


	break;
	$dbObj->disconnect();

}





?>