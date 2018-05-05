<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES GENÉRICAS DO SISTEMA NO BANCO DE DADOS
	*/
	class Geral_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR DESCOBRIR QUAL MENU O MÓDULO A SER CARREGADO PERTENCE, ISSO É NECESSÁRIO 
   			PARA DEIXAR ABERTO O MENU NA TELA.

   			$modulo -> nome do modulo do sistema
		*/
		public function get_identificador_menu($modulo)
		{
			$query = $this->db->query("SELECT Menu_id FROM Modulo WHERE Url LIKE ".$this->db->escape($modulo."%")."");
			$result = $query->row_array();
				
			return $result['Menu_id'];
		}
		/*
			RESPONSÁVEL POR VERIFICAR O TIPO DE PERMISSÃO DE UM USUÁRIO LOGADO A UM DETERMINADO MÓDULO DO SISTEMA

			$type -> tipo de permissão (criar, remover, ler, atualizar)
			$modulo -> nome do modulo do sistema
		*/
		public function get_permissao($type, $modulo)
		{
			$CI = get_instance();
			$CI->load->model("Account_model");

			$query = $this->db->query("
				SELECT a.$type 
					FROM Acesso a  
						INNER JOIN Modulo m ON a.Modulo_id = m.Id 
				WHERE a.Usuario_Id = ".$CI->Account_model->session_is_valid()['id']."  
				AND m.Url LIKE ".$this->db->escape($modulo."%")."");
			$result = $query->row_array(); 
			
			if(!empty($result))
				return $result["$type"] == 1;
			return false;
		}
	}
?>