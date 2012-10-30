<?php
################################################################################
# File Name : index.php                                                        #
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
$strTemplateFile = 'conf.tpl.html';
$intEnd = strrpos($_SERVER['PHP_SELF'], "/");
$intLength = ++$intEnd;
$strPHP_SELFFolder = substr($_SERVER['PHP_SELF'], 0, $intEnd);
if (!empty($_SERVER['HTTPS']))
	$strServer = "https://";
else
	$strServer = "http://";

$arrFormConf = array(
	'formFile' => "conf.tpl.html",
	'validReferer' => array(
		'[PHP_SELF]',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder.'index.php',
		$strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder,
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
		'0' => "<br /><br />\n".'<div style="background-color:#ffffd5; border:3px #c00 solid; color:#c00; padding:5px;">'."\n\t".'<div style="font-size:1.5em; font-weight:bold; padding-bottom:.5em;"><img src="../img/monphi/logo.png" style="vertical-align:middle;" /> Uh oh, there seems to be a problem!</div>',
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
#echo "REQUEST : <pre>";print_r($_REQUEST);echo "</pre><br />\n";
#echo "POST : <pre>";print_r($_POST);echo "</pre><br />\n";
#echo "GET : <pre>";print_r($_GET);echo "</pre><br />\n";
#if (empty($_REQUEST))
if (array_key_exists('submit', $_REQUEST))
	$boolDataSent = true;
else
	$boolDataSent = false;
/*
if (array_key_exists('id', $_REQUEST))
	$boolEdit = true;
else
	$boolEdit = false;
*/
################################################################################
# If submission modify monphi.conf.php                                         #
################################################################################
if ($boolDataSent)
{
	// run error checking
	$refForm->checkReferer();
	$refForm->checkValidation();
	// add additional error checking here
	// check error "list"
	if ($refForm->returnErrors())
	{
		// process form submission data if errors for form reprint
		$refForm->processInputData();
		echo $refForm->strTemplate;
	}
	else
	{
		//good referer etc.
		########################################################################
		# monphi.conf.php write here                                           #
		########################################################################
		// need to generate random seed and enter data from form.
		// then modify if statement below. Move that statement above and do a boolError check.
		if(!$fileHandle = fopen("monphi.conf.php", "r"))
		{
			echo 'cannot read file "/setup/monphi.conf.php"<br />';
			exit;
		}
		else
		{
			$strTemplate = fread($fileHandle, filesize("monphi.conf.php"));
			fclose($fileHandle);
			if(preg_match("/\[randomseed\]/", $strTemplate))
			{
				$strTemplate = str_replace("[randomseed]", md5(rand(10,50)), $strTemplate);
			}
			#echo '<br /><br />Post :'.$_POST['intKeepAlive'].'<br />';
			echo '<pre>'; print_r($_POST); echo '</pre>';
			if(preg_match("/\[keepalive\]/", $strTemplate))
			{
				#echo 'here';
				$strTemplate = str_replace("[keepalive]", $_POST['intKeepAlive'], $strTemplate);
			}
			if(preg_match("/\[usetoken\]/", $strTemplate))
			{
				if (array_key_exists('boolUseToken', $_POST))
					$strTemplate = str_replace("[usetoken]", 'true', $strTemplate);
				else
					$strTemplate = str_replace("[usetoken]", 'false', $strTemplate);
			}
			if(preg_match("/\[usehttpscookies\]/", $strTemplate))
			{
				if (array_key_exists('boolUseHTTPSCookies', $_POST))
					$strTemplate = str_replace("[usehttpscookies]", 'true', $strTemplate);
				else
					$strTemplate = str_replace("[usehttpscookies]", 'false', $strTemplate);
			}
			if(preg_match("/\[checkipaddress\]/", $strTemplate))
			{
				if (array_key_exists('boolCheckIPAddress', $_POST))
					$strTemplate = str_replace("[checkipaddress]", 'true', $strTemplate);
				else
					$strTemplate = str_replace("[checkipaddress]", 'false', $strTemplate);
			}
			if(preg_match("/\[checkuseragent\]/", $strTemplate))
			{
				if (array_key_exists('boolCheckUserAgent', $_POST))
					$strTemplate = str_replace("[checkuseragent]", 'true', $strTemplate);
				else
					$strTemplate = str_replace("[checkuseragent]", 'false', $strTemplate);
			}
			// write the monphi.conf.php file.
			if(!$fileHandle = fopen("../conf/monphi.conf.php", "w"))
			{
				echo 'cannot write to "/conf/monphi.conf.php"<br />';
				exit;
			}
			else
			{
				// store file in strTemplate
				fwrite($fileHandle, $strTemplate);
				fclose($fileHandle);
			}
		}
		########################################################################
		# Everything should be done at this point redirect to next page        #
		########################################################################
		$strFolderURL = $strServer.$_SERVER['HTTP_HOST'].$strPHP_SELFFolder;
		$strRedirect = $strFolderURL."dbsetup.php";
		header('Location: '.$strRedirect);
		#echo 'done';
	}
}
################################################################################
#                                                                              #
################################################################################
else
{
	echo $refForm->strTemplateOrig;
}
?>