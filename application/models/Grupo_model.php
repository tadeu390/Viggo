<?php
	class Grupo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		
		public function get_grupo($Ativo = FALSE, $id = FALSE, $page = FALSE)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND Ativo = 1 ";

			if ($id === FALSE)
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === false)
					$pagination = "";
				
				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Grupo) AS Size, Id, Nome AS Nome_grupo, Ativo 
						FROM Grupo WHERE TRUE ".$Ativos."
					ORDER BY Data_registro DESC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("SELECT Id, Nome AS Nome_grupo, Ativo FROM Grupo 
										WHERE TRUE ".$Ativos." AND Id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Grupo SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}

		public function get_grupo_acesso($Grupo_id)
		{
			$query = $this->db->query("SELECT *,
				(SELECT COUNT(*)  FROM Usuario u WHERE u.Grupo_id = ".$this->db->escape($Grupo_id).") AS Qtd_user,
				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso A ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Criar = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_criar,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso A ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Ler = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_ler,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso A ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Atualizar = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_atualizar,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso A ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Remover = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_remover
				FROM(
						SELECT M.Id as Modulo_id, a.Id as Acesso_id, g.Nome as Nome_grupo,
						m.Nome as Nome_modulo, a.Criar, a.Ler,
						a.Atualizar, a.Remover
						FROM Modulo m 
                        LEFT JOIN Acesso a ON m.Id = a.Modulo_id 
                       	LEFT JOIN Usuario u ON a.Usuario_id = u.Id 
                        LEFT JOIN Grupo g ON u.Grupo_id = g.Id 
                        GROUP BY m.Nome 
					) as x ORDER BY x.Modulo_id"); //ORDER BY, NÃO REMOVER EM HIPÓTESE ALGUMA, O MÉTODO GET_ACESSO DA MODEL ACESSO_MODEL FAZ A MESMA ORDENAÇÃO, OS DOIS SÃO NECESSÁRIOS PRA PODER ESPECIFICAR AS PERMISSIOS NO BANCO DE FORMA CORRETA 
			return $query->result_array();
		}
		
		public function set_grupo($data)
		{
			if(empty($data['Id']))
				$this->db->insert('Grupo',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				$this->db->update('Grupo', $data);
			}
		}
	}
?>