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

			$this->load->model("Professor_model");
			$this->load->model("Regras_model");
			$this->load->model("Aluno_model");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A TELA INICIAL DO ADMINISTRADOR.
		*/
		public function dashboard()
		{
			$this->data['periodo'] = $this->Regras_model->get_regras(FALSE, $this->input->cookie('periodo_letivo_id'), FALSE, FALSE, FALSE)['Nome_periodo'];
			if(empty($this->data['periodo']))
				$this->data['periodo'] = "Aguardando período letivo";
			$this->data['title'] = 'Acadêmico';
			//if($this->Account_model->session_is_valid()['grupo_id'] == ADMIN)
				$this->view("academico/dashboard", $this->data);
			//else 
				//redirect("account/login");
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
				
				$this->data['periodo'] = $this->Regras_model->get_regras(FALSE, $this->input->cookie('periodo_letivo_id'), FALSE, FALSE, FALSE)['Nome_periodo'];
				if(empty($this->data['periodo']))
				$this->data['periodo'] = "Aguardando período letivo";
			
				$this->data['lista_periodos'] = $this->Professor_model->get_periodos_professor($this->Account_model->session_is_valid()['id']);
				$this->view("academico/professor", $this->data);
			}
			else 
				redirect("account/login");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A TELA INICIAL DO ALUNO.
		*/
		public function aluno()
		{
			$this->data['title'] = 'Portal do aluno';
			if($this->Account_model->session_is_valid()['grupo_id'] == ALUNO)
			{
				$periodo = $this->Aluno_model->get_periodos_aluno($this->Account_model->session_is_valid()['id'], $this->input->cookie('curso_id'));

				$this->data['periodo'] = "Aguardando período letivo";
				if(!empty($periodo))
					$this->data['periodo'] = $periodo[0]['Curso'];

				$this->data['lista_periodos'] = $this->Aluno_model->get_periodos_aluno($this->Account_model->session_is_valid()['id'], FALSE);
				$this->view("academico/aluno", $this->data);
			}
			else 
				redirect("account/login");
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE ESPECIFICANDO O PERÍODO LETIVO EM QUE O PROFESSOR ESTÁ TRABALHANDO.
		*
		*	$periodo_letivo_id -> Periodo letivo selecionado.
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
			delete_cookie('curso_id');
			redirect("account/login");
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE ESPECIFICANDO O QUAL O CURSO ELE DESEJA ACESSAR DENTRO DE UM PÉRÍODO LETIVO.
		*
		*	$periodo_letivo_id -> Periodo letivo selecionado.
		*	$curso_id -> Curso selecionado.
		*/
		public function set_curso_periodo($periodo_letivo_id, $curso_id)
		{
			delete_cookie('curso_id');

			$cookie = array(
	            'name'   => 'curso_id',
	            'value'  => $curso_id,
	            'expire' => 100000000,
	            'secure' => FALSE
            );
            $this->input->set_cookie($cookie);

            $this->set_periodo_letivo($periodo_letivo_id);
		}
	}
?>