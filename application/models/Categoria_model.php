<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DAS CATEGORIAS DE DISCIPLINAS.
	*/
	class Categoria_model extends CI_Model 
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
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE CATEGORIAS OU UMA CATEGORIA ESPECÍFICA.
		*
		*	$Id -> Quando passado algum valor inteiro, retorna uma categoria caso a mesma exista no banco de dados.
		*/
		public function get_categoria($id = FALSE)
		{
			if ($id === FALSE)//retorna todos se nao passar o parametro
			{
				$query = $this->db->query("
					SELECT c.Id, c.Nome as Nome_categoria, 
					DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, c.Ativo   
					FROM Categoria c");

				return $query->result_array();
			}

			$query = $this->db->query("
					SELECT c.Id, c.Nome as Nome_categoria, 
					DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, c.Ativo   
					FROM Categoria c 
					WHERE c.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
	}
?>