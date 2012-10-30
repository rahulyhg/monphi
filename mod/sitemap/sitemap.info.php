<?php
# basic information
$name = "Site Map";
$description = "The sitemap creates an XML based page for search engine optimization.";
$version = "20110628";
$core = "alpha";

$moduleInsert = "
INSERT INTO `modules` (`name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
('Site Map', 'The sitemap creates an XML based page for search engine optimization.', 'mod/sitemap/', 'sitemap_content.php', 'sitemap_content.tpl.html', 'mod_sitemap', '', 1);
";

$blocksInsert = "
INSERT INTO `module_blocks` (`mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
([mid], 'sitemap', 'sitemap.php', 0, 1);


";
$moduleTable = "
CREATE TABLE IF NOT EXISTS `mod_sitemap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT 'page id',
  `changefreq` varchar(7) NOT NULL DEFAULT 'monthly' COMMENT 'for xml sitemap',
  `priority` varchar(3) NOT NULL DEFAULT '0.5' COMMENT 'sitemap priority',
  `boolSitemapHide` tinyint(1) NOT NULL COMMENT 'Hide from sitemap',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;
";
$moduleTable = "
CREATE TABLE IF NOT EXISTS `mod_sitemap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT 'page id',
  `changefreq` varchar(7) NOT NULL DEFAULT 'monthly' COMMENT 'for xml sitemap',
  `priority` varchar(3) NOT NULL DEFAULT '0.5' COMMENT 'sitemap priority',
  `boolSitemapHide` tinyint(1) NOT NULL COMMENT 'Hide from sitemap',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;
";
$moduleTableInsert = "
INSERT INTO `mod_sitemap` (`pid`, `changefreq`, `priority`, `boolSitemapHide`) VALUES
";
$moduleTableValue = "
('[pid]', 'never', '0.5', 0)
";
/*
$moduleInsert = "
INSERT INTO `modules` (`id`, `name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
(2, 'Add To Any', 'The add to any module adds a social networking sharing button to the page.\r\nhttp://www.addtoany.com/\r\n', 'mod/addtoany/', '', '', '', '', 0);
";

$blocksInsert = "INSERT INTO `module_blocks` (`id`, `mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
(2, 2, 'addtoany', 'addtoany.php', 0, 0);
";
*/

?>