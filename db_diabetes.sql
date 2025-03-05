-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 05-03-2025 a las 18:12:26
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_diabetes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_preguntas`
--

CREATE TABLE `pr_preguntas` (
  `id_pregunta` int NOT NULL,
  `id_usuario` int NOT NULL,
  `orden` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pr_preguntas`
--

INSERT INTO `pr_preguntas` (`id_pregunta`, `id_usuario`, `orden`, `titulo`, `contenido`, `estado`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 1, 1, '¿QUÉ TAN FRECUENTE PRESENTA ALTOS NIVELES DE ANSIEDAD, ESTRÉS Y/O PREOCUPACION?', '1', 'Activo', '2025-02-27 12:27:54', 1, NULL, NULL, 0, NULL, NULL),
(2, 1, 2, '¿CONSUMES COMIDAS CON ALTO CONTENIDO EN GRASAS?', '2', 'Activo', '2025-02-27 12:28:32', 1, '2025-02-27 12:28:49', 1, 0, NULL, NULL),
(3, 1, 3, '¿TIENES SED Y/O HAMBRE CONSTANTE?', '3', 'Activo', '2025-02-27 12:29:21', 1, NULL, NULL, 0, NULL, NULL),
(4, 1, 4, '¿LE HAN ENCONTRADO ALGUNA VEZ NIVELES ALTOS DE GLUCOSA EN SANGRE, POR EJEMPLO, EN UN EXAMEN MÉDICO, DURANTE UNA ENFERMEDAD, DURANTE EL EMBARAZO?', '4', 'Activo', '2025-02-27 12:29:52', 1, NULL, NULL, 0, NULL, NULL),
(5, 1, 5, '¿TIENES VISIÓN BORROSA?', '5', 'Activo', '2025-02-27 12:31:34', 1, NULL, NULL, 0, NULL, NULL),
(6, 1, 6, '¿TIENES CICATRIZACIÓN LENTA DE HERIDAS?', '6', 'Activo', '2025-02-27 12:32:19', 1, NULL, NULL, 0, NULL, NULL),
(7, 1, 7, '¿TIENES CANSANCIO O DEBILIDAD CONSTANTE?', '7', 'Activo', '2025-02-27 12:32:57', 1, NULL, NULL, 0, NULL, NULL),
(8, 1, 8, '¿TIENES HORMIGUEO O ENTUMECIMIENTO DE MANOS Y PIES?', '8', 'Activo', '2025-02-27 12:33:34', 1, '2025-02-27 12:33:48', 1, 0, NULL, NULL),
(9, 1, 9, '¿REALIZAS ACTIVIDAD FISÍCA?', '9', 'Activo', '2025-02-27 12:34:25', 1, NULL, NULL, 0, NULL, NULL),
(10, 1, 10, '¿CONSUMES A DIARIO ALGUNAS FRUTAS O VERDURAS?', '10', 'Activo', '2025-02-27 12:35:15', 1, NULL, NULL, 0, NULL, NULL),
(11, 1, 11, '¿TIENES ANTECEDENTES FAMILIARES RELACIONADOS A LA DIABETES?', '11', 'Activo', '2025-02-27 12:39:55', 1, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_respuestas`
--

CREATE TABLE `pr_respuestas` (
  `id_respuesta` int NOT NULL,
  `id_pregunta` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_tipo_respuesta` int NOT NULL,
  `contenido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido_alternativo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadatos` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_respuesta_aceptada` tinyint(1) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pr_respuestas`
--

INSERT INTO `pr_respuestas` (`id_respuesta`, `id_pregunta`, `id_usuario`, `id_tipo_respuesta`, `contenido`, `contenido_alternativo`, `metadatos`, `es_respuesta_aceptada`, `fecha_registro`) VALUES
(1, 1, 1, 7, 'Casi Nunca', 'Nivel bajo', '{\"opciones\":[\"Casi Nunca\",\"A veces\",\"Casi Siempre\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:27:54'),
(2, 1, 1, 7, 'A veces', 'Nivel moderado', '{\"opciones\":[\"Casi Nunca\",\"A veces\",\"Casi Siempre\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:27:54'),
(3, 1, 1, 7, 'Casi Siempre', 'Nivel alto', '{\"opciones\":[\"Casi Nunca\",\"A veces\",\"Casi Siempre\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:27:54'),
(7, 2, 1, 7, 'Nada', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:28:49'),
(8, 2, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:28:49'),
(9, 2, 1, 7, 'Mucho', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:28:49'),
(10, 3, 1, 7, 'Nada', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:29:21'),
(11, 3, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:29:21'),
(12, 3, 1, 7, 'Mucho', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:29:21'),
(13, 4, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"Si\"],\"valores\":[0,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:29:52'),
(14, 4, 1, 7, 'Si', 'Nivel alto', '{\"opciones\":[\"No\",\"Si\"],\"valores\":[0,2],\"seleccionada\":1,\"valor_seleccionado\":2}', 1, '2025-02-27 12:29:52'),
(15, 5, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"S\\u00ed pero ocasionalmente\",\"S\\u00b41 de forma constante\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:31:34'),
(16, 5, 1, 7, 'Sí pero ocasionalmente', 'Nivel moderado', '{\"opciones\":[\"No\",\"S\\u00ed pero ocasionalmente\",\"S\\u00b41 de forma constante\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:31:34'),
(17, 5, 1, 7, 'Sí de forma constante', 'Nivel alto', '{\"opciones\":[\"No\",\"S\\u00ed pero ocasionalmente\",\"S\\u00b41 de forma constante\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:31:34'),
(18, 6, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"S\\u00ed ocasionalmente\",\"S\\u00ed siempre\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:32:19'),
(19, 6, 1, 7, 'Sí ocasionalmente', 'Nivel moderado', '{\"opciones\":[\"No\",\"S\\u00ed ocasionalmente\",\"S\\u00ed siempre\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:32:19'),
(20, 6, 1, 7, 'Sí siempre', 'Nivel alto', '{\"opciones\":[\"No\",\"S\\u00ed ocasionalmente\",\"S\\u00ed siempre\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:32:19'),
(21, 7, 1, 7, 'Nada', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:32:57'),
(22, 7, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:32:57'),
(23, 7, 1, 7, 'Mucho', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:32:57'),
(27, 8, 1, 7, 'Nada', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:33:48'),
(28, 8, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:33:48'),
(29, 8, 1, 7, 'Mucho', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:33:48'),
(30, 9, 1, 7, 'Nada', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:34:25'),
(31, 9, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:34:25'),
(32, 9, 1, 7, 'Mucho', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:34:25'),
(33, 10, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:35:15'),
(34, 10, 1, 7, 'Regularmente', 'Nivel moderado', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:35:15'),
(35, 10, 1, 7, 'Si', 'Nivel alto', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:35:15'),
(36, 11, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:39:55'),
(37, 11, 1, 7, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', 'Nivel moderado', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:39:55'),
(38, 11, 1, 7, 'Si (primer grado de consanguinidad): padres e hijos', 'Nivel alto', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:39:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pr_tipo_respuestas`
--

CREATE TABLE `pr_tipo_respuestas` (
  `id_tipo_respuesta` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadatos_requeridos` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pr_tipo_respuestas`
--

INSERT INTO `pr_tipo_respuestas` (`id_tipo_respuesta`, `nombre`, `descripcion`, `metadatos_requeridos`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 'Opción múltiple', 'Pregunta con varias opciones donde solo una es correcta', '{\"opciones\": [\"array\"], \"correcta\": \"integer\"}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(2, 'Selección múltiple', 'Pregunta con varias opciones donde se pueden elegir varias respuestas', '{\"opciones\": [\"array\"], \"correctas\": [\"array\"]}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(3, 'Verdadero/Falso', 'Pregunta donde la respuesta es verdadera o falsa', '{\"correcta\": \"boolean\"}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(4, 'Respuesta corta', 'Pregunta que requiere una respuesta breve de texto', '{\"longitud_maxima\": \"integer\"}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(5, 'Escala', 'Evaluación en una escala numérica', '{\"min\": \"integer\", \"max\": \"integer\"}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(6, 'Desarrollo', 'Pregunta que requiere una respuesta detallada', '{\"palabras_minimas\": \"integer\"}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL),
(7, 'Escala Likert', 'Escala de frecuencia o intensidad con valores numéricos asociados', '{\"opciones\": [\"array\"], \"valores\": [\"array\"]}', '2025-02-27 12:27:24', 1, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_especialidades`
--

CREATE TABLE `sd_especialidades` (
  `idespecialidad` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_especialidades`
--

INSERT INTO `sd_especialidades` (`idespecialidad`, `nombre`, `descripcion`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 'Cardiología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(2, 'Cirugía General', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(3, 'Emergencia y Desastres', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(4, 'Gastroenterología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(5, 'Urología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(6, 'Ginecología y Obstetricia', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(7, 'Enfermedades Infecciosas y Tropicales', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(8, 'Medicina Externa', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(9, 'Medicina Intensiva', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(10, 'Psiquiatría', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(11, 'Radiología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(12, 'Neumología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(13, 'Oftalmología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(14, 'Otorrinolaringología', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(15, 'Patología Clínica', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL),
(16, 'Pediatría', NULL, '2025-02-24 16:25:10', 1, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_pacientes`
--

CREATE TABLE `sd_pacientes` (
  `idpaciente` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peso` decimal(6,2) NOT NULL,
  `altura` decimal(6,2) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_pacientes`
--

INSERT INTO `sd_pacientes` (`idpaciente`, `nombre`, `dni`, `celular`, `edad`, `sexo`, `peso`, `altura`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 'BUSTAMANTE FERNANDEZ ASHLY NAOMI', '76144151', '0', '46', '0', 75.00, 1.55, '2025-02-25 15:07:18', 0, '2025-03-05 00:05:43', 1, 0, NULL, NULL),
(2, 'BUSTAMANTE FERNANDEZ LEENH ALEXANDER', '76144152', '987654321', '23', 'M', 70.00, 1.70, '2025-03-04 21:08:02', 1, '2025-03-05 00:14:49', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_personal_especialidad`
--

CREATE TABLE `sd_personal_especialidad` (
  `id_per_esp` int NOT NULL,
  `idespecialidad` int NOT NULL,
  `idpersonal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_personal_medico`
--

CREATE TABLE `sd_personal_medico` (
  `idpersonal` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `celular` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_test`
--

CREATE TABLE `sd_test` (
  `idtest` int NOT NULL,
  `idpaciente` int NOT NULL,
  `idusuario` int DEFAULT NULL,
  `peso` decimal(13,3) NOT NULL,
  `altura` decimal(13,3) NOT NULL,
  `imc` decimal(13,3) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `tendencia_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tendencia_modelo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `procesado_modelo` tinyint(1) NOT NULL,
  `respuesta_analisis` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_test`
--

INSERT INTO `sd_test` (`idtest`, `idpaciente`, `idusuario`, `peso`, `altura`, `imc`, `fecha_hora`, `tendencia_label`, `tendencia_modelo`, `procesado_modelo`, `respuesta_analisis`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 2, 1, 70.000, 1.700, 24.200, '2025-03-05 00:14:49', 'Alto', '38.461538461538', 1, '{\"entradas\":[0,0,0,2,1,2,2,0,2,1,2,1,1],\"conteo\":{\"bajo\":4,\"moderado\":4,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":30.76923076923077,\"alto\":38.46153846153847},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-03-05 00:14:49', 1, '2025-03-05 00:14:49', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_test_preguntas`
--

CREATE TABLE `sd_test_preguntas` (
  `id_test_pregunta` int NOT NULL,
  `idtest` int NOT NULL,
  `id_pregunta` int NOT NULL,
  `id_respuesta` int DEFAULT NULL,
  `respuesta_usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_test_preguntas`
--

INSERT INTO `sd_test_preguntas` (`id_test_pregunta`, `idtest`, `id_pregunta`, `id_respuesta`, `respuesta_usuario`, `fecha_registro`) VALUES
(1, 1, 1, 1, 'Casi Nunca', '2025-03-05 00:14:49'),
(2, 1, 2, 9, 'Mucho', '2025-03-05 00:14:49'),
(3, 1, 3, 11, 'Poco', '2025-03-05 00:14:49'),
(4, 1, 4, 14, 'Si', '2025-03-05 00:14:49'),
(5, 1, 5, 17, 'Sí de forma constante', '2025-03-05 00:14:49'),
(6, 1, 6, 18, 'No', '2025-03-05 00:14:49'),
(7, 1, 7, 23, 'Mucho', '2025-03-05 00:14:49'),
(8, 1, 8, 28, 'Poco', '2025-03-05 00:14:49'),
(9, 1, 9, 32, 'Mucho', '2025-03-05 00:14:49'),
(10, 1, 10, 34, 'Regularmente', '2025-03-05 00:14:49'),
(11, 1, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-05 00:14:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_acciones`
--

CREATE TABLE `sis_acciones` (
  `idaccion` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identificador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_acciones`
--

INSERT INTO `sis_acciones` (`idaccion`, `nombre`, `identificador`, `descripcion`, `estado`) VALUES
(1, 'Ver', 'view', '', 1),
(2, 'Editar/Actualizar', 'update', '', 1),
(3, 'Eliminar', 'delete', '', 1),
(4, 'Crear', 'create', '', 1),
(5, 'Developer', 'developer', 'Esta opción permite ver opciones solo para desarrolladores', 1),
(6, 'Imprimir', 'print', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_centinela`
--

CREATE TABLE `sis_centinela` (
  `idcentinela` int NOT NULL,
  `codigo` int NOT NULL,
  `ip` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `agente` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_centinela`
--

INSERT INTO `sis_centinela` (`idcentinela`, `codigo`, `ip`, `agente`, `method`, `url`, `fecha_registro`) VALUES
(7380, 6576, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:20:03'),
(7381, 3320, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:20:03'),
(7382, 3638, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:20:20'),
(7383, 6520, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:20:20'),
(7384, 9696, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:22:22'),
(7385, 8312, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:22:24'),
(7386, 6991, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:23:26'),
(7387, 3596, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:23:27'),
(7388, 7939, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:24:48'),
(7389, 9165, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:24:48'),
(7390, 7066, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:24:55'),
(7391, 5635, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:24:55'),
(7392, 9629, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:44:27'),
(7393, 6911, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:44:28'),
(7394, 9812, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:44:28'),
(7395, 8719, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:45:04'),
(7396, 2108, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:45:05'),
(7397, 7058, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:45:05'),
(7398, 6870, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:46:49'),
(7399, 3762, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:46:50'),
(7400, 9039, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:46:50'),
(7401, 6614, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:48:50'),
(7402, 9018, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:48:51'),
(7403, 7231, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:48:51'),
(7404, 4709, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:49:02'),
(7405, 6275, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:49:02'),
(7406, 9361, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:49:03'),
(7407, 6510, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:49:15'),
(7408, 7652, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:49:15'),
(7409, 3381, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:49:15'),
(7410, 3013, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:49:46'),
(7411, 1022, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:49:46'),
(7412, 1999, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:49:46'),
(7413, 8069, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-04 23:49:51'),
(7414, 6844, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:52:28'),
(7415, 8136, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:52:29'),
(7416, 3460, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:52:29'),
(7417, 9183, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:53:46'),
(7418, 2655, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:53:47'),
(7419, 8045, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:53:47'),
(7420, 3167, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:56:00'),
(7421, 7453, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:56:01'),
(7422, 8409, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:56:02'),
(7423, 3455, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:57:26'),
(7424, 3286, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:57:26'),
(7425, 1125, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:57:26'),
(7426, 3510, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-04 23:59:14'),
(7427, 5226, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-04 23:59:14'),
(7428, 6000, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-04 23:59:14'),
(7429, 6922, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-04 23:59:32'),
(7430, 8933, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/print/1', '2025-03-05 00:00:11'),
(7431, 7184, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:00:37'),
(7432, 4192, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:00:38'),
(7433, 5238, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:00:38'),
(7434, 7072, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:01:00'),
(7435, 4988, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:01:01'),
(7436, 1356, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:01:01'),
(7437, 5334, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:03:10'),
(7438, 5429, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:03:11'),
(7439, 6101, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:03:11'),
(7440, 7695, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:03:30'),
(7441, 4426, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:03:30'),
(7442, 5931, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:03:30'),
(7443, 8058, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:04:01'),
(7444, 3560, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:04:01'),
(7445, 1373, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:04:01'),
(7446, 1828, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:04:04'),
(7447, 2168, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:04:05'),
(7448, 4783, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:04:05'),
(7449, 2717, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:04:47'),
(7450, 1514, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:04:48'),
(7451, 5927, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:04:48'),
(7452, 6223, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 00:05:22'),
(7453, 8224, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:05:22'),
(7454, 8746, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/buscar-pacientes?q=nao', '2025-03-05 00:05:25'),
(7455, 8365, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/obtener-preguntas', '2025-03-05 00:05:31'),
(7456, 1047, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:05:43'),
(7457, 4755, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:05:50'),
(7458, 6498, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:05:50'),
(7459, 4360, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:05:51'),
(7460, 2879, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 00:06:24'),
(7461, 1047, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:07:01'),
(7462, 2047, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:07:02'),
(7463, 9526, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:07:02'),
(7464, 1200, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 00:07:04'),
(7465, 2099, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:07:12'),
(7466, 3549, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:07:13'),
(7467, 9129, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:07:13'),
(7468, 1322, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:07:20'),
(7469, 3661, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:07:21'),
(7470, 4409, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:07:21'),
(7471, 1419, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 00:07:26'),
(7472, 9020, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/2', '2025-03-05 00:07:29'),
(7473, 2335, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 00:07:33'),
(7474, 4110, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:07:45'),
(7475, 8445, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:07:46'),
(7476, 4273, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:07:46'),
(7477, 2882, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:08:11'),
(7478, 8667, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:08:11'),
(7479, 4653, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:08:11'),
(7480, 4852, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:08:27'),
(7481, 6202, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:08:27'),
(7482, 3255, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:08:27'),
(7483, 6523, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:11:30'),
(7484, 8358, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:11:30'),
(7485, 1309, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:11:30'),
(7486, 1406, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 00:11:32'),
(7487, 6870, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:11:32'),
(7488, 9196, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/buscar-pacientes?q=leenh', '2025-03-05 00:11:36'),
(7489, 7341, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/obtener-preguntas', '2025-03-05 00:11:40'),
(7490, 5474, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:11:52'),
(7491, 8208, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:12:32'),
(7492, 1215, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:13:08'),
(7493, 4620, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:13:26'),
(7494, 5001, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:13:42'),
(7495, 9082, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:13:43'),
(7496, 2187, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:13:43'),
(7497, 3613, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:13:59'),
(7498, 4863, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:13:59'),
(7499, 6657, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:13:59'),
(7500, 4315, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 00:14:02'),
(7501, 5926, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:14:02'),
(7502, 7784, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:14:12'),
(7503, 8842, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:14:12'),
(7504, 7904, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:14:13'),
(7505, 5243, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 00:14:26'),
(7506, 3339, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:14:26'),
(7507, 9180, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/buscar-pacientes?q=leenh', '2025-03-05 00:14:35'),
(7508, 2098, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos/obtener-preguntas', '2025-03-05 00:14:38'),
(7509, 6807, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/diagnosticos/guardar-respuestas', '2025-03-05 00:14:49'),
(7510, 7813, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:14:54'),
(7511, 7662, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:14:54'),
(7512, 1628, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:14:54'),
(7513, 3540, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:21:23'),
(7514, 7369, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:21:24'),
(7515, 3479, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:21:24'),
(7516, 6063, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:21:57'),
(7517, 6936, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:21:57'),
(7518, 2860, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:21:57'),
(7519, 6894, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:22:22'),
(7520, 6299, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:22:23'),
(7521, 7619, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:22:23'),
(7522, 3428, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:22:31'),
(7523, 6426, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:22:31'),
(7524, 8621, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:22:31'),
(7525, 2616, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:22:49'),
(7526, 4871, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:22:50'),
(7527, 2970, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:22:50'),
(7528, 3654, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 00:24:00'),
(7529, 6661, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 00:24:01'),
(7530, 5512, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 00:24:01'),
(7531, 2163, ' IP: 127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/login', '2025-03-05 09:26:14'),
(7532, 3226, ' IP: 127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/img/loading.svg', '2025-03-05 09:26:14'),
(7533, 9272, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/login', '2025-03-05 09:26:50'),
(7534, 5726, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/login', '2025-03-05 09:26:51'),
(7535, 3223, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin', '2025-03-05 09:26:51'),
(7536, 6978, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:26:57'),
(7537, 5031, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:26:57'),
(7538, 9437, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:36:35'),
(7539, 8708, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:36:36'),
(7540, 3206, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:43:06'),
(7541, 2459, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:43:07'),
(7542, 6661, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/menus', '2025-03-05 09:43:41'),
(7543, 8209, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/menus', '2025-03-05 09:43:42'),
(7544, 8654, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/submenus', '2025-03-05 09:43:57'),
(7545, 1091, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/submenus', '2025-03-05 09:43:57'),
(7546, 3085, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/submenus/menus', '2025-03-05 09:44:40'),
(7547, 4242, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/submenus/search', '2025-03-05 09:44:40'),
(7548, 2223, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/submenus', '2025-03-05 09:45:52'),
(7549, 7180, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/submenus', '2025-03-05 09:45:53'),
(7550, 7520, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos', '2025-03-05 09:46:08'),
(7551, 5771, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos', '2025-03-05 09:46:08'),
(7552, 8925, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 09:47:58'),
(7553, 5750, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 09:47:58'),
(7554, 2036, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 09:47:58'),
(7555, 7258, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:47:58'),
(7556, 1406, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 09:47:58'),
(7557, 5865, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 09:47:59'),
(7558, 4259, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:47:59'),
(7559, 8288, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 09:47:59'),
(7560, 9390, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 09:51:40'),
(7561, 9134, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 09:51:40'),
(7562, 7255, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 09:51:40'),
(7563, 9759, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:51:41'),
(7564, 2893, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 09:51:41'),
(7565, 3977, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 09:51:41'),
(7566, 2442, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:51:41'),
(7567, 8883, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 09:51:41'),
(7568, 5017, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:52:19'),
(7569, 2144, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:52:20'),
(7570, 4636, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 09:53:32'),
(7571, 5449, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 09:53:33'),
(7572, 3816, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 09:53:33'),
(7573, 8021, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:53:33'),
(7574, 8501, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 09:53:33'),
(7575, 8428, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 09:53:34'),
(7576, 5421, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 09:53:34'),
(7577, 5273, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 09:53:34'),
(7578, 1122, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos', '2025-03-05 09:53:34'),
(7579, 4482, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos', '2025-03-05 09:53:35'),
(7580, 2231, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/submenus', '2025-03-05 09:53:35'),
(7581, 6539, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/submenus', '2025-03-05 09:53:35'),
(7582, 8145, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/menus', '2025-03-05 09:53:36'),
(7583, 4212, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/menus', '2025-03-05 09:53:36'),
(7584, 5974, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/horario-medico', '2025-03-05 09:53:44'),
(7585, 6774, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/menus', '2025-03-05 09:53:45'),
(7586, 5540, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/menus', '2025-03-05 09:53:46'),
(7587, 5662, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:54:39'),
(7588, 6865, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:54:39'),
(7589, 9923, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 09:54:40'),
(7590, 1673, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 09:54:44'),
(7591, 7316, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 09:54:44'),
(7592, 6852, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 10:07:57'),
(7593, 4211, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/print/1', '2025-03-05 10:08:00'),
(7594, 9658, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/print/1', '2025-03-05 10:08:09'),
(7595, 5938, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/login', '2025-03-05 12:25:49'),
(7596, 9743, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/img/loading.svg', '2025-03-05 12:25:49'),
(7597, 1211, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/login', '2025-03-05 12:29:07'),
(7598, 6130, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/img/loading.svg', '2025-03-05 12:29:07'),
(7599, 2535, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/login', '2025-03-05 12:38:04'),
(7600, 2826, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/login', '2025-03-05 12:38:06'),
(7601, 2101, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin', '2025-03-05 12:38:06'),
(7602, 2490, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos', '2025-03-05 12:38:16'),
(7603, 8798, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos', '2025-03-05 12:38:16'),
(7604, 7098, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos/active', '2025-03-05 12:38:26'),
(7605, 7149, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos', '2025-03-05 12:38:28'),
(7606, 2751, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos', '2025-03-05 12:38:28'),
(7607, 5280, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:39:30'),
(7608, 2909, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:39:30'),
(7609, 8275, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 12:40:16'),
(7610, 8528, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 12:40:16'),
(7611, 5574, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 12:40:16'),
(7612, 8732, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:40:16'),
(7613, 1483, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 12:40:16'),
(7614, 6630, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 12:40:16'),
(7615, 8221, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:40:16'),
(7616, 6079, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 12:40:16'),
(7617, 9047, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/saverecurso', '2025-03-05 12:40:45'),
(7618, 9560, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:40:45'),
(7619, 1647, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 12:40:48'),
(7620, 8585, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 12:40:48'),
(7621, 3564, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 12:40:48'),
(7622, 6848, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 12:40:48'),
(7623, 8082, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:40:48'),
(7624, 3117, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 12:40:48'),
(7625, 8258, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:40:48'),
(7626, 2287, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 12:40:48'),
(7627, 2727, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/savepermiso', '2025-03-05 12:41:00'),
(7628, 1123, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getpermisosporrol/1', '2025-03-05 12:41:00'),
(7629, 4870, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/savepermiso', '2025-03-05 12:41:03'),
(7630, 5736, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getpermisosporrol/1', '2025-03-05 12:41:03'),
(7631, 8641, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales', '2025-03-05 12:41:13'),
(7632, 9140, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2-bootstrap4.min.css', '2025-03-05 12:41:13'),
(7633, 7528, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/css/select2.min.css', '2025-03-05 12:41:13');
INSERT INTO `sis_centinela` (`idcentinela`, `codigo`, `ip`, `agente`, `method`, `url`, `fecha_registro`) VALUES
(7634, 9470, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:41:13'),
(7635, 8985, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getroles', '2025-03-05 12:41:13'),
(7636, 8250, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/permisos-especiales/getacciones', '2025-03-05 12:41:13'),
(7637, 8592, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getrecursos', '2025-03-05 12:41:13'),
(7638, 2003, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/permisos-especiales/getacciones', '2025-03-05 12:41:13'),
(7639, 3207, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:41:17'),
(7640, 4815, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:41:17'),
(7641, 3130, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:41:34'),
(7642, 9541, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:41:35'),
(7643, 7295, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 12:41:44'),
(7644, 4255, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:45:05'),
(7645, 9131, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 12:45:06'),
(7646, 9081, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:45:06'),
(7647, 7026, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/export/pdf?', '2025-03-05 12:45:12'),
(7648, 5970, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/export/excel?', '2025-03-05 12:45:30'),
(7649, 2658, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:57:52'),
(7650, 8911, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:57:53'),
(7651, 7335, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 12:58:41'),
(7652, 7429, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 12:58:42'),
(7653, 3388, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:01:04'),
(7654, 8236, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:01:05'),
(7655, 3557, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:01:10'),
(7656, 1040, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:01:11'),
(7657, 5413, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:01:12'),
(7658, 3640, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:01:12'),
(7659, 1642, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:01:42'),
(7660, 3435, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:01:42'),
(7661, 8892, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:01:43'),
(7662, 7713, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:01:52'),
(7663, 9782, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:01:53'),
(7664, 6075, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:01:53'),
(7665, 8685, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:01:55'),
(7666, 1916, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:01:56'),
(7667, 6640, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:01:56'),
(7668, 1629, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:02:45'),
(7669, 9733, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:02:47'),
(7670, 8812, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:02:47'),
(7671, 5227, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:03:09'),
(7672, 7174, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:03:09'),
(7673, 3310, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:03:09'),
(7674, 1558, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:03:26'),
(7675, 6134, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:03:27'),
(7676, 3173, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:03:27'),
(7677, 5036, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/export/pdf?', '2025-03-05 13:03:37'),
(7678, 4628, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/export/excel?', '2025-03-05 13:04:04'),
(7679, 7350, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:04:50'),
(7680, 4502, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:05:38'),
(7681, 3805, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:05:38'),
(7682, 9014, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:05:38'),
(7683, 4385, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:05:40'),
(7684, 2065, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:05:42'),
(7685, 2361, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:05:45'),
(7686, 8652, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/diagnosticos', '2025-03-05 13:06:04'),
(7687, 6506, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:06:04'),
(7688, 1972, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:07:23'),
(7689, 1604, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:07:24'),
(7690, 1753, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:07:25'),
(7691, 7511, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:07:27'),
(7692, 5001, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:09:02'),
(7693, 1036, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:09:02'),
(7694, 1098, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:09:03'),
(7695, 9780, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:09:04'),
(7696, 6389, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test', '2025-03-05 13:09:28'),
(7697, 2934, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/js/popper.min.js.map', '2025-03-05 13:09:29'),
(7698, 4924, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'POST', '/admin/lista-test', '2025-03-05 13:09:29'),
(7699, 1463, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/admin/lista-test/get-test-details/1', '2025-03-05 13:09:30'),
(7700, 9842, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/', '2025-03-05 13:10:17'),
(7701, 7552, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/iniciar-sesion', '2025-03-05 13:10:22'),
(7702, 4090, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/registrarse', '2025-03-05 13:10:27'),
(7703, 1927, ' IP: ::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'GET', '/doc/dni/76144152', '2025-03-05 13:10:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_menus`
--

CREATE TABLE `sis_menus` (
  `idmenu` int NOT NULL,
  `men_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `men_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `men_controlador` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `men_icono` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `men_url_si` tinyint(1) NOT NULL DEFAULT '0',
  `men_orden` int NOT NULL,
  `men_visible` tinyint(1) NOT NULL,
  `men_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_menus`
--

INSERT INTO `sis_menus` (`idmenu`, `men_nombre`, `men_url`, `men_controlador`, `men_icono`, `men_url_si`, `men_orden`, `men_visible`, `men_fecha`) VALUES
(1, 'Maestras', '#', NULL, 'bx bx-lock-open-alt', 0, 100, 1, '2023-03-06 12:39:09'),
(4, 'Modulo Paciente', '#', NULL, 'bx bxs-user-badge bx-sm', 0, 3, 1, '2025-02-17 12:58:09'),
(5, 'Modulo Personal Médico', '#', NULL, 'bx bx-user-plus bx-sm', 0, 5, 1, '2025-02-18 16:03:05'),
(6, 'Modulo Usuario', '#', NULL, 'bx bxs-user-circle bx-sm', 0, 6, 1, '2025-02-18 18:02:04'),
(7, 'Modulo Predicción', '#', NULL, 'bx bx-edit-alt bx-sm', 0, 1, 1, '2025-02-20 10:38:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_permisos`
--

CREATE TABLE `sis_permisos` (
  `idpermisos` int NOT NULL,
  `idrol` int NOT NULL,
  `idsubmenu` int NOT NULL,
  `perm_r` int DEFAULT NULL,
  `perm_w` int DEFAULT NULL,
  `perm_u` int DEFAULT NULL,
  `perm_d` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_permisos`
--

INSERT INTO `sis_permisos` (`idpermisos`, `idrol`, `idsubmenu`, `perm_r`, `perm_w`, `perm_u`, `perm_d`) VALUES
(3, 1, 2, 1, 1, 1, 1),
(4, 1, 3, 1, 1, 1, 1),
(5, 1, 1, 1, 1, 1, 1),
(9, 1, 6, 1, 0, 0, 0),
(10, 1, 7, 1, 0, 0, 0),
(11, 1, 8, 1, 0, 0, 0),
(12, 1, 9, 1, 0, 0, 0),
(13, 1, 10, 1, 0, 0, 0),
(14, 1, 11, 1, 0, 0, 0),
(15, 1, 12, 1, 0, 0, 0),
(16, 1, 13, 1, 0, 0, 0),
(17, 1, 14, 0, 0, 0, 0),
(18, 1, 15, 1, 0, 0, 0),
(19, 1, 16, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_permisos_extras`
--

CREATE TABLE `sis_permisos_extras` (
  `idpermiso` int NOT NULL,
  `idrol` int NOT NULL DEFAULT '0',
  `idrecurso` int NOT NULL,
  `idaccion` int NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_permisos_extras`
--

INSERT INTO `sis_permisos_extras` (`idpermiso`, `idrol`, `idrecurso`, `idaccion`, `estado`, `fecha_registro`) VALUES
(1, 1, 1, 1, 1, '2025-02-17 12:59:42'),
(2, 1, 1, 2, 1, '2025-02-17 12:59:45'),
(3, 1, 1, 3, 1, '2025-02-17 12:59:48'),
(4, 1, 1, 4, 1, '2025-02-17 12:59:50'),
(5, 1, 2, 1, 1, '2025-02-17 13:09:12'),
(6, 1, 1, 5, 1, '2025-02-18 00:52:28'),
(7, 1, 1, 6, 1, '2025-02-18 01:16:09'),
(8, 1, 3, 1, 1, '2025-02-18 16:09:33'),
(9, 1, 3, 2, 1, '2025-02-18 16:09:38'),
(10, 1, 3, 3, 1, '2025-02-18 16:09:42'),
(11, 1, 3, 4, 1, '2025-02-18 16:09:46'),
(12, 1, 3, 5, 0, '2025-02-18 16:33:56'),
(13, 1, 3, 6, 1, '2025-02-18 17:51:47'),
(14, 1, 4, 1, 1, '2025-02-18 18:03:08'),
(15, 1, 4, 4, 1, '2025-02-18 18:03:11'),
(16, 1, 4, 2, 1, '2025-02-18 18:03:13'),
(17, 1, 4, 3, 1, '2025-02-18 18:03:15'),
(18, 1, 6, 1, 1, '2025-02-19 14:20:53'),
(19, 1, 6, 4, 1, '2025-02-19 14:20:56'),
(20, 1, 6, 2, 1, '2025-02-19 14:20:59'),
(21, 1, 6, 3, 1, '2025-02-19 14:21:01'),
(22, 1, 6, 5, 0, '2025-02-19 14:21:03'),
(23, 1, 6, 6, 1, '2025-02-19 14:21:06'),
(24, 1, 7, 1, 1, '2025-02-19 14:23:23'),
(25, 1, 7, 2, 1, '2025-02-19 14:23:26'),
(26, 1, 7, 3, 1, '2025-02-19 14:23:28'),
(27, 1, 7, 4, 1, '2025-02-19 14:23:30'),
(28, 1, 7, 5, 0, '2025-02-19 14:23:32'),
(29, 1, 5, 1, 1, '2025-02-19 16:24:01'),
(30, 1, 5, 2, 1, '2025-02-19 16:24:03'),
(31, 1, 5, 3, 1, '2025-02-19 16:24:05'),
(32, 1, 5, 4, 1, '2025-02-19 16:24:07'),
(33, 1, 5, 5, 1, '2025-02-19 16:25:20'),
(34, 1, 8, 1, 1, '2025-02-20 10:41:16'),
(35, 1, 8, 2, 1, '2025-02-20 10:41:18'),
(36, 1, 8, 3, 1, '2025-02-20 10:41:20'),
(37, 1, 8, 4, 1, '2025-02-20 10:41:22'),
(38, 1, 9, 1, 1, '2025-02-24 16:29:31'),
(39, 1, 9, 2, 1, '2025-02-24 16:29:33'),
(40, 1, 9, 3, 1, '2025-02-24 16:29:35'),
(41, 1, 9, 4, 1, '2025-02-24 16:29:37'),
(42, 1, 9, 5, 1, '2025-02-24 16:29:39'),
(43, 1, 10, 1, 1, '2025-02-25 17:27:10'),
(44, 1, 10, 2, 1, '2025-02-25 17:27:12'),
(45, 1, 10, 3, 1, '2025-02-25 17:27:14'),
(46, 1, 10, 4, 1, '2025-02-25 17:27:16'),
(47, 1, 10, 5, 0, '2025-02-25 17:27:18'),
(48, 1, 10, 6, 0, '2025-02-25 17:27:20'),
(49, 1, 11, 1, 1, '2025-02-27 12:59:07'),
(50, 1, 11, 2, 1, '2025-02-27 12:59:09'),
(51, 1, 11, 3, 1, '2025-02-27 12:59:11'),
(52, 1, 11, 4, 1, '2025-02-27 12:59:13'),
(53, 1, 11, 5, 0, '2025-02-27 12:59:15'),
(54, 1, 11, 6, 0, '2025-02-27 12:59:18'),
(55, 1, 12, 1, 1, '2025-03-04 12:42:43'),
(56, 1, 12, 2, 1, '2025-03-04 12:42:45'),
(57, 1, 12, 3, 1, '2025-03-04 12:42:46'),
(58, 1, 12, 4, 1, '2025-03-04 12:42:49'),
(59, 1, 12, 5, 0, '2025-03-04 12:42:52'),
(60, 1, 13, 1, 1, '2025-03-05 12:41:00'),
(61, 1, 13, 6, 1, '2025-03-05 12:41:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_personal`
--

CREATE TABLE `sis_personal` (
  `idpersona` int NOT NULL,
  `per_dni` int NOT NULL,
  `per_nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_celular` int DEFAULT NULL,
  `per_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_direcc` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_foto` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `per_estado` tinyint(1) NOT NULL DEFAULT '1',
  `per_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_personal`
--

INSERT INTO `sis_personal` (`idpersona`, `per_dni`, `per_nombre`, `per_celular`, `per_email`, `per_direcc`, `per_foto`, `per_estado`, `per_fecha`) VALUES
(1, 76144152, 'desarrollador', 987654321, 'hackingleenh@gmail.com', '', NULL, 1, '2022-07-22 01:09:20'),
(2, 76144151, 'BUSTAMANTE FERNANDEZ ASHLY NAOMI', NULL, 'hackingleenh@gmail.com', NULL, NULL, 1, '2025-02-25 15:07:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_recursos`
--

CREATE TABLE `sis_recursos` (
  `idrecurso` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `identificador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_recursos`
--

INSERT INTO `sis_recursos` (`idrecurso`, `nombre`, `descripcion`, `tipo`, `identificador`, `estado`, `fecha_registro`) VALUES
(1, 'Pacientes', NULL, 'ruta', 'ruta.paciente', 1, '2025-02-17 12:59:35'),
(2, 'Consultar Dni', NULL, 'accion', 'doc.dni', 1, '2025-02-17 13:09:00'),
(3, 'Medicos', NULL, 'ruta', 'ruta.medicos', 1, '2025-02-18 16:09:14'),
(4, 'Usuarios del sistema', NULL, 'ruta', 'ruta.usuarios', 1, '2025-02-18 18:02:58'),
(5, 'Especialidades medicas', NULL, 'ruta', 'ruta.especialidades', 1, '2025-02-19 00:57:28'),
(6, 'Personal para acceder al sistema', NULL, 'ruta', 'ruta.personal', 1, '2025-02-19 14:20:44'),
(7, 'Roles de usuario', NULL, 'ruta', 'ruta.roles', 1, '2025-02-19 14:23:15'),
(8, 'Diagnosticos', NULL, 'ruta', 'ruta.diagnosticos', 1, '2025-02-20 10:41:05'),
(9, 'Horario laboral médico', NULL, 'ruta', 'ruta.horario-medico', 1, '2025-02-24 16:29:09'),
(10, 'Preguntas del test', NULL, 'ruta', 'ruta.preguntas', 1, '2025-02-25 17:26:57'),
(11, 'Realizar Test', NULL, 'ruta', 'ruta.test', 1, '2025-02-27 12:58:54'),
(12, 'Configuración de la IA', NULL, 'ruta', 'ruta.ia', 1, '2025-03-04 12:42:32'),
(13, 'Lista de Test', NULL, 'ruta', 'ruta.lista', 1, '2025-03-05 12:40:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_rol`
--

CREATE TABLE `sis_rol` (
  `idrol` int NOT NULL,
  `rol_cod` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol_descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rol_estado` tinyint(1) NOT NULL,
  `rol_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_rol`
--

INSERT INTO `sis_rol` (`idrol`, `rol_cod`, `rol_nombre`, `rol_descripcion`, `rol_estado`, `rol_fecha`) VALUES
(1, '/', 'developer', NULL, 1, '2022-07-22 01:09:56'),
(2, 'developer', 'Desarrollador del sistema', 'descripción', 0, '2025-02-19 14:35:08'),
(3, 'doc', 'Personal Médico', '', 1, '2025-02-24 16:02:31'),
(4, 'web', 'Usuario Web', '', 1, '2025-02-25 13:46:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_sesiones`
--

CREATE TABLE `sis_sesiones` (
  `idsesion` int NOT NULL,
  `idusuario` int NOT NULL,
  `session_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tiempo_expiracion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_sesiones`
--

INSERT INTO `sis_sesiones` (`idsesion`, `idusuario`, `session_token`, `ip`, `fecha_registro`, `tiempo_expiracion`, `activo`) VALUES
(1, 1, '65bcd025d27725cc26d57a11f7e2a86471635feb6cf355e3a8c9d589103996080061c6fa47a0ed35', '::1', '2025-02-17 12:03:23', '1739815409', 0),
(2, 1, '9ce715d1d28504afc869b856ad799a2d2059339454058a99dc95f0d996317b91ec9507fd1dd20a22', '::1', '2025-02-17 12:03:33', '1739819964', 0),
(3, 1, 'c2fb1851c506854848b63a155cc532880ab2bd7f682431c407d0c8ce22702919c4275c6ab1d137fa', '::1', '2025-02-17 15:15:59', '1739832785', 0),
(4, 1, 'f54236b248b5149c9632c4e8415b74cf18b14d9e1ed5db8614da82050281e049266898d2db0b7729', '::1', '2025-02-17 23:56:16', '1739863028', 0),
(5, 1, '98c12595cbf00f4a69fc5033e1bfbe43f2669592e10c718d83abece0771ec926ef99a794ffb3b4e2', '::1', '2025-02-18 15:56:34', '1739918067', 0),
(6, 1, '8f567c92f864fcf44b2a587436e61642830bf3106a575e8099532ce16d7a48443aceaa36552240d4', '::1', '2025-02-18 17:40:00', '1739923395', 0),
(7, 1, 'e0422379cc3feba57a83349961e8d0b996a21bb00a23eed76b0793556e1ddb83aab91dac416c34cc', '::1', '2025-02-19 00:56:17', '1739950211', 0),
(8, 1, 'd60dca70cda43fa6818a77f92203765eeb085b2d715480b4ffd40ef886a11d9d0cdafae68ce8f1b6', '::1', '2025-02-19 14:12:26', '1739997722', 0),
(9, 1, '23204e02f8f6ae54c3503fdbefb6e9854ea733ca540abfe15cd6565cfd3eb01cec5681d0354c7032', '::1', '2025-02-19 14:42:34', '1740005618', 0),
(10, 1, '36efa4094b58a299698afba072951b1784fa3c30c5a9fdbf596d8c121e4f40ad99d9e69d46335069', '::1', '2025-02-20 00:21:17', '1740033847', 0),
(11, 1, '7fcd1244782cbcdb9a19d8c9459cc328711246b54c7d7055efaf389aae865b02309dcd34f2d5b0fd', '::1', '2025-02-20 10:31:03', '1740069682', 0),
(12, 1, 'e282a15d4876252257d5fb36c33f7bf4ef0c3b9806f985bf7e49099d2ea6f589fc4ab55e6af24bfd', '::1', '2025-02-20 11:57:39', '1740074563', 0),
(13, 1, 'bc0619926eab043cafec1e6fc978561f637e2150965669807cd8ff8d036cd5c21416d663bd7358f6', '::1', '2025-02-21 11:45:52', '1740161931', 0),
(14, 1, 'd95e70e774e19597ec877fe096b41ef76b0807b60fd905d7e6f5d053934e79745cca9abc2daf040b', '::1', '2025-02-23 20:44:03', '1740366316', 0),
(15, 1, 'bbfcbcf644cf387de6d8252feef03dc411a878f5419708493865f0123d07e0430beaad0f17724525', '::1', '2025-02-23 22:35:20', '1740374798', 0),
(16, 1, '3e1cc2f8a5c7a98e74d3e151ffff70d2aa0e923739d72af1059fef1925d50323471264d0da96e9ba', '::1', '2025-02-24 14:17:52', '1740434356', 0),
(17, 1, 'b9878ce6edce7ea1000f8588caf2085ef5486f5fda7bcceb1979525759a8761f83674eea23ee8c5f', '::1', '2025-02-24 16:00:57', '1740436190', 0),
(18, 1, '5ddc3373b2c3f980797c893f66563ab62ddffda8f250730df4f43d9883f84c3f0d62b016fded0891', '::1', '2025-02-24 21:56:20', '1740455811', 0),
(19, 1, '091939a22406ddb0277bbe768328dfbffcf3c8ed68d125ac4ceac210036c0d9da518516735531752', '::1', '2025-02-25 13:46:01', '1740512786', 0),
(20, 1, 'dffe15cfc10b85d6aaa17c047f270b422e4d4ce27cbbea343f913882247b6999f6db25dac6c4f8bd', '::1', '2025-02-25 15:27:29', '1740519234', 0),
(21, 1, '8db32c20c34d7b1020a0237fa357736abeaa6b5c7eaf911784d7eba381a6e4f6539c4255c6a2a396', '::1', '2025-02-25 17:18:49', '1740531645', 0),
(22, 1, '2b56a149310b7159e872ef08df663c9c8d8756a0f63dcbd02de1d6d73522d19fa3aed00d489e0673', '::1', '2025-02-25 23:47:49', '1740555790', 0),
(23, 1, '6f22c6b94871c27523da1235e07f01d985dd72adcdc8ed2bad3e83b24701f9d1bd46e0def3647143', '::1', '2025-02-26 08:37:44', '1740583335', 0),
(24, 1, '962f4a87a93a490ae179d8b291cbeb16fdc640331332077af0dcb4dd79169b27b9ef9b73999b2b00', '::1', '2025-02-27 12:23:05', '1740682787', 0),
(25, 1, '0f79613d866dc071ff18b5eaa4c561dd7925aefec0765c4b3ae5b5ccd510ef09810c0e52020b8055', '::1', '2025-02-27 14:04:29', '1740691642', 0),
(26, 1, '920842ec836f5819cc9f8c392b5a410a3f42ef4aef7616403daca56b0388b4f14c28463a131099e3', '::1', '2025-03-03 13:58:33', '1741039416', 0),
(27, 1, 'a6ee11743f68172438a62c679deb95a19bca1250bd41ceaf33526a32c0503eb96e42cf6b5e16e994', '::1', '2025-03-03 19:04:31', '1741051543', 1),
(28, 1, '2976702195710ae73226d6665f89f40d1559c71b0258996efc0ea0520b9319d8cfef88470dae6e4e', '::1', '2025-03-03 23:03:19', '1741068533', 0),
(29, 1, 'c8a2704a0956e0ce8328c0bf1292363e13207ea9d95857cca6abdd4e83a94024552cde13568e05da', '::1', '2025-03-04 01:17:21', '1741073961', 1),
(30, 1, '295162374f46e214c477f40a908bea51fb71304a9779b359e5d1d3b43d92bfcc9d0d217ccd47e7b2', '::1', '2025-03-04 12:27:57', '1741117249', 0),
(31, 1, '653a51bdff01ca723660764bc20b9a750b82e74ea4a66770bdac362c436fef1b461be633667e6919', '::1', '2025-03-04 15:57:28', '1741131084', 0),
(32, 1, 'e2ad7dcf34dcdf86a7cdc0ee07dc829f88e5712db672407f9877803db5eed2bfc2a01494019e9f41', '::1', '2025-03-04 18:35:27', '1741139440', 0),
(33, 1, '558c33e51baf3399d5aabe028dd3502d426d8f477a852b6eb66590f87aeba8e9e6ce758b068b2b26', '::1', '2025-03-04 20:51:14', '1741155841', 1),
(34, 1, '15867efbf12b64dae13eeec0ea06088daefc66c323c17d11822adc4f9c724dcfd5f3a0abfb25a05d', '::1', '2025-03-05 09:26:50', '1741190889', 0),
(35, 1, 'c537cc3ec9bb6f46a242f50f0937c066e85664a52c64deef1ca20df19ca2c2511c03a862db51cd10', '::1', '2025-03-05 12:38:05', '1741201770', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_submenus`
--

CREATE TABLE `sis_submenus` (
  `idsubmenu` int NOT NULL,
  `idmenu` int NOT NULL,
  `sub_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_externo` tinyint(1) NOT NULL DEFAULT '0',
  `sub_controlador` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_metodo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'index',
  `sub_icono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_orden` int NOT NULL DEFAULT '1',
  `sub_visible` tinyint(1) NOT NULL DEFAULT '1',
  `sub_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_submenus`
--

INSERT INTO `sis_submenus` (`idsubmenu`, `idmenu`, `sub_nombre`, `sub_url`, `sub_externo`, `sub_controlador`, `sub_metodo`, `sub_icono`, `sub_orden`, `sub_visible`, `sub_fecha`) VALUES
(1, 1, 'Menús', '/admin/menus', 0, 'MenusController', 'index', 'bx-menu', 1, 1, '2023-03-06 12:41:05'),
(2, 1, 'Submenús', '/admin/submenus', 0, 'SubMenusController', 'index', 'bx-menu-alt-right', 2, 1, '2023-03-06 12:41:44'),
(3, 1, 'Permisos', '/admin/permisos', 0, 'PermisosController', 'index', 'bx-key', 4, 1, '2023-03-06 12:42:10'),
(6, 1, 'P. Extras', '/admin/permisos-especiales', 0, 'PermisosEspecialesController', 'index', 'bx bx-key', 5, 1, '2025-02-17 11:52:57'),
(7, 4, 'registrar', '/admin/pacientes', 0, 'PacientesController', 'index', 'bx-plus-medical text-success', 1, 1, '2025-02-17 12:59:00'),
(8, 5, 'Registrar', '/admin/personal', 0, 'PersonalController', 'index', 'bx bx-plus-medical text-success', 1, 1, '2025-02-18 16:04:05'),
(9, 6, 'Crear', '/admin/usuarios', 0, 'UsuariosController', 'index', 'bx bx-plus-medical text-success', 1, 1, '2025-02-18 18:02:34'),
(10, 5, 'Especialidades', '/admin/especialidades', 0, 'EspecialidadController', 'index', 'bx-list-check text-info', 2, 1, '2025-02-19 00:57:01'),
(11, 6, 'Registrar Personas', '/admin/personas', 0, 'PersonasController', 'index', 'bxs-user-plus text-info', 2, 1, '2025-02-19 14:20:04'),
(12, 6, 'Roles', '/admin/roles', 0, 'RolesController', 'index', 'bx bx-plus-circle text-danger', 3, 1, '2025-02-19 14:22:27'),
(13, 7, 'Análisis de Tendencias', '/admin/diagnosticos', 0, 'TestController', 'index', 'bx-book-add text-primary', 2, 1, '2025-02-20 10:40:37'),
(14, 5, 'Horario Médico', '/admin/horario-medico', 0, '#', 'index', 'bx bx-time', 3, 1, '2025-02-24 16:27:09'),
(15, 7, 'Preguntas', '/admin/preguntas', 0, 'PreguntasController', 'index', 'bx-sm bx-question-mark text-info', 3, 1, '2025-02-25 17:19:26'),
(16, 7, 'Lista de Test', '/admin/lista-test', 0, 'ListaTestController', 'index', 'bx-sm bx bx-list-ul text-success', 1, 1, '2025-03-04 12:41:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sis_usuarios`
--

CREATE TABLE `sis_usuarios` (
  `idusuario` int NOT NULL,
  `idrol` int NOT NULL,
  `idpersona` int NOT NULL,
  `usu_usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usu_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usu_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usu_activo` tinyint(1) NOT NULL,
  `usu_estado` tinyint(1) NOT NULL,
  `usu_primera` tinyint(1) NOT NULL,
  `usu_twoauth` tinyint(1) NOT NULL,
  `usu_code_twoauth` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usu_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_usuarios`
--

INSERT INTO `sis_usuarios` (`idusuario`, `idrol`, `idpersona`, `usu_usuario`, `usu_pass`, `usu_token`, `usu_activo`, `usu_estado`, `usu_primera`, `usu_twoauth`, `usu_code_twoauth`, `usu_fecha`) VALUES
(1, 1, 1, 'developer', '$2y$10$Fit/2psoTtAP.pctt2qiluYnf4vYcKqbGvFbZa.8/ngskf1HlwZvW', NULL, 1, 1, 0, 0, '', '2022-07-22 01:10:31'),
(2, 4, 2, 'leenhcraft', '$2y$10$xi42mbC26BQdjewO6/uwr.qPw4f6MjyVg/sU3frWBSkQSf8VQMR2q', '690189541371207fb6feffb8d22c2b92e171e47fa568d61bb343031d33416b7ada65bbbdf92a77ca', 0, 1, 1, 0, '', '2025-02-25 15:07:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pr_preguntas`
--
ALTER TABLE `pr_preguntas`
  ADD PRIMARY KEY (`id_pregunta`);

--
-- Indices de la tabla `pr_respuestas`
--
ALTER TABLE `pr_respuestas`
  ADD PRIMARY KEY (`id_respuesta`);

--
-- Indices de la tabla `pr_tipo_respuestas`
--
ALTER TABLE `pr_tipo_respuestas`
  ADD PRIMARY KEY (`id_tipo_respuesta`);

--
-- Indices de la tabla `sd_especialidades`
--
ALTER TABLE `sd_especialidades`
  ADD PRIMARY KEY (`idespecialidad`);

--
-- Indices de la tabla `sd_pacientes`
--
ALTER TABLE `sd_pacientes`
  ADD PRIMARY KEY (`idpaciente`);

--
-- Indices de la tabla `sd_personal_especialidad`
--
ALTER TABLE `sd_personal_especialidad`
  ADD PRIMARY KEY (`id_per_esp`);

--
-- Indices de la tabla `sd_personal_medico`
--
ALTER TABLE `sd_personal_medico`
  ADD PRIMARY KEY (`idpersonal`);

--
-- Indices de la tabla `sd_test`
--
ALTER TABLE `sd_test`
  ADD PRIMARY KEY (`idtest`);

--
-- Indices de la tabla `sd_test_preguntas`
--
ALTER TABLE `sd_test_preguntas`
  ADD PRIMARY KEY (`id_test_pregunta`);

--
-- Indices de la tabla `sis_acciones`
--
ALTER TABLE `sis_acciones`
  ADD PRIMARY KEY (`idaccion`);

--
-- Indices de la tabla `sis_centinela`
--
ALTER TABLE `sis_centinela`
  ADD PRIMARY KEY (`idcentinela`);

--
-- Indices de la tabla `sis_menus`
--
ALTER TABLE `sis_menus`
  ADD PRIMARY KEY (`idmenu`);

--
-- Indices de la tabla `sis_permisos`
--
ALTER TABLE `sis_permisos`
  ADD PRIMARY KEY (`idpermisos`);

--
-- Indices de la tabla `sis_permisos_extras`
--
ALTER TABLE `sis_permisos_extras`
  ADD PRIMARY KEY (`idpermiso`);

--
-- Indices de la tabla `sis_personal`
--
ALTER TABLE `sis_personal`
  ADD PRIMARY KEY (`idpersona`);

--
-- Indices de la tabla `sis_recursos`
--
ALTER TABLE `sis_recursos`
  ADD PRIMARY KEY (`idrecurso`);

--
-- Indices de la tabla `sis_rol`
--
ALTER TABLE `sis_rol`
  ADD PRIMARY KEY (`idrol`);

--
-- Indices de la tabla `sis_sesiones`
--
ALTER TABLE `sis_sesiones`
  ADD PRIMARY KEY (`idsesion`);

--
-- Indices de la tabla `sis_submenus`
--
ALTER TABLE `sis_submenus`
  ADD PRIMARY KEY (`idsubmenu`);

--
-- Indices de la tabla `sis_usuarios`
--
ALTER TABLE `sis_usuarios`
  ADD PRIMARY KEY (`idusuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pr_preguntas`
--
ALTER TABLE `pr_preguntas`
  MODIFY `id_pregunta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pr_respuestas`
--
ALTER TABLE `pr_respuestas`
  MODIFY `id_respuesta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `pr_tipo_respuestas`
--
ALTER TABLE `pr_tipo_respuestas`
  MODIFY `id_tipo_respuesta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `sd_especialidades`
--
ALTER TABLE `sd_especialidades`
  MODIFY `idespecialidad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `sd_pacientes`
--
ALTER TABLE `sd_pacientes`
  MODIFY `idpaciente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sd_personal_especialidad`
--
ALTER TABLE `sd_personal_especialidad`
  MODIFY `id_per_esp` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sd_personal_medico`
--
ALTER TABLE `sd_personal_medico`
  MODIFY `idpersonal` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sd_test`
--
ALTER TABLE `sd_test`
  MODIFY `idtest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `sd_test_preguntas`
--
ALTER TABLE `sd_test_preguntas`
  MODIFY `id_test_pregunta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `sis_acciones`
--
ALTER TABLE `sis_acciones`
  MODIFY `idaccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sis_centinela`
--
ALTER TABLE `sis_centinela`
  MODIFY `idcentinela` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7704;

--
-- AUTO_INCREMENT de la tabla `sis_menus`
--
ALTER TABLE `sis_menus`
  MODIFY `idmenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `sis_permisos`
--
ALTER TABLE `sis_permisos`
  MODIFY `idpermisos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `sis_permisos_extras`
--
ALTER TABLE `sis_permisos_extras`
  MODIFY `idpermiso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `sis_personal`
--
ALTER TABLE `sis_personal`
  MODIFY `idpersona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sis_recursos`
--
ALTER TABLE `sis_recursos`
  MODIFY `idrecurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `sis_rol`
--
ALTER TABLE `sis_rol`
  MODIFY `idrol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sis_sesiones`
--
ALTER TABLE `sis_sesiones`
  MODIFY `idsesion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `sis_submenus`
--
ALTER TABLE `sis_submenus`
  MODIFY `idsubmenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `sis_usuarios`
--
ALTER TABLE `sis_usuarios`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
