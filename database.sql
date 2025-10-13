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
CREATE DATABASE IF NOT EXISTS `database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `database`;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE `usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` TEXT NOT NULL,
  `contrasena` TEXT NOT NULL,
  `nombre` TEXT NOT NULL,
  `apellido` TEXT NOT NULL,
  `numDni` TEXT NOT NULL,
  `letraDni` TEXT NOT NULL,
  `tlfn` TEXT NOT NULL,
  `fNacimien` DATE NOT NULL,
  `email` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO `usuarios` (`usuario`, `contrasena`, `nombre`, `apellido`, `numDni`, `letraDni`, `tlfn`, `fNacimien`, `email`) VALUES
('admin', 'admin123', 'Admin', 'Admin', '12345678', 'A', '600123456', '1990-01-01', 'admin@gmail.com');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `pelicula`
--
CREATE TABLE `pelicula` (
  `idPelicula` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(40) NOT NULL,
  `anio` VARCHAR(10) NOT NULL,
  `genero` VARCHAR(20) NOT NULL,
  `director` VARCHAR(30) NOT NULL,
  `duracion` INT(4) NOT NULL,
  PRIMARY KEY (`idPelicula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pelicula`
--
INSERT INTO `pelicula` (`idPelicula`, `titulo`, `anio`, `genero`, `director`, `duracion`) VALUES
(1, 'Interstellar', '2014', 'Ciencia Ficción', 'Christopher Nolan', 169),
(2, 'Mamma Mia!', '2008', 'Musical', 'Phyllida Lloyd', 108),
(3, 'Zohan', '2008', 'Comedia', 'Dennis Dugan', 112),
(4, 'Wicked', '2024', 'Musical', 'Stephen Daldry', 150),
(5, 'El gran Gatsby', '2013', 'Drama', 'Baz Luhrmann', 143);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;