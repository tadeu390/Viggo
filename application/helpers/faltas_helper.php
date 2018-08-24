<?php 
	class faltas
	{
		/*!
			RESPONSÁVEL POR INTERFACEAR O MÉTODO DO MODEL PARA SE OBTER A LISTA DE ALUNOS
		*/
		public static function get_alunos_chamada($disc_grade_id, $turma_id, $disc_hor_id)
		{
			$CI = get_instance();
			$CI->load->model("Professor_model");	
			
			$lista_alunos = $CI->Professor_model->get_alunos_chamada($disc_grade_id, $turma_id, $disc_hor_id);
			return $lista_alunos;
		}
	}
?>