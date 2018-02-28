<?php
	require_once("Geral.php");

	class Menu extends Geral {
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
			
			$this->data['title'] = 'Administração - Menus';
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['lista_menus'] = $this->Menu_model->get_menu_tela(false,$page);
				$this->data['paginacao']['size'] = $this->data['lista_menus'][0]['size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("menu/index",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE,get_class($this)) == true)
				$this->Menu_model->deletar($id);
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function edit($id = false)
		{
			$this->data['title'] = 'Menu - Cadastro';
			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Menu_model->get_menu_tela($id);
				$this->view("menu/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function create()
		{
			$this->data['title'] = 'Menu - Cadastro';
			if($this->Geral_model->get_permissao(CREATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Menu_model->get_menu_tela($id);
				$this->view("menu/create_edit",$this->data);
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
				'ordem' => $this->input->post('ordem'),
				'ativo' => $this->input->post('menu_ativo')
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['nome']))
					$this->Menu_model->set_menu($dataToSave);
			 else
				redirect('admin/dashboard');
			
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>