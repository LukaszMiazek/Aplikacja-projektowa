-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 20 Gru 2022, 19:09
-- Wersja serwera: 10.4.8-MariaDB
-- Wersja PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `tasks`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `assignment`
--

CREATE TABLE `assignment` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED DEFAULT NULL,
  `id_job` int(11) UNSIGNED DEFAULT NULL,
  `rola` int(11) UNSIGNED DEFAULT NULL,
  `status` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `assignment`
--

INSERT INTO `assignment` (`id`, `id_user`, `id_job`, `rola`, `status`) VALUES
(41, 15, 19, 1, 0),
(42, 16, 19, 2, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `job`
--

CREATE TABLE `job` (
  `id` int(11) UNSIGNED NOT NULL,
  `nazwa` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tresc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termin` date DEFAULT NULL,
  `task` int(11) UNSIGNED DEFAULT NULL,
  `tworca` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `job`
--

INSERT INTO `job` (`id`, `nazwa`, `tresc`, `termin`, `task`, `tworca`) VALUES
(19, 'zadanie 1', 'tresc1', '2022-12-31', 15, 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `notification`
--

CREATE TABLE `notification` (
  `id` int(11) UNSIGNED NOT NULL,
  `tresc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_user` int(11) UNSIGNED DEFAULT NULL,
  `data` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `notification`
--

INSERT INTO `notification` (`id`, `tresc`, `id_user`, `data`) VALUES
(58, 'Zostałeś(aś) dodana do nowego projektu: projekt1 przez: user1', 16, '2022-12-20 19:05:40'),
(59, 'Zostałeś(aś) dodana do nowego projektu: projekt1 przez: user1', 22, '2022-12-20 19:06:26'),
(60, 'Zostało ci przydzielone nowe zadanie: zadanie 1 w projekcie projekt1 przez: user1', 16, '2022-12-20 19:08:06');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `part`
--

CREATE TABLE `part` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED DEFAULT NULL,
  `id_task` int(11) UNSIGNED DEFAULT NULL,
  `role` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `part`
--

INSERT INTO `part` (`id`, `id_user`, `id_task`, `role`) VALUES
(42, 15, 15, 3),
(43, 16, 15, 2),
(44, 22, 15, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role_names`
--

CREATE TABLE `role_names` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(32) COLLATE utf16_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_polish_ci;

--
-- Zrzut danych tabeli `role_names`
--

INSERT INTO `role_names` (`id`, `nazwa`) VALUES
(1, 'członek'),
(2, 'moderator'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `task`
--

CREATE TABLE `task` (
  `id` int(11) UNSIGNED NOT NULL,
  `nazwa` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opis` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wlasciciel` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `task`
--

INSERT INTO `task` (`id`, `nazwa`, `opis`, `wlasciciel`) VALUES
(15, 'projekt1', 'opis1', '15');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `login` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `haslo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imie` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nazwisko` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `login`, `haslo`, `imie`, `nazwisko`) VALUES
(15, 'user1', 'haslo1', 'tomek', 'ryba'),
(16, 'user2', 'haslo2', 'hubert', 'lama'),
(22, 'user3', 'haslo3', 'Adam', 'Stary');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `role_names`
--
ALTER TABLE `role_names`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `assignment`
--
ALTER TABLE `assignment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT dla tabeli `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT dla tabeli `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT dla tabeli `role_names`
--
ALTER TABLE `role_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
