<?php
	/*
		A CLASSE ABAIXO RETORNA INFORMAÇÕES DE CONFIGURAÇÕES DO SISTEMA, CADASTRA E ATUALIZA 
		DADOS DE CONFIGURAÇÕES
	*/
	class Configuracoes_email_model extends CI_Model {
		
		/*
			CARREGA O DRIVER DO BANCO DE DADOS
		*/
		public function __construct()
		{
			$this->load->database();
		}

		//ESTE MÉTODO É RESPONSÁVEL POR RETORNAR INFORMAÇÕES DE CONFIGURAÇÃO DO SISTEMA
		public function get_configuracoes_email()
		{
			$query = $this->db->query("
				SELECT * FROM 
				Settings_email");
			return $query->row_array();
		}

		//ESTE MÉTODO É RESPONSÁVEL POR CADASTRAR E ATUALIZAR DADOS DE CONFIGURAÇÕES DO SISTEMA
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