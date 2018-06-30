<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES GENÉRICAS DO SISTEMA NO BANCO DE DADOS.
	*/
	class Geral_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR DESCOBRIR QUAL MENU, O MÓDULO QUE ESTÁ EM USO PERTENCE, ISSO É NECESSÁRIO 
   		*	PARA DEIXAR ABERTO APENAS O MENU DESTE MÓDULO NA TELA.
		*
   		*	$modulo -> nome do modulo do sistema.
		*/
		public function get_identificador_menu($modulo)
		{
			$query = $this->db->query("SELECT Menu_id FROM Modulo WHERE Url LIKE ".$this->db->escape($modulo."%")."");
			$result = $query->row_array();
				
			return $result['Menu_id'];
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR PARA UM DETERMINADO TIPO DE PERMISSÃO EM UM DETERMINADO MÓDULO SE A PERMISSÃO EXISTE OU NÃO.
		*
		*	$type -> Tipo de permissão (criar, remover, ler, atualizar).
		*	$modulo -> Nome do modulo do sistema.
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