/*TODA VEZ QUE REIMPORTAR O BANCO, APAGA AS TABELAS ANTIGAS PARA EVITAR CONFLITOS*/
DROP TABLE IF EXISTS Acesso_padrao;
DROP TABLE IF EXISTS Acesso;
DROP TABLE IF EXISTS Modulo;
DROP TABLE IF EXISTS Menu;
DROP TABLE IF EXISTS Senha;
DROP TABLE IF EXISTS Log;
DROP TABLE IF EXISTS Notas;
DROP TABLE IF EXISTS Descricao_nota;
DROP TABLE IF EXISTS Calendario_presenca;
DROP TABLE IF EXISTS Matricula;
DROP TABLE IF EXISTS Conteudo;
DROP TABLE IF EXISTS Disc_hor;
DROP TABLE IF EXISTS Horario;
DROP TABLE IF EXISTS Disc_turma;
DROP TABLE IF EXISTS Renovacao_matricula;
DROP TABLE IF EXISTS Doc_inscricao;
DROP TABLE IF EXISTS Inscricao;
DROP TABLE IF EXISTS Doc;
DROP TABLE IF EXISTS Endereco;
DROP TABLE IF EXISTS Aluno;
DROP TABLE IF EXISTS Professor;
DROP TABLE IF EXISTS Usuario;
DROP TABLE IF EXISTS Grupo;
DROP TABLE IF EXISTS Settings;
DROP TABLE IF EXISTS Settings_email;
DROP TABLE IF EXISTS Disc_grade;
DROP TABLE IF EXISTS Grade;
DROP TABLE IF EXISTS Disciplina;
DROP TABLE IF EXISTS Curso;
DROP TABLE IF EXISTS Intervalo;
DROP TABLE IF EXISTS Etapa;
DROP TABLE IF EXISTS Periodo_letivo;
DROP TABLE IF EXISTS Modalidade;
DROP TABLE IF EXISTS Categoria;
DROP TABLE IF EXISTS Turma;
DROP FUNCTION IF EXISTS mes_por_numero;
###################

/*ABAIXO CRIA AS TABELAS*/
CREATE TABLE Grupo(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(20) NOT NULL
);

CREATE TABLE Usuario(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Data_nascimento TIMESTAMP,
    Sexo BOOLEAN,
    Grupo_id INT NOT NULL,
    Status INT DEFAULT 1 COMMENT '0 - Conta ok 1 - Primeiro acesso 2 - Esqueceu a senha',
    Codigo_ativacao INT DEFAULT 0,
    Contador_tentativa INT DEFAULT 0,
    Data_ultima_tentativa TIMESTAMP,
    Email_notifica_nova_conta BOOLEAN DEFAULT 0,
    CONSTRAINT FK_GRUPO_USUARIO 
        FOREIGN KEY (Grupo_id) REFERENCES Grupo(Id) 
);

CREATE TABLE Aluno (
    Id INT(8) ZEROFILL AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'RA',
    Usuario_id INT NOT NULL,
    Naturalidade VARCHAR(100),
    Uf CHAR(2),
    Nome_mae VARCHAR(100),
    Nome_pai VARCHAR(100),
    Especial BOOLEAN, /*Portador de necessidades especiais*/ 
    Identificacao VARCHAR(100),
    Escola VARCHAR(20), /*pública ou privada antes do CEP*/ 
    Cpf VARCHAR(15),
    Rg VARCHAR(20),
    Orgao_expedidor VARCHAR(20),
    Data_expedicao DATE,
    Titulo_eleitor VARCHAR(15),
    Zona_eleitoral VARCHAR(5),
    Secao_eleitoral VARCHAR(5),
    Uf_titulo CHAR(2),
    Cor VARCHAR(30),
    Sit_ensino_medio VARCHAR(20),
    CONSTRAINT FK_USUARIO_ALUNO 
        FOREIGN KEY(Usuario_id) REFERENCES Usuario(Id)
);

