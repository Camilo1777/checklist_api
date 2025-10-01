-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3305
-- Generation Time: Sep 29, 2025 at 09:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `checklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `asignacion`
--

CREATE TABLE `asignacion` (
  `idasignacion` int(11) NOT NULL,
  `idmateria` int(11) DEFAULT NULL,
  `cedulaprofesor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asignacion`
--

INSERT INTO `asignacion` (`idasignacion`, `idmateria`, `cedulaprofesor`) VALUES
(1, 1, 2001),
(2, 2, 2001),
(3, 3, 2002),
(4, 4, 2002),
(5, 5, 2003),
(6, 6, 2003);

-- --------------------------------------------------------

--
-- Table structure for table `asistencias`
--

CREATE TABLE `asistencias` (
  `idasistencia` int(11) NOT NULL,
  `idasignacion` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `idinscripcion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estudiante`
--

CREATE TABLE `estudiante` (
  `idestudiante` varchar(45) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `correoinst` varchar(100) DEFAULT NULL,
  `programa` varchar(100) DEFAULT NULL,
  `semestre` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estudiante`
--

INSERT INTO `estudiante` (`idestudiante`, `nombre`, `apellido`, `correoinst`, `programa`, `semestre`) VALUES
('E001', 'Camilo', 'Aguilar', 'camilo.aguilar@uni.edu', 'Ingeniería de Sistemas', 3),
('E002', 'Alejandro', 'Roncancio', 'alejandro.roncancio@uni.edu', 'Ingeniería de Sistemas', 4),
('E003', 'Maria Fernanda', 'Rios', 'maria.rios@uni.edu', 'Ingeniería de Sistemas', 2),
('E004', 'Santiago', 'Daza', 'santiago.daza@uni.edu', 'Ingeniería de Sistemas', 5),
('E005', 'Valeria', 'Ospina', 'valeria.ospina@uni.edu', 'Ingeniería de Sistemas', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inscripcion`
--

CREATE TABLE `inscripcion` (
  `idinscripcion` int(11) NOT NULL,
  `idestudiante` varchar(45) DEFAULT NULL,
  `idasignacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inscripcion`
--

INSERT INTO `inscripcion` (`idinscripcion`, `idestudiante`, `idasignacion`) VALUES
(1, 'E001', 1),
(2, 'E001', 2),
(3, 'E001', 3),
(4, 'E001', 4),
(5, 'E002', 2),
(6, 'E002', 3),
(7, 'E002', 4),
(8, 'E002', 5),
(9, 'E003', 3),
(10, 'E003', 4),
(11, 'E003', 5),
(12, 'E003', 6),
(13, 'E004', 1),
(14, 'E004', 4),
(15, 'E004', 5),
(16, 'E004', 6),
(17, 'E005', 1),
(18, 'E005', 2),
(19, 'E005', 5),
(20, 'E005', 6);

-- --------------------------------------------------------

--
-- Table structure for table `materias`
--

CREATE TABLE `materias` (
  `idmateria` int(11) NOT NULL,
  `nombremateria` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materias`
--

INSERT INTO `materias` (`idmateria`, `nombremateria`) VALUES
(1, 'Internet de las Cosas'),
(2, 'Desarrollo de Software I'),
(3, 'Bases de Datos'),
(4, 'Arquitectura de Datos'),
(5, 'Programación Web'),
(6, 'Programación Avanzada y Aplicada');

-- --------------------------------------------------------

--
-- Table structure for table `profesor`
--

CREATE TABLE `profesor` (
  `cedulaprofesor` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profesor`
--

INSERT INTO `profesor` (`cedulaprofesor`, `nombre`, `apellido`, `email`, `password`) VALUES
(100, 'camilo', 'aguilar', 'camilo@usb', '$2b$12$LG885IBEDu2u34j6haLceudp6KJm7y8rIy3lfeMecR2bxRbSntfFq'),
(2001, 'Paolo', 'Tovar', 'paolo.tovar@usbbog', '$2b$12$utTk8vSCU12F1v2Gi5pGxeVDjI.c1dbw0cILC7rDkTyEbxLcSu6AG'),
(2002, 'Mary', 'Rubiano', 'mary.rubiano@usbbog', '$2b$12$HA1NbXtsa8KlJRud7zjmJOjy.51qpBaQViGVcRdGzYOj4JvxQkl3y'),
(2003, 'Andres', 'Miranda', 'andres.miranda@usbbog', '$2b$12$IIiSGdKJeGc08kg7gK1vROD29yCnsiU7HRDKPYmW.BVlk/iyWkyEq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`idasignacion`),
  ADD KEY `idmateria` (`idmateria`),
  ADD KEY `cedulaprofesor` (`cedulaprofesor`);

--
-- Indexes for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`idasistencia`),
  ADD KEY `idasignacion` (`idasignacion`),
  ADD KEY `idinscripcion` (`idinscripcion`);

--
-- Indexes for table `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`idestudiante`);

--
-- Indexes for table `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD PRIMARY KEY (`idinscripcion`),
  ADD KEY `idestudiante` (`idestudiante`),
  ADD KEY `idasignacion` (`idasignacion`);

--
-- Indexes for table `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idmateria`);

--
-- Indexes for table `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`cedulaprofesor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `idasignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscripcion`
--
ALTER TABLE `inscripcion`
  MODIFY `idinscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `materias`
--
ALTER TABLE `materias`
  MODIFY `idmateria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asignacion`
--
ALTER TABLE `asignacion`
  ADD CONSTRAINT `asignacion_ibfk_1` FOREIGN KEY (`idmateria`) REFERENCES `materias` (`idmateria`),
  ADD CONSTRAINT `asignacion_ibfk_2` FOREIGN KEY (`cedulaprofesor`) REFERENCES `profesor` (`cedulaprofesor`);

--
-- Constraints for table `asistencias`
--
ALTER TABLE `asistencias`
  ADD CONSTRAINT `asistencias_ibfk_1` FOREIGN KEY (`idasignacion`) REFERENCES `asignacion` (`idasignacion`),
  ADD CONSTRAINT `asistencias_ibfk_2` FOREIGN KEY (`idinscripcion`) REFERENCES `inscripcion` (`idinscripcion`);

--
-- Constraints for table `inscripcion`
--
ALTER TABLE `inscripcion`
  ADD CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (`idestudiante`) REFERENCES `estudiante` (`idestudiante`),
  ADD CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`idasignacion`) REFERENCES `asignacion` (`idasignacion`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
