<?php
	require_once("Geral.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS GRUPOS DO SISTEMA.
	*/
	class Grupo extends Geral 
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

			$this->load->model('Grupo_model');
			$this->load->model('Usuario_model');
			$this->set_menu();
			$this->data['controller'] = strtolower(get_class($this));
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS GRUPOS CADASTRADOS E ENVIA-LOS A VIEW.
		*
		*	$page -> Número da página atual de registros.
		*/
		public function index($page = FALSE)
		{
			if($page === false)
				$page = 1;
			
			$this->set_page_cookie($page);
			
			$this->data['title'] = 'Grupos';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['lista_grupos'] = $this->Grupo_model->get_grupo(FALSE, FALSE, $page);
				$this->data['paginacao']['size'] = $this->data['lista_grupos'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("grupo/index", $this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UM ID DE GRUPO PARA "APAGAR".
		*
		*	$id -> Id do grupo.
		*/
		public function deletar($id = FALSE)
		{
			if($this->Geral_model->get_permissao(DELETE, get_class($this)) == TRUE)
			{
				$this->Grupo_model->deletar($id);
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
		*	DO GRUPO QUE SE DESEJA EDITAR.
		*
		*	$id -> Id do grupo.
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Editar Grupo';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE);
				$this->data['lista_acesso_padrao'] = $this->Acesso_padrao_model->get_acesso_padrao($id);
			}
			else
				$this->view("templates/permissao", $this->data);
			$this->view("grupo/create_edit", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O FORMULÁRIO DE CADASTRO DO GRUPO.
		*/
		public function create()
		{
			$this->data['title'] = 'Novo Grupo';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, 0, FALSE);
				$this->data['lista_acesso_padrao'] = $this->Acesso_padrao_model->get_acesso_padrao();
				$this->view("grupo/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL TODOS OS ATRIBUTOS DE UM GRUPO E OS ENVIA-LOS A VIEW.
		*
		*	$id -> Id de um grupo.
		*/
		public function detalhes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Detalhes do grupo';
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE);
				$this->data['lista_grupos_acesso'] = $this->Acesso_padrao_model->get_acesso_padrao($id);
				$this->view("grupo/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR OS DADOS NECESSÁRIOS DO GRUPO.
		*
		*	$Grupo -> Contém todos os dados do grupo a ser validado.
		*/
		public function valida_grupo($Grupo)
		{
			if(empty($Grupo['Nome']))
				return "Informe o nome de grupo";
			else if(mb_strlen($Grupo['Nome']) > 20)
				return "Máximo 20 caracteres";
			//se estiver editando um grupo, ao salvar é preciso retirar da verificação o seu nome, pois se não o sistema mostrará uma mensagem de que o nome de grupo já existe
			else if(($this->Grupo_model->nome_valido($Grupo['Nome'], $Grupo['Id']) == 'invalido'))
				return "O nome informado para o Grupo já se encontra cadastrado no sistema.";
			else
				return 1;
		}
		/*!
		*	RESPONSÁVEL POR ENVIAR AO MODEL OS DADOS DO GRUPO.
		*
		*	$dataToSave -> Contém todos os dados do grupo a ser cadastrado/editado.
		*/
		public function store_banco($dataToSave)
		{
			$this->Grupo_model->set_grupo($dataToSave);
			$Grupo_id = $this->Grupo_model->get_grupo_por_nome($dataToSave['Nome'])['Id'];

			//grava no banco as permissões padrões
			for($i = 0; $this->input->post('modulo_id'.$i) != null; $i++)
			{
				$dataAcessoToSave = array(
				'Id' => $this->input->post("acesso_padrao_id".$i.""),
				'Grupo_id' => $Grupo_id,
				'Modulo_id' => $this->input->post("modulo_id".$i.""),
				'Criar' => (($this->input->post("linha".$i."col0") == null) ? 0 : 1),
				'Ler' => (($this->input->post("linha".$i."col1") == null) ? 0 : 1),
				'Atualizar' => (($this->input->post("linha".$i."col2") == null) ? 0 : 1),
				'Remover' => (($this->input->post("linha".$i."col3") == null) ? 0 : 1)
				);
				$this->Acesso_padrao_model->set_acesso_padrao($dataAcessoToSave);
			}
		}
		/*!
		*	RESPONSÁVEL POR CAPTAR OS DADOS DO FORMULÁRIO SUBMETIDO.
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('grupo_ativo'),
				'Nome' => $this->input->post('nome')
			);

			if(empty($dataToSave['Ativo']))
				$dataToSave['Ativo'] = 0;
			
			//bloquear acesso direto ao metodo store
			 if(!empty($this->input->post()))
			 {
			 	if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			 	{
				 	$resultado = $this->valida_grupo($dataToSave);

					if($resultado == 1)
					{ 
						$this->store_banco($dataToSave);
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
				redirect('grupo/index');
		}
		/*!
		*	RESPONSÁVEL POR RECEBER DA MODEL AS PERMISSÕES POR MÓDULO DE UM DETERMINADO GRUPO E ENVIA-LAS 
		*   A VIEW.
		*
		*	$id -> Id do grupo.
		*/
		public function permissoes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Permissões do grupo';
				$this->data['grupo_id'] = $id;
				$this->data['lista_grupo_acesso'] = $this->Grupo_model->get_grupo_acesso($id);
				$this->data['grupo'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE)['Nome_grupo'];
				$this->view("grupo/permissoes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*!
		*	RESPONSÁVEL CAPTAR AS PERMISSÕES POR GRUPO. 
		*	ESTE MÉTODO TAMBÉM IDENTIFICA SE CADA CHECKBOX ESTÁ MARCADO, 
		*	DESMARCADO OU MARCADO COMO PARCIAL(PARCIAL CHECKBOX AMARELO).
		*/
		public function store_permissoes()
		{
			//NÃO PERMITE ACESSO DIRET AO METODO STORE
			if(!empty($this->input->post()))
			{
				if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE || $this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			 	{
					$resultado = "sucesso";
					$usuarios = $this->Usuario_model->get_usuario_por_grupo($this->input->post('grupo_id'));

					for ($i=0; $i < COUNT($usuarios); $i++) 
					{
						$acesso = $this->Acesso_model->get_acesso($usuarios[$i]['Id']);
						for($j = 0; $this->input->post('modulo_id'.$j) != null; $j++)
						{
							$dataAcessoToSave = array(
								'Id' => $acesso[$j]['Acesso_id'],
								'Usuario_id' => $usuarios[$i]['Id'],
								'Modulo_id' => $this->input->post("modulo_id".$j.""),
								'Criar' => (($this->input->post("linha".$j."col0") == null) ? 0 : 1),
								'Ler' => (($this->input->post("linha".$j."col1") == null) ? 0 : 1),
								'Atualizar' => (($this->input->post("linha".$j."col2") == null) ? 0 : 1),
								'Remover' => (($this->input->post("linha".$j."col3") == null) ? 0 : 1)
							);
							
							//QUANDO ESTA AMARELO NA TELA SIGNIFICA QUE NEM TODOS OS USUARIOS POSSUEM PERMISSOES, ENTAO SE NAO TRATAR ISSO, AO EXECUTAR ESTE METODO
							//TODOS PASSAM A TER PERMISSAO, UMA VEZ QUE ESTE ALTERA AS PERMISSÕES PARA TODOS OS USUÁRIOS DO GRUPO EM QUESTÃO.
							//OU SEJA SE O USUARIO SALVAR COM ALGUM CHECKBOX AMARELO, NAO MEXER NA PERMISSAO REFERENTE A CADA UM DELES, SIMPLESMENTE PERMANECE COMO ESTÁ

							/*OBS.: SUCCESS: PERMISSAO TOTAL CONCEDIDA
								  WARNING: PERMISSAO PARCIAL APENAS (USUARIO SOMENTE VISUALIZOU E ESTÁ SALVANDO)*/
							if($this->input->post("flagcr".$j) == 'Warning')
								unset($dataAcessoToSave['Criar']);//REMOVE ESSA PERMISSAO NO ARRAY SE A MESMA NÃO ESTIVER COMO TOTAL NA TELA (A MESMA COISA NOS DEMAIS ABAIXO)
							
							if($this->input->post("flagle".$j) == 'Warning')
								unset($dataAcessoToSave['Ler']);
							
							if($this->input->post("flagat".$j) == 'Warning')
								unset($dataAcessoToSave['Atualizar']);
							
							if($this->input->post("flagre".$j) == 'Warning')
								unset($dataAcessoToSave['Remover']);

							$this->Acesso_model->set_acesso($dataAcessoToSave);
						}
					}
				}
				else
					$resultado = "Você não tem permissão para realizar esta ação.";
				
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('grupo/index');
		}
	}
?>