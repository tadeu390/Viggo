<?php
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE CURSOS.
	*/
	class Curso_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS METODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE CURSOS OU UM CURSO ESPECÍFICO.
		*
		*	$Ativo -> Quando passado como "TRUE", este permite retornar apenas cursos que estão ativos no banco de dados.
		*	$Id -> Quando passado algum valor inteiro, retorna um curso caso o mesmo exista no banco de dados.
		*	$page -> Pagina atual.
		*	$filter -> Quando há filtros, esta recebe os parâmetros utilizados para filtrar.
		*/
		public function get_curso($Ativo, $id = FALSE, $page = FALSE, $filter = FALSE)
		{
			$Ativos = "";
			if($Ativo == TRUE)
				$Ativos = " AND d.Ativo = 1 ";

			if ($id === FALSE)//retorna todos se nao passar o parametro
			{
				$limit = $page * ITENS_POR_PAGINA;
				$inicio = $limit - ITENS_POR_PAGINA;
				$step = ITENS_POR_PAGINA;	
				
				$pagination = " LIMIT ".$inicio.",".$step;
				if($page === FALSE)
					$pagination = "";

				$query = $this->db->query("
					SELECT (SELECT count(*) FROM  Curso WHERE TRUE ".$Ativos.") AS Size, 
					c.Id, c.Nome as Nome_curso, 
					DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, 
						(SELECT COUNT(*) FROM Disc_curso dc 
							WHERE dc.Curso_id = c.Id) as Qtd_disciplina, c.Ativo   
					FROM Curso c 
					WHERE TRUE ".$Ativos." ORDER BY c.Id ". $pagination ."");

				return $query->result_array();
			}

			$query =  $this->db->query("
				SELECT c.Id, c.Nome as Nome_curso, 
				DATE_FORMAT(c.Data_registro, '%d/%m/%Y') as Data_registro, 
				(SELECT COUNT(*) FROM Disc_curso dc 
							WHERE dc.Curso_id = c.Id) as Qtd_disciplina,
				c.Ativo  
					FROM Curso c 
				WHERE TRUE ".$Ativos." AND c.Id = ".$this->db->escape($id)."");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR AS INFORMAÇÕES DE UM CURSO NO BANCO DE DADOS  E 
		*	TAMBÉM AS DISCIPLINAS SELECIONADAS PARA O MESMO.
		*
		*	$data-> Contém os dados do curso a ser cadastrado/atualizado.
		*/
		public function set_curso($data)
		{
			if($this->valida_curso($data) > 0)
				return "Este curso já está cadastrado no sistema.";

			if(empty($data['Id']))
			{
				$dataToSave = array(
					'Nome' => $data['Nome'],
					'Ativo' => $data['Ativo']
				);
				
				$this->db->insert('Curso',$dataToSave);
				$query = $this->db->query("
					SELECT Id FROM Curso 
						WHERE Nome = ".$this->db->escape($dataToSave['Nome'])."");

				$query = $query->row_array();

				for($i = 0; $i < count($data['Disciplinas_id']); $i++)
				{
					$dataToSave = array(
						'Disciplina_id' => $data['Disciplinas_id'][$i],
						'Curso_id' => $query['Id']
					);
					$this->db->insert('Disc_curso',$dataToSave);
				}
			}
			else
			{
				$query = $this->db->query("
					SELECT Disciplina_id FROM Disc_curso
						WHERE Curso_id = ".$this->db->escape($data['Id'])."");

				$query = $query->result_array();
				
				//DELETA OS QUE FORAM REMOVIDOS NA TELA PELO USUARIO.
				for($i = 0; $i < count($query); $i++)
				{
					$flag = 0;
					for($j = 0; $j < count($data['Disciplinas_id']); $j++)
					{
						if($query[$i]['Disciplina_id'] == $data['Disciplinas_id'][$j])
							$flag = 1;
					}
					if($flag == 0)
						$this->db->query("
							DELETE FROM Disc_curso 
								WHERE Disciplina_id = ".$this->db->escape($query[$i]['Disciplina_id'])." 
								AND Curso_id = ".$this->db->escape($data['Id'])."");
				}

				for($i = 0; $i < count($data['Disciplinas_id']); $i++)
				{
					$flag = 0;
					for($j = 0; $j < count($query); $j++)
					{
						if($data['Disciplinas_id'][$i] == $query[$j]['Disciplina_id'])
							$flag = 1;
					}
					if($flag == 0)
						$this->db->query("
							INSERT INTO Disc_curso(Disciplina_id, Curso_id)
								VALUES(".$this->db->escape($data['Disciplinas_id'][$i]).",".$this->db->escape($data['Id']).")");
				}

				$dataToSave = array(
					'Id' => $data['Id'],
					'Nome' => $data['Nome'],
					'Ativo' => $data['Ativo']
				);

				$this->db->where('Id', $data['Id']);
				$this->db->update('Curso', $dataToSave);
			}
			return "sucesso";
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR O NOME DE UM CURSO PROCURANDO PELA EXISTÊNCIA DO NOME EM QUESTÃO NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados do curso a ser cadastrado/editado.
		*/
		public function valida_curso($data)
		{
			$query = $this->db->query("
				SELECT Nome FROM Curso 
				WHERE UPPER(Nome) = UPPER(".$this->db->escape($data['Nome']).") AND 
				Id != ".$this->db->escape($data['Id'])."");

			return $query->num_rows();
		}
		/*!
		*	RESPONSÁVEL POR "APAGAR" UM CURSO DO BANCO DE DADOS.
		*
		*	$id -> Id do curso a ser "apagado".
		*/
		public function delete_curso($id)
		{
			return $this->db->query("
				UPDATE Curso SET Ativo = 0 WHERE Id = ".$this->db->escape($id)."");
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE UM DETERMIANDO CURSO JÁ EXISTE NO BANCO DE DADOS.
		*
		*	$Nome -> Nome do curso a ser validado.
		*	$Id -> Id do curso.
		*/
		public function nome_valido($Nome, $Id)
		{
			$query = $this->db->query("
				SELECT Nome FROM Curso 
				WHERE Nome = ".$this->db->escape($Nome)."");
			$query = $query->row_array();
			
			if(!empty($query) && $this->get_curso(FALSE ,$Id, FALSE)['Nome_curso'] != $query['Nome'])
				return "invalido";
			
			return "valido";
		}
	}
?>