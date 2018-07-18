<?php
	/*!
	*	ESTA MODEL TRATA DAS OPERAÇÕES NA BASE DE DADOS REFERENTE AS RENOVAÇÕES DE MATRICULAS DO SISTEMA.
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
			if(empty($data['Id']))
				return $this->db->insert('Renovacao_matricula',$data);
			else
			{
				$this->db->where('Id', $data['Id']);
				return $this->db->update('Renovacao_matricula', $data);
			}
		}
	}
?>