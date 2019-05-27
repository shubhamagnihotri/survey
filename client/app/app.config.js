'use strict';

aadinathUI.config(function ($stateProvider, $urlRouterProvider, $ocLazyLoadProvider, cfpLoadingBarProvider, ngDialogProvider, $provide, $compileProvider, paginationTemplateProvider, KeepaliveProvider, IdleProvider) {

	//loader declaration
    cfpLoadingBarProvider.includeSpinner = false;
    cfpLoadingBarProvider.includeBar = true;
    cfpLoadingBarProvider.parentSelector = '#loading-bar-container';

	//logout if user is idle
	IdleProvider.idle(10000);
	IdleProvider.timeout(10000);
	KeepaliveProvider.interval(15000);

	//default pagination file path
	paginationTemplateProvider.setPath('client/lib/angular/pagination/dirPagination.tpl.html');

    $stateProvider.state('main', { //main is abstract state which will be used to show common layout to all the other states.
		url: '',
		abstract: true,
		views: {
				header: { templateUrl: 'client/app/common/views/header.html' },
                footer: { templateUrl: 'client/app/common/views/footer.html' },
                container: { templateUrl: 'client/app/common/views/container.html' },
        },
		ncyBreadcrumb: {
			skip: true
		}
	}).state('login', {
        url: '/login',
        controller: 'loginCtrl',
        views: {
            'nocontainer@': { templateUrl: 'client/app/login/login.html' },
        },
        resolve: {
			loadUserController: function ($ocLazyLoad) {
                return $ocLazyLoad.load('client/app/login/loginCtrl.js');
            }
        },
        ncyBreadcrumb: {
            skip: true
        }
    });
	//admin section URLs
	$stateProvider.state('main.admin', {
		url: '/admin',
		views:{
		  "menu": {templateUrl: 'client/app/admin/menu.html'},
			"content": {templateUrl: 'client/app/admin/dashboard.html',
				controller: 'adminCtrl',
			}
		},
		resolve: {
			loadUserController : function ($ocLazyLoad) {
				return $ocLazyLoad.load(['client/app/admin/adminCtrl.js']);
			}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
            ncyBreadcrumbLabel: '<i class="fa fa-home"></i>'
		}
	}).state('main.admin.dashboard', {
		url: '/dashboard',
		views:{
		  "content@main": {templateUrl: 'client/app/admin/dashboard.html',
							controller: 'adminCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
			parent: 'main.admin'
		}
	}).state('main.admin.reports', {
		url: '/reports/{pageType}',
		views:{
		  "content@main": {templateUrl: function($stateParams){ return 'client/app/admin/reports/'+$stateParams.pageType+'.html' },
							controller: 'adminCtrl',}
		},
		ncyBreadcrumb: {
			label: 'reports',
			parent: 'main.admin'
		}
	}).state('main.admin.user', {
		url: '/user/{pageType}',
		views:{
      "content@main": {templateUrl: function($stateParams){ return 'client/app/admin/user/'+$stateParams.pageType+'.html' },
							controller: 'adminCtrl'},
		},
		ncyBreadcrumb: {
			label: 'Users',
			parent: 'main.admin',
		}
	});
  //admin section URLs
	$stateProvider.state('main.crm', {
		url: '/crm',
		views:{
		  "menu": {templateUrl: 'client/app/crm/menu.html'},
			"content": {templateUrl: 'client/app/crm/dashboard.html',
				controller: 'crmCtrl',
			}
		},
		resolve: {
			loadUserController : function ($ocLazyLoad) {
				return $ocLazyLoad.load(['client/app/crm/crmCtrl.js']);
			}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
            ncyBreadcrumbLabel: '<i class="fa fa-home"></i>'
		}
	}).state('main.crm.dashboard', {
		url: '/dashboard',
		views:{
		  "content@main": {templateUrl: 'client/app/crm/dashboard.html',
							controller: 'crmCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
			parent: 'main.crm'
		}
	}).state('main.crm.lead', {
    url: '/lead/{pageType}',
    views:{
      "content@main": {templateUrl: function($stateParams){ return 'client/app/crm/lead/'+$stateParams.pageType+'.html' },
              controller: 'crmCtrl',}
    },
		ncyBreadcrumb: {
			label: 'Lead',
			parent: 'main.crm'
		}
	}).state('main.crm.reports', {
		url: '/reports',
		views:{
		  "content@main": {templateUrl: 'client/app/crm/reports/view.html',
							controller: 'crmCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Reports',
			parent: 'main.crm'
		}
	}).state('main.crm.lead.edit', {
		url: '/lead/{cmsId}',
		views:{
		  "content@main": {templateUrl: function($stateParams){ return 'client/app/crm/lead/add.html'},
							controller: 'crmCtrl'},
		},
		ncyBreadcrumb: {
			label: 'Edit Lead',
			parent: 'main.crm',
		}
	});
    $urlRouterProvider.otherwise('/login');

});

aadinathUI.run(function($rootScope, $state, authService){
    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams){
        let loggedIn = authService.getUserDetail();
        if  (toState.name !== 'login' && !loggedIn){
            $state.go('login');
        }
    });
});
