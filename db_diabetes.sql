-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 06-03-2025 a las 02:49:06
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
(1, 'BUSTAMANTE FERNANDEZ ASHLY NAOMI', '76144151', '0', '46', 'F', 75.00, 1.55, '2025-02-25 15:07:18', 0, '2025-03-05 00:05:43', 1, 0, NULL, NULL),
(2, 'BUSTAMANTE FERNANDEZ LEENH ALEXANDER', '76144152', '987654321', '23', 'M', 70.00, 1.70, '2025-03-04 21:08:02', 1, '2025-03-05 00:14:49', 1, 0, NULL, NULL),
(3, 'MONTENEGRO RAMIREZ, JACK BRAYAN', '76144150', '0', '23', '0', 70.00, 1.60, '2025-03-05 18:02:41', 0, '2025-03-05 18:26:45', 3, 0, NULL, NULL);

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
(1, 2, 1, 70.000, 1.700, 24.200, '2025-03-05 00:14:49', 'Alto', '38.461538461538', 1, '{\"entradas\":[0,0,0,2,1,2,2,0,2,1,2,1,1],\"conteo\":{\"bajo\":4,\"moderado\":4,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":30.76923076923077,\"alto\":38.46153846153847},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-03-05 00:14:49', 1, '2025-03-05 00:14:49', 1, 0, NULL, NULL),
(2, 3, 3, 70.000, 1.700, 24.200, '2025-03-05 18:17:58', 'Alto', '46.153846153846', 1, '{\"entradas\":[0,0,0,2,1,2,1,2,1,2,2,2,1],\"conteo\":{\"bajo\":3,\"moderado\":4,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":30.76923076923077,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-03-05 18:17:58', 3, '2025-03-05 18:17:58', 1, 0, NULL, NULL),
(3, 3, 3, 70.000, 1.700, 24.200, '2025-03-05 18:19:40', 'Moderado', '46.153846153846', 1, '{\"entradas\":[0,0,0,1,1,2,0,1,0,2,1,1,1],\"conteo\":{\"bajo\":5,\"moderado\":6,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":46.15384615384615,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-03-05 18:19:40', 3, '2025-03-05 18:19:40', 1, 0, NULL, NULL),
(4, 3, 3, 70.000, 1.600, 27.300, '2025-03-05 18:25:32', 'Bajo', '84.615384615385', 1, '{\"entradas\":[0,1,0,0,0,0,0,0,0,0,0,0,2],\"conteo\":{\"bajo\":11,\"moderado\":1,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":84.61538461538461,\"moderado\":7.6923076923076925,\"alto\":7.6923076923076925},\"clasificacion\":\"Bajo\",\"recomendaciones\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"]}', '2025-03-05 18:25:32', 3, '2025-03-05 18:25:32', 1, 0, NULL, NULL),
(5, 3, 3, 70.000, 1.600, 27.300, '2025-03-05 18:26:45', 'Bajo', '92.307692307692', 1, '{\"entradas\":[0,1,0,0,0,0,0,0,0,0,0,0,0],\"conteo\":{\"bajo\":12,\"moderado\":1,\"alto\":0,\"total\":13},\"probabilidades\":{\"bajo\":92.3076923076923,\"moderado\":7.6923076923076925,\"alto\":0},\"clasificacion\":\"Bajo\",\"recomendaciones\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"]}', '2025-03-05 18:26:45', 3, '2025-03-05 18:26:45', 1, 0, NULL, NULL);

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
(11, 1, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-05 00:14:49'),
(12, 2, 1, 1, 'Casi Nunca', '2025-03-05 18:17:58'),
(13, 2, 2, 9, 'Mucho', '2025-03-05 18:17:58'),
(14, 2, 3, 11, 'Poco', '2025-03-05 18:17:58'),
(15, 2, 4, 14, 'Si', '2025-03-05 18:17:58'),
(16, 2, 5, 16, 'Sí pero ocasionalmente', '2025-03-05 18:17:58'),
(17, 2, 6, 20, 'Sí siempre', '2025-03-05 18:17:58'),
(18, 2, 7, 22, 'Poco', '2025-03-05 18:17:58'),
(19, 2, 8, 29, 'Mucho', '2025-03-05 18:17:58'),
(20, 2, 9, 32, 'Mucho', '2025-03-05 18:17:58'),
(21, 2, 10, 35, 'Si', '2025-03-05 18:17:58'),
(22, 2, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-05 18:17:58'),
(23, 3, 1, 1, 'Casi Nunca', '2025-03-05 18:19:40'),
(24, 3, 2, 8, 'Poco', '2025-03-05 18:19:40'),
(25, 3, 3, 11, 'Poco', '2025-03-05 18:19:40'),
(26, 3, 4, 14, 'Si', '2025-03-05 18:19:40'),
(27, 3, 5, 15, 'No', '2025-03-05 18:19:40'),
(28, 3, 6, 19, 'Sí ocasionalmente', '2025-03-05 18:19:40'),
(29, 3, 7, 21, 'Nada', '2025-03-05 18:19:40'),
(30, 3, 8, 29, 'Mucho', '2025-03-05 18:19:40'),
(31, 3, 9, 31, 'Poco', '2025-03-05 18:19:40'),
(32, 3, 10, 34, 'Regularmente', '2025-03-05 18:19:40'),
(33, 3, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-05 18:19:40'),
(34, 4, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-05 18:25:32'),
(35, 5, 4, 13, 'No', '2025-03-05 18:26:45');

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
(2, 76144151, 'BUSTAMANTE FERNANDEZ ASHLY NAOMI', NULL, 'hackingleenh@gmail.com', NULL, NULL, 1, '2025-02-25 15:07:18'),
(3, 76144150, 'MONTENEGRO RAMIREZ, JACK BRAYAN', NULL, '76144150@gmail.com', NULL, NULL, 1, '2025-03-05 18:02:41');

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
(1, 2, '0ff58d7c85bc3f2c17efa627b5a75c58ac9f88f86f03c2e357236496869d451828ab06de505931be', '::1', '2025-03-05 15:49:42', '1741211382', 0),
(2, 2, '3af7cfcb4de34e7b86b03b2003afde037c8f8d22f7ecc37117feab03cee2f04f75223d1ac79e9c81', '::1', '2025-03-05 15:50:24', '1741211424', 0),
(3, 2, 'a8645d7bfac6932f8b0fb027f92bc3fc8feed9af50a7c0d33d24a3b4ac6d84cd74f7e8b33c0296ee', '::1', '2025-03-05 15:51:56', '1741211516', 0),
(4, 2, 'c819b3a71f4c5b78c596fe34172379dc6ac85490cb5a6cfb37a4480bcba3187184e292be296c8fb0', '::1', '2025-03-05 15:52:03', '1741211523', 0),
(5, 2, '2dbc3b4587db5b6444e95889698c986b8adf376cb3ad37b17c328aa60ace93f4f9730c7557565e25', '::1', '2025-03-05 15:54:32', '1741211672', 0),
(6, 2, 'd48412add0dfc8bc24d764c2b93798b30cca647f2f74135f8ad39fc689f72a88fd0c2195f68fd400', '::1', '2025-03-05 16:03:14', '1741212315', 0),
(7, 2, '39a81939b88b918a25c37c741b35d841fb9ad918804b286146e8438fbd4c6d4db1b6a45802b5776c', '::1', '2025-03-05 16:07:27', '1741212495', 0),
(8, 2, '901544878c8950724d9ac22d065e45aeff71f72f1177bccb7e436fa5422aa7dafcf1286c14bae187', '::1', '2025-03-05 16:08:23', '1741212504', 0),
(9, 2, 'b7e51a80ae9a52436b8de2bd72c7dca584b39767d31224c97148a5966906bb36722c83c82f902644', '::1', '2025-03-05 16:09:25', '1741212648', 0),
(10, 2, 'e528ce6a43dd8c3d19da58f13cc4f16aff8009f914749ec84d61baeaf81026e3cb7b5713733a5f9a', '::1', '2025-03-05 16:10:54', '1741219149', 0),
(11, 1, '5c0222b76535b60401d46e535a0e2e57a083df026008f7921bc6edb3bd043b84ba3252432afee3bf', '::1', '2025-03-05 18:00:20', '1741219335', 0),
(12, 3, '6d29ec861ed55f5a1d167f7e4eaf8516956069038202b834f582ad7412cbbf91b8bb357c5e98000f', '::1', '2025-03-05 18:03:07', '1741220327', 0),
(13, 3, '6395f03c5b9b18d833ccef10eee196954643e73dbe1b76ab9e4c2034d521ea93c8df1fa40bf937d1', '::1', '2025-03-05 18:19:23', '1741221192', 1),
(14, 1, 'bcbca9c8c6a8b7c026a06917ecd444bd38f2da09b2b8b5759d446dedf15201e4169263d4103ae278', '::1', '2025-03-05 18:20:01', '1741220441', 1);

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
  `usu_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultima_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sis_usuarios`
--

INSERT INTO `sis_usuarios` (`idusuario`, `idrol`, `idpersona`, `usu_usuario`, `usu_pass`, `usu_token`, `usu_activo`, `usu_estado`, `usu_primera`, `usu_twoauth`, `usu_code_twoauth`, `usu_fecha`, `ultima_actualizacion`) VALUES
(1, 1, 1, 'developer', '$2y$10$Fit/2psoTtAP.pctt2qiluYnf4vYcKqbGvFbZa.8/ngskf1HlwZvW', NULL, 1, 1, 0, 0, '', '2022-07-22 01:10:31', NULL),
(2, 4, 2, 'leenhcraft', '$2y$10$xi42mbC26BQdjewO6/uwr.qPw4f6MjyVg/sU3frWBSkQSf8VQMR2q', NULL, 1, 1, 0, 0, '', '2025-02-25 15:07:18', NULL),
(3, 4, 3, '76144150', '$2y$10$wg7QnvHRLBWCbTi1FOcP5e1KVRHev.Sy2bYMMTnvN7gP08/J43f4a', NULL, 1, 1, 0, 0, '', '2025-03-05 18:02:41', NULL);

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
  MODIFY `idpaciente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `idtest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `sd_test_preguntas`
--
ALTER TABLE `sd_test_preguntas`
  MODIFY `id_test_pregunta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `sis_acciones`
--
ALTER TABLE `sis_acciones`
  MODIFY `idaccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sis_centinela`
--
ALTER TABLE `sis_centinela`
  MODIFY `idcentinela` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8025;

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
  MODIFY `idpersona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `idsesion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `sis_submenus`
--
ALTER TABLE `sis_submenus`
  MODIFY `idsubmenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `sis_usuarios`
--
ALTER TABLE `sis_usuarios`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
