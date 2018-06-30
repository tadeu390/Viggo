<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS GRUPOS DE USUÁRIOS.
	*/
	class Grupo_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS OS GRUPOS CADASTRADOS OU UM GRUPO ESPECÍFICO.
		*
		*	$Ativo -> Quando passadO "TRUE" quer dizer pra retornar somente registro(s) ativos(s), se for passado FALSE retorna tudo.
		*	$id -> Quando especificado na chamada do método retorna apenas um grupo específico, se for passado FALSE retorna 
		*	todos os registros de acordo com o primeiro argumento.
		*	$page -> Número da página que determina o intervalo de registros a serem buscados no banco, se for passado como FALSE retorna tudo sem paginar.
		*/
		public function get_grupo($Ativo = FALSE, $id = FALSE, $page = FALSE)
		{
			$Ativos = "";
			if($Ativo == true)
				$Ativos = " AND g.Ativo = 1 ";

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
					ORDER BY Data_registro ASC ".$pagination."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT g.Id, g.Nome AS Nome_grupo, g.Ativo, 
				DATE_FORMAT(g.Data_registro, '%d/%m/%Y') as Data_registro,
				(SELECT COUNT(*) FROM Usuario WHERE Grupo_id = g.Id) as Qtd_usuario,
				(SELECT COUNT(*) FROM Usuario WHERE Grupo_id = g.Id AND Ativo = 1) as Qtd_usuario_ativo
				FROM Grupo g
					WHERE TRUE ".$Ativos." AND g.Id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UM GRUPO DO BANCO DE DADOS.
		*
		*	$id -> Id do grupo a ser "apagado".
		*/
		public function deletar($id)
		{
			return $this->db->query("
				UPDATE Grupo SET Ativo = 0 
				WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS OS MÓDULOS DO SISTEMA, JUNTAMENTE COM A QUANTIDADE DE USUÁRIOS DO GRUPO EM QUESTÃO E TAMBÉM A 
		*	QUANTIDADE DE USUÁRIOS POR TIPO DE PERMISSÃO DE CADA MÓDULO NO GRUPO. ISSO É UTILIZADO PARA SE VISUALIZAR AS PERMISSÕES DE UM 
		*	GRUPO POR MÓDULO NO SISTEMA E TAMBÉM PARA SABER SE É APENAS ALGUNS USUÁRIOS 
		*	OU TODOS QUE POSSUEM UM TIPO DE PERMISSÃO EM UM DETERMINADO MÓDULO.
		*	
		*	$Grupo_id -> id do grupo que se deseja listar as permissões.
		*/
		public function get_grupo_acesso($Grupo_id)
		{
			$query = $this->db->query("SELECT *,
				(SELECT COUNT(*)  FROM Usuario u WHERE u.Grupo_id = ".$this->db->escape($Grupo_id).") AS Qtd_user,
				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso a ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Criar = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_criar,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso a ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Ler = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_ler,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso a ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Atualizar = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_atualizar,

				(SELECT COUNT(*) FROM Usuario usp 
					INNER JOIN Acesso a ON usp.Id = a.Usuario_id 
						WHERE a.Modulo_id = x.Modulo_id AND a.Remover = 1 
						AND usp.Grupo_id = ".$this->db->escape($Grupo_id).") AS Permissoes_remover
				FROM(
						SELECT m.Id as Modulo_id, a.Id as Acesso_id, g.Nome as Nome_grupo,
						m.Nome as Nome_modulo, a.Criar, a.Ler,
						a.Atualizar, a.Remover
						FROM Modulo m 
                        LEFT JOIN Acesso a ON m.Id = a.Modulo_id 
                       	LEFT JOIN Usuario u ON a.Usuario_id = u.Id 
                        LEFT JOIN Grupo g ON u.Grupo_id = g.Id 
                        GROUP BY m.Nome 
					) as x ORDER BY x.Modulo_id"); //ORDER BY, NÃO REMOVER EM HIPÓTESE ALGUMA, O MÉTODO GET_ACESSO DA MODEL ACESSO_MODEL FAZ A MESMA ORDENAÇÃO, OS DOIS SÃO NECESSÁRIOS PRA PODER ESPECIFICAR AS PERMISSIOS NO BANCO DE FORMA CORRETA.
			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR OU ATUALIZAR AS INFORMAÇÕES DE UM DETERMINADO DO GRUPO.
		*
		*	$data -> Contém todo os dados do grupo.
		*/
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
		/*!
		*	RESPONSÁVEL POR RETORNAR UM GRUPO CONFORME O NOME.
		*
		*	$nome -> Contém o nome do grupo a ser buscado no banco de dados.
		*/
		public function get_grupo_por_nome($nome)
		{
			$nome = mb_strtolower($nome);//usar mb_strtolower em vez de strtolower, pois pode vir caracter especial na string.
			$query = $this->db->query("
				SELECT * FROM Grupo m WHERE LOWER(m.Nome) = ".$this->db->escape($nome)."");
			return $query->row_array();
		}
	}
?>