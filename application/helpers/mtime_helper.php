<?php 
	/*!
		ESTA CLASSE É RESPONSÁVEL POR IMPLEMENTAR MÉTODOS DE AUXILIO NA MANIPULAÇÃO DE HORAS.
	*/
	class mtime
	{
		/*!
		*	RESPONSÁVEL POR SOMAR UMA HORA NA OUTRA OU ADICIONAR UM DETERMINADO TEMPO $add(00:00:00) EM UMA DETERMINADA HORA $alvo(00:00:00).
		*
		*	$alvo -> Hora em que se quer adicionar algum tempo. ex.: 07:00:00.
		*	$add -> Hora a ser adicionada 00:00:00.
		*/
		public static function add_time($alvo, $add)
		{
			$segundos = mtime::to_second($alvo);

			$total = $segundos + (mtime::to_second($add));
			
			return mtime::to_hour($total);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A DIFERENÇA DE HORAS ENTRE DUAS HORAS.
		*
		*	$t2 -> Hora fim.
		*	$t2 -> Hora inicio.
		*/
		public static function diff_time($t2, $t1)
		{
			$segundos_t2 = mtime::to_second($t2);
			$segundos_t1 = mtime::to_second($t1);

			$diff = $segundos_t2 - $segundos_t1;

			return mtime::to_hour($diff);
		}
		/*!
		*	RESPONSÁVEL POR TRANSFORMAR HORAS EM SEGUNDOS.
		*
		*	$alvo -> Hora que se deseja transformar em segundos.
		*/
		public static function to_second($alvo)
		{
			$explode = explode(":", $alvo);
			return ($explode[0] * 3600) + ($explode[1] * 60) + ($explode[2]);
		}
		/*!
		*	RESPONSÁVEL POR CONVERTER UM HORÁRIO DE SEGUNDOS PARA HORA (00:00:00).
		*
		*	$alvo -> Segundos que se deseja transformar em horas.
		*/
		public static function to_hour($alvo)
		{
			$hora = 0;
			$i = 0;
			for($i = $alvo; $i >= 3600; $i -= 3600)
				$hora = $hora + 1;
			
			$minuto = 0;
			$j = 0;
			for($j = $i; $j >= 60; $j -= 60)
				$minuto = $minuto + 1;
			
			if($hora < 10)
				$hora = "0".$hora;
			if($minuto < 10)
				$minuto = "0".$minuto;
			if($j < 10)
				$j = "0".$j;

			return $hora.":".$minuto.":".$j;
		}
	}
?>