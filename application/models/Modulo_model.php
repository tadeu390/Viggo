<?php
	class Modulo_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		
		public function get_modulo()//usado apenas para montar o menu
		{
			$CI = get_instance();
			$CI->load->model("Account_model");

			$query = $this->db->query("SELECT mo.nome as nome_modulo, mo.id as id_modulo,
				mo.menu_id, mo.url as url_modulo, mo.icone 
					FROM modulo mo 
				INNER JOIN acesso a ON mo.id = a.modulo_id 
				WHERE mo.ativo = 1 AND grupo_id = ".$CI->Account_model->session_is_valid()['id']." 
				AND a.visualizar = 1 
				ORDER BY mo.ordem");

			return $query->result_array();
		}
		
		public function get_modulo_tela($id = false, $page = false)
		{
			if($id === false)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;

				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";

				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  modulo) AS size, me.nome AS nome_menu, 
					mo.descricao, mo.ativo, mo.ordem, 
					DATE_FORMAT(mo.data_registro, '%d/%m/%Y') as data_registro, 
					mo.nome as nome_modulo, mo.id, mo.url as url_modulo, mo.icone 
						FROM menu me 
					RIGHT JOIN modulo mo ON me.id = mo.menu_id 
					ORDER BY mo.data_registro DESC ".$pagination."");

				return $query->result_array();
			}
			$query = $this->db->query("
				SELECT me.nome AS nome_menu, mo.descricao, mo.ativo, mo.ordem, 
				DATE_FORMAT(mo.data_registro, '%d/%m/%Y') as data_registro,
				mo.nome as nome_modulo, mo.id, mo.url as url_modulo, mo.menu_id, mo.icone 
					FROM menu me 
				RIGHT JOIN modulo mo ON me.Id = mo.menu_id 
				WHERE mo.id = ".$this->db->escape($id)." 
				ORDER BY mo.ordem, me.ordem");

			return $query->row_array();
		}
		
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE modulo SET ativo = 0 
				WHERE id = ".$this->db->escape($id)."");
		}
		
		public function set_modulo($data)
		{
			if($data['menu_id'] == "0")
				$data['menu_id'] = null;
			if(empty($data['id']))
				return $this->db->insert('modulo',$data);
			else
			{
				$this->db->where('id', $data['id']);
				return $this->db->update('modulo', $data);
			}
		}
	}
?>