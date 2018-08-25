<?php
	require_once("Geral_model.php");//INCLUI A CLASSE GENÉRICA.
	/*!
	*	ESTA MODAL TRATA DAS OPERAÇÕES NO BANCO DE DADOS REFERENTE AS INFORMAÇÕES DE NOTAS.
	*/
	class Nota_model extends Geral_model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR UMA DETERMINADA NOTA POR ALUNO EM UMA DETERMINADA DISCIPLINA.
		*
		*	$descricao_nota_id -> Tipo de nota que se deseja obter.
		*	$matricula_id -> Especifica a matricula do aluno para o qual se deseja saber a nota.
		*	$turma_id -> Id da turma na qual se encontra o aluno.
		*	$disciplina_id -> Id da disciplina que se deseja saber a nota.
		*	$bimestre_id -> Id do bimestres que se quer saber a nota para a disciplina.
		*/
		public function get_nota($descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			$query = $this->db->query("
				SELECT n.Valor AS Nota, n.Id AS Nota_id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				INNER JOIN Notas n ON m.Id = n.Matricula_id 
				WHERE n.Descricao_nota_id = ".$this->db->escape($descricao_nota_id)." AND n.Matricula_id = ".$this->db->escape($matricula_id)." AND 
				dt.Turma_id = ".$this->db->escape($turma_id)." AND dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				n.Bimestre_id = ".$this->db->escape($bimestre_id)."");
			
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UMA NOTA POR ALUNO EM UMA DETERMINADA DISCIPLINA, 
		*	E ENTÃO ATUALIZAR OU CRIAR A NOTA CONFORME AS VERIFICAÇÕES REALIZADAS DENTRO DO MÉTODO.
		*
		* 	$nota -> Nota enviada pelo usuário.
		*	$descricao_nota_id -> Tipo de nota adicionada.
		*	$matricula_id -> Matricula do aluno.
		*	$turma_id -> Id da turma na qual se encontra o aluno.
		*	$disciplina_id -> Id da disciplina.
		*	$bimestre_id -> Id do bimestre para o qual se deseja inserir a nota.
		*/
		public function set_notas($nota, $descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			//verificar se já existe essa nota no banco para os parâmetros informados acima
			$query = $this->get_nota($descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id);

			if(!empty($query))
			{
				$dataToSave = array(
					'Valor' => $nota
				);
				$this->db->where('Id', $query['Nota_id']);
				$this->db->update('Notas', $dataToSave);
				return "sucesso";
			}
			else
			{
				$dataToSave = array(
					'Valor' => $nota,
					'Bimestre_id' => $bimestre_id,
					'Matricula_id' => $matricula_id,
					'Descricao_nota_id' => $descricao_nota_id
				);
				$this->db->insert('Notas', $dataToSave);
				return "sucesso";
			}
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR SE UMA NOTA SUBMETIDA DO FORMULÁRIO JUNTAMENTE COM AS NOTAS JÁ CADASTRADAS 
		*	NO BANCO DE DADOS, NÃO ULTRAPASSA O LIMITE ESTABELECIDO PARA O BIMESTRE.
		*
		*	$matricula_id -> Matricula do aluno.
		*	$bimestre_id -> Id do bimestre para o qual se deseja saber o total de nota.
		*	$turma_id -> Id da turma na qual se encontra o aluno.
		*	$disciplina_id -> Id da disciplina.
		* 	$nota -> Nota enviada pelo usuário.
		*	$descricao_nota_id -> Tipo de nota adicionada.
		*/
		public function validar_nota($matricula_id, $bimestre_id, $turma_id, $disciplina_id, $nota, $descricao_nota_id)
		{
			if($nota == null)
				$nota = 0;
			//caso esteja alterando a nota, busca o valor já cadastrado no banco.
			$nota_banco = $this->get_nota($descricao_nota_id, $matricula_id, $turma_id, $disciplina_id, $bimestre_id)['Nota'];
			
			$nota_banco = (empty($nota_banco) ? 0 : $nota_banco);

			$total_nota = $this->total_nota($matricula_id, $turma_id, $disciplina_id, $bimestre_id);

			//buscar o valor do bimestre
			$query = $this->db->query("
				SELECT b.Valor FROM Bimestre b 
				WHERE b.Id = ".$this->db->escape($bimestre_id)."");

			$val_bimestre = $query->row_array()['Valor'];

			//validar desconsiderando a nota do banco, pois a soma é feita com a nota que o usuário informou
			if(($nota + $total_nota - $nota_banco) > $val_bimestre)
				return "invalido";
			else 
				return ($nota + $total_nota - $nota_banco);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O TOTAL DE NOTA POR ALUNO EM UM DETERMINADO BIMESTRE PARA UMA DETERMINADA DISCIPLINA.
		*
		*	$matricula_id -> Matricula do aluno.
		*	$turma_id -> Id da turma na qual se encontra o aluno.
		*	$disciplina_id -> Id da disciplina.
		*	$bimestre_id -> Id do bimestre para o qual se deseja saber o total de nota.
		*/
		public function total_nota($matricula_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			//buscar todas as notas já cadastradas
			$query = $this->db->query("
				SELECT SUM(Valor) AS Total_nota 
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				INNER JOIN Notas n ON n.Matricula_id = m.Id AND n.Matricula_id = ".$this->db->escape($matricula_id)." AND n.Bimestre_id = ".$this->db->escape($bimestre_id)." 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND dg.Disciplina_id = ".$this->db->escape($disciplina_id)."");

			return $query->row_array()['Total_nota'];
		}
		/*!
		*	RESPONSÁVEL POR VERIFICAR SE O ALUNO ESTÁ DENTRO DA MÉDIA OU NÃO EM UMA DETERMINADA DISCIPLINA EM UM DETERMINADADO BIMESTRE.
		*
		*	$matricula_id -> Matricula do aluno.
		*	$turma_id -> Id da turma na qual se encontra o aluno.
		*	$disciplina_id -> Id da disciplina.
		*	$bimestre_id -> Id do bimestre para o qual se deseja saber o total de nota.			
		*	$periodo_letivo_id -> Id do período letivo para se obter as regras do período.
		*/
		public function status_nota_total_bimestre($matricula_id, $turma_id, $disciplina_id, $bimestre_id, $periodo_letivo_id)
		{
			//obter o total de nota
			$total_nota = $this->total_nota($matricula_id, $turma_id, $disciplina_id, $bimestre_id);

			$CI = get_instance();
			$CI->load->model("Regras_model");

			//obter a media do período letivo
			$media = $CI->Regras_model->get_regras(FALSE, $periodo_letivo_id, FALSE, FALSE, FALSE)['Media'];

			$CI = get_instance();
			$CI->load->model("Bimestre_model");

			//obter o valor total do bimestre, trata, caso nao seja possivel obter o valor do bimestre, isso pode ocorrer caso haja erro nas datas de inicio e fim do bimestre
			$val_bimestre = (!empty($CI->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Valor']) ? $CI->Bimestre_model->get_bimestre(FALSE, $bimestre_id)['Valor'] : 0);

			//calcular o valor da media para o bimestre de acordo com o percentual 'media'
			$media_bimestre = ($media / 100) * $val_bimestre;

			if($total_nota >= $media_bimestre)
				return "ok";
			else
				return "abaixo_da_media";
		}
		/*!
			RESPONSÁVEL POR REMOVER UMA COLUNA DE NOTA PARA TODOS OS ALUNOS EM UMA DETERMINADA DISCIPLINA.
		*/
		public function remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $bimestre_id)
		{
			$query = $this->db->query("
				SELECT n.Id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Notas n ON m.Id = n.Matricula_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				n.Bimestre_id = ".$this->db->escape($bimestre_id)." AND n.Descricao_nota_id = ".$this->db->escape($descricao_nota_id)."");

			$result = $query->result_array();
			for($i = 0; $i < COUNT($result); $i++)
			{
				$query = $this->db->query("DELETE FROM Notas WHERE Id = ".$result[$i]['Id']."");
			}
		}
	}
?>