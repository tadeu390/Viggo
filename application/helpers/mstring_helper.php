<?php
	class mstring
	{
		public static function corta_string($string, $tam, $r = FALSE)
		{
			$str = substr($string, 0, $tam);
			
			if(strlen($string) > $tam && $r == TRUE)
				$str = $str."...";
			
			return $str;
		}
	}
?>