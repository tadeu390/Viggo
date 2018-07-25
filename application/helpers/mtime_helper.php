<?php
	class mtime
	{
		/*!
		*	RESPONSÁVEL POR ADICIONAR UM VALOR EM MINUTOS A UMA DETERMINADA HORA.
		*
		*	$alvo -> Hora em que se quer adicionar algum tempo. ex.: 07:00:00
		*	$add -> Valor a ser adicionado na hora especificada (EM MINUTOS).
		*/
		public static function add_time($alvo, $add)
		{
			$explode = explode(":", $alvo);

			$segundos = ($explode[0] * 3600) + ($explode[1] * 60) + ($explode[2]);

			$total = $segundos + ($add * 60);
			
			$hora = 0;
			$i = 0;
			for($i = $total; $i >= 3600; $i -= 3600)
				$hora = $hora + 1;
			
			$minuto = 0;
			$j = 0;
			for($j = $i; $j >= 60; $j -= 60)
				$minuto = $minuto + 1;
			
			if($j < 10)
				$j = "0".$j;
			if($hora < 10)
				$hora = "0".$hora;

			return $hora.":".$minuto.":".$j;
		}
	}
?>