CREATE TABLE Endereco (
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Rua VARCHAR(100),
    Bairro VARCHAR(40),
    Municipio VARCHAR(50),
    Zona VARCHAR(10),
    Transp_publico BOOLEAN, /*ESSE CAMPO SO ESTA AQUI POR CONTA DA IMPRESSAO DA FICHA, TALVEZ COLOCAR EM ALUNO*/
    Uf CHAR(2),
    Cep VARCHAR(15),
    Telefone_aluno VARCHAR(15),
    Telefone_responsavel VARCHAR(15),
    Aluno_id INT(8) ZEROFILL NOT NULL,
    CONSTRAINT FK_ALUNO_ENDERECO 
        FOREIGN KEY (Aluno_id) REFERENCES Aluno(Id)
);

CREATE TABLE Curso(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(100) NOT NULL
);

CREATE TABLE Modalidade(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Nome VARCHAR(100) NOT NULL,
    Ativo BOOLEAN DEFAULT TRUE,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Periodo_letivo(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Periodo VARCHAR(100) NOT NULL,
    Limite_falta INT,
    Media INT NOT NULL,
    Modalidade_id INT NOT NULL,
    Avaliar_faltas BOOLEAN NOT NULL,
    Dias_letivos INT NOT NULL,
    Duracao_aula INT NOT NULL COMMENT 'minutos',
    Hora_inicio_aula TIME NOT NULL,
    Quantidade_aula INT NOT NULL,
    Reprovas INT NOT NULL,
    Qtd_minima_aluno INT UNSIGNED,
    Qtd_maxima_aluno INT UNSIGNED,
    CONSTRAINT FK_MODALIDADE_PERIODO_LETIVO
        FOREIGN KEY(Modalidade_id) REFERENCES Modalidade(Id)
);

CREATE TABLE Inscricao(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Aluno_id INT(8) ZEROFILL NOT NULL,
    Curso_id INT NOT NULL,
    Periodo_letivo_id INT NOT NULL,
    CONSTRAINT FK_ALUNO_INSCRICAO 
        FOREIGN KEY(Aluno_id) REFERENCES Aluno(Id),
    CONSTRAINT FK_CURSO_INSCRICAO 
        FOREIGN KEY(Curso_id) REFERENCES Curso(Id),
    CONSTRAINT FK_PERIODO_LETIVO_INSCRICAO 
        FOREIGN KEY (Periodo_letivo_id) REFERENCES Periodo_letivo(Id)
);

CREATE TABLE Doc(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(70),
    Tipo INT COMMENT 'Tipo 1 e Tipo 2'
);

CREATE TABLE Doc_inscricao(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Inscricao_id INT NOT NULL,
    Doc_id INT NOT NULL,
    Arquivo VARCHAR(300),
    Outros VARCHAR(50),
    CONSTRAINT FK_INSCRICAO_DOC_INSCRICAO 
        FOREIGN KEY (Inscricao_id) REFERENCES Inscricao(Id),
    CONSTRAINT FK_DOC_DOC_INSCRICAO 
        FOREIGN KEY (Doc_id) REFERENCES Doc(Id)
);

CREATE TABLE Menu(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(20) NOT NULL,
    Ordem INT 
);

CREATE TABLE Modulo(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(20) NOT NULL,
    Descricao VARCHAR(50),
    Url VARCHAR(100) NOT NULL,
    Ordem INT, 
    Icone VARCHAR(50),
    Menu_id INT,
    CONSTRAINT FK_MENU_MODULO 
        FOREIGN KEY(Menu_id) REFERENCES Menu(Id)       
);

CREATE TABLE Acesso(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Criar BOOLEAN,
    Ler BOOLEAN,
    Atualizar BOOLEAN,
    Remover BOOLEAN,
    Usuario_id INT,
    Modulo_id INT,
    CONSTRAINT FK_USUARIO_ACESSO 
        FOREIGN KEY(Usuario_id) REFERENCES Usuario(Id)
            ON DELETE CASCADE,
    CONSTRAINT FK_MODULO_ACESSO 
        FOREIGN KEY(Modulo_id) REFERENCES Modulo(Id)
);

CREATE TABLE Acesso_padrao(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Criar BOOLEAN,
    Ler BOOLEAN,
    Atualizar BOOLEAN,
    Remover BOOLEAN,
    Grupo_id INT,
    Modulo_id INT,
    CONSTRAINT FK_GRUPO_ACESSO_PADRAO 
        FOREIGN KEY(Grupo_id) REFERENCES Grupo(Id),
    CONSTRAINT FK_MODULO_ACESSO_PADRAO 
        FOREIGN KEY(Modulo_id) REFERENCES Modulo(Id)
);

CREATE TABLE Senha (
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Valor VARCHAR(100) NOT NULL,
    Usuario_id INT NOT NULL,
    CONSTRAINT FK_USUARIO_SENHA
        FOREIGN KEY(Usuario_id) REFERENCES Usuario(Id)
            ON DELETE CASCADE 
);

CREATE TABLE Log(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Usuario_id INT,
    Sucesso BOOLEAN,
    Ip VARCHAR(20) NOT NULL,
    CONSTRAINT FK_USUARIO_LOG 
        FOREIGN KEY (Usuario_id) REFERENCES Usuario(Id) 
            ON DELETE CASCADE 
);

CREATE TABLE Settings(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Itens_por_pagina INT NOT NULL
);

CREATE TABLE Settings_email(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Email VARCHAR(70) NOT NULL,
    Descricao VARCHAR(200),
    Usuario VARCHAR(30),
    Senha VARCHAR(30),
    Protocolo VARCHAR(10) NOT NULL,
    Host VARCHAR(70) NOT NULL,
    Porta INT NOT NULL
);

CREATE TABLE Disciplina(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE, 
    Nome VARCHAR(200) NOT NULL,
    Apelido VARCHAR(40) NOT NULL
);

CREATE TABLE Renovacao_matricula(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Inscricao_id INT NOT NULL,
    Periodo_letivo_id INT NOT NULL,
    CONSTRAINT FK_INSCRICAO_RENOVACAO_MATRICULA 
        FOREIGN KEY(Inscricao_id) REFERENCES Inscricao(Id)
            ON DELETE CASCADE,
    CONSTRAINT FK_PERIODO_LETIVO_RENOVACAO_MATRICULA 
        FOREIGN KEY(Periodo_letivo_id) REFERENCES Periodo_letivo(Id)
);

CREATE TABLE Etapa(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(50) NOT NULL,
    Valor INT NOT NULL,
    Media INT NOT NULL,
    Data_abertura TIMESTAMP NOT NULL,
    Data_fechamento TIMESTAMP NOT NULL,
    Data_inicio TIMESTAMP,
    Data_fim TIMESTAMP,
    Periodo_letivo_id INT NOT NULL,
    Tipo INT NOT NULL COMMENT '1 - Etapas anuais, 2 Etapas extras', 
    CONSTRAINT FK_PERIODO_LETIVO_BIMESTRE
        FOREIGN KEY(Periodo_letivo_id) REFERENCES Periodo_letivo(Id)
);

CREATE TABLE Intervalo(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Dia INT NOT NULL,
    Hora_inicio TIME NOT NULL,
    Hora_fim TIME NOT NULL,
    Periodo_letivo_id INT NOT NULL,
    CONSTRAINT FK_PERIODO_LETIVO_INTERVALO
        FOREIGN KEY(Periodo_letivo_id) REFERENCES Periodo_letivo(Id)
);

CREATE TABLE Turma(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(20) NOT NULL
);

CREATE TABLE Categoria(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(50) NOT NULL
);

CREATE TABLE Grade(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Nome VARCHAR(100) NOT NULL,
    Curso_id INT NOT NULL,
    Modalidade_id INT NOT NULL,
    CONSTRAINT FK_CURSO_GRADE 
        FOREIGN KEY(Curso_id) REFERENCES Curso(Id),
    CONSTRAINT FK_MODALIDADE_GRADE 
        FOREIGN KEY(Modalidade_id) REFERENCES Modalidade(Id)
);

CREATE TABLE Disc_grade(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Periodo INT NOT NULL,
    Grade_id INT NOT NULL,
    Disciplina_id INT NOT NULL,
    CONSTRAINT FK_DISCIPLINA_DISC_GRADE 
        FOREIGN KEY (Disciplina_id) REFERENCES Disciplina(Id),
    CONSTRAINT FK_GRADE_DISC_GRADE 
        FOREIGN KEY (Grade_id) REFERENCES Grade(Id)
);

CREATE TABLE Disc_turma(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Turma_id INT NOT NULL,
    Disc_grade_id INT NOT NULL,
    Categoria_id INT NOT NULL,
    Periodo_letivo_id INT NOT NULL,
    Professor_Id INT NOT NULL,
    CONSTRAINT FK_TURMA_DISC_TURMA 
        FOREIGN KEY(Turma_id) REFERENCES Turma(Id),
    CONSTRAINT FK_DISC_GRADE_DISC_TURMA 
        FOREIGN KEY(Disc_grade_id) REFERENCES Disc_grade(Id),
    CONSTRAINT FK_CATEGORIA_DISC_TURMA 
        FOREIGN KEY(Categoria_id) REFERENCES Categoria(Id),
    CONSTRAINT FK_PERIODO_LETIVO_DISC_TURMA 
        FOREIGN KEY(Periodo_letivo_id) REFERENCES Periodo_letivo(Id),
    CONSTRAINT FK_PROFESSOR_DISC_TURMA 
        FOREIGN KEY(Professor_Id) REFERENCES Usuario(Id)
);

CREATE TABLE Matricula(
    Id INT(8) ZEROFILL AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Sub_turma INT NOT NULL,
    Inscricao_id INT NOT NULL,
    Disc_turma_id INT NOT NULL,
    CONSTRAINT FK_INSCRICAO_MATRICULA 
        FOREIGN KEY(Inscricao_id) REFERENCES Inscricao(Id),
    CONSTRAINT FK_DISC_TURMA_MATRICULA 
        FOREIGN KEY(Disc_turma_id) REFERENCES Disc_turma(Id)
            ON DELETE CASCADE
);

CREATE TABLE Horario(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Dia INT NOT NULL,
    Inicio TIME NOT NULL,
    Fim TIME NOT NULL,
    Aula INT NOT NULL
);

CREATE TABLE Disc_hor(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Sub_turma INT NOT NULL,
    Ativo BOOLEAN NOT NULL,
    Horario_id INT NOT NULL,
    Disc_turma_id INT NOT NULL,
    CONSTRAINT FK_HORARIO_DISC_HOR 
        FOREIGN KEY(Horario_id) REFERENCES Horario(Id),
    CONSTRAINT FK_DISC_TURMA_DISC_HOR 
        FOREIGN KEY(Disc_turma_id) REFERENCES Disc_turma(Id)
);

CREATE TABLE Conteudo(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, 
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    Descricao TEXT NOT NULL, 
    Disc_hor_id INT NOT NULL, 
    CONSTRAINT FK_DISC_HOR_CONTEUDO 
        FOREIGN KEY(Disc_hor_id) REFERENCES Disc_hor(Id)
);

CREATE TABLE Descricao_nota(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Descricao VARCHAR(100) NOT NULL
);

CREATE TABLE Notas(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Valor DECIMAL(10,2),
    Etapa_id INT NOT NULL,
    Matricula_id INT(8) ZEROFILL NOT NULL, 
    Descricao_nota_id INT NOT NULL,
    CONSTRAINT FK_ETAPA_NOTAS 
        FOREIGN KEY(Etapa_id) REFERENCES Etapa(Id),
    CONSTRAINT FK_MATRICULA_NOTAS 
        FOREIGN KEY(Matricula_id) REFERENCES Matricula(Id),
    CONSTRAINT FK_DESCRICAO_NOTA 
        FOREIGN KEY (Descricao_nota_id) REFERENCES Descricao_nota(Id)
);

CREATE TABLE Calendario_presenca(
    Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    Data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Ativo BOOLEAN DEFAULT TRUE,
    Presenca BOOLEAN,
    Justificativa VARCHAR(3000),
    Matricula_id INT(8) ZEROFILL NOT NULL,
    Horario_id INT NOT NULL,
    CONSTRAINT FK_MATRICULA_CALENDARIO_PRESENCA 
        FOREIGN KEY(Matricula_id) REFERENCES Matricula(Id),
    CONSTRAINT FK_HORARIO_CALENDARIO_PRESENCA 
        FOREIGN KEY(Horario_id) REFERENCES Horario(Id)
);

/*FIM DA CRIAÇÃO DAS TABELAS*/


/*ABAIXO INSERE O GRUPO DE ADMINISTRADOR*/
/*
        ***NÃO ALTERAR A ORDEM DOS INSERTS DE GRUPOS ABAIXO***    
*/
INSERT INTO Grupo(Nome) 
        VALUES('Administrador');

INSERT INTO Grupo(Nome) 
        VALUES('Aluno');

INSERT INTO Grupo(Nome) 
        VALUES('Secretaria');

INSERT INTO Grupo(Nome) 
        VALUES('Professor');
###################

/*ABAIXO INSERE OS USUÁRIOS DO SISTEMA*/
INSERT INTO Usuario (Nome, Email, Data_nascimento, Sexo, Grupo_id, Email_notifica_nova_conta) 
        VALUES('Admin', 'admin@cepbrazopolis.com.br', '2018-04-19', 1, 1, 1);
INSERT INTO Usuario (Nome, Email, Data_nascimento, Sexo, Grupo_id) 
        VALUES('Tadeu', 'tadeu.390@gmail.com', '2018-04-19', 1, 1);
INSERT INTO Usuario (Nome, Email, Data_nascimento, Sexo, Grupo_id)
        VALUES('Rodrigo', 'rodrigo_piranguinho@hotmail.com', '2018-04-19', 1, 1);
INSERT INTO Usuario (Nome, Email, Data_nascimento, Sexo, Grupo_id)
        VALUES('Ney', 'ney.candigo.ribeiro@gmail.com', '2018-04-19', 1, 1);
###################

/*ABAIXO CADASTRA A SENHA DOS USUÁRIOS DO SISTEMA*/
INSERT INTO Senha(Valor, Usuario_id, Ativo)
        VALUES('$2y$10$zpV3q4vyx89BPFui4RU9WuUsVqBo7bJLauBAbga.jm.uHdcdnz74S', 1, 0);/*senha123*/
INSERT INTO Senha(Valor, Usuario_id)
        VALUES('$2y$10$/3f5wjE6TDTgShYe6u43FO688.Z2EQg1d.CVHfPkQRaXn5zRKyDg.', 1);/*cepbrazopolisadmin2018*/
INSERT INTO Senha(Valor, Usuario_id)
        VALUES('$2y$10$zpV3q4vyx89BPFui4RU9WuUsVqBo7bJLauBAbga.jm.uHdcdnz74S', 2);/*senha123*/
INSERT INTO Senha(Valor, Usuario_id)
        VALUES('$2y$10$zpV3q4vyx89BPFui4RU9WuUsVqBo7bJLauBAbga.jm.uHdcdnz74S', 3);/*senha123*/
INSERT INTO Senha(Valor, Usuario_id)
        VALUES('$2y$10$zpV3q4vyx89BPFui4RU9WuUsVqBo7bJLauBAbga.jm.uHdcdnz74S', 4);/*senha123*/
###################

/*CRIA OS MENUS ABAIXO*/
INSERT INTO Menu(Nome, Ordem)
        VALUES('Gestão', 1);
INSERT  INTO Menu(Nome, Ordem)
        VALUES('Acadêmico', 2);
INSERT  INTO Menu(Nome, Ordem)
        VALUES('Professor', 3);
INSERT  INTO Menu(Nome, Ordem)
        VALUES('Aluno', 4);
###################

/*ABAIXO INSERE OS NOMES DOS MÓDULOS BÁSICOS E OS COLOCA NO MENU DE GESTÃO*/
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Usuários', 'Usuários', 'usuario', 1, 'glyphicon glyphicon-user', 1);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Grupos', 'Grupos', 'grupo', 2, 'fa fa-group', 1);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Menus', 'Menus', 'menu', 3, 'fa fa-navicon', 1);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Modulos', 'Módulos', 'modulo', 4, 'fa fa-list-alt', 1);
###################

