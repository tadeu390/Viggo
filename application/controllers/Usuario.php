<?php
	require_once("Geral.php");

	class Usuario extends Geral {
		public function __construct()
		{
			parent::__construct();
			
			if(empty($this->Account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');
			
			$this->load->model('Usuario_model');
			$this->load->model('Grupo_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		
		/*
			Renderiza o dashboard
		*/
		public function index($page = false)
		{
			if($page === false)
				$page = 1;
			
			$this->data['title'] = 'Administração - dashboard';

			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['usuarios'] = $this->Usuario_model->get_usuario(false,$page);
				$this->data['paginacao']['size'] = $this->data['usuarios'][0]['size'];
				$this->data['paginacao']['pg_atual'] = $page;
				
				$this->view("usuario/index",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE,get_class($this)) == true)
				$this->Usuario_model->deletar($id);
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function detalhes($id = false)
		{
			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == true)
			{
				$this->data['title'] = 'Usuario - Detalhes';
				$this->data['obj'] = $this->Usuario_model->get_usuario($id);
				$this->view("usuario/detalhes",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function create()
		{
			if($this->Geral_model->get_permissao(CREATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Usuario_model->get_usuario(0);
				$this->data['title'] = 'Usuario - Cadastro';
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo();
				$this->view("usuario/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function edit($id = false)
		{
			if($id === false)
				$id = $this->Account_model->session_is_valid()['id'];

			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == true)
			{
				$this->data['title'] = 'Usuario - Cadastro';
				
				$this->data['obj'] = $this->Usuario_model->get_usuario($id);
				
				$this->data['read'] = ""; //para deixar como somente leitura o campo de senha, caso o usuario logado seja um adm
				if($id > 0 && $this->Usuario_model->get_grupo($this->session->id) > 1)
					$this->data['obj']['senha'] = "";
				if($id > 0 && $this->Usuario_model->get_grupo($this->session->id) == 1)
				{
					$this->data['obj']['senha'] = "xxx";//qualquer coisa, so pra nao deixar o campo de senha vazio
					$this->data['read'] = "readonly='readonly'";
				}
				
				$this->data['grupos_usuario'] = $this->Grupo_model->get_grupo();
	
				$this->view("usuario/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'id' => $this->input->post('id'),
				'ativo' => $this->input->post('conta_ativa'),
				'nome' => $this->input->post('nome'),
				'email' => $this->input->post('email'),
				'senha' => $this->input->post('senha'),
				'grupo_id' => $this->input->post('grupo_id')
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['nome']))
			 {
				if($this->Usuario_model->email_valido($dataToSave['email'],$dataToSave['id']) == "invalido")
					$resultado = "O e-mail informado já está em uso.";
				else if($this->Usuario_model->get_grupo($this->session->id) > 1 && 
					$this->Usuario_model->get_usuario($dataToSave['id'])['senha'] != $dataToSave['senha'])
					$resultado = "A senha atual fornecida é inválida";
				else
				{
					// a senha nunca é carregada pra tela, portanto é necessiário carrega-la do banco
					//antes de fazer um update
					if($dataToSave['id'] >= 1)//somente se estiver editando
					{
						//se o campo conter algo significa que a senha deve ser alterado, entao ja carrega a senha
						//com o que vir do campo nova_senha no formulario
						if($this->input->post('nova_senha') != null && !empty($this->input->post('nova_senha')))
							$dataToSave['senha'] = $this->input->post('nova_senha');
						else
							$dataToSave['senha'] = $this->Usuario_model->get_usuario($dataToSave['id'])['senha'];
					}
					$this->Usuario_model->set_usuario($dataToSave);
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