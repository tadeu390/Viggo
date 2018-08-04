<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TODOS OS RECURSOS DOS MODULOS DO SISTEMA.
	*/
	class Modulo extends Geral 
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
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS MODULO CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			if($page === false)
				$page = 1;

			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Módulos';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == true)
			{
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->data['lista_modulos'] = $this->Modulo_model->get_modulo(FALSE, FALSE, $page, $ordenacao);
				
				$this->data['paginacao']['size'] = $this->data['lista_modulos'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->data['paginacao']['itens_por_pagina'] = ITENS_POR_PAGINA;
				
				$this->view("modulo/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE MÓDULO PARA "APAGAR".
		*
		*	$id -> Id do módulo.
		*/
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == true)
			{
				$this->Modulo_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MÓDULOS E RECEBER DA MODEL OS DADOS 
		*	DO MÓDULO QUE SE DESEJA EDITAR.
		*
		*	$id -> Id do módulo.
		*/
		public function edit($id = false)
		{
			$this->data['title'] = 'Editar Módulo';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == true)
			{
				$this->data['obj'] = $this->Modulo_model->get_modulo(FALSE, $id, FALSE);
				$this->data['lista_menus'] = $this->Menu_model->get_menu(FALSE, FALSE, FALSE);
				
				$this->view("modulo/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO MÓDULO.
		*/
		public function create()
		{
			$this->data['title'] = 'Novo Módulo';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == true)
			{
				$this->data['obj'] = $this->Modulo_model->get_modulo(FALSE, 0, FALSE);
				$this->data['lista_menus'] = $this->Menu_model->get_menu(FALSE, FALSE, FALSE);
				
				$this->view("modulo/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO MODULO.
		*
		*	$Modulo -> Contém todos os dados do modulo a ser validado.
		*/
		public function valida_modulo($Modulo)
		{
			if(empty($Modulo['Nome']))
				return "Informe o nome de módulo";
			else if($this->Modulo_model->nome_valido($Modulo['Nome'], $Modulo['Id']) == 'invalido')
				return "O nome informado para o módulo já se encontra cadastrado no sistema.";
			else if(mb_strlen($Modulo['Nome']) > 20)
				return "Máximo 20 caracteres";
			else if(empty($Modulo['Descricao']))
				return "Informe a descrição de módulo";
			else if(mb_strlen($Modulo['Descricao']) > 50)
				return "Máximo 50 caracteres";
			else if(empty($Modulo['Url']))
				return "Informe a url de módulo";
			else if(mb_strlen($Modulo['Url']) > 100)
				return "Máximo 20 caracteres";
			else if(empty($Modulo['Ordem']))
				return "Informe o número da ordem";
			else if(empty($Modulo['Icone']))
				return "Informe o ícone do módulo";
			else if(mb_strlen($Modulo['Icone']) > 50)
				return "Máximo 50 caracteres";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO MODULO.
		*
		*	$dataToSave -> Contém todos os dados do modulo a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Modulo_model->set_modulo($dataToSave);
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
				'Descricao' => $this->input->post('descricao'),
				'Url' => $this->input->post('url_modulo'),
				'Ordem' => $this->input->post('ordem'),
				'Icone' => $this->input->post('icone'),
				'Menu_id' => $this->input->post('menu_id'),
				'Ativo' => $this->input->post('modulo_ativo')
			);

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = $this->valida_modulo($dataToSave);

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
				redirect('modulo/index');
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM MÓDULO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de um módulo.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{		
				$this->data['title'] = 'Detalhes do módulo';
				$this->data['obj'] = $this->Modulo_model->get_modulo(FALSE, $id, FALSE);
	
				$this->view("modulo/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>