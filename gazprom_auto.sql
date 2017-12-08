-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Дек 08 2017 г., 15:38
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
('Новый проект', '[\"Модуль\",\"Плата\"]', NULL),
('Пользователи', '[\"Имя пользователя\"]', NULL),
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
('admin', '$2a$10$2124cb399981916dc1c74u7CKmeJMT8rRuhh2ODOqfNWXRkBirVSa'),
('ya.pechenko92@gmail.com', '$2a$10$4d86aed8e94f7c27730a1OYf1zo1OjJDhPfMmlXT8Cdm8/B3iGLeu'),
('admin2', '$2a$10$54b2d442ee26f36bc6e1fOsbSTM75phKDtG1NPyXbr2yM7L0gM4b.'),
('admin3', '$2a$10$730b7eaa8e2d89cc6596buqFndMmiAaWT3P0nas7RQ6bzPT0r3rf6');

-- --------------------------------------------------------

--
-- Структура таблицы `registration`
--

CREATE TABLE `registration` (
  `login` varchar(32) NOT NULL,
  `mail` varchar(64) NOT NULL,
  `role` int(11) DEFAULT '0',
  `dater` datetime NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `registration`
--

INSERT INTO `registration` (`login`, `mail`, `role`, `dater`, `name`) VALUES
('admin', 'mwork92@gmail.com', 1, '2016-05-27 11:15:07', 'admin'),
('ya.pechenko92@gmail.com', 'ya.pechenko92@gmail.com', 2, '2016-09-23 17:09:59', '111'),
('admin2', '', 5, '2017-11-09 09:54:47', 'Рученькин А. А.'),
('admin3', '', 1, '2017-12-07 14:04:57', '');

-- --------------------------------------------------------

--
-- Структура таблицы `rights`
--

CREATE TABLE `rights` (
  `id` int(11) NOT NULL,
  `id_table` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `rights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights`
--

INSERT INTO `rights` (`id`, `id_table`, `login`, `rights`) VALUES
(27, 17, 'admin', 11),
(29, 19, 'admin', 15);

-- --------------------------------------------------------

--
-- Структура таблицы `rights_template`
--

CREATE TABLE `rights_template` (
  `id` int(11) NOT NULL,
  `id_table` int(11) NOT NULL,
  `login` varchar(64) NOT NULL,
  `rights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `rights_template`
--

INSERT INTO `rights_template` (`id`, `id_table`, `login`, `rights`) VALUES
(24, 12, 'admin', 15),
(25, 13, 'admin', 15);

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
(5, 'Инженер', 82);

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
('GNo8pNbvRww', 'admin', '2e41b35f728f2e746e01bbe143905968', 0, '', '2017-12-08 15:36:19'),
('G2382WMJO44', 'admin', 'b3df1ea4c44c0edc63d40e298fe1b940', 0, '', '2017-12-05 13:22:09');

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
(17, 'Пользователи', 'Пользователи', '', 0),
(19, 'Таблица подключений для САПР', 'Проект', '', 0);

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
(12, 'RX3i', 'Плата', 'xcvxcv', 0),
(13, 'БП 16 RX3i', 'Модуль', 'xcvxc', 0);

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
(12, 'q', 'БП 16 RX3i', '', ''),
(13, 'w', 'БП 16 RX3i', '', ''),
(14, 'e', 'БП 16 RX3i', '', ''),
(15, 'r', 'БП 16 RX3i', '', ''),
(16, 't', 'БП 16 RX3i', '', ''),
(17, 'y', 'БП 16 RX3i', '', ''),
(18, 'u', 'БП 16 RX3i', '', ''),
(19, 'i', 'БП 16 RX3i', '', '');

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
(1271, 17, 'Имя пользователя', 0, '[\"Рученькин\",\"Михаил\",\"Андреевич\",\"\",\"mwork92@gmail.com\",0]'),
(1272, 17, 'Имя пользователя', 0, '[\"Руенькина\",\"Карина\",\"Александровна\",\"\",\"\",0]'),
(1273, 17, 'Имя пользователя', 0, '[\"Рученькин\",\"Андрей\",\"Александрович\",\"\",\"\",0]'),
(1274, 17, 'Имя пользователя', 0, '[\"Рученькина\",\"Наринэ\",\"Суреновна\",\"\",\"\",0]'),
(1275, 19, 'УСО/ШУ', 0, '[\"УСО ПЭБ\",\"\"]'),
(1276, 19, 'Шкаф', 1275, '[\"АСУЭ.021.УСО\",\"АСУЭ.021.УСО\",3844]'),
(1277, 19, 'Устройство', 1276, '[\"BR (ПЛК RX3i)\",\"RX3i\",\"нет\",\"нет\"]'),
(1280, 19, 'Плата', 1277, '[\"BR1 (БП 16 RX3i)\",\"\",\"BR1\",\"IC695CHS016\"]'),
(1281, 19, 'Модуль', 1280, '[\"BR1.12 (MDL660)\",\"Модуль дискретного ввода RX3i\",\"BR1.12\",\"IC694MDL660\",\"DI\"]'),
(1284, 19, 'Модуль', 1280, '[\"BR1.11 (ALG616)\",\"Модуль аналогового ввода RX3i\",\"BR1.11\",\"IC695ALG616\",\"AI\"]'),
(1285, 19, 'Модуль', 1280, '[\"BR1.7 (MDL660)\",\"Модуль дискретного ввода RX3i\",\"BR1.12\",\"IC694MDL660\",\"DI\"]');

-- --------------------------------------------------------

--
-- Структура таблицы `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `info` varchar(4096) DEFAULT NULL,
  `file_list` varchar(2048) NOT NULL,
  `check_list` varchar(2048) NOT NULL,
  `date_begin` datetime NOT NULL,
  `dead_line` datetime NOT NULL,
  `no_dead_line` tinyint(1) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `info`, `file_list`, `check_list`, `date_begin`, `dead_line`, `no_dead_line`, `status`) VALUES
(1, 'Задача admin', '', '[]', '[]', '2017-12-08 13:05:08', '2017-12-08 00:00:00', 1, 2),
(2, 'Задача admin2', '', '[]', '[]', '2017-12-08 13:05:39', '2017-12-08 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_people`
--

CREATE TABLE `tasks_people` (
  `id` int(11) NOT NULL,
  `login` varchar(32) NOT NULL,
  `id_task` int(11) NOT NULL,
  `role` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tasks_people`
--

INSERT INTO `tasks_people` (`id`, `login`, `id_task`, `role`) VALUES
(1, 'admin2', 1, 'director'),
(2, 'admin', 1, 'responsible'),
(3, 'admin3', 1, 'observer'),
(4, 'admin', 2, 'director'),
(5, 'admin2', 2, 'responsible'),
(6, 'admin3', 2, 'observer');

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
('Имя пользователя', '[]', '[]', '[{\"name\":\"Фамилия\",\"type\":\"VARCHAR\"},{\"name\":\"Имя\",\"type\":\"VARCHAR\"},{\"name\":\"Отчество\",\"type\":\"VARCHAR\"},{\"name\":\"Логин\",\"type\":\"VARCHAR\"},{\"name\":\"Почта\",\"type\":\"VARCHAR\"},{\"name\":\"Табельный номер\",\"type\":\"INT\"}]', NULL),
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
('Имя платы', '[\"БП 16 RX3i\",\"БП 12 RX3i\",\"aadsssss\"]', NULL),
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
-- Индексы таблицы `rights_template`
--
ALTER TABLE `rights_template`
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
-- Индексы таблицы `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_people`
--
ALTER TABLE `tasks_people`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `rights_template`
--
ALTER TABLE `rights_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `table_big`
--
ALTER TABLE `table_big`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `table_initialization`
--
ALTER TABLE `table_initialization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `table_init_12`
--
ALTER TABLE `table_init_12`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `table_init_13`
--
ALTER TABLE `table_init_13`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `table_tree_big`
--
ALTER TABLE `table_tree_big`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1286;

--
-- AUTO_INCREMENT для таблицы `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tasks_people`
--
ALTER TABLE `tasks_people`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
