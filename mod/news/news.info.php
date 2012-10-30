<?php
# basic information
$name = "News";
$description = "News Module creates additional content view. A news block for displaying the primary news. A news archive block for displaying all archived news.";
$version = "20110628";
$core = "alpha";

$moduleInsert = "
INSERT INTO `modules` (`name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
('News', 'News Module creates additional content view. A news block for displaying the primary news. A news archive block for displaying all archived news.', 'mod/news/', 'news_content.php', 'news_content.tpl.html', 'mod_news', '', 1);


";

$blocksInsert = "
INSERT INTO `module_blocks` (`mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
([mid], 'news', 'news.php', 0, 0),
([mid], 'news-archive', 'news-archive.php', 0, 0);
";
$moduleTable = "
CREATE TABLE IF NOT EXISTS `mod_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT 'page id',
  `boolDisplayNews` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'display page in news section',
  `boolArchiveNews` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This boolean will allow display the article in the archived news section',
  `boolNewsPagetitle` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Display Page Title as Bold in News',
  `boolNewsContent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'use the news content on the news page use with more boolean',
  `boolNewsMore` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'display more link in news article',
  `contentNews` longtext NOT NULL COMMENT 'if boolnewscontent is used display this in front page use with boolbbcode',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0;
";
$moduleTableInsert = "
INSERT INTO `mod_news` (`pid`, `boolDisplayNews`, `boolArchiveNews`, `boolNewsPagetitle`, `boolNewsContent`, `boolNewsMore`, `contentNews`) VALUES
";
$moduleTableValue = "
([pid], 0, 0, 0, 0, 0, '')
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