<?php
# basic information
$name = "Add To Any";
$description = "The add to any module adds a social networking sharing button to the page.
http://www.addtoany.com/";
$version = "20110625";
$core = "alpha";

$moduleInsert = "
INSERT INTO `modules` (`name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
('Add To Any', 'The add to any module adds a social networking sharing button to the page.\r\nhttp://www.addtoany.com/\r\n', 'mod/addtoany/', '', '', '', '', 1);
";

$blocksInsert = "INSERT INTO `module_blocks` (`mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
([mid], 'addtoany', 'addtoany.php', 0, 0);
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