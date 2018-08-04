<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE CONFIGURAÇÕES DO SISTEMA.
	*/
	class Configuracoes_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR INFORMAÇÕES DE CONFIGURAÇÃO DO SISTEMA.
		*
		*	$id -> Id de uma congifuraçção.
		*/
		public function get_configuracoes($id = FALSE)
		{
			$query = $this->db->query("
				SELECT Id, Itens_por_pagina FROM 
				Settings");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR E ATUALIZAR DADOS DE CONFIGURAÇÕES DO SISTEMA.
		*
		*	$data -> Contém as configurações.
		*/
		public function set_configuracoes($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Settings',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Settings', $data);
			}
		}
	}
?>