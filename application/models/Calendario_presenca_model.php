<?php
/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS PRESÊNÇA DOS ALUNOS.
	*/
	class Calendario_presenca_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
			REPONSÁVEL POR RETORNAR A PRESENÇA DE UM DETERMINADO ALUNO EM UMA DETERMINADA TURMA 
			DE UMA DETERMINADA DISCIPLINA.
		*/
		public function get_presenca_aluno($matricula_id, $disciplina_id, $turma_id, $sub_turma, $data)
		{
			$query = $this->db->query("
				SELECT cp.Id AS Calendario_presenca_id, cp.Presenca, cp.Data_registro, cp.Justificativa,
				DATE_FORMAT(cp.Data_registro,'%W') AS Dia_presenca, CAST(cp.Data_registro AS TIME) AS Hora_inicio 
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				LEFT JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id AND m.Sub_turma = dh.Sub_turma 
                LEFT JOIN Calendario_presenca cp ON m.Id = cp.Matricula_id 
                LEFT JOIN Horario h ON h.Id = dh.Horario_id AND 
                h.Dia = DATE_FORMAT(cp.Data_registro,'%w') AND 
                CAST(cp.Data_registro AS TIME) = h.Inicio 
				
				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." AND 
				dh.Sub_turma = ".$this->db->escape($sub_turma)." AND 
				m.Id = ".$this->db->escape($matricula_id)." AND 
				CAST(cp.Data_registro AS DATE) = CAST(".$this->db->escape($data)." AS DATE)
                GROUP BY dh.Sub_turma, m.Id, cp.Data_registro
			");

			return $query->result_array();
		}

		public function set_presenca($data)
		{
			for($i = 0; $i < COUNT($data); $i++)
			{
				if(empty($data[$i]['Id']))
					$this->db->insert('Calendario_presenca', $data[$i]);
				else
				{
					$this->db->where('Id', $data[$i]['Id']);
					$this->db->update('Calendario_presenca', $data[$i]);
				}
			}
		}
	}
?>