-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Сен 07 2012 г., 15:48
-- Версия сервера: 5.5.27
-- Версия PHP: 5.3.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: ``
--

-- --------------------------------------------------------
-- Структура таблицы `col_players`

CREATE TABLE IF NOT EXISTS `col_players` (
  `account_id` int(12) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `updated_at` int(12) NOT NULL,
  `all_spotted` int(8) NOT NULL,
  `all_hits` int(8) NOT NULL,
  `all_battle_avg_xp` int(8) NOT NULL,
  `all_draws` int(8) NOT NULL,
  `all_wins` int(8) NOT NULL,
  `all_losses` int(8) NOT NULL,
  `all_capture_points` int(8) NOT NULL,
  `all_battles` int(8) NOT NULL,
  `all_damage_dealt` int(8) NOT NULL,
  `all_hits_percents` int(8) NOT NULL,
  `all_damage_received` int(8) NOT NULL,
  `all_shots` int(8) NOT NULL,
  `all_xp` int(8) NOT NULL,
  `all_frags` int(8) NOT NULL,
  `all_survived_battles` int(8) NOT NULL,
  `all_dropped_capture_points` int(8) NOT NULL,
  `clan_spotted` int(8) NOT NULL,
  `clan_hits` int(8) NOT NULL,
  `clan_battle_avg_xp` int(8) NOT NULL,
  `clan_draws` int(8) NOT NULL,
  `clan_wins` int(8) NOT NULL,
  `clan_losses` int(8) NOT NULL,
  `clan_capture_points` int(8) NOT NULL,
  `clan_battles` int(8) NOT NULL,
  `clan_damage_dealt` int(8) NOT NULL,
  `clan_hits_percents` int(8) NOT NULL,
  `clan_damage_received` int(8) NOT NULL,
  `clan_shots` int(8) NOT NULL,
  `clan_xp` int(8) NOT NULL,
  `clan_frags` int(8) NOT NULL,
  `clan_survived_battles` int(8) NOT NULL,
  `clan_dropped_capture_points` int(8) NOT NULL,
  `company_spotted` int(8) NOT NULL,
  `company_hits` int(8) NOT NULL,
  `company_battle_avg_xp` int(8) NOT NULL,
  `company_draws` int(8) NOT NULL,
  `company_wins` int(8) NOT NULL,
  `company_losses` int(8) NOT NULL,
  `company_capture_points` int(8) NOT NULL,
  `company_battles` int(8) NOT NULL,
  `company_damage_dealt` int(8) NOT NULL,
  `company_hits_percents` int(8) NOT NULL,
  `company_damage_received` int(8) NOT NULL,
  `company_shots` int(8) NOT NULL,
  `company_xp` int(8) NOT NULL,
  `company_frags` int(8) NOT NULL,
  `company_survived_battles` int(8) NOT NULL,
  `company_dropped_capture_points` int(8) NOT NULL,
  `max_xp` int(4) NOT NULL,
  `created_at` int(12) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Структура таблицы `col_ratings`
CREATE TABLE IF NOT EXISTS `col_ratings` (
  `account_id` int(12) NOT NULL,
  `updated_at` int(12) NOT NULL,
  `spotted_place` int(12) NOT NULL,
  `dropped_ctf_points_place` int(12) NOT NULL,
  `battle_avg_xp_place` int(12) NOT NULL,
  `battles_place` int(12) NOT NULL,
  `damage_dealt_place` int(12) NOT NULL,
  `frags_place` int(12) NOT NULL,
  `ctf_points_place` int(12) NOT NULL,
  `integrated_rating_place` int(12) NOT NULL,
  `xp_place` int(12) NOT NULL,
  `battle_avg_performance_place` int(12) NOT NULL,
  `battle_wins_place` int(12) NOT NULL,
  `spotted_value` int(12) NOT NULL,
  `dropped_ctf_points_value` int(12) NOT NULL,
  `battle_avg_xp_value` int(12) NOT NULL,
  `battles_value` int(12) NOT NULL,
  `damage_dealt_value` int(12) NOT NULL,
  `frags_value` int(12) NOT NULL,
  `ctf_points_value` int(12) NOT NULL,
  `integrated_rating_value` int(12) NOT NULL,
  `xp_value` int(12) NOT NULL,
  `battle_avg_performance_value` int(12) NOT NULL,
  `battle_wins_value` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------
-- Структура таблицы `multiclan`

CREATE TABLE IF NOT EXISTS `multiclan` (
  `id` bigint(225) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `sort` int(9) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `server` varchar(3) NOT NULL,
  `cron` int(13) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Структура таблицы `config`

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(15) NOT NULL,
  `value` varchar(33) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `config`
--

INSERT INTO `config` (`name`, `value`) VALUES
('lockin', '0'),
('lang', 'ru'),
('server', 'ru'),
('clan', '37'),
('cache', '12'),
('theme', 'ui-lightness'),
('pars', 'curl'),
('time', '2'),
('cron', '1'),
('news', '1'),
('cron_time', '23'),
('multiget', '20'),
('autoclean', '0'),
('cron_auth', '0'),
('cron_multi', '0'),
('a_rights', '2'),
('we_loosed', '172800'),
('new_players', '172800'),
('main_progress', '172800'),
('medal_progress', '172800'),
('version', '3.0.0'),
('new_tanks', '172800'),
('application_id', 'demo'),
('top', '5');

-- --------------------------------------------------------
-- Структура таблицы `gk`

CREATE TABLE IF NOT EXISTS `gk` (
  `name` varchar(50) NOT NULL,
  `tank` varchar(50) NOT NULL,
  `time` int(20) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Структура таблицы `tabs`

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
(115, 'Активность игроков', 'ajax_activity.php', 0, 1, 'all'),
(1, 'Приветственное', 'avt.php', 0, 1, 'all'),
(5, 'Состав', 'roster.php', 0, 1, 'all'),
(70, 'Рейтинг', 'rating_all.php', 0, 1, 'all'),
(50, 'Боевая эффективность', 'perform_all.php', 0, 1, 'all'),
(40, 'Общие результаты', 'overall.php', 0, 1, 'all'),
(60, 'Награды', 'medals_all.php', 0, 1, 'all'),
(110, 'Блокированная техника', 'gk.php', 0, 1, 'all'),
(30, 'Лучшие результаты', 'best.php', 0, 1, 'all'),
(90, 'Наличие танков', 'available_tanks.php', 0, 1, 'all'),
(100, 'Боевой опыт', 'battel.php', 0, 1, 'all'),
(80, 'Техника', 'ajaxtank.php', 0, 1, 'all'),
(10, 'Активность общая', 'active.php', 0, 1, 'all'),
(20, 'Активность награды', 'active_1.php', 0, 1, 'all');

-- --------------------------------------------------------
-- Структура таблицы `tanks`

CREATE TABLE IF NOT EXISTS `tanks` (
  `tank_id` int(8),
  `nation_i18n` varchar(20) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `nation` varchar(20) NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `name_i18n` varchar(40) NOT NULL,
  `type` varchar(15) NOT NULL,
  `image` text NOT NULL,
  `contour_image` text NOT NULL,
  `image_small` text NOT NULL,
  PRIMARY KEY (`tank_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Структура таблицы `top_tanks`

CREATE TABLE IF NOT EXISTS `top_tanks` (
  `tank_id` int(8) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `shortname` varchar(30) NOT NULL DEFAULT '',
  `index` tinyint(10) unsigned NOT NULL DEFAULT '1',
  KEY `index` (`index`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `top_tanks`
--

INSERT INTO `top_tanks` (`tank_id`, `show`, `order`, `shortname`, `index`) VALUES
(10785, 1, 10, 'T110E5', 1),
(14881, 1, 20, 'T57 Heavy Tank', 1),
(6929,  1, 30, 'Maus', 1),
(9489,  1, 40, 'E-100', 1),
(7169,  1, 50, 'IS-7', 1),
(6145,  1, 60, 'IS-4', 1),
(6209,  1, 70, 'AMX 50B', 1),
(5425,  1, 80, '113', 1),
(6225,  1, 90, 'FV215b', 1);

-- --------------------------------------------------------
-- Структура таблицы `users`

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `group` varchar(25) NOT NULL COMMENT 'user, admin',
  `prefix` VARCHAR(6) NOT NULL DEFAULT 'all' COMMENT 'all - login to all multiclans',
  `replays` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1 - allowed, 0 - not allowed',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT IGNORE INTO `users` (`id`, `user`, `password`, `email`, `group`, `replays`) VALUES
(1, 'admin', '20ccbe71c69cb25e4e0095483cb63bd394a12b23', 'admin@local.com', 'admin', '1'),
(2, 'user', '20ccbe71c69cb25e4e0095483cb63bd394a12b23', 'user@local.com', 'user', '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;