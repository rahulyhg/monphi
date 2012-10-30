<?php
################################################################################
# File Name : dbsetup.php                                                      #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011070700                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010, 2011 Philip Allen            #
#   This program is distributed under the terms of the GNU General Public      #
#   License                                                                    #
#                                                                              #
#   This file is free software; you can redistribute it and/or modify          #
#   it under the terms of the GNU General Public License as published          #
#   by the Free Software Foundation; either version 2 of the License,          #
#   or (at your option) any later version.                                     #
#                                                                              #
#   This File is distributed in the hope that it will be useful,               #
#   but WITHOUT ANY WARRANTY; without even the implied warranty of             #
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              #
#   GNU General Public License for more details.                               #
#                                                                              #
#   You should have received a copy of the GNU General Public License          #
#   along with This File; if not, write to the Free Software                   #
#   Foundation, Inc., 51 Franklin St, Fifth Floor,                             #
#   Boston, MA  02110-1301  USA                                                #
#                                                                              #
# External Files:                                                              #
#                                                                              #
# General Information (algorithm) :                                            #
#                                                                              #
################################################################################
################################################################################
# Session information                                                          #
################################################################################
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists("../inc/form_process.class.inc.php")) { include ("../inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
if (file_exists("../inc/arrstripslashes.inc.php")) { include_once ("../inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$arrFormConf = array(
	'formFile' => "dbsetup.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
	),
	'fieldText' => array(
		'user' => '<span id="select">User :</span>',
		'password' => '<span id="select">Password :</span>',
		'database' => '<span id="select">Database :</span>',
		'user2' => '<span id="admin">User :</span>',
		'password2' => '<span id="admin">Password :</span>',
		'database2' => '<span id="admin">Database :</span>',
	),
	'validation' => array(
		'user' => array(
			'required',
		),
		'password' => array(
			'required',
		),
		'database' => array(
			'required',
		),
		'user2' => array(
			'required',
		),
		'password2' => array(
			'required',
		),
	),
	'errorWrapper' => array(
		'0' => "\n".'<span style="color:#c00;">',
		'1' => "</span>\n"
	),
	'errorMsgWrapper' => array(
		'0' => "\n".'<div style="text-align:center; font-size:1.5em; font-weight:bold; color:#c00;">Error</div><div style="color:#c00; border:1px #c00 solid; padding:5px;">',
		'1' => "</div>\n"
	),
	'errorMsg' => array(
		'referer' => 'Invalid Referer [referer]- You must use the same form provided by the host processing it',
		'required' => '<b>[field]</b> is a required field and cannot be blank',
	)
);
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
################################################################################
#                                                                              #
################################################################################
#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
#echo "POST : <pre>";print_r($_POST);echo "</pre><br />\n";
#echo "GET : <pre>";print_r($_GET);echo "</pre><br />\n";
#if (empty($_REQUEST))
if (array_key_exists('submit', $_REQUEST))
	$boolDataSent = true;
else
	$boolDataSent = false;
################################################################################
#                                                                              #
################################################################################
if ($boolDataSent)
{
	// run error checking
	$refForm->checkReferer();
	$refForm->checkValidation();
	// add additional error checking here
	/*
	...Check Code...
	if(....error found...)
	{
		$refForm->boolError = true;
		$refForm->strErrorMsg .= ...addyourmessage here..."<br />\n";
	}
	*/
	// check error "list"
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		echo $refForm->strTemplate;
	}
	else
	{
		// the file permission checks were done before this should work.
		$strTemplate = "";
		########################################################################
		# dbc.conf.php write here                                              #
		########################################################################
		// read file
		if(!$fileHandle = fopen("dbc.conf.php", "r"))
		{
			echo 'cannot read file "/setup/dbc.conf.php"<br />';
			exit;
		}
		else
		{
			// store file in strTemplate
			$strTemplate = fread($fileHandle, filesize("dbc.conf.php"));
			fclose($fileHandle);
			// replace file contents with form contents
			if (get_magic_quotes_gpc())
				$_REQUEST = arrstripslashes($_REQUEST);
			if(preg_match("'dbuserhere2'", $strTemplate))
			{
				$strTemplate = str_replace("dbuserhere2", $_REQUEST['user2'], $strTemplate);
			}
			if(preg_match("'dbpasshere2'", $strTemplate))
			{
				$strTemplate = str_replace("dbpasshere2", $_REQUEST['password2'], $strTemplate);
			}

			if(preg_match("'dbuserhere'", $strTemplate))
			{
				$strTemplate = str_replace("dbuserhere", $_REQUEST['user'], $strTemplate);
			}
			if(preg_match("'dbpasshere'", $strTemplate))
			{
				$strTemplate = str_replace("dbpasshere", $_REQUEST['password'], $strTemplate);
			}

			if(preg_match("'dbnamehere'", $strTemplate))
			{
				$strTemplate = str_replace("dbnamehere", $_REQUEST['database'], $strTemplate);
			}
		}
		//echo $strTemplate."<br />\n";
		//exit;
		// write the dbc.conf.php file.
		if(!$fileHandle = fopen("../conf/dbc.conf.php", "w"))
		{
			echo 'cannot write to "/conf/dbc.conf.php"<br />';
			exit;
		}
		else
		{
			// store file in strTemplate
			fwrite($fileHandle, $strTemplate);
			fclose($fileHandle);
		}
		########################################################################
		# Populate Database Here                                               #
		########################################################################
		// late includes as file was not written
		// database stuff
		if (file_exists("../conf/dbc.conf.php")) { include_once ("../conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
		if (file_exists("../inc/dbc.class.inc.php")) { include_once ("../inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
		$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
/*
		// table module
		$strQuery = "CREATE TABLE IF NOT EXISTS `module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `boolAdd` tinyint(1) NOT NULL DEFAULT '0',
  `boolAltMenu` tinyint(1) NOT NULL DEFAULT '0',
  `boolDelete` tinyint(1) NOT NULL DEFAULT '0',
  `boolEdit` tinyint(1) NOT NULL DEFAULT '0',
  `boolLoadinall` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'load module in all pages',
  `boolOB` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'use php OB output buffer not yet implemented',
  `boolSupErrMsg` tinyint(1) NOT NULL COMMENT 'Suppress Template Error Messages',
  `description` text NOT NULL,
  `fileAdd` varchar(200) NOT NULL COMMENT 'Admin file for Modules Add form',
  `fileDelete` varchar(200) NOT NULL COMMENT 'Admin file for Modules Delete form',
  `fileEdit` varchar(200) NOT NULL COMMENT 'Admin file for Modules Edit form',
  `fileName` varchar(200) NOT NULL COMMENT 'include path from index.php mod/menu.inc.php',
  `moduleName` varchar(50) NOT NULL,
  `pathAltMenu` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "module" table<br />';
		// table modulelink
		$strQuery = "CREATE TABLE IF NOT EXISTS `modulelink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `tid` int(10) NOT NULL COMMENT 'template id',
  `mid` int(10) NOT NULL COMMENT 'module id',
  `boolEnable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'quick way to disable module incase it screws something up',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "modulelink" table<br />';
*/
		// table modules
		$strQuery = "CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'module id',
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `path` varchar(50) NOT NULL COMMENT 'module folder path',
  `content_file` varchar(50) DEFAULT NULL COMMENT 'content module file location',
  `content_tpl_file` varchar(50) DEFAULT NULL COMMENT 'content html file',
  `content_db` varchar(50) NOT NULL,
  `view_file` varchar(50) DEFAULT NULL COMMENT 'Admin view module file location',
  `boolEnable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "modules" table<br />';

		// table module_blocks
		$strQuery = "CREATE TABLE IF NOT EXISTS `module_blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT 'module ID',
  `block` varchar(50) NOT NULL,
  `block_file` varchar(50) DEFAULT NULL COMMENT 'block module file location',
  `boolLoadinall` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'load module in all pages',
  `boolSupErrMsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Suppress Template Error Messages',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "module_blocks" table<br />';

		// table module_block_links
		$strQuery = "CREATE TABLE IF NOT EXISTS `module_block_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `pid` int(10) NOT NULL COMMENT 'page id',
  `mid` int(10) NOT NULL COMMENT 'module id',
  `bid` int(10) NOT NULL COMMENT 'block id',
  `boolEnable` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'quick way to disable module incase it screws something up',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "module_block_links" table<br />';

		//table page
/*		$strQuery = "CREATE TABLE IF NOT EXISTS `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `url` varchar(150) NOT NULL,
  `boolIndex` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if this is checked this will be the default page.',
  `boolError` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if this is checked this is the error page.',
  `template` varchar(50) NOT NULL DEFAULT 'main.tpl.html' COMMENT 'html template file to use',
  `boolComments` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'boolean true or false 1 or 0 used for commenting structure',
  `htmltitle` varchar(255) NOT NULL DEFAULT 'Powered by Monphi' COMMENT 'title that displays in browser window title',
  `pagetitle` varchar(100) DEFAULT NULL COMMENT 'website variable displays as \"title\" inside the website',
  `content` longtext NOT NULL COMMENT 'web site content',
  `boolBBCode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'use bb2html function for content',
  `boolIncAmmendment` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'allows include to take passed variables of section page and article',
  `boolInclude` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'include a file defined by includepath',
  `includePath` varchar(255) NOT NULL,
  `boolForm` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'include form file defined by includeform',
  `includeForm` varchar(255) NOT NULL,
  `boolDisplayNews` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'display page in news section',
  `boolArchiveNews` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'This boolean will allow display the article in the archived news section',
  `boolNewsPagetitle` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Display Page Title as Bold in News',
  `boolNewsContent` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'use the news content on the news page use with more boolean',
  `boolNewsMore` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'display more link in news article',
  `contentNews` longtext NOT NULL COMMENT 'if boolnewscontent is used display this in front page use with boolbbcode',
  `changefreq` varchar(7) NOT NULL DEFAULT 'monthly' COMMENT 'for xml sitemap',
  `priority` varchar(3) NOT NULL DEFAULT '0.5' COMMENT 'sitemap priority',
  `boolSitemapHide` tinyint(1) NOT NULL COMMENT 'Hide from sitemap',
  `dateadded` date NOT NULL COMMENT 'date the article was added',
  `datemod` date NOT NULL COMMENT 'date article was modified',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";*/
		$strQuery = "CREATE TABLE IF NOT EXISTS `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `url` varchar(150) NOT NULL,
  `boolIndex` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if this is checked this will be the default page.',
  `boolError` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if this is checked this is the error page.',
  `template` varchar(50) NOT NULL DEFAULT 'main.tpl.html' COMMENT 'html template file to use',
  `htmltitle` varchar(255) NOT NULL DEFAULT 'Powered by Monphi' COMMENT 'title that displays in browser window title',
  `pagetitle` varchar(100) DEFAULT NULL COMMENT 'website variable displays as \"title\" inside the website',
  `content` longtext NOT NULL COMMENT 'web site content',
  `boolBBCode` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'use bb2html function for content',
  `boolIncAmmendment` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'allows include to take passed variables of section page and article',
  `boolInclude` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'include a file defined by includepath',
  `includePath` varchar(255) NOT NULL,
  `boolForm` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'include form file defined by includeform',
  `includeForm` varchar(255) NOT NULL,
  `dateadded` date NOT NULL COMMENT 'date the article was added',
  `datemod` date NOT NULL COMMENT 'date article was modified',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "page" table<br />';

		$strDate = date("Y-m-d");
		//insert default page data
		$strQuery = "
INSERT INTO `page` (`id`, `url`, `boolIndex`, `boolError`, `template`, `htmltitle`, `pagetitle`, `content`, `boolBBCode`, `boolIncAmmendment`, `boolInclude`, `includePath`, `boolForm`, `includeForm`, `dateadded`, `datemod`) VALUES
(1, 'index.html', 1, 0, 'main.tpl.html', 'The Monphi Engine - Content Mangement and HTML Template Parsing System', 'Setup Complete', '<b>About</b> - The Monphi CMS is a Content Management System that was created to simplify the design and mofication time of website design and deployment.\r\n\r\n<br /><br />\r\n\r\n<b>Where to start!</b> - Go to the Administrative control panel, by default this is located in the <a href=\"admin/\">admin</a> folder on your web server.\r\n<br /><br />\r\nStart modifying this page in the admin interface by going to the \"Content\" link and selecting the \"edit\" link for index.html. On the editing page find the text field for \"Page Title\" and the text area for \"Main Content\". Or you could simply click the button below.\r\n<br />\r\n<a class=\"button_edit_this_page\" href=\"admin/content_edit.php?id=1\" alt=\"Edit This Page\"></a>\r\n\r\n\r\n<br /><br />\r\nAfter you have successfully navigated to the administrative control panel start hacking away at a new template. We recommend modifying the main.tpl.html file located in the root directory of the Monphi CMS.\r\n\r\n<br /><br />\r\n\r\n	<b>Templates</b> - Templates can easily be created, existing or new webpages can be used as a template with the minor addition of blocks. The templates consist of HTML and simple bracketed blocks where dynamic content will be placed. The primary content is placed in the &#91;content&#93; block. Additional blocks can be added inside the content block with custom modules.\r\n\r\nThe default <b>blocks</b> for a template are :\r\n<blockquote>\r\n	<b>&#91;admin&#93;</b> - For the administrative toolbar.<br />\r\n	<b>&#91;content&#93;</b> - This is the main content pulled from the \"Main Content\" section.<br />\r\n	<b>&#91;error&#93;</b> - Location where engine errors will be displayed<br />\r\n	<b>&#91;htmltitle&#93;</b> - The title that will be displayed in your windows title bar, this should be between your templates &lt;title&gt; tags.<br />\r\n	<b>&#91;includeData&#93;</b> - Used for the output generated by the simple include<br />\r\n	<b>&#91;title&#93;</b> - title to be displayed like a \"header\", say above the main content.<br />\r\n</blockquote>\r\n\r\n<br />\r\n\r\n\r\n<b>License</b> - This software is free to use under the General Public License (GPL).<br /><br />\r\nIcons located in /img/monphi/famfamfam_silk_icons_v013 are from the <a href=\"http://www.famfamfam.com/lab/icons/silk/\">Silk icon set 1.3</a> licensed under the Creative Commons Attribution 2.5.<br />\r\n Icons located in /img/monphi/fugue-icons-2.4.3 are from the <a href=\"http://www.pinvoke.com/\">Fugue Icons set</a> licensed under the Creative Commons Attribution 3.0\r\n', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(2, 'missing.html', 0, 1, 'main.tpl.html', 'Error 404 - Page not found ', 'Error 404 - Page not found ', 'The page you requested cannot be found, it may have been moved or renamed.\r\nPlease follow the navigation menu to locate the page.', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(3, 'sitemap.xml', 0, 0, 'mod/sitemap/sitemap.tpl.html', 'sitemap', 'sitemap', '[sitemap]', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(4, 'example-news.html', 0, 0, 'main.tpl.html', 'The Monphi Engine - Example News', 'Example News', 'Below will show examples in the news\r\n<hr />\r\n[news]', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(5, 'example-news-archive.html', 0, 0, 'main.tpl.html', 'The Monphi Engine - Example News Archive', 'Example News Archive', 'This is the example of the news archive. Below will show an archive of the pages listed as news articles.\r\n<hr />\r\n[news-archive]', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(6, 'example-news-setup.html', 0, 0, 'main.tpl.html', 'The Monphi Engine - Example News Setup', 'Example News Setup', 'The Monphi Engine has been setup on one more machine. You are now being assimilated into The Monphi Engine ++ club!\r\n<br /><br />\r\nA little more text just to fill things up!', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(7, 'example-news-cool.html', 0, 0, 'main.tpl.html', 'The Monphi Engine - Example News Cool', 'Example News Cool', 'Being cool is <br /><br />\r\nThe best way to say something is neat-o, awesome, or swell. The phrase \"cool\" is very relaxed, never goes out of style, and people will never laugh at you for using it, very conveniant for people like me who don''t care about what''s \"in.\"', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."'),
(8, 'example-news-more.html', 0, 0, 'main.tpl.html', 'The Monphi Engine - Example News When Less is More', 'Example News When Less is More', 'Sometimes its good to pipe your standard out to more. I find it better to pipe your standard out to less.<br />\r\nI really love how you can scroll with less. I told someone how much I loved it and got them to use it but they couldn''t figure out how to exit. So I walked over to their keyboard and pushed the Q button.<br />\r\nThat is why Less is More!', 0, 0, 0, '', 0, '', '".$strDate ."', '".$strDate ."')
";
		$refDBC->query($strQuery);
		// echo 'Successfully populated "page" table<br />';

		// populate modules
/*		$strQuery = "
INSERT INTO `module` (`id`, `boolAdd`, `boolAltMenu`, `boolDelete`, `boolEdit`, `boolLoadinall`, `boolOB`, `boolSupErrMsg`, `description`, `fileAdd`, `fileDelete`, `fileEdit`, `fileName`, `moduleName`, `pathAltMenu`) VALUES
(1, 0, 0, 0, 0, 0, 0, 1, 'The sitemap creates an XML based page for search engine optimization.', '', '', '', 'mod/sitemap/sitemap_xml.php', 'sitemap', ''),
(2, 0, 0, 0, 0, 0, 0, 0, 'The add to any module adds a social networking sharing button to the page.\r\nhttp://www.addtoany.com/\r\n', '', '', '', 'mod/addtoany/addtoany.php', 'addtoany', ''),
(3, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 'mod/contact/contact.php', 'contact', ''),
(4, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 'mod/socialsharing/socialsharing.php', 'socialsharing', '');
";*/
		$strQuery = "
INSERT INTO `modules` (`id`, `name`, `description`, `path`, `content_file`, `content_tpl_file`, `content_db`, `view_file`, `boolEnable`) VALUES
(1, 'Site Map', 'The sitemap creates an XML based page for search engine optimization.', 'mod/sitemap/', 'sitemap_content.php', 'sitemap_content.tpl.html', 'mod_sitemap', '', 1),
(2, 'News', 'News Module creates additional content view. A news block for displaying the primary news. A news archive block for displaying all archived news.', 'mod/news/', 'news_content.php', 'news_content.tpl.html', 'mod_news', '', 1);
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "modules" table<br />';

/*		// populate modulelink
		$strQuery = "
INSERT INTO `modulelink` (`id`, `tid`, `mid`, `boolEnable`) VALUES
(1, 3, 1, 1);
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "modulelink" table<br />';
*/
		// populate module_blocks
		$strQuery = "
INSERT INTO `module_blocks` (`id`, `mid`, `block`, `block_file`, `boolLoadinall`, `boolSupErrMsg`) VALUES
(1, 1, 'sitemap', 'sitemap.php', 0, 1),
(2, 2, 'news', 'news.php', 0, 0),
(3, 2, 'news-archive', 'news-archive.php', 0, 0);
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "module_blocks" table<br />';

		//populate module_block_links
		$strQuery = "
INSERT INTO `module_block_links` (`id`, `pid`, `mid`, `bid`, `boolEnable`) VALUES
(1, 3, 1, 1, 1),
(2, 4, 2, 2, 1),
(3, 5, 2, 3, 1);
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "module_block_links" table<br />';

		// add users table.
		$strQuery = "
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'table ID, primary key for db linkage',
  `user` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "users" table<br />';

		// create preferences table
		$strQuery = "
CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_default` varchar(50) NOT NULL DEFAULT './main.tpl.html',
  `auth_random_seed` varchar(50) NOT NULL,
  `auth_keep_alive` int(11) NOT NULL DEFAULT '600',
  `auth_bool_use_token` tinyint(1) NOT NULL,
  `auth_bool_use_HTTPS_cookies` tinyint(1) NOT NULL,
  `auth_bool_check_IP_address` tinyint(1) NOT NULL,
  `auth_bool_check_user_agent` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "preferences" table<br />';


		// setup preinstalled modules
		// table mod_news
		$strQuery = "
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "mod_news" table<br />';

		// populate mod_news
		$strQuery = "
INSERT INTO `mod_news` (`id`, `pid`, `boolDisplayNews`, `boolArchiveNews`, `boolNewsPagetitle`, `boolNewsContent`, `boolNewsMore`, `contentNews`) VALUES
(1, 1, 0, 0, 0, 0, 0, ''),
(2, 2, 0, 0, 0, 0, 0, ''),
(3, 3, 0, 0, 0, 0, 0, ''),
(4, 4, 0, 0, 0, 0, 0, ''),
(5, 5, 0, 0, 0, 0, 0, ''),
(6, 6, 1, 1, 1, 1, 1, 'The Monphi Engine has been setup on one more machine.'),
(7, 7, 1, 1, 1, 1, 1, 'Ever wonder what cool was?'),
(8, 8, 1, 1, 1, 1, 1, 'Ever wonder if Less is More?');
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "mod_news" table<br />';

		// table mod_sitemap
		$strQuery = "
CREATE TABLE IF NOT EXISTS `mod_sitemap` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL COMMENT 'page id',
  `changefreq` varchar(7) NOT NULL DEFAULT 'monthly' COMMENT 'for xml sitemap',
  `priority` varchar(3) NOT NULL DEFAULT '0.5' COMMENT 'sitemap priority',
  `boolSitemapHide` tinyint(1) NOT NULL COMMENT 'Hide from sitemap',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;
";
		$refDBC->query($strQuery);
		//echo 'Successfully created "mod_sitemap" table<br />';

		// populate mod_sitemap
		$strQuery = "
INSERT INTO `mod_sitemap` (`id`, `pid`, `changefreq`, `priority`, `boolSitemapHide`) VALUES
(1, 1, 'never', '0.5', 0),
(2, 2, 'never', '0.5', 1),
(3, 3, 'never', '0.5', 1),
(4, 4, 'never', '0.5', 0),
(5, 5, 'never', '0.5', 0),
(6, 6, 'never', '0.5', 0),
(7, 7, 'never', '0.5', 0),
(8, 8, 'never', '0.5', 0);
";
		$refDBC->query($strQuery);
		//echo 'Successfully populated "mod_sitemap" table<br />';

		$refDBC->sql_close();
		########################################################################
		# completion redirect to admin account                                 #
		########################################################################
		// after writing display happy message
		//echo "Redirect Here";
		/*
		if(!$fileHandle = fopen("dbsetup_complete.tpl.html", "r"))
		{
			echo 'cannot read file "dbsetup_complete.tpl.html"<br />';
			exit;
		}
		else
		{
			// store file in strTemplate
			$strComplete = fread($fileHandle, filesize("dbsetup_complete.tpl.html"));
			fclose($fileHandle);
			echo $strComplete;
		}
		*/
		//header location.

		if (!empty($_SERVER['HTTPS']))
			$strServer = "https://";
		else
			$strServer = "http://";
		$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
		$intLength = ++$intEnd;
		$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
		#$strRedirect = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'adminsetup.php';
		$strRedirect = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'preferences.php';
		header('Location: '.$strRedirect);
	}
}
else
{
	echo $refForm->strTemplateOrig;
}
#echo "<pre>"; print_r($_REQUEST); echo "</pre>";
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
