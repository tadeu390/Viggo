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
				$query = $this->db->query("SELECT id, nome AS nome_grupo FROM grupo WHERE ativo = 1");
				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT id, nome AS nome_grupo FROM grupo WHERE ativo = 1 
				WHERE id = ".$this->db->escape($id)."");

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
					SELECT (SELECT count(*) FROM  grupo) AS size, id, nome AS nome_grupo, ativo  
						FROM grupo ORDER BY data_registro DESC ".$pagination."");

				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT id, nome AS nome_grupo, ativo FROM grupo 
				WHERE id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE grupo SET ativo = 0 
				WHERE id = ".$this->db->escape($id)."");
		}
		
		public function get_grupo_acesso($id)
		{
			$query = $this->db->query("
				SELECT m.nome AS nome_modulo, a.grupo_id, m.id AS modulo_id,
				a.criar, a.visualizar, a.atualizar, a.apagar, a.id as acesso_id  
					FROM modulo m 
				LEFT JOIN acesso a ON m.id = a.modulo_id AND a.grupo_id = ".$this->db->escape($id)."
				WHERE a.grupo_id = ".$this->db->escape($id)." OR a.grupo_id IS NULL");

			return $query->result_array();
		}
		
		public function set_grupo($data)
		{
			if(empty($data['id']))
			{
				$this->db->insert('grupo',$data);
				$query = $this->db->query("SELECT id FROM grupo ORDER BY id DESC LIMIT 1");
				return $query->row_array()['id'];
			}
			else
			{
				$this->db->where('id', $data['id']);
				$this->db->update('grupo', $data);
				return $data['id'];
			}
		}
		
		public function set_grupo_acesso($data)
		{
			if(empty($data['id']))
				$this->db->insert('acesso',$data);
			else
			{
				$this->db->where('id', $data['id']);
				$this->db->update('acesso', $data);
			}
		}
	}
?>