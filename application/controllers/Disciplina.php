<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AS DISCIPLINAS.
	*/
	class Disciplina extends Geral 
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

			$this->load->model('Disciplina_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODAS AS DICIPLINAS CADASTRADAS E ENVIA-LAS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === FALSE)
				$page = 1;
			
			$this->data['title'] = 'Disciplinas';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_disciplinas'] = $this->Disciplina_model->get_disciplina(FALSE, FALSE, $page, FALSE);
				
				$this->data['paginacao']['size'] = (!empty($this->data['lista_disciplinas'][0]['Size'])?$this->data['lista_disciplinas'][0]['Size'] : 0 );
				$this->data['paginacao']['pg_atual'] = $page;
				
				$this->view("disciplina/index", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE DISCIPLINA PARA "APAGAR".
		*
		*	$id -> Id da disciplina.
		*/
		public function deletar($id = NULL)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Disciplina_model->delete_disciplina($id);
				$resultado = "sucesso";
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DE DISCIPLINAS E RECEBER DA MODEL OS DADOS 
		*	DA DISCIPLINA QUE SE DESEJA EDITAR.
		*
		*	$id -> Id da disciplina.
		*/
		public function edit($id = NULL)
		{
			$this->data['title'] = 'Editar Disciplina';
			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Disciplina_model->get_disciplina(FALSE, $id, FALSE, FALSE);
				$this->view("disciplina/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO DISCIPLINAS.
		*/
		public function create()
		{
			$this->data['title'] = 'Criar Disciplina';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Disciplina_model->get_disciplina(FALSE, 0, FALSE, FALSE);
				$this->view("disciplina/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DA DISCIPLINA.
		*
		*	$Disciplina -> Contém todos os dados da disciplina a ser validada.
		*/
		public function valida_disciplina($Disciplina)
		{
			if(empty($Disciplina['Nome']))
				return "Informe o nome da disciplina";
			else if(mb_strlen($Disciplina['Nome']) > 200)
				return "Máximo 200 caracteres";
			else if(empty($Disciplina['Apelido']))
				return "Informe o apelido da disciplina";
			else if(mb_strlen($Disciplina['Apelido']) > 10)
				return "Máximo 10 caracteres";
			else if($this->Disciplina_model->nome_valido($Disciplina['Nome'], $Disciplina['Id']) == 'invalido')
				return "O nome informado para a disciplina já se encontra cadastrado no sistema.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DA DISCIPLINA.
		*
		*	$dataToSave -> Contém todos os dados da disciplina a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Disciplina_model->set_disciplina($dataToSave);
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('disciplina_ativa'),
				'Nome' => $this->input->post('nome'),
				'Apelido' => $this->input->post('apelido')
			);
			
			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			if(!empty($this->input->post()))
			{
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			 	{
					$resultado = $this->valida_disciplina($dataToSave);

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
				redirect('disciplina/index');	
		}
	}
?>