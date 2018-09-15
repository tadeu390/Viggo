<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS MATRÍCULAS DE CADA ALUNO
	*	EM CADA DISCIPLINA.
	*/
	class Matricula_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}

		public function set_matricula($data)
		{
			


			if(empty($data['Id']))
				return $this->db->insert('Modalidade',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Modalidade', $data);
			}
		}

		public function get_matriculas($aluno_id, $turma_id)
		{
			$query = $this->db->query("
				SELECT m.Id AS Matricula_id FROM Matricula m 
				INNER JOIN Disc_turma dt ON m.Disc_turma_id = dt.Id 
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND 
				a.Id = ".$this->db->escape($aluno_id)."");

			return $query->result_array();
		}
	}
?>