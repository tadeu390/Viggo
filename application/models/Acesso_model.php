<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS PERMISSÕES DE USUÁRIO OU DE GRUPO DE USUÁRIOS
	*/
	class Acesso_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR OS MODULOS E AS PERMISSÕES POR USUÁRIO PARA PODER VISUALIZAR
			QUAIS MÓDULO O USUÁRIO TEM ACESSO

			$id -> id do usuário
		*/
		public function get_acesso($id)
		{
			$query = $this->db->query("
				SELECT m.Nome AS Nome_modulo, m.Id AS Modulo_id, a.Usuario_id, 
				a.Criar, a.Ler, a.Atualizar, a.Remover, a.Id as Acesso_id  
				FROM Modulo m 
				LEFT JOIN Acesso a ON m.id = a.Modulo_id AND a.Usuario_id = ".$this->db->escape($id)."
				WHERE a.Usuario_id = ".$this->db->escape($id)." OR a.Usuario_id IS NULL ORDER BY m.Id");
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
		/*
			RESPONSÁVEL POR RETORNAR TODOS OS MÓDULOS QUE O USUÁRIO LOGADO PODE VISUALIZAR
		*/
		public function get_modulo_acesso()//usado apenas para montar o menu
		{
			$CI = get_instance();
			$CI->load->model("Account_model");

			$query = $this->db->query("SELECT mo.Nome as Nome_modulo, mo.Id as Id_modulo,
				mo.Menu_id, mo.Url as Url_modulo, mo.Icone 
					FROM Modulo mo 
				INNER JOIN Acesso a ON mo.Id = a.Modulo_id 
				WHERE mo.Ativo = 1 AND a.Usuario_id = ".$CI->Account_model->session_is_valid()['id']." 
				AND a.Ler = 1 
				ORDER BY mo.Ordem");

			return $query->result_array();
		}
	}
?>