<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS DOCUMENTOS CADASTRADOS PARA CADA INSCRIÇÃO.
	*/
	class Doc_inscricao_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS OS DOCUMENTOS CADASTRADOS PARA UMA INSCRIÇÃO.
		*	
		*	$inscricao_id -> Id da inscrição para se buscar os possíveis documentos.
		*/
		public function get_doc_inscricao($inscricao_id)
		{
			$query = $this->db->query("
				SELECT Doc_id, Outros FROM Doc_inscricao 
				WHERE Inscricao_id = ".$this->db->escape($inscricao_id)."
			");
			
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR OS DOCUMENTOS QUE A ESCOLA POSSUI DE UM ALUNO EM UMA DETERMINADA INSCRIÇÃO.
		*
		*	$docs_id -> Id dos documentos que o aluno trouxe.
		*	$inscricao_id -> Id da inscrição para a qual se está cadastrando/atualizando os documentos.
		*/
		public function set_doc_inscricao($docs_id, $doc_outros, $inscricao_id)
		{
			for($i = 0; $i < COUNT($docs_id); $i++)
			{
				$data = array(
					'Inscricao_id' => $inscricao_id,
					'Doc_id' => $docs_id[$i]
				);
				if($docs_id[$i] == 14)
					$data['Outros'] = $doc_outros['Rg_outro'];
				else if($docs_id[$i] == 15)
					$data['Outros'] = $doc_outros['Cpf_outro'];

				//verificar se já existe
				$query = $this->db->query("
					SELECT Id FROM Doc_inscricao 
					WHERE Inscricao_id = ".$this->db->escape($inscricao_id)." AND Doc_id = ".$this->db->escape($docs_id[$i])."");
				
				if(empty($query->row_array()))
					$this->db->insert('Doc_inscricao', $data);
				else{
					$this->db->where('Id', $query->row_array()['Id']);
					$this->db->update('Doc_inscricao', $data);
				}
			}

			//buscar todos do banco
			$query = $this->db->query("
				SELECT Doc_id FROM Doc_inscricao 
				WHERE Inscricao_id = ".$this->db->escape($inscricao_id)."
			");
			$doc_banco = $query->result_array();
			for($i = 0; $i < COUNT($doc_banco); $i++)
			{	
				$flag = 0;
				for($j = 0; $j < COUNT($docs_id); $j++)
				{
					if($doc_banco[$i]['Doc_id'] == $docs_id[$j])
						$flag = 1;
				}
				if($flag == 0)
				{
					$this->db->where('Inscricao_id', $inscricao_id);
					$this->db->where('Doc_id', $doc_banco[$i]['Doc_id']);
					$this->db->delete('Doc_inscricao');
				}
			}
		}
		/*!
		*	RESPONSÁVEL POR REMOVER TODOS OS DOCUMENTOS CADASTRADOS PARA UMA INSCRIÇÃO.
		*
		*	$inscricao_id -> Id da inscrição do aluno.
		*/
		public function delete_doc_inscricao($inscricao_id)
		{
			$this->db->where('Inscricao_id', $inscricao_id);
			$this->db->delete('Doc_inscricao');
		}
	}
?>