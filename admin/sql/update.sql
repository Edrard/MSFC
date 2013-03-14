
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

ALTER TABLE  col_medals RENAME `col_medals`;
ALTER TABLE  col_players RENAME `col_players`;
ALTER TABLE  col_rating_tank_china RENAME `col_rating_tank_china`;
ALTER TABLE  col_rating_tank_france RENAME `col_rating_tank_france`;
ALTER TABLE  col_rating_tank_germany RENAME `col_rating_tank_germany`;
ALTER TABLE  col_rating_tank_uk RENAME `col_rating_tank_uk`;
ALTER TABLE  col_rating_tank_usa RENAME `col_rating_tank_usa`;
ALTER TABLE  col_rating_tank_ussr RENAME `col_rating_tank_ussr`;
ALTER TABLE  col_tank_china RENAME `col_tank_china`;
ALTER TABLE  col_tank_france RENAME `col_tank_france`;
ALTER TABLE  col_tank_germany RENAME `col_tank_germany`;
ALTER TABLE  col_tank_uk RENAME `col_tank_uk`;
ALTER TABLE  col_tank_usa RENAME `col_tank_usa`;
ALTER TABLE  col_tank_ussr RENAME `col_tank_ussr`;
ALTER TABLE  config RENAME `config`;
ALTER TABLE  gk RENAME `gk`;
ALTER TABLE  tabs RENAME `tabs`;
ALTER TABLE  tanks RENAME `tanks`;
ALTER TABLE  top_tanks RENAME `top_tanks`;
ALTER TABLE  users RENAME msfcmt_users;
ALTER TABLE  msfcmt_users ADD `prefix` VARCHAR(6) NOT NULL DEFAULT 'all' COMMENT 'all - login to all multiclans';

INSERT INTO `config` (`name`, `value`) VALUES
('theme', 'ui-lightness'),
('multiget', '10'),
('autoclean', '0'),
('cron_auth', '0'),
('cron_multi', '0'),
('a_rights', '2'),
('we_loosed', '172800'),
('new_players', '172800'),
('main_progress', '172800'),
('medal_progress', '172800'),
('new_tanks', '172800');

CREATE TABLE IF NOT EXISTS `multiclan` (
  `id` int(25) NOT NULL,
  `prefix` varchar(64) NOT NULL,
  `sort` int(9) NOT NULL,
  `main` int(1) NOT NULL DEFAULT '0',
  `server` varchar(3) NOT NULL,
  `cron` int(13) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;