<?php
	class Account_model extends CI_Model {

		public function __construct()
		{
			$this->load->database();
		}
		
		public function get_login($email = FALSE, $senha = FALSE)
		{
			$dataResult = "";
			$query = $this->db->query("SELECT id, grupo_id, senha FROM 
								usuario WHERE email = ".$this->db->escape($email)." AND senha = ".$this->db->escape($senha)."");
			 $data =  $query->row_array();
			 if(!empty($data))
			 {
				 //atualizar ultimo acesso
				 $query = $this->db->query("UPDATE usuario SET ultimo_acesso = NOW()  
				 					WHERE id = ".$this->db->escape($data['id'])."");
				 $dataResult = $data;
			 }
			return $dataResult;
		}

		//METODO VERIFICA SE EXISTE COOKIE OU SESSAO E VALIDA ESSES DADOS
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
				$query = $this->db->query("SELECT id, grupo_id FROM usuario 
										WHERE id = ".$this->db->escape($id)." AND
										grupo_id = ".$this->db->escape($grupo_id)."");
				if($query->num_rows() > 0)
				{
					$sessao = array(
						'status' => 'ok',
						'id' => $query->row_array()['id'],
						'grupo_id' => $query->row_array()['grupo_id']
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