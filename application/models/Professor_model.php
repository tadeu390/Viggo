<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS MATRICULAS DO SISTEMA.
	*/
	class Professor_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS
		*	
		*	$Ativo -> Quando passado "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma matricula específica.
		*	$page-> Número da página de registros que se quer carregar.
		*/
		public function get_disciplinas($Ativo = FALSE, $id = false, $page = false, $filter = false, $ordenacao = false)
		{
			//obs.: não precisa paginar, dificilmente um professor irá ter uma quantidade gigante de disciplinas por período.
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{
				$CI = get_instance();
				$CI->load->model("Account_model");

				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];

				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT d.Nome AS Nome_disciplina, dt.Id AS Disc_turma_id  
					FROM Disc_turma dt 
					INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
					INNER JOIN Turma t ON dt.Turma_id = t.Id 
					INNER JOIN Disciplina d ON dg.Disciplina_id = d.Id 
					INNER JOIN Grade g ON dg.Grade_id = g.Id 
					INNER JOIN Curso c ON g.Curso_id = c.Id 
					WHERE dt.Periodo_letivo_id = ".$this->db->escape($this->input->cookie('periodo_letivo_id'))." AND dt.Professor_Id = ".$this->db->escape($CI->Account_model->session_is_valid()['id'])." 
				    ".$Ativos." 
				    GROUP BY 1, 2 
					".str_replace("'", "", $this->db->escape($order))." ".$pagination."");

				return $query->result_array();
			}

			$query = $this->db->query("
				
				WHERE i.Id = ".$this->db->escape($id)." ".$Ativos."");

			return $query->row_array();
		}
	}
?>