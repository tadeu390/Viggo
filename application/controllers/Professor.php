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
		private $etapa_id_default;

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

			//$this->load->model('Professor_model');
			$this->load->model('Regras_model');
			$this->load->model('Nota_model');
			$this->load->model('Etapa_model');
			$this->load->model('Account_model');
			$this->load->model('Descricao_nota_model');
			$this->load->model('Calendario_presenca_model');
			$this->load->model('Conteudo_model');
			$this->load->model('Turma_model');
			$this->load->model('Disc_turma_model');
			$this->load->model('Disciplina_model');
			$this->load->model('Intervalo_model');
			$this->load->model('Horario_model');
			$this->load->model('Matricula_model');
			$this->load->model('Aluno_model');

			//ABAIXO DETERMINA O PROFESSOR E O PERÍODO LETIVO
			$this->professor_id = $this->Account_model->session_is_valid()['id'];
			$this->periodo_letivo_id = $this->input->cookie('periodo_letivo_id');

			//ESTA CLASSE SÓ PODE SER ACESSADA DESDE QUE UM PERÍODO LETIVO ESTEJA SELECIONADO.
			if(empty($this->periodo_letivo_id))
				redirect("academico/professor");

			//A DISCIPLINA CARREGA COM BASE NO HORÁRIO.
			$this->disciplina_id_default = (empty($this->Disciplina_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disciplina_id']) ? 
														$this->Disciplina_model->get_disciplinas_prof($this->professor_id, $this->periodo_letivo_id)[0]['Disciplina_id'] : 
														$this->Disciplina_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Disciplina_id']);

			//A TURMA ESTÁ AMARRADA A DISCIPLINA, O QUE CONSEQUENTEMENTE CARREGA COM BASE NA DISCIPLINA
			$this->turma_id_default = (empty($this->Disciplina_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']) ? $this->Disciplina_model->get_disciplinas_prof($this->professor_id, $this->periodo_letivo_id)[0]['Turma_id'] : $this->Disciplina_model->get_disciplina_default($this->professor_id, $this->periodo_letivo_id)['Turma_id']);
			
			$this->sub_turma_default = $this->Turma_model->get_sub_turma_default($this->disciplina_id_default, $this->turma_id_default);
			
			//CARREGA A ETAPA PADRÃO COM BASE NA DATA.
			$this->etapa_id_default = $this->Etapa_model->get_etapa_default($this->periodo_letivo_id)['Id'];
			/////////

			$this->data['periodo'] = $this->Regras_model->get_regras(FALSE, $this->input->cookie('periodo_letivo_id'), FALSE, FALSE, FALSE)['Nome_periodo'];
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR AS INFORMAÇÕES BÁSICAS UTILIZADAS EM TODOS OS MÉTODOS PRINCIPAIS, 
		*	COMO O NOTAS, FALTAS, ETC.
		*	ISSO É NECESSÁRIO, POIS COMO A TELA É PRATICAMENTE A MESMA, QUANDO USANDO OS MÉTODOS PRINCIPAIS, 
		*	ENTÃO MUITA INFORMAÇÃO
		*	É EM COMUM ENTRE ELES PARA MONTAR A TELA.
		*
		*	$turma_id -> Id da turma para carregar os alunos.
		*	$disciplina_id -> Id da disciplina para carregar alunos.
		*	$subturma -> Se desejar carregar uma subturma específica então passa a subturma.
		*	$etapa_id -> Etapa que se deseja carregar.
		*/
		public function dados_base($turma_id, $disciplina_id, $subturma, $etapa_id)
		{
			$this->data['lista_disciplinas'] = $this->Disciplina_model->get_disciplinas_prof($this->professor_id, $this->periodo_letivo_id);
			$this->data['lista_etapas'] = $this->Etapa_model->get_etapa($this->periodo_letivo_id, FALSE, FALSE);
			$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);
			$this->data['lista_alunos'] = $this->Aluno_model->get_aluno_turma($disciplina_id, $turma_id, $subturma);

			$this->data['url_part']['turma_id'] = $turma_id;
			$this->data['url_part']['disciplina_id'] = $disciplina_id;
			$this->data['url_part']['etapa_id'] = $etapa_id;
		}
		/*!
		*	RESPONSÁVEL POR DETERMINAR A TURMA QUE PERMANECERÁ SELECIONADA NA TELA, SE AO TROCAR DE DISCIPLINA, 
		*	A TURMA QUE ESTAVA SELECIONADA
		*	ANTES NÃO ESTIVER ASSOCIADA COM A DISCIPLINA TROCADA ENTÃO PROCURAR A PRIMEIRA TURMA DE ACORDO 
		*	COM A DISCIPLINA QUE FOI PASSADA.
		*
		*	$lista_turmas -> Array contendo todas as turmas relacionadas com uma disciplina.
		*	$turma_id -> Id da turma selecionada na tela do professor.
		*/
		public function determina_turma($lista_turmas, $turma_id)
		{
			if(empty($lista_turmas))
				return 0;
			$flag = 0;
			for($i = 0; $i < COUNT($lista_turmas); $i++)
			{
				if($lista_turmas[$i]['Turma_id'] == $turma_id)
					$flag = 1;
			}
			if($flag == 1)
				return $turma_id;///SE A TURMA EXISTE NA DISCIPLINA SELECIONADA ENTÃO MANTÉM O ID DE TURMA SUBMETIDO.
			else 
				return $lista_turmas[0]['Turma_id'];//CASO CONTRÁRIO PEGAR POR DEFAUL O PRIMEIRO ID DISPONÍVEL.
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS DADOS DE NOTA DE CADA ALUNO DE UM DETERMINADO PROFESSOR 
		*	E ENVIA-LOS A VIEW.
		*
		*	$disciplina_id -> Id da disciplina da grade. É usado para se obter as notas da disciplina 
		*	pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$etapa_id -> Id da etapa especificado pelo usuário quando clica nos botões de etapas.
		*/
		public function notas($disciplina_id = FALSE, $turma_id = FALSE, $etapa_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTÃO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$etapa_id = $this->etapa_id_default;
			}

			$this->data['title'] = 'Notas e faltas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_prof($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
				$turma_id = $this->determina_turma($this->data['lista_turmas'], $turma_id);

				$this->data['method'] = __FUNCTION__;
				$this->data['periodo_letivo_id'] = $this->periodo_letivo_id;

				$this->dados_base($turma_id, $disciplina_id, FALSE, $etapa_id);

				///DETERMINAR SE A ETAPA ESTÁ ABERTA.
				if($this->Etapa_model->get_status_etapa($this->data['etapa']['Id']) == true)
					$this->data['status_etapa'] = '';
				else 
					$this->data['status_etapa'] = "disabled";
				/////

				$this->data['lista_descricao_nota'] = $this->Descricao_nota_model->get_descricao(TRUE, FALSE);

				$this->data['lista_colunas_nota'] = $this->Nota_model->get_colunas_nota($disciplina_id, $turma_id, $etapa_id);

				$this->view("professor/notas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER A NOTA A SER CADASTRADA/ALTERADA DE UM DETERMINADO ALUNO.
		*	
		*	$nota -> Nota do aluno.
		*	$descricao_nota_id -> Descrição da nota, ou seja, o tipo.
		*	$matricula_id -> Matrícula do aluno na disciplina.
		*	$etapa_id -> Etapa que terá a nota do aluno alterada.
		*	$disciplina_id -> Disciplina a alterar a nota.
		*	$turma_id -> Turma do aluno
		*	obs.:  os dois últimos parâmetros são apenas para verificar se o professor pode realizara a ação.
		*/
		public function altera_nota($nota, $descricao_nota_id, $matricula_id, $etapa_id, $disciplina_id, $turma_id)
		{
			$resultado = "sucesso";
			$somatorio = 0;
			$status = "";
			$status_rec = "";
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE AND $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				if($this->disc_turma_professor($disciplina_id, $this->professor_id, $turma_id) == true)//verifica se o professor logado da aula pra uma turma em uma determinada disciplina
				{
					if($nota == 'null')
						$nota = null;
					
					$resultado = $this->Nota_model->validar_nota($matricula_id, $etapa_id, $nota, $descricao_nota_id);

					$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);

					///DETERMINAR SE A ETAPA ESTÁ ABERTA.
					//if($this->Etapa_model->get_status_etapa($etapa_id) == true)
					//{
						if($resultado != "invalido")
						{
							$somatorio = $resultado;
							$resultado = $this->Nota_model->set_notas($nota, $descricao_nota_id, $matricula_id, $etapa_id);

							$status = $this->Nota_model->status_nota($etapa_id, $this->periodo_letivo_id, $somatorio);
							
							if($status == "ok")
								$status = "info";
							else
								$status = "danger";

							if($descricao_nota_id == RECUPERACAO_PARALELA)
							{
								if($this->Nota_model->status_nota($etapa_id, $this->periodo_letivo_id, $nota) == "ok")
									$status_rec = "info";
								else
									$status_rec = "danger";
							}
						}
						else 
							$resultado = "O valor informado ultrapassa o limite de ".$this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Valor']." pontos estabelecido para o ".$this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Nome'].".";
					//}
					//else
					//	$resultado = "Não é possível alterar a nota.";
				}
			}
			else
				$resultado = "Você não tem permissão para realizar esta ação. Entre em contato com o administrador do sistema.";
			
			$arr = array('response' => $resultado, 'somatorio' => $somatorio, 'status' => $status, 'status_rec' => $status_rec);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE O PROFESSOR DA AULA PARA UMA TURMA EM UMA DETERMINADA DISCIPLINA.
		*	ISSO É ÚTIL PARA VALIDAR A INSERÇÃO, REMOÇÃO OU ALTERAÇÃO DE UMA NOTA E TAMBÉM A REMOÇÃO DE 
		*	UMA COLUNA DE NOTA PELO PROFESSOR.
		*
		*	$disciplina_id -> Id da disciplina a ser validada.
		*	$professor_id -> Id do professor logado no sistema.
		*	$turma_id -> Id da turma a ser validada.
		*/
		public function disc_turma_professor($disciplina_id, $professor_id, $turma_id)
		{
			$turmas = $this->Turma_model->get_turma_prof($disciplina_id, $professor_id, $this->periodo_letivo_id);

			for($i = 0; $i < COUNT($turmas); $i)
			{
				if($turmas[$i]['Turma_id'] == $turma_id)
					return true;
			}
			return false;
		}
		/*!
		*	RESPONSÁVEL POR SOLICITAR A MODEL A REMOÇÃO DE UMA COLUNA INTEIRA DE NOTA PARA UMA DETERMINADA DISCIPLINA,
		*	ESTA AÇÃO NÃO É REVERSÍVEL DE FORMA ALGUMA ATÉ O PRESENTE MOMENTO.
		
		*	$descricao_nota_id -> Id da nota que se quer remover da disciplina.
		*	$turma_id -> Id da turma que está associada com a disciplina.
		*	$disciplina_id -> Id da disciplina que se quer remover a nota.
		*	$etapa_id -> Id da etapa que está sendo removida a coluna de nota.
		*/
		public function remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $etapa_id)
		{
			$resultado = "sucesso";
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				if($this->disc_turma_professor($disciplina_id, $this->professor_id, $turma_id) == true)//verifica se o professor logado da aula pra uma turma em uma determinada disciplina
				{
					$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);

					///DETERMINAR SE A ETAPA ESTÁ ABERTA.
		//			if($this->Etapa_model->get_status_etapa($etapa_id) == true)
						$this->Nota_model->remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $etapa_id);
		//			else
		//				$resultado = "Não foi possível apagar a coluna de nota.";
				}
			}
			else 
				$resultado = "Você não tem permissão para realizar esta ação.";
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DISCIPLINAS E TODOS OS DADOS DE FALTAS DE CADA ALUNO DE 
		*	UM DETERMINADO PROFESSOR E ENVIA-LOS A VIEW.
		*
		*	$disciplina_id -> Id da disciplina da grade. É usado para se obter as faltas da disciplina pra cada aluno.
		*	$turma_id -> Id da turma que está sendo consultada pelo professor.
		*	$etapa_id -> Id da etapa especificado pelo usuário quando clicar nos botões de etapas.
		*/
		public function faltas($disciplina_id = FALSE, $turma_id = FALSE, $etapa_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECIFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$etapa_id = $this->etapa_id_default;
			}
			
			$this->data['title'] = 'Notas e faltas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_prof($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
				$turma_id = $this->determina_turma($this->data['lista_turmas'], $turma_id);
				$this->data['meses'] = $this->Calendario_presenca_model->get_intervalo_mes(
					$this->convert_date($this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Data_inicio'],"en"), 
					$this->convert_date($this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Data_fim'],"en"));

				$this->dados_base($turma_id, $disciplina_id, FALSE, $etapa_id);
				
				//especificar uma subturma default para a turma em questão
				$this->data['lista_subturmas'] = $this->Turma_model->get_sub_turmas($disciplina_id, $turma_id, date('Y-m-d'));
				if($this->sub_turma_default == null)
					$this->sub_turma_default = (empty($this->data['lista_subturmas'][0]['Sub_turma']) ? 0 : $this->data['lista_subturmas'][0]['Sub_turma']);

				$this->view("professor/faltas", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL AS INFORMAÇÕES PARA A CHAMADA, COMO A LISTA DE ALUNOS, OS HORÁRIOS.
		*
		*	$disciplina_id -> Id da disciplina para fazer a chamada.
		*	$turma_id -> Id da turma para se fazer a chamada.
		*	$etapa_id -> Id da etapa (somente para identificar o etapa a ser selecionado na tela e para verificar
		*	se o etapa está aberto ou não)
		*/
		public function chamada($disciplina_id = FALSE, $turma_id = FALSE, $etapa_id = FALSE)
		{
			if($disciplina_id == FALSE)//SE NADA FOI ESPECFICADO ENTAO DETERMINAR A PARTIR DOS DEFAULT.
			{
				$disciplina_id = $this->disciplina_id_default;
				$turma_id = $this->turma_id_default;
				$etapa_id = $this->etapa_id_default;
			}
			
			$this->data['title'] = 'Notas e faltas';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE && $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['method'] = __FUNCTION__;
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_prof($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
				$turma_id = $this->determina_turma($this->data['lista_turmas'], $turma_id);

				////obter a lista de sub_turmas
				$this->data['lista_subturmas'] = $this->Turma_model->get_sub_turmas($disciplina_id, $turma_id, date('Y-m-d'));
				
				//especificar uma subturma default para a turma em questão
				if($this->sub_turma_default == null)
					$this->sub_turma_default =  (empty($this->data['lista_subturmas'][0]['Sub_turma']) ? 0 : $this->data['lista_subturmas'][0]['Sub_turma']);
				
				$this->dados_base($turma_id, $disciplina_id, $this->sub_turma_default, $etapa_id);

				$this->data['sub_turma'] = $this->sub_turma_default;

				/////obter a lista de horários.
				$this->data['lista_horarios'] = $this->Horario_model->get_horarios_disciplina($disciplina_id, $turma_id, $this->sub_turma_default, date('Y-m-d'), 1);
				if(empty($this->data['lista_horarios']))
					$this->data['lista_horarios'] = $this->Horario_model->get_horarios_disciplina($disciplina_id, $turma_id, $this->sub_turma_default, date('Y-m-d'), 0);

				if(!empty($this->data['lista_horarios']))
				{
					for($i = 0; $i < COUNT($this->data['lista_horarios']); $i ++)
					{
						$this->data['conteudo'] = $this->Conteudo_model->get_conteudo($this->data['lista_horarios'][$i]['Disc_hor_id'], date('Y-m-d'));	
						if(!empty($this->data['conteudo']))//tentar até achar em algum horário, isso porque pra mesma data é registrado o mesmo conteudo independentemente 
						//da quantidade de aula e se for removido alguma disciplina do horario entao é necessario procurar nas restantes que ficou até achar o coteúdo.
							break;
					}
				}
				else 
					$this->data['conteudo'] = "";

				$this->view("professor/chamada", $this->data);
			}
			else
				redirect("Professor/faltas");
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL TODAS AS SUBTURMAS DE UMA DETERMINADA DISCIPLINA DE UMA DETERMINADA 
		*	TURMA EM UMA DETERMINADA DATA (DIA DA SEMANA). SE NÃO HOUVER SUBTURMA PRA TAL OCASIÃO A MODEL 
		*	RETORNARÁ SUBTURMA 0.
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

			$this->data['lista_subturmas'] = $this->Turma_model->get_sub_turmas($disciplina_id, $turma_id, $data);
			$resultado = $this->load->view("professor/_subturmas", $this->data, TRUE);

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL A LISTA DE ALUNOS DE ACORDO COM O HORÁRIO, O QUE ACARRETA EM
		*	CARREGAR APENAS ALUNOS DA SUBTURMA EM QUESTÃO CASO EXISTA SUBTURMA.
		*
		*	$disciplina_id -> Disciplina que os alunos cursam.
		*	$turma_id -> Turma que se quer obter os alunos.
		*	$subturma -> Se tiver uma subturma.
		*	$data -> Data da chamada selecionada.
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

			$this->data['lista_alunos'] = $this->Aluno_model->get_aluno_turma($disciplina_id, $turma_id, $subturma);
			/////obter a lista de horários.
			$this->data['lista_horarios'] = $this->Horario_model->get_horarios_disciplina($disciplina_id, $turma_id, $subturma, $data, 1);
			if(empty($this->data['lista_horarios']))
			{
				$status = "vazio";
				$this->data['lista_horarios'] = $this->Horario_model->get_horarios_disciplina($disciplina_id, $turma_id, $subturma, $data, 0);
			}
			
			if(!empty($this->data['lista_horarios']))
			{
				for($i = 0; $i < COUNT($this->data['lista_horarios']); $i ++)
				{
					$this->data['conteudo'] = $this->Conteudo_model->get_conteudo($this->data['lista_horarios'][$i]['Disc_hor_id'], $data);	
					if(!empty($this->data['conteudo']))//tentar até achar em algum horário, isso porque pra mesma data é registrado o mesmo conteudo independentemente 
					//da quantidade de aula e se for removido alguma horario da disciplnina entao é necessario procurar nas restantes que ficou até achar o conteúdo.
						break;
				}
			}
			else 
				$this->data['conteudo'] = "";
			
			$resultado = $this->load->view("professor/_alunos", $this->data, TRUE);
			
			$arr = array('response' => $resultado, 'status' => $status);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR PEGAR DO FORMULÁRIO A CHAMADA DOS ALUNOS, A JUSTIFICATIVA DE FALTA E O CONTEÚDO LECIONADO.
		*/
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
							'Disc_hor_id' => $this->input->post('disc_hor_id'.$j),
							'Data_registro' => $this->convert_date($this->input->post('data_atual'),"en")
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
		/*!
		*	RESPONSÁVEL POR CARREGAR O DADOS PARA A ETAPA EXTRA, LEVANDO EM CONSIDERAÇÃO A DATA EM QUE ABRE E FECHA.
		*
		*	$disciplina_id -> Disciplina para a etapa.
		*	$turma_id -> Turma para a etapa.
		*	$etapa_id -> Id de uma determinada etapa extra, uma vez que podem existir várias.
		*/
		public function etapa_extra($disciplina_id, $turma_id, $etapa_id)
		{
			$this->data['title'] = 'Notas e faltas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['method'] = "notas";
				$this->data['lista_turmas'] = $this->Turma_model->get_turma_prof($disciplina_id, $this->professor_id, $this->periodo_letivo_id);
				$turma_id = $this->determina_turma($this->data['lista_turmas'], $turma_id);

				$this->dados_base($turma_id, $disciplina_id, FALSE, $etapa_id);
				
				$this->data['lista_colunas_nota'] = array();
				$this->data['lista_alunos'] = array();
				$this->data['periodo_letivo_id'] = $this->periodo_letivo_id;

				//determinar se a etapa anterior já passou 
				if($this->Etapa_model->etapa_ja_passou($this->Etapa_model->get_etapa_anterior($etapa_id)['Id']) == true)
				{
					$this->data['status_etapa_extra'] = '';

					$this->data['lista_colunas_nota'] = $this->Nota_model->get_colunas_nota($disciplina_id, $turma_id, $etapa_id);
					$this->data['lista_alunos'] = $this->Aluno_model->get_aluno_turma($disciplina_id, $turma_id);

					$regras = $this->Regras_model->get_regras(FALSE, $this->periodo_letivo_id, FALSE, FALSE, FALSE);
					do{
						$etapas = "";
						$media = 0;
						$e = $this->Etapa_model->get_etapa_anterior($etapa_id);

						$etapa_id = $e['Id'];

						if($e['Tipo'] == ETAPA_NORMAL)//se for, buscar todas as ids de etapa para o período letivo corrente
						{
							$lista_etapas = $this->Etapa_model->get_etapa($this->periodo_letivo_id, FALSE, ETAPA_NORMAL);
							for($i = 0; $i < COUNT($lista_etapas); $i++)
							{
								$etapas = $etapas.$lista_etapas[$i]['Id'];
								if($i != (COUNT($lista_etapas) - 1))
									$etapas = $etapas.",";
							}
							$media = $this->Regras_model->get_regras(FALSE, $this->periodo_letivo_id, FALSE, FALSE, FALSE)['Media'];
						}
						else
						{
							$etapas = $etapa_id;
							$media = $e['Media'];
						}

						//remover da lista os alunos que estão aprovados
						$lista_alunos = $this->data['lista_alunos'];
						for($i = 0; $i < COUNT($lista_alunos); $i++)
						{
							if($this->Nota_model->situacao_nota_aluno_disciplina($this->data['lista_alunos'][$i]['Matricula_id'], $etapas, $media) == APROVADO && 
							   $this->Calendario_presenca_model->situacao_falta_aluno($turma_id, $this->data['lista_alunos'][$i]['Aluno_id'], $regras, $e['Tipo']) == APROVADO)
								unset($this->data['lista_alunos'][$i]);
							
							if($this->Etapa_model->get_etapa_anterior($etapas)['Tipo'] == ETAPA_NORMAL)
							{
								$disc_mat = $this->Matricula_model->get_matriculas($lista_alunos[$i]['Aluno_id'], $turma_id);//levantar as ids de todas as disciplinas que o aluno cursa
								$reprovas = array();
								for($j = 0; $j < COUNT($disc_mat); $j++)
								{
									if($this->Nota_model->situacao_nota_aluno_disciplina($disc_mat[$j]['Matricula_id'], $etapas, $media) != APROVADO)
										array_push($reprovas, 1);
								}
								if(COUNT($reprovas) > $regras['Reprovas'])
									unset($this->data['lista_alunos'][$i]);//NÃO PODE IR PARA PROGRESSÃO PARCIAL SE REPROVAR EM MAIS DO QUE A REGRA PERMITE.
							}
						}
						$this->data['lista_alunos'] = array_values($this->data['lista_alunos']);//resetar o indice do array
					}while($e['Tipo'] != ETAPA_NORMAL);//etapa normal é a última
				}
				else
					$this->data['status_etapa_extra'] = "disabled";

				$this->data['lista_descricao_nota'] = $this->Descricao_nota_model->get_descricao(TRUE, FALSE);

				$this->view("professor/etapa_extra", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR OS DADOS QUE IRÃO MONTAR UMA TABELA CONTENDO TODOS OS ALUNOS E TODAS AS NOTAS 
		*	E FALTAS DE CADA UM E A SITUAÇÃO (APROVADO, REPROVADO, RECUPERACAO FINAL, ESTUDOS INDEPENDENTES).
		*	
		*	$disciplina_id -> Id da disciplina para se consultar os dados.
		*	$turma_id -> Id da turma para indentificar uma disciplina em uma turma.
		*/
		public function visao_geral($disciplina_id, $turma_id)
		{
			$resultado = "sucesso";

			//TRAZER TODOS OS ALUNOS
			$this->data['lista_alunos'] = $this->Aluno_model->get_aluno_turma($disciplina_id, $turma_id, FALSE);
			$this->data['lista_etapas'] = $this->Etapa_model->get_etapa($this->periodo_letivo_id, FALSE, FALSE);
			$this->data['disciplina_id'] = $disciplina_id;
			$this->data['turma_id'] = $turma_id;
			$this->data['regra_letiva'] = $this->Regras_model->get_regras(FALSE, $this->periodo_letivo_id, FALSE, FALSE, FALSE);

			$disciplina = $this->Disciplina_model->get_disciplina(FALSE, $disciplina_id, FALSE, FALSE, FALSE);
			$turma = $this->Disc_turma_model->get_disc_turma_header($turma_id);
			$periodo = $this->Disc_turma_model->get_periodo_turma($turma_id);

			$resultado = $this->load->view("/professor/_visao_geral", $this->data, TRUE);

			$arr = array('response' => $resultado, 'disciplina' => $disciplina['Nome_disciplina'], 'turma' => $turma['Nome_turma'], 'periodo' => $periodo['Periodo']);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR DA MODEL OS HORÁRIOS DE UMA ESPECÍFICA PARA O PROFESSOR.
		*	
		*	$turma_id -> Id da turma que se deseja obter os horários.
		*/
		public function horarios_turma($turma_id)
		{
			//validar o turma id, para garantir que o professor acesse somente os horários das turmas que lhe diz respeito.
			$flag = 0;
			$resultado = "sucesso";
			$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($turma_id);
			$periodo = $this->Disc_turma_model->get_periodo_turma($turma_id);
			$turmas = $this->Turma_model->get_turma_prof(FALSE, $this->professor_id, $this->periodo_letivo_id);
			for($i = 0; $i < COUNT($turmas); $i ++)
			{
				if($turmas[$i]['Turma_id'] == $turma_id)
					$flag = 1;
			}
			if($flag == 1)
			{
				$this->data['regras'] = $this->Regras_model->get_regras(FALSE, $this->data['lista_disc_turma_header']['Periodo_letivo_id'], FALSE, FALSE, FALSE);
				
				///DADOS DA TURMA

				//CARREGAR TODOS OS INTERVALOS PARA PODER MONTAR O HORÁRIO
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($this->data['lista_disc_turma_header']['Periodo_letivo_id']);
				$this->data['qtd_intervalo_por_dia'] = $this->Intervalo_model->get_qtd_intervalo_dia($this->data['lista_disc_turma_header']['Periodo_letivo_id']);

				//CARREGAR TODAS AS DISCIPLINAS DA TURMA
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_grade_disciplina(
					$this->Disc_turma_model->get_grade_id_turma($turma_id)['Grade_id'], 
					$this->Disc_turma_model->get_periodo_turma($turma_id)['Periodo'], $turma_id);
				
				//CARREGAR OS DADOS DOS HORÁRIOS VINCULADOS AS DISCIPLINAS
				$this->data['lista_disc_turma_horario'] = $this->Horario_model->get_disc_hor_turma($turma_id);

				$resultado = $this->load->view("/professor/horario", $this->data, TRUE);
			}
			else
				$resultado = "Você não tem permissão para visualizar o horário dessa turma.";

			$arr = array('response' => $resultado, 'turma' => $this->data['lista_disc_turma_header']['Nome_turma'],'periodo' => $periodo['Periodo']);
				header('Content-Type: application/json');
				echo json_encode($arr);
		}
	}
?>