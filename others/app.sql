DROP TABLE IF EXISTS usuario;
DROP TABLE IF EXISTS acesso;
DROP TABLE IF EXISTS grupo;
DROP TABLE IF EXISTS modulo;
DROP TABLE IF EXISTS menu;

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

CREATE TABLE configuracoes_geral(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    itens_por_pagina INT NOT NULL
);

INSERT INTO grupo(nome,ativo) VALUES('Administrador',1);
INSERT INTO usuario (nome,email,senha,ativo,grupo_id) VALUES(
					'Admin','admin@dominio.com.br','admin123',1,1);

INSERT INTO menu(ativo,nome,ordem) VALUES(1,'Administrativo',1);
INSERT INTO modulo(ativo,nome,ordem,descricao,icone,menu_id,url) VALUES(1,'Módulos',1,'Lista de módulos','fa fa-list-alt',1,'Modulo');
INSERT INTO modulo(ativo,nome,ordem,descricao,icone,menu_id,url) VALUES(1,'Menus',1,'Lista de menus','fa fa-navicon',1,'Menu');
INSERT INTO modulo(ativo,nome,ordem,descricao,icone,menu_id,url) VALUES(1,'Grupos',1,'Lista de grupos','fa fa-th-large',1,'Grupo');
INSERT INTO modulo(ativo,nome,ordem,descricao,icone,menu_id,url) VALUES(1,'Usuários',1,'Lista de usuários','glyphicon glyphicon-user',1,'Usuario');
INSERT INTO acesso(modulo_id,criar,visualizar,atualizar,apagar,grupo_id) VALUES(3,1,1,1,1,1);