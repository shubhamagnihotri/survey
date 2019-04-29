aadinathUI.directive('tooltip', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {

            $(element).hover(function () {
                // on mouseenter
                $(element).tooltip('show');
            }, function () {
                // on mouseleave
                $(element).tooltip('hide');
            });

            $(element).on('click', function () {
                $(element).blur();
            });
        }
    };
});

aadinathUI.directive('popover', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {

            $(element).hover(function () {
                // on mouseenter
                $(element).popover('show');
            }, function () {
                // on mouseleave
                $(element).popover('hide');
            });

            $(element).on('click', function () {
                $(element).blur();
            });
        }
    };
});