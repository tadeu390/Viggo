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
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS AS TURMAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE, $field = FALSE, $order = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Horários';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma(FALSE, FALSE, $page, FALSE, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_turmas']) ? $this->data['lista_turmas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];
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
			delete_cookie ('horario');
			$this->data['title'] = 'Alterar horário';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				///DADOS DA TURMA

				//carregar informações básicas da turma.
				$this->data['lista_disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);

				//carregar regras do periodo letivo associado a turma.
				$this->data['regras'] = $this->Regras_model->get_regras(FALSE, $this->data['lista_disc_turma_header']['Periodo_letivo_id'], FALSE, FALSE, FALSE);
				
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				
				$this->data['lista_grades'] = $this->Grade_model->get_grade_por_mc(FALSE, 
					$this->data['lista_disc_turma_header']['Modalidade_id'], 
					$this->data['lista_disc_turma_header']['Curso_id']);
				//periodo da grade
				$this->data['lista_periodo_grade'] = $this->Grade_model->get_periodo_grade($this->Disc_turma_model->get_grade_id_turma($id)['Grade_id']);
				
				///DADOS DA TURMA

				//CARREGAR TODOS OS INTERVALOS PARA PODER MONTAR O HORÁRIO
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($this->data['lista_disc_turma_header']['Periodo_letivo_id']);
				$this->data['qtd_intervalo_por_dia'] = $this->Intervalo_model->get_qtd_intervalo_dia($this->data['lista_disc_turma_header']['Periodo_letivo_id']);

				//CARREGAR TODAS AS DISCIPLINAS DA TURMA
				$this->data['lista_disc_turma_disciplina'] = $this->Disc_turma_model->get_grade_disciplina(
					$this->Disc_turma_model->get_grade_id_turma($id)['Grade_id'], 
					$this->Disc_turma_model->get_periodo_turma($id)['Periodo'], $id);
				
				//CARREGAR OS DADOS DOS HORÁRIOS VINCULADOS AS DISCIPLINAS
				$this->data['lista_disc_turma_horario'] = $this->Horario_model->get_disc_hor_turma($id);
				//print_r($this->data['lista_disc_turma_horario']);
				//print_r($this->data['lista_disc_turma_header']);

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
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DOS HORÁRIOS DE CADA DISCIPLJNA.
		*
		*	$lista_horarios -> Contém uma lista de horários informados no formulário
		*	$lista_disc_hor -> Contém uma lista  de horários de cada disciplina
		*/
		public function store_banco($lista_horarios, $lista_disc_hor)
		{
			$this->Horario_model->set_horario($lista_horarios);
			$this->Horario_model->set_disc_hor($lista_disc_hor);
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
					$horario = array();
					$disc_hor = array();
					$lista_horarios = array();
					$lista_disc_hor = array();
					//rodar um for pra pegar todos os dias da semana
					for($i = 0; $i < 7; $i++)
					{

						for($j = 0; $j < $this->input->post('maior_qtd'); $j++)//FAZ A LEITURA DE TODAS AS AULAS DE CADA DIA IGNORANDO INTERVALOS
						{
							//echo 'hour_init_dia'.($i + 1).'_aula'.($j + 1);
							//echo"<br />";
							if(!empty($this->input->post('hour_fim_dia'.($i + 1).'_aula'.($j + 1))))//ignorar coluna dos intervalos
							{

								$horario = array(
									'Dia' => ($i + 1),
									'Aula' => ($j + 1),
									'Inicio' => $this->input->post('hour_init_dia'.($i + 1).'_aula'.($j + 1)),
									'Fim' => $this->input->post('hour_fim_dia'.($i + 1).'_aula'.($j + 1))
								);
								array_push($lista_horarios, $horario);
								//print_r($horario);
								//echo "<br />";
								for($k = 0; $k < $this->input->post('qtd_sub_turma'); $k++)
								{
									if($this->input->post('dia'.($i + 1).'_aula'.($j + 1).'_disc_turma'.($k + 1)) != 0)
									{
										/*echo $this->input->post('dia'.($i + 1).'_aula'.($j + 1).'_disc_turma'.($k + 2))."-<br />";
										$sub_turma = 0;
										if($this->input->post('dia'.($i + 1).'_aula'.($j + 1).'_disc_turma'.($k + 2)) > 0 || $this->input->post('dia'.($i + 1).'_aula'.($j + 1).'_disc_turma'.$k) > 0)//se o sucessor estiver marcado ou o anterior estiver marcado(nesse caso seria a última posicao do array)
											$sub_turma = ($k + 1);*/	

										$disc_hor = array(
											'Disc_turma_id' => $this->input->post('dia'.($i + 1).'_aula'.($j + 1).'_disc_turma'.($k + 1)), 
											'Sub_turma' => (($this->input->post('qtd_sub_turma') == 1) ? 0 :($k + 1)),//se nao tiver sub_turmas entao zera
											'Dia' => ($i + 1),
											'Aula' => ($j + 1),
											'Inicio' => $this->input->post('hour_init_dia'.($i + 1).'_aula'.($j + 1)),
											'Fim' => $this->input->post('hour_fim_dia'.($i + 1).'_aula'.($j + 1))
										);
										array_push($lista_disc_hor, $disc_hor);
									}
								}
								//echo "<br />";
								//echo "<br />";
							}
						}
						
						//echo"<br />";
					}
					/*for($i = 0; $i< count($lista_disc_hor); $i++)
					{
						print_r($lista_disc_hor[$i]);
						echo "<br />";
					}*/

					$this->store_banco($lista_horarios, $lista_disc_hor);
					
					//se alguma disciplina foi deletada do horário então remover ela do banco
					$this->Horario_model->delete_disc_hor($lista_disc_hor, $this->input->post('id'));

					$arr = array('response' => $resultado);
					header('Content-Type: application/json');
					echo json_encode($arr);
				}
			 }
			 else
				redirect('horario/index');
		}
	}
?>