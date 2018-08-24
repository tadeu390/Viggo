<?php
/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS PRESÊNÇA DOS ALUNOS.
	*/
	class Calendario_presenca_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		
		public function set_presenca($data)
		{
			for($i = 0; $i < COUNT($data); $i++)
			{
				if(empty($data[$i]['Id']))
				{
					
				}
			}
		}
	}
?>