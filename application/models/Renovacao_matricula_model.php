<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS RENOVAÇÕES DE MATRICULAS DO SISTEMA.
	*/
	class Renovacao_matricula_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE RENOVAÇÕES DE MATRICULAS OU UMA RENOVAÇÃO ESPECÍFICA.
		*	
		*	$Ativo -> Quando passado "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Id de uma renovação específica.
		*	$page-> Número da página de registros que se quer carregar.
		*/
		public function get_renovacao_matricula($Ativo = FALSE, $id = false)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if($id === false)
			{	
				$query = $this->db->query("
					SELECT rm.ID AS Id, rm.Inscricao_id AS Inscricao_id, rm.Periodo_letivo_id AS Periodo_letivo_id 
					FROM Renovacao_matricula rm");
				
				return $query->result_array();
			}

			$query = $this->db->query("
				SELECT rm.ID AS Id, rm.Inscricao_id AS Inscricao_id, rm.Periodo_letivo_id AS Periodo_letivo_id 
					FROM Renovacao_matricula rm
				WHERE rm.Id = ".$this->db->escape($id)." ".$Ativos."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UMA RENOVAÇÃO DE MATRÍCULA DO BANCO DE DADOS.
		*
		*	$id -> Id da renovação a ser "apagada".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Inscricao SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA RENOVAÇÃO DE MATRÍCULA NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da renovação.
		*/
		public function set_renovacao_matricula($data)
		{
			if(empty($data['Id']))
				return $this->db->insert('Renovacao_matricula',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Renovacao_matricula', $data);
			}
		}
	}
?>