//form valitation functions
    function ValidateEmail(input_id) {
        var valid = true;
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        var input_value = $(input_id).val();
        if(!expr.test(input_value)){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be a valid email.</div>');
        }else{
            $(input_id).css('border','1px solid #ccc');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            printDiv = false;
        }
        return valid;
    }

    function validateCharacter(input_id){
        var valid=true;
        var expr = /^[a-zA-Z ]+$/;
        var input_value = $(input_id).val();
        if(!expr.test(input_value)){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be alphabets.</div>');
        }else{
            $(input_id).css('border','1px solid #ccc');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            printDiv = false;
        }
        return valid;
    }

    function validateNumeric(input_id) {
        var valid = true;
        var expr = /^\d+$/;
        var input_value = $(input_id).val();
        if(!expr.test(input_value)){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be a valid number.</div>');
        }else if(input_value<0){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be a greater than or equal to 0.</div>');
        }else{
            $(input_id).css('border','1px solid #ccc');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            printDiv = false;
        }
        return valid;
    }

    function toTitleCase(str){
        return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }

    function validateFloat(input_id){
        var valid = true;
        var expr = /^-?\d*(\.\d+)?$/;
        var input_value = $(input_id).val();
        if(!expr.test(input_value)){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be a valid number.</div>');
        }else if(input_value<0){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value should be a greater than or equal to 0.</div>');
        }else{
            $(input_id).css('border','1px solid #ccc');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            printDiv = false;
        }
        return valid;
    }

    function validateInputField(input_id){
        var valid = true;
        var input_value = $(input_id).val();
        if(!input_value){
            valid = false;
            $(input_id).css('border','1px solid red');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            $(input_id).after('<div id="'+ input_id.substring(1) +'_errormsg" class="custom-error-msg">This value is required.</div>');
        }else{
            $(input_id).css('border','1px solid #ccc');
            $('#'+ input_id.substring(1) +'_errormsg').html('');
            printDiv = false;
        }
        return valid;
    }

    function getMapArrayValue(arr, find, key, value){
        var result=null, selectedIdx=null;
        result = arr.map(function(val, i){
            if(val[key] == find){
                selectedIdx = i;
                return val[value];
            }
        })[selectedIdx];
        return result;
    }

	//return month-day-hour-min from minutes
	function getDayHour(start_dt, end_dt){
		var start_date = new Date(start_dt);
        var end_date = new Date(end_dt);
        var current_date = new Date();
        var result = '';
        if(end_dt) result = dateDifference(start_date, end_date);
        else result = dateDifference(start_date, current_date);
		var unit = {
			months: Math.floor(result/24/60/30),
			days: Math.floor(result/24/60) > 30 ? Math.floor((result/24/60)%8) : Math.floor(result/24/60),
			hours: Math.floor(result/60%24) > 24 ? Math.floor((result/60%24)%24) : Math.floor(result/60%24),
			mins: (result%60) > 60 ? Math.floor((result%60)/60) : result%60,
		}
		var convertStr = unit.months + " Mth " + unit.days + " Days " + unit.hours + " Hr " + unit.mins + " Mins";
		return convertStr;
	}

	function dateDifference(a, b){
        var difference = b.getTime() - a.getTime(); // This will give difference in milliseconds
        var resultInMinutes = Math.round(difference / 60000);
        return resultInMinutes;
    }

	function hideAddressBar() {
	  if(!window.location.hash) {
		if(document.height < window.outerHeight)
		  document.body.style.height = (window.outerHeight + 50) + 'px';
		setTimeout( function(){
			window.scrollTo(0, 1);
			document.body.style.height = 'auto';
		  }, 50 );
	  }
	}

	function removeDuplicates(originalArray, objKey) {
	  var trimmedArray = [];
	  var values = [];
	  var value;

	  for(var i = 0; i < originalArray.length; i++) {
		value = originalArray[i][objKey];

		if(values.indexOf(value) === -1) {
		  trimmedArray.push(originalArray[i]);
		  values.push(value);
		}
	  }

	  return trimmedArray;

	}

    function convert2minutes(mins){
        var totalMinutes=0;
        if(mins){
            var tempMin = mins.split(':');
			var hr = 0;
			if(tempMin[0] == "00") hr=24;
			if(tempMin[0] == "01") hr=25;
			if(tempMin[0] == "02") hr=26;
			if(tempMin[0] == "03") hr=27;
			if(tempMin[0] == "04") hr=28;
			if(tempMin[0] == "05") hr=29;
			if(tempMin[0] == "06") hr=30;
			if(tempMin[0] == "07") hr=31;
			if(hr == 0) hr=tempMin[0];
			var hour = 60*hr;
			var min = parseInt(tempMin[1]);
            totalMinutes = hour + min;
        }
        return totalMinutes;
    }
