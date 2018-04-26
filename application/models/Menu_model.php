<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS MENUS DO SISTEMA
	*/
	class Menu_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR UMA LISTA DE MENUS OU UM MENU ESPECÍFICO, AMBOS PODENDO INCLUIR
			APENAS REGISTROS ATIVOS OU TODOS.
			
			$Ativo -> Especifica o retorno de menus ativos ou todos. true p/ ativos false p/ todos
			$id -> id de um menu específico
			$page-> número da paginação para retornar a página de registros correta
		*/
		public function get_menu($Ativo = FALSE, $id = false, $page = false)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Menu) AS Size, Id, Nome, Ordem, Ativo 
						FROM Menu 
					WHERE TRUE ".$Ativos."
					ORDER BY Data_registro DESC ".$pagination."");
				
				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT Id, Nome, Ordem, Ativo 
					FROM Menu 
				WHERE Id = ".$this->db->escape($id)." ".$Ativos."");
			
			return $query->row_array();
		}
		/*
			RESPONSÁVEL POR OCULTAR UM MENU ESPECÍFICO

			$id -> Id de um Menu específico
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Menu SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*
			RESPONSÁVEL POR CADASTRAR UM NOVO MENU OU ATUALIZA-LO CASO JÁ EXISTA NO BANCO DE DADOS

			$data -> Contém os dados do menu
		*/
		public function set_menu($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Menu',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Menu', $data);
			}
		}
	}
?>