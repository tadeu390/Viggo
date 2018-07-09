<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DA TURMA DE ENSINO.
	*/
	class Disc_turma_model extends CI_Model 
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
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE MODALIDADES OU UMA MODALIDADE ESPECÍFICA.
		*
		*	$id -> Quando passado algum valor inteiro, retorna todos os registros de uma determinada turma.
		*/
		public function get_disc_turma($id = FALSE)
		{
			$query = $this->db->query("
				SELECT dcx.Curso_id, dt.Turma_id, d.Id AS Disciplina_id, dt.Categoria_id, 
				dt.Periodo_letivo_id, dt.Professor_id, dt.Matricula_id 
				FROM Disciplina d 
                LEFT JOIN Disc_turma dt ON d.Id = dt.Disc_curso_id 
                	AND dt.Turma_id = ".$this->db->escape($id)."  
                LEFT JOIN (SELECT dcx.Id as Disc_curso_id, dx.Id as Disciplina_id, 
                			dx.Nome as Nome_disciplina, cx.Id as Curso_id, cx.Nome as Nome_curso
                          	FROM Curso cx 
                          	INNER JOIN Disc_curso dcx ON cx.Id = dcx.Curso_id 
                          	INNER JOIN Disciplina dx ON dcx.Disciplina_id = dx.Id) as dcx ON dcx.Disc_curso_id = dt.Disc_curso_id 
                LEFT JOIN Usuario u ON u.Id = dt.Professor_Id AND u.Grupo_id = ".PROFESSOR." 
				LEFT JOIN Matricula m ON  m.Id = dt.Matricula_id");

			return $query->result_array();
		}
	}
?>