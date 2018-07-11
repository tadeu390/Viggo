<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DA TURMA DE ENSINO.
	*/
	class Disc_turma_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS MÉTODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADES OU UMA MODALIDADE ESPECÍFICA.
		*
		*	$id -> Quando passado algum valor inteiro, retorna todos os registros de uma determinada turma.
		*/
		public function get_disc_turma_header($id, $curso_id = false)
		{
			$query = $this->db->query("
				SELECT t.Id, dc.Curso_id, p.Modalidade_id, t.Nome as Nome_turma 
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
				INNER JOIN Disc_curso dc ON dc.Id = dt.Disc_curso_id 
 				INNER JOIN Periodo_letivo p ON p.Id = dt.Periodo_letivo_id 
                INNER JOIN Modalidade md ON md.Id = p.Modalidade_id 
                WHERE  t.Id = ".$this->db->escape($id)."  
                GROUP BY t.Id, dc.Curso_id, p.Modalidade_id, t.Nome");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADES OU UMA MODALIDADE ESPECÍFICA.
		*
		*	$id -> Quando passado algum valor inteiro, retorna todos os registros de uma determinada turma.
		*/
		public function get_disc_turma_disciplina($id, $curso_id = false)
		{
			$query = $this->db->query("
				SELECT d.Id AS Disciplina_id, dt.Categoria_id, 
				dt.Professor_id, d.Nome as Nome_disciplina, dt.Turma_id, dc.Id as Disc_curso_id 
				FROM Disciplina d 
				INNER JOIN Disc_curso dc ON dc.Disciplina_id = d.Id 
                LEFT JOIN Disc_turma dt ON dc.Id = dt.Disc_curso_id 
                	AND dt.Turma_id = ".$this->db->escape($id)." 
                WHERE dc.Curso_id = ".$curso_id." 
                GROUP BY d.Id, dt.Categoria_id, dt.Professor_id, d.Nome");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADES OU UMA MODALIDADE ESPECÍFICA.
		*
		*	$id -> Quando passado algum valor inteiro, retorna todos os registros de uma determinada turma.
		*/
		public function get_disc_turma_aluno($id, $curso_id = false)
		{
			$query = $this->db->query("
				SELECT u.Nome as Nome_aluno, a.Id as Aluno_id, m.Sub_turma  
				FROM Disc_turma dt 
				INNER JOIN Matricula m ON  m.Id = dt.Matricula_id 
				INNER JOIN Aluno a ON m.Aluno_id = a.Id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
                WHERE dt.Turma_id = ".$this->db->escape($id)." 
                GROUP by u.Nome");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O CURSO DE UMA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja saber o curso.
		*/
		public function get_curso_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT dc.Curso_id FROM Disc_curso dc 
				INNER JOIN Disc_turma dt ON dc.Id = dt.Disc_curso_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." LIMIT 1");

			return $query->row_array();
		}
		/*
		*	RESPONSÁVEL POR FILTRAR OS ALUNOS NA TELA DE TURMA A SER CRIADA/EDITADA.
		*
		*	$filtros -> Contém todos os filtros.
		*/
		public function get_disc_turma_filtro($filtros)
		{
			$f = $this->filtros($filtros);
			$query = $this->db->query("
				SELECT a.Usuario_id, a.Id as Aluno_id, u.Nome as Nome_aluno 
				FROM Usuario u 
				INNER JOIN Aluno a ON u.Id = a.Usuario_id 
				LEFT JOIN Matricula m ON a.Id = m.Aluno_id 
				LEFT JOIN Disc_turma dt ON dt.Matricula_id = m.Id 
				WHERE /*Numero IS NOT NULL*/ u.Ativo = 1 ".$f."
				GROUP BY a.Usuario_id, a.Id, u.Nome");
			return $query->result_array();
		}
		/*
		*	RESPONSÁVEL POR MONTAR A QUERY DE FILTROS 
		*
		*	$filter -> Contém todos os filtros.
		*/
		public function filtros($filter)
		{
			$filtros = "";
			if(!empty($filter))
			{
				if(isset($filter['turma_id']) && $filter['turma_id'] != 0)
					$filtros = " AND dt.Turma_id = ".$this->db->escape($filter['turma_id']);
				if(isset($filter['nome']) && $filter['nome'] != '0')
					$filtros = $filtros." AND u.Nome LIKE ".$this->db->escape($filter['nome']."%");
				if(isset($filter['data_registro_inicio']) && $filter['data_registro_inicio'] != 0)
					$filtros = $filtros." AND u.Data_registro >= STR_TO_DATE(".$this->db->escape($filter['data_registro_inicio']).",'%Y-%m-%d')";
				if(isset($filter['data_registro_fim']) && $filter['data_registro_fim'] != 0)
					$filtros = $filtros." AND u.Data_registro <= STR_TO_DATE(".$this->db->escape($filter['data_registro_fim']).",'%Y-%m-%d')";
			}
			return $filtros;
		}
	}
?>