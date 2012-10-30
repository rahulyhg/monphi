<?php
################################################################################
# File Name : auth.php                                                         #
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
#echo md5('password'); // if you locked yourself enter "5f4dcc3b5aa765d61d8327deb882cf99" into the db password for the password of "password"
################################################################################
# Grab Admin Menu                                                              #
################################################################################
$strAdmin = file_get_contents('admin_menu.tpl.html');
################################################################################
# Session information                                                          #
################################################################################
if (!ini_get("cookie_httponly"))
	ini_set("session.cookie_httponly", true);
session_start();
#echo '<br /><br /><script>document.write(document.cookie)</script>';
################################################################################
# Variables                                                                    #
################################################################################
#echo '<br /><br />'.$_COOKIE['monphi_auth_token'].'<br />'.$_COOKIE['monphi_auth_token'];
$strPath = "../";
################################################################################
# Includes                                                                     #
################################################################################
// form
if (file_exists($strPath."inc/form_process.class.inc.php")) { include_once ($strPath."inc/form_process.class.inc.php"); } else { echo 'failed to open form_process.class.inc.php'; exit;}
if (file_exists($strPath."inc/arrstripslashes.inc.php")) { include_once ($strPath."inc/arrstripslashes.inc.php"); } else { echo 'failed to open arrstripslashes.inc.php'; exit;}
// database
if (file_exists($strPath."conf/dbc.conf.php")) { include_once ($strPath."conf/dbc.conf.php"); } else { echo 'failed to open dbc.conf.php'; exit;}
if (file_exists($strPath."inc/dbc.class.inc.php")) { include_once ($strPath."inc/dbc.class.inc.php"); } else { echo 'failed to open dbc.class.inc.php'; exit;}
// auth variables
#if (file_exists($strPath."conf/auth.conf.php")) { include_once ($strPath."conf/auth.conf.php"); } else { echo 'failed to open auth.conf.php'; exit;}
#if (file_exists($strPath."conf/monphi.conf.php")) { include_once ($strPath."conf/monphi.conf.php"); } else { echo 'failed to open monphi.conf.php'; exit;}
################################################################################
# Variables                                                                    #
################################################################################
// collect data for valid referers
$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
$intLength = ++$intEnd;
$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
if (!empty($_SERVER['HTTPS']))
	$strServer = "https://";
else
	$strServer = "http://";
// form configuration array
//----------------------------------------------------------------------------//
// arrFormConf - Form Configurations                                          //
//----------------------------------------------------------------------------//
$arrFormConf = array(
	'formFile' => "auth.tpl.html",
	'validReferer' => array( // valid referers need to be added for every page that uses auth
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'index.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content_add.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content_delete.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'content_edit.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'modules.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'modules_load.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'module_add.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'module_edit.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'module_delete.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'user.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'user_add.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'preferences.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'',
	),
	'tokenTimer' => '60',
	'fieldText' => array(
		'user' => 'User Name :',
		'pass' => 'Password :',
	),
	'fieldError' => array(
		'user' => 'User Name',
		'pass' => 'Password',
	),
	'validation' => array(
		'user' => array(
			'required',
		),
		'pass' => array(
			'required',
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
		'token' => '<b>Invalid security token</b> Commonly caused by reloading the page, try resubmitting the form with the form buttons',
		'tokentime' => 'Security token ran out of time. Resubmit the form to solve this problem.',
		'useragent' => '<b>Possible session hijacking</b>, form submission has been halted.'
	)
);
//----------------------------------------------------------------------------//
// End arrFormConf - Form Configurations                                      //
//----------------------------------------------------------------------------//
################################################################################
# Reference Variables                                                          #
################################################################################
$refForm = new form_process($arrFormConf);
################################################################################
#                                                                              #
################################################################################
#echo "<pre>"; print_r($_SESSION); echo "</pre><br />\n";
#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
#echo "POST : <pre>";print_r($_POST);echo "</pre><br />\n";
#echo "GET : <pre>";print_r($_GET);echo "</pre><br />\n";
// this is just a prelim check to see if it should display the auth form or not.
if (array_key_exists('auth', $_POST))
	$boolDataSent = true;
else
	$boolDataSent = false;
################################################################################
# Core Processessing                                                           #
################################################################################
if ($boolDataSent)
{
	if (get_magic_quotes_gpc()) // incase magic quotes adds slashes we strip them
		$_POST = arrstripslashes($_POST);
	// run form error checking
	$refForm->checkValidation();
	$refForm->checkReferer();
	$refForm->checkToken();
	$refForm->checkUserAgent($refForm->strRandomSeed);
	//------------------------------------------------------------------------//
	// Data Valid Enter your Processing Code HERE                             //
	//   Use a simple if statement, if failure add                            //
	//   1. $refForm->boolError = true;                                       //
	//   2. $refForm->strErrorMsg .= "<custom error message><br />\n";        //
	//   if(<check for error>)                                                //
	//   {                                                                    //
	//       $refForm->boolError = true;                                      //
	//       $refForm->strErrorMsg .= "<custom error message><br />\n";       //
	//   }                                                                    //
	//------------------------------------------------------------------------//
	// check database for auth values
	$refDBC = new dbc($arrDBCAdmin);
	$refDBC->sql_connect();
	$strQuery = '
	SELECT
		uid, user, password
	FROM
		users
	WHERE
		active = "1"
		&&
		user = "'.mysql_real_escape_string($_POST['user']).'"
	';
	$resResult = $refDBC->query($strQuery);
	$intNumRows = mysql_num_rows($resResult);
	if ($intNumRows != 0)
	{
		$arrFetch = mysql_fetch_assoc($resResult);
			$intUID = $arrFetch['uid'];
		if (mysql_real_escape_string(md5($_POST['pass'])) != $arrFetch['password'])
		{
			$boolAuth = false;
		}
		else
		{
			$boolAuth = true;
		}
	}
	else
		$boolAuth = false;
	if (!$boolAuth) // if errors set error message
	{
		$refForm->boolError = true;
		$refForm->strErrorMsg .= "Invalid <b>Username</b> or <b>Password</b><br />\n";
		$_SESSION['auth'] = false;
	}
	//------------------------------------------------------------------------//
	// End Data Valid Enter your Processing Code HERE                         //
	//------------------------------------------------------------------------//
	if ($refForm->returnErrors()) // if error return form
	{
		$refForm->processInputData();
		echo $refForm->strTemplate;
		exit;
	}
	else // no errors generate session data
	{
		//--------------------------------------------------------------------//
		// Final Code Processing Here                                         //
		//--------------------------------------------------------------------//
		if ($boolAuth)
		{
			// get variables from database here
			$refDBC = new dbc($arrDBCAdmin);
			$refDBC->sql_connect();
//, auth_bool_use_token, auth_bool_use_HTTPS_cookies, auth_bool_check_IP_address, auth_bool_check_user_agent

			$strQuery = '
SELECT
	auth_random_seed, auth_keep_alive, auth_bool_use_HTTPS_cookies
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
				#$boolUseToken = $arrFetch['auth_bool_use_token'];
				$boolUseHTTPSCookies = $arrFetch['auth_bool_use_HTTPS_cookies'];
				#$boolCheckIPAddress = $arrFetch['auth_bool_check_IP_address'];
				#$boolCheckUserAgent = $arrFetch['auth_bool_check_user_agent'];
			}
			else
			{
				echo 'unable to retreive preferences from database';
				exit;
			}
			mysql_free_result($resResult);
			#$refDBC->sql_close();

			$_SESSION['monphi_auth'] = true; // primary auth flag
			#$_SESSION['monphi_auth_level'] = "administrator"; // this isn't checked yet, when rolls are made load roll in here instead of administrator.
			$_SESSION['monphi_auth_user'] = mysql_real_escape_string($_POST['user']); // why is this escaped?
			$_SESSION['monphi_auth_keepalive'] = time(); // timer for user keep alive. If no activity within designated time, auth is cancelled.
			$_SESSION['monphi_auth_ip_addr'] = $_SERVER['REMOTE_ADDR']; // this may cause issues with certain users/isp's
			$_SESSION['monphi_auth_user_agent'] = md5($_SERVER['HTTP_USER_AGENT'].$strRandomSeed); // Do we need the md5 and random seed?
			//$_SESSION['monphi_auth_random_seed'] = $strRandomSeed; // this should be the token, and regen and send through $_GET?
			$strToken = md5(uniqid(rand(), true));
			$_SESSION['monphi_auth_token'] = $strToken;
			if ($boolUseHTTPSCookies) // monphi.conf.php setting
				setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', true, true);
			else
				setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', false, true);



			$strDate = date("Y-m-d H:i:s"); // update datetime in last_login YYYY-MM-DD HH:mm:ss
			$strQuery = '
UPDATE users
SET last_login = "'.$strDate.'"
WHERE uid = "'.mysql_real_escape_string($intUID).'"
';
			$refDBC->query($strQuery);
		}
		//--------------------------------------------------------------------//
		// End Final Code Processing Here                                     //
		//--------------------------------------------------------------------//
	}
	$refDBC->sql_close();
}
else // The auth form wasn't used check session or send user form
{
	//------------------------------------------------------------------------//
	// session check                                                          //
	//------------------------------------------------------------------------//
	if (array_key_exists("monphi_auth", $_SESSION)) //only check for auth, then check for errors in auth.
	{
		// is auth set to true
		if (!$_SESSION['monphi_auth'])
		{
			// don't throw error, user may have clicked logout and our logout just removes the monphi_auth variable not key.
			#$refForm->boolError = true;
			#$refForm->strErrorMsg .= "Unable to Authorize<br />\n";
			echo $refForm->strTemplateOrig;
			exit;
		}
		// get variables from database here
		$refDBC = new dbc($arrDBCAdmin);
		$refDBC->sql_connect();
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
		$refDBC->sql_close();

		// check that the users IP has not changed
		if ($boolCheckIPAddress) // monphi.conf.php setting
		{
			if ($_SESSION['monphi_auth_ip_addr'] != $_SERVER['REMOTE_ADDR'])
			{
				$refForm->boolError = true;
				$refForm->strErrorMsg .= "Remote IP changed<br />\n";
			}
		}
		// check that the user agent has not changed
		if ($boolCheckUserAgent) // monphi.conf.php setting
		{
			if ($_SESSION['monphi_auth_user_agent'] != md5($_SERVER['HTTP_USER_AGENT'].$strRandomSeed))
			{
				$refForm->boolError = true;
				$refForm->strErrorMsg .= "User Agent changed<br />\n";
			}
		}
		// check for valid token
		if ($boolUseToken) // monphi.conf.php setting
		{
			if ($_COOKIE['monphi_auth_token'] != $_SESSION['monphi_auth_token'])
			{
				$refForm->boolError = true;
				$refForm->strErrorMsg .= "Invalid Token or Token Timeout Reached<br />\n";
			}
		}
		// Incase user doesn't want to use token check session keep alive.
		$intInactiveTime = time() - $_SESSION['monphi_auth_keepalive'];
		if ($intInactiveTime >= $intKeepAlive)
		{
			$refForm->boolError = true;
			$refForm->strErrorMsg .= "Inactivity timer reached, session has been disabled<br />\n";
		}
/*
		// Token will return invalid if timer is out, no need to check twice
		$intCookieTime = time() - $_SESSION['monphi_auth_keepalive'];
		if ($intCookieTime >= $intKeepAlive)
		{
			$refForm->boolError = true;
			$refForm->strErrorMsg .= "Inactivity timer reached, cookie has been disabled<br />\n";
		}
*/
		if ($refForm->returnErrors())
		{
			// session should be dumped and data should be logged, even though we send this data
			// attacker could reattempt with data without token etc.
			// hmm maybe data should just be logged if attacked we don't want user constantly booted.
			// but this will tell the user if they have been session jacked.
			// process form submission data if errors for form reprint
			$refForm->processInputData();
			echo $refForm->strTemplate;
			exit;
		}
		else
		{
			$_SESSION['monphi_auth_keepalive'] = time(); // reset keep alive timer
			$strToken = md5(uniqid(rand(), true)); // regen token
			$_SESSION['monphi_auth_token'] = $strToken; // set token in session for verification.
			if ($boolUseHTTPSCookies) // monphi.conf.php setting
				setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', true, true);
			else
				setcookie("monphi_auth_token", $strToken, time()+$intKeepAlive, '/', '', false, true);
		}
	}
	//------------------------------------------------------------------------//
	// if no data submission and no session display auth form.                //
	//------------------------------------------------------------------------//
	else 
	{
		echo $refForm->strTemplateOrig;
		exit;
	}
}
#echo '<br /><br /><pre>'; print_r($_SESSION); print_r($_REQUEST); echo '</pre>';
#echo '<br /><br /><pre>'; print_r($_COOKIE); print_r($_SESSION);echo '</pre>';
#echo $_COOKIE['monphi_auth_token'];
#echo '<br />';
#echo $_SESSION['monphi_auth_token'];
#echo "<pre>"; print_r($refForm->arrFields); echo "</pre>";
