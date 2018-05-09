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
				SELECT u.Id, u.Grupo_id, u.Redefinir_senha, u.Contador_tentativa, s.Valor, u.Nome, u.Email  
				FROM Usuario u INNER JOIN Senha s ON u.Id = s.Usuario_id 
				WHERE Email = ".$this->db->escape($email)." AND s.Valor = ".$this->db->escape($senha)." 
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
				UPDATE Usuario SET Codigo_ativacao = ".$this->db->escape($codigo).", Contador_tentativa = 0 
				WHERE Id = ".$this->db->escape($Id)."AND Redefinir_senha = 1");

		}
		/*
			RESPONSÁVEL POR RESTAURAR OS VALORES PADRÕES DE CAMPOS QUE AUXILIAM NA VALIDAÇÃO DO LOGIN, 
			ISSO EVITA QUE UMA SENHA SEJA TROCADA MAIS DE UMA VEZ PELA TELA DE PRIMEIRO ACESSO

			$id -> id do usuário
		*/
		public function reset_auxiliar_login($id)
		{
			$this->db->query("
				UPDATE Usuario SET Redefinir_senha = 0, Codigo_ativacao = 0, Contador_tentativa = 0,Data_ultima_tentativa = '0000-00-00'  
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
	}
?>