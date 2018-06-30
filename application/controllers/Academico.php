<?php
	require_once("Geral.php");//HERDA AS ESPECIFICAÇÕES DA CLASSE GENÉRICA.
	/*!
	*	ESTA CLASSE TEM POR FUNÇÃO CONTROLAR A TELA INICIAL.
	*/
	class Academico extends Geral 
	{
		/*!
		*	CONSTRUTOR RESPONSÁVEL POR VALIDAR A SESSÃO E VERIFICAR O MENU SELECIONADO.
		*/
		public function __construct()
		{
			parent::__construct();
			if($this->Account_model->session_is_valid()['status'] != "ok")
				redirect('account/login');
			$this->set_menu();
			$this->data['controller'] = get_class($this);
			$this->data['menu_selectd'] = $this->Geral_model->get_identificador_menu(strtolower(get_class($this)));
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR A TELA INICIAL DO DASHOBOARD.
		*/
		public function dashboard()
		{
			$this->data['title'] = 'Acadêmico';
			$this->view("academico/dashboard", $this->data);
		}
	}
?>