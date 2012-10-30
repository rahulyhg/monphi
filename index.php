<?php
################################################################################
# File Name : index.php                                                        #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2012102400                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010, 2011 2012 Philip Allen       #
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
#   This file connects to the template systems database pulls the              #
#   data from it and processes the website as needed.                          #
#                                                                              #
# Functions :                                                                  #
#   Currently N/A                                                              #
#                                                                              #
# Classes :                                                                    #
#   Currently N/A                                                              #
#                                                                              #
# CSS :                                                                        #
#                                                                              #
# JavaScript :                                                                 #
#                                                                              #
# Variable Lexicon :                                                           #
#   String             - $strStringName                                        #
#   Array              - $arrArrayName                                         #
#   Resource           - $resResourceName                                      #
#   Reference Variable - $refReferenceVariableName  (aka object)               #
#   Integer            - $intIntegerName                                       #
#   Boolean            - $boolBooleanName                                      #
#   Function           - function_name (all lowercase _ as space)              #
#   Class              - class_name (all lowercase _ as space)                 #
#                                                                              #
# Commenting Style :                                                           #
#   # (in boxes) denotes commenting for large blocks of code, function         #
#       and classes                                                            #
#   # (single at beginning of line) denotes debugging infromation              #
#       like printing out array data to see if data has properly been          #
#       entered                                                                #
#   # (single indented) denotes commented code that may later serve            #
#       some type of purpose                                                   #
#   // used for simple notes inside of code for easy follow capability         #
#   /* */ is only used to comment out mass lines of code, if we follow         #
#       the above way of code we will be able to comment out entire            #
#       files for major debugging                                              #
#                                                                              #
################################################################################
################################################################################
# Path Variable                                                                #
################################################################################
$strPath = './';
################################################################################
# benchmark                                                                    #
################################################################################
if (file_exists($strPath."inc/benchmark.inc.php")) { include_once ($strPath."inc/benchmark.inc.php"); } else { echo 'failed to open benchmark.inc.php'; exit;}
$intStartTime = benchmark_time();
################################################################################
# Session information                                                          #
################################################################################
if (!ini_get("cookie_httponly"))
	ini_set("session.cookie_httponly", true);
