-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 10 2017 г., 22:21
-- Версия сервера: 5.5.50
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `chameleon`
--

-- --------------------------------------------------------

--
-- Структура таблицы `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `authors`
--

INSERT INTO `authors` (`id`, `firstName`, `lastName`) VALUES
(1, 'Роджер', 'Желязны'),
(2, 'Виктор', 'Пелевин'),
(4, 'Анджей', 'Сапковски'),
(5, 'Джон Р.', 'Толкин');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT 'Название',
  `date_create` datetime NOT NULL COMMENT 'Дата создания записи',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления записи',
  `preview` varchar(100) NOT NULL COMMENT 'Превью',
  `date` date NOT NULL COMMENT 'Дата выхода книги'
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `author_id`, `name`, `date_create`, `date_update`, `preview`, `date`) VALUES
(31, 2, 'Empire V-1', '2016-12-30 01:12:19', '2017-01-05 09:01:51', 'preview_31_05-11-01.jpg', '2006-12-10'),
(32, 1, 'Ночь в тоскливом октябре', '2016-12-30 02:12:37', '2017-01-03 09:40:39', 'preview_32.jpg', '1993-12-31'),
(42, 4, 'Башня Ласточки', '2016-12-30 14:33:16', '2017-01-03 09:41:34', 'preview_42.jpg', '1997-12-17'),
(44, 2, 'Поколение  П', '2016-12-31 00:22:44', '2017-01-03 09:37:22', 'preview_44.jpg', '1999-12-10'),
(46, 4, 'Последнее желание 2234', '2017-01-02 14:22:48', '2017-01-04 18:38:01', 'preview_46.jpg', '1990-12-08'),
(47, 1, 'Хроники Амбера', '2017-01-02 14:36:29', '2017-01-03 09:34:46', 'preview_47.jpg', '1991-01-14'),
(48, 4, 'Цири', '2017-01-02 14:53:13', '2017-01-03 09:33:24', 'preview_48.jpg', '1997-10-13'),
(49, 1, 'Князь Света', '2017-01-02 14:55:45', '2017-01-03 09:32:15', 'preview_49.jpg', '1967-01-13'),
(50, 5, 'Властелин колец - Братство кольца', '2017-01-02 14:58:45', '2017-01-03 09:31:15', 'preview_50.jpg', '1954-01-22'),
(51, 5, 'Властелин колец - Две крепости', '2017-01-02 15:09:42', '2017-01-03 14:03:31', 'preview_51.jpg', '1954-01-13'),
(52, 1, 'Двери в песке', '2017-01-02 22:46:23', '2017-01-03 09:29:19', 'preview_52.jpg', '1976-01-01'),
(53, 5, 'Властелин колец  - Возвращение короля', '2017-01-03 09:11:11', '2017-01-03 09:28:31', 'preview_53.jpg', '1955-01-01'),
(54, 1, 'Джек из Тени', '2017-01-03 09:12:38', '2017-01-03 09:27:41', 'preview_54.jpg', '1971-01-01'),
(55, 1, 'Владычица озера', '2017-01-03 10:00:09', '2017-01-03 09:50:42', 'preview_55.jpg', '1998-02-01'),
(56, 2, 'Священная книга оборотня', '2017-01-03 10:03:18', '2017-01-03 09:23:23', 'preview_56.jpg', '2001-01-01'),
(57, 5, 'Новая книга', '2017-01-03 11:49:36', '2017-01-03 12:04:22', 'preview_57.jpg', '2017-01-01'),
(58, 1, 'egewrgwerg', '2017-01-03 14:22:45', '2017-01-03 14:05:08', 'preview_58.jpg', '2017-01-13'),
(59, 1, 'Священная книга оборотня', '2017-01-03 16:05:59', '2017-01-10 20:13:22', 'preview_59.jpg', '2017-01-01'),
(60, 1, 'Lord of the Light', '2017-01-04 09:47:19', '2017-01-10 20:12:36', 'preview_60.jpg', '2017-01-01');

-- --------------------------------------------------------

--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL,
  `attribute` varchar(50) NOT NULL,
  `value` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`id`, `attribute`, `value`) VALUES
(1, 'tmpImage', '../preview/tmp/tmpimg.jpg'),
(2, 'pathToPreview', '../preview/');

-- --------------------------------------------------------

--
-- Структура таблицы `filter`
--

CREATE TABLE IF NOT EXISTS `filter` (
  `id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `d1` date DEFAULT NULL,
  `d2` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `filter`
--

INSERT INTO `filter` (`id`, `author_id`, `name`, `d1`, `d2`) VALUES
(1, 1, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `userName` varchar(50) DEFAULT NULL,
  `userEmail` varchar(50) DEFAULT NULL,
  `userPassword` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `userName`, `userEmail`, `userPassword`) VALUES
(1, 'oleksii', NULL, '5bff5d128a4059473573f9508ff23d54d97e6899'),
(2, 'chameleon', NULL, '04e8696e6424c21d717e46008780505d598eb59a'),
(3, 'user', NULL, '8cb2237d0679ca88db6464eac60da96345513964'),
(4, 'user1', NULL, '8cb2237d0679ca88db6464eac60da96345513964');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT для таблицы `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `filter`
--
ALTER TABLE `filter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
