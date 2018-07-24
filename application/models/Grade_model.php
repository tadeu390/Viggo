<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS GRADES DO SISTEMA.
	*/
	class Grade_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}

		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE GRADE OU UMA GRADE ESPECÍFICA.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas grades que estão ativas no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna uma grade caso a mesma exista no banco de dados.
		*/
		public function get_grade($Ativo, $id = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND g.Ativo = 1 ";

			if ($id === FALSE)//retorna todos se nao passar o parametro
			{
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Grade WHERE TRUE ".$Ativos.") AS Size, 
					g.Ativo, g.Id, g.Nome AS Nome_grade, DATE_FORMAT(g.Data_registro, '%d/%m/%Y') as Data_registro, 
					dg.Periodo AS Periodo, c.Nome AS Nome_curso, m.Nome AS Nome_modalidade, pl.Periodo AS Nome_periodo_letivo 
					FROM Grade g 
					INNER JOIN Disc_grade dg ON g.Id = dg.Grade_id 
                    LEFT JOIN Curso c ON c.Id = g.Curso_id 
                    LEFT JOIN Modalidade m ON m.Id = g.Modalidade_id 
                    LEFT JOIN Periodo_letivo pl ON m.Id = pl. Modalidade_id 
					WHERE TRUE ".$Ativos." 
                    GROUP BY g.Id");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT g.Id, g.Nome as Nome_grade, DATE_FORMAT(g.Data_registro, '%d/%m/%Y') as Data_registro, 
				c.Nome AS Nome_curso, m.Nome AS Nome_modalidade, c.Id AS Curso_id, m.Id AS Modalidade_id, g.Ativo  
				FROM Grade g 
				INNER JOIN Curso c ON c.Id = G.Curso_id 
				INNER JOIN Modalidade m ON m.Id = g.Modalidade_id 
				WHERE TRUE ".$Ativos." AND g.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA GRADE DO BANCO DE DADOS.
		*
		*	$id -> Id da grade a ser "apagada".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Grade SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA GRADE NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da grade.
		*/
		public function set_grade($data)
		{
			$Disc_grade = $data['Disc_grade_to_save'];
			unset($data['Disc_grade_to_save']);

			if(empty($data['Id']))
			{
				$this->db->insert('Grade',$data);
				$query = $this->db->query("
					SELECT Id FROM Grade 
						WHERE Nome = ".$this->db->escape($data['Nome'])."");

				$query = $query->row_array();

				for($i = 0; $i < count($Disc_grade); $i++)
				{
					$dataToSave = array(
						'Disciplina_id' => $Disc_grade[$i]['Disciplina_id'],
						'Periodo' => $Disc_grade[$i]['Periodo'],
						'Grade_id' => $query['Id']
					);
					$this->db->insert('Disc_grade',$dataToSave);
				}
			}
			else
			{
				$this->db->where('Grade_id', $data['Id']);
				$this->db->delete('Disc_grade');

				for($i = 0; $i < count($Disc_grade); $i++)
				{
					$dataToSave = array(
						'Disciplina_id' => $Disc_grade[$i]['Disciplina_id'],
						'Periodo' => $Disc_grade[$i]['Periodo'],
						'Grade_id' => $data['Id']
					);
					$this->db->insert('Disc_grade',$dataToSave);
				}

				$this->db->where('Id', $data['Id']);
				$this->db->update('Grade', $data);
			}
			return "sucesso";
		}
	}
?>