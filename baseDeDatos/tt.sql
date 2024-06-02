-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2024 a las 21:54:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tt`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `NumeroEmpleado` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `ApellidoPaterno` varchar(50) NOT NULL,
  `ApellidoMaterno` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`NumeroEmpleado`, `Nombre`, `ApellidoPaterno`, `ApellidoMaterno`, `Correo`, `Password`) VALUES
(7, 'Administrador CATT', 'A', 'B', 'a@a', '$2y$10$itQ6XZfaDCDeFMS.XpNfo.GgCr/I6IDh07.Z8ERWv.UWPjhEqNfXq');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `Boleta` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido_Paterno` varchar(50) NOT NULL,
  `Apellido_Materno` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`Boleta`, `Nombre`, `Apellido_Paterno`, `Apellido_Materno`, `Correo`, `Password`) VALUES
(212101, 'Alumno Juan', 'Be', 'R', 'al@al', '$2y$10$Le.B7sRRDhQQBHZ9Zc.adezRPB5ZVqsNbGB4YO0TQ.N0WjyvSiW32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('bernal12312@gmail.com', '86630d3cc29e1bc85d3410619e4c9f1eb2335309b04dcaa93a33f402ae31d965d34bc4fd794a08ea130fae53e3af5b80b2f6', '2024-05-31 05:04:57'),
('bernal12312@gmail.com', '6d27c9b8530136d8ab22fc9c551e5924827e39646383a903a2aa3431b0d9a655845d993be03e3ae92289d190aae8e528c8bd', '2024-05-31 05:05:04'),
('bernal12312@gmail.com', '5cba363fb3503219cefb528541de1f90a2c273bda850de12090f15cc41cad765762c40dfc2c0b43a2b8b103e490f8579ea93', '2024-05-31 05:05:57'),
('bernal12312@gmail.com', 'e277986679a4feffe4d045915ba94ac9b0ba8583b4f27c87ee2474db8c7cec5f58fe01ff41a14f46627588a5e8566b7fa59e', '2024-05-31 05:06:01'),
('bernal12312@gmail.com', '9788d5be10abd338115f6fcf2435599a5fcb920684ff8a48b6deb00e4dcbfdeef361bb226c928b5b77d4661ea2d489c4efad', '2024-05-31 14:12:22'),
('bernal12312@gmail.com', 'a8429dde80c21f2b2808ad4c83a2ef6d19c698908610c6e0f49ba885b76d9975156b5cc0f1f7a71a66ddb626aa45107e803c', '2024-06-01 22:09:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `NumeroEmpleado` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `ApellidoPaterno` varchar(50) NOT NULL,
  `ApellidoMaterno` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`NumeroEmpleado`, `Nombre`, `ApellidoPaterno`, `ApellidoMaterno`, `Correo`, `Password`) VALUES
(5555, 'Linares Profe', 'A', 'B', 'p@p', '$2y$10$TsXr/J2wg9fY.qbNijOdr.9FTfGHcD83jmSnTTLeJu4JaSPPjRg3K');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`NumeroEmpleado`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`Boleta`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`NumeroEmpleado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