/*ABAIXO INSERE OS NOMES DOS MÓDULOS ACADÊMICOS E OS COLOCA NO MENU DE ACADÊMICO*/
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Modalidades', 'Modalidade', 'modalidade', 5, 'fa fa-book', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Regras letivas', 'Regras letivas', 'regras', 6, 'fa fa-exclamation-circle', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Disciplinas', 'Disciplina', 'disciplina', 7, 'glyphicon glyphicon-book', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Cursos', 'Curso', 'curso', 8, 'fa fa-folder-open', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Grade escolar', 'Grade escolar', 'grade', 9, 'fa fa-institution', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Matrículas', 'Matrículas', 'inscricao', 10, 'glyphicon glyphicon-education', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Turmas', 'Turmas', 'turma', 11, 'fa fa-address-book', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Horários', 'Horários', 'horario', 12, 'glyphicon glyphicon-time', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Notas e faltas', 'Notas e faltas', 'nota_falta', 13, 'glyphicon glyphicon-calendar', 2);
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Boletim', 'Boletim', 'boletim', 14, 'fa fa-id-card', 2);
###################

/*ABAIXO INSERE OS NOMES DOS MÓDULOS DE PROFESSOR E OS COLOCA NO MENU DE PROFESSOR*/
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Minhas disciplinas', 'Minhas disciplinas', 'professor/chamada', 1, 'glyphicon glyphicon-book', 3);
###################

