<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS CONTEÚDOS LECIONADOS PELOS PROFESSORES.
	*/
	class Conteudo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}

		public function get_conteudo($disc_hor_id, $data)
		{
			$query = $this->db->query("
				SELECT Id AS Conteudo_id, Descricao FROM Conteudo 
				WHERE Disc_hor_id = ".$this->db->escape($disc_hor_id)." AND 
				CAST(Data_registro AS DATE) = ".$this->db->escape($data)."
			");
			return $query->row_array();
		}

		public function set_conteudo($data)
		{
			for($i = 0; $i < COUNT($data); $i++)
			{
				if(empty($data[$i]['Id']))
					$this->db->insert('Conteudo',$data[$i]);
				else
				{
					$this->db->where('Id',$data[$i]['Id']);
					$this->db->update('Conteudo',$data[$i]);
				}
			}
		}
	}
?>