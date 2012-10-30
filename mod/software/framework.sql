CREATE TABLE IF NOT EXISTS `mod_software` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT 'tbl id',
  `description` longtext default NULL,
  `img` varchar(200) NOT NULL,
  `imgthumb` varchar(200) NOT NULL,
  `filesize` varchar(20) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `header` varchar(150) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `urlwebsite` varchar(255) NOT NULL,
  `urldownload` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
