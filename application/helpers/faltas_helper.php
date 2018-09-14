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
		public static function get_faltas($disciplina_id, $turma_id, $matricula_id, $mes)
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$faltas = $CI->Calendario_presenca_model->get_faltas($disciplina_id, $turma_id, $matricula_id, $mes);
			return $faltas;
		}
	}
?>