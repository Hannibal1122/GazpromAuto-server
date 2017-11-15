-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 15 2017 г., 15:23
-- Версия сервера: 10.1.28-MariaDB
-- Версия PHP: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `gazprom_auto`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bind_template`
--

CREATE TABLE `bind_template` (
  `id` int(11) NOT NULL,
  `name_table` varchar(64) NOT NULL,
  `name_template` varchar(64) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `id_parent_cell` int(11) NOT NULL,
  `info` varchar(2048) DEFAULT NULL,
  `rights` int(11) NOT NULL DEFAULT '0',
  `_default` tinyint(1) DEFAULT '0',
  `status` varchar(64) DEFAULT NULL,
  `person` varchar(256) DEFAULT NULL,
  `terms` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `bind_template`
--

INSERT INTO `bind_template` (`id`, `name_table`, `name_template`, `id_parent`, `id_parent_cell`, `info`, `rights`, `_default`, `status`, `person`, `terms`) VALUES
(1, 'УСО ПЭБ', 'УСО/ШУ', -1, -1, '', 0, 0, '', 'Не выбран', ''),
(2, 'АСУЭ.021.УСО', 'Шкаф', -1, -1, '', 0, 0, '', 'Не выбран', ''),
(3, 'BR (ПЛК RX3i)', 'Устройство', -1, -1, '', 0, 0, '', 'Не выбран', ''),
(4, 'BR1 (БП 16 RX3i)', 'Плата', -1, -1, '', 0, 0, '', 'Не выбран', '');

-- --------------------------------------------------------

--
-- Структура таблицы `password`
--

CREATE TABLE `password` (
  `login` varchar(32) NOT NULL,
  `hash` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `password`
--

INSERT INTO `password` (`login`, `hash`) VALUES
('admin', '$2a$10$255bb543b1626576824ccORRjxiJk0TEDxSHoX.RLWn1kw69cNKgm'),
('ya.pechenko92@gmail.com', '$2a$10$4d86aed8e94f7c27730a1OYf1zo1OjJDhPfMmlXT8Cdm8/B3iGLeu'),
('admin2', '$2a$10$89767a74a40b57b93228cuHMn22SbHRSkqC5B/zx63ifa01lK3GF.');

-- --------------------------------------------------------

--
-- Структура таблицы `registration`
--

CREATE TABLE `registration` (
  `login` varchar(64) NOT NULL,
  `mail` varchar(64) NOT NULL,
  `role` int(11) DEFAULT '0',
  `dater` datetime NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `registration`
--

INSERT INTO `registration` (`login`, `mail`, `role`, `dater`, `name`) VALUES
('admin', 'mwork92@gmail.com', 1, '2016-05-27 11:15:07', 'admin'),
('ya.pechenko92@gmail.com', 'ya.pechenko92@gmail.com', 2, '2016-09-23 17:09:59', '111'),
('admin2', 'test1@gmail.com', 5, '2017-11-09 09:54:47', 'test');

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE `rights` (
  `id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `login` varchar(64) NOT NULL,
  `rights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights`
--

INSERT INTO `rights` (`id`, `table_id`, `login`, `rights`) VALUES
(1, 1, 'admin', 1),
(2, 2, 'admin', 1),
(3, 3, 'admin', 1),
(4, 4, 'admin', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `rights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `rights`) VALUES
(1, 'Administrator', 31),
(2, 'Manager', 25),
(5, 'Инженер', 18);

-- --------------------------------------------------------

--
-- Структура таблицы `signin`
--

CREATE TABLE `signin` (
  `id` varchar(32) NOT NULL,
  `login` varchar(32) NOT NULL,
  `checkkey` varchar(32) NOT NULL,
  `countreg` int(1) NOT NULL,
  `captcha` varchar(64) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `signin`
--

INSERT INTO `signin` (`id`, `login`, `checkkey`, `countreg`, `captcha`, `date`) VALUES
('GNo8pNbvRww', 'admin', 'bc3128d1b2886a7eb99138ff80dd4ba1', 0, '', '2017-11-15 15:10:03');

-- --------------------------------------------------------

--
-- Структура таблицы `table_1`
--

CREATE TABLE `table_1` (
  `f_0` varchar(2048) DEFAULT NULL,
  `f_1` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `table_2`
--

CREATE TABLE `table_2` (
  `f_0` varchar(2048) DEFAULT NULL,
  `f_1` varchar(2048) DEFAULT NULL,
  `f_2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `table_3`
--

CREATE TABLE `table_3` (
  `f_0` varchar(2048) DEFAULT NULL,
  `f_1` varchar(2048) DEFAULT NULL,
  `f_2` varchar(2048) DEFAULT NULL,
  `f_3` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `table_4`
--

CREATE TABLE `table_4` (
  `f_0` varchar(2048) DEFAULT NULL,
  `f_1` varchar(2048) DEFAULT NULL,
  `f_2` varchar(2048) DEFAULT NULL,
  `f_3` varchar(2048) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `template`
--

CREATE TABLE `template` (
  `name` varchar(64) NOT NULL,
  `status` varchar(512) DEFAULT NULL,
  `status_color` varchar(512) DEFAULT NULL,
  `fields` varchar(512) NOT NULL,
  `rights` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `template`
--

INSERT INTO `template` (`name`, `status`, `status_color`, `fields`, `rights`) VALUES
('Плата', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Обозначение (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Примечание (КД)\",\"type\":\"VARCHAR\"}]', NULL),
('УСО/ШУ', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование(КД)\",\"type\":\"VARCHAR\"}]', NULL),
('Устройство', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Обозначение (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Примечание (КД)\",\"type\":\"VARCHAR\"}]', NULL),
('Шкаф', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Заводской номер\",\"type\":\"INT\"}]', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `type`
--

CREATE TABLE `type` (
  `name` varchar(64) NOT NULL,
  `_default` varchar(512) DEFAULT NULL,
  `rights` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `type`
--

INSERT INTO `type` (`name`, `_default`, `rights`) VALUES
('Новый тип', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\"]', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bind_template`
--
ALTER TABLE `bind_template`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password`
--
ALTER TABLE `password`
  ADD PRIMARY KEY (`login`);

--
-- Индексы таблицы `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`login`);

--
-- Индексы таблицы `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `signin`
--
ALTER TABLE `signin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`name`);

--
-- Индексы таблицы `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bind_template`
--
ALTER TABLE `bind_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `rights`
--
ALTER TABLE `rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
