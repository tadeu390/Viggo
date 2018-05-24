<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE A SENHA DO USUÁRIO. 
		PODE CADASTRAR UMA SENHA NOVA, CONSULTAR HISTÓRICO DE SENHAS, ETC.
	*/
	class Senha_model extends CI_Model 
	{
		/*CAREGA O DRIVE DO BANCO DE DADOS*/
		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR RETORNAR UMA SENHA DE ACORDO COM UM ID DE USUARIO OU RETORNA UMA LISTA DE SENHA
		 	SE NÃO FOR PASSADO ARGUMENTO NA CHAMADA DO MÉTODO

		 	$Usuario_id -> id de um usuário
		 	$All -> especifica se é pra voltar todas as senha de um determinado usuario ou simplesmente a última senha ativa
		 */
		public function get_senha($Usuario_id = FALSE, $All = FALSE)
		{
			if($All === FALSE)
			{
				$query = $this->db->query("SELECT * FROM Senha WHERE Usuario_id = ".$this->db->escape($Usuario_id)."");
				
				$data = $query->result_array();
				$data['rows'] = $query->num_rows();
				
				return $data;
			}
			
			$query = $this->db->query("
				SELECT * FROM Senha 
				WHERE Usuario_id = ".$this->db->escape($Usuario_id)." AND Ativo = 1");
			return $query->row_array();
		}
		/*
			REPONSÁVEL POR CADASTRAR UMA NOVA SENHA PARA O USUÁRIO

			$data -> Contem os dados da senha
		*/
		public function set_senha($data)
		{
			$this->desativar_senha($data['Usuario_id']);
			return $this->db->insert('Senha',$data);
		}
		/*
			RESPONSÁVEL POR DESABILITAR A SENHA DE UM USUÁRIO

			$Usuario_id -> identificador do usuário
		*/
		public function desativar_senha($Usuario_id)
		{
			$this->db->query("
				UPDATE Senha SET Ativo = 0 WHERE Usuario_id = ".$this->db->escape($Usuario_id)."");
		}
	}
?>