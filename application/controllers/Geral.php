<?php
	/*
		ESTA CLASSE FORNECE RECURSOS GENÉRICOS QUE SÃO REUTILIZADOS EM DIVERSAS PARTES DO SISTEMA
	*/

	//SETA OS TIPOS DE PERMISSÕES NO SISTEMA
	define("CREATE", 'Criar');
	define("READ", 'Ler');
	define("UPDATE", 'Atualizar');
	define("DELETE", 'Remover');
	
	define("ADMIN", 1);
	class Geral extends CI_Controller 
	{
		//VARIAVEL RESPONSÁVEL POR ARMAZENZAR TODO O CONTEÚDO A SER EXIBIDO NAS VIEWS
		protected $data;
		/*
			CONSTRUTOR RESPONSÁVEL POR CARREGAR BIBLIOTECAS E MODELS UTILIZADAS
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
			$this->load->library('session');
			$this->load->library('email');
			$this->load->helper('cookie');
			$this->data['url'] = base_url();
			$this->data['paginacao']['url'] = base_url();
			$this->data['paginacao']['itens_por_pagina'] = ITENS_POR_PAGINA;
			$this->data['usuario'] = $this->Usuario_model->get_usuario(1, $this->Account_model->session_is_valid()['id'], FALSE)['Nome_usuario'];

			$this->config_email_server();
		}
		public function config_email_server()
		{
			$config['protocol'] = 'smtp';
			//$config['smtp_crypto'] = 'tls';
			//$config['mailpath'] = '/usr/sbin/sendmail';
			$config['smtp_host'] = '127.0.0.1';
			$config['mailtype'] = 'html';
			$config['user'] = 'tadeu_local';
			$config['pass'] = '123456789';
			
			$config['charset'] = 'utf-8';
			$config['wordwrap'] = TRUE;

			$this->email->initialize($config);
		}
		/*
			RESPONSÁVEL POR CARREGAR OS MENUS E OS MÓDULOS DO SISTEMA
		*/
		public function set_menu()
		{
			$this->data['menu'] = $this->Menu_model->get_menu_acesso();
			$this->data['modulo'] = $this->Acesso_model->get_modulo_acesso();
		}
		/*
			RESPONSÁVEL POR CARREGAR A VIEW COM OS DADOS PASSADOS
			
			$v -> Endereço da View que receberá o conteúdo e que será carregada na tela para o usuário
			$dt -> Dados para a View
		*/
		public function view($v, $dt)
		{
			$dt['title'] = "CEP - ".$dt['title']; 
			
			$this->load->view('templates/header_admin', $dt);
			$this->load->view($v, $dt);
			$this->load->view('templates/footer', $dt);
		}
		/*
			RESPONSÁVEL POR CRIAR UM COOKIE CONTENDO A PÁGINA ATUAL DA LISTAGEM DE REGISTROS
			DE QUALQUER MÓDULO DO SISTEMA. ISSO AUXILIA NO REDIRECIONAMENTO PARA A PÁGINA CORRETA AO
			EDITAR UM DETERMINADO REGISTRO DE UM DETERMINADO MÓDULO, OU SEJA, SE O USUÁRIO CLICAR NO BOTÃO
			EDITAR, DE UM REGISTRO NA PÁGINA X, AO SALVAR AS ALTERAÇÕES O SISTEMA UTILIZARÁ O COOKIE PARA
			SABER PRA QUAL PÁGINA DA LISTAGEM REDIRECIONAR O USUÁRIO
			
			$page -> pagina atual da listagem de registros 
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
		/*
			RESPONSÁVEL POR CRIAR UM COOKIE QUE CONTÉM A URL EM QUE O USUÁRIO ESTÁ TRABALHANDO, É NECESSÁRIO QUANDO A SESSÃO EXPIRA E REDIRECIONA O USUÁRIO 
			PRA TELA DE LOGIN, COM ISSO AO FAZER O LOGIN NOVAMENTE, ELE CONTINUA DE ONDE ESTAVA

			$url_redirect -> Url em que o usuário está
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
	}
?>