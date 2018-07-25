<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES 
	*	DAS DISCIPLINAS E GRADES.
	*/
	class Disc_grade_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS MÉTODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS CADASTRADAS OU NÃO PARA UMA DETERMINADA GRADE.
		*
		*	$id -> Id da grade para que se possa obter as disciplinas já cadastradas para ela caso exista.
		*/
		public function get_disc_grade_disciplina($id)
		{
			$query = $this->db->query("
				SELECT d.Id AS Disciplina_id, d.Nome AS Nome_disciplina, d.Apelido AS Apelido_disciplina, dg.Periodo AS Periodo 
				FROM Disciplina d 
				INNER JOIN Disc_grade dg ON dg.Disciplina_id = d.Id 
                	AND dg.Grade_id = ".$this->db->escape($id)." ORDER BY Periodo");

			return $query->result_array();
		}
		/*!
			RESPONSÁVEL POR CRIAR OU ATUALIZAR OS DADOS DE UMA GRADE PARA CADA DISCIPLINA CONTIDA.

			$data -> Contém os dados a serem cadastrados como as disciplinas marcadas e seus respectivos
			período de uma grade.
			$grade_id -> Id da grade que contém os dados a serem cadastrados ou modificados.
		*/
		public function set_disc_grade($data, $grade_id)
		{
			for($i = 0; $i < count($data['Disc_grade_to_save']); $i++)
			{
				if($data['Disc_grade_to_save'][$i]['Periodo'] > 0)
				{
					//verificar se ja existe no banco
					$query = $this->db->query("
						SELECT Id FROM Disc_grade 
						WHERE Grade_id = ".$this->db->escape($grade_id)."");
					$r = $query->row_array();
					
					//carrega o array com os dados da disciplina para a grade em questão
					$dataToSave = array(
						'Grade_id' => $grade_id,
						'Disciplina_id' => $data['Disc_grade_to_save'][$i]['Disciplina_id'],
						'Periodo' => $data['Disc_grade_to_save'][$i]['Periodo']
					 );
					
					//se não encontrou a disciplina cadastrada para a grade entao insere.
					if(empty($r))
						$this->db->insert('Disc_grade',$dataToSave);
					else //se a disciplina para a grade já estiver cadastrada então apenas atualiza
					{
						$this->db->where('Grade_id', $grade_id);
						$this->db->where('Disciplina_id', $data['Disc_grade_to_save'][$i]['Disciplina_id']);
						$this->db->where('Periodo', $data['Disc_grade_to_save'][$i]['Periodo']);
						$this->db->update('Disc_grade', $dataToSave);
					}

					//obter o id da disc grade
					$query = $this->db->query("
						SELECT Id FROM Disc_grade 
						WHERE Grade_id = ".$this->db->escape($grade_id)."");
					$r = $query->row_array();
				}
				else
				{
					//APAGA AS DISCIPLINAS REMOVIDAS, EXTREMO CUIDADO AO APAGAR UMA DISCIPLINA, 
					//POIS O SISTEMA DELETA EM CASCATA.
					$query = $this->db->query("
						DELETE FROM Disc_grade 
						WHERE Grade_id = ".$this->db->escape($grade_id)."");
				}
			}
		}
	}
?>