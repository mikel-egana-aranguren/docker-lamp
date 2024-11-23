-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

-- "ERABILTZAILEAK" taula

CREATE TABLE `erabiltzailea` (
  `izena` varchar(20) NOT NULL,
  `abizena` varchar(40) NOT NULL,
  `NAN` varchar(10) NOT NULL,
  `pasahitza` CHAR(60) NOT NULL,
  `telefonoa` INT(9) NOT NULL,
  `jaiotzeData` VARCHAR(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` boolean NOT NULL DEFAULT 0,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE bideojokoa (
  `titulu` varchar(50) NOT NULL,
  `egilea` varchar(50) NOT NULL,
  `prezioa` float NOT NULL,
  `mota` varchar(20) NOT NULL,
  `urtea` int(4) NOT NULL,
  PRIMARY KEY (`titulu`, `egilea`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO bideojokoa VALUES('Super Mario Odyssey', 'Nintendo', 59.99, 'Plataformas', 2017);
INSERT INTO bideojokoa VALUES('The Legend of Zelda: Breath of the Wild', 'Nintendo', 59.99, 'Aventura', 2017);
INSERT INTO bideojokoa VALUES('Mario Kart 8 Deluxe', 'Nintendo', 59.99, 'Carreras', 2017);
INSERT INTO bideojokoa VALUES('Splatoon 2', 'Nintendo', 59.99, 'Shooter', 2017);

INSERT INTO erabiltzailea VALUES('admin', 'admin', 'NAN', '$2y$10$sQlobyZ3F9TDBBnkCo820uowm5G.GxUUZHMBmPun5OrGTR4./MF8W', 000000000, '2020-09-16', 'issksadmin@admin.com', 1);
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`) VALUES
(1, 'mikel'),
(2, 'aitor');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
