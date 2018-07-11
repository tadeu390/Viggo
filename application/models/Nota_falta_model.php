<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE NOTA_FALTA.
	*/
	class Nota_falta_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE TURMAS OU UMA TURMA ESPECÍFICA.
		*	
		*	$Ativo -> Quando passadO "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma turma específica.
		*	$page-> Número da página de registros que se quer carregar.
		*	$filter -> Contém todos os filtros utilizados pelo usuário para a fazer a busca no banco de dados.
		*/
		public function get_nota_falta($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND t.Ativo = 1 ";

			if ($Id === FALSE)
			{
				$filtros = "";//$this->filtros($filter);
				
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM Turma u WHERE TRUE ".$filtros.") AS Size, t.Id, 
					t.Nome as Nome_turma, t.Ativo 
					
					FROM Turma t 
					WHERE TRUE ".$Ativos."".$filtros."
					ORDER BY t.Id ASC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT t.Id, t.Nome as Nome_turma, t.Ativo,
				DATE_FORMAT(t.Data_registro, '%d/%m/%Y') as Data_registro 
					FROM Turma t 
				WHERE TRUE ".$Ativos." AND t.Id = ".$this->db->escape($Id)."");
			return $query->row_array();
		}
	}
?>