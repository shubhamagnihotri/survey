aadinathUI.filter('sumByKey', function () {
    return function (data, key) {
        if (typeof (data) === 'undefined' || typeof (key) === 'undefined') {
            return 0;
        }

        var sum = 0;
        for (var i = data.length - 1; i >= 0; i--) {
			if(!data[i][key]) data[i][key]=0;
			//if(key == 'actual_basecoat' || key == 'basecoat' || key == 'diff_basecoat' || key == 'actual_topcoat' || key == 'topcoat' || key == 'diff_topcoat' || key == 'actual_primer' || key == 'primer' || key == 'diff_primer') sum += parseFloat(data[i][key]);			
			else sum += parseInt(data[i][key]);           
        }

        return sum;
    };
});

aadinathUI.filter('sumByArrayKey', function () {
    return function (data, key) {
        if (typeof (data) === 'undefined' || typeof (key) === 'undefined') {
            return 0;
        }

        var sum = 0;
        data.map(function(val, i){
            sum += parseInt(data[i].rejection[key]);
        });

        return sum;
    };
})