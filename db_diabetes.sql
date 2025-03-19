-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 19-03-2025 a las 15:46:20
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
-- Estructura de tabla para la tabla `ac_citas`
--

CREATE TABLE `ac_citas` (
  `idcita` int NOT NULL,
  `idusuario` int NOT NULL,
  `idpaciente` int NOT NULL,
  `idpersonal` int NOT NULL,
  `id_estado_cita` int NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_eliminacion` datetime DEFAULT NULL,
  `eliminado_por` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ac_citas`
--

INSERT INTO `ac_citas` (`idcita`, `idusuario`, `idpaciente`, `idpersonal`, `id_estado_cita`, `fecha`, `hora`, `observaciones`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 0, 32, 3, 1, '2025-03-31', '11:00:00', 'prueba de agenda de cita médica, fecha y hora actualizada.', '2025-03-18 22:05:04', 1, '2025-03-18 23:48:07', 1, 0, NULL, NULL),
(2, 0, 11, 3, 2, '2025-03-24', '08:00:00', 'cita programada para el 31 pero media hora despues', '2025-03-18 23:49:40', 1, '2025-03-19 00:03:13', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ac_estado_cita`
--

CREATE TABLE `ac_estado_cita` (
  `id_estado_cita` int NOT NULL,
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
-- Volcado de datos para la tabla `ac_estado_cita`
--

INSERT INTO `ac_estado_cita` (`id_estado_cita`, `nombre`, `descripcion`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 'Programada', 'Cita confirmada y agendada para una fecha y hora específica', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(2, 'Cancelada', 'Cita cancelada por el paciente o por el personal médico', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(3, 'Completada', 'Cita que ya fue realizada exitosamente', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(4, 'Reprogramada', 'Cita que fue cambiada a una nueva fecha u horario', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(5, 'En espera', 'Cita en lista de espera, pendiente de confirmación de horario', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(6, 'No asistió', 'El paciente no se presentó a la cita programada', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(7, 'En proceso', 'El paciente está siendo atendido actualmente', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(8, 'Confirmada', 'Cita confirmada por el paciente después de recordatorio', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(9, 'Bloqueada', 'Horario bloqueado por el médico (no disponible)', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL),
(10, 'Emergencia', 'Cita de tipo emergencia o urgencia', '2025-03-16 22:24:50', 1, NULL, NULL, 0, NULL, NULL);

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
(9, 1, 9, '¿REALIZAS ACTIVIDAD FISÍCA?', '9', 'Activo', '2025-02-27 12:34:25', 1, '2025-03-10 12:25:49', 1, 0, NULL, NULL),
(10, 1, 10, '¿CONSUMES A DIARIO ALGUNAS FRUTAS O VERDURAS?', '10', 'Activo', '2025-02-27 12:35:15', 1, '2025-03-10 12:26:11', 1, 0, NULL, NULL),
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
(36, 11, 1, 7, 'No', 'Nivel bajo', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":0,\"valor_seleccionado\":0}', 1, '2025-02-27 12:39:55'),
(37, 11, 1, 7, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', 'Nivel moderado', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-02-27 12:39:55'),
(38, 11, 1, 7, 'Si (primer grado de consanguinidad): padres e hijos', 'Nivel alto', '{\"opciones\":[\"No\",\"Si (segundo y tercer grado de consanguinidad): abuelos t\\u00edos sobrinos\",\"Si (primer grado de consanguinidad): padres e hijos\"],\"valores\":[0,1,2],\"seleccionada\":2,\"valor_seleccionado\":2}', 1, '2025-02-27 12:39:55'),
(39, 9, 1, 7, 'Nada', 'Nivel alto', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[2,1,0],\"seleccionada\":0,\"valor_seleccionado\":2}', 1, '2025-03-10 12:25:49'),
(40, 9, 1, 7, 'Poco', 'Nivel moderado', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[2,1,0],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-03-10 12:25:49'),
(41, 9, 1, 7, 'Mucho', 'Nivel bajo', '{\"opciones\":[\"Nada\",\"Poco\",\"Mucho\"],\"valores\":[2,1,0],\"seleccionada\":2,\"valor_seleccionado\":0}', 1, '2025-03-10 12:25:49'),
(42, 10, 1, 7, 'No', 'Nivel alto', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[2,1,0],\"seleccionada\":0,\"valor_seleccionado\":2}', 1, '2025-03-10 12:26:11'),
(43, 10, 1, 7, 'Regularmente', 'Nivel moderado', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[2,1,0],\"seleccionada\":1,\"valor_seleccionado\":1}', 1, '2025-03-10 12:26:11'),
(44, 10, 1, 7, 'Si', 'Nivel bajo', '{\"opciones\":[\"No\",\"Regularmente\",\"Si\"],\"valores\":[2,1,0],\"seleccionada\":2,\"valor_seleccionado\":0}', 1, '2025-03-10 12:26:11');

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
-- Estructura de tabla para la tabla `sd_dias_semana`
--

CREATE TABLE `sd_dias_semana` (
  `iddia` int NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `eliminado_por` int DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_dias_semana`
--

INSERT INTO `sd_dias_semana` (`iddia`, `nombre`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `eliminado_por`, `fecha_eliminacion`) VALUES
(1, 'Lunes', '2025-03-14 15:48:05', 1, NULL, NULL, 0, NULL, NULL),
(2, 'Martes', '2025-03-14 15:48:05', 1, NULL, NULL, 0, NULL, NULL),
(3, 'Miercoles', '2025-03-14 15:49:01', 1, NULL, NULL, 0, NULL, NULL),
(4, 'Jueves', '2025-03-14 15:49:01', 1, NULL, NULL, 0, NULL, NULL),
(5, 'Viernes', '2025-03-14 15:49:01', 1, NULL, NULL, 0, NULL, NULL),
(6, 'Sábado', '2025-03-14 15:49:01', 1, NULL, NULL, 0, NULL, NULL),
(7, 'Domingo', '2025-03-14 15:49:01', 1, NULL, NULL, 0, NULL, NULL);

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
-- Estructura de tabla para la tabla `sd_horarios_medicos`
--

CREATE TABLE `sd_horarios_medicos` (
  `id_horario_medico` int NOT NULL,
  `idpersonal` int NOT NULL,
  `iddia` int NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creado_por` int NOT NULL,
  `ultima_actualizacion` datetime DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  `eliminado_por` int DEFAULT NULL,
  `fecha_eliminacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_horarios_medicos`
--

INSERT INTO `sd_horarios_medicos` (`id_horario_medico`, `idpersonal`, `iddia`, `hora_inicio`, `hora_fin`, `activo`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `eliminado_por`, `fecha_eliminacion`) VALUES
(1, 1, 1, '07:00:00', '13:00:00', 1, '2025-03-14 17:55:03', 1, NULL, NULL, 0, NULL, NULL),
(2, 1, 2, '13:00:00', '17:00:00', 1, '2025-03-14 17:59:28', 1, NULL, NULL, 0, NULL, NULL),
(3, 3, 1, '07:00:00', '13:00:00', 1, '2025-03-14 18:03:58', 1, NULL, NULL, 0, NULL, NULL),
(4, 3, 3, '07:00:00', '13:00:00', 1, '2025-03-14 18:03:58', 1, NULL, NULL, 0, NULL, NULL),
(5, 3, 5, '07:00:00', '13:00:00', 1, '2025-03-14 18:03:58', 1, NULL, NULL, 0, NULL, NULL),
(6, 1, 3, '13:00:00', '17:00:00', 1, '2025-03-14 18:04:22', 1, NULL, NULL, 0, NULL, NULL),
(7, 6, 3, '07:00:00', '17:00:00', 1, '2025-03-14 18:05:43', 1, NULL, NULL, 0, NULL, NULL),
(8, 6, 5, '08:00:00', '13:00:00', 1, '2025-03-14 18:09:07', 1, NULL, NULL, 0, NULL, NULL),
(9, 7, 1, '07:00:00', '13:00:00', 1, '2025-03-14 18:22:03', 1, NULL, NULL, 0, NULL, NULL),
(10, 7, 2, '07:00:00', '13:00:00', 1, '2025-03-14 18:22:03', 1, NULL, NULL, 0, NULL, NULL),
(11, 7, 3, '07:00:00', '13:00:00', 1, '2025-03-14 18:22:03', 1, NULL, NULL, 0, NULL, NULL),
(12, 5, 1, '07:00:00', '12:00:00', 1, '2025-03-14 23:01:46', 1, NULL, 1, 1, 1, '2025-03-14 23:10:38'),
(13, 8, 1, '07:00:00', '13:00:00', 1, '2025-03-14 23:16:54', 1, NULL, NULL, 0, NULL, NULL),
(14, 8, 3, '07:00:00', '13:00:00', 1, '2025-03-14 23:16:54', 1, NULL, NULL, 1, 1, '2025-03-14 23:17:33'),
(15, 8, 5, '13:00:00', '17:00:00', 1, '2025-03-14 23:16:54', 1, NULL, 1, 0, NULL, NULL);

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
(1, 'RODRIGUEZ LOPEZ, LEIVY MELINA', '45451239', '969620259', '32', 'F', 56.00, 1.58, '2025-03-10 23:26:38', 1, '2025-03-11 15:21:23', 1, 0, NULL, NULL),
(2, 'CORONADO LOPEZ, YAEL CATHERINE', '75937506', '969620259', '28', 'F', 52.00, 1.58, '2025-03-10 23:27:28', 1, '2025-03-11 16:44:00', 1, 0, NULL, NULL),
(3, 'SALAS ISUIZA, CLYDER', '40667777', '969620259', '45', 'F', 70.00, 1.69, '2025-03-10 23:39:42', 1, '2025-03-11 16:45:44', 1, 0, NULL, NULL),
(4, 'JIMENEZ GUERRERO, EDMUNDO AMADO', '27734529', '969620259', '50', 'M', 80.00, 1.74, '2025-03-10 23:45:43', 1, '2025-03-11 16:46:40', 1, 0, NULL, NULL),
(5, 'RAMOS SANTA CRUZ, VILMA', '47776832', '969620259', '45', 'F', 59.00, 1.50, '2025-03-10 23:46:15', 1, '2025-03-11 16:48:38', 1, 0, NULL, NULL),
(6, 'QUINTANA CHILON, LINDIHS JHEYSI', '76558108', '969620259', '24', 'F', 62.00, 1.60, '2025-03-10 23:47:00', 1, '2025-03-11 16:49:45', 1, 0, NULL, NULL),
(7, 'TARRILLO OCAS, JEAN LIU', '72845692', '969620259', '24', 'M', 93.00, 1.75, '2025-03-10 23:47:36', 1, '2025-03-11 16:51:16', 1, 0, NULL, NULL),
(8, 'BENITES UTRILLA, ELMER ALADINO', '19670940', '969620259', '54', 'M', 72.00, 1.65, '2025-03-10 23:48:25', 1, '2025-03-11 16:52:15', 1, 0, NULL, NULL),
(9, 'ROJAS GUERRA, LITA GISSELLE', '40782195', '969620259', '46', 'F', 72.00, 1.62, '2025-03-10 23:49:30', 1, '2025-03-11 16:54:40', 1, 0, NULL, NULL),
(10, 'PISCO CHUJUTALLI, JUAN HILTER', '40068127', '969620259', '51', 'M', 76.00, 1.65, '2025-03-10 23:50:03', 1, '2025-03-11 16:56:58', 1, 0, NULL, NULL),
(11, 'CABANILLAS CHOQUEHUANCA, JORGE JHACKSON', '74530862', '969620259', '23', 'M', 73.00, 1.73, '2025-03-10 23:51:17', 1, '2025-03-11 16:58:56', 1, 0, NULL, NULL),
(12, 'HUERTAS CASTAÑEDA, ISABEL', '47028098', '969620259', '35', 'F', 64.00, 1.58, '2025-03-10 23:58:51', 1, '2025-03-11 17:00:25', 1, 0, NULL, NULL),
(13, 'CAMPOS PONCE, LUZ DEL PILAR', '71299341', '969620259', '34', 'F', 70.00, 1.60, '2025-03-11 11:59:57', 1, '2025-03-11 17:02:08', 1, 0, NULL, NULL),
(14, 'SABOYA TUESTA, ISABEL', '43623910', '969620259', '55', 'F', 78.00, 1.65, '2025-03-11 12:03:56', 1, '2025-03-11 17:08:06', 1, 0, NULL, NULL),
(15, 'LLACSAHUACHE VELA, DIANA ROSA', '74134414', '969620259', '29', 'F', 65.00, 1.68, '2025-03-11 12:06:04', 1, '2025-03-11 20:42:23', 1, 0, NULL, NULL),
(16, 'TUESTA ANGULO, ELIZABETH', '46425100', '969620259', '48', 'F', 59.00, 1.50, '2025-03-11 12:06:39', 1, '2025-03-11 20:43:59', 1, 0, NULL, NULL),
(17, 'VASQUEZ BECERRA, DILMER', '43912752', '969620259', '40', 'M', 72.00, 1.60, '2025-03-11 12:07:57', 1, '2025-03-11 20:45:03', 1, 0, NULL, NULL),
(18, 'LOJA VARGAS, ANITA', '00838633', '969620259', '50', 'F', 68.00, 1.49, '2025-03-11 12:12:27', 1, '2025-03-11 20:46:01', 1, 0, NULL, NULL),
(19, 'GUEVARA TANTALEAN, ELVIA', '27727677', '969620259', '52', 'F', 76.00, 1.50, '2025-03-11 12:13:05', 1, '2025-03-11 20:53:18', 1, 0, NULL, NULL),
(20, 'CASIQUE SABOYA, LEONOR', '40705710', '969620259', '44', 'F', 64.00, 1.55, '2025-03-11 12:20:14', 1, '2025-03-11 20:54:59', 1, 0, NULL, NULL),
(21, 'GONZALES GONZALES, SONI GODOLBERTO', '43203102', '969620259', '39', 'M', 97.00, 1.68, '2025-03-11 12:21:13', 1, '2025-03-11 20:57:05', 1, 0, NULL, NULL),
(22, 'SANTILLANA BARBARAN, JUAN', '42885529', '969620259', '47', 'M', 90.00, 1.70, '2025-03-11 12:22:08', 1, '2025-03-12 09:35:27', 1, 0, NULL, NULL),
(23, 'ALVARADO PACHERRES, ARNULFO', '16468744', '969620259', '65', 'M', 83.00, 169.00, '2025-03-11 12:22:51', 1, '2025-03-14 23:19:25', 1, 0, NULL, NULL),
(24, 'GUEVARA REYES, RUBEN ISMAEL', '18127427', '969620259', '50', 'M', 98.00, 1.73, '2025-03-11 12:23:46', 1, '2025-03-12 09:38:28', 1, 0, NULL, NULL),
(25, 'GONZALES PEREZ, MAX RULEN', '80572180', '969620259', '47', 'M', 78.00, 1.69, '2025-03-11 12:24:35', 1, '2025-03-12 09:39:45', 1, 0, NULL, NULL),
(26, 'RIVA ACOSTA, LILIBETH', '45220449', '969620259', '38', 'F', 74.00, 1.65, '2025-03-11 12:25:25', 1, '2025-03-12 21:45:59', 1, 0, NULL, NULL),
(27, 'MAYTA CIEZA, ALAN', '44133886', '969620259', '40', 'M', 80.00, 1.67, '2025-03-11 12:25:51', 1, '2025-03-12 21:47:34', 1, 0, NULL, NULL),
(28, 'YNGA TORRES, ROSA MERCEDES', '33402933', '969620259', '68', 'F', 61.00, 1.52, '2025-03-11 12:26:27', 1, '2025-03-12 21:48:54', 1, 0, NULL, NULL),
(29, 'CAMPOS LOZANO, UVILDA', '47010613', '969620259', '32', 'F', 70.00, 1.62, '2025-03-11 12:26:49', 1, '2025-03-12 21:50:34', 1, 0, NULL, NULL),
(30, 'SANDOVAL ROQUE, MARIA JHOILY', '72738916', '969620259', '26', 'F', 53.00, 155.00, '2025-03-11 12:27:20', 1, '2025-03-13 18:55:06', 1, 0, NULL, NULL),
(32, 'BUSTAMANTE FERNANDEZ LEENH ALEXANDER', '76144152', '987654321', '23', 'M', 72.00, 169.00, '2025-03-14 14:43:10', 1, '2025-03-19 10:27:10', 2, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sd_personal_especialidad`
--

CREATE TABLE `sd_personal_especialidad` (
  `id_per_esp` int NOT NULL,
  `idespecialidad` int NOT NULL,
  `idpersonal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sd_personal_especialidad`
--

INSERT INTO `sd_personal_especialidad` (`id_per_esp`, `idespecialidad`, `idpersonal`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 8, 4),
(5, 7, 5),
(6, 4, 6),
(7, 6, 7),
(8, 9, 8);

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

--
-- Volcado de datos para la tabla `sd_personal_medico`
--

INSERT INTO `sd_personal_medico` (`idpersonal`, `nombre`, `dni`, `sexo`, `edad`, `direccion`, `celular`, `fecha_registro`, `creado_por`, `ultima_actualizacion`, `actualizado_por`, `eliminado`, `fecha_eliminacion`, `eliminado_por`) VALUES
(1, 'ABANTO PEÑA, JHOHAN ANDREE', '70790218', 'M', '40', '.', '987654321', '2025-03-14 15:42:08', 1, NULL, NULL, 0, NULL, NULL),
(2, 'ABARCA GUTIERREZ, NATHALY YAHAIRA', '74075940', 'F', '40', '.', '987654321', '2025-03-14 15:42:27', 1, NULL, NULL, 0, NULL, NULL),
(3, 'AGIP RUBIO, RICARDO GERMAN', '27440013', 'M', '40', '.', '987654321', '2025-03-14 16:01:00', 1, NULL, NULL, 0, NULL, NULL),
(4, 'BRIOSO MEJIA, JUAN BERLIN', '71298019', 'M', '40', '.', '987654321', '2025-03-14 16:01:58', 1, NULL, NULL, 0, NULL, NULL),
(5, 'CAMPOSANO DE LA CRUZ, CRISTIAN JAHIR', '70296583', 'M', '40', '.', '987654321', '2025-03-14 16:07:35', 1, NULL, NULL, 0, NULL, NULL),
(6, 'CARRANZA VELARDE, ANA PAULA', '73612932', 'F', '40', '.', '987654321', '2025-03-14 16:08:06', 1, NULL, NULL, 0, NULL, NULL),
(7, 'CURIHUAMAN ALVA, LISBET MARIBEL', '75701592', 'F', '40', '.', '987654321', '2025-03-14 18:21:41', 1, NULL, NULL, 0, NULL, NULL),
(8, 'ESPINOLA SILVA, JIMENA ALESSANDRA', '70929145', 'F', '40', '.', '987654321', '2025-03-14 23:16:31', 1, NULL, NULL, 0, NULL, NULL);

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
(1, 1, 1, 56.000, 1.580, 22.400, '2025-02-03 10:21:23', 'Moderado', '53.846153846154', 1, '{\"entradas\":[0,0,1,1,1,0,1,0,1,1,2,1,0],\"conteo\":{\"bajo\":5,\"moderado\":7,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":53.84615384615385,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 10:21:23', 1, '2025-02-03 10:21:23', 1, 0, NULL, NULL),
(2, 2, 1, 52.000, 1.580, 20.800, '2025-02-03 10:44:00', 'Alto', '46.153846153846', 1, '{\"entradas\":[0,0,2,2,1,0,2,0,1,0,2,2,2],\"conteo\":{\"bajo\":5,\"moderado\":2,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":15.384615384615385,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-03 10:44:00', 1, '2025-02-03 10:44:00', 1, 0, NULL, NULL),
(3, 3, 1, 70.000, 1.690, 24.500, '2025-02-03 10:55:44', 'Moderado', '61.538461538462', 1, '{\"entradas\":[1,0,1,1,1,0,1,0,1,1,2,1,0],\"conteo\":{\"bajo\":4,\"moderado\":8,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":61.53846153846154,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 10:55:44', 1, '2025-02-03 10:55:44', 1, 0, NULL, NULL),
(4, 4, 1, 80.000, 1.740, 26.400, '2025-02-03 11:26:40', 'Moderado', '84.615384615385', 1, '{\"entradas\":[1,1,2,1,1,0,1,1,1,1,1,1,1],\"conteo\":{\"bajo\":1,\"moderado\":11,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":7.6923076923076925,\"moderado\":84.61538461538461,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 11:26:40', 1, '2025-02-03 11:26:40', 1, 0, NULL, NULL),
(5, 5, 1, 59.000, 1.500, 26.200, '2025-02-03 11:38:38', 'Moderado', '76.923076923077', 1, '{\"entradas\":[1,1,0,1,1,2,1,1,1,1,1,1,0],\"conteo\":{\"bajo\":2,\"moderado\":10,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":76.92307692307693,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 11:38:38', 1, '2025-02-03 11:38:38', 1, 0, NULL, NULL),
(6, 6, 1, 62.000, 1.600, 24.200, '2025-02-03 11:49:45', 'Bajo', '76.923076923077', 1, '{\"entradas\":[0,0,0,1,0,0,0,0,1,0,0,1,0],\"conteo\":{\"bajo\":10,\"moderado\":3,\"alto\":0,\"total\":13},\"probabilidades\":{\"bajo\":76.92307692307693,\"moderado\":23.076923076923077,\"alto\":0},\"clasificacion\":\"Bajo\",\"recomendaciones\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"]}', '2025-02-03 11:49:45', 1, '2025-02-03 11:49:45', 1, 0, NULL, NULL),
(7, 7, 1, 93.000, 1.750, 30.400, '2025-02-03 12:15:16', 'Moderado', '46.153846153846', 1, '{\"entradas\":[0,2,1,2,2,2,1,0,2,1,1,1,1],\"conteo\":{\"bajo\":2,\"moderado\":6,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":46.15384615384615,\"alto\":38.46153846153847},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 12:15:16', 1, '2025-02-03 12:15:16', 1, 0, NULL, NULL),
(8, 8, 1, 72.000, 1.650, 26.400, '2025-02-03 12:30:15', 'Moderado', '76.923076923077', 1, '{\"entradas\":[1,1,1,1,1,0,1,0,1,1,1,1,0],\"conteo\":{\"bajo\":3,\"moderado\":10,\"alto\":0,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":76.92307692307693,\"alto\":0},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-03 12:30:15', 1, '2025-02-03 12:30:15', 1, 0, NULL, NULL),
(9, 9, 1, 72.000, 1.620, 27.400, '2025-02-03 12:54:40', 'Alto', '46.153846153846', 1, '{\"entradas\":[1,1,2,2,2,2,1,1,2,1,0,2,0],\"conteo\":{\"bajo\":2,\"moderado\":5,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":38.46153846153847,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-03 12:54:40', 1, '2025-02-03 12:54:40', 1, 0, NULL, NULL),
(10, 10, 1, 76.000, 1.650, 27.900, '2025-02-04 09:56:58', 'Moderado', '69.230769230769', 1, '{\"entradas\":[1,1,1,1,2,2,1,1,1,1,1,0,0],\"conteo\":{\"bajo\":2,\"moderado\":9,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":69.23076923076923,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-04 09:56:58', 1, '2025-02-04 09:56:58', 1, 0, NULL, NULL),
(11, 11, 1, 73.000, 1.730, 24.400, '2025-02-04 10:18:56', 'Moderado', '53.846153846154', 1, '{\"entradas\":[0,0,1,1,2,2,1,1,1,1,1,0,0],\"conteo\":{\"bajo\":4,\"moderado\":7,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":53.84615384615385,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-04 10:18:56', 1, '2025-02-04 10:18:56', 1, 0, NULL, NULL),
(12, 12, 1, 64.000, 1.580, 25.600, '2025-02-04 10:40:25', 'Alto', '46.153846153846', 1, '{\"entradas\":[0,1,2,2,2,0,1,0,2,1,2,2,1],\"conteo\":{\"bajo\":3,\"moderado\":4,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":30.76923076923077,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-04 10:40:25', 1, '2025-02-04 10:40:25', 1, 0, NULL, NULL),
(13, 13, 1, 70.000, 1.600, 27.300, '2025-02-04 11:02:08', 'Alto', '46.153846153846', 1, '{\"entradas\":[0,1,2,2,2,2,1,0,1,1,2,2,0],\"conteo\":{\"bajo\":3,\"moderado\":4,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":30.76923076923077,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-04 11:02:08', 1, '2025-02-04 11:02:08', 1, 0, NULL, NULL),
(14, 14, 1, 78.000, 1.650, 28.700, '2025-02-04 11:28:07', 'Alto', '61.538461538462', 1, '{\"entradas\":[2,1,1,2,2,2,1,1,2,1,2,2,2],\"conteo\":{\"bajo\":0,\"moderado\":5,\"alto\":8,\"total\":13},\"probabilidades\":{\"bajo\":0,\"moderado\":38.46153846153847,\"alto\":61.53846153846154},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-04 11:28:07', 1, '2025-02-04 11:28:07', 1, 0, NULL, NULL),
(15, 15, 1, 65.000, 1.680, 23.000, '2025-02-04 11:42:23', 'Alto', '61.538461538462', 1, '{\"entradas\":[0,0,1,2,2,2,2,0,2,1,2,2,2],\"conteo\":{\"bajo\":3,\"moderado\":2,\"alto\":8,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":15.384615384615385,\"alto\":61.53846153846154},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-04 11:42:23', 1, '2025-02-04 11:42:23', 1, 0, NULL, NULL),
(16, 16, 1, 59.000, 1.500, 26.200, '2025-02-04 12:10:59', 'Moderado', '53.846153846154', 1, '{\"entradas\":[1,1,1,2,1,2,2,0,1,1,2,1,0],\"conteo\":{\"bajo\":2,\"moderado\":7,\"alto\":4,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":53.84615384615385,\"alto\":30.76923076923077},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-04 12:10:59', 1, '2025-02-04 12:10:59', 1, 0, NULL, NULL),
(17, 17, 1, 72.000, 1.600, 28.100, '2025-02-04 12:45:03', 'Moderado', '69.230769230769', 1, '{\"entradas\":[0,1,1,1,1,2,1,0,1,1,1,2,1],\"conteo\":{\"bajo\":2,\"moderado\":9,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":69.23076923076923,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-04 12:45:03', 1, '2025-02-04 12:45:03', 1, 0, NULL, NULL),
(18, 18, 1, 68.000, 1.490, 30.600, '2025-02-05 10:15:01', 'Moderado/Alto', '38.461538461538', 1, '{\"entradas\":[1,2,1,2,2,0,1,0,2,1,2,1,0],\"conteo\":{\"bajo\":3,\"moderado\":5,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":38.46153846153847,\"alto\":38.46153846153847},\"clasificacion\":\"Moderado\\/Alto\",\"recomendaciones\":{\"moderado\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"],\"alto\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}}', '2025-02-05 10:15:01', 1, '2025-02-05 10:15:01', 1, 0, NULL, NULL),
(19, 19, 1, 76.000, 1.500, 33.800, '2025-02-05 10:53:18', 'Alto', '53.846153846154', 1, '{\"entradas\":[1,2,1,1,1,2,1,2,1,2,2,2,2],\"conteo\":{\"bajo\":0,\"moderado\":6,\"alto\":7,\"total\":13},\"probabilidades\":{\"bajo\":0,\"moderado\":46.15384615384615,\"alto\":53.84615384615385},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-05 10:53:18', 1, '2025-02-05 10:53:18', 1, 0, NULL, NULL),
(20, 20, 1, 64.000, 1.550, 26.600, '2025-02-05 11:14:59', 'Moderado', '53.846153846154', 1, '{\"entradas\":[0,1,2,1,1,0,2,0,1,1,2,1,1],\"conteo\":{\"bajo\":3,\"moderado\":7,\"alto\":3,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":53.84615384615385,\"alto\":23.076923076923077},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-05 11:14:59', 1, '2025-02-05 11:14:59', 1, 0, NULL, NULL),
(21, 21, 1, 97.000, 1.680, 34.400, '2025-02-05 11:57:05', 'Alto', '61.538461538462', 1, '{\"entradas\":[0,2,2,2,2,0,2,1,2,1,2,2,1],\"conteo\":{\"bajo\":2,\"moderado\":3,\"alto\":8,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":23.076923076923077,\"alto\":61.53846153846154},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-05 11:57:05', 1, '2025-02-05 11:57:05', 1, 0, NULL, NULL),
(22, 22, 1, 90.000, 1.700, 31.100, '2025-02-05 12:35:27', 'Moderado', '76.923076923077', 1, '{\"entradas\":[1,2,1,1,1,0,1,0,1,1,1,1,1],\"conteo\":{\"bajo\":2,\"moderado\":10,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":15.384615384615385,\"moderado\":76.92307692307693,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-05 12:35:27', 1, '2025-02-05 12:35:27', 1, 0, NULL, NULL),
(23, 23, 1, 83.000, 1.690, 29.100, '2025-02-05 12:49:33', 'Bajo', '53.846153846154', 1, '{\"entradas\":[2,1,1,1,1,0,0,0,0,0,0,1,0],\"conteo\":{\"bajo\":7,\"moderado\":5,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":53.84615384615385,\"moderado\":38.46153846153847,\"alto\":7.6923076923076925},\"clasificacion\":\"Bajo\",\"recomendaciones\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"]}', '2025-02-05 12:49:33', 1, '2025-02-05 12:49:33', 1, 0, NULL, NULL),
(24, 24, 1, 98.000, 1.730, 32.700, '2025-02-06 09:38:28', 'Moderado', '53.846153846154', 1, '{\"entradas\":[1,2,1,1,1,0,1,0,0,0,1,1,2],\"conteo\":{\"bajo\":4,\"moderado\":7,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":53.84615384615385,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-06 09:38:28', 1, '2025-02-06 09:38:28', 1, 0, NULL, NULL),
(25, 25, 1, 78.000, 1.690, 27.300, '2025-02-06 10:00:45', 'Moderado', '69.230769230769', 1, '{\"entradas\":[1,1,1,1,2,0,0,0,1,1,1,1,1],\"conteo\":{\"bajo\":3,\"moderado\":9,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":69.23076923076923,\"alto\":7.6923076923076925},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-06 10:00:45', 1, '2025-02-06 10:00:45', 1, 0, NULL, NULL),
(26, 26, 1, 74.000, 1.650, 27.200, '2025-02-06 10:45:59', 'Alto', '38.461538461538', 1, '{\"entradas\":[0,1,2,1,2,0,2,0,2,1,1,2,0],\"conteo\":{\"bajo\":4,\"moderado\":4,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":30.76923076923077,\"alto\":38.46153846153847},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-06 10:45:59', 1, '2025-02-06 10:45:59', 1, 0, NULL, NULL),
(27, 27, 1, 80.000, 1.670, 28.700, '2025-02-06 11:17:34', 'Moderado', '61.538461538462', 1, '{\"entradas\":[0,1,1,1,1,0,1,0,1,0,1,1,0],\"conteo\":{\"bajo\":5,\"moderado\":8,\"alto\":0,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":61.53846153846154,\"alto\":0},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-06 11:17:34', 1, '2025-02-06 11:17:34', 1, 0, NULL, NULL),
(28, 28, 1, 61.000, 1.520, 26.400, '2025-02-06 11:38:54', 'Moderado', '46.153846153846', 1, '{\"entradas\":[2,1,2,1,1,0,0,0,1,0,1,1,0],\"conteo\":{\"bajo\":5,\"moderado\":6,\"alto\":2,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":46.15384615384615,\"alto\":15.384615384615385},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-02-06 11:38:54', 1, '2025-02-06 11:38:54', 1, 0, NULL, NULL),
(29, 29, 1, 70.000, 1.620, 26.700, '2025-02-06 11:50:34', 'Alto', '46.153846153846', 1, '{\"entradas\":[0,1,2,2,2,2,0,0,2,1,2,1,0],\"conteo\":{\"bajo\":4,\"moderado\":3,\"alto\":6,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":23.076923076923077,\"alto\":46.15384615384615},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-02-06 11:50:34', 1, '2025-02-06 11:50:34', 1, 0, NULL, NULL),
(30, 30, 1, 53.000, 1.550, 22.100, '2025-02-06 12:20:44', 'Bajo/Moderado', '38.461538461538', 1, '{\"entradas\":[0,0,1,1,2,2,1,0,1,0,1,0,2],\"conteo\":{\"bajo\":5,\"moderado\":5,\"alto\":3,\"total\":13},\"probabilidades\":{\"bajo\":38.46153846153847,\"moderado\":38.46153846153847,\"alto\":23.076923076923077},\"clasificacion\":\"Bajo\\/Moderado\",\"recomendaciones\":{\"bajo\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"],\"moderado\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}}', '2025-02-06 12:20:44', 1, '2025-02-06 12:20:44', 1, 0, NULL, NULL),
(31, 30, 1, 53.000, 155.000, 22.100, '2025-03-13 18:55:06', 'Moderado', '46.153846153846', 1, '{\"entradas\":[0,0,1,1,2,2,1,1,1,0,1,0,2],\"conteo\":{\"bajo\":4,\"moderado\":6,\"alto\":3,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":46.15384615384615,\"alto\":23.076923076923077},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-03-13 18:55:06', 1, '2025-03-13 18:55:06', 1, 0, NULL, NULL),
(49, 32, 2, 72.000, 169.000, 25.200, '2025-03-15 00:49:49', 'Moderado', '53.846153846154', 1, '{\"entradas\":[0,1,0,1,1,0,1,2,1,2,1,2,1],\"conteo\":{\"bajo\":3,\"moderado\":7,\"alto\":3,\"total\":13},\"probabilidades\":{\"bajo\":23.076923076923077,\"moderado\":53.84615384615385,\"alto\":23.076923076923077},\"clasificacion\":\"Moderado\",\"recomendaciones\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}', '2025-03-15 00:49:49', 2, '2025-03-15 00:49:49', 1, 0, NULL, NULL),
(50, 32, 2, 72.000, 169.000, 25.200, '2025-03-19 10:04:27', 'Bajo/Moderado', '46.153846153846', 1, '{\"entradas\":[0,1,0,1,0,0,0,1,1,0,2,1,1],\"conteo\":{\"bajo\":6,\"moderado\":6,\"alto\":1,\"total\":13},\"probabilidades\":{\"bajo\":46.15384615384615,\"moderado\":46.15384615384615,\"alto\":7.6923076923076925},\"clasificacion\":\"Bajo\\/Moderado\",\"recomendaciones\":{\"bajo\":[\"Continuar con h\\u00e1bitos saludables\",\"Monitoreo rutinario de glucosa en chequeos anuales\"],\"moderado\":[\"Consulta m\\u00e9dica para evaluaci\\u00f3n preventiva\",\"Revisar h\\u00e1bitos alimenticios y actividad f\\u00edsica\",\"Considerar pruebas espec\\u00edficas de glucosa\"]}}', '2025-03-19 10:04:27', 2, '2025-03-19 10:04:27', 1, 0, NULL, NULL),
(51, 32, 2, 72.000, 169.000, 25.200, '2025-03-19 10:27:10', 'Alto', '38.461538461538', 1, '{\"entradas\":[0,1,2,2,1,2,1,0,0,1,2,2,0],\"conteo\":{\"bajo\":4,\"moderado\":4,\"alto\":5,\"total\":13},\"probabilidades\":{\"bajo\":30.76923076923077,\"moderado\":30.76923076923077,\"alto\":38.46153846153847},\"clasificacion\":\"Alto\",\"recomendaciones\":[\"Consulta m\\u00e9dica inmediata\",\"Pruebas de laboratorio para evaluar niveles de glucosa\",\"Posible derivaci\\u00f3n a especialista en endocrinolog\\u00eda\"]}', '2025-03-19 10:27:10', 2, '2025-03-19 10:27:11', 1, 0, NULL, NULL);

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
(1, 1, 1, 1, 'Casi Nunca', '2025-03-10 13:00:53'),
(2, 1, 2, 8, 'Poco', '2025-03-10 13:00:53'),
(3, 1, 3, 12, 'Mucho', '2025-03-10 13:00:53'),
(4, 1, 4, 13, 'No', '2025-03-10 13:00:53'),
(5, 1, 5, 16, 'Sí pero ocasionalmente', '2025-03-10 13:00:53'),
(6, 1, 6, 20, 'Sí siempre', '2025-03-10 13:00:53'),
(7, 1, 7, 21, 'Nada', '2025-03-10 13:00:53'),
(8, 1, 8, 28, 'Poco', '2025-03-10 13:00:53'),
(9, 1, 9, 41, 'Mucho', '2025-03-10 13:00:53'),
(10, 1, 10, 42, 'No', '2025-03-10 13:00:53'),
(11, 1, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-10 13:00:53'),
(12, 2, 1, 2, 'A veces', '2025-03-10 14:12:09'),
(13, 2, 2, 8, 'Poco', '2025-03-10 14:12:09'),
(14, 2, 3, 12, 'Mucho', '2025-03-10 14:12:09'),
(15, 2, 4, 14, 'Si', '2025-03-10 14:12:09'),
(16, 2, 5, 16, 'Sí pero ocasionalmente', '2025-03-10 14:12:09'),
(17, 2, 6, 18, 'No', '2025-03-10 14:12:09'),
(18, 2, 7, 22, 'Poco', '2025-03-10 14:12:09'),
(19, 2, 8, 27, 'Nada', '2025-03-10 14:12:09'),
(20, 2, 9, 40, 'Poco', '2025-03-10 14:12:09'),
(21, 2, 10, 44, 'Si', '2025-03-10 14:12:09'),
(22, 2, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-10 14:12:09'),
(23, 3, 1, 2, 'A veces', '2025-03-10 14:17:18'),
(24, 3, 2, 9, 'Mucho', '2025-03-10 14:17:18'),
(25, 3, 3, 12, 'Mucho', '2025-03-10 14:17:18'),
(26, 3, 4, 13, 'No', '2025-03-10 14:17:18'),
(27, 3, 5, 15, 'No', '2025-03-10 14:17:18'),
(28, 3, 6, 18, 'No', '2025-03-10 14:17:18'),
(29, 3, 7, 22, 'Poco', '2025-03-10 14:17:18'),
(30, 3, 8, 27, 'Nada', '2025-03-10 14:17:18'),
(31, 3, 9, 39, 'Nada', '2025-03-10 14:17:18'),
(32, 3, 10, 42, 'No', '2025-03-10 14:17:18'),
(33, 3, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-10 14:17:18'),
(34, 1, 1, 2, 'A veces', '2025-03-11 15:21:23'),
(35, 1, 2, 8, 'Poco', '2025-03-11 15:21:23'),
(36, 1, 3, 11, 'Poco', '2025-03-11 15:21:23'),
(37, 1, 4, 13, 'No', '2025-03-11 15:21:23'),
(38, 1, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 15:21:23'),
(39, 1, 6, 18, 'No', '2025-03-11 15:21:23'),
(40, 1, 7, 22, 'Poco', '2025-03-11 15:21:23'),
(41, 1, 8, 28, 'Poco', '2025-03-11 15:21:23'),
(42, 1, 9, 39, 'Nada', '2025-03-11 15:21:24'),
(43, 1, 10, 43, 'Regularmente', '2025-03-11 15:21:24'),
(44, 1, 11, 36, 'No', '2025-03-11 15:21:24'),
(45, 2, 1, 3, 'Casi Siempre', '2025-03-11 16:44:00'),
(46, 2, 2, 9, 'Mucho', '2025-03-11 16:44:00'),
(47, 2, 3, 11, 'Poco', '2025-03-11 16:44:00'),
(48, 2, 4, 13, 'No', '2025-03-11 16:44:00'),
(49, 2, 5, 17, 'Sí de forma constante', '2025-03-11 16:44:01'),
(50, 2, 6, 18, 'No', '2025-03-11 16:44:01'),
(51, 2, 7, 22, 'Poco', '2025-03-11 16:44:01'),
(52, 2, 8, 27, 'Nada', '2025-03-11 16:44:01'),
(53, 2, 9, 39, 'Nada', '2025-03-11 16:44:01'),
(54, 2, 10, 42, 'No', '2025-03-11 16:44:01'),
(55, 2, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-11 16:44:01'),
(56, 3, 1, 2, 'A veces', '2025-03-11 16:45:44'),
(57, 3, 2, 8, 'Poco', '2025-03-11 16:45:44'),
(58, 3, 3, 11, 'Poco', '2025-03-11 16:45:44'),
(59, 3, 4, 13, 'No', '2025-03-11 16:45:44'),
(60, 3, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:45:44'),
(61, 3, 6, 18, 'No', '2025-03-11 16:45:44'),
(62, 3, 7, 22, 'Poco', '2025-03-11 16:45:44'),
(63, 3, 8, 28, 'Poco', '2025-03-11 16:45:44'),
(64, 3, 9, 39, 'Nada', '2025-03-11 16:45:44'),
(65, 3, 10, 43, 'Regularmente', '2025-03-11 16:45:44'),
(66, 3, 11, 36, 'No', '2025-03-11 16:45:45'),
(67, 4, 1, 3, 'Casi Siempre', '2025-03-11 16:46:40'),
(68, 4, 2, 8, 'Poco', '2025-03-11 16:46:40'),
(69, 4, 3, 11, 'Poco', '2025-03-11 16:46:40'),
(70, 4, 4, 13, 'No', '2025-03-11 16:46:40'),
(71, 4, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:46:40'),
(72, 4, 6, 19, 'Sí ocasionalmente', '2025-03-11 16:46:40'),
(73, 4, 7, 22, 'Poco', '2025-03-11 16:46:40'),
(74, 4, 8, 28, 'Poco', '2025-03-11 16:46:40'),
(75, 4, 9, 40, 'Poco', '2025-03-11 16:46:40'),
(76, 4, 10, 43, 'Regularmente', '2025-03-11 16:46:40'),
(77, 4, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 16:46:40'),
(78, 5, 1, 1, 'Casi Nunca', '2025-03-11 16:48:38'),
(79, 5, 2, 8, 'Poco', '2025-03-11 16:48:38'),
(80, 5, 3, 11, 'Poco', '2025-03-11 16:48:38'),
(81, 5, 4, 14, 'Si', '2025-03-11 16:48:38'),
(82, 5, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:48:38'),
(83, 5, 6, 19, 'Sí ocasionalmente', '2025-03-11 16:48:38'),
(84, 5, 7, 22, 'Poco', '2025-03-11 16:48:38'),
(85, 5, 8, 28, 'Poco', '2025-03-11 16:48:38'),
(86, 5, 9, 40, 'Poco', '2025-03-11 16:48:38'),
(87, 5, 10, 43, 'Regularmente', '2025-03-11 16:48:38'),
(88, 5, 11, 36, 'No', '2025-03-11 16:48:38'),
(89, 6, 1, 1, 'Casi Nunca', '2025-03-11 16:49:46'),
(90, 6, 2, 8, 'Poco', '2025-03-11 16:49:46'),
(91, 6, 3, 10, 'Nada', '2025-03-11 16:49:46'),
(92, 6, 4, 13, 'No', '2025-03-11 16:49:46'),
(93, 6, 5, 15, 'No', '2025-03-11 16:49:46'),
(94, 6, 6, 18, 'No', '2025-03-11 16:49:46'),
(95, 6, 7, 22, 'Poco', '2025-03-11 16:49:46'),
(96, 6, 8, 27, 'Nada', '2025-03-11 16:49:46'),
(97, 6, 9, 41, 'Mucho', '2025-03-11 16:49:46'),
(98, 6, 10, 43, 'Regularmente', '2025-03-11 16:49:46'),
(99, 6, 11, 36, 'No', '2025-03-11 16:49:46'),
(100, 7, 1, 2, 'A veces', '2025-03-11 16:51:16'),
(101, 7, 2, 9, 'Mucho', '2025-03-11 16:51:16'),
(102, 7, 3, 12, 'Mucho', '2025-03-11 16:51:16'),
(103, 7, 4, 14, 'Si', '2025-03-11 16:51:16'),
(104, 7, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:51:16'),
(105, 7, 6, 18, 'No', '2025-03-11 16:51:16'),
(106, 7, 7, 23, 'Mucho', '2025-03-11 16:51:16'),
(107, 7, 8, 28, 'Poco', '2025-03-11 16:51:16'),
(108, 7, 9, 40, 'Poco', '2025-03-11 16:51:16'),
(109, 7, 10, 43, 'Regularmente', '2025-03-11 16:51:16'),
(110, 7, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 16:51:16'),
(111, 8, 1, 2, 'A veces', '2025-03-11 16:52:15'),
(112, 8, 2, 8, 'Poco', '2025-03-11 16:52:15'),
(113, 8, 3, 11, 'Poco', '2025-03-11 16:52:15'),
(114, 8, 4, 13, 'No', '2025-03-11 16:52:15'),
(115, 8, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:52:15'),
(116, 8, 6, 18, 'No', '2025-03-11 16:52:15'),
(117, 8, 7, 22, 'Poco', '2025-03-11 16:52:16'),
(118, 8, 8, 28, 'Poco', '2025-03-11 16:52:16'),
(119, 8, 9, 40, 'Poco', '2025-03-11 16:52:16'),
(120, 8, 10, 43, 'Regularmente', '2025-03-11 16:52:16'),
(121, 8, 11, 36, 'No', '2025-03-11 16:52:16'),
(122, 9, 1, 3, 'Casi Siempre', '2025-03-11 16:54:40'),
(123, 9, 2, 9, 'Mucho', '2025-03-11 16:54:40'),
(124, 9, 3, 12, 'Mucho', '2025-03-11 16:54:40'),
(125, 9, 4, 14, 'Si', '2025-03-11 16:54:40'),
(126, 9, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:54:40'),
(127, 9, 6, 19, 'Sí ocasionalmente', '2025-03-11 16:54:40'),
(128, 9, 7, 23, 'Mucho', '2025-03-11 16:54:40'),
(129, 9, 8, 28, 'Poco', '2025-03-11 16:54:40'),
(130, 9, 9, 41, 'Mucho', '2025-03-11 16:54:40'),
(131, 9, 10, 42, 'No', '2025-03-11 16:54:40'),
(132, 9, 11, 36, 'No', '2025-03-11 16:54:40'),
(133, 10, 1, 2, 'A veces', '2025-03-11 16:56:58'),
(134, 10, 2, 8, 'Poco', '2025-03-11 16:56:58'),
(135, 10, 3, 12, 'Mucho', '2025-03-11 16:56:58'),
(136, 10, 4, 14, 'Si', '2025-03-11 16:56:58'),
(137, 10, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:56:58'),
(138, 10, 6, 19, 'Sí ocasionalmente', '2025-03-11 16:56:58'),
(139, 10, 7, 22, 'Poco', '2025-03-11 16:56:58'),
(140, 10, 8, 28, 'Poco', '2025-03-11 16:56:59'),
(141, 10, 9, 40, 'Poco', '2025-03-11 16:56:59'),
(142, 10, 10, 44, 'Si', '2025-03-11 16:56:59'),
(143, 10, 11, 36, 'No', '2025-03-11 16:56:59'),
(144, 11, 1, 2, 'A veces', '2025-03-11 16:58:56'),
(145, 11, 2, 8, 'Poco', '2025-03-11 16:58:56'),
(146, 11, 3, 12, 'Mucho', '2025-03-11 16:58:56'),
(147, 11, 4, 14, 'Si', '2025-03-11 16:58:56'),
(148, 11, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 16:58:56'),
(149, 11, 6, 19, 'Sí ocasionalmente', '2025-03-11 16:58:56'),
(150, 11, 7, 22, 'Poco', '2025-03-11 16:58:56'),
(151, 11, 8, 28, 'Poco', '2025-03-11 16:58:56'),
(152, 11, 9, 40, 'Poco', '2025-03-11 16:58:56'),
(153, 11, 10, 44, 'Si', '2025-03-11 16:58:56'),
(154, 11, 11, 36, 'No', '2025-03-11 16:58:56'),
(155, 12, 1, 3, 'Casi Siempre', '2025-03-11 17:00:25'),
(156, 12, 2, 9, 'Mucho', '2025-03-11 17:00:25'),
(157, 12, 3, 12, 'Mucho', '2025-03-11 17:00:25'),
(158, 12, 4, 13, 'No', '2025-03-11 17:00:25'),
(159, 12, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 17:00:25'),
(160, 12, 6, 18, 'No', '2025-03-11 17:00:25'),
(161, 12, 7, 23, 'Mucho', '2025-03-11 17:00:25'),
(162, 12, 8, 28, 'Poco', '2025-03-11 17:00:25'),
(163, 12, 9, 39, 'Nada', '2025-03-11 17:00:25'),
(164, 12, 10, 42, 'No', '2025-03-11 17:00:25'),
(165, 12, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 17:00:25'),
(166, 13, 1, 3, 'Casi Siempre', '2025-03-11 17:02:08'),
(167, 13, 2, 9, 'Mucho', '2025-03-11 17:02:08'),
(168, 13, 3, 12, 'Mucho', '2025-03-11 17:02:08'),
(169, 13, 4, 14, 'Si', '2025-03-11 17:02:08'),
(170, 13, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 17:02:08'),
(171, 13, 6, 18, 'No', '2025-03-11 17:02:08'),
(172, 13, 7, 22, 'Poco', '2025-03-11 17:02:08'),
(173, 13, 8, 28, 'Poco', '2025-03-11 17:02:08'),
(174, 13, 9, 39, 'Nada', '2025-03-11 17:02:08'),
(175, 13, 10, 42, 'No', '2025-03-11 17:02:08'),
(176, 13, 11, 36, 'No', '2025-03-11 17:02:08'),
(177, 14, 1, 2, 'A veces', '2025-03-11 17:08:07'),
(178, 14, 2, 9, 'Mucho', '2025-03-11 17:08:07'),
(179, 14, 3, 12, 'Mucho', '2025-03-11 17:08:07'),
(180, 14, 4, 14, 'Si', '2025-03-11 17:08:07'),
(181, 14, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 17:08:07'),
(182, 14, 6, 19, 'Sí ocasionalmente', '2025-03-11 17:08:07'),
(183, 14, 7, 23, 'Mucho', '2025-03-11 17:08:07'),
(184, 14, 8, 28, 'Poco', '2025-03-11 17:08:07'),
(185, 14, 9, 39, 'Nada', '2025-03-11 17:08:07'),
(186, 14, 10, 42, 'No', '2025-03-11 17:08:07'),
(187, 14, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-11 17:08:07'),
(188, 15, 1, 2, 'A veces', '2025-03-11 20:42:23'),
(189, 15, 2, 9, 'Mucho', '2025-03-11 20:42:23'),
(190, 15, 3, 12, 'Mucho', '2025-03-11 20:42:23'),
(191, 15, 4, 14, 'Si', '2025-03-11 20:42:23'),
(192, 15, 5, 17, 'Sí de forma constante', '2025-03-11 20:42:23'),
(193, 15, 6, 18, 'No', '2025-03-11 20:42:23'),
(194, 15, 7, 23, 'Mucho', '2025-03-11 20:42:23'),
(195, 15, 8, 28, 'Poco', '2025-03-11 20:42:23'),
(196, 15, 9, 39, 'Nada', '2025-03-11 20:42:23'),
(197, 15, 10, 42, 'No', '2025-03-11 20:42:23'),
(198, 15, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-11 20:42:23'),
(199, 16, 1, 2, 'A veces', '2025-03-11 20:43:59'),
(200, 16, 2, 9, 'Mucho', '2025-03-11 20:43:59'),
(201, 16, 3, 11, 'Poco', '2025-03-11 20:43:59'),
(202, 16, 4, 14, 'Si', '2025-03-11 20:43:59'),
(203, 16, 5, 17, 'Sí de forma constante', '2025-03-11 20:43:59'),
(204, 16, 6, 18, 'No', '2025-03-11 20:43:59'),
(205, 16, 7, 22, 'Poco', '2025-03-11 20:43:59'),
(206, 16, 8, 28, 'Poco', '2025-03-11 20:43:59'),
(207, 16, 9, 39, 'Nada', '2025-03-11 20:43:59'),
(208, 16, 10, 43, 'Regularmente', '2025-03-11 20:43:59'),
(209, 16, 11, 36, 'No', '2025-03-11 20:43:59'),
(210, 17, 1, 2, 'A veces', '2025-03-11 20:45:03'),
(211, 17, 2, 8, 'Poco', '2025-03-11 20:45:03'),
(212, 17, 3, 11, 'Poco', '2025-03-11 20:45:03'),
(213, 17, 4, 14, 'Si', '2025-03-11 20:45:03'),
(214, 17, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 20:45:03'),
(215, 17, 6, 18, 'No', '2025-03-11 20:45:03'),
(216, 17, 7, 22, 'Poco', '2025-03-11 20:45:03'),
(217, 17, 8, 28, 'Poco', '2025-03-11 20:45:03'),
(218, 17, 9, 40, 'Poco', '2025-03-11 20:45:03'),
(219, 17, 10, 42, 'No', '2025-03-11 20:45:03'),
(220, 17, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 20:45:03'),
(221, 18, 1, 2, 'A veces', '2025-03-11 20:46:01'),
(222, 18, 2, 9, 'Mucho', '2025-03-11 20:46:01'),
(223, 18, 3, 12, 'Mucho', '2025-03-11 20:46:01'),
(224, 18, 4, 13, 'No', '2025-03-11 20:46:01'),
(225, 18, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 20:46:01'),
(226, 18, 6, 18, 'No', '2025-03-11 20:46:01'),
(227, 18, 7, 23, 'Mucho', '2025-03-11 20:46:01'),
(228, 18, 8, 28, 'Poco', '2025-03-11 20:46:01'),
(229, 18, 9, 39, 'Nada', '2025-03-11 20:46:01'),
(230, 18, 10, 43, 'Regularmente', '2025-03-11 20:46:01'),
(231, 18, 11, 36, 'No', '2025-03-11 20:46:01'),
(232, 19, 1, 2, 'A veces', '2025-03-11 20:53:18'),
(233, 19, 2, 8, 'Poco', '2025-03-11 20:53:18'),
(234, 19, 3, 11, 'Poco', '2025-03-11 20:53:18'),
(235, 19, 4, 14, 'Si', '2025-03-11 20:53:18'),
(236, 19, 5, 16, 'Sí pero ocasionalmente', '2025-03-11 20:53:18'),
(237, 19, 6, 20, 'Sí siempre', '2025-03-11 20:53:18'),
(238, 19, 7, 22, 'Poco', '2025-03-11 20:53:18'),
(239, 19, 8, 29, 'Mucho', '2025-03-11 20:53:18'),
(240, 19, 9, 39, 'Nada', '2025-03-11 20:53:18'),
(241, 19, 10, 42, 'No', '2025-03-11 20:53:18'),
(242, 19, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-11 20:53:18'),
(243, 20, 1, 3, 'Casi Siempre', '2025-03-11 20:54:59'),
(244, 20, 2, 8, 'Poco', '2025-03-11 20:54:59'),
(245, 20, 3, 11, 'Poco', '2025-03-11 20:54:59'),
(246, 20, 4, 13, 'No', '2025-03-11 20:54:59'),
(247, 20, 5, 17, 'Sí de forma constante', '2025-03-11 20:54:59'),
(248, 20, 6, 18, 'No', '2025-03-11 20:54:59'),
(249, 20, 7, 22, 'Poco', '2025-03-11 20:54:59'),
(250, 20, 8, 28, 'Poco', '2025-03-11 20:54:59'),
(251, 20, 9, 39, 'Nada', '2025-03-11 20:54:59'),
(252, 20, 10, 43, 'Regularmente', '2025-03-11 20:54:59'),
(253, 20, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 20:54:59'),
(254, 21, 1, 3, 'Casi Siempre', '2025-03-11 20:57:05'),
(255, 21, 2, 9, 'Mucho', '2025-03-11 20:57:05'),
(256, 21, 3, 12, 'Mucho', '2025-03-11 20:57:05'),
(257, 21, 4, 13, 'No', '2025-03-11 20:57:05'),
(258, 21, 5, 17, 'Sí de forma constante', '2025-03-11 20:57:05'),
(259, 21, 6, 19, 'Sí ocasionalmente', '2025-03-11 20:57:05'),
(260, 21, 7, 23, 'Mucho', '2025-03-11 20:57:05'),
(261, 21, 8, 28, 'Poco', '2025-03-11 20:57:05'),
(262, 21, 9, 39, 'Nada', '2025-03-11 20:57:05'),
(263, 21, 10, 42, 'No', '2025-03-11 20:57:05'),
(264, 21, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-11 20:57:05'),
(265, 22, 1, 2, 'A veces', '2025-03-12 09:35:27'),
(266, 22, 2, 8, 'Poco', '2025-03-12 09:35:27'),
(267, 22, 3, 11, 'Poco', '2025-03-12 09:35:27'),
(268, 22, 4, 13, 'No', '2025-03-12 09:35:27'),
(269, 22, 5, 16, 'Sí pero ocasionalmente', '2025-03-12 09:35:27'),
(270, 22, 6, 18, 'No', '2025-03-12 09:35:27'),
(271, 22, 7, 22, 'Poco', '2025-03-12 09:35:27'),
(272, 22, 8, 28, 'Poco', '2025-03-12 09:35:27'),
(273, 22, 9, 40, 'Poco', '2025-03-12 09:35:27'),
(274, 22, 10, 43, 'Regularmente', '2025-03-12 09:35:27'),
(275, 22, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-12 09:35:27'),
(276, 23, 1, 2, 'A veces', '2025-03-12 09:37:33'),
(277, 23, 2, 8, 'Poco', '2025-03-12 09:37:33'),
(278, 23, 3, 11, 'Poco', '2025-03-12 09:37:33'),
(279, 23, 4, 13, 'No', '2025-03-12 09:37:33'),
(280, 23, 5, 15, 'No', '2025-03-12 09:37:33'),
(281, 23, 6, 18, 'No', '2025-03-12 09:37:33'),
(282, 23, 7, 21, 'Nada', '2025-03-12 09:37:33'),
(283, 23, 8, 27, 'Nada', '2025-03-12 09:37:33'),
(284, 23, 9, 41, 'Mucho', '2025-03-12 09:37:33'),
(285, 23, 10, 43, 'Regularmente', '2025-03-12 09:37:33'),
(286, 23, 11, 36, 'No', '2025-03-12 09:37:33'),
(287, 24, 1, 2, 'A veces', '2025-03-12 09:38:28'),
(288, 24, 2, 8, 'Poco', '2025-03-12 09:38:28'),
(289, 24, 3, 11, 'Poco', '2025-03-12 09:38:28'),
(290, 24, 4, 13, 'No', '2025-03-12 09:38:28'),
(291, 24, 5, 16, 'Sí pero ocasionalmente', '2025-03-12 09:38:28'),
(292, 24, 6, 18, 'No', '2025-03-12 09:38:28'),
(293, 24, 7, 21, 'Nada', '2025-03-12 09:38:28'),
(294, 24, 8, 27, 'Nada', '2025-03-12 09:38:28'),
(295, 24, 9, 40, 'Poco', '2025-03-12 09:38:28'),
(296, 24, 10, 43, 'Regularmente', '2025-03-12 09:38:28'),
(297, 24, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-12 09:38:28'),
(298, 25, 1, 2, 'A veces', '2025-03-12 09:39:45'),
(299, 25, 2, 8, 'Poco', '2025-03-12 09:39:45'),
(300, 25, 3, 12, 'Mucho', '2025-03-12 09:39:45'),
(301, 25, 4, 13, 'No', '2025-03-12 09:39:45'),
(302, 25, 5, 15, 'No', '2025-03-12 09:39:45'),
(303, 25, 6, 18, 'No', '2025-03-12 09:39:45'),
(304, 25, 7, 22, 'Poco', '2025-03-12 09:39:45'),
(305, 25, 8, 28, 'Poco', '2025-03-12 09:39:45'),
(306, 25, 9, 40, 'Poco', '2025-03-12 09:39:45'),
(307, 25, 10, 43, 'Regularmente', '2025-03-12 09:39:45'),
(308, 25, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-12 09:39:45'),
(309, 26, 1, 3, 'Casi Siempre', '2025-03-12 21:45:59'),
(310, 26, 2, 8, 'Poco', '2025-03-12 21:45:59'),
(311, 26, 3, 12, 'Mucho', '2025-03-12 21:45:59'),
(312, 26, 4, 13, 'No', '2025-03-12 21:45:59'),
(313, 26, 5, 17, 'Sí de forma constante', '2025-03-12 21:45:59'),
(314, 26, 6, 18, 'No', '2025-03-12 21:45:59'),
(315, 26, 7, 23, 'Mucho', '2025-03-12 21:45:59'),
(316, 26, 8, 28, 'Poco', '2025-03-12 21:45:59'),
(317, 26, 9, 40, 'Poco', '2025-03-12 21:45:59'),
(318, 26, 10, 42, 'No', '2025-03-12 21:45:59'),
(319, 26, 11, 36, 'No', '2025-03-12 21:45:59'),
(320, 27, 1, 2, 'A veces', '2025-03-12 21:47:34'),
(321, 27, 2, 8, 'Poco', '2025-03-12 21:47:34'),
(322, 27, 3, 11, 'Poco', '2025-03-12 21:47:34'),
(323, 27, 4, 13, 'No', '2025-03-12 21:47:34'),
(324, 27, 5, 16, 'Sí pero ocasionalmente', '2025-03-12 21:47:34'),
(325, 27, 6, 18, 'No', '2025-03-12 21:47:34'),
(326, 27, 7, 22, 'Poco', '2025-03-12 21:47:34'),
(327, 27, 8, 27, 'Nada', '2025-03-12 21:47:34'),
(328, 27, 9, 40, 'Poco', '2025-03-12 21:47:34'),
(329, 27, 10, 43, 'Regularmente', '2025-03-12 21:47:34'),
(330, 27, 11, 36, 'No', '2025-03-12 21:47:34'),
(331, 28, 1, 3, 'Casi Siempre', '2025-03-12 21:48:54'),
(332, 28, 2, 8, 'Poco', '2025-03-12 21:48:54'),
(333, 28, 3, 11, 'Poco', '2025-03-12 21:48:54'),
(334, 28, 4, 13, 'No', '2025-03-12 21:48:54'),
(335, 28, 5, 15, 'No', '2025-03-12 21:48:54'),
(336, 28, 6, 18, 'No', '2025-03-12 21:48:54'),
(337, 28, 7, 22, 'Poco', '2025-03-12 21:48:54'),
(338, 28, 8, 27, 'Nada', '2025-03-12 21:48:54'),
(339, 28, 9, 40, 'Poco', '2025-03-12 21:48:54'),
(340, 28, 10, 43, 'Regularmente', '2025-03-12 21:48:54'),
(341, 28, 11, 36, 'No', '2025-03-12 21:48:54'),
(342, 29, 1, 3, 'Casi Siempre', '2025-03-12 21:50:34'),
(343, 29, 2, 9, 'Mucho', '2025-03-12 21:50:34'),
(344, 29, 3, 12, 'Mucho', '2025-03-12 21:50:34'),
(345, 29, 4, 14, 'Si', '2025-03-12 21:50:34'),
(346, 29, 5, 15, 'No', '2025-03-12 21:50:34'),
(347, 29, 6, 18, 'No', '2025-03-12 21:50:34'),
(348, 29, 7, 23, 'Mucho', '2025-03-12 21:50:34'),
(349, 29, 8, 28, 'Poco', '2025-03-12 21:50:34'),
(350, 29, 9, 39, 'Nada', '2025-03-12 21:50:34'),
(351, 29, 10, 43, 'Regularmente', '2025-03-12 21:50:34'),
(352, 29, 11, 36, 'No', '2025-03-12 21:50:34'),
(353, 30, 1, 2, 'A veces', '2025-03-12 21:51:44'),
(354, 30, 2, 8, 'Poco', '2025-03-12 21:51:44'),
(355, 30, 3, 12, 'Mucho', '2025-03-12 21:51:44'),
(356, 30, 4, 14, 'Si', '2025-03-12 21:51:44'),
(357, 30, 5, 16, 'Sí pero ocasionalmente', '2025-03-12 21:51:44'),
(358, 30, 6, 18, 'No', '2025-03-12 21:51:44'),
(359, 30, 7, 22, 'Poco', '2025-03-12 21:51:44'),
(360, 30, 8, 27, 'Nada', '2025-03-12 21:51:44'),
(361, 30, 9, 40, 'Poco', '2025-03-12 21:51:44'),
(362, 30, 10, 44, 'Si', '2025-03-12 21:51:44'),
(363, 30, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-12 21:51:44'),
(364, 31, 1, 2, 'A veces', '2025-03-13 18:55:06'),
(365, 31, 2, 8, 'Poco', '2025-03-13 18:55:06'),
(366, 31, 3, 12, 'Mucho', '2025-03-13 18:55:06'),
(367, 31, 4, 14, 'Si', '2025-03-13 18:55:06'),
(368, 31, 5, 16, 'Sí pero ocasionalmente', '2025-03-13 18:55:06'),
(369, 31, 6, 19, 'Sí ocasionalmente', '2025-03-13 18:55:06'),
(370, 31, 7, 22, 'Poco', '2025-03-13 18:55:06'),
(371, 31, 8, 27, 'Nada', '2025-03-13 18:55:06'),
(372, 31, 9, 40, 'Poco', '2025-03-13 18:55:06'),
(373, 31, 10, 44, 'Si', '2025-03-13 18:55:06'),
(374, 31, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-13 18:55:06'),
(375, 32, 1, 2, 'A veces', '2025-03-14 14:17:44'),
(376, 32, 2, 8, 'Poco', '2025-03-14 14:17:44'),
(377, 32, 3, 12, 'Mucho', '2025-03-14 14:17:44'),
(378, 32, 4, 14, 'Si', '2025-03-14 14:17:44'),
(379, 32, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 14:17:44'),
(380, 32, 6, 19, 'Sí ocasionalmente', '2025-03-14 14:17:44'),
(381, 32, 7, 22, 'Poco', '2025-03-14 14:17:44'),
(382, 32, 8, 27, 'Nada', '2025-03-14 14:17:44'),
(383, 32, 9, 40, 'Poco', '2025-03-14 14:17:44'),
(384, 32, 10, 42, 'No', '2025-03-14 14:17:44'),
(385, 32, 11, 38, 'Si (primer grado de consanguinidad): padres e hijos', '2025-03-14 14:17:44'),
(386, 33, 1, 3, 'Casi Siempre', '2025-03-14 14:22:41'),
(387, 33, 2, 9, 'Mucho', '2025-03-14 14:22:41'),
(388, 33, 3, 12, 'Mucho', '2025-03-14 14:22:41'),
(389, 33, 4, 14, 'Si', '2025-03-14 14:22:41'),
(390, 33, 5, 15, 'No', '2025-03-14 14:22:41'),
(391, 33, 6, 18, 'No', '2025-03-14 14:22:41'),
(392, 33, 7, 23, 'Mucho', '2025-03-14 14:22:41'),
(393, 33, 8, 28, 'Poco', '2025-03-14 14:22:41'),
(394, 33, 9, 41, 'Mucho', '2025-03-14 14:22:41'),
(395, 33, 10, 43, 'Regularmente', '2025-03-14 14:22:41'),
(396, 33, 11, 36, 'No', '2025-03-14 14:22:41'),
(397, 34, 1, 1, 'Casi Nunca', '2025-03-14 14:23:10'),
(398, 34, 2, 8, 'Poco', '2025-03-14 14:23:10'),
(399, 34, 3, 12, 'Mucho', '2025-03-14 14:23:10'),
(400, 34, 4, 14, 'Si', '2025-03-14 14:23:10'),
(401, 34, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 14:23:10'),
(402, 34, 6, 20, 'Sí siempre', '2025-03-14 14:23:10'),
(403, 34, 7, 21, 'Nada', '2025-03-14 14:23:10'),
(404, 34, 8, 27, 'Nada', '2025-03-14 14:23:10'),
(405, 34, 9, 40, 'Poco', '2025-03-14 14:23:10'),
(406, 34, 10, 42, 'No', '2025-03-14 14:23:10'),
(407, 34, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 14:23:10'),
(408, 35, 1, 3, 'Casi Siempre', '2025-03-14 14:23:41'),
(409, 35, 2, 9, 'Mucho', '2025-03-14 14:23:41'),
(410, 35, 3, 12, 'Mucho', '2025-03-14 14:23:41'),
(411, 35, 4, 14, 'Si', '2025-03-14 14:23:41'),
(412, 35, 5, 17, 'Sí de forma constante', '2025-03-14 14:23:41'),
(413, 35, 6, 18, 'No', '2025-03-14 14:23:41'),
(414, 35, 7, 21, 'Nada', '2025-03-14 14:23:41'),
(415, 35, 8, 27, 'Nada', '2025-03-14 14:23:41'),
(416, 35, 9, 40, 'Poco', '2025-03-14 14:23:41'),
(417, 35, 10, 43, 'Regularmente', '2025-03-14 14:23:41'),
(418, 35, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 14:23:41'),
(419, 36, 1, 2, 'A veces', '2025-03-14 14:39:04'),
(420, 36, 2, 8, 'Poco', '2025-03-14 14:39:04'),
(421, 36, 3, 11, 'Poco', '2025-03-14 14:39:04'),
(422, 36, 4, 13, 'No', '2025-03-14 14:39:04'),
(423, 36, 5, 15, 'No', '2025-03-14 14:39:04'),
(424, 36, 6, 18, 'No', '2025-03-14 14:39:04'),
(425, 36, 7, 23, 'Mucho', '2025-03-14 14:39:04'),
(426, 36, 8, 29, 'Mucho', '2025-03-14 14:39:04'),
(427, 36, 9, 41, 'Mucho', '2025-03-14 14:39:04'),
(428, 36, 10, 43, 'Regularmente', '2025-03-14 14:39:04'),
(429, 36, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 14:39:04'),
(430, 37, 1, 1, 'Casi Nunca', '2025-03-14 14:50:51'),
(431, 37, 2, 8, 'Poco', '2025-03-14 14:50:51'),
(432, 37, 3, 10, 'Nada', '2025-03-14 14:50:51'),
(433, 37, 4, 13, 'No', '2025-03-14 14:50:51'),
(434, 37, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 14:50:51'),
(435, 37, 6, 18, 'No', '2025-03-14 14:50:51'),
(436, 37, 7, 22, 'Poco', '2025-03-14 14:50:51'),
(437, 37, 8, 27, 'Nada', '2025-03-14 14:50:51'),
(438, 37, 9, 40, 'Poco', '2025-03-14 14:50:51'),
(439, 37, 10, 42, 'No', '2025-03-14 14:50:51'),
(440, 37, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 14:50:51'),
(441, 38, 1, 1, 'Casi Nunca', '2025-03-14 15:03:40'),
(442, 38, 2, 8, 'Poco', '2025-03-14 15:03:40'),
(443, 38, 3, 10, 'Nada', '2025-03-14 15:03:40'),
(444, 38, 4, 13, 'No', '2025-03-14 15:03:40'),
(445, 38, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 15:03:40'),
(446, 38, 6, 18, 'No', '2025-03-14 15:03:40'),
(447, 38, 7, 22, 'Poco', '2025-03-14 15:03:40'),
(448, 38, 8, 27, 'Nada', '2025-03-14 15:03:40'),
(449, 38, 9, 40, 'Poco', '2025-03-14 15:03:40'),
(450, 38, 10, 42, 'No', '2025-03-14 15:03:40'),
(451, 38, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 15:03:40'),
(452, 39, 1, 2, 'A veces', '2025-03-14 15:06:57'),
(453, 39, 2, 7, 'Nada', '2025-03-14 15:06:57'),
(454, 39, 3, 11, 'Poco', '2025-03-14 15:06:57'),
(455, 39, 4, 13, 'No', '2025-03-14 15:06:57'),
(456, 39, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 15:06:57'),
(457, 39, 6, 18, 'No', '2025-03-14 15:06:57'),
(458, 39, 7, 22, 'Poco', '2025-03-14 15:06:57'),
(459, 39, 8, 27, 'Nada', '2025-03-14 15:06:57'),
(460, 39, 9, 40, 'Poco', '2025-03-14 15:06:57'),
(461, 39, 10, 42, 'No', '2025-03-14 15:06:57'),
(462, 39, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 15:06:57'),
(463, 40, 1, 1, 'Casi Nunca', '2025-03-14 23:23:01'),
(464, 40, 2, 8, 'Poco', '2025-03-14 23:23:01'),
(465, 40, 3, 10, 'Nada', '2025-03-14 23:23:01'),
(466, 40, 4, 13, 'No', '2025-03-14 23:23:01'),
(467, 40, 5, 15, 'No', '2025-03-14 23:23:01'),
(468, 40, 6, 19, 'Sí ocasionalmente', '2025-03-14 23:23:01'),
(469, 40, 7, 21, 'Nada', '2025-03-14 23:23:01'),
(470, 40, 8, 27, 'Nada', '2025-03-14 23:23:01'),
(471, 40, 9, 40, 'Poco', '2025-03-14 23:23:01'),
(472, 40, 10, 43, 'Regularmente', '2025-03-14 23:23:01'),
(473, 40, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 23:23:01'),
(474, 41, 1, 1, 'Casi Nunca', '2025-03-14 23:31:30'),
(475, 41, 2, 7, 'Nada', '2025-03-14 23:31:30'),
(476, 41, 3, 10, 'Nada', '2025-03-14 23:31:30'),
(477, 41, 4, 13, 'No', '2025-03-14 23:31:30'),
(478, 41, 5, 15, 'No', '2025-03-14 23:31:30'),
(479, 41, 6, 18, 'No', '2025-03-14 23:31:30'),
(480, 41, 7, 21, 'Nada', '2025-03-14 23:31:30'),
(481, 41, 8, 27, 'Nada', '2025-03-14 23:31:30'),
(482, 41, 9, 39, 'Nada', '2025-03-14 23:31:30'),
(483, 41, 10, 42, 'No', '2025-03-14 23:31:30'),
(484, 41, 11, 36, 'No', '2025-03-14 23:31:30'),
(485, 42, 1, 1, 'Casi Nunca', '2025-03-14 23:36:23'),
(486, 42, 2, 8, 'Poco', '2025-03-14 23:36:23'),
(487, 42, 3, 11, 'Poco', '2025-03-14 23:36:23'),
(488, 42, 4, 14, 'Si', '2025-03-14 23:36:23'),
(489, 42, 5, 15, 'No', '2025-03-14 23:36:23'),
(490, 42, 6, 18, 'No', '2025-03-14 23:36:23'),
(491, 42, 7, 22, 'Poco', '2025-03-14 23:36:23'),
(492, 42, 8, 27, 'Nada', '2025-03-14 23:36:23'),
(493, 42, 9, 39, 'Nada', '2025-03-14 23:36:23'),
(494, 42, 10, 42, 'No', '2025-03-14 23:36:23'),
(495, 42, 11, 36, 'No', '2025-03-14 23:36:23'),
(496, 43, 1, 1, 'Casi Nunca', '2025-03-14 23:38:19'),
(497, 43, 2, 8, 'Poco', '2025-03-14 23:38:19'),
(498, 43, 3, 11, 'Poco', '2025-03-14 23:38:19'),
(499, 43, 4, 14, 'Si', '2025-03-14 23:38:19'),
(500, 43, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 23:38:19'),
(501, 43, 6, 20, 'Sí siempre', '2025-03-14 23:38:19'),
(502, 43, 7, 21, 'Nada', '2025-03-14 23:38:19'),
(503, 43, 8, 27, 'Nada', '2025-03-14 23:38:19'),
(504, 43, 9, 40, 'Poco', '2025-03-14 23:38:19'),
(505, 43, 10, 43, 'Regularmente', '2025-03-14 23:38:19'),
(506, 43, 11, 36, 'No', '2025-03-14 23:38:19'),
(507, 44, 1, 3, 'Casi Siempre', '2025-03-14 23:39:26'),
(508, 44, 2, 8, 'Poco', '2025-03-14 23:39:26'),
(509, 44, 3, 12, 'Mucho', '2025-03-14 23:39:26'),
(510, 44, 4, 14, 'Si', '2025-03-14 23:39:26'),
(511, 44, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 23:39:26'),
(512, 44, 6, 19, 'Sí ocasionalmente', '2025-03-14 23:39:26'),
(513, 44, 7, 22, 'Poco', '2025-03-14 23:39:26'),
(514, 44, 8, 29, 'Mucho', '2025-03-14 23:39:26'),
(515, 44, 9, 40, 'Poco', '2025-03-14 23:39:26'),
(516, 44, 10, 44, 'Si', '2025-03-14 23:39:26'),
(517, 44, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 23:39:26'),
(518, 45, 1, 3, 'Casi Siempre', '2025-03-14 23:41:05'),
(519, 45, 2, 8, 'Poco', '2025-03-14 23:41:05'),
(520, 45, 3, 12, 'Mucho', '2025-03-14 23:41:05'),
(521, 45, 4, 13, 'No', '2025-03-14 23:41:05'),
(522, 45, 5, 16, 'Sí pero ocasionalmente', '2025-03-14 23:41:05'),
(523, 45, 6, 20, 'Sí siempre', '2025-03-14 23:41:05'),
(524, 45, 7, 22, 'Poco', '2025-03-14 23:41:05'),
(525, 45, 8, 27, 'Nada', '2025-03-14 23:41:06'),
(526, 45, 9, 39, 'Nada', '2025-03-14 23:41:06'),
(527, 45, 10, 43, 'Regularmente', '2025-03-14 23:41:06'),
(528, 45, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-14 23:41:06'),
(529, 46, 1, 1, 'Casi Nunca', '2025-03-15 00:36:38'),
(530, 46, 2, 9, 'Mucho', '2025-03-15 00:36:39'),
(531, 46, 3, 10, 'Nada', '2025-03-15 00:36:39'),
(532, 46, 4, 14, 'Si', '2025-03-15 00:36:39'),
(533, 46, 5, 16, 'Sí pero ocasionalmente', '2025-03-15 00:36:39'),
(534, 46, 6, 19, 'Sí ocasionalmente', '2025-03-15 00:36:39'),
(535, 46, 7, 22, 'Poco', '2025-03-15 00:36:39'),
(536, 46, 8, 28, 'Poco', '2025-03-15 00:36:39'),
(537, 46, 9, 39, 'Nada', '2025-03-15 00:36:39'),
(538, 46, 10, 43, 'Regularmente', '2025-03-15 00:36:39'),
(539, 46, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-15 00:36:39'),
(540, 47, 1, 2, 'A veces', '2025-03-15 00:43:24'),
(541, 47, 2, 8, 'Poco', '2025-03-15 00:43:24'),
(542, 47, 3, 12, 'Mucho', '2025-03-15 00:43:24'),
(543, 47, 4, 14, 'Si', '2025-03-15 00:43:24'),
(544, 47, 5, 16, 'Sí pero ocasionalmente', '2025-03-15 00:43:24'),
(545, 47, 6, 19, 'Sí ocasionalmente', '2025-03-15 00:43:24'),
(546, 47, 7, 23, 'Mucho', '2025-03-15 00:43:24'),
(547, 47, 8, 28, 'Poco', '2025-03-15 00:43:24'),
(548, 47, 9, 40, 'Poco', '2025-03-15 00:43:24'),
(549, 47, 10, 43, 'Regularmente', '2025-03-15 00:43:24'),
(550, 47, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-15 00:43:24'),
(551, 48, 1, 1, 'Casi Nunca', '2025-03-15 00:44:09'),
(552, 48, 2, 8, 'Poco', '2025-03-15 00:44:09'),
(553, 48, 3, 11, 'Poco', '2025-03-15 00:44:09'),
(554, 48, 4, 14, 'Si', '2025-03-15 00:44:09'),
(555, 48, 5, 15, 'No', '2025-03-15 00:44:09'),
(556, 48, 6, 19, 'Sí ocasionalmente', '2025-03-15 00:44:09'),
(557, 48, 7, 22, 'Poco', '2025-03-15 00:44:09'),
(558, 48, 8, 29, 'Mucho', '2025-03-15 00:44:09'),
(559, 48, 9, 40, 'Poco', '2025-03-15 00:44:09'),
(560, 48, 10, 42, 'No', '2025-03-15 00:44:09'),
(561, 48, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-15 00:44:09'),
(562, 49, 1, 1, 'Casi Nunca', '2025-03-15 00:49:49'),
(563, 49, 2, 8, 'Poco', '2025-03-15 00:49:49'),
(564, 49, 3, 11, 'Poco', '2025-03-15 00:49:49'),
(565, 49, 4, 13, 'No', '2025-03-15 00:49:49'),
(566, 49, 5, 16, 'Sí pero ocasionalmente', '2025-03-15 00:49:49'),
(567, 49, 6, 20, 'Sí siempre', '2025-03-15 00:49:49'),
(568, 49, 7, 22, 'Poco', '2025-03-15 00:49:49'),
(569, 49, 8, 29, 'Mucho', '2025-03-15 00:49:49'),
(570, 49, 9, 40, 'Poco', '2025-03-15 00:49:49'),
(571, 49, 10, 42, 'No', '2025-03-15 00:49:49'),
(572, 49, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-15 00:49:49'),
(573, 50, 1, 1, 'Casi Nunca', '2025-03-19 10:04:27'),
(574, 50, 2, 8, 'Poco', '2025-03-19 10:04:27'),
(575, 50, 3, 10, 'Nada', '2025-03-19 10:04:27'),
(576, 50, 4, 13, 'No', '2025-03-19 10:04:27'),
(577, 50, 5, 15, 'No', '2025-03-19 10:04:27'),
(578, 50, 6, 19, 'Sí ocasionalmente', '2025-03-19 10:04:27'),
(579, 50, 7, 22, 'Poco', '2025-03-19 10:04:27'),
(580, 50, 8, 27, 'Nada', '2025-03-19 10:04:27'),
(581, 50, 9, 39, 'Nada', '2025-03-19 10:04:27'),
(582, 50, 10, 43, 'Regularmente', '2025-03-19 10:04:27'),
(583, 50, 11, 37, 'Si (segundo y tercer grado de consanguinidad): abuelos tíos sobrinos', '2025-03-19 10:04:27'),
(584, 51, 1, 3, 'Casi Siempre', '2025-03-19 10:27:10'),
(585, 51, 2, 9, 'Mucho', '2025-03-19 10:27:10'),
(586, 51, 3, 11, 'Poco', '2025-03-19 10:27:10'),
(587, 51, 4, 14, 'Si', '2025-03-19 10:27:10'),
(588, 51, 5, 16, 'Sí pero ocasionalmente', '2025-03-19 10:27:10'),
(589, 51, 6, 18, 'No', '2025-03-19 10:27:10'),
(590, 51, 7, 21, 'Nada', '2025-03-19 10:27:10'),
(591, 51, 8, 28, 'Poco', '2025-03-19 10:27:10'),
(592, 51, 9, 39, 'Nada', '2025-03-19 10:27:10'),
(593, 51, 10, 42, 'No', '2025-03-19 10:27:11'),
(594, 51, 11, 36, 'No', '2025-03-19 10:27:11');

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
(7, 'Modulo Predicción', '#', NULL, 'bx bx-edit-alt bx-sm', 0, 1, 1, '2025-02-20 10:38:45'),
(8, 'Citas Médicas', '#', NULL, 'bx bx-calendar-check bx-sm', 0, 2, 1, '2025-03-16 22:27:22');

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
(17, 1, 14, 1, 0, 0, 0),
(18, 1, 15, 1, 0, 0, 0),
(19, 1, 16, 1, 0, 0, 0),
(20, 1, 17, 1, 0, 0, 0);

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
(61, 1, 13, 6, 1, '2025-03-05 12:41:03'),
(62, 1, 15, 1, 1, '2025-03-16 22:30:55'),
(63, 1, 15, 2, 1, '2025-03-16 22:30:57'),
(64, 1, 15, 3, 1, '2025-03-16 22:31:00'),
(65, 1, 15, 4, 1, '2025-03-16 22:31:02'),
(66, 1, 15, 6, 1, '2025-03-16 22:31:05');

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
(1, 75933129, 'BENITES LOJA, ROCIO ISABEL', 987654321, 'rocioisabelbenitesloja@gmail.com', '', NULL, 1, '2022-07-22 01:09:20'),
(2, 76144152, 'BUSTAMANTE FERNANDEZ LEENH ALEXANDER', NULL, 'hackingleenh@gmail.com', NULL, NULL, 1, '2025-03-10 18:12:53'),
(3, 70790218, 'ABANTO PEÑA, JHOHAN ANDREE', 987654321, NULL, '.', NULL, 1, '2025-03-14 15:42:08'),
(4, 74075940, 'ABARCA GUTIERREZ, NATHALY YAHAIRA', 987654321, NULL, '.', NULL, 1, '2025-03-14 15:42:27'),
(5, 27440013, 'AGIP RUBIO, RICARDO GERMAN', 987654321, NULL, '.', NULL, 1, '2025-03-14 16:01:00'),
(6, 71298019, 'BRIOSO MEJIA, JUAN BERLIN', 987654321, NULL, '.', NULL, 1, '2025-03-14 16:01:58'),
(7, 70296583, 'CAMPOSANO DE LA CRUZ, CRISTIAN JAHIR', 987654321, NULL, '.', NULL, 1, '2025-03-14 16:07:35'),
(8, 73612932, 'CARRANZA VELARDE, ANA PAULA', 987654321, NULL, '.', NULL, 1, '2025-03-14 16:08:06'),
(9, 75701592, 'CURIHUAMAN ALVA, LISBET MARIBEL', 987654321, NULL, '.', NULL, 1, '2025-03-14 18:21:41'),
(10, 70929145, 'ESPINOLA SILVA, JIMENA ALESSANDRA', 987654321, NULL, '.', NULL, 1, '2025-03-14 23:16:31');

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
(9, 'Asignar Horario Médico', NULL, 'ruta', 'ruta.horario-medico', 1, '2025-02-24 16:29:09'),
(10, 'Preguntas del test', NULL, 'ruta', 'ruta.preguntas', 1, '2025-02-25 17:26:57'),
(11, 'Realizar Test', NULL, 'ruta', 'ruta.test', 1, '2025-02-27 12:58:54'),
(12, 'Configuración de la IA', NULL, 'ruta', 'ruta.ia', 1, '2025-03-04 12:42:32'),
(13, 'Lista de Test', NULL, 'ruta', 'ruta.lista', 1, '2025-03-05 12:40:45'),
(15, 'Citas Medicas', NULL, 'ruta', 'ruta.citas-medicas', 1, '2025-03-16 22:30:44');

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
-- Estructura de tabla para la tabla `sis_server_email`
--

CREATE TABLE `sis_server_email` (
  `idserveremail` int NOT NULL,
  `em_host` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `em_usermail` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `em_pass` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `em_port` int NOT NULL,
  `em_estado` tinyint(1) NOT NULL DEFAULT '1',
  `em_default` tinyint(1) DEFAULT NULL,
  `em_fupdate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `em_fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Volcado de datos para la tabla `sis_server_email`
--

INSERT INTO `sis_server_email` (`idserveremail`, `em_host`, `em_usermail`, `em_pass`, `em_port`, `em_estado`, `em_default`, `em_fupdate`, `em_fecha`) VALUES
(1, 'mail.leenhcraft.com', 'servicios@leenhcraft.com', 'DJ-leenh-#1', 465, 1, 1, '2022-05-06 22:29:56', '2022-03-19 23:12:56'),
(2, 'smtp.gmail.com', '2018100486facke@gmail.com', 'bteaasmagqeaiyax', 465, 1, 0, '2022-03-19 23:25:14', '2022-03-19 23:25:14');

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
(1, 1, 'f1bf88d1fdd2e017343121a2e669856c8593a0a6db1c3c283a756746a35636c370c1bb3478238c05', '::1', '2025-03-08 00:50:47', '1741416893', 0),
(2, 2, '1f20ccc35eeb30703e121a56e384fa45b02aceff873ae320b04fa8072b89b249a4258049690e57e4', '::1', '2025-03-08 00:56:08', '1741416972', 1),
(3, 1, '65b2c89390cbe0c19a4587da9fec4ea9b3bccd8e6463298e094da15bc23fe749525be9c1fd20b8ca', '::1', '2025-03-10 12:22:38', '1741630965', 0),
(4, 1, 'f3376ab7a41d329ad316d5f03e16b16b30b39f60a7de590e1b59b46fd83dadd591cd2c4131c11158', '::1', '2025-03-10 12:22:49', '1741631376', 1),
(5, 2, 'df2b0db88f210717430c1d67d7b961b99b8cc92e8af89483804f1a09cdb542548c53b009c03e3f56', '::1', '2025-03-10 18:13:22', '1741653716', 1),
(6, 1, '5939767ef4b31594d51c9077ff77d17a67e4d3852a2aa38a79f86a4e3c0fa6b58b6e15e70111a23a', '::1', '2025-03-11 11:49:44', '1741719273', 0),
(7, 2, '33b0ea6b5e354d13f837012323e3aaa08f83c19302f23590349700275ca9ccc0513e4882687e9e2d', '::1', '2025-03-11 11:51:19', '1741717697', 0),
(8, 2, 'a31499b3817b44aca7caedf7fe0fefbcb2dfd8a912863f10e26ad67a77ec8aa571ae84be75cd5bc9', '::1', '2025-03-11 12:53:15', '1741719263', 0),
(9, 1, '3ab280bb07a8afaa23705c34b4ae216cdeb7c43121f4c806fc648423378b1f81120d286af87b634c', '::1', '2025-03-11 20:57:08', '1741750501', 1),
(10, 1, 'c2ea1106bb6797cf8336225c84c2caa6bda3f4b0e31124e4365d39948383ace6f241e233e181aa3b', '::1', '2025-03-13 15:33:49', '1741902659', 0),
(11, 1, '9ac48a7680c6b986b1f978253ede53e63be0f5ab2021ee0b1c8bb2735304caee748aaa22c0c7519d', '::1', '2025-03-13 15:51:22', '1741903184', 0),
(12, 1, 'b83be31525029dd60beb5322dd62124fac310397b21436dc03180efb6e20144ccd0fa4eb4f1f40f9', '::1', '2025-03-13 18:34:10', '1741914668', 1),
(13, 2, '47e4432c690a257830a1c9e6c2b47b6a072b3c78b899ac5a5441ae4aed410e6ae1c374d7dce31996', '::1', '2025-03-14 14:12:21', '1741986454', 0),
(14, 1, '8544a1ba038f66565c7443a36cb8de67b5d7ae129a6f6272044ff3cb21957e458738003556be84f4', '::1', '2025-03-14 14:16:25', '1742000777', 0),
(15, 1, '0bb05069d93438f23386f0181c663344b36fd77cf2f2b242ff84f55c7c1c428c112934ffffdbf8ee', '::1', '2025-03-14 22:51:38', '1742017340', 0),
(16, 2, 'df8ba832d22f9ce0c49dc2827a91509106c4119f465309f4a811440ea80292e205c1666b2fa2e02b', '::1', '2025-03-14 23:18:48', '1742021389', 0),
(17, 1, '7ec8d0599ca3463fe64a06581ea3ab89bd84e34a93452079a236856e05f10d4bdc5969be2301641b', '::1', '2025-03-16 22:11:07', '1742194662', 1),
(18, 2, '42bf458a2fe88cf465051814bb439c2973380a544ceae9335e6f2d27dacd96ccfcd57a45000334bb', '::1', '2025-03-16 22:11:59', '1742184935', 1),
(19, 2, 'c243e5ef120b9df37a5c0a5a084b8dd261517ea792ac3d3139735b767f58aac6dbcfd67358a857a9', '::1', '2025-03-17 12:56:16', '1742237777', 0),
(20, 1, 'fce43899019e507ba2f290e16957a113d4ddc22d3df56cf666dad577fe1335b487c657b0b557b0af', '::1', '2025-03-17 12:56:19', '1742243191', 0),
(21, 1, 'a74877e6e548aa9e028dd3650155fea6af5d77f031d6f2e71f96684006d9ddc83e175de91972b753', '::1', '2025-03-17 15:54:27', '1742250580', 1),
(22, 1, '9eac6e119c38958c5753a20ddbea8c26dc335036304793394166c871377da6fb8badd8d978ca7f39', '::1', '2025-03-17 21:53:38', '1742270248', 0),
(23, 2, 'aaf8f7218c5e619d2b5a5a01f1a10010f20516b0903956150cc8d04e85fa94ebd013ba479d24dba1', '::1', '2025-03-17 21:58:10', '1742270294', 0),
(24, 1, '86707a26475c89617e815b171ebb0caee4258b95a11e4ffb1c582cbbc31f9387d95e17f93dcb1c20', '::1', '2025-03-17 23:09:20', '1742281381', 1),
(25, 1, 'ad84d1af38bb9e5ef088836eda19bd0f6d78d4ded741a6babff2b34c164d12d123d2118c989f9e37', '::1', '2025-03-18 11:20:07', '1742325676', 0),
(26, 1, '2d99936d59108d40400437949c120e3656e0352ca8a246e56bb7501d42fed8dc6607f9d602056f93', '::1', '2025-03-18 14:52:02', '1742333547', 0),
(27, 1, 'a2bd64db7a073622e4a9610476560bdae1b1001e12e5fc19f4a46480170821c60b1df5ed39a75a88', '::1', '2025-03-18 16:48:59', '1742339362', 0),
(28, 1, '9069e49e90bf4dbcac80c1f2d0ee7ea15c3cab51e4a7c0c5028f49ad1339e985b51d18e3cc1361fa', '::1', '2025-03-18 19:58:51', '1742351307', 0),
(29, 1, 'a49738718e3eed98b6b6461b03cbebfa911d712799862f4da3d48f9bd587b3e5fe5d88cbbfe00875', '::1', '2025-03-18 22:00:27', '1742367043', 1),
(30, 2, '180e32ea331d55a496fe67c5ddc8da1ac6c3e5d81e9b88c9e0186eb39eb2fa0567090a3ce07db683', '::1', '2025-03-19 00:04:19', '1742368147', 1),
(31, 2, '627ca122254b5aebc84680e896a4ea4f6212d448c1ca608a0d7bf1b48be56561436590a709fdddb9', '::1', '2025-03-19 08:20:43', '1742401691', 1),
(32, 1, 'fa19356d22b7ea3c8442bea6267ccebccaefa5b8c751aff9977deb2dff52146b8bf49a6ade7442ca', '::1', '2025-03-19 08:29:02', '1742394796', 1);

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
(14, 5, 'Horario Médico', '/admin/horario-medico', 0, 'HorarioController', 'index', 'bx bx-time', 3, 1, '2025-02-24 16:27:09'),
(15, 7, 'Preguntas', '/admin/preguntas', 0, 'PreguntasController', 'index', 'bx-sm bx-question-mark text-info', 3, 1, '2025-02-25 17:19:26'),
(16, 7, 'Lista de Test', '/admin/lista-test', 0, 'ListaTestController', 'index', 'bx-sm bx bx-list-ul text-success', 1, 1, '2025-03-04 12:41:55'),
(17, 8, 'Agendar cita', '/admin/citas', 0, 'CitasController', 'index', 'bx-circle', 1, 1, '2025-03-16 22:29:58');

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
(2, 4, 2, 'leenhcraft', '$2y$10$iTsh3NcBhufyfCBNG15NyuSsdAgfCsy.V1GMWa2wAeB1DaeVmqKIC', NULL, 1, 1, 1, 0, '', '2025-03-10 18:12:53', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ac_citas`
--
ALTER TABLE `ac_citas`
  ADD PRIMARY KEY (`idcita`);

--
-- Indices de la tabla `ac_estado_cita`
--
ALTER TABLE `ac_estado_cita`
  ADD PRIMARY KEY (`id_estado_cita`);

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
-- Indices de la tabla `sd_dias_semana`
--
ALTER TABLE `sd_dias_semana`
  ADD PRIMARY KEY (`iddia`);

--
-- Indices de la tabla `sd_especialidades`
--
ALTER TABLE `sd_especialidades`
  ADD PRIMARY KEY (`idespecialidad`);

--
-- Indices de la tabla `sd_horarios_medicos`
--
ALTER TABLE `sd_horarios_medicos`
  ADD PRIMARY KEY (`id_horario_medico`);

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
-- Indices de la tabla `sis_server_email`
--
ALTER TABLE `sis_server_email`
  ADD PRIMARY KEY (`idserveremail`);

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
-- AUTO_INCREMENT de la tabla `ac_citas`
--
ALTER TABLE `ac_citas`
  MODIFY `idcita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ac_estado_cita`
--
ALTER TABLE `ac_estado_cita`
  MODIFY `id_estado_cita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `pr_preguntas`
--
ALTER TABLE `pr_preguntas`
  MODIFY `id_pregunta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pr_respuestas`
--
ALTER TABLE `pr_respuestas`
  MODIFY `id_respuesta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `pr_tipo_respuestas`
--
ALTER TABLE `pr_tipo_respuestas`
  MODIFY `id_tipo_respuesta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `sd_dias_semana`
--
ALTER TABLE `sd_dias_semana`
  MODIFY `iddia` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `sd_especialidades`
--
ALTER TABLE `sd_especialidades`
  MODIFY `idespecialidad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `sd_horarios_medicos`
--
ALTER TABLE `sd_horarios_medicos`
  MODIFY `id_horario_medico` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `sd_pacientes`
--
ALTER TABLE `sd_pacientes`
  MODIFY `idpaciente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `sd_personal_especialidad`
--
ALTER TABLE `sd_personal_especialidad`
  MODIFY `id_per_esp` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sd_personal_medico`
--
ALTER TABLE `sd_personal_medico`
  MODIFY `idpersonal` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sd_test`
--
ALTER TABLE `sd_test`
  MODIFY `idtest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `sd_test_preguntas`
--
ALTER TABLE `sd_test_preguntas`
  MODIFY `id_test_pregunta` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=595;

--
-- AUTO_INCREMENT de la tabla `sis_acciones`
--
ALTER TABLE `sis_acciones`
  MODIFY `idaccion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sis_centinela`
--
ALTER TABLE `sis_centinela`
  MODIFY `idcentinela` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13415;

--
-- AUTO_INCREMENT de la tabla `sis_menus`
--
ALTER TABLE `sis_menus`
  MODIFY `idmenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sis_permisos`
--
ALTER TABLE `sis_permisos`
  MODIFY `idpermisos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sis_permisos_extras`
--
ALTER TABLE `sis_permisos_extras`
  MODIFY `idpermiso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `sis_personal`
--
ALTER TABLE `sis_personal`
  MODIFY `idpersona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `sis_recursos`
--
ALTER TABLE `sis_recursos`
  MODIFY `idrecurso` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `sis_rol`
--
ALTER TABLE `sis_rol`
  MODIFY `idrol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sis_server_email`
--
ALTER TABLE `sis_server_email`
  MODIFY `idserveremail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sis_sesiones`
--
ALTER TABLE `sis_sesiones`
  MODIFY `idsesion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `sis_submenus`
--
ALTER TABLE `sis_submenus`
  MODIFY `idsubmenu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `sis_usuarios`
--
ALTER TABLE `sis_usuarios`
  MODIFY `idusuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
