-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Gegenereerd op: 03 okt 2026 om 12:43
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
USE `maranzano`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `bank_session`
--

DROP TABLE IF EXISTS `bank_session`;
CREATE TABLE `bank_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `friend_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `bank_session`
--

TRUNCATE TABLE `bank_session`;
--
-- Gegevens worden geëxporteerd voor tabel `bank_session`
--

INSERT INTO `bank_session` (`id`, `player_id`, `friend_id`, `created_at`, `expires_at`) VALUES
(1, 10, 2, '2026-09-29 12:08:25', '2026-09-29 14:08:02'),
(2, 10, 2, '2026-09-30 17:02:22', '2026-10-01 17:02:17'),
(3, 10, 2, '2026-10-02 09:13:54', '2026-10-03 09:13:54');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `crime_session`
--

DROP TABLE IF EXISTS `crime_session`;
CREATE TABLE `crime_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `crime_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `succeeded` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `crime_session`
--

TRUNCATE TABLE `crime_session`;
--
-- Gegevens worden geëxporteerd voor tabel `crime_session`
--

INSERT INTO `crime_session` (`id`, `player_id`, `crime_id`, `created_at`, `expires_at`, `succeeded`) VALUES
(1, 10, 1, '2026-09-30 17:38:52', '2026-09-30 17:39:34', 1),
(2, 10, 1, '2026-09-30 17:41:19', '2026-09-30 17:42:06', 1),
(3, 10, 1, '2026-09-30 17:42:52', '2026-09-30 17:43:42', 1),
(4, 10, 1, '2026-09-30 17:44:10', '2026-09-30 17:45:00', 1),
(5, 10, 1, '2026-09-30 17:45:11', '2026-09-30 17:46:01', 1),
(6, 10, 1, '2026-09-30 17:46:16', '2026-09-30 17:47:06', 0),
(7, 10, 1, '2026-09-30 17:47:56', '2026-09-30 17:48:46', 0),
(8, 10, 1, '2026-10-01 13:21:27', '2026-10-01 13:22:17', 0),
(9, 10, 1, '2026-10-01 15:54:07', '2026-10-01 15:54:57', 0),
(10, 10, 1, '2026-10-01 19:47:57', '2026-10-01 19:48:47', 0),
(11, 10, 1, '2026-10-01 20:28:00', '2026-10-01 20:28:50', 1),
(12, 10, 1, '2026-10-01 20:28:53', '2026-10-01 20:29:43', 0),
(13, 10, 1, '2026-10-01 20:30:13', '2026-10-01 20:31:03', 0),
(14, 10, 1, '2026-10-01 20:33:47', '2026-10-01 20:34:37', 1),
(15, 10, 1, '2026-10-01 20:34:38', '2026-10-01 20:35:28', 0),
(16, 2, 1, '2026-10-02 16:47:29', '2026-10-02 16:48:19', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `friend_id` int NOT NULL,
  `is_confirmed` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `friends`
--

TRUNCATE TABLE `friends`;
--
-- Gegevens worden geëxporteerd voor tabel `friends`
--

INSERT INTO `friends` (`id`, `player_id`, `friend_id`, `is_confirmed`, `created_at`) VALUES
(1, 1, 2, 1, '2026-09-28 13:54:38'),
(3, 2, 1, 1, '2026-09-28 13:55:19'),
(4, 10, 2, 1, '2026-09-28 13:56:48'),
(5, 2, 10, 1, '2026-09-28 13:56:48');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `garage`
--

DROP TABLE IF EXISTS `garage`;
CREATE TABLE `garage` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `vehicle_type` int NOT NULL,
  `vehicle_id` int NOT NULL,
  `sold` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `garage`
--

TRUNCATE TABLE `garage`;
--
-- Gegevens worden geëxporteerd voor tabel `garage`
--

INSERT INTO `garage` (`id`, `player_id`, `vehicle_type`, `vehicle_id`, `sold`, `created_at`) VALUES
(1, 10, 1, 1, 0, '2026-10-01 13:13:36'),
(2, 10, 1, 2, 0, '2026-10-01 13:17:37'),
(3, 10, 1, 2, 0, '2026-10-01 20:28:00'),
(4, 10, 1, 1, 0, '2026-10-01 20:33:47');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hospital_session`
--

DROP TABLE IF EXISTS `hospital_session`;
CREATE TABLE `hospital_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `hospital_session`
--

TRUNCATE TABLE `hospital_session`;
-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `jail_session`
--

DROP TABLE IF EXISTS `jail_session`;
CREATE TABLE `jail_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `location_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `jail_session`
--

TRUNCATE TABLE `jail_session`;
--
-- Gegevens worden geëxporteerd voor tabel `jail_session`
--

INSERT INTO `jail_session` (`id`, `player_id`, `location_id`, `created_at`, `expires_at`) VALUES
(9, 10, 2, '2026-10-01 15:54:07', '2026-10-01 15:54:28'),
(10, 10, 2, '2026-10-01 19:47:56', '2026-10-01 19:48:17'),
(11, 10, 0, '2026-10-01 20:28:53', '2026-10-01 20:29:14'),
(12, 10, 0, '2026-10-01 20:30:13', '2026-10-01 20:30:34'),
(13, 10, 6, '2026-10-01 20:34:38', '2026-10-02 17:50:32'),
(14, 2, 6, '2026-10-02 16:47:29', '2026-10-02 17:43:28');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `login_user`
--

DROP TABLE IF EXISTS `login_user`;
CREATE TABLE `login_user` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `login_user`
--

TRUNCATE TABLE `login_user`;
--
-- Gegevens worden geëxporteerd voor tabel `login_user`
--

INSERT INTO `login_user` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES
(119, 14, '$2y$10$zGg9vZbQnNU8gKGs0dUR3.ApI4sgyXwQls8fj1moNI2zcVXjO7zOu', '2026-09-23 17:05:49', '2026-09-23 23:05:49', 1),
(120, 14, '$2y$10$zX3iRXrjhKQeO1FSdPsD0enPJvSAn/5RMEKI84bxcax9/bwP.aBp6', '2026-09-23 17:16:46', '2026-09-23 23:16:46', 1),
(121, 14, '$2y$10$ZTGfIP8pQN.cKQWADuE1A.s1aVibYsX4q9TqcHhMNaNlt9iHauJ0e', '2026-09-23 17:16:58', '2026-09-23 23:16:58', 1),
(122, 14, '$2y$10$NM36h6Ku1izqP4jnF1dJCOOhu1MHAMBR3G2FENPFWl1botTK5AdeO', '2026-09-23 17:17:39', '2026-09-23 23:17:39', 1),
(123, 14, '$2y$10$ly4n2cbYDoOqUHMuCsd4U.yKcVhMMAAfv4pk8.Ucz5EPs7UeKxp5y', '2026-09-23 17:21:51', '2026-09-22 23:21:51', 1),
(124, 2, '$2y$10$/uLTHCp2PixYHjZMzeqhNOnYWfOYo2a6jF6RBTQ7qaXqebdSiqiMm', '2026-09-23 20:48:50', '2026-09-23 02:48:49', 1),
(125, 14, '$2y$10$pt1PEpSjrm8p8l1FyyeTse3bt7rXtwt.Jiq4.Yxk9OCG.ggnEn92a', '2026-09-23 21:00:43', '2026-09-23 03:00:43', 1),
(126, 14, '$2y$10$KXfi4SdV6ng03PGDbs5Y3eXfOOzorwK521VJ92GCfnd.nVThZ9s52', '2026-09-24 09:40:12', '2026-09-23 15:40:12', 1),
(127, 14, '$2y$10$qxgMFF.6woa7NrLVv8Q6NuQo2BKoPC.tUAlR6IOo99veZEcoechUC', '2026-09-24 09:46:17', '2026-09-25 15:46:17', 1),
(128, 14, '$2y$10$piFrQfacI5aWPnRs0GZmHuMkUFu4iIN6oTZjF8q9WWEjWChoCW6jC', '2026-09-25 20:25:13', '2026-09-27 02:25:13', 1),
(129, 14, '$2y$10$a7MDpDsQaO2hGr2VCfvRzuUiIR00PGd6uOlFs1p9air9lYcOmvnYC', '2026-09-27 12:40:53', '2026-09-28 18:40:53', 1),
(130, 2, '$2y$10$OgXxvQdn9XrWqKkLafcYbuNsrHVpUpdJRC0e2swALG0B/af5xCXPC', '2026-09-27 17:15:20', '2026-09-28 23:15:20', 1),
(131, 14, '$2y$10$o17DnXDpsD1CZKZKZiMTP.Ee8a9NDUvHwLt3htKcXbB39xjahIKwq', '2026-09-27 17:25:35', '2026-09-28 23:25:35', 1),
(132, 2, '$2y$10$Y36rXn6XsGyZjaJchJV01.BAFgyMzKq8zs2kT0ANSvfN.r3AVEoeS', '2026-09-27 17:27:40', '2026-09-28 23:27:40', 1),
(133, 14, '$2y$10$S/jnFERjI1eBlcLzDcMS3emSCDt6mgOzB/L0kVzvT2jMz0mMpsXyi', '2026-09-28 09:25:21', '2026-09-29 15:25:21', 1),
(134, 14, '$2y$10$DIzrugJNAakl.Utbfw.NUuVl9b1J81F.n3EKk588w5opoM.LvZnOe', '2026-09-28 11:04:57', '2026-09-29 17:04:57', 1),
(135, 2, '$2y$10$R1DvfJ362uvsbxfsLuAtZeWQCMxuZZC2LUcEfkY3XRzG/Ym7PKH1C', '2026-09-28 14:17:43', '2026-09-29 20:17:43', 1),
(136, 14, '$2y$10$l77M/gyVfGyRw17CgufshuWWkmPmeza38MUMclEHJQpT5rAfjDUSO', '2026-09-28 15:22:51', '2026-09-29 21:22:50', 1),
(137, 2, '$2y$10$VEaK5QIKM/BmllyP.6BHYOGtWIreYj26FCEnI88Bk8ZzzauHUQXsW', '2026-09-28 15:31:22', '2026-09-29 21:31:22', 1),
(138, 2, '$2y$10$K/GRr56iQrRlal36io9ERuxIOHCoA4deoKAry1D7xBCZyLOxZ03HK', '2026-09-28 20:26:53', '2026-09-30 02:26:53', 1),
(139, 14, '$2y$10$yb0P4vDECy6oT1b.NZXBU.xlXIy/o4TGKLBQbC1VB6pmj5TtEqFwu', '2026-09-30 16:33:52', '2026-10-01 22:33:52', 1),
(140, 2, '$2y$10$eJRsUqKi7j1P5DaaPvBNT.Cyknug7mFxZfzGXtqOvVOKafIaiQTJe', '2026-09-30 19:12:45', '2026-10-02 01:12:45', 1),
(141, 2, '$2y$10$7.Wl8qKVJhRapG4.Y.LkXeyyN3dLTpxJrBLfQNrHmYvgoOpoHnSHG', '2026-10-01 21:07:21', '2026-10-03 03:07:21', 1),
(142, 14, '$2y$10$Vp55XgZSf4yxrkXTp5ohhOBptgFhB4wVnqX7Iqpce2l4TNoGM89SS', '2026-10-02 08:01:35', '2026-10-03 14:01:35', 1),
(143, 2, '$2y$10$WQeUm7tyQt6ZG.qn5aKzOOP55meT8GHQITEyOj./.ra31KeMzk7DG', '2026-10-02 16:47:21', '2026-10-03 22:47:21', 1),
(144, 2, '$2y$10$u4TvdWB5YAUOXLUu09Hxdeo32PV7JER6wH7jIMxvToNmO7RQ5UJ5.', '2026-10-02 17:46:37', '2026-10-03 23:46:37', 0),
(145, 14, '$2y$10$thvE7XZ1IhTl.8X.Xkiki.ZJmDPpfG1S7/u3STJ8qZGQw0L3V9sKO', '2026-10-03 12:25:19', '2026-10-04 18:25:19', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `family_id` int DEFAULT NULL,
  `rank` int NOT NULL DEFAULT '1',
  `progress` int NOT NULL DEFAULT '0',
  `cash` int NOT NULL DEFAULT '0',
  `bank` int NOT NULL DEFAULT '0',
  `location_id` int NOT NULL DEFAULT '0',
  `health` int NOT NULL DEFAULT '1000',
  `bullets` int NOT NULL DEFAULT '0',
  `shields` int NOT NULL DEFAULT '0',
  `deceased` tinyint NOT NULL DEFAULT '0',
  `killer_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_active` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `players`
--

TRUNCATE TABLE `players`;
--
-- Gegevens worden geëxporteerd voor tabel `players`
--

INSERT INTO `players` (`id`, `user_id`, `name`, `family_id`, `rank`, `progress`, `cash`, `bank`, `location_id`, `health`, `bullets`, `shields`, `deceased`, `killer_id`, `created_at`, `last_active`) VALUES
(1, 1, 'Zowiezo101', NULL, 1, 0, 10000, 0, 0, 1000, 0, 0, 0, NULL, '2026-09-21 14:37:12', '2026-09-28 20:29:27'),
(2, 2, 'TheToweler', NULL, 2, 120, 1500, 0, 6, 1300, 900, 1100, 0, NULL, '2026-09-21 14:37:24', '2026-10-02 17:50:33'),
(8, 14, 'Zowiezo102', NULL, 4, 0, 0, 0, 0, 1000, 0, 0, 1, 2, '2026-09-23 14:58:27', '2026-09-24 09:57:27'),
(9, 14, 'Zowiezo103', NULL, 1, 0, 0, 0, 2, 1000, 0, 0, 1, 2, '2026-09-24 09:57:07', '2026-09-25 20:48:32'),
(10, 14, 'Zowiezo104', NULL, 3, 3360, 43999, 50000, 6, 1700, 2500, 2900, 0, NULL, '2026-09-25 20:49:08', '2026-10-02 17:43:35');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reset_pass`
--

DROP TABLE IF EXISTS `reset_pass`;
CREATE TABLE `reset_pass` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `reset_pass`
--

TRUNCATE TABLE `reset_pass`;
--
-- Gegevens worden geëxporteerd voor tabel `reset_pass`
--

INSERT INTO `reset_pass` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES
(12, 14, 'f4e54e0dc32030f8740f00993fef0cbba0b79ca2d867934552615e0b4f160cc2', '2026-09-23 16:17:36', '2026-09-23 16:47:36', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `shop_session`
--

DROP TABLE IF EXISTS `shop_session`;
CREATE TABLE `shop_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `shop_session`
--

TRUNCATE TABLE `shop_session`;
--
-- Gegevens worden geëxporteerd voor tabel `shop_session`
--

INSERT INTO `shop_session` (`id`, `player_id`, `created_at`, `expires_at`) VALUES
(1, 10, '2026-09-26 16:46:32', '2026-09-26 16:46:32'),
(2, 10, '2026-09-26 16:52:35', '2026-09-26 18:52:35'),
(6, 10, '2026-09-27 17:12:04', '2026-09-27 19:12:04'),
(7, 2, '2026-09-27 17:28:41', '2026-09-27 19:28:41'),
(8, 10, '2026-09-28 09:26:10', '2026-09-28 11:26:10'),
(9, 2, '2026-09-28 14:18:09', '2026-09-28 16:18:09'),
(10, 10, '2026-09-28 20:33:13', '2026-09-28 22:33:13'),
(11, 10, '2026-09-29 11:49:34', '2026-09-29 12:49:34'),
(12, 10, '2026-09-30 19:20:51', '2026-09-30 21:20:51'),
(13, 10, '2026-10-01 13:14:34', '2026-10-01 15:14:34');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `travel_session`
--

DROP TABLE IF EXISTS `travel_session`;
CREATE TABLE `travel_session` (
  `id` int NOT NULL,
  `player_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `travel_session`
--

TRUNCATE TABLE `travel_session`;
--
-- Gegevens worden geëxporteerd voor tabel `travel_session`
--

INSERT INTO `travel_session` (`id`, `player_id`, `created_at`, `expires_at`) VALUES
(1, 10, '2026-09-25 21:13:02', '2026-09-25 21:24:02'),
(2, 10, '2026-09-25 21:24:21', '2026-09-25 21:25:21'),
(3, 10, '2026-09-25 21:27:40', '2026-09-25 21:57:40'),
(4, 10, '2026-09-26 09:11:04', '2026-09-26 09:11:04'),
(5, 10, '2026-09-26 09:21:10', '2026-09-26 09:21:10'),
(6, 10, '2026-09-26 09:22:25', '2026-09-26 09:22:25'),
(7, 10, '2026-09-26 09:23:44', '2026-09-26 09:23:44'),
(8, 10, '2026-09-26 09:24:18', '2026-09-26 09:54:18'),
(9, 10, '2026-09-26 16:21:36', '2026-09-26 16:51:36'),
(10, 10, '2026-09-27 16:57:49', '2026-09-27 17:27:49'),
(11, 2, '2026-09-27 17:15:46', '2026-09-27 17:45:46'),
(12, 10, '2026-09-28 09:25:48', '2026-09-28 09:55:48'),
(13, 2, '2026-09-28 14:18:35', '2026-09-28 14:48:35'),
(14, 10, '2026-09-28 20:33:04', '2026-09-28 21:03:04'),
(15, 10, '2026-09-29 11:49:17', '2026-09-29 12:19:17'),
(16, 10, '2026-09-30 19:09:36', '2026-09-30 19:39:36'),
(17, 10, '2026-10-01 13:13:31', '2026-10-01 13:43:31'),
(18, 10, '2026-10-01 20:26:36', '2026-10-01 20:56:36'),
(19, 2, '2026-10-01 21:08:25', '2026-10-01 21:38:25'),
(20, 10, '2026-10-02 16:48:06', '2026-10-02 17:18:06');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass_hash` varchar(255) NOT NULL,
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `users`
--

TRUNCATE TABLE `users`;
--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass_hash`, `is_verified`, `type`, `created_at`) VALUES
(1, 'Zowiezo101', 'zoegeurts@gmail2.com', '$2y$10$MbWPbllCM8FBQFbigsY5oulCBfwUFqWUd5ihqbO.2dyDvI6VzGUVq', 1, 0, '2026-09-01 13:36:42'),
(2, 'TheToweler', 'wilcovdb17@gmail.com', '$2y$10$nj5rjD8kEq1snyssRMbrse8CAubSrAhobiVQquV.wwOo1.WuoGoLS', 1, 0, '2026-09-01 13:44:03'),
(14, 'Zowiezo102', 'zoegeurts@gmail.com', '$2y$10$PHnvpBQ/bVmgQOSsQirXhOEmFUc4oYD9i9xBkkq0yhXHDJ2jwdFTe', 1, 0, '2026-09-23 14:58:27');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `verify_user`
--

DROP TABLE IF EXISTS `verify_user`;
CREATE TABLE `verify_user` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `used` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tabel leegmaken voor invoegen `verify_user`
--

TRUNCATE TABLE `verify_user`;
--
-- Gegevens worden geëxporteerd voor tabel `verify_user`
--

INSERT INTO `verify_user` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES
(16, 14, '0b7a723455ad2d121b2ca94b68772b5c38c0da0e92110b5676940878dd22f0a4', '2026-09-23 14:58:32', '2026-09-23 15:28:32', 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `bank_session`
--
ALTER TABLE `bank_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `crime_session`
--
ALTER TABLE `crime_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `hospital_session`
--
ALTER TABLE `hospital_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `jail_session`
--
ALTER TABLE `jail_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `login_user`
--
ALTER TABLE `login_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `reset_pass`
--
ALTER TABLE `reset_pass`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `shop_session`
--
ALTER TABLE `shop_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `travel_session`
--
ALTER TABLE `travel_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexen voor tabel `verify_user`
--
ALTER TABLE `verify_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `bank_session`
--
ALTER TABLE `bank_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `crime_session`
--
ALTER TABLE `crime_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT voor een tabel `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT voor een tabel `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `hospital_session`
--
ALTER TABLE `hospital_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `jail_session`
--
ALTER TABLE `jail_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `login_user`
--
ALTER TABLE `login_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT voor een tabel `players`
--
ALTER TABLE `players`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `reset_pass`
--
ALTER TABLE `reset_pass`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `shop_session`
--
ALTER TABLE `shop_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT voor een tabel `travel_session`
--
ALTER TABLE `travel_session`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `verify_user`
--
ALTER TABLE `verify_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
