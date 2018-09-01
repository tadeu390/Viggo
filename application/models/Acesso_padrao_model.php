<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS PERMISSÕES PADRÕES POR CADA GRUPO EM CADA MÓDULO DO SISTEMA.
	*/
	class Acesso_padrao_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR TRAZR UMA LISTA QUE CONTÉM AS PERMISSÕES PADRÕES DE CADA MÓDULO DO SISTEMA POR GRUPO.
		*
		*	$id -> Id do grupo que se quer buscar as permissões.
		*/
		public function get_acesso_padrao($id = FALSE)
		{
			$query = $this->db->query("
				SELECT *, 
				(SELECT ap.Id FROM Acesso_padrao ap WHERE ap.Grupo_id = ".$this->db->escape($id)." AND ap.Modulo_id = x.Modulo_id) as Acesso_padrao_id,
			    (SELECT ap.Criar FROM Acesso_padrao ap WHERE ap.Grupo_id = ".$this->db->escape($id)." AND ap.Modulo_id = x.Modulo_id) as Criar,
			    (SELECT ap.Ler FROM Acesso_padrao ap WHERE ap.Grupo_id = ".$this->db->escape($id)." AND ap.Modulo_id = x.Modulo_id) as Ler,
			    (SELECT ap.Atualizar FROM Acesso_padrao ap WHERE ap.Grupo_id = ".$this->db->escape($id)." AND ap.Modulo_id = x.Modulo_id) as Atualizar,
			    (SELECT ap.Remover FROM Acesso_padrao ap WHERE ap.Grupo_id = ".$this->db->escape($id)." AND ap.Modulo_id = x.Modulo_id) as Remover
				FROM(
				    SELECT m.Id AS Modulo_id, m.Nome as Nome_modulo
				    from Modulo m 
				    GROUP BY m.Id, m.Nome ORDER BY m.Id
				) as x");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/EDITAR PERMISSÇOES POR GRUPO.
		*
		*	$data -> Contém os dados de permissões do grupo em questão de cada módulo.
		*/
		public function set_acesso_padrao($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Acesso_padrao',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Acesso_padrao', $data);
			}
		}
	}
?>