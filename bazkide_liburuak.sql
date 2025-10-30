-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 29-10-2025 a las 07:44:20
-- Versión del servidor: 10.8.2-MariaDB-1:10.8.2+maria~focal
-- Versión de PHP: 8.3.26

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

--
-- Estructura de tabla para la tabla `bazkide_liburuak`
--

CREATE TABLE `bazkide_liburuak` (
  `bazkide_id` int(11) NOT NULL,
  `liburu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bazkide_liburuak`
--
ALTER TABLE `bazkide_liburuak`
  ADD PRIMARY KEY (`bazkide_id`,`liburu_id`),
  ADD KEY `liburu_id` (`liburu_id`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bazkide_liburuak`
--
ALTER TABLE `bazkide_liburuak`
  ADD CONSTRAINT `bazkide_liburuak_ibfk_1` FOREIGN KEY (`bazkide_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bazkide_liburuak_ibfk_2` FOREIGN KEY (`liburu_id`) REFERENCES `elementuak` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
