<?php
	require_once("Geral.php");
	/*
		ESTA CLASSE TEM POR FUNÇÃO CONTROLAR TUDO REFERENTE AOS GRUPOS DO SISTEMA
	*/
	class Grupo extends Geral 
	{
		public function __construct()
		{
			parent::__construct();

			if(empty($this->account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');

			$this->load->model('Grupo_model');
			$this->load->model('Usuario_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*
			RESPONSÁVEL POR LISTAR TODOS OS GRUPOS NA TELA
			$page -> número da página atual registros
		*/
		public function index($page = false)
		{
			if($page === false)
				$page = 1;

			$this->data['title'] = 'Administração - dashboard';
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['lista_grupos'] = $this->Grupo_model->get_grupo(FALSE, FALSE, $page);
				$this->data['paginacao']['size'] = $this->data['lista_grupos'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("grupo/index",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}
		/*
			RESPONSÁVEL POR OCULTAR UM GRUPO DO SISTEMA
			$id -> id de um grupo
		*/
		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE,get_class($this)) == true)
				$this->Grupo_model->deletar($id);
			else
				$this->view("templates/permissao",$this->data);
		}

		public function detalhes($id = FALSE)
		{
			$this->data['title'] = 'Grupo - Detalhes';
			if($this->Geral_model->get_permissao(READ, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE);
				$this->view("grupo/detalhes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DE GRUPO PARA EDIÇÃO
			$id -> id de um grupo
		*/
		public function edit($id = FALSE)
		{
			$this->data['title'] = 'Grupo - Cadastro';
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE);
			}
			else
				$this->view("templates/permissao", $this->data);
			$this->view("grupo/create_edit", $this->data);
		}
		/*
			RESPONSÁVEL POR RENDERIZAR O FORMULÁRIO DE CADASTRO DE grupo PARA CRIAR
		*/
		public function create()
		{
			$this->data['title'] = 'Grupo - Cadastro';
			if($this->Geral_model->get_permissao(CREATE, get_class($this)) == TRUE)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo(FALSE, 0, FALSE);
				$this->view("grupo/create_edit", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR RECEBER OS DADOS DO FORMULÁRIO E OS ENVIA-LO PARA A MODEL
		*/
		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'Id' => $this->input->post('id'),
				'Ativo' => $this->input->post('grupo_ativo'),
				'Nome' => $this->input->post('nome')
			);

			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['Nome']))
					$this->Grupo_model->set_grupo($dataToSave);
			 else
				redirect('grupo/index');

			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
		/*
			RESPONSÁVEL CARREGAR AS INFORMAÇÕES PARA VIEW QUE MOSTRARÁ AS PERMISSÕES POR MODULOS DE UM DETERMINADO GRUPO

			$id -> id do grupo
		*/
		public function permissoes($id = FALSE)
		{
			if($this->Geral_model->get_permissao(UPDATE, get_class($this)) == TRUE)
			{
				$this->data['title'] = 'Grupos - Permissões';
				$this->data['grupo_id'] = $id;
				$this->data['lista_grupo_acesso'] = $this->Grupo_model->get_grupo_acesso($id);
				$this->data['grupo'] = $this->Grupo_model->get_grupo(FALSE, $id, FALSE)['Nome_grupo'];
				$this->view("grupo/permissoes", $this->data);
			}
			else
				$this->view("templates/permissao", $this->data);
		}
		/*
			RESPONSÁVEL POR CADASTRAR OU ATUALIZAR PERMISSÕES POR GRUPO. ESTE MÉTODO TAMBÉM IDENTIFICA SE CADA CHECKBOX ESTÁ MARCADO, DESMARCADO OU MARCADO COMO PARCIAL(FICA EM AMARELO E QUANDO É SALVO AS ALTERAÇÕES, TUDO O QUE ESTÁ MARCADO EM AMARELO NÃO ALTERA NADA NO BANCO)
		*/
		public function store_permissoes()
		{
			if(!empty($this->input->post("grupo_id")))
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
						//print_r($dataAcessoToSave);
						$this->Acesso_model->set_acesso($dataAcessoToSave);
					}
				}	
				$arr = array('response' => $resultado);
				header('Content-Type: application/json');
				echo json_encode($arr);
			}
			else
				redirect('grupo/index');
		}
	}
?>
