DROP TABLE IF EXISTS Boletim;
DROP TABLE IF EXISTS Turma_Aluno;
DROP TABLE IF EXISTS Aluno;
DROP TABLE IF EXISTS Disciplina_Curso;
DROP TABLE IF EXISTS Turma_Disciplina;
DROP TABLE IF EXISTS Disciplina;
DROP TABLE IF EXISTS Categoria;
DROP TABLE IF EXISTS Turma;
DROP TABLE IF EXISTS Curso;
DROP TABLE IF EXISTS Usuario;

CREATE TABLE Categoria(
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Nome VARCHAR(100)
);

CREATE TABLE Curso (
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Nome VARCHAR(100),
	Ativo BOOLEAN,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Turma (
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Ativo BOOLEAN,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	Nome VARCHAR(100),
	CursoId INT NOT NULL,
	CONSTRAINT fk_Curso
		FOREIGN KEY(CursoId) REFERENCES Curso(Id)
);

CREATE TABLE Disciplina (
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Nome VARCHAR(100),
	Ativo BOOLEAN,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CategoriaId INT NOT NULL,
	CONSTRAINT fk_Categoria
		FOREIGN KEY(CategoriaId) REFERENCES Categoria(Id)
);

CREATE TABLE Turma_Disciplina (
	TurmaId INT NOT NULL,
	DisciplinaId INT NOT NULL,
	CONSTRAINT pk_turma_disciplina 
		PRIMARY KEY (TurmaId, DisciplinaId),
	CONSTRAINT fk_turma
		FOREIGN KEY (TurmaId) REFERENCES Turma(Id),
	CONSTRAINT fk_Disciplina
		FOREIGN KEY (DisciplinaId) REFERENCES Disciplina(Id)
);

CREATE TABLE Disciplina_Curso (
	DisciplinaId INT NOT NULL,
	CursoId INT NOT NULL,
	CONSTRAINT pk_disciplina_curso 
		PRIMARY KEY(DisciplinaId,CursoId),
	CONSTRAINT fk_Disciplina_Disciplina_Curso
		FOREIGN KEY (DisciplinaId) REFERENCES Disciplina(Id),
	CONSTRAINT fk_Curso_Disciplina_Curso
		FOREIGN KEY(CursoId) REFERENCES Curso(Id)
);

CREATE TABLE Aluno (
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Ativo BOOLEAN,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	Matricula INT,
	Nome VARCHAR(100),
	Sexo char(1),
	DataNascimento DATE,
	NumeroChamada INT,
	TurmaId INT,
	CursoId INT NOT NULL,
	CONSTRAINT fk_turmaAluno
		FOREIGN KEY (TurmaId) REFERENCES Turma(Id),
	CONSTRAINT fk_CursoAluno
		FOREIGN KEY (CursoId) REFERENCES Curso(Id)
);

CREATE TABLE Turma_Aluno(
	TurmaId INT,
	AlunoId INT,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT pk_turma_aluno 
		PRIMARY KEY (TurmaId,AlunoId),
	CONSTRAINT fk_turma_turma_aluno 
		FOREIGN KEY (TurmaId) REFERENCES Turma(Id),
	CONSTRAINT fk_aluno_turma_aluno 
		FOREIGN KEY (AlunoId) REFERENCES Aluno(Id)
);

CREATE TABLE Boletim (
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Ativo BOOLEAN,
	DataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	Nota1 DOUBLE,
	Falta1 INT,
	Nota2 DOUBLE,
	Falta2 INT,
	Nota3 DOUBLE,
	Falta3 INT,
	Nota4 DOUBLE,
	Falta4 INT,
	Bimestre INT,
	AlunoId INT NOT NULL,
	DisciplinaId INT NOT NULL,
	CONSTRAINT fk_AlunoBoletim
		FOREIGN KEY (AlunoId) REFERENCES Aluno(Id),
	CONSTRAINT fk_DisciplinaBoletim
		FOREIGN KEY (DisciplinaId) REFERENCES Disciplina(Id)
);

CREATE TABLE Usuario(
	Id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	Nome VARCHAR(100) NOT NULL,
	Email VARCHAR(50) NOT NULL,
	Senha VARCHAR(200) NOT NULL
);
INSERT INTO Usuario(Nome,Email,Senha) VALUES('teste','teste@teste.com',sha2('teste',512));
INSERT INTO Categoria(Nome) VALUES('Matérias Técnicas');
INSERT INTO Categoria(Nome) VALUES('Matérias Ensino Médio');