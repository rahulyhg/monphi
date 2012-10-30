<?php
################################################################################
# File Name : template_add.php                                                 #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011060300                                                         #
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
if (file_exists("auth.php")) { include_once ("auth.php"); } else { echo 'failed to open auth.php'; exit;}
$arrContent['admin'] = $strAdmin;
################################################################################
# Session information                                                          #
################################################################################
#session_start();
################################################################################
# Path Variable                                                                #
################################################################################
$strPath = '../';
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include_once ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
if (file_exists($strPath."inc/arrmysqlrealescapestring.inc.php")) { include_once ($strPath."inc/arrmysqlrealescapestring.inc.php"); } else { echo 'failed to open arrmysqlrealescapestring.inc.php'; exit;}
if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$arrFormConf = array(
	'formFile' => "user_add.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
	),
	'fieldText' => array(
		'user' => 'User :',
		'email' => 'Email Address :',
		'password' => 'Password :',
		'password2' => 'Retype password :',
	),
	'fieldError' => array(
		'user' => 'User',
		'email' => 'Email Address',
		'password' => 'Password',
		'password2' => 'Retype password',
	),
	'validation' => array(
		'user' => array(
			'required',
		),
		'email' => array(
			'required',
		),
		'password' => array(
			'required',
		),
		'password2' => array(
			'required',
		),
		'match' => array(
			'password',
			'password2',
		),
	),
	'errorWrapper' => array(
		'0' => "\n".'<span style="color:#c00;">',
		'1' => "</span>\n"
	),
	'errorMsgWrapper' => array(
		'0' => "<br /><br />\n".'<div style="background-color:#ffffd5; border:3px #c00 solid; color:#c00; padding:5px;">'."\n\t".'<div style="font-size:1.5em; font-weight:bold; padding-bottom:.5em;"><img src="../img/monphi/logo.png" style="vertical-align:middle;" /> Uh oh, there seems to be a problem!</div>',
		'1' => "</div>\n"
	),
	'errorMsg' => array(
		'referer' => 'Invalid Referer [referer]- You must use the same form provided by the host processing it',
		'required' => '<b>[field]</b> is a required field and cannot be blank',
		'match' => 'The password fields <b>[field-a]</b> and <b>[field-b]</b> do not match.',
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
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		#echo $refForm->strTemplate;
		// added this to take input on auth admin menu
		$refTPL = new tpl();
		$refTPL->go($refForm->strTemplate, $arrContent, false);
		echo $refTPL->get_content();
	}
	else
	{
		// moved connection above arrMysqlRealEscapeString
		// because I was getting an error message saying I didn't
		// have access to run it.
		$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		########################################################################
		# Process input from arrData                                           #
		########################################################################
				$strQuery = '
INSERT INTO `users`
	(`user`, `password`, `email`, `active`)
VALUES
	("'.mysql_real_escape_string($_REQUEST['user']).'", "'.mysql_real_escape_string(md5($_REQUEST['password'])).'","'.mysql_real_escape_string($_REQUEST['email']).'", 1)
';
		#echo "<br /><br /><br /><pre>".$strQuery."</pre><br />\n";
		$refDBC->query($strQuery);
		# Process input from arrData                                           #
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();
		$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
		$arrContent['content'] = '
	<b>User Added</b>
	<br /><br />
	<a href="'.$strReturn.'user.php">Return to User Management</a>
';
		$refTPL = new tpl();
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
	}
}
else
{
	#echo $refForm->strTemplateOrig;
	// added this to take input on auth admin menu
	$refTPL = new tpl();
	$refTPL->go($refForm->strTemplateOrig, $arrContent, false);
	echo $refTPL->get_content();
}
#echo "<pre>"; print_r($_REQUEST); echo "</pre>";
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
?>