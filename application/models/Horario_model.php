<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS HORÁRIOS DAS TURMAS.
	*/
	class Horario_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UM DETERMINADO HORÁRIO CASO EXISTA.
		*	
		*	$horario -> Array contendo as informações de um horário como, dia, hora de início e hora de fim.
		*/
		public function get_horario($horario = FALSE)
		{
			$query = $this->db->query("
				SELECT Id, Dia, Aula, Inicio, Fim 
				FROM Horario  
				WHERE Dia = ".$this->db->escape($horario['Dia'])." AND 
				Aula = ".$this->db->escape($horario['Aula'])." AND 
				Inicio = ".$this->db->escape($horario['Inicio'])." AND 
				Fim = ".$this->db->escape($horario['Fim'])." 
			");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE HORÁRIOS DE CADA DISCIPLINA DE UMA DETERMINADA TURMA.
		*
		*	$id -> Id da turma a buscar os horários de cada disciplina.
		*/
		public function get_disc_hor_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT t.Id AS Turma_id, dt.Id AS Disc_turma_id, dh.Sub_turma, h.Dia, 
				h.Aula, h.Inicio, h.Fim, dh.Id AS Disc_hor_id, dh.Horario_id, dh.Ativo  
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
			    LEFT JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id 
			    LEFT JOIN Horario h on dh.Horario_id = h.Id 
				WHERE t.Id = ".$this->db->escape($turma_id)."");

			return $query->result_array();
		}

		public function set_horario($horario)
		{
			for($i = 0; $i < COUNT($horario); $i++)
			{
				//se nao existir, então grava o horário
				if(empty($this->get_horario($horario[$i])))
					$this->db->insert('Horario',$horario[$i]);
			}
		}
		public function set_disc_hor($disc_hor)
		{
			/*for($i = 0; $i < COUNT($disc_hor); $i++)
			{
				print_r($disc_hor[$i]);
				echo "<br />";
			}
			echo "<br />";
			echo "<br />";*/
			for($i = 0; $i < COUNT($disc_hor); $i++)
			{
				//primeiro montar o array de cada horário
				$horario = array(
					'Dia' => $disc_hor[$i]['Dia'],
					'Aula' => $disc_hor[$i]['Aula'],
					'Inicio' => $disc_hor[$i]['Inicio'],
					'Fim' => $disc_hor[$i]['Fim']
				);

				//montar o array do disc_hor
				$disc_hor_consulta = array(
					'Horario_id' => $this->get_horario($horario)['Id'],
					'Disc_turma_id' => $disc_hor[$i]['Disc_turma_id'],
					'Sub_turma' => $disc_hor[$i]['Sub_turma'],
					'Ativo' => 1
				);

				$disc_hor_banco = $this->get_disc_hor($disc_hor_consulta);
				//dar insert somente nos que são novos
				if(empty($disc_hor_banco))
				{
					$this->db->insert('Disc_hor',$disc_hor_consulta);
					//echo "insert ";
				}
				else
				{
					//echo "update";
					$this->db->where('Id', $disc_hor_banco['Disc_hor_id']);
					$this->db->update('Disc_hor',$disc_hor_consulta);//$disc_hor_consulta pois o disc_hor_banco contém o id do Disc_hor escrito "Disc_hor_id" em vez de Id
				}
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR  DO BANCO UMA DISC_TURMA ASSOCIADO A UM DETERMINADO HORÁRIO.
		*
		*	$lista_disc_hor -> Contém os horários atualizados que originaram do formulário submetido pelo usuário.
		*	$turma_id -> Contém o id da turma que está com o horário em edição.
		*/
		public function delete_disc_hor($lista_disc_hor, $turma_id)
		{
			$disc_hor_banco = $this->get_disc_hor_turma($turma_id);

			for($i = 0; $i < COUNT($disc_hor_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < COUNT($lista_disc_hor); $j++)
				{
					//primeiro montar o array de cada horário
					$horario = array(
						'Dia' => $lista_disc_hor[$j]['Dia'],
						'Aula' => $lista_disc_hor[$j]['Aula'],
						'Inicio' => $lista_disc_hor[$j]['Inicio'],
						'Fim' => $lista_disc_hor[$j]['Fim']
					);

					$disc_hor = array(
						'Horario_id' => $this->get_horario($horario)['Id'],
						'Disc_turma_id' => $lista_disc_hor[$j]['Disc_turma_id'],
						'Sub_turma' => $lista_disc_hor[$j]['Sub_turma']
					);

					if($disc_hor_banco[$i]['Horario_id'] == $disc_hor['Horario_id'] && 
					  $disc_hor_banco[$i]['Disc_turma_id'] == $disc_hor['Disc_turma_id'] &&
					  $disc_hor_banco[$i]['Sub_turma'] == $disc_hor['Sub_turma'] 
					)
					$flag = 1;
				}
				if($flag == 0)
				{
					//$this->db->delete('Disc_hor');
					$up = array(
						//'Horario_id' => $disc_hor_banco[$j]['Horario_id'],
						//'Disc_turma_id' => $disc_hor_banco[$j]['Disc_turma_id'],
						//'Sub_turma' => $disc_hor_banco[$j]['Sub_turma'],
						'Ativo' => 0
					);
					$this->db->where('Id', $disc_hor_banco[$i]['Disc_hor_id']);
					
					$this->db->update('Disc_hor', $up);
				}
			}
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM DETERMINADO DISC_HOR JÁ EXISTE NO BANCO.
		*	
		*	$disc_hor -> Disc_hor a ser procurado no banco de dados.
		*/
		public function get_disc_hor($disc_hor)
		{
			$query = $this->db->query("
				SELECT Id AS  Disc_hor_id, Horario_id, Disc_turma_id, Sub_turma 
				FROM Disc_hor 
				WHERE Horario_id = ".$this->db->escape($disc_hor['Horario_id'])." AND 
				Sub_turma = ".$this->db->escape($disc_hor['Sub_turma'])." AND 
				Disc_turma_id = ".$this->db->escape($disc_hor['Disc_turma_id'])." 
			");

			return $query->row_array();
		}
		public function get_horarios_disciplina($disciplina_id, $turma_id, $subturma, $data, $ativo)
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
				    (dh.Sub_turma = ".$this->db->escape($subturma)." OR dh.Sub_turma = 0) AND  
				    dh.Ativo = ".$this->db->escape($ativo)." AND 
				    h.Dia = DATE_FORMAT(".$this->db->escape($data).", '%w') 
				    GROUP BY h.Dia, h.Inicio, h.Fim 
			    ) AS x");
			
			return $query->result_array();
		}
	}
?>