<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE TURMAS.
	*/
	class Turma_model extends CI_Model 
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
		public function get_turma($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE)
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
		/*!
		*	RESPONSÁVEL POR "APAGAR" UA TURMA DO BANCO DE DADOS.
		*
		*	$id -> Id da turma a ser "apagada".
		*/
		public function deletar($Id)
		{
			return $this->db->query("
				UPDATE Turma SET Ativo = 0 
				WHERE Id = ".$this->db->escape($Id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA TURMA E EM SEGUIDA RETORNA A SUA ID.
		*
		*	$data -> Contém todos os dados da turma.
		*/
		public function set_turma($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Turma',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Turma', $data);
			}
			return $this->get_turma_por_nome($data['Nome'], $data['Modalidade_id'])['Id'];
		}
		/*!
		*	RESPONSÁVEL POR RETORAR UM TURMA DE ACORDO COM O NOME E MODALIDADE.
		*
		*	$nome -> Nome da turma a ser cadastrada/editada.
		*	$modalidade_id -> Modalidade ensino especificado para a turma.
		*/
		public function get_turma_por_nome($nome, $modalidade_id)
		{
			$query = $this->db->query("
				SELECT t.Id FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
				INNER JOIN Periodo_letivo p ON dt.Periodo_letivo_id = p.Id 
				INNER JOIN Modalidade m ON m.Id = p.Id and m.Id = ".$this->db->escape($modalidade_id)."
				WHERE UPPER(t.Nome) = UPPER(".$this->db->escape($nome).") ");
			
			return $query->row_array();
		}
	}
?>