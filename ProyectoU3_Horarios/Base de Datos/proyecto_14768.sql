-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 27-02-2024 a las 23:02:30
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_14768`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` varchar(30) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `bloque` varchar(10) NOT NULL,
  `observacion` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`id_aula`, `capacidad`, `bloque`, `observacion`) VALUES
('G304', 40, 'G', ''),
('H204', 2, 'H', 'Nhfghfghfgha'),
('H3052', 22, 'H', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `nombre_carrera` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`) VALUES
(1, 'Ingeniería de Software'),
(4, 'TICS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_cursos`
--

CREATE TABLE `carreras_cursos` (
  `id_carrera_curso` int(11) NOT NULL,
  `id_carrera` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nrc` int(11) NOT NULL,
  `periodos_id_periodo` int(11) NOT NULL,
  `cod_materia` varchar(20) NOT NULL,
  `horarios_id_horario` int(11) NOT NULL,
  `id_aula` varchar(30) NOT NULL,
  `id_docente` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nrc`, `periodos_id_periodo`, `cod_materia`, `horarios_id_horario`, `id_aula`, `id_docente`) VALUES
(75, 1, 13, 'mart-484', 2, 'G304', 'L00111111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` varchar(45) NOT NULL,
  `nombres` varchar(45) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `horas_disponibles` int(5) NOT NULL,
  `tipo_contrato` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `nivel_educacion` varchar(100) NOT NULL,
  `especializacion` varchar(100) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `estado` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `nombres`, `apellidos`, `horas_disponibles`, `tipo_contrato`, `correo`, `nivel_educacion`, `especializacion`, `celular`, `cedula`, `estado`) VALUES
('L00', 'prueba', 'prueba', 16, 'Parcial', 'prueba@prueba', 'prueba', 'prueba', '43', '43', 1),
('L00111111', 'Stephen', 'Drouet', 16, 'Completo', 'dGamboa@espe.edu.ec', 'Licenciatura', 'Sistemas', '232', '1561561651', 1),
('L0033', 'aaa', 'aa', 12, 'Parcial', 'a@a', 'as', 'as', '43', '43', 0),
('L099999999999', 'nuevoo para probar', 'aa', 10, 'Parcial', 'a@a', 'as', 'as', '43', '43', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id_horario` int(11) NOT NULL,
  `dia` varchar(45) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id_horario`, `dia`, `hora_inicio`, `hora_fin`) VALUES
(1, 'lunes', '11:20:00', '13:20:00'),
(2, 'martes', '07:00:00', '09:00:00'),
(5, 'jueves', '09:00:00', '11:00:00'),
(6, 'jueves', '11:00:00', '13:00:00'),
(8, 'LUNES', '05:43:00', '00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_aulas`
--

CREATE TABLE `horarios_aulas` (
  `id_horario__aula` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `id_aula` varchar(30) NOT NULL,
  `disponible` int(11) NOT NULL DEFAULT '1',
  `id_periodo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `horarios_aulas`
--

INSERT INTO `horarios_aulas` (`id_horario__aula`, `id_horario`, `id_aula`, `disponible`, `id_periodo`) VALUES
(77, 1, 'G304', 1, 13),
(78, 2, 'G304', 0, 13),
(79, 5, 'G304', 1, 13),
(80, 6, 'G304', 1, 13),
(81, 1, 'H204', 1, 13),
(82, 2, 'H204', 1, 13),
(83, 5, 'H204', 1, 13),
(84, 6, 'H204', 1, 13),
(85, 1, 'H3052', 1, 13),
(86, 2, 'H3052', 1, 13),
(87, 5, 'H3052', 1, 13),
(88, 6, 'H3052', 1, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `cod_materia` varchar(20) NOT NULL,
  `nombre_materia` varchar(100) NOT NULL,
  `departamento` varchar(45) NOT NULL,
  `horas_semana` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`cod_materia`, `nombre_materia`, `departamento`, `horas_semana`) VALUES
('CCE-1572', 'Economia', 'CIENCIAS ECONÓMICAS, ADMINISTRATIVAS Y DEL CO', 6),
('mart-484', 'lenguaaas', 'exacatas', 4),
('mat-151', 'matematicas', 'exacatas', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `tipo_perfil` varchar(45) NOT NULL,
  `privilegios` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `tipo_perfil`, `privilegios`) VALUES
(1, 'admin', 'todos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL,
  `nombre_periodo` varchar(45) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`id_periodo`, `nombre_periodo`, `fecha_inicio`, `fecha_fin`) VALUES
(13, '2024-17feb', '2024-02-17', '2024-05-17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_docentes`
--

CREATE TABLE `periodos_docentes` (
  `id_periodo_docente` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_docente` varchar(45) NOT NULL,
  `horas_asignadas` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `periodos_docentes`
--

INSERT INTO `periodos_docentes` (`id_periodo_docente`, `id_periodo`, `id_docente`, `horas_asignadas`) VALUES
(15, 13, 'L00', 16),
(16, 13, 'L00111111', 14),
(17, 13, 'L099999999999', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_horarios`
--

CREATE TABLE `periodos_horarios` (
  `id_periodo_horario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `periodos_horarios`
--

INSERT INTO `periodos_horarios` (`id_periodo_horario`, `id_periodo`, `id_horario`) VALUES
(37, 13, 1),
(38, 13, 2),
(39, 13, 5),
(40, 13, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` varchar(20) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `usuario`, `clave`, `id_perfil`) VALUES
('L00242414', 'Stephen', 'Drouet', 'admin', '170400', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id_carrera`);

--
-- Indices de la tabla `carreras_cursos`
--
ALTER TABLE `carreras_cursos`
  ADD PRIMARY KEY (`id_carrera_curso`,`id_carrera`,`id_curso`),
  ADD KEY `fk_carreras_has_cursos_carreras1` (`id_carrera`),
  ADD KEY `fk_carreras_has_cursos_cursos1` (`id_curso`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`,`periodos_id_periodo`,`cod_materia`,`horarios_id_horario`,`id_aula`),
  ADD KEY `fk_cursos_docentes1` (`id_docente`),
  ADD KEY `fk_cursos_materias1` (`cod_materia`),
  ADD KEY `fk_cursos_aulas1` (`id_aula`),
  ADD KEY `fk_cursos_periodos1` (`periodos_id_periodo`),
  ADD KEY `fk_cursos_horarios1` (`horarios_id_horario`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id_horario`);

--
-- Indices de la tabla `horarios_aulas`
--
ALTER TABLE `horarios_aulas`
  ADD PRIMARY KEY (`id_horario__aula`,`id_horario`,`id_aula`),
  ADD KEY `fk_horarios_has_aulas_horarios1` (`id_horario`),
  ADD KEY `fk_horarios_has_aulas_aulas1` (`id_aula`),
  ADD KEY `fk_horarios_aulas_periodos` (`id_periodo`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`cod_materia`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `periodos_docentes`
--
ALTER TABLE `periodos_docentes`
  ADD PRIMARY KEY (`id_periodo_docente`,`id_periodo`,`id_docente`),
  ADD KEY `fk_periodos_has_docentes_periodos1` (`id_periodo`),
  ADD KEY `fk_periodos_has_docentes_docentes1` (`id_docente`);

--
-- Indices de la tabla `periodos_horarios`
--
ALTER TABLE `periodos_horarios`
  ADD PRIMARY KEY (`id_periodo_horario`,`id_periodo`,`id_horario`),
  ADD KEY `fk_periodos_has_horarios_periodos` (`id_periodo`),
  ADD KEY `fk_periodos_has_horarios_horarios1` (`id_horario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`,`id_perfil`),
  ADD KEY `fk_usuarios_perfiles1` (`id_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `carreras_cursos`
--
ALTER TABLE `carreras_cursos`
  MODIFY `id_carrera_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `horarios_aulas`
--
ALTER TABLE `horarios_aulas`
  MODIFY `id_horario__aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `periodos_docentes`
--
ALTER TABLE `periodos_docentes`
  MODIFY `id_periodo_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `periodos_horarios`
--
ALTER TABLE `periodos_horarios`
  MODIFY `id_periodo_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carreras_cursos`
--
ALTER TABLE `carreras_cursos`
  ADD CONSTRAINT `fk_carreras_has_cursos_carreras1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  ADD CONSTRAINT `fk_carreras_has_cursos_cursos1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `fk_cursos_aulas1` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`),
  ADD CONSTRAINT `fk_cursos_horarios1` FOREIGN KEY (`horarios_id_horario`) REFERENCES `horarios` (`id_horario`),
  ADD CONSTRAINT `fk_cursos_materias1` FOREIGN KEY (`cod_materia`) REFERENCES `materias` (`cod_materia`),
  ADD CONSTRAINT `fk_cursos_periodos1` FOREIGN KEY (`periodos_id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `horarios_aulas`
--
ALTER TABLE `horarios_aulas`
  ADD CONSTRAINT `fk_horarios_aulas_periodos` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
  ADD CONSTRAINT `fk_horarios_has_aulas_aulas1` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`),
  ADD CONSTRAINT `fk_horarios_has_aulas_horarios1` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`);

--
-- Filtros para la tabla `periodos_docentes`
--
ALTER TABLE `periodos_docentes`
  ADD CONSTRAINT `fk_periodos_has_docentes_docentes1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `fk_periodos_has_docentes_periodos1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `periodos_horarios`
--
ALTER TABLE `periodos_horarios`
  ADD CONSTRAINT `fk_periodos_has_horarios_horarios1` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`),
  ADD CONSTRAINT `fk_periodos_has_horarios_periodos` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_perfiles1` FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id_perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
