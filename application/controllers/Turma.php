<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS TURMAS.
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
			
			$this->load->model("Turma_model");
			
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS AS TURMAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Turmas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma(FALSE, FALSE, $page, FALSE);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_turmas']) ? $this->data['lista_turmas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("turma/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE UMA TURMA PARA "APAGAR".
		*
		*	$id -> Id da turma.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Turma_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE TURMA E RECEBER DA MODEL OS DADOS 
		*	DA TURMA QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da turma.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar turma';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);
				$this->view("turma/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE TURMA.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova turma';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, 0, FALSE, FALSE);
				$this->view("turma/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DA TURMA.
		*
		*	$Turma -> Contém todos os dados do menu a ser validado.
		*/
		public function valida_turma($Turma)
		{
			if(empty($Turma['Nome']))
				return "Informe o nome da turma";
			else if(mb_strlen($Turma['Nome']) > 20)
				return "Máximo 20 caracteres";
			else if($this->Turma_model->nome_valido($Turma['Nome'], $Turma['Id']) == 'invalido')
				return "O nome informado para a turma já se encontra cadastrado no sistema.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DA TURMA.
		*
		*	$dataToSave -> Contém todos os dados da turma a ser cadastrada/editada.
		*/
		public function store_banco($dataToSave)
		{
			$this->Turma_model->set_turma($dataToSave);
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
					//$resultado = $this->valida_turma($dataToSave);

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
				redirect('turma/index');
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UMA TURMA E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de uma turma.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Detalhes da turma';
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);
				$this->view("turma/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>