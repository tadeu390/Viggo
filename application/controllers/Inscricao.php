<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS MATRICULAS DOS ALUNOS.
	*/
	class Inscricao extends Geral 
	{
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->load->model('Inscricao_model');
			$this->load->model('Curso_model');
			$this->load->model('Modalidade_model');
			$this->load->model('Usuario_model');
			$this->load->model('Aluno_model');
			$this->load->model('Aluno_model');
			$this->load->model('Renovacao_matricula_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS MATRICULAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*	$field -> Campo de ordenação.
		*	$order -> Tipo de ordenação (ASC ou DESC).
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
			
			$this->data['title'] = 'Matrículas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_matriculas'] = $this->Inscricao_model->get_inscricao(FALSE, FALSE, $page, FALSE, $ordenacao);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_matriculas']) ? $this->data['lista_matriculas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;

				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->view("inscricao/index", $this->data);
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
				if($this->Inscricao_model->get_inscricao(FALSE, $id, FALSE)['Editar_apagar'] != 'bloqueado')
					$this->Inscricao_model->deletar($id);
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
			$this->data['title'] = 'Editar inscrição';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Inscricao_model->get_inscricao(FALSE, $id, FALSE);
				if($this->data['obj']['Editar_apagar'] == 'bloqueado')
					redirect("inscricao/index");
				$this->data['lista_alunos'] = $this->Aluno_model->get_aluno(FALSE);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);

				$this->view("inscricao/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE MATRICULA.
		*/
		public function create()
		{
			$this->data['title'] = 'Nova inscrição';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Inscricao_model->get_inscricao(FALSE, 0, FALSE);
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, FALSE);
				$this->data['lista_modalidades'] = $this->Modalidade_model->get_modalidade(FALSE, FALSE, FALSE);

				$this->data['lista_alunos'] = $this->Aluno_model->get_aluno(FALSE);
				$this->view("inscricao/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DE MATRICULA.
		*
		*	$Matricula -> Contém todos os dados da matricula a ser validada.
		*/
		public function valida_inscricao($Matricula)
		{
			if($Matricula['Aluno_id'] == '0')
				return "Selecione um aluno.";
			else if($Matricula['Curso_id'] == 0)
				return "Selecione um curso.";
			else if($Matricula['Modalidade_id'] == 0)
				return "Selecione uma modalidade.";
			else if(empty($this->Modalidade_model->get_periodo_por_modalidade($Matricula['Modalidade_id'])))
				return "Não existe nenhum período letivo cadastrado para a modalidade selecionada.";
			else if(!empty($this->Inscricao_model->get_inscricao_por_aluno($Matricula)) && $this->Inscricao_model->get_inscricao_por_aluno($Matricula)['Id'] != $Matricula['Id'])
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
			$this->Inscricao_model->set_inscricao($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Aluno_id' => $this->input->post('aluno_id'),
				'Curso_id' => $this->input->post('curso_id'),
				'Modalidade_id' => $this->input->post('modalidade_id')
			);

			//BLOQUEIA ACESSO DIRETO AO MÉTODO
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
				 	$resultado = $this->valida_inscricao($dataToSave);

				 	if($resultado == 1)
				 	{
				 		$resultado = $this->store_banco($dataToSave);

				 		if($this->input->post('matricular') == 1)//se marcar esta opção, cria a inscrição e já gera a matrícula para o período corrente
				 		{
					 		$dataRenovacaoToSave = array(
								'Inscricao_id' => $this->Inscricao_model->get_inscricao_por_aluno($dataToSave)['Id'],
								'Periodo_letivo_id' => $this->Modalidade_model->get_periodo_por_modalidade($dataToSave['Modalidade_id'])['Id']
							);
					 		$this->Renovacao_matricula_model->set_renovacao_matricula($dataRenovacaoToSave);
				 		}
				 		else
				 		{
				 			if(!empty($dataToSave['Id']))
				 				$this->Renovacao_matricula_model->delete_matricula($dataToSave['Id']);
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
				redirect('inscricao/index');
		}
		/*!
		*	RESPONSÁVEL POR REALIZAR A MATRICULA OU A RENOVAÇÃO DA MESMA PARA CADA INSCRIÇÃO DE CADA ALUNO.
		*
		*	$inscricao_id -> Id da inscrição, identificar um aluno que está renovando a matrícula para o próximo 
		*	período letivo.
		*/
		public function matricula($inscricao_id)
		{
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$Inscricao = $this->Inscricao_model->get_inscricao(false, $inscricao_id, false, false);

				$dataRenovacaoToSave = array(
					'Inscricao_id' => $inscricao_id, 
					'Periodo_letivo_id' => $this->Modalidade_model->get_periodo_por_modalidade($Inscricao['Modalidade_id'])['Id']
				);
		 		$resultado = $this->Renovacao_matricula_model->set_renovacao_matricula($dataRenovacaoToSave);
		 		
				$arr = array('response' => $resultado);
					header('Content-Type: application/json');
					echo json_encode($arr);
			}
		}
	}
?>