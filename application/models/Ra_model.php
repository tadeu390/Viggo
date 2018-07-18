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
					SELECT (SELECT count(*) FROM  Inscricao) AS Size, i.Id, i.Aluno_id AS Aluno_id, 
					i.Curso_id, i.Ativo, u.Nome AS Nome_aluno, c.Nome AS Nome_curso, 
					u.Id AS Usuario_id, m.Nome as Nome_modalidade, m.Id as Modalidade_id, 
					CASE #SE NAO HOUVER NENHUMA OCORRENCIA DE RENOVACAO DA INSCRICAO NA TABELA DE RENOVACAO, ENTAO CRIAR MATRICULA
						WHEN (SELECT MAX(Id) FROM Renovacao_matricula WHERE Inscricao_id = i.Id) IS NULL THEN
							'matricular'
							#SE HOUVER UM PERÍODO LETIVO MAIS RECENTE QUE O ÚLTIMO NA TABELA DE RENOVAÇÃO MATRÍCULA PRA UMA DETERMINADA INSCRICAO, ENTÃO RENOVAR 
						WHEN (SELECT MAX(Id) FROM Periodo_letivo WHERE Modalidade_id = m.Id AND Ativo = 1) > (SELECT MAX(Periodo_letivo_id) FROM Renovacao_matricula WHERE Inscricao_id = i.Id) THEN 
							'renovar'
					END AS Status,
					CASE 
						WHEN (SELECT mm.Id FROM Matricula mm 
								INNER JOIN Disc_turma dt ON mm.Disc_turma_id = dt.Id 
								INNER JOIN Inscricao ii ON mm.Inscricao_id = ii.Id 
								WHERE ii.Aluno_id = a.Id 
								AND dt.Periodo_letivo_id = i.Periodo_letivo_id LIMIT 1) IS NULL THEN 
							'editar'
						ELSE 'bloqueado'
					END AS Editar 
					FROM Inscricao i 
					INNER JOIN Aluno a ON a.Id = i.Aluno_id 
					INNER JOIN Usuario u ON u.Id = a.Usuario_id 
					INNER JOIN Curso c ON c.Id = i.Curso_id 
					INNER JOIN Periodo_letivo p ON p.Id = i.Periodo_letivo_id 
					INNER JOIN Modalidade m ON m.Id = p.Modalidade_id 
					WHERE TRUE ".$Ativos." 
					ORDER BY i.Data_registro ASC ".$pagination."");

				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT i.Id, i.Aluno_id AS Aluno_id, 
				i.Curso_id, i.Ativo, u.Nome AS Nome_aluno, c.Nome AS Nome_curso, 
				u.Id AS Usuario_id, m.Nome as Nome_modalidade, m.Id as Modalidade_id, rm.Id as Renovacao_matricula_id, i.Periodo_letivo_id   
				FROM Inscricao i 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
				INNER JOIN Curso c ON c.Id = i.Curso_id 
				INNER JOIN Periodo_letivo p ON p.Id = i.Periodo_letivo_id 
				INNER JOIN Modalidade m ON m.Id = p.Modalidade_id 
				LEFT JOIN Renovacao_matricula rm ON rm.Inscricao_id = i.Id 
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
			{
				$CI = get_instance();
				$CI->load->model("Modalidade_model");
				
				$data['Periodo_letivo_id'] = $CI->Modalidade_model->get_periodo_por_modalidade($data['Modalidade_id'])['Id'];

				unset($data['Modalidade_id']);

				return $this->db->insert('Inscricao',$data);
			}
			else
			{	
				unset($data['Modalidade_id']);
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
			$CI = get_instance();
				$CI->load->model("Modalidade_model");
			if(empty($Matricula['Id']))	
				$last_periodo_letivo_id = $CI->Modalidade_model->get_periodo_por_modalidade($Matricula['Modalidade_id'])['Id'];
			else //se estiver editando uma inscrição considera o período letivo dela e não o último.
				$last_periodo_letivo_id = $this->get_ra(FALSE, $Matricula['Id'], false, false)['Periodo_letivo_id'];

			$query = $this->db->query("
				SELECT i.Id 
				FROM Inscricao i 
				WHERE i.Aluno_id = ".$this->db->escape($Matricula['Aluno_id'])." AND 
				i.Curso_id = ".$this->db->escape($Matricula['Curso_id'])." AND 
				i.Periodo_letivo_id = ".$last_periodo_letivo_id."");
			
			return $query->row_array();
		}
	}
?>