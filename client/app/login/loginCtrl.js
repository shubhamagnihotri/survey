angular.module('aadinathUI').controller('loginCtrl', function($scope, $window, $http, $state, authService){

	$scope.credentials = {};
	$scope.userDetail = null;

	$scope.login = function () {
		var valid = true;
	   if (!$scope.credentials.name){
			valid = false;
		}
		if (!$scope.credentials.password){
			valid = false;
		}

        if (!valid) {
        	$('.error_msg').text('Please enter Username or Password.');
        	return false;
        }else{
        	$('.error_msg').text('');
        }

		authService.login($scope.credentials).then(function (result) {
			$('.error_msg').text('');

			$scope.userDetail = result;
			//if ($scope.userDetail.roleId == 1) $state.go('main.admin');
			if ($scope.userDetail.roleId == 1) $state.go('main.admin');
			// else if ($scope.userDetail.roleId == 2) $state.go('main.marketing');
			// else if ($scope.userDetail.roleId == 3) $state.go('main.gateEntry');
			// else if ($scope.userDetail.roleId == 4) $state.go('main.store');
			// else if ($scope.userDetail.roleId == 5) $state.go('main.shiftEngineer');
			// else if ($scope.userDetail.roleId == 6) $state.go('main.coordinator');
			// else if ($scope.userDetail.roleId == 7) $state.go('main.maintenance');
			// else if ($scope.userDetail.roleId == 8) $state.go('main.productionManager');
			// else if ($scope.userDetail.roleId == 9) $state.go('main.mis');
			// else if ($scope.userDetail.roleId == 18) $state.go('main.crm');
			else if ($scope.userDetail.roleId == 2) $state.go('main.admin');
		}, function (error) {
			$('.error_msg').text(error);
		});



    }

});
