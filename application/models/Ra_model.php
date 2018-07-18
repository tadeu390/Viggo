<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS MATRICULAS DO SISTEMA.
	*/
	class Ra_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MATRICULAS OU UMA MATRICULA ESPECÍFICA.
		*	
		*	$Ativo -> Quando passado "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma matricula específica.
		*	$page-> Número da página de registros que se quer carregar.
		*/
		public function get_ra($Ativo = FALSE, $id = false, $page = false, $filter = false)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Inscricao) AS Size, i.Id AS Id, i.Aluno_id AS Aluno_id, i.Curso_id AS Curso_id, i.Modalidade_id AS Modalidade_id, i.Ativo AS Ativo, u.Nome AS Nome_usuario, c.Nome AS Nome_curso, m.Nome AS Nome_modalidade, pl.Id AS Periodo_letivo_id 
					FROM Inscricao i 
					INNER JOIN Aluno a ON a.Id = i.Aluno_id 
					INNER JOIN Usuario u ON u.Id = a.Usuario_id 
					INNER JOIN Curso c ON c.Id = i.Curso_id 
					INNER JOIN Modalidade m ON m.Id = i.Modalidade_id 
					INNER JOIN Periodo_letivo pl ON pl.Modalidade_id = m.Id 
					WHERE TRUE ".$Ativos." 
					ORDER BY i.Data_registro ASC ".$pagination."");
				
				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT (SELECT count(*) FROM  Inscricao) AS Size, i.Id AS Id, i.Aluno_id AS Aluno_id, i.Curso_id AS Curso_id, i.Modalidade_id AS Modalidade_id, i.Ativo AS Ativo, u.Nome AS Nome_usuario, c.Nome AS Nome_curso, m.Nome AS Nome_modalidade, pl.Id AS Periodo_letivo_id, u.Id AS Usuario_id 
				FROM Inscricao i
				INNER JOIN Aluno a ON a.Id = i.Aluno_id
				INNER JOIN Usuario u ON u.Id = a.Usuario_id
				INNER JOIN Curso c ON c.Id = i.Curso_id
				INNER JOIN Modalidade m ON m.Id = i.Modalidade_id
				INNER JOIN Periodo_letivo pl ON pl.Modalidade_id = m.Id
				WHERE i.Id = ".$this->db->escape($id)." ".$Ativos."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA MATRICULA DO BANCO DE DADOS.
		*
		*	$id -> Id da matricula a ser "apagada".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Inscricao SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA MATRICULA NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da matricula.
		*/
		public function set_ra($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Inscricao',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Inscricao', $data);
			}
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA INSCRIÇÃO DA MATRICULA DE UM ALUNO NO PERIODO LETIVO MAIS RECENTE DO CURSO.
		*
		*	$Matricula -> Matricula do aluno a ser retornada a inscrição.
		*/
		public function get_inscricao_por_aluno($Matricula)
		{
			$query = $this->db->query("
				SELECT i.Id 
				FROM Inscricao i 
				WHERE i.Aluno_id = ".$this->db->escape($Matricula['Aluno_id'])." AND i.Curso_id = ".$this->db->escape($Matricula['Curso_id'])." AND 
				i.Modalidade_id = ".$this->db->escape($Matricula['Modalidade_id'])."");
			
			return $query->row_array();
		}
	}
?>