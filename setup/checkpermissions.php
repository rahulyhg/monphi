<?php
################################################################################
# File Name : checkpermissions.php                                             #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011063000                                                         #
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
# Path Variable                                                                #
################################################################################
$strPath = '../';
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include_once ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// templates (for front page select)
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
//if (file_exists($strPath."conf/select_template.conf.php")) { include_once ($strPath."conf/select_template.conf.php"); } else { echo 'failed to open select_template.conf.php'; exit;}
// 
if (file_exists($strPath."inc/arrmysqlrealescapestring.inc.php")) { include_once ($strPath."inc/arrmysqlrealescapestring.inc.php"); } else { echo 'failed to open arrmysqlrealescapestring.inc.php'; exit;}
#if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$strTemplateFile = 'checkpermissions.tpl.html';
$arrFormConf = array(
	'formFile' => "checkpermissions.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
	),
	'fieldText' => array(
		'name' => 'Name :<br />',
		'email' => 'Email :<br />',
		'message' => 'Message :<br />',
		'captcha' => 'Enter text from security image :<br />',
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
		'required' => '<b>[field]</b> is a required field and cannot be blank.',
	)
);
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
################################################################################
#                                                                              #
################################################################################
	$refTPL = new tpl();
	$boolWrite = false;
	$arrContent['frmAction'] = $_SERVER['PHP_SELF'];
	$arrContent['content'] = "";
	$arrContent['continue'] = "";

	// check permissions
#	$intMonphiConf = substr(sprintf('%o', fileperms('../conf/monphi.conf.php')), -4);
	$intDbcConf = substr(sprintf('%o', fileperms('../conf/dbc.conf.php')), -4);
	$intRobots = substr(sprintf('%o', fileperms('../robots.txt')), -4);

	// check if writable, if so allow continue.
#	if (!is_writable("../conf/monphi.conf.php"))
#		$arrContent['content'] .= 'Test read only permissions on <span style="color:#4aa02c">conf/monphi.conf.php</span> - returned '.$intMonphiConf.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
#	else
#	{
#		$arrContent['content'] .= 'Test read only permissions on <span style="color:#c00">conf/monphi.conf.php</span> - returned '.$intMonphiConf.' - <span style="color:#c00">Fail</span>'."<br />\n";
#		$boolWrite = true;
#	}

	if (!is_writable("../conf/dbc.conf.php"))
		$arrContent['content'] .= 'Test read only permissions on <span style="color:#4aa02c">conf/dbc.conf.php</span> - returned '.$intDbcConf.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
	else
	{
		$arrContent['content'] .= 'Test read only permissions on <span style="color:#c00">conf/dbc.conf.php</span> - returned '.$intDbcConf.' - <span style="color:#c00">Fail</span>'."<br />\n";
		$boolWrite = true;
	}
	if (!is_writable("../robots.txt"))
		$arrContent['content'] .= 'Test read only permissions on <span style="color:#4aa02c">robots.txt</span> - returned '.$intRobots.' - <span style="color:#4aa02c">Success</span>'."<br />\n";
	else
	{
		$arrContent['content'] .= 'Test read only permissions on <span style="color:#c00">robots.txt</span> - returned '.$intRobots.' - <span style="color:#c00">Fail</span>'."<br />\n";
		$boolWrite = true;
	}

	// change recheck button to submit button.
	if ($boolWrite)
		$arrContent['continue'] = '<a class="button_recheck_permissions" href="checkpermissions.php" alt=""></a>';
	else
		$arrContent['continue'] = '<a class="button_continue" href="finish.html" alt=""></a>';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
?>
