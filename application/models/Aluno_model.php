<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS ESPECÍFICOS DO ALUNO.
	*/
	class Aluno_model extends CI_Model 
	{
		/*CAREGA O DRIVE DO BANCO DE DADOS*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR OS DADOS DE UM ALUNO DE ACORDO COM UM ID DE USUÁRIO.
		*
		*	$id -> Id de usuário do aluno.
		*/
		public function get_aluno($Id)
		{
			$query =  $this->db->query("
				SELECT a.Id 
					FROM Aluno a 
	
				WHERE a.Usuario_id = ".$this->db->escape($Id)."");
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
	}
?>