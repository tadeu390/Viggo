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
		public function get_turma($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE, $ordenacao = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND t.Ativo = 1 ";

			if ($Id === FALSE)
			{
				$filtros = "";//$this->filtros($filter);
				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];

				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM Turma u WHERE TRUE ".$filtros.") AS Size, t.Id, 
					t.Nome as Nome_turma, t.Ativo as Ativo_turma, dt.Periodo_letivo_id, p.Periodo, m.Nome as Nome_modalidade 
					FROM Turma t 
					INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
					INNER JOIN Periodo_letivo p ON dt.periodo_letivo_id = p.Id 
					INNER JOIN Modalidade m ON p.Modalidade_id = m.Id 
					WHERE TRUE ".$Ativos."".$filtros." GROUP BY t.Id, t.Nome 
					".$order." ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT t.Id, t.Nome as Nome_turma, t.Ativo,  
				c.Id AS Curso_id, 
				DATE_FORMAT(t.Data_registro, '%d/%m/%Y') as Data_registro, dt.Periodo_letivo_id, CONCAT(p.Periodo, ' / ', m.Nome) as Pe_modi  
					FROM Turma t 
					INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
					INNER JOIN Disc_curso dc ON dc.Id = dt.Disc_curso_id 
					INNER JOIN Curso c ON c.Id = dc.Curso_id 
					INNER JOIN Periodo_letivo p ON dt.periodo_letivo_id = p.Id 
					INNER JOIN Modalidade m ON p.Modalidade_id = m.Id  
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
			$modalidade_id = $data['Modalidade_id'];
			
			unset($data['Modalidade_id']);
			unset($data['Disc_to_save']);
			unset($data['Aluno_to_save']);
			
			if(empty($data['Id']))
				$this->db->insert('Turma',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Turma', $data);
			}
			return $this->get_turma_por_nome($data['Nome'])['Id'];
		}
		/*!
		*	RESPONSÁVEL POR RETORAR UM TURMA DE ACORDO COM O NOME.
		*
		*	$nome -> Nome da turma a ser cadastrada/editada.
		*	$modalidade_id -> Modalidade ensino especificado para a turma.
		*/
		public function get_turma_por_nome($nome)
		{
			$query = $this->db->query("
				SELECT Id FROM Turma 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($nome).") ORDER BY Id DESC LIMIT 1");
			
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR O NOME DA TURMA, OU SEJA, VERIFICA SE O NOME JÁ ESTÁ EM USO PARA 
		*	UMA DETERMINADA MODALIDADE EM UM DETERMINADO PERÍODO
		*
		*	$id -> Id da turma.
		*	$nome -> Nome da turma.
		*	$modalidade_id -> Modalidade selecionada para a turma.
		*/
		public function nome_valido($id, $nome, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT t.Id 
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
				WHERE UPPER(t.Nome) = UPPER(".$this->db->escape($nome).") AND
			    DT.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)."
				GROUP BY 1");

			$query = $query->row_array();

			if(empty($query['Id']) || $query['Id'] == $id)
				return "valido";
			return "invalido";
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE TURMAS DE UM DETERMINADO CURSO CUJO PERÍODO LETIVO SEJA
		*	ANTERIOR AO PASSADO COMO PARÂMETRO. APENAS PARA MONTAR O COMBO BOX DE TURMA PARA USAR COMO FILTRO.
		*
		*	$curso_id -> Curso da turma.
		*	$periodo_letivo_id -> Id do período letivo das turmas a serem buscadas.
		*/
		public function get_turma_cp($curso_id, $modalidade_id,$periodo_letivo_id)
		{

			$query = $this->db->query("
				SELECT t.Id, t.Nome as Nome_turma, CONCAT(p.Periodo, ' - ', m.Nome) as Pe_modi  
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
				INNER JOIN Disc_curso dc ON dc.Id = dt.Disc_curso_id 
				INNER JOIN Periodo_letivo p ON dt.Periodo_letivo_id = p.Id 
				INNER JOIN Modalidade m ON p.Modalidade_id = m.Id 
				WHERE dc.Curso_id = ".$this->db->escape($curso_id)." AND 
				dt.Periodo_letivo_id < ".$this->db->escape($periodo_letivo_id)." AND 
				m.Id = ".$this->db->escape($modalidade_id)." 
                GROUP BY 1,2");
			return $query->result_array();
		}
	}
?>


