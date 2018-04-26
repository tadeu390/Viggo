<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS PERMISSÕES DE USUÁRIO OU DE GRUPO DE USUÁRIOS
	*/
	class Acesso_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR OS MODULOS E AS PERMISSÕES POR USUÁRIO

			$id -> id do usuário
		*/
		public function get_acesso($id)
		{
			$query = $this->db->query("
				SELECT m.Nome AS Nome_modulo, m.Id AS Modulo_id,
				a.Criar, a.Ler, a.Atualizar, a.Remover, a.Id as Acesso_id  
				FROM Modulo m 
				LEFT JOIN Acesso a ON m.id = a.Modulo_id AND a.Usuario_id = ".$this->db->escape($id)."
				WHERE a.Usuario_id = ".$this->db->escape($id)." OR a.Usuario_id IS NULL");
			return $query->result_array();
		}
		/*
			RESPONSÁVEL POR ALTERAR O STATUS DAS PERMISSÕES DE UM MÓDULO POR USUÁRIO

			$data -> contém todos os dados de permissão de um módulo por usuário
		*/
		public function set_acesso($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Acesso',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Acesso', $data);
			}
		}
	}
?>