/*ABAIXO INSERE OS NOMES DOS MÓDULOS DE ALUNO E OS COLOCA NO MENU DE ALUNO*/
INSERT INTO Modulo(Nome, Descricao, Url, Ordem, Icone , Menu_id) 
        VALUES('Notas e faltas', 'Notas e faltas', 'academico/dashboard', 1, 'glyphicon glyphicon-book', 4);
###################

/*ABAIXO DETERMINA O ACESSO AOS MÓDULOS PARA OS USUÁRIOS ADMINISTRADORES*/
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 1);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 2);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 3);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 4);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 5);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 6);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 7);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 8);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 9);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 10);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 11);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 12);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 13);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 1, 14);

INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 1);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 2);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 3);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 4);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 5);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 6);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 7);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 8);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 9);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 10);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 11);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 12);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 13);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 2, 14);

INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 1);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 2);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 3);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 4);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 5);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 6);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 7);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 8);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 9);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 10);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 11);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 12);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 13);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 3, 14);

INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 1);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 2);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 3);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 4);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 5);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 6);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 7);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 8);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 9);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 10);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 11);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 12);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 13);
INSERT INTO Acesso(Criar, Ler, Atualizar, Remover, Usuario_id, Modulo_id)
        VALUES(1, 1, 1, 1, 4, 14);
