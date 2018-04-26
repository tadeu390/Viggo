DROP TABLE IF EXISTS boletim;
DROP TABLE IF EXISTS turma_aluno;
DROP TABLE IF EXISTS aluno;
DROP TABLE IF EXISTS disciplina_curso;
DROP TABLE IF EXISTS turma_disciplina;
DROP TABLE IF EXISTS disciplina;
DROP TABLE IF EXISTS categoria;
DROP TABLE IF EXISTS turma;
DROP TABLE IF EXISTS curso;
DROP TABLE IF EXISTS acesso;
DROP TABLE IF EXISTS modulo;
DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS grupo;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS configuracoes_geral;

CREATE TABLE grupo(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	ativo BOOLEAN,
	nome VARCHAR(100)
);

CREATE TABLE usuario (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	ultimo_acesso TIMESTAMP,
	grupo_id INT,
	CONSTRAINT fk_grupo_usuario 
		FOREIGN KEY(grupo_id) REFERENCES grupo(id),
	ativo BOOLEAN,
	nome VARCHAR(70) NOT NULL,
	email VARCHAR(70) NOT NULL,
	senha VARCHAR(200) NOT NULL
);

CREATE TABLE menu(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	ativo BOOLEAN,
	nome VARCHAR(100),
	ordem INT
);

CREATE TABLE modulo(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	ativo BOOLEAN,
	nome VARCHAR(30),
	descricao VARCHAR(100),
	url VARCHAR(20),
	ordem INT,
	icone VARCHAR(50),
	menu_id INT,
	CONSTRAINT fk_menu_modulo 
		FOREIGN KEY(menu_id) REFERENCES menu(id)
);

CREATE TABLE acesso(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	modulo_id INT,
	criar BOOLEAN,
	visualizar BOOLEAN,
	atualizar BOOLEAN,
	apagar BOOLEAN,
	CONSTRAINT fk_modulo_acesso 
		FOREIGN KEY(modulo_id) REFERENCES modulo(id),
	grupo_id INT,
	CONSTRAINT fk_grupo_acesso 
		FOREIGN KEY(grupo_id) REFERENCES grupo(id)
);

CREATE TABLE categoria(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	nome VARCHAR(100)
);

