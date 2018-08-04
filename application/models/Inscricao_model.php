<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS MATRICULAS DO SISTEMA.
	*/
	class Inscricao_model extends Geral_model 
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
		public function get_inscricao($Ativo = FALSE, $id = false, $page = false, $filter = false, $ordenacao = false)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{
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
					SELECT (SELECT count(*) FROM  Inscricao) AS Size, i.Id, i.Aluno_id AS Aluno_id, 
					DATE_FORMAT(i.Data_registro, '%d/%m/%Y') as Data_registro, 
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
							'editar_apagar'
						ELSE 'bloqueado'
					END AS Editar_apagar 
					FROM Inscricao i 
					INNER JOIN Aluno a ON a.Id = i.Aluno_id 
					INNER JOIN Usuario u ON u.Id = a.Usuario_id 
					INNER JOIN Curso c ON c.Id = i.Curso_id 
					INNER JOIN Periodo_letivo p ON p.Id = i.Periodo_letivo_id 
					INNER JOIN Modalidade m ON m.Id = p.Modalidade_id 
					WHERE TRUE ".$Ativos." 
					".str_replace("'", "", $this->db->escape($order))." ".$pagination."");

				return $query->result_array();
			}

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
							'editar_apagar'
						ELSE 'bloqueado'
					END AS Editar_apagar,rm.Id as Renovacao_matricula_id, i.Periodo_letivo_id  
					FROM Inscricao i 
					INNER JOIN Aluno a ON a.Id = i.Aluno_id 
					INNER JOIN Usuario u ON u.Id = a.Usuario_id 
					INNER JOIN Curso c ON c.Id = i.Curso_id 
					INNER JOIN Periodo_letivo p ON p.Id = i.Periodo_letivo_id 
					INNER JOIN Modalidade m ON m.Id = p.Modalidade_id 
					LEFT JOIN Renovacao_matricula rm ON i.Id = rm.Inscricao_id 
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
				DELETE FROM Inscricao  
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA MATRICULA NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da matricula.
		*/
		public function set_inscricao($data)
		{
			$CI = get_instance();
			$CI->load->model("Modalidade_model");
			$data['Periodo_letivo_id'] = $CI->Modalidade_model->get_periodo_por_modalidade($data['Modalidade_id'])['Id'];
			if(empty($data['Id']))
			{
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
				$last_periodo_letivo_id = $this->get_inscricao(FALSE, $Matricula['Id'], false, false)['Periodo_letivo_id'];

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