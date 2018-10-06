<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS TURMAS.
	*/
	class Nota_falta extends Geral 
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
			
			$this->load->model("Nota_model");
			$this->load->model("Turma_model");
			$this->load->model("Disc_turma_model");
			$this->load->model("Etapa_model");
			$this->load->model("Descricao_nota_model");
			$this->load->model("Aluno_model");
			$this->load->model("Nota_model");
			
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
			
			$this->set_page_cookie($page);
			
			$ordenacao = array(
				"order" => $this->order_default($order),
				"field" => $this->field_default($field)
			);

			$this->data['title'] = 'Nota e faltas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_turmas'] = $this->Turma_model->get_turma(FALSE, FALSE, $page, FALSE);
				
				$this->data['paginacao']['order'] =$this->inverte_ordem($ordenacao['order']);
				$this->data['paginacao']['field'] = $ordenacao['field'];

				$this->data['paginacao']['size'] = (!empty($this->data['lista_turmas']) ? $this->data['lista_turmas'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("nota_falta/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL AS NOTAS E FALTAS DE UMA DETERMINADA TURMA.
		*
		*	$id -> Id da turma selecionada.
		*	$disc_turma -> Id da disciplina da turma.
		*/
		public function notas($disciplina_id, $turma_id, $etapa_id)
		{
			$this->data['title'] = 'Turma';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($turma_id);
				
				$this->data['method'] = __FUNCTION__;
				
				$disciplinas = $this->Disc_turma_model->get_grade_disciplina(
					$this->Disc_turma_model->get_grade_id_turma($turma_id)['Grade_id'],  
					$this->Disc_turma_model->get_periodo_turma($turma_id)['Periodo'], $turma_id);

				if($disciplina_id == 0)
					$disciplina_id = $disciplinas[0]['Disciplina_id']; //pega a primeira disciplina por padrao.

				
				$periodo_letivo_id = $this->Disc_turma_model->get_disc_turma_header($turma_id)['Periodo_letivo_id'];

				$this->data['lista_etapas'] = $this->Etapa_model->get_etapa($periodo_letivo_id, FALSE, FALSE);

				if($etapa_id == 0)
					$etapa_id = $this->data['lista_etapas'][0]['Id'];

				$this->data['url_part']['etapa_id'] = $etapa_id;
				$this->data['url_part']['disciplina_id'] = $disciplina_id;
				$this->data['url_part']['turma_id'] = $turma_id;
				
				$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);

				$this->data['lista_descricao_nota'] = $this->Descricao_nota_model->get_descricao(TRUE, FALSE);
				
				$this->data['lista_alunos'] = $this->Aluno_model->get_aluno_turma($disciplina_id, $turma_id, FALSE);

				$this->data['lista_colunas_nota'] = $this->Nota_model->get_colunas_nota($disciplina_id, $turma_id, $etapa_id);

				$this->data['periodo_letivo_id'] = $periodo_letivo_id;

				$this->data['disciplinas'] = $disciplinas;
				$this->view("nota_falta/notas", $this->data);
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
				if($nota == 'null')
					$nota = null;
				
				$periodo_letivo_id = $this->Disc_turma_model->get_disc_turma_header($turma_id)['Periodo_letivo_id'];

				$resultado = $this->Nota_model->validar_nota($matricula_id, $etapa_id, $nota, $descricao_nota_id);

				$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);

				if($resultado != "invalido")
				{
					$somatorio = $resultado;
					$resultado = $this->Nota_model->set_notas($nota, $descricao_nota_id, $matricula_id, $etapa_id);

					$status = $this->Nota_model->status_nota($etapa_id, $periodo_letivo_id, $somatorio);
					
					if($status == "ok")
						$status = "info";
					else
						$status = "danger";

					if($descricao_nota_id == RECUPERACAO_PARALELA)
					{
						if($this->Nota_model->status_nota($etapa_id, $periodo_letivo_id, $nota) == "ok")
							$status_rec = "info";
						else
							$status_rec = "danger";
					}
				}
				else 
					$resultado = "O valor informado ultrapassa o limite de ".$this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Valor']." pontos estabelecido para o ".$this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE)['Nome'].".";
			}
			else
				$resultado = "Você não tem permissão para realizar esta ação. Entre em contato com o administrador do sistema.";
			
			$arr = array('response' => $resultado, 'somatorio' => $somatorio, 'status' => $status, 'status_rec' => $status_rec);
			header('Content-Type: application/json');
			echo json_encode($arr);
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
				$this->data['etapa'] = $this->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);
				$this->Nota_model->remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $etapa_id);
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
	}
?>