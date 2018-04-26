<?php
	class Grupo_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		
		public function get_grupo($id = FALSE)///usado para o cadastro de usuario
		{
			if ($id === FALSE)
			{
				$query = $this->db->query("SELECT Id, Nome AS Nome_grupo FROM Grupo WHERE Ativo = 1");
				return $query->result_array();
			}

			$query =  $this->db->query("SELECT Id, Nome AS Nome_grupo FROM Grupo WHERE Ativo = 1 
										WHERE Id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		
		public function get_grupo_tela($id = FALSE, $page = FALSE)
		{
			if ($id === FALSE)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Grupo) AS Size, Id, Nome AS Nome_grupo, Ativo 
						FROM Grupo 
					ORDER BY Data_registro DESC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("SELECT Id, Nome AS Nome_grupo, Ativo FROM Grupo 
										WHERE Id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Grupo SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		
		public function get_grupo_acesso($id)
		{
			$query = $this->db->query("
				SELECT m.Nome AS Nome_modulo, a.Grupo_id, m.Id AS modulo_id,
				a.Criar, a.Visualizar, a.Atualizar, a.Remover, a.Id as Acesso_id  
				FROM Modulo m 
				LEFT JOIN Acesso a ON m.id = a.Modulo_id AND a.Usuario_id = ".$this->db->escape($id)."
				WHERE a.Usuario_id = ".$this->db->escape($id)." OR a.Usuario_id IS NULL");
			return $query->result_array();
		}
		
		public function set_grupo($data)
		{
			if(empty($data['Id']))
			{
				$this->db->insert('Grupo',$data);
				$query = $this->db->query("SELECT Id FROM Grupo ORDER BY Id DESC LIMIT 1");
				return $query->row_array()['Id'];
			}
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Grupo', $data);
				return $data['Id'];
			}
		}
		
		public function set_grupo_acesso($data)
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