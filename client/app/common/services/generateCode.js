'use strict';

aadinathUI.service('generateCode', function ($http, $q) {
	
	var deferred = $q.defer();
 
    this.getCount = function () {
        return $http.get('server/controller/activity.php?choice=getItemCount')
            .then(function (response) {
                // promise is fulfilled
                deferred.resolve(response.data);
                // promise is returned
                return deferred.promise;
            }, function (response) {
                // the following line rejects the promise 
                deferred.reject(response);
                // promise is returned
                return deferred.promise;
            })
        ;
    };
});