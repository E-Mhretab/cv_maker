-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Gegenereerd op: 22 sep 2025 om 10:13
-- Serverversie: 10.11.14-MariaDB
-- PHP-versie: 8.4.11

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
(3, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-17 14:05:11'),
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
(19, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '217.198.193.220', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 11:31:15'),
(20, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '217.198.193.220', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 11:31:15'),
(21, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:05:32'),
(22, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:05:49'),
(23, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:07:04'),
(25, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:12:45'),
(26, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:13:07'),
(29, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:18:22'),
(30, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:18:49'),
(35, 32, 'LOGIN', 'users', 32, NULL, '{\"username\":\"jan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:34:41'),
(36, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:40:14'),
(37, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:40:27'),
(39, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:43:43'),
(40, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:51:41'),
(42, 34, 'LOGIN', 'users', 34, NULL, '{\"username\":\"fey\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:53:41'),
(44, 35, 'LOGIN', 'users', 35, NULL, '{\"username\":\"nath\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 13:59:08'),
(45, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:07:17'),
(46, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2025-09-18 14:08:16'),
(47, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:11:13'),
(48, 1, 'INSERT', 'cv', 42, NULL, '{\"name\":\"ash\",\"email\":\"ash@gmail.com\",\"template_type\":1,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:11:40'),
(49, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:12:09'),
(50, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:12:23'),
(51, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2025-09-18 14:13:32'),
(52, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:13:53'),
(53, 1, 'DELETE', 'cv', 42, '{\"id\":42,\"name\":\"ash\",\"address\":\"Juliettepad\",\"phone_number\":\"+3164363218229\",\"email\":\"ash@gmail.com\",\"date_of_birth\":\"2025-09-07\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"ash@gmail.com\",\"user_id\":1,\"template_type\":1,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:14:48'),
(54, 35, 'DELETE', 'cv', 41, '{\"id\":41,\"name\":\"nath\",\"address\":\"Juliettepa\",\"phone_number\":\"+31643618222\",\"email\":\"nath@gmail.com\",\"date_of_birth\":\"2025-09-14\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"nath@gmail.com\",\"user_id\":35,\"template_type\":2,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:14:53'),
(55, 34, 'DELETE', 'cv', 40, '{\"id\":40,\"name\":\"fey\",\"address\":\"jdan\",\"phone_number\":\"+316436222\",\"email\":\"fey@gmail.com\",\"date_of_birth\":\"2025-09-10\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"fey@gmail.com\",\"user_id\":34,\"template_type\":1,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:14:58'),
(56, 33, 'DELETE', 'cv', 39, '{\"id\":39,\"name\":\"nee\",\"address\":\"Juliettepad\",\"phone_number\":\"+31643618229\",\"email\":\"nee@gmail.com\",\"date_of_birth\":\"2025-09-09\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"nee@gmail.com\",\"user_id\":33,\"template_type\":1,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:15:05'),
(57, 32, 'DELETE', 'cv', 38, '{\"id\":38,\"name\":\"jan\",\"address\":\"Juliettepad\",\"phone_number\":\"+31643618229\",\"email\":\"jan@gmail.com\",\"date_of_birth\":\"2025-09-04\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"jan@gmail.com\",\"user_id\":32,\"template_type\":1,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:15:13'),
(58, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:15:28'),
(60, 36, 'UPDATE', 'cv', 43, '{\"id\":43,\"name\":\"arjun\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":2,\"is_public\":0}', '{\"name\":\"arjunsdk\",\"email\":\"arjun@gmail.com\",\"template_type\":null,\"is_public\":null}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:16:49'),
(61, 36, 'UPDATE', 'cv', 43, '{\"id\":43,\"name\":\"arjunsdk\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":2,\"is_public\":0}', '{\"name\":\"arjunsdk\",\"email\":\"arjun@gmail.com\",\"template_type\":null,\"is_public\":null}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:16:56'),
(62, 36, 'UPDATE', 'cv', 43, '{\"id\":43,\"name\":\"arjunsdk\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":2,\"is_public\":0}', '{\"name\":\"arjunsdk\",\"email\":\"arjun@gmail.com\",\"template_type\":null,\"is_public\":null}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:17:19'),
(63, 36, 'UPDATE', 'cv', 43, '{\"id\":43,\"name\":\"arjunsdk\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":2,\"is_public\":0}', '{\"name\":\"arjunsdk\",\"email\":\"arjun@gmail.com\",\"template_type\":0,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:19:51'),
(64, 36, 'UPDATE', 'cv', 43, '{\"id\":43,\"name\":\"arjunsdk\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":0,\"is_public\":0}', '{\"name\":\"arjunsdkdfj llsc\",\"email\":\"arjun@gmail.com\",\"template_type\":0,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:20:05'),
(65, 36, 'DELETE', 'cv', 43, '{\"id\":43,\"name\":\"arjunsdkdfj llsc\",\"address\":\"Juliettepa2d\",\"phone_number\":\"+316436182292\",\"email\":\"arjun@gmail.com\",\"date_of_birth\":\"2024-02-01\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"arjun@gmail.com\",\"user_id\":36,\"template_type\":0,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:20:16'),
(66, 36, 'LOGOUT', 'users', 36, NULL, '{\"username\":\"arjun\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:20:20'),
(67, 23, 'LOGOUT', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', '2025-09-18 14:20:32'),
(68, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-18 14:21:08'),
(69, 37, 'INSERT', 'cv', 44, NULL, '{\"name\":\"user1\",\"email\":\"user1@gmail.com\",\"template_type\":2,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:01:28'),
(70, 37, 'UPDATE', 'cv', 44, '{\"id\":44,\"name\":\"user1\",\"address\":\"Juliettepad\",\"phone_number\":\"+31643618229\",\"email\":\"user1@gmail.com\",\"date_of_birth\":\"2025-09-12\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"user1@gmail.com\",\"user_id\":37,\"template_type\":2,\"is_public\":0}', '{\"name\":\"user12\",\"email\":\"user1@gmail.com\",\"template_type\":0,\"is_public\":0}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:01:54'),
(71, 37, 'DELETE', 'cv', 44, '{\"id\":44,\"name\":\"user12\",\"address\":\"Juliettepad\",\"phone_number\":\"+31643618229\",\"email\":\"user1@gmail.com\",\"date_of_birth\":\"2025-09-12\",\"linkedin_profile\":\"https:\\/\\/linkedin.com\\/in\\/nathan-jethoe\",\"portfolio\":\"\",\"profile_summary\":\"user1@gmail.com\",\"user_id\":37,\"template_type\":0,\"is_public\":0}', NULL, '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:02:57'),
(72, 37, 'LOGOUT', 'users', 37, NULL, '{\"username\":\"user1\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:03:01'),
(73, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:03:13'),
(74, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:05:02'),
(76, 37, 'LOGIN', 'users', 37, NULL, '{\"username\":\"user1\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:05:34'),
(77, 37, 'LOGOUT', 'users', 37, NULL, '{\"username\":\"user1\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:05:48'),
(78, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:06:22'),
(79, 38, 'UPDATE', 'cv', 45, '{\"id\":45,\"name\":\"Abreham\",\"address\":\"sdfasdafa\",\"phone_number\":\"+1234949948\",\"email\":\"abreham@gmail.com\",\"date_of_birth\":\"2025-09-02\",\"linkedin_profile\":\"https:\\/\\/luxdemoestate.com\\/E-N\\/create\\/cv_create_form.php\",\"portfolio\":\"https:\\/\\/luxdemoestate.com\\/E-N\\/create\\/cv_create_form.php\",\"profile_summary\":\"https:\\/\\/luxdemoestate.com\\/E-N\\/create\\/cv_create_form.php\",\"user_id\":38,\"template_type\":2,\"is_public\":0}', '{\"name\":\"Abreham1\",\"email\":\"abreham@gmail.com\",\"template_type\":0,\"is_public\":0}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:06:29'),
(80, 23, 'LOGOUT', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:07:36'),
(81, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:07:48'),
(82, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:08:28'),
(83, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:08:41'),
(84, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:11:02'),
(85, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:11:23'),
(86, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:54:11'),
(87, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 08:54:24'),
(88, 23, 'LOGIN', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 08:54:58'),
(89, 23, 'LOGOUT', 'users', 23, NULL, '{\"username\":\"nathan\",\"role\":\"user\"}', '46.6.118.79', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-09-19 09:02:18'),
(90, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:09:28'),
(91, 38, 'LOGIN', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:09:44'),
(92, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:10:07'),
(93, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:10:19'),
(94, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:29:05'),
(95, 38, 'LOGIN', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:29:21'),
(96, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:29:52'),
(97, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:30:03'),
(98, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:33:42'),
(99, 38, 'LOGIN', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:33:55'),
(100, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:34:02'),
(101, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:34:14'),
(102, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:44:34'),
(103, 38, 'LOGIN', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:44:47'),
(104, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:44:50'),
(105, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:45:02'),
(106, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:48:01'),
(107, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:48:12'),
(108, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:55:05'),
(109, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 09:55:18'),
(110, 1, 'LOGOUT', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:01'),
(111, 38, 'LOGIN', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:17'),
(112, 38, 'LOGOUT', 'users', 38, NULL, '{\"username\":\"abreham\",\"role\":\"user\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:20'),
(113, 1, 'LOGIN', 'users', 1, NULL, '{\"username\":\"admin\",\"role\":\"admin\"}', '149.22.84.162', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Safari/605.1.15', '2025-09-19 10:03:33');

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
) ;

--
-- Gegevens worden geëxporteerd voor tabel `cv`
--

INSERT INTO `cv` (`id`, `name`, `address`, `phone_number`, `email`, `date_of_birth`, `linkedin_profile`, `portfolio`, `profile_summary`, `user_id`) VALUES
(1, 'Nathan Jethoe', 'Hoogvliet Rotterdam, Netherlands', '0643618229', 'Nathanjethoe007@gmail.com', '2004-12-09', 'https://linkedin.com/in/nathan-jethoe', '', 'Hello! I\'m Nathan Jethoe, a 19-year-old software developer from the Netherlands. I specialize in front-end technologies like HTML, CSS, and JavaScript, with back-end experience in PHP and MySQL. I\'m passionate about creating responsive and user-friendly web designs and always eager to learn new technologies!', 23),
(2, 'Esey Mhretab', 'ROC Mondriaan Delft', NULL, 'Eseymhretab@hotmail.com', '2003-02-21', NULL, 'https://e-mhretab.github.io/', 'I am a highly adaptable and versatile professional with a strong foundation in both customer-facing roles and technical skills. My experience includes providing efficient service and maintaining high standards in fast-paced environments. I am dedicated to continuous learning and am currently expanding my expertise in software development.', NULL),
(6, 'Semere1', 'Delft station 4', NULL, 'Semere@gmail.com', '2025-05-05', '', 'https://luxdemoestate.com/E-N//cv_create_form.php', 'This is Semere', NULL),
(7, 'Mirian Trujillo', 'Fuengirola', NULL, 'mirian@businessdevelopment.es', '1995-08-01', 'https://www.linkedin.com/in/miriantrujillomerino', 'https://github.com/miritru/', 'Computer Engineering student | Full Stack Developer in training | Java, Python. Constantly developing and learning, with the goal of continuing my professional career in the IT sector.', NULL),
(45, 'Abreham1', 'sdfasdafa', '+1234949948', 'abreham@gmail.com', '2025-09-02', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 38);

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
(1, 1, 1, 1, '2025-09-12 09:23:55', '2025-09-09 08:44:23', '2025-09-12 14:06:47'),
(2, 2, 2, 1, '2025-09-12 11:44:07', '2025-09-09 08:44:37', '2025-09-12 13:44:07'),
(3, 6, 2, 1, '2025-09-12 11:43:47', '2025-09-09 13:14:13', '2025-09-12 13:43:47'),
(4, 7, 3, 0, NULL, '2025-09-12 06:44:18', '2025-09-12 06:44:18'),
(25, 45, 0, 1, '2025-09-19 06:07:50', '2025-09-19 08:05:19', '2025-09-19 08:07:50');

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
) ;

--
-- Gegevens worden geëxporteerd voor tabel `education`
--

INSERT INTO `education` (`id`, `cv_id`, `degree`, `institution`, `education_start`, `education_end`, `is_current`, `description`) VALUES
(3, 2, 'Software Development', 'ROC Mondriaan', '2023-01-01', NULL, 1, 'Currently studying software development'),
(4, 2, 'Automotive Technician Level 1 & 2', 'ROC Mondriaan', '2020-01-01', '2023-12-31', 0, 'Completed automotive technician training'),
(11, 6, 'Lewyer', 'ROC', '2025-06-03', NULL, 1, ''),
(12, 7, 'Computer Engineering', 'National University of Distance Education', '2022-09-01', NULL, 1, 'Currently studying Computer Engineering'),
(13, 7, 'Food Science and Technology', 'University of Granada', '2017-09-01', '2022-07-20', 0, NULL),
(36, 1, 'Creative Software Development', 'Grafisch Lyceum Rotterdam', '2023-08-01', NULL, 1, 'Currently studying software development'),
(37, 1, 'Middlebare School (MAVO)', 'Einstein Lyceum', '2019-08-01', '2023-07-31', 0, 'Completed secondary education'),
(39, 45, 'dfsfdsa', 'asdia', '2025-09-02', NULL, 1, 'https://luxdemoestate.com/E-N/create/cv_create_form.php');

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
(6, 2, 'Fitness', 'Regular physical exercise and training'),
(7, 2, 'Walking', 'Enjoying outdoor walks and nature'),
(15, 6, 'GYM', 'I am a bodybuilder'),
(16, 7, 'Music', 'Rock/indie music, going to concerts'),
(17, 7, 'Board games', 'Playing board games with my family and friends'),
(41, 1, 'Front-end Development', 'Building responsive web applications'),
(42, 1, 'Learning Technologies', 'Continuously learning new programming languages and frameworks'),
(43, 1, 'Team Collaboration', 'Working effectively in development teams'),
(44, 1, 'Problem Solving', 'Analyzing and solving complex programming challenges'),
(45, 1, 'Creative Thinking', 'Designing innovative solutions and user experiences');

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
(4, 2, 'English', 'native'),
(5, 2, 'French', 'basic'),
(8, 45, 'tigrinya', 'fluent');

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
(4, 2, 'Python', 'Data analysis and web development'),
(5, 2, 'Machine Learning', 'Building predictive models and AI solutions'),
(7, 45, 'php', 'good'),
(8, 45, 'css', 'very good');

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
(23, 'nathan', 'nathanjethoe007@gmail.com', '$2y$12$AwTv3ctQP7PTNjiM1PxBf.a5fnwO2o2rfkjnswya1s3iof6FocLCm', 'user', 1, '2025-09-12 14:06:47', '2025-09-19 08:54:58', '2025-09-19 08:54:58'),
(32, 'jan', 'jan@gmail.com', '$2y$12$ceU76Z35h5Hzp2FAaKQWq.gk.ad7SSK4hFGzht8g7vH4i.qzaWBOO', 'user', 1, '2025-09-18 13:33:50', '2025-09-18 13:34:41', '2025-09-18 13:34:41'),
(33, 'nee', 'nee@gmail.com', '$2y$12$X.fFb/BSAp0oWzYUK6lszeXRNFFvkfwpz.vL43HGjRU.AOES4Jq7a', 'user', 1, '2025-09-18 13:41:12', '2025-09-18 13:41:13', '2025-09-18 13:41:13'),
(34, 'fey', 'fey@gmail.com', '$2y$12$ZFsReHZen6yQcQFrjZGVDuUmpa0C8m5yWp1ZOuMuYJZRXPy5JzQyK', 'user', 1, '2025-09-18 13:53:10', '2025-09-18 13:53:41', '2025-09-18 13:53:41'),
(35, 'nath', 'nath@gmail.com', '$2y$12$fdoBAPVSzy5.mISp9jZjm.peGeOOFnGSjgabW7u7OhB8/7wpjZKxO', 'user', 1, '2025-09-18 13:58:03', '2025-09-18 13:59:08', '2025-09-18 13:59:08'),
(36, 'arjun', 'arjun@gmail.com', '$2y$12$ukDxmTUdoz0/dy7Obj4vL.AwhAlKICX7qcNttPBStTzeCp0VaYqDm', 'user', 1, '2025-09-18 14:16:33', '2025-09-18 14:16:33', '2025-09-18 14:16:33'),
(37, 'user1', 'user1@gmail.com', '$2y$12$Afn4dDqtNRbEMj3Nlf7y0.EO9x4jmQyT9p.Du8lTRp0wX1nA8339u', 'user', 1, '2025-09-19 08:01:00', '2025-09-19 08:05:34', '2025-09-19 08:05:34'),
(38, 'abreham', 'abreham@gmail.com', '$2y$12$gJL/8uOxOdaPTTL.Rxx8l.BPnbUtMTS6fr7914FMrFPzL.Q4ZvJYi', 'user', 1, '2025-09-19 08:06:04', '2025-09-19 10:03:17', '2025-09-19 10:03:17');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NOT NULL,
  `device_id` varchar(255) DEFAULT NULL,
  `refresh_token` varchar(255) DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

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
) ;

--
-- Gegevens worden geëxporteerd voor tabel `work_experience`
--

INSERT INTO `work_experience` (`id`, `cv_id`, `job_title`, `company_name`, `work_start`, `work_end`, `description`, `is_current`) VALUES
(4, 2, 'Crew Member', 'Burger King', '2023-01-01', '2024-12-31', 'Efficiently serving customers at the register and taking orders with a customer-friendly attitude.', 0),
(5, 2, 'Crew Member', 'McDonald', '2020-01-01', '2023-12-31', 'Preparing food while following strict hygiene and safety regulations.', 0),
(15, 6, 'CEO', 'IT GOLD', '2025-05-01', NULL, 'It is going well', 1),
(16, 7, 'Food Quality and Safety Technician', 'Embutidos Moreno Plaza', '2020-02-02', '2025-07-02', 'Supplier management. Traceability control. Audits (IFS, Health, Iberian). Quality control. Optimization.', 0),
(17, 7, 'Food Quality and Safety Technician', 'Avomix', '2019-11-02', '2020-01-30', 'Plant and documentary quality control.', 0),
(18, 7, 'Food Quality and Safety Technician', 'Puleva Food', '2017-02-02', '2017-11-30', 'Quality control focused on cleaning (CIP).', 0),
(40, 1, 'Web Developer', 'Car Wash Company', '2023-06-01', '2023-08-31', 'Designed and built a modern, user-friendly website. Ensured clear access to services and pricing for customers. Optimized site responsiveness for desktop and mobile devices.', 1),
(41, 1, 'Owner', 'Prestige Elegance', '2022-12-01', '2024-10-31', 'Managed a webshop, overseeing design, development, and customer service. Built the website using HTML, CSS, JavaScript, and PHP. Implemented marketing strategies to drive growth.', 0),
(42, 1, 'Delivery Driver', 'Chinese Restaurant Zilverrijin', '2022-01-01', '2025-08-31', 'Delivered orders timely and accurately while ensuring excellent customer service. Minimized delivery routes to save time and improve efficiency. Enhanced organizational skills, time management, and customer focus.', 0),
(44, 45, 'dsadf', 'sdf', '2025-09-09', NULL, 'https://luxdemoestate.com/E-N/create/cv_create_form.php', 1);

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
-- Indexen voor tabel `hobbies`
--
ALTER TABLE `hobbies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cv_id` (`cv_id`);

--
-- Indexen voor tabel `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_languages_cv_id` (`cv_id`),
  ADD KEY `idx_languages_name` (`language_name`),
  ADD KEY `idx_languages_proficiency` (`proficiency`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `cv_metadata`
--
ALTER TABLE `cv_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT voor een tabel `education`
--
ALTER TABLE `education`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `hobbies`
--
ALTER TABLE `hobbies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT voor een tabel `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT voor een tabel `work_experience`
--
ALTER TABLE `work_experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
