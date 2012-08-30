-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 20 2012 г., 14:06
-- Версия сервера: 5.5.25a-cll
-- Версия PHP: 5.3.15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `test3`
--

DROP TABLE gk;
DROP TABLE tabs;
DROP TABLE top_tanks;

-- --------------------------------------------------------

--
-- Структура таблицы `gk`
--

CREATE TABLE IF NOT EXISTS `gk` (
  `id` mediumint(10) NOT NULL,
  `tank` varchar(50) NOT NULL,
  `time` int(20) NOT NULL,
  KEY `name` (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tabs`
--

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
(1, 'Состав', 'roster.php', 0, 1, 'all'),
(10, 'Лучшие результаты', 'best.php', 0, 1, 'all'),
(20, 'Активность общая', 'active.php', 0, 1, 'all'),
(30, 'Активность награды', 'active_1.php', 0, 1, 'all'),
(40, 'Средняя производительность', 'average_perform.php', 0, 1, 'all'),
(50, 'Боевая эффективность', 'perform.php', 0, 1, 'all'),
(60, 'Общие результаты', 'overall.php', 0, 1, 'all'),
(70, 'Боевой опыт', 'battel.php', 0, 1, 'all'),
(140, 'Рейтинг (Место)', 'rating.php', 0, 1, 'all'),
(80, 'Рейтинг (Значение)', 'rating_1.php', 0, 1, 'all'),
(160, 'Бронетехника 10', 'tank10.php', 0, 1, 'all'),
(150, 'Бронетехника 9', 'tank9.php', 0, 1, 'all'),
(170, 'Арта 7', 'spg7.php', 0, 1, 'all'),
(180, 'Арта 8', 'spg8.php', 0, 1, 'all'),
(200, 'Этапные достижения', 'major.php', 0, 1, 'all'),
(210, 'Герой битвы', 'hero.php', 0, 1, 'all'),
(220, 'Эпические достижения', 'epic.php', 0, 1, 'all'),
(230, 'Почетные звания', 'special.php', 0, 1, 'all'),
(240, 'Запланированные атаки', './attack.php', 1, 1, 'all'),
(250, 'Собственность клана', './poss.php', 1, 1, 'all'),
(190, 'Наличие танков', 'aval_top_tank.php', 0, 1, 'all'),
(100, 'Бронетехника 10(тт)', 'tank10heavy.php', 0, 1, 'all'),
(90, 'Блокированная техника', 'gk.php', 0, 1, 'all'),
(110, 'Бронетехника 10(остальная)', 'tank10others.php', 0, 1, 'all'),
(120, 'Бронетехника 9(тт)', 'tank9heavy.php', 0, 1, 'all'),
(130, 'Бронетехника 9(остальная)', 'tank9others.php', 0, 1, 'all');

-- --------------------------------------------------------

--
-- Структура таблицы `top_tanks`
--

CREATE TABLE IF NOT EXISTS `top_tanks` (
  `title` varchar(25) NOT NULL,
  `lvl` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `shortname` varchar(20) NOT NULL DEFAULT '',
  `index` tinyint(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`title`),
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `top_tanks`
--

INSERT INTO `top_tanks` (`title`, `lvl`, `type`, `show`, `order`, `shortname`, `index`) VALUES
('Maus', 10, 'heavyTank', 1, 40, '', 1),
('F10_AMX_50B', 10, 'heavyTank', 1, 30, '', 1),
('IS-7', 10, 'heavyTank', 1, 20, '', 1),
('E-100', 10, 'heavyTank', 1, 30, '', 1),
('T110', 10, 'heavyTank', 1, 10, '', 1),
('IS-4', 10, 'heavyTank', 1, 50, '', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
