<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS CONTEÚDOS LECIONADOS PELOS PROFESSORES.
	*/
	class Conteudo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}

		public function set_conteudo($data, $disc_hor)
		{

		}
	}
?>