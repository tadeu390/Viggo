<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS GRADES DO SISTEMA.
	*/
	class Grade extends Geral 
	{
		/*
			No construtor carregamos as bibliotecas necessarias e tambem nossa model.
		*/
		public function __construct()
		{
			parent::__construct();
			
			if(empty($this->Account_model->session_is_valid()['id']))
			{
				$url_redirect = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$url_redirect = str_replace("/","-x",$url_redirect);
				redirect('account/login/'.$url_redirect);
			}
			
			$this->load->model('Grade_model');
			$this->load->model('Curso_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Disciplina_model');
			$this->load->model('Disc_grade_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS MENUS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);

			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->data['title'] = 'Grades';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->data['lista_grades'] = $this->Grade_model->get_grade(FALSE, FALSE, $page, FALSE, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_grades'][0]['Size']) ? $this->data['lista_grades'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("grade/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE GRADE PARA "APAGAR".
		*
		*	$id -> Id da grade.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Grade_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE GRADE E RECEBER DA MODEL OS DADOS 
		*	DA GRADE QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da grade.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar grade';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grade_model->get_grade(FALSE, $id, FALSE);
								$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);
				$this->data['lista_disciplinas'] = $this->Disciplina_model->get_disciplina(FALSE,FALSE,FALSE);
				$this->data['lista_disc_grade'] = $this->Disc_grade_model->get_disc_grade($id);
				$this->view("grade/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DA GRADE.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova Grade';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grade_model->get_grade(FALSE, 0, FALSE);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);
				$this->data['lista_disciplinas'] = $this->Disciplina_model->get_disciplina(FALSE,FALSE,FALSE);
				$this->data['lista_disc_grade'] = $this->Disc_grade_model->get_disc_grade(0);
				$this->view("grade/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DA GRADE.
		*
		*	$Grade -> Contém todos os dados da grade a ser validada.
		*/
		public function valida_grade($Grade)
		{
			if($Grade['Curso_id'] == 0)
				return "Informe o curso da grade.";
			else if($Grade['Modalidade_id'] == 0)
				return "Informe a modalidade da grade.";
			elseif(empty($Grade['Disc_grade_to_save']))
				return "Selecione alguma disciplina para a grade.";
			else if($Grade['Modalidade_id'] != $this->Modalidade_model->get_periodo_por_modalidade($Grade['Modalidade_id'])['Modalidade_id'])
				return "A modalidade selecionada nao possui periodo letivo cadastrado.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DA GRADE.
		*
		*	$dataToSave -> Contém todos os dados da grade a ser cadastrada/editada.
		*/
		public function store_banco($dataToSave)
		{
			$this->Grade_model->set_grade($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Nome' => $this->Curso_model->get_curso(FALSE,$this->input->post('curso_id'),FALSE,FALSE)['Nome_curso'].'-'.
				$this->Modalidade_model->get_modalidade(FALSE,$this->input->post('modalidade_id'),FALSE)['Nome_modalidade'].'-'.
				$this->Modalidade_model->get_periodo_por_modalidade($this->input->post('modalidade_id'))['Periodo'],
				'Curso_id' => $this->input->post('curso_id'),
				'Modalidade_id' => $this->input->post('modalidade_id'),
				'Ativo' => $this->input->post('grade_ativo')
			);

			$Disc_grade_to_save = array();
			for($i = 0; $i < $this->input->post("limite_disciplina_add"); $i++)
			{
				if($this->input->post("disciplina_id_add".$i) != null)
				{
					$Disc_grade_to_save_item = array(
						'Disciplina_id' => $this->input->post("disciplina_id_add".$i),
						'Periodo' => $this->input->post("periodo_add".$i)
					);
					array_push($Disc_grade_to_save, $Disc_grade_to_save_item);
				}
			}

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$dataToSave['Disc_grade_to_save'] = $Disc_grade_to_save;
					$resultado = $this->valida_grade($dataToSave);

				 	if($resultado == 1)
				 	{ 
				 		$resultado = $this->store_banco($dataToSave);

				 		$resultado = "sucesso";
				 	}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação.";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			 }
			 else
				redirect('grade/index');
		}
	}
?>