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
					ORDER BY Data_registro ASC ".$pagination."");
				
				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT Id, Nome, Ordem, Ativo 
					FROM Menu 
				WHERE Id = ".$this->db->escape($id)." ".$Ativos."");
			
			return $query->row_array();
		}
		/*
			RESPONSÁVEL POR RETORNAR TODOS OS MENUS QUE O USUÁRIO LOGADO PODE VISUALIZAR, 
			ESSES MENUS SÃO RETORNADOS DESDE QUE HAJA PELO MENOS UM MODULO DENTRO DO MENU E DESDE QUE O USUÁRIO LOGADO TENHA PERMISSAO DE LEITURA PARA VISUALIZAR PELO MENOS UM MODULO.
			SE NÃO ATENDER A ESSAS REGRAS O MENU É SIMPLESMENTE IGNORADO. SENDO ASSIM SÓ EXIBE MENUS QUE O USUÁRIO LOGADO POSSUA PERMISSAO DE LEITURA EM ALGUM MODULO DENTRO DOS MESMOS

			obs.: modulo acesso é uma VIEW
		*/
		public function get_menu_acesso()//usado apenas para montar o menu
		{
			$CI = get_instance();
			$CI->load->model("Account_model");

			$query = $this->db->query("SELECT Nome_menu, Menu_id FROM Modulo_acesso_view 
			WHERE Usuario_id = ".$CI->Account_model->session_is_valid()['id']." GROUP BY 1,2 ORDER BY Ordem");
			
			return $query->result_array();
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