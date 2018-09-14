<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS NOTAS ESPECIAIS QUE SÃO NOTAS 
		DE RECUPERAÇÃO, ESTUDOS INDEPENDENTES, ETC.
	*/
	class Nota_especial_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE NOTAS ESPECIAIS OU UMA NOTA ESPECIAL ESPECÍFICA.
		*
		*	$Periodo_letivo_id -> Para retornar as notas especiais de um determinado periodo letivo.
		*/
		public function get_nota_especial($Periodo_letivo_id = FALSE, $id = FALSE)
		{
			if($id === FALSE)
			{
				$query = $this->db->query("
						SELECT Id, Ativo, Nome, Valor, Media,
						DATE_FORMAT(Data_abertura, '%d/%m/%Y') as Data_abertura, 
						DATE_FORMAT(Data_fechamento, '%d/%m/%Y') as Data_fechamento, Periodo_letivo_id,
						CAST(Data_abertura AS DATE) as Data_abertura2, # somente para comparar ao criar uma ne
						CAST(Data_fechamento AS DATE) as Data_fechamento2 #somente para comparar ao criar uma ne
						FROM Nota_especial  
						WHERE Periodo_letivo_id = ".$this->db->escape($Periodo_letivo_id)." ORDER BY Data_abertura DESC");

				return $query->result_array();
			}

			$query = $this->db->query("
						SELECT Id, Ativo, Nome, Valor, Media, 
						DATE_FORMAT(Data_abertura, '%d/%m/%Y') as Data_abertura, 
						DATE_FORMAT(Data_fechamento, '%d/%m/%Y') as Data_fechamento, Periodo_letivo_id 
						FROM Nota_especial  
						WHERE Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR OS DADOS DE UMA NOTA ESPECIAL NO BANCO DE DADOS.
		*
		*	$data-> Contém os dados da nota especial a ser cadastrada/atualizada.
		*	$Periodo_letivo_id -> Contém a id do período letivo.
		*/
		public function set_nota_especial($data, $Periodo_letivo_id)
		{
			$notas_especiais_banco = $this->get_nota_especial($Periodo_letivo_id);

			for($i = 0; $i < count($data); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($notas_especiais_banco); $j++)
				{
					if($notas_especiais_banco[$j]['Nome'] == $data[$i]['Nome'] &&
						$notas_especiais_banco[$j]['Valor'] == $data[$i]['Valor'] &&
						$notas_especiais_banco[$j]['Data_abertura2'] == $data[$i]['Data_abertura'] && 
						$notas_especiais_banco[$j]['Data_fechamento2'] == $data[$i]['Data_fechamento'] && 
						$notas_especiais_banco[$j]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
				{
					$data[$i]['Periodo_letivo_id'] = $Periodo_letivo_id;
					$this->db->insert('Nota_especial',$data[$i]);
				}
			}
			//print_r($data);
			for($i = 0; $i < count($notas_especiais_banco); $i++)
			{
				$flag = 0;
				for($j = 0; $j < count($data); $j++)
				{
					if($notas_especiais_banco[$i]['Nome'] == $data[$j]['Nome'] &&
						$notas_especiais_banco[$i]['Valor'] == $data[$j]['Valor'] &&
						$notas_especiais_banco[$i]['Data_abertura2'] == $data[$j]['Data_abertura'] && 
						$notas_especiais_banco[$i]['Data_fechamento2'] == $data[$j]['Data_fechamento'] && 
						$notas_especiais_banco[$i]['Periodo_letivo_id'] == $Periodo_letivo_id)
						$flag = 1;
				}
				if($flag == 0)
					$this->delete_nota_especial($notas_especiais_banco[$i]['Id']);
			}
		}
		/*!
		*	RESPONSÁVEL POR APAGAR UMA NOTA ESPECIAL DO BANCO DE DADOS.
		*
		*	$id -> Id da nota especial a ser apagada.
		*/
		public function delete_nota_especial($id)
		{
			return $this->db->query("
				DELETE FROM Nota_especial WHERE Id = ".$this->db->escape($id)."");
		}
	}
?>
