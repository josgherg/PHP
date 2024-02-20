-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-02-2024 a las 22:22:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rsocial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `id_publicacion` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `contenido` varchar(1000) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `id_publicacion`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(2, 4, 1, 'Si, yo pienso lo mismo!', '2024-02-15 14:01:22', '2024-02-15 14:01:22'),
(3, 3, 3, 'Dijo su primer agugugaga! (=', '2024-02-15 18:25:19', '2024-02-15 18:25:19'),
(14, 10, 3, 'excelente!', '2024-02-19 03:46:09', '2024-02-19 03:46:09'),
(16, 1, 3, 'Excelente, yo quiero ver como es!', '2024-02-19 04:04:37', '2024-02-19 04:04:56'),
(18, 7, 1, 'Naranjas todavia!', '2024-02-20 21:19:13', '2024-02-20 21:19:13'),
(19, 3, 1, 'haha si!!', '2024-02-20 21:19:46', '2024-02-20 21:19:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos`
--

CREATE TABLE `fotos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `enlace` varchar(256) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fotos`
--

INSERT INTO `fotos` (`id`, `id_usuario`, `enlace`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, './images/foto1.jpeg', '2024-02-13 20:14:04', '2024-02-13 20:14:04'),
(2, 5, './images/foto3.jpeg', '2024-02-18 20:24:23', '2024-02-18 20:24:23'),
(3, 3, './images/foto2.jpeg', '2024-02-18 20:41:46', '2024-02-18 20:41:46'),
(4, 2, './images/foto4.jpg', '2024-02-18 20:46:11', '2024-02-18 20:47:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `contenido` varchar(1000) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `id_usuario`, `contenido`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, 'Estoy creando una nueva red social la cual pondré en prueba pronto. ¿Quien quiere probarla? diga yo xD', '2024-02-13 20:35:59', '2024-02-19 21:16:46'),
(3, 2, 'ahgugugaga!!!', '2024-02-13 21:32:30', '2024-02-18 20:48:55'),
(4, 3, 'Que bonito día!', '2024-02-15 14:00:22', '2024-02-15 14:00:22'),
(6, 3, 'Ya casi es fin de semana!', '2024-02-16 18:14:29', '2024-02-16 18:14:29'),
(7, 5, 'Crearon la aplicación? era para ayer!', '2024-02-16 19:01:42', '2024-02-16 19:01:42'),
(10, 1, 'Hoy es Domingo. a terminar el proyecto de PHP...', '2024-02-18 14:40:33', '2024-02-18 20:09:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperaciones`
--

CREATE TABLE `recuperaciones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `codigo` varchar(6) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recuperaciones`
--

INSERT INTO `recuperaciones` (`id`, `id_usuario`, `codigo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(4, 1, '91wpfJ', '2024-02-14 00:21:30', '2024-02-14 00:21:30'),
(5, 5, 'MR9THJ', '2024-02-16 00:09:28', '2024-02-16 00:09:28'),
(6, 2, 'gDGE83', '2024-02-20 18:56:01', '2024-02-20 18:56:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(100) DEFAULT NULL,
  `correo_electronico` varchar(100) DEFAULT NULL,
  `contrasena` varchar(100) DEFAULT NULL,
  `rol` varchar(7) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `correo_electronico`, `contrasena`, `rol`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Jose Herrera', 'herr100j@gmail.com', '$2y$10$3/L0jW4reTVpp4vz97d0hOxBaarX.dmGQQHJm9SEDpm/hg4AHNM6K', 'user', '2024-02-13 18:11:44', '2024-02-13 18:18:22'),
(2, 'Ángel Marino Herrera Marcano', 'am@gmail.com', '$2y$10$KA.1Z25xmymCOAVGhGbbVeoM7kxMWP5RtIEiyNg9i3EYl1yFhY4CS', 'admin', '2024-02-13 21:31:18', '2024-02-20 19:16:10'),
(3, 'Enmary Marcano', 'marry100e@gmail.com', '$2y$10$tBsqGDG2uXHdx4VFZ0BCqOmzM1nutx3uk1GfJOm8b.YK2JJAIW6Xy', 'user', '2024-02-15 13:50:28', '2024-02-15 13:50:28'),
(5, 'Elimelech Attale', 'elimelechattale23@gmail.com', '$2y$10$a32z5yDOeGLB2HSoxlcFvO/7Vjh6sCVfdvzsRHrM4ks1uf8NrxyLe', 'user', '2024-02-16 00:08:40', '2024-02-16 00:10:13');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_publicacion` (`id_publicacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `recuperaciones`
--
ALTER TABLE `recuperaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `recuperaciones`
--
ALTER TABLE `recuperaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`id_publicacion`) REFERENCES `publicaciones` (`id`),
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fotos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `recuperaciones`
--
ALTER TABLE `recuperaciones`
  ADD CONSTRAINT `recuperaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
