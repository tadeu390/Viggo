<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE DISCIPLINAS.
	*/
	class Disciplina_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS OU UMA DISCIPLINA ESPECÍFICA.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas disciplinas que estão ativas no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna uma disciplina caso a mesma exista no banco de dados.
		*	$page -> Pagina atual.
		*	$filter -> Quando há filtros, esta recebe os parâmetros utilizados para filtrar.
		*/
		public function get_disciplina($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE, $ordenacao = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND d.Ativo = 1 ";

			if ($Id === FALSE)//retorna todos se nao passar o parametro
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;

				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
					
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Disciplina WHERE TRUE ".$Ativos." ) AS Size,  
					d.Id, d.Nome as Nome_disciplina, d.Ativo, 
					d.Data_registro 
						FROM Disciplina d
					WHERE TRUE ".$Ativos." 
					".str_replace("'", "", $this->db->escape($order))." ". $pagination ."");

				return $query->result_array();
			}
			$query = $this->db->query("
					SELECT d.Id, d.Nome as Nome_disciplina, d.Ativo, d.Apelido,
					DATE_FORMAT(d.Data_registro, '%d/%m/%Y') as Data_registro 
						FROM Disciplina d
					WHERE TRUE ".$Ativos." AND Id = ".$this->db->escape($Id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS AS DISCIPLINAS DE UM DETERMINADO CURSO.
		*
		*	$id -> Id do curso.
		*/
		public function get_disciplina_por_curso($id)
		{
			$query = $this->db->query("
				SELECT d.Id, d.Nome FROM Disc_curso dc 
				INNER JOIN Disciplina d ON dc.Disciplina_id = d.Id 
				WHERE dc.Curso_id = ".$this->db->escape($id)."");
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR AS INFORMAÇÕES DE UMA DISCIPLINA NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados da disciplina a ser cadastrada/atualizada.
		*/
		public function set_disciplina($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Disciplina',$data);	
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Disciplina', $data);
			}
			return "sucesso";
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA DICIPLINA DO BANCO DE DADOS.
		*
		*	$id -> Id da disciplina a ser "apagada".
		*/
		public function delete_disciplina($id)
		{
			return $this->db->query("
				UPDATE Disciplina SET Ativo = 0 WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UMA DETERMINADA DISCIPLINA JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome da disciplina a ser validada.
		*	$Id -> Id da disciplina.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Disciplina 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($Nome).")");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_disciplina(FALSE, $Id, FALSE, FALSE)['Nome_disciplina'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
	}
?>