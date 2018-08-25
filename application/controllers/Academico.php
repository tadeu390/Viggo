<?php
	require_once("Geral.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR A TELA INICIAL.
	*/
	class Academico extends Geral 
	{
		/*!
		*	CONSTRUTOR RESPONSÁVEL POR VALIDAR A SESSÃO E VERIFICAR O MENU SELECIONADO.
		*/
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));

			$this->load->model("Academico_model");
			$this->load->model("Regras_model");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A TELA INICIAL DO ADMINISTRADOR.
		*/
		public function dashboard()
		{
			$this->data['title'] = 'Acadêmico';
			if($this->Account_model->session_is_valid()['grupo_id'] == ADMIN)
				$this->view("academico/dashboard", $this->data);
			else 
				redirect("account/login");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A TELA INICIAL DO PROFESSOR.
		*/
		public function professor()
		{
			$this->data['title'] = 'Portal do professor';
			if($this->Account_model->session_is_valid()['grupo_id'] == PROFESSOR)
			{
				if(!empty($this->input->cookie('periodo_letivo_id')))
					redirect("professor/chamada");
				
				$this->data['Nome_periodo'] = $this->Regras_model->get_regras(FALSE, $this->input->cookie('periodo_letivo_id'), FALSE, FALSE, FALSE)['Nome_periodo'];
				$this->data['lista_periodos'] = $this->Academico_model->get_periodos_professor($this->Account_model->session_is_valid()['id']);
				$this->view("academico/professor", $this->data);
			}
			else 
				redirect("account/login");
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE ESPECIFICANDO O PERÍODO LETIVO EM QUE O PROFESSOR ESTÁ TRABALHANDO.
		*
		*	$periodo_letivo_id -> Periodo letivo a ser colocado em cookie.
		*/
		public function set_periodo_letivo($periodo_letivo_id)
		{
			delete_cookie('periodo_letivo_id');

			$cookie = array(
	            'name'   => 'periodo_letivo_id',
	            'value'  => $periodo_letivo_id,
	            'expire' => 100000000,
	            'secure' => FALSE
            );
            $this->input->set_cookie($cookie);

            $arr = array('response' => $periodo_letivo_id);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR APAGAR UM PERÍODO LETIVO QUANDO O USUÁRIO QUER TROCAR DE PERÍODO.
		*/
		public function delete_periodo_letivo()
		{
			delete_cookie('periodo_letivo_id');
			redirect("account/login");
		}
	}
?>