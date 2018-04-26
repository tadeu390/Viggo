<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS MÓDULOS DO SISTEMA
	*/
	class Modulo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR UMA LISTA DE MÓDULOS OU UM MÓDULO ESPECÍFICO, AMBOS PODENDO INCLUIR
			APENAS REGISTROS ATIVOS OU TODOS.
			
			$Ativo -> Especifica o retorno de módulos ativos ou todos. true p/ ativos false p/ todos
			$id -> id de um menu específico
			$page-> número da paginação para retornar a página de registros correta
		*/
		public function get_modulo($Ativo, $id = false, $page = false)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND mo.Ativo = 1 ";

			if($id === false)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";

				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Modulo) AS Size, me.Nome AS Nome_menu, 
					me.Id as Menu_id, mo.Descricao, mo.Ativo, mo.Ordem, 
					DATE_FORMAT(mo.Data_registro, '%d/%m/%Y') as Data_registro, 
					mo.nome as Nome_modulo, mo.Id, mo.Url as Url_modulo, mo.Icone 
						FROM Menu me 
					RIGHT JOIN Modulo mo ON me.Id = mo.Menu_id 
					WHERE TRUE ".$Ativos."
					ORDER BY mo.Data_registro DESC ".$pagination."");

				return $query->result_array();
			}
			$query = $this->db->query("
				SELECT me.Nome AS Nome_menu, mo.Descricao, mo.Ativo, mo.Ordem, 
				DATE_FORMAT(mo.Data_registro, '%d/%m/%Y') as Data_registro,
				mo.Nome as Nome_modulo, mo.Id, mo.Url as Url_modulo, mo.Menu_id, mo.Icone 
					FROM Menu me 
				RIGHT JOIN Modulo mo ON me.Id = mo.Menu_id 
				WHERE TRUE ".$Ativos." AND mo.Id = ".$this->db->escape($id)." 
				ORDER BY mo.Ordem, me.Ordem");

			return $query->row_array();
		}
		/*
			RESPONSÁVEL POR OCULTAR UM MÓDULO ESPECÍFICO

			$id -> Id de um Módulo específico
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Modulo SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*
			RESPONSÁVEL POR CADASTRAR UM NOVO MÓDULO OU ATUALIZA-LO CASO JÁ EXISTA NO BANCO DE DADOS

			$data -> Contém os dados do menu
		*/
		public function set_modulo($data)
		{
			if($data['Menu_id'] == "0")
				$data['Menu_id'] = null;
			if(empty($data['Id']))
				return $this->db->insert('Modulo',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Modulo', $data);
			}
		}
	}
?>