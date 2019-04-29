'use strict';

var aadinathUI = angular.module('aadinathUI', ['ngAnimate', 'ngSanitize', 'infinite-scroll', 'ui.router', 'oc.lazyLoad', 'ncy-angular-breadcrumb', 'angular-loading-bar', 'trNgGrid', 'ngDialog', 'toaster', 'angularUtils.directives.dirPagination', 'ngIdle', 'highcharts-ng']);

aadinathUI.controller("MainController", function ($window, $scope, $rootScope, $sce, $state, $http, $ocLazyLoad, toaster, ngDialog, $timeout, $interval, getRecord, authService, Idle, Keepalive) {

  //state to access in container html
  $scope.$state = $state;
  $scope.started = false;

  $scope.$on('$stateChangeStart', function(event, toState, toParams) {
    $rootScope.currentUrlPath= location.hash.split('/');
    var currentRoot=$state.current.name.split('.');

    if( currentRoot[1] && ($rootScope.currentUrlPath[1]!==currentRoot[1]) && $rootScope.currentUrlPath[1] !== "login" && currentRoot[1]!=="login"){
      $scope.logout();
    }
  });


  function closeModals() {
    if ($scope.timedout) {
      $scope.timedout.close();
      $scope.timedout = null;
    }
  }

  $scope.$on('IdleEnd', function() {
    closeModals();
  });

  $scope.$on('IdleTimeout', function() {
    closeModals();
    $scope.timedout = ngDialog.open({
      template: 'timedout-dialog.html',
      className: 'ngdialog-theme-plain',
      scope: $scope,
      closeByDocument: false,
      closeByEscape: false,
    });
  });

  $scope.start = function() {
    closeModals();
    Idle.watch();
    $scope.started = true;
  };

  $scope.stop = function() {
    closeModals();
    Idle.unwatch();
    $scope.started = false;

  };

  $scope.redirectToLogin = function(){
    $scope.logout();
    closeModals();
  }

  var notificationInterval;
  $scope.initHeader = function () {
    $scope.userDetail = authService.getUserDetail();
    $scope.notify.get(10);
    $scope.startInterval();
  }

  $scope.startInterval = function() {
    notificationInterval = $interval(function () {
      if ($window.sessionStorage["userDetail"]) {
        //$scope.notify.get(10);
      }
    }, 20000);
  };

  $scope.logout = function () {
    authService.logout().then(function (result) {
      $scope.userDetail = null;
      $scope.notifications = [];
      $interval.cancel(notificationInterval);
      $state.go('login');
    }, function (error) {
      console.log(error);
    });
  };
  //Event fires when any ngDiagonal opens.
  $rootScope.$on('ngDialog.opened', function (e, $dialog) {
    $timeout(function () {
      Main.init();

      //datepicker
      //$('#deliveryDate, #scheduleDate').datepicker({ minDate: "0", maxDate:"2M" });

      //inline process checker time field
      //$('#entry_time').timepicker({'scrollDefault': 'now', 'step': 60});

      //coordinator fixture inspection
      //$('#fixtureReceivingDt').datepicker({  maxDate: "0" });

      //admin employee dob & doj
      //$('#dobTextField').datepicker({  maxDate: "0" });
      //$('#dojTextField').datepicker({  maxDate: "0" });
    }, 300);
  });

  //get the notifications from server.
  $scope.notify = {
    get: function(recordCount){
    
      if($scope.userDetail){
      let tempData = getRecord.getNotifications(recordCount, $scope.userDetail.roleId);
      tempData.then(function(data) {
        $scope.notifications = data.data.notifications;
        if($scope.notifications){
          $scope.notifications.filter(function(val, i){
            val['Duration'] = getDuration(val.created_date);
          });
        }
        $scope.notificationCount = data.data.notificationCount;
      });
      }


    },
    update: function(){
      $scope.notificationCount = 0;
      getRecord.updateNotifications($scope.userDetail.roleId);
    },
    viewAll: function(){
      let tempData = getRecord.getNotifications(0, $scope.userDetail.roleId);
      tempData.then(function(data) {
        $scope.notificationsAll = data.data.notifications;
        $scope.notificationsAll.filter(function(val, i){
          val['Duration'] = getDuration(val.created_date);
        });
        $scope.notificationCount = data.data.notificationCount;
      });
      ngDialog.open({
        template: 'NotificationPopup',
        className: 'ngdialog-theme-plain',
        scope: $scope,
        width: 800,
        showClose: true,
        closeByDocument: false,
        closeByEscape: false,
      });
    },
    viewHoliday: function(){
      $http.get('controller/admin.php?choice=viewHoliday').success(function(result) {
        if(result.status){
          $scope.holidayList = result.success;
        }else{
          $scope.holidayList = [];
        }
      });
      ngDialog.open({
        template: 'HolidayPopup',
        className: 'ngdialog-theme-plain',
        scope: $scope,
        width: 700,
        showClose: true,
        closeByDocument: false,
        closeByEscape: false,
      });
    },
    viewFeedback: function(){
      $http.post('server/controller/activity.php?choice=viewFeedback', {emp_id:  $scope.userDetail.empId}).success(function(result) {

        if(result.status){
          $scope.feedbackList = result.success;
        }else{
          $scope.feedbackList = [];
        }
      });
      ngDialog.open({
        template: 'FeedbackPopup',
        className: 'ngdialog-theme-plain',
        scope: $scope,
        width: 700,
        showClose: true,
        closeByDocument: false,
        closeByEscape: false,
      });
    }
  }

  function getDuration(dt_added){
    var strDuration = null;
    var notifyDate = new Date(dt_added);
    var currDate = new Date();
    var result = dateDifference(notifyDate, currDate);
    var difference = {
      TotalMinutes: result,
      TotalHours: Math.floor(result/60),
      TotalDays: Math.floor((result/60)/24),
    };

    if (difference.TotalMinutes < 1)
    strDuration = "few seconds ago";
    else if (difference.TotalMinutes < 60)
    strDuration = difference.TotalMinutes + " minute" + (difference.TotalMinutes > 1 ? "s" : "") + " ago";
    else if (difference.TotalHours < 12)
    strDuration = difference.TotalHours + " hour" + (difference.TotalHours > 1 ? "s" : "") + " ago";
    else if (difference.TotalDays < 1)
    strDuration = "few hours ago";
    else if (difference.TotalDays < 8)
    strDuration = difference.TotalDays + " day" + (difference.TotalDays > 1 ? "s" : "") + " ago";
    else if (difference.TotalDays < 28)
    strDuration = "few weeks ago";
    else if (difference.TotalDays < 56)
    strDuration = "a month ago";
    else
    strDuration = "older than a month";

    return strDuration;
  }

  $(document).on('click', '#ngdialog1 .message>a', function () {
    ngDialog.close();
  });

  $(document).on('click', ".custom-datepicker", function (e) {
    $('.custom-datepicker').datetimepicker({
      weekStart: 1,
      todayBtn:  0,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0,
      //daysOfWeekDisabled: [3],
      format: 'yyyy-mm-dd hh:ii:ss',
      pickerReferer: 'input',
      pickerPosition: 'bottom-left',
      //startDate: '2017-11-12',
      //endDate: '2017-11-18'
    });
    $(this).datetimepicker('show');
  });

  $(document).on('click', ".custom-only-datepicker", function (e) {
    $('.custom-only-datepicker').datetimepicker({
      weekStart: 1,
      todayBtn:  0,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0,
      //daysOfWeekDisabled: [3],
      format: 'yyyy-mm-dd',
      pickerReferer: 'input',
      pickerPosition: 'bottom-left',
      //startDate: '2017-11-12',
      //endDate: '2017-11-18'
    });
    $(this).datetimepicker('show');
  });
  $(document).on('click', ".year-picker", function (e) {
    $('.year-picker').datetimepicker({
      weekStart: 1,
      todayBtn:  0,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0,
      //daysOfWeekDisabled: [3],
      format: 'yyyy',
      pickerReferer: 'input',
      pickerPosition: 'bottom-left',
      //startDate: '2017-11-12',
      //endDate: '2017-11-18'
    });
    $(this).datetimepicker('show');
  });
  $(document).on('click', ".month-picker", function (e) {
    $('.month-picker').datetimepicker({
      weekStart: 1,
      todayBtn:  0,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0,
      //daysOfWeekDisabled: [3],
      format: 'mm',
      pickerReferer: 'input',
      pickerPosition: 'bottom-left',
      //startDate: '2017-11-12',
      //endDate: '2017-11-18'
    });
    $(this).datetimepicker('show');
  });
  $(document).on('click', ".custom-timepicker", function (e) {
    $('.custom-timepicker').datetimepicker({
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 1,
      minView: 0,
      maxView: 1,
      forceParse: 0,
      format: 'hh:ii',
      pickerReferer: 'input',
      pickerPosition: 'bottom-left',
    });

    $(this).datetimepicker('show');
  });

  function setHeight() {
    var windowHeight = $(window).height()-175;
    $('.panel-body').css('min-height', windowHeight);
  };

  $(window).resize(function() {
    setHeight();
  });

  $scope.$on('$viewContentLoaded', function () {

    var currState = $state.current.name;
    var currURL = $state.current.url.replace("/", "");

    $timeout(function () {
      Main.init();
      setHeight();

      //trigger popover
      $('[data-toggle="popover"]').popover();

      //disable drag of image
      $('img').on('dragstart', function (event) { event.preventDefault(); });

      //date picker condition
      //$('#electricPicker, #waterPicker').datepicker({ minDate: "-15D", maxDate: "0" });

     //set active li selected on refresh
      var defaultURL = ["main.admin", "main.marketing", "main.gateEntry", "main.store", "main.shiftEngineer", "main.coordinator", "main.maintenance", "main.productionManager", "main.mis", "main.hr"];
      var hrAttendanceURL = ['main.hr.attendance']; var hrEmployeeURL = ['main.hr.employee'];
      //crm
      var crmURL = ['main.crm.leadlist'];
      var path = "";
      var setActive = 'ul.main-navigation-menu > li a';
      if(defaultURL.includes(currState)){
        $('ul.main-navigation-menu > li').first('li').addClass('active');
      } else if (hrEmployeeURL.includes(currState)) {
        path = '#/hr/employee/view';
      }else if (crmURL.includes(currState)) {
        path = '#/crm/lead/list';
      }else if (!path) {
        path = window.location.hash;
      }
      var loopFlag=true;
      $(setActive).filter(function (i, val) {
        var href = $(this).attr('href');
        if (path === href && loopFlag) {
          loopFlag = false;
          $(this).parent().parent().find('li').removeClass('active open').find('ul.sub-menu').hide();
          if ($(this).parent().find('ul.sub-menu')) {
            $(this).parent().find('ul.sub-menu').show();
          } else {
            $(this).parent().find('ul.sub-menu').hide();
          }
          $(this).closest('li').addClass('active');
        }
      });
    }, 100);
  });

  $scope.trustHTML = function (html) {
    return $sce.trustAsHtml(html);
  }

  $scope.showTooltip = function (val, evt) {
    var nxtSibling = evt.currentTarget.parentElement.children[1];
    if (val) {
      if (val.length > 20) {
        if (evt.type == 'mouseover') {
          nxtSibling.style.display = 'block';
        } else {
          nxtSibling.style.display = 'none';
        }
      }
    }
  }

  $scope.advanceSearch = {
    openPopup: function () {
      ngDialog.open({
        template: 'client/app/common/views/searchPopup.html',
        className: 'ngdialog-theme-plain',
        scope: $scope,
        width: 800,
        showClose: true,
        closeByDocument: false,
        closeByEscape: false,
      });
    },
    search: function(){

    }
  };

  //hide address bar on mobile devices
  if(navigator.platform != 'Win32'){
    hideAddressBar();
  }

});

/*
This will run on boostrap of angularjs application.
*/
aadinathUI.run(function (Idle) {
  Array.prototype.includes = function (element) {
    return this.indexOf(element) > -1;
  };

  //use to watch user loggin time
  Idle.watch();
});
