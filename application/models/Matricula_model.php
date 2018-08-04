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
	}
?>