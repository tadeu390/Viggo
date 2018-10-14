<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE DISCIPLINAS.
	*/
	class Disciplina_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS OU UMA DISCIPLINA ESPECÍFICA.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas disciplinas que estão ativas no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna uma disciplina caso a mesma exista no banco de dados.
		*	$page -> Pagina atual.
		*	$filter -> Quando há filtros, esta recebe os parâmetros utilizados para filtrar.
		*/
		public function get_disciplina($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE, $ordenacao = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND d.Ativo = 1 ";

			if ($Id === FALSE)//retorna todos se nao passar o parametro
			{
				$filtros = $this->filtros($filter);
				
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;

				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
					
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Disciplina WHERE TRUE ".$Ativos." ) AS Size,  
					d.Id, d.Nome as Nome_disciplina, d.Ativo, 
					d.Data_registro 
						FROM Disciplina d
					WHERE TRUE ".$Ativos." ".$filtros."
					".str_replace("'", "", $this->db->escape($order))." ". $pagination ."");

				return $query->result_array();
			}
			$query = $this->db->query("
					SELECT d.Id, d.Nome as Nome_disciplina, d.Ativo, d.Apelido,
					DATE_FORMAT(d.Data_registro, '%d/%m/%Y') as Data_registro 
						FROM Disciplina d
					WHERE TRUE ".$Ativos." AND Id = ".$this->db->escape($Id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR MONTAR A STRING SQL DO FILTRO.
		*	
		*	$filter -> Contém os filtros a serem colocados na string SQL.
		*/
		public function filtros($filter)
		{
			$filtros = "";
			if(!empty($filter))
				$filtros = " AND d.Nome LIKE ".$this->db->escape($filter['nome_disciplina']."%");
			return $filtros;
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS AS DISCIPLINAS DE UM DETERMINADO CURSO.
		*
		*	$id -> Id do curso.
		*/
		public function get_disciplina_por_curso($id)
		{
			$query = $this->db->query("
				SELECT d.Id, d.Nome FROM Disc_curso dc 
				INNER JOIN Disciplina d ON dc.Disciplina_id = d.Id 
				WHERE dc.Curso_id = ".$this->db->escape($id)."");
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR AS INFORMAÇÕES DE UMA DISCIPLINA NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados da disciplina a ser cadastrada/atualizada.
		*/
		public function set_disciplina($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Disciplina',$data);	
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Disciplina', $data);
			}
			return "sucesso";
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA DICIPLINA DO BANCO DE DADOS.
		*
		*	$id -> Id da disciplina a ser "apagada".
		*/
		public function delete_disciplina($id)
		{
			return $this->db->query("
				UPDATE Disciplina SET Ativo = 0 WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UMA DETERMINADA DISCIPLINA JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome da disciplina a ser validada.
		*	$Id -> Id da disciplina.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Disciplina 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($Nome).")");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_disciplina(FALSE, $Id, FALSE, FALSE)['Nome_disciplina'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
		//AQUI COMEÇA OS MÉTODOS UTILIZADOS NA TELA DO PROFESSOR
				/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE DISCIPLINAS QUE O PROFESSOR DA AULA, NO PERÍODO SELECIONADO.
		*	$professor_id -> Id do professor para se obter as disciplinas ligadas a ele.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_disciplinas_prof($professor_id, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT d.Nome AS Nome_disciplina, dt.Id AS Disc_turma_id, d.Id AS Disciplina_id, t.Id AS Turma_id, dh.Sub_turma    
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Disc_hor dh ON dh.Disc_turma_id = dt.Id 
				INNER JOIN Turma t ON dt.Turma_id = t.Id 
				INNER JOIN Disciplina d ON dg.Disciplina_id = d.Id 
				INNER JOIN Grade g ON dg.Grade_id = g.Id 
				INNER JOIN Curso c ON g.Curso_id = c.Id 
				WHERE dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." AND dt.Professor_Id = ".$this->db->escape($professor_id)." 
			    GROUP BY 1 ORDER BY d.Nome ");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A DISCIPLINA DA GRADE A SER EXIBIDA NA TELA PARA O PROFESSOR, CONFORME O HORÁRIO E PERÍODO LETIVO.
		*
		*	$professor_id -> Id do professor logado.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_disciplina_default($professor_id, $periodo_letivo_id)
		{
			$data = date('Y-m-d');

			$query = $this->db->query("
				SELECT dg.Disciplina_id, dt.Turma_id 
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
				INNER JOIN Horario h ON dh.Horario_id = h.Id 

				WHERE dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." AND 
				dt.Professor_Id = ".$this->db->escape($professor_id)." AND 
				h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') AND 
				dh.Ativo = 1 AND  
				TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') >= TIME_FORMAT(h.Inicio, '%H:%i') AND 
				TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') <= TIME_FORMAT(h.Fim, '%H:%i') 
			");
			return $query->row_array();
		}
		///AQUI COMEÇA OS MÉTODOS UTILIZADOS NO PORTAL DO ALUNO
		/*!
		*	RESPONSÁVEL POR RETORNAR TODAS AS DISCIPLINAS QUE UM ALUNO ESTÁ CURSANDO EM UM DETERMINADO 
		*	CURSO EM UM DETERMINADO PERÍODO LETIVO.
		*	
		*	$curso_id -> Curso selecionado pelo aluno ao entrar no portal.
		*	$periodo_letivo_id -> Período letivo selecionado pelo aluno ao entrar no portal.
		*/
		public function get_disciplinas_aluno($curso_id, $periodo_letivo_id, $aluno_id)
		{
			$query = $this->db->query("
				SELECT d.Nome AS Nome_disciplina, m.Id AS Matricula_id, dt.Turma_id  
				FROM Aluno a 
				INNER JOIN Inscricao i ON i.Aluno_id = a.Id AND i.Curso_id = ".$this->db->escape($curso_id)." 
				INNER JOIN Matricula m ON m.Inscricao_id = i.Id 
				INNER JOIN Disc_turma dt ON dt.Id = m.Disc_turma_id AND 
					dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
				INNER JOIN Disc_grade dg ON dg.Id = dt.Disc_grade_id 
				INNER JOIN Disciplina d ON d.Id = dg.Disciplina_id 
				WHERE a.Usuario_id = ".$this->db->escape($aluno_id)."
			");
			
			return $query->result_array();
		}
	}
?>