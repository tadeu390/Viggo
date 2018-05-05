<?php
	require_once("Geral.php");
	class Configuracoes extends Geral {
		/*
			no construtor carregamos as bibliotecas necessarias e tambem nossa model
		*/
		public function __construct()
		{
			parent::__construct();
			if(empty($this->account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');
				$this->load->model('Configuracoes_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		
		public function geral()
		{
			$this->data['title'] = 'Configurações gerais';
			$this->data['obj'] = $this->Configuracoes_model->get_configuracoes();
			$this->view("Configuracoes/geral",$this->data);
		}

		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Itens_por_pagina' => $this->input->post('itens_por_pagina')
				
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['Id']))
					$this->Configuracoes_model->set_configuracoes($dataToSave);
			 else
				redirect('admin/dashboard');
			
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}

		public function index()
		{
			redirect("admin/dashboard");
		}
	}
?>