session_start();
################################################################################
# Includes                                                                     #
################################################################################
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
if (file_exists($strPath."conf/monphi.conf.php")) { include_once ($strPath."conf/monphi.conf.php"); } else { echo 'failed to open monphi.conf.php'; exit;}
#if (file_exists($strPath."conf/template.conf.php")) { include_once ($strPath."conf/template.conf.php"); } else { echo 'failed to open template.conf.php'; exit;}
// form
#if (file_exists($strPath."inc/form_data.class.inc.php")) { include_once ($strPath."inc/form_data.class.inc.php"); } else { echo 'failed to open form_data.class.inc.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// security
if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
// bb codes
if (file_exists($strPath."inc/bb2html.func.inc.php")) { include_once ($strPath."inc/bb2html.func.inc.php"); } else { echo 'failed to open bb2html.func.inc.php'; exit;}
// other
if (file_exists($strPath."inc/convert_smart_quotes.func.inc.php")) { include_once ($strPath."inc/convert_smart_quotes.func.inc.php"); } else { echo 'failed to open convert_smart_quotes.func.inc.php'; exit;}
if (file_exists($strPath."inc/get_include_contents.func.inc.php")) { include_once ($strPath."inc/get_include_contents.func.inc.php"); } else { echo 'failed to open get_include_contents.func.inc.php'; exit;}
################################################################################
# Setup Check                                                                  #
#   Checks to see if dbc conf has database settings if it doesn't we assume    #
#   setup has not been ran                                                     #
################################################################################
if ($arrDBC['user'] == 'dbuserhere' && $arrDBC['pass'] == 'dbpasshere' && $arrDBC['db'] == 'dbnamehere')
{
	if (file_exists("./setup/setup.blurb.php")) { include_once ("./setup/setup.blurb.php"); } else { echo 'failed to open setup.blurb.php'; exit;}
	// setup has not been run
	$refTPL = new tpl();
	$arrContent['title'] = 'Monphi CMS';
	$arrContent['htmltitle'] = 'Monphi CMS';
	$arrContent['content'] = $strSetupBlurb;
	$arrContent['includeData'] = '';
	#$arrContent['modData'] = '';
	$arrContent['comments'] = '';
	$arrContent['error'] = '';
	$arrContent['admin'] = '';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
	exit;
}
################################################################################
# Reference Variables                                                          #
################################################################################
$refTPL = new tpl();
$refDBC = new dbc($arrDBC);
$refDBC->sql_connect();
################################################################################
# Variables                                                                    #
################################################################################
#$boolModRewrite = false;
$boolComments = false;
$boolInclude = false;
$boolBBCode = false;
$boolSupErrMsg = false;
$arrContent['error'] = "";
################################################################################
# Process section and page input                                               #
################################################################################
#echo "strURL :".$_SERVER['REQUEST_URI']."<br />\n";
// explode the input to allow get variables. Explode set to 2 to only split on ?
$arrExplode = explode('?', $_SERVER['REQUEST_URI'],2);
#echo "<br /><br />\n\n<pre>"; print_r($arrExplode); echo "</pre>\n\n";
// replace none exploded input
#$strURL = preg_replace('/[^A-Za-z0-9-_.\/]/', '', $_SERVER['REQUEST_URI']); // we will only allow alpha-numeric underscore, dash, and forward slash in URL inputs.
// with exploded input!
// 20121025 added ~ common for home directories.
$strURL = preg_replace('/[^A-Za-z0-9-_.\/~]/', '', $arrExplode['0']);
#$strURL = preg_replace('/[^A-Za-z0-9-_.\/]/', '', $arrExplode['0']);
$strURL = mysql_real_escape_string($strURL); // push left over input through escapes
$strURL = substr($strURL, 1); //we want to strip the first slash (/)
#$strCurrentLocation = $_SERVER['PHP_SELF']; // we need to find out where we are.
$strMonphiRoot = str_replace("index.php", "", $_SERVER['PHP_SELF']); // strip index.php from php self. and remove left overs from php self will return 
$strCurrentLocation = substr($strMonphiRoot, 1);
#echo $strCurrentLocation."<br />\n";
if ($strCurrentLocation != "")
	$strURL = str_replace($strCurrentLocation, "", $strURL);
#echo "strURL: ".$strURL."<br /><br />\n\n";
// Modify PHP_SELF to reflect monphi URL.
#echo "PHP_SELF: ".$_SERVER['PHP_SELF']."<br /><br />";
$strTempSelf = str_replace("index.php", $strURL, $_SERVER['PHP_SELF']);
#echo "strTempSelf: ".$strTempSelf."<br /><br />";
$_SERVER['PHP_SELF'] = $strTempSelf;
#echo "PHP_SELF: ".$_SERVER['PHP_SELF']."<br /><br />";
################################################################################
# Collect Page Contents                                                        #
################################################################################
$refDBC->sql_connect();
$boolPageAvail = false;
if ($strURL == "") // No page input check for index
{
	$strQuery = '
SELECT
	*
FROM
	page
WHERE
	boolIndex = "1"
';
}
else // have page input check for it
{
	$strQuery = '
SELECT
	*
FROM
	page
WHERE
	url = "'.$strURL.'"
';
}
#echo '<!--'.$strQuery.'-->';
// Check to see if our queries found data, if not we'll create an error page
$resResult = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResult);
if ($intNumRows == 0) // no index or no data found, check for default error page.
{
	$strQuery = '
SELECT
	*
FROM
	page
WHERE
	boolError = "1"
';
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0) { // found an error page in database
		$boolPageAvail = true;
		header("HTTP/1.0 404 Not Found");
	}
	else // no error page found create generic
		$boolPageAvail = false;
}
else // found a record we have a page!
	$boolPageAvail = true;