CREATE TABLE curso (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	nome VARCHAR(100),
	ativo BOOLEAN,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE turma (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	ativo BOOLEAN,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	nome VARCHAR(100),
	ano_letivo INT,
	curso_id INT NOT NULL,
	CONSTRAINT fk_curso
		FOREIGN KEY(curso_id) REFERENCES curso(id)
);

CREATE TABLE disciplina (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	nome VARCHAR(100),
	ativo BOOLEAN,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	categoria_id INT NOT NULL,
	CONSTRAINT fk_categoria
		FOREIGN KEY(categoria_id) REFERENCES categoria(id)
);

CREATE TABLE turma_disciplina (
	turma_id INT NOT NULL,
	disciplina_id INT NOT NULL,
	CONSTRAINT pk_turma_disciplina 
		PRIMARY KEY (turma_id, disciplina_id),
	CONSTRAINT fk_turma
		FOREIGN KEY (turma_id) REFERENCES turma(id),
	CONSTRAINT fk_disciplina
		FOREIGN KEY (disciplina_id) REFERENCES disciplina(id)
);

CREATE TABLE disciplina_curso (
	disciplina_id INT NOT NULL,
	curso_id INT NOT NULL,
	CONSTRAINT pk_disciplina_curso 
		PRIMARY KEY(disciplina_id,curso_id),
	CONSTRAINT fk_disciplina_disciplina_curso
		FOREIGN KEY (disciplina_id) REFERENCES disciplina(id),
	CONSTRAINT fk_curso_disciplina_curso
		FOREIGN KEY(curso_id) REFERENCES curso(id)
);

CREATE TABLE aluno (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	ativo BOOLEAN,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	matricula INT,
	nome VARCHAR(100),
	sexo char(1),
	data_nascimento DATE,
	numero_chamada INT,
	turma_id INT,
	curso_id INT NOT NULL,
	ano_letivo INT,
	CONSTRAINT fk_turma_aluno
		FOREIGN KEY (turma_id) REFERENCES turma(id),
	CONSTRAINT fk_curso_aluno
		FOREIGN KEY (curso_id) REFERENCES curso(id)
);

CREATE TABLE turma_aluno(
	turma_id INT,
	aluno_id INT,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT pk_turma_aluno 
		PRIMARY KEY (turma_id,aluno_id),
	CONSTRAINT fk_turma_turma_aluno 
		FOREIGN KEY (turma_id) REFERENCES turma(id),
	CONSTRAINT fk_aluno_turma_aluno 
		FOREIGN KEY (aluno_id) REFERENCES aluno(id)
);

CREATE TABLE boletim (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	ativo BOOLEAN,
	data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	nota1 DOUBLE DEFAULT NULL,
	falta1 INT DEFAULT NULL,
	nota2 DOUBLE DEFAULT NULL,
	falta2 INT DEFAULT NULL,
	nota3 DOUBLE DEFAULT NULL,
	falta3 INT DEFAULT NULL,
	nota4 DOUBLE DEFAULT NULL,
	falta4 INT DEFAULT NULL,
	bimestre INT,
	aluno_id INT NOT NULL,
	disciplina_id INT NOT NULL,
	turma_id INT NOT NULL,
	nota_final DOUBLE DEFAULT NULL,
	status VARCHAR(11),
	exame DOUBLE DEFAULT NULL,
	CONSTRAINT fk_aluno_boletim
		FOREIGN KEY (aluno_id) REFERENCES aluno(id),
	CONSTRAINT fk_disciplina_boletim
		FOREIGN KEY (disciplina_id) REFERENCES disciplina(id),
	CONSTRAINT fk_turma_boletim
		FOREIGN KEY (turma_id) REFERENCES turma(id)
);

CREATE TABLE configuracoes_geral (
  	id int(11) NOT NULL,
	media double NOT NULL,
  	total_faltas int(11) NOT NULL,
  	primeiro_bimestre int(11) NOT NULL,
  	segundo_bimestre int(11) NOT NULL,
  	terceiro_bimestre int(11) NOT NULL,
  	quarto_bimestre int(11) NOT NULL,
  	itens_por_pagina int(11) NOT NULL
);

INSERT INTO grupo (id, data_registro, ativo, nome) VALUES
(1, '2017-12-28 22:40:46', 1, 'Administrador'),
(2, '2017-12-29 06:17:22', 1, 'Professor'),
(3, '2018-01-18 19:04:31', 1, 'Coordenador');

INSERT INTO usuario (nome,email,senha,ativo,grupo_id) VALUES(
					'Admin','admin@dominio.com.br','admin123',1,1);

INSERT INTO menu (id, data_registro, ativo, nome, ordem) VALUES
(1, '2017-12-28 22:40:47', 1, 'Gestão', 1),
(2, '2018-01-18 21:22:24', 1, 'Acadêmico', 2);

INSERT INTO modulo (id, data_registro, ativo, nome, descricao, url, ordem, icone, menu_id) VALUES
(1, '2017-12-28 22:40:47', 1, 'Módulos', 'Lista de módulos', 'Modulo', 1, 'fa fa-list-alt', 1),
(2, '2017-12-28 22:40:47', 1, 'Menus', 'Lista de menus', 'Menu', 1, 'fa fa-navicon', 1),
(3, '2017-12-28 22:40:47', 1, 'Grupos', 'Lista de grupos', 'Grupo', 1, 'fa fa-th-large', 1),
(4, '2017-12-28 22:40:48', 1, 'Usuários', 'Lista de usuários', 'Usuario', 1, 'glyphicon glyphicon-user', 1),
(5, '2017-12-29 05:55:14', 1, 'Disciplina', 'Disciplinas', 'Disciplina', 1, ' glyphicon glyphicon-paperclip', 2),
(6, '2017-12-29 20:09:51', 1, 'Curso', 'Curso', 'Curso', 2, 'glyphicon glyphicon-folder-open', 2),
(7, '2017-12-29 21:25:48', 1, 'Aluno', 'Alunos', 'Aluno', 3, 'glyphicon glyphicon-user', 2),
(8, '2017-12-30 01:15:48', 1, 'Turma', 'Turma', 'Turma', 4, 'glyphicon glyphicon-book', 2),
(9, '2018-01-18 19:28:13', 1, 'Notas', 'Notas', 'Nota', 5, 'glyphicon glyphicon-file', 2),
(10, '2018-02-06 16:08:54', 1, 'Boletim', 'Boletim', 'boletim', 6, 'glyphicon glyphicon-list-alt', 2);



INSERT INTO acesso (id, data_registro, modulo_id, criar, visualizar, atualizar, apagar, grupo_id) VALUES
(1, '2017-12-28 22:40:48', 3, 1, 1, 1, 1, 1),
(2, '2017-12-28 22:44:07', 1, 1, 1, 1, 1, 1),
(3, '2017-12-28 22:44:07', 2, 1, 1, 1, 1, 1),
(4, '2017-12-28 22:44:07', 4, 1, 1, 1, 1, 1),
(5, '2017-12-29 05:58:10', 5, 1, 1, 1, 1, 1),
(6, '2017-12-29 06:17:22', 1, 0, 0, 0, 0, 2),
(7, '2017-12-29 06:17:23', 2, 0, 0, 0, 0, 2),
(8, '2017-12-29 06:17:23', 3, 0, 0, 0, 0, 2),
(9, '2017-12-29 06:17:23', 4, 0, 0, 0, 0, 2),
(10, '2017-12-29 06:17:23', 5, 1, 1, 1, 1, 2),
(11, '2017-12-29 20:12:49', 6, 1, 1, 1, 1, 1),
(12, '2017-12-29 21:26:26', 7, 1, 1, 1, 1, 1),
(13, '2017-12-30 01:17:07', 8, 1, 1, 1, 1, 1),
(14, '2018-01-18 19:04:31', 1, 0, 0, 0, 0, 3),
(15, '2018-01-18 19:04:31', 2, 0, 0, 0, 0, 3),
(16, '2018-01-18 19:04:31', 3, 0, 0, 0, 0, 3),
(17, '2018-01-18 19:04:31', 4, 1, 1, 1, 1, 3),
(18, '2018-01-18 19:04:32', 5, 0, 0, 0, 0, 3),
(19, '2018-01-18 19:04:32', 6, 0, 0, 0, 0, 3),
(20, '2018-01-18 19:04:32', 7, 0, 0, 0, 0, 3),
(21, '2018-01-18 19:04:32', 8, 0, 0, 0, 0, 3),
(22, '2018-01-18 19:29:06', 9, 1, 1, 1, 1, 1),
(23, '2018-02-06 16:09:29', 10, 1, 1, 1, 1, 1);

INSERT INTO categoria(nome) VALUES('Matérias Técnicas');
INSERT INTO categoria(nome) VALUES('Matérias Ensino Médio');
INSERT INTO configuracoes_geral (id, media, total_faltas, primeiro_bimestre, segundo_bimestre, terceiro_bimestre, quarto_bimestre, itens_por_pagina) VALUES(1, 60, 200, 20, 25, 25, 30, 5);