-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2024 a las 18:43:06
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
-- Base de datos: `vitalfarma`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizaciones`
--

CREATE TABLE `autorizaciones` (
  `cod_auto` varchar(3) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `documento` int(11) NOT NULL,
  `docu_medico` int(11) NOT NULL,
  `medicamento` varchar(400) NOT NULL,
  `fecha_venc` date NOT NULL,
  `id_estado` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time NOT NULL,
  `docu_medico` int(11) NOT NULL,
  `id_esp` int(3) NOT NULL,
  `id_estado` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id_depart` int(11) NOT NULL,
  `depart` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`id_depart`, `depart`) VALUES
(1, 'Antioquia'),
(2, 'Atlantico'),
(3, 'Bogotá, D.C'),
(4, 'Bolivar'),
(5, 'Boyacá'),
(6, 'Caldas'),
(7, 'Caquetá'),
(8, 'Cauca'),
(9, 'Cesar'),
(10, 'Córdoba'),
(11, 'Cundinamarca'),
(12, 'Chocó'),
(13, 'Huila'),
(14, 'La Guajira'),
(15, 'Magdalena'),
(16, 'Nariño'),
(17, 'Norte de Santander'),
(18, 'Quindio'),
(19, 'Risaralda'),
(20, 'Santander'),
(21, 'Sucre'),
(22, 'Tolima'),
(23, 'Valle del Cauca'),
(24, 'Arauca'),
(25, 'Casanare'),
(26, 'Putumayo'),
(27, 'Archipiélago de San Andrés y Providencia'),
(28, 'Amazonas'),
(29, 'Guainía'),
(30, 'Guaviare'),
(31, 'Vaupés'),
(32, 'Vichada'),
(33, 'Meta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `det_autorizacion`
--

CREATE TABLE `det_autorizacion` (
  `id_detalle` int(11) NOT NULL,
  `id_auto` int(11) NOT NULL,
  `id_medicamento` int(11) NOT NULL,
  `cantidad` varchar(20) NOT NULL,
  `medida_cant` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `nit` varchar(10) NOT NULL,
  `empresa` varchar(50) NOT NULL,
  `licencia` varchar(10) DEFAULT NULL,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `codigo_unico` int(3) NOT NULL,
  `id_estado` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`nit`, `empresa`, `licencia`, `inicio`, `fin`, `codigo_unico`, `id_estado`) VALUES
('9572841630', 'Salud Total', '3UJ6M#fXFd', '2024-05-10', '2025-05-10', 951, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especializacion`
--

CREATE TABLE `especializacion` (
  `id_esp` int(3) NOT NULL,
  `especializacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `especializacion`
--

INSERT INTO `especializacion` (`id_esp`, `especializacion`) VALUES
(1, 'Medicina Interna'),
(2, 'Pediatría'),
(3, 'Cirugía'),
(4, 'Ginecología y Obstetricia'),
(5, 'Psiquiatría'),
(6, 'Dermatología'),
(7, 'Oftalmología'),
(8, 'Otorrinolaringología'),
(9, 'Cardiología'),
(10, 'Neurología'),
(11, 'Endocrinología'),
(12, 'Nefrología'),
(13, 'Reumatología'),
(15, 'Radiología');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(3) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Entregado'),
(3, 'Activo'),
(4, 'Inactivo'),
(5, 'Activa'),
(6, 'Cancelada'),
(7, 'Disponible'),
(8, 'Agotado'),
(10, 'Ocupado'),
(11, 'Atendido'),
(13, 'Autorizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `histo_clinica`
--

CREATE TABLE `histo_clinica` (
  `id_histo` int(11) NOT NULL,
  `id_cita` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `documento` int(11) NOT NULL,
  `docu_medico` int(11) NOT NULL,
  `descripcion` varchar(400) NOT NULL,
  `diagnostico` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_lab` int(4) NOT NULL,
  `laboratorio` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `laboratorio`
--

INSERT INTO `laboratorio` (`id_lab`, `laboratorio`) VALUES
(2, 'Laboratorio Pharma'),
(3, 'Laboratorio Tecnofarma'),
(4, 'Laboratorio MK'),
(5, 'Laboratorio Biogalenic'),
(6, 'Laboratorio Takeda'),
(7, 'Laboratorio LKM'),
(8, 'Laboratorio Roche'),
(9, 'Laboratorio Bayer'),
(10, 'Laboratorio Abbott'),
(11, 'Laboratorio Duran');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicamentos`
--

CREATE TABLE `medicamentos` (
  `id_medicamento` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `id_cla` int(3) NOT NULL,
  `presentacion` varchar(30) NOT NULL,
  `cantidad` varchar(20) NOT NULL,
  `medida_cant` varchar(20) NOT NULL,
  `id_lab` int(5) NOT NULL,
  `f_vencimiento` date NOT NULL,
  `codigo_barras` varchar(200) NOT NULL,
  `id_estado` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `medicamentos`
--

INSERT INTO `medicamentos` (`id_medicamento`, `nombre`, `id_cla`, `presentacion`, `cantidad`, `medida_cant`, `id_lab`, `f_vencimiento`, `codigo_barras`, `id_estado`) VALUES
(21, 'Paracetamol', 1, 'Tableta', '234UND', '500mg', 5, '2024-06-25', '667964113d3147433', 7),
(22, 'Ibuprofeno', 7, 'Tableta', '213UND', '400mg', 8, '2025-07-22', '6679643fb28391767', 7),
(23, 'Amoxicilina', 2, 'Capsula', '428UND', '500mg', 5, '2024-06-11', '667964954501e7103', 7),
(24, 'Loratadina', 11, 'Jarabe', '234UND', '5ml', 10, '2024-06-12', '667964be6d9f42698', 7),
(25, 'Metformina', 5, 'Tableta', '267UND', '850mg', 2, '2024-10-26', '667965117966b8507', 7),
(26, 'Omeprazol', 12, 'Capsula', '455UND', '20mg', 8, '2026-01-06', '667965511f4742844', 7),
(27, 'Simvastatina', 13, 'Tableta', '567UND', '10mg', 4, '2025-04-09', '6679659f4ba493452', 7),
(28, 'Clonazepam', 14, 'tableta', '116UND', '2mg', 9, '2025-07-15', '667965cb9cf098485', 7),
(29, 'Salbutamol', 15, 'Inhalador', '231UND', '100mcs', 10, '2024-06-20', '66796608801c86070', 7),
(30, 'Diclofenaco', 7, 'Tableta', '541UND', '50mg', 10, '2025-07-15', '6679664c49b485502', 7),
(31, 'Atenolol', 6, 'Tableta', '243UND', '50mg', 10, '2025-08-12', '6679667fdd7f34020', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE `medicos` (
  `docu_medico` int(11) NOT NULL,
  `id_doc` int(2) NOT NULL,
  `nombre_comple` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `id_rol` int(3) NOT NULL,
  `id_estado` int(3) NOT NULL,
  `id_esp` int(3) NOT NULL,
  `nit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int(11) NOT NULL,
  `municipio` varchar(100) NOT NULL,
  `id_depart` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `municipio`, `id_depart`) VALUES
(1, 'Andes', 1),
(3, 'Armenia', 1),
(4, 'Barbosa', 1),
(5, 'Bello', 1),
(7, 'Caceres', 1),
(8, 'Caldas', 1),
(9, 'Caracoli', 1),
(10, 'Ciudad Bolivar', 1),
(11, 'Copacabana', 1),
(12, 'Envigado', 1),
(13, 'Granada', 1),
(14, 'Guatape', 1),
(15, 'Itagui', 1),
(16, 'Ituango', 1),
(17, 'Jerico', 1),
(18, 'Medellin', 1),
(19, 'Montebello', 1),
(20, 'Nariño', 1),
(21, 'Rionegro', 1),
(22, 'Sabanalarga', 1),
(23, 'Sabaneta', 1),
(24, 'San Andrés', 1),
(25, 'San Vicente', 1),
(27, 'Urrao', 1),
(28, 'Zaragoza', 1),
(29, 'Barranquilla', 2),
(30, 'Candelaria', 2),
(31, 'Malambo', 2),
(32, 'Sabanagrande', 2),
(33, 'Sabanalarga', 2),
(34, 'Santa Lucia', 2),
(35, 'Santo Tomas', 2),
(36, 'Bogotá D.C.', 3),
(37, 'Cartagena', 4),
(38, 'Córdoba', 4),
(39, 'Magangué', 4),
(40, 'Margarita', 4),
(41, 'Santa Catalina', 4),
(42, 'Turbaco', 4),
(43, 'Villanueva', 4),
(44, 'Chiquinquirá', 5),
(45, 'Duitama', 5),
(46, 'Tunja', 5),
(47, 'Sogamoso', 5),
(48, 'Villa de Leyva', 5),
(49, 'La Dorada', 6),
(50, 'Manizales', 6),
(51, 'Marquetalia', 6),
(52, 'Risaralda', 6),
(53, 'San Jose de Caldas', 6),
(54, 'Curillo', 7),
(55, 'Florencia', 7),
(56, 'San José del Fragua', 7),
(57, 'San Vicente del Caguan', 7),
(58, 'Bolivar', 8),
(59, 'Guachené', 8),
(60, 'La Sierra', 8),
(61, 'Popayan', 8),
(62, 'Puerto Tejada', 8),
(63, 'Sucre', 8),
(64, 'Villarica', 8),
(65, 'Aguachica', 9),
(66, 'Bosconia', 9),
(67, 'Chimichagua', 9),
(68, 'Curumani', 9),
(69, 'a Gloria', 9),
(70, 'Puebo Bello', 9),
(71, 'San Martin', 9),
(72, 'Valledupar', 9),
(73, 'Buenavista', 10),
(74, 'Cienaga de Oro', 10),
(75, 'Montelibano', 10),
(76, 'Monteria', 10),
(77, 'Pueblo Nuevo', 10),
(78, 'Puerto Escondido', 10),
(79, 'Anapoima', 11),
(80, 'Anolaima', 11),
(81, 'Apulo', 11),
(82, 'Cachipay', 11),
(83, 'Cajica', 11),
(84, 'Chia', 11),
(85, 'Choconta', 11),
(86, 'Cota', 11),
(87, 'Facatativa', 11),
(88, 'Funza', 11),
(89, 'Fusagasuga', 11),
(90, 'Girardot', 11),
(91, 'Guatavita', 11),
(92, 'La calera', 11),
(93, 'La mesa', 11),
(94, 'Madrid', 11),
(95, 'Mosquera', 11),
(96, 'Nocaima', 11),
(97, 'Sibate', 11),
(98, 'Silvania', 11),
(99, 'Soacha', 11),
(100, 'Subachoque', 11),
(101, 'Villeta', 11),
(102, 'Yacopi', 11),
(103, 'Zipaquira', 11),
(104, 'Atrato', 12),
(105, 'Bojaya', 12),
(106, 'Quibdo', 12),
(107, 'Rio Quito', 12),
(108, 'San Jose del Palmar', 12),
(109, 'Acevedo', 13),
(110, 'Algeciras', 13),
(111, 'Altamira', 13),
(112, 'Campoalegre', 13),
(113, 'Neiva', 13),
(114, 'Palermo', 13),
(115, 'Pitalito', 13),
(116, 'Yaguara', 13),
(117, 'Albania', 14),
(118, 'Barrancas', 14),
(119, 'Maicao', 14),
(120, 'Riohacha', 14),
(121, 'Cienaga', 15),
(122, 'Nueva Granada', 15),
(123, 'Santa Marta', 15),
(124, 'Zapayan', 15),
(125, 'Cordoba', 16),
(126, 'Ipiales', 16),
(127, 'La Florida', 16),
(128, 'Leiva', 16),
(129, 'Mosquera', 16),
(130, 'Pasto', 16),
(131, 'Tumaco', 16),
(132, 'Barrancabermeja', 17),
(133, 'Chinacota', 17),
(134, 'Cucuta', 17),
(135, 'Puerto Santander', 17),
(136, 'Villa del Rosario', 17),
(137, 'Armenia', 18),
(138, 'Buenavista', 18),
(139, 'Calarca', 18),
(140, 'Cordoba', 18),
(141, 'Montenegro', 18),
(142, 'Dos Quebrada', 19),
(143, 'Guatica', 19),
(144, 'Pereira', 19),
(145, 'Pueblorico', 19),
(146, 'Quinchia', 19),
(147, 'Aracota', 20),
(148, 'Barichara', 20),
(149, 'Bolivar', 20),
(150, 'Bucaramanga', 20),
(151, 'Curiti', 20),
(152, 'FloridaBlanca', 20),
(153, 'Buenavista', 21),
(154, 'Majagual', 21),
(155, 'Sincelejo', 21),
(156, 'Sucre', 21),
(157, 'Alpujarra', 22),
(158, 'Alvarado', 22),
(159, 'Armero', 22),
(160, 'Ataco', 22),
(161, 'Cajamarca', 22),
(162, 'Carmen de Apicala', 22),
(163, 'Chaparral', 22),
(164, 'Coello', 22),
(165, 'Coyaima', 22),
(166, 'Cunday', 22),
(167, 'Dolores', 22),
(168, 'Espinal', 22),
(169, 'Flandes', 22),
(170, 'Fresno', 22),
(171, 'Guamo', 22),
(172, 'Honda', 22),
(173, 'Ibagué', 22),
(174, 'Icononzo', 22),
(175, 'Libano', 22),
(176, 'Mariquita', 22),
(177, 'Melgar', 22),
(178, 'Murillo', 22),
(179, 'Natagaima', 22),
(180, 'Ortega', 22),
(181, 'Planadas', 22),
(182, 'Prado', 22),
(183, 'Purificación', 22),
(184, 'RioBlanco', 22),
(185, 'Roncesvalles', 22),
(186, 'Rovira', 22),
(187, 'Saldaña', 22),
(188, 'San Antonio', 22),
(189, 'San Luis', 22),
(190, 'Valle de San Juan', 22),
(191, 'Venadillo', 22),
(192, 'VillaHermosa', 22),
(193, 'VillaRica', 22),
(194, 'Alcala', 23),
(195, 'Buenaventura', 23),
(196, 'Buga', 23),
(197, 'Cali', 23),
(198, 'Florida', 23),
(199, 'Palmira', 23),
(200, 'Restrepo', 23),
(201, 'Tulua', 23),
(202, 'Arauca', 24),
(203, 'Arauquita', 24),
(204, 'Saravena', 24),
(205, 'Tame', 24),
(206, 'Aguazul', 25),
(207, 'Monterrey', 25),
(208, 'Sabanalarga', 25),
(209, 'Tauramena', 25),
(210, 'Villanueva', 25),
(211, 'Yopal', 25),
(212, 'Mocoa', 26),
(213, 'Puerto Asis', 26),
(214, 'Villa Amazonica', 26),
(215, 'VillaGarzon', 26),
(216, 'Providencia', 27),
(217, 'San Andrés', 27),
(218, 'Leticia', 28),
(219, 'Puerto Arica', 28),
(220, 'Puerto Nariño', 28),
(221, 'Puerto Santander', 28),
(222, 'Cacahual', 29),
(223, 'Guaviare', 29),
(224, 'PTO Inrida', 29),
(225, 'Calamar', 30),
(226, 'El Retorno', 30),
(227, 'MiraFlores', 30),
(228, 'San Jose del Guaviare', 30),
(229, 'Mitú', 31),
(230, 'Morichal', 31),
(231, 'Taraira', 31),
(232, 'La Primavera', 32),
(233, 'Puerto Carreño', 32),
(234, 'Puerto Murillo', 32),
(235, 'Puerto Nariño', 32),
(236, 'El Carmen', 33),
(237, 'Granada', 33),
(238, 'La Macarena', 33),
(239, 'Mesetas', 33),
(240, 'Puerto Gaitan', 33),
(241, 'Villavicencio', 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rh`
--

CREATE TABLE `rh` (
  `id_rh` int(2) NOT NULL,
  `rh` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `rh`
--

INSERT INTO `rh` (`id_rh`, `rh`) VALUES
(1, 'A+'),
(2, 'A-'),
(3, 'B+'),
(4, 'B-'),
(5, 'AB+'),
(6, 'AB-'),
(7, 'O+'),
(8, 'O-');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(3) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'Desarrollador'),
(2, 'Administrador'),
(3, 'Medico'),
(4, 'Farmaceuta'),
(5, 'Paciente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_medicamento`
--

CREATE TABLE `tipo_medicamento` (
  `id_cla` int(3) NOT NULL,
  `clasificacion` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tipo_medicamento`
--

INSERT INTO `tipo_medicamento` (`id_cla`, `clasificacion`) VALUES
(1, 'Analgesicos'),
(2, 'Antibioticos'),
(3, 'Anticonvulsivos'),
(4, 'Antidepresivos'),
(5, 'Antidiabeticos'),
(6, 'Antihipertensivos'),
(7, 'Antiinflamatorios'),
(8, 'Antipireticos'),
(9, 'Antitusivos'),
(10, 'Jarabe'),
(11, ' Antihistamínico'),
(12, 'Antiácido'),
(13, 'Hipolipemiante'),
(14, 'Ansiolítico'),
(15, 'Broncodilatador'),
(16, 'Diurético');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trigg`
--

CREATE TABLE `trigg` (
  `n_password` varchar(500) DEFAULT NULL,
  `v_password` varchar(500) DEFAULT NULL,
  `tipo` varchar(20) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `t_documento`
--

CREATE TABLE `t_documento` (
  `id_doc` int(2) NOT NULL,
  `tipo` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `t_documento`
--

INSERT INTO `t_documento` (`id_doc`, `tipo`) VALUES
(1, 'Registro CIvil'),
(2, 'Tarjeta de Identidad'),
(3, 'Cedula de Ciudadania'),
(4, 'Cedula de Extrangeria ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `documento` int(11) NOT NULL,
  `id_doc` int(2) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `id_rh` int(2) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `id_rol` int(3) NOT NULL,
  `id_estado` int(3) NOT NULL,
  `nit` varchar(10) DEFAULT NULL,
  `token` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`documento`, `id_doc`, `nombre`, `apellido`, `id_rh`, `telefono`, `correo`, `id_municipio`, `direccion`, `password`, `id_rol`, `id_estado`, `nit`, `token`) VALUES
(1106226573, 3, 'Santiago ', 'Pinilla Prada', 2, '3144342215', 'santiagomagnos63@gmail.com', 173, 'Cra 2 #75-b31 villa cristales', '$2y$10$wRYenuFuXZLt1QmyrURITeyy8A.uPzFKFSQIc61AiCRbX/LvvCyI2', 1, 3, '9572841630', '');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `update_contra` AFTER UPDATE ON `usuarios` FOR EACH ROW begin insert into trigg(n_password, v_password, tipo) values(new.password, old.password, 'update'); end
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autorizaciones`
--
ALTER TABLE `autorizaciones`
  ADD PRIMARY KEY (`cod_auto`),
  ADD KEY `id_medicamento` (`medicamento`),
  ADD KEY `documento` (`documento`),
  ADD KEY `docu_medico` (`docu_medico`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_medico` (`docu_medico`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_depart`);

--
-- Indices de la tabla `det_autorizacion`
--
ALTER TABLE `det_autorizacion`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_auto` (`id_auto`),
  ADD KEY `id_medicamento` (`id_medicamento`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`nit`);

--
-- Indices de la tabla `especializacion`
--
ALTER TABLE `especializacion`
  ADD PRIMARY KEY (`id_esp`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `histo_clinica`
--
ALTER TABLE `histo_clinica`
  ADD PRIMARY KEY (`id_histo`),
  ADD KEY `docu_medico` (`docu_medico`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_lab`);

--
-- Indices de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD PRIMARY KEY (`id_medicamento`),
  ADD KEY `id_cla` (`id_cla`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_lab` (`id_lab`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`docu_medico`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_esp` (`id_esp`),
  ADD KEY `id_doc` (`id_doc`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Indices de la tabla `rh`
--
ALTER TABLE `rh`
  ADD PRIMARY KEY (`id_rh`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tipo_medicamento`
--
ALTER TABLE `tipo_medicamento`
  ADD PRIMARY KEY (`id_cla`);

--
-- Indices de la tabla `t_documento`
--
ALTER TABLE `t_documento`
  ADD PRIMARY KEY (`id_doc`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`documento`),
  ADD KEY `id_doc` (`id_doc`),
  ADD KEY `id_rh` (`id_rh`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_ciudad` (`id_municipio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_depart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `det_autorizacion`
--
ALTER TABLE `det_autorizacion`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `especializacion`
--
ALTER TABLE `especializacion`
  MODIFY `id_esp` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `histo_clinica`
--
ALTER TABLE `histo_clinica`
  MODIFY `id_histo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_lab` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  MODIFY `id_medicamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=693237;

--
-- AUTO_INCREMENT de la tabla `rh`
--
ALTER TABLE `rh`
  MODIFY `id_rh` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo_medicamento`
--
ALTER TABLE `tipo_medicamento`
  MODIFY `id_cla` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `t_documento`
--
ALTER TABLE `t_documento`
  MODIFY `id_doc` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `medicamentos`
--
ALTER TABLE `medicamentos`
  ADD CONSTRAINT `medicamentos_ibfk_1` FOREIGN KEY (`id_cla`) REFERENCES `tipo_medicamento` (`id_cla`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicamentos_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicamentos_ibfk_3` FOREIGN KEY (`id_lab`) REFERENCES `laboratorio` (`id_lab`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicos_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicos_ibfk_3` FOREIGN KEY (`id_esp`) REFERENCES `especializacion` (`id_esp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `medicos_ibfk_4` FOREIGN KEY (`id_doc`) REFERENCES `t_documento` (`id_doc`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
