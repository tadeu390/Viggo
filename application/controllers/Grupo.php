<?php
	require_once("Geral.php");

	class Grupo extends Geral {
		public function __construct()
		{
			parent::__construct();

			if(empty($this->account_model->session_is_valid($this->session->id)['id']))
				redirect('Account/login');

			$this->load->model('Grupo_model');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
 
		/*
			Renderiza o dashboard
		*/
		public function index($page = false)
		{
			if($page === false)
				$page = 1;

			$this->data['title'] = 'Administração - dashboard';
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['lista_grupos'] = $this->Grupo_model->get_grupo_tela(false,$page);
				$this->data['paginacao']['size'] = $this->data['lista_grupos'][0]['Size'];
				$this->data['paginacao']['pg_atual'] = $page;
				$this->view("grupo/index",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}

		public function deletar($id = false)
		{
			if($this->Geral_model->get_permissao(DELETE,get_class($this)) == true)
				$this->Grupo_model->deletar($id);
			else
				$this->view("templates/permissao",$this->data);
		}

		public function detalhes($id = false)
		{
			$this->data['title'] = 'Grupo - Detalhes';
			if($this->Geral_model->get_permissao(READ,get_class($this)) == true)
			{
				$this->data['lista_grupos_acesso'] = $this->Grupo_model->get_grupo_acesso($id);
				$this->data['obj'] = $this->Grupo_model->get_grupo_tela($id);
				$this->view("grupo/detalhes",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}

		public function edit($id = false)
		{
			$this->data['title'] = 'Grupo - Cadastro';
			if($this->Geral_model->get_permissao(UPDATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo_tela($id);
				$this->data['lista_grupos_acesso'] = $this->Grupo_model->get_grupo_acesso($id);
			}
			else
				$this->view("templates/permissao",$this->data);
			$this->view("grupo/create_edit",$this->data);
		}

		public function create()
		{
			$this->data['title'] = 'Grupo - Cadastro';
			if($this->Geral_model->get_permissao(CREATE,get_class($this)) == true)
			{
				$this->data['obj'] = $this->Grupo_model->get_grupo_tela(0);
				$this->data['lista_grupos_acesso'] = $this->Grupo_model->get_grupo_acesso(0);
				$this->view("grupo/create_edit",$this->data);
			}
			else
				$this->view("templates/permissao",$this->data);
		}

		public function store()
		{
			$resultado = "sucesso";
			$dataToSave = array(
				'id' => $this->input->post('id'),
				'ativo' => $this->input->post('grupo_ativo'),
				'nome' => $this->input->post('nome')
			);
			$grupo_id = 0;
			//bloquear acesso direto ao metodo store
			 if(!empty($dataToSave['nome']))
					$grupo_id = $this->Grupo_model->set_grupo($dataToSave);
			 else
				redirect('admin/dashboard');

			// $modulos = array();
			// $acessos = array();
			// for($i = 0; $this->input->post('modulo_id'.$i) != null; $i++)
			// {
				// array_push($modulos,$this->input->post("modulo_id".$i.""));
				// array_push($acessos,$this->input->post("acesso_id".$i.""));
			// }
			for($i = 0; $this->input->post('modulo_id'.$i) != null; $i++)
			{
				$dataAcessoToSave = array(
					'id' => $this->input->post("acesso_id".$i.""),
					'grupo_id' => $grupo_id,
					'modulo_id' => $this->input->post("modulo_id".$i.""),
					'criar' => (($this->input->post("linha".$i."col0") == null) ? 0 : 1),
					'visualizar' => (($this->input->post("linha".$i."col1") == null) ? 0 : 1),
					'atualizar' => (($this->input->post("linha".$i."col2") == null) ? 0 : 1),
					'apagar' => (($this->input->post("linha".$i."col3") == null) ? 0 : 1)
				);
				$this->Grupo_model->set_grupo_acesso($dataAcessoToSave);
			}
			$arr = array('response' => $resultado);
			header('Content-Type: application/json');
			echo json_encode($arr);
		}
	}
?>
