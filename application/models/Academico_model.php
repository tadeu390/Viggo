<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS OPÇÕES DA TELA INICIAL QUANDO UM USUÁRIO ENTRA.
	*/
	class Academico_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
	}
?>