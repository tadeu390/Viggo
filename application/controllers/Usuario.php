<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELATIVO AOS DADOS DO USUÁRIO.
	*/
	class Usuario extends Geral 
	{
		/*
			CONSTRUTOR CARREGA OS MODELS E VERIFICAR A EXSISTÊNCIA DA SESSÃO DE USUÁRIO.
		*/
		public function __construct()
		{
			parent::__construct();

			if(empty($this->Account_model->session_is_valid()['id']))
			{
				$url_redirect = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_redirect = str_replace("/","-x",$url_redirect);
				redirect('account/login/'.$url_redirect);
			}
			$this->load->model('Grupo_model');
			$this->load->model('Senha_model');
			$this->load->model('Logs_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS USUÁRIOS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			if($page === FALSE)//QUANDO A PÁGINA NÃO É ESPECIFICADA, POR DEFAULT CARREGA A PRIMEIRA PÁGINA
				$page = 1;

			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Usuários';

			$this->data['paginacao']['filter'] = (!empty($this->input->get()) ? "?".explode("?",$_SERVER["REQUEST_URI"])[1] : "");

			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['usuarios'] = $this->Usuario_model->get_usuario(FALSE, FALSE, $page, $this->input->get(), $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['usuarios']) ? $this->data['usuarios'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				//--FILTROS--//
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];
				
				$this->data['filtros']['grupos'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->data['filtros']['outros'] = $this->input->get();
				//--FILTROS--//
				$this->view("usuario/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE USUÁRIO PARA "APAGAR".
		*
		*	$id -> Id do usuário.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Usuario_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM USUÁRIO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de um usuário.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Detalhes do usuário';
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				$this->data['obj']['Ultimo_acesso'] = $this->Logs_model->get_last_access_user($this->data['obj']['Id'])['Data_registro'];
				$this->view("usuario/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL AS PERMISSÕES POR MÓDULO DE UM DETERMINADO USUÁRIO E ENVIA-LAS 
		*   A VIEW. É POSSIVEL ALTERAR ESTAS PERMISSÕES.
		*
		*	$id -> Id do usuário.
		*/
		public function permissoes($id = FALSE)
		{
			if($this->Account_model->session_is_valid()['grupo_id'] == ADMIN)
			{
				$this->data['title'] = 'Permissões do usuário';
				$this->data['usuario_id'] = $id;
				$this->data['lista_usuario_acesso'] = $this->Acesso_model->get_acesso($id);
				$this->data['usuario'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE)['Nome_usuario'];
				$this->view("usuario/permissoes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL CAPTAR AS PERMISSÕES DE UM DETERMINADO USUÁRIO DO FORMULÁRIO SUBMETIDO.
		*/
		public function store_permissoes()
		{
			
			//NÃO PERMITE ACESSO DIRETO A METODO STORE
			if(!empty($this->input->post()))
			{
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = "sucesso";
					for($i = 0; $this->input->post('modulo_id'.$i) != null; $i++)
					{
						$dataAcessoToSave = array(
							'Id' => $this->input->post("acesso_id".$i.""),
							'Usuario_id' => $this->input->post("usuario_id"),
							'Modulo_id' => $this->input->post("modulo_id".$i.""),
							'Criar' => (($this->input->post("linha".$i."col0") == null) ? 0 : 1),
							'Ler' => (($this->input->post("linha".$i."col1") == null) ? 0 : 1),
							'Atualizar' => (($this->input->post("linha".$i."col2") == null) ? 0 : 1),
							'Remover' => (($this->input->post("linha".$i."col3") == null) ? 0 : 1)
						);
						$this->Acesso_model->set_acesso($dataAcessoToSave);
					}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação.";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('usuario/index');
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO USUÁRIO.
		* 	$id -> Contém a id de um usuário.
		*	$type -> Contém um número inteiro, que diz respeito ao tipo de usuário que se quer criar.
		*/
		public function create($id = FALSE, $type = NULL)
		{
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['type'] = $type;
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, 0, FALSE);
				$this->data['title'] = 'Novo usuário';
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->view("usuario/create", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE USUÁRIO E RECEBER DA MODEL OS DADOS 
		*	DO USUÁRIO QUE SE DESEJA EDITAR.
		*
		* 	$id -> Contém a id de um usuário.
		*	$type -> Contém um número inteiro, que diz respeito ao tipo de usuário que se quer criar.
		*/
		public function edit($id = FALSE, $type = NULL)
		{
			if($id === FALSE)
				$id = $this->Account_model->session_is_valid()['id'];

			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Editar usuário';
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				
				if($type == NULL)
					$this->data['type'] = $this->data['obj']['Grupo_id'];
				else
					$this->data['type'] = $type;

				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->view("usuario/create", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DOS USUÁRIOS.
		*
		*	$Usuario -> Contém todos os dados do usuário a ser validado.
		*/
		public function valida_usuario($Usuario)
		{	
			if($Usuario['Grupo_id'] == 0)
				return "Selecione um tipo de usuário";
			else if(empty($Usuario['Nome']))
				return "Informe o nome de usuário";
			else if(mb_strlen($Usuario['Nome']) > 100)
				return "Máximo 100 caracteres";
			else if(empty($Usuario['Email']))
				return "Informe o e-mail de usuário";
			else if(mb_strlen($Usuario['Email']) > 100)
				return "Máximo 100 caracteres";
			else if($this->Usuario_model->email_valido($Usuario['Email'],$Usuario['Id']) == "invalido")
				return "O e-mail informado já está em uso.";
			else if(empty($Usuario['Data_nascimento']))
				return "Informe a data de nascimento do usuário";
			else if($Usuario['Sexo'] == NULL)
				return "Selecione o sexo do usuário";
			else if(empty($this->input->post('senha')) && $Usuario['Id'] == NULL)
				return "Informe a senha de usuário";
			else if(strlen($this->input->post('senha')) < 8 && $Usuario['Id'] == NULL)
				return "A senha deve conter no mínimo 8 caracteres.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO USUÁRIO.
		*
		*	$dataToSave -> Contém todos os dados usuário a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			//se trocar o usuario de grupo, setar para este as permissões padrões 
			//do novo grupo atribuído a ele
			$Usuario = $this->Usuario_model->get_usuario(FALSE, $dataToSave['Id'], FALSE);
			$senha_email = "";
			if($dataToSave['Id'] >= 1)//somente se estiver editando, esse trecho é necessário ser executado antes da próxima linha depois do if
			{
				if($dataToSave['Grupo_id'] != $Usuario['Grupo_id'])
					$this->permissoes_default($dataToSave['Id'], $dataToSave['Grupo_id']);
			}
			$Usuario_id = $this->Usuario_model->set_usuario($dataToSave);

			if($dataToSave['Id'] >= 1)//somente se estiver editando
			{
				//SE O CAMPO CONTER ALGO ENTÃO SIGNIFICA QUE A SENHA DEVE SER ALTERADA.
				if($this->input->post('nova_senha') != NULL && !empty($this->input->post('nova_senha')))
				{
					$senha_email = $this->input->post('nova_senha');

					$data = array(
						'Usuario_id' => $Usuario_id,
						'Valor' => $this->hashing($this->input->post('nova_senha'))
					);
					$this->Senha_model->set_senha($data);
				}
			}
			else
			{
				$senha_email = $this->input->post('senha');

				$data = array(
					'Usuario_id' => $Usuario_id,
					'Valor' => $this->hashing($this->input->post('senha'))
				);
				$this->Senha_model->set_senha($data);
				$this->permissoes_default($Usuario_id, $dataToSave['Grupo_id']);
			}
			if($dataToSave['Email_notifica_nova_conta'] == 1 && empty($Usuario))
			{
				$dataToSave['Nome_usuario'] = $dataToSave['Nome'];
				$this->envia_email_nova_conta($dataToSave, $senha_email);
			}
			else if($dataToSave['Email_notifica_nova_conta'] == 1 && $Usuario['Email_notifica_nova_conta'] == 0)
				$this->envia_email_nova_conta($Usuario, $senha_email);
			return $Usuario_id;
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('conta_ativa'),
				'Nome' => $this->input->post('nome'),
				'Email' => $this->input->post('email'),
				'Data_nascimento' => $this->input->post('data_nascimento'),
				'Sexo' => $this->input->post('sexo'),
				'Grupo_id' => $this->input->post('grupo_id'),
				'Email_notifica_nova_conta' => $this->input->post('email_notifica_nova_conta')
			);

			$dataToSave['Data_nascimento'] = $this->convert_date($dataToSave['Data_nascimento'], "en");

			if(empty($dataToSave['Email_notifica_nova_conta']))
				$dataToSave['Email_notifica_nova_conta'] = 0;

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, "Usuario") == TRUE || $this->Geral_model->get_permissao(UPDATE, "Usuario") == TRUE)
				{
				 	$resultado = $this->valida_usuario($dataToSave);

				 	if($resultado == 1)
				 	{ 
				 		$this->store_banco($dataToSave);
				 		$resultado = "sucesso";
				 	}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('usuario/index');
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR E-MAIL PARA O USUÁRIO QUANDO SUA CONTA FOR CRIADA NO SISTEMA (ISTO SÓ É EXECUTADO SE QUEM ESTÁ CRIANDO O
		*	USUÁRIO, MARCAR O CHECKBOX NA TELA DE CADASTRO).
		*
		*	$Usuario -> Dados do usuário.
		*/
		public function envia_email_nova_conta($Usuario, $senha_email)
		{
			$this->email->from($this->Configuracoes_email_model->get_configuracoes_email()['Email'], 'CEP - Centro de Educação Profissional "Tancredo Neves"');
			$this->email->to($Usuario['Email']);
			$Usuario['url'] = base_url();
			$Usuario['Senha'] = $senha_email;
			$mensagem = $this->load->view("templates/email_nova_conta", $Usuario, TRUE);
			$this->email->subject('Bem vindo ao CEP');
			$this->email->message($mensagem);
			$this->email->send();
		}
		/*!
		*	RESPONSÁVEL POR IDENTIFICAR AS PERMISSÕES DEFAULT DO USUÁRIO DE ACORDO COM O GRUPO QUE LHE É SELECIONADO E ENVIAR PARA A MODEL 
		*	PARA QUE SEJA CADASTRADO CADA PERMISSÃO.
		*
		*	$id -> Id -> do usuário.
		*	$grupo_id -> Id do grupo que usuário foi colocado.
		*/
		public function permissoes_default($id, $grupo_id)
		{
			$permissoes_default = $this->Acesso_padrao_model->get_acesso_padrao($grupo_id);
			$permissoes_current = $this->Acesso_model->get_acesso($id);
			
			for($i = 0; $i < COUNT($permissoes_default); $i++)
			{
				$dataAcessoToSave = array(
					'Id' => $permissoes_current[$i]['Acesso_id'],
					'Usuario_id' => $id,
					'Modulo_id' => $permissoes_default[$i]['Modulo_id'],
					'Criar' => $permissoes_default[$i]['Criar'],
					'Ler' => $permissoes_default[$i]['Ler'],
					'Atualizar' => $permissoes_default[$i]['Atualizar'],
					'Remover' => $permissoes_default[$i]['Remover']
				);
				$this->Acesso_model->set_acesso($dataAcessoToSave);
			}
		}
	}
?>