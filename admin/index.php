<?php
################################################################################
# File Name : index.php                                                        #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011102200                                                         #
#                                                                              #
# Copyright :                                                                  #
#   Copyright 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012 Philip Allen      #
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
# Path Variable                                                                #
################################################################################
$strPath = '../';
################################################################################
# Includes                                                                     #
################################################################################
if (file_exists("auth.php")) { include_once ("auth.php"); } else { echo 'failed to open auth.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
################################################################################
# Connect to Database                                                          #
################################################################################
//connect to mysql so we can get version for display
$refDBC = new dbc($arrDBCAdmin);
$refDBC->sql_connect();
$strMonphiVersion = 'alpha-20121030';
################################################################################
# Gather Content                                                               #
################################################################################
// check permissions
	$intMonphiConf = substr(sprintf('%o', fileperms('../conf/monphi.conf.php')), -4);
	$intDbcConf = substr(sprintf('%o', fileperms('../conf/dbc.conf.php')), -4);
	$intRobots = substr(sprintf('%o', fileperms('../robots.txt')), -4);
	// display reports with permissions
	// should do a check on everything
	$arrContent = array();
	$arrContent['content'] = '';
	if (!is_writable("../conf/monphi.conf.php"))
		$arrContent['content'] .= '<span style="color:#4aa02c">Good</span> - conf/monphi.conf.php - fileperms returned '.$intMonphiConf."<br />\n";
	else
		$arrContent['content'] .= '<span style="color:#c00; font-weight:bold;">Fail</span> - conf/monphi.conf.php should not be writable - fileperms returned '.$intMonphiConf."<br />\n";
	if (!is_writable("../conf/dbc.conf.php"))
		$arrContent['content'] .= '<span style="color:#4aa02c">Good</span> - conf/dbc.conf.php - fileperms returned '.$intDbcConf."<br />\n";
	else
		$arrContent['content'] .= '<span style="color:#c00; font-weight:bold;">Fail</span> - conf/dbc.conf.php should not be writable - fileperms returned '.$intDbcConf."<br />\n";
	if (!is_writable("../robots.txt"))
		$arrContent['content'] .= '<span style="color:#4aa02c">Good</span> - robots.txt - fileperms returned '.$intRobots."<br />\n";
	else
		$arrContent['content'] .= '<span style="color:#c00; font-weight:bold;">Fail</span> - robots.txt should not be writable - fileperms returned '.$intRobots."<br />\n";

// check for mod rewrite
#echo "<br /><br /><pre>";
#print_r(apache_get_modules());
#echo "</pre>";
// blue host
/*
PHP Fatal error: Call to undefined function apache_get_modules() in
environmental variables
_SERVER["GATEWAY_INTERFACE"]
GATEWAY_INTERFACE 
_ENV["GATEWAY_INTERFACE"]
//getenv('HTTP_MOD_REWRITE')=='On' 
*/
if (function_exists('apache_get_modules')) {
	if (in_array("mod_rewrite", apache_get_modules()))
		$strModRewrite = '<span style="color:#4aa02c">Good</span> - Mod rewrite appears to be enabled. Checked via apache_get_modules.';
	else
		$strModRewrite = '<span style="color:#c00">Fail</span> - Mod rewrite is not enabled, URL aliases will not function! Checked via apache_get_modules.';
}
else {
	//echo 'apache_get_modules DOES NOT exists';
	if (getenv('HTTP_MOD_REWRITE') == 'On') {
		//echo 'found mod rewrite via get env.';
		$strModRewrite = '<span style="color:#4aa02c">Good</span> - Mod rewrite appears to be enabled. Checked via HTTP_MOD_REWRITE';
	}
	else {
		//http://php.net/manual/en/function.php-sapi-name.php
		$strSapiType = php_sapi_name();
		if (substr($strSapiType, 0, 3) == 'cgi') {
			$strModRewrite = '<span style="color:#b1ab00">Warning</span> - Found CGI API settings on server, this is not a definitive answer, assume it is running. If you are having issues with URL Aliases Mod Rewrite may not be enabled. Checked via php_sapi_name.';
		}
		else {
			$strModRewrite = '<span style="color:#b1ab00">Unknown</span> - Cannot determine if mod rewrite is enabled. Unable to view apache modules, get mod rewrite environmental variables, or determine CGI settings.';
		}
		/*
		// this check sucks, need to fix it up later.
		if (getenv('GATEWAY_INTERFACE') == 'On') {
			$strModRewrite = '<span style="color:#b1ab00">Warning</span> - Found CGI settings on server, but no other traces of mod rewrite. Our best guess is that it is enabled. If you have problems with URL Aliases Mod Rewrite may not be enabled.';
		}
		else {
			$strModRewrite = '<span style="color:#b1ab00">Unknown</span> - Cannot determin if mod rewrite is enabled. Unable to view apache modules, get mod rewrite environmental variables, or determine CGI settings.';

		}
*/
	}
}

// check for setup files.
#$strSetupFolder = '../setup';
#echo "<br /><br />\ncheck setup file: "; echo file_exists($strSetupFolder); echo "<br /><br />\n";
#echo "<br /><br />\ncheck setup file: "; echo file_exists('../setup'); echo "<br /><br />\n";

$arrSetupCoreFiles = array('adminsetup.php', 'checkpermissions.php', 'conf.php', 'dbsetup.php', 'index.php');
$arrSetupMiscFiles = array('adminsetup.tpl.html', 'checkpermissions.tpl.html', 'conf.tpl.html', 'dbc.conf.php', 'dbsetup.tpl.html', 'finish.html', 'index.tpl.html', 'monphi.conf.php', 'robots.txt', 'setup.info.txt', 'setup.blurb.php', 'style.css');

#echo '<br /><br />';
#echo '<pre>';
#print_r($arrSetupCoreFiles);
#print_r($arrSetupMiscFiles);
#echo '</pre>';

if (file_exists('../setup'))
{
	// should flag a warning that setup folder exists?
	$boolCoreFileExists = false;
	$boolMiscFileExists = false;
	foreach ($arrSetupCoreFiles as $strFile)
	{
		if (file_exists('../setup/'.$strFile))
			$boolCoreFileExists = true;
	}
	foreach ($arrSetupMiscFiles as $strFile)
	{
		if (file_exists('../setup/'.$strFile))
			$boolMiscFileExists = true;
	}
	if ($boolCoreFileExists)
		$strSetupCheck = '<span style="color:#c00;font-weight:bold;">ALERT</span> - Found at least one system file in the setup folder.<br />It is highly recommended that you remove the setup folder.';
	elseif ($boolMiscFileExists)
		$strSetupCheck = '<span style="color:#EEC900;font-weight:bold;">Warning</span> - Found at least one system file in the setup folder. This file <u>should not</u> pose a security risk, but it is strange that the file exists without the core PHP files.<br />It is strongly recommended that you remove the setup folder.';
	else
		$strSetupCheck = '<span style="color:#EEC900;font-weight:bold; padding:2px;">Warning</span> - Found a folder or file called setup. The setup folder should have been removed, it appears that you may have deleted the files but not the folder.';
}
else
	$strSetupCheck = '<span style="color:#4aa02c">Good</span> - No setup files located!';

#echo 'Setup Check: '.$strSetupCheck;
################################################################################
# HTML Display                                                                 #
################################################################################
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<title>
		Monphi CMS - Administration
	</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<style type="text/css" media="screen">
		@import url("../css/monphi-admin.css");
		@import url("../css/monphi.css");
	</style>
</head>

<body>
<?php echo $strAdmin; ?>
<!-- Form here -->
<div class="content">
	<div style="text-align:center;">
		<a href="index.php"><img src="../img/monphi/banner.jpg" alt="Monphi" title="monphi" style="border:1px #ccc solid; border-bottom: 2px solid #333; -webkit-border-radius: 5px; -moz-border-radius: 5px;" /></a><br />
	</div>
	<br /><br />
	<div style="text-align:center; font-weight:bold;">
		Administration
	</div>
	<br /><br />
	<div style="line-height:150%; color:#333;">
		<div class="listgroups">CORE</div>
		<div style="padding:15px; border:1px #ccc solid;">
			<b><a href="content.php">Content</a></b> - Add, edit and delete pages.
			<br /><br />
			<b><a href="modules.php">Modules</a></b> - Modules are "plug-in" programs or scripts that can create additional content blocks, dynamic content and additional views.
			<br /><br />
			<b><a href="user.php">Users</a></b> - Add, edit and delete users.
			<br /><br />
			<b><a href="preferences.php">Preferences</a></b> - Set Preferences for default template, timeouts, seeds, error wrappers, security options.
<!--
			<br /><br />
			<b><a href="roles.php">Roles</a></b> - User rolls e.g. Content Editor, Module Manager, Administrator. (coming sometime)
-->
<!--			<br /><br />
			<b><a href="preferences.php">Preferences</a></b> - Preferences to be added here. (Things like how many pages to display in content. Default 1000?, how many users to display. This will happen way later commenting for now.)-->


		</div>

		<br />
<!--
		<div class="listgroups">PRE-PACKAGED</div>
		<div style="padding:15px; border:1px #ccc solid;">
			<b><a href="../mod/modfolder/modadmin.php">Site Map</a></b> - The Site Map module is a block based module that creates an XML based page to assist with search engine optimization.
		</div>

		<br />
-->
<?php

	$strQuery = '
SELECT
	*
FROM
	modules
WHERE
	boolEnable = "1"
	&&
	view_file != ""
';

$resResultModuleView = $refDBC->query($strQuery);
$intNumRows = mysql_num_rows($resResultModuleView);
if ($intNumRows != 0)
{
echo '		<div class="listgroups">ADDITIONAL MODULAR VIEWS</div>
		<div style="padding:15px; border:1px #ccc solid;">';
	while ($arrFetch = mysql_fetch_assoc($resResultModuleView))
	{

echo '			<b><a href="../'.$arrFetch['path'].$arrFetch['view_file'].'">'.$arrFetch['name'].'</a></b> - '.$arrFetch['description'].'<br /><br />';
	} // end while
echo '		</div>';
}
/*
			<b><a href="../mod/modfolder/modadmin.php">Some Module View</a></b> - Module Views will dynamically be loaded here. (coming soon)<br /><br />
			Run a select against modules table for "view_file"... select * from modules where view_file != null.
*/
?>


		<br />


		<div class="listgroups">SYSTEM INFORMATION</div>

		<div style="padding:15px; border:1px #ccc solid;">
			<table id="list">
				<tr>
					<td style="width:150px;"><b>Monphi Version</b></td>
					<td><?php echo $strMonphiVersion; ?></td>
				</tr>
				<tr>
					<td class="list2"><b>Web Server</b></td>
					<td class="list2"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
				</tr>
				<tr>
					<td><b>MySQL Version</b></td>
					<td><?php echo mysql_get_server_info(); ?></td>
				</tr>
				<tr>
					<td class="list2"><b>PHP Version</b></td>
					<td class="list2"><?php echo phpversion(); ?></td>
				</tr>
				<tr>
					<td><b>URL Alias Check</b></td>
					<td><?php echo $strModRewrite; ?></td>
				</tr>
				<tr>
					<td class="list2"><b>File Permission Check</b></td>
					<td class="list2"><?php echo $arrContent['content']; ?></td>
				</tr>
				<tr>
					<td><b>Setup Files</b></td>
					<td><?php echo $strSetupCheck; ?></td>
				</tr>
			</table>

		</div>
	</div>
</div>
</body>
</html>
