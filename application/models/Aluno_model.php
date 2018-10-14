<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AOS ESPECÍFICOS DO ALUNO.
	*/
	class Aluno_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR OS DADOS DE UM ALUNO DE ACORDO COM UM ID DE USUÁRIO.
		*
		*	$id -> Id de usuário do aluno.
		*/
		public function get_aluno($id = FALSE)
		{
			if($id === FALSE)
			{
				$query =  $this->db->query("
					SELECT a.Id, u.Nome as Nome_aluno, CONCAT(u.Nome,' - ', a.Cpf) as Nome_ra, a.Usuario_id  
						FROM Aluno a 
						INNER JOIN Usuario u ON a.Usuario_id = u.Id 
					WHERE u.Ativo = 1 ORDER BY u.Nome ASC");
				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT *, DATE_FORMAT(Data_expedicao, '%d/%m/%Y') as Data_expedicao_pt 
					FROM Aluno a 
	
				WHERE a.Usuario_id = ".$this->db->escape($id)."");
			return $query->row_array();
		}
		/*!
		*	REPONSÁVEL POR CADASTRAR OU ATUALIZAR OS DADOS DE UM ALUNO.
		*
		*	$data -> Contem os dados do aluno.
		*/
		public function set_aluno($data)
		{
			if(empty($this->get_aluno($data['Usuario_id'])))
			{
				$this->db->insert('Aluno',$data);
				return $this->get_aluno($data['Usuario_id'])['Id'];
			}
			else
			{
				$this->db->where('Usuario_id', $data['Usuario_id']);
				$this->db->update('Aluno', $data);
				return $data['Id'];
			}
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR TODOS OS ALUNOS MATRICULADOS EM UMA DETERMINADA DISCIPLINA DE UMA 
		*	DETERMINADA TURMA, DE ACORDO COM O HORÁRIO, PODENDO RETORNAR OU A TURMA TODA OU UMA SUBTURMA.
		*
		*	$disciplina_id -> Id da disciplina selecionada na tela do professor.
		*	$turma_id -> Id da turma selecionada na tela do professor.
		*	$sub_turma -> Quando informado retorna alunos de uma sub_turma específica.
		*/
		public function get_aluno_turma($disciplina_id, $turma_id, $sub_turma = FALSE)
		{
			if($sub_turma !== FALSE)
				$sub_turma = "AND dh.Sub_turma = ".$this->db->escape($sub_turma);
			else 
				$sub_turma = "";
			$query = $this->db->query("
				SELECT u.Nome AS Nome_aluno, m.Id AS Matricula_id, 
				dh.Sub_turma, a.Id AS Aluno_id
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id  
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON a.Id = i.Aluno_id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
				LEFT JOIN Disc_hor dh ON dt.Id = dh.Disc_turma_id AND (m.Sub_turma = dh.Sub_turma OR dh.Sub_turma = 0)
				WHERE dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." ".$sub_turma." GROUP BY #dh.Sub_turma, 
				m.Id ORDER BY u.Nome");
			
				return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA OU UM PERÍODO LETIVO, ASSOCIADO AO CURSO PARA O ALUNO SELECIONAR O QUE ELE DESEJA VER;
		*
		*	$aluno_id -> Id de usuário do aluno logado.
		*	$curso_id -> Id do curso escolhido pelo aluno.
		*/
		public function get_periodos_aluno($aluno_id, $curso_id)
		{
			$curso = "";
			if($curso_id !== FALSE)
				$curso = " AND c.Id = ".$this->db->escape($curso_id);

			$query = $this->db->query("
				SELECT p.Id AS Periodo_letivo_id, c.Id AS Curso_id, CONCAT(c.Nome, ': ', p.Periodo, ' - ', md.Nome) AS Curso
				FROM Periodo_letivo p 
		        INNER JOIN Modalidade md ON md.Id = p.Modalidade_id 
				INNER JOIN Disc_turma dt ON p.Id = dt.Periodo_letivo_id 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Inscricao i ON m.Inscricao_id = i.Id 
		    	INNER JOIN Curso c ON c.Id = i.Curso_id 
				INNER JOIN Aluno a ON i.Aluno_id = a.Id 
				INNER JOIN Usuario u ON a.Usuario_id = u.Id 
				WHERE u.Id = ".$this->db->escape($aluno_id)." ".$curso." 
		        GROUP BY c.Id  
        	");

        	return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR UM CPF, OU SEJA, SE AO CADASTRAR OU ATUALIZAR UM ALUNO, O NÚMERO DE CPF INFORMADO 
		*	ESTÁ DISPONÍVEL.
		*
		*	$numero -> Cpf do aluno a ser validado.
		*	$id -> Id de usuário do aluno.
		*/
		public function cpf_valido($numero, $id)
		{
			$query = $this->db->query("
				SELECT Cpf FROM Aluno 
				WHERE Cpf = ".$this->db->escape($numero)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_aluno($id)['Cpf'] != $query['Cpf'])
				return "invalido";
			
			return "valido";
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR UM RG, OU SEJA, SE AO CADASTRAR OU ATUALIZAR UM ALUNO, O NÚMERO DE RG INFORMADO 
		*	ESTÁ DISPONÍVEL.
		*
		*	$numero -> RG do aluno a ser validado.
		*	$id -> Id de usuário do aluno.
		*/
		public function rg_valido($numero, $id)
		{
			$query = $this->db->query("
				SELECT Rg FROM Aluno 
				WHERE Rg = ".$this->db->escape($numero)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_aluno($id)['Rg'] != $query['Rg'])
				return "invalido";
			
			return "valido";
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR UM TÍTULO DE ELEITOR, OU SEJA, SE AO CADASTRAR OU ATUALIZAR UM ALUNO, 
		*	O NÚMERO DO TÍTULO INFORMADO ESTÁ DISPONÍVEL.
		*
		*	$numero -> Título de eleitor do aluno a ser validado.
		*	$id -> Id de usuário do aluno.
		*/
		public function titulo_eleitor_valido($numero, $id)
		{
			$query = $this->db->query("
				SELECT Titulo_eleitor FROM Aluno 
				WHERE Titulo_eleitor = ".$this->db->escape($numero)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_aluno($id)['Titulo_eleitor'] != $query['Titulo_eleitor'])
				return "invalido";
			
			return "valido";
		}
	}
?>