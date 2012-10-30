<?php
################################################################################
# File Name : adminsetup.php                                                   #
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
# Session information                                                          #
################################################################################
################################################################################
# Includes                                                                     #
################################################################################
$strPath = '../';
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
// database
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$arrFormConf = array(
	'formFile' => "adminsetup.tpl.html",
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
		// this looks like the processing section.
		$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		// uid, user, password, email, last_login, active
		$strQuery = "
INSERT INTO `users` (`user`, `password`, `email`, `active`) VALUES
('".mysql_real_escape_string($_REQUEST['user'])."', '".mysql_real_escape_string(md5($_REQUEST['password']))."','".mysql_real_escape_string($_REQUEST['email'])."', 1)
";
		#echo $strQuery;
		$refDBC->query($strQuery);

		if (!empty($_SERVER['HTTPS']))
			$strServer = "https://";
		else
			$strServer = "http://";
		$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
		$intLength = ++$intEnd;
		$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
		$strRedirect = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'checkpermissions.php';
		header('Location: '.$strRedirect);
	}
}
else
{
	echo $refForm->strTemplateOrig;
}
#echo "<pre>"; print_r($_REQUEST); echo "</pre>";
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
