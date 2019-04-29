'use strict';

aadinathUI.factory("authService", function ($http, $q, $window) {
    var userDetail;

    function login(params) {
        var deferred = $q.defer();

        $http.post("server/controller/activity.php?choice=login", params)
            .then(function (result) {
				if(result.data.status){
					userDetail = {
						accessToken: generatePassword(),
						empId: result.data.success.emp_id,
						userName: result.data.success.full_name,
						roleId: result.data.success.role_id,
					};
					$window.sessionStorage["userDetail"] = JSON.stringify(userDetail);
					deferred.resolve(userDetail);
				}else{
					deferred.reject(result.data.error);
				}     
            });

        return deferred.promise;
    }

    function logout() {
        var deferred = $q.defer();

        $http({
            method: "POST",
            url: "server/controller/activity.php?choice=logout"
        }).then(function (result) {
            userDetail = null;
            $window.sessionStorage["userDetail"] = null;
            deferred.resolve(result);
        }, function (error) {
            deferred.reject(error);
        });

        return deferred.promise;
    }
	
	function generatePassword() {
		var length = 10,
			charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
			retVal = "";
		for (var i = 0, n = charset.length; i < length; ++i) {
			retVal += charset.charAt(Math.floor(Math.random() * n));
		}
		return retVal;
	}

    function getUserDetail() {
        return userDetail;
    }

    function init() {
        if ($window.sessionStorage["userDetail"]) {
            userDetail = JSON.parse($window.sessionStorage["userDetail"]);
        }
    }
    init();

    return {
        login: login,
        logout: logout,
        getUserDetail: getUserDetail
    };
});