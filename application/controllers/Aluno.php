<?php
	require_once("Usuario.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE DE USUÁRIO
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR AS INFORMAÇÕES DE ALUNOS 
	*/
	define("ALUNO", 2);
	class Aluno extends Usuario 
	{
		/*
			CONSTRUTOR RESPONSÁVEL POR VALIDAR A SESSÃO E VERIFICAR O MENU SELECIONADO
		*/
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->load->model('Aluno_model');
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu("usuario");
		}
		/*
			RESPONSÁVEL POR REDIRECIONAR A PÁGINA PRO INDEX DE USUÁRIOS

			$page -> pagina atual
		*/
		public function index($page = FALSE, $tipo_usuario = FALSE, $data_registro_inicio = FALSE, $data_registro_fim = FALSE,
								$nome = FALSE, $email = FALSE, $ativo =FALSE, $data_nascimento_inicio = FALSE, $data_nascimento_fim = FALSE)
		{
			redirect("usuario/index");
		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO COM CAMPOS DE USUARIO + OS CAMPOS DE ALUNO

			$type -> contém um numero inteiro, que diz respeito ao tipo de usuário que se quer criar
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
		/*
			RESPONSÁVEL POR CARREGAR OS DADOS DE UM ALUNO QUE ESTÁ EDITANDO + OS DADOS DE USUARIO DO MESMO E EM SEGUIDA CARREGA O FORMULARIO
			EXBINDO ESTES DADOS

			$id -> id do usuario/aluno 
			$type -> grupo de usuario
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
		/*
			RESPONSÁVEL POR VALIDAR OS DADOS ANTES DE ENVIA-LO AO BANCO

			$dataToSave -> Contém os dados do aluno
		*/
		public function store_banco($dataToSave)
		{
			$matrícula = $this->Aluno_model->get_aluno_por_matricula($dataToSave['Matricula']);

			if(empty($matrícula) || $matrícula['Usuario_id'] == $dataToSave['Usuario_id'])
			{
				$this->Aluno_model->set_aluno($dataToSave);
				return "sucesso";
			}
			else
				return "O número de matrícula informado já está em uso para outro aluno";
		}
		/*
			RESPONSÁVEL POR CAPTAR OS DADOS SUBMETIDOS DO FORMULÁRIO
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
				'Grupo_id' => $this->input->post('grupo_id'),
				'Email_notifica_nova_conta' => $this->input->post('email_notifica_nova_conta')
			);

			$dataToSave['Data_nascimento'] = $this->convert_date($dataToSave['Data_nascimento'], "en");


			if(empty($dataToSave['Email_notifica_nova_conta']))
				$dataToSave['Email_notifica_nova_conta'] = 0;

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($dataToSave['Nome']))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_parent_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_parent_class($this)) == TRUE)
				{
				 	$resultado = parent::store_banco($dataToSave);

					if(is_numeric($resultado))
					{
						$dataToSaveAluno = array(
							'Usuario_id' => $resultado,
							'Matricula' => $this->input->post('matricula')
						);
						$resultado = $this->store_banco($dataToSaveAluno);
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
		/*
			RESPONSÁVEL POR EXIBIR TODOS OS ATRIBUTOS DO ALUNO.

			$id -> id do aluno
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
	}
?>