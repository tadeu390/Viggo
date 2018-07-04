<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS CURSOS.
	*/
	class Curso extends Geral 
	{
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

			$this->load->model('Curso_model');
			$this->load->model('Disciplina_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS CURSOS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->data['title'] = 'Cursos';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_cursos'] = $this->Curso_model->get_curso(FALSE, FALSE, $page, FALSE);
				$this->data['paginacao']['size'] = (!empty($this->data['lista_cursos'][0]['Size']) ? $this->data['lista_cursos'][0]['Size'] : 0);
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("curso/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE CURSO PARA "APAGAR".
		*
		*	$id -> Id do curso.
		*/
		public function deletar($id = null)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Curso_model->delete_curso($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE CURSO E RECEBER DA MODEL OS DADOS 
		*	DO CURSO QUE SE DESEJA EDITAR.
		*
		*	$id -> Id do curso.
		*/
		public function edit($id = null)
		{
			$this->data['title'] = 'Editar curso';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['Disciplinas'] = $this->Disciplina_model->get_disciplina(FALSE, FALSE, FALSE, FALSE);
				$this->data['obj'] = $this->Curso_model->get_curso(FALSE, $id, FALSE, FALSE);
				$this->data['Disciplinas_curso'] = $this->Disciplina_model->get_disciplina_por_curso($this->data['obj']['Id']);
				$this->view("curso/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO CURSO.
		*/
		public function create()
		{
			$this->data['title'] = 'Novo Curso';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['Disciplinas'] = $this->Disciplina_model->get_disciplina(FALSE, FALSE, FALSE, FALSE);
				$this->data['obj'] = $this->Curso_model->get_curso(FALSE, 0, FALSE, FALSE);
				$this->data['Disciplinas_curso'] = $this->Disciplina_model->get_disciplina_por_curso($this->data['obj']['Id']);
			}
			$this->view("curso/create_edit", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO CURSO.
		*
		*	$Curso -> Contém todos os dados do curso a ser validado.
		*/
		public function valida_curso($Curso)
		{
			if(empty($Curso['Nome']))
				return "Informe o nome do curso";
			else if(mb_strlen($Curso['Nome']) > 100)
				return "Máximo 100 caracteres";
			else if($Curso['Disciplinas_id'] == NULL)
				return "Selecione ao menos uma disciplina";
			else if($this->Curso_model->nome_valido($Curso['Nome'], $Curso['Id']) == 'invalido')
				return "O nome informado para o Curso já se encontra cadastrado no sistema.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO CURSO.
		*
		*	$dataToSave -> Contém todos os dados do curso a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Curso_model->set_curso($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('curso_ativo'),
				'Nome' => $this->input->post('nome'),
				'Disciplinas_id' => $this->input->post('disciplinas')
			);

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			if(!empty($this->input->post()))
			{
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
				{
					$resultado = $this->valida_curso($dataToSave);

				 	if($resultado == 1)
				 	{ 
				 		$this->store_banco($dataToSave);
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
				redirect('curso/index');
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM CURSO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de um curso.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{		
				$this->data['title'] = 'Detalhes do curso';
				$this->data['obj'] = $this->Curso_model->get_curso(FALSE, $id, FALSE, FALSE);
	
				$this->view("curso/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
	}
?>