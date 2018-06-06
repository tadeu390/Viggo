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
				SELECT u.Id, u.Grupo_id, u.Status, u.Contador_tentativa, 
				s.Valor, u.Nome AS Nome_usuario, u.Email, u.Ativo, g.Ativo AS g_ativo, u.Codigo_ativacao  
				FROM Usuario u 
				INNER JOIN Senha s ON u.Id = s.Usuario_id 
				INNER JOIN Grupo g ON u.Grupo_id = g.Id 
				WHERE Email = ".$this->db->escape($email)."  
					AND s.Ativo = 1 AND (u.Contador_tentativa < ".LIMITE_TENTATIVA." OR (NOW() - Data_ultima_tentativa) >= ".TEMPO_ESPERA.")");
			 
			 $data = $query->row_array();
			 $data['rows'] =  $query->num_rows();
			 return $data;
		}
		/*
			RESPONSÁVEL POR VERIFICAR SE EXISTE COOKIE OU SESSAO E VALIDA ESSES DADOS NO BANCO E RETORNA OS DADOS DA SESSÃO OU DO COOKIE
		*/
		public function session_is_valid()
		{
			$id = "";
			$grupo_id = "";
			$token = "";
		
			//verificar se existe uma sessao ou cookie
			if(!empty($this->session->id))
			{
				if(!empty($this->session->grupo_id))
				{
					if(!empty($this->session->token))
					{
						$id = $this->session->id;
						$grupo_id = $this->session->grupo_id;
						$token = $this->session->token;
					}
				}
			}
			else if(!empty($this->input->cookie('id')))
			{
				if(!empty($this->input->cookie('grupo_id')))
				{
					if(!empty($this->input->cookie('token')))
					{
						$id = $this->input->cookie('id');
						$grupo_id = $this->input->cookie('grupo_id');
						$token = $this->input->cookie('token');
					}
				}
			}

			$sessao = "";

			if($id != "")
			{
				$query = $this->db->query("
					SELECT u.Id, u.Grupo_id, s.Valor  
						FROM Usuario u
						INNER JOIN Senha s ON u.Id = s.Usuario_id 
					WHERE u.Id = ".$this->db->escape($id)." AND u.Ativo = 1 AND 
					u.Grupo_id = ".$this->db->escape($grupo_id)." AND s.Valor = ".$this->db->escape($token)."");

				if($query->num_rows() > 0)
				{
					$sessao = array(
						'status' => 'ok',
						'id' => $query->row_array()['Id'],
						'grupo_id' => $query->row_array()['Grupo_id'],
						'token' => $query->row_array()['Valor']
					);
					return $sessao;
				}
				$sessao = array(
					'status' => 'invalido',
					'id' => '0',
					'grupo_id' => '0',
					'token' => '0'
				);
				return $sessao;
			}

			$sessao = array(
				'status' => 'inexistente',
				'id' => '0',
				'grupo_id' => '0',
				'token' => '0'
			);
			return $sessao;
		}
		/*
			RESPONSÁVEL POR VERIFICAR SE OS DADOS QUE ESTÃO NA SESSÃO QUANDO ESTÁ TROCANDO A SENHA SÃO VÁLIDOS,
			SEJA TROCANDO A SENHA NO PRIMEIRO ACESSO OU APENAS REDEFININDO A MESMA CASO O USUÁRIO ESQUEÇA.
		*/
		public function session_is_valid_troca_senha()
		{
			$sessao_troca_senha = array();

			if(!empty($this->session->id_troca_senha))
			{
				if(!empty($this->session->nome_troca_senha))
				{
					if(!empty($this->session->nome_troca_senha))
					{
						$id_troca_senha = $this->session->id_troca_senha;
						$nome_troca_senha = $this->session->nome_troca_senha;
						$email_troca_senha = $this->session->email_troca_senha;
						
						//validar no banco se existe isso
						$query = $this->db->query("
							SELECT Id FROM Usuario 
							WHERE Id = ".$this->db->escape($id_troca_senha)." AND 
							Nome = ".$this->db->escape($nome_troca_senha)." AND 
							Email = ".$this->db->escape($email_troca_senha)." AND (Status = 1 OR Status = 2)");
						
						if($query->num_rows() > 0)
						{
							$sessao_troca_senha = array(
								'id_troca_senha' => $id_troca_senha,
								'nome_troca_senha' => $nome_troca_senha,
								'email_troca_senha' => $email_troca_senha
							);
						}
						return $sessao_troca_senha;
					}
				}
			}
			return $sessao_troca_senha;
		}
		/*
			RESPONSÁVEL POR GERAR E CADASTRAR NO BANCO DE DADOS O CÓDIGO GERADO.

			$id -> id do usuário
		*/
		public function gera_codigo_ativacao($id)
		{
			$codigo = rand(100000,999999);
			$this->db->query("
				UPDATE Usuario SET Codigo_ativacao = ".$this->db->escape($codigo).", Contador_tentativa = 0 
				WHERE Id = ".$this->db->escape($id)."");
			return $codigo;
		}
		/*
			RESPONSÁVEL POR RESTAURAR OS VALORES PADRÕES DE CAMPOS QUE AUXILIAM NA VALIDAÇÃO DO LOGIN, 
			ISSO EVITA QUE UMA SENHA SEJA TROCADA MAIS DE UMA VEZ PELA TELA DE PRIMEIRO ACESSO

			$id -> id do usuário
		*/
		public function reset_auxiliar_login($id)
		{
			$this->db->query("
				UPDATE Usuario SET Status = 0, Codigo_ativacao = 0, Contador_tentativa = 0,Data_ultima_tentativa = '0000-00-00'  
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*
			RESPONSÁVEL POR CONTABILIZAR A QUANTIDADE DE TENTATIVAS AO VALIDAR O CÓDIGO DE ACESSO, AO EXECEDER TRÊS TENTATIVAS, GERAR UM NOVO CÓDIGO
			
			$id -> id do usuário
		*/
		public function tentativas_erro($id)
		{
			$this->db->query("
				UPDATE Usuario SET Contador_tentativa = (Contador_tentativa + 1), Data_ultima_tentativa = NOW()
				WHERE Id = ".$this->db->escape($id)."");

			return $this->db->query("
				SELECT Contador_tentativa FROM Usuario 
				WHERE Id = ".$this->db->escape($id)."")->row_array()['Contador_tentativa'];
		}
		/*
			RESPONSÁVEL POR gera o código de alteração da senha e marcar o campo
			com status de solicitação de de alteração de senha com o valor 2

			$id -> id do usuário
		*/
		public function gera_codigo_alteracao($id)
		{
			$codigo = rand(100000,999999);
			$this->db->query("
				UPDATE Usuario SET Codigo_ativacao = ".$this->db->escape($codigo).", Contador_tentativa = 0, Status = 2 
				WHERE Id = ".$this->db->escape($id)."");
			return $codigo;
		}
	}
?>