###################

/*ABAIXO DETERMINA AS CONFIGURAÇÕES PADRÕES DO SISTEMA*/
INSERT INTO Settings(Itens_por_pagina) 
        VALUES(30);
INSERT INTO Settings_email(Email, Descricao, Usuario, Senha, Protocolo, Host, Porta)
        VALUES('portal@cepbrazopolis.com.br', 'E-mail de alteração de senha', 'portal@cepbrazopolis.com.br','portalcep2018', 'smtp', 'mail.cepbrazopolis.com.br', '465');/*port 465 ssl. 587 tls*/
###################

/*ABAIXO DETERMINA AS PERMISSÕES PADRÕES POR GRUPO DE CADA MÓDULO DO SISTEMA*/
    ##GRUPO ADMINISTRADOR
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,1,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,2,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,3,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,4,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,5,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,6,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,7,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,8,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,9,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,10,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,11,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,12,1);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,13,1);

    ##GRUPO SECRETARIA
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,1,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,5,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,6,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,7,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,8,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,9,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,10,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,11,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,12,3);
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,13,3);

    ##GRUPO PROFESSOR
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(1,1,1,1,15,4);    

    ##GRUPO ALUNO
INSERT INTO Acesso_padrao(Criar, Ler, Atualizar, Remover, Modulo_id, Grupo_id)
        VALUES(0,1,0,0,16,2);
