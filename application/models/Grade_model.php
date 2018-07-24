<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DAS GRADES
	*	DISCIPLINARES DOS CURSOS.
	*/
	class Grade_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEX�O ACESS�VEL PARA OS METODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE GRADES OU UMA GRADE ESPECÍFICA.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas grades que estão ativas no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna uma grade caso a mesma exista no banco de dados.
		*	$page -> Pagina atual.
		*	$filter -> Quando há filtros, esta recebe os parâmetros utilizados para filtrar.
		*/
		public function get_grade($Ativo, $id = FALSE, $page = FALSE, $filter = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND g.Ativo = 1 ";

			if ($Id === FALSE)//retorna todos se nao passar o parametro
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;	
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
					
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM Turma u WHERE TRUE ".$Ativos.") AS Size,  
					g.Nome AS Nome_grade, g.Curso_id, g.Modalidade_id 
					FROM Grade g 
					WHERE TRUE ".$Ativos." 
					ORDER BY g.Id". $pagination ."");

				return $query->result_array();
			}
			$query = $this->db->query("
				SELECT (SELECT count(*) FROM Turma u WHERE TRUE ".$Ativos.") AS Size,  
				g.Nome AS Nome_grade, g.Curso_id, g.Modalidade_id 
				FROM Grade g 
				WHERE TRUE ".$Ativos." AND ".$this->db->escape($id)." 
				ORDER BY g.Id");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE GRADES CADASTRADAS.
		*
		*	$modalidade_id -> Id da modalidade selecionada para a turma no formulário,
		*	$curso_id -> Id do curso selecionado para a turma no formulários.
		*/
		public function get_grade_por_mc($modalidade_id, $curso_id)
		{
			$query = $this->db->query("
				SELECT Id, Nome as Nome_grade FROM Grade 
				WHERE Modalidade_id = ".$this->db->escape($modalidade_id)." AND 
				Curso_id = ".$this->db->escape($curso_id)." AND Ativo = 1 ORDER BY Id DESC");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR LEVANTAR QUANTOS PERÍDOS UMA GRADE TEM. ESSES PERÍODOS TAMBÉM
		*	SÃO A QUANTIDADE DE PERÍODO QUE O CURSO NA GRADE POSSUI.
		*
		*	$grade_id -> Id da grade que se deseja obter os períodos.
		*/
		public function get_periodo_grade($grade_id)
		{
			$query = $this->db->query("
				SELECT dg.Periodo FROM Grade g 
				INNER JOIN Disc_grade dg ON g.Id = dg.Grade_id 
				WHERE dg.Grade_id = ".$this->db->escape($grade_id)." GROUP BY 1");

			return $query->result_array();
		}
	}
?>