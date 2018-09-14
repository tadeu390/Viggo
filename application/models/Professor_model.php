<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS OPERAÇÕES QUE O PROFESSOR REALIZA.
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
		*	RESPONSÁVEL POR RETORNAR TODAS AS TURMAS QUE O PROFESSOR DA AULA NO PERÍODO SELECIONADO (utilizado para listar as turmas de cada disciplina e 
		*	também utilizado para listar as turmas pra que o professor possa ver o horário).
		*
		*	$disciplina_id -> Id da disciplina de uma determinada grade.
		*	$professor_id -> Id do professor para se obter as disciplinas ligadas a ele.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_turma($disciplina_id = FALSE, $professor_id, $periodo_letivo_id)
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
		*	RESPONSÁVEL POR CARREGAR O BIMESTRE A SER SELECIONADO POR PADRÃO NA TELA PARA O PROFESSOR, 
		*	ISSO É FEITO COM BASE NA DATA CORRENTE E SE CASO O ACESSO ESTIVER OCORRENDO EM UMA DATA QUE NÃO
		*	CORRESPONDE A NENHUMA DATA DE NENHUM BIMESTRE, O MÉTODO ENTÃO CARREGA O PRIMEIRO BIMESTRE POR DEFAULT.
		*
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*/
		public function get_etapa_default($periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT * FROM Etapa 
				WHERE CAST(NOW() AS DATE) >= CAST(Data_inicio AS DATE) AND CAST(NOW() AS DATE) <= CAST(Data_fim AS DATE) AND 
				Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)."");

			if(empty($query->row_array()))
			{
				$query = $this->db->query("
					SELECT * FROM Etapa  
					WHERE CAST(NOW() AS DATE) >= CAST(Data_abertura AS DATE) AND CAST(NOW() AS DATE) <= CAST(Data_fechamento AS DATE) AND 
					Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)."");
				
				if(empty($query->row_array()))
				{
					$query = $this->db->query("
						SELECT * FROM Etapa 
						WHERE Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." ORDER BY Data_inicio LIMIT 1");
				}
			}
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
		/*!
			RESPONSÁVEL POR IDENTIFICAR A SUBTURMA PADRÃO QUANDO O PROFESSOR ACESSA O PORTAL, COM BASE NO DIA E HORÁRIO.
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
					WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND dt.Turma_id = ".$this->db->escape($turma_id)." AND 
					h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') AND 
				 	dh.Ativo = 1 AND 
				 	TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') >= TIME_FORMAT(h.Inicio, '%H:%i') AND 
					TIME_FORMAT(CAST(NOW() AS TIME), '%H:%i') <= TIME_FORMAT(h.Fim, '%H:%i') 
				 	GROUP BY dh.Horario_id) AS x GROUP BY x.Sub_turma 
			");
			if(empty($query->row_array()))
			{
				return null;//sem subturma para o horario corrente
			}
			$query->row_array()['Sub_turma'];
			/*{echo "string";
				$subturma = $this->get_sub_turmas($disciplina_id, $turma_id, date('Y-m-d'));//degbugar aqui
				$subturma = (empty($subturma) ? 0 : $subturma[0]['Sub_turma']);
				return $subturma;
			}
			else 
				return $query->row_array()['Sub_turma'];*/
		}
		//MÉTODOS DAQUI PRA BAIXO TALVEZ SEJAM MIGRADOS PARA O NOTAS_MODEL
		/*!
			RESPONSÁVEL POR RETORNAR TODAS AS COLUNAS DE NOTAS EXISTENTES PARA UMA DETERMINADA DISCIPLINA EM UMA DETERMINADA TURMA DE UM DETERMINADO BIMESTRE
		*/
		public function get_colunas_nota($disciplina_id, $turma_id, $etapa_id)
		{
			$query = $this->db->query("
				SELECT dn.Descricao, dn.Id AS Descricao_nota_id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Notas n ON m.Id = n.Matricula_id 
				INNER JOIN Descricao_nota dn ON dn.Id = n.Descricao_nota_id 

				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." AND 
				n.etapa_id = ".$this->db->escape($etapa_id)." 
				GROUP BY dn.Descricao, dn.Id ORDER BY n.Id");

			return $query->result_array();
		}
		/*!
			RESPONSÁVEL POR RETORNAR TODOS OS ALUNOS MATRICULADOS EM UMA DETERMINADA DISCIPLINA DE UMA DETERMINADA TURMA.
		*/
		/*public function get_alunos($disciplina_id, $turma_id)
		{
			$query = $this->db->query("
				SELECT u.Nome AS Nome_aluno, m.Id AS Matricula_id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)."");

			return $query->result_array();
		}*/
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE HORÁRIOS QUE O PROFESSOR DA AULA EM UMA DETERMINADA DISCIPLINA DE
		*	UMA DETERMINADA TURMA NUMA DETERMINADA DATA.
		*
		*	$disciplina_id -> Id da disciplina que se deseja obter os horários.
		*	$turma_id -> Id da turma que se deseja obter o horário.
		* 	$professor_id -> Id do professor (somente para certificar-se de que irá voltar os horários das disciplinas 
		*	que dizem respeito ao mesmo).
		*/
		public function get_horarios_professor($disciplina_id, $turma_id, $professor_id, $subturma, $data, $ativo)
		{
			
			//OS CASES SÃO UTILIZADOS PARA DETECTAR MUDANÇAS NO HORÁRIO, OU SEJA, SE HOUVER UM HORÁRIO DIFERENTE PARA A DATA EM QUESTÃO
			//NA TABELA DE CALENDEARIO_PRESENCA, ENTAO CONSIDERA O QUE ESTÁ LÁ
			$query = $this->db->query("
				SELECT  
					CONCAT(x.Dia, ' / ', x.Inicio, ' - ', x.Fim) AS Horario,
					x.Aula ,
					x.Horario_id
					, x.Disc_hor_id 
				FROM( 
					SELECT 
					CASE 
				    	WHEN h.Dia = 1 THEN 'Segunda' 
				    	WHEN h.Dia = 2 THEN 'Terça' 
				        WHEN h.Dia = 3 THEN 'Quarta' 
				        WHEN h.Dia = 4 THEN 'Quinta' 
				        WHEN h.Dia = 5 THEN 'Sexta' 
				        WHEN h.Dia = 6 THEN 'Sábado' 
				        WHEN h.Dia = 7 THEN 'Domingo' 
				    END AS Dia, 
				    TIME_FORMAT(h.Inicio, '%H:%i') AS Inicio, TIME_FORMAT(h.Fim, '%H:%i') AS Fim, 
				    h.Aula, h.Id AS Horario_id, m.Id AS Matricula_id, dh.Id AS Disc_hor_id    
				    FROM Disc_turma dt 
				    INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				    INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				    INNER JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
				    INNER JOIN Horario h ON h.Id = dh.Horario_id 
				    WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND 
				    dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				    dt.Professor_Id = ".$this->db->escape($professor_id)." AND 
				    (dh.Sub_turma = ".$this->db->escape($subturma)." OR dh.Sub_turma = 0) AND  
				    dh.Ativo = ".$this->db->escape($ativo)." AND 
				    h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') 
				    GROUP BY h.Dia, h.Inicio, h.Fim 
			    ) AS x");
			
			return $query->result_array();
		}
		/*!
			RESPONSÁVEL POR RETORNAR TODOS OS ALUNOS MATRICULADOS EM UMA DETERMINADA DISCIPLINA DE UMA 
			DETERMINADA TURMA, DE ACORDO COM O HORÁRIO (PARA IDENTIFICAR A SUBTURMA).
		*/
		public function get_alunos($disciplina_id, $turma_id, $sub_turma = FALSE)  //nome anterior get_alunos_chamada
		{
			if($sub_turma !== FALSE)
				$sub_turma = "AND dh.Sub_turma = ".$this->db->escape($sub_turma);
			else 
				$sub_turma = "";
			$query = $this->db->query("
				SELECT u.Nome AS Nome_aluno, m.Id AS Matricula_id, 
				dh.Sub_turma, a.Id AS Aluno_id
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id  
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
				LEFT JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id AND (m.Sub_turma = dh.Sub_turma OR dh.Sub_turma = 0)
				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." ".$sub_turma." GROUP BY #dh.Sub_turma, 
				m.Id");
			
				return $query->result_array();
		}
		/*!
			RESPONSÁVEL POR IDENTIFICAR A QUANTIDADE DE SUBTURMAS E RETORNAR CADA SUB_TURMA QUANDO EXISTIR.
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