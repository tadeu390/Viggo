<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES REFERENTE AOS ACESSOS REALIZADOS POR CADA USUÁRIO.
	*/
	class Logs_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR CADA ACESSO DE UM DETERMINADO USUÁRIO.
		*
		*	$Usuario_id -> Id do usuário que está logando em sua conta.
		*/
		public function set_log($Usuario_id)
		{
			$data = array(
				'Ativo' => 1,
				'Usuario_id' => $Usuario_id
			);
			$query = $this->db->insert('Log',$data);
		}
		/*!
		*	RESPONSÁVEL POR LEVANTAR O ÚLTIMO ACESSO REALIZADO POR UM DETERMINADO USUÁRIO.
		*
		*	$Usuario_id -> Id do usuário.
		*/
		public function get_last_access_user($Usuario_id)
		{
			$query = $this->db->query("
				SELECT DATE_FORMAT(l.Data_registro, '%d/%m/%Y') as Data_registro FROM Log l 
				WHERE l.Usuario_id = ".$this->db->escape($Usuario_id)." ORDER BY Id DESC LIMIT 1");

			return $query->row_array();
		}
	}
?>