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

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nombre` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `dni` VARCHAR(30) NOT NULL UNIQUE,
  `telefono` INT NOT NULL,
  `fechaNacimiento` DATE NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` (`nombre`, `apellidos`, `dni`, `telefono`, `fechaNacimiento`, `email`, `password`) VALUES
('pablo', 'Apellidos1', '123456789', 1234567890, '2000-01-01', 'correo1@example.com', 'contraseña1'),
('Nombre2', 'Apellidos2', '987654321', 9876543210, '2001-02-02', 'correo2@example.com', 'contraseña2');


CREATE TABLE `asignaturas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(255) NOT NULL,
    `descripcion` TEXT,
    `creditos` INT NOT NULL,
    `convocatorias_usadas` INT NOT NULL,
    `imagen` LONGBLOB,
    `dni` VARCHAR(30) NOT NULL
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;






ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`id`, `dni`);
COMMIT;

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`dni`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
