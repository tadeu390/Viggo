<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS RENOVAÇÕES DE MATRICULAS DOS ALUNOS.
	*/
	class Renovacao_matricula_model extends CI_Model 
	{
		public function __construct()
		{
			$this->load->database();
		}
		/*!
			RESPONSÁVEL POR CANCELAR UMA MATRÍCULA DO ALUNO, ISSO SÓ PODERÁ SER EXECUTADO PELO USUÁRIO ENQUANTO 
			O ALUNO CONTIDO NESTA INSCRIÇÃO NÃO ESTIVER EM ALGUMA TURMA. O TRATAMENTO PARA PODER EXECUTAR OU NÃO 
			EXECUTAR ESTE MÉTODO SERÁ FEITO NA VIEW ONDE IRÁ DESABILITAR O BOTÃO DE EDITAR INSCRIÇÃO QUANDO O ALUNO 
			JÁ ESTIVER EM ALGUMA TURMA.
			
			$inscricao_id -> Id da inscrição do aluno.
		*/
		public function delete_matricula($inscricao_id)
		{
			$query = $this->db->query("
				DELETE FROM Renovacao_matricula 
				WHERE Inscricao_id = ".$this->db->escape($inscricao_id)."");
		}
		/*!
		*	RESPONSÁVEL POR CADASTRAR/ATUALIZAR UMA RENOVAÇÃO DE MATRÍCULA NO BANCO DE DADOS.
		*
		*	$data -> Contém os dados da renovação.
		*/
		public function set_renovacao_matricula($data)
		{
			if(!empty($this->valida_matricula($data['Inscricao_id'], $data['Periodo_letivo_id'])))
				return "Este aluno já possui a matrícula criada ou renovada.";

			if(empty($data['Id']))
				return $this->db->insert('Renovacao_matricula',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Renovacao_matricula', $data);
			}
		}
		/*!
			RESPONSÁVEL POR VALIDAR SE UMA MATRICULA PODE SER FEITA. ELA PODE CASO A MESMA AINDA NÃO SE ENCONTRAR 
			NA TABELA DE RENOVACAO COM O PERÍODO LETIVO PASSADO COMO PARÂMETRO.

			$inscricao_id -> Id da inscrição que se deseja validar a matrícula.
			$periodo_letivo_id -> Id do período letivo para o qual se deseja validar a matrícula.
		*/
		public function valida_matricula($inscricao_id, $periodo_letivo_id)
		{
			$query = $this->db->query("
				SELECT Id FROM Renovacao_matricula 
				WHERE Inscricao_id = ".$this->db->escape($inscricao_id)." AND 
				Periodo_letivo_id = ".$this->db->escape($periodo_letivo_id)."");

			return $query->row_array();
		}
	}
?>