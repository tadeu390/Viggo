<?php
	require_once("Geral.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE GENÉRICA.
	/*
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO RELATIVO AS REGRAS DO PERÍODO LETIVO, OU SEJAS, AS REGRAS ADOTADAS GERENCIAR A SITUAÇÃO DO ALUNO EM 
	*	VÁRIOS ASPECTOS.
	*/
	class Regras extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->load->model('Regras_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Intervalo_model');
			$this->load->model('Bimestre_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS PERÓODOS LETIVOS CADASTRADOS E ENVIA-LOS A VIEW.
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

			$this->data['title'] = 'Regras letivas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->data['lista_regras'] = $this->Regras_model->get_regras(FALSE, FALSE, $page, FALSE, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_regras'][0]['Size']) ? $this->data['lista_regras'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("regras/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE REGRAS DO PERÍODO LETIVO.
		*	$id -> Id de um período letivo.
		*/
		public function create($id = FALSE)//NESSE CASO USAR O ID NO PARÂMETRO DO CREATE PARA FAZER O "COPIAR PARA"
		{
			if($id == FALSE)
				$id = 0;

			$this->data['title'] = 'Nova regra';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Regras_model->get_regras(FALSE, $id, FALSE, FALSE);
				$this->data['modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo(FALSE);
				$this->data['bimestres'] = $this->Bimestre_model->get_bimestre(FALSE);
				if($id > 0)//QUANDO CLICA EM COPIAR PARA
				{
					$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($id);
					$this->data['bimestres'] = $this->Bimestre_model->get_bimestre($id);
					$this->data['obj']['Id'] = ""; //copiar para.
				}
				$this->view("regras/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE REGRAS E RECEBER DA MODEL OS DADOS 
		*	DA REGRA QUE SE DESEJA EDITAR.
		*
		* 	$id -> Contém a id de uma regra.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar regra';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Regras_model->get_regras(FALSE, $id, FALSE, FALSE);
				$this->data['modalidades'] = $this->Modalidade_model->get_modalidade(FALSE);
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($id);
				$this->data['bimestres'] = $this->Bimestre_model->get_bimestre($id);
				$this->view("regras/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('regra_ativa'),
				'Periodo' => $this->input->post('periodo'),
				'Limite_falta' => $this->input->post('limite_falta'),
				'Media' => $this->input->post('media'),
				'Modalidade_id' => $this->input->post('modalidade_id'),
				'Avaliar_faltas' => $this->input->post('avaliar_faltas'),
				'Dias_letivos' => $this->input->post('dias_letivos'),
				'Duracao_aula' => $this->input->post('duracao_aula'),
				'Hora_inicio_aula' => $this->input->post('hora_inicio_aula'),
				'Quantidade_aula' => $this->input->post('quantidade_aula'),
				'Reprovas' => $this->input->post('reprovas'),
				'Qtd_minima_aluno' => $this->input->post('qtd_minima'),
				'Qtd_maxima_aluno' => $this->input->post('qtd_maxima')
			);

			if(empty($dataToSave['Avaliar_faltas']))
				$dataToSave['Avaliar_faltas'] = 0;

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;

			$dataIntervaloToSave = array();
			for($i = 0; $i < $this->input->post('max_value_intervalo'); $i++)
			{
				if($this->input->post("dia".$i) != null)
				{
					$dataIntervaloLinhaToSave = array(
						//'Periodo_letivo_id' => $Periodo_letivo_id,
						'Dia' => $this->input->post("dia".$i),
						'Hora_inicio' => $this->input->post("hora_inicio".$i),
						'Hora_fim' => $this->input->post("hora_fim".$i)
					);
					array_push($dataIntervaloToSave, $dataIntervaloLinhaToSave);
				}
			}
			$dataToSave['intervalos'] = $dataIntervaloToSave;
			$dataBimestreToSave = array();
			for($i = 0; $i < $this->input->post('max_value_bimestre'); $i++)
			{
				if($this->input->post("nome_bimestre".$i) != null)
				{
					$dataBimestreLinhaToSave = array(
						//'Periodo_letivo_id' => $Periodo_letivo_id,
						'Nome' => $this->input->post("nome_bimestre".$i),
						'Valor' => $this->input->post("valor".$i),
						'Data_inicio' => $this->convert_date($this->input->post("data_inicio".$i),"en"),
						'Data_fim' => $this->convert_date($this->input->post("data_fim".$i),"en"),
						'Data_abertura' => $this->convert_date($this->input->post("data_abertura".$i),"en"),
						'Data_fechamento' => $this->convert_date($this->input->post("data_fechamento".$i),"en")
					);
					array_push($dataBimestreToSave, $dataBimestreLinhaToSave);
				}
			}
			$dataToSave['bimestres'] = $dataBimestreToSave;

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
				 	$resultado = $this->valida_regras($dataToSave);

				 	if($resultado == 1)
				 	{
				 		$resultado = $this->store_banco($dataToSave);//retorna a id do período letivo.
				 		if(count($dataToSave['intervalos']) > 0)
							$this->Intervalo_model->set_intervalo($dataToSave['intervalos'], $resultado);
						else
						{
							$intervalos = $this->Intervalo_model->get_intervalo($resultado);
							for($i = 0; $i < count($intervalos); $i++)
								$this->Intervalo_model->delete_intervalo($intervalos[$i]['Id']);
						}

						if(count($dataToSave['bimestres']) > 0)
							$this->Bimestre_model->set_bimestre($dataToSave['bimestres'], $resultado);
						else
						{
							$bimestres = $this->Bimestre_model->get_bimestre($resultado);
							for($i = 0; $i < count($bimestres); $i++)
								$this->Bimestre_model->delete_bimestre($bimestres[$i]['Id']);
						}

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
				redirect('regras/index');
		}
		/*!
		*	RESPONSÁVEL POR REALIZAR AS DEVIDAS VALIDAÇÕES NAS REGRAS E REPORTAR AS DEVIDAS MENSAGENS DE ERRO.
		*
		*	$data -> Contém todos os dados das regras.
		*/
		public function valida_regras($Regras)
		{
			if($Regras['Avaliar_faltas'] == 1)
				$Regras['Limite_falta'] = 0;

			if($Regras['Modalidade_id'] == 0)
				return "Selecione uma modalidade.";
			else if(empty($Regras['Periodo']))
				return "Informe o período letivo.";
			else if(empty($Regras['Limite_falta']) && $Regras['Avaliar_faltas'] == 0)
				return "Informe o limite de faltas ou marque a opção acima.";
			else if(($Regras['Limite_falta']) > 100 && $Regras['Avaliar_faltas'] == 0)
				return "O limite de faltas deve estar entre 0% e 100%.";
			else if(empty($Regras['Dias_letivos']))
				return "Informe quantos dias letivos terá este período.";
			else if(($Regras['Media']) > 100)
				return "A média de aprovação deve estar entre 0% e 100%.";
			else if(empty($Regras['Media']))
				return "Informe a média de aprovação.";
			else if(empty($Regras['Duracao_aula']))
				return "Informe quanto tempo terá cada aula.";
			else if(empty($Regras['Hora_inicio_aula']))
				return "Informe a hora de início da aula.";
			else if(empty($Regras['Quantidade_aula']))
				return "Informe a quantidade de aulas por dia.";
			else if(empty($Regras['Reprovas']))
				return "Informe quantas disciplinas o aluno poderá carregar.";
			else if(!empty($Regras['Qtd_minima_aluno']) && !empty($Regras['Qtd_maxima_aluno'])
					&& $Regras['Qtd_minima_aluno'] > $Regras['Qtd_maxima_aluno'])
				return "A quantidade máxima deve ser superior ou igual a quantidade mínima.";
			else if($this->Regras_model->valida_nome_periodo($Regras) == FALSE)
				return "Já existe um período cadastrado com este nome para a modalidade em questão.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DAS REGRAS.
		*
		*	$data -> Contém todos os dados de uma regra a ser cadastrada/editada.
		*/
		public function store_banco($data)
		{
			return $this->Regras_model->set_regras($data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE UMA REGRA PARA "APAGAR".
		*
		*	$id -> Id de uma determinada regra.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Regras_model->delete_regras($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DAS REGRA LETIVA E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de uma regra.
		*/
		public function detalhes($id)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{		
				$this->data['title'] = 'Detalhes da regra';
				$this->data['obj'] = $this->Regras_model->get_regras(FALSE, $id, FALSE, FALSE);
				$this->data['intervalos'] = $this->Intervalo_model->get_intervalo($id);
				$this->data['bimestres'] = $this->Bimestre_model->get_bimestre($id);
				$this->view("regras/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>