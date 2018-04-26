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
				SELECT u.Id, u.Grupo_id, s.Valor 
				FROM Usuario u INNER JOIN Senha s ON u.Id = s.Usuario_id 
				WHERE Email = ".$this->db->escape($email)." AND s.Valor = ".$this->db->escape($senha)." AND s.Ativo = 1");
			 
			 $data = $query->row_array();
			 $data['rows'] =  $query->num_rows();
			 return $data;
		}
		/*
			RESPONSÁVEL POR VERIFICAR SE EXISTE COOKIE OU SESSAO E VALIDA ESSES DADOS
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
	}
?>