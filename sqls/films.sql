-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 29 2020 г., 19:27
-- Версия сервера: 10.1.37-MariaDB
-- Версия PHP: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `films`
--

-- --------------------------------------------------------

--
-- Структура таблицы `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `image` tinytext NOT NULL,
  `title` tinytext NOT NULL,
  `code` tinytext NOT NULL,
  `genreCode` tinytext NOT NULL,
  `info` text NOT NULL,
  `description` text NOT NULL,
  `dateAdded` datetime NOT NULL,
  `link` text NOT NULL,
  `topFilm` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `films`
--

INSERT INTO `films` (`id`, `image`, `title`, `code`, `genreCode`, `info`, `description`, `dateAdded`, `link`, `topFilm`) VALUES
(1, 'img/batman.jpg', 'Batman', 'batman', 'crime,drama', '<b>Премьера:</b> 25 июня 2021 г. (США)</br>\r\n<b>Режиссер:</b> Мэтт Ривз</br>\r\n<b>Композитор:</b> Майкл Джаккино</br>\r\n<b>Оператор:</b> Грег Фрайзер</br>\r\n<b>Сценарий:</b> Мэтт Ривз, Билл Фингер</br>\r\n<b>Длительность:</b> 130 мин', 'Фильм перезагружает серию фильмов о Бэтмене, рассказывая историю происхождения Брюса Уэйна от смерти его родителей до его путешествия, чтобы стать Бэтменом, и его битвы за то, чтобы остановить Рас-аль-Гул и Пугало от погружения Готэм-сити в хаос.', '2020-02-08 00:00:00', 'https://www.youtube.com/embed/cElcLipGNIA', 0),
(2, 'img/joker.jpg', 'Joker', 'joker', 'drama,thriller', '<b>Премьера:</b> 31 августа 2019 г. (Италия)</br>\r\n<b>Режиссер:</b> Тодд Филлипс</br>\r\n<b>Сборы:</b> $1 071 030 470</br>\r\n<b>Год:</b> 2019</br>\r\n<b>Бюджет:</b> $55 млн', 'Готэм, начало 1980-х годов. Комик Артур Флек живет с больной матерью, которая с детства учит его «ходить с улыбкой». Пытаясь нести в мир хорошее и дарить людям радость, Артур сталкивается с человеческой жестокостью и постепенно приходит к выводу, что этот мир получит от него не добрую улыбку, а ухмылку злодея Джокера.', '2020-02-08 00:00:00', 'https://www.youtube.com/embed/zAGVQLHvwOY', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `code` tinytext NOT NULL,
  `title` tinytext NOT NULL,
  `listOrder` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `genres`
--

INSERT INTO `genres` (`id`, `code`, `title`, `listOrder`) VALUES
(1, 'amateur', 'Любительские', 1),
(2, 'military', 'Военный', 2),
(3, 'militants', 'Боевики', 3),
(4, 'fantasy', 'Фантастика', 4),
(5, 'comedy', 'Комедия', 5),
(6, 'detective', 'Детектив', 6),
(7, 'prison', 'Тюремные', 7),
(8, 'crime', 'Криминал', 8),
(9, 'serials', 'Сериалы', 9),
(10, 'drama', 'Драма', 10),
(11, 'thriller', 'Триллеры', 11);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
