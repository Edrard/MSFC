INSERT INTO `config` (`name`, `value`) VALUES ('we_loosed', '172800'), ('new_players', '172800'), ('main_progress', '172800'), ('medal_progress', '172800'), ('new_tanks', '172800'), ('a_rights', '2');
DELETE FROM `tabs` WHERE file = 'aval_top_tank.php' LIMIT 1;
CREATE TABLE IF NOT EXISTS msfcmt_users (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `group` varchar(25) NOT NULL COMMENT 'user, admin',
  `prefix` VARCHAR(6) NOT NULL DEFAULT 'all' COMMENT 'all - login to all multiclans',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3 ;
INSERT IGNORE INTO msfcmt_users (`id`, `user`, `password`, `email`, `group`) VALUES
(1, 'admin', '20ccbe71c69cb25e4e0095483cb63bd394a12b23', 'admin@local.com', 'admin'),
(2, 'user', '20ccbe71c69cb25e4e0095483cb63bd394a12b23', 'user@local.com', 'user');