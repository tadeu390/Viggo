<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE TURMAS.
	*/
	class Turma_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE TURMAS (COM O STATUS DO HORÁRIO DA TURMA) OU UMA TURMA ESPECÍFICA.
		*	
		*	$Ativo -> Quando passadO "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma turma específica.
		*	$page-> Número da página de registros que se quer carregar.
		*	$filter -> Contém todos os filtros utilizados pelo usuário para a fazer a busca no banco de dados.
		*/
		public function get_turma($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE, $ordenacao = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND t.Ativo = 1 ";

			if ($Id === FALSE)
			{
				$filtros = "";//$this->filtros($filter);
				$order = "";
				
				if($ordenacao != FALSE)
					$order = "ORDER BY ".$ordenacao['field']." ".$ordenacao['order'];

				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM Turma u WHERE TRUE ".$filtros.") AS Size, t.Id, 
					t.Nome as Nome_turma, t.Ativo as Ativo_turma, dt.Periodo_letivo_id, p.Periodo, m.Nome as Nome_modalidade,
					(SELECT COUNT(*) FROM Disc_turma dtx WHERE dtx.Turma_Id = dt.Turma_Id) AS Total_disciplina,
					(SELECT COUNT(DISTINCT dty.Id) FROM Disc_turma dty INNER JOIN Disc_hor dh ON dty.Id = dh.Disc_turma_id 
						WHERE dty.Turma_Id = dt.Turma_Id AND dh.Ativo = 1) AS Disciplina_com_horario 
					FROM Turma t 
					INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
					INNER JOIN Periodo_letivo p ON dt.periodo_letivo_id = p.Id 
					INNER JOIN Modalidade m ON p.Modalidade_id = m.Id 
					WHERE TRUE ".$Ativos."".$filtros." GROUP BY t.Id, t.Nome 
					".str_replace("'", "", $this->db->escape($order))." ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT t.Id, t.Nome as Nome_turma, t.Ativo,  
				c.Id AS Curso_id, 
				DATE_FORMAT(t.Data_registro, '%d/%m/%Y') as Data_registro, dt.Periodo_letivo_id, CONCAT(p.Periodo, ' / ', m.Nome) as Pe_modi  
					FROM Turma t 
					INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
					INNER JOIN Disc_grade dg ON dg.Id = dt.Disc_grade_id 
					INNER JOIN Grade g ON dg.Grade_id = g.Id 
					INNER JOIN Curso c ON c.Id = g.Curso_id 
					INNER JOIN Periodo_letivo p ON dt.periodo_letivo_id = p.Id 
					INNER JOIN Modalidade m ON p.Modalidade_id = m.Id  
				WHERE TRUE ".$Ativos." AND t.Id = ".$this->db->escape($Id)."");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UA TURMA DO BANCO DE DADOS.
		*
		*	$id -> Id da turma a ser "apagada".
		*/
		public function deletar($Id)
		{
			return $this->db->query("
				UPDATE Turma SET Ativo = 0 
				WHERE Id = ".$this->db->escape($Id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA TURMA E EM SEGUIDA RETORNA A SUA ID.
		*
		*	$data -> Contém todos os dados da turma.
		*/
		public function set_turma($data)
		{
			$modalidade_id = $data['Modalidade_id'];
			
			unset($data['Modalidade_id']);
			unset($data['Disc_to_save']);
			unset($data['Aluno_to_save']);
			
			if(empty($data['Id']))
				$this->db->insert('Turma',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Turma', $data);
			}
			return $this->get_turma_por_nome($data['Nome'])['Id'];
		}
		/*!
		*	RESPONSÁVEL POR RETORAR UM TURMA DE ACORDO COM O NOME.
		*
		*	$nome -> Nome da turma a ser cadastrada/editada.
		*	$modalidade_id -> Modalidade ensino especificado para a turma.
		*/
		public function get_turma_por_nome($nome)
		{
			$query = $this->db->query("
				SELECT Id FROM Turma 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($nome).") ORDER BY Id DESC LIMIT 1");
			
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR O NOME DA TURMA, OU SEJA, VERIFICA SE O NOME JÁ ESTÁ EM USO PARA 
		*	UMA DETERMINADA MODALIDADE EM UM DETERMINADO PERÍODO
		*
		*	$id -> Id da turma.
		*	$nome -> Nome da turma.
		*	$modalidade_id -> Modalidade selecionada para a turma.
		*/
		public function nome_valido($id, $nome, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT t.Id 
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
				WHERE UPPER(t.Nome) = UPPER(".$this->db->escape($nome).") AND 
			    dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
				GROUP BY 1");

			$query = $query->row_array();

			if(empty($query['Id']) || $query['Id'] == $id)
				return "valido";
			return "invalido";
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE TURMAS DE UM DETERMINADO CURSO CUJO PERÍODO LETIVO SEJA
		*	ANTERIOR AO PASSADO COMO PARÂMETRO. APENAS PARA MONTAR O COMBO BOX DE TURMA PARA USAR COMO FILTRO.
		*	(TURMAS ANTIGAS)
		*
		*	$curso_id -> Curso da turma.
		*	$periodo_letivo_id -> Id do período letivo das turmas a serem buscadas.
		*	$grade -> Id da grade selecionada no formulário de cadastro de turma.
		*/
		public function get_turma_cp($curso_id, $modalidade_id,$periodo_letivo_id, $grade_id)//COLOCAR PERIODO
		{
			$query = $this->db->query("
				SELECT t.Id, t.Nome as Nome_turma, CONCAT(p.Periodo, ' - ', m.Nome) as Pe_modi  
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_Id 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Grade g ON g.Id = dg.Grade_id 
				INNER JOIN Periodo_letivo p ON dt.Periodo_letivo_id = p.Id 
				INNER JOIN Modalidade m ON p.Modalidade_id = m.Id 
				WHERE g.Curso_id = ".$this->db->escape($curso_id)." AND 
				g.Id = ".$this->db->escape($grade_id)." AND 
				dt.Periodo_letivo_id < ".$this->db->escape($periodo_letivo_id)." AND 
				m.Id = ".$this->db->escape($modalidade_id)." 
                GROUP BY 1,2");
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODAS AS TURMAS QUE O PROFESSOR DA AULA NO PERÍODO SELECIONADO.
		* 	(utilizado para listar as turmas de cada disciplina e também utilizado para listar as turmas
		*	pra que o professor possa ver o horário).
		*
		*	$disciplina_id -> Id da disciplina de uma determinada grade.
		*	$professor_id -> Id do professor para se obter as disciplinas ligadas a ele.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_turma_prof($disciplina_id = FALSE, $professor_id, $periodo_letivo_id)
		{
			$disciplina = "";
			if($disciplina_id !== FALSE)
				$disciplina = " AND dg.Disciplina_id = ".$this->db->escape($disciplina_id);

			$query = $this->db->query("
				SELECT t.Nome AS Nome_turma, dg.Disciplina_id, t.Id AS Turma_id
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
				INNER JOIN Disc_grade dg ON dg.Id = dt.Disc_grade_id  
				INNER JOIN Disc_hor dh ON dh.Disc_turma_id = dt.Id #somente turmas que tem a disciplina em questão em algum horário
				WHERE dt.Professor_Id = ".$this->db->escape($professor_id)." ".$disciplina." AND 
				dt.periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
				GROUP BY 1, 2, 3
			");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR IDENTIFICAR A SUBTURMA PADRÃO QUANDO O PROFESSOR ACESSA O PORTAL, COM BASE NO DIA E HORÁRIO.
		*
		*	$disciplina_id -> Id da disciplina que se quer verificar se há subturma nela.
		*	$turma_id -> Id da turma na qual está associada a disciplina em questão.
		*/
		public function get_sub_turma_default($disciplina_id, $turma_id)
		{
			$data = date('Y-m-d');
			
			$query = $this->db->query("
				SELECT x.Sub_turma FROM (SELECT dh.Sub_turma 
					FROM Disc_turma dt 
					INNER JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
					INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
					INNER JOIN Horario h ON dh.Horario_id = h.Id 
					WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
					dt.Turma_id = ".$this->db->escape($turma_id)." AND 
					h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') AND 
				 	dh.Ativo = 1 AND 
				 	TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') >= TIME_FORMAT(h.Inicio, '%H:%i') AND 
					TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') <= TIME_FORMAT(h.Fim, '%H:%i') 
				 	GROUP BY dh.Horario_id) AS x GROUP BY x.Sub_turma 
			");
			
			if(empty($query->row_array()))
				return null;//sem subturma para o horario corrente
			
			return $query->row_array()['Sub_turma'];
		}
		/*!
		*	RESPONSÁVEL POR IDENTIFICAR A QUANTIDADE DE SUBTURMAS E RETORNAR CADA SUB_TURMA QUANDO EXISTIR.
		*
		*	$disciplina_id -> Id da disciplina que se quer verificar se há subturma nela.
		*	$turma_id -> Id da turma na qual está associada a disciplina em questão.
		*/
		public function get_sub_turmas($disciplina_id, $turma_id, $data= false)
		{
			$query = $this->db->query("
				SELECT x.Sub_turma FROM (SELECT dh.Sub_turma 
					FROM Disc_turma dt 
					INNER JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
					INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
					INNER JOIN Horario h ON dh.Horario_id = h.Id 
					WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND dt.Turma_id = ".$this->db->escape($turma_id)." AND 
					h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') AND 
				 	dh.Ativo = 1
				 	GROUP BY dh.Horario_id) AS x GROUP BY x.Sub_turma 
			");
			return $query->result_array();
		}
	}
?>