<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELACIONADO AO ACESSO DOS USUÁRIOS
	*/
	class Account extends Geral 
	{
		//NO CONSTRUTOR DA CLASSE CARREGA AS MODELS UTILIZADAS NO CONTROLLER, ENTRE OUTRAS CONFIGURAÇÕES
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Account_model');
			$this->load->model('Logs_model');
			$this->data['controller'] = 'Account';

		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE LOGIN NA TELA, CASO HAJA UMA SESSÃO ATIVA ELE AUTOMATICAMENTE
			REDIRECIONA O USUÁRIO PARA A TELA CORRETA.
		*/
		public function login()
		{
			$this->data['title'] = 'Login';
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/login', $this->data);
			$this->load->view('templates/footer', $this->data);
			if($this->account_model->session_is_valid()['status'] == "ok")
				redirect('admin/dashboard');
		}
		/*
			RESPONSÁVEL POR APAGAR TODAS AS SESSÕES ATIVAS NO COMPUTADOR DO CLIENTE
		*/
		public function logout()
		{
			unset($_SESSION['id']);
			unset($_SESSION['grupo_id']);
			delete_cookie ('id');
			delete_cookie ('grupo_id');
		}
		/*
				hmmm... deve ter um motivo pra existir isso, verificar depois
		*/
		public function index()
		{
			redirect("Account/login");
		}
		/*
			REPONSÁVEL POR REALIZAR TODAS AS VALIDAÇÕES DO LOGIN
		*/
		public function validar()
		{
			$email = $this->input->post('email-login');
			$senha = $this->input->post('senha-login');
			$conectado = $this->input->post('conectado');

			$login = $this->Account_model->valida_login($email, $senha);
			$data['title'] = 'Login';


			if($this->account_model->session_is_valid() == "ok")//verifica se ja existe uma sessao, caso sim apenas ira recarregar a pagina
				$login = 'valido';
			else if($login['rows'] > 0)
			{
				$this->Logs_model->set_log($login['Id']);
				$this->set_sessao($login, $conectado);
				$login = 'valido';
			}
			else
				$login = 'invalido';

			$arr = array('response' => $login);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*
			RESPONSÁVEL POR CRIAR TODAS AS SESSÕES DO LOGIN
			
			$Usuario -> objeto Usuário 
			$conecatdo -> flag que pega o status do campo Manter conectado na View de login
		*/
		public function set_sessao($Usuario, $conectado)
		{
			if($conectado == 1)
			{
				$cookie = array(
		            'name'   => 'id',
		            'value'  => $Usuario['Id'],
		            'expire' => 100000000,
		            'secure' => FALSE
	            );
		  		$this->input->set_cookie($cookie);

		  		$cookie = array(
		            'name'   => 'grupo_id',
		            'value'  => $Usuario['Grupo_id'],
		            'expire' => 100000000,
		            'secure' => FALSE
		            );
		  		$this->input->set_cookie($cookie);
	  		}
	  		else
	  		{
	  			$login = array(
					'id'  => $Usuario['Id'],
					'grupo_id'  => $Usuario['Grupo_id']
					);
				$this->session->set_userdata($login);
	  		}
		}
	}
?>