###################

/* ABAIXO INSERE AS MODALIDADES DE ENSINO */
INSERT INTO Modalidade(Nome)
        VALUES('Ensino Integrado');
INSERT INTO Modalidade(Nome)
        VALUES('Pós Médio');
###################

/* ABAIXO INSERE AS CATEGORIAS DE DISCIPLINA */
INSERT INTO Categoria(Nome)
        VALUES('Matérias Técnicas');
INSERT INTO Categoria(Nome)
        VALUES('Matérias Ensino Médio');
###################

/*ABAIXO INSERE OS TIPOS DE NOTAS*/
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Rec. do bimestre'); /*NÃO TROCAR DE POSIÇÃO ESSE TIPO DE NOTA, SE FOR ADICIONAR, FAZER ISSO DEPOIS DESSE TIPO DE NOTA.*/
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Ex. Sala');
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Ex. Lab.');
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Conceito');
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Av. Mensal');        
INSERT INTO Descricao_nota (Descricao) 
        VALUES('Av. Bimestral');
###################

/*ABAIXO INSERE OS DOCUMENTOS QUE O ALUNO DEVE LEVAR*/

/*ONDE TIPO 1 É DOC DE ALUNO E TIPO 2 É DOC DE RESPONSÁVEL*/
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Certidão de nascimento ou casamento', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Histórico escolar ensino médio', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Declaração de conclusão ensino médio', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Declaração de matrícula no ensino médio', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Foto (1 foto 3x4)', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Carteira de identidade - RG', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Cadastro de pessoa física - CPF', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Título de eleitor', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Certificado de reservista', 1);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('Conta de energia elétrica', 1);
###################

/*ABAIXO INSERE OS DOCUMENTOS DOS RESPONSÁVEIS*/
INSERT INTO Doc(Nome, Tipo) 
        VALUES('RG Paterno', 2);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('CPF Materno', 2);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('CPF Paterno', 2);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('RG Outros', 2);
INSERT INTO Doc(Nome, Tipo) 
        VALUES('CPF Outros', 2);
###################

/*ABAIXO CRIA UMA VIEW PARA AUXILIO AO MONTAR O MENU E TAMBÉM OS MODULOS, OU SEJA, AJUDA A IDENTIFICAR O QUE DEVE SER 
EXIBIDO NA TELA CONFORME AS PERMISSÕES DO USUÁRIO LOGADO*/
CREATE OR REPLACE VIEW Modulo_acesso_view AS 
    SELECT m.Ativo, mo.Nome AS Nome_modulo, mo.Id AS Modulo_id, mo.Menu_id AS Menu_id, 
    mo.Url AS Url_modulo, mo.Icone, m.Nome AS Nome_menu, a.Usuario_id AS Usuario_id, 
    m.Ordem AS Ordem_menu, mo.Ordem AS Ordem_modulo 
    FROM Modulo mo 
    INNER JOIN Acesso a on mo.Id = a.Modulo_id 
    LEFT JOIN Menu m on mo.Menu_id = m.Id 
    WHERE mo.Ativo = 1 and a.Ler = 1 ORDER BY mo.Ordem;
###################

/*ABAIXO CRIA A FUNÇÃO UTILIZADA PARA RETORNAR O NOME DO MÊS CONFORME O NÚMERO QUE IDENTIFICA O MESMO*/
CREATE FUNCTION mes_por_numero (numero int) RETURNS VARCHAR(50)
RETURN
    (SELECT 
  
        CASE 
            WHEN numero = 1 THEN 
            'Janeiro' 
            WHEN numero = 2 THEN 
            'Fevereiro' 
            WHEN numero = 3 THEN 
            'Março'
            WHEN numero = 4 THEN 
            'Abril'
            WHEN numero = 5 THEN 
            'Maio'
            WHEN numero = 6 THEN 
            'Junho'
            WHEN numero = 7 THEN 
            'Julho'
            WHEN numero = 8 THEN 
            'Agosto'
            WHEN numero = 9 THEN 
            'Setembro'
            WHEN numero = 10 THEN 
            'Outubro'    
            WHEN numero = 11 THEN 
            'Novembro'
            WHEN numero = 12 THEN 
            'Dezembro'
        END AS Mes);
###################