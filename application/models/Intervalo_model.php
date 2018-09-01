<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DOS INTERVALOS DOS HORÁRIOS.
	*/
	class Intervalo_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE INTERVALOS OU UM INTERVALO ESPECÍFICO.
		*
		*	$Periodo_letivo_id -> Para retornar os intervalos de um determinado periodo letivo.
		*/
		public function get_intervalo($Periodo_letivo_id = FALSE)
		{
			$query = $this->db->query("
				SELECT i.Id, i.Dia, i.Hora_inicio, i.Hora_fim, Periodo_letivo_id, 
				DATE_FORMAT(i.Data_registro, '%d/%m/%Y') as Data_registro, i.Ativo   
				FROM Intervalo i
				WHERE i.Periodo_letivo_id = ".$this->db->escape($Periodo_letivo_id)." ORDER BY Dia");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR OS INTERVALOS DE UMA DETERMINADA REGRA LETIVA.
		*
		*	$data-> Contém os intervalos a serem cadastrados/atualizados.
		*	$Periodo_letivo_id -> Contém a id do período letivo.
		*/
		public function set_intervalo($data, $Periodo_letivo_id)
		{
			$intervalos_banco = $this->get_intervalo($Periodo_letivo_id);

			for($i = 0; $i < count($data); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($intervalos_banco); $j++)
				{
					if($intervalos_banco[$j]['Dia'] == $data[$i]['Dia'] &&
						$intervalos_banco[$j]['Hora_inicio'] == $data[$i]['Hora_inicio'] &&
						$intervalos_banco[$j]['Hora_fim'] == $data[$i]['Hora_fim'] && 
						$intervalos_banco[$j]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
				{
					$data[$i]['Periodo_letivo_id'] = $Periodo_letivo_id;
					$this->db->insert('Intervalo',$data[$i]);
				}
			}

			for($i = 0; $i < count($intervalos_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($data); $j++)
				{
					if($data[$j]['Dia'] == $intervalos_banco[$i]['Dia'] &&
						$data[$j]['Hora_inicio'] == $intervalos_banco[$i]['Hora_inicio'] &&
						$data[$j]['Hora_fim'] == $intervalos_banco[$i]['Hora_fim'] && 
						$Periodo_letivo_id == $intervalos_banco[$i]['Periodo_letivo_id'])
						$flag = 1;
				}
				if($flag == 0)
					$this->delete_intervalo($intervalos_banco[$i]['Id']);
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR UM INTERVALO DO BANCO DE DADOS.
		*
		*	$id -> Id do intervalo a ser apagado.
		*/
		public function delete_intervalo($id)
		{
			return $this->db->query("
				DELETE FROM Intervalo WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A QUANTIDADE DE INTERVALOS EXISTENTES EM CADA 
		*	DIA.
		*
		*	$periodo_letivo_id -> Id do período letivo que se deseja obter os intervalos.
		*/
		public function get_qtd_intervalo_dia($periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT 
				SUM(CASE WHEN x.Dia = 1 THEN x.Qtd ELSE 0 END) as Segunda, 
				SUM(CASE WHEN x.Dia = 2 THEN x.Qtd ELSE 0 END) as Terca,
				SUM(CASE WHEN x.Dia = 3 THEN x.Qtd ELSE 0 END) as Quarta, 
				SUM(CASE WHEN x.Dia = 4 THEN x.Qtd ELSE 0 END) as Quinta,
				SUM(CASE WHEN x.Dia = 5 THEN x.Qtd ELSE 0 END) as Sexta,
				SUM(CASE WHEN x.Dia = 6 THEN x.Qtd ELSE 0 END) as Sabado,
				SUM(CASE WHEN x.Dia = 7 THEN x.Qtd ELSE 0 END) as Domingo
				FROM 
				(SELECT COUNT(*) AS Qtd, Dia FROM Intervalo i 
					WHERE i.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
					GROUP BY Dia 
					ORDER BY i.Dia ) AS x ");

			return $query->row_array();
		}
	}
?>