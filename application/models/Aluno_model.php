<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS ESPECÍFICOS DO ALUNO.
	*/
	class Aluno_model extends CI_Model 
	{
		/*CAREGA O DRIVE DO BANCO DE DADOS*/
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR UMA SENHA DE ACORDO COM UM ID DE USUARIO OU RETORNA UMA LISTA DE SENHA
		 	SE NÃO FOR PASSADO ARGUMENTO NA CHAMADA DO MÉTODO

		 	$id -> id do aluno
		 */
		public function get_aluno($Id = FALSE)
		{
			$query =  $this->db->query("
				SELECT a.Id, a.Matricula 
					FROM Aluno a 
	
				WHERE a.Usuario_id = ".$this->db->escape($Id)."");
			return $query->row_array();
		}
		/*
			REPONSÁVEL POR CADASTRAR OU ATUALIZAR OS DADOS DE UM ALUNO

			$data -> Contem os dados do aluno
		*/
		public function set_aluno($data)
		{
			if(empty($this->get_aluno($data['Usuario_id'])))
				$this->db->insert('Aluno',$data);
			else
			{
				$this->db->where('Usuario_id', $data['Usuario_id']);
				$this->db->update('Aluno', $data);
			}
		}
		/*
			RESPONSÁVEL POR RETORNAR UM ALUNO DE ACORDO COM A MATRÍCULA

			$matricula -> matricula do aluno
		*/
		public function get_aluno_por_matricula($matricula)
		{
			$query = $this->db->query("
				SELECT * FROM Aluno WHERE Matricula = ".$this->db->escape($matricula)."");
			return $query->row_array();
		}
	}
?>