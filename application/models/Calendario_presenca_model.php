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
		*	RESPONSÁVEL POR RETORNAR A PRESENÇA DE UM DETERMINADO ALUNO EM UMA DETERMINADA DISCIPLINA.
		*
		*	$matricula_id -> Matrícula do aluno na disciplina.
		*	$subturma -> Id da subturma da turma.
		*	$data -> Data de que se precisar obter as presênças/faltas do aluno.
		*/
		public function get_presenca_aluno($matricula_id, $sub_turma, $data)
		{
			$query = $this->db->query("
				SELECT cp.Id AS Calendario_presenca_id, cp.Presenca, cp.Justificativa, h.Id AS Horario_id 
					FROM Disc_turma dt 
					INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
					INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
					INNER JOIN Calendario_presenca cp ON m.Id = cp.Matricula_id 
					INNER JOIN Horario h ON cp.Horario_id = h.Id 
					WHERE CAST(cp.Data_registro AS DATE) = DATE_FORMAT(".$this->db->escape($data).",'%Y-%m-%d') AND 
					m.Id = ".$this->db->escape($matricula_id)."
			");
			
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR NO BANCO DE DADOS A PRESENÇA/FALTA POR ALUNO.
		*	
		*	$data -> Contém os dados fe presênç/fata de cada aluno.
		*/
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
		/*!
		*	RESPONSÁVEL POR RETORNAR AS FALTAS DE UM DETERMINADO ALUNO POR MÊS (utilizado para contabilizar as faltas por mês).
		*
		*	$matricula_id -> Matricula do aluno na disciplina.
		* 	$data_inicio -> Data de iniício da etapa. Garante pegar no mês somente as faltas referente a etapa em questão.
		* 	$data_fim -> Data de fim da etapa. Garante pegar no mês somente as faltas referente a etapa em questão.
		*	*mes -> Mês em que se deseja obter as faltas.
		*/
		public function get_faltas($matricula_id, $data_inicio, $data_fim, $mes)
		{
			$query = $this->db->query("	
				SELECT COUNT(cp.Presenca) AS Faltas FROM Calendario_presenca cp 
				WHERE MONTH(cp.Data_registro) = ".$this->db->escape($mes)." AND 
				cp.Data_registro >= ".$this->db->escape($data_inicio)." AND 
				cp.Data_registro <= ".$this->db->escape($data_fim)." AND 
				Presenca = 0 AND cp.Matricula_id = ".$this->db->escape($matricula_id)."
			");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODAS AS FALTAS DO ALUNO EM UMA ETAPA PARA UMA DETERMINADA 
		*	DISCIPLINA.
		*
		*	$data_inicio -> Data de início da etapa.
		*	$data_fim -> Data de término da etapa.
		*	$matricula_id -> Matrícula do aluno na disciplina.
		*/
		public function get_faltas_etapa($data_inicio, $data_fim, $matricula_id)
		{
			$query = $this->db->query("	
				SELECT COUNT(cp.Presenca) AS Faltas FROM Calendario_presenca cp 
				WHERE Presenca = 0 AND cp.Matricula_id = ".$this->db->escape($matricula_id)." AND 
				cp.Data_registro >= ".$this->db->escape($data_inicio)." AND 
				cp.Data_registro <= ".$this->db->escape($data_fim)."
			");

			return $query->row_array();		
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR OS MESES POR EXTENSO DE UM INTERVALO DE DATA.
		*
		*	$data_inicio -> Data de início do intervalo.
		*	$data_inicio -> Data de fim do intervalo.
		*/
		public function get_intervalo_mes($data_inicio, $data_fim)
		{
			$query = $this->db->query("
					SELECT MONTH(".$this->db->escape($data_inicio).") AS Mes_inicio,
					MONTH(".$this->db->escape($data_fim).") AS Mes_fim
				");

			$meses = array();

			for($i = $query->row_array()['Mes_inicio']; $i <= $query->row_array()['Mes_fim']; $i++)
			{
				$query2 = $this->db->query("
					SELECT mes_por_numero(".$i.") AS Mes, ".$i." AS Mes_numero 
				");

				array_push($meses, $query2->row_array());
			}
			return $meses;
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR DO BANCO A QUANTIDADE TOTAL DE FALTAS DE UM ALUNO EM TODAS AS DISCIPLINAS (utilizado para determinar se 
		*	o aluno estourou em faltas).
		*	
		*	$aluno_id -> Id do aluno que se quer saber a quantidade de faltas.
		*	$turma_id -> Id da turma do aluno para encontrar todas as disicplinas que o mesmo faz nela.
		*/
		public function get_total_faltas($aluno_id, $turma_id)
		{
			$query = $this->db->query("
					SELECT COUNT(cp.Presenca) AS Faltas FROM Disc_turma dt 
					INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
					INNER JOIN Calendario_presenca cp ON m.Id = cp.Matricula_id 
					INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
					INNER JOIN Aluno a ON i.Aluno_id = a.Id 
					WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND a.Id = ".$this->db->escape($aluno_id)." AND 
					cp.Presenca = 0
			");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM ALUNO ESTOUROU OU NÃO EM FALTAS. NESSE CASO É SOMADO TODAS AS FALTAS DE TODAS AS DISCIPLINAS.
		*
		*	$aluno_id -> Id do aluno.
		*	$turma_id -> Id da turma do aluno.
		*	$regras -> Regras do período letivo para se determinar o status.
		*	$tipo -> Se for etapa extra, simplesmente retorna aprovado, pois nessa situação não se contabiliza faltas.
		*/
		public function situacao_falta_aluno($aluno_id, $turma_id, $regras, $tipo)
		{
			if($tipo == ETAPA_EXTRA)//pois em etapas extras não se faz chamada em sala de aula.
				return APROVADO;
			else if($this->get_total_faltas($aluno_id, $turma_id)['Faltas'] <= ($regras['Limite_falta'] / 100) * ($regras['Dias_letivos'] * $regras['Quantidade_aula']))
				return APROVADO;
			else 
				return RECUPERACAO_FALTA;
		}
	}
?>