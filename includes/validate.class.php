<?php

	class Validate
	{
		static function IsValidTime($text)
		{
			return preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $text);
		}
	}

?>