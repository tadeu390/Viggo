<?php

define("CREATE",'criar');
define("READ",'visualizar');
define("UPDATE",'atualizar');
define("DELETE",'apagar');

define("USUARIO_PADRAO",3);

	class Geral extends CI_Controller 
	{
		protected $data;

		public function __construct()
		{
			parent::__construct();
			$this->load->model('Settings_model');
			define("ITENS_POR_PAGINA",$this->Settings_model->get_geral()['itens_por_pagina']);

			$this->load->model('Account_model');
			$this->load->model('Menu_model');
			$this->load->model('Modulo_model');
			$this->load->model('Geral_model');
			$this->load->helper('url_helper');
			$this->load->helper('url');
			$this->load->helper('html');
			$this->load->helper('form');
			$this->load->library('session');
			$this->load->helper('cookie');
			$this->data['url'] = base_url();
			$this->data['paginacao']['url'] = base_url();
			$this->data['paginacao']['itens_por_pagina'] = ITENS_POR_PAGINA;
		}

		public function set_menu()
		{
			$this->data['menu'] = $this->Menu_model->get_menu();
			$this->data['modulo'] = $this->Modulo_model->get_modulo();
		}
		
		public function view($v,$dt)
		{
			$this->load->view('templates/header_admin',$dt);
			$this->load->view($v,$dt);
			$this->load->view('templates/footer',$dt);
		}
	}
?>