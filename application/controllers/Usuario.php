<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELATIVO AOS DADOS DO USUÁRIO
	*/
	class Usuario extends Geral 
	{
		/*
			CONSTRUTOR CARREGA OS MODELS E VERIFICAR A EXSISTÊNCIA DA SESSÃO DE USUÁRIO
		*/
		public function __construct()
		{
			parent::__construct();
			
			if(empty($this->account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');
			
			$this->load->model('Usuario_model');
			$this->load->model('Grupo_model');
			$this->load->model('Senha_model');
			$this->load->model('Logs_model');
			$this->load->model('Acesso_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*
			RESPONSÁVEL POR CARREGAR A LISTA DE USUÁRIOS

			$page -> número da página atual registros
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)//QUANDO A PÁGINA NÃO É ESPECIFICADA, POR DEFAULT CARREGA A PRIMEIRA PÁGINA
				$page = 1;
			
			$this->data['title'] = 'Administração - dashboard';

			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['usuarios'] = $this->Usuario_model->get_usuario(FALSE, FALSE, $page);
				$this->data['paginacao']['size'] = $this->data['usuarios'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				
				$this->view("usuario/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR OCULTAR UM REGISTRO DE USUÁRIO

			$id -> id de um usuário
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
				$this->Usuario_model->deletar($id);
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR EXIBIR TODOS OS ATRIBUTOS DE UM USUÁRIO.

			$id -> id de um usuário
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Usuário - Detalhes';
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				$this->data['obj']['Ultimo_acesso'] = $this->Logs_model->get_last_access_user($this->data['obj']['Id'])['Data_registro'];
				$this->view("usuario/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR CARREGAR NA TELA TODAS AS PERMISSÕES DOS MÓDULOS PRA UM DETERMINADO USUÁRIO. TAMBÉM PERMITE ALTERAR AS PERMISSÕES.

			$id -> id do usuário
		*/
		public function permissoes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Usuário - Permissões';
				$this->data['usuario_id'] = $id;
				$this->data['lista_usuario_acesso'] = $this->Acesso_model->get_acesso($id);
				$this->data['usuario'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE)['Nome_usuario'];
				$this->view("usuario/permissoes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR CAPTAR OS DADOS DE PERMISSÕES PARA TODOS OS MÓDULOS COM RELAÇÃO A UM DETERMINADO USUÁRIO
		*/
		public function store_permissoes()
		{
			if(!empty($this->input->post("usuario_id")))
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
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('usuario/index');
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DO USUÁRIO PARA CRIAR
		*/
		public function create()
		{
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, 0, FALSE);
				$this->data['title'] = 'Usuário - Cadastro';
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
				$this->view("usuario/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DO USUÁRIO PARA EDIÇÃO
			
			$id -> id de um usuário
		*/
		public function edit($id = FALSE)
		{
			if($id === FALSE)
				$id = $this->Account_model->session_is_valid()['id'];

			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Usuario - Cadastro';
				
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);
				
				$this->data['read'] = ""; //para deixar como somente leitura o campo de senha, caso o usuario logado seja um adm

				if($this->session->grupo_id > 1)
					$this->data['obj']['Senha'] = "";
				if($this->session->grupo_id == 1)
				{
					$this->data['obj']['Senha'] = "xxx";//qualquer coisa, so pra nao deixar o campo de senha vazio
					$this->data['read'] = "readonly='readonly'";
				}
				
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo(FALSE, FALSE, FALSE);
	
				$this->view("usuario/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RECEBER, CADASTRAR OU ATUALIZAR OS DADOS DE USUÁRIO NO BANCO DE DADOS
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('conta_ativa'),
				'Nome' => $this->input->post('nome'),
				'Email' => $this->input->post('email'),
				'Grupo_id' => $this->input->post('grupo_id')
			);

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($dataToSave['Nome']))
			 {
				if($this->Usuario_model->email_valido($dataToSave['Email'],$dataToSave['Id']) == "invalido")
					$resultado = "O e-mail informado já está em uso.";
				else if($this->session->Grupo_id > 1 && 
					$this->Senha_model->get_senha($dataToSave['Id'])['Senha'] != $dataToSave['Senha'])
					$resultado = "A senha atual fornecida é inválida";
				else
				{
					$Usuario_id = $this->Usuario_model->set_usuario($dataToSave);

					if($dataToSave['Id'] >= 1)//somente se estiver editando
					{
						//SE O CAMPO CONTER ALGO ENTÃO SIGNIFICA QUE A SENHA DEVE SER ALTERADA.
						if($this->input->post('nova_senha') != NULL && !empty($this->input->post('nova_senha')))
						{
							$data = array(
								'Usuario_id' => $Usuario_id,
								'Valor' => $this->input->post('nova_senha')
							);
							$this->Senha_model->set_senha($data);
						}
					}
					else
					{
						$data = array(
							'Usuario_id' => $Usuario_id,
							'Valor' => $this->input->post('senha')
						);
						$this->Senha_model->set_senha($data);
					}
				}
			 }
			 else
				redirect('admin/dashboard');
			
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>