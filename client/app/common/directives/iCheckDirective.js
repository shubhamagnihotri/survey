// This directive is required to fix the issue of not updating the ng-model and also the click was not working with iCheck.
aadinathUI.directive('iCheck', ['$timeout', '$parse', function ($timeout, $parse) {
    return {
        require: 'ngModel',
        link: function ($scope, element, $attrs, ngModel) {
            return $timeout(function () {
                var value = $attrs.value;
                var $element = $(element);

                // Instantiate the iCheck control.                            
                $element.iCheck({
                    checkboxClass: 'icheckbox_minimal-grey',
                    radioClass: 'iradio_minimal-grey',
                    increaseArea: '20%'
                });

                // If the model changes, update the iCheck control.
                $scope.$watch($attrs.ngModel, function (newValue) {
                    $element.iCheck('update');
                });

                // If the iCheck control changes, update the model.
                $element.on('ifChanged', function (event) {
                    if ($element.attr('type') === 'radio' && $attrs.ngModel) {
                        $scope.$apply(function () {
                            $parse($attrs.ngModel).assign($scope.$parent, value);//assign the value in $scope so that it is accessible in controller as well.
                            ngModel.$setViewValue(value);//set the value in view.
                            ngModel.$render();
                        });
                    }

                    if ($element.attr('type') === 'checkbox' && $attrs.ngModel) {
                        $scope.$apply(function () {
                            $parse($attrs.ngModel).assign($scope.$parent, event.target.checked); //assign the value in $scope so that it is accessible in controller as well.
                            ngModel.$setViewValue(event.target.checked); //set the value in view.
                            ngModel.$render();
                        });
                    }
                });

            });
        }
    };
}]);