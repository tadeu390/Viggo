<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS HORÁRIOS DAS TURMAS.
	*/
	class Horario_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE TURMAS COM HORÁRIOS OU UM TURMA COM HORÁRIO ESPECÍFICO.
		*	
		*	$Ativo -> Quando passado "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de um horário específico.
		*	$page-> Número da página de registros que se quer carregar.
		*/
		public function get_horario($horario = FALSE)
		{
			if($horario === false)
			{
				
				//return $query->result_array();
			}

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
				SELECT t.Id AS Turma_id, dt.Id AS Disc_turma_id, dh.Sub_turma, h.Dia, h.Aula, h.Inicio, h.Fim, dh.Id AS Disc_hor_id, dh.Horario_id FROM Turma t 
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
					'Sub_turma' => $disc_hor[$i]['Sub_turma']
				);

				$disc_hor_banco = $this->get_disc_hor($disc_hor_consulta);
				//dar insert somente nos que são novos
				if(empty($disc_hor_banco))
					$this->db->insert('Disc_hor',$disc_hor_consulta);
				else
				{
					$this->db->where('Id', $disc_hor_banco['Disc_hor_id']);
					$this->db->update('Disc_hor',$disc_hor_consulta);//$disc_hor_consulta pois o disc_hor_banco contém o id do Disc_hor escrito "Disc_hor_id" em vez de Id
				}
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR  DO BANDO UMA DISC_TURMA ASSOCIADO A UM DETERMINADO HORÁRIO.
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
					$this->db->where('Id', $disc_hor_banco[$i]['Disc_hor_id']);
					$this->db->delete('Disc_hor');
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

	}
?>