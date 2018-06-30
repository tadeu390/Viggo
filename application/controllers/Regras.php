<?php
	require_once("Geral.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE GENÉRICA
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELATIVO AS REGRAS DO PERÍODO LETIVO, OU SEJAS, AS REGRAS ADOTADAS GERENCIAR A SITUAÇÃO DO ALUNO EM 
		VÁRIOS ASPECTOS
	*/
	class Regras extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->load->model('Regras_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Intervalo_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*
			RESPONSÁVEL POR CARREGAR TODAS AS REGRAS DE CADA PERÍODO LETIVO CADASTRADO

			$page -> Número da página atual de registros
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->data['title'] = 'Regras letivas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_regras'] = $this->Regras_model->get_regras(FALSE, FALSE, $page, FALSE);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_regras'][0]['Size']) ? $this->data['lista_regras'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("regras/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}

		public function create($id = FALSE)//NESSE CASO USAR O ID NO PARÂMETRO DO CREATE PARA FAZER O "COPIAR PARA"
		{
			$this->data['title'] = 'Nova regra';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Regras_model->get_regras(FALSE, 0, FALSE, FALSE);
				$this->data['modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo(FALSE);
				$this->view("regras/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>