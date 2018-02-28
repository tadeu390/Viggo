<?php
	require_once("Geral.php");
	class Account extends Geral {
		/*
			no construtor carregamos as bibliotecas necessarias e tambem nossa model
		*/
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Usuario_model');
			$this->data['controller'] = 'Account';
		}
		
		/*
			metodo responsavel carregar o formulario de login
		*/
		public function login()
		{
			$this->data['title'] = 'Login';
			$this->load->view('templates/header',$this->data);
			$this->load->view('account/login',$this->data);
			$this->load->view('templates/footer',$this->data);
			if($this->Account_model->session_is_valid()['status'] == "ok")
				redirect('admin/dashboard');
		}
		/*
			destroi a sessao de login
		*/
		public function logout()
		{
			unset($_SESSION['id']);
			unset($_SESSION['grupo_id']);
			delete_cookie ('id');
			delete_cookie ('grupo_id');
		}
			
		public function index()
		{
			redirect("Account/login");
		}
		/*
			Valida os dados de login
		*/
		public function validar()
		{
			$email = $this->input->post('email-login');
			$senha = $this->input->post('senha-login');
			$conectado = $this->input->post('conectado');

			$response = $this->Account_model->get_login($email,$senha);
			$data['title'] = 'Login';
			
			
			if($this->Account_model->session_is_valid() == "ok")//verifica se ja existe uma sessao, caso sim apenas ira recarregar a pagina
				$response = 'valido';
			else if(!empty($response)) //se os dados do login forem validados com sucesso entao cria o cookie ou a sessao
			{
				if($conectado == 1)
				{
					 $cookie = array(
                        'name'   => 'id',
                        'value'  => $response['id'],                            
                        'expire' => 100000000,                                                                                   
                        'secure' => FALSE
                        );
              		$this->input->set_cookie($cookie);

              		 $cookie = array(
                        'name'   => 'grupo_id',
                        'value'  => $response['grupo_id'],                            
                        'expire' => 100000000,                                                                                   
                        'secure' => FALSE
                        );
              		$this->input->set_cookie($cookie);
				}
				else
				{
					$login = array(
					   'id'  => $response['id'],
					   'grupo_id'  => $response['grupo_id']
					);
					$this->session->set_userdata($login);
				}
				$response = 'valido';
			}
			else
				$response = 'invalido';

			$arr = array('response' => $response);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}

		public function registrar()
		{
			$this->data['obj'] = $this->Usuario_model->get_usuario(0);
			$this->data['title'] = 'Usuario - Cadastro';
			$this->load->view('templates/header',$this->data);
			$this->load->view("account/cadastro",$this->data);
			$this->load->view('templates/footer',$this->data);
		}

		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'id' => $this->input->post('id'),
				'ativo' => 0,
				'nome' => $this->input->post('nome'),
				'email' => $this->input->post('email'),
				'senha' => $this->input->post('senha'),
				'grupo_id' => USUARIO_PADRAO
			);
			if(!empty($dataToSave['nome']))
			{
				if($this->Usuario_model->email_valido($dataToSave['email'],$dataToSave['id']) == "invalido")
					$resultado = "O e-mail informado já está em uso.";
				else
					$this->Usuario_model->set_usuario($dataToSave);
			}
			else
				redirect('account/login');

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>