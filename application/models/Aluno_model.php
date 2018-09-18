<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS ESPECÍFICOS DO ALUNO.
	*/
	class Aluno_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR OS DADOS DE UM ALUNO DE ACORDO COM UM ID DE USUÁRIO.
		*
		*	$id -> Id de usuário do aluno.
		*/
		public function get_aluno($id = FALSE)
		{
			if($id === FALSE)
			{
				$query =  $this->db->query("
					SELECT a.Id, u.Nome as Nome_aluno, CONCAT(a.Id,' - ', u.Nome) as Nome_ra, a.Usuario_id  
						FROM Aluno a 
						INNER JOIN Usuario u ON a.Usuario_id = u.Id 
					WHERE u.Ativo = 1 ORDER BY u.Nome ASC");
				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT a.Id 
					FROM Aluno a 
	
				WHERE a.Usuario_id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		/*!
		*	REPONSÁVEL POR CADASTRAR OU ATUALIZAR OS DADOS DE UM ALUNO.
		*
		*	$data -> Contem os dados do aluno.
		*/
		public function set_aluno($data)
		{
			if(empty($this->get_aluno($data['Usuario_id'])))
			{
				$this->db->insert('Aluno',$data);
			}
			else
			{
				$this->db->where('Usuario_id', $data['Usuario_id']);
				$this->db->update('Aluno', $data);
			}
		}
		/*!
			RESPONSÁVEL POR RETORNAR TODOS OS ALUNOS MATRICULADOS EM UMA DETERMINADA DISCIPLINA DE UMA 
			DETERMINADA TURMA, DE ACORDO COM O HORÁRIO, PODENDO RETORNAR OU A TURMA TODA OU UMA SUBTURMA.
		*/
		public function get_alunos($disciplina_id, $turma_id, $sub_turma = FALSE)  //nome anterior get_alunos_chamada
		{
			if($sub_turma !== FALSE)
				$sub_turma = "AND dh.Sub_turma = ".$this->db->escape($sub_turma);
			else 
				$sub_turma = "";
			$query = $this->db->query("
				SELECT u.Nome AS Nome_aluno, m.Id AS Matricula_id, 
				dh.Sub_turma, a.Id AS Aluno_id
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id  
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
				LEFT JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id AND (m.Sub_turma = dh.Sub_turma OR dh.Sub_turma = 0)
				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." ".$sub_turma." GROUP BY #dh.Sub_turma, 
				m.Id");
			
				return $query->result_array();
		}
	}
?>