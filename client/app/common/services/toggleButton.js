'use strict';

aadinathUI.service('toggleButton', function () {
	
	this.enableButton = function (btnId) {
        $('#'+btnId).removeAttr('disabled');
    };
	
	this.disableButton = function(btnId){
		$('#'+btnId).attr('disabled',true);
	}
	
});