-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 09-03-2024 a las 22:03:53
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
-- Base de datos: `horarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `id_aula` int(11) NOT NULL,
  `cod_aula` varchar(30) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `bloque` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `nombre_carrera` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_docente` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `horas_disponibles` int(5) NOT NULL,
  `tipo_contrato` varchar(45) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `nivel_educacion` varchar(100) NOT NULL,
  `especializacion` varchar(100) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `estado` int(5) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_aulas`
--

CREATE TABLE `horarios_aulas` (
  `id_horario__aula` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL,
  `disponible` int(11) NOT NULL DEFAULT '1',
  `id_periodo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_aulas_cursos`
--

CREATE TABLE `horarios_aulas_cursos` (
  `id_horarios_aulas_cursos` int(11) NOT NULL,
  `id_horario__aula` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL,
  `cod_materia` varchar(20) NOT NULL,
  `nombre_materia` varchar(100) NOT NULL,
  `departamento` varchar(45) NOT NULL,
  `horas_semana` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedades`
--

CREATE TABLE `novedades` (
  `id_novedad` int(11) NOT NULL,
  `fecha_novedad` datetime DEFAULT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_aula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `tipo_perfil` varchar(45) NOT NULL,
  `privilegios` varchar(300) NOT NULL,
  `funciones` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_docentes`
--

CREATE TABLE `periodos_docentes` (
  `id_periodo_docente` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `horas_asignadas` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos_horarios`
--

CREATE TABLE `periodos_horarios` (
  `id_periodo_horario` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_horario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cod_usuario` varchar(45) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `clave` varchar(45) NOT NULL,
  `id_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`id_aula`),
  ADD UNIQUE KEY `cod_aula_UNIQUE` (`cod_aula`);

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
  ADD PRIMARY KEY (`id_curso`,`periodos_id_periodo`,`id_materia`),
  ADD UNIQUE KEY `nrc_UNIQUE` (`nrc`),
  ADD KEY `fk_cursos_periodos1` (`periodos_id_periodo`),
  ADD KEY `fk_cursos_docentes1` (`id_docente`),
  ADD KEY `fk_cursos_materias1` (`id_materia`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`,`id_usuario`),
  ADD UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`);

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
  ADD KEY `fk_horarios_aulas_aulas1` (`id_aula`),
  ADD KEY `fk_horarios_aulas_periodos` (`id_periodo`);

--
-- Indices de la tabla `horarios_aulas_cursos`
--
ALTER TABLE `horarios_aulas_cursos`
  ADD PRIMARY KEY (`id_horarios_aulas_cursos`,`id_horario__aula`,`id_curso`),
  ADD KEY `fk_horarios_aulas_has_cursos_horarios_aulas1` (`id_horario__aula`),
  ADD KEY `fk_horarios_aulas_has_cursos_cursos1` (`id_curso`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`),
  ADD UNIQUE KEY `cod_materia_UNIQUE` (`cod_materia`);

--
-- Indices de la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD PRIMARY KEY (`id_novedad`,`id_usuario`,`id_aula`),
  ADD KEY `fk_novedades_usuarios1` (`id_usuario`),
  ADD KEY `fk_novedades_aulas1` (`id_aula`);

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
  ADD KEY `fk_periodos_docentes_docentes1` (`id_docente`);

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
  ADD UNIQUE KEY `cod_usuario_UNIQUE` (`cod_usuario`),
  ADD KEY `fk_usuarios_perfiles1` (`id_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `carreras_cursos`
--
ALTER TABLE `carreras_cursos`
  MODIFY `id_carrera_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horarios_aulas`
--
ALTER TABLE `horarios_aulas`
  MODIFY `id_horario__aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `horarios_aulas_cursos`
--
ALTER TABLE `horarios_aulas_cursos`
  MODIFY `id_horarios_aulas_cursos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `novedades`
--
ALTER TABLE `novedades`
  MODIFY `id_novedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `periodos_docentes`
--
ALTER TABLE `periodos_docentes`
  MODIFY `id_periodo_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `periodos_horarios`
--
ALTER TABLE `periodos_horarios`
  MODIFY `id_periodo_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `fk_cursos_materias1` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  ADD CONSTRAINT `fk_cursos_periodos1` FOREIGN KEY (`periodos_id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `fk_docentes_usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `horarios_aulas`
--
ALTER TABLE `horarios_aulas`
  ADD CONSTRAINT `fk_horarios_aulas_aulas1` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`),
  ADD CONSTRAINT `fk_horarios_aulas_periodos` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
  ADD CONSTRAINT `fk_horarios_has_aulas_horarios1` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`);

--
-- Filtros para la tabla `horarios_aulas_cursos`
--
ALTER TABLE `horarios_aulas_cursos`
  ADD CONSTRAINT `fk_horarios_aulas_has_cursos_cursos1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `fk_horarios_aulas_has_cursos_horarios_aulas1` FOREIGN KEY (`id_horario__aula`) REFERENCES `horarios_aulas` (`id_horario__aula`);

--
-- Filtros para la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD CONSTRAINT `fk_novedades_aulas1` FOREIGN KEY (`id_aula`) REFERENCES `aulas` (`id_aula`),
  ADD CONSTRAINT `fk_novedades_usuarios1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `periodos_docentes`
--
ALTER TABLE `periodos_docentes`
  ADD CONSTRAINT `fk_periodos_docentes_docentes1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
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
