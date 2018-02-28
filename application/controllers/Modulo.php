<?php
	require_once("Geral.php");

	class Modulo extends Geral {
		public function __construct()
		{
			parent::__construct();
			if(empty($this->Account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		
		/*
			Renderiza o dashboard
		*/
		public function index($page = false)
		{
			if($page === false)
				$page = 1;
			
			$this->data['title'] = 'Administração - Módulos';
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['lista_modulos'] = $this->Modulo_model->get_modulo_tela(false,$page);
				
				$this->data['paginacao']['size'] = $this->data['lista_modulos'][0]['size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->data['paginacao']['itens_por_pagina'] = ITENS_POR_PAGINA;
				
				$this->view("modulo/index",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE,get_class($this)) == true)	
				$this->Modulo_model->deletar($id);
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function edit($id = false)
		{
			$this->data['title'] = 'Módulo - Cadastro';
			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Modulo_model->get_modulo_tela($id);
				$this->data['lista_menus'] = $this->Menu_model->get_menu_tela();
				
				$this->view("modulo/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function create()
		{
			$this->data['title'] = 'Módulo - Cadastro';
			if($this->Geral_model->get_permissao(CREATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Modulo_model->get_modulo_tela(0);
				$this->data['lista_menus'] = $this->Menu_model->get_menu_tela();
				
				$this->view("modulo/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'id' => $this->input->post('id'),
				'nome' => $this->input->post('nome'),
				'descricao' => $this->input->post('descricao'),
				'url' => $this->input->post('url_modulo'),
				'ordem' => $this->input->post('ordem'),
				'icone' => $this->input->post('icone'),
				'menu_id' => $this->input->post('menu_id'),
				'ativo' => $this->input->post('modulo_ativo')
			);
			
			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['nome']))
					$this->Modulo_model->set_modulo($dataToSave);
			 else
				redirect('admin/dashboard');
			
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		
		public function detalhes($id = false)
		{
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{		
				$this->data['title'] = 'Módulo - Detalhes';
				$this->data['obj'] = $this->Modulo_model->get_modulo_tela($id);
	
				$this->view("modulo/detalhes",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
	}
?>