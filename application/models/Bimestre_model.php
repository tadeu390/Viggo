<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS BIMESTRES.
	*/
	class Bimestre_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE BIMESTRES OU UM BIMESTRE ESPECÍFICO.
		*
		*	$Periodo_letivo_id -> Para retornar os bimestres de um determinado periodo letivo.
		*/
		public function get_bimestre($Periodo_letivo_id = FALSE)
		{
			$query = $this->db->query("
					SELECT Id, Ativo, Nome, Valor, DATE_FORMAT(Data_inicio, '%d/%m/%Y') as Data_inicio, 
					DATE_FORMAT(Data_fim, '%d/%m/%Y') as Data_fim, 
					DATE_FORMAT(Data_abertura, '%d/%m/%Y') as Data_abertura, 
					DATE_FORMAT(Data_fechamento, '%d/%m/%Y') as Data_fechamento, Periodo_letivo_id 
					FROM Bimestre 
					WHERE Periodo_letivo_id = ".$this->db->escape($Periodo_letivo_id)."");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR OS DADOS DE UM BIMESTRE NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados do bimestre a ser cadastrado/atualizado.
		*	$Periodo_letivo_id -> Contém a id do período letivo.
		*/
		public function set_bimestre($data, $Periodo_letivo_id)
		{
			$bimestres_banco = $this->get_bimestre($Periodo_letivo_id);

			for($i = 0; $i < count($data); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($bimestres_banco); $j++)
				{
					if($bimestres_banco[$j]['Nome'] == $data[$i]['Nome'] &&
						$bimestres_banco[$j]['Valor'] == $data[$i]['Valor'] &&
						$bimestres_banco[$j]['Data_inicio'] == $data[$i]['Data_inicio'] && 
						$bimestres_banco[$j]['Data_fim'] == $data[$i]['Data_fim'] && 
						$bimestres_banco[$j]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
				{
					$data[$i]['Periodo_letivo_id'] = $Periodo_letivo_id;
					$this->db->insert('Bimestre',$data[$i]);
				}
			}
			//print_r($data);
			for($i = 0; $i < count($bimestres_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($data); $j++)
				{
					if($bimestres_banco[$i]['Nome'] == $data[$j]['Nome'] &&
						$bimestres_banco[$i]['Valor'] == $data[$j]['Valor'] &&
						$bimestres_banco[$i]['Data_inicio'] == $data[$j]['Data_inicio'] && 
						$bimestres_banco[$i]['Data_fim'] == $data[$j]['Data_fim'] && 
						$bimestres_banco[$i]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
					$this->delete_bimestre($bimestres_banco[$i]['Id']);
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR UM BIMESTRE DO BANCO DE DADOS.
		*
		*	$id -> Id do bimestre a ser apagado.
		*/
		public function delete_bimestre($id)
		{
			return $this->db->query("
				DELETE FROM Bimestre WHERE Id = ".$this->db->escape($id)."");
		}
	}
?>