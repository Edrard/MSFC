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
  `battles_count_rank` int(12) NOT NULL DEFAULT '0',
  `wins_ratio_rank` int(12) NOT NULL DEFAULT '0',
  `frags_count_rank` int(12) NOT NULL DEFAULT '0',
  `damage_dealt_rank` int(12) NOT NULL DEFAULT '0',
  `xp_avg_rank` int(12) NOT NULL DEFAULT '0',
  `xp_max_rank` int(12) NOT NULL DEFAULT '0',
  `hits_ratio_rank` int(12) NOT NULL DEFAULT '0',
  `battles_count_value` int(12) NOT NULL DEFAULT '0',
  `wins_ratio_value` int(12) NOT NULL DEFAULT '0',
  `frags_count_value` int(12) NOT NULL DEFAULT '0',
  `damage_dealt_value` int(12) NOT NULL DEFAULT '0',
  `xp_avg_value` int(12) NOT NULL DEFAULT '0',
  `xp_max_value` int(12) NOT NULL DEFAULT '0',
  `hits_ratio_value` int(12) NOT NULL DEFAULT '0',
  `survived_ratio_rank` int(12) NOT NULL DEFAULT '0',
  `survived_ratio_value` int(12) NOT NULL DEFAULT '0',
  `global_rating_rank` int(12) NOT NULL DEFAULT '0',
  `global_rating_value` int(12) NOT NULL DEFAULT '0'
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
('api_lang', 'ru'),
('try_count', '5'),
('server', 'ru'),
('clan', '37'),
('cache', '12'),
('theme', 'ui-lightness'),
('pars', 'curl'),
('time', '2'),
('dst', '0'),
('cron', '1'),
('cron_autoclean', '0'),
('cron_cleanleft', '1'),
('cron_cleanold', '1'),
('cron_cleanold_d', '90'),
('cron_clean_log', '0'),
('news', '1'),
('cron_time', '23'),
('multiget', '20'),
('autoclean', '0'),
('cron_multi', '0'),
('a_rights', '2'),
('we_loosed', '172800'),
('new_players', '172800'),
('main_progress', '172800'),
('medal_progress', '172800'),
('version', '320.0'),
('new_tanks', '172800'),
('application_id', '54b29552a32dd5f3ade861259e38a368'),
('company', '0'),
('company_count', '1'),
('top', '5');

-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 10 2015 г., 11:46
-- Версия сервера: 10.0.10-MariaDB
-- Версия PHP: 5.4.38

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `msfc`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tronghold`
--

CREATE TABLE IF NOT EXISTS `stronghold` (
  `title` varchar(106) NOT NULL,
  `type` int(10) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(130) NOT NULL,
  `short_description` varchar(162) NOT NULL,
  `reserve|image_url` varchar(143) NOT NULL,
  `reserve|description` text NOT NULL,
  `reserve|title` varchar(99) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
  `auth` varchar(25) NOT NULL COMMENT '0 - all, 2 - admin, 1 - user',
  UNIQUE KEY `file` (`file`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tabs`
--

INSERT INTO `tabs` (`id`, `name`, `file`, `type`, `status`, `auth`) VALUES
(160, 'Укрепрайон', './stronghold.php', 1, 1, '0'),
(150, 'Очки славы', 'famepoints.php', 0, 0, '0'),
(130, 'Собственность клана', './poss.php', 1, 1, '0'),
(120, 'Запланированные атаки', './attack.php', 1, 1, '0'),
(115, 'Активность игроков', 'ajax_activity.php', 0, 1, '0'),
(1, 'Приветственное', 'avt.php', 0, 1, '0'),
(5, 'Состав', 'roster.php', 0, 1, '0'),
(70, 'Рейтинг', 'rating_all.php', 0, 1, '0'),
(50, 'Боевая эффективность', 'perform_all.php', 0, 1, '0'),
(40, 'Общие результаты', 'overall.php', 0, 1, '0'),
(60, 'Награды', 'medals_all.php', 0, 1, '0'),
(110, 'Блокированная техника', 'gk.php', 0, 1, '0'),
(30, 'Лучшие результаты', 'best.php', 0, 1, '0'),
(90, 'Наличие танков', 'available_tanks.php', 0, 1, '0'),
(100, 'Боевой опыт', 'battel.php', 0, 1, '0'),
(80, 'Техника', 'ajaxtank.php', 0, 1, '0'),
(10, 'Активность общая', 'active.php', 0, 1, '0'),
(20, 'Активность награды', 'active_1.php', 0, 1, '0');

-- --------------------------------------------------------
-- Структура таблицы `tanks`

CREATE TABLE IF NOT EXISTS `tanks` (
  `tank_id` int(8),
  `nation_i18n` varchar(20) NOT NULL,
  `level` tinyint(2) NOT NULL,
  `nation` varchar(20) NOT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(40) NOT NULL,
  `name_i18n` varchar(40) NOT NULL,
  `type` varchar(15) NOT NULL,
  `image` text NOT NULL,
  `contour_image` text NOT NULL,
  `image_small` text NOT NULL,
  PRIMARY KEY (`tank_id`)
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

-- --------------------------------------------------------
-- Структура таблицы `achievements`

CREATE TABLE IF NOT EXISTS `achievements` (
  `name` varchar(40) NOT NULL,
  `section` varchar(20) NOT NULL,
  `section_i18n` varchar(40) NOT NULL,
  `options` text NOT NULL,
  `section_order` tinyint(2) NOT NULL,
  `image` varchar(150) NOT NULL,
  `name_i18n` varchar(30) NOT NULL,
  `type` varchar(20) NOT NULL,
  `order` smallint(10) NOT NULL,
  `description` varchar(250) NOT NULL,
  `condition` varchar(500) NOT NULL,
  `hero_info` varchar(250) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;