
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

ALTER TABLE  col_medals RENAME msfc_col_medals ;
ALTER TABLE  col_players RENAME msfc_col_players ;
ALTER TABLE  col_rating_tank_china RENAME msfc_col_rating_tank_china ;
ALTER TABLE  col_rating_tank_france RENAME msfc_col_rating_tank_france ;
ALTER TABLE  col_rating_tank_germany RENAME msfc_col_rating_tank_germany ;
ALTER TABLE  col_rating_tank_uk RENAME msfc_col_rating_tank_uk ;
ALTER TABLE  col_rating_tank_usa RENAME msfc_col_rating_tank_usa ;

ALTER TABLE  col_rating_tank_ussr RENAME msfc_col_rating_tank_ussr;
ALTER TABLE  col_tank_china RENAME msfc_col_tank_china;
ALTER TABLE  col_tank_france RENAME msfc_col_tank_france;
ALTER TABLE  col_tank_germany RENAME msfc_col_tank_germany;
ALTER TABLE  col_tank_uk RENAME msfc_col_tank_uk;
ALTER TABLE  col_tank_usa RENAME msfc_col_tank_usa;
ALTER TABLE  col_tank_ussr RENAME msfc_col_tank_ussr;
ALTER TABLE  config RENAME msfc_config;
ALTER TABLE  gk RENAME msfc_gk;
ALTER TABLE  medals RENAME  msfc_medals;
ALTER TABLE  tabs RENAME  msfc_tabs;
ALTER TABLE  tanks RENAME  msfc_tanks;
ALTER TABLE  top_tanks RENAME  msfc_top_tanks;
ALTER TABLE  users RENAME  msfc_users;

INSERT INTO `config` (`name`, `value`) VALUES
('theme', 'ui-lightness'),
('multiget', '10'),
('autoclean', '0'),
('cron_auth', '0'),
('cron_multi', '0'),
('a_rights', '2');


CREATE TABLE IF NOT EXISTS `multiclan` (
  `id` int(25) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `sort` int(9) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `server` varchar(3) NOT NULL,
  `cron` int(13) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;