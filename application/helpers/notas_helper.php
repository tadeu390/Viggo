<?php 
	class notas
	{
		/*!
			RESPONSÁVEL POR INTERFACEAR O MÉTODO DO MODEL PARA SE OBTER UM DETERMINADO TIPO DE NOTA (DESCRICAO DA NOTA) DO ALUNO.
		*/
		public static function get_nota($descricao_nota_id, $matricula_id, $turma_id, $disc_grade_id, $etapa_id)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	
			
			return $CI->Nota_model->get_nota($descricao_nota_id, $matricula_id, $turma_id, $disc_grade_id, $etapa_id);
		}

		public static function status_nota_total_etapa($matricula_id, $turma_id, $disc_grade_id, $etapa_id, $periodo_letivo_id)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	

			$status = $CI->Nota_model->status_nota_total_etapa($matricula_id, $turma_id, $disc_grade_id, $etapa_id, $periodo_letivo_id);
			if($status == "ok")
				return "info";
			return "danger";
		}
		/*!
		*	RESPONSÁVEL POR CRIAR A INTERFACE QUE BUSCARÁ A NOTA TOTAL DO ALUNO DE UMA DISCIPLINA EM UMA TURMA.
		*
		*	$matricula_id -> Matrícula do aluno.
		*	$disciplina_id -> Id da disciplina que se quer obter a nota.
		*	$turma_id -> Id da turma do aluno.
		*	$etapa_id -> Id da etapa, bimestres,trimestre.
		*/
		public static function get_total_nota_etapa($matricula_id, $disciplina_id, $turma_id, $etapa_id)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	

			$total_nota = $CI->Nota_model->total_nota($matricula_id, $turma_id, $disciplina_id, $etapa_id);
			
			return $total_nota;
		}

		public static function status_nota_total($total_nota, $periodo_letivo_id)
		{
			$CI = get_instance();
			$CI->load->model("Regras_model");	

			//obter a media do período letivo
			$media = $CI->Regras_model->get_regras(FALSE, $periodo_letivo_id, FALSE, FALSE, FALSE)['Media'];

			//$media_nota = ($media / 100) * 100; //ONDE 100 É O VALOR TOTAL (O SEGUNDO 100)

			if($total_nota >= $media)
				return "info";
			return "danger";
		}
	}
?>