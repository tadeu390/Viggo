<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS MENUS DO SISTEMA.
	*/
	class Horario extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			
			if(empty($this->Account_model->session_is_valid()['id']))
			{
				$url_redirect = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_redirect = str_replace("/","-x",$url_redirect);
				redirect('account/login/'.$url_redirect);
			}
			$this->load->model('Horario_model');
			$this->load->model('Regras_model');
			$this->load->model('Disc_turma_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Curso_model');
			$this->load->model('Grade_model');
			$this->load->model('Turma_model');
			$this->load->model('Intervalo_model');
			
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS MENUS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Horários';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				
				$this->view("horario/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE MENU PARA "APAGAR".
		*
		*	$id -> Id do menu.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Menu_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE HORÁRIO PARA A TURMA EM QUESTÃO.
		*
		*	$id -> Id da turma que se deseja alterar o horário.
		*/
		public function create($id)
		{
			$this->data['title'] = 'Alterar horário';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{

				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);

				//carregar informações básicas da turma.
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);

				//carregar regras do periodo letivo associado a turma.
				$this->data['regras'] = $this->Regras_model->get_regras(FALSE, $this->data['lista_disc_turma_header']['Periodo_letivo_id'], FALSE, FALSE, FALSE);
				
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(TRUE, FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);

				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($this->data['lista_disc_turma_header']['Periodo_letivo_id']);
				print_r($this->data['intervalos']);
				$this->data['lista_grades'] = $this->Grade_model->get_grade_por_mc(
					$this->data['lista_disc_turma_header']['Modalidade_id'], 
					$this->data['lista_disc_turma_header']['Curso_id']);

				$this->data['lista_periodo_grade'] = $this->Grade_model->get_periodo_grade($this->Disc_turma_model->get_grade_id_turma($id)['Grade_id']);


				$this->view("horario/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO MENU.
		*
		*	$Menu -> Contém todos os dados do menu a ser validado.
		*/
		public function valida_horario($Menu)
		{
			
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO MENU.
		*
		*	$dataToSave -> Contém todos os dados do menu a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Menu_model->set_menu($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{

				}
			 }
			 else
				redirect('menu/index');
		}
	}
?>