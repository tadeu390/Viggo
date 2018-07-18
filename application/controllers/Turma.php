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
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Turmas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma(FALSE, FALSE, $page, FALSE);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_turmas']) ? $this->data['lista_turmas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
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
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, $id, FALSE, FALSE);
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);
				$curso_id = $this->Disc_turma_model->get_curso_turma($id)['Curso_id'];
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_disc_turma_disciplina($id,$curso_id);
				$this->data['lista_disc_turma_aluno'] = $this->Disc_turma_model->get_disc_turma_aluno($id);
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_cp($this->data['lista_disc_turma_header']['Curso_id'], $this->data['lista_disc_turma_header']['Modalidade_id'], $this->data['lista_disc_turma_header']['Periodo_letivo_id']);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(TRUE, FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
				$professor = array('grupo_id' => PROFESSOR);
				$this->data['lista_professores'] = $this->Usuario_model->get_usuario(TRUE, FALSE, FALSE, $professor);

				/*$aluno = array(
					'data_registro_inicio' => date("Y-m-d", strtotime('-6 months')), 
					'data_registro_fim' => date("Y-m-d")
				);*/
				$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos($this->data['lista_disc_turma_header']['Curso_id'], $this->data['lista_disc_turma_header']['Modalidade_id'], $this->data['lista_disc_turma_header']['Periodo_letivo_id'], FALSE); //$this->Disc_turma_model->get_disc_turma_filtro($aluno);
				
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
				$this->data['obj'] = $this->Turma_model->get_turma(FALSE, 0, FALSE, FALSE);
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header(0);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(TRUE, FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
				$this->data['lista_turmas'] = array();//$this->Turma_model->get_turma(TRUE, FALSE, FALSE, FALSE);
				$this->data['lista_disc_turma_aluno'] = $this->Disc_turma_model->get_disc_turma_aluno(0);
				
				/*$aluno = array(
					'data_registro_inicio' => date("Y-m-d", strtotime('-6 months')), 
					'data_registro_fim' => date("Y-m-d")
				);*/
				//$this->data['lista_alunos'] = $this->Disc_turma_model->get_disc_turma_filtro($aluno);
				//depois chamar abaixo o metodo que carrega a lista de alunos pra inserir na turma
				//aqui vai ser preciso chamar pra que possa ser feito o copiar para
				$this->data['lista_alunos'] = array();
				$this->view("turma/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL UMA LISTA COM AS DISCIPLINAS CADASTRADAS OU NÃO PARA A TURMA EM
		*	QUESTÃO, DE ACORDO COM O CURSO.
		*
		*	$turma_id -> Id da turma pra identificar as disciplinas relacionadas com ela.
		*	$curso_id -> Id co curso para identificar somente as disciplinas ligadas ao curso em questão.
		*/
		public function disciplina_por_curso($turma_id, $curso_id)
		{
			$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_disc_turma_disciplina($turma_id, $curso_id);
			$this->data['lista_categorias'] = $this->Categoria_model->get_categoria(FALSE);
			$professor = array('grupo_id' => PROFESSOR);
			$this->data['lista_professores'] = $this->Usuario_model->get_usuario(TRUE, FALSE, FALSE, $professor);
			$resultado = $this->load->view("turma/_disciplinas",$this->data, TRUE);
			$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
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
			else if(count($this->Modalidade_model->get_periodo_por_modalidade($Turma['Modalidade_id'])) == 0)
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
					'Disc_curso_id' => $this->input->post("Disc_curso_id".$i),
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
			$resultado = $this->Modalidade_model->get_periodo_por_modalidade($modalidade_id);
			if(empty($resultado))
				$resultado = "0";
			$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UM JSON QUE CONTÉM TODOS OS ALUNOS QUE PODEM SER CANDIDATOS
		*	A ESTAREM NA TURMA A SER CRIADA/EDITADA.
		*
		*	Id da turma que está criando.
		*	$curso_id -> id do curso, para saber todos os inscritos no curso selecionado.
		*	$modalidade_id -> id da modalidade.
		*	$nome -> Nome inserido no campo de filtro.
		*	$data_renovacao_inicio -> Início do intervalo de data em que a matricula foi renovada.
		*	$data_renovacao_fim -> Fim do intervalo de data em que a matricula foi renovada.
		*/
		public function get_alunos_inscritos($curso_id, $modalidade_id, $turma_id, $nome = false, $data_renovacao_inicio = false, $data_renovacao_fim = false)
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

			$this->data['lista_alunos'] = $this->Disc_turma_model->get_alunos_inscritos($curso_id, $modalidade_id, $periodo_letivo_id, $alunos);
			
			if(count($this->data['lista_alunos']) == 0)
				$resultado = "<div class='text-center'>Nenhum aluno encontrado ou todos os alunos já se encontram em uma turma de acordo com a modalidade e curso especificados acima.</div>";
			else
				$resultado = $this->load->view("turma/_alunos", $this->data, TRUE);
			$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL AS OPÇÕES DE TURMA JÁ CADASTRADAS NO SISTEMA ANTERIORMENTE,
		*	COM ESSE FILTRO É POSSÍVEL CARREGAR TODOS OS ALUNOS DE UMA TURMA ANTIGA PRA SE COLOCAR NUMA TURMA NOVA.
		*	ISSO RETORNA UM JSON.
		*
		*	$curso_id -> Curso selecionado para a turma.
		*	$modalidade -> Modalidade selecionada para a turma a ser criada.
		*/
		public function get_filtro_turma($curso_id, $modalidade_id)
		{
			$this->data['lista_turmas'] = $this->Turma_model->get_turma_cp($curso_id, $modalidade_id, $this->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Id']);
			
			$resultado = $this->load->view("turma/_filtro_turma", $this->data, TRUE);
			$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ALUNOS DE UMA DETERMINADA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja carregar a lista de alunos.
		*/
		public function get_alunos_inscritos_turma_antiga($turma_id)
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
						 'aviso' => $aviso);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
	}
?>