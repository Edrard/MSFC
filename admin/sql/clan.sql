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
-- База данных: `test2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `col_medals`
--

CREATE TABLE IF NOT EXISTS `col_medals` (
  `account_id` int(12) NOT NULL,
  `up` int(12) NOT NULL,
  `medalCarius` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalHalonen` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `invader` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalFadin` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `armorPiercer` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalEkins` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `mousebane` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalKay` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `defender` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalLeClerc` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `supporter` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `steelwall` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalAbrams` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalPoppel` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalOrlik` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `handOfDeath` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `sniper` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `warrior` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `titleSniper` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalWittmann` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalBurda` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `scout` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `beasthunter` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `kamikaze` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `raider` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalOskin` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalBillotte` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalLavrinenko` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalKolobanov` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `invincible` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `lumberjack` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `tankExpert` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `diehard` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalKnispel` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `maxDiehardSeries` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `maxInvincibleSeries` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `maxPiercingSeries` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `maxKillingSeries` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `maxSniperSeries` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalBoelter` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `sinai` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `evileye` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalDeLanglade` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalTamadaYoshio` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalNikolas` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalLehvaslaiho` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalDumitru` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalPascucci` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalLafayettePool` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalRadleyWalters` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalTarczay` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalBrunoPietro` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalCrucialContribution` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `medalBrothersInArms` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `huntsman` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `luckyDevil` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `ironMan` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `sturdy` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `pattonValley` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `heroesOfRassenay` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `bombardier` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `mechanicEngineer` smallint(11) UNSIGNED NOT NULL DEFAULT 0,
  `tankExperts_usa` tinyint(1) NOT NULL DEFAULT 0,
  `tankExperts_france` tinyint(1) NOT NULL DEFAULT 0,
  `tankExperts_ussr` tinyint(1) NOT NULL DEFAULT 0,
  `tankExperts_china` tinyint(1) NOT NULL DEFAULT 0,
  `tankExperts_uk` tinyint(1) NOT NULL DEFAULT 0,
  `tankExperts_germany` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_usa` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_france` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_ussr` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_china` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_uk` tinyint(1) NOT NULL DEFAULT 0,
  `mechanicEngineers_germany` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Структура таблицы `col_players`
--

CREATE TABLE IF NOT EXISTS `col_players` (
  `account_id` int(12) NOT NULL,
  `name` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `server` varchar(2) NOT NULL,
  `reg` int(12) NOT NULL,
  `local` int(12) NOT NULL,
  `member_since` varchar(11) NOT NULL,
  `up` int(12) NOT NULL,
  `total` int(8) NOT NULL,
  `win` int(8) NOT NULL,
  `lose` int(8) NOT NULL,
  `alive` int(8) NOT NULL,
  `des` int(8) NOT NULL,
  `spot` int(8) NOT NULL,
  `accure` int(3) NOT NULL,
  `dmg` int(15) NOT NULL,
  `cap` int(12) NOT NULL,
  `def` int(12) NOT NULL,
  `exp` int(15) NOT NULL,
  `averag_exp` int(4) NOT NULL,
  `max_exp` int(4) NOT NULL,
  `gr_v` int(12) NOT NULL,
  `gr_p` int(12) NOT NULL,
  `wb_v` int(12) NOT NULL,
  `wb_p` int(12) NOT NULL,
  `eb_v` int(12) NOT NULL,
  `eb_p` int(12) NOT NULL,
  `win_v` int(12) NOT NULL,
  `win_p` int(12) NOT NULL,
  `gpl_v` int(12) NOT NULL,
  `gpl_p` int(12) NOT NULL,
  `cpt_p` int(12) NOT NULL,
  `cpt_v` int(12) NOT NULL,
  `dmg_p` int(12) NOT NULL,
  `dmg_v` int(12) NOT NULL,
  `dpt_p` int(12) NOT NULL,
  `dpt_v` int(12) NOT NULL,
  `frg_p` int(12) NOT NULL,
  `frg_v` int(12) NOT NULL,
  `spt_p` int(12) NOT NULL,
  `spt_v` int(12) NOT NULL,
  `exp_p` int(12) NOT NULL,
  `exp_v` int(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Структура таблицы `multiclan`
--

CREATE TABLE IF NOT EXISTS `multiclan` (
  `id` bigint(225) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `sort` int(9) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `server` varchar(3) NOT NULL,
  `cron` int(13) NOT NULL DEFAULT 0,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Структура таблицы `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `name` varchar(15) NOT NULL,
  `value` varchar(25) NOT NULL,
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
('multiget', '10'),
('autoclean', '0'),
('cron_auth', '0'),
('cron_multi', '0'),
('a_rights', '2'),
('we_loosed', '172800'),
('new_players', '172800'),
('main_progress', '172800'),
('medal_progress', '172800'),
('version', '2.2.1'),
('new_tanks', '172800');

-- --------------------------------------------------------

--
-- Структура таблицы `gk`
--

CREATE TABLE IF NOT EXISTS `gk` (
  `name` varchar(50) NOT NULL,
  `tank` varchar(50) NOT NULL,
  `time` int(20) NOT NULL,
  KEY `name` (`name`)
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

--
-- Структура таблицы `tanks`
--

CREATE TABLE IF NOT EXISTS `tanks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL,
  `tank` varchar(40) NOT NULL,
  `nation` varchar(20) NOT NULL,
  `lvl` varchar(4) NOT NULL,
  `type` varchar(15) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `top_tanks`
--

CREATE TABLE IF NOT EXISTS `top_tanks` (
  `title` varchar(40) NOT NULL,
  `lvl` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `shortname` varchar(30) NOT NULL DEFAULT '',
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

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

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