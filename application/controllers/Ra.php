<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS MATRICULAS DO SISTEMA.
	*/
	class Ra extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->load->model('Ra_model');
			$this->load->model('Curso_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Usuario_model');
			$this->load->model('Aluno_model');
			$this->load->model('Renovacao_matricula_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS MATRICULAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Matrículas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_matriculas'] = $this->Ra_model->get_ra(FALSE, FALSE, $page, FALSE);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_matriculas']) ? $this->data['lista_matriculas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("ra/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE MATRICULA PARA "APAGAR".
		*
		*	$id -> Id da matricula.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Ra_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MATRICULA E RECEBER DA MODEL OS DADOS 
		*	DA MATRICULA QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da matricula.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar matricula';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Ra_model->get_ra(FALSE, $id, FALSE);
				$filter = array();
				$filter['grupo_id'] = ALUNO;
				$this->data['lista_alunos'] = $this->Usuario_model->get_usuario(FALSE, FALSE, FALSE, $filter);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);

				$this->view("ra/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MATRICULA.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova Inscrição';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Ra_model->get_ra(FALSE, 0, FALSE);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);

				$filter = array();
				$filter['grupo_id'] = ALUNO;
				$this->data['lista_alunos'] = $this->Usuario_model->get_usuario(FALSE, FALSE, FALSE, $filter);
				$this->view("ra/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DE MATRICULA.
		*
		*	$Matricula -> Contém todos os dados da matricula a ser validada.
		*/
		public function valida_ra($Matricula)
		{
			if($Matricula['Aluno_id'] == 0)
				return "Selecione um aluno.";
			else if($Matricula['Curso_id'] == 0)
				return "Selecione um curso.";
			else if($Matricula['Modalidade_id'] == 0)
				return "Selecione uma modalidade.";
			else if(!empty($this->Ra_model->get_inscricao_por_aluno($Matricula)))
				return "O aluno selecionado ja se encontra inscrito para este curso.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DE MATRICULA.
		*
		*	$dataToSave -> Contém todos os dados da matricula a ser cadastrada/editada.
		*/
		public function store_banco($dataToSave)
		{
			$this->Ra_model->set_ra($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Aluno_id' => $this->Aluno_model->get_aluno($this->input->post('aluno_id'))['Id'],
				'Curso_id' => $this->input->post('curso_id'),
				'Modalidade_id' => $this->input->post('modalidade_id'),
				'Ativo' => $this->input->post('matricular')
			);

			if (empty($dataToSave['Ativo']))
				unset($dataToSave['Ativo']);

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
				 	$resultado = $this->valida_ra($dataToSave);

				 	if($resultado == 1)
				 	{
				 		$resultado = $this->store_banco($dataToSave);

				 		$dataRenovacaoToSave = array(
							'Inscricao_id' => $this->Ra_model->get_inscricao_por_aluno($dataToSave)['Id'],
							'Periodo_letivo_id' => $this->Modalidade_model->get_periodo_por_modalidade($dataToSave['Modalidade_id'])['Id']
						);
				 		$this->Renovacao_matricula_model->set_renovacao_matricula($dataRenovacaoToSave);
				 		
				 		$resultado = "sucesso";
				 	}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('ra/index');
		}
	}
?>