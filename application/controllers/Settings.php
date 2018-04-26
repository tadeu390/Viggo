<?php
	require_once("Geral.php");
	class Settings extends Geral {
		/*
			no construtor carregamos as bibliotecas necessarias e tambem nossa model
		*/
		public function __construct()
		{
			parent::__construct();
			if(empty($this->account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');
				$this->load->model('Settings_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		
		public function geral()
		{
			$this->data['title'] = 'Configurações gerais';
			$this->data['obj'] = $this->Settings_model->get_geral();
			$this->view("settings/geral",$this->data);
		}

		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'id' => $this->input->post('id'),
				'media' => $this->input->post('media'),
				'itens_por_pagina' => $this->input->post('itens_por_pagina'),
				'total_faltas' => $this->input->post('total_faltas'),
				'primeiro_bimestre' => $this->input->post('primeiro_bimestre'),
				'segundo_bimestre' => $this->input->post('segundo_bimestre'),
				'terceiro_bimestre' => $this->input->post('terceiro_bimestre'),
				'quarto_bimestre' => $this->input->post('quarto_bimestre')
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['media']))
					$this->Settings_model->set_geral($dataToSave);
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