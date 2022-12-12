-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 12 Gru 2022, 08:42
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
(5, 15, 3, 1, 0),
(22, 17, 3, 2, 1);

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
(3, 'test2', 'bbb', '2022-12-04', 3, 15);

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
(1, 'Zostało ci przydzielone nowe zadanie: test2 w projekcie pro2 przez: user1', 17, '2022-12-11 19:42:29'),
(2, 'Zostałeś(aś) dodana do nowego projektu: pro2 przez: user1', 17, '2022-12-11 19:50:14'),
(15, 'user3 ukończył zadanie test2 z projektu pro2', 15, '2022-12-11 20:55:12'),
(16, 'user3 wprowadził poprawki w zadaniu test2 z projektu pro2', 15, '2022-12-11 20:55:25'),
(17, 'user3 wprowadził poprawki w zadaniu test2 z projektu pro2', 15, '2022-12-11 20:55:32');

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
(2, 15, 3, 3),
(3, 16, 3, 2),
(25, 15, 11, 3),
(26, 16, 11, 2),
(33, 17, 3, 1);

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
  `wlasciciel` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `task`
--

INSERT INTO `task` (`id`, `nazwa`, `opis`, `wlasciciel`) VALUES
(3, 'pro2', 'aba', 15),
(11, 'Pro4', 'Projekt nr 4', 15);

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
(17, 'user3', 'haslo3', 'ewa', 'jabłko'),
(18, 'user4', 'haslo4', 'Piotr', 'Nowak'),
(19, 'user5', 'haslo5', 'Piotr', 'Danielowski');

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT dla tabeli `job`
--
ALTER TABLE `job`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT dla tabeli `role_names`
--
ALTER TABLE `role_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
