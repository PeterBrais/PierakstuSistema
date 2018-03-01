<?php

	class Validate
	{
		static function IsValidTime($text)
		{
			return preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $text);
		}

		static function IsValidFloatNumber($number)
		{
			return preg_match("/^\d{1,12}([\.]\d{1,3}+)?$/", $number);
		}

		static function IsValidDate($text)
		{
			return preg_match("/^\d{4}[\-\/\s]?((((0[13578])|(1[02]))[\-\/\s]?(([0-2][0-9])|(3[01])))|(((0[469])|(11))[\-\/\s]?(([0-2][0-9])|(30)))|(02[\-\/\s]?[0-2][0-9]))$/", $text);
		}

		static function IsValidIntegerNumber($number)
		{
			return preg_match("/^\d{1,11}+$/", $number);
		}

		static function IsValidTextLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 255)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidText($text)
		{
			return preg_match("/^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u", $text);
		}

		static function IsValidNameLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 50)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidName($text)
		{
			return preg_match("/^\p{L}[\p{L}\s-]+$/u", $text);
		}

		static function IsValidPositionLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 40)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidUsername($text)
		{
			return preg_match("/^[a-zA-Z0-9]*$/", $text);
		}

		static function IsValidPasswordLength($text)
		{
			if(mb_strlen($text) < 8 || mb_strlen($text) > 64)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidPassword($text)
		{
			return preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,64}$/", $text);
		}
	}

?>