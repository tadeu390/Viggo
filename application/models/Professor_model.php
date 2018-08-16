<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS MATRICULAS DO SISTEMA.
	*/
	class Professor_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS QUE O PROFESSOR DA AULA, NO PERÍODO SELECIONADO.
		*	$professor_id -> Id do professor para se obter as disciplinas ligadas a ele.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_disciplinas($professor_id, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT d.Nome AS Nome_disciplina, dt.Id AS Disc_turma_id, dt.Disc_grade_id, t.Id AS Turma_id   
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Turma t ON dt.Turma_id = t.Id 
				INNER JOIN Disciplina d ON dg.Disciplina_id = d.Id 
				INNER JOIN Grade g ON dg.Grade_id = g.Id 
				INNER JOIN Curso c ON g.Curso_id = c.Id 
				WHERE dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." AND dt.Professor_Id = ".$this->db->escape($professor_id)." 
			    GROUP BY 1 ORDER BY d.Nome ");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODAS AS TURMAS QUE O PROFESSOR DA AULA NO PERÍODO SELECIONADO.
		*
		*	$disc_grade_id -> Id da disciplina de uma determinada grade.
		*	$professor_id -> Id do professor para se obter as disciplinas ligadas a ele.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_turma($disc_grade_id, $professor_id, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT t.Nome AS Nome_turma, dt.Disc_grade_id, t.Id AS Turma_id
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
				WHERE dt.Professor_Id = ".$this->db->escape($professor_id)." AND dt.Disc_grade_id = ".$this->db->escape($disc_grade_id)."  AND 
				dt.periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
				GROUP BY 1, 2 
			");
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CARREGAR O BIMESTRE A SER SELECIONADO POR PADRÃO NA TELA PARA O PROFESSOR, ISSO É FEITO COM BASE NA DATA CORRENTE.
		*
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_bimestre_default($periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT * FROM Bimestre 
				WHERE CAST(NOW() AS DATE) >= CAST(Data_inicio AS DATE) AND CAST(NOW() AS DATE) <= CAST(Data_fim AS DATE) AND 
				Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A DISCIPLINA DA GRADE A SER EXIBIDA NA TELA PARA O PROFESSOR, CONFORME O HORÁRIO E PERÍODO LETIVO.
		*
		*	$professor_id -> Id do professor logado.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_disciplina_default($professor_id, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT dt.Disc_grade_id, dt.Turma_id  
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
				INNER JOIN Horario h ON dh.Horario_id = h.Id 

				WHERE dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." AND dt.Professor_Id = ".$this->db->escape($professor_id)." 
				AND TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') >= TIME_FORMAT(h.Inicio, '%H:%i') AND TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') <= TIME_FORMAT(h.Fim, '%H:%i')
			    ");

			return $query->row_array();
		}
	}
?>