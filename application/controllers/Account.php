<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELACIONADO AO ACESSO DOS USUÁRIOS
	*/
	
	define("LIMITE_TENTATIVA", 3);//TENTATIVAS CONSECUTIVAS
	define("TEMPO_ESPERA", 60);//TEMPO NECESSÁRIO PARA DESBLOQUEAR A CONTA DO USUÁRIO AO EXCEDER O LIMITE ACIMA (EM SEGUNDOS)
	
	class Account extends Geral 
	{
		//NO CONSTRUTOR DA CLASSE CARREGA AS MODELS UTILIZADAS NO CONTROLLER, ENTRE OUTRAS CONFIGURAÇÕES
		public function __construct()
		{
			parent::__construct();
			$this->load->model('Logs_model');
			$this->data['controller'] = strtolower(get_class($this));
		}

		public function teste_template()
		{
			$this->data['codigo'] = 133412;
			$this->data['Nome_usuario'] = 133412;
			$this->data['url'] = base_url();

			$this->load->view('templates/email_primeiro_acesso', $this->data);
		}
		
		public function teste_template_r()
		{
			$this->data['codigo'] = 133412;
			$this->data['Nome'] = 'Tadeu';
			$this->data['url'] = base_url();
			$this->data['Id'] = 1;
			
			$this->load->view('templates/email_redefinir_senha', $this->data);
		}

		public function teste_template_u()
		{
			$this->data['codigo'] = 133412;
			$this->data['Nome_usuario'] = 'Tadeu';
			$this->data['Email'] = 'tadeu.390@gmail.com';
			$this->data['Valor'] = '123@mudar';
			$this->data['url'] = base_url();
			$this->data['Id'] = 1;
			
			$this->load->view('templates/email_nova_conta', $this->data);
		}
		/*
			RESPONSÁVEL POR CARREGAR OS DADOS DE USUARIO PARA QUALQUER USUARIO QUE NÃO FOR ADMINISTRADOR
		*/
		public function meus_dados()
		{
			$sessao = $this->Account_model->session_is_valid();
			if($sessao['status'] == "ok")
			{
				$this->set_menu();
				$this->data['title'] = 'Meus dados';
				$this->data['obj'] = $this->Usuario_model->get_usuario(FALSE, $sessao['id'], FALSE);
				$this->data['obj']['Ultimo_acesso'] = $this->Logs_model->get_last_access_user($this->data['obj']['Id'])['Data_registro'];
				$this->view("account/meus_dados", $this->data);
			}
			else
				redirect("account/login");
		}
		/*
#################### AQUI COMEÇA OS MÉTODOS REFERENTES AO LOGIN DO USUÁRIO

			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE LOGIN NA TELA, CASO HAJA UMA SESSÃO ATIVA ELE AUTOMATICAMENTE
			REDIRECIONA O USUÁRIO PARA A TELA CORRETA.
		*/
		public function login($url_redirect = FALSE)
		{
			$this->data['title'] = 'CEP - Login';
			$this->data['url_redirect'] = $url_redirect;
			
			$this->limpa_sessao_troca_senha();
			
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/login', $this->data);
			$this->load->view('templates/footer', $this->data);
			
			if($this->Account_model->session_is_valid()['status'] == "ok")
				redirect('academico/dashboard');
			$this->logout();
		}
		/*
			RESPONSÁVEL POR APAGAR TODAS AS SESSÕES ATIVAS NO COMPUTADOR DO CLIENTE
		*/
		public function logout()
		{
			unset($_SESSION['id']);
			unset($_SESSION['grupo_id']);
			unset($_SESSION['token']);
			delete_cookie ('id');
			delete_cookie ('token');
			delete_cookie ('page');
			delete_cookie('url_redirect');
			delete_cookie ('grupo_id');
		}
		/*
			RESPONSÁVEL ELIMINAR AS SESSÕES UTILIZADAS NA TROCA DE SENHA TANTO NO PRIMEIRO ACESSO QUANTO PARA REDEFINIÇÃO DE SENHA
		*/
		public function limpa_sessao_troca_senha()
		{
			unset($_SESSION['id_troca_senha']);//deleta a sessao utilizada para o primeiro acesso ou para a redefinição de senha
			unset($_SESSION['email_troca_senha']);//deleta a sessao utilizada para o primeiro acesso ou para a redefinição de senha
			unset($_SESSION['nome_troca_senha']);//deleta a sessao utilizada para o primeiro acesso ou para a redefinição de senha
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


			if($this->Account_model->session_is_valid()['status'] == "ok")//verifica se ja existe uma sessao, caso sim apenas ira recarregar a pagina
				$login = 'valido';
			else if($login['rows'] > 0 && $this->valida_data_hashing($login['Valor'], $senha) == TRUE)
			{
				$this->Logs_model->set_log($login['Id']);
				
				if($login['Ativo'] == 0 || $login['g_ativo'] == 0)
					$login = "Conta desativada. Entre em contato com a sua escola para mais detalhes.";
				else if($this->Senha_model->get_senha($login['Id'])['rows'] < 2)
				{
					$this->gera_codigo_ativacao($login['Id'], FALSE);
					$this->set_sessao_troca_senha($login);
					$login = "primeiro_acesso";//para o js redirecionar
				}	
				else
				{
					$this->set_sessao($login, $conectado);
					$this->Account_model->reset_auxiliar_login($login['Id']);
					$login = 'valido';
				}
			}
			else
			{
				$login = "E-mail e/ou senha inválidos";
				$id = $this->Usuario_model->get_usuario_por_email($email)['Id'];
				if($this->Account_model->tentativas_erro($id) >= LIMITE_TENTATIVA)
					$login = "Conta temporariamente bloqueada, pois você antingiu o limite de tentativas. Tente novamente daqui alguns minutos.";
			}

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

		  		$cookie = array(
		            'name'   => 'token',
		            'value'  => $Usuario['Valor'],
		            'expire' => 100000000,
		            'secure' => FALSE
		            );
		  		$this->input->set_cookie($cookie);
	  		}
	  		else
	  		{
	  			$login = array(
					'id'  => $Usuario['Id'],
					'grupo_id'  => $Usuario['Grupo_id'],
					'token'  => $Usuario['Valor'],
					);
				$this->session->set_userdata($login);
	  		}
		}
		/*
#################### AQUI COMEÇA OS MÉTODOS REFERENTES AO PROCESSO DE PRIMEIRA ACESSO
					 DO USUÁRIO AO SISTEMA

			RESPONSÁVEL POR GERAR O CÓDIGO DE ATIVAÇÃO PARA A CONTA EM QUESTÃO E PASSA O MESMO PARA A FUNÇÃO DE ENVIO DE EMAIL
		*/
		public function gera_codigo_ativacao($id, $redirect)
		{
			$codigo = $this->Account_model->gera_codigo_ativacao($id);
			$Usuario = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);

			if($Usuario['Status'] == 1 && $this->Account_model->session_is_valid()['status'] != "ok") //só envia email se ainda não foi redefinido a senha e se não houver ninguém já conectado
				$this->envia_email_primeiro_acesso($Usuario, $codigo);
			if($redirect != FALSE)
				redirect("account/primeiro_acesso");
		}
		/*
			RESPONSÁVEL POR ENVIAR O CÓDIGO DE ACESSO PARA O EMAIL DO USUÁRIO

			$Usuario -> Contém os dados do usupário que receberá o e-mail com ó código de ativação
			$codigo -> Contém o código gerado para o usuário que está tentando ativar sua conta
		*/
		public function envia_email_primeiro_acesso($Usuario, $codigo)
		{
			$this->email->from($this->Configuracoes_email_model->get_configuracoes_email()['Email'], 'CEP - Centro de Educação Profissional "Tancredo Neves"');
			$this->email->to($Usuario['Email']);
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');
			//$mensagem = "Este é o seu primeiro acesso a sua conta. Segue abaixo o seu código de ativação. <br /><br /> Código: <b>".$codigo."</b>";
			$Usuario['codigo'] = $codigo;
			$Usuario['url'] =  base_url();

			$mensagem = $this->load->view("templates/email_primeiro_acesso", $Usuario, TRUE);
			$this->email->subject('Ativação da sua conta');
			$this->email->message($mensagem);

			$this->email->send();
		}
		/*
			RESPONSÁVEL POR CRIAR A SESSÃO PARA A TELA DE TROCA DE SENHA NO PRIMEIRO ACESSO OU NA
			REDEFINIÇÃO DA SENHA QUANDO ESQUECER
			$Usuario -> Contém os dados do usuário que está fazendo seu primeiro acesso ao sistema
		*/
		public function set_sessao_troca_senha($Usuario)
		{
			$troca_senha = array(
				'id_troca_senha'  => $Usuario['Id'],
				'nome_troca_senha'  => $Usuario['Nome_usuario'],
				'email_troca_senha'  => $Usuario['Email']
			);
			$this->session->set_userdata($troca_senha);
		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE TROCA DE SENHA NO PRIMEIRO ACESSO
		*/
		public function primeiro_acesso()
		{
			$sessao_troca_senha = $this->Account_model->session_is_valid_troca_senha();
			//nesse caso nao precisa de verificar se há alguem logado, pois quando alguém loga, todas as sessoes de redefinicao de senha sao apagadas
			//sendo assim, ao passar pela verificação abaixo, a mesma falhará e assim redirecionando o usuário para a tela de login

			if(empty($sessao_troca_senha))//se nenhuma sessão válida foi encontrada, redireciona para o login
				redirect('account/login');

			$Usuario = $this->Usuario_model->get_usuario(FALSE, $sessao_troca_senha['id_troca_senha'], FALSE);
			$this->data['sessao_primeiro_acesso'] = $sessao_troca_senha;
			$this->data['title'] = 'CEP - Primeiro acesso';
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
			if($this->Account_model->session_is_valid()['status'] == "ok")//se alguém já estiver logado, cancela esta operação
				$resultado = "sucesso";
			else
			{
				$sessao_troca_senha = $this->Account_model->session_is_valid_troca_senha();
				$codigo_ativacao = $this->input->post('codigo_ativacao');
				$nova_senha = $this->input->post('nova_senha');

				$usuario = $this->Usuario_model->get_usuario(FALSE, $sessao_troca_senha['id_troca_senha'], FALSE);
				
				
				if($usuario['Codigo_ativacao'] != 0 && $usuario['Codigo_ativacao'] == $codigo_ativacao)
				{
					$data = array(
						'Usuario_id' => $sessao_troca_senha['id_troca_senha'],
						'Valor' => $this->hashing($nova_senha)
					);
					//atualiza a senha
					$this->Senha_model->set_senha($data);
					//valida novamente os dados de login (agora com a senha nova)
					$login = $this->Account_model->valida_login($sessao_troca_senha['email_troca_senha'], $nova_senha);

					//cria a sessao
					if($login['rows'] > 0)
						$this->set_sessao($login, 0);

					$this->Account_model->reset_auxiliar_login($sessao_troca_senha['id_troca_senha']);

					$resultado = "sucesso";
				}
				else
				{
					$resultado = "O código de ativação informado está incorreto";
					if($this->Account_model->tentativas_erro($sessao_troca_senha['id_troca_senha']) == LIMITE_TENTATIVA)
					{
						$this->gera_codigo_ativacao($sessao_troca_senha['id_troca_senha'], FALSE);
						$resultado = "Limite de tentativas para o código gerado foi excedido. Um novo código de ativação foi enviado para o seu e-mail.";	
					}
				}
			}

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*
#################### AQUI COMEÇA OS MÉTODOS REFERENTES A REDEFINIÇÃO DE SENHA QUANDO
					 O USUÁRIO A ESQUECE.

			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE REDEFINIÇÃO DE SENHA QUANDO ESQUECER
		*/
		public function redefinir_senha()
		{
			if($this->Account_model->session_is_valid()['status'] == "ok")//se alguém já estiver logado, cancela esta operação
				redirect('academico/dashboard');
			$this->data['title'] = 'CEP - Alterar senha';
			$this->load->view('templates/header', $this->data);
			$this->load->view('account/redefinir_senha', $this->data);
			$this->load->view('templates/footer', $this->data);
		}
		/*
			RESPONSÁVEL POR VALIDAR O EMAIL QUE O USUARIO SOLICITOU A REDEFINIÇÃO DA SENHA
			SE EXISTIR ELE ENVIA UM EMAIL COM O LINK, SE NÃO, ELE APENAS NOTIFICA O USUARIO
			SOBRE A INEXISTÊNCIA DO E-MAIL
		*/
		public function valida_redefinir_senha()
		{
			$resultado = "sucesso";
			$email = $this->input->post('email');
			$Usuario = $this->Usuario_model->get_usuario_por_email($email);

			if(empty($Usuario))
				$resultado = "Este e-mail não se encontra cadastrado em nosso sistema";
			else
			{
				$codigo = $this->Account_model->gera_codigo_alteracao($Usuario['Id']);
				$this->envia_email_redefinir_senha($Usuario, $codigo);
			}

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*
			RESPONSÁVEL POR ENVIAR UM E-MAIL COM UM LINK PARA O USUÁRIO PODER REDEFINIR A SUA
			SENHA

			$Usuario -> objeto usuário que contém todas as informações do mesmo
			$codigo -> código de alteração de senha gerado
		*/
		public function envia_email_redefinir_senha($Usuario, $codigo)
		{
			$this->email->from($this->Configuracoes_email_model->get_configuracoes_email()['Email'], 'CEP - Centro de Educação Profissional "Tancredo Neves"');
			$this->email->to($Usuario['Email']);
			//$mensagem = "Você solicitou a alteração da sua senha. Segue abaixo o link para que possa efetuar a ação";

			//$mensagem = $mensagem."<br /> <a href='".$this->data['url']."account/alterando_senha/".$Usuario['Id']."/".$codigo."'>
			//".$this->data['url']."account/alterando_senha/".$Usuario['Id']."/".$codigo."</a>";
			$this->email->subject('Alteração de senha');
			
			$Usuario['url'] =  base_url();
			$Usuario['codigo'] =  $codigo;

			$mensagem = $this->load->view("templates/email_redefinir_senha", $Usuario, TRUE);
			
			$this->email->message($mensagem);

			$this->email->send();	
		}
		/*
			RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE REDEFINIÇÃO DE SENHA QUANDO ESQUECER, É ONDE O USUÁRIO INSERE A NOVA SENHA
			
			$id -> id do usuário
			$codigo -> codigo de alteração de senha gerado na solicitação da alteração
		*/
		public function alterando_senha($id, $codigo)//PEGAR TAMBEM ID PARA GERAR A SESSAO(criptografar)
		{
			$Usuario = $this->Usuario_model->get_usuario(FALSE, $id, FALSE);

			if($codigo != $Usuario['Codigo_ativacao'] || $Usuario['Status'] != 2 || $this->Account_model->session_is_valid()['status'] == "ok")
				redirect('account/login');

			$this->set_sessao_troca_senha($Usuario);
			$sessao_troca_senha = $this->Account_model->session_is_valid_troca_senha();
			$this->data['sessao_primeiro_acesso'] = $sessao_troca_senha;

			$this->data['title'] = 'CEP - Altere sua senha';

			$this->load->view('templates/header', $this->data);
			$this->load->view('account/alterando_senha', $this->data);
			$this->load->view('templates/footer', $this->data);
		}
		/*
			RESPONSÁVEL POR CAPTURAR A SENHA NOVA INSERIDA NO FORMULÁRIO
		*/
		public function alterar_senha()
		{
			$resultado = "sucesso";
			if($this->Account_model->session_is_valid()['status'] != "ok")
			{
				$nova_senha = $this->input->post('nova_senha');
				$sessao_troca_senha = $this->Account_model->session_is_valid_troca_senha();
				
				if(!empty($sessao_troca_senha))
				{
					$Usuario = $this->Usuario_model->get_usuario(FALSE, $sessao_troca_senha['id_troca_senha'], FALSE);
					$senha = array(
						'Usuario_id' => $Usuario['Id'],
						'Valor' => $this->hashing($nova_senha)
					);
					$this->Senha_model->set_senha($senha);

					$login = $this->Account_model->valida_login($sessao_troca_senha['email_troca_senha'], $nova_senha);

					//cria a sessao
					if($login['rows'] > 0)
						$this->set_sessao($login, 0);

					$this->Account_model->reset_auxiliar_login($sessao_troca_senha['id_troca_senha']);
				}
			}
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>