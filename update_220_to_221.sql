CREATE TABLE IF NOT EXISTS `top_tanks_presets` (
  `id` TINYINT(3) NOT NULL AUTO_INCREMENT,
  `lvl` tinyint(3) unsigned NOT NULL,
  `type` varchar(15) NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '0',
  `index` tinyint(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `top_tanks_presets` (`lvl` , `type` , `show` , `index`) VALUES
('10', 'heavyTank', '1', '1' ),
('10', 'mediumTank', '0', '2'),
('10', 'AT-SPG', '0', '3'),
('8', 'SPG', '0', '4');