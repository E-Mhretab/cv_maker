-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 23 sep 2025 om 09:52
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luxdemoestate_cv`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `timestamp`) VALUES
(1, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:04:33'),
(2, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:04:47'),
(4, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:05:34'),
(5, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:09:20'),
(6, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:09:39'),
(7, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:12:44'),
(8, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:12:55'),
(9, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:13:12'),
(10, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:13:24'),
(11, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:14:03'),
(12, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:14:16'),
(13, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '138.199.50.135', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-18 10:36:01'),
(14, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '138.199.50.135', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-18 10:36:22'),
(17, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '217.198.193.220', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 11:12:33'),
(18, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '217.198.193.220', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 11:12:46'),
(21, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:05:32'),
(22, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:05:49'),
(23, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:07:04'),
(25, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:12:45'),
(26, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:13:07'),
(29, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:18:22'),
(30, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:18:49'),
(36, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:40:14'),
(37, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:40:27'),
(39, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:43:43'),
(40, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:51:41'),
(45, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:07:17'),
(46, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2025-09-18 14:08:16'),
(47, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:11:13'),
(48, 1, 'INSERT', 'cv', 42, NULL, '{\"name\":\"ash\",\"email\":\"ash@gmail.com\",\"template_type\":1,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:11:40'),
(49, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:12:09'),
(50, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:12:23'),
(52, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:13:53'),
(53, 1, 'DELETE', 'cv', 42, '{\"id\":42,\"name\":\"ash\",\"address\":\"Juliettepad\",\"phone_number\":\"+3164363218229\",\"email\":\"ash@gmail.com\",\"date_of_birth\":\"2025-09-07\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"ash@gmail.com\",\"user_id\":1,\"template_type\":1,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:14:48'),
(58, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:15:28'),
(68, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:21:08'),
(73, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:03:13'),
(74, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:05:02'),
(81, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:07:48'),
(83, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:08:41'),
(84, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:11:02'),
(85, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:11:23'),
(86, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:54:11'),
(87, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:54:24'),
(90, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:09:28'),
(93, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:10:19'),
(94, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:29:05'),
(97, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:30:03'),
(98, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:33:42'),
(101, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:34:14'),
(102, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:44:34'),
(105, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:45:02'),
(106, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:48:01'),
(107, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:48:12'),
(108, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:55:05'),
(109, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:55:18'),
(110, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:01'),
(113, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:33');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-test|127.0.0.1', 'i:1;', 1758552731),
('laravel-cache-test|127.0.0.1:timer', 'i:1758552731;', 1758552731);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cv`
--

CREATE TABLE `cv` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `linkedin_profile` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `profile_summary` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cv`
--

INSERT INTO `cv` (`id`, `name`, `address`, `phone_number`, `email`, `date_of_birth`, `linkedin_profile`, `portfolio`, `profile_summary`, `user_id`) VALUES
(1, 'John Doe', '123 Main Street, Amsterdam, Netherlands', '+31 6 12345678', 'john.doe@example.com', '1995-03-15', 'https://linkedin.com/in/john-doe', 'https://johndoe.dev', 'Experienced software developer with 5+ years in web development. Passionate about creating efficient and user-friendly applications using modern technologies.', NULL),
(2, 'Jane Smith', '456 Oak Avenue, Rotterdam, Netherlands', '+31 6 87654321', 'jane.smith@example.com', '1992-07-22', 'https://linkedin.com/in/jane-smith', 'https://janesmith.dev', 'Full-stack developer specializing in React and Node.js. Strong background in database design and API development. Always eager to learn new technologies.', NULL),
(6, 'Semere1', 'Delft station 4', NULL, 'Semere@gmail.com', '2025-05-05', '', 'https://luxdemoestate.com/E-N//cv_create_form.php', 'This is Semere', NULL),
(7, 'Mirian Trujillo', 'Fuengirola', NULL, 'mirian@businessdevelopment.es', '1995-08-01', 'https://www.linkedin.com/in/miriantrujillomerino', 'https://github.com/miritru/', 'Computer Engineering student | Full Stack Developer in training | Java, Python. Constantly developing and learning, with the goal of continuing my professional career in the IT sector.', NULL),
(45, 'Abreham1', 'sdfasdafa', '+1234949948', 'abreham@gmail.com', '2025-09-02', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', NULL),
(46, 'test', 'test@gmail.com', '12454554554554', 'test@gmail.com', '2025-09-02', NULL, NULL, 'test@gmail.com', 1),
(47, 'test1', 'test1@gmail.com', '124343434', 'test1@gmail.com', '2025-09-02', NULL, NULL, 'test1@gmail.com', 1),
(48, 'test', 'test@gmail.com', '12344454', 'test@gmail.com', '2025-09-01', NULL, NULL, 'test@gmail.com', 42);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cv_metadata`
--

CREATE TABLE `cv_metadata` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `template_type` smallint(3) DEFAULT 1,
  `is_public` tinyint(1) DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cv_metadata`
--

INSERT INTO `cv_metadata` (`id`, `cv_id`, `template_type`, `is_public`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-09-20 08:00:00', '2025-09-20 08:00:00', '2025-09-20 08:00:00'),
(2, 2, 2, 0, NULL, '2025-09-21 12:30:00', '2025-09-21 12:30:00'),
(3, 6, 2, 1, '2025-09-12 11:43:47', '2025-09-09 13:14:13', '2025-09-12 13:43:47'),
(4, 7, 3, 0, NULL, '2025-09-12 06:44:18', '2025-09-12 06:44:18'),
(25, 45, 0, 1, '2025-09-19 06:07:50', '2025-09-19 08:05:19', '2025-09-19 08:07:50'),
(27, 46, 2, 0, NULL, '2025-09-22 13:59:35', '2025-09-22 13:59:35'),
(28, 47, 2, 0, NULL, '2025-09-22 14:14:25', '2025-09-22 14:14:25'),
(29, 48, 1, 0, NULL, '2025-09-22 14:58:09', '2025-09-22 14:58:09');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `education`
--

CREATE TABLE `education` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `degree` varchar(150) NOT NULL,
  `institution` varchar(150) NOT NULL,
  `education_start` date DEFAULT NULL,
  `education_end` date DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `education`
--

INSERT INTO `education` (`id`, `cv_id`, `degree`, `institution`, `education_start`, `education_end`, `is_current`, `description`) VALUES
(1, 1, 'Bachelor of Computer Science', 'University of Amsterdam', '2018-09-01', '2022-06-30', 0, 'Specialized in software engineering and database systems. Graduated with honors.'),
(2, 2, 'Master of Software Engineering', 'Delft University of Technology', '2020-09-01', NULL, 1, 'Advanced studies in software architecture and distributed systems.'),
(3, 2, 'Bachelor of Information Technology', 'Eindhoven University of Technology', '2016-09-01', '2020-06-30', 0, 'Foundation in computer science with focus on web technologies.'),
(4, 2, 'Automotive Technician Level 1 & 2', 'ROC Mondriaan', '2020-01-01', '2023-12-31', 0, 'Completed automotive technician training'),
(11, 6, 'Lewyer', 'ROC', '2025-06-03', NULL, 1, ''),
(12, 7, 'Computer Engineering', 'National University of Distance Education', '2022-09-01', NULL, 1, 'Currently studying Computer Engineering'),
(13, 7, 'Food Science and Technology', 'University of Granada', '2017-09-01', '2022-07-20', 0, NULL),
(36, 1, 'Creative Software Development', 'Grafisch Lyceum Rotterdam', '2023-08-01', NULL, 1, 'Currently studying software development'),
(37, 1, 'Middlebare School (MAVO)', 'Einstein Lyceum', '2019-08-01', '2023-07-31', 0, 'Completed secondary education'),
(39, 45, 'dfsfdsa', 'asdia', '2025-09-02', NULL, 1, 'https://luxdemoestate.com/E-N/create/cv_create_form.php'),
(40, 47, 'test1@gmail.com', 'test1@gmail.com', '2025-09-03', NULL, 1, 'test1@gmail.com'),
(41, 48, 'test@gmail.com', 'test@gmail.com', '2025-09-01', NULL, 1, 'test@gmail.com');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `hobbies`
--

CREATE TABLE `hobbies` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `hobby_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `hobbies`
--

INSERT INTO `hobbies` (`id`, `cv_id`, `hobby_name`, `description`) VALUES
(1, 1, 'Programming', 'Open source contributions and personal projects'),
(2, 1, 'Photography', 'Digital photography and photo editing'),
(3, 1, 'Fitness', 'Regular gym workouts and outdoor activities'),
(4, 2, 'Reading', 'Technical books and software development blogs'),
(5, 2, 'Hiking', 'Weekend hiking trips and nature photography'),
(6, 2, 'Fitness', 'Regular physical exercise and training'),
(7, 2, 'Walking', 'Enjoying outdoor walks and nature'),
(15, 6, 'GYM', 'I am a bodybuilder'),
(16, 7, 'Music', 'Rock/indie music, going to concerts'),
(17, 7, 'Board games', 'Playing board games with my family and friends'),
(41, 1, 'Front-end Development', 'Building responsive web applications'),
(42, 1, 'Learning Technologies', 'Continuously learning new programming languages and frameworks'),
(43, 1, 'Team Collaboration', 'Working effectively in development teams'),
(44, 1, 'Problem Solving', 'Analyzing and solving complex programming challenges'),
(45, 1, 'Creative Thinking', 'Designing innovative solutions and user experiences'),
(46, 47, 'test1@gmail.com', 'test1@gmail.com'),
(47, 48, 'test@gmail.com', 'test@gmail.com');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `language_name` varchar(100) NOT NULL,
  `proficiency` enum('basic','conversational','fluent','native') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `languages`
--

INSERT INTO `languages` (`id`, `cv_id`, `language_name`, `proficiency`) VALUES
(1, 1, 'English', 'fluent'),
(2, 1, 'Dutch', 'native'),
(3, 1, 'Spanish', 'conversational'),
(4, 2, 'English', 'fluent'),
(5, 2, 'German', 'basic'),
(8, 45, 'tigrinya', 'fluent'),
(9, 47, 'test1@gmail.com', 'fluent'),
(10, 48, 'test@gmail.com', 'basic');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Gegevens worden geëxporteerd voor tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_09_22_101934_create_sessions_table', 1),
(2, '0001_01_01_000001_create_cache_table', 2),
(3, '0001_01_01_000002_create_jobs_table', 3),
(4, '2025_09_22_102511_add_payload_column_to_user_sessions_table', 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `skills`
--

INSERT INTO `skills` (`id`, `cv_id`, `skill_name`, `description`) VALUES
(1, 1, 'PHP', 'Backend development with PHP and MySQL'),
(2, 1, 'JavaScript', 'Frontend development with vanilla JS and frameworks'),
(3, 1, 'HTML/CSS', 'Responsive web design and modern CSS techniques'),
(4, 2, 'React', 'Frontend development with React and Redux'),
(5, 2, 'Node.js', 'Backend development with Node.js and Express'),
(6, 2, 'TypeScript', 'Type-safe JavaScript development'),
(7, 45, 'php', 'good'),
(8, 45, 'css', 'very good'),
(9, 47, 'test1@gmail.com', 'test1@gmail.com'),
(10, 48, 'test@gmail.com', 'test@gmail.com');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user','guest') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `is_active`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'admin', 'admin@cvsystem.com', '$2y$12$PARQcIq4w9BqakCNtEPF0OH0TSTZ5I0.YjQPX4.x74hUnE6YQttym', 'admin', 1, '2025-09-12 07:55:16', '2025-09-19 10:03:33', '2025-09-19 10:03:33'),
(42, 'test', 'test@gmail.com', '$2y$12$cmHBVKSL2w5uNnpSk8LAteep2h1EZi3l7SYMqMiIgy/H9q0EA1fky', 'user', 1, '2025-09-22 12:57:13', '2025-09-22 12:57:13', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payload` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `created_at`, `expires_at`, `device_id`, `refresh_token`, `last_activity`, `payload`) VALUES
('0DRfglul38FDTRkIdJ7g4aJXcQGZgRprdprkVSGd', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-22 13:07:24', '2025-09-22 15:07:24', NULL, NULL, '2025-09-22 13:07:24', 'a:4:{s:6:\"_token\";s:40:\"N7WbZwudBp6z9oG1gHdZp7ectxPCXO1m3oik8i7v\";s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}s:9:\"_previous\";a:1:{s:3:\"url\";s:25:\"http://127.0.0.1:8000/cvs\";}s:50:\"login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d\";i:1;}'),
('cHIeqlw7Ale4kZrVNIG2vsCuyp41p28XIqCRoJh7', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:29:17', '2025-09-22 12:29:17', NULL, NULL, '2025-09-22 10:29:17', 'a:2:{s:6:\"_token\";s:40:\"mPYsGEhqM3y67cGSLnHpgGl6DzPpRRXhyNCpdJtO\";s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('dEuhgQZ9mG1ddTkU3EB201JdrNezepUhVi0iCXDo', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:29:07', '2025-09-22 12:29:07', NULL, NULL, '2025-09-22 10:29:07', 'a:3:{s:6:\"_token\";s:40:\"qduMTJNhjmQ5PgIJiMBJRjBNIApSyXCq6jUWpUzd\";s:9:\"_previous\";a:1:{s:3:\"url\";s:30:\"http://127.0.0.1:8000/register\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('E1uvc8U31uaMRb380GLa2GMRDtYFzRKctKV2wMMJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-23 05:25:55', '2025-09-23 07:25:55', NULL, NULL, '2025-09-23 05:25:55', 'a:3:{s:6:\"_token\";s:40:\"Y6phh9BFR6Dar6hdRQBDM611ZFHAQ4E69Tv0Mnp6\";s:9:\"_previous\";a:1:{s:3:\"url\";s:21:\"http://127.0.0.1:8000\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('GMEKnNZBqpgGO4cUqlfqmhcScG9rCyVQAmnDdgBM', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:29:14', '2025-09-22 12:29:14', NULL, NULL, '2025-09-22 10:29:14', 'a:3:{s:6:\"_token\";s:40:\"jixPTlQhPjkx8mAaapsRpvmNmghD5Xo68swHau2t\";s:9:\"_previous\";a:1:{s:3:\"url\";s:30:\"http://127.0.0.1:8000/register\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('L5ADFiBsYruVyRxY36XViHBAaLzorQCLgCAzmJKY', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:23:12', '2025-09-22 12:23:12', NULL, NULL, '2025-09-22 10:23:12', 'a:3:{s:6:\"_token\";s:40:\"X53B0UsFwTp4gA5hZBaLbQQjaocjg6RJpQkmBZnZ\";s:9:\"_previous\";a:1:{s:3:\"url\";s:30:\"http://127.0.0.1:8000/register\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('okdUQrsCLTWvms4omkXFwU0lrcF2qFIbGb5wKF0y', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:23:39', '2025-09-22 12:23:39', NULL, NULL, '2025-09-22 10:23:39', 'a:2:{s:6:\"_token\";s:40:\"AsMB2KuZP9sN1G4xZZka6uMImfNFErmXkjdQrlh7\";s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('PhqACOwHb8m6b77EI3fVbYXX8GLT407h9iHHonBA', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:22:59', '2025-09-22 12:22:59', NULL, NULL, '2025-09-22 10:22:59', 'a:3:{s:6:\"_token\";s:40:\"lqAfVoPMr8kumIg3xREArcdaLieTR3aZAYOIUmyS\";s:9:\"_previous\";a:1:{s:3:\"url\";s:30:\"http://127.0.0.1:8000/register\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('UlYcuhd9IEQO65JZV8cYWGhuQfDxS3J7xJlSthQN', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:25:49', '2025-09-22 12:25:49', NULL, NULL, '2025-09-22 10:25:49', 'a:3:{s:6:\"_token\";s:40:\"U8GLDAUQ48jP2qx8vYRkgPj8cC16ZgBi1MwjSsmi\";s:9:\"_previous\";a:1:{s:3:\"url\";s:30:\"http://127.0.0.1:8000/register\";}s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}'),
('vc17ksz7xHdf0w6imvrylAIpNOTIyikjwAxzqFXT', NULL, '127.0.0.1', 'curl/8.4.0', '2025-09-22 10:29:11', '2025-09-22 12:29:11', NULL, NULL, '2025-09-22 10:29:11', 'a:2:{s:6:\"_token\";s:40:\"bBiFHEJnPU0soMkiVKPOfe69BcJ1cQ8CIuutFKhu\";s:6:\"_flash\";a:2:{s:3:\"old\";a:0:{}s:3:\"new\";a:0:{}}}');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `work_experience`
--

CREATE TABLE `work_experience` (
  `id` int(11) NOT NULL,
  `cv_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `work_start` date DEFAULT NULL,
  `work_end` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_current` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `work_experience`
--

INSERT INTO `work_experience` (`id`, `cv_id`, `job_title`, `company_name`, `work_start`, `work_end`, `description`, `is_current`) VALUES
(1, 1, 'Senior Software Developer', 'TechCorp Solutions', '2022-01-01', NULL, 'Leading development of web applications using React, Node.js, and PostgreSQL. Mentoring junior developers and implementing best practices.', 1),
(2, 1, 'Full Stack Developer', 'Digital Innovations', '2020-06-01', '2021-12-31', 'Developed and maintained web applications using PHP, MySQL, and JavaScript. Collaborated with design team to create user-friendly interfaces.', 0),
(3, 2, 'Frontend Developer', 'WebStudio Pro', '2021-03-01', NULL, 'Creating responsive web applications with React and TypeScript. Focus on performance optimization and user experience.', 1),
(4, 2, 'Crew Member', 'Burger King', '2023-01-01', '2024-12-31', 'Efficiently serving customers at the register and taking orders with a customer-friendly attitude.', 0),
(5, 2, 'Crew Member', 'McDonald', '2020-01-01', '2023-12-31', 'Preparing food while following strict hygiene and safety regulations.', 0),
(15, 6, 'CEO', 'IT GOLD', '2025-05-01', NULL, 'It is going well', 1),
(16, 7, 'Food Quality and Safety Technician', 'Embutidos Moreno Plaza', '2020-02-02', '2025-07-02', 'Supplier management. Traceability control. Audits (IFS, Health, Iberian). Quality control. Optimization.', 0),
(17, 7, 'Food Quality and Safety Technician', 'Avomix', '2019-11-02', '2020-01-30', 'Plant and documentary quality control.', 0),
(18, 7, 'Food Quality and Safety Technician', 'Puleva Food', '2017-02-02', '2017-11-30', 'Quality control focused on cleaning (CIP).', 0),
(40, 1, 'Web Developer', 'Car Wash Company', '2023-06-01', '2023-08-31', 'Designed and built a modern, user-friendly website. Ensured clear access to services and pricing for customers. Optimized site responsiveness for desktop and mobile devices.', 1),
(41, 1, 'Owner', 'Prestige Elegance', '2022-12-01', '2024-10-31', 'Managed a webshop, overseeing design, development, and customer service. Built the website using HTML, CSS, JavaScript, and PHP. Implemented marketing strategies to drive growth.', 0),
(42, 1, 'Delivery Driver', 'Chinese Restaurant Zilverrijin', '2022-01-01', '2025-08-31', 'Delivered orders timely and accurately while ensuring excellent customer service. Minimized delivery routes to save time and improve efficiency. Enhanced organizational skills, time management, and customer focus.', 0),
(44, 45, 'dsadf', 'sdf', '2025-09-09', NULL, 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 1),
(45, 47, 'test1@gmail.com', 'test1@gmail.com', '2025-09-03', NULL, 'test1@gmail.com', 1),
(46, 48, 'test@gmail.com', 'test@gmail.com', '2025-09-02', NULL, 'test@gmail.com', 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_timestamp` (`timestamp`);

--
-- Indexen voor tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexen voor tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexen voor tabel `cv`
--
ALTER TABLE `cv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_user_id` (`user_id`);

--
-- Indexen voor tabel `cv_metadata`
--
ALTER TABLE `cv_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cv_metadata` (`cv_id`),
  ADD KEY `idx_cv_metadata_template_type` (`template_type`),
  ADD KEY `idx_cv_metadata_is_public` (`is_public`),
  ADD KEY `idx_cv_metadata_published_at` (`published_at`);

--
-- Indexen voor tabel `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_id` (`cv_id`);

--
-- Indexen voor tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexen voor tabel `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_id` (`cv_id`);

--
-- Indexen voor tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexen voor tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_languages_cv_id` (`cv_id`),
  ADD KEY `idx_languages_name` (`language_name`),
  ADD KEY `idx_languages_proficiency` (`proficiency`);

--
-- Indexen voor tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_skills_cv_id` (`cv_id`),
  ADD KEY `idx_skills_name` (`skill_name`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_username` (`username`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexen voor tabel `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sessions_user_id` (`user_id`),
  ADD KEY `idx_sessions_expires` (`expires_at`),
  ADD KEY `idx_device_id` (`device_id`),
  ADD KEY `idx_refresh_token` (`refresh_token`),
  ADD KEY `idx_last_activity` (`last_activity`),
  ADD KEY `idx_user_device` (`user_id`,`device_id`);

--
-- Indexen voor tabel `work_experience`
--
ALTER TABLE `work_experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_id` (`cv_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT voor een tabel `cv`
--
ALTER TABLE `cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT voor een tabel `cv_metadata`
--
ALTER TABLE `cv_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT voor een tabel `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT voor een tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT voor een tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT voor een tabel `work_experience`
--
ALTER TABLE `work_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `cv`
--
ALTER TABLE `cv`
  ADD CONSTRAINT `cv_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Beperkingen voor tabel `cv_metadata`
--
ALTER TABLE `cv_metadata`
  ADD CONSTRAINT `cv_metadata_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `hobbies`
--
ALTER TABLE `hobbies`
  ADD CONSTRAINT `hobbies_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `languages_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `work_experience`
--
ALTER TABLE `work_experience`
  ADD CONSTRAINT `work_experience_ibfk_1` FOREIGN KEY (`cv_id`) REFERENCES `cv` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
