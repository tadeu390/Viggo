<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE CONFIGURAÇÕES DE E-MAIL DO SISTEMA.
	*/
	class Configuracoes_email_model extends CI_Model 
	{
		/*
			CARREGA O DRIVER DO BANCO DE DADOS
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR AS CONFIGURAÇÕES DE E-MAIL DO SISTEMA.
		*/
		public function get_configuracoes_email()
		{
			$query = $this->db->query("
				SELECT * FROM 
				Settings_email");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR E ATUALIZAR DADOS DE CONFIGURAÇÕES DE E-MAIL DO SISTEMA.
		*
		*	$data -> Contém as informações e-mail.
		*/
		public function set_configuracoes_email($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Settings_email',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Settings_email', $data);
			}
		}
	}
?>