-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 04:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sswms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bcp_lgu2_g67_users`
--

CREATE TABLE `bcp_lgu2_g67_users` (
  `user_name` varchar(100) DEFAULT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) DEFAULT NULL,
  `user_type` enum('ADMIN','SOCIALWORKERS','USERS') DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `user_fname` text DEFAULT NULL,
  `user_lname` text DEFAULT NULL,
  `user_link` varchar(50) DEFAULT NULL,
  `user_address` text NOT NULL,
  `user_number` text DEFAULT NULL,
  `profile_picture` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bcp_lgu2_g67_users`
--

INSERT INTO `bcp_lgu2_g67_users` (`user_name`, `user_email`, `user_password`, `user_type`, `user_id`, `user_fname`, `user_lname`, `user_link`, `user_address`, `user_number`, `profile_picture`) VALUES
(NULL, 'cologenangejoh@gmail.com', '$2y$10$xJy3AbW.Ogwaw90OuAIWOuEazuTxBMlsIpiFSyrgaF5Ej.wGup0t6', '', 'user_id6735641901a6c5.85472592', 'colo', 'ange', NULL, 'cologenangejoh@gmail.com', '12312341324', NULL),
(NULL, 'gelisne2s11sd@gmail.com', '$2y$10$nWZKZALzWj4.BEcviK5l0eDOSiXGLi8a.Fp6U9gubjyzRmGdTuIIC', 'USERS', '0', 'angelinesd', 'basassd', NULL, '123sdf', '45245245', NULL),
(NULL, 'henrybuena052@gmail.com', '$2y$10$QNsMEOLphVs0Od3m.CaY3eJJ/KCav8JNPnXL8ZjZNulpQ/OImTt/i', '', 'user_id6734424f4f4d66.81005113', 'henry', 'buena', NULL, 'sdsdsd', '45245245', NULL),
(NULL, 'hernycobuena7@gmail.com', '$2y$10$TcK.b3muSzYDMHFIzC2NcO46bBjHNIC0kwnD8jpLjZJ7BhLgHxY02', '', 'user_67d2daf927ba70.37591103', 'henry', 'ange', NULL, 'hernycobuena7@gmail.com', '1232', NULL),
('kael', 'kael@gmail.com', '123', 'USERS', '12', 'kael', 'asuncion', NULL, 'qwddsaqwdasdfewa', '069456923', NULL),
(NULL, 'test1@gmail.com', '$2y$10$kCXL2.a0lgrQKXeh0YfkA.HykO4kregb02XWHZhMmwfgNJlGTMoa6', '', 'user_67ab86d1bcbbf8.57292873', 'henry', 'buena', NULL, '123@gmail.com', '09279546521', NULL),
(NULL, 'test2@gmail.com', '$2y$10$1upGax3BBsq/fXpYa1ZJee8Bjsr1EL8BWR41GaRvi2F5jlsqmTWVy', '', 'user_67b6fdeae36da7.76776686', 'henry', 'buena', NULL, '123@gmail.com', '09279546521', NULL),
(NULL, 'test5@gmail.com', '$2y$10$gRiItm9z9JHQrCKgChIve.GSfQaRnV11rAXd6tnMooor5z9h4B5Hm', '', 'user_67bd74d8e8b4f9.33589158', 'henry', 'buena', NULL, '123@gmail.com', '09279546521', NULL),
('lloyd', 'test@gmail.com', '$2y$10$Pjny/C6KeOMF4UNFmW7cxuj2UupPucF3qNt/Ty/YgZtfcgnNKsLc.', 'ADMIN', '121', 'Lloyd', 'Lagman', '0', 'qweqw21ewq', '2147483647', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bcp_lgu2_g67_users`
--
ALTER TABLE `bcp_lgu2_g67_users`
  ADD PRIMARY KEY (`user_email`),
  ADD UNIQUE KEY `profile_picture` (`profile_picture`) USING HASH,
  ADD KEY `user_link` (`user_link`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
