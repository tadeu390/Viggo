<?php
	/*
		ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS DADOS DE USUÁRIOS 
	*/
	class Usuario_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*
			REPONSÁVEL POR RETORNAR UMA LISTA DE USUÁRIOS OU UM USUÁRIO ESPECÍFICO SE FOR PASSADO UM VALOR
			PARA O PARÂMETRO Id. PAGINA O RESULTADO QUANDO NECESSÁRIO E VOLTA SOMENTE REGISTRO(S) ATIVOS SE FOR PASSA TRUE PARA
			O PRIMEIRO PARÂMETRO
		*/
		public function get_usuario($Ativo, $Id = FALSE, $page = FALSE, $filter = FALSE)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND u.Ativo = 1 ";

			if ($Id === FALSE)
			{
				$filtros = $this->filtros($filter);
				
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Usuario u WHERE TRUE ".$filtros.") AS Size, u.Id, 
					u.Nome as Nome_usuario, u.Email, u.Ativo, g.Nome AS Nome_grupo, u.Codigo_ativacao, u.Status, u.Sexo,   
					CASE 
						WHEN u.Grupo_id = 2 THEN
							'aluno'
						ELSE 'usuario'
					END AS Method 
					FROM Usuario u 
					LEFT JOIN Grupo g ON u.Grupo_id = g.Id 
					WHERE TRUE ".$Ativos."".$filtros."
					ORDER BY u.Id ASC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT u.Id, u.Nome as Nome_usuario, u.Email, u.Ativo, u.Sexo,
				DATE_FORMAT(u.Data_registro, '%d/%m/%Y') as Data_registro, 
				DATE_FORMAT(u.Data_nascimento, '%d/%m/%Y') as Data_nascimento, 
				g.Nome AS Nome_grupo, u.Status, u.Codigo_ativacao,  
				u.Grupo_id, u.Email_notifica_nova_conta, s.Valor   
					FROM Usuario u 
				LEFT JOIN Senha s ON u.Id = s.Usuario_id 
				LEFT JOIN Grupo g ON u.Grupo_id = g.Id
				WHERE TRUE ".$Ativos." AND u.Id = ".$this->db->escape($Id)."");
			return $query->row_array();
		}

		public function filtros($filter)
		{
			$filtros = "";
			if(!empty($filter))
			{
				if($filter['grupo_id'] != 0)
					$filtros = " AND u.Grupo_id = ".$this->db->escape($filter['grupo_id']);
				if(!empty($filter['data_registro_inicio']))
					$filtros = $filtros." AND u.Data_registro >= DATE_FORMAT(STR_TO_DATE(".$this->db->escape($filter['data_registro_inicio']).", '%d/%m/%Y'), '%Y-%m-%d')";
				if(!empty($filter['data_registro_fim']))
					$filtros = $filtros." AND u.Data_registro <= DATE_FORMAT(STR_TO_DATE(".$this->db->escape($filter['data_registro_fim']).", '%d/%m/%Y'), '%Y-%m-%d')";
				if(!empty($filter['nome']))
					$filtros = $filtros." AND u.Nome LIKE ".$this->db->escape($filter['nome']."%");
				if(!empty($filter['email']))
					$filtros = $filtros." AND u.Email LIKE ".$this->db->escape($filter['email']."%");
				if($filter['ativo'] != 0)
					$filtros = $filtros." AND u.Ativo = ".$this->db->escape($filter['ativo']);
				if(!empty($filter['data_nascimento_inicio']))
					$filtros = $filtros." AND u.Data_nascimento >= DATE_FORMAT(STR_TO_DATE(".$this->db->escape($filter['data_nascimento_inicio']).", '%d/%m/%Y'), '%Y-%m-%d')";
				if(!empty($filter['data_nascimento_fim']))
					$filtros = $filtros." AND u.Data_nascimento <= DATE_FORMAT(STR_TO_DATE(".$this->db->escape($filter['data_nascimento_fim']).", '%d/%m/%Y'), '%Y-%m-%d')";
			}
			return $filtros;
		}
		/*
			REPONSÁVEL POR OCULTAR UM USUÁRIO NO BANCO DE DADOS

			$Id -> id do usuário
		*/
		public function deletar($Id)
		{
			return $this->db->query("
				UPDATE Usuario SET Ativo = 0 
				WHERE Id = ".$this->db->escape($Id)."");
		}
		/*
			RESPONSÁVEL POR CADASTRAR OU ATUALIZAR UM USUÁRIO E EM SEGUIDA RETORNA A ID DO USUÁRIO CADASTRADO

			$data -> Contém todos os dados do usuário
		*/
		public function set_usuario($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Usuario',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Usuario', $data);
			}
			return $this->get_usuario_por_email($data['Email'])['Id'];
		}
		/*
			RESPONSÁVEL POR RETORAR UM USUÁRIO DE ACORDO COM UM E-MAIL

			$Email -> Endereço de e-mail do usuário
		*/
		public function get_usuario_por_email($Email)
		{
			$query = $this->db->query("
				SELECT * FROM Usuario WHERE Email = ".$this->db->escape($Email)."");
			return $query->row_array();
		}
		/*
			RESPONSÁVEL POR VERIFICAR A DISPONIBILIDADE DE UM ENDEREÇO DE E-MAIL

			$Email -> Endereço de e-mail a ser validado
			$Id -> Id do usuário
		*/
		public function email_valido($Email, $Id)
		{
			$query = $this->db->query("
				SELECT Email FROM Usuario 
				WHERE Email = ".$this->db->escape($Email)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_Usuario(FALSE ,$Id, FALSE)['Email'] != $query['Email'])
				return "invalido";
			
			return "valido";
		}
		/*
			RESPONSÁVEL POR RETORNAR OS USUÁRIOS DE UM DETERMINADO GRUPO

			$Grupo_id -> id de um grupo
		*/
		public function get_usuario_por_grupo($Grupo_id)
		{
			$query = $this->db->query("
				SELECT * FROM Usuario WHERE Grupo_id = ".$Grupo_id."");

			return $query->result_array();
		}
	}
?>