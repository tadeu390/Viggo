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
		*	RESPONSÁVEL POR RETORNAR UM DETERMINADO TIPO DE NOTA POR ALUNO EM UMA DETERMINADA DISCIPLINA.
		*
		*	$descricao_nota_id -> Tipo de nota que se deseja obter.
		*	$matricula_id -> Especifica a matricula do aluno na disciplina para o qual se deseja saber a nota.
		*	$etapa_id -> Id do bimestres que se quer saber a nota para a disciplina.
		*/
		public function get_nota($descricao_nota_id, $matricula_id, $etapa_id)
		{
			$query = $this->db->query("
				SELECT n.Valor AS Nota, n.Id AS Nota_id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				INNER JOIN Notas n ON m.Id = n.Matricula_id 
				WHERE n.Descricao_nota_id = ".$this->db->escape($descricao_nota_id)." AND n.Matricula_id = ".$this->db->escape($matricula_id)." AND 
				n.etapa_id = ".$this->db->escape($etapa_id)."");
			
			return $query->row_array();
		}
		/*!
		*	RESPONSÁVEL POR RECEBER UMA NOTA POR ALUNO EM UMA DETERMINADA DISCIPLINA, 
		*	E ENTÃO ATUALIZAR OU CRIAR A NOTA CONFORME AS VERIFICAÇÕES REALIZADAS DENTRO DO MÉTODO.
		*
		* 	$nota -> Nota enviada pelo usuário.
		*	$descricao_nota_id -> Tipo de nota adicionada.
		*	$matricula_id -> Matricula do aluno na disciplina.
		*	$etapa_id -> Id da etapa para a qual se deseja inserir a nota.
		*/
		public function set_notas($nota, $descricao_nota_id, $matricula_id, $etapa_id)
		{
			//verificar se já existe essa nota no banco para os parâmetros informados acima
			$query = $this->get_nota($descricao_nota_id, $matricula_id, $etapa_id);

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
					'etapa_id' => $etapa_id,
					'Matricula_id' => $matricula_id,
					'Descricao_nota_id' => $descricao_nota_id
				);
				$this->db->insert('Notas', $dataToSave);
				return "sucesso";
			}
		}
		/*!
		*	RESPONSÁVEL POR VALIDAR SE UMA NOTA SUBMETIDA DO FORMULÁRIO JUNTAMENTE COM AS NOTAS JÁ CADASTRADAS 
		*	NO BANCO DE DADOS, NÃO ULTRAPASSA O LIMITE ESTABELECIDO PARA A ETAPA.
		*
		*	$matricula_id -> Matricula do aluno na disciplina.
		*	$etapa_id -> Id da etapa para a qual se deseja saber o total de nota.
		* 	$nota -> Nota enviada pelo usuário.
		*	$descricao_nota_id -> Tipo de nota adicionada.
		*/
		public function validar_nota($matricula_id, $etapa_id, $nota, $descricao_nota_id)
		{
			$nota_temp = $nota;
			if($nota == null || $descricao_nota_id == RECUPERACAO_PARALELA)
				$nota = 0;
			
			//caso esteja alterando a nota, busca o valor já cadastrado no banco.
			if($descricao_nota_id == RECUPERACAO_PARALELA)
				$nota_banco = 0;				
			else
				$nota_banco = $this->get_nota($descricao_nota_id, $matricula_id, $etapa_id)['Nota'];

			$nota_banco = (empty($nota_banco) ? 0 : $nota_banco);

			$total_nota = $this->total_nota($matricula_id, $etapa_id);

			$total_nota = (empty($total_nota) ? 0 : $total_nota);
			//buscar o valor da etapa
			$query = $this->db->query("
				SELECT e.Valor FROM Etapa e 
				WHERE e.Id = ".$this->db->escape($etapa_id)."");

			$val_bimestre = $query->row_array()['Valor'];

			//validar desconsiderando a nota do banco, pois a soma é feita com a nota que o usuário informou
			if((($nota + $total_nota - $nota_banco) > $val_bimestre) || ($descricao_nota_id == RECUPERACAO_PARALELA && $nota_temp > $val_bimestre))
				return "invalido";
			else 
				return (string)($nota + $total_nota - $nota_banco);
		}
		/*!
		*	RESPONSÁVEL POR RETORNAR O TOTAL DE NOTA POR ALUNO EM UMA DETERMINADA ETAPA PARA UMA DETERMINADA DISCIPLINA.
		*
		*	$matricula_id -> Matricula do aluno na disciplina.
		*	$etapa_id -> Id da etapa para a qual se deseja saber o total de nota.
		*/
		public function total_nota($matricula_id, $etapa_id)
		{
			//buscar todas as notas já cadastradas
			$query = $this->db->query("
				SELECT SUM(Valor) AS Total_nota 
				FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON m.Disc_turma_id = dt.Id 
				INNER JOIN Notas n ON n.Matricula_id = m.Id AND n.Matricula_id = ".$this->db->escape($matricula_id)." AND 
				n.etapa_id = ".$this->db->escape($etapa_id)." 
				WHERE n.Descricao_nota_id != ".RECUPERACAO_PARALELA."");

			return $query->row_array()['Total_nota'];
		}
		/*!
		*	RESPONSÁVEL POR DETERMINAR SE UMA NOTA ESTÁ DENTRO DA MÉDIA OU NÃO.
		*
		*	$etapa_id -> Id da etapa que se deseja verificar a nota.
		*	$periodo_letivo_id -> Id do período letivo selecionado.
		*	$nota -> Nota que se quer saber se está na média ou não.
		*/
		public function status_nota($etapa_id, $periodo_letivo_id, $nota)
		{
			$CI = get_instance();
			$CI->load->model("Regras_model");

			//obter a media do período letivo
			$media = $CI->Regras_model->get_regras(FALSE, $periodo_letivo_id, FALSE, FALSE, FALSE)['Media'];

			$CI = get_instance();
			$CI->load->model("Etapa_model");

			$etapa = $CI->Etapa_model->get_etapa(FALSE, $etapa_id, FALSE);

			if($etapa['Tipo'] == ETAPA_EXTRA)//se for uma etapa extra, considerar a média cadastrada especificamente para a etapa.
				$media = $etapa['Media'];

			//obter o valor total da etapa, trata, caso nao seja possivel obter o valor da etapa, isso pode ocorrer caso haja erro nas datas de inicio e fim da etapa
			$val_etapa = (!empty($etapa['Valor']) ? $etapa['Valor'] : 0);

			//calcular o valor da media para a etapa de acordo com o percentual 'media'
			$media_etapa = ($media / 100) * $val_etapa;

			if($nota >= $media_etapa)
				return "ok";
			else
				return "abaixo_da_media";
		}
		/*!
		*	RESPONSÁVEL POR REMOVER UMA COLUNA DE NOTA PARA TODOS OS ALUNOS EM UMA DETERMINADA DISCIPLINA.
		*
		*	$descricao_nota_id -> Nota que se quer apagar.
		*	$turma_id -> Id da turma que se deseja remover as notas.
		*	$disciplina_id -> Id da disciplina que se deseja remover as notas.
		*	$etapa_id -> Id da etapa que se deseja remover as notas.
		*/
		public function remover_coluna_nota($descricao_nota_id, $turma_id, $disciplina_id, $etapa_id)
		{
			$query = $this->db->query("
				SELECT n.Id FROM Disc_turma dt 
				INNER JOIN Disc_grade dg ON dt.Disc_grade_id = dg.Id 
				INNER JOIN Matricula m ON dt.Id = m.Disc_turma_id 
				INNER JOIN Notas n ON m.Id = n.Matricula_id 
				WHERE dt.Turma_id = ".$this->db->escape($turma_id)." AND dg.Disciplina_id = ".$this->db->escape($disciplina_id)." AND 
				n.etapa_id = ".$this->db->escape($etapa_id)." AND n.Descricao_nota_id = ".$this->db->escape($descricao_nota_id)."");

			$result = $query->result_array();
			for($i = 0; $i < COUNT($result); $i++)
			{
				$query = $this->db->query("DELETE FROM Notas WHERE Id = ".$result[$i]['Id']."");
			}
		}
		/*!
		*	RESPONSÁVEL POR FAZER A SOMA DAS NOTAS DE UM ALUNO EM UMA DISCIPLINA E RETORNAR O STATUS DO MESMO.
		*
		*	$matricula_id -> Id da matrícula do aluno na disciplina.
		*	$etapas -> Id(s) da(s) etapa(s) utilizada(s) na soma para determinar o status.
		*	$media -> Valor mínimo estipulado que o aluno deve alcançar para que possa ser aprovado.
		*/
		public function situacao_nota_aluno_disciplina($matricula_id, $etapas, $media)
		{
			$query = $this->db->query("
				SELECT SUM(Valor) AS Total FROM Notas 
				WHERE Matricula_id = ".$this->db->escape($matricula_id)." AND Etapa_id IN (".str_replace("'", "", $this->db->escape($etapas)).")");

			if((empty($query->row_array()['Total']) ? 0 : $query->row_array()['Total']) >= $media)
				return APROVADO;
			return RECUPERACAO;
		}
	}
?>