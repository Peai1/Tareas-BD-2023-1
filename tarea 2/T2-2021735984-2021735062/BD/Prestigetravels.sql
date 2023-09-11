-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-06-2023 a las 01:20:42
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tareabd`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_hp` (IN `id` INT, IN `tipo` VARCHAR(256), IN `prom` FLOAT)   BEGIN
	IF tipo = 'Hotel' THEN
    	UPDATE hoteles SET calif_promedio = prom WHERE id_hotel = id;
    ELSE
    	UPDATE paquetes SET calif_promedio = prom WHERE id_paquete = id;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `rut_usuario` varchar(9) NOT NULL,
  `id_paquete` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `pago_unitario` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `pago_total` int(11) NOT NULL,
  `nro_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`rut_usuario`, `id_paquete`, `id_hotel`, `pago_unitario`, `cantidad`, `pago_total`, `nro_item`) VALUES
('204296146', NULL, 6, 1000, 1, 1000, 1),
('204296146', 4, NULL, 3000, 1, 3000, 2),
('211472854', NULL, 5, 100, 1, 100, 1),
('211472854', 4, NULL, 3000, 2, 6000, 2),
('204296146', NULL, 6, 1000, 2, 2000, 3);

--
-- Disparadores `carrito`
--
DELIMITER $$
CREATE TRIGGER `actualizar_disponibilidad` AFTER DELETE ON `carrito` FOR EACH ROW BEGIN
	IF OLD.id_hotel IS NOT NULL THEN
    	UPDATE hoteles SET hoteles.habitaciones_dispo = hoteles.habitaciones_dispo + 1 WHERE hoteles.id_hotel = OLD.id_hotel;
    ELSE
    	UPDATE paquetes SET paquetes.numero_paquetesdispo = paquetes.numero_paquetesdispo + 1 WHERE paquetes.id_paquete = OLD.id_paquete;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `rut_usuario` varchar(9) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `id_paquete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`rut_usuario`, `id_hotel`, `id_paquete`) VALUES
('204296146', NULL, 10),
('204296146', 7, NULL),
('204296146', 8, NULL),
('204296146', NULL, 9),
('204296146', NULL, 7),
('204296146', 6, NULL),
('204296146', 10, NULL),
('204296146', 9, NULL),
('211472854', 1, NULL),
('211472854', 3, NULL),
('211472854', 5, NULL),
('211472854', 4, NULL),
('211472854', 2, NULL),
('211472854', NULL, 1),
('211472854', NULL, 2),
('211472854', NULL, 3),
('211472854', NULL, 4),
('211472854', NULL, 5),
('211472854', NULL, 6),
('211472854', NULL, 8),
('204296146', 1, NULL),
('204296146', NULL, 1),
('204296146', NULL, 5),
('208198122', 4, NULL),
('208198122', NULL, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoteles`
--

CREATE TABLE `hoteles` (
  `id_hotel` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `num_estrellas` int(11) NOT NULL,
  `precio_noche` float NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `num_habitaciones` int(11) NOT NULL,
  `habitaciones_dispo` int(11) NOT NULL,
  `estacionamiento` tinyint(1) NOT NULL,
  `piscina` tinyint(1) NOT NULL,
  `lavanderia` tinyint(1) NOT NULL,
  `pet_friendly` tinyint(1) NOT NULL,
  `desayuno` tinyint(1) NOT NULL,
  `calif_promedio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hoteles`
--

INSERT INTO `hoteles` (`id_hotel`, `nombre`, `num_estrellas`, `precio_noche`, `ciudad`, `num_habitaciones`, `habitaciones_dispo`, `estacionamiento`, `piscina`, `lavanderia`, `pet_friendly`, `desayuno`, `calif_promedio`) VALUES
(1, 'Trivago', 5, 1000, 'Santiago', 100, 39, 1, 0, 1, 0, 1, 3.5),
(2, 'Sheraton', 4, 500, 'Puente Alto', 90, 22, 1, 1, 1, 1, 1, 0),
(3, 'USM', 1, 3000, 'San Joaquin', 900, 893, 1, 1, 1, 0, 0, 1),
(4, 'CIAC', 3, 4000, 'La Florida', 30, 10, 1, 1, 1, 1, 1, 3.5),
(5, 'NOAC', 5, 100, 'La Granja', 400, 121, 1, 0, 0, 0, 0, 3),
(6, 'hotel_6', 2, 1000, 'Santiago', 100, 46, 1, 0, 1, 0, 1, 4.75),
(7, 'hotel_7', 5, 500, 'Puente Alto', 90, 21, 1, 1, 1, 1, 1, 3.75),
(8, 'hotel_8', 3, 3000, 'San Joaquin', 900, 893, 1, 1, 1, 0, 0, 2.6),
(9, 'hotel_9', 4, 4000, 'La Florida', 30, 0, 1, 1, 1, 1, 1, 4),
(10, 'hotel_10', 4, 100, 'La Granja', 400, 121, 1, 0, 0, 0, 0, 3.08333),
(11, 'Mercure', 4, 3000, 'Santiago', 523, 520, 1, 1, 1, 0, 1, 0),
(12, 'La Casa Roja', 4, 2500, 'Santiago', 220, 200, 1, 0, 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `id_paquete` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `aerolinea_ida` varchar(100) NOT NULL,
  `aerolinea_vuelta` varchar(100) NOT NULL,
  `hospedaje_1` varchar(50) NOT NULL,
  `hospedaje_2` varchar(50) DEFAULT NULL,
  `hospedaje_3` varchar(50) DEFAULT NULL,
  `ciudad_1` varchar(50) NOT NULL,
  `ciudad_2` varchar(50) DEFAULT NULL,
  `ciudad_3` varchar(50) DEFAULT NULL,
  `fecha_salida` date NOT NULL,
  `fecha_llegada` date NOT NULL,
  `noches_totales` int(11) NOT NULL,
  `precio_persona` float NOT NULL,
  `numero_paquetesdispo` int(11) NOT NULL,
  `numero_paquetestotal` int(11) NOT NULL,
  `maximo_personas` int(11) NOT NULL,
  `calif_promedio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`id_paquete`, `nombre`, `aerolinea_ida`, `aerolinea_vuelta`, `hospedaje_1`, `hospedaje_2`, `hospedaje_3`, `ciudad_1`, `ciudad_2`, `ciudad_3`, `fecha_salida`, `fecha_llegada`, `noches_totales`, `precio_persona`, `numero_paquetesdispo`, `numero_paquetestotal`, `maximo_personas`, `calif_promedio`) VALUES
(1, 'paquete 1', 'aerolinea 1', 'aerolinea 2', 'Sheraton', 'Trivago', NULL, 'Puente Alto', 'Santiago', NULL, '2023-05-01', '2023-05-03', 5, 1000, 147, 200, 4, 3),
(2, 'paquete_2', 'aerolinea_4', 'aerolinea_3', 'CIAC', 'NOAC', 'USM', 'La Florida', 'La Granja', 'San Joaquin', '2023-03-01', '2023-05-12', 7, 3000, 78, 100, 2, 4),
(3, 'paquete_3', 'aerolinea 1', 'aerolinea 2', 'Sheraton', 'Trivago', NULL, 'Puente Alto', 'Santiago', NULL, '2023-05-01', '2023-05-03', 5, 1000, 149, 200, 4, 4),
(4, 'paquete_4', 'aerolinea_4', 'aerolinea_3', 'CIAC', 'NOAC', 'USM', 'La Florida', 'La Granja', 'San Joaquin', '2023-03-01', '2023-05-12', 7, 3000, 76, 100, 2, 4),
(5, 'paquete_5', 'aerolinea 1', 'aerolinea 2', 'Sheraton', 'Trivago', NULL, 'Puente Alto', 'Santiago', NULL, '2023-05-01', '2023-05-03', 5, 1000, 147, 200, 4, 3),
(6, 'paquete_6', 'aerolinea_4', 'aerolinea_3', 'CIAC', 'NOAC', 'USM', 'La Florida', 'La Granja', 'San Joaquin', '2023-03-01', '2023-05-12', 7, 3000, 78, 100, 2, 1),
(7, 'paquete_7', 'aerolinea 1', 'aerolinea 2', 'Sheraton', 'Trivago', NULL, 'Puente Alto', 'Santiago', NULL, '2023-05-01', '2023-05-03', 5, 1000, 147, 200, 4, 3.55),
(8, 'paquete_8', 'aerolinea_4', 'aerolinea_3', 'CIAC', 'NOAC', 'USM', 'La Florida', 'La Granja', 'San Joaquin', '2023-03-01', '2023-05-12', 7, 3000, 78, 100, 2, 3),
(9, 'paquete_9', 'aerolinea 1', 'aerolinea 2', 'Sheraton', 'Trivago', NULL, 'Puente Alto', 'Santiago', NULL, '2023-05-01', '2023-05-03', 5, 1000, 147, 200, 4, 3),
(10, 'paquete_10', 'aerolinea_4', 'aerolinea_3', 'CIAC', 'NOAC', 'USM', 'La Florida', 'La Granja', 'San Joaquin', '2023-03-01', '2023-05-12', 7, 3000, 77, 100, 2, 3.75),
(11, 'paquete_11', 'Despegar', 'Emirates', 'Trivago', 'Mercure', NULL, 'Santiago', 'Santiago', NULL, '2023-06-06', '2023-06-08', 9, 5000, 25, 10, 3, 0),
(12, 'paquete_12', 'LATAM', 'UNITED', 'Sheraton', 'La Casa Roja', NULL, 'Puente Alto', 'Santiago', NULL, '2023-06-15', '2023-06-17', 5, 5350, 214, 153, 4, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(11) NOT NULL,
  `rut_usuario` varchar(9) NOT NULL,
  `id_hotel` int(11) DEFAULT NULL,
  `id_paquete` int(11) DEFAULT NULL,
  `calificacion_1` int(11) NOT NULL,
  `calificacion_2` int(11) NOT NULL,
  `calificacion_3` int(11) NOT NULL,
  `calificacion_4` int(11) NOT NULL,
  `calif_prom` float NOT NULL,
  `comentario` varchar(256) NOT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id_resena`, `rut_usuario`, `id_hotel`, `id_paquete`, `calificacion_1`, `calificacion_2`, `calificacion_3`, `calificacion_4`, `calif_prom`, `comentario`, `fecha`) VALUES
(24, '204296146', 7, NULL, 5, 3, 5, 2, 3.75, '¡Increíble! Habitaciones amplias, personal amable y excelente ubicación.', '2023-05-29'),
(26, '204296146', 8, NULL, 4, 3, 2, 1, 2, 'La limpieza dejaba mucho que desear. Las habitaciones estaban descuidadas y sucias.', '2023-05-28'),
(27, '204296146', 8, NULL, 2, 3, 4, 5, 2, 'El servicio fue deficiente. El personal era poco atento y poco dispuesto a ayudar.', '2023-05-28'),
(76, '204296146', NULL, 7, 4, 5, 5, 3, 4, 'El hotel ofrecía una calidad excepcional. Las habitaciones eran lujosas y el servicio impecable. El transporte era conveniente y la relación precio-calidad valía la pena.', '2023-05-29'),
(77, '204296146', NULL, 7, 4, 5, 5, 3, 4, 'Una estancia fantástica en un hotel de alta calidad. El transporte era eficiente y el servicio del personal fue excepcional. La relación precio-calidad era muy buena.', '2023-05-29'),
(78, '204296146', NULL, 7, 4, 5, 5, 3, 4, 'Un hotel de gran calidad. Las habitaciones eran espaciosas y bien equipadas. El transporte era fácilmente accesible y el servicio fue excelente. La relación precio-calidad fue excelente.', '2023-05-29'),
(79, '204296146', 8, NULL, 3, 5, 4, 5, 4, 'Impecable limpieza, servicio amable y decoración moderna.', '2023-05-29'),
(80, '204296146', 8, NULL, 4, 3, 5, 3, 4, 'Todo estaba perfectamente limpio. Camas muy cómodas.', '2023-05-29'),
(82, '204296146', 9, NULL, 4, 3, 3, 5, 4, 'Limpieza impecable, servicio excepcional y decoración elegante.', '2023-05-29'),
(83, '204296146', 6, NULL, 5, 5, 5, 4, 4.75, 'Personal amable, camas cómodas y una limpieza impecable.', '2023-05-29'),
(84, '204296146', 10, NULL, 5, 5, 5, 4, 4.75, 'Hotel muy limpio, servicio atento y decoración encantadora.', '2023-05-29'),
(85, '204296146', 6, NULL, 4, 4, 5, 3, 4, 'Buen hotel', '2023-05-29'),
(86, '204296146', 9, NULL, 4, 4, 4, 5, 4.25, 'Limpieza impecable, personal servicial y camas de calidad.', '2023-05-29'),
(87, '204296146', 7, NULL, 2, 3, 1, 3, 2.25, 'Horrible experiencia', '2023-05-29'),
(88, '204296146', 10, NULL, 3, 3, 3, 5, 3.5, 'Buenas camas', '2023-05-29'),
(89, '204296146', NULL, 10, 3, 5, 3, 4, 3.75, 'La calidad del hotel era deficiente. Las habitaciones estaban descuidadas y el servicio dejaba mucho que desear. El transporte era poco confiable y la relación precio-calidad no valía la pena.', '2023-05-29'),
(90, '204296146', NULL, 10, 5, 3, 1, 4, 3.25, 'Mal servicio', '2023-05-29'),
(91, '204296146', NULL, 9, 2, 3, 3, 4, 3, 'Una experiencia decepcionante en un hotel de baja calidad. El transporte era ineficiente y el servicio del personal fue deficiente. La relación precio-calidad no estaba justificada.', '2023-05-29'),
(92, '204296146', NULL, 9, 5, 4, 2, 1, 3, 'El hotel carecía de calidad. Las habitaciones eran antiguas y mal mantenidas. El transporte era limitado y el servicio dejaba mucho que desear. La relación precio-calidad no era favorable.', '2023-05-29'),
(93, '211472854', 3, NULL, 3, 3, 4, 5, 3.75, 'Muy bueno', '2023-05-29'),
(94, '211472854', 3, NULL, 5, 5, 4, 3, 4.25, 'Todo estaba impecable. Camas muy cómodas y servicio amable.', '2023-05-29'),
(95, '211472854', 4, NULL, 2, 3, 5, 3, 3.25, 'Las camas eran incómodas y de mala calidad. No pude descansar adecuadamente durante mi estancia.', '2023-05-29'),
(96, '211472854', 4, NULL, 4, 5, 3, 5, 4.25, 'Limpieza impecable, personal servicial y camas cómodas.', '2023-05-29'),
(97, '211472854', 5, NULL, 3, 4, 4, 1, 3, 'El servicio fue negligente. El personal no mostró interés en atender nuestras necesidades.', '2023-05-29'),
(98, '211472854', 5, NULL, 5, 4, 4, 5, 4.5, 'Limpieza impecable, personal servicial y camas de ensueño.', '2023-05-29'),
(99, '211472854', NULL, 8, 2, 4, 4, 1, 2.75, 'La calidad del hotel no cumplió con mis expectativas. Las instalaciones eran mediocres y el servicio era poco profesional. El transporte era poco conveniente y la relación precio-calidad no estaba justificada.', '2023-05-29'),
(100, '211472854', NULL, 4, 3, 4, 4, 4, 3.75, 'Un hotel de baja calidad. Las habitaciones eran incómodas y poco limpias. El transporte era ineficiente y el servicio del personal fue deficiente. La relación precio-calidad fue insatisfactoria.', '2023-05-29'),
(101, '211472854', NULL, 4, 4, 3, 5, 5, 4.25, 'Me impresionó la calidad del hotel. Las instalaciones eran modernas y elegantes. El transporte era conveniente y el servicio fue amable y atento. La relación precio-calidad era muy favorable.', '2023-05-29'),
(102, '211472854', NULL, 8, 4, 4, 4, 3, 3.75, 'Un hotel de baja calidad. Las habitaciones eran incómodas y poco limpias. El transporte era ineficiente y el servicio del personal fue deficiente. La relación precio-calidad fue insatisfactoria.', '2023-05-29'),
(103, '211472854', NULL, 3, 4, 4, 3, 4, 3.75, 'La calidad del hotel era deficiente. Las habitaciones estaban descuidadas y el servicio dejaba mucho que desear. El transporte era poco confiable y la relación precio-calidad no valía la pena.', '2023-05-29'),
(104, '211472854', NULL, 3, 5, 4, 3, 5, 4.25, 'Un hotel de alta calidad. Las habitaciones eran cómodas y bien mantenidas. El transporte estaba bien conectado y el servicio del personal fue excepcional. La relación precio-calidad fue muy buena.', '2023-05-29'),
(105, '211472854', NULL, 2, 2, 3, 4, 5, 3.5, 'El hotel no ofrecía la calidad esperada. Las habitaciones eran pequeñas y mal equipadas. El transporte era limitado y el servicio fue descuidado. La relación precio-calidad no era satisfactoria.', '2023-05-29'),
(106, '211472854', NULL, 2, 4, 5, 4, 5, 4.5, 'El hotel superó mis expectativas en términos de calidad. Las habitaciones eran espaciosas y elegantes. El transporte era fácilmente accesible y el servicio fue impecable. La relación precio-calidad fue excelente.', '2023-05-29'),
(110, '204296146', 1, NULL, 3, 3, 3, 5, 3.5, 'La limpieza del hotel fue decepcionante. Las habitaciones no estaban bien mantenidas.', '2023-05-31'),
(111, '204296146', NULL, 5, 5, 3, 2, 3, 3.25, 'Pan con queso', '2023-05-31'),
(119, '204296146', 10, NULL, 1, 1, 1, 1, 1, 'La decoración era aburrida y sin vida. El ambiente general del hotel era poco acogedor.', '2023-06-02'),
(121, '204296146', NULL, 10, 4, 5, 4, 4, 4.25, 'Buenos hoteles', '2023-06-02'),
(122, '204296146', NULL, 7, 4, 3, 5, 3, 3.75, 'Hoteles promedio', '2023-06-06'),
(138, '208198122', 4, NULL, 5, 5, 1, 1, 3, '', '2023-06-08'),
(139, '208198122', NULL, 7, 1, 1, 5, 1, 2, '', '2023-06-08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `rut` varchar(9) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `descuento` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`rut`, `nombre`, `fecha_nacimiento`, `correo`, `password`, `descuento`) VALUES
('204296146', 'GT', '2000-06-12', 'hola@usm.cl', '123', 1),
('208198122', 'Joaquin', '2023-06-12', 'joaquin@gmail.com', '1234', 0),
('211472854', 'qwe', '2023-01-01', 'adfvbb', 'asd', 1),
('21313', 'FELIPE ANDRE', '2023-05-16', 'aer', 'awq', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wishlist`
--

CREATE TABLE `wishlist` (
  `rut_usuario` varchar(9) NOT NULL,
  `id_paquete` int(11) DEFAULT NULL,
  `id_hotel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `wishlist`
--

INSERT INTO `wishlist` (`rut_usuario`, `id_paquete`, `id_hotel`) VALUES
('204296146', NULL, 8),
('204296146', 7, NULL),
('204296146', NULL, 1),
('204296146', NULL, 7),
('211472854', NULL, 6),
('211472854', 5, NULL),
('211472854', NULL, 4),
('204296146', NULL, 4),
('211472854', NULL, 1),
('204296146', NULL, 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD KEY `usuarios_rut_usuario_carrito` (`rut_usuario`),
  ADD KEY `paquetes_id_paquete_carrito` (`id_paquete`),
  ADD KEY `hoteles_id_hotel_carrito` (`id_hotel`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD KEY `rut_usuario` (`rut_usuario`),
  ADD KEY `id_hotel` (`id_hotel`),
  ADD KEY `id_paquete` (`id_paquete`);

--
-- Indices de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`id_paquete`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `usuarios_rut_usuario_resenas` (`rut_usuario`),
  ADD KEY `hoteles_id_hotel_resenas` (`id_hotel`),
  ADD KEY `paquetes_id_paquete_resenas` (`id_paquete`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`rut`);

--
-- Indices de la tabla `wishlist`
--
ALTER TABLE `wishlist`
  ADD KEY `hoteles_id_hotel_wishlist` (`id_hotel`),
  ADD KEY `paquetes_id_paquete_wishlist` (`id_paquete`),
  ADD KEY `usuarios_rut_usuario_wishlist` (`rut_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `id_paquete` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `hoteles_id_hotel_carrito` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paquetes_id_paquete_carrito` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id_paquete`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_rut_usuario_carrito` FOREIGN KEY (`rut_usuario`) REFERENCES `usuarios` (`rut`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`rut_usuario`) REFERENCES `usuarios` (`rut`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_ibfk_3` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id_paquete`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `hoteles_id_hotel_resenas` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paquetes_id_paquete_resenas` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id_paquete`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_rut_usuario_resenas` FOREIGN KEY (`rut_usuario`) REFERENCES `usuarios` (`rut`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `hoteles_id_hotel_wishlist` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paquetes_id_paquete_wishlist` FOREIGN KEY (`id_paquete`) REFERENCES `paquetes` (`id_paquete`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_rut_usuario_wishlist` FOREIGN KEY (`rut_usuario`) REFERENCES `usuarios` (`rut`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
