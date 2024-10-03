-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `database`;
USE `database`;

-- Crear la tabla para los usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `apellidos` VARCHAR(100) NOT NULL,
  `dni` VARCHAR(10) NOT NULL UNIQUE,
  `telefono` VARCHAR(9) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `nombre_usuario` VARCHAR(50) NOT NULL UNIQUE,
  `contrasena` VARCHAR(255) NOT NULL, -- Se guardará encriptada
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `usuarios` (`nombre`, `apellidos`, `dni`, `telefono`, `fecha_nacimiento`, `email`, `nombre_usuario`, `contrasena`) VALUES
('Juan', 'Pérez', '12345678-Z', '600123456', '1990-05-10', 'juan.perez@example.com', 'juanp', MD5('password123')),
('Ana', 'García', '87654321-X', '600987654', '1985-11-22', 'ana.garcia@example.com', 'anag', MD5('mypassword'));

-- Crear la tabla para los ítems (por ejemplo, películas)
CREATE TABLE IF NOT EXISTS `items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NOT NULL,
  `anio` INT(4) NOT NULL,
  `director` VARCHAR(100) NOT NULL,
  `genero` VARCHAR(50) NOT NULL,
  `descripcion` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar algunos ítems de ejemplo (películas)
INSERT INTO `items` (`titulo`, `anio`, `director`, `genero`, `descripcion`) VALUES
('The Matrix', 1999, 'Lana Wachowski, Lilly Wachowski', 'Ciencia ficción', 'Un hacker descubre la verdadera naturaleza de su realidad.'),
('Inception', 2010, 'Christopher Nolan', 'Ciencia ficción', 'Un ladrón especializado en el espionaje dentro de los sueños.');
