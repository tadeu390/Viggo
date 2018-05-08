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
			$this->load->model('Logs_model');
			$this->data['controller'] = strtolower(get_class($this));

		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE LOGIN NA TELA, CASO HAJA UMA SESSÃO ATIVA ELE AUTOMATICAMENTE
			REDIRECIONA O USUÁRIO PARA A TELA CORRETA.
		*/
		public function login()
		{
			unset($_SESSION['nome_primeiro_acesso']);//deleta a sessao utilizada para o primeiro acesso
			unset($_SESSION['email_primeiro_acesso']);//deleta a sessao utilizada para o primeiro acesso
			unset($_SESSION['id_primeiro_acesso']);//deleta a sessao utilizada para o primeiro acesso

			$this->data['title'] = 'Login';
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/login', $this->data);
			$this->load->view('templates/footer', $this->data);
			if($this->Account_model->session_is_valid()['status'] == "ok")
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
			QUANDO TROCA A SENHA NO PRIMEIRO ACESSO, O JS REDIRECIONA PRA CÁ, AI REDIRECIONA PARA A TELA DE LOGIN
		*/
		public function index()
		{
			redirect("account/login");
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


			if($this->Account_model->session_is_valid() == "ok")//verifica se ja existe uma sessao, caso sim apenas ira recarregar a pagina
				$login = 'valido';
			else if($login['rows'] > 0)
			{
				$this->Logs_model->set_log($login['Id']);
				if($login['Redefinir_senha'] == 0)
				{
					$this->set_sessao($login, $conectado);
					$login = 'valido';
				}
				else
				{
					$this->gera_codigo_ativacao($login['Id'], FALSE);
					$this->set_sessao_primeiro_acesso($login);
					$login = "primeiro_acesso";//para o js redirecionar
				}
			}
			else
				$login = 'invalido';

			$arr = array('response' => $login);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*
			RESPONSÁVEL POR GERAR O CÓDIGO DE ATIVAÇÃO PARA A CONTA EM QUESTÃO E PASSA O MESMO PARA A FUNÇÃO DE ENVIO DE EMAIL
		*/
		public function gera_codigo_ativacao($id, $redirect)
		{
			$this->Account_model->gera_codigo_ativacao($id);
			$Usuario = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);

			if($Usuario['Redefinir_senha'] == 1) //só envia email se ainda não foi redefinido a senha
				$this->envia_email_primeiro_acesso($Usuario);
			if($redirect != FALSE)
				redirect("account/primeiro_acesso");
		}
		/*
			RESPONSÁVEL POR ENVIAR O CÓDIGO DE ACESSO PARA O EMAIL DO USUÁRIO

			$Usuario -> Contém os dados do usupário que receberá o e-mail com ó código de ativação
		*/
		public function envia_email_primeiro_acesso($Usuario)
		{
			//enviar email para o usuario
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
		/*
			RESPONSÁVEL POR CRIAR A SESSÃO PARA A TELA DE TROCA DE SENHA NO PRIMEIRO ACESSO
			$Usuario -> Contém os dados do usuário que está fazendo seu primeiro acesso ao sistema
		*/
		public function set_sessao_primeiro_acesso($Usuario)
		{
			$primeiro_acesso = array(
				'id_primeiro_acesso'  => $Usuario['Id'],
				'nome_primeiro_acesso'  => $Usuario['Nome'],
				'email_primeiro_acesso'  => $Usuario['Email']
			);
			$this->session->set_userdata($primeiro_acesso);
		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE REDEFINIÇÃO DE SENHA QUANDO ESQUECER
		*/
		public function redefinir_senha()
		{
			$this->data['title'] = 'Redefinir senha';
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/redefinir_senha', $this->data);
			$this->load->view('templates/footer', $this->data);
		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE TROCA DE SENHA NO PRIMEIRO ACESSO
		*/
		public function primeiro_acesso()
		{
			if(empty($this->session->email_primeiro_acesso))
				redirect('account/login');

			$this->data['sessao_primeiro_acesso']['id_primeiro_acesso'] = $this->session->id_primeiro_acesso;
			$this->data['sessao_primeiro_acesso']['nome_primeiro_acesso'] = $this->session->nome_primeiro_acesso;
			$this->data['sessao_primeiro_acesso']['email_primeiro_acesso'] = $this->session->email_primeiro_acesso;

			$this->data['title'] = 'Primeiro acesso';
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/primeiro_acesso', $this->data);
			$this->load->view('templates/footer', $this->data);
		}
		/*
			RESPONSÁVEL POR RECEBER O CÓDIGO DE ATIVAÇÃO E A NOVA SENHA, VALIDAR O CÓDIGO DE ATIVAÇÃO
		*/
		public function altera_senha_primeiro_acesso()
		{
			$resultado = "";
			$codigo_ativacao = $this->input->post('codigo_ativacao');
			$nova_senha = $this->input->post('nova_senha');
			$usuario = $this->Usuario_model->get_usuario(FALSE, $this->session->id_primeiro_acesso, FALSE);
			
			if($usuario['Codigo_ativacao'] == $codigo_ativacao && $usuario['Redefinir_senha'] == 1)
			{
				$data = array(
					'Usuario_id' => $this->session->id_primeiro_acesso,
					'Valor' => $nova_senha
				);
				//atualiza a senha
				$this->Senha_model->set_senha($data);
				//valida novamente os dados de login (agora com a senha nova)
				$login = $this->Account_model->valida_login($this->session->email_primeiro_acesso, $nova_senha);

				//cria a sessao
				if($login['rows'] > 0)
					$this->set_sessao($login, 1);

				$this->Account_model->desativa_redef_senha($this->session->id_primeiro_acesso);

				$resultado = "sucesso";
			}
			else
				$resultado = "O código de ativação informado está incorreto";

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>