<?php 
	class aluno{
		/*
			retorna todas as faltas  de todas as disciplinas de um determinado alunoe em uma determinada turma
		*/
		public static function get_faltas($aluno_id, $turma_id)
		{
			$CI = get_instance();
			$CI->load->model("Boletim_model");	
			return $CI->Boletim_model->total_faltas($aluno_id,$turma_id);
		}
		/*
			retorna informacoes do aluno em uma turma com uma determinada disciplina
		*/
		public static function get_info_aluno($aluno_id, $turma_id, $disciplina_id)
		{
			//4 == POR_TURMA_E_DISCIPLINA_E_ALUNO
			$CI = get_instance();
			$CI->load->model("Boletim_model");	
			return $CI->Boletim_model->get_boletim(4,$aluno_id,$turma_id,$disciplina_id);
		}
	}
	
?>