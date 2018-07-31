<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS TURMAS.
	*/
	class Turma extends Geral 
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
			
			$this->load->model("Turma_model");
			$this->load->model("Curso_model");
			$this->load->model("Disc_turma_model");
			$this->load->model("Grade_model");

			$this->load->model("Categoria_model");
			$this->load->model("Modalidade_model");
			
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS AS TURMAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			//redireciona para o horário da turma, toda vez que uma turma é criada ou alterada caso o usuário queira.
			if(!empty($this->input->cookie('horario')))
				redirect("horario/create/".$this->input->cookie('horario'));

			if($page === FALSE)
				$page = 1;
			
			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Turmas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma(FALSE, FALSE, $page, FALSE, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_turmas']) ? $this->data['lista_turmas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];
				$this->view("turma/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE UMA TURMA PARA "APAGAR".
		*
		*	$id -> Id da turma.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Turma_model->deletar($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE TURMA E RECEBER DA MODEL OS DADOS 
		*	DA TURMA QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da turma.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar turma';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				//$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);
				$curso_id = $this->Disc_turma_model->get_curso_turma($id)['Curso_id'];
				
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_grade_disciplina(
					$this->Disc_turma_model->get_grade_id_turma($id)['Grade_id'], 
					$this->Disc_turma_model->get_periodo_turma($id)['Periodo'], $id);

				$this->data['lista_disc_turma_aluno'] = $this->Disc_turma_model->get_disc_turma_aluno($id);
				
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_cp(
					$this->data['lista_disc_turma_header']['Curso_id'], 
					$this->data['lista_disc_turma_header']['Modalidade_id'], 
					$this->data['lista_disc_turma_header']['Periodo_letivo_id'], 
					$this->Disc_turma_model->get_grade_id_turma($id)['Grade_id']);

				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(TRUE, FALSE, FALSE);
				$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
				
				$this->data['lista_grades'] = $this->Grade_model->get_grade_por_mc(FALSE, 
					$this->data['lista_disc_turma_header']['Modalidade_id'], 
					$this->data['lista_disc_turma_header']['Curso_id']);

				$this->data['lista_periodo_grade'] = $this->Grade_model->get_periodo_grade($this->Disc_turma_model->get_grade_id_turma($id)['Grade_id']);

				$professor = array('grupo_id' => PROFESSOR);
				$ordenacao = array('order' => 'ASC', 'field' => 'Nome');
				$this->data['lista_professores'] = $this->Usuario_model->get_usuario(TRUE, FALSE, FALSE, $professor, $ordenacao);
					
					$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos(
					$this->data['lista_disc_turma_header']['Curso_id'],
					$this->data['lista_disc_turma_header']['Modalidade_id'], 
					$this->data['lista_disc_turma_header']['Grade_id'],
					$this->data['lista_disc_turma_header']['Periodo_letivo_id']);
				$this->view("turma/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE TURMA.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova turma';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				//$this->data['obj'] = $this->Turma_model->get_turma(FALSE, 0, FALSE, FALSE);
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header(0);
				$this->data['lista_cursos'] = array();
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(TRUE, FALSE, FALSE);
				$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
				$this->data['lista_turmas'] = array();
				$this->data['lista_grades'] = array();
				$this->data['lista_periodo_grade'] = array();
				$this->data['lista_disc_turma_aluno'] = $this->Disc_turma_model->get_disc_turma_aluno(0);
				$this->data['lista_alunos'] = array();
				$this->view("turma/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR A QUANTIDADE DE DISCIPLINAS, NÃO SE CRIA TURMAS SEM DISCIPLINAS.
		*
		*	$Disc_to_save -> Contém as disciplinas.
		*/
		public function valida_disciplinas($Disc_to_save)
		{
			for($i = 0; $i < count($Disc_to_save); $i++)
				if($Disc_to_save[$i]['Value'] == 1)
					return TRUE;
			return FALSE;
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DA TURMA.
		*
		*	$Turma -> Contém todos os dados do menu a ser validado.
		*/
		public function valida_turma($Turma)
		{
			if(empty($Turma['Nome']))
				return "Informe o nome da turma";
			else if(mb_strlen($Turma['Nome']) > 20)
				return "Máximo 20 caracteres";
			else if($this->Turma_model->nome_valido($Turma['Id'], $Turma['Nome'], $this->Disc_turma_model->get_disc_turma_header($Turma['Id'])['Periodo_letivo_id']) == 'invalido')
				return "O nome informado para a turma já se encontra cadastrado no sistema.";
			else if($Turma['Modalidade_id'] == 0)
				return "Selecione uma modalidade de ensino.";
			else if(count($this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])) == 0 && empty($Turma['Id']))
				return "Nenhum período letivo foi identificado para esta modalidade. Por favor, primeiro cadastre o período letivo.";
			else if($this->valida_disciplinas($Turma['Disc_to_save']) == FALSE)
				return "Selecione ao menos uma disciplina para a turma";
			else if(count($Turma['Aluno_to_save']) == 0)
				return "Selecione pelo menos um aluno para a turma.";
			else if($this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])['Qtd_minima_aluno'] != 0 &&
					 count($Turma['Aluno_to_save']) < $this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])['Qtd_minima_aluno'])
				return "A quantidade de alunos adicionados é inferior a quantidade mínima permitda.";
			else if($this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])['Qtd_maxima_aluno'] != 0 &&
					 count($Turma['Aluno_to_save']) > $this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])['Qtd_maxima_aluno'])
				return "A quantidade de alunos adicionados é superior a quantidade máxima permitda.";
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DA TURMA.
		*
		*	$dataToSave -> Contém todos os dados da turma a ser cadastrada/editada.
		*/
		public function store_banco($dataToSave)
		{
			return $this->Turma_model->set_turma($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Nome' => $this->input->post('nome'),
				'Modalidade_id' => $this->input->post('modalidade_id'),
				'Ativo' => $this->input->post('turma_ativa')
			);
			
			$Disc_to_save = array();
			for($i = 0; $i < $this->input->post("limite_disciplina"); $i++)
			{
				$Disc_to_save_item = array(
					'Disc_grade_id' => $this->input->post("disc_grade_id".$i),
					'Value' => $this->input->post("nome_disciplina".$i),
					'Professor_id' => $this->input->post("professor_id".$i),
					'Categoria_id' => $this->input->post("categoria_id".$i)
				);
				array_push($Disc_to_save, $Disc_to_save_item);
			}

			$Aluno_to_save = array();
			for($i = 0; $i < $this->input->post("limite_aluno_add"); $i++)
			{
				if($this->input->post("aluno_id_add".$i) != null)
				{
					$Aluno_to_save_item = array(
						'Aluno_id' => $this->input->post("aluno_id_add".$i),
						'Sub_turma' => $this->input->post("sub_turma_add".$i)
					);
					array_push($Aluno_to_save, $Aluno_to_save_item);
				}
			}

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;

			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$dataToSave['Disc_to_save'] = $Disc_to_save;
					$dataToSave['Aluno_to_save'] = $Aluno_to_save;
					$resultado = $this->valida_turma($dataToSave);
				 	if($resultado == 1)
				 	{
				 		$resultado = $this->store_banco($dataToSave);
				 		
				 		$turma_id = 0;
				 		if(empty($dataToSave['Id']))
				 		{
				 			$turma_id = $resultado;
				 			//se estiver criando pega o último período letivo pra turma de acordo com a modalidade
				 			$periodo_letivo_id = $this->Modalidade_model->get_periodo_por_modalidade($dataToSave['Modalidade_id'])['Id'];
				 		}
				 		else
				 		{
				 			$turma_id = $dataToSave['Id'];
				 			//se estiver editando, pega o periodo_letivo_id registrado para a turma
				 			$periodo_letivo_id = $this->Disc_turma_model->get_disc_turma_header($turma_id)['Periodo_letivo_id'];
				 		}

				 		$this->Disc_turma_model->set_disc_turma($dataToSave, $turma_id, $periodo_letivo_id, $this->input->post('curso_id'));
				 		$resultado = "sucesso";

				 		if($this->input->post('horario') != NULL)
				 			$this->set_horario_cookie($turma_id);
				 	}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação.";

				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			 }
			 else
				redirect('turma/index');
		}
		/*!
		*	RESPONSÁVEL POR CRIAR UM COOKIE QUANDO O USUÁRIO DESEJA IR PARA A TELA DE ALTERAR HORÁRIO DA TURMA.
		*
		*	*$turma_id -> Id da turma usado para redirecionar pra a tela de horário da mesma.
		*/
		public function set_horario_cookie($turma_id)
		{
			$cookie = array(
		            'name'   => 'horario',
		            'value'  => $turma_id,
		            'expire' => 100000000,
		            'secure' => FALSE
	            );
		  	$this->input->set_cookie($cookie);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UMA TURMA E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de uma turma.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Detalhes da turma';
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);
				$curso_id = $this->Disc_turma_model->get_curso_turma($id)['Curso_id'];
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_disc_turma_disciplina($id,$curso_id);
				$this->data['lista_disc_turma_aluno'] = $this->Disc_turma_model->get_disc_turma_aluno($id);
				$this->data['lista_disc_turma_professor'] = $this->Disc_turma_model->get_disc_turma_professor($id);
				$this->view("turma/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UM PERIODO LETIVO NO FORMATO JSON. USADO PARA
		*	CARREGAR AS INFORMAÇÕES DO PERIODO LETIVO NA TELA DE TURMA.
		*	
		*	$modalidade_id -> Contém a modalidade que se deseja obter o período letivo.
		*/
		public function periodo_letivo($modalidade_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$resultado = $this->Modalidade_model->get_periodo_por_modalidade($modalidade_id);
				if(empty($resultado))
					$resultado = "0";
				$arr = array('response' => $resultado);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS CURSOS CADASTRADO NO SISTEMA.
		*/
		public function cursos()
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$aviso = "";
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(TRUE, FALSE, FALSE, FALSE);
				$this->data['lista_disc_turma_header']['Curso_id'] = 0;
				if(empty($this->data['lista_cursos']))
					$aviso = "Nenhum curso cadastrado foi encontrado";
				
				$resultado = $this->load->view("turma/_cursos", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'aviso' => $aviso);
					header('Content-Type: application/json');
					echo json_encode($arr);			
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL UMA LISTA DE GRADES CADASTRADAS PARA O CURSO E MODALIDADE EM QUESTÃO..
		*
		*	$modalidade_id -> Id da modalidade de ensino.
		*	$curso_id -> Id do curso.
		*/
		public function grade($modalidade_id, $curso_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$aviso = "";
				$this->data['lista_grades']	= $this->Grade_model->get_grade_por_mc(TRUE, $modalidade_id, $curso_id);
				$this->data['lista_disc_turma_header']['Grade_id'] = 0;
				if(empty($this->data['lista_grades']))
					$aviso = "Nenhuma grade cadastrada para este curso nesta modalidade foi encontrada.";
				
				$resultado = $this->load->view("turma/_grade", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'aviso' => $aviso);
					header('Content-Type: application/json');
					echo json_encode($arr);			
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS PERÍODOS QUE UMA GRADE TIVER.
		*
		*	$grade_id -> Id da grade que se deseja carregar os períodos.
		*/
		public function periodo_grade($grade_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$aviso = "";
				$this->data['lista_periodo_grade']	= $this->Grade_model->get_periodo_grade($grade_id);
				$this->data['lista_disc_turma_header']['Periodo'] = 0;
				
				$resultado = $this->load->view("turma/_periodo_grade", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'aviso' => $aviso);
					header('Content-Type: application/json');
					echo json_encode($arr);			
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL UMA GRADE CADASTRADA OU NÃO PARA A TURMA EM
		*	QUESTÃO, DE ACORDO COM O CURSO.
		*
		*	$grade_id -> Id da grade..
		*	$periodo -> Periodo da grade (as disciplinas são agrupadas conforme o período).
		*	$turma_id -> Id da turma. Para identificar se a turma está cadastrada com a grade e periodo em questão,
		*	isso permite carrega-la com a categoria professor já selecionado pra cada disciplina.
		*/
		public function grade_disciplina($grade_id, $periodo, $turma_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_grade_disciplina($grade_id, $periodo, $turma_id);
				$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
				$professor = array('grupo_id' => PROFESSOR);
				$ordenacao = array('order' => 'ASC', 'field' => 'Nome');
				$this->data['lista_professores'] = $this->Usuario_model->get_usuario(TRUE, FALSE, FALSE, $professor, $ordenacao);
				$resultado = $this->load->view("turma/_disciplinas",$this->data, TRUE);
				$arr = array('response' => $resultado);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UM JSON QUE CONTÉM TODOS OS ALUNOS QUE PODEM SER CANDIDATOS
		*	A ESTAREM NA TURMA A SER CRIADA/EDITADA.
		*
		*	Id da turma que está criando.
		*	$curso_id -> id do curso, para saber todos os inscritos no curso selecionado.
		*	$modalidade_id -> id da modalidade.
		*	$nome -> Nome inserido no campo de filtro.
		*	$data_renovacao_inicio -> Todos os alunos que renovaram a matricula a partir dessa data.
		*	$data_renoovacao_fim -> Todos os alunos que renovaram a matricula até esta data.
		*/
		public function get_alunos_inscritos($curso_id, $modalidade_id, $turma_id, $grade_id, $nome = false, $data_renovacao_inicio = false, $data_renovacao_fim = false)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				//SE ESTIVER EDITANDO ENTAO PEGA O PERÍODO LETIVO DA TURMA.
				if($turma_id != 0)
					$periodo_letivo_id = $this->Turma_model->get_turma(FALSE, $turma_id, FALSE, FALSE)['Periodo_letivo_id'];
				else // SE ESTIVER CRIANDO ENTÃO DEIXAR O MODEL PEGAR O ÚLTIMO PERÍODO LETIVO PARA A MODALIDADE EM QUESTÃO.
					$periodo_letivo_id = FALSE;

				$alunos = array(
					'nome' => $nome,
					'data_renovacao_inicio' => $data_renovacao_inicio, 
					'data_renovacao_fim' => $data_renovacao_fim
				);

				$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos($curso_id, $modalidade_id, $grade_id, $periodo_letivo_id, $alunos);
				
				if(count($this->data['lista_alunos']) == 0)
					$resultado = "<div class='text-center'>Nenhum aluno encontrado ou todos os alunos já se encontram em uma turma de acordo com a modalidade e curso especificados acima.</div>";
				else
					$resultado = $this->load->view("turma/_alunos", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'quantidade' => COUNT($this->data['lista_alunos'])
							);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
		/*!
			RESPONSÁVEL POR RECEBER DA MODEL UMA LISTA DE ALUNOS CUJA A INSCRIÇÃO AINDA NÃO ESTEJA LIGADA 
			EM NENHUM PERIODO ANTERIOR.

			$curso_id -> Id do curso selecionado, carrega apenas alunos inscritos no curso em questão.
			$modalidade_id -> Id da modalidade, carrega apenas alunos inscritos na modalidade em questão.
			$turma_id -> Id da turma que está sendo editada ou criada(turma sendo criada o id é zero).
			$nome -> Filtro por nome de aluno.
			$data_renovacao_inicio -> Todos os alunos que renovaram a matricula a partir dessa data.
			$data_renoovacao_fim -> Todos os alunos que renovaram a matricula até esta data.
		*/
		public function get_alunos_inscritos_novos($curso_id, $modalidade_id, $turma_id, $grade_id, $nome = false, $data_renovacao_inicio = false, $data_renovacao_fim = false)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$alunos = array(
					'nome' => $nome,
					'data_renovacao_inicio' => $data_renovacao_inicio, 
					'data_renovacao_fim' => $data_renovacao_fim
				);

				$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos_novos($curso_id, $modalidade_id, $alunos);
				
				if(count($this->data['lista_alunos']) == 0)
					$resultado = "<div class='text-center'>Nenhum aluno encontrado ou todos os alunos já se encontram em uma turma de acordo com a modalidade e curso especificados acima.</div>";
				else
					$resultado = $this->load->view("turma/_alunos", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'quantidade' => COUNT($this->data['lista_alunos'])
						);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL AS OPÇÕES DE TURMA JÁ CADASTRADAS NO SISTEMA ANTERIORMENTE,
		*	COM ESSE FILTRO É POSSÍVEL CARREGAR TODOS OS ALUNOS DE UMA TURMA ANTIGA PRA SE COLOCAR NUMA TURMA NOVA.
		*	ISSO RETORNA UM JSON.
		*
		*	$curso_id -> Curso selecionado para a turma.
		*	$modalidade -> Modalidade selecionada para a turma a ser criada.
		*	$grade_id -> Id da grade selecionada no formulário de cadastro de turma.
		*/
		public function get_filtro_turma($curso_id, $modalidade_id, $grade_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_cp($curso_id, $modalidade_id, $this->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Id'], $grade_id);
				
				$resultado = $this->load->view("turma/_filtro_turma", $this->data, TRUE);
				$arr = array('response' => $resultado);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ALUNOS DE UMA DETERMINADA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja carregar a lista de alunos.
		*/
		public function get_alunos_inscritos_turma_antiga($turma_id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$curso_id = $this->Disc_turma_model->get_curso_turma($turma_id)['Curso_id'];

				$modalidade_id = $this->Disc_turma_model->get_modalidade_turma($turma_id)['Modalidade_id'];
		
				$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos_turma_antiga($turma_id, $curso_id, $modalidade_id);
				$aviso = "";
				
				if((!empty($this->data['lista_alunos']) && $this->data['lista_alunos'][0]['Size_total_turma_antiga_renovado'] < $this->data['lista_alunos'][0]['Size_total_turma_antiga']))
					$aviso = "A turma selecionada contém alunos que não estão disponíveis para serem inclusos nesta turma, 
							 pois as suas respectivas matrículas não estão renovadas para o período letivo corrente. Talvez deva verificar isso.";
				else if(empty($this->data['lista_alunos']))
					$aviso = "Todos os alunos desta turma já se encontram cadastrados em alguma turma do período letivo corrente.";
				$resultado = $this->load->view("turma/_alunos", $this->data, TRUE);
				$arr = array('response' => $resultado,
							 'aviso' => $aviso,
							 'quantidade' => COUNT($this->data['lista_alunos'])
						);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
	}
?>