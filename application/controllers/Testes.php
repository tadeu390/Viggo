<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS GRUPOS DO SISTEMA
	*/
	class Testes extends Geral 
	{
		public function __construct()
		{
			parent::__construct();

			$this->load->model('Grupo_model');
			$this->load->model('Usuario_model');
			$this->load->model('Disc_turma_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*
			RESPONSÁVEL POR LISTAR TODOS OS GRUPOS NA TELA
			$page -> número da página atual registros
		*/
		public function index($page = FALSE)
		{
			print_r($this->Disc_turma_model->get_disc_turma(1));
			$this->load->view('templates/testes', $this->data);
		}
	}
?>
