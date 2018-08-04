<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE CURSOS.
	*/
	class Curso_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE CURSOS OU UM CURSO ESPECÍFICO.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas cursos que estão ativos no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna um curso caso o mesmo exista no banco de dados.
		*	$page -> Pagina atual.
		*	$filter -> Quando há filtros, esta recebe os parâmetros utilizados para filtrar.
		*/
		public function get_curso($Ativo, $id = FALSE, $page = FALSE, $filter = FALSE, $ordenacao = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND c.Ativo = 1 ";

			if ($id === FALSE)//retorna todos se nao passar o parametro
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
					SELECT (SELECT count(*) FROM  Curso WHERE TRUE ".$Ativos.") AS Size, 
					c.Id, c.Nome as Nome_curso, 
					DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, c.Ativo 
					FROM Curso c 
					WHERE TRUE ".$Ativos." ".str_replace("'", "", $this->db->escape($order))." ". $pagination ."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT c.Id, c.Nome as Nome_curso, 
				DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, c.Ativo 
					FROM Curso c 
				WHERE TRUE ".$Ativos." AND c.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR AS INFORMAÇÕES DE UM CURSO NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados do curso a ser cadastrado/atualizado.
		*/
		public function set_curso($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Curso',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Curso', $data);
			}
			return "sucesso";
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UM CURSO DO BANCO DE DADOS.
		*
		*	$id -> Id do curso a ser "apagado".
		*/
		public function delete_curso($id)
		{
			return $this->db->query("
				UPDATE Curso SET Ativo = 0 WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM DETERMIANDO CURSO JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome do curso a ser validado.
		*	$Id -> Id do curso.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Curso 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($Nome).")");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_curso(FALSE, $Id, FALSE, FALSE)['Nome_curso'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
	}
?>