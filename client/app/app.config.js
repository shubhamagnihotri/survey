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
	//hr section URLs
	$stateProvider.state('main.hr', {
		url: '/hr',
		views:{
		  "menu": {templateUrl: 'client/app/hr/menu.html'},
			"content": {templateUrl: 'client/app/hr/dashboard.html',
				controller: 'hrCtrl',
			}
		},
		resolve: {
			loadUserController : function ($ocLazyLoad) {
				return $ocLazyLoad.load(['client/app/hr/hrCtrl.js']);
			}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
            ncyBreadcrumbLabel: '<i class="fa fa-home"></i>'
		}
	}).state('main.hr.dashboard', {
		url: '/dashboard',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/dashboard.html',
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Dashboard',
			parent: 'main.hr'
		}
	}).state('main.hr.kaizen', {
		url: '/kaizen',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/kaizen/view.html',
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Kaizen',
			parent: 'main.hr'
		}
	}).state('main.hr.training', {
		url: '/training',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/training/view.html',
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Training',
			parent: 'main.hr'
		}
	}).state('main.hr.managementUpdate', {
		url: '/managementUpdate',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/managementUpdate/announcement.html',
							controller: 'hrCtrl'},
		},
		ncyBreadcrumb: {
			skip:true
		}
	}).state('main.hr.managementUpdate.announcement', {
		url: '/announcement',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/managementUpdate/announcement.html',
							controller: 'hrCtrl'},
		},
		ncyBreadcrumb: {
			label: 'Announcement',
			parent: 'main.hr',
		}
	}).state('main.hr.managementUpdate.holidayList', {
		url: '/holidayList',
		views:{
		  "content@main": {templateUrl: 'client/app/hr/managementUpdate/holidayList.html',
							controller: 'hrCtrl'},
		},
		ncyBreadcrumb: {
			label: 'Holiday List',
			parent: 'main.hr',
		}
	}).state('main.hr.employee', {
		url: '/employee/{pageType}',
		views:{
      "content@main": {templateUrl: function($stateParams){ return 'client/app/hr/employee/'+$stateParams.pageType+'.html' },
							controller: 'hrCtrl'},
		},
		ncyBreadcrumb: {
			label: 'Employee',
			parent: 'main.hr',
		}
	}).state('main.hr.attendance', {
		url: '/attendance/{pageType}',
		views:{
		  "content@main": {templateUrl: function($stateParams){ return 'client/app/hr/attendance/'+$stateParams.pageType+'.html' },
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Attendance',
			parent: 'main.hr'
		}
	}).state('main.hr.salaryMaster', {
		url: '/salaryMaster/{pageType}',
		views:{
		  "content@main": {templateUrl: function($stateParams){ return 'client/app/hr/salaryMaster/'+$stateParams.pageType+'.html' },
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Salary Master',
			parent: 'main.hr'
		}
	}).state('main.hr.payroll', {
		url: '/payroll/{pageType}',
		views:{
		  "content@main": {templateUrl: function($stateParams){ return 'client/app/hr/payroll/'+$stateParams.pageType+'.html' },
							controller: 'hrCtrl',}
		},
		ncyBreadcrumb: {
			label: 'Payroll',
			parent: 'main.hr'
		}
	});
  //hr section URLs
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
