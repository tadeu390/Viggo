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
			$this->data['controller'] = strtolower(get_class($this));
			$this->load->model('Aluno_model');
			$this->load->model('Curso_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Account_model');
			$this->load->model('Disciplina_model');
			$this->load->model('Academico_model');
			$this->load->model('Etapa_model');
			$this->load->model('Regras_model');
			//$this->load->model('Doc_model');
			$this->load->model('Endereco_model');
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
			//redireciona para a inscrição do aluno, toda vez que um aluno é criado ou alterado caso o usuário queira.
			if(!empty($this->input->cookie('inscricao_aluno')))
				redirect("inscricao/create/");

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
			delete_cookie ('inscricao_aluno');//usado na tela de inscricao
			if($this->Geral_model->get_permissao(CREATE, get_parent_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, 0, FALSE);
				$this->data['obj_aluno'] = $this->Aluno_model->get_aluno(0);
				$this->data['Endereco'] = array(array(
											'Endereco_id' => '', 'Rua' => '', 'Bairro' => '',
											'Zona' => '', 'Transp_publico' => '', 'Municipio' => '',
											'Uf' => '', 'Cep' => '', 'Telefone_aluno' => '',
											'Telefone_responsavel' => '', 'Aluno_id' => ''));
				//$this->data['lista_documentos_aluno'] = $this->Doc_model->get_doc(TRUE, FALSE, DOC_ALUNO);
				//$this->data['lista_documentos_responsavel'] = $this->Doc_model->get_doc(TRUE, FALSE, DOC_RESPONSAVEL);

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
			delete_cookie ('inscricao_aluno');//usado na tela de inscricao
			if($this->Geral_model->get_permissao(UPDATE, get_parent_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				$this->data['obj_aluno'] = $this->Aluno_model->get_aluno($this->data['obj']['Id']);
				$this->data['Endereco'] = $this->Endereco_model->get_endereco(TRUE, FALSE, $this->data['obj_aluno']['Id']);
				if(empty($this->data['Endereco']))
				{
					$this->data['Endereco'] = array(array(
											'Endereco_id' => '', 'Rua' => '', 'Bairro' => '',
											'Zona' => '', 'Transp_publico' => null, 'Municipio' => '',
											'Uf' => '', 'Cep' => '', 'Telefone_aluno' => '',
											'Telefone_responsavel' => '', 'Aluno_id' => ''));
				}
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
			if($this->Aluno_model->cpf_valido($Aluno['Cpf'], $Aluno['Usuario_id']) == "invalido")
				return "O CPF informado já se encontra em uso por outro aluno.";
			else if($this->Aluno_model->rg_valido($Aluno['Rg'], $Aluno['Usuario_id']) == "invalido")
				return "O RG informado já se encontra em uso por outro aluno.";
			else if($this->Aluno_model->titulo_eleitor_valido($Aluno['Titulo_eleitor'], $Aluno['Usuario_id']) == "invalido")
				return "O título de eleitor informado já se encontra em uso por outro aluno.";
			return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO ALUNO.
		*
		*	$Aluno -> Contém todos os dados de um aluno a ser cadastrado/editado.
		*/
		public function store_banco($Aluno)
		{
			return $this->Aluno_model->set_aluno($Aluno);
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

			$endereco = array(
				'Id' => $this->input->post('endereco_id'),
				'Rua' => $this->input->post('rua'),
				'Bairro' => $this->input->post('bairro'),
				'Zona' => $this->input->post('zona'),
				'Transp_publico' => $this->input->post('transp_publico'),
				'Municipio' => $this->input->post('municipio'),
				'Uf' => $this->input->post('uf_endereco'),
				'Cep' => $this->input->post('cep'),
				'Telefone_aluno' => $this->input->post('telefone_aluno'),
				'Telefone_responsavel' => $this->input->post('telefone_responsavel'),
				'Aluno_id' => $this->input->post('aluno_id')
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
				 			'Id' => $this->input->post('aluno_id'),
							'Usuario_id' => $this->input->post('id'),
							'Naturalidade' => $this->input->post('naturalidade'),
							'Uf' => $this->input->post('uf'),
							'Nome_mae' => $this->input->post('nome_mae'),
							'Nome_pai' => $this->input->post('nome_pai'),
							'Cor' => $this->input->post('cor'),
							'Especial' => $this->input->post('necessidade_especial'),
							'Identificacao' => $this->input->post('identificacao'),
							'Sit_ensino_medio' => $this->input->post('situacao_em'),
							'Escola' => $this->input->post('escola'),
							'Cpf' => $this->input->post('cpf'),
							'Rg' => $this->input->post('rg'),
							'Orgao_expedidor' => $this->input->post('orgao_expedidor'),
							'Data_expedicao' => $this->convert_date($this->input->post("data_expedicao"),"en"),
							'Titulo_eleitor' => $this->input->post('titulo_eleitor'),
							'Zona_eleitoral' => $this->input->post('zona_eleitoral'),
							'Secao_eleitoral' => $this->input->post('secao_eleitoral'),
							'Uf_titulo' => $this->input->post('uf_titulo')
						);

						$resultado = $this->valida_aluno($dataToSaveAluno);
						if($resultado == 1)
						{
							$resultado = parent::store_banco($dataToSave);

							$dataToSaveAluno['Usuario_id'] = $resultado;//QUANDO ESTIVER CRIANDO ISSO É NECESSARIO, POIS O POST DO ID VIRÁ VAZIO
							$Usuario_id = $resultado;
							$resultado = $this->store_banco($dataToSaveAluno);

							$endereco['Aluno_id'] = $resultado;
							$this->Endereco_model->set_endereco($endereco);

							if($this->input->post('inscricao_aluno') != NULL)
								$this->set_aluno_cookie($Usuario_id);
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
		*	RESPONSÁVEL POR CRIAR UM COOKIE QUANDO O USUÁRIO DESEJA IR PARA A TELA DE INSCRIÇÃO DO ALUNO.
		*
		*	$usuario_id -> Id de usuário do aluno usado para redirecionar para tela de inscrição.
		*/
		public function set_aluno_cookie($usuario_id)
		{
			$cookie = array(
		            'name'   => 'inscricao_aluno',
		            'value'  => $usuario_id,
		            'expire' => 100000000,
		            'secure' => FALSE
	            );
		  	$this->input->set_cookie($cookie);
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
		////AQUI PRA BAIXO PARA ACESSO DO ALUNO NO PORTAL
		private $curso_id;
		private $periodo_letivo_id;
		/*!
		*	RESPONSÁVEL POR CARREGAR INFORMAÇÕES PARA O ACESSO DO ALUNO
		*/
		public function set_default()
		{
			$this->curso_id = $this->input->cookie("curso_id");
			$this->periodo_letivo_id = $this->input->cookie("periodo_letivo_id");
			$this->aluno_id = $this->Account_model->session_is_valid()['id'];
			$this->data['periodo'] = $this->Regras_model->Aluno_model->get_periodos_aluno($this->Account_model->session_is_valid()['id'], $this->input->cookie('curso_id'))[0]['Curso'];
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR  A TELA DE NOTAS E FALTAS DO ALUNO.
		*/
		public function resultado()
		{
			
			$this->data['title'] = "Notas e faltas";
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->set_default();//carrega informações do período selecionado
				$this->data['Aluno_id']  = $this->aluno_id;

				$this->data['lista_etapas'] = $this->Etapa_model->get_etapa($this->periodo_letivo_id, FALSE, FALSE);
				$this->data['lista_disciplinas'] = $this->Disciplina_model->get_disciplinas_aluno($this->curso_id, $this->periodo_letivo_id, $this->aluno_id);
				$this->data['turma_id'] = $this->data['lista_disciplinas'][0]['Turma_id'];
				$this->data['regra_letiva'] = $this->Regras_model->get_regras(FALSE, $this->periodo_letivo_id, FALSE, FALSE, FALSE);
				return $this->view("aluno/nota_falta", $this->data);
			}
		}
	}
?>