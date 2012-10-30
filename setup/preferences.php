<?php
################################################################################
# File Name : preferences.php                                                  #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011111300                                                         #
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
#if (file_exists("auth.php")) { include_once ("auth.php"); } else { echo 'failed to open auth.php'; exit;}
#$arrContent['admin'] = $strAdmin;
################################################################################
# Session information                                                          #
################################################################################
#session_start(); // session should be started with auth.php
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
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
$intLength = ++$intEnd;
$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
if($_SERVER['HTTPS']) // https is not defined.
	$strServer = "https://";
else
	$strServer = "http://";

$arrFormConf = array(
	'formFile' => "preferences.tpl.html",
	'boolFile' => true,
	'validReferer' => array(
		$strServer.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'],
	),
	'fieldText' => array(
		'name' => 'Name :<br />',
		'email' => 'Email :<br />',
		'message' => 'Message :<br />',
		'captcha' => 'Enter text from security image :<br />',
	),
/*
we shouldn't need to do any validation here
this means we won't have errors.
	'validation' => array(
		'name' => array(
			'required',
		),
		'email' => array(
			'required',
			'email',
		),
		'message' => array(
			'required',
		),
		'captcha' => array(
			'required',
			'captcha',
		),
	),
*/
	'errorWrapper' => array(
		'0' => "\n".'<span style="color:#c00;">',
		'1' => "</span>\n"
	),
	'errorMsgWrapper' => array(
		'0' => "\n".'<div style="text-align:center; font-size:1.5em; font-weight:bold; color:#c00;">Error</div><div style="color:#c00; border:1px #c00 solid; padding:5px;">',
		'1' => "</div>\n"
	),
	'errorMsg' => array(
		'referer' => 'Invalid Referer ['.$_SERVER['HTTP_REFERER'].']- You must use the same form provided by the host processing it',
		'required' => '<b>[field]</b> is a required field and cannot be blank.',
		'email' => 'The Email address could not be validated, please enter a valid email address.',
		'captcha' => 'Captcha text does not match the image, use the reset checkbox to reset the image.',
	)
);
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
$refDBC = new dbc($arrDBCAdmin);
$refTPL = new tpl();
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
# Final Edit to Database                                                       #
#   Process input and display submission complete page                         #
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
		#$_SESSION['captcha_valid'] = false;
		#$_SESSION['captcha_reset'] = true;
		#$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		if (get_magic_quotes_gpc())
			$_REQUEST = arrstripslashes($_REQUEST);
		#echo "REQUEST strip :<pre>"; print_r($_REQUEST); echo "</pre>";
		$_REQUEST = arrMysqlRealEscapeString($_REQUEST);

		$arrData = array ( 'auth_bool_use_token', 'auth_bool_use_HTTPS_cookies', 'auth_bool_check_IP_address', 'auth_bool_check_user_agent');
		foreach ($arrData as $strValue)
		{
			if(!array_key_exists($strValue, $_REQUEST))
			{
				$_REQUEST[$strValue] = 0;
			}
		}
$strQuery = '
INSERT INTO `preferences` (`id`, `template_default`, `auth_random_seed`, `auth_keep_alive`, `auth_bool_use_token`, `auth_bool_use_HTTPS_cookies`, `auth_bool_check_IP_address`, `auth_bool_check_user_agent`) VALUES
(1, "'.$_REQUEST['template_default'].'", "'.$_REQUEST['auth_random_seed'].'", "'.$_REQUEST['auth_keep_alive'].'", "'.$_REQUEST['auth_bool_use_token'].'", "'.$_REQUEST['auth_bool_use_HTTPS_cookies'].'", "'.$_REQUEST['auth_bool_check_IP_address'].'", "'.$_REQUEST['auth_bool_check_user_agent'].'");
';
		#echo $strQuery."<br />\n";
		$refDBC->query($strQuery);
/*
		#echo "REQUEST escaped :<pre>"; print_r($_REQUEST); echo "</pre>";
		$arrData = array ('template_default', 'auth_random_seed', 'auth_keep_alive', 'auth_bool_use_token', 'auth_bool_use_HTTPS_cookies', 'auth_bool_check_IP_address', 'auth_bool_check_user_agent');
		foreach ($arrData as $strValue)
		{
			if(array_key_exists($strValue, $_REQUEST))
			{
				$strQuery = '
UPDATE preferences
SET '.mysql_real_escape_string($strValue).'="'.mysql_real_escape_string($_REQUEST[$strValue]).'"
WHERE id = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
				#echo $strQuery."<br />\n";
				#$resResult = $refDBC->query($strQuery);
				#echo $strQuery."<br />\n";
				$refDBC->query($strQuery);
			}
			####################################################################
			# checkboxes must be processed different                           #
			#   They do not appear in the data unless checked                  #
			#   So we set them to zero here                                    #
			####################################################################
			else
			{
				if (substr($strValue, 0, 9) == "auth_bool")
				{
					$strQuery = '
UPDATE preferences
SET '.mysql_real_escape_string($strValue).' = "0"
WHERE id = "'.mysql_real_escape_string($_REQUEST['id']).'"
';
					#echo $strQuery."<br />\n";
					$refDBC->query($strQuery);
				}
			}
		}
*/



		# Process input from arrData                                           #
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();

		#$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		#$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
/*
		$strReturn = $_SERVER['PHP_SELF'];

		$arrContent['content'] = '
	<b>Preferences Submission Complete</b>
	<br /><br />
	<a href="'.htmlspecialchars($strReturn).'">Return to Preferences</a>
	<br /><br />';
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
*/
		########################################################################
		# Everything should be done at this point redirect to next page        #
		########################################################################
		$strFolderURL = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder;
		#$strRedirect = $strFolderURL."dbsetup.php";
		$strRedirect = $strFolderURL."adminsetup.php";
		header('Location: '.$strRedirect);
	}
}
################################################################################
# collect data to populate forms                                               #
#    Display the edit template page form. Grabs data to populate forms and     #
#    displays everything for user.                                             #
################################################################################
// check boxes aren't processing correctly with the arrdata push
// think of a way around this.. should we have a check for checkbox value
// of 0? along with if there is a request or not? yeah we should do that easy in the class.
else
{
/*
	$refDBC->sql_connect();
	$strQuery = '
SELECT *
FROM preferences
';
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		#echo "got preferences";
		$arrFetch = mysql_fetch_assoc($resResult);
#print_r($arrFetch);
		$refTPL = new tpl();
		$refTPL->go($arrFormConf['formFile'], $arrContent);
		#echo $refTPL->get_content();
		$arrFormConf['formFile'] = $refTPL->get_content();
		$arrFormConf['boolFile'] = false;
		$refForm = new form_process($arrFormConf);
		// send database query to form class setArrRequest 
		$refForm->setArrRequest($arrFetch);

	}
	else
	{
		echo "No preferences in database";
		exit;
	}
	mysql_free_result($resResult);
	$refDBC->sql_close();
	$refForm->processInputData();
	echo $refForm->strTemplate;
*/
		$arrContent['errorMsg'] = "";
		#$refTPL = new tpl();
		#$refTPL->go($arrFormConf['formFile'], $arrContent);
		#echo $refTPL->get_content();
		$refTPL = new tpl();
		$refTPL->go($arrFormConf['formFile'], $arrContent);

		#echo $refTPL->get_content();
		$arrFormConf['formFile'] = $refTPL->get_content();
		$arrFormConf['boolFile'] = false;
		$arrFetch['auth_random_seed'] = md5(rand(10,50)); // 32 character
		$arrFetch['auth_bool_use_token'] = true;
		$arrFetch['auth_bool_use_HTTPS_cookies'] = true;
		$arrFetch['auth_bool_check_IP_address'] = true;
		$arrFetch['auth_bool_check_user_agent'] = true;

		$refForm = new form_process($arrFormConf);
		// send database query to form class setArrRequest 
		$refForm->setArrRequest($arrFetch);

	$refForm->processInputData();

	echo $refForm->strTemplate;
}

#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
