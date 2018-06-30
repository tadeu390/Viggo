<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DAS MODALIDADES DE ENSINO.
	*/
	class Modalidade_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS MÉTODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADES OU UMA MODALIDADE ESPECÍFICA.
		*
		*	$Id -> Quando passado algum valor inteiro, retorna uma modalidade caso a mesma exista no banco de dados.
		*/
		public function get_modalidade($id = FALSE)
		{
			if ($id === FALSE)//retorna todos se nao passar o parametro
			{
				$query = $this->db->query("
					SELECT m.Id, m.Nome as Nome_modalidade, 
					DATE_FORMAT(m.Data_registro, '%d/%m/%Y') as Data_registro, m.Ativo   
					FROM Modalidade m");

				return $query->result_array();
			}

			$query = $this->db->query("
					SELECT m.Id, m.Nome as Nome_modalidade, 
					DATE_FORMAT(m.Data_registro, '%d/%m/%Y') as Data_registro, m.Ativo   
					FROM Modalidade m 
					WHERE m.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
	}
?>