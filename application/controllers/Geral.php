<?php
	/*!
	*	ESTA CLASSE FORNECE RECURSOS GENÉRICOS QUE SÃO REUTILIZADOS EM DIVERSAS PARTES DO SISTEMA.
	*/
	//SETA OS TIPOS DE PERMISSÕES NO SISTEMA.
	define("CREATE", 'Criar');
	define("READ", 'Ler');
	define("UPDATE", 'Atualizar');
	define("DELETE", 'Remover');
	
	define("ADMIN", 1);
	define("SECRETARIA", 3);
	class Geral extends CI_Controller 
	{
		//VARIAVEL RESPONSÁVEL POR ARMAZENZAR TODO O CONTEÚDO A SER EXIBIDO NAS VIEWS.
		protected $data;
		/*!
		*	CONSTRUTOR RESPONSÁVEL POR CARREGAR BIBLIOTECAS E MODELS UTILIZADAS.
		*/
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('Configuracoes_model');

			define("ITENS_POR_PAGINA", $this->Configuracoes_model->get_configuracoes()['Itens_por_pagina']);

			$this->load->library('pdfgenerator');
			$this->load->model('Account_model');
			$this->load->model('Usuario_model');
			$this->load->model('Menu_model');
			$this->load->model('Modulo_model');
			$this->load->model('Acesso_padrao_model');
			$this->load->model('Senha_model');
			$this->load->model('Acesso_model');
			$this->load->model('Geral_model');
			$this->load->helper('url_helper');
			$this->load->helper('url');
			$this->load->helper('html');
			$this->load->helper('form');
			$this->load->model('Configuracoes_email_model');
			$this->load->library('session');
			$this->load->library('email');
			$this->load->helper('cookie');
			$this->data['url'] = base_url();
			$this->data['paginacao']['url'] = base_url();
			$this->data['paginacao']['itens_por_pagina'] = ITENS_POR_PAGINA;
			$this->data['usuario'] = $this->Usuario_model->get_usuario(1, $this->Account_model->session_is_valid()['id'], FALSE)['Nome_usuario'];

			$this->config_email_server();
		}
		/*!
		*	RESPONSÁVEL POR CONFIGURAR A CLASSE DE E-MAIL PARA QUE O SISTEMA POSSA REALIZAR 
		*	O ENVIO DE E-MAILS QUANDO NECESSÁRIO.
		*/
		public function config_email_server()
		{
			$config_email = $this->Configuracoes_email_model->get_configuracoes_email();
			$config['protocol'] = $config_email['Protocolo'];
			//$config['smtp_crypto'] = 'tls';
			//$config['mailpath'] = '/usr/sbin/sendmail';
			$config['smtp_host'] = $config_email['Host'];
			$config['smtp_port'] = $config_email['Porta'];
			$config['mailtype'] = 'html';
			$config['user'] = $config_email['Usuario'];
			$config['pass'] = $config_email['Senha'];
			
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;

			$this->email->initialize($config);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR OS MENUS E OS MÓDULOS DO SISTEMA PARA QUE POSSA 
		*	MONTAR O MENU NA TELA.
		*/
		public function set_menu()
		{
			$this->data['menu'] = $this->Menu_model->get_menu_acesso();
			$this->data['modulo'] = $this->Acesso_model->get_modulo_acesso();
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A VIEW COM OS DADOS PASSADOS.
		*	
		*	$v -> Endereço da View que receberá o conteúdo e que será carregada na tela para o usuário.
		*	$dt -> Dados para a View.
		*/
		public function view($v, $dt)
		{
			$dt['title'] = "CEP - ".$dt['title']; 
			
			$this->load->view('templates/header_admin', $dt);
			$this->load->view($v, $dt);
			$this->load->view('templates/footer', $dt);
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE CONTENDO A PÁGINA ATUAL DA LISTAGEM DE REGISTROS
		*	DE QUALQUER MÓDULO DO SISTEMA. ISSO AUXILIA NO REDIRECIONAMENTO PARA A PÁGINA CORRETA AO
		*	EDITAR UM DETERMINADO REGISTRO DE UM DETERMINADO MÓDULO, OU SEJA, SE O USUÁRIO CLICAR NO BOTÃO
		*	EDITAR, DE UM REGISTRO NA PÁGINA X, AO SALVAR AS ALTERAÇÕES O SISTEMA UTILIZARÁ O COOKIE PARA
		*	REDIRECIONAR O USUÁRIO DE VOLTA PARA A PÁGINA X.
		*	
		*	$page -> Página atual da listagem de registros.
		*/
		public function set_page_cookie($page)
		{
			$cookie = array(
		            'name'   => 'page',
		            'value'  => $page,
		            'expire' => 100000000,
		            'secure' => FALSE
	            );
		  	$this->input->set_cookie($cookie);
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE QUE CONTÉM A URL EM QUE O USUÁRIO ESTÁ TRABALHANDO, 
		*	É NECESSÁRIO QUANDO A SESSÃO EXPIRA E REDIRECIONA O USUÁRIO 
		*	PRA TELA DE LOGIN, COM ISSO AO FAZER O LOGIN NOVAMENTE, ELE CONTINUA DE ONDE ESTAVA.
		*
		*	$url_redirect -> Url em que o usuário está.
		*/
		public function set_url_redirect_cookie($url_redirect)///NÃO CONCLUÍDO AINDA.
		{
			$cookie = array(
				'name' => 'url_redirect',
				'value' => $url_redirect,
				'expire' => 100000000,
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie);
		}
		/*!
		*	RESPONSÁVEL POR CONVERTER UMA DATA DE UM FORMATO PARA OUTRO.
		*
		*	$data -> Data que se deseja converter.
		*	$region -> Indica a região para se saber para qual formato converter.
		*/
		public function convert_date($data, $region)
		{
			$dataTemp = "";
			if(!empty($data) && $region == "en")
			{
				$dataTemp = str_replace("/", "-", $data);

				$dataTemp = explode("-", $dataTemp);

				$dataTemp = $dataTemp[2]."-".$dataTemp[1]."-".$dataTemp[0];
			}
			else if(!empty($data) && $region == "pt")
			{
				$dataTemp = str_replace("-", "/", $data);

				$dataTemp = explode("/", $dataTemp);

				$dataTemp = $dataTemp[2]."/".$dataTemp[1]."/".$dataTemp[0];	
			}
			return $dataTemp;
		}
		/*!
		*	RESPONSÁVEL POR CRIPTOGRAFAR QUALQUER INFORMAÇÃO E RETORNA-LA.
		*	
		*	$data -> Contém a string a ser criptografada.
		*/
		public function hashing($data)
		{
			return password_hash($data, PASSWORD_DEFAULT);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR SE UMA STRING CORRESPONDE A UMA HASH.
		*	
		*	$hash -> Contém a hash.
		*	$data -> Contém uma string que será verificada se corresponde a hash informada.
		*/
		public function valida_data_hashing($hash, $data)
		{
			if (password_verify($data, $hash))
				return 1;
			else
				return 0;
		}
	}
?>