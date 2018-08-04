<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS MODALIDADES DO SISTEMA.
	*/
	class Modalidade extends Geral 
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

			$this->load->model('Modalidade_model');

			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS MODALIDADES CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			if($page === FALSE)
				$page = 1;

			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Modalidades';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, $page, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_modalidades']) ? $this->data['lista_modalidades'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("modalidade/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE MODALIDADE PARA "APAGAR".
		*
		*	$id -> Id da modalidade.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Modalidade_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MODALIDADE E RECEBER DA MODEL OS DADOS 
		*	DA MODALIDADE QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da modalidade.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar modalidade';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Modalidade_model->get_modalidade(FALSE, $id, FALSE);
				$this->view("modalidade/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DA MODALIDADE.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova modalidade';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Modalidade_model->get_modalidade(FALSE, 0, FALSE);
				$this->view("modalidade/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DA MODALIDADE.
		*
		*	$Modalidade -> Contém todos os dados da modalidade a ser validada.
		*/
		public function valida_modalidade($Modalidade)
		{
			if(empty($Modalidade['Nome']))
				return "Informe o nome da modalidade";
			else if(mb_strlen($Modalidade['Nome']) > 100)
				return "Máximo 100 caracteres";
			else if($this->Modalidade_model->nome_valido($Modalidade['Nome'], $Modalidade['Id']) == 'invalido')
				return "O nome informado para a modalidade já se encontra cadastrado no sistema.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DA MODALIDADE.
		*
		*	$dataToSave -> Contém todos os dados da modalidade a ser cadastrada/editada.
		*/
		public function store_banco($dataToSave)
		{
			$this->Modalidade_model->set_modalidade($dataToSave);
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
				'Ativo' => $this->input->post('modalidade_ativo')
			);

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = $this->valida_modalidade($dataToSave);

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
				redirect('modalidade/index');
		}
	}
?>