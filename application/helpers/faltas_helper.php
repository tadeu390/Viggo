<?php 
	class faltas
	{
		/*!
		*	RESPONSÁVEL POR RETORNAR TODAS AS PRESENÇAS OU FALTAS POR ALUNO DE UMA TURMA EM UMA DISCIPLINA PARA UMA DETERMINADA DATA.
		*
		*	$matricula_id -> Matrícula do aluno.
		*	$disciplina_id -> Id da disciplina selecionada na tela.
		*	$turma_id -> Id da turma selecionada.
		*	$subturma -> Id da subturma da turma.
		*	$data -> Data de que se precisar obter as presênças/faltas do aluno.
		*/
		public static function get_presenca_aluno($matricula_id, $disciplina_id, $turma_id, $subturma, $data)
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$lista_presenca = $CI->Calendario_presenca_model->get_presenca_aluno($matricula_id, $disciplina_id, $turma_id, $subturma, $data);
			return $lista_presenca;
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR DA MODEL AS FALTAS DE UM DETERMINADO ALUNO POR MÊS.
		*
		*	$disciplina_id -> Id da disciplina selecionada na tela.
		*	$turma_di -> Id da turma selecionada.
		*	$matricula_id -> Matricula do aluno na disciplina.
		*/
		public static function get_faltas($disciplina_id, $turma_id, $matricula_id, $mes)//ISSO AQUI VAI MUDAR, TEM BUG NO CONTEXTO EM QUE É UTILIZADO
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$faltas = $CI->Calendario_presenca_model->get_faltas($disciplina_id, $turma_id, $matricula_id, $mes);
			return $faltas;
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR DA MODEL TODAS AS FALTAS DE UM DETERMINADO ALUNO EM UMA DETERMINADA ETAPA.
		*	
		*	$data_inicio -> Data de início da etapa.
		*	$data_fim -> Data de fim da etapa.
		*	$matricula_id -> Matrícula do aluno na disciplina que se deseja obter as faltas.
		*/
		public static function get_faltas_etapa($data_inicio, $data_fim, $matricula_id)
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$faltas = $CI->Calendario_presenca_model->get_faltas_etapa($data_inicio, $data_fim, $matricula_id)['Faltas'];
			return $faltas;
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR DA MODEL TODAS AS FALTAS DE UM ALUNO EM TODAS AS DISCIPLINAS.
		*	
		*	$aluno_id -> Id do aluno que se quer saber a quantidade de faltas.
		*	$turma_id -> Id da turma do aluno para encontrar todas as disicplinas que o mesmo faz nela.
		*/
		public static function get_total_faltas($aluno_id, $turma_id)
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$faltas = $CI->Calendario_presenca_model->get_total_faltas($aluno_id, $turma_id)['Faltas'];
			return $faltas;
		}
	}
?>