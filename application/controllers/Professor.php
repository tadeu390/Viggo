<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS AÇÕES DO PROFESSOR.
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
			$this->load->model('Professor_model');
			$this->load->model('Regras_model');
			$this->load->model('Nota_model');
			$this->load->model('Bimestre_model');
			$this->load->model('Account_model');
			$this->load->model('Descricao_nota_model');
			$this->load->model('Calendario_presenca_model');
			$this->load->model('Conteudo_model');

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
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['periodo_letivo_id'] = $this->periodo_letivo_id;
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

				///DETERMINAR SE O BIMESTRE ESTÁ ABERTO.
				$timeZone = new DateTimeZone('UTC');
				$data_abertura = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_abertura'] : '00/00/000'), $timeZone);
				$data_fechamento = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_fechamento'] : '00/00/000'), $timeZone);
				$data_atual = DateTime::createFromFormat ('d/m/Y', date('d/m/Y'), $timeZone);
				
				if($data_atual >= $data_abertura && $data_atual <= $data_fechamento)
					$this->data['status_bimestre'] = '';
				else 
					$this->data['status_bimestre'] = "disabled";
				/////

				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONADA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
				$flag = 0;
				for($i = 0; $i < COUNT($this->data['lista_turmas']); $i++)
				{
					if($this->data['lista_turmas'][$i]['Turma_id'] == $turma_id)
						$flag = 1;
				}
				if($flag == 1)
					$this->data['url_part']['turma_id'] = $turma_id;///SE A TURMA ESTÁ ASSOCIADA A DISCIPLINA SELECIONADA ENTÃO MANTÉM O ID DE TURMA SUBMETIDO.
				else 
				{
					$this->data['url_part']['turma_id'] = $this->data['lista_turmas'][0]['Turma_id'];//CASO CONTRÁRIO PEGAR POR DEFAULT O PRIMEIRO ID DISPONÍVEL.
					$turma_id = $this->data['lista_turmas'][0]['Turma_id'];//SOBRESCREVE O ID SUBMETIDO
				}
				//////

				$this->data['url_part']['disc_grade_id'] = $disc_grade_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;

				//DESCRIÇÃO DE NOTA
				$this->data['lista_descricao_nota'] = $this->Descricao_nota_model->get_descricao(TRUE, FALSE);
				//////

				///TABELA DE NOTAS
				$this->data['lista_colunas_nota'] = $this->Professor_model->get_colunas_nota($disc_grade_id, $turma_id, $bimestre_id);
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disc_grade_id, $turma_id);
				//////

				$this->view("professor/notas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		public function notas_geral($disc_grade_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disc_grade_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disc_grade_id = $this->disc_grade_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = "notas";
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['periodo_letivo_id'] = $this->periodo_letivo_id;
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);


				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONADA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
				$flag = 0;
				for($i = 0; $i < COUNT($this->data['lista_turmas']); $i++)
				{
					if($this->data['lista_turmas'][$i]['Turma_id'] == $turma_id)
						$flag = 1;
				}
				if($flag == 1)
					$this->data['url_part']['turma_id'] = $turma_id;///SE A TURMA ESTÁ ASSOCIADA A DISCIPLINA SELECIONADA ENTÃO MANTÉM O ID DE TURMA SUBMETIDO.
				else 
				{
					$this->data['url_part']['turma_id'] = $this->data['lista_turmas'][0]['Turma_id'];//CASO CONTRÁRIO PEGAR POR DEFAULT O PRIMEIRO ID DISPONÍVEL.
					$turma_id = $this->data['lista_turmas'][0]['Turma_id'];//SOBRESCREVE O ID SUBMETIDO
				}
				//////

				$this->data['url_part']['disc_grade_id'] = $disc_grade_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;

				///TABELA DE ALUNOS
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disc_grade_id, $turma_id);
				//////

				$this->view("professor/notas_geral", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
			RESPONSÁVEL POR RECEBER OS DADOS DO FORMULÁRIO A NOTA A SER ALTERADA.

		*/
		public function altera_nota($nota, $descricao_nota_id, $matricula_id, $turma_id, $disc_grade_id, $bimestre_id)
		{
			if($nota == 'null')
				$nota = null;
			//Não foi possível completar esta operação. Entre em contato com o administrador do sistema.
			$resultado = "sucesso";
			$somatorio = 0;
			$status = "";
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE AND $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$resultado = $this->Nota_model->validar_nota($matricula_id, $bimestre_id, $turma_id, $disc_grade_id, $nota, $descricao_nota_id);

				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

				///DETERMINAR SE O BIMESTRE ESTÁ ABERTO.
				$timeZone = new DateTimeZone('UTC');
				$data_abertura = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_abertura'] : '00/00/000'), $timeZone);
				$data_fechamento = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_fechamento'] : '00/00/000'), $timeZone);
				$data_atual = DateTime::createFromFormat ('d/m/Y', date('d/m/Y'), $timeZone);
				
				if($data_atual >= $data_abertura && $data_atual <= $data_fechamento)
				{
					if($resultado != "invalido")
					{
						$somatorio = $resultado;
						$resultado = $this->Nota_model->set_notas($nota, $descricao_nota_id, $matricula_id, $turma_id, $disc_grade_id, $bimestre_id);

						$status = $this->Nota_model->status_nota_total_bimestre($matricula_id, $turma_id, $disc_grade_id, $bimestre_id, $this->periodo_letivo_id);
						
						if($status == "ok")
							$status = "info";
						else
							$status = "danger";
					}
					else 
						$resultado = "O valor informado ultrapassa o limite de ".$this->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Valor']." pontos estabelecido para o ".$this->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Nome'].".";
				}
				else
					$resultado = "Não é possível alterar a nota.";

			}
			else
				$resultado = "Você não tem permissão para realizar esta ação. Entre em contato com o administrador do sistema.";
				
			$arr = array('response' => $resultado, 'somatorio' => $somatorio, 'status' => $status);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}

		public function remover_coluna_nota($descricao_nota_id, $turma_id, $disc_grade_id, $bimestre_id)
		{
			$resultado = "sucesso";
			$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

			///DETERMINAR SE O BIMESTRE ESTÁ ABERTO.
			$timeZone = new DateTimeZone('UTC');
			$data_abertura = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_abertura'] : '00/00/000'), $timeZone);
			$data_fechamento = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_fechamento'] : '00/00/000'), $timeZone);
			$data_atual = DateTime::createFromFormat ('d/m/Y', date('d/m/Y'), $timeZone);
			
			if($data_atual >= $data_abertura && $data_atual <= $data_fechamento)
				$this->Nota_model->remover_coluna_nota($descricao_nota_id, $turma_id, $disc_grade_id, $bimestre_id);
			else
				$resultado = "Não foi possível apagar a coluna de nota.";

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE FALTAS DE CADA ALUNO DE UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disc_grade_id -> Id da disciplina da grade. É usado para se obter as faltas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function faltas($disc_grade_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disc_grade_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disc_grade_id = $this->disc_grade_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONADA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
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
				$this->view("professor/faltas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE FALTAS DE CADA ALUNO DE UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disc_grade_id -> Id da disciplina da grade. É usado para se obter as faltas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function faltas_geral($disc_grade_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disc_grade_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disc_grade_id = $this->disc_grade_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONADA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
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
				$this->view("professor/faltas_geral", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL AS INFORMAÇÕES PARA A CHAMADA, COMO A LISTA DE ALUNOS, OS HORÁRIOS.
		*
		*	$disc_grade_id -> Id da disciplina para fazer a chamada.
		*	$turma_id -> Id da turma para se fazer a chamada.
		*	$bimestre_id -> Id do bimestre (somente para identificar o bimestre a ser selecionado na tela e para verificar
		*	se o bimestre está aberto ou não)
		*/
		public function chamada($disc_grade_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disc_grade_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disc_grade_id = $this->disc_grade_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disc_grade_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);
				//////DETERMINAR A DISCIPLINA PADRÃO A SER SELECIONADA, ESSE TRATAMENTO É NECESSÁRIO, POIS AO TROCAR DE DISCIPLINA, O ID DE TURMA SUBMETIDO PODE NÃO SERVIR DE NADA, 
				//////CASO ESSA TURMA SELECIONADA NÃO APAREÇA NOVAMENTE COM A TROCA DE DISCIPLINA.
				$flag = 0;
				for($i = 0; $i < COUNT($this->data['lista_turmas']); $i++)
				{
					if($this->data['lista_turmas'][$i]['Turma_id'] == $turma_id)
						$flag = 1;
				}
				if($flag == 1)
					$this->data['url_part']['turma_id'] = $turma_id;///SE A TURMA EXISTE NA DISCIPLINA SELECIONADA ENTÃO MANTÉM O ID DE TURMA SUBMETIDO.
				else 
				{
					$this->data['url_part']['turma_id'] = $this->data['lista_turmas'][0]['Turma_id'];//CASO CONTRÁRIO PEGAR POR DEFAUL O PRIMEIRO ID DISPONÍVEL.
					$turma_id = $this->data['lista_turmas'][0]['Turma_id'];
				}
				//////obter os alunos para a chamada
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disc_grade_id, $turma_id);
				
				$this->data['url_part']['disc_grade_id'] = $disc_grade_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;
				
				/////obter a lista de horários.
				$this->data['lista_horarios'] = $this->Professor_model->get_horarios_professor($disc_grade_id, $turma_id, $this->professor_id);
				$this->view("professor/create", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
			RESPONSÁVEL POR CARREGAR DA MODEL A LISTA DE ALUNOS DE ACORDO COM O HORÁRIO, O QUE ACARRETA EM
			CARREGAR APENAS ALUNOS DA SUBTURMA EM QUESTÃO CASO EXISTA SUBTURMA.
		*/
		public function get_alunos_chamada($disc_grade_id, $turma_id, $disc_hor_id)
		{
			$resultado = "sucesso";
			$this->load->helper("mstring");
			//print_r($this->Professor_model->get_alunos_chamada($disc_grade_id, $turma_id, $disc_hor_id));

			$this->data['lista_alunos'] = $this->Professor_model->get_alunos_chamada($disc_grade_id, $turma_id, $disc_hor_id);
			$resultado = $this->load->view("professor/_alunos", $this->data, TRUE);


			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}

		public function store_chamada()
		{
			$resultado = "sucesso";
			$dataToSaveItem = array();
			$dataToSave = array();
			for($i = 0; $i < $this->input->post('qtd_aluno'); $i++)
			{
				$dataToSaveItem = array(
					'Id' => $this->input->post('calendario_presenca_id'.$i),
					'Matricula_id' => $this->input->post('matricula'.$i),
					'Presenca' => (empty($this->input->post('presenca'.$i)) ? 0 : $this->input->post('presenca'.$i)),
					'Justificativa' => $this->input->post('justificativa'.$i)
				);
				array_push($dataToSave, $dataToSaveItem);
			}

			$conteudo = $this->input->post('conteudo_lecionado');
			$disc_hor_id = $this->input->post('horarios');

			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					//$resultado = $this->valida_modulo($dataToSave);

				 	if($resultado == 1)
				 	{
				 		$this->Calendario_presenca_model->set_presenca($dataToSave);
				 		$this->conteudo_model->set_conteudo($conteudo, $disc_hor_id);
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
				redirect('professor/faltas');
		}
	}
?>