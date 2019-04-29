aadinathUI.directive('dialogButton', function (ngDialog) {
    return {
        restrict: 'C',
		scope: {
			control: '='
		},
        template: '<button ng-click="maximizePopup();" class="fa fa-window-restore btn-transparent"></button>',
		link: function(scope, element, attrs, ctrl) {
				scope.maximizePopup = function(){
					console.log(scope);
					console.log(element);
					console.log(attrs);
					var dialogObj = {
						title:element.parent().find('span.title').text(),
						divData:element.parent().parent()[0].innerHTML.trim(),
					}
					ngDialog.open({
						template: 'client/app/common/views/expandedPopup.html',
						className: 'ngdialog-theme-plain',
						closeByDocument: false,
						closeByEscape: false,
						data:dialogObj,
						width:800
					});
				}
		}
    }
});
