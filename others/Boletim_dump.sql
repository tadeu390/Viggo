-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 28-Fev-2018 às 12:20
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
-- Estrutura da tabela `acesso`
--

CREATE TABLE `acesso` (
  `id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modulo_id` int(11) DEFAULT NULL,
  `criar` tinyint(1) DEFAULT NULL,
  `visualizar` tinyint(1) DEFAULT NULL,
  `atualizar` tinyint(1) DEFAULT NULL,
  `apagar` tinyint(1) DEFAULT NULL,
  `grupo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `acesso`
--

INSERT INTO `acesso` (`id`, `data_registro`, `modulo_id`, `criar`, `visualizar`, `atualizar`, `apagar`, `grupo_id`) VALUES
(1, '2017-12-29 00:40:48', 3, 1, 1, 1, 1, 1),
(2, '2017-12-29 00:44:07', 1, 1, 1, 1, 1, 1),
(3, '2017-12-29 00:44:07', 2, 1, 1, 1, 1, 1),
(4, '2017-12-29 00:44:07', 4, 1, 1, 1, 1, 1),
(5, '2017-12-29 07:58:10', 5, 1, 1, 1, 1, 1),
(6, '2017-12-29 08:17:22', 1, 0, 0, 0, 0, 2),
(7, '2017-12-29 08:17:23', 2, 0, 0, 0, 0, 2),
(8, '2017-12-29 08:17:23', 3, 0, 0, 0, 0, 2),
(9, '2017-12-29 08:17:23', 4, 0, 0, 0, 0, 2),
(10, '2017-12-29 08:17:23', 5, 1, 1, 1, 1, 2),
(11, '2017-12-29 22:12:49', 6, 1, 1, 1, 1, 1),
(12, '2017-12-29 23:26:26', 7, 1, 1, 1, 1, 1),
(13, '2017-12-30 03:17:07', 8, 1, 1, 1, 1, 1),
(14, '2018-01-18 21:04:31', 1, 0, 0, 0, 0, 3),
(15, '2018-01-18 21:04:31', 2, 0, 0, 0, 0, 3),
(16, '2018-01-18 21:04:31', 3, 0, 0, 0, 0, 3),
(17, '2018-01-18 21:04:31', 4, 1, 1, 1, 1, 3),
(18, '2018-01-18 21:04:32', 5, 0, 0, 0, 0, 3),
(19, '2018-01-18 21:04:32', 6, 0, 0, 0, 0, 3),
(20, '2018-01-18 21:04:32', 7, 0, 0, 0, 0, 3),
(21, '2018-01-18 21:04:32', 8, 0, 0, 0, 0, 3),
(22, '2018-01-18 21:29:06', 9, 1, 1, 1, 1, 1),
(23, '2018-02-06 18:09:29', 10, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `matricula` int(11) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `numero_chamada` int(11) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `curso_id` int(11) NOT NULL,
  `ano_letivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`id`, `ativo`, `data_registro`, `matricula`, `nome`, `sexo`, `data_nascimento`, `numero_chamada`, `turma_id`, `curso_id`, `ano_letivo`) VALUES
(1, 1, '2018-02-08 17:40:26', 1234, 'Tadeu', '1', '1995-05-17', 1, 5, 1, 0),
(2, 1, '2018-02-08 17:40:50', 12345, 'Bruno', '1', '1994-12-03', 2, 5, 1, 0),
(3, 1, '2018-02-08 17:41:11', 123456, 'Carlos', '1', '2010-10-10', 3, 5, 1, 0),
(4, 1, '2018-02-08 17:43:09', 1234567, 'Natália', '0', '1996-05-15', 4, 5, 1, 0),
(5, 1, '2018-02-08 17:43:49', 12345678, 'Patrícia', '0', '2009-12-10', 5, 5, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `boletim`
--

CREATE TABLE `boletim` (
  `id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nota1` double DEFAULT NULL,
  `falta1` int(11) DEFAULT NULL,
  `nota2` double DEFAULT NULL,
  `falta2` int(11) DEFAULT NULL,
  `nota3` double DEFAULT NULL,
  `falta3` int(11) DEFAULT NULL,
  `nota4` double DEFAULT NULL,
  `falta4` int(11) DEFAULT NULL,
  `bimestre` int(11) DEFAULT NULL,
  `aluno_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `nota_final` double DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `exame` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `boletim`
--

INSERT INTO `boletim` (`id`, `ativo`, `data_registro`, `nota1`, `falta1`, `nota2`, `falta2`, `nota3`, `falta3`, `nota4`, `falta4`, `bimestre`, `aluno_id`, `disciplina_id`, `turma_id`, `nota_final`, `status`, `exame`) VALUES
(15, 1, '2018-02-16 04:27:38', 18, 10, 20, 10, 20, 20, 2, 100, 1, 1, 1, 5, 60, 'Aprovado', 60),
(16, 1, '2018-02-16 04:28:35', 20, 10, 20, 10, 21, 10, 20, 10, 1, 1, 3, 5, 81, 'Aprovado', NULL),
(17, 1, '2018-02-16 22:16:25', 18, 10, 20, 10, 20, 25, 20, 5, 1, 2, 1, 5, 78, 'Aprovado', NULL),
(18, 1, '2018-02-27 23:59:38', 20, 2, 3, NULL, NULL, NULL, NULL, NULL, 1, 3, 1, 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'Matérias Técnicas'),
(2, 'Matérias Ensino Médio');

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracoes_geral`
--

CREATE TABLE `configuracoes_geral` (
  `id` int(11) NOT NULL,
  `media` double NOT NULL,
  `total_faltas` int(11) NOT NULL,
  `primeiro_bimestre` int(11) NOT NULL,
  `segundo_bimestre` int(11) NOT NULL,
  `terceiro_bimestre` int(11) NOT NULL,
  `quarto_bimestre` int(11) NOT NULL,
  `itens_por_pagina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `configuracoes_geral`
--

INSERT INTO `configuracoes_geral` (`id`, `media`, `total_faltas`, `primeiro_bimestre`, `segundo_bimestre`, `terceiro_bimestre`, `quarto_bimestre`, `itens_por_pagina`) VALUES
(1, 60, 200, 20, 25, 25, 30, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE `curso` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `curso`
--

INSERT INTO `curso` (`id`, `nome`, `ativo`, `data_registro`) VALUES
(1, 'Eletrônica industrial', 1, '2018-02-08 17:39:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina`
--

CREATE TABLE `disciplina` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `categoria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `disciplina`
--

INSERT INTO `disciplina` (`id`, `nome`, `ativo`, `data_registro`, `categoria_id`) VALUES
(1, 'Português', 1, '2018-02-08 17:36:14', 2),
(3, 'Matemática', 1, '2018-02-08 17:38:00', 2),
(4, 'Química', 1, '2018-02-08 17:38:18', 2),
(5, 'El. Analógica', 1, '2018-02-08 17:38:30', 1),
(6, 'El. Digital', 1, '2018-02-08 17:38:43', 1),
(7, 'Eletricidade', 1, '2018-02-08 17:38:52', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplina_curso`
--

CREATE TABLE `disciplina_curso` (
  `disciplina_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `disciplina_curso`
--

INSERT INTO `disciplina_curso` (`disciplina_id`, `curso_id`) VALUES
(1, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupo`
--

CREATE TABLE `grupo` (
  `id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `grupo`
--

INSERT INTO `grupo` (`id`, `data_registro`, `ativo`, `nome`) VALUES
(1, '2017-12-29 00:40:46', 1, 'Administrador'),
(2, '2017-12-29 08:17:22', 1, 'Professor'),
(3, '2018-01-18 21:04:31', 1, 'Coordenador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `menu`
--

INSERT INTO `menu` (`id`, `data_registro`, `ativo`, `nome`, `ordem`) VALUES
(1, '2017-12-29 00:40:47', 1, 'Gestão', 1),
(2, '2018-01-18 23:22:24', 1, 'Acadêmico', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `modulo`
--

CREATE TABLE `modulo` (
  `id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(1) DEFAULT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `descricao` varchar(100) DEFAULT NULL,
  `url` varchar(20) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  `icone` varchar(50) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `modulo`
--

INSERT INTO `modulo` (`id`, `data_registro`, `ativo`, `nome`, `descricao`, `url`, `ordem`, `icone`, `menu_id`) VALUES
(1, '2017-12-29 00:40:47', 1, 'Módulos', 'Lista de módulos', 'Modulo', 1, 'fa fa-list-alt', 1),
(2, '2017-12-29 00:40:47', 1, 'Menus', 'Lista de menus', 'Menu', 1, 'fa fa-navicon', 1),
(3, '2017-12-29 00:40:47', 1, 'Grupos', 'Lista de grupos', 'Grupo', 1, 'fa fa-th-large', 1),
(4, '2017-12-29 00:40:48', 1, 'Usuários', 'Lista de usuários', 'Usuario', 1, 'glyphicon glyphicon-user', 1),
(5, '2017-12-29 07:55:14', 1, 'Disciplina', 'Disciplinas', 'Disciplina', 1, ' glyphicon glyphicon-paperclip', 2),
(6, '2017-12-29 22:09:51', 1, 'Curso', 'Curso', 'Curso', 2, 'glyphicon glyphicon-folder-open', 2),
(7, '2017-12-29 23:25:48', 1, 'Aluno', 'Alunos', 'Aluno', 3, 'glyphicon glyphicon-user', 2),
(8, '2017-12-30 03:15:48', 1, 'Turma', 'Turma', 'Turma', 4, 'glyphicon glyphicon-book', 2),
(9, '2018-01-18 21:28:13', 1, 'Notas', 'Notas', 'Nota', 5, 'glyphicon glyphicon-file', 2),
(10, '2018-02-06 18:08:54', 1, 'Boletim', 'Boletim', 'boletim', 6, 'glyphicon glyphicon-list-alt', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `id` int(11) NOT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nome` varchar(100) DEFAULT NULL,
  `curso_id` int(11) NOT NULL,
  `ano_letivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`id`, `ativo`, `data_registro`, `nome`, `curso_id`, `ano_letivo`) VALUES
(5, 1, '2018-02-08 20:21:15', 'E1A', 1, 0),
(8, 1, '2018-02-08 21:03:06', 'E2A', 1, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_aluno`
--

CREATE TABLE `turma_aluno` (
  `turma_id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma_aluno`
--

INSERT INTO `turma_aluno` (`turma_id`, `aluno_id`, `data_registro`) VALUES
(5, 1, '2018-02-08 20:49:43'),
(5, 2, '2018-02-08 20:49:43'),
(5, 3, '2018-02-08 20:50:08'),
(5, 4, '2018-02-08 20:50:08'),
(5, 5, '2018-02-08 20:50:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma_disciplina`
--

CREATE TABLE `turma_disciplina` (
  `turma_id` int(11) NOT NULL,
  `disciplina_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `turma_disciplina`
--

INSERT INTO `turma_disciplina` (`turma_id`, `disciplina_id`) VALUES
(5, 1),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acesso` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `grupo_id` int(11) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `nome` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `senha` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `data_registro`, `ultimo_acesso`, `grupo_id`, `ativo`, `nome`, `email`, `senha`) VALUES
(1, '2018-02-08 17:35:13', '2018-02-28 11:19:51', 1, 1, 'Admin', 'admin@dominio.com.br', 'admin123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acesso`
--
ALTER TABLE `acesso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_modulo_acesso` (`modulo_id`),
  ADD KEY `fk_grupo_acesso` (`grupo_id`);

--
-- Indexes for table `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turma_aluno` (`turma_id`),
  ADD KEY `fk_curso_aluno` (`curso_id`);

--
-- Indexes for table `boletim`
--
ALTER TABLE `boletim`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_aluno_boletim` (`aluno_id`),
  ADD KEY `fk_disciplina_boletim` (`disciplina_id`),
  ADD KEY `fk_turma_boletim` (`turma_id`);

--
-- Indexes for table `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categoria` (`categoria_id`);

--
-- Indexes for table `disciplina_curso`
--
ALTER TABLE `disciplina_curso`
  ADD PRIMARY KEY (`disciplina_id`,`curso_id`),
  ADD KEY `fk_curso_disciplina_curso` (`curso_id`);

--
-- Indexes for table `grupo`
--
ALTER TABLE `grupo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu_modulo` (`menu_id`);

--
-- Indexes for table `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_curso` (`curso_id`);

--
-- Indexes for table `turma_aluno`
--
ALTER TABLE `turma_aluno`
  ADD PRIMARY KEY (`turma_id`,`aluno_id`),
  ADD KEY `fk_aluno_turma_aluno` (`aluno_id`);

--
-- Indexes for table `turma_disciplina`
--
ALTER TABLE `turma_disciplina`
  ADD PRIMARY KEY (`turma_id`,`disciplina_id`),
  ADD KEY `fk_disciplina` (`disciplina_id`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grupo_usuario` (`grupo_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acesso`
--
ALTER TABLE `acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `aluno`
--
ALTER TABLE `aluno`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `boletim`
--
ALTER TABLE `boletim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `curso`
--
ALTER TABLE `curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `grupo`
--
ALTER TABLE `grupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `modulo`
--
ALTER TABLE `modulo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `turma`
--
ALTER TABLE `turma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `acesso`
--
ALTER TABLE `acesso`
  ADD CONSTRAINT `fk_grupo_acesso` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`),
  ADD CONSTRAINT `fk_modulo_acesso` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`);

--
-- Limitadores para a tabela `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `fk_curso_aluno` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `fk_turma_aluno` FOREIGN KEY (`turma_id`) REFERENCES `turma` (`id`);

--
-- Limitadores para a tabela `boletim`
--
ALTER TABLE `boletim`
  ADD CONSTRAINT `fk_aluno_boletim` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `fk_disciplina_boletim` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplina` (`id`),
  ADD CONSTRAINT `fk_turma_boletim` FOREIGN KEY (`turma_id`) REFERENCES `turma` (`id`);

--
-- Limitadores para a tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

--
-- Limitadores para a tabela `disciplina_curso`
--
ALTER TABLE `disciplina_curso`
  ADD CONSTRAINT `fk_curso_disciplina_curso` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`id`),
  ADD CONSTRAINT `fk_disciplina_disciplina_curso` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplina` (`id`);

--
-- Limitadores para a tabela `modulo`
--
ALTER TABLE `modulo`
  ADD CONSTRAINT `fk_menu_modulo` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Limitadores para a tabela `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `fk_curso` FOREIGN KEY (`curso_id`) REFERENCES `curso` (`id`);

--
-- Limitadores para a tabela `turma_aluno`
--
ALTER TABLE `turma_aluno`
  ADD CONSTRAINT `fk_aluno_turma_aluno` FOREIGN KEY (`aluno_id`) REFERENCES `aluno` (`id`),
  ADD CONSTRAINT `fk_turma_turma_aluno` FOREIGN KEY (`turma_id`) REFERENCES `turma` (`id`);

--
-- Limitadores para a tabela `turma_disciplina`
--
ALTER TABLE `turma_disciplina`
  ADD CONSTRAINT `fk_disciplina` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplina` (`id`),
  ADD CONSTRAINT `fk_turma` FOREIGN KEY (`turma_id`) REFERENCES `turma` (`id`);

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_grupo_usuario` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