if ($boolPageAvail) // process data
{
	// if some data exists we will populate the template variables.
	// $arrContent will match template variables
	// $bool will be flags used later
	$arrFetch = mysql_fetch_assoc($resResult);
	$arrContent['title'] = $arrFetch['pagetitle'];
	$arrContent['htmltitle'] = $arrFetch['htmltitle'];
	$arrContent['content'] = $arrFetch['content'];
	$intID = $arrFetch['id'];
//	$boolComments = $arrFetch['boolComments'];
	$boolInclude = $arrFetch['boolInclude'];
	$includePath = $arrFetch['includePath'];
	$boolBBCode = $arrFetch['boolBBCode'];
	$strTemplateFile = $arrFetch['template'];
	$strId = $arrFetch['id'];
}
else // no pages and no error generate generic error 404 page
{
	header("HTTP/1.0 404 Not Found");
	$arrContent['title'] = 'Error 404 - Page not found';
	$arrContent['htmltitle'] = 'Error 404 - Page not found';
	$strId = '';
	$arrContent['content'] = 'The page you requested cannot be found, it may have been moved or renamed.';
}

################################################################################
# Check Login stuff                                                            #
################################################################################
#echo '<pre style="font-size:2em;">'; print_r($_SESSION); echo "</pre>";
$arrContent['admin'] = ''; // set admin content to none its (I keep removing this because of the one in setup, so stop removing this!)
#echo '<br /><br /><pre>'; print_r($_COOKIE); print_r($_SESSION);echo '</pre>';
#echo '<br /><br />cookie:'.$_COOKIE['monphi_auth_token'].'<br />session:'.$_SESSION['monphi_auth_token'];
if (array_key_exists("monphi_auth", $_SESSION))
{
	// auth variables
	#if (file_exists($strPath."conf/auth.conf.php")) { include_once ($strPath."conf/auth.conf.php"); } else { echo 'failed to open auth.conf.php'; exit;}
	#$intInactiveTime = time() - $_SESSION['monphi_auth_keepalive']; // generate inactive timer
	// do our checks should match admin/auth.php but reversed
	#if ($_SESSION['monphi_auth'] && $_SESSION['monphi_auth_ip_addr'] == $_SERVER['REMOTE_ADDR'] && $_SESSION['monphi_auth_random_seed'] == $strRandomSeed && $_SESSION['monphi_auth_user_agent'] == md5($_SERVER['HTTP_USER_AGENT'].$strRandomSeed) && $intInactiveTime <= $intKeepAlive)
	$boolDisplayAdminBar = true;

	//------------------------------------------------------------------------//
	// get preferences from db                                                //
	//------------------------------------------------------------------------//
		$strQuery = '
SELECT
	auth_random_seed, auth_keep_alive, auth_bool_use_token, auth_bool_use_HTTPS_cookies, auth_bool_check_IP_address, auth_bool_check_user_agent
FROM
	preferences
';
		$resResult = $refDBC->query($strQuery);
		$intNumRows = mysql_num_rows($resResult);
		if ($intNumRows != 0)
		{
			$arrFetch = mysql_fetch_assoc($resResult);
			// set all of the things that would be in monphi.conf here.
			$strRandomSeed = $arrFetch['auth_random_seed'];
			$intKeepAlive = $arrFetch['auth_keep_alive'];
			$boolUseToken = $arrFetch['auth_bool_use_token'];
			$boolUseHTTPSCookies = $arrFetch['auth_bool_use_HTTPS_cookies'];
			$boolCheckIPAddress = $arrFetch['auth_bool_check_IP_address'];
			$boolCheckUserAgent = $arrFetch['auth_bool_check_user_agent'];
		}
		else
		{
			echo 'unable to retreive preferences from database';
			exit;
		}
		mysql_free_result($resResult);
	//------------------------------------------------------------------------//
	// session check                                                          //
	//------------------------------------------------------------------------//
	if (!$_SESSION['monphi_auth'])
		$boolDisplayAdminBar = false;
	if ($boolCheckIPAddress) // monphi.conf.php setting
	{
		if ($_SESSION['monphi_auth_ip_addr'] != $_SERVER['REMOTE_ADDR'])
			$boolDisplayAdminBar = false;
	}
	if ($boolCheckUserAgent) // monphi.conf.php setting
	{
		if ($_SESSION['monphi_auth_user_agent'] != md5($_SERVER['HTTP_USER_AGENT'].$strRandomSeed))
			$boolDisplayAdminBar = false;
	}
	if ($boolUseToken) // monphi.conf.php setting
	{
		if ($_COOKIE['monphi_auth_token'] != $_SESSION['monphi_auth_token'])
			$boolDisplayAdminBar = false;
	}
	$intInactiveTime = time() - $_SESSION['monphi_auth_keepalive'];
	if ($intInactiveTime >= $intKeepAlive)
		$boolDisplayAdminBar = false;

	if ($boolDisplayAdminBar)
	{
// && $_COOKIE['monphi_auth_token'] == $_SESSION['monphi_auth_token']
		if ($strId == '') // no page found display strike through for edit this page
			$strEditPage = '<a href="" class="editthispage" style="text-decoration: line-through;">Edit This Page</a>';
		else // determine page ID for editing
			$strEditPage = '<a href="'.$strMonphiRoot.'admin/content_edit.php?id='.$strId.'" class="editthispage">Edit This Page</a>';
		$arrContent['admin'] = '
<div id="monphi_menu_position">
	<div style="float:left; height:22px; border-right:1px #ccc solid;">
		<a href="http://monphi.sourceforge.net" target="_blank"><img src="./img/monphi/logo.png" style="float:left; padding-right:3px;" /></a>
	</div>
	<div id="monphi_menu">
		'.$strEditPage.'
		<a href="'.$strMonphiRoot.'">Home</a>
		<a href="'.$strMonphiRoot.'admin/">Admin</a>
		<a href="'.$strMonphiRoot.'admin/content.php">Content</a>
		<a href="'.$strMonphiRoot.'admin/modules.php">Modules</a>
		<!--<a href="'.$strMonphiRoot.'admin/preferences.php">Preferences</a>-->
		<a href="'.$strMonphiRoot.'admin/user.php">Users</a>
		<a href="'.$strMonphiRoot.'admin/logout.php">Logout</a>
	</div>
</div>
';
		// reset keepalive for session on every page hit
		$_SESSION['monphi_auth_keepalive'] = time();
		// reset token
		$strToken = md5(uniqid(rand(), true));
		$_SESSION['monphi_auth_token'] = $strToken;
		if ($boolUseHTTPSCookies) // monphi.conf.php setting
			setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', true, true);
		else
			setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', false, true);
	}
}
################################################################################
# Modular Process section                                                      #
################################################################################
// two ways of loading modules
// include via the database uses php Output Buffer.
// need to have a javascript css load? module folder css and js folders
// takes module name
// cache the module info somewhere.
// to comply with the *all module check we'll need to do a query for that too.

