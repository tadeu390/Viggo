<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS OPÇÕES DA TELA INICIAL QUANDO UM USUÁRIO ENTRA.
	*/
	class Academico_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE PERÍODOS LETIVOS. SOMENTE OS PERÍODOS LETIVOS EM QUE O PROFESSOR LOGADO POSSUI ALGUM HISTÓRICO. (NO MÁXIMO 10)
		*
		*	$professor_id -> id do professor logado no sistema.
		*/
		public function get_periodos_professor($professor_id)
		{
			$query = $this->db->query("
				SELECT p.Id AS Periodo_letivo_id, p.Periodo AS Nome_periodo, m.Nome AS Nome_modalidade  
				FROM Periodo_letivo p 
				INNER JOIN Disc_turma dt ON dt.Periodo_letivo_id = p.Id 
				INNER JOIN Modalidade m ON m.Id = p.Modalidade_id 
				WHERE dt.Professor_Id = ".$this->db->escape($professor_id)." 
				GROUP BY 2 ORDER BY p.Id DESC LIMIT 10");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA OU UM PERÍODO LETIVO, ASSOCIADO AO CURSO PARA O ALUNO SELECIONAR O QUE ELE DESEJA VER;
		*	$aluno_id -> Id de usuário do aluno logado.
		*	$curso_id -> Id do curso escolhido pelo aluno.
		*/
		public function get_periodos_aluno($aluno_id, $curso_id)
		{
			$curso = "";
			if($curso_id !== FALSE)
				$curso = " AND c.Id = ".$this->db->escape($curso_id);

			$query = $this->db->query("
				SELECT p.Id AS Periodo_letivo_id, c.Id AS Curso_id, CONCAT(c.Nome, ': ', p.Periodo, ' - ', md.Nome) AS Curso
				FROM Periodo_letivo p 
		        INNER JOIN Modalidade md ON md.Id = p.Modalidade_id 
				INNER JOIN Disc_turma dt ON p.Id = dt.Periodo_letivo_id 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Inscricao i ON m.Inscricao_id = i.Id 
		    	INNER JOIN Curso c ON c.Id = i.Curso_id 
				INNER JOIN Aluno a ON i.Aluno_id = a.Id 
				INNER JOIN Usuario u ON a.Usuario_id = u.Id 
				WHERE u.Id = ".$this->db->escape($aluno_id)." ".$curso." 
		        GROUP BY c.Id  
        	");

        	return $query->result_array();
		}
	}
?>