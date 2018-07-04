<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS MENUS DO SISTEMA.
	*/
	class Menu extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			
			if(empty($this->Account_model->session_is_valid()['id']))
			{
				$url_redirect = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_redirect = str_replace("/","-x",$url_redirect);
				redirect('account/login/'.$url_redirect);
			}
			
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS MENUS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Menus';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_menus'] = $this->Menu_model->get_menu(FALSE, FALSE, $page);
				$this->data['paginacao']['size'] = $this->data['lista_menus'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("menu/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE MENU PARA "APAGAR".
		*
		*	$id -> Id do menu.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Menu_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MENU E RECEBER DA MODEL OS DADOS 
		*	DO MENU QUE SE DESEJA EDITAR.
		*
		*	$id -> Id do menu.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar Menu';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Menu_model->get_menu(FALSE, $id, FALSE);
				$this->view("menu/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO MENU.
		*/
		public function create()
		{
			$this->data['title'] = 'Novo Menu';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Menu_model->get_menu(FALSE, 0, FALSE);
				$this->view("menu/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO MENU.
		*
		*	$Menu -> Contém todos os dados do menu a ser validado.
		*/
		public function valida_menu($Menu)
		{
			if(empty($Menu['Nome']))
				return "Informe o nome de menu";
			else if(mb_strlen($Menu['Nome']) > 20)
				return "Máximo 20 caracteres";
			else if($this->Menu_model->nome_valido($Menu['Nome'], $Menu['Id']) == 'invalido')
				return "O nome informado para o menu já se encontra cadastrado no sistema.";
			else if(empty($Menu['Ordem']))
				return "Informe o número da ordem";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO MENU.
		*
		*	$dataToSave -> Contém todos os dados do menu a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Menu_model->set_menu($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
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

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = $this->valida_menu($dataToSave);

				 	if($resultado == 1)
				 	{ 
				 		$this->store_banco($dataToSave);
				 		$resultado = "sucesso";
				 	}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação.";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			 }
			 else
				redirect('menu/index');
		}
	}
?>