<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS MENUS DO SISTEMA.
	*/
	class Menu_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MENUS OU UM MENU ESPECÍFICO.
		*	
		*	$Ativo -> Quando passadO "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de um menu específico.
		*	$page-> Número da página de registros que se quer carregar.
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
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS OS MENUS QUE O USUÁRIO LOGADO PODE VISUALIZAR, 
		*	ESSES MENUS SÃO RETORNADOS DESDE QUE HAJA PELO MENOS UM MÓDULO DENTRO DO MENU E DESDE QUE O USUÁRIO 
		*	LOGADO TENHA PERMISSÃO DE LEITURA PARA VISUALIZAR PELO MENOS UM MÓDULO.
		*	SE NÃO ATENDER A ESSAS REGRAS O MENU É SIMPLESMENTE IGNORADO. SENDO ASSIM, SÓ EXIBE MENUS QUE O USUÁRIO 
		*	LOGADO TENHA PERMISSÃO DE LEITURA EM ALGUM MÓDULO DO MENU.
		*
		*	Obs.: modulo acesso é uma VIEW.
		*/
		public function get_menu_acesso()//usado apenas para montar o menu
		{
			$CI = get_instance();
			$CI->load->model("Account_model");

			$query = $this->db->query("SELECT Nome_menu, Menu_id FROM Modulo_acesso_view 
			WHERE Usuario_id = ".$CI->Account_model->session_is_valid()['id']." AND Ativo = 1 GROUP BY 1,2 ORDER BY Ordem_menu");
			
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UM MENU DO BANCO DE DADOS.
		*
		*	$id -> Id do menu a ser "apagado".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Menu SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UM MENU NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados do menu.
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
		/*!
		*	RESPONSÁVEL POR RETORNAR UM MENU CONFORME O NOME.
		*
		*	$nome -> Contém o nome do menu a ser buscado no banco de dados.
		*/
		public function get_menu_por_nome($nome)
		{
			$nome = mb_strtolower($nome);
			$query = $this->db->query("
				SELECT * FROM Menu m WHERE LOWER(m.Nome) = ".$this->db->escape($nome)."");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM DETERMIANDO MENU JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome do menu a ser validado.
		*	$Id -> Id do menu.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Menu 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($Nome).")");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_menu(FALSE ,$Id, FALSE)['Nome'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
	}
?>