<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS ENDEREÇOS DO ALUNO.
	*/
	class Endereco_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA OU UM ÚNICO ENDEREÇO.
		*
		* 	$ativo-> Retorna apenas endereço ativo se passado algo diferente de false.
		*	$id -> Id de um endereço.
		*	$aluno_id -> Id do aluno.
		*/
		public function get_endereco($ativo, $id, $aluno_id)
		{
			$a = "";
			if($ativo !== FALSE)
				$a = " AND Ativo = ".$ativo;

			$aluno = "";
			if($aluno_id !== FALSE && !empty($aluno_id))
				$aluno = " AND Aluno_id = ".$aluno_id;

			if($id === FALSE)
			{
				$query =  $this->db->query("
					SELECT Id AS Endereco_id, Rua, Bairro, Municipio, Zona, 
					Transp_publico, Uf, Cep, Telefone_aluno, Telefone_responsavel, Aluno_id 
					FROM Endereco  
					WHERE TRUE ".$a." ".$aluno."");

				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT Id AS Endereco_id, Rua, Bairro, Municipio, Zona, 
					Transp_publico, Uf, Cep, Telefone_aluno, Telefone_responsavel, Aluno_id 
					FROM Endereco  
				WHERE Id = ".$this->db->escape($id)." ".$a." ".$t."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR UM ENDEREÇO.
		*
		*	$data -> Contém os dados de um endereço.
		*/
		public function set_endereco($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Endereco',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Endereco', $data);
			}
		}
	}
?>