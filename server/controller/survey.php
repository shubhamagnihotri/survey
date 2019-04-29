<?php


require_once('../config/dbconfig.php');
$dbObj =  new Connection();
$dbObj->connect();

$choice= $_REQUEST['choice'];

switch($choice){

	case 'dashboardData':
	$result = [];
	$totalsurveyQuery = "SELECT count(id) AS total_survey  from  survey_data ";

	 $result['restotal_survey'] =  mysql_fetch_array(mysql_query($totalsurveyQuery))['total_survey'];

	$coverdistictQuery = "SELECT count(DISTINCT(`distict`)) AS cover_distict FROM survey_data";
	$result['cover_distict'] =  mysql_fetch_array(mysql_query($coverdistictQuery))['cover_distict'];
	
	
	$pendingsurveyQuery = "SELECT count(survey_status) as pending_survey from survey_data WHERE survey_status=1 ";
	$result['pending_survey'] =  mysql_fetch_array(mysql_query($pendingsurveyQuery))['pending_survey'];


	$approvesurveyQuery = "SELECT count(survey_status) as approve_survey from survey_data WHERE survey_status=2 ";
	$result['approve_survey'] =  mysql_fetch_array(mysql_query($approvesurveyQuery))['approve_survey'];

	$rejectedsurveyQuery = "SELECT count(survey_status) as rejected_survey from survey_data WHERE survey_status=3 ";
	$result['rejected_survey'] =  mysql_fetch_array(mysql_query($rejectedsurveyQuery))['rejected_survey'];

	$coverdist_surveycount = "SELECT city.name,`distict`,count(*) AS sum FROM `survey_data` INNER JOIN city on survey_data.distict=city.id GROUP BY `distict`";
	
	$rescoverdist_surveycount=mysql_query($coverdist_surveycount);
	while($row=mysql_fetch_assoc($rescoverdist_surveycount)){
				
				$result['coverdist_surveycount'] = $row;
	}

	echo json_encode($result);
	
	break;

	case 'serveyDone':

	$_REQUEST['ownership_of_assets'] = json_encode($_REQUEST['ownership_of_assets']);
	
	// $main = $_REQUEST['main'];
	
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

	$dbObj->disconnect();

}





?>