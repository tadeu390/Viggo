<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE NOTA_FALTA.
	*/
	class Nota_falta_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
	}
?>