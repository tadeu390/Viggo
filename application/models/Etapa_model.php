<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS ETAPAS.
	*/
	class Etapa_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE ETAPAS OU UMA ETAPA ESPECÍFICA.
		*
		*	$Periodo_letivo_id -> Para retornar os etapas de um determinado periodo letivo.
		*/
		public function get_etapa($Periodo_letivo_id, $id, $tipo)
		{
			if($id === FALSE)
			{
				if($tipo !== FALSE)
					$tipo = " AND Tipo = ".$this->db->escape($tipo);

				$query = $this->db->query("
						SELECT Id, Ativo, Nome, Valor, Media, DATE_FORMAT(Data_inicio, '%d/%m/%Y') as Data_inicio, 
						DATE_FORMAT(Data_fim, '%d/%m/%Y') as Data_fim, 
						CAST(Data_inicio AS DATE) as Data_inicio2, #somente para comparar ao criar um etapa
						CAST(Data_fim AS DATE) as Data_fim2, #somente para comparar ao criar um etapa
						DATE_FORMAT(Data_abertura, '%d/%m/%Y') as Data_abertura, 
						DATE_FORMAT(Data_fechamento, '%d/%m/%Y') as Data_fechamento,
						CAST(Data_abertura AS DATE) as Data_abertura2, #somente para comparar ao criar um etapa
						CAST(Data_fechamento AS DATE) as Data_fechamento2, Periodo_letivo_id  #somente para comparar ao criar um etapa
						FROM Etapa 
						WHERE Periodo_letivo_id = ".$this->db->escape($Periodo_letivo_id)." ".$tipo." ORDER BY Id"); //arryumar a ordencao
				return $query->result_array();
			}

			$query = $this->db->query("
						SELECT Id, Ativo, Nome, Valor, Media, DATE_FORMAT(Data_inicio, '%d/%m/%Y') as Data_inicio, 
						DATE_FORMAT(Data_fim, '%d/%m/%Y') as Data_fim, 
						DATE_FORMAT(Data_abertura, '%d/%m/%Y') as Data_abertura, 
						DATE_FORMAT(Data_fechamento, '%d/%m/%Y') as Data_fechamento, Periodo_letivo_id 
						FROM Etapa 
						WHERE Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR OS DADOS DAS ETAPAS NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados do etapa a ser cadastrado/atualizado.
		*	$Periodo_letivo_id -> Contém a id do período letivo.
		*/
		public function set_etapa($data, $Periodo_letivo_id)
		{
			$etapas_banco = $this->get_etapa($Periodo_letivo_id, FALSE, ETAPA_NORMAL);
			
			for($i = 0; $i < count($data); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($etapas_banco); $j++)
				{
					if($etapas_banco[$j]['Nome'] == $data[$i]['Nome'] &&
						$etapas_banco[$j]['Valor'] == $data[$i]['Valor'] &&
						$etapas_banco[$j]['Data_inicio2'] == $data[$i]['Data_inicio'] && 
						$etapas_banco[$j]['Data_fim2'] == $data[$i]['Data_fim'] && 
						$etapas_banco[$j]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
				{
					$data[$i]['Periodo_letivo_id'] = $Periodo_letivo_id;
					$this->db->insert('Etapa',$data[$i]);
				}
			}
			//print_r($data);
			for($i = 0; $i < count($etapas_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($data); $j++)
				{
					if($etapas_banco[$i]['Nome'] == $data[$j]['Nome'] &&
						$etapas_banco[$i]['Valor'] == $data[$j]['Valor'] &&
						$etapas_banco[$i]['Data_inicio2'] == $data[$j]['Data_inicio'] && 
						$etapas_banco[$i]['Data_fim2'] == $data[$j]['Data_fim'] && 
						$etapas_banco[$i]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
					$this->delete_etapa($etapas_banco[$i]['Id']);
			}
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR OS DADOS DAS ETAPAS EXTRAS NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados da etapa extra a ser cadastrada/atualizada.
		*	$Periodo_letivo_id -> Contém a id do período letivo.
		*/
		public function set_etapa_extra($data, $Periodo_letivo_id)
		{
			$etapa_extra_banco = $this->get_etapa($Periodo_letivo_id, FALSE, ETAPA_EXTRA);

			for($i = 0; $i < count($data); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($etapa_extra_banco); $j++)
				{
					if($etapa_extra_banco[$j]['Nome'] == $data[$i]['Nome'] &&
						$etapa_extra_banco[$j]['Valor'] == $data[$i]['Valor'] &&
						$etapa_extra_banco[$j]['Data_abertura2'] == $data[$i]['Data_abertura'] && 
						$etapa_extra_banco[$j]['Data_fechamento2'] == $data[$i]['Data_fechamento'] && 
						$etapa_extra_banco[$j]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
				{
					$data[$i]['Periodo_letivo_id'] = $Periodo_letivo_id;
					$this->db->insert('Etapa',$data[$i]);
				}
			}
			//print_r($data);
			for($i = 0; $i < count($etapa_extra_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($data); $j++)
				{
					if($etapa_extra_banco[$i]['Nome'] == $data[$j]['Nome'] &&
						$etapa_extra_banco[$i]['Valor'] == $data[$j]['Valor'] &&
						$etapa_extra_banco[$i]['Data_abertura2'] == $data[$j]['Data_abertura'] && 
						$etapa_extra_banco[$i]['Data_fechamento2'] == $data[$j]['Data_fechamento'] && 
						$etapa_extra_banco[$i]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
					$this->delete_etapa($etapa_extra_banco[$i]['Id']);
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR UMA ETAPA DO BANCO DE DADOS.
		*
		*	$id -> Id da etapa a ser apagado.
		*/
		public function delete_etapa($id)
		{
			return $this->db->query("
				DELETE FROM Etapa WHERE Id = ".$this->db->escape($id)."");
		}
	}
?>