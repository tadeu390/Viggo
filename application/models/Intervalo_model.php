<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DOS INTERVALOS DOS HORÁRIOS.
	*/
	class Intervalo_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS MÉTODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE INTERVALOS OU UM INTERVALO ESPECÍFICO.
		*
		*	$Id -> Quando passado algum valor inteiro, retorna um intervalo caso a mesmo exista no banco de dados.
		*/
		public function get_intervalo($id = FALSE)
		{
			if ($id === FALSE)//retorna todos se nao passar o parametro
			{
				$query = $this->db->query("
					SELECT i.Id, i.Dia, i.Hora_inicio, i.Hora_fim, Periodo_letivo_id,  
					DATE_FORMAT(i.Data_registro, '%d/%m/%Y') as Data_registro, i.Ativo   
					FROM Intervalo i");

				return $query->result_array();
			}

			$query = $this->db->query("
					SELECT i.Id, i.Dia, i.Hora_inicio, i.Hora_fim, Periodo_letivo_id,  
					DATE_FORMAT(i.Data_registro, '%d/%m/%Y') as Data_registro, i.Ativo   
					FROM Intervalo i
					WHERE i.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
	}
?>