<?php
	/*
		ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AOS ACESSOS DOS USUÁRIOS
	*/
	class Account_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		/*
			RESPONSÁVEL POR VALIDAR SE O USUÁRIO E SENHA É UMA CONTA VÁLIDA PARA PERMITIR ACESSO AO SISTEMA
			
			$email -> e-mail de usuário
			$senha -> senha de acesso do usuário
		*/
		public function valida_login($email, $senha)
		{
			$query = $this->db->query("
				SELECT u.Id, u.Grupo_id, u.Redefinir_senha, s.Valor, u.Nome, u.Email  
				FROM Usuario u INNER JOIN Senha s ON u.Id = s.Usuario_id 
				WHERE Email = ".$this->db->escape($email)." AND s.Valor = ".$this->db->escape($senha)." AND s.Ativo = 1");
			 
			 $data = $query->row_array();
			 $data['rows'] =  $query->num_rows();
			 return $data;
		}
		/*
			RESPONSÁVEL POR VERIFICAR SE EXISTE COOKIE OU SESSAO E VALIDA ESSES DADOS NO BANCO
		*/
		public function session_is_valid()
		{
			$id = "";
			$grupo_id = "";
		
			//verificar se existe uma sessao ou cookie
			if(!empty($this->session->id))
			{
				if(!empty($this->session->grupo_id))
				{
					$id = $this->session->id;
					$grupo_id = $this->session->grupo_id;
				}
			}
			else if(!empty($this->input->cookie('id')))
			{
				if(!empty($this->input->cookie('grupo_id')))
				{
					$id = $this->input->cookie('id');
					$grupo_id = $this->input->cookie('grupo_id');
				}
			}

			$sessao = "";

			if($id != "")
			{
				$query = $this->db->query("
					SELECT Id, Grupo_id 
						FROM Usuario 
					WHERE Id = ".$this->db->escape($id)." AND
					Grupo_id = ".$this->db->escape($grupo_id)."");

				if($query->num_rows() > 0)
				{
					$sessao = array(
						'status' => 'ok',
						'id' => $query->row_array()['Id'],
						'grupo_id' => $query->row_array()['Grupo_id']
					);
					return $sessao;
				}
				$sessao = array(
					'status' => 'invalido',
					'id' => '0',
					'grupo_id' => '0'
				);
				return $sessao;
			}

			$sessao = array(
				'status' => 'inexistente',
				'id' => '0',
				'grupo_id' => '0'
			);
			return $sessao;
		}
		/*
			RESPONSÁVEL POR GERAR E CADASTRAR NO BANCO DE DADOS O CÓDIGO GERADO.

			$id -> id do usuário
		*/
		public function gera_codigo_ativacao($Id)
		{
			$codigo = rand(100000,999999);
			$this->db->query("
				UPDATE Usuario SET Codigo_ativacao = ".$this->db->escape($codigo)." 
				WHERE Id = ".$this->db->escape($Id)."AND Redefinir_senha = 1");

		}
		/*
			RESPONSÁVEL POR DESATIVAR A REDEFINIÇÃO DE SENHA DO PRIMEIRO ACESSO, 
			ISSO EVITA QUE UMA SENHA SEJA TROCADA MAIS DE UMA VEZ PELA TELA DE PRIMEIRO ACESSO

			$id -> id do usuario
		*/
		public function desativa_redef_senha($id)
		{
			$this->db->query("
				UPDATE Usuario SET Redefinir_senha = 0, Codigo_ativacao = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
	}
?>