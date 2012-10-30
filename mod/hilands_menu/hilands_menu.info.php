<?php
# basic information
$name = "Hilands Menu";
$description = "Menu's used on hilands.com";
$version = "20110625";
$core = "alpha";


/*
mod/hilands_menu/menu.inc.php 	navigationmenu
mod/hilands_menu/submenu.inc.php 	submenu
both boolLoadinAll
*/
$moduleInsert = "
INSERT INTO `modules` (`name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
('Hilands Menu', 'Menu\'s used on hilands.com', 'mod/hilands_menu/', '', '', '', '', 1);
";

$blocksInsert = "
INSERT INTO `module_blocks` (`mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
([mid], 'navigationmenu', 'menu.inc.php', 1, 0),
([mid], 'submenu', 'submenu.inc.php', 1, 0)
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