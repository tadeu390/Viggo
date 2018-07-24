<?php
	require_once("Usuario.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE DE USUÁRIO.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR AS INFORMAÇÕES DE ALUNOS.
	*/
	class Aluno extends Usuario 
	{
		/*
			CONSTRUTOR RESPONSÁVEL POR VALIDAR A SESSÃO E VERIFICAR O MENU SELECIONADO.
		*/
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->load->model('Aluno_model');
			$this->load->model('Curso_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Regras_model');
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu("usuario");
		}
		/*!
		*	RESPONSÁVEL POR REDIRECIONAR A PÁGINA PRO INDEX DE USUÁRIOS. O JS REDIRECIONA PRA ESTE MÉTODO
		*	QUE POR SUA VEZ REDIRECIONA PARA A LISTAGEM DE USUÁRIOS.
		*
		*	$page -> Página atual.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			redirect("usuario/index");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO COM CAMPOS DE USUARIO + OS CAMPOS DE ALUNO.
		*
		*	$id -> Contém a id do aluno.
		*	$type -> Contém um número inteiro, que diz respeito ao tipo de usuário que se quer criar.
		*/
		public function create($id = NULL, $type = NULL)
		{
			if($this->Geral_model->get_permissao(CREATE, get_parent_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, 0, FALSE);
				$this->data['type'] = $type;
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->data['title'] = 'Novo aluno';
				$this->view("aluno/create", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL OS DADOS DE UM ALUNO + OS DADOS DE USUÁRIO DO MESMO E 
		*	EM SEGUIDA ENVIA-LOS A VIEW EXBINDO ESTES DADOS.
		*
		*	$id -> Id do usuário/aluno.
		*	$type -> Grupo de usuário.
		*/
		public function edit($id = FALSE, $type = NULL)
		{
			if($this->Geral_model->get_permissao(UPDATE, get_parent_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				$this->data['obj_aluno'] = $this->Aluno_model->get_aluno($this->data['obj']['Id']);
				$this->data['type'] = ALUNO;
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->data['title'] = 'Editar aluno';
				$this->view("aluno/create", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);	
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO ALUNO.
		*
		*	$Aluno -> Contém todos os dados do aluno a ser validado.
		*/
		public function valida_aluno($Aluno)
		{
			return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO ALUNO.
		*
		*	$Aluno -> Contém todos os dados de um aluno a ser cadastrado/editado.
		*/
		public function store_banco($Aluno)
		{
			$this->Aluno_model->set_aluno($Aluno);
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
				'Email_notifica_nova_conta' => $this->input->post('email_notifica_nova_conta'),
			);

			$dataToSave['Data_nascimento'] = $this->convert_date($dataToSave['Data_nascimento'], "en");

			if(empty($dataToSave['Email_notifica_nova_conta']))
				$dataToSave['Email_notifica_nova_conta'] = 0;

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_parent_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_parent_class($this)) == TRUE)
				{
					$resultado = parent::valida_usuario($dataToSave);

				 	if($resultado == 1)
				 	{
				 		$dataToSaveAluno = array(
							'Usuario_id' => $this->input->post('id')
						);
						$resultado = $this->valida_aluno($dataToSaveAluno);
						if($resultado == 1)
						{
							$resultado = parent::store_banco($dataToSave);

							$dataToSaveAluno['Usuario_id'] = $resultado;//QUANDO ESTIVER CRIANDO ISSO É NECESSARIO, POIS O POST DO ID VIRÁ VAZIO

							$resultado = $this->store_banco($dataToSaveAluno);

							$resultado = "sucesso";
						}
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
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM ALUNO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id do aluno.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_parent_class($this)) == TRUE)
			{
				$this->data['title'] = 'Detalhes do usuário';
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				$this->data['obj']['Ultimo_acesso'] = $this->Logs_model->get_last_access_user($this->data['obj']['Id'])['Data_registro'];
				$this->data['obj_aluno'] = $this->Aluno_model->get_aluno($this->data['obj']['Id']);
				$this->view("aluno/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR GERAR UM JSON DO NOME DE UM PERÍODO LETIVO.
		*	
		*	$modalidade_id -> Id da modalidade que se deseja obter o nome do período letivo.
		*/
		public function get_periodo_por_modalidade($modalidade_id)
		{
			$resultado = $this->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Nome_periodo'];

			$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
	}
?>