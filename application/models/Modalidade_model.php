<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DAS MODALIDADES DE ENSINO.
	*/
	class Modalidade_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADE OU UMA MODALIDADE ESPECÍFICA.
		*	
		*	$Ativo -> Quando passad "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma modalidade específica.
		*	$page-> Número da página de registros que se quer carregar.
		*/
		public function get_modalidade($Ativo = FALSE, $id = false, $page = false, $ordenacao = false)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;

				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Modalidade) AS Size, Id, Nome AS Nome_modalidade, Ativo 
						FROM Modalidade 
					WHERE TRUE ".$Ativos."
					".str_replace("'", "", $this->db->escape($order))." ".$pagination."");
				
				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT Id, Nome AS Nome_modalidade, Ativo 
					FROM Modalidade 
				WHERE Id = ".$this->db->escape($id)." ".$Ativos."");
			
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA MODALIDADE DO BANCO DE DADOS.
		*
		*	$id -> Id da modalidade a ser "apagada".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Modalidade SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA MODALIDADE NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da modalidade.
		*/
		public function set_modalidade($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Modalidade',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Modalidade', $data);
			}
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UMA DETERMINADA MODALIDADE JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome da modalidade a ser validada.
		*	$Id -> Id da modalidade.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Modalidade 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($Nome).")");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_modalidade(FALSE ,$Id, FALSE)['Nome_modalidade'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O ÚLTIMO PERÍODO LETIVO CADASTRADO PARA UMA DETERMINADA MODALIDADE.
		*	
		*	$modalidade_id -> Id da modalidade que se deseja obter o período letivo.
		*/
		public function get_periodo_por_modalidade($modalidade_id)
		{
			$query = $this->db->query("
				SELECT * FROM Periodo_letivo 
				WHERE Modalidade_id = ".$this->db->escape($modalidade_id)." AND Ativo = 1 
				ORDER BY Id DESC LIMIT 1");
			
			return $query->row_array();
		}
	}
?>