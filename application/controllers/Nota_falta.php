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
		public function turma($id, $disc_turma)
		{
			$this->data['title'] = 'Turma';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['disc_turma_header'] = $this->Disc_turma_model->get_disc_turma_header($id);
				
				
				$disciplinas = $this->Disc_turma_model->get_grade_disciplina(
					$this->Disc_turma_model->get_grade_id_turma($id)['Grade_id'],  
					$this->Disc_turma_model->get_periodo_turma($id)['Periodo'], $id);

				if($disc_turma == 0)
					$disc_turma = $disciplinas[0]['Disc_turma_id']; //pega a primeira disciplina por padrao.

				$this->data['disc_turma'] = $disc_turma;
				
				
				$this->data['disciplinas'] = $disciplinas;
				$this->view("nota_falta/turma", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>