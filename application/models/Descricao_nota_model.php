<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES 
	*	DAS DESCRIÇÕES DE NOTAS.
	*/
	class Descricao_nota_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DESCRIÇÕES DE NOTAS CADASTRADAS.
		*
		*	Ativo -> Se passado como TRUE retorna apenas registros ativos.
		*	$id -> Id de uma descrição de nota.
		*/
		public function get_descricao($Ativo = FALSE, $id = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND Ativo = 1 ";

			if($id === FALSE)
			{
				$query = $this->db->query("
					SELECT Id, Descricao 
					FROM Descricao_nota 
					WHERE TRUE ".$Ativos." ORDER BY Id");
				return $query->result_array();
			}

			$query = $this->db->query("
					SELECT Id, Descricao 
					FROM Descricao_nota 
					WHERE Id = ".$this->db->escape($id)." ".$Ativos."");
			return $query->row_array();
		}
	}
?>