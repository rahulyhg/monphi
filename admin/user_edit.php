<?php
################################################################################
# File Name : user_edit.php                                                    #
# Author(s) :                                                                  #
#   Phil Allen phil@hilands.com                                                #
# Last Edited By :                                                             #
#   phil@hilands.com                                                           #
# Version : 2011060100                                                         #
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
// database stuff
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
#if (file_exists("./sql/sql.inc.php")) { include_once ("./sql/sql.inc.php"); } else { echo 'failed to open sql.inc.php'; exit;}
// templates
if (file_exists($strPath."inc/tpl.class.inc.php")) { include_once ($strPath."inc/tpl.class.inc.php"); } else { echo 'failed to open tpl.class.inc.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
#$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
#$intLength = ++$intEnd;
#$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
#if($_SERVER['HTTPS']) // this gives error.
#if (!empty($_SERVER['HTTPS']))
#	$strServer = "https://";
#else
#	$strServer = "http://";

$arrFormConf = array(
	'formFile' => "user_edit.tpl.html",
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
		'required' => '<b>[field]</b> is a required field and cannot be blank.',
		'match' => 'The password fields <b>[field-a]</b> and <b>[field-b]</b> do not match.',
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
if (array_key_exists('uid', $_REQUEST))
	$boolEdit = true;
else
	$boolEdit = false;
################################################################################
# Final Edit to Database                                                       #
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
		#echo $refForm->strTemplate;
		// added this to take input on auth admin menu
		$refTPL = new tpl();
		$refTPL->go($refForm->strTemplate, $arrContent, false);
		echo $refTPL->get_content();
	}
	else
	{
		#$_SESSION['captcha_valid'] = false;
		#$_SESSION['captcha_reset'] = true;
		#$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
		if (get_magic_quotes_gpc())
			$_REQUEST = arrstripslashes($_REQUEST);
		#$_REQUEST = arrMysqlRealEscapeString($_REQUEST);
		// if password is blank do not update.
		if ($_REQUEST['password'] == '')
		{
			#echo "<br /><br />Not Updating Password";
			$arrData = array('user', 'email');
		}
		else
			$arrData = array('user', 'password', 'email');
		#echo "<br /><br /><pre>"; print_r($arrData); echo "</pre>";
		########################################################################
		# Process input from arrData                                           #
		########################################################################
		foreach ($arrData as $strValue)
		{
			if(array_key_exists($strValue, $_REQUEST))
			{
				if ($strValue == 'password')
				{
#echo 'shouldn\'t be here';
					$strQuery = '
UPDATE users
SET '.$strValue.'="'.md5($_REQUEST[$strValue]).'"
WHERE uid = "'.$_REQUEST['uid'].'"
';
				}
				else
				{
					$strQuery = '
UPDATE users
SET '.$strValue.'="'.mysql_real_escape_string($_REQUEST[$strValue]).'"
WHERE uid = "'.$_REQUEST['uid'].'"
';
				}
				#echo $strQuery."<br />\n";
				#$resResult = $refDBC->query($strQuery);
				$refDBC->query($strQuery);
			}
			####################################################################
			# checkboxes must be processed different                           #
			#   They do not appear in the data unless checked                  #
			#   So we set them to zero here                                    #
			####################################################################
			else
			{
/*
				if (substr($strValue, 0, 4) == "bool")
				{
					$strQuery = '
UPDATE module
SET '.$strValue.' = "0"
WHERE id = "'.$_REQUEST['id'].'"
';
					#echo $strQuery."<br />\n";
					$refDBC->query($strQuery);
				}
*/
			}
		}
		# Process input from arrData                                           #
		#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
		$refDBC->sql_close();
		#echo "data has been submitted!<br />\n";
		#echo '<a href="'.$_SERVER['PHP_SELF'].'">'.$_SERVER['PHP_SELF']."</a><br />\n";
		$intSlashPos = strrpos($_SERVER['PHP_SELF'], "/");
		$strReturn = substr($_SERVER['PHP_SELF'], 0, $intSlashPos+1);
		$arrContent['content'] = '
	<b>User Edit Submission Complete</b>
	<br /><br />
	<a href="'.$strReturn.'user.php">Return to User Management</a>
';
		$strTemplateFile = 'submission_complete.tpl.html';
		$refTPL->go($strTemplateFile, $arrContent);
		echo $refTPL->get_content();
	}
}
################################################################################
# collect data to populate forms                                               #
#    Display Form to edit page                                                 #
################################################################################
// check boxes aren't processing correctly with the arrdata push
// think of a way around this.. should we have a check for checkbox value
// of 0? along with if there is a request or not? yeah we should do that easy in the class.
else if($boolEdit)
{
	############################################################################
	# collect data to populate forms                                           #
	############################################################################
	$refDBC->sql_connect();
	$strQuery = '
SELECT
	uid, user, email
FROM
	users
WHERE
	uid = "'.preg_replace('/[^0-9]/', '', $_REQUEST['uid']).'"
';
	#echo "strQuery : ".$strQuery."<br />\n";
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		#while ($arrFetch = mysql_fetch_assoc($resResult))
		#{
		$arrFetch = mysql_fetch_assoc($resResult);
		#print_r($arrFetch);
		// now stash arrFetch into $refForm->arrRequest;
		#}
		mysql_free_result($resResult);
		// send database query to form class setArrRequest 
		$refForm->setArrRequest($arrFetch);
	}
	else
	{
		echo "Invalid Page to Edit";
		exit;
	}
	// process input should now process the database query from setArrRequest above.
	$refForm->processInputData();
	// display the new template YAY!
	#echo $refForm->strTemplate;
	// added this to take input on auth admin menu
	$refTPL = new tpl();
	$refTPL->go($refForm->strTemplate, $arrContent, false);
	echo $refTPL->get_content();

	############################################################################
	#                                                                          #
	############################################################################
	$refDBC->sql_close();
	#echo str_replace("[modules]", $strModules, $refForm->strTemplate);
}
################################################################################
# collect data to populate forms                                               #
#    Select display list for editing                                           #
################################################################################
else
{
	$arrContent['frmAction'] = $_SERVER['PHP_SELF'];
	$arrContent['content'] = "";
	// list pages here
	$refDBC->sql_connect();
	$strQuery = '
SELECT uid, user
FROM users
ORDER BY user
';

// create a select based on id > show 
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		/* javascript code
		http://www.victorchen.info/submit-form-on-enter-key-solution/
		http://www.allasp.net/enterkey.aspx
		onkeydown="if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {document.getElementById('submit').click();return false;}  else return true;"
		*/
		$arrContent['content'] .= '<select name="uid" id="uid" onkeydown="if ((event.which && event.which == 13) || (event.keyCode && event.keyCode == 13)) {document.getElementById(\'submit\').click();return false;}  else return true;">';
		while ($arrFetch = mysql_fetch_assoc($resResult))
		{
			#$arrFetch = mysql_fetch_assoc($resResult);
			#echo "<pre>"; print_r($arrFetch); echo "</pre><br />";
			#echo '<option value="'.$arrFetch['id'].'">'.$arrFetch['section'].'-'.$arrFetch['page'].'-'.$arrFetch['article'].'.html</option>';
			$arrContent['content'] .= '<option value="'.$arrFetch['uid'].'">'.$arrFetch['user'].'</option>';
		}
		$arrContent['content'] .= '</select>';
		// how do I get the data into setArrRequest. shit this won't work with options.
		#$refFormSelect->setArrRequest($arrFetch);
		#$refFormSelect->processInputData();
		// display the new template YAY!
		#echo $refFormSelect->strTemplate;
	}
	mysql_free_result($resResult);
	// hmm perhaps this should display list of what to edit.
	#echo "ERROR";
	#echo $refForm->strTemplateOrig;
	$strTemplateFile = './user_edit_select.tpl.html';
	$refTPL->go($strTemplateFile, $arrContent);
	echo $refTPL->get_content();
}
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
