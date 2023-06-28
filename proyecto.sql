-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 28-06-2023 a las 03:16:32
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `surname` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `gender` enum('man','woman') CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT 'man',
  `user` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `dni_prefix` enum('V','E') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'V',
  `dni` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_spanish_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `last_session` datetime DEFAULT NULL,
  `session` enum('0','1') COLLATE latin1_spanish_ci NOT NULL DEFAULT '0',
  `code_email` varchar(6) CHARACTER SET latin1 COLLATE latin1_spanish_ci DEFAULT NULL,
  `code_expire` datetime DEFAULT NULL,
  `type_user` enum('admin','regular') COLLATE latin1_spanish_ci NOT NULL DEFAULT 'regular',
  `eula` varchar(6) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'accept',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci COMMENT='USUARIOS PARA LOS PROFESORES Y ESTUDIANTES';

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `gender`, `user`, `dni_prefix`, `dni`, `email`, `password`, `created`, `updated`, `last_session`, `session`, `code_email`, `code_expire`, `type_user`, `eula`) VALUES
(1, 'Jos&eacute;', 'Hern&aacute;ndez', 'man', 'tagdisk', 'V', '27630600', 'hernandezjose0901@gmail.com', '$2y$10$09MnWCoLqTJZWdU8OQhUVOR9As76IN/cs7MFDsHWw9NYtem1WIZMa', '2023-06-27 23:12:23', '2023-06-27 23:16:00', '2023-06-27 23:15:51', '0', NULL, NULL, 'admin', 'accept');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
