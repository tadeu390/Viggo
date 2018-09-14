<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS AÇÕES DO PROFESSOR.
	*/
	class Professor extends Geral 
	{
		private $professor_id;
		private $periodo_letivo_id;
		private $disciplina_id_default;
		private $turma_id_default;
		private $sub_turma_default;
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
			$this->load->model('Nota_especial_model');

			//ABAIXO DETERMINA O PROFESSOR E O PERÍODO LETIVO
			$this->professor_id = $this->Account_model->session_is_valid()['id'];
			$this->periodo_letivo_id = $this->input->cookie('periodo_letivo_id');
			
			//ESTA CLASSE SÓ PODE SER ACESSADA NA PRESÊNÇA DE UM PERÍODO LETIVO SELECIONADO.
			if(empty($this->periodo_letivo_id))
				redirect("academico/professor");

			/////////DISCIPLINA, TURMA E BIMESTRE PADRÃO.
			//A DISCIPLINA CARREGA COM BASE NO HORÁRIO.
			$this->disciplina_id_default = (empty($this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disciplina_id']) ? 
														$this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id)[0]['Disciplina_id'] : 
														$this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disciplina_id']);
			
			//A TURMA ESTÁ AMARRADA A DISCIPLINA, O QUE CONSEQUENTEMENTE CARREGA COM BASE NA DISCIPLINA
			$this->turma_id_default = (empty($this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']) ? $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id)[0]['Turma_id'] : $this->Professor_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']);
			
			$this->sub_turma_default = $this->Professor_model->get_sub_turma_default($this->disciplina_id_default, $this->turma_id_default);

			//echo $this->sub_turma_default;
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
		*	$disciplina_id -> Id da disciplina da grade. É usado para se obter as notas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function notas($disciplina_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_notas_especiais'] = $this->Nota_especial_model->get_nota_especial($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
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

				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;

				//DESCRIÇÃO DE NOTA
				$this->data['lista_descricao_nota'] = $this->Descricao_nota_model->get_descricao(TRUE, FALSE);
				//////

				///TABELA DE NOTAS
				$this->data['lista_colunas_nota'] = $this->Professor_model->get_colunas_nota($disciplina_id, $turma_id, $bimestre_id);
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disciplina_id, $turma_id);
				//////

				$this->view("professor/notas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		public function notas_geral($disciplina_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = "notas";
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_notas_especiais'] = $this->Nota_especial_model->get_nota_especial($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
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

				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;

				///TABELA DE ALUNOS
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disciplina_id, $turma_id);
				//////

				$this->view("professor/notas_geral", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
			RESPONSÁVEL POR RECEBER OS DADOS DO FORMULÁRIO A NOTA A SER ALTERADA.

		*/
		public function altera_nota($nota, $descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			if($nota == 'null')
				$nota = null;
			//Não foi possível completar esta operação. Entre em contato com o administrador do sistema.
			$resultado = "sucesso";
			$somatorio = 0;
			$status = "";
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE AND $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$resultado = $this->Nota_model->validar_nota($matricula_id, $bimestre_id, $turma_id, $disciplina_id, $nota, $descricao_nota_id);

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
						$resultado = $this->Nota_model->set_notas($nota, $descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id);

						$status = $this->Nota_model->status_nota_total_bimestre($matricula_id, $turma_id, $disciplina_id, $bimestre_id, $this->periodo_letivo_id);
						
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

		public function remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			$resultado = "sucesso";
			$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

			///DETERMINAR SE O BIMESTRE ESTÁ ABERTO.
			$timeZone = new DateTimeZone('UTC');
			$data_abertura = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_abertura'] : '00/00/000'), $timeZone);
			$data_fechamento = DateTime::createFromFormat ('d/m/Y', (!empty($this->data['bimestre']['Data_abertura']) ? $this->data['bimestre']['Data_fechamento'] : '00/00/000'), $timeZone);
			$data_atual = DateTime::createFromFormat ('d/m/Y', date('d/m/Y'), $timeZone);
			
			if($data_atual >= $data_abertura && $data_atual <= $data_fechamento)
				$this->Nota_model->remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $bimestre_id);
			else
				$resultado = "Não foi possível apagar a coluna de nota.";

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE FALTAS DE CADA ALUNO DE UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disciplina_id -> Id da disciplina da grade. É usado para se obter as faltas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function faltas($disciplina_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_notas_especiais'] = $this->Nota_especial_model->get_nota_especial($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
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

				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;
				$this->view("professor/faltas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE FALTAS DE CADA ALUNO DE UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disciplina_id -> Id da disciplina da grade. É usado para se obter as faltas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$bimestre_id -> Id do bimestre especificado pelo usuário quando clicar nos botões de bimestres;
		*/
		public function faltas_geral($disciplina_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_notas_especiais'] = $this->Nota_especial_model->get_nota_especial($this->periodo_letivo_id);
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
				$this->data['bimestre'] = $this->Bimestre_model->get_bimestre(FALSE, $bimestre_id);

				$this->data['meses'] = $this->Calendario_presenca_model->get_intervalo_mes(
					$this->convert_date($this->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Data_inicio'],"en"), 
					$this->convert_date($this->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Data_fim'],"en"));
				
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disciplina_id, $turma_id, $this->sub_turma_default);

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

				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;
				$this->view("professor/faltas_geral", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL AS INFORMAÇÕES PARA A CHAMADA, COMO A LISTA DE ALUNOS, OS HORÁRIOS.
		*
		*	$disciplina_id -> Id da disciplina para fazer a chamada.
		*	$turma_id -> Id da turma para se fazer a chamada.
		*	$bimestre_id -> Id do bimestre (somente para identificar o bimestre a ser selecionado na tela e para verificar
		*	se o bimestre está aberto ou não)
		*/
		public function chamada($disciplina_id = FALSE, $turma_id = FALSE, $bimestre_id = FALSE)
		{ ///TROCAR BIMESTRE OU BIMESTRE_ID POR ETAPA
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$bimestre_id = $this->bimestre_id_default;
			}
			
			$this->data['title'] = 'Minhas disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_disciplinas'] = $this->Professor_model->get_disciplinas($this->professor_id, $this->periodo_letivo_id);
				$this->data['lista_bimestres'] = $this->Bimestre_model->get_bimestre($this->periodo_letivo_id);
				$this->data['lista_notas_especiais'] = $this->Nota_especial_model->get_nota_especial($this->periodo_letivo_id);
				
				$this->data['lista_turmas'] = $this->Professor_model->get_turma($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
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
				
				
				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['bimestre_id'] = $bimestre_id;
				
				$this->data['sub_turma'] = $this->sub_turma_default;

				////obter a lista de sub_turmas
				$this->data['lista_subturmas'] = $this->Professor_model->get_sub_turmas($disciplina_id, $turma_id, date('Y-m-d'));
				//$subturma = (empty($this->data['lista_subturmas'][0]['Sub_turma']) ? 0 : $this->data['lista_subturmas'][0]['Sub_turma']);
				
				//$this->data['subturma'] = $subturma;
				//////obter os alunos para a chamada
				$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disciplina_id, $turma_id, $this->sub_turma_default);

				/////obter a lista de horários.
				$this->data['lista_horarios'] = $this->Professor_model->get_horarios_professor($disciplina_id, $turma_id, $this->professor_id, $this->sub_turma_default, date('Y-m-d'), 1);
				if(empty($this->data['lista_horarios']))
					$this->data['lista_horarios'] = $this->Professor_model->get_horarios_professor($disciplina_id, $turma_id, $this->professor_id, $this->sub_turma_default, date('Y-m-d'), 0);

				if(!empty($this->data['lista_horarios']))
					$this->data['conteudo'] = $this->Conteudo_model->get_conteudo($this->data['lista_horarios'][0]['Disc_hor_id']);	
				else 
					$this->data['conteudo'] = "";

				$this->view("professor/chamada", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL TODAS AS SUBTURMAS DE UMA DETERMINADA DISCIPLINA DE UMA DETERMINADA 
		*	TURMA EM UMA DETERMINADA DATA (DIA DA SEMANA). SE NÃO HOUVER SUBTURMA PRA TAL OCASIÃO A MODEL RETORNARÁ SUBTURMA 0
		*
		*	$disciplina_id -> Id da disciplina que se quer obter as subturmas.
		*	$turma_id -> Id da turma turma associada a disicplina.
		*	$data -> Data selecionada pelo professor.
		*/
		public function get_sub_turmas($disciplina_id, $turma_id, $data)
		{
			$resultado = "sucesso";
			$this->data['url_part']['disciplina_id'] = $disciplina_id;
			$this->data['url_part']['turma_id'] = $turma_id;
			$this->data['sub_turma'] = $this->sub_turma_default;

			$this->data['lista_subturmas'] = $this->Professor_model->get_sub_turmas($disciplina_id, $turma_id, $data);
			$resultado = $this->load->view("professor/_subturmas", $this->data, TRUE);

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
			RESPONSÁVEL POR CARREGAR DA MODEL A LISTA DE ALUNOS DE ACORDO COM O HORÁRIO, O QUE ACARRETA EM
			CARREGAR APENAS ALUNOS DA SUBTURMA EM QUESTÃO CASO EXISTA SUBTURMA.
		*/
		public function get_alunos_chamada($disciplina_id, $turma_id, $subturma, $data)
		{
			$resultado = "sucesso";
			$status = "ok";
			$this->data['url_part']['disciplina_id'] = $disciplina_id;
			$this->data['url_part']['turma_id'] = $turma_id;

			$this->load->helper("mstring");
			$this->load->helper("faltas");
			$this->data['data'] = $data;
			//print_r($this->Professor_model->get_alunos_chamada($disciplina_id, $turma_id, $disc_hor_id));

			$this->data['lista_alunos'] = $this->Professor_model->get_alunos($disciplina_id, $turma_id, $subturma);
			/////obter a lista de horários.
			$this->data['lista_horarios'] = $this->Professor_model->get_horarios_professor($disciplina_id, $turma_id, $this->professor_id, $subturma, $data, 1);
			if(empty($this->data['lista_horarios']))
			{
				$status = "vazio";
				$this->data['lista_horarios'] = $this->Professor_model->get_horarios_professor($disciplina_id, $turma_id, $this->professor_id, $subturma, $data, 0);
			}
			
			if(!empty($this->data['lista_horarios']))
				$this->data['conteudo'] = $this->Conteudo_model->get_conteudo($this->data['lista_horarios'][0]['Disc_hor_id']);	
			else 
				$this->data['conteudo'] = "";
			
			$resultado = $this->load->view("professor/_alunos", $this->data, TRUE);
			
			$arr = array('response' => $resultado, 'status' => $status);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}

		public function store_chamada()
		{
			$resultado = "sucesso";
			$dataToSaveItem = array();
			$dataToSave = array();
			$conteudo = array();

			for($i = 0; $i < $this->input->post('qtd_aluno'); $i++)
			{
				for($j = 0; $j < $this->input->post('qtd_coluna'); $j++)
				{
					if($i == 0)//só pege 1 na primeira volta do loop externo pra nao duplicar o conteudo lecionado
					{
						$conteudo_horario = array(
							'Id' => $this->input->post('conteudo_id'),
							'Descricao' => $this->input->post('conteudo_lecionado'),
							'Disc_hor_id' => $this->input->post('disc_hor_id'.$j)
						);
						array_push($conteudo, $conteudo_horario);
					}
					
					$dataToSaveItem = array(
						'Id' => $this->input->post('calendario_presenca_id'.$i."".$j),
						'Matricula_id' => $this->input->post('matricula_id'.$i),
						'Presenca' => (empty($this->input->post('presenca'.$i."".$j)) ? 0 : $this->input->post('presenca'.$i."".$j)),
						'Justificativa' => $this->input->post('justificativa'.$i),
						'Horario_id' => $this->input->post('horario_id'.$j),
						'Data_registro' => $this->convert_date($this->input->post('data_atual'),"en")
					);
					array_push($dataToSave, $dataToSaveItem);

				}
			}

			//print_r($dataToSave);

			


			$disc_hor_id = $this->input->post('horarios');
			//print_r($disc_hor_id);
			//print_r($conteudo);
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					//$resultado = $this->valida_chamada($dataToSave);
					$resultado = 1;
				 	if($resultado == 1)
				 	{
				 		$this->Calendario_presenca_model->set_presenca($dataToSave);
				 		$this->Conteudo_model->set_conteudo($conteudo);
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