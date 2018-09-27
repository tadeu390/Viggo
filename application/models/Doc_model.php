<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS DOCUMENTOS NECESSÁRIOS PARA OS ALUNOS ESTAREM EM DIA COM A ESCOLA.
	*/
	class Doc_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA OU UM DOCUMENTO ESPECÍFICO.
		*
		* 	$ativo-> Retorna apenas documento ativo se passado algo diferente de false.
		*	$id -> Id de usuário do aluno.
		*	$tipo -> Documento de pais ou documento de aluno
		*/
		public function get_doc($ativo, $id, $tipo)
		{
			$a = "";
			if($ativo !== FALSE)
				$a = " AND Ativo = ".$ativo;

			$t = "";
			if($tipo !== FALSE)
				$t = " AND Tipo = ".$tipo;

			if($id === FALSE)
			{
				$query =  $this->db->query("
					SELECT Id AS Doc_id, Nome AS Nome_doc, Tipo 
						FROM Doc  
					WHERE TRUE ".$a." ".$t."");

				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT Id AS Doc_id, Nome AS Nome_doc, Tipo 
						FROM Doc 
				WHERE Id = ".$this->db->escape($id)." ".$a." ".$t."");

			return $query->row_array();
		}
	}
?>