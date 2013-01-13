-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 29 2012 г., 13:44
-- Версия сервера: 5.5.16
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `wot`
--

-- --------------------------------------------------------

--
-- Структура таблицы `gk`
--

DROP TABLE IF EXISTS `gk`;
CREATE TABLE IF NOT EXISTS `gk` (
  `name` varchar(50) NOT NULL,
  `tank` varchar(50) NOT NULL,
  `time` int(20) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
DROP TABLE IF EXISTS `tabs`;
CREATE TABLE IF NOT EXISTS `tabs` (
  `id` mediumint(10) NOT NULL,
  `name` varchar(65) NOT NULL,
  `file` varchar(65) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0 -normal, 1-ajax',
  `status` tinyint(1) NOT NULL COMMENT '0 - Off, 1 - On',
  `auth` varchar(25) NOT NULL COMMENT 'all, admin, user'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tabs`
--

INSERT INTO `tabs` (`id`, `name`, `file`, `type`, `status`, `auth`) VALUES
(130, 'Собственность клана', './poss.php', 1, 1, 'all'),
(120, 'Запланированные атаки', './attack.php', 1, 1, 'all'),
(1, 'Состав', 'roster.php', 0, 1, 'all'),
(70, 'Рейтинг', 'rating_all.php', 0, 1, 'all'),
(50, 'Боевая эффективность', 'perform_all.php', 0, 1, 'all'),
(40, 'Общие результаты', 'overall.php', 0, 1, 'all'),
(60, 'Награды', 'medals_all.php', 0, 1, 'all'),
(110, 'Блокированная техника', 'gk.php', 0, 1, 'all'),
(30, 'Лучшие результаты', 'best.php', 0, 1, 'all'),
(90, 'Наличие танков', 'aval_top_tank.php', 0, 1, 'all'),
(100, 'Боевой опыт', 'battel.php', 0, 1, 'all'),
(80, 'Техника', 'ajaxtank.php', 0, 1, 'all'),
(10, 'Активность общая', 'active.php', 0, 1, 'all'),
(20, 'Активность награды', 'active_1.php', 0, 1, 'all');