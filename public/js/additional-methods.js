jQuery.validator.addMethod("IsValidText", function(value, element) {
	return this.optional(element) || /^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u.test(value);
}, "Letters, numbers and special symbols only allowed");

jQuery.validator.addMethod("IsValidName", function(value, element) {
	return this.optional(element) || /^\p{L}[\p{L}\s-]+$/u.test(value);
}, "Letters, numbers and special symbols only allowed");

jQuery.validator.addMethod("ChosenWorkplaceDropdown", function(value, element) {
	 return (value == "1" || value == "2" || value == "3");
}, "Please choose value");

jQuery.validator.addMethod("ChosenShiftDropdown", function(value, element) {
	 return (value == "1" || value == "2");
}, "Please choose value");

jQuery.validator.addMethod("IsValidFloatNumberWithTwoDigitsAfterDot", function(value, element) {
	 return this.optional(element) || /^\d{1,12}([\.,]\d{1,2})?$/.test(value);
}, "Enter a value with a max 2 numbers after comma");

jQuery.validator.addMethod("IsValidFloatNumber", function(value, element) {
	 return (value > 0);
}, "Enter a valid value");

// jQuery.validator.addMethod("ExistsPositionName", function(value, element) {
	
// 	var position = $( "#positionArea" ).val();

// 	$.ajax({
// 	        type: "POST",
// 	        url: "check_position",
// 	        data: {position:position},
// 	        dataType: "json",
// 	        success: function(returnData)
//         	{
// 	            if (returnData!== 'true')
// 	            {
// 	              return false;
// 	            }
// 	            else
// 	            {
// 	               return true;
// 	            }
//         	}
// 	    });

// }, "Position already exists");