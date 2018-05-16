<?php
	class mstring
	{
		public static function corta_string($string, $tam)
		{
			$str = substr($string, 0, $tam);
			
			if(strlen($string) > $tam)
				$str = $str."...";
			
			return $str;
		}
	}
?>