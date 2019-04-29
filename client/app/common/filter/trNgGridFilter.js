aadinathUI.filter("parseObject", function () {
    return function (fieldValueUnused, item) {
		var tempObj = item;
		var len = tempObj.length;
        var str = '';
        tempObj.forEach(function(val, i){
        	 if(i<(len-1)){
				 str += 'Count: ' + val.count + ' ' + 'State: ' + val.state + '\n';
			 }else{
				 str += 'Count: ' + val.count + ' ' + 'State: ' + val.state;
			 }
        });
        return str;
    };
});