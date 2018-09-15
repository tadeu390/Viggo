<?php 
	class notas
	{
		/*!
		*	RESPONSÁVEL POR POR AUXILIAR NA CONSTRUÇÃO DA VIEW DE NOTAS, CADA ALUNO POSSUI VÁRIAS NOTAS CADASTRADAS,
		*	ENTÃO O OBJETIVO DESTE MÉTODO É INTERMEDIAR O MÉTODO DA MODEL QUE RETORNA UMA DETERMINADA NOTA.
		*	
		*	$descricao_nota_id -> Tipo de nota que se deseja consultar.
		*	$matriculad_id -> Matrícula do aluno na disciplina.
		*	$etapa_id -> Id da etapa que se deseja obter a nota.
		*/
		public static function get_nota($descricao_nota_id, $matricula_id, $etapa_id)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	
			
			return $CI->Nota_model->get_nota($descricao_nota_id, $matricula_id, $etapa_id);
		}
		/*!
			RESPONSÁVEL POR AUXILIAR NA CONSTRUÇÃO DA VIEW DE NOTAS, PRA CADA ALUNO SE TEM UMA NOTA,
			ENTÃO O OBJETIVO DESTE MÉTODO É INTERMEDIAR O MÉTODO DA MODEL QUE DETERMINA O STATUS DE UMA NOTA.

			$etapa_id -> Id da etapa que está carregando na tela.
			$periodo_letivo_id -> Id do período letivo selecionado.
			$nota -> Nota que se deseja consultar o status.
		*/
		public static function status_nota($etapa_id, $periodo_letivo_id, $nota)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	

			$status = $CI->Nota_model->status_nota($etapa_id, $periodo_letivo_id, $nota);
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
		public static function get_total_nota_etapa($matricula_id, $etapa_id)
		{
			$CI = get_instance();
			$CI->load->model("Nota_model");	

			$total_nota = $CI->Nota_model->total_nota($matricula_id, $etapa_id);
			
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