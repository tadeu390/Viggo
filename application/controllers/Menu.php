<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS MENUS DO SISTEMA
	*/
	class Menu extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			if(empty($this->Account_model->session_is_valid($this->session->id)['id']))
				redirect('account/login');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*
			RESPONSÁVEL POR LISTAR TODOS OS MENUS NA TELA
			$page -> número da página atual registros
		*/
		public function index($page = false)
		{
			if($page === false)
				$page = 1;
			
			$this->data['title'] = 'Menus';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == true)
			{
				$this->data['lista_menus'] = $this->Menu_model->get_menu(FALSE, FALSE, $page);
				$this->data['paginacao']['size'] = $this->data['lista_menus'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("menu/index", $this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		/*
			RESPONSÁVEL POR OCULTAR UM MENU DO SISTEMA
			$id -> id de um menu
		*/
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == true)
				$this->Menu_model->deletar($id);
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DE MENU PARA EDIÇÃO
			$id -> id de um menu
		*/
		public function edit($id = false)
		{
			$this->data['title'] = 'Editar Menu';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == true)
			{
				$this->data['obj'] = $this->Menu_model->get_menu(FALSE, $id, FALSE);
				$this->view("menu/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DE MENU PARA CRIAR
		*/
		public function create()
		{
			$this->data['title'] = 'Novo Menu';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == true)
			{
				$this->data['obj'] = $this->Menu_model->get_menu(FALSE, 0, FALSE);
				$this->view("menu/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RECEBER OS DADOS DO FORMULÁRIO E OS ENVIA-LO PARA A MODEL
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Nome' => $this->input->post('nome'),
				'Ordem' => $this->input->post('ordem'),
				'Ativo' => $this->input->post('menu_ativo')
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['Nome']))
			 {
			 	if(empty($this->Menu_model->get_menu_por_nome($dataToSave['Nome'])))
					$this->Menu_model->set_menu($dataToSave);
				else
					$resultado = "O nome informado para o Menu já se encontra cadastrado no sistema.";
			 }
			 else
				redirect('admin/dashboard');
			
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>