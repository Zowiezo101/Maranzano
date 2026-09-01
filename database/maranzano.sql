-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Gegenereerd op: 01 sep 2026 om 13:50
-- Serverversie: 8.3.0
-- PHP-versie: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maranzano`
--
CREATE DATABASE IF NOT EXISTS `maranzano` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `maranzano`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `users`
--

TRUNCATE TABLE `users`;
--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass_hash`, `token`, `is_verified`, `type`, `created_at`) VALUES
(1, 'Zowiezo101', 'zoegeurts@gmail.com', '$2y$10$JsXQfQQXT4BL1FVkETbRpO/DyaW4aHuKrpFEw3ceeZhKpnOE4cDVi', 'a7c52a3cca3c515ad50c062da782bef81d6ec530aa8d033894bb53e9c7d554e343a12350556c2fde1ddbd116b97dd6352915', 0, 0, '2026-09-01 13:36:42'),
(2, 'TheToweler', 'wilcovdb17@gmail.com', '$2y$10$pMT8mTu1KUaWzVuaODGD2O2VHGNWk2Jr/cmeaHLrPo2JCpCytS6oS', '5aaafe35f3ea4e7f7818bb1f9b81d621b2d8960f0fc7fc4a21bf819da8eecc33a7afae68877b4728bc1462db5b48ca614435', 0, 0, '2026-09-01 13:44:03');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
