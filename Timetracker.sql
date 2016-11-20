-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Ноя 20 2016 г., 21:22
-- Версия сервера: 10.1.16-MariaDB
-- Версия PHP: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Timetracker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Log`
--

CREATE TABLE `Log` (
  `id` int(11) NOT NULL,
  `task` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Log`
--

INSERT INTO `Log` (`id`, `task`, `user`, `date`, `time`) VALUES
(1, 1, 1, '2016-11-20', '06:00:00'),
(2, 1, 1, '2016-11-21', '03:00:00'),
(3, 1, 1, '2016-11-22', '00:30:00'),
(4, 4, 1, '2016-11-22', '07:00:00'),
(5, 0, 1, '1970-01-01', '10:00:00'),
(6, 1, 1, '2016-11-26', '01:00:00'),
(7, 5, 6, '2016-11-20', '18:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `Tasks`
--

CREATE TABLE `Tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` int(11) NOT NULL,
  `performer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Tasks`
--

INSERT INTO `Tasks` (`id`, `name`, `description`, `author`, `performer`) VALUES
(1, 'name', 'descr', 1, 1),
(2, 'my', 'task', 1, 1),
(3, 'my task', 'n 2', 1, 1),
(4, 'lol', 'troll', 1, 1),
(5, 'yes', 'works', 1, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`id`, `username`, `email`, `password`, `is_admin`, `is_active`) VALUES
(1, 'admin', 'mail@mail.com', '202cb962ac59075b964b07152d234b70', 1, 1),
(2, 'oik', 'ijfdg', '865c0c0b4ab0e063e5caa3387c1a8741', 0, 0),
(3, 'smolik', 'mail', '1a1dc91c907325c69271ddf0c944bc72', 0, 0),
(4, 'smoliksoft', 'maill', '202cb962ac59075b964b07152d234b70', 0, 0),
(6, 'loginn', 'maile', '202cb962ac59075b964b07152d234b70', 0, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Log`
--
ALTER TABLE `Log`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Log`
--
ALTER TABLE `Log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
