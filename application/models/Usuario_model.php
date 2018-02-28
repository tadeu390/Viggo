<?php

	class Usuario_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		
		public function get_usuario($id = FALSE, $page = false)
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
					SELECT (SELECT count(*) FROM usuario) AS size, u.id, 
					u.nome as nome_usuario, u.email, u.ativo, g.nome AS nome_grupo 
						FROM usuario u 
					LEFT JOIN grupo g ON u.grupo_id = g.Id 
					ORDER BY u.data_registro DESC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT u.id, u.nome as nome_usuario, u.email, u.senha, u.ativo, 
				DATE_FORMAT(u.data_registro, '%d/%m/%Y') as data_registro, 
				DATE_FORMAT(u.ultimo_acesso, '%d/%m/%Y - %r') as ultimo_acesso, 
				g.nome AS nome_grupo, u.grupo_id  
					FROM usuario u 
				LEFT JOIN grupo g ON u.grupo_id = g.id
				WHERE u.id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE usuario SET ativo = 0 
				WHERE id = ".$this->db->escape($id)."");
		}
		
		public function set_usuario($data)
		{
			if(empty($data['id']))
				return $this->db->insert('usuario',$data);
			else
			{
				$this->db->where('id', $data['id']);
				return $this->db->update('usuario', $data);
			}
		}
		
		public function get_grupo($id)
		{
			$query = $this->db->query("
				SELECT grupo_id FROM usuario 
				WHERE id = ".$this->db->escape($id)."");

			return $query->row_array()['grupo_id'];
		}
		
		public function email_valido($email,$id = false)
		{
			$query = $this->db->query("
				SELECT email FROM usuario 
				WHERE email = ".$this->db->escape($email)."");

			$query = $query->row_array();
			
			//verifica se foi inserido o no formulario o email atual, logicamente a busca anterior tera algum resultado, porem o emeil encontrado e o que esta cadastrado para o usuario que esta sendo editado

			if(!empty($query) && $this->get_usuario($id)['email'] != $query['email'])
				return "invalido";
			return "valido";
		}
	}
?>