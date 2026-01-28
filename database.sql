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
  `idU` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` TEXT NOT NULL,
  `contrasena` TEXT NOT NULL,
  `nombre` TEXT NOT NULL,
  `apellido` TEXT NOT NULL,
  `numDni` TEXT NOT NULL,
  `letraDni` TEXT NOT NULL,
  `tlfn` TEXT NOT NULL,
  `fNacimiento` DATE NOT NULL,
  `email` TEXT NOT NULL,
  `rol` TEXT NOT NULL DEFAULT 'user',
  PRIMARY KEY (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO `usuarios`
(`usuario`, `contrasena`, `nombre`, `apellido`, `numDni`, `letraDni`, `tlfn`, `fNacimiento`, `email`, `rol`)
VALUES
('admin', 'admin123', 'Admin', 'Admin', '12345678', 'A', '600123456', '1990-01-01', 'admin@gmail.com', 'admin');

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
INSERT INTO `pelicula` (`titulo`, `anio`, `genero`, `director`, `duracion`) VALUES
('Interstellar', '2014', 'Ciencia Ficción', 'Christopher Nolan', 169),
('Mamma Mia!', '2008', 'Musical', 'Phyllida Lloyd', 108),
('Zohan', '2008', 'Comedia', 'Dennis Dugan', 112),
('Wicked', '2024', 'Musical', 'Stephen Daldry', 150),
('El gran Gatsby', '2013', 'Drama', 'Baz Luhrmann', 143);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;