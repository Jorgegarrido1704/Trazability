-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2023 at 09:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `registro`
--

-- --------------------------------------------------------

--
-- Table structure for table `registro_paro`
--

CREATE TABLE `registro_paro` (
  `id` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(5) NOT NULL,
  `equipo` varchar(30) NOT NULL,
  `nombreEquipo` varchar(200) NOT NULL,
  `dano` varchar(25) NOT NULL,
  `quien` varchar(30) NOT NULL,
  `area` varchar(20) NOT NULL,
  `atiende` varchar(30) NOT NULL,
  `Tiempo` varchar(50) NOT NULL,
  `finhora` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registro_paro`
--

INSERT INTO `registro_paro` (`id`, `fecha`, `hora`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`, `Tiempo`, `finhora`) VALUES
(1, '13-10-2023 ', '12:45', 'Maquinas', 'CR-22', 'Impresora', 'FERNANDO', 'Fernando_cort', 'juanperes', '', ''),
(2, '13-10-2023 ', '12:49', 'otro', 'PARNOS', '', 'ZAMARRIPA', 'Zamarripa_tab', 'juan perez', '', ''),
(3, '13-10-2023 ', '12:51', '', '', '', '', '', '7506475113915', '', ''),
(4, '13-10-2023 ', '13:01', '', '', '', '', '', '2020392', '', ''),
(5, '13-10-2023 ', '13:02', 'Bancos para terminales', 'CR.21', 'Ajuste de presión', 'ANGEL', 'Angel_lib', 'juan', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `registro_paro_corte`
--

CREATE TABLE `registro_paro_corte` (
  `id` int(11) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `hora` varchar(5) NOT NULL,
  `equipo` varchar(30) NOT NULL,
  `nombreEquipo` varchar(200) NOT NULL,
  `dano` varchar(25) NOT NULL,
  `quien` varchar(30) NOT NULL,
  `area` varchar(20) NOT NULL,
  `atiende` varchar(30) NOT NULL,
  `Tiempo` varchar(50) NOT NULL,
  `finhora` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registro_paro_corte`
--

INSERT INTO `registro_paro_corte` (`id`, `fecha`, `hora`, `equipo`, `nombreEquipo`, `dano`, `quien`, `area`, `atiende`, `Tiempo`, `finhora`) VALUES
(4, '13-10-2023 ', '13:01', '', '', '', '', '', '2020392', '', ''),
(5, '13-10-2023 ', '13:02', 'Bancos para terminales', 'CR.21', 'Ajuste de presión', 'ANGEL', 'Angel_lib', 'juan', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tiempoman`
--

CREATE TABLE `tiempoman` (
  `id` int(11) NOT NULL,
  `idincidencia` int(11) NOT NULL,
  `iniciofecha` varchar(10) NOT NULL,
  `iniciohora` varchar(5) NOT NULL,
  `finfecha` varchar(10) NOT NULL,
  `finhora` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tiempoman`
--

INSERT INTO `tiempoman` (`id`, `idincidencia`, `iniciofecha`, `iniciohora`, `finfecha`, `finhora`) VALUES
(1, 1, '13/10/2023', '12:46', '13/10/2023', '12:46'),
(2, 2, '13/10/2023', '12:49', '13/10/2023', '12:50'),
(3, 3, '13/10/2023', '12:51', '13/10/2023', '12:51'),
(4, 4, '13/10/2023', '13:02', '', ''),
(5, 5, '13/10/2023', '13:03', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registro_paro`
--
ALTER TABLE `registro_paro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registro_paro_corte`
--
ALTER TABLE `registro_paro_corte`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiempoman`
--
ALTER TABLE `tiempoman`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `registro_paro`
--
ALTER TABLE `registro_paro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `registro_paro_corte`
--
ALTER TABLE `registro_paro_corte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tiempoman`
--
ALTER TABLE `tiempoman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
