aadinathUI.filter('titleEllipsis', function () {
    return function (str) {
       if (str) {
        if (str.length > 12) {
            var sub = str.substring(0, 11) + '...';
            return sub;
        } else {
            return str;
        }
    }
    }
});

aadinathUI.filter('roleEllipsis', function () {
    return function (str) {
        if (str != undefined) {
            if (str.length > 32) {
                var sub = str.substring(0, 31) + '...';
                return sub;
            } else {
                return str;
            }
        }
    }
});

aadinathUI.filter('optionEllipsis', function () {
    return function (str) {
        if (str != undefined) {
            if (str.length > 45) {
                var sub = str.substring(0, 44) + '...';
                return sub;
            } else {
                return str;
            }
        }
    }
});