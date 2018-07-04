<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS MÓDULOS DO SISTEMA.
	*/
	class Modulo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MÓDULOS OU UM MÓDULO ESPECÍFICO.
		*	
		*	$Ativo -> Quando passadO "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de um módulo específico.
		*	$page-> Número da página de registros que se quer carregar.
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
					ORDER BY mo.Data_registro ASC ".$pagination."");

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
		/*!
		*	RESPONSÁVEL POR "APAGAR" UM MÓDULO DO BANCO DE DADOS.
		*
		*	$id -> Id do módulo a ser "apagado".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Modulo SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UM MÓDULO BANCO DE DADOS.
		*
		*	$data -> Contém os dados do módulo.
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
		/*!
		*	RESPONSÁVEL POR RETORNAR UM MÓDULO CONFORME O NOME.
		*
		*	$nome -> Contém o nome do módulo a ser buscado no banco de dados.
		*/
		public function get_modulo_por_nome($nome)
		{
			$nome = mb_strtolower($nome);
			$query = $this->db->query("
				SELECT * FROM Modulo m WHERE LOWER(m.Nome) = ".$this->db->escape($nome)."");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM DETERMIANDO MODULO JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome do modulo a ser validado.
		*	$Id -> Id do modulo.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Modulo 
				WHERE Nome = ".$this->db->escape($Nome)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_modulo(FALSE ,$Id, FALSE)['Nome_modulo'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
	}
?>