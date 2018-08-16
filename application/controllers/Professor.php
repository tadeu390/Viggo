<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS CURSOS.
	*/
	class Professor extends Geral 
	{
		private $professor_id;
		private $periodo_letivo_id;
		private $disc_grade_id_default;
		private $turma_id_default;
		private $bimestre_id_default;

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

			$this->load->model('Professor_model');
			$this->load->model('Regras_model');
			$this->load->model('Bimestre_model');
			$this->load->model('Account_model');

			//ABAIXO DETERMINA O PROFESSOR E O PERÍODO LETIVO
			$this->professor_id = $this->Account_model->session_is_valid()['id'];
			$this->periodo_letivo_id = $this->input->cookie('periodo_letivo_id');
			
			//ESTA CLASSE SÓ PODE SER ACESSADA NA PRESÊNÇA DE UM PERÍODO LETIVO SELECIONADO.
			if(empty($this->periodo_letivo_id))
				redirect("academico/professor");

			/////////DISCIPLINA, TURMA E BIMESTRE PADRÃO.
			//A DISCIPLINA CARREGA COM BASE NO HORÁRIO.
			$this->disc_grade_id_default = (empty($this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disc_grade_id']) ? 
														$this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id)[0]['Disc_grade_id'] : 
														$this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disc_grade_id']);
			//A TURMA ESTÁ AMARRADA A DISCIPLINA, O QUE CONSEQUENTEMENTE CARREGA COM BASE NA DISCIPLINA
			$this->turma_id_default = (empty($this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']) ? $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id)[0]['Turma_id'] : $this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']);
			//CARREGA O BIMESTRE PADRÃO COM BASE NA DATA.
			$this->bimestre_id_default = $this->Professor_model->get_bimestre_default($this->periodo_letivo_id)['Id'];
			/////////


			$this->data['Nome_periodo'] = $this->Regras_model->get_regras(FALSE, $this->input->cookie('periodo_letivo_id'), FALSE, FALSE, FALSE)['Nome_periodo'];
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE NOTA DE CADA ALUNO DE UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disc_grade_id -> Id da disciplina da grade. É usado para se obter as notas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function notas($disc_grade_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disc_grade_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disc_grade_id = $this->disc_grade_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE AND $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);

				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
				$flag = 0;
				for($i = 0; $i < COUNT($this->data['lista_turmas']); $i++)
				{
					if($this->data['lista_turmas'][$i]['Turma_id'] == $turma_id)
						$flag = 1;
				}
				if($flag == 1)
					$this->data['url_part']['turma_id'] = $turma_id;///SE A TURMA EXISTE NA DISCIPLINA SELECIONADA ENTÃO MANTÉM O ID DE TURMA SUBMETIDO.
				else 
					$this->data['url_part']['turma_id'] = $this->data['lista_turmas'][0]['Turma_id'];//CASO CONTRÁRIO PEGAR POR DEFAUL O PRIMEIRO ID DISPONÍVEL.
				//////

				$this->data['url_part']['disc_grade_id'] = $disc_grade_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;
				$this->view("professor/notas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE CURSO PARA "APAGAR".
		*
		*	$id -> Id do curso.
		*/
		public function deletar($id = null)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Curso_model->delete_curso($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE CURSO E RECEBER DA MODEL OS DADOS 
		*	DO CURSO QUE SE DESEJA EDITAR.
		*
		*	$id -> Id do curso.
		*/
		public function edit($id = null)
		{
			$this->data['title'] = 'Editar Formação de nota';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{

			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO CURSO.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova formação de nota';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				
			}
			$this->view("curso/create_edit", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO CURSO.
		*
		*	$Curso -> Contém todos os dados do curso a ser validado.
		*/
		public function valida_formacao_nota($Formacao_nota)
		{
			return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO CURSO.
		*
		*	$dataToSave -> Contém todos os dados do curso a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Curso_model->set_curso($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('curso_ativo'),
				'Nome' => $this->input->post('nome')
			);

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			if(!empty($this->input->post()))
			{
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = $this->valida_formacao_nota($dataToSave);

				 	if($resultado == 1)
				 	{ 
				 		$this->store_banco($dataToSave);
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
				redirect('curso/index');
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM CURSO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de um curso.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{		
	
	
				$this->view("curso/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>