<?php 
	class faltas
	{
		/*!
			RESPONSÁVEL POR INTERFACEAR O MÉTODO DO MODEL PARA SE OBTER A LISTA DE ALUNOS
		*/
		public static function get_alunos_chamada($disciplina_id, $turma_id, $subturma)
		{
			$CI = get_instance();
			$CI->load->model("Professor_model");	
			
			$lista_alunos = $CI->Professor_model->get_alunos($disciplina_id, $turma_id, $subturma);
			return $lista_alunos;
		}

		public static function get_presenca_aluno($matricula_id, $disciplina_id, $turma_id, $subturma, $data)
		{
			$CI = get_instance();
			$CI->load->model("Calendario_presenca_model");

			$lista_presenca = $CI->Calendario_presenca_model->get_presenca_aluno($matricula_id, $disciplina_id, $turma_id, $subturma, $data);
			return $lista_presenca;
		}
	}
?>