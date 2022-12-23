-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 18. pro 2022, 18:39
-- Verze serveru: 10.4.24-MariaDB
-- Verze PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `yetinder`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(10) UNSIGNED NOT NULL,
  `yeti_id` int(11) UNSIGNED NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `value` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `ratings`
--

INSERT INTO `ratings` (`rating_id`, `yeti_id`, `timestamp`, `value`) VALUES
(21, 7, '2022-11-30 16:22:22', 3),
(22, 2, '2022-11-30 16:22:37', 5),
(23, 10, '2022-11-30 16:22:52', 4),
(24, 8, '2022-11-30 16:23:05', 1),
(25, 10, '2022-11-30 16:24:07', 2),
(26, 7, '2022-11-30 16:24:42', 2),
(27, 1, '2022-11-30 16:24:51', 5),
(28, 5, '2022-12-02 19:00:48', 3),
(29, 7, '2022-12-02 19:00:54', 4),
(30, 2, '2022-12-02 19:01:02', 2),
(31, 1, '2022-12-02 19:01:07', 4),
(32, 8, '2022-12-02 19:01:11', 2),
(33, 2, '2022-12-02 19:01:14', 3),
(34, 6, '2022-12-02 19:01:19', 4),
(35, 6, '2022-12-02 19:01:25', 2),
(36, 1, '2022-12-02 19:01:29', 4),
(37, 2, '2022-12-02 19:01:32', 5),
(38, 5, '2022-12-02 19:01:36', 1),
(39, 10, '2022-12-02 19:01:40', 4),
(40, 6, '2022-12-02 19:01:43', 2),
(41, 5, '2022-12-02 19:01:46', 5),
(42, 7, '2022-12-02 19:01:48', 3),
(43, 5, '2022-12-02 19:01:51', 2),
(44, 7, '2022-12-02 19:01:54', 3),
(45, 5, '2022-12-02 19:01:57', 4),
(46, 10, '2022-12-02 19:52:41', 4),
(47, 16, '2022-12-02 19:52:45', 1),
(48, 7, '2022-12-02 19:52:49', 4),
(49, 18, '2022-12-02 19:52:54', 2),
(50, 10, '2022-12-02 19:52:59', 4),
(51, 5, '2022-12-02 19:53:03', 2),
(52, 18, '2022-12-02 19:53:06', 4),
(53, 20, '2022-12-02 19:53:12', 5),
(54, 17, '2022-12-02 19:53:16', 3),
(55, 16, '2022-12-02 19:53:19', 3),
(56, 2, '2022-12-02 19:53:22', 5),
(57, 6, '2022-12-02 19:53:25', 3),
(58, 10, '2022-12-02 19:53:29', 5),
(59, 10, '2022-12-02 19:53:33', 2),
(60, 16, '2022-12-02 19:53:36', 4),
(61, 6, '2022-12-02 19:53:40', 4),
(62, 19, '2022-12-02 19:53:44', 3),
(63, 5, '2022-12-02 19:53:48', 4),
(64, 7, '2022-12-02 19:53:51', 2),
(65, 17, '2022-12-02 19:53:54', 4),
(66, 1, '2022-12-02 19:54:16', 5),
(67, 16, '2022-12-02 19:54:21', 1),
(68, 8, '2022-12-02 19:54:25', 3),
(69, 17, '2022-12-02 19:54:32', 2),
(70, 20, '2022-12-02 19:54:36', 4),
(71, 10, '2022-12-02 19:54:39', 2),
(72, 8, '2022-12-02 19:54:44', 4),
(73, 8, '2022-12-02 19:54:47', 4),
(74, 20, '2022-12-02 19:54:54', 5),
(75, 19, '2022-12-02 19:55:45', 4),
(76, 2, '2022-12-02 20:00:28', 5),
(77, 1, '2022-12-02 20:01:33', 3),
(78, 17, '2022-12-02 20:01:40', 4),
(79, 8, '2022-12-02 20:03:04', 3),
(80, 8, '2022-12-02 20:04:00', 3),
(81, 20, '2022-12-02 20:04:21', 2),
(82, 18, '2022-12-02 20:04:29', 4),
(83, 2, '2022-12-02 20:04:44', 3),
(84, 8, '2022-12-02 20:05:13', 3),
(85, 19, '2022-12-02 20:05:23', 2),
(86, 7, '2022-12-02 20:57:49', 4),
(87, 19, '2022-12-02 20:58:41', 2),
(88, 2, '2022-12-02 21:06:01', 3),
(89, 19, '2022-12-02 21:07:52', 5),
(90, 8, '2022-12-02 21:08:53', 2),
(91, 5, '2022-12-07 18:35:13', 5),
(92, 2, '2022-12-07 18:35:24', 2),
(93, 10, '2022-11-27 20:19:07', 2),
(94, 10, '2022-11-23 20:19:16', 1),
(95, 18, '2022-12-07 21:06:45', 5),
(96, 19, '2022-12-07 21:09:05', 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `yetis`
--

CREATE TABLE `yetis` (
  `yeti_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `weight` int(10) UNSIGNED DEFAULT NULL,
  `residence` varchar(50) NOT NULL DEFAULT 'Undefined',
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `age` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Vypisuji data pro tabulku `yetis`
--

INSERT INTO `yetis` (`yeti_id`, `name`, `gender`, `height`, `weight`, `residence`, `created`, `age`) VALUES
(1, 'Bigfoot', 'Male', 261, 147, 'Zaly', '2022-10-28 22:00:00', 45),
(2, 'Snowflake', 'Female', 211, 99, 'Vapenice', '2022-10-28 22:00:00', 32),
(5, 'Fowe', 'Male', 205, 102, 'Lisci hora', '2022-11-14 23:00:00', 26),
(6, 'Fox', 'Female', 201, 108, 'Lisci kopec', '2022-11-14 23:00:00', 32),
(7, 'Freya', 'Female', 219, 112, 'Jankuv kopec', '2022-11-14 23:00:00', 29),
(8, 'Etokal', 'Male', 268, 154, 'Violik', '2022-11-14 23:00:00', 43),
(10, 'Yaddu', 'Male', 205, 108, 'Violik', '2022-11-17 23:00:00', 26),
(16, 'Kize', 'Female', 241, 112, 'Struhadlo', '2022-12-01 23:00:00', 38),
(17, 'Ikaro', 'Male', 281, 161, 'Stoh', '2022-12-01 23:00:00', 42),
(18, 'Evroph', 'Male', 222, 127, 'Planina', '2022-12-01 23:00:00', 28),
(19, 'Onuru', 'Male', 278, 176, 'Kotel', '2022-12-01 23:00:00', 63),
(20, 'Meraph', 'Female', 231, 143, 'Mechovinec', '2022-12-01 23:00:00', 47);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `Yeti_id_key` (`yeti_id`);

--
-- Indexy pro tabulku `yetis`
--
ALTER TABLE `yetis`
  ADD PRIMARY KEY (`yeti_id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT pro tabulku `yetis`
--
ALTER TABLE `yetis`
  MODIFY `yeti_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `Yeti_id_key` FOREIGN KEY (`yeti_id`) REFERENCES `yetis` (`yeti_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
