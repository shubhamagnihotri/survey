'use strict';

angular.module('aadinathUI').controller('adminCtrl', function($scope, $window, $http, $state, ngDialog, authService, toaster, $timeout, getRecord, EMP_DOCUMENT_TYPE, ATTENDANCE_DEVICE){

	// $scope.filter = {
	// 	searchType: 'byMonth'
	// };
	$scope.dashboard = {};
	$scope.viewEmployee = function() {
		//$scope.filter.roleId = $scope.userDetail.roleId;
		$scope.employeeList = getRecord.viewEmployee($scope.filter);
		//console.log($scope.employeeList);
		//$scope.filter.payroll = false;
		$scope.salaryList = $scope.employeeList;
	}

	$scope.refreshData = function() {
		var type = $scope.filter.searchType;
		$scope.filter = {};
		$scope.filter.searchType = type;
		$scope.filterResult = {};
	}

	$scope.appreaciationList = [];
	$scope.kaizenList = [];
	$scope.payrollObj = {};
	$scope.attFormObj = {};
	$scope.skillList = [];
	$scope.iQTestList = [];
	$scope.integrityList = [];
	$scope.payrollList = [];
	$scope.trainingList = [];
	$scope.iqList = [];
	$scope.kaizenFormObj = {};
	$scope.integrityTestFormObj = {};
	$scope.iqTestFormObj = {};
	$scope.kaizenEmpDetails = {};
	$scope.trainingFormObj = {};
	$scope.apCrFormObj = {};
	$scope.empAttendanceList = {};
	$scope.trainingList = [];
	$scope.documentList = [];
	$scope.suplimentryType = '';
	$scope.addSuplimentryBtn = '';
	$scope.documentType = EMP_DOCUMENT_TYPE;
	$scope.attendanceDevice = ATTENDANCE_DEVICE;
	$scope.uploadPATH = 'uploads/';
	$scope.userDetail = authService.getUserDetail();
	$scope.updateByFilterValues = [];
	$scope.filterHttpData = {};
	$scope.skillSet = [
		{value: 'Metallizing', name: 'Metallizing', process: '4'},
		{value: 'Paintline', name: 'Paintline', process: '4'},
		{value: 'Maintenance', name: 'Maintenance', process: '4'},
		{value: 'Quality', name: 'Quality', process: '4'},
		{value: 'Topcoat', name: 'Topcoat', process: '2'},
		{value: 'Basecoat', name: 'Basecoat', process: '2'},
		{value: 'Golden', name: 'Golden', process: '2'},
		{value: 'Metallizing', name: 'Metallizing', process: '2'},
		{value: 'Quality_Control', name: 'Quality Control', process: '2'},
		{value: 'Spray', name: 'Spray', process: '2'},
	];
	$scope.surveyStatus = [
		{id:1,value:1,statusName:'Pending'},
		{id:2,value:2,statusName:'Approve'},
		{id:3,value:3,statusName:'Rejected'}
	]
	$scope.levelSet = [
		{value: 'L0', name: 'L0'},
		{value: 'L1', name: 'L1'},
		{value: 'L2', name: 'L2'},
		{value: 'L3', name: 'L3'},
	];
	$scope.kaizenType = [
		{value: 'S', name: 'Suggestion'},
		{value: 'A', name: 'A'},
		{value: 'B', name: 'B'},
	];
	$scope.surveyFilterBy = [
		{id:1,option:'By District',value:'byDistrict'},
		// {id:2,option:'By Role Type',value:'byRole'},
		{id:2,option:'By surveyor name',value:'bySurveyor'},
		{id:3,option:'By status',value:'byStatus'},
		{id:4,option:'By Only Date',value:'byDate'}

	];
	$scope.mapFilters = [
		{ id:1,option:'By All District',value:'byAlldistrict'},
		{ id:2,option:'By Specific District',value:'bySpecificdistrict'},
		{ id:3,option:'By Surveyor',value:'bySurveyor'}
	];
	$scope.chartLength = 0;
	$scope.options = {
		type: 'column'
	}

	function init() {
	$scope.getAllDistrict = getRecord.getAllDistrict({parent_code:1,type:2});
	$scope.surveyRecord = getRecord.surveyRecord();
	$scope.surveyorRecord = getRecord.surveyorRecord();
	$scope.filterGoogleItem = 'byAlldistrict';
	$scope.filterHttpData.typeName= 'byAlldistrict';
	$scope.filteredMapData = getRecord.getFilteredMapData($scope.filterHttpData);
	$scope.getRolesRecords = getRecord.getRolesRecord();
	//$scope.getPieChartDetail();
	//$scope.getBarChartDetail();
	 //console.log($scope.getRolesRecords);

  	}
	
  	$scope.getPieChartDetail = function(){
  	Highcharts.chart('pieChart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Chrome',
            y: 61.41,
            sliced: true,
            selected: true
        }, {
            name: 'Internet Explorer',
            y: 11.84
        }, {
            name: 'Firefox',
            y: 10.85
        }, {
            name: 'Edge',
            y: 4.67
        }, {
            name: 'Safari',
            y: 4.18
        }, {
            name: 'Sogou Explorer',
            y: 1.64
        }, {
            name: 'Opera',
            y: 1.6
        }, {
            name: 'QQ',
            y: 1.2
        }, {
            name: 'Other',
            y: 2.61
       	 }]
    	}]
	});
  	}

  	$scope.getBarChartDetail = function(){
  		 Highcharts.chart('barChart', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Stacked column chart'
    },
    xAxis: {
        categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total fruit consumption'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
            }
        }
    },
    series: [{
        name: 'John',
        data: [5, 3, 4, 7, 2]
    }, {
        name: 'Jane',
        data: [2, 2, 3, 2, 1]
    }, {
        name: 'Joe',
        data: [3, 4, 4, 2, 5]
    }]
});
}


  	$scope.update = function(){

  		$scope.filterHttpData.fiterBy=$scope.surveyFilterItem;
  		// $scope.filterHttpData.push({fiterBy:$scope.surveyFilterItem});

  		if($scope.surveyFilterItem == 'byDistrict'){
  		$scope.updateByFilterValues=$scope.getAllDistrict;

  		}else if($scope.surveyFilterItem == 'bySurveyor'){

  			$scope.updateByFilterValues = $scope.surveyorRecord;

  		}else if($scope.surveyFilterItem == 'byStatus'){
  			$scope.updateByFilterValues =$scope.surveyStatus;
  		}
  	}

  	$scope.filterGoogleMap = function(){
  		// console.log($scope.getAllDistrict);
  		// console.log($scope.surveyorRecord);
  		$scope.filterHttpData.typeName = $scope.filterGoogleItem;
  		if($scope.filterGoogleItem == 'byAlldistrict'){
			//$scope.filterHttpData.typeName = $scope.filterGoogleItem;

  			//$scope.updateGoogleMapFilters=$scope.getAllDistrict;
  			$scope.filteredMapData = getRecord.getFilteredMapData($scope.filterHttpData);

  		}
  		if($scope.filterGoogleItem == 'bySpecificdistrict'){
  			console.log($scope.filterHttpData);

  			$scope.filterHttpData.typeName = $scope.filterGoogleItem;
  			$scope.updateGoogleMapFilters=$scope.getAllDistrict;
  			//console.log($scope.updateGoogleMapFilters);
  		}
  		if($scope.filterGoogleItem == 'bySurveyor'){

  			$scope.filterHttpData.typeName = $scope.filterGoogleItem;
  			$scope.updateGoogleMapFilters=$scope.surveyorRecord;
  			console.log($scope.filterHttpData);
  		}
  	}

  	// $scope.filterGoogleMapByValue = function(){

  	// }

  	$scope.getDataForMap = function(){
  		console.log($scope.filterHttpData);
		$scope.filteredMapData = getRecord.getFilteredMapData($scope.filterHttpData);

  	}




  	// $scope.refine = function(refineType){
  	// 	alert('asdasd');
  	// 	if(refineType == 'surveyRecordRefine'){
  	// 		alert('asdasd');
  	// 		$scope.updateByFilterValues=getRecord.surveyRecord({});
  	// 	}
  	// }

  	$scope.filterSurveyData = function (){
  		console.log('adhajdhjhsda');

  		if($scope.updateByFilterItem){
  			$scope.filterHttpData.fiterValue = $scope.updateByFilterItem;
  		}
  		$scope.surveyRecord = getRecord.surveyRecord($scope.filterHttpData);
  		console.log($scope.filterHttpData);
  	}


	$scope.printDivElem = function(elem){
		$("#"+elem).printThis({
			importCSS: true,
			importStyle: true,
			loadCSS: ["lib/bootstrap/css/bootstrap.min.css",
			"style/main.css",
			"style/style.css"],
			printDelay: 333,
			formValues: true,
			base: true,
		});
	}

	$scope.openPopup = function(type, templateName, width, obj){

		if(templateName == 'employee'){
			if(type == 'new'){
				$scope.isView = 'New';
				$scope.formObj = {};
			}else{
				$scope.formObj = obj;
				let tempObj = obj;
				$scope.skillList = [];
				if(tempObj.skills){
					$scope.skillList = JSON.parse(tempObj.skills.replace(/\n/g, "\\n").replace(/\r/g, "\\r").replace(/\t/g, "\\t"));
					$scope.formObj.skills = {};
				}
				$scope.isView = 'Edit';
			}
		}
		else if(templateName == 'uploadDocuments'){
			$scope.docFormObj = obj;
			$scope.employee.getDocuments();
		}
		else if(templateName == 'addKaizen'){
			$scope.kaizenFormObj.employee_id = obj.emp_id;
			$scope.kaizenFormObj.emp_name = obj.full_name;
			$scope.employee.viewKaizen();
		}
		else if(templateName == 'addTraining'){
		  $scope.trainingFormObj.employee_id = obj.emp_id;
		  $scope.trainingFormObj.emp_name = obj.full_name;
		  $scope.employee.viewTraining();
		}
		else if(templateName == 'addAC'){
			$scope.apCrFormObj.employee_id = obj.emp_id;
			$scope.apCrFormObj.emp_name = obj.full_name;
			$scope.employee.viewAC();
		}
		else if(templateName == 'addIQTest'){
			$scope.iqTestFormObj.employee_id = obj.emp_id;
			$scope.iqTestFormObj.emp_name = obj.full_name;
			$scope.employee.viewIQTest();
		}
		else if(templateName == 'addIntegrityTest'){
			$scope.integrityTestFormObj.employee_id = obj.emp_id;
			$scope.integrityTestFormObj.emp_name = obj.full_name;
			$scope.employee.viewIntegrityTest();
		}
		else if(templateName == 'addAttendance'){
			$scope.attFormObj = obj;
		}
		else if(templateName == 'updateSalary'){
			$scope.formObj = obj;
		}
		else{
			if(type == 'new'){
				$scope.isView = 'New';
				$scope.formObj = {};
			}else{
				$scope.formObj = obj;
			}
		}
		ngDialog.open({
			template: templateName,
			className: 'ngdialog-theme-plain',
			scope: $scope,
			width: width,
			data: obj || null,
			closeByDocument: false,
			closeByEscape: false,
		});
	}

	$scope.dashboard = {
		init: function(){
			$scope.dashboard.data();
			$timeout(function(){
				$('.table-head-fix').tableHeadFixer();
			}, 200);
		},
		data : function(){
			$http.get('server/controller/hr.php?choice=dashboardData').success(function(result) {
				$scope.home = result;
			});
		}
	};

	$(function(){
		//file input field trigger when the drop box is clicked
		$("#dropBox").click(function(){
			$("#fileInput").click();
		});

		//prevent browsers from opening the file when its dragged and dropped
		$(document).on('drop dragover', function (e) {
			e.preventDefault();
		});

		//call a function to handle file upload on select file
		$('input[type=file]').on('change', fileUpload);
	});

	function fileUpload(event){
		//notify user about the file upload status
		$("#dropBox").html(event.target.value+" uploading...");

		//get selected file
		var files = event.target.files;

		//form data check the above bullet for what it is
		var data = new FormData();

		//file data is presented as an array
		for (var i = 0; i < files.length; i++) {
			var file = files[i];
			if(!file.type.match("application/vnd.ms-excel")) {
				//check file type
				$("#dropBox").html("Please choose an csv file.");
				$scope.finalArr = [];
			}else if(file.size > 1048576){
				//check file size (in bytes)
				$("#dropBox").html("Sorry, your file is too large (>1 MB)");
				$scope.finalArr = [];
			}else{
				//append the uploadable file to FormData object
				data.append('file', file, file.name);

				//create a new XMLHttpRequest
				var xhr = new XMLHttpRequest();

				//post file data for upload
				xhr.open('POST', './server/config/upload.php', true);
				xhr.send(data);
				xhr.onload = function () {
					//get response and show the uploading status
					var response = JSON.parse(xhr.responseText);
					if(xhr.status === 200 && response.status == 'ok'){
						$("#dropBox").html("File has been uploaded successfully.");
						$scope.attendance.readCSV();
					}else if(response.status == 'type_err'){
						$("#dropBox").html("Please choose an csv file.");
						$scope.finalArr = [];
					}else{
						$("#dropBox").html("Some problem occured, please try again.");
						$scope.finalArr = [];
					}
				};
			}
		}
	}


	function convertToArrayOfObjects(data) {
		var keys = data.shift(),
		m=0, k=0,
		obj = null,
		output = [];

		for (m = 0; m < data.length; m++) {
			obj = {};

			for (k = 0; k < keys.length; k++) {
				var ot,tt;
				if(keys[k] == 'Employee Code') keys[k]='EmployeeCode';
				if(keys[k] == 'Employee Name') keys[k]='EmployeeName';
				if(keys[k] == 'In Time') keys[k]='InTime';
				if(keys[k] == 'Out Time') keys[k]='OutTime';
				if(keys[k] == 'Duration') {
					var temp=(data[m][k]).split(":");
					tt = 60*parseInt(temp[0]) + parseInt(temp[1]);
					if(tt>480) ot=tt-(480+15)	//total-time - (8 hours + 15 mins)
					else ot=0;
				}
				if(keys[k] == 'Late By') keys[k]='LateBy';
				if(keys[k] == 'Early By') keys[k]='EarlyBy';
				if(keys[k] == 'Punch Records') keys[k]='PunchRecords';
				if(keys[k] == 'Overtime'){
					data[m][k] = ot;
				}
				obj[keys[k]] = data[m][k];
			}
			output.push(obj);
		}
		return output;
	}

	$scope.employee = {
		view: function(){
			$http.post('server/controller/hr.php?choice=viewEmployee', $scope.filter).success(function(result) {
				if(result.status){
					let tempObj = result.success;
					$scope.employeeList = tempObj;
				}
			});
		},
		addSuplimentryDetails: function(gridItem, suplimentryType, index){
			if(suplimentryType){
				$scope.openPopup(suplimentryType, suplimentryType, 1000, gridItem);
				$("#suplimentry-"+index).hide();
			}
		},
		showSuplimentryList: function(index){
			$("#suplimentry-"+index).val('');
			$("#suplimentry-"+index).show();
		},
		save: function(){
			// var isValid = $(".addEmployeeForm").parsley().validate();
			// if(!isValid) return false;
			$scope.formObj.skills = angular.toJson($scope.skillList);
			console.log($scope.formObj);
			$http.post('server/controller/hr.php?choice=addEmployee', $scope.formObj).success(function(result){
				if(result.status){
					$scope.skillList = [];
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.employee.view();
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
		delete: function(rowData){
			$http.post('server/controller/hr.php?choice=deleteEmployee', rowData).success(function(result){
				if(result.status){
					toaster.pop('success', result.success);
					$scope.employee.view();
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
		updateAddress: function(){
			var sameAdd = $scope.formObj.same_address;
			if(sameAdd){
				$scope.formObj.permanent_address=$scope.formObj.temporary_address;
			}else{
				$scope.formObj.permanent_address="";
			}
		},
		hideDetailRow: function(){
			if($scope.formObj.emp_id){
				return false;
			}else{
				return true;
			}
		},
		viewLeaveApplication: function(){
				$http.post('server/controller/hr.php?choice=viewLeaveApplication', $scope.filter).success(function(result) {
					if(result.status){
						$scope.employeeLeaveApplication = result.success;
					}else{
						$scope.employeeLeaveApplication = [];
					}
				});
			},
			deleteLeaveApplication: function(tmpObj){
					$http.post('server/controller/hr.php?choice=deleteLeaveApplication', tmpObj).success(function(result) {
						if(result.status){
							$scope.employee.viewLeaveApplication();
						}else{
							$scope.employeeLeaveApplication = [];
						}
					});
				},
			saveLeaveApplication: function(){
				var isValid = $(".leaveApplicationForm").parsley().validate();
				if(!isValid) return false;
				//append the uploadable file to FormData object
				var data = new FormData();
				var file = $("#applicationFile")[0].files[0];
				if(file)
				data.append('file', file, file.name);
				data.append('emp_id', $scope.formObj.emp_id);
				data.append('leave_from', $scope.formObj.leave_from);
				data.append('leave_to', $scope.formObj.leave_to);
				data.append('status', $scope.formObj.status);
				if($scope.formObj.id)
					data.append('id', $scope.formObj.id);
				$http.post('server/controller/hr.php?choice=addLeaveApplication', data, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function(result) {
					if(result.status){
						toaster.pop('success', result.success);
						ngDialog.close();
						$scope.employee.viewLeaveApplication();
					}else{
						toaster.pop('error', result.error);
					}
				});
			},
			getDocuments: function(){
				$http.post('server/controller/hr.php?choice=getDocuments', $scope.docFormObj).success(function(result) {
					if(result.status){
						let tempObj = result.success;
						$scope.documentList = tempObj;
					}
					else {
						$scope.documentList = [];
					}
				});
			},
			addKaizen: function(){
				var isValid = $(".addKaizenForm").parsley().validate();
				if(!isValid) return false;
				$http.post('server/controller/hr.php?choice=saveKaizen', $scope.kaizenFormObj).success(function(result) {
					if(result.status){
						toaster.pop('success', result.success);
						$scope.employee.viewKaizen();
						var tmp = {};
						tmp = $scope.kaizenFormObj;
						$scope.kaizenFormObj = {};
						$scope.kaizenFormObj.employee_id = tmp.employee_id;
						$scope.kaizenFormObj.emp_name = tmp.emp_name;
					} else {
						toaster.pop('error', result.error);
					}
				});
			},
			viewKaizen: function(){
				$http.post('server/controller/hr.php?choice=viewKaizen', $scope.kaizenFormObj).success(function(result) {
					if(result.status){
						let tempObj = result.success;
						$scope.kaizenList = tempObj;
					}
					else {
						$scope.kaizenList = [];
					}
				});
			},
			editKaizen: function(obj){
				obj.amount_paid = obj.amount_paid ? true : false;
				$scope.kaizenFormObj = obj;
			},
			geKaizen: function(){
				$http.post('server/controller/hr.php?choice=geKaizen', $scope.kaizenFormObj).success(function(result) {
					if(result.status){
						let tempObj = result.success;
						$scope.kaizenList = tempObj;
					}
					else {
						$scope.kaizenList = [];
					}
				});
			},
			deleteKaizen: function(obj){
				$http.post('server/controller/hr.php?choice=deleteKaizen', obj).success(function(result) {
					if(result.status){
						toaster.pop('success', result.success);
						//ngDialog.close();
						$scope.employee.viewKaizen();
					}else{
						toaster.pop('error', result.error);
					}
				});
			},
			addTraining: function(){
			  var isValid = $(".addTrainingForm").parsley().validate();
			  if(!isValid) return false;
			  $http.post('server/controller/hr.php?choice=saveTraining', $scope.trainingFormObj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      $scope.employee.viewTraining();
			      var tmp = {};
			      tmp = $scope.trainingFormObj;
			      $scope.trainingFormObj = {};
			      $scope.trainingFormObj.employee_id = tmp.employee_id;
			      $scope.trainingFormObj.emp_name = tmp.emp_name;
			    } else {
			      toaster.pop('error', result.error);
			    }
			  });
			},
			viewTraining: function(){
			  $http.post('server/controller/hr.php?choice=viewTraining', $scope.trainingFormObj).success(function(result) {
			    if(result.status){
			      let tempObj = result.success;
			      $scope.trainingList = tempObj;
			    }
			    else {
			      $scope.trainingList = [];
			    }
			  });
			},
			editTraining: function(obj){
			  $scope.trainingFormObj = obj;
			},
			deleteTraining: function(obj){
			  $http.post('server/controller/hr.php?choice=deleteTraining', obj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      //ngDialog.close();
			      $scope.employee.viewTraining();
			    }else{
			      toaster.pop('error', result.error);
			    }
			  });
			},
			addIQTest: function(){
			  var isValid = $(".addIQTestForm").parsley().validate();
			  if(!isValid) return false;
			  $http.post('server/controller/hr.php?choice=saveIQTest', $scope.iqTestFormObj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      $scope.employee.viewIQTest();
			      var tmp = {};
			      tmp = $scope.iqTestFormObj;
			      $scope.iqTestFormObj = {};
			      $scope.iqTestFormObj.employee_id = tmp.employee_id;
			      $scope.iqTestFormObj.emp_name = tmp.emp_name;
			    } else {
			      toaster.pop('error', result.error);
			    }
			  });
			},
			viewIQTest: function(){
			  $http.post('server/controller/hr.php?choice=viewIQTest', $scope.iqTestFormObj).success(function(result) {
			    if(result.status){
			      let tempObj = result.success;
			      $scope.iqList = tempObj;
			    }
			    else {
			      $scope.iqList = [];
			    }
			  });
			},
			editIQTest: function(obj){
			  $scope.iqTestFormObj = obj;
			},
			deleteIQTest: function(obj){
			  $http.post('server/controller/hr.php?choice=deleteIQTest', obj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      //ngDialog.close();
			      $scope.employee.viewIQTest();
			    }else{
			      toaster.pop('error', result.error);
			    }
			  });
			},
			addIntegrityTest: function(){
			  var isValid = $(".addIntegrityTestForm").parsley().validate();
			  if(!isValid) return false;
			  $http.post('server/controller/hr.php?choice=saveIntegrityTest', $scope.integrityTestFormObj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      $scope.employee.viewIntegrityTest();
			      var tmp = {};
			      tmp = $scope.integrityTestFormObj;
			      $scope.integrityTestFormObj = {};
			      $scope.integrityTestFormObj.employee_id = tmp.employee_id;
			      $scope.integrityTestFormObj.emp_name = tmp.emp_name;
			    } else {
			      toaster.pop('error', result.error);
			    }
			  });
			},
			viewIntegrityTest: function(){
			  $http.post('server/controller/hr.php?choice=viewIntegrityTest', $scope.integrityTestFormObj).success(function(result) {
			    if(result.status){
			      let tempObj = result.success;
			      $scope.integrityList = tempObj;
			    }
			    else {
			      $scope.integrityList = [];
			    }
			  });
			},
			editIntegrityTest: function(obj){
			  $scope.integrityTestFormObj = obj;
			},
			deleteIntegrityTest: function(obj){
			  $http.post('server/controller/hr.php?choice=deleteIntegrityTest', obj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      //ngDialog.close();
			      $scope.employee.viewIntegrityTest();
			    }else{
			      toaster.pop('error', result.error);
			    }
			  });
			},
			addAC: function(){
			  var isValid = $(".addACForm").parsley().validate();
			  if(!isValid) return false;
			  $http.post('server/controller/hr.php?choice=saveAC', $scope.apCrFormObj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      $scope.employee.viewAC();
			      var tmp = {};
			      tmp = $scope.apCrFormObj;
			      $scope.apCrFormObj = {};
			      $scope.apCrFormObj.employee_id = tmp.employee_id;
			      $scope.apCrFormObj.emp_name = tmp.emp_name;
			    } else {
			      toaster.pop('error', result.error);
			    }
			  });
			},
			viewAC: function(){
			  $http.post('server/controller/hr.php?choice=viewAC', $scope.apCrFormObj).success(function(result) {
			    if(result.status){
			      let tempObj = result.success;
			      $scope.appreaciationList = tempObj;
			    }
			    else {
			      $scope.appreaciationList = [];
			    }
			  });
			},
			editAC: function(obj){
			  $scope.apCrFormObj = obj;
			},
			deleteAC: function(obj){
			  $http.post('server/controller/hr.php?choice=deleteAC', obj).success(function(result) {
			    if(result.status){
			      toaster.pop('success', result.success);
			      //ngDialog.close();
			      $scope.employee.viewAC();
			    }else{
			      toaster.pop('error', result.error);
			    }
			  });
			},
			deleteDocument: function(fileObj){
				$http.post('server/controller/hr.php?choice=deleteDocument', fileObj).success(function(result) {
					if(result.status){
						toaster.pop('success', result.success);
						//ngDialog.close();
						$scope.employee.getDocuments();
					}else{
						toaster.pop('error', result.error);
					}
				});
			},
			uploadDocument: function(){
				var isValid = $(".uploadDocumentForm").parsley().validate();
				if(!isValid) return false;
				//append the uploadable file to FormData object
				var data = new FormData();
				var file = $("#documantFile")[0].files[0];
				if(file)
				data.append('file', file, file.name);
				data.append('file_type', $scope.docFormObj.type);
				data.append('emp_id', $scope.docFormObj.emp_id);
				$http.post('server/controller/hr.php?choice=uploadDocuments', data, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function(result) {
					if(result.status){
						$("#documantFile").val('');
						toaster.pop('success', result.success);
						$scope.docFormObj.type='';
						//ngDialog.close();
						$scope.employee.getDocuments();
					}else{
						toaster.pop('error', result.error);
					}
				});
			},
			addRow: function(arrType){
				var valid = true;
				switch(arrType){
					case 'skills':
						if(!validateInputField('#sk_process')) {valid = false;}
						//else if (!validateInputField('#sk_skill')) {valid = false;}
						else if(!validateInputField('#sk_level')) {valid = false;}
						if(!valid) return false;
						$scope.skillList.push($scope.formObj.skills);
						$scope.formObj.skills = {};
					break;

				}
			},
			deleteRow: function(index, arrType){
				if(arrType == 'supplierName'){
					$scope.supplierList.splice(index, 1);
				} else if(arrType == 'contactPerson'){
					$scope.contactList.splice(index, 1);
				} else if(arrType == 'itemList'){
					$scope.itemList.splice(index, 1);
				}
				else if(arrType == 'subItemList'){
					$scope.subItemList.splice(index, 1);
				}
				else if(arrType == 'skills'){
					$scope.skillList.splice(index, 1);
				}
			},
	};

	$scope.attendance = {
		readCSV : function() {
			// http get request to read CSV file content
			$http.get('/aadinath/server/uploads/attendance.csv').success($scope.attendance.processData);
		},
		add: function(){
			var isValid = $(".addAttendanceForm").parsley().validate();
			if(!isValid) return false;
			var attForm = {};
			attForm.emp_code = $scope.attFormObj.employee_code;
			attForm.attendance_date = $scope.attFormObj.attendance_date+' '+$scope.attFormObj.attendance_time;
			attForm.device_id = $scope.attFormObj.device_id;
			attForm.full_name = $scope.attFormObj.full_name;
			attForm.remarks = $scope.attFormObj.remarks;
			$http.post('server/controller/hr.php?choice=saveAttendance', attForm).success(function(result) {
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.attFormObj = {};
				} else {
					toaster.pop('error', result.error);
				}
			});
		},
		addOT: function(){
			var isValid = $(".addAttendanceForm").parsley().validate();
			if(!isValid) return false;
			var attForm = {};
			attForm.emp_code = $scope.attFormObj.emp_code;
			attForm.from_date = $scope.attFormObj.from_date+' '+$scope.attFormObj.from_time;
			attForm.to_date = $scope.attFormObj.to_date+' '+$scope.attFormObj.to_time;
			attForm.remarks = $scope.attFormObj.remarks;
			$http.post('server/controller/hr.php?choice=saveOT', attForm).success(function(result) {
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.attFormObj = {};
				} else {
					toaster.pop('error', result.error);
				}
			});
		},
		deleteAttendance: function(){
			var isValid = $(".deleteAttendanceForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=deleteAttendance', $scope.formObj).success(function(result) {
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.formObj = {};
				} else {
					toaster.pop('error', result.error);
				}
			});
		},
		processData : function(allText) {
			// split content based on new line
			var allTextLines = allText.split(/\r\n|\n/);
			var headers = allTextLines[0].split(',');
			var lines = [];

			for ( var i = 0; i < allTextLines.length; i++) {
				// split content based on comma
				var data = allTextLines[i].split(',');
				if (data.length == headers.length) {
					var tarr = [];
					for ( var j = 0; j < headers.length; j++) {
						tarr.push(data[j]);
					}
					lines.push(tarr);
				}
			}
			$scope.finalArr = convertToArrayOfObjects(lines);
			$timeout(function(){
				$('#attendanceTable').tableHeadFixer();
				$scope.attendance.save2Db();
			},1000);
		},
		save2Db : function(){
			$http.post('server/controller/hr.php?choice=save2Db', $scope.finalArr).success(function(result) {
				if(result.status){
					toaster.pop('success', result.success);
				} else {
					toaster.pop('error', result.error);
				}
			});
		},
		showResult: function(){
			//for map

			if($scope.filteredMapData.length <=0 ){

				return false;
			}

			var options = {
                    zoom: 5,
                    center: new google.maps.LatLng(34.083656, 74.797371), // centered US
                    mapTypeId: google.maps.MapTypeId.TERRAIN,
                    mapTypeControl: false
                };

                // init map
                var map = new google.maps.Map(document.getElementById('map'), options);

                // NY and CA sample Lat / Lng
                // var southWest = new google.maps.LatLng(45.744656, 70.005966);
                // var northEast = new google.maps.LatLng(30.052234, 90.243685);
                // var lngSpan = northEast.lng() - southWest.lng();
                // var latSpan = northEast.lat() - southWest.lat();

                // set multiple marker
                var i=0;
                console.log('checkData');
                console.log($scope.filteredMapData);
                $scope.filteredMapData.forEach(function(data){
                	if($scope.filterGoogleItem=='bySpecificdistrict' || $scope.filterGoogleItem=='bySurveyor'){

                		var marker = new google.maps.Marker({
	                        position: new google.maps.LatLng(data.latitude, data.longnitude),
	                        map: map,
	                        title: 'Click Me ' + i
                    	});
                	}else{
                		var marker = new google.maps.Marker({
	                        position: new google.maps.LatLng(data.district_lat,data.district_long),
	                        map: map,
	                        title: 'Click Me ' + i
                    	});
                	}
                	(function(marker, i) {
                        // add click event
                        google.maps.event.addListener(marker, 'click', function() {
                             infowindow = new google.maps.InfoWindow({
                                content: 'Hello, World!!'
                        	});

                            infowindow.open(map, marker);
                        });
                    })(marker, i);

                	i++;


                });return false;

                // for (var i = 0; i < 250; i++) {
                //     // init markers
                //     var marker = new google.maps.Marker({
                //         position: new google.maps.LatLng(southWest.lat() + latSpan * Math.random(), southWest.lng() + lngSpan * Math.random()),
                //         map: map,
                //         title: 'Click Me ' + i
                //     });

                //     // process multiple info windows
                //     (function(marker, i) {
                //         // add click event
                //         google.maps.event.addListener(marker, 'click', function() {
                //             infowindow = new google.maps.InfoWindow({
                //                 content: 'Hello, World!!'
                //             });
                //             infowindow.open(map, marker);
                //         });
                //     })(marker, i);
                // };return false;
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			if(new Date($scope.filter.to_date) < new Date($scope.filter.from_date)){
				$scope.error = 'from date should be less than to date';
				return false;
			}
			$scope.error = '';
			$http.post('server/controller/hr.php?choice=showAttendance', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
					$timeout(function(){
						$('#attendanceTable').tableHeadFixer();
					},1500);
				} else {
					$scope.filterResult = [];
				}
			});
		},
		showRowAttendance: function(){
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			if(new Date($scope.filter.to_date) < new Date($scope.filter.from_date)){
				$scope.error = 'from date should be less than to date';
				return false;
			}
			$scope.error = '';
			$http.post('server/controller/hr.php?choice=showRowAttendance', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
					$timeout(function(){
						$('#attendanceTable').tableHeadFixer();
					},1500);
				} else {
					$scope.filterResult = [];
				}
			});
		},
		filterView: function(){
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=getAttendance', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
					$timeout(function(){
						$('#filterViewTable').tableHeadFixer();
					},20);
				} else {
					$scope.filterResult = [];
				}
			});
		},
		showOT: function(){
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			if(new Date($scope.filter.to_date) < new Date($scope.filter.from_date)){
				$scope.error = 'from date should be less than to date';
				return false;
			}
			$scope.error = '';
			$http.post('server/controller/hr.php?choice=showOT', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
					$timeout(function(){
						$('#attendanceTable').tableHeadFixer();
					},1500);
				} else {
					$scope.filterResult = [];
				}
			});
		}
	}
	$scope.kaizen = {
		view: function(){
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=getKaizen', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
				} else {
					$scope.filterResult = [];
				}
			});
		}
	}
	$scope.training = {
		view: function(){
			$scope.filterResult = [];
			var isValid = $(".searchAttendanceForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=getTraining', $scope.filter).success(function(result) {
				if(result.status){
					$scope.filterResult = result.success;
				} else {
					$scope.filterResult = [];
				}
			});
		}
	}
	$scope.managementUpdate = {
		holiday: {
			view: function(){
		     	$http.get('server/controller/admin.php?choice=viewHoliday').success(function(result) {
		            if(result.status){
		                $scope.holidayList = result.success;
		            }else{
						$scope.holidayList = [];
					}
		        });
		     },
		    save: function(){
		     	var isValid = $(".addHolidayForm").parsley().validate();
				 if(!isValid) return false;
				 $http.post('server/controller/admin.php?choice=addHoliday', $scope.formObj).success(function(result){
		            if(result.status){
						toaster.pop('success', result.success);
						ngDialog.close();
						$scope.managementUpdate.holiday.view();
					}else{
						toaster.pop('error', result.error);
					}
		        });
		    },
		     update: function(rowData){
		     	$scope.openPopup('update', 'holiday', '600', rowData);
		     },
		     delete: function(rowData){
		     	$http.post('server/controller/admin.php?choice=deleteHoliday', rowData).success(function(result){
		            if(result.status){
						toaster.pop('success', result.success);
						$scope.managementUpdate.holiday.view();
					}else{
						toaster.pop('error', result.error);
					}
		        });
		     }
		}
	}

	$scope.salaryMaster = {
		save: function(){
			var isValid = $(".updateSalary").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=updateSalary', $scope.formObj).success(function(result){
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.employee.view();
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
	}

	$scope.payroll = {
		view: function(){
			var isValid = $(".viewPayroll").parsley().validate();
			if(!isValid) return false;
			$scope.filter.roleId = $scope.userDetail.roleId;
			$http.post('server/controller/hr.php?choice=viewPayroll', $scope.filter).success(function(result){
				if(result.status){
					$scope.payrollList = result.success;
				}else{
					$scope.payrollList = [];
					toaster.pop('error', result.error);
				}
			});
		},
		getAttendance: function(){
			var isValid = $(".searchSalarySlipForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=getAttendanceForSalary', $scope.filter).success(function(result){
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.empAttendanceList = result.success;
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
		getAttendanceForSalary: function(){
			var isValid = $(".searchSalarySlipForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=getAttendanceForSalary', $scope.filter).success(function(result){
				if(result.status){
					$scope.payrollObj = result.success;
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
		save: function(){
			var isValid = $(".payrollForm").parsley().validate();
			if(!isValid) return false;
			$http.post('server/controller/hr.php?choice=addPayroll', $scope.payrollObj.payroll).success(function(result){
				if(result.status){
					toaster.pop('success', result.success);
					ngDialog.close();
					$scope.payrollObj = {};
				}else{
					toaster.pop('error', result.error);
				}
			});
		},
		calculateSalary: function(){
			if(!validateFloat('#advance_amount')) {return false;}
			$http.post('server/controller/hr.php?choice=calculateActualSalary', $scope.payrollObj.payroll).success(function(result){
				if(result.status){
					toaster.pop('success', result.success);
					var data = result.success;
					$scope.payrollObj.payroll.pf = data.pf;
					$scope.payrollObj.payroll.esi = data.esi;
					$scope.payrollObj.payroll.cheque_amount = data.cheque_amount;
					$scope.payrollObj.payroll.cash_amount = data.cash_amount;
					$scope.payrollObj.payroll.total_amount_paid = data.total_amount_paid;
				}else{
					toaster.pop('error', result.error);
				}
			});
			//$scope.payrollObj.payroll.total_amount_paid = ((parseFloat($scope.payrollObj.payroll.net_amount) + parseFloat($scope.payrollObj.payroll.ot_amount)) - (parseFloat($scope.payrollObj.payroll.pf) + parseFloat($scope.payrollObj.payroll.esi) + parseFloat($scope.payrollObj.payroll.advance_given) + parseFloat($scope.payrollObj.payroll.security_deducted))).toFixed(2);
		},
	}

	$scope.getDistrictCoverdSurvey = function(){
        //getRolesData = [];
        $http.get('server/controller/survey.php?choice=getDistrictCoverdSurvey').success(function(data){
            if(data.status){
            	let obj = [];
            	data.success.forEach(function(val) {
					  console.log(val);
					  let data = {};
					  data.name = val.name;
					  data.y = parseFloat(val.y);
					  obj.push(data);
					});
            	console.log(obj);

                Highcharts.chart('pieChart', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<b>District wise report</b>'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                style: {
                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                }
            }
        }
    },
    series: [{
        name: 'District wise report',
        colorByPoint: true,
        data: obj
    	}]
	});
            }
        });
    }

    $scope.getDistrictByChart = function(){
        //getRolesData = [];
        $http.get('server/controller/survey.php?choice=getCoveredDistrictByMap').success(function(data){
            if(data.status){
            	let obj = [];
            	data.success.forEach(function(val) {
					  console.log(val);
					  let data = {};
					  data.name = val.name;
					  data.y = parseFloat(val.y);
					  obj.push(data);
					});
            	console.log(obj);

                Highcharts.chart('barchart', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Stacked column chart'
			    },
			    yAxis: {
			        min: 0,
			        title: {
			            text: 'Total fruit consumption'
			        },
			        stackLabels: {
			            enabled: true,
			            style: {
			                fontWeight: 'bold',
			                color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
			            }
			        }
			    },
			    legend: {
			        align: 'right',
			        x: -30,
			        verticalAlign: 'top',
			        y: 25,
			        floating: true,
			        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			        borderColor: '#CCC',
			        borderWidth: 1,
			        shadow: false
			    },
			    tooltip: {
			        headerFormat: '<b>{point.x}</b><br/>',
			        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
			    },
			    plotOptions: {
			        column: {
			            stacking: 'normal',
			            dataLabels: {
			                enabled: true,
			                color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
			            }
			        }
			    },
			    series: data.success
			});
            }
        });
    }





	init();
});
