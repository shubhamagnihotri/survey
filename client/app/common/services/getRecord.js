'use strict';

aadinathUI.service('getRecord', function ($http) {

    var jwoNumbers = [];
    var partyList = [];
    var itemList = [];
    var processList = [];
    var companyList = [];
    var colorList = [];
    var partyTypeList = [];
    var roleList = [];
	var empList = [];
	var batchList = [];
    var allSurveyRecord= [];
    var surveyorRecordData = [];
    var getAllDistrictData =[];
    var googleMapRecordData = [];
    var getRolesData = []; 
	this.viewJWO = function(obj){
		jwoNumbers = [];
        $http.post('server/controller/activity.php?choice=viewJWO', obj).success(function (data) {
            if(data.length){
                data.forEach(function (jwo) {
                    jwoNumbers.push(jwo);
                });
            }
        });

        return jwoNumbers;
	};

	this.viewParty = function (obj) {
        partyList = [];
        $http.post('server/controller/activity.php?choice=viewParty', obj).success(function (data) {
            if(data.length){
                data.forEach(function (party) {
                    partyList.push(party);
                });
            }
        });

        return partyList;
    };

    this.viewItem = function (obj) {
        itemList = [];
        $http.post('server/controller/activity.php?choice=viewItem', obj).success(function (data) {
            if(data.length){
                data.forEach(function (item) {
                    itemList.push(item);
                });
            }
        });

        return itemList;
    };

    this.viewPartyType = function () {
        partyTypeList = [];
        $http.post('server/controller/admin.php?choice=viewPartyType').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (type) {
                    partyTypeList.push(type);
                });
            }
        });

        return partyTypeList;
    };

    this.viewCompany = function () {
        companyList = [];
        $http.post('server/controller/admin.php?choice=viewCompany').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (company) {
                    companyList.push(company);
                });
            }
        });

        return companyList;
    };

    this.viewProcess = function () {
        processList = [];
        $http.post('server/controller/admin.php?choice=viewProcess').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (process) {
                    processList.push(process);
                });
            }
        });

        return processList;
    };

    this.viewColor = function () {
        colorList = [];
        $http.post('server/controller/admin.php?choice=viewColor').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (color) {
                    colorList.push(color);
                });
            }
        });

        return colorList;
    };

    this.viewRole = function () {
        roleList = [];
        $http.post('server/controller/admin.php?choice=viewRole').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (role) {
                    roleList.push(role);
                });
            }
        });

        return roleList;
    };

	this.viewEmployee = function ($filter= '') {
        empList = [];
        console.log($filter);
        $http.post('server/controller/hr.php?choice=viewEmployee', $filter).success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (emp) {
                    empList.push(emp);
                });
            }
        });

        return empList;
    };

	this.viewBatch = function () {
        batchList = [];
        $http.post('server/controller/shiftEngineer.php?choice=viewBatch').success(function (data) {
            let tempArr = data.success;
            if(tempArr.length){
                tempArr.forEach(function (batch) {
                    batchList.push(batch);
                });
            }
        });

        return batchList;
    };

    this.getNotifications = function (recordCount, roleId) {
        return $http.post('server/controller/activity.php?choice=viewNotification', {limit: recordCount, role_id: roleId}).then(function (data) {
           return data;
        });
    };

    this.updateNotifications = function (roleId) {
        $http.post('server/controller/activity.php?choice=updateNotification', {role_id: roleId}).success(function (data) {
            if(data.success){
                console.log('Notification updated.');
            }
        });
    };

    this.getAllDistrict = function(filter = {}){
        getAllDistrictData = [];
         $http.post('server/controller/activity.php?choice=getDistict',filter).success(function(data){
            if(data.success){
                let allDistrictArr = data.success;
                allDistrictArr.forEach(function(batch){
                    getAllDistrictData.push(batch);
                });
            }
           
         });
         return  getAllDistrictData;
    }

    this.surveyRecord = function(filterdata = {}){
       allSurveyRecord = [];
     
        $http.post('server/controller/surveyor.php?choice=getAllSurveyDetail',filterdata).success(function (data) {
          
            if(data.success){
                let surveyArr = data.success;
                surveyArr.forEach(function (batch) {
                    allSurveyRecord.push(batch);
                });
            }

        });
        return  allSurveyRecord;
    }

    this.surveyorRecord = function(){
        surveyorRecordData = [];
        $http.post('server/controller/surveyor.php?choice=employeeDetails',{type:19}).success(function (data){
            if(data.success){
                let surveyorRecordArr =  data.success;

                surveyorRecordArr.forEach(function(itemTerm){
                    surveyorRecordData.push(itemTerm);
                });
            };
        });

        return surveyorRecordData;
    };


    this.getFilteredMapData = function(filter){
        googleMapRecordData = [];
        $http.post('server/controller/surveyor.php?choice=getDataForGoogleMap',filter).success(function(data){
            if(data.success){
                let googlMapRecordArr = data.success;
                googlMapRecordArr.forEach(function(itemTerm){
                    googleMapRecordData.push(itemTerm);

                });
            }
        });
        return googleMapRecordData;
    }

    this.getRolesRecord = function(){
        getRolesData = [];
        $http.get('server/controller/surveyor.php?choice=getAllRoles').success(function(data){
            if(data.success){
                let getRolesDataArr = data.success;
                getRolesDataArr.forEach(function(itemTerm){
                    getRolesData.push(itemTerm);
                });
            }
        });
        return getRolesData;
    }
 
});
