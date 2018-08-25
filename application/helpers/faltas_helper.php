<?php 
	class faltas
	{
		/*!
			RESPONSÁVEL POR INTERFACEAR O MÉTODO DO MODEL PARA SE OBTER A LISTA DE ALUNOS
		*/
		public static function get_alunos_chamada($disc_grade_id, $turma_id, $subturma)
		{
			$CI = get_instance();
			$CI->load->model("Professor_model");	
			
			$lista_alunos = $CI->Professor_model->get_alunos($disc_grade_id, $turma_id, $subturma);
			return $lista_alunos;
		}
	}
?>