-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Jun-2026 às 09:37
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `hotel`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `adm`
--

CREATE TABLE `adm` (
  `id_reserva` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tipo` enum('executivo','espacoso','suite','simples','duplo','triplo','executivo2','espacoso2','suite2','simples2','duplo2','triplo2') NOT NULL,
  `entrada` date NOT NULL,
  `saida` date NOT NULL,
  `status` enum('pendente','bloqueada','ativa','concluida') DEFAULT 'pendente',
  `data_reserva` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `adm`
--

INSERT INTO `adm` (`id_reserva`, `id_client`, `name`, `email`, `tipo`, `entrada`, `saida`, `status`, `data_reserva`) VALUES
(19, 12, 'Jack', 'jack@gmail.com', 'suite', '2026-05-17', '2026-05-31', 'concluida', '2026-06-03 17:14:53'),
(20, 12, 'fulano', 'fulano@gmail.com', 'espacoso', '2026-05-22', '2026-05-23', 'concluida', '2026-06-03 17:14:44'),
(21, 13, 'Jairo', 'jairo@gmail.com', 'duplo', '2026-05-30', '2026-06-13', 'ativa', '2026-06-03 17:14:26');

-- --------------------------------------------------------

--
-- Estrutura da tabela `contactos`
--

CREATE TABLE `contactos` (
  `id` bigint(20) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `contactos`
--

INSERT INTO `contactos` (`id`, `nome`, `email`, `mensagem`, `criado_em`) VALUES
(3, 'Belone Alberto', 'beloneambrosio@Gmail.com', 'oi', '2026-06-03 17:41:28'),
(4, 'Belone Alberto', 'belone@gmail.com', 'oi', '2026-06-03 17:42:40');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `tipo` enum('executivo','espacoso','suite','simples','duplo','triplo','executivo2','espacoso2','suite2','simples2','duplo2','triplo2') NOT NULL,
  `entrada` date NOT NULL,
  `saida` date NOT NULL,
  `status` enum('pendente','bloqueada','ativa','concluida') DEFAULT 'pendente',
  `data_reserva` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_client`, `tipo`, `entrada`, `saida`, `status`, `data_reserva`) VALUES
(51, 13, 'triplo', '2026-05-10', '2026-05-17', 'concluida', '2026-06-03 17:14:08'),
(52, 13, 'executivo', '2026-05-11', '2026-05-17', 'concluida', '2026-06-03 17:14:08');

-- --------------------------------------------------------

--
-- Estrutura da tabela `rooms`
--

CREATE TABLE `rooms` (
  `id_rooms` int(11) NOT NULL,
  `tipo` enum('executivo','espacoso','suite','simples','duplo','triplo','executivo2','espacoso2','suite2','simples2','duplo2','triplo2') NOT NULL,
  `descricao` varchar(1000) NOT NULL,
  `preco` decimal(10,0) NOT NULL,
  `data_insert` date NOT NULL,
  `imagem` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `rooms`
--

INSERT INTO `rooms` (`id_rooms`, `tipo`, `descricao`, `preco`, `data_insert`, `imagem`) VALUES
(29, 'executivo', 'CORPORAÇÃO EXECUTIVA', 30000, '0000-00-00', 'img/room3.jpg'),
(30, 'espacoso', 'GRANDES BAGAGENS', 15000, '0000-00-00', 'img/room6.jpg'),
(31, 'simples', 'SOLTEIRO', 10000, '0000-00-00', 'img/room2.jpg'),
(32, 'suite', 'QUARTO COM WC', 18000, '0000-00-00', 'img/gallery4.jpg'),
(33, 'duplo', 'DOIS EM UM', 20000, '0000-00-00', 'img/room4.jpg'),
(36, 'triplo', 'TRÊS EM UM.', 25000, '0000-00-00', 'img/room1.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `testemunho`
--

CREATE TABLE `testemunho` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `feedback` varchar(200) NOT NULL,
  `imagem` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `testemunho`
--

INSERT INTO `testemunho` (`id`, `nome`, `feedback`, `imagem`) VALUES
(4, 'João Neves', '“Fiquei impressionado com a facilidade do site e com o excelente atendimento do Hotel Dayane. Tudo rápido, organizado e muito acolhedor!”', 'img/user-3.jpg'),
(5, 'Filipe Manuel', '“Site moderno, fácil de usar e atendimento impecável. Com certeza voltaremos ao Hotel Dayane!”', 'img/user-2.jpg'),
(6, 'Euclides Miguel', '“Reservar pelo site foi simples e seguro. No hotel, fomos recebidos com simpatia e profissionalismo. Experiência incrível!”', 'img/user-5.jpg'),
(7, 'Teresa da Silva', '“A experiência foi maravilhosa! O atendimento do Hotel Dayane é diferenciado e o site facilita muito na hora da reserva.”', 'img/user-1.jpg'),
(8, 'Manuela Francisco', '“Gostamos muito da agilidade no atendimento e da praticidade do site. O Hotel Dayane oferece uma experiência excelente!”', 'img/user-4.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id_client` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','admin','funcionario') DEFAULT 'client',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id_client`, `name`, `email`, `password`, `role`, `reset_token`, `reset_expires`) VALUES
(12, 'Administrador ', 'admin@hotel.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '', NULL),
(13, 'Belone Alberto', 'belone@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'client', NULL, NULL),
(15, 'funcionario', 'funcionario@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'funcionario', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `adm`
--
ALTER TABLE `adm`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_cliente` (`id_client`);

--
-- Índices para tabela `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_client` (`id_client`);

--
-- Índices para tabela `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id_rooms`);

--
-- Índices para tabela `testemunho`
--
ALTER TABLE `testemunho`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_client`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adm`
--
ALTER TABLE `adm`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_rooms` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `testemunho`
--
ALTER TABLE `testemunho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `adm`
--
ALTER TABLE `adm`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`id_client`) REFERENCES `users` (`id_client`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `users` (`id_client`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
