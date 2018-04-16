jQuery.validator.addMethod("IsValidText", function(value, element) {
	return this.optional(element) || (/^[0-9a-zāčēģīķļņšūžA-ZĀČĒĢĪĶĻŅŠŪŽ][a-zāčēģīķļņšūžA-ZĀČĒĢĪĶĻŅŠŪŽ\/0-9\s.,_-]+$/u).test(value);
}, "Letters, numbers and special symbols only allowed");

jQuery.validator.addMethod("IsValidName", function(value, element) {
	return this.optional(element) || /^[a-zāčēģīķļņšūžA-ZĀČĒĢĪĶĻŅŠŪŽ][a-zāčēģīķļņšūžA-ZĀČĒĢĪĶĻŅŠŪŽ\s-]+$/u.test(value);
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

jQuery.validator.addMethod("IsValidFloatNumberWithThreeDigitsAfterDot", function(value, element) {
	return this.optional(element) || /^\d{1,12}([\.,]\d{1,3})?$/.test(value);
}, "Enter a value with a max 2 numbers after comma");

jQuery.validator.addMethod("IsValidFloatNumber", function(value, element) {
	return (value > 0);
}, "Enter a valid value");

jQuery.validator.addMethod("IsValidUsername", function(value, element) {
	return this.optional(element) || /^[a-zA-Z0-9]*$/.test(value);
}, "Letters and numbers only allowed");

jQuery.validator.addMethod("IsValidPassword", function(value, element) {
	return this.optional(element) || /^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,64}$/.test(value);
}, "Uppercase and lowercase letter, number, sepcial symbol required");

jQuery.validator.addMethod("ChosenRoleDropdown", function(value, element) {
	return (value == "1" || value == "2" || value == "3");
}, "Please choose value");

jQuery.validator.addMethod("IsValidDate", function(value, element) {
	return this.optional(element) || /^\d{4}[\-\/\s]?((((0[13578])|(1[02]))[\-\/\s]?(([0-2][0-9])|(3[01])))|(((0[469])|(11))[\-\/\s]?(([0-2][0-9])|(30)))|(02[\-\/\s]?[0-2][0-9]))$/.test(value);
}, "Enter valid date, example: YYYY-MM-DD");

jQuery.validator.addMethod("IsValidTime", function(value, element) {
	return this.optional(element) || /^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/.test(value);
}, "Enter valid time, example: HH:MM");

jQuery.validator.addMethod("IsValidIntegerNumber", function(value, element) {
	return this.optional(element) || /^\d{1,12}$/.test(value);
}, "Enter valid number");

jQuery.validator.addMethod("IsValidInteger", function(value, element) {
	return (value > 0);
}, "Enter a valid value");

jQuery.validator.addMethod("IsValidHours", function(value, element) {
	return this.optional(element) || /^([1-9]|1[0-9]|2[0-4])$/.test(value);
}, "Enter valid hours");

jQuery.validator.addMethod("IsValidPersonNo", function(value, element) {
	return this.optional(element) || /^\d{6}-\d{5}$/.test(value);
}, "Enter valid hours");

jQuery.validator.addMethod("IsValidActNo", function(value, element) {
	return this.optional(element) || /^([0-9][0-9]{0,2}|1000)$/.test(value);
}, "Enter valid hours");

jQuery.validator.addMethod("ChosenNonWorkingDropdown", function(value, element) {
	return (value == "1" || value == "2" || value == "3" || value == "0" || value == "");
}, "Please choose value");

jQuery.validator.addMethod("IsValidWorkingHours", function(value, element) {
	return this.optional(element) || /^[aAcCnNsS1-8]?$/.test(value);
}, "Enter valid hours or options");

jQuery.validator.addMethod("IsValidOvertimeHours", function(value, element) {
	return this.optional(element) || /^[1-8]?$/.test(value);
}, "Enter valid hours or options");
