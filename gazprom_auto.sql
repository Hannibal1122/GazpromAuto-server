-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 29 2017 г., 16:29
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
-- Структура таблицы `big_template`
--

CREATE TABLE `big_template` (
  `name` varchar(64) NOT NULL,
  `hierarchy` varchar(512) NOT NULL,
  `rights` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `big_template`
--

INSERT INTO `big_template` (`name`, `hierarchy`, `rights`) VALUES
('Проект', '[\"УСО/ШУ\",\"Шкаф\",\"Устройство\",\"Плата\",\"Модуль\"]', NULL);

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
('admin2', '$2a$10$40bde076522466bae6f39uAG.AbJOzdgc.lIanuOCTKTtvyv50Yuu');

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
(12, 5, 'admin', 15),
(13, 6, 'admin', 15),
(14, 7, 'admin', 15),
(15, 8, 'admin', 15),
(16, 9, 'admin', 15),
(17, 10, 'admin', 15),
(18, 11, 'admin', 15),
(19, 12, 'admin', 15);

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
(1, 'Administrator', 255),
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
('GNo8pNbvRww', 'admin', '679c1114ef5a34d54c53c19720cebc80', 0, '', '2017-11-29 16:24:13');

-- --------------------------------------------------------

--
-- Структура таблицы `table_big`
--

CREATE TABLE `table_big` (
  `id` int(11) NOT NULL,
  `name_table` varchar(64) NOT NULL,
  `name_template` varchar(64) NOT NULL,
  `info` varchar(2048) DEFAULT NULL,
  `rights` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `table_big`
--

INSERT INTO `table_big` (`id`, `name_table`, `name_template`, `info`, `rights`) VALUES
(12, '1', 'Проект', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `table_initialization`
--

CREATE TABLE `table_initialization` (
  `id` int(11) NOT NULL,
  `name_table` varchar(64) NOT NULL,
  `name_template` varchar(64) NOT NULL,
  `info` varchar(2048) DEFAULT NULL,
  `rights` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `table_initialization`
--

INSERT INTO `table_initialization` (`id`, `name_table`, `name_template`, `info`, `rights`) VALUES
(12, 'RX3i', 'Плата', '', 0),
(13, 'БП 16 RX3i', 'Модуль', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `table_init_12`
--

CREATE TABLE `table_init_12` (
  `id` int(11) NOT NULL,
  `f_0` varchar(256) DEFAULT NULL,
  `f_1` varchar(256) DEFAULT NULL,
  `f_2` varchar(256) DEFAULT NULL,
  `f_3` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `table_init_12`
--

INSERT INTO `table_init_12` (`id`, `f_0`, `f_1`, `f_2`, `f_3`) VALUES
(1, 'q', '', '', ''),
(2, 'w', '', '', ''),
(3, 'e', '', '', ''),
(4, 'r', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `table_init_13`
--

CREATE TABLE `table_init_13` (
  `id` int(11) NOT NULL,
  `f_0` varchar(256) DEFAULT NULL,
  `f_1` varchar(256) DEFAULT NULL,
  `f_2` varchar(256) DEFAULT NULL,
  `f_3` varchar(256) DEFAULT NULL,
  `f_4` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `table_init_13`
--

INSERT INTO `table_init_13` (`id`, `f_0`, `f_1`, `f_2`, `f_3`, `f_4`) VALUES
(1, 'q', '', '', '', ''),
(2, 'w', '', '', '', ''),
(3, 'e', '', '', '', ''),
(4, 'r', '', '', '', ''),
(5, 't', '', '', '', ''),
(6, 'y', '', '', '', ''),
(7, 'u', '', '', '', ''),
(8, 'i', '', '', '', ''),
(9, 'o', '', '', '', ''),
(10, 'p', '', '', '', ''),
(11, '[', '', '', '', ''),
(12, ']', '', '', '', ''),
(13, '\\', '', '', '', ''),
(14, 'a', '', '', '', ''),
(15, 's', '', '', '', ''),
(16, 'd', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `table_tree_big`
--

CREATE TABLE `table_tree_big` (
  `id` int(11) NOT NULL,
  `id_table` int(11) NOT NULL,
  `template` varchar(64) NOT NULL,
  `parent` int(11) NOT NULL,
  `fields` varchar(4096) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `table_tree_big`
--

INSERT INTO `table_tree_big` (`id`, `id_table`, `template`, `parent`, `fields`) VALUES
(1, 12, 'УСО/ШУ', 0, '[\"1\",\"2\"]'),
(2, 12, 'Шкаф', 1, '[\"4\",\"5\",77]'),
(3, 12, 'Устройство', 2, '[\"e\",\"RX3i\",\"r\",\"t\"]'),
(4, 12, 'Плата', 3, '[\"\",\"\",\"\",\"\"]'),
(5, 12, 'Модуль', 4, '[\"\",\"\",\"\",\"\",\"\"]'),
(6, 12, 'Плата', 3, '[\"\",\"\",\"\",\"\"]'),
(7, 12, 'Плата', 3, '[\"\",\"\",\"\",\"\"]'),
(8, 12, 'Модуль', 4, '[\"\",\"\",\"\",\"\",\"\"]'),
(9, 12, 'Шкаф', 1, '[\"\",\"\",\"\"]'),
(10, 12, 'УСО/ШУ', 0, '[\"\",\"\"]'),
(11, 12, 'Шкаф', 10, '[\"\",\"\",\"\"]'),
(12, 12, 'Устройство', 11, '[\"\",\"\",\"\",\"\"]'),
(13, 12, 'Плата', 12, '[\"\",\"\",\"\",\"\"]'),
(14, 12, 'Модуль', 13, '[\"\",\"\",\"\",\"\",\"\"]'),
(15, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(16, 12, 'Плата', 15, '[\"\",\"\",\"\",\"\"]'),
(17, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(18, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(19, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(20, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(21, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(22, 12, 'Модуль', 6, '[\"\",\"\",\"\",\"\",\"\"]'),
(23, 12, 'Модуль', 7, '[\"\",\"\",\"\",\"\",\"\"]'),
(24, 12, 'Модуль', 16, '[\"\",\"\",\"\",\"\",\"\"]'),
(25, 12, 'Плата', 17, '[\"\",\"\",\"\",\"\"]'),
(26, 12, 'Плата', 18, '[\"\",\"\",\"\",\"\"]'),
(27, 12, 'Плата', 19, '[\"\",\"\",\"\",\"\"]'),
(28, 12, 'Плата', 20, '[\"\",\"\",\"\",\"\"]'),
(29, 12, 'Плата', 21, '[\"\",\"\",\"\",\"\"]'),
(30, 12, 'Плата', 21, '[\"\",\"\",\"\",\"\"]'),
(31, 12, 'Модуль', 25, '[\"\",\"\",\"\",\"\",\"\"]'),
(32, 12, 'Модуль', 26, '[\"\",\"\",\"\",\"\",\"\"]'),
(33, 12, 'Модуль', 27, '[\"\",\"\",\"\",\"\",\"\"]'),
(34, 12, 'Модуль', 28, '[\"\",\"\",\"\",\"\",\"\"]'),
(35, 12, 'Модуль', 29, '[\"\",\"\",\"\",\"\",\"\"]'),
(36, 12, 'Модуль', 30, '[\"\",\"\",\"\",\"\",\"\"]'),
(37, 12, 'Устройство', 9, '[\"\",\"\",\"\",\"\"]'),
(38, 12, 'Плата', 37, '[\"\",\"\",\"\",\"\"]'),
(39, 12, 'Модуль', 38, '[\"\",\"\",\"\",\"\",\"\"]');

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
('Модуль', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование\",\"type\":\"VARCHAR\"},{\"name\":\"Обозначение\",\"type\":\"VARCHAR\"},{\"name\":\"Примечание\",\"type\":\"VARCHAR\"},{\"name\":\"Тип\",\"type\":\"VARCHAR\"}]', NULL),
('Плата', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование\",\"type\":\"Имя платы\"},{\"name\":\"Обозначение (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Примечание (КД)\",\"type\":\"VARCHAR\"}]', NULL),
('УСО/ШУ', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование(КД)\",\"type\":\"VARCHAR\"}]', NULL),
('Устройство', '[]', '[]', '[{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Наименование (КД)\",\"type\":\"Новый тип\"},{\"name\":\"Обозначение (КД)\",\"type\":\"VARCHAR\"},{\"name\":\"Примечание (КД)\",\"type\":\"VARCHAR\"}]', NULL),
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
('Имя платы', '[\"БП 16 RX3i\",\" БП 12 RX3i\"]', NULL),
('Новый тип', '[\"RX3i\",\"RX4i\",\"RX7i\"]', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `big_template`
--
ALTER TABLE `big_template`
  ADD PRIMARY KEY (`name`);

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
-- Индексы таблицы `table_big`
--
ALTER TABLE `table_big`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `table_initialization`
--
ALTER TABLE `table_initialization`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `table_init_12`
--
ALTER TABLE `table_init_12`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `table_init_13`
--
ALTER TABLE `table_init_13`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `table_tree_big`
--
ALTER TABLE `table_tree_big`
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
-- AUTO_INCREMENT для таблицы `rights`
--
ALTER TABLE `rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `table_big`
--
ALTER TABLE `table_big`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `table_initialization`
--
ALTER TABLE `table_initialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `table_init_12`
--
ALTER TABLE `table_init_12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `table_init_13`
--
ALTER TABLE `table_init_13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `table_tree_big`
--
ALTER TABLE `table_tree_big`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
