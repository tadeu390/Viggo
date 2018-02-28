<?php
	class Settings_model extends CI_Model {
		
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS METODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}

		public function get_geral($id = FALSE)
		{
			$query = $this->db->query("SELECT id, itens_por_pagina FROM 
				configuracoes_geral");

			return $query->row_array();
		}

		public function set_geral($data)
		{
			if(empty($data['id']))
				return $this->db->insert('configuracoes_geral',$data);
			else
			{
				$this->db->where('id', $data['id']);
				return $this->db->update('configuracoes_geral', $data);
			}
		}
	}
?>