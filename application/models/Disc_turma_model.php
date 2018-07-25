<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES 
	*	DOS ALUNOS, DISCIPLINAS E PROFESSORES DA TURMA.
	*/
	class Disc_turma_model extends CI_Model 
	{
		/*
			CONECTA AO BANCO DE DADOS DEIXANDO A CONEXÃO ACESSÍVEL PARA OS MÉTODOS
			QUE NECESSITAREM REALIZAR CONSULTAS.
		*/
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O CABEÇALHO DE UMA TURMA.
		*
		*	$id -> Id da turma.
		*/
		public function get_disc_turma_header($id)
		{
			$query = $this->db->query("
				SELECT t.Id, g.Curso_id, p.Modalidade_id, t.Nome as Nome_turma, p.Qtd_minima_aluno, p.Qtd_maxima_aluno, 
				p.Periodo as Nome_periodo, dt.Periodo_letivo_id, g.Id as Grade_id, dg.Periodo  
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
				INNER JOIN Disc_grade dg ON dg.Id = dt.Disc_grade_id 
				INNER JOIN Grade g ON g.Id = dg.Grade_id 
 				INNER JOIN Periodo_letivo p ON p.Id = dt.Periodo_letivo_id 
                INNER JOIN Modalidade md ON md.Id = p.Modalidade_id 
                WHERE  t.Id = ".$this->db->escape($id)."  
                GROUP BY t.Id, g.Curso_id, p.Modalidade_id, t.Nome");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA GRADE POR PERÍODO.
		*
		*	$id -> Id da grade para que se possa obter as disciplinas da grade já cadastradas para ela caso exista.
		*	$periodo -> Periodo a que se refere as disciplinas da grade.
		*/
		public function get_grade_disciplina($id, $periodo, $turma_id)
		{
			$query = $this->db->query("
				SELECT d.Id AS Disciplina_id, dg.Periodo, dt.Categoria_id, 
				dt.Professor_id, 
                d.Nome as Nome_disciplina, dt.Turma_id,
                dg.Id As Disc_Grade_id 
				FROM Disciplina d 
				INNER JOIN Disc_grade dg ON dg.Disciplina_id = d.Id 
                LEFT JOIN Disc_turma dt ON dg.Id = dt.Disc_grade_id  AND dt.Turma_id = ".$this->db->escape($turma_id)."
                WHERE dg.Grade_id = ".$this->db->escape($id)." AND dg.Periodo = ".$this->db->escape($periodo)."");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE ALUNOS CADASTRADAS PARA UMA TURMA.
		*
		*	$id -> Id da turma.
		*/
		public function get_disc_turma_aluno($id)
		{
			$query = $this->db->query("
				SELECT u.Nome as Nome_aluno, a.Id as Aluno_id, m.Sub_turma  
				FROM Disc_turma dt 
				INNER JOIN Matricula m ON  m.Disc_turma_id = dt.Id 
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON i.Aluno_id = a.Id 
				INNER JOIN Usuario u ON u.Id = a.Usuario_id 
                WHERE dt.Turma_id = ".$this->db->escape($id)." 
                GROUP by u.Nome");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE PROFESSORES CADASTRADOS PARA UMA TURMA EM UMA DISCIPLINA.
		*
		*	$id -> Id da turma.
		*/
		public function get_disc_turma_professor($id)
		{
			$query = $this->db->query("
				SELECT u.Nome as Nome_professor, u.Id as Professor_id, d.Id AS Disciplina_id 
				FROM Disc_turma dt 
				INNER JOIN Usuario u ON u.Id = dt.Professor_id 
				INNER JOIN Disc_Grade dg ON dg.Id = dt.Disc_grade_id 
				INNER JOIN Disciplina d ON d.Id = dc.Disciplina_id 
                WHERE u.Grupo_id = 4 AND dt.Turma_id = ".$this->db->escape($id)."");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O CURSO DE UMA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja saber o curso.
		*/
		public function get_curso_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT g.Curso_id FROM Grade g 
				INNER JOIN Disc_Grade dg ON g.Id = dg.Grade_id 
				INNER JOIN Disc_turma dt ON dg.Id = dt.Disc_grade_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." LIMIT 1");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O PERIODO DE UMA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja saber o periodo.
		*/
		public function get_periodo_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT dg.Periodo FROM Grade g 
				INNER JOIN Disc_Grade dg ON g.Id = dg.Grade_id 
				INNER JOIN Disc_turma dt ON dg.Id = dt.Disc_grade_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." LIMIT 1");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A ID DA GRADE DE UMA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja saber a grade.
		*/
		public function get_grade_id_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT g.Id AS Grade_id FROM Grade g 
				INNER JOIN Disc_Grade dg ON g.Id = dg.Grade_id 
				INNER JOIN Disc_turma dt ON dg.Id = dt.Disc_grade_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." LIMIT 1");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR A MODALIDADE DE UMA DETERMINADA TURMA.
		*
		*	$turma_id -> Id da turma que se deseja saber a modalidade.
		*/
		public function get_modalidade_turma($turma_id)
		{
			$query = $this->db->query("
				SELECT p.Modalidade_id 
				FROM Turma t 
				INNER JOIN Disc_turma dt ON t.Id = dt.Turma_id 
				INNER JOIN Periodo_letivo p ON p.Id = dt.Periodo_letivo_id 
				WHERE t.Id = ".$this->db->escape($turma_id)." 
				GROUP BY 1");

			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE ALUNOS DE UMA TURMA DESDE QUE ESTES ALUNOS ESTEJAM COM AS MATRÍCULAS RENOVADAS PARA O PÉRIODO
		*	LETIVO CORRENTE PARA A MODALIDADE EM QUESTÃO E NÃO ESTEJAM CADASTRADO NO MESMO CURSO E MESMO PERÍODO LETIVO DA TURMA QUE SE DESEJA CADASTRAR.
		*
		*	$turma_id -> Id da turma que se deseja buscar os alunos.
		*	$curso_id -> Id do Curso da turma.
		*	$modalidade_id -> Id da modalidade da turma.
		*/
		public function get_alunos_inscritos_turma_antiga($turma_id, $curso_id, $modalidade_id)
		{
			$CI = get_instance();
				$CI->load->model("Modalidade_model");
				
			$last_periodo_letivo_id = $CI->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Id'];
			$perido_letivo_turma_antiga =  $this->get_disc_turma_header($turma_id)['Periodo_letivo_id'];
			//Size_total -> Retorna a quantidade de totalde alunos da turma independente de estar renovado a matrícula ou não
			//isso ajuda a quando for criar uma turma a partir de outra turma pode ser alertado pelo sistama caso haja algum aluno da turma selecionada no filtro
			//que esteja sem renovação da matricula para  oeríodo corrente.
			$query = $this->db->query("
				SELECT a.Id, u.Nome as Nome_aluno, a.Id AS Aluno_id, 
				(SELECT COUNT(*) FROM (
					SELECT a.Id FROM Disc_turma dt 
						INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id
						INNER JOIN Inscricao i ON m.Inscricao_id = i.Id  
						INNER JOIN Aluno a ON i.Aluno_id = a.Id 
						INNER JOIN Usuario u ON a.Usuario_id = u.Id 
						LEFT JOIN Renovacao_matricula r ON r.Inscricao_id = i.Id AND r.Periodo_letivo_id = ".$perido_letivo_turma_antiga."  
		                WHERE dt.Turma_id = ".$turma_id."  
		                GROUP BY 1) 
				    AS X) AS Size_total_turma_antiga,
				    (SELECT COUNT(*) FROM (
					SELECT a.Id FROM Disc_turma dt 
						INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id
						INNER JOIN Inscricao i ON m.Inscricao_id = i.Id  
						INNER JOIN Aluno a ON i.Aluno_id = a.Id 
						INNER JOIN Usuario u ON a.Usuario_id = u.Id 
						LEFT JOIN Renovacao_matricula r ON r.Inscricao_id = i.Id AND r.Periodo_letivo_id = ".$last_periodo_letivo_id." 
		                WHERE dt.Turma_id = ".$turma_id." AND r.Id IS NOT NULL  
		                GROUP BY 1) 
				    AS X) AS Size_total_turma_antiga_renovado  

				FROM Disc_turma dt 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
				INNER JOIN Aluno a ON i.Aluno_id = a.Id 
				INNER JOIN Usuario u ON a.Usuario_id = u.Id 
				INNER JOIN Renovacao_matricula r ON r.Inscricao_id = i.Id AND r.Periodo_letivo_id = ".$last_periodo_letivo_id."
				#ABAIXO LEVANTA TODO MUNDO DA TURMA ANTIGA COM MATRICULA RENOVADA PRO PERÍODO LETIVO 
				#CORRENTE E QUE JÁ SE ENCONTRAM EM UMA NOVA TURMA
				LEFT JOIN (SELECT a.Id as Aluno_id 
	                            FROM Aluno a 
	                            INNER JOIN Inscricao i ON i.Aluno_id = a.Id 
	                            INNER JOIN Matricula m ON m.Inscricao_id = i.Id 
	                            INNER JOIN Disc_turma dt ON m.Disc_turma_id = dt.Id 
	                            INNER JOIN Disc_grade dg ON dt.Disc_Grade_id = dg.Id 
	                            INNER JOIN Grade g ON dg.Grade_id = g.Id 
	                            WHERE dt.Periodo_letivo_id = ".$last_periodo_letivo_id." #ultimo periodo letivo
	                            AND g.Curso_id = ".$this->db->escape($curso_id)."
	                            GROUP BY 1) adicionados ON adicionados.Aluno_id = a.Id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND adicionados.Aluno_id IS NULL GROUP BY 1, 2");
	
			return $query->result_array();
		}
		/*
			RESPONSÁVEL POR RETORNAR UMA LISTA DE ALUNOS CUJA INSCRIÇÃO NÃO EXSITE RELACIONAMENTO ALGUM COM 
			A TABELA DE MATRICULA (MATRICULA NAS DISCIPLINAS).
		*/
		public function get_alunos_inscritos_novos($curso_id, $modalidade_id, $filtros = FALSE)
		{
			$CI = get_instance();
			$CI->load->model("Modalidade_model");
			//para ver se o aluno está renovado para o último período letivo.
			$periodo_letivo_id = $CI->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Id'];

			$f = $this->filtros($filtros);

			$query = $this->db->query("
				SELECT A.Id as Aluno_id, u.Nome as Nome_aluno
				FROM Usuario u 
				INNER JOIN Aluno a ON u.Id = a.Usuario_id 
				INNER JOIN Inscricao i ON a.Id = i.Aluno_id  
				INNER JOIN Renovacao_matricula rm ON i.Id = rm.Inscricao_id 
                	AND rm.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
                LEFT JOIN matricula m ON m.Inscricao_id = i.Id 
                WHERE m.Inscricao_id IS NULL AND i.Curso_id = ".$this->db->escape($curso_id)." ".$f."");

			return $query->result_array();
		}

		/*!
		*	RESPONSÁVEL POR RETORNAR UMA LISTA DE ALUNOS PARA SE CRIAR UMA TURMA,
		*	A LISTA É COMPOSTA POR ALUNOS INSCRITOS EM UM DETERMINADO CURSO CUJA MATRICULA
		*	ESTÁ CRIADA/RENOVADA PARA O PERÍODO LETIVO CORRENTE DE UMA DETERMINADA MODALIDADE.
		*	COM BASE NISSO É CRUZADA ESSA LISTA COM UMA LISTA DE ALUNOS QUE ATENDEM AS REGRAS ACIMA, PORÉM 
		*	QUE JÁ SE ENCONTRAM EM UMA DETERMINADA TURMA DE UM DETERMINADO PERÍODO DE UM DETERMINADO CURSO.
		*	ISSO AJUDA A FILTRAR SOMENTE ALUNOS QUE AINDA NÃO POSSUEM TURMA.
		*
		*	$curso_id -> id do curso, para saber todos os inscritos no curso selecionado.
		*	$modalidade_id -> id da modalidade.
		*	$filtros -> Contém todos os filtros.
		*/
		public function get_alunos_inscritos($curso_id, $modalidade_id, $grade_id, $periodo_letivo_id = FALSE, $filtros = FALSE)
		{
			//SE ESTIVER EDITANDO ($periodo_letivo_id != FALSE) então considera o periodo letivo da turma
			//Se estiver criando considera o último período letivo da modalidade.
			if($periodo_letivo_id == FALSE)
			{
				$CI = get_instance();
				$CI->load->model("Modalidade_model");
				//para ver se o aluno está renovado para o último período letivo.
				$periodo_letivo_id = $CI->Modalidade_model->get_periodo_por_modalidade($modalidade_id)['Id'];
			}

			$f = $this->filtros($filtros);
			$query = $this->db->query("
				SELECT A.Id as Aluno_id, u.Nome as Nome_aluno 
				FROM Usuario u 
				INNER JOIN Aluno a ON u.Id = a.Usuario_id 
				INNER JOIN Inscricao i ON a.Id = i.Aluno_id AND 
					i.Curso_id = ".$this->db->escape($curso_id)." #AND 
					#(SELECT m.Id FROM Modalidade m 
						#INNER JOIN Periodo_letivo p 
						#ON m.Id = p.Modalidade_id WHERE p.Id = ".$this->db->escape($periodo_letivo_id).") = ".$this->db->escape($modalidade_id)." 
				INNER JOIN Renovacao_matricula rm ON rm.Inscricao_id = i.Id AND 
				rm.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
				
				LEFT JOIN Matricula m ON m.Inscricao_id = i.Id 
				LEFT JOIN Disc_turma dt ON m.Disc_turma_id = dt.Id 
				LEFT JOIN Disc_grade dg ON dg.Id = dt.Disc_Grade_id 
				LEFT JOIN Grade g ON g.Id = dg.Grade_id 

				#ABAIXO LEVANTA TODO MUNDO COM MATRICULA RENOVADA PRO PERÍODO LETIVO 
				#CORRENTE E QUE JÁ SE ENCONTRAM EM UMA NOVA TURMA
				LEFT JOIN (SELECT a.Id as Aluno_id 
                            FROM Aluno a 
                            INNER JOIN Inscricao i ON i.Aluno_id = a.Id 
                            INNER JOIN Matricula m ON i.Id = m.Inscricao_id 
                            INNER JOIN Disc_turma dt ON m.Disc_turma_id = dt.Id 
                            INNER JOIN Disc_Grade dg ON dg.Id = dt.Disc_grade_id 
                            INNER JOIN Grade g ON dg.Grade_id = g.Id 
                            WHERE dt.Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)." 
                            AND g.Curso_id = ".$this->db->escape($curso_id)."
                            GROUP BY 1) adicionados ON adicionados.Aluno_id = a.Id 
                
                WHERE adicionados.Aluno_id IS NULL AND u.Ativo = 1 AND 
                (g.Id = ".$this->db->escape($grade_id)." OR g.Id IS NULL)".$f." 
                GROUP BY 1,2");

			return $query->result_array();
		}
		/*!
		*	RESPONSÁVEL POR MONTAR A QUERY DE FILTROS 
		*
		*	$filter -> Contém todos os filtros.
		*/
		public function filtros($filter)
		{
			$filtros = "";
			if(!empty($filter))
			{
				//if(isset($filter['turma_id']) && $filter['turma_id'] != 0)
					//$filtros = " AND dt.Turma_id = ".$this->db->escape($filter['turma_id']);
				if(isset($filter['nome']) && $filter['nome'] != '0')
					$filtros = $filtros." AND u.Nome LIKE ".$this->db->escape($filter['nome']."%");
				if(isset($filter['data_renovacao_inicio']) && $filter['data_renovacao_inicio'] != 0)
					$filtros = $filtros." AND cast(rm.Data_registro as date) >= STR_TO_DATE(".$this->db->escape($filter['data_renovacao_inicio']).",'%Y-%m-%d')";
				if(isset($filter['data_renovacao_fim']) && $filter['data_renovacao_fim'] != 0)
					$filtros = $filtros." AND cast(rm.Data_registro as date) <= STR_TO_DATE(".$this->db->escape($filter['data_renovacao_fim']).",'%Y-%m-%d')";
			}
			return $filtros;
		}
		/*!
			RESPONSÁVEL POR CRIAR OU ATUALIZAR OS DADOS DE UMA TURMA PARA CADA ALUNO, CADA DISCIPLINA
			CONTIDA.

			$data -> Contém os dados a serem cadastrados como as disciplinas marcadas e seus respectivos
			professores e categorias e os alunos adicionados.
			$turma_id -> Id da turma que contém os dados a serem cadastrados ou modificados.
			$periodo_letivo_id -> Id do periodo letivo da turma.
		*/
		public function set_disc_turma($data, $turma_id, $periodo_letivo_id, $curso_id)
		{
			for($i = 0; $i < count($data['Disc_to_save']); $i++)
			{
				//somente disciplinas marcadas.
				if($data['Disc_to_save'][$i]['Value'] > 0)
				{
					//verificar se ja existe no banco
					$query = $this->db->query("
						SELECT Id FROM Disc_turma 
						WHERE Turma_id = ".$this->db->escape($turma_id)." 
						AND Disc_grade_id = ".$this->db->escape($data['Disc_to_save'][$i]['Disc_grade_id'])."");
					$r = $query->row_array();
					
					//carrega o array com os dados da disciplina para a turma em questão
					$dataToSave = array(
						'Turma_id' => $turma_id,
						'Disc_grade_id' => $data['Disc_to_save'][$i]['Disc_grade_id'],
						'Categoria_id' => $data['Disc_to_save'][$i]['Categoria_id'],
						'Periodo_letivo_id' => $periodo_letivo_id,
						'Professor_id' => $data['Disc_to_save'][$i]['Professor_id']
					 );
					
					//se não encontrou a disciplina cadastrada para a turma entao insere.
					if(empty($r))
						$this->db->insert('Disc_turma',$dataToSave);
					else //se a disciplina para a turma já estiver cadastrada então apenas atualiza
					{	//os dados como o professor e a categoria dela.
						$this->db->where('Disc_grade_id', $dataToSave['Disc_grade_id']);
						$this->db->where('Turma_id', $turma_id);
						$this->db->update('Disc_turma', $dataToSave);
					}

					//obter o id da disc turma
					$query = $this->db->query("
						SELECT Id FROM Disc_turma 
						WHERE Turma_id = ".$this->db->escape($turma_id)." 
						AND Disc_grade_id = ".$this->db->escape($dataToSave['Disc_grade_id'])."");
					$r = $query->row_array();
					
					//matricular o aluno na disciplina
					for ($j = 0; $j < count($data['Aluno_to_save']); $j++)
					{
						//verificar se o aluno já tem matricula na disciplina (disc_turma_id)
						$query = $this->db->query("
							SELECT m.Id FROM Matricula m 
							INNER JOIN Inscricao i ON i.Id = m.Inscricao_id 
							WHERE i.Aluno_id = ".$this->db->escape($data['Aluno_to_save'][$j]['Aluno_id'])." 
							AND m.Disc_turma_id = ".$this->db->escape($r['Id'])."");
						$r2 = $query->row_array();

						//pega a inscricao do aluno para o curso e período em questão
						$query = $this->db->query("
							SELECT i.Id FROM Inscricao i
							INNER JOIN Renovacao_matricula rm ON i.Id = rm.Inscricao_id 
							WHERE i.Curso_id = ".$this->db->escape($curso_id)." AND 
							i.Aluno_id = ".$this->db->escape($data['Aluno_to_save'][$j]['Aluno_id'])." AND 
							rm.Periodo_letivo_id = ".$periodo_letivo_id."");
						
						$inscricao_id = $query->row_array()['Id'];
						//carrega os dados da matricula do aluno na disciplina (disc_turma_id)
						$dataToSaveAluno = array(
							'Sub_turma' => $data['Aluno_to_save'][$j]['Sub_turma'],
							'Inscricao_id' => $inscricao_id,
							'Disc_turma_id' => $r['Id']
						);
						//se nao houver matricula do aluno na turma em questao, 
						//entao insere pra esse aluno.
						if(empty($r2))
							$this->db->insert('Matricula',$dataToSaveAluno);
						else
						{	//se ja existir da um update.
							$this->db->where('Inscricao_id', $inscricao_id);
							$this->db->where('Disc_turma_id', $r['Id']);
							$this->db->update('Matricula', $dataToSaveAluno);
						}
					}
				}
				else
				{
					//APAGA AS DISCIPLINAS REMOVIDAS, EXTREMO CUIDADO AO APAGAR UMA DISCIPLINA, 
					//POIS O SISTEMA DELETA EM CASCATA.
					$query = $this->db->query("
						DELETE FROM Disc_turma 
						WHERE Turma_id = ".$this->db->escape($turma_id)." 
						AND Disc_grade_id = ".$this->db->escape($data['Disc_to_save'][$i]['Disc_grade_id'])."");
				}
			}

			//remover aluno
			//busca todos os alunos cadastrado para a turma em questao.
			$alunos = $this->get_disc_turma_aluno($turma_id);
			for ($i = 0; $i < count($alunos); $i++)
			{
				$flag = 0;
				//aqui busca cada aluno que veio do banco na lista abaixo
				//aqueles que nao estiverem na lista abaixo sao apagados.
				for ($j = 0; $j < count($data['Aluno_to_save']); $j++)
				{
					if($alunos[$i]['Aluno_id'] == $data['Aluno_to_save'][$j]['Aluno_id'])
						$flag = 1;
				}
				//se nao encontrou na lista que veio do formulario entao remove.
				if($flag == 0)
				{
				//pega a inscricao do aluno para o curso e período em questão
					$query = $this->db->query("
						SELECT i.Id FROM Inscricao i 
						INNER JOIN Renovacao_matricula rm ON i.Id = rm.Inscricao_id 
						WHERE i.Curso_id = ".$this->db->escape($curso_id)." AND 
						i.Aluno_id = ".$alunos[$i]['Aluno_id']." AND 
						rm.Periodo_letivo_id = ".$periodo_letivo_id."");
					
					$inscricao_id = $query->row_array()['Id'];
					//remove pra todas as disciplinas o determinado aluno.
					for ($k=0; $k < count($data['Disc_to_save']); $k++) { 
						if($data['Disc_to_save'][$k]['Value'] > 0)//as que estao com zero foram removidas do banco e consequentemente removeu as matriculas ligadas a ela
						{
							$query = $this->db->query("
							DELETE FROM Matricula 
							WHERE Inscricao_id = '".$inscricao_id."' AND 
							Disc_turma_id IN (SELECT Id FROM Disc_turma WHERE Turma_id = ".$turma_id.")");
							//REMOVER A LIGACAO DO ALUNO COM TODAS AS DISCIPLINAS DO DISC_TURMA
						}
					}
				}
			}
		}
	}
?>