// strId is set above in the return variables from the database. We set this
// to get the page ID which is linked in the modulelink page.


// ok think this needs to be redone.
// Currently all of the modules are loaded and displayed one after the other..
// this is not a good idea.. I'm thinking we'll do a parse on the
// $arrContent['content'] to see if we can spread the module data around.
// should we also allow something to be pushed into the templates?
// we need to keep this simple and not very confusing. Perhaps by default
// we will assume everything is done inside the [content] and set a checkbox
// to allow otherwise at a later time. Yeah lets just get this done internal.

// so the processing will check for [] variables inside of [content] if they do
// not exist we need an error message to display. We will look for the module
// name e.g. [contact], [socialsharing], etc.

// we should shorten this code with the following table join.
// ok here we check for the module and the module file name, and check the 
// page id against the module link table id's. the boolenable inside module
// link must be checked for the query to return any values.

//above this line are old notes
// we need to do something to fix the navigation menu something that shows up on
// all pages.
// the error message and possibly all other variables should be reset in the template.

# If the module is not specified to load in all process here                   #
/*
$strQuery = '
SELECT
	module.moduleName, module.fileName, module.boolSupErrMsg
FROM modulelink
LEFT JOIN module
	ON modulelink.mid = module.id
WHERE modulelink.boolEnable = "1" &&
	modulelink.tid = "'.mysql_real_escape_string($strId).'" &&
	module.boolLoadinall = "0"
';
// this should return like
// moduleName	fileName					boolSupErrMsg
// sitemap		mod/sitemap/sitemap.php		1
*/
/*
// messed up pid and mid for blocks.
$strQuery = '
SELECT
	modules.name, module_blocks.block, modules.path, module_blocks.block_file, module_blocks.boolSupErrMsg
FROM module_block_links
LEFT JOIN module_blocks
	ON module_block_links.mid = module_blocks.id
LEFT JOIN modules
	on module_blocks.mid = modules.id
WHERE module_block_links.boolEnable = "1" &&
	module_block_links.pid = "'.mysql_real_escape_string($strId).'" &&
	module_blocks.boolLoadinall = "0"
';
*/
$strQuery = '
SELECT
	modules.name, module_blocks.block, modules.path, module_blocks.block_file, module_blocks.boolSupErrMsg
FROM module_block_links
LEFT JOIN module_blocks
	ON module_block_links.bid = module_blocks.id
LEFT JOIN modules
	on module_blocks.mid = modules.id
WHERE module_block_links.boolEnable = "1" &&
	module_block_links.pid = "'.mysql_real_escape_string($strId).'" &&
	module_blocks.boolLoadinall = "0" &&
	modules.boolEnable = "1"
';

// this should return like 
//name		block		path			block_file		boolSupErrMsg
//Site Map	sitemap		mod/sitemap		sitemap.php		1
#echo $strQuery;
// strange errors if module is calling another $refDBC, only one module may load.
$resResultModuleLoad = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResultModuleLoad);
if ($intNumRows != 0)
{
	$refModTpl = new tpl(); // with the reset variables we shouldn't need to make a new tpl.
	$strModError = "";
	$i = 1;
	// while loop will fail if module is using resResultModule or closes the sql database!
	$boolModLoadError = false;
	while ($arrFetch = mysql_fetch_assoc($resResultModuleLoad))
	{
		#print_r($arrFetch);
		#echo 'blockname : "'.$arrFetch['block'].'" ';
		// check for error message suppression do not match only set to false if found
		if ($arrFetch['boolSupErrMsg'] == 1)
			$boolSupErrMsg = true;
		// find the modules filename
		//if (is_file($arrFetch['fileName']))
		if (is_file($arrFetch['path'].$arrFetch['block_file']))
		{
			#echo 'found the file '.$arrFetch['path'].$arrFetch['block_file'];
			// collect module data in Output Buffer (OB)
			ob_start();
			#include $arrFetch['fileName'];
			include $arrFetch['path'].$arrFetch['block_file'];
			$strModData = ob_get_contents();
			ob_end_clean();
			#echo '<pre>'.$strModData.'</pre>';
			// process modifications to the primary "content"
			#$arrModData[$arrFetch['moduleName']] = $strModData;
			#echo 'blockname : "'.$arrFetch['block'].'" '; // block will be empty if $arrFetch is defined in module.
			if ($arrFetch['block'] == '')
			{
				// this findds the duplicate arrfetch bug. Should have arrfetch data pulled somewhere else so we don't have this problem.....
				$arrContent['error'] .= 'Probable logic error. Block module using same variable as engine. Try redifining the $arrFetch variable in your module.';
			}
			$arrModData[$arrFetch['block']] = $strModData;
			#print_r($arrModData); // news isn't getting here.. why? was $arrFetch defined again in module.
			$refModTpl->go($arrContent['content'], $arrModData, false); // now we're not getting news to throw an error here.
			#echo "<!------ mod ".$arrFetch['block']."------->\n\n\n<pre>".$arrContent['content']."</pre><!------ mod ".$arrFetch['block']."------->\n\n\n<pre>";
			#echo '<pre>'.$refModTpl->strError.'</pre>';
			// store errors in primary template arrContent error container
			if ($refModTpl->strError != "")
			{
				$arrContent['error'] .= $refModTpl->strError. 'E.G. In the pages "Main Content"<br />'."\n";
			}
			$arrContent['content'] = $refModTpl->get_content();
		}
		// if the file doesn't exist we need an error
		else
		{
			$boolModLoadError = true;
			#$strModError .= 'Failed to load module: "'.$arrFetch['moduleName'].'" unable to locate the file.'."<br />\n";
			$strModError .= 'Failed to load module: "'.$arrFetch['name'].'" unable to locate the file.'."<br />\n";
		}
		$refModTpl->reset_variables();
		$arrModData = array();
	}
	$arrContent['error'] .= $strModError;
}
mysql_free_result($resResultModuleLoad);

// secondary module loading for "Load in All Pages" sweet! I love it.
// we may want another module loading and a check box for load in main template
// instead of the local content template.
// this would be handled with an if statement but the join query doesn't
// find the boolLoadinall if no links are created. This is the work around
// more processing on the server.
// no need to do a no load as it will be disabled if its not checked to load in all
// if the all module name is entered into the content it will still display
// as the preg_replace finds all matches and runs a replace.
# if module is to be loaded in all                                             #
/*
$strQuery = '
SELECT
	moduleName, fileName, boolSupErrMsg
FROM module
WHERE boolLoadinall = "1"
';
// will return like
// moduleName		fileName								boolSupErrMsg
// socialsharing	mod/socialsharing/socialsharing.php		0
*/
$strQuery = '
SELECT
	modules.name, module_blocks.block, modules.path, module_blocks.block_file, module_blocks.boolSupErrMsg
FROM module_blocks
LEFT JOIN modules
	on module_blocks.mid = modules.id
WHERE module_blocks.boolLoadinall = "1" &&
	modules.boolEnable = "1"

';
// will return eg.
// name				block			path				block_file			boolSupErrMsg
// Social Sharing	socialsharing	mod/socialsharing/	socialsharing.php	0
#echo $strQuery;
// create the field boolEnable for the modulename table
//boolEnable = "1" &&
$resResultModuleLoad = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResultModuleLoad);
if ($intNumRows != 0)
{
	// with the reset variables we shouldn't need to make a new tpl.
	//$refModTpl = new tpl();
	$strModError = "";
	$i = 1;
	// while loop will fail if module is using resResultModule or closes the sql database!
	$boolModLoadError = false;
	while ($arrFetch = mysql_fetch_assoc($resResultModuleLoad))
	{
		// check for error message suppression do not match only set to false if found
		if ($arrFetch['boolSupErrMsg'] == 1)
			$boolSupErrMsg = true;
		// find the modules filename
		#if (is_file($arrFetch['fileName']))
		if (is_file($arrFetch['path'].$arrFetch['block_file']))
		{
			// collect module data in Output Buffer (OB)
			ob_start();
			#include $arrFetch['fileName'];
			include $arrFetch['path'].$arrFetch['block_file'];
			$strModData = ob_get_contents();
			ob_end_clean();
			// process modifications to the primary "content"
			#$arrContent[$arrFetch['moduleName']] = $strModData;
			$arrContent[$arrFetch['block']] = $strModData;
			//$refModTpl->go($arrContent['content'], $arrModData, false);
			// store errors in primary template arrContent error container
			//if ($refModTpl->strError != "")
			//	$arrContent['error'] .= $refModTpl->strError. 'E.G. In the pages "Main Content"<br />'."\n";
			//$arrContent['content'] = $refModTpl->get_content();
		}
		// if the file doesn't exist we need an error
		else
		{
			$boolModLoadError = true;
			#$strModError .= 'Failed to load module: "'.$arrFetch['moduleName'].'" unable to locate the file.'."<br />\n";
			$strModError .= 'Failed to load module: "'.$arrFetch['name'].'" unable to locate the file.'."<br />\n";
		}
		//$refModTpl->reset_variables();
		$arrModData = array();
	}
	$arrContent['error'] .= $strModError;
}
mysql_free_result($resResultModuleLoad);

################################################################################
# parse for bbcodes if boolean is set                                          #
################################################################################
if ($boolBBCode)
	$arrContent['content'] = bb2html($arrContent['content']);
################################################################################
# Basic PHP block include modules                                              #
################################################################################
if ($boolInclude)
{
	if (is_file($includePath)) // found file lets process it.
	{
		ob_start();
		include $includePath;
		$arrContent['includeData'] = ob_get_contents();
		ob_end_clean();
	}
	else
	{
		// unable to find the page.
		$arrContent['includeData'] = $arrwraperror[0];
		$arrContent['includeData'] .= '
Error with "Basic PHP Block Include Module"<br />
The most likely cause is the checkbox for "Use Include" is set but the "Path for Include" is empty or invalid.
Please validate the "Path for Include" file or uncheck the "Use Include" option.
';
		$arrContent['includeData'] .= $arrwraperror[1];
	}
}
else
	$arrContent['includeData'] = '';
################################################################################
// what to do if the module closes the database.
// maybe modules should create a new reference variable like $refModname...
$refDBC->sql_close();
################################################################################
# Process Template Data                                                        #
################################################################################
$refTPL->set_file($strTemplateFile, $arrContent);
$refTPL->error_check();
$refTPL->arrContent['error'] .= $refTPL->strError; // work around to resend errors back into the arrContent of [error]
// notes say anomoly with arrContent and modules not reporting errors properly. was this fixed?
if ($refTPL->arrContent['error'] != "") // wrap error message if it exists
	$refTPL->arrContent['error'] = $arrwraperror[0].$refTPL->arrContent['error'].$arrwraperror[1];
if ($boolSupErrMsg) // if we're hiding error messages do it here, not really useful...
	$refTPL->arrContent['error'] = "";
//parse template
$refTPL->parse_str($refTPL->strTemplate, $refTPL->arrContent);
// incase [error] block doesn't exist display an error
if (!preg_match("[error]", $refTPL->strTemplate) && $boolSupErrMsg != true)
	echo $arrwraperror[0].'Template Warning "[error]" does not exist in '.$strTemplateFile.$arrwraperror[1];
//display template!
echo $refTPL->get_content();
################################################################################
# benchmark end time and calculations                                          #
################################################################################
$intEndTime = benchmark_time();
echo "\n".'<!-- Page Process Time : '.benchmark_calculate($intStartTime, $intEndTime).' seconds -->'."\n";
# helpful debuggin code
/*
echo "\n".'
self :'.$_SERVER['PHP_SELF']."<br />\n".'
argv :'.print_r($_SERVER['argv'])."<br />\n".'
argc :'.$_SERVER['argc']."<br />\n".'
REQUEST_URI :'.$_SERVER['REQUEST_URI']."<br />\n".'
'."\n";
*/
#echo "\n".$intID."<br />\n";
#echo "\n ".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."\n";
?>
