<?php
	class mdate
	{
		public static function weekday($dia)
		{
			$arrayDia = array();
			$arrayDia[1] = "Segunda";
			$arrayDia[2] = "Terça";
			$arrayDia[3] = "Quarta";
			$arrayDia[4] = "Quinta";
			$arrayDia[5] = "Sexta";
			$arrayDia[6] = "Sábado";
			$arrayDia[7] = "Domingo";

			return $arrayDia[$dia];
		}
	}
?>