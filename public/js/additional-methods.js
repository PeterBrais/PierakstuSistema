jQuery.validator.addMethod("IsValidText", function(value, element) {
	return this.optional(element) || /^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u.test(value);
}, "Letters, numbers and special symbols only allowed");

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