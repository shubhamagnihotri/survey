'use strict';

aadinathUI.factory('httpRequestInterceptor', function () {
  return {
    request: function (config) {

      //config.headers['Authorization'] = 'Basic vikram';
      //config.headers['Accept'] = 'application/json;odata=verbose';

      return config;
    }
  };
});

aadinathUI.config(function ($httpProvider) {
  $httpProvider.interceptors.push('httpRequestInterceptor');
});
