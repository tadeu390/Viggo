-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18-Dez-2017 às 16:54
-- Versão do servidor: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boletim`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `Id` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `DataRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Matricula` int(11) DEFAULT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  `Sexo` char(1) DEFAULT NULL,
  `DataNascimento` date DEFAULT NULL,
  `NumeroChamada` int(11) DEFAULT NULL,
  `TurmaId` int(11) DEFAULT NULL,
  `CursoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`Id`, `ativo`, `DataRegistro`, `Matricula`, `Nome`, `Sexo`, `DataNascimento`, `NumeroChamada`, `TurmaId`, `CursoId`) VALUES
(6, 1, '2017-12-18 11:57:04', 13567, 'Tadeu', '1', '2017-12-17', 1, 18, 1),
(7, 1, '2017-12-18 11:57:25', 111123, 'Bruno', '1', '2017-12-29', 2, 17, 1),
(8, 1, '2017-12-18 14:05:33', 1, 'yyy', '1', '2017-12-27', 2, 18, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `boletim`
--

CREATE TABLE `boletim` (
  `Id` int(11) NOT NULL,
  `Nota` float DEFAULT NULL,
  `Falta` int(11) DEFAULT NULL,
  `Bimestre` int(11) DEFAULT NULL,
  `AlunoId` int(11) NOT NULL,
  `DisciplinaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `Id` int(11) NOT NULL,
  `Nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`Id`, `Nome`) VALUES
(1, 'Matérias técnicas'),
(2, 'Matérias do ensino médio');

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `Id` int(11) NOT NULL,
  `Ativo` tinyint(1) NOT NULL,
  `DataRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`Id`, `Ativo`, `DataRegistro`, `Nome`) VALUES
(1, 1, '2017-12-17 02:19:09', 'Eletrônica'),
(2, 1, '2017-12-17 02:19:09', 'Informática'),
(21, 1, '2017-12-18 01:58:06', 'Logística'),
(26, 0, '2017-12-18 10:46:55', 'iii'),
(27, 1, '2017-12-18 11:31:22', 'sdfsd'),
(28, 1, '2017-12-18 11:31:43', 'dfgfd'),
(29, 1, '2017-12-18 11:32:19', 'ggg'),
(30, 1, '2017-12-18 11:32:34', 'dfgd');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina`
--

CREATE TABLE `disciplina` (
  `Id` int(11) NOT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  `Ativo` tinyint(1) DEFAULT NULL,
  `DataRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CategoriaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`Id`, `Nome`, `Ativo`, `DataRegistro`, `CategoriaId`) VALUES
(1, 'Eletrônica', 1, '2017-12-17 02:02:57', 2),
(2, 'Eletrônica Analógica', 1, '2017-12-17 02:03:15', 1),
(5, 'Matemática', 0, '2017-12-17 02:31:02', 2),
(6, 'Desenhp Técnico', 0, '2017-12-17 02:31:02', 1),
(7, 'História', 1, '2017-12-18 01:12:33', 2),
(8, 'yyyy', 1, '2017-12-18 10:09:21', 1),
(9, '8888888', 1, '2017-12-18 10:36:33', 1),
(10, 'lll', 0, '2017-12-18 10:40:13', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina_curso`
--

CREATE TABLE `disciplina_curso` (
  `DisciplinaId` int(11) NOT NULL,
  `CursoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `disciplina_curso`
--

INSERT INTO `disciplina_curso` (`DisciplinaId`, `CursoId`) VALUES
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `Id` int(11) NOT NULL,
  `Ativo` tinyint(1) NOT NULL,
  `DataRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Nome` varchar(100) DEFAULT NULL,
  `CursoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`Id`, `Ativo`, `DataRegistro`, `Nome`, `CursoId`) VALUES
(17, 1, '2017-12-18 11:57:52', 'xx', 1),
(18, 1, '2017-12-18 12:02:01', 'xx2', 1),
(19, 1, '2017-12-18 15:24:07', 'uuu', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_aluno`
--

CREATE TABLE `turma_aluno` (
  `TurmaId` int(11) NOT NULL,
  `AlunoId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma_aluno`
--

INSERT INTO `turma_aluno` (`TurmaId`, `AlunoId`) VALUES
(17, 6),
(17, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_disciplina`
--

CREATE TABLE `turma_disciplina` (
  `TurmaId` int(11) NOT NULL,
  `DisciplinaId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma_disciplina`
--

INSERT INTO `turma_disciplina` (`TurmaId`, `DisciplinaId`) VALUES
(17, 7),
(18, 9),
(19, 7),
(19, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `senha` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'teste', 'teste@teste.com', 'b123e9e19d217169b981a61188920f9d28638709a5132201684d792b9264271b7f09157ed4321b1c097f7a4abecfc0977d40a7ee599c845883bd1074ca23c4af');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_turmaAluno` (`TurmaId`),
  ADD KEY `fk_CursoAluno` (`CursoId`);

--
-- Indexes for table `boletim`
--
ALTER TABLE `boletim`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_AlunoBoletim` (`AlunoId`),
  ADD KEY `fk_DisciplinaBoletim` (`DisciplinaId`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Categoria` (`CategoriaId`);

--
-- Indexes for table `disciplina_curso`
--
ALTER TABLE `disciplina_curso`
  ADD PRIMARY KEY (`DisciplinaId`,`CursoId`),
  ADD KEY `fk_Curso_Disciplina_Curso` (`CursoId`);

--
-- Indexes for table `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `fk_Curso` (`CursoId`);

--
-- Indexes for table `turma_aluno`
--
ALTER TABLE `turma_aluno`
  ADD PRIMARY KEY (`TurmaId`,`AlunoId`),
  ADD KEY `fk_aluno_turma_aluno` (`AlunoId`);

--
-- Indexes for table `turma_disciplina`
--
ALTER TABLE `turma_disciplina`
  ADD PRIMARY KEY (`TurmaId`,`DisciplinaId`),
  ADD KEY `fk_Disciplina` (`DisciplinaId`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aluno`
--
ALTER TABLE `aluno`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `boletim`
--
ALTER TABLE `boletim`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `curso`
--
ALTER TABLE `curso`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `turma`
--
ALTER TABLE `turma`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `fk_CursoAluno` FOREIGN KEY (`CursoId`) REFERENCES `curso` (`Id`),
  ADD CONSTRAINT `fk_turmaAluno` FOREIGN KEY (`TurmaId`) REFERENCES `turma` (`Id`);

--
-- Limitadores para a tabela `boletim`
--
ALTER TABLE `boletim`
  ADD CONSTRAINT `fk_AlunoBoletim` FOREIGN KEY (`AlunoId`) REFERENCES `aluno` (`Id`),
  ADD CONSTRAINT `fk_DisciplinaBoletim` FOREIGN KEY (`DisciplinaId`) REFERENCES `disciplina` (`Id`);

--
-- Limitadores para a tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD CONSTRAINT `fk_Categoria` FOREIGN KEY (`CategoriaId`) REFERENCES `categoria` (`Id`);

--
-- Limitadores para a tabela `disciplina_curso`
--
ALTER TABLE `disciplina_curso`
  ADD CONSTRAINT `fk_Curso_Disciplina_Curso` FOREIGN KEY (`CursoId`) REFERENCES `curso` (`Id`),
  ADD CONSTRAINT `fk_Disciplina_Curso` FOREIGN KEY (`DisciplinaId`) REFERENCES `disciplina` (`Id`);

--
-- Limitadores para a tabela `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `fk_Curso` FOREIGN KEY (`CursoId`) REFERENCES `curso` (`Id`);

--
-- Limitadores para a tabela `turma_aluno`
--
ALTER TABLE `turma_aluno`
  ADD CONSTRAINT `fk_aluno_turma_aluno` FOREIGN KEY (`AlunoId`) REFERENCES `aluno` (`Id`),
  ADD CONSTRAINT `fk_turma_turma_aluno` FOREIGN KEY (`TurmaId`) REFERENCES `turma` (`Id`);

--
-- Limitadores para a tabela `turma_disciplina`
--
ALTER TABLE `turma_disciplina`
  ADD CONSTRAINT `fk_Disciplina` FOREIGN KEY (`DisciplinaId`) REFERENCES `disciplina` (`Id`),
  ADD CONSTRAINT `fk_turma` FOREIGN KEY (`TurmaId`) REFERENCES